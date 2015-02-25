<?php
/**
 * Description of ModeloVenta
 *
 * @author Javier Gallego
 */
class ModeloVenta {
    private $bd;
    private $tabla="venta";
    
    function __construct(BaseDatos $bd) {
        $this->bd = $bd;
    }
    function add(Venta $venta){
        $sql = "INSERT INTO $this->tabla VALUES (null, CURDATE(), CURTIME(), :pago, :direccion, :nombre, :precio, :iva)";
        //$param['fecha']=$venta->getFecha();
        //$param['hora']=$venta->getHora();
        $param['pago']=$venta->getPago();
        $param['direccion']=$venta->getDireccion();
        $param['nombre']=$venta->getNombre();
        $param['precio']=$venta->getPrecio();
        $param['iva']=$venta->getIva();
        $r=$this->bd->setConsulta($sql, $param);
        if(!$r){
            return -1;
        }
        return $this->bd->getAutonumerico();
    }
    
    function saldar($id, $pago="si"){
        $sql = "UPDATE $this->tabla SET pago=:pago WHERE id=:id";
        $param['pago'] = $pago;
        $param['id']=$id;
        $r=$this->bd->setConsulta($sql, $param);
        if(!$r){
            return -1;
        }
        return $this->bd->getNumeroFilas();
    }
    
    function edit(Venta $venta){
        $sql = "UPDATE $this->tabla SET fecha=:fecha, hora=:hora, pago=:pago, direccion=:direccion, nombre=:nombre, precio=:precio, iva=:iva WHERE id=:id";
        $param['fecha']=$venta->getFecha();
        $param['hora']=$venta->getHora();
        $param['pago']=$venta->getPago();
        $param['direccion']=$venta->getDireccion();
        $param['nombre']=$venta->getNombre();
        $param['precio']=$venta->getPrecio();
        $param['iva']=$venta->getIva();
        $param['id']=$venta->getId();
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
            $venta = new Venta();
            $venta->set($this->bd->getFila());
            return $venta;
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
                $venta = new Venta();
                $venta->set($fila);
                $list[] = $venta;
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
