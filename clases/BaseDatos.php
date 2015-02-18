<?php

/**
 * Description of BaseDatos
 *
 * @author Usuario
 */
class BaseDatos {
    //put your code here
    private $conexion, $sentencia;
    
    function __construct() {
        try {
            $this->conexion = new PDO(
                    'mysql:host=' . Configuracion::SERVIDOR . ';dbname=' . Configuracion::BASEDATOS, Configuracion::USUARIO, Configuracion::CLAVE, array(
                PDO::ATTR_PERSISTENT => true,
                PDO::MYSQL_ATTR_INIT_COMMAND => 'set names utf8')
            );
        } catch (PDOException $ex) {
            $this->conexion = null;
        }
    }
    function closeConexion(){
        $this->conexion = null;
    }
    function getAutonumerico(){
        return $this->conexion->lastInsertId();
    }
    function getFila(){
        if($this->sentencia!=null){
            return $this->sentencia->fetch();
        }
        return false;
    }
    function getError(){
        $this->sentencia->errorInfo();
    }
    function getNumeroFilas(){
        if($this->sentencia!=null){
            return $this->sentencia->rowCount();
        }
        return false;
    }
    function isConectado(){
        return $this->conexion;
    }
    function setBaseDatos($base){
        
    }
    function setConsulta($sql, $param=array()){
        $this->sentencia =  $this->conexion->prepare($sql);
        foreach($param as $indice => $valor){
            $this->sentencia->bindValue($indice, $valor);
        }
        return $this->sentencia->execute();
    }
    function setConsultaPreparada($consulta, $param){
        $this->consultaSQL = $consulta;
        $this->parametros = $param;
    }
    function setConsultaSQL($consulta){
        $this->consultaSQL = $consulta;
    }
    function setTransaccion(){
        $this->conexion->beginTransaction();
    }
    function validaTransaccion(){
        $this->conexion->commit();
    }
    function anulaTransaccion(){
        $this->conexion->rollBack();
    }
}
