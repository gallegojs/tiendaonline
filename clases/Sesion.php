<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Sesion
 *
 * @author Usuario
 */
class Sesion {
    private $error;
    
    const ERROR_NO=0, ERROR_NOVAR=1, ERROR_EXISTE=2, ERROR_PARAMETRO=3;

    function __construct($nombre=""){
        if($nombre!=""){
            session_name($nombre);
        }
        session_start();

        $this->error=0;
    }
    
    function set($nombre, $valor){
        if(isset($_SESSION[$nombre])){
            $_SESSION[$nombre]=$valor;
            return true;
        }else{
            $this->error=ERROR_NOVAR;
            return false;
        }
    }
    
    function add($nombre, $valor){
        if(isset($_SESSION[$nombre])){
            $this->error=  $this->ERROR_EXISTE;
            return false;
        }else{
            $_SESSION[$nombre]=$valor;
            return true;
        }
    }
    
    function get($nombre){
        if(isset($_SESSION[$nombre])){
            return $_SESSION[$nombre];
        }else{
            $this->error = ERROR_NOVAR;
            return NULL;
        }
    }
    
    function getNombre(){
        $indices[] = array();
        foreach ($_SESSION as $key => $value) {
            $indices[]=$key;
        }
        return $indices;
    }
    
    function delete($nombre=""){
        if($nombre==""){
            unset($_SESSION);
            return true;
        }
        if(isset($_SESSION[$nombre])){
            unset($_SESSION[$nombre]);
            return true;
        }else{
            $this->error = ERROR_NOVAR;
            return false;
        }
    }
    
    function isSesion(){
        if(count(getNombre())>0){
            return true;
        }else{
            return false;
        }
    }
    
    function isAutentificado(){
        if(isset($_SESSION['autentificado'])){
            return $_SESSION['autentificado'];
        }
        return false;
    }
    
    function setAutentificado($bool){
        if($bool==true || $bool==false){
            $_SESSION['autentificado']=$bool;
        }else{
            $this->error=ERROR_PARAMETRO;
            return false;
        }
    }
    
    function getUsuario(){
        return $_SESSION['usuario'];
    }
    
    function setUsuario($valor){
        return $_SESSION['usuario']=$valor;
    }
    
    function destroy(){
        $this->delete();
        session_destroy();
    }
    function getMensajeError(){
        if($this->error===ERROR_NO){
            return "No hay error";
        }elseif($this->error===ERROR_NOVAR){
            return "La variable no ha sido añadida previamente";
        }elseif($this->error===ERROR_EXISTE){
            return "Ya existe una variable con ese nombre";
        }elseif($this->error===ERROR_PARAMETRO){
            return "El parámetro pasado no es valido";
        }
    }
    function redirigir($destino="index.php"){
        header("Location:".$destino);
        exit();
    }
    function noAutentificado($destino="index.php"){
        if(!$this->isAutentificado()){
            $this->redirigir($destino);
        }
    }
    function siAutentificado($destino="index.php"){
        if($this->isAutentificado()){
            $this->redirigir($destino);
        }
    }
    function administrador($destino="index.php"){
        $usuario = $this->getUsuario();
        if(!$this->isAutentificado() || !$usuario->getIsroot()){
            $this->redirigir($destino);
        }
    }
    function isAdministrador(){
        $usuario = $this->getUsuario();
        if(!$this->isAutentificado() || !$usuario->getIsroot()){
            return false;
        }
        return true;
    }
    function salir(){
        $this->setAutentificado(false);
        unset($_SESSION);
    }
}
/**
 * constructor pasando el nombre de la sesion(por defecto "");
 * metodos
 *  add(nombre, valor);
 *  set(nombre, valor);
 *  get(nombre);
 *  getNombres();
 *  delete(nombres);
 *  delete();
 *  isSesion();
 *  isAutentificado(),
 *  getusuario();
 *  setusuario();
 * 
 */

/* singleton
<?php

class SesionSingleton {

    private static $instancia;

    private function __construct($nombre="") {
        if ($nombre !== "") {
            session_name($nombre);
        }
        session_start();
    }

    public static function getSesion($nombre="") {
        if (is_null(self::$instancia)) {
            self::$instancia = new self($nombre);
        }
        return self::$instancia;
    }

    function cerrar() {
        session_unset();
        session_destroy();
    }
    
    function set($variable, $valor) {
        $_SESSION[$variable] = $valor;
    }
    
    function delete($variable=""){
        if($variable===""){
            unset($_SESSION);
        } else {
            unset($_SESSION[$variable]);
        }
    }

    function get($variable) {
        if (isset($_SESSION[$variable]))
            return $_SESSION[$variable];
        return null;
    }
    
    function getNombres(){
        $array = array();
        foreach ($_SESSION as $key => $value) {
            $array[] = $key;
        }
        return $array;
    }

    function isSesion(){
        return count($_SESSION)>0;
    }

    function isAutentificado(){
 * //condicion para ver si ip es correcta 
 * //y para ver si navegador tambien
 * //
        return isset($_SESSION["__usuario"]);
    }

    function setUsuario($usuario){
        if($usuario instanceof Usuario){
            $this->set("__usuario",$usuario);
        }
    }
    
    function getUsuario(){
        if($this->get("__usuario")!=null)
            return $this->get("__usuario");
        return null;
    }
    
    private function redirigir($destino="index.php"){
        header("Location:".$destino);
        exit;
    }
}
 */