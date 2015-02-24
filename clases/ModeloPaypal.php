<?php

/**
 * Description of ModeloPaypal
 *
 * @author Javier Gallego
 */
class ModeloPaypal {
    private $bd, $tabla="paypal";
    
    function __construct($bd) {
        $this->bd = $bd;
    }

}
