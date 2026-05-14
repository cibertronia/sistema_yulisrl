<?php
	include 'conexion.php';
	$idCliente 		=	$_POST['idCliente'];
	$queryCliente 	=	mysqli_query($MySQLi,"SELECT * FROM Clientes WHERE idCliente='$idCliente' ");
	$dataCliente 	=	mysqli_fetch_assoc($queryCliente);
	$Nombres 		=	$dataCliente['Nombres']." ".$dataCliente['Apellidos'];
	$Empresa 		=	$dataCliente['Empresa'];

	$idPlantilla 	=	$_POST['id'];
	$query 			=	mysqli_query($MySQLi,"SELECT * FROM Plantilla_Email WHERE id='$idPlantilla'");
	$data 			=	mysqli_fetch_assoc($query);
	$data_			=	$data['Contenido'];

	//$data_ 			=	str_replace("{Pais}", $Pais, $data_);
	$data_ 			=	str_replace("{Cliente}", $Nombres, $data_);
	$data_ 			=	str_replace("{Empresa}", $Empresa, $data_);
	//$data_ 			=	str_replace("{Web}", $WebSite, $data_);
	echo $data_;
?>