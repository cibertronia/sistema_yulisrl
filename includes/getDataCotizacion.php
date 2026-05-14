<?php
	include 'conexion.php';
	$Clave 	=	$_POST['id'];
	$queryClave	=	mysqli_query($MySQLi,"SELECT Forma_Pago, FinFecha_Oferta, Dias_Entrega, Comentarios, Clave FROM Cotizaciones WHERE Clave='$Clave' ");
	$dataCotiza =	mysqli_fetch_assoc($queryClave);
	echo json_encode($dataCotiza);
?>