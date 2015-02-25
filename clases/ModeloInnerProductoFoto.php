<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ModeloInnerProductoFoto
 *
 * @author Javier
 */
class ModeloInnerProductoFoto {
    private $bd;
    function __construct(BaseDatos $bd) {
        $this->bd = $bd;
    }

    function getLeftList(){
        $sql = "select p.*, f.* from producto p left join foto f on p.id = f.idproducto";
        $r = $this->bd->setConsulta($sql);
        $respuesta = array();
        while ($fila = $this->bd->getFila()){
            $obj1 = new Producto();
            $obj1->set($fila);
            $obj2 = new Foto();
            $obj2->set($fila, 6);
            $objeto = new InnerProductoFoto($obj1, $obj2);
            $respuesta[] = $objeto;
        }
        return $respuesta;
    }
}
