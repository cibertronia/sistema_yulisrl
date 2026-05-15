<?php
	include 'conexion.php';
	$id 		=	$_POST['id'];
	$queryCotiza=	mysqli_query($MySQLi,"SELECT * FROM Cotizaciones WHERE idCotizacion='$id' ");
	$dataCotiza =	mysqli_fetch_assoc($queryCotiza);
	$Sucursal 	=	$dataCotiza['Sucursal'];
	$ClaveTemp  =	$dataCotiza['Clave'];
	$CodeCotiza =	$dataCotiza['Code'];

	//OBTENER DATOS DEL CLIENTE
	$idCliente 	=	$dataCotiza['idCliente'];
	$queryCliente=	mysqli_query($MySQLi,"SELECT * FROM Clientes WHERE idCliente='$idCliente' ");
	$dataCliente=	mysqli_fetch_assoc($queryCliente);
	$NameCliente=	$dataCliente['Nombres']." ".$dataCliente['Apellidos'];

	$queryClave =	mysqli_query($MySQLi,"SELECT id, Clave, idProducto, Cantidad, PrecioLista, PrecioOferta, SUM(Cantidad*PrecioOferta)AS Total FROM ClaveTemporal WHERE Clave='$ClaveTemp' ");
	$dataClave 	=	mysqli_fetch_assoc($queryClave);
	$Total		=	$dataClave['Total'];

	$Respuesta = array(
		'idCotizacion'	=>	$id,
		'TOTAL' 		=>	$Total,
		'Sucursal'		=>	$Sucursal,
		'CodeCotiza'	=>	$CodeCotiza,
		'NameCliente'	=>	$NameCliente );
	echo json_encode($Respuesta);
?>