<?php
/**
 * Description of Validar
 *
 * @author 
 */
class Validar {
    static function isCorreo($v){
        return filter_var($v, FILTER_VALIDATE_EMAIL);
    }
    static function isLogin($v){
        if(self::isCorreo($v)){
            return false;
        }
        return true;
    }
    static function isEntero($v){
        return filter_var($v, FILTER_VALIDATE_INT);
    }
    static function isNumero($v){
        return filter_var($v, FILTER_VALIDATE_FLOAT);
    }
    static function isTelefono($v){
        return self::isCondicion($v,'/^[6-9][0-9]{8}$/');
    }
    static function isClave($v){
        //return self::isCondicion($v, '/[A-Za-z0-9]{6,10}$/');
        //desactivada para poder realizar pruebas más facilmente
        return true;
    }
    static function isURL($v){
        return filter_var($v, FILTER_VALIDATE_URL);
    }
    static function isFecha($v){
        return self::isCondicion($v,'/^(0[1-9]|1[0-9]|2[0-9]|3[01])(.|-)(0[1-9]|1[012])(.|-)(19[5-9]|20[012])[0-9]$/'); 
    }
    static function isDNI($v){
        return self::isCondicion($v,'/^(([X-Z]{1})([-]?)(\d{7})([-]?)([A-Z]{1}))|((\d{8})([-]?)([A-Z]{1}))$/');
    }
    static function isIP($v){
        return filter_var($v, FILTER_VALIDATE_IP);
    }
    static function isCP($v){
        return self::isCondicion($v,'/^(((0[1-9]|5[0-2])|[1-4][0-9])|AD)[0-9]{3}$/');
    }
    static function isAltaUsuario($login, $clave, $claveconfirmada, $nombre, $apellidos, $email){
        return true;
    }
    static function isCondicion($v, $condicion){
        return preg_match($condicion, $v);
    }
}