<?php
/**
 * Description of Vista
 *
 * @author Usuario
 */
class Vista {
    private $datos;
    private $plantilla;
    
    function __construct($plantilla = null, $datos = array()) {
        $this->datos = $datos;
        $this->plantilla = $plantilla;
    }
    
    function render()
    {        
        print $this->renderData();      
    }
    
    function leerPlantilla()
    {
        return file_get_contents("../plantilla/" .$this->plantilla. ".html");
    }
    
    function renderData()
    {
        $contenidoPlantilla = $this->leerPlantilla();
         foreach ($this->datos as $clave => $valor) {   
            $contenidoPlantilla = str_replace("{".$clave."}", $valor, $contenidoPlantilla); 
        }
        return $contenidoPlantilla;
    }
    
    public function getDatos() {
        return $this->datos;
    }

    public function setDatos($datos) {
        $this->datos = $datos;
    }

    public function getPlantilla() {
        return $this->plantilla;
    }

    public function setPlantilla($plantilla) {
        $this->plantilla = $plantilla;
    }
}
