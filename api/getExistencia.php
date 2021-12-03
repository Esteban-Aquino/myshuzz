<?php

/**
 * Get de existencia
 * Autor: Esteban Aquino
 * Empresa: Optima SA
 * Fecha: 27/07/2020
 */
require_once '../DAO/getdataDB.php';
require_once '../shared/sharedFunctions.php';
require_once '../shared/http_response_code.php';

# Obtener headers
$head = getallheaders();

$ok = false;

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
    $acceso = true;
    $datos = "";
    $mensaje = "";
    $res_code = 200;

    $fecha = NVL(filter_input(INPUT_GET, 'FECHA', FILTER_SANITIZE_STRING), '');
    $sku = NVL(filter_input(INPUT_GET, 'SKU', FILTER_SANITIZE_STRING), '');
    $descuento = "";
    IF (strlen ($sku) === 1) {
        $descuento = $sku;
        $sku = "";
    }

    // validar fecha
    $regexFecha = '/^([0-2][0-9]|3[0-1])(\/|-)(0[1-9]|1[0-2])\2(\d{4})(\s)([0-1][0-9]|2[0-3])(:)([0-5][0-9])(:)([0-5][0-9])$/';

    if (!preg_match($regexFecha, $fecha, $matchFecha)) {
        $ok = false;
        $mensaje = "Formato de fecha no valido, debe ser: dd-mm-yyyy hh:mi:ss";
        $res_code = StatusCodes::HTTP_BAD_REQUEST;
    } else {
        // Aumenta el tiempo de ejecucion a 600 segundos por que la vista tarda mucho. Esteban Aquino 29-06-2017
        ini_set('max_execution_time', 600);
        
        $data = getdataDB::getExistencia($fecha, $sku, $descuento);

        if (is_array($data)) {
            $longitud = count($data);
            if ($longitud > 0) {
                $res_code = StatusCodes::HTTP_OK;
                $datos = $data;
            } else {
                $res_code = StatusCodes::HTTP_NOT_FOUND;
                $mensaje = "Sin datos";
            }
        } else {
            // error
            $res_code = StatusCodes::HTTP_INTERNAL_SERVER_ERROR;
            $mensaje = formatea_error($data);
        }
    }
} else {
    $res_code = StatusCodes::HTTP_UNAUTHORIZED;
    $acceso = false;
    $mensaje = 'Token no valido';
    $datos = '';
}

print formatea_respuesta($acceso, $datos, $mensaje, $res_code);
