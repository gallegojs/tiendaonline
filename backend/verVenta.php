<?php
    require '../require/comun.php';
    $bd = new BaseDatos();
    $modelodetalle = new ModeloDetalleVenta($bd);
    $idventa = Leer::get("id");
    $parametro['idventa'] = $idventa;
    $detalles = $modelodetalle->getList(0, 30, "idventa=:idventa", $parametro);
    $modeloventas = new ModeloVenta($bd);
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="../css/estilos-front.css">
        <title>Venta <?php echo $idventa; ?> - Gestión tienda</title>
    </head>
    <body>
        <header><img src="../img/logo.png"></header>
        <section>
            <h1>Detalles de la venta: <?php echo $idventa ?></h1>
            <div class="tabla-tienda" style="width: 80%">
                <table>
                    <tr>
                        <td>Producto</td>
                        <td>Cantidad</td>
                        <td>precio</td>
                        <td>iva</td>
                        <td>total</td>
                    </tr>
                    <?php
                        foreach($detalles as $clave => $detalle){
                            ?>
                    <tr>
                        <td><?php echo $detalle->getIdproducto() ?></td>
                        <td><?php echo $detalle->getCantidad() ?></td>
                        <td><?php echo $detalle->getPrecio() ?></td>
                        <td><?php echo $detalle->getIva() ?></td>
                        <td><?php echo $detalle->getPrecio() * $detalle->getCantidad() ?></td>
                    </tr>
                    <?php
                        }
                    ?>
                </table>
            </div>
        </section>
    </body>
</html>