<?php
	include 'conexion.php';
	if (isset($_POST['id'])) {
		$id	=	$_POST['id'];
		$query=	mysqli_query($MySQLi,"SELECT * FROM TablaComisiones WHERE idTabla='$id' ");
		$data 	=	mysqli_fetch_assoc($query);
		echo json_encode($data);
	}
?>