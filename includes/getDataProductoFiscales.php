<?php
	include 'conexion.php';
	$idProducto	=	$_POST['id'];
	$queryProd 	=	mysqli_query($MySQLi,"SELECT * FROM productos_fiscales WHERE idProducto='$idProducto' ");
	$dataProd 	=	mysqli_fetch_assoc($queryProd);
	echo json_encode($dataProd);
?>