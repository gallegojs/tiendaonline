<?php
/**
 * Description of Paypal
 *
 * @author Javier Gallego
 */
class Paypal {
    private $estado, $emailvendedor, $importe, $moneda, $idpaypal, $emailcomprador, $idpropio;
    function __construct($estado, $emailvendedor, $importe, $moneda, $idpaypal, $emailcomprador, $idpropio) {
        $this->estado = $estado;
        $this->emailvendedor = $emailvendedor;
        $this->importe = $importe;
        $this->moneda = $moneda;
        $this->idpaypal = $idpaypal;
        $this->emailcomprador = $emailcomprador;
        $this->idpropio = $idpropio;
    }
    
    function set($datos, $inicio){
        $this->estado = $datos[0+$inicio];
        $this->emailvendedor = $datos[1+$inicio];
        $this->importe = $datos[2+$inicio];
        $this->moneda = $datos[3+$inicio];
        $this->idpaypal = $datos[4+$inicio];
        $this->emailcomprador = $datos[5+$inicio];
        $this->idpropio = $datos[6+$inicio];
    }
    
    function setEstado($estado) {
        $this->estado = $estado;
    }

    function setEmailvendedor($emailvendedor) {
        $this->emailvendedor = $emailvendedor;
    }

    function setImporte($importe) {
        $this->importe = $importe;
    }

    function setMoneda($moneda) {
        $this->moneda = $moneda;
    }

    function setIdpaypal($idpaypal) {
        $this->idpaypal = $idpaypal;
    }

    function setEmailcomprador($emailcomprador) {
        $this->emailcomprador = $emailcomprador;
    }

    function setIdpropio($idpropio) {
        $this->idpropio = $idpropio;
    }

    function getEstado() {
        return $this->estado;
    }

    function getEmailvendedor() {
        return $this->emailvendedor;
    }

    function getImporte() {
        return $this->importe;
    }

    function getMoneda() {
        return $this->moneda;
    }

    function getIdpaypal() {
        return $this->idpaypal;
    }

    function getEmailcomprador() {
        return $this->emailcomprador;
    }

    function getIdpropio() {
        return $this->idpropio;
    }


}
