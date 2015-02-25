<?php

/**
 * Description of InnerProductoFoto
 *
 * @author Javier
 */
class InnerProductoFoto {
    private $producto, $foto;
    function __construct(Producto $producto, Foto $foto) {
        $this->producto = $producto;
        $this->foto = $foto;
    }

    function getProducto() {
        return $this->producto;
    }

    function getFoto() {
        return $this->foto;
    }

    function setProducto($producto) {
        $this->producto = $producto;
    }

    function setFoto($foto) {
        $this->foto = $foto;
    }

}
