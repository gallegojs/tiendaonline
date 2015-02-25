<?php

require '../require/comun.php';

$idventa = Leer::get("id");
if(Leer::get("id")==null){
    header("Location: gestion.php?e=5");
    exit();
}
$bd = new BaseDatos();
$modeloventas = new ModeloVenta($bd);
$venta = $modeloventas->get($idventa);
$venta->setPago("si");
$r=$modeloventas->edit($venta);
header("Location: gestion.php?e=$r");


