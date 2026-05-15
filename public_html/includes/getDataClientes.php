<?php
	include 'conexion.php';
	if (isset($_POST['idClienteEdit'])) {
		$idCliente 	=	$_POST['idClienteEdit'];
		$queryClient=	mysqli_query($MySQLi,"SELECT * FROM Clientes WHERE idCliente='$idCliente' ");
		$dataCliente=	mysqli_fetch_assoc($queryClient);
		echo json_encode($dataCliente);
	}elseif (isset($_POST['idClienteMail'])) {
		$idCliente 	=	$_POST['idClienteMail'];
		$queryClient=	mysqli_query($MySQLi,"SELECT * FROM Clientes WHERE idCliente='$idCliente' ");
		$dataCliente=	mysqli_fetch_assoc($queryClient);
		echo json_encode($dataCliente);
	}elseif (isset($_POST['idCliente'])) {
		$idCliente 	=	$_POST['idCliente'];
		$queryClient=	mysqli_query($MySQLi,"SELECT * FROM Clientes WHERE idCliente='$idCliente' ORDER BY Nombres ASC");
		$dataCliente=	mysqli_fetch_assoc($queryClient);
		echo json_encode($dataCliente);
	}elseif (isset($_POST['idClienteNotaCredito'])) {
		$idCliente  = $_POST['idClienteNotaCredito'];
		$queryClient= mysqli_query($MySQLi,"SELECT SUM(MontoUSD)AS MontoUSD, SUM(MontoBs)AS MontoBs FROM notasCredito WHERE idCliente='$idCliente' AND Estado=1 ");
		$dataCliente= mysqli_fetch_assoc($queryClient);
		// echo "<br>
		// Monto USD= ".$dataCliente['MontoUSD']."<br>
		// Monto Bs= ".$dataCliente['MontoBs'];
		// $resultClien= mysqli_num_rows($queryClient);
		// echo $resultClien;
		// if ($resultClien>0) {
		// $dataCliente = mysqli_fetch_assoc($queryClient);
		if ($dataCliente['MontoUSD']=='' or $dataCliente['MontoBs']=='') {
		 	echo "";
		}else{
			echo '<div class="col  C"><div class="alert alert-success" style="font-size: 20px" role="alert">El cliente tiene un crédito disponible de:<br>Crédito en USD: '.$dataCliente['MontoUSD'] .'<br>Crédito en Bs: '.$dataCliente['MontoBs'].'</div></div>';
		}
	}
?>