<?php

/**
 * Provee las operaciones de bd para consultas
 * Autor: Esteban Aquino
 * Empresa: Optima SA
 * Fecha: 27/07/2020
 */
require '../config/oraconnect.php';
require '../shared/util.php';

class pruebaDB {

    function __construct() {
        
    }

    public static function insertaPrueba($nombre) {
        try {
            $sql = "INSERT INTO PRUEBA VALUES (:NOMBRE)";
            $comando = oraconnect::getInstance()->getDb()->prepare($sql);
            $comando->bindParam(':NOMBRE', $nombre, PDO::PARAM_STR);
            $comando->execute();
            $respuesta = 'insertado';
            return $respuesta;
        } catch (PDOException $e) {
            print print ($e->getMessage());
        }
    }

}
