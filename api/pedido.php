<?php

/**
 * Get de clientes
 * Autor: Esteban Aquino
 * Empresa: Optima SA
 * Fecha: 03/09/2020
 */
require_once '../DAO/pedidoDB.php';
require_once '../shared/sharedFunctions.php';
require_once '../shared/http_response_code.php';

# Objener metodo HTTP
$metodo = filter_input(INPUT_SERVER, 'REQUEST_METHOD', FILTER_SANITIZE_STRING);
// SI LA PETICION ES GET
IF ($metodo == 'POST') {
    # Obtener headers
    $head = getallheaders();
    $ok = false;

    $res_code = StatusCodes::HTTP_OK;
    $acceso = true;
    $mensaje = '';
    $nro_mens = 0;
    $nro_error = 0;

    // Verificar autenticidad del token
    if (VERIFICA_TOKEN) {
        $token = $head['token'];
        if ($token !== 'null' && $token !== null) {
            $ok = validarToken($token)['valid'];
        }
    } else {
        $ok = true;
    }

    if ($ok) {
        $json_str = file_get_contents('php://input');
        $json_obj = json_decode(utf8_converter_sting($json_str), true);
        $cabecera = $json_obj["CABECERA"];
        // Validar fecha
        $detalle = $json_obj["DETALLE"];

        // GUARDAR CABECERA
        $respCab = pedidoDB::insertaCabeceraPedido($cabecera);
        
        $nro_pedido = $respCab['RESULTADO'];
        $mensajeCab = $respCab['MENSAJE'];
        if (NVL($mensajeCab,'N') != 'N') {
            $mensajes[$nro_mens] = $mensajeCab;
            $nro_mens ++;
        }
        
        // $dsc = formatea_error($nro_pedido);
       
        // Sino hubo error
        if (!is_numeric($nro_pedido)) {
            
            // ERROR EN LA CABECERA
            $res_code = StatusCodes::HTTP_BAD_REQUEST;
            $acceso = true;
            $datos = '';
            $errores[$nro_error] = formatea_error($nro_pedido);
            $nro_error++;
        } ELSE {
            
            // Si la cabecera inserto bien $nro_pedido va a tener el nro de pedido insertado 
            $longitud = count($detalle);
            // PRINT_R($longitud);
            //Recorro todos los elementos
            for ($i = 0; $i < $longitud; $i++) {

                //saco el valor de cada elemento
                $detalle[$i]['NRO_PEDIDO'] = $nro_pedido;
                $datosDet = pedidoDB::insertaDetallePedido($detalle[$i]);

                $detOk = $datosDet['RESULTADO'];
                $mensajeDet = $datosDet['MENSAJE'];
                if (NVL($mensajeDet,'N') != 'N') {
                    $mensajes[$nro_mens] = $mensajeDet;
                    $nro_mens ++;
                }
                if ($detOk !== 'OK') {
                    break;
                }
                //print_r($detalle[$i]);
            }
            // Error al insertar el detalle
            if ($detOk !== 'OK') {
                $res_code = StatusCodes::HTTP_BAD_REQUEST;
                $acceso = true;
                $errores[$nro_error] = formatea_error($detOk);
                $nro_error++;
            }
        }
        
        // completar carga
        IF ($res_code === StatusCodes::HTTP_OK) {
            $confirmado = pedidoDB::completaCarga($nro_pedido);
            
            // Error al completar la carga
            if ($confirmado !== 'OK') {
                $res_code = StatusCodes::HTTP_BAD_REQUEST;
                $acceso = true;
                $errores[$nro_error] = formatea_error($confirmado);
                $nro_error++;
            }
        }
        
        
        // confirmar
        /*IF ($res_code === StatusCodes::HTTP_OK) {
            $confirmado = pedidoDB::confirmaPedido($nro_pedido);
            
            // Error al confirmar
            if ($confirmado !== 'OK') {
                $res_code = StatusCodes::HTTP_BAD_REQUEST;
                $acceso = true;
                $errores[$nro_error] = formatea_error($confirmado);
                $nro_error++;
            }
        }*/
       
        // Todo OK
        IF ($res_code === StatusCodes::HTTP_OK) {
            $res_code = StatusCodes::HTTP_OK;
            $acceso = true;
            $datos = utf8_converter_sting($nro_pedido);
        }
        
    } else {

        $acceso = false;
        $mensaje = 'Token no valido';
        $errores[$nro_error] = formatea_error($confirmado);
        $nro_error++;
    }
/*} ELSEIF ($metodo == 'GET') {*/
    // CONSULTA PEDIDO
} ELSE {
    $res_code = StatusCodes::HTTP_NOT_FOUND; // NOT FOUND
    $acceso = false;
    $mensaje = 'Metodo http no encontrado';
    $errores[$nro_error] = utf8_converter_sting(formatea_error($mensaje));
    $nro_error++;
}

print formatea_respuesta_pedido($acceso, $datos, $mensajes, $errores, $res_code);
