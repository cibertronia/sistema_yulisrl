<?php
include './../conexion_yuliimport.php';
if (isset($_POST['idCliente'])) {
	$idCliente 	=	$_POST['idCliente'];
	$queryClient =	mysqli_query($YuliimportDB, "SELECT * FROM Clientes WHERE idCliente='$idCliente' ORDER BY Nombres ASC");
	$dataCliente =	mysqli_fetch_assoc($queryClient);
	echo json_encode($dataCliente);
}
