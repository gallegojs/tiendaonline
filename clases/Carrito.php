<?php
/**
 * Description of Carrito
 *
 * @author Javier Gallego
 */
class Carrito implements Iterator{
    private $carrito;
    private $posicion=0;
            function __construct($carrito=null) {
        if($carrito==null){
            $this->carrito = array();
        }
        $this->carrito = $carrito;
    }
    public function getCarrito() {
        return $this->carrito;
    }
    public function set($carrito) {
        $this->carrito = $carrito;
    }
    public function delLinea($id){
        unset($this->carrito[$id]);
    }
    public function addLinea(Producto $producto){
        $id = $producto->getId();
        if (isset($this->carrito[$id])) {
            $lineacarrito = $this->getLinea($id);
            $lineacarrito->setCantidad($lineacarrito->getCantidad() + 1);
        } else {
            $lineacarrito = new LineaCarrito($producto, 1);
            $this->carrito[$id] = $lineacarrito;
        }
    }
    public function supLinea($id){
        if (isset($this->carrito[$id])) {
            $lineacarrito = $this->getLinea($id);
            $lineacarrito->setCantidad($lineacarrito->getCantidad() - 1);
            if ($lineacarrito->getCantidad() < 1) {
                $this->delLinea($id);
            }
        }
    }
    public function getLinea($id){
        return $this->carrito[$id];
    }

    public function current() {
        $claves = array_keys($this->carrito);
        return $this->carrito[$this->key()];
    }

    public function key() {
        $claves = array_keys($this->carrito);
        return $claves[$this->posicion];
    }

    public function next() {
        $this->posicion++;
    }

    public function rewind() {
        $this->posicion = 0;
    }

    public function valid() {
        $claves = array_keys($this->carrito);
        if(isset($claves[$this->posicion])){
            return isset($this->carrito[$this->key()]);
        }
        return false;
    }
}
