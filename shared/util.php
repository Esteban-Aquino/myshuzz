<?php
/**
 * Herramientas Utiles
 * Autor: Esteban Aquino
 * Fecha: 27/07/2020
 */

function utf8_converter($array)
    {
        array_walk_recursive($array, function(&$item, $key){
            if(!mb_detect_encoding($item, 'utf-8', true)){
                    $item = utf8_encode($item);
            }
        });

        return $array;
    }
function utf8_decoder($array)
    {
        array_walk_recursive($array, function(&$item, $key){
            if(!mb_detect_encoding($item, 'utf-8', true)){
                    $item = utf8_decode($item);
            }
        });

        return $array;
    }
    
    
function utf8_converter_sting($string)
{
    if(!mb_detect_encoding($string, 'utf-8', true)){
            $string = utf8_encode($string);
    }
    return $string;
}

function nvl($var, $default = "")
{   
    if (!isset($var)) {
        $valor = $default;
    } else if ($var === "") {
        $valor = $default;
    } else if ($var === null) {
        $valor = $default;
    } else {
        $valor = $var;
    }
    return $valor;
}
