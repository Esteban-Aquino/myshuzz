<?php

/**
 * Provee las operaciones de bd para consultas
 * Autor: Esteban Aquino
 * Empresa: Optima SA
 * Fecha: 27/07/2020
 */
require '../config/oraconnect.php';
require '../shared/util.php';

class getdataDB {

    function __construct() {
        
    }

    public static function getExistencia($fecha, $sku, $descuento) {
        try {
            $cant = 10;
            //print($fecha);
            //print_r($sku);
            //print_r($descuento);
            $consulta = "SELECT COD_MYSHUZZ,
                        COD_COLOR_MYSHUZZ,
                        TAMANIO,
                        CANTIDAD,
                        PRECIO_NORMAL,
                        PRECIO_CON_DESCUENTO,
                        DESCUENTO_FECHA_INICIO,
                        DESCUENTO_FECHA_FIN,
                        NOMBRE,
                        EC_DESCRIPCION_CORTA,
                        DESCRIPCION,
                        DEPARTAMENTO,
                        CATEGORIA,
                        SUBCATEGORIA,
                        ESTILO,
                        MARCA,
                        GENERO,
                        TO_CHAR(FEC_ULT_MOVIMIENTO, 'dd-mm-yyyy hh24:mi:ss') FEC_ULT_MOVIMIENTO,
                        COD_OFERTA,
                        PROV_EXT
                   FROM ECV_ARTICULOS_EXIS_PRE E
                  WHERE (E.COD_MYSHUZZ = :P_COD_MYSHUZZ OR :P_COD_MYSHUZZ1 IS NULL)
                    AND (E.FEC_ULT_MOVIMIENTO >= TO_DATE(:PFECHA, 'dd-mm-yyyy hh24:mi:ss') OR
                        :PFECHA1 IS NULL)
                    AND ((E.PRECIO_CON_DESCUENTO IS NOT NULL AND NVL(:DESCUENTO,'N') = 'S')
                         OR NVL(:DESCUENTO1,'N') = 'N')";

            //print_r($fec_des);
            // Preparar sentencia
            //print_r($consulta);
            $comando = oraconnect::getInstance()->getDb()->prepare($consulta);

            // Ejecutar sentencia preparada
            $comando->bindParam(':P_COD_MYSHUZZ', $sku, PDO::PARAM_STR);
            $comando->bindParam(':P_COD_MYSHUZZ1', $sku, PDO::PARAM_STR);
            $comando->bindParam(':PFECHA', $fecha, PDO::PARAM_STR);
            $comando->bindParam(':PFECHA1', $fecha, PDO::PARAM_STR);
            $comando->bindParam(':DESCUENTO', $descuento, PDO::PARAM_STR);
            $comando->bindParam(':DESCUENTO1', $descuento, PDO::PARAM_STR);
            $comando->execute();
            /*$comando->execute([':P_COD_MYSHUZZ' => $sku,
                               ':P_COD_MYSHUZZ1' => $sku,
                               ':PFECHA' => $fecha,
                               ':PFECHA1' => $fecha]);*/

            $result = $comando->fetchAll(PDO::FETCH_ASSOC);
            //PRINT_R($result);
            return utf8_converter($result);
        } catch (PDOException $e) {
            //print_r($e->getMessage());
            return utf8_converter_sting($e->getMessage());
        }
    }
    
    
    public static function getClientes($documento) {
        try {
            $cant = 10;
            //print($fecha);
            //print_r($busqueda);
            $consulta = "SELECT C.COD_EMPRESA, 
                                C.COD_CLIENTE, 
                                C.NOMBRE, 
                                C.NOMBRE_FANTASIA, 
                                C.DOCUMENTO, 
                                C.RUC, 
                                C.CI, 
                                C.COD_DIRECCION, 
                                C.DESC_DIRECCION, 
                                C.DESC_TELEFONO
                         FROM ECV_CLIENTES C
                         WHERE (C.RUC = :P_DOC OR :P_DOC1 IS NULL)
                         OR (C.CI = :P_DOC2 OR :P_DOC3 IS NULL)";

            //print_r($fec_des);
            // Preparar sentencia
            //print_r($consulta);
            $comando = oraconnect::getInstance()->getDb()->prepare($consulta);

            // Ejecutar sentencia preparada
            $comando->execute([':P_DOC' => $documento,
                               ':P_DOC1' => $documento,
                               ':P_DOC2' => $documento,
                               ':P_DOC3' => $documento]);

            $result = $comando->fetchAll(PDO::FETCH_ASSOC);
            //PRINT_R($result);
            return utf8_converter($result);
        } catch (PDOException $e) {
            //print_r($e->getMessage());
            return utf8_converter_sting($e->getMessage());
        }
    }
    
    public static function getPedidosEstados($NRO_PEDIDO) {
        try {
            $cant = 10;
            //print($fecha);
            //print_r($busqueda);
            $consulta = "SELECT NRO_PEDIDO, 
                                NRO_PEDIDO_EC, 
                                ESTADO
                         FROM ECV_PEDIDOS_ESTADO
                         WHERE (NRO_PEDIDO = :P_NRO_PEDIDO OR TRIM(:P_NRO_PEDIDO1) IS NULL)
                         ORDER BY 1 desc, 2";

            //print_r($fec_des);
            // Preparar sentencia
            //print_r($consulta);
            $comando = oraconnect::getInstance()->getDb()->prepare($consulta);

            // Ejecutar sentencia preparada
            $comando->execute([':P_NRO_PEDIDO' => $NRO_PEDIDO,
                               ':P_NRO_PEDIDO1' => $NRO_PEDIDO]);

            $result = $comando->fetchAll(PDO::FETCH_ASSOC);
            //PRINT_R($result);
            return utf8_converter($result);
        } catch (PDOException $e) {
            //print_r($e->getMessage());
            return utf8_converter_sting($e->getMessage());
        }
    }

}
