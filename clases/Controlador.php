<?php
/**
 * Description of Controlador
 *
 * @author Javier
 */
class Controlador {
    function selectViewProductos(){
        $bd = new BaseDatos();
        /*$modelo = new ModeloProducto($bd);
        $productos = $modelo->getList();*/
        $filas = "";
        $modeloprodfoto = new ModeloInnerProductoFoto($bd);
        $productos = $modeloprodfoto->getLeftList();
        foreach ($productos as $key => $valor) {
            $producto = $valor->getProducto();
            $foto = $valor->getFoto();
            if($producto->getEstado()=="inactivo"){
                continue;
            }
            //$modelofoto = new ModeloFoto($bd);
            //$foto = $modelofoto->getNombreFotos($value->getId()); 
            $datos = array(
                "id" => $producto->getId(),
                "nombre" => $producto->getNombre(),
                "precio" => $producto->getPrecio(),
                "iva" => $producto->getIva(),
                "descripcion" => $producto->getDescripcion(),
                "foto" => $foto->getNombre()
            );
            $v = new Vista("plantillaProductoListado", $datos);
            $filas.= $v->renderData();
        }
        $datos = array(
            "datos" => $filas
        );
        $v = new Vista("plantillaProductos", $datos);
        $tabla = $v->renderData();

        $tablacarrito = $this->selectViewCarrito();
        $datos = array(
            "contenido" => $tabla,
            "carrito" => $tablacarrito,
            "enlace" => Entorno::getEnlaceCarpeta()
        );
        $v = new Vista("escaparate", $datos);
        $escaparate = $v->renderData();
        
        
        $datos = array(
            "contenido-general" => $escaparate,
            "enlace" => Entorno::getEnlaceCarpeta()
        );
        $v = new Vista("plantillaPrincipal", $datos);
        $v->render();
        exit();
    
    }
    
    function insertDoCarrito(){
        $id = Leer::request("id");
        $bd = new BaseDatos();
        session_start();
        if(!isset($_SESSION["__carrito"])) {
            $_SESSION["__carrito"] = new Carrito(); 
        }
        $carrito = $_SESSION["__carrito"];
        $modelo = new ModeloProducto($bd);
        $producto = $modelo->get($id);

        $carrito->addLinea($producto);
        $_SESSION["__carrito"] = $carrito;
        header('Location: '.Entorno::getProcedencia());
    }
    function subDoCarrito(){
        $id = Leer::request("id");
        $bd = new BaseDatos();
        session_start();
        if(!isset($_SESSION["__carrito"])) {
            header('Location: '.Entorno::getProcedencia());
            exit();
        }
        $carrito = $_SESSION["__carrito"];
        $carrito->supLinea($id);
        $_SESSION["__carrito"] = $carrito;
        header('Location: '.Entorno::getProcedencia());
    }
    function deleteDoCarrito(){
        
        $id = Leer::request("id");
        $bd = new BaseDatos();
        session_start();
        if(!isset($_SESSION["__carrito"])) {
            header('Location: '.Entorno::getProcedencia());
            exit();
        }
        $carrito = $_SESSION["__carrito"];
        $carrito->delLinea($id);
        $_SESSION["__carrito"] = $carrito;
        header('Location: '.Entorno::getProcedencia());
        
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
            "carrito" => $tablacarrito,
            "enlace" => Entorno::getEnlaceCarpeta()
        );
        $v = new Vista("plantillaPagoPaypal", $datos);
        $previopago = $v->renderData();
        
        $datos = array(
            "contenido-general" => $previopago,
            "enlace" => Entorno::getEnlaceCarpeta()
        );
        $v = new Vista("plantillaPrincipal", $datos);
        $v->render();
        exit();
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
        
        header("Location: ?op=insert&action=view&target=paypal&idventa=$idventa");
        
    }
    function insertViewPaypal(){
        $idventa = Leer::get("idventa");
        $bd = new BaseDatos();
        $modeloventa = new ModeloVenta($bd);
        $venta = $modeloventa->get($idventa);
        $datos = array(
            "correo-vendedor" => Configuracion::CORREO_PAYPAL,
            "idventa" => $venta->getId(),
            "precio-total" => $venta->getPrecio()
        );
        $v = new Vista("plantillaConfirmarPago", $datos);
        $contenido = $v->renderData();
        
        $datos = array(
            "contenido-general" => $contenido,
            "enlace" => Entorno::getEnlaceCarpeta()
        );
        $v = new Vista("plantillaPrincipal", $datos);
        $v->render();
        exit();
        
    }
    function insertDoPaypal(){
        $idpaypal = Leer::post("txn_id");
        $idpropio = Leer::post("item_name");
        $estado = Leer::post("payment_status");
        $importe = Leer::post("mc_gross");
        $moneda = Leer::post("mc_currency");
        $emailvendedor = Leer::post("receiver_email");
        $emailcomprador = Leer::post("payer_email");
        
        $bd = new BaseDatos();
        $modelopaypal = new ModeloPaypal($bd);
        $modeloventa = new ModeloVenta($bd);
        $venta = $modeloventa->get($idpropio);
        $paypal = new Paypal($idpaypal, $idpropio, $estado, $importe, $moneda, $emailvendedor, $emailcomprador);
        $modelopaypal->add($paypal);
        

        $cURL = curl_init();
        curl_setopt($cURL, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($cURL, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($cURL, CURLOPT_URL, "https://www.sandbox.paypal.com/cgi-bin/webscr");
        curl_setopt($cURL, CURLOPT_ENCODING, 'gzip');
        curl_setopt($cURL, CURLOPT_BINARYTRANSFER, true);
        curl_setopt($cURL, CURLOPT_POST, true);
        $_POST['cmd'] = '_notify-validate';
        curl_setopt($cURL, CURLOPT_POSTFIELDS, $_POST);
        curl_setopt($cURL, CURLOPT_HEADER, false);
        curl_setopt($cURL, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($cURL, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);
        curl_setopt($cURL, CURLOPT_FORBID_REUSE, true);
        curl_setopt($cURL, CURLOPT_FRESH_CONNECT, true);
        curl_setopt($cURL, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($cURL, CURLOPT_TIMEOUT, 60);
        curl_setopt($cURL, CURLINFO_HEADER_OUT, true);
        curl_setopt($cURL, CURLOPT_HTTPHEADER, array(
        'Connection: close',
        'Expect: ',
        ));
        $respuesta = curl_exec($cURL);
        $estatus = (int)curl_getinfo($cURL, CURLINFO_HTTP_CODE);
        
        file_put_contents("errores.txt", $respuesta."\n", FILE_APPEND);
        file_put_contents("errores.txt", $estatus."\n", FILE_APPEND);
        file_put_contents("errores.txt", strcmp($respuesta, "VERIFIED")."\n", FILE_APPEND);
        
        if (strcmp ($respuesta, "VERIFIED") == 0){
            $venta->setPago("si");
            $r=$modeloventa->edit($venta);
        }else{
            $venta->setPago("rev");
            $r=$modeloventa->edit($venta);
        }
        
        curl_close($cURL);
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
