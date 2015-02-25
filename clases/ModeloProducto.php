<?php
/**
 * Description of ModeloProducto
 *
 * @author Javier Gallego
 */
class ModeloProducto {
    private $bd;
    private $tabla="producto";
    
    function __construct(BaseDatos $bd) {
        $this->bd = $bd;
    }
    
    function add(Producto $producto){
        $sql = "INSERT INTO $this->tabla VALUES (null, :nombre, :descripcion, :precio, :iva, :estado)";
        $param['nombre'] = $producto->getNombre();
        $param['descripcion'] = $producto->getDescripcion();
        $param['precio'] = $producto->getPrecio();
        $param['iva'] = $producto->getIva();
        $param['estado'] = $producto->getEstado();
        $r=$this->bd->setConsulta($sql, $param);
        if(!$r){
            return -1;
        }
        return $this->bd->getAutonumerico();
    }
    
    function lock($id, $estado="inactivo"){
        $sql = "UPDATE $this->tabla SET estado=:estado WHERE id=:id";
        $param['estado'] = $estado;
        $param['id']=$id;
        $r=$this->bd->setConsulta($sql, $param);
        if(!$r){
            return -1;
        }
        return $this->bd->getNumeroFilas();
    }
    
    function edit(Producto $producto){
        $sql = "UPDATE $this->tabla SET nombre=:nombre, descripcion=:descripcion, precio=:precio, iva=:iva, estado=:estado WHERE id=:id";
        $param['id'] = $producto->getId();
        $param['nombre'] = $producto->getNombre();
        $param['descripcion'] = $producto->getDescripcion();
        $param['precio'] = $producto->getPrecio();
        $param['iva'] = $producto->getIva();
        $param['estado'] = $producto->getEstado();
        $r=$this->bd->setConsulta($sql, $param);
        if(!$r){
            return -1;
        }
        return $this->bd->getNumeroFilas();
    }
    
    function get($id){
        $sql = "SELECT * FROM $this->tabla where id=:id";
        $param['id']=$id;
        $r=$this->bd->setConsulta($sql, $param);
        if($r){
            $producto = new Producto();
            $producto->set($this->bd->getFila());
            return $producto;
        }
        return null;
    }
    
    function getList($condicion="1=1", $parametro=array(), $orderby = "1"){
        $list = array();
        $sql = "select * from $this->tabla where $condicion order by $orderby";
        $r = $this->bd->setConsulta($sql, $parametro);
        if($r){
            while($fila = $this->bd->getFila()){
                $producto = new Producto();
                $producto->set($fila);
                $list[] = $producto;
            }
        }else{
            return null;
        }
        return $list;
    }
    
    function count($condicion = "1=1", $parametros = array()) {
        $sql = "select count(*) from $this->tabla where $condicion";
        $r = $this->bd->setConsulta($sql, $parametros);
        if ($r) {
            $x = $this->bd->getFila();
            return $x[0];
        }
        return -1;
    }
}
