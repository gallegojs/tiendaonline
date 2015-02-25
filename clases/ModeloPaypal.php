<?php

/**
 * Description of ModeloPaypal
 *
 * @author Javier Gallego
 */
class ModeloPaypal {
    private $bd, $tabla="paypal";
    
    function __construct($bd) {
        $this->bd = $bd;
    }

    function add(Paypal $paypal){
        $sql = "INSERT INTO $this->tabla VALUES (:idpaypal, :idpropio, :estado, :importe, :moneda, :emailvendedor, :emailcomprador)";
        $param['idpaypal'] = $paypal->getIdpaypal();
        $param['idpropio'] = $paypal->getIdpropio();
        $param['estado'] = $paypal->getEstado();
        $param['importe'] = $paypal->getImporte();
        $param['moneda'] = $paypal->getMoneda();
        $param['emailvendedor'] = $paypal->getEmailvendedor();
        $param['emailcomprador'] = $paypal->getEmailcomprador();
        $r=$this->bd->setConsulta($sql, $param);
        if(!$r){
            return -1;
        }
        return $this->bd->getAutonumerico();
    }
}

