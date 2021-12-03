<?php

/**
 * Provee las operaciones de bd para consultas
 * Autor: Esteban Aquino
 * Empresa: Optima SA
 * Fecha: 27/07/2020
 */
require '../config/oraconnect.php';
require '../shared/util.php';

class pedidoDB {

    function __construct() {
        
    }


    public static function insertaCabeceraPedido($pedidoCab) {
        try {
            $respuesta['RESULTADO'] = '';
            $respuesta['MENSAJE'] = '';
            $result = [];
            $mensaje = "";
            $sql = "begin :res := FEC_INSERTA_CAB_PED_PROV(:PMENSAJE,
                                                           :PFEC_COMPROBANTE,
                                                           :PNOM_CLIENTE,
                                                           :PDIR_CLIENTE,
                                                           :PDIR_CLIENTE_ENT,
                                                           :PTEL_CLIENTE,
                                                           :PRUC,
                                                           :PNRO_PEDIDO_EC,
                                                           :PCOD_CUPON_EC,
                                                           :PPORC_CUPON_EC,
                                                           :PCOD_CLIENTE,
                                                           :PCOD_DIRECCION,
                                                           :PCOMENTARIO,
                                                           :POBS_PEDIDO,
                                                           :PFEC_ENTREGA,
                                                           :PCOD_PROVINCIA_ENT,
                                                           :PCOD_CIUDAD_ENT,
                                                           :PFORMA_PAGO_EC) ; end;";
            $comando = oraconnect::getInstance()->getDb()->prepare($sql);
            $comando->bindParam(':res', $result, PDO::PARAM_STR | PDO::PARAM_INPUT_OUTPUT, 400);
            $comando->bindParam(':PMENSAJE', $mensaje, PDO::PARAM_STR | PDO::PARAM_INPUT_OUTPUT, 400);
            $comando->bindParam(':PFEC_COMPROBANTE', $pedidoCab['FEC_COMPROBANTE'], PDO::PARAM_STR);
            $comando->bindParam(':PNOM_CLIENTE', utf8_decode($pedidoCab['NOM_CLIENTE']), PDO::PARAM_STR);
            $comando->bindParam(':PDIR_CLIENTE', utf8_decode($pedidoCab['DIR_CLIENTE']), PDO::PARAM_STR);
            $comando->bindParam(':PDIR_CLIENTE_ENT', utf8_decode($pedidoCab['DIR_CLIENTE_ENT']), PDO::PARAM_STR);
            $comando->bindParam(':PTEL_CLIENTE', utf8_decode($pedidoCab['TEL_CLIENTE']), PDO::PARAM_STR);
            $comando->bindParam(':PRUC', $pedidoCab['RUC'], PDO::PARAM_STR);
            $comando->bindParam(':PNRO_PEDIDO_EC', $pedidoCab['NRO_PEDIDO_EC'], PDO::PARAM_STR);
            $comando->bindParam(':PCOD_CUPON_EC', $pedidoCab['COD_CUPON_EC'], PDO::PARAM_STR);
            $comando->bindParam(':PPORC_CUPON_EC', $pedidoCab['PORC_CUPON_EC'], PDO::PARAM_INT);
            $comando->bindParam(':PCOD_CLIENTE', $pedidoCab['COD_CLIENTE'], PDO::PARAM_STR);
            $comando->bindParam(':PCOD_DIRECCION', $pedidoCab['COD_DIRECCION'], PDO::PARAM_STR);
            $comando->bindParam(':PCOMENTARIO', utf8_decode($pedidoCab['COMENTARIO']), PDO::PARAM_STR);
            $comando->bindParam(':POBS_PEDIDO', utf8_decode($pedidoCab['OBS_PEDIDO']), PDO::PARAM_STR);
            $comando->bindParam(':PFEC_ENTREGA', utf8_decode($pedidoCab['FEC_ENTREGA']), PDO::PARAM_STR);
            $comando->bindParam(':PCOD_PROVINCIA_ENT', utf8_decode($pedidoCab['COD_PROVINCIA_ENT']), PDO::PARAM_STR);
            $comando->bindParam(':PCOD_CIUDAD_ENT', utf8_decode($pedidoCab['COD_CIUDAD_ENT']), PDO::PARAM_STR);
            $comando->bindParam(':PFORMA_PAGO_EC', $pedidoCab['FORMA_PAGO_EC'], PDO::PARAM_STR);
            //$comando->debugDumpParams();
            $comando->execute();
            //var_dump($result);
            //$result = $comando->fetchAll(PDO::FETCH_ASSOC);
            $respuesta['RESULTADO'] = $result;
            $respuesta['MENSAJE'] = $mensaje;
            //print_r($respuesta);
            return utf8_converter($respuesta);
        } catch (PDOException $e) {
            //print_r($e->getMessage());
            $respuesta['MENSAJE'] = $e->getMessage();
            return utf8_converter($respuesta);
        }
    }

    public static function insertaDetallePedido($pedidoDet) {
        try {
            $respuesta['RESULTADO'] = '';
            $respuesta['MENSAJE'] = '';

            $result = [];
            $mensaje = "";
            
            $sql = "begin :res := FEC_INSERTA_DET_PED_PROV(:PMENSAJE,
                                                           :PNRO_PEDIDO,
                                                           :PCOD_MYSHUZZ,
                                                           :PCANTIDAD,
                                                           :PPRECIO_LISTA,
                                                           :PCOD_OFERTA,
                                                           :PAPLICA_CUPON,
                                                           :PAPLICA_CANJE,
                                                           :PCOD_MYSHUZZ_REF) ; end;";
            //print_r($sql);
            // Preparar sentencia
            //print_r($pedidoDet);
            //print $persona['NOMBRE'];
            $comando = oraconnect::getInstance()->getDb()->prepare($sql);
            $comando->bindParam(':res', $result, PDO::PARAM_STR | PDO::PARAM_INPUT_OUTPUT, 400);
            $comando->bindParam(':PMENSAJE', $mensaje, PDO::PARAM_STR | PDO::PARAM_INPUT_OUTPUT, 400);
            $comando->bindParam(':PNRO_PEDIDO', $pedidoDet['NRO_PEDIDO'], PDO::PARAM_STR);
            $comando->bindParam(':PCOD_MYSHUZZ', $pedidoDet['COD_MYSHUZZ'], PDO::PARAM_STR);
            $comando->bindParam(':PCANTIDAD', $pedidoDet['CANTIDAD'], PDO::PARAM_STR);
            $comando->bindParam(':PPRECIO_LISTA', $pedidoDet['PRECIO_LISTA'], PDO::PARAM_STR);
            $comando->bindParam(':PCOD_OFERTA', $pedidoDet['COD_OFERTA'], PDO::PARAM_STR);
            $comando->bindParam(':PAPLICA_CUPON', $pedidoDet['APLICA_CUPON'], PDO::PARAM_STR);
            $comando->bindParam(':PAPLICA_CANJE', $pedidoDet['APLICA_CANJE'], PDO::PARAM_STR);
            $comando->bindParam(':PCOD_MYSHUZZ_REF', $pedidoDet['COD_MYSHUZZ_REF'], PDO::PARAM_STR);

            $comando->execute();
            
            $respuesta['RESULTADO'] = $result;
            $respuesta['MENSAJE'] = $mensaje;
            
            
            return utf8_converter($respuesta);
        } catch (PDOException $e) {
            //print_r($e->getMessage());
            return utf8_converter_sting($e->getMessage());
        }
    }

    public static function completaCarga($nro_pedido) {
        try {

            $sql = "begin :res := FEC_COMPLETA_CARGA(:PNRO_PEDIDO); end;";

            $result = [];

            $comando = oraconnect::getInstance()->getDb()->prepare($sql);
            $comando->bindParam(':res', $result, PDO::PARAM_STR | PDO::PARAM_INPUT_OUTPUT, 400);
            $comando->bindParam(':PNRO_PEDIDO', $nro_pedido, PDO::PARAM_STR);

            $comando->execute();

            return $result;
        } catch (PDOException $e) {
            return $e->getMessage();
        }
    }

    public static function confirmaPedido($nro_pedido) {
        try {

            $sql = "begin :res := FEC_CONFIRMA_PEDIDO(:PNRO_PEDIDO);  end;";
            $result = [];

            $comando = oraconnect::getInstance()->getDb()->prepare($sql);
            $comando->bindParam(':res', $result, PDO::PARAM_STR | PDO::PARAM_INPUT_OUTPUT);
            $comando->bindParam(':PNRO_PEDIDO', $nro_pedido, PDO::PARAM_STR);
            $comando->execute();

            return $result;
        } catch (PDOException $e) {
            return $e->getMessage();
        }
    }
    
      public static function anulaPedido($nro_pedido) {
        try {

            $sql = "begin :res := FEC_ANULA_PEDIDO(:PNRO_PEDIDO);  end;";
            $result = [];

            $comando = oraconnect::getInstance()->getDb()->prepare($sql);
            $comando->bindParam(':res', $result, PDO::PARAM_STR | PDO::PARAM_INPUT_OUTPUT, 400);
            $comando->bindParam(':PNRO_PEDIDO', $nro_pedido, PDO::PARAM_STR);
            $comando->execute();

            return $result;
        } catch (PDOException $e) {
            return $e->getMessage();
        }
    }

}
