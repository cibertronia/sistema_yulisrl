<?php
	include 'conexion.php';
	$querySucu 	=	mysqli_query($MySQLi,"SELECT * FROM Sucursales ORDER BY Sucursal ASC ");
	//echo "<option disabled selected>Seleccione Sucursal</option>";
	while ($dataSucu=mysqli_fetch_assoc($querySucu)) {
		echo "<option value=".$dataSucu['Sucursal'] .">".$dataSucu['Sucursal'] ."</option>";
	}
	// $dataSucu 	=	mysqli_fetch_assoc($querySucu);
	// echo json_encode($dataSucu);
?>