<?php
/**
 * Description of Controlador
 *
 * @author Javier
 */
class Controlador {
    function selectViewProductos(){
        $bd = new BaseDatos();
        $modelo = new ModeloProducto($bd);
        $productos = $modelo->getList(0, 6);
        $filas = "";
        foreach ($productos as $key => $value) {
            if($value->getEstado()=="inactivo"){
                continue;
            }
            $modelofoto = new ModeloFoto($bd);
            $foto = $modelofoto->getNombreFotos($value->getId()); //////////////implementar ijpf
            $datos = array(
                "id" => $value->getId(),
                "nombre" => $value->getNombre(),
                "precio" => $value->getPrecio(),
                "iva" => $value->getIva(),
                "descripcion" => $value->getDescripcion(),
                "foto" => $foto[0]
            );
            $v = new Vista("plantillaProductoListado", $datos);
            $filas.= $v->renderData();
        }
        $datos = array(
            "datos" => $filas
        );
        $v = new Vista("plantillaProductos", $datos);
        $tabla = $v->renderData();

        /*Procesado del carrito*/
        $tablacarrito = $this->selectViewCarrito();
        $datos = array(
            "title" => "Titulo",
            "title" => "Otro titulo",
            "contenido" => $tabla,
            "carrito" => $tablacarrito,
            "enlace" => Entorno::getEnlaceCarpeta()
        );
        $v = new Vista("escaparate", $datos);
        $v->render();
        exit();
    
    }
    
    function insertDoCarrito(){
        $id = Leer::request("id");
        $bd = new BaseDatos();
        // añadir el producto a la cesta
        session_start();
        
        if(!isset($_SESSION["__carrito"])) {
            $_SESSION["__carrito"] = new Carrito(); 
        }
        $carrito = $_SESSION["__carrito"];
        $modelo = new ModeloProducto($bd);
        $producto = $modelo->get($id);

        $carrito->addLinea($producto);
        $_SESSION["__carrito"] = $carrito;
        header('Location: ?op=select&action=view&target=productos');
    }
    function subDoCarrito(){
        $id = Leer::request("id");
        $bd = new BaseDatos();
        session_start();
        if(!isset($_SESSION["__carrito"])) {
            header('Location: ?op=select&action=view&target=productos');
            exit();
        }
        $carrito = $_SESSION["__carrito"];
        $carrito->supLinea($id);
        $_SESSION["__carrito"] = $carrito;
        header('Location: ?op=select&action=view&target=productos');
    }
    function deleteDoCarrito(){
        $id = Leer::request("id");
        $bd = new BaseDatos();
        session_start();
        if(!isset($_SESSION["__carrito"])) {
            header('Location: ?op=select&action=view&target=productos');
            exit();
        }
        $carrito = $_SESSION["__carrito"];
        $carrito->delLinea($id);
        $_SESSION["__carrito"] = $carrito;
        header('Location: ?op=select&action=view&target=productos');
    }
    function selectViewCarrito(){
        session_start();
        $tablacarrito = "";
        if(isset($_SESSION['__carrito'])){
            $carrito = $_SESSION['__carrito'];
            $tuplas="";
            $totalprecio=0;
            foreach($carrito as $key => $valor){
                $totalprecio+=$valor->getProducto()->getPrecio()*$valor->getCantidad();
                $datos = array(
                    "nombre-carrito" => $valor->getProducto()->getNombre(),
                    "precio-carrito" => $valor->getProducto()->getPrecio(),
                    "cantidad-carrito" => $valor->getCantidad(),
                    "id" => $valor->getProducto()->getId()
                );
                $v = new Vista("plantillaCarritoDetalle", $datos);
                $tuplas .= $v->renderData();
            }
            $datos = array(
                "detallecarrito" => $tuplas,
                "total" => $totalprecio
            );
            $v = new Vista("plantillaCarrito", $datos);
            $tablacarrito .= $v->renderData();
            return $tablacarrito;
        }
        return "";
    }
    function insertViewVenta(){
        $tablacarrito = $this->selectViewCarrito();
        $datos = array(
            "title" => "Titulo",
            "title" => "Otro titulo",
            "contenido" => $tabla,
            "carrito" => $tablacarrito,
            "enlace" => Entorno::getEnlaceCarpeta()
        );
        $v = new Vista("plantillaPagoPaypal", $datos);
        $v->render();
    }
    function insertDoVenta(){
        $nombre = Leer::post("nombre");
        $direccion = Leer::post("direccion");
        
        session_start();
        if(!isset($_SESSION["__carrito"])) {
            header('Location: ?op=select&action=view&target=produ');
            exit();
        }
        
        $bd = new BaseDatos();
        $modelodetalle = new ModeloDetalleVenta($bd);
        $modeloventa = new ModeloVenta($bd);
        
        $venta = new Venta();
        $venta->setNombre($nombre);
        $venta->setDireccion($direccion);
        $idventa = $modeloventa->add($venta);
        $preciototal = 0;
        
        $carrito = $_SESSION["__carrito"];
        foreach ($carrito as $clave => $valor) {
            $detalle = new DetalleVenta(null, $idventa, $valor->getProducto()->getId(), $valor->getCantidad(), $valor->getProducto()->getPrecio(), $valor->getProducto()->getIva());
            $r = $modelodetalle->add($detalle);
            if($r!=-1){
                $preciototal += $valor->getCantidad() * $valor->getProducto()->getPrecio();
            }
        }
        
        $venta = $modeloventa->get($idventa);
        $venta->setPrecio($preciototal);
        $modeloventa->edit($venta);
        
        echo "ok o no ok no se sabe";
        //header("Location: ?")
        
    }
    function handle(){
        $metodo = "";
        $op = Leer::request("op");
        $action = Leer::request("action");
        $target = Leer::request("target");
        $metodo = $op.ucfirst($action).ucfirst($target);

        if(method_exists($this, $metodo)){
            $this->$metodo();
        }
        else{
            $this->nop();
        }
    }
    function nop(){
        $this->selectViewProductos();
    }
}
