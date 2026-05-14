<?php
include './../conexion_yuliimport.php';
$idProducto	=	$_POST['id'];
$queryProd 	=	mysqli_query($YuliimportDB, "SELECT * FROM productos_fiscales WHERE idProducto='$idProducto' ");
$dataProd 	=	mysqli_fetch_assoc($queryProd);
echo json_encode($dataProd);
