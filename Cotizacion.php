<?php
	function getPlantilla(){
		include 'includes/conexion.php';
		$idCotiza 	=	4;	//$_POST['id'];
		$queryCotiza=	mysqli_query($MySQLi,"SELECT idCotizacion, Code, Clave, idUser, idCliente, Forma_Pago, DATE_FORMAT(FinFecha_Oferta, '%d de %M, %Y') AS FinFecha_Oferta, Dias_Entrega, Comentarios, Sucursal, DATE_FORMAT(Fecha, '%d de %M, %Y') AS Fecha, DATE_FORMAT(Hora, '%h:%i:%s %p') AS Hora FROM Cotizaciones WHERE idCotizacion='$idCotiza' ")or die(mysqli_error($MySQLi));
		$datosCotiza=	mysqli_fetch_assoc($queryCotiza);
		//DATOS DEL USUARIO
		$idUser 	=	$datosCotiza['idUser'];
		$queryUser	=	mysqli_query($MySQLi,"SELECT * FROM Usuarios WHERE idUser='$idUser' ")or die(mysqli_error($MySQLi));
		$datosUser 	=	mysqli_fetch_assoc($queryUser);
		$Sexo 		=	$datosUser['Sexo'];
		$UserName	=	$datosUser['Nombres']." ".$datosUser['Apellidos'];
		$UserPhone	=	$datosUser['Telefono'];

		//DATOS DEL CLIENTE
		$idCliente	=	$datosCotiza['idCliente'];
		$queryClient=	mysqli_query($MySQLi,"SELECT * FROM Clientes WHERE idCliente='$idCliente' ")or die(mysqli_error($MySQLi));
		$datosClient=	mysqli_fetch_assoc($queryClient);
		$ClienteName=	$datosClient['Nombres']." ".$datosClient['Apellidos'];
		$MailCliente=	$datosClient['Correo'];
		$EmpresaClte=	$datosClient['Empresa'];	

		//DATOS DE LA COTIZACION
		$CodeCotiza	=	$datosCotiza['Code'];
		$FormaPago	=	$datosCotiza['Forma_Pago'];
		$FinOferta	=	$datosCotiza['FinFecha_Oferta'];
		$DiasEntrega=	$datosCotiza['Dias_Entrega'];
		$Comentarios=	$datosCotiza['Comentarios'];
		$Sucursal 	=	$datosCotiza['Sucursal'];
		$Fecha 		=	$datosCotiza['Fecha'];
		$Hora 		=	$datosCotiza['Hora'];

		$Plantilla 	=	'
		<p>Señor: '.$ClienteName.'</p>
		<p>Mediante la presente, detallamos la cotización requerida - REF: <span>'.$CodeCotiza .'</span></p>';
		$ClaveCotiza=	$datosCotiza['Clave'];
		$queryClave =	mysqli_query($MySQLi,"SELECT * FROM ClaveTemporal WHERE Clave='$ClaveCotiza' ")or die(mysqli_error($MySQLi));
		while ($datosCot = mysqli_fetch_assoc($queryClave)) {
			echo '
			<table border="1">
				<thead>
					<tr>
						<th colspan="3">'.$datosProduc['Producto'].' / '.$datosProduc['Marca'].' / '.$datosProduc['Modelo'].'</th>
					</tr>
					<tr>
						<th width="5%">Cant</th>
						<th width="60%">Descripción</th>
						<th width="35%">Imagen</th>
					</tr>
				</thead>
				<tbody>';
					$idProducto	=	$datosCot['idProducto'];
					$queryProduc=	mysqli_query($MySQLi,"SELECT * FROM Productos WHERE idProducto='$idProducto' ")or die(mysqli_error($MySQLi));
					$datosProduc=	mysqli_fetch_assoc($queryProduc);
					echo $Plantilla .=	'
					<tr>
						<td>'.$datosCot['Cantidad'] .'</td>
						<td>'.$datosProduc['Descripcion'] .'</td>
						<td><img src="Productos/'.$datosProduc['Imagen'].'" width="50" alt=""></td>
					</tr>
				</tbody>
			</table><br><br>';
		}
		return $Plantilla;
	}
?>