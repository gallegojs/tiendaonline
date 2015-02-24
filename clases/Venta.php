<?php
/**
 * Description of Venta
 *
 * @author Javier Gallego
 */
class Venta {
    private $id, $fecha, $hora, $pago, $direccion, $nombre, $precio, $iva;
    
    function __construct($id=null, $fecha=null, $hora=null, $pago="no", $direccion="", $nombre="", $precio=0, $iva=0) {
        $this->id = $id;
        $this->fecha = $fecha;
        $this->hora = $hora;
        $this->pago = $pago;
        $this->direccion = $direccion;
        $this->nombre = $nombre;
        $this->precio = $precio;
        $this->iva = $iva;
    }

    function set($datos, $inicio=0){
        $this->id = $datos[0+$inicio];
        $this->fecha = $datos[1+$inicio];
        $this->hora = $datos[2+$inicio];
        $this->pago = $datos[3+$inicio];
        $this->direccion = $datos[4+$inicio];
        $this->nombre = $datos[5+$inicio];
        $this->precio = $datos[6+$inicio];
        $this->iva = $datos[7+$inicio];
    }
    
    function setId($id) {
        $this->id = $id;
    }

    function setFecha($fecha) {
        $this->fecha = $fecha;
    }

    function setHora($hora) {
        $this->hora = $hora;
    }

    function setPago($pago) {
        $this->pago = $pago;
    }

    function setDireccion($direccion) {
        $this->direccion = $direccion;
    }

    function setNombre($nombre) {
        $this->nombre = $nombre;
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

    function getFecha() {
        return $this->fecha;
    }

    function getHora() {
        return $this->hora;
    }

    function getPago() {
        return $this->pago;
    }

    function getDireccion() {
        return $this->direccion;
    }

    function getNombre() {
        return $this->nombre;
    }

    function getPrecio() {
        return $this->precio;
    }

    function getIva() {
        return $this->iva;
    }
}