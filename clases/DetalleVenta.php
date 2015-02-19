<?php
/**
 * Description of DetalleVenta
 *
 * @author Javier Gallego
 */
class DetalleVenta {
    private $id, $idventa, $idproducto, $cantidad, $precio, $iva;
    
    function __construct($id=null, $idventa=null, $idproducto=null, $cantidad=null, $precio=null, $iva=null) {
        $this->id = $id;
        $this->idventa = $idventa;
        $this->idproducto = $idproducto;
        $this->cantidad = $cantidad;
        $this->precio = $precio;
        $this->iva = $iva;
    }

    function set($datos, $inicio){
        $this->id = $datos[0+$inicio];
        $this->idventa = $datos[1+$inicio];
        $this->idproducto = $datos[2+$inicio];
        $this->cantidad = $datos[3+$inicio];
        $this->precio = $datos[4+$inicio];
        $this->iva = $datos[5+$inicio];
    }
    function setId($id) {
        $this->id = $id;
    }

    function setIdventa($idventa) {
        $this->idventa = $idventa;
    }

    function setIdproducto($idproducto) {
        $this->idproducto = $idproducto;
    }

    function setCantidad($cantidad) {
        $this->cantidad = $cantidad;
    }

    function setPrecio($precio) {
        $this->precio = $precio;
    }

    function setIva($iva) {
        $this->iva = $iva;
    }

    function getId() {
        return $this->id;
    }

    function getIdventa() {
        return $this->idventa;
    }

    function getIdproducto() {
        return $this->idproducto;
    }

    function getCantidad() {
        return $this->cantidad;
    }

    function getPrecio() {
        return $this->precio;
    }

    function getIva() {
        return $this->iva;
    }


}