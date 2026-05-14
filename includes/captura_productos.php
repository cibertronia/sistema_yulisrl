<?php

include 'conexion.php';
include 'date.class.php';
include 'funcionesListaProductos.php';
include 'captura_productos_fiscales.php';
$queryProductos = mysqli_query(
    $MySQLi,
    "SELECT * FROM `Productos`
ORDER BY `Productos`.`idProducto` ASC"
);

// $dataProductos = mysqli_fetch_assoc($queryProductos);
date_default_timezone_set('America/La_Paz');
$fechaActual = date('c');

$productos = [];
$count = 0;
while ($dataProductos = mysqli_fetch_assoc($queryProductos)) {

    // $productos[$count] = array(
    //     'idProducto' => $dataProductos['idProducto'],
    //     'StockCB' => $dataProductos['StockCB'],
    //     'StockLP' => $dataProductos['StockLP'],
    //     'StockSC' => $dataProductos['StockSC'],
    //     'StockTJ' => $dataProductos['StockTJ'],
    //     'StockTotal' => $dataProductos['StockTotal'],  
    // );
    $productos[$count] = array(
        $dataProductos['idProducto'],
        $dataProductos['StockCB'],
        $dataProductos['StockLP'],
        $dataProductos['StockSC'],
        $dataProductos['StockTJ'],
        $dataProductos['StockTotal']
    );
    $count++;
}

$obj_merged = (object) [];
$obj_merged->fecha = $fechaActual;

$obj_merged->idProducto = 0;
$obj_merged->StockCB = 1;
$obj_merged->StockLP = 2;
$obj_merged->StockSC = 3;
$obj_merged->StockTJ = 4;
$obj_merged->StockTotal = 5;

$obj_merged->productos = $productos; //array de productos stocks en la fecha

$captura = json_encode($obj_merged);

$capturaQuery = mysqli_query($MySQLi, "INSERT INTO `capturas_productos`(`captura`, `fecha`) VALUES ('$captura','$fechaActual')");


if ($capturaQuery) {
    echo 'insercion captura correcto';
} else {
    echo ' algo salio mal xd';
}
capturar_fiscales();
