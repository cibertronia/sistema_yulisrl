<?php
	include 'conexion.php';
	if (isset($_POST['idUserEdit'])) {
		$idUser 	=	$_POST['idUserEdit'];
		$queryUserEd=	mysqli_query($MySQLi,"SELECT idUser, Nombres, Apellidos, Telefono, Ciudad, Correo, Sexo, Cargo FROM Usuarios WHERE idUser='$idUser' ");
		$dataUser 	=	mysqli_fetch_assoc($queryUserEd);
		echo json_encode($dataUser);
	}else{

	}
?>