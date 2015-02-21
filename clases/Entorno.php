<?php

class Entorno {

    private function __construct() {
    }

    static function getEnlaceCarpeta($pagina=""){
        return "http://".self::getServidor().":".self::getPuerto().self::getCarpetaServidor().$pagina;
    }

    static function getArrayParametros() {
        $array = array();
        $parametros = self::getParametros();
        $partes = explode("&", $parametros);
        foreach ($partes as $indices => $valor) {
            $subPartes = explode("=", $valor);
            if (!isset($subPartes[1])) {
                $subPartes[1] = "";
            }
            if (isset($array[$subPartes[0]])) {
                if (is_array($array[$subPartes[0]])) {
                    $array[$subPartes[0]][] = $subPartes[1];
                } else {
                    $subArray = array();
                    $subArray[] = $array[$subPartes[0]];
                    $subArray[] = $subPartes[1];
                    $array[$subPartes[0]] = $subArray;
                }
            } else {
                $array[$subPartes[0]] = $subPartes[1];
            }
        }
        return $array;
    }

    static function getCarpetaServidor() {
        $script = self::getScript();
        $pos = strrpos($script, "/");
        $carpeta = substr($script, 0, $pos + 1);
        return $carpeta;
    }

    static function getIpCliente() {
        return $_SERVER["REMOTE_ADDR"];
    }

    static function getMetodo() {
        return $_SERVER['REQUEST_METHOD'];
    }

    static function getNavegadorCliente() {
        return $_SERVER["HTTP_USER_AGENT"];
    }

    static function getPadreRaiz() {
        $raiz = self::getRaiz();
        $pos = strrpos($raiz, "/");
        $carpetaPadre = substr($raiz, 0, $pos + 1);
        return $carpetaPadre;
    }

    static function getPagina() {
        $script = self::getScript();
        $pos = strrpos($script, "/");
        $pagina = substr($script, $pos + 1);
        return $pagina;
    }

    static function getParametros() {
        return $_SERVER['QUERY_STRING'];
    }

    static function getPuerto() {
        return $_SERVER['SERVER_PORT'];
    }

    static function getRaiz() {
        return $_SERVER['DOCUMENT_ROOT'];
    }

    static function getScript() {
        return $_SERVER['SCRIPT_NAME'];
    }

    static function getServidor() {
        return $_SERVER['SERVER_NAME'];
    }

}