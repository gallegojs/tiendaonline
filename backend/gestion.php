<?php
    require '../require/comun.php';
    
    $pagina = Leer::get("pagina");
    if(Leer::get("pagina")==null){
        $pagina = 0;
    }
    
    
    $bd = new BaseDatos();
    $modeloventas = new ModeloVenta($bd);
    $ventas = $modeloventas->getList($pagina, Configuracion::RPP);
    
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Gestión tienda</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="../css/estilos-front.css">
    </head>
    <body>
        <header><img src="../img/logo.png"></header>
        <section>
        
        <div class="tabla-tienda" style="width: 80%">
            <h1>Lista de ventas</h1>
        <table>
            <tr>
                <td>idventa</td>
                <td>fecha</td>
                <td>hora</td>
                <td>pagado</td>
                <td>nombre</td>
                <td>dirección</td>
                <td>precio</td>
                <td>iva</td>
                <td>detalle</td>
                <td>conf. pago</td>
            </tr>
            <?php
                foreach($ventas as $clave => $venta){
                    ?>
            <tr>
                <td><?php echo $venta->getId() ?></td>
                <td><?php echo $venta->getFecha() ?></td>
                <td><?php echo $venta->getHora() ?></td>
                <td><?php echo $venta->getPago() ?></td>
                <td><?php echo $venta->getNombre() ?></td>
                <td><?php echo $venta->getDireccion() ?></td>
                <td><?php echo $venta->getPrecio() ?></td>
                <td><?php echo $venta->getIva() ?></td>
                <td><a href="verVenta.php?id=<?php echo $venta->getId()?>">ver</a></td>
                <td><a href="phpConfirmarPago.php?id=<?php echo $venta->getId()?>">Confirmar</a></td>
            </tr>
            <?php
                }
            ?>
        </table>
            <?php
            $paginas = $modeloventas->count()/Configuracion::RPP;
            echo Util::getEnlacesPaginacion($pagina, $paginas, "gestion.php?pagina");
            ?>
        </div>
        </section>
    </body>
</html>
