<?php
include './../conexion.php';
$Inicio = $_POST['inicio'];
$Fin = $_POST['fin'];
$sucursal = $_POST['sucursal'];
if ($sucursal == '') {
    $queryClientes = "SELECT
idCliente,Sucursal,
COUNT(`idCliente`) AS 'cantidad_habitual'
FROM
Ventas
WHERE
Fecha BETWEEN '$Inicio' AND '$Fin'
GROUP BY
idCliente
ORDER BY `cantidad_habitual` DESC LIMIT 10";

} else {

$queryClientes = "SELECT
idCliente,Sucursal,
COUNT(`idCliente`) AS 'cantidad_habitual'
FROM
Ventas
WHERE
Fecha BETWEEN '$Inicio' AND '$Fin' AND Sucursal ='$sucursal'
GROUP BY
idCliente
ORDER BY `cantidad_habitual` DESC LIMIT 10";
}

$MySQLiexecute = mysqli_query($MySQLi, $queryClientes);
$datos1 = array();
$datos2 = array();
while ($dataClientes = mysqli_fetch_assoc($MySQLiexecute)) {
    $idCliente = $dataClientes['idCliente'];

    $QueryClientes = mysqli_query($MySQLi, "SELECT * FROM Clientes WHERE idCliente='$idCliente'");
    $datosCliente = mysqli_fetch_assoc($QueryClientes);
    $nombreCompleto = '[' . $dataClientes['cantidad_habitual'] . '] ' . $datosCliente['Nombres'] . ' ' . $datosCliente['Apellidos'];

    array_push($datos1, $nombreCompleto);
    array_push($datos2, $dataClientes['cantidad_habitual']);
}

$array = [
    "etiquetas" => $datos1,
    "datos" => $datos2,
];
echo json_encode($array);
