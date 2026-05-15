<?php
	include 'conexion.php';
	$queryProd 	=	mysqli_query($MySQLi,"SELECT * FROM Productos ORDER BY Producto ");
	echo "<option disabled selected>Seleccione Producto</option>";
	while ($dataProd=mysqli_fetch_assoc($queryProd)) {
		echo "<option value=".$dataProd['idProducto'] .">".$dataProd['Producto'] ."</option>";
	}
?>