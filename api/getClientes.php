<?php

/**
 * Get de clientes
 * Autor: Esteban Aquino
 * Empresa: Optima SA
 * Fecha: 02/09/2020
 */
require_once '../DAO/getdataDB.php';
require_once '../shared/sharedFunctions.php';
require_once '../shared/http_response_code.php';
require_once '../config/parametros.php';



// Verificar autenticidad del token
# Obtener headers
$head = getallheaders();
$ok = false;

# Obtener parametros
$dir_foto = DIR_FOTOS;
$dir_foto_web = DIR_FOTOS_WEB;

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

    $documento = NVL(filter_input(INPUT_GET, 'DOCUMENTO', FILTER_SANITIZE_STRING), '');
    $data = getdataDB::getClientes($documento);

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
} else {
    $res_code = 401; // No autorizado
    $acceso = false;
    $mensaje = 'Token no valido';
    $datos = '';
}
print formatea_respuesta($acceso, $datos, $mensaje, $res_code);
