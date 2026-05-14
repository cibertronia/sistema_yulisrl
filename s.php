<?php
	    include 'includes/conexion.php';
	    
 	    //2024-03-20|2|purbs|348|1|2
	    $p = $_GET['p'];
	     
	    $s = "delete from Productos where idProducto = " . $p;
        mysqli_query($MySQLi,$s);

?>