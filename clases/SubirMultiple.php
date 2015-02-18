<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of SubirMultiple
 * @version 1.0
 * @author Javier Gallego
 * @license http://URL sin licencia
 * @copyright (c) 2014, Javier Gallego
 * 
 * Permite la subida de uno o varios ficheros simultaneamente.
 */
class SubirMultiple {
    private $inputname, $tamMax, $tamMaxTotal, $extensiones, $tipos, $accion, $destino, $crearCarpeta, $nuevoNombre, $nombresSubidos;
    private $cantidadMax, $accionExcede;
    private $error, $errorPHP, $listaErrores;
    
    const NO_ERROR = 0, ERROR_TAM_TOTAL = 1, ERROR_NUM_MAX = 2, ERROR_EXT = 3, 
            ERROR_TIPO = 4, ERROR_TAM = 5, ERROR_SUBIDA = 6, ERROR_CREAR_FALSE = 7, 
            ERROR_SIN_CARPETA = 8, ERROR_OMISION = 9;
    const OMITIR = 0, RENOMBRAR = 1, REEMPLAZAR = 2;
    const OMITIR_TODO = 0, SUBIR_PARTE = 1;
    
    function __construct($nombreinput) {
        $this->inputname = $nombreinput;
        $this->tamMax = 1024*1024*2; //2 MB por archivo por defecto
        $this->tamMaxTotal = $this->tamMax*10; //20MB de subida total
        $this->extensiones = Array();
        $this->tipos = Array();
        $this->accion = SubirMultiple::OMITIR; //omitimos las subidas repetidas
        $this->destino = "./"; //carpeta actual por defecto
        $this->crearCarpeta = false; //no podemos crear carpetas
        $this->error = SubirMultiple::NO_ERROR;
        $this->errorPHP = UPLOAD_ERR_OK;
        $this->accionExcede = SubirMultiple::OMITIR_TODO; //si algún archivo sobrepasa tamaño o numero no se sube ninguno
        $this->cantidadMax = 10; //numero archivos permitidos 10
        $this->listaErrores = Array();
        $this->nombresSubidos = Array();
    }
    /**
     * Comprueba que la extension está permitida
     * @access private
     * @param string $extension cadena con la extension a comprobar
     * @return bool Devuelve true si está permitida la extension
     * no se ha pasado y un array si el parametro es multiple.
     */
    private function isExtension($extension){
        if (sizeof($this->extensiones) > 0 && !in_array($extension, $this->extensiones)) {
            return false;
        }
        return true;
    }
    /**
     * Añade una extensión a las permitidas
     * @access public
     * @param string $ext cadena con la nueva extensión
     */
    public function addExtension($ext){
        if(is_array($ext)){
            $this->extensiones = array_merge($this->extensiones, $ext);
        }else{
            $this->extensiones[] = $ext;
        }
    }
    /**
     * Establece las extensiones mediante un array borrando las anteriores
     * @access public
     * @param array|string $ext array o string con las extensiones
     */
    public function setExtension($ext){
        if(is_array($ext)){
            $this->extensiones = $ext;
        }else{
            unset($this->extensiones);
            $this->extensiones[] = $ext;
        }
    }
    /**
     * Añade un tipo MIME a los permitidos
     * @access public
     * @param string $tipo cadena con el nombre del tipo
     */
    public function addTipo($tipo) {
        if (is_array($tipo)) {
            $this->tipos = array_merge($this->tipos, $tipo);
        } else {
            $this->tipos[] = $tipo;
        }
    }
    /**
     * Establece los tipos MIME mediante un array borrando los anteriores
     * @access public
     * @param array|string $tipo array o string con los tipos permitidos
     */
    public function setTipo($tipo){
        if(is_array($tipo)){
            $this->tipos = $tipo;
        }else{
            unset($this->tipos);
            $this->extensiones[] = $tipo;
        }
    }
    /**
     * Comprueba que los tipos MIME esten permitidos
     * @access private
     * @param string $tipo cadena con el tipo a comprobar
     * @return bool Devuelve true si el tipo es permitido, false si no lo es
     */
    private function isTipo($tipo){
        if (sizeof($this->tipos) > 0 && !in_array($tipo, $this->tipos)) {
            return false;
        }
        return true;
    }
    /**
     * Establece que se hará con los archivos repetidos
     * @access public
     * @param int $accion entero 0 si se quiere omitir, 1 si queremos renombrar
     * 2 si queremos reemplazar 
     * @return bool Devuelve true si se establecio correctamente
     */
    public function setAcccion($accion){
        if($accion==SubirMultiple::OMITIR){
            $this->accion = SubirMultiple::OMITIR;
        }elseif($accion==SubirMultiple::RENOMBRAR){
            $this->accion = SubirMultiple::RENOMBRAR;
        }elseif($accion==SubirMultiple::REEMPLAZAR){
            $this->accion = SubirMultiple::REEMPLAZAR;
        }else{
            return false;
        }
        return true;
    }
    /**
     * Crea la carpeta destino del directorio
     * @access private
     * @return bool Devuelve true si crea la carpeta con exito y false si no la
     * crea o si no hemos extablecido que podamos crearla
     */
    private function crearCarpeta(){ 
        if($this->crearCarpeta){
            if(mkdir($this->destino , 0774, true)){
                return true;
            }else{
                return false;
            }
        }
        $this->error = SubirMultiple::ERROR_CREAR_FALSE;
        $this->listaErrores['carpeta'] = SubirMultiple::ERROR_CREAR_FALSE;
        return false;
    }
    /**
     * Establece si se pueden crear carpetas de destino si estas no existiesen
     * @access public
     * @param bool $var true para poder crear carpetas, false para no poder crear
     */
    public function setCrearCarpeta($var){
        $this->crearCarpeta = $var;
    }
    /**
     * Establece el destino como aquel que le pasemos por parámetro
     * @access public
     * @param string $var cadena con el nombre del destino
     */
    public function setDestino($var){
        $caracter = substr($var, -1);
        if ($caracter != "/")
            $var.="/";
        $this->destino = $var;
    }    
    /**
     * Establece el tamaño máximo por archivo individual
     * @access public
     * @param int $tam entero con el tamaño maximo
     */
    public function setTamanio($tam){
        $this->tamMax = $tam;
    }
    /**
     * Establece el tamaño máximo total de toda la subida
     * @access public
     * @param int $tam entero con el tamaño máximo total
     */
    public function setTamanioTotal($tam){
        $this->tamMaxTotal = $tam;
    }
    /**
     * Comprueba que el archivo cumpla las condiciones de tamaño
     * @access private
     * @param int $size tamaño del archivo a comprobar.
     * @return bool Devuelve true si el archivo es válido y false si no lo es.
     */
    private function isTamanio($size){
        if($this->tamMax >= $size){
            return true;
        }else{
            return false;
        }
    }
    /**
     * Establece el número máximo de archivos que podemos subir de golpe.
     * @access public
     * @param int $cantidad entero con el máximo de archivos que podemos subir
     */
    public function setCantidadMaxima($cantidad){
        $this->cantidadMax = $cantidad;
    }
    /**
     * Nos dice la cantidad máxima de archivos que podemos subir
     * @access private
     * @return int Devuelve un entero con la cantidad
     */
    private function getCantidadMaxima(){
        return $this->cantidadMax;
    }
    /**
     * Nos dice si la cantidad de archivos subida es permitida
     * @access private
     * @return bool Devuelve true si la cantidad es permitida y false si no lo es
     */
    private function isCantidad(){
        if($this->cantidadMax != NULL && $this->cantidadMax < $this->getNumeroArchivos()){
            return false;
        }
        return true;
    }
    /**
     * Establece el procedimiento a seguir en caso de excederse el tamaño o
     * número máximos de archivos a subir
     * @access public
     * @param int $accion entero 0 si queremos omitir todoy 1 si queremos 
     * omitir parte
     */
    public function setAccionExcede($accion){
        $this->accionExcede = $accion;
        return true;
    }
    /**
     * Nos dice el tamaño total de los archivos enviados
     * @access private
     * @return int $total Devuelve una entero con el tamaño total de la subida
     */
    private function getTamanioTotal(){
        $total = 0;
        $archivos = $_FILES[$this->inputname];
        $i=0;
        foreach($archivos['name'] as $archivo){
            $total += $archivos['size'][$i];
            $i++;
        }
        return $total;
    }
    /**
     * Devuelve el numero de archivos enviados
     * @access private
     * @return int Devuelve el número de archivos que estamos intentando subir
     */
    private function getNumeroArchivos(){
        $archivos = $_FILES[$this->inputname];
        return sizeof($archivos['name']);
    }
    /**
     * Comprueba si el tamaño total de los archivos a subir es permitido
     * @access private
     * @return bool Devuelve true si el tamaño es permitido, false si no lo es
     */
    private function isTamanioTotal(){
        if($this->tamMaxTotal >= $this->getTamanioTotal()){
            return true;
        }
        return false;
    }
    /**
     * Devuelve un error si lo hubiera (eficaz solo para subidas de un único fichero)
     * @access public
     * @return int $error devuelve el codigo de error que hubiera dado
     * en ese momento
     */
    public function getError(){
        return $this->error;
    }
    /**
     * Devuelve todos los codigos de error que se hubieran producido
     * @access public
     * @return string Devuelve una cadena con las parejas de valores archivo y
     * mensaje de error
     */
    public function getErrores(){
        $cadenaerror = "Resultado de la subida: <br />";
        foreach ($this->listaErrores as $key => $value) {
            $cadenaerror .= $key." --- ".$this->getMensajeError($value);
            $cadenaerror .= "<br />";
        }
        return $cadenaerror;
    }
    /**
     * Devuelve todos los nombres de los archivos subidos
     * @access public
     * @return Array Devuelve un array con los nombres de
     * de los archivos que se han subido 
     */
    public function getNombres(){
        return $this->nombresSubidos;
    }
    /**
     * Establece un nombre generico para las subidas.
     * @access public
     * @param string $nom cadena con el nuevo nombre
     */
    public function setNuevoNombre($nom){
        $this->nuevoNombre = $nom;
        return true;
    }
    /**
     * Devuelve un string con el significado de un código de error pasado
     * por parámetro.
     * @access private
     * @param int $codigoerror entero con el número del error
     * @return string Devuelve una cadena con el significado del error
     */
    private function getMensajeError($codigoerror){
        switch($codigoerror){
            case SubirMultiple::NO_ERROR:
                return "No se han detectado errores";
            case SubirMultiple::ERROR_TAM_TOTAL:
                return "Se ha sobrepasado el tamaño total permitido: ".$this->tamMaxTotal;
            case SubirMultiple::ERROR_NUM_MAX:
                return "Se ha sobrepasado el número máximo de archivos: ".$this->cantidadMax;
            case SubirMultiple::ERROR_EXT:
                return "Extensión no permitida";
            case SubirMultiple::ERROR_TIPO:
                return "Tipo MIME no permitido";
            case SubirMultiple::ERROR_TAM:
                return "Archivo demasiado grande";
            case SubirMultiple::ERROR_SUBIDA:
                return "Ha fallado la subida del archivo";
            case SubirMultiple::ERROR_CREAR_FALSE:
                return "No se ha creado el directorio";
            case SubirMultiple::ERROR_SIN_CARPETA:
                return "No se ha encontrado la carpeta de destino";
            case SubirMultiple::ERROR_OMISION:
                return "El archivo ya existe en el directorio y se ha omitido su subida";
            default:
                return "Error desconocido";
        }
         
    }
    /**
     * Sube el archivo o archivos enviados en el formulario según la
     * configuración especificada.
     * @access public
     */
    public function subir(){
        $archivos = $_FILES[$this->inputname];
        //comprobaciones en caso de que queramos OMITIR_TODO en caso de ser mayor la subida
        if($this->accionExcede == SubirMultiple::OMITIR_TODO){
            if(!$this->isCantidad()){
                $this->error = SubirMultiple::ERROR_NUM_MAX;
                $this->listaErrores['subida'] = SubirMultiple::ERROR_NUM_MAX;
                return false;
            }
            if(!$this->isTamanioTotal()){
                $this->error = SubirMultiple::ERROR_TAM_TOTAL;
                $this->listaErrores['subida'] = SubirMultiple::ERROR_TAM_TOTAL;
                return false;
            }
        }
        //comprobamos si existe el destino e intentamos crearlo si no existe
        if(!file_exists($this->destino)){
            if(!$this->crearCarpeta()){
                $this->error = SubirMultiple::ERROR_SIN_CARPETA;
                $this->listaErrores['subida'] = SubirMultiple::ERROR_SIN_CARPETA;
                return false;
            }
        }
        /*recorremos los archivos enviados mediante un foreach,
         * dentro de el comprobaremos si cada archivo cumple las condiciones individuales
         * y tambien si el subida es demasiado grande en total
         */
        $i=-1;
        $totalsubida=0;
        $contador=0;
        foreach($archivos['name'] as $archivo){
            $i++;
            $totalsiguiente = $totalsubida + $archivos['size'][$i];
            if($contador>=$this->getCantidadMaxima()){
                $this->listaErrores[$archivos['name'][$i]] = SubirMultiple::ERROR_NUM_MAX;
                return false;
            }
            if($totalsiguiente >= $this->tamMaxTotal){
                $this->listaErrores[$archivos['name'][$i]] = SubirMultiple::ERROR_TAM_TOTAL;
                continue;
            }
            $partes = pathinfo($archivos["name"][$i]);
            $extension = $partes['extension'];
            $nombre="";
            if($this->nuevoNombre=="" || $this->nuevoNombre==NULL){
                $nombre = $partes['filename'];
            }else{
                $nombre = $this->nuevoNombre;
            }
            $origen=$archivos['tmp_name'][$i];
            $lugardestino="";
            $nombreunico="";
            if(!$this->isExtension($extension)){
                $this->error = SubirMultiple::ERROR_EXT;
                $this->listaErrores[$archivos['name'][$i]] = SubirMultiple::ERROR_EXT;
                continue;
            }
            if(!$this->isTipo($archivos['type'][$i])){
                $this->error = SubirMultiple::ERROR_TIPO;
                $this->listaErrores[$archivos['name'][$i]] = SubirMultiple::ERROR_TIPO;
                continue;
            }
            if(!$this->isTamanio($archivos['size'][$i])){
                $this->error = SubirMultiple::ERROR_TAM;
                $this->listaErrores[$archivos['name'][$i]] = SubirMultiple::ERROR_TAM;
                continue;
            }
            if($this->accion == SubirMultiple::REEMPLAZAR){
                $lugardestino = $this->destino.$nombre.".".$extension;
                $nombreunico = $nombre.".".$extension;
            }elseif($this->accion == SubirMultiple::RENOMBRAR){
                $x=1;
                $lugardestino = $this->destino . $nombre .".". $extension;
                $nombreunico = $nombre . "." . $extension;
                while (file_exists($lugardestino)) {
                    $lugardestino = $this->destino . $nombre . "($x)." . $extension;
                    $nombreunico = $nombre . "($x)." . $extension;
                    $x++;
                }
            }elseif($this->accion == SubirMultiple::OMITIR){
                $lugardestino = $this->destino.$nombre.".".$extension;
                $nombreunico = $nombre.".".$extension;
                if (file_exists($lugardestino)) {
                    $this->listaErrores[$archivos['name'][$i]] = SubirMultiple::ERROR_OMISION;
                    continue;
                }
            }
            if(!move_uploaded_file($origen, $lugardestino)){
                $this->error = SubirMultiple::ERROR_SUBIDA;
                $this->listaErrores[$archivos['name'][$i]] = SubirMultiple::ERROR_SUBIDA;
            }else{
                $this->nombresSubidos[] = $nombreunico;
            }
            $totalsubida += $archivos['size'][$i];
            $contador++;
            $this->listaErrores[$archivos['name'][$i]] = SubirMultiple::NO_ERROR;
        }
    }
}
