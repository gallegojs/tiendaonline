<?php
/**
 * Description of Paypal
 *
 * @author Javier Gallego
 */
class Paypal {
    private $idpaypal, $idpropio, $estado, $importe, $moneda, $emailvendedor, $emailcomprador;
    
    function __construct($idpaypal=null, $idpropio=null, $estado=null, $importe=null, $moneda=null, $emailvendedor=null, $emailcomprador=null) {
        $this->idpaypal = $idpaypal;
        $this->idpropio = $idpropio;
        $this->estado = $estado;
        $this->importe = $importe;
        $this->moneda = $moneda;
        $this->emailvendedor = $emailvendedor;
        $this->emailcomprador = $emailcomprador;
    }
    
    function set($datos, $inicio=0){
        $this->idpaypal = $datos[0+$inicio];
        $this->idpropio = $datos[1+$inicio];
        $this->estado = $datos[2+$inicio];
        $this->importe = $datos[3+$inicio];
        $this->moneda = $datos[4+$inicio];
        $this->emailvendedor = $datos[5+$inicio];
        $this->emailcomprador = $datos[6+$inicio];
    }
    
    function setIdpaypal($idpaypal) {
        $this->idpaypal = $idpaypal;
    }

    function setIdpropio($idpropio) {
        $this->idpropio = $idpropio;
    }

    function setEstado($estado) {
        $this->estado = $estado;
    }

    function setImporte($importe) {
        $this->importe = $importe;
    }

    function setMoneda($moneda) {
        $this->moneda = $moneda;
    }

    function setEmailvendedor($emailvendedor) {
        $this->emailvendedor = $emailvendedor;
    }

    function setEmailcomprador($emailcomprador) {
        $this->emailcomprador = $emailcomprador;
    }

    function getIdpaypal() {
        return $this->idpaypal;
    }

    function getIdpropio() {
        return $this->idpropio;
    }

    function getEstado() {
        return $this->estado;
    }

    function getImporte() {
        return $this->importe;
    }

    function getMoneda() {
        return $this->moneda;
    }

    function getEmailvendedor() {
        return $this->emailvendedor;
    }

    function getEmailcomprador() {
        return $this->emailcomprador;
    }


    

}
