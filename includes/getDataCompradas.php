<?php
include 'conexion.php';
session_start();
$idCotizacion = $_POST['id'];

$sqlCotizacion = mysqli_query($MySQLi, "SELECT * FROM Cotizaciones WHERE idCotizacion='$idCotizacion' ");
$dataCotiza = mysqli_fetch_assoc($sqlCotizacion);
$sucursalCompra = $dataCotiza['Sucursal'];
$codigoCotizacion = $dataCotiza['Code'];
$idCliente = $dataCotiza['idCliente'];
$idUsuario = $dataCotiza['idUser'];
$clave = $dataCotiza['Clave'];

$sqlCliente = mysqli_query($MySQLi, "SELECT * FROM Clientes WHERE idCliente='$idCliente' ");
$dataCliente = mysqli_fetch_assoc($sqlCliente);
$NombreCliente = $dataCliente['Nombres'] . ' ' . $dataCliente['Apellidos'];
$nitCliente = $dataCliente['NIT'];
$ciudadCliente = $dataCliente['Ciudad'];
$correoCliente = $dataCliente['Correo'];

$sqlUsuario = mysqli_query($MySQLi, "SELECT * FROM Usuarios WHERE idUser='$idUsuario' ");
$dataUsuario = mysqli_fetch_assoc($sqlUsuario);
$nombreVendedor = $dataUsuario['Nombres'] . " " . $dataUsuario['Apellidos'];

$sqlPrecioDolar = mysqli_query($MySQLi, "SELECT * FROM precio ");
$dolarBd = mysqli_fetch_assoc($sqlPrecioDolar);

$sqlClave = mysqli_query($MySQLi, "SELECT * FROM ClaveTemporal WHERE Clave='$clave' ");
//productos array
$datos = array();
$_SESSION["carrito"] = [];
$count = 0;
while ($data = mysqli_fetch_assoc($sqlClave)) {

    $idProducto = $data['idProducto'];
    $sqlProducto = mysqli_query($MySQLi, "SELECT * FROM Productos WHERE idProducto='$idProducto' ");
    $dataProducto = mysqli_fetch_assoc($sqlProducto);
    $codeProduct = $dataProducto['Modelo'];
    $codeProductSin = $dataProducto['codeProductSin'];
    $ProductoName = $dataProducto['Producto'] . " " . $dataProducto['Marca'] . " " . $dataProducto['Modelo'];

    $qty = $data['Cantidad'];
    $priceUnit = number_format(($data['PrecioOferta'] * $dolarBd['precioDolar']), 2, ".", "");

    $datos[$count] = array(
        'activityEconomic' => '465000',
        'unitMeasure' => 62,
        'codeProductSin' => $codeProductSin,
        'codeProduct' => $codeProduct,
        'description' => $ProductoName,
        'qty' => (int) $qty,
        'priceUnit' => $priceUnit,
        'idProducto' => $idProducto,
    );
    $count++;
}
$_SESSION["carrito"] = $datos;

$sqlClave2 = mysqli_query($MySQLi, "SELECT SUM(cantidad*PrecioOferta)AS total FROM ClaveTemporal WHERE Clave='$clave' ");
$dataTotal = mysqli_fetch_assoc($sqlClave2);
$dataTotal = number_format($dataTotal['total'] * $dolarBd['precioDolar'], 2, ".", "");

//superjson
//$obj_merged = (object) array_merge((array) $dataCotiza, (array) $dataCliente,(array) $dataUsuario);

$obj_merged = (object) [];

$obj_merged->idCotizacion = $idCotizacion;
$obj_merged->sucursalCompra = $sucursalCompra;
$obj_merged->codigoCotizacion = $codigoCotizacion;
$obj_merged->idCliente = $idCliente;

$obj_merged->idUsuario = $idUsuario;
$obj_merged->clave = $clave;
$obj_merged->NombreCliente = $NombreCliente;

$obj_merged->nitCliente = $nitCliente;
$obj_merged->ciudadCliente = $ciudadCliente;
$obj_merged->correoCliente = $correoCliente;

$obj_merged->nombreVendedor = $nombreVendedor;
$obj_merged->productosVendidos = $datos;//array con los productos vendidos
$obj_merged->dataTotal = $dataTotal;

echo json_encode($obj_merged);

