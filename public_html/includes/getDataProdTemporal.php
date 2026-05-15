<?php
	include 'conexion.php';
	$id 	=	$_POST['id'];
	$sqlProd=	mysqli_query($MySQLi,"SELECT id, Clave, Cantidad, PrecioOferta FROM ClaveTemporal WHERE id='$id' ");
	$dataPro=	mysqli_fetch_assoc($sqlProd);
	echo json_encode($dataPro);
?>