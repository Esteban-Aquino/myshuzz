<?php

/**
 * Funciones compartidas
 * Autor: Esteban Aquino
 * Fecha: 27/07/2020
 */



/**
 * Formatea error de base de datos Oracle
 * @author Esteban Aquino <esteban.aquino@leaderit.com.py>
 * @param string $error Error rertornado por la BD
 * @return string Error formateado
 */
function formatea_error($error) {
    return str_replace(')', '', str_replace('(', '', str_replace(']', '', str_replace('[', '', str_replace('"', '', $error)
                            )
                    )
            )
    );
}
/**
 * Devuelve respuesta formateada JSON incluidas las cabeceras con acces control
 * @author Esteban Aquino <esteban.aquino@leaderit.com.py>
 * @param boolean $acceso Tipo de acceso otorgado
 * @param Object $datos Datos retornados
 * @param string $mensaje Algun retorno de mensaje o error
 * @param number $res_code Codigo de respuesta http- https://es.wikipedia.org/wiki/Anexo:C%C3%B3digos_de_estado_HTTP
 * @return JSON
 */
function formatea_respuesta($acceso, $datos, $mensaje, $res_code) {
    $respuesta["acceso"] = $acceso;
    $respuesta["datos"] = $datos;
    $respuesta["mensaje"] = $mensaje;
    http_response_code($res_code);
    agrega_cabecera_json();
    return json_encode($respuesta);
}


/**
 * Devuelve respuesta formateada JSON incluidas las cabeceras con acces control
 * es diferente para el pedido
 * @author Esteban Aquino <esteban.aquino@leaderit.com.py>
 * @param boolean $acceso Tipo de acceso otorgado
 * @param Object $datos Datos retornados
 * @param string $mensaje Algun retorno de mensaje o error
 * @param number $res_code Codigo de respuesta http- https://es.wikipedia.org/wiki/Anexo:C%C3%B3digos_de_estado_HTTP
 * @return JSON
 */
function formatea_respuesta_pedido($acceso, $datos, $mensajes, $errores, $res_code) {
    $respuesta["acceso"] = $acceso;
    $respuesta["datos"] = $datos;
    $respuesta["mensajes"] = $mensajes;
    $respuesta["errores"] = $errores;
    http_response_code($res_code);
    agrega_cabecera_json();
    return json_encode($respuesta);
}

function agrega_cabecera_json() {
    header("Content-type: application/json");
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Headers: *');
}

