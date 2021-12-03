<?php
 require_once '../DAO/pruebaDB.php';
 require_once '../shared/sharedFunctions.php';
 require_once '../shared/http_response_code.php';
 
 $json_str = file_get_contents('php://input');
 $json_obj = json_decode(utf8_converter_sting($json_str), true);
 $nombre = $json_obj["NOMBRE"];
 $resp = pruebaDB::insertaPrueba(utf8_decode($nombre));
 
 print_r ($resp);
 