<?php
/**
 * Description of ModeloFoto
 *
 * @author Javier Gallego
 */
class ModeloFoto {
    private $bd, $tabla="foto";
    function __construct(BaseDatos $bd) {
        $this->bd = $bd;
    }
    function add(Foto $foto){
        $sql = "INSERT INTO $this->tabla VALUES(null, :idplato, :nombre)";
        $param['idproducto']=$foto->getIdproducto();
        $param['nombre']=$foto->getNombre();
        $r=$this->bd->setConsulta($sql, $param);
        if(!$r){
            $r=-1;
        }
        return $this->bd->getAutonumerico();
    }
    function delete($id){
        $sql = "DELETE FROM $this->tabla WHERE id=:id";
        $param['id'] = $id;
        $r=$this->bd->setConsulta($sql, $param);
        if(!$r){
            return -1;
        }
        unlink("../imagenes/".$foto->getNombre());
        return $this->bd->getNumeroFilas();
    }
    function deletePorProducto($idproducto){
        $nombres = $this->getNombreFotos($idproducto);
        $sql = "DELETE FROM $this->tabla WHERE idproducto=:idproducto";
        $param['idproducto'] = $idproducto;
        $r=$this->bd->setConsulta($sql, $param);
        if(!$r){
            return -1;
        }
        foreach($nombres as $nombre){
            unlink("../imagenes/".$nombre);
        }
        return $this->bd->getNumeroFilas();
    }
    function getNombreFotos($idproducto){
        $list = array();
        $sql = "select * from $this->tabla where idproducto=:idproducto";
        $param['idproducto']=$idproducto;
        $r = $this->bd->setConsulta($sql, $param);
        if($r){
            while($fila = $this->bd->getFila()){
                $foto = new Foto();
                $foto->set($fila);
                $list[] = $foto->getNombre();
            }
        }else{
            return null;
        }
        return $list;
    }
    function getListJSON($idplato){
        $sql = "select * from $this->tabla where idplato=:idplato";
        $param['idplato'] = $idplato;
        $this->bd->setConsulta($sql, $param);
        $r = "[ ";
        while($fila = $this->bd->getFila()){
            $foto = new Foto();
            $foto->set($fila);
            $r .= $foto->getJSON() . ",";
        }
        $r = substr($r, 0, -1)."]";
        return $r;
    }
}
