<?php
/**
 * Description of ModeloDetalleVenta
 *
 * @author Javier Gallego
 */
class ModeloDetalleVenta {
    private $bd;
    private $tabla="detalleventa";
    
    function __construct(BaseDatos $bd) {
        $this->bd = $bd;
    }
    
    function add(DetalleVenta $detalleventa){
        $sql = "INSERT INTO $this->tabla VALUES (null, :idventa, :idproducto, :cantidad, :precio, :iva)";
        $param['idventa']=$detalleventa->getIdventa();
        $param['idproducto']=$detalleventa->getIdproducto();
        $param['cantidad']=$detalleventa->getCantidad();
        $param['precio']=$detalleventa->getPrecio();
        $param['iva']=$detalleventa->getIva();
        $r=$this->bd->setConsulta($sql, $param);
        if(!$r){
            return -1;
        }
        return $this->bd->getAutonumerico();
    }
    
    function edit(DetalleVenta $detalleventa){
        $sql = "UPDATE $this->tabla SET idventa=:idventa, idproducto=:idproducto, cantidad=:cantidad, precio=:precio, iva=:iva WHERE id=:id";
        $param['idventa']=$detalleventa->getIdventa();
        $param['idproducto']=$detalleventa->getIdproducto();
        $param['cantidad']=$detalleventa->getCantidad();
        $param['precio']=$detalleventa->getPrecio();
        $param['iva']=$detalleventa->getIva();
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
            $detalleventa = new DetalleVenta();
            $detalleventa->set($this->bd->getFila());
            return $detalleventa;
        }
        return null;
    }
    
    function getList($p=0, $rpp=3, $condicion="1=1", $parametro=array(), $orderby = "1"){
        $principio = $p*$rpp;
        $list = array();
        $sql = "select * from $this->tabla where $condicion order by $orderby limit $principio,$rpp";
        $r = $this->bd->setConsulta($sql, $parametro);
        if($r){
            while($fila = $this->bd->getFila()){
                $detalleventa = new DetalleVenta();
                $detalleventa->set($fila);
                $list[] = $detalleventa;
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
