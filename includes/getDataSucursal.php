<?php
	include 'conexion.php';
	$idSucursal	=	$_POST['id'];
	$querySucu 	=	mysqli_query($MySQLi,"SELECT * FROM Sucursales WHERE idSucursal='$idSucursal' ");
	$dataSucu 	=	mysqli_fetch_assoc($querySucu);
	echo json_encode($dataSucu);
?>