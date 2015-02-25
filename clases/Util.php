<?php

/**
 * Description of Util
 *
 * @author Usuario
 */
class Util {
    static function getEnlacesPaginacion($p, $paginas, $enlace){  //               
        $html = "<a href='$enlace=0'>&lt;&lt;</a> ";
        if($p>0){
            $html .= "<a href='$enlace=".($p-1)."'>&lt;</a> ";
        }else{
            $html .= "<a href='#' class=''>&lt;</a> ";
        }
        $x = array();
        if($p==0){
            for($i=0; $i<$paginas && $i<$p+5; $i++){
                $x[] = $i;
            }
        }else if($p==1){
            for($i=0; $i<$paginas && $i<$p+4; $i++){
                $x[] = $i;
            }
        }else if($p>1&&$p<$paginas-2){
            $x[]=$p-2;
            $x[]=$p-1;
            for($i=$p; $i<$paginas && $i<=$p+2; $i++){
                $x[] = $i;
            }
        }else if($p==$paginas-2){
            for($i=$p-3; $i<$paginas && $i<=$p+1; $i++){
                if($i>=0){
                    $x[] = $i;
                }
            }
        }else if($p==$paginas-1){
            for($i=$p-4; $i<$paginas && $i<=$p; $i++){
                if($i>=0){
                    $x[] = $i;
                }
            }
        }

        foreach($x as $valor){
            if($valor!=$p){
                $html .= "<a href='$enlace=".($valor)."'>".($valor+1)."</a> ";
            }else{
                $html .= "<a class='pagactual' href='#'>".($valor+1)."</a> ";
            }
        }
        if($p+1>$paginas-1){
            $html .= "<a href='#'>&gt;</a> ";
        }else{
            $html .= "<a href='$enlace=".($p+1)."'>&gt;</a> ";
        }
        
        $html .= "<a href='$enlace=".(round($paginas, 0, PHP_ROUND_HALF_UP))."'>&gt;&gt;</a> ";
        return $html;
    }
    static function getEnlacesAjax($p, $paginas){
        $html = "<span>&lt;&lt;</span> ";
        if($p>0){
            $html .= "<span>&lt;</span> ";
        }else{
            $html .= "<span>&lt;</span> ";
        }
        

        if($p==0){
            $x = Array($p, $p+1, $p+2, $p+3, $p+4);
        }else if($p==1){
            $x = Array($p-1, $p, $p+1, $p+2, $p+3);
        }else if($p==$paginas-2){
            $x = Array($p-3, $p-2, $p-1, $p, $p+1);
        }else if($p==$paginas-1){
            $x = Array($p-4, $p-3, $p-2, $p-1, $p);
        }else if($p<$paginas-2){
            $x = Array($p-2, $p-1, $p, $p+1, $p+2);
        }


        $html .= "<span>".($x[0]+1)."</span> ";
        $html .= "<span>".($x[1]+1)."</span> ";
        $html .= "<span>".($x[2]+1)."</span> ";
        $html .= "<span>".($x[3]+1)."</span> ";
        $html .= "<span>".($x[4]+1)."</span> ";

        $html .= "<span>&gt;</span> ";
        $html .= "<span>&gt;&gt;</span> ";
        return $html;
    }
    static function cifrarPass($clave, $digito=7){
        $permitidos = './1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
        $salt = sprintf('$2y$%02d$', $digito);
        for($i = 0; $i < 22; $i++){
            $salt .= $permitidos[mt_rand(0, 63)];
        }
        return crypt($clave, $salt);
    }
    static function isPass($clave, $guardada){
        if(crypt($clave, $guardada) == $guardada){
            return true;
        }
        return false;
    }
    static function muestraMensaje($mensaje){
        switch ($mensaje) {
            case 0:
                return "Editado con exito";
            case 1:
                return "No se pudo encontrar el inmueble seleccinado";
            case 31:
                return "No tiene permisos para acceder a esa página";
            case 30:
                return "Inicie sesión para poder hacer cambios";
            case 20:
                return "Ya está logueado en el sistema";
            case 32:
                return "Inicie sesión para poder acceder";
            case 40:
                return "No existe el usuario introducido";
            case 41:
                return "Contraseña incorrecta";
            case 50:
                return "Las contraseñas no coinciden";
            case 51:
                return "Ha ocurrido un error, pruebe a solicitar una recuperación de contraseña otra vez";
            case 52:
                return "Usuario o Email desconocidos";
            case 53:
                return "Correo confirmado con éxito";
            case 9:
                return "No se ha podido editar";
            case 4:
                return "Ha ocurrido un error, intentelo más tarde";
            case 3:
                return "No ha subido fotos";
            case 5:
                return "Anuncio creado con exito";
            case 6:
                return "No se ha creado el anuncio por algún error";
            case 7:
                return "Eliminado con exito";
            case 8:
                return "No se pudo eliminar";
            case 60:
                return "Correo no válido";
            case 61:
                return "Nombre de usuario no válido";
            case 62:
                return "Correo ya existente en el sistema";
            case 63:
                return "Contraseña no válida o no coinciden";
            case 99:
                return "Algo ha salido mal";
            default:
                return "Error desconocido";
        }
    }
}
