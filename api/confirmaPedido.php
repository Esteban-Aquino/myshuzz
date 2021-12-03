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
        $nro_pedido = NVL(filter_input(INPUT_GET, 'NRO_PEDIDO', FILTER_SANITIZE_NUMBER_INT), '');
        
        
        
        // confirmar
        IF ($res_code === StatusCodes::HTTP_OK) {
            $confirmado = pedidoDB::confirmaPedido($nro_pedido);
            
            // Error al confirmar
            if ($confirmado !== 'OK') {
                $res_code = StatusCodes::HTTP_BAD_REQUEST;
                $acceso = true;
                $errores[$nro_error] = utf8_converter_sting($confirmado);
                $nro_error++;
            }
        }
       
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
    $errores[$nro_error] = formatea_error($mensaje);
    $nro_error++;
}

print formatea_respuesta_pedido($acceso, $datos, $mensajes, $errores, $res_code);
