<?php
	include 'conexion.php';
	$idCuenta	=	$_POST['id'];
	$queryCuenta=	mysqli_query($MySQLi,"SELECT * FROM CuentasMail WHERE idCuenta='$idCuenta' ");
	$dataMail	=	mysqli_fetch_assoc($queryCuenta);
	echo json_encode($dataMail);
?>