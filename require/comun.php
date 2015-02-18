<?php
function autoload($clase) {
    if(file_exists('clases/'.$clase . '.php')){
        require 'clases/'.$clase . '.php';
    } else{
        require '../clases/'.$clase . '.php';
    }
}
spl_autoload_register('autoload');
/*$sesion = SesionSingleton::getSesion();
$autentificado = $sesion->isAutentificado();
$mensaje = "";
$tipo = 0;
$ajax = false;*/