<?php
/**
 * Description of Foto
 *
 * @author Javier Gallego
 */
class Foto {
    private $id, $idproducto, $nombre;
    
    function __construct($id=null, $idproducto=null, $nombre=null) {
        $this->id = $id;
        $this->idproducto = $idproducto;
        $this->nombre = $nombre;
    }
    
    function set($datos, $inicio=0){
        $this->id=$datos[0 + $inicio];
        $this->idproducto=$datos[1 + $inicio];
        $this->nombre=$datos[2 + $inicio];
    }
            
    function setId($id) {
        $this->id = $id;
    }
    
    function setIdproducto($idproducto) {
        $this->idproducto = $idproducto;
    }

    function setNombre($nombre) {
        $this->nombre = $nombre;
    }

    function getId() {
        return $this->id;
    }
    function getIdproducto() {
        return $this->idproducto;
    }

    function getNombre() {
        return $this->nombre;
    }

    public function getJSON(){
        $prop = get_object_vars($this);
        $resp = "{";
        foreach ($prop as $key => $value){
            $resp.='"'.$key.'":'.json_encode($value).',';
            //htmlspecialchars_decode()
        }
        $resp = substr($resp, 0, -1)."}";
        return $resp;
    }
}
