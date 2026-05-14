<?php
	if (isset($_GET['idReporte'])) {
		//require '../includes/librerias/mPDF/vendor/autoload.php';
		require '../includes/conexion.php';
		include '../includes/date.class.php';
		mysqli_query($MySQLi,"SET lc_time_names= 'es_BO' ");

		header("Content-type: application/vnd.ms-excel; name='excel'");
		header("Content-Disposition: filename=archivo.xls");
		header("Pragma: no-cache");
		//header("Pragma: no-cache");
		header("Expires: 0"); ?>
		<style>
			*{
				margin: 0;
				padding: 0;
			}
			body{
				height: 21.59cm;
				width:  27.94cm;
				font-size: 10px;
			}
			table{
				border-collapse: collapse;
    			border-spacing: 0;
			}
		</style>
		
		<table border="1">
			<thead>
				<tr>
					<th colspan="22" style="text-align: center;">
						<h3>Reporte de Ventas</h3>
					</th>
				</tr>
				<tr>
					<th width="1px" class="text-center">N&ordm;</th>
					<th width="5px" class="text-center">FECHA</th>
					<th width="5px" class="text-center">RECIBO</th>
					<th width="10px" class="text-center">CODIGO</th>
					<th width="10px" class="text-center">FACTURA</th>
					<th width="10px" class="text-center">CLIENTE</th>
					<th width="5px" class="text-center">NIT</th>
					<th width="5px" class="text-center">TELEFONO</th>
					<th width="15px" class="text-center">PRODUCTO</th>
					<th width="5px" class="text-center">MARCA</th>
					<th width="5px" class="text-center">MODELO</th>
					<th width="1px" class="text-center">CANT</th>
					<th width="2px" class="text-center">PRE_LISTA</th>
					<th width="5px" class="text-center">DESC</th>
					<th width="2px" class="text-center">PRE_VENTA</th>
					<th width="5px" class="text-center">Bs</th>
					<th width="5px" class="text-center">PAGO_VENTA Bs</th>
					<th width="5px" class="text-center">PAGO_VENTA USD</th>
					<th width="1px" class="text-center">VENDEDOR</th>
					<th width="1px" class="text-center">SUCURSAL</th>
					<th width="5px" class="text-center">No.</th>
					<th width="1px" class="text-center">OBSERVACIONES</th>
				</tr>
			</thead>
			<tbody>
				<?php
					$queryVentas=	mysqli_query($MySQLi,"SELECT idVenta, Cotizacion, idUser, idCliente, idProducto, Cantidad, PrecioLista, PrecioVenta, Sucursal, DATE_FORMAT(Fecha, '%d-%m-%Y') AS Fecha FROM Ventas WHERE Fecha BETWEEN '$startBusqueda' AND '$fecha' ORDER BY Sucursal ASC ");
					while ($datosVenta = mysqli_fetch_assoc($queryVentas)) {
				?>
				<tr>
					<td><?php echo $Number ?></td>
					<td><?php echo $datosVenta['Fecha'] ?></td>
					<td></td>
					<td><?php echo $datosVenta['Cotizacion'] ?></td>
					<td></td>
					<?php
						$idCliente		=	$datosVenta['idCliente'];
						$consultCliente =	mysqli_query($MySQLi,"SELECT * FROM Clientes WHERE idCliente='$idCliente' ");
						$datosCliente	=	mysqli_fetch_assoc($consultCliente);
						if ($datosCliente['Celular']=='' AND $datosCliente['Otro']=='') {
							$TelCliente	=	'VAC&icute;O';
						}elseif ($datosCliente['Otro']=='') {
							$TelCliente	=	$datosCliente['Celular'];
						}elseif ($datosCliente['Celular']=='') {
							$TelCliente	=	$datosCliente['Otro'];
						}else{
							$TelCliente	=	$datosCliente['Celular']." / ".$datosCliente['Otro'];
						}
						$NameCliente 	=	utf8_encode($datosCliente['Nombres']." ".$datosCliente['Apellidos']);

						$idUsuario		=	$datosVenta['idUser'];
						$consultUsuario	=	mysqli_query($MySQLi,"SELECT Nombres, Apellidos FROM Usuarios WHERE idUser='$idUsuario' ");
						$datosUsuario	=	mysqli_fetch_assoc($consultUsuario);
						$Vendedor 		=	utf8_encode($datosUsuario['Nombres']." ".$datosUsuario['Apellidos']);

						$idProducto 	=	$datosVenta['idProducto'];
						$consultProducto=	mysqli_query($MySQLi,"SELECT Producto, Marca, Modelo FROM Productos WHERE idProducto='$idProducto' ");
						$datosProducto	=	mysqli_fetch_assoc($consultProducto);
					?>
					<td><?php echo utf8_encode($NameCliente) ?></td>
					<td><?php echo $datosCliente['NIT'] ?></td>
					<td><?php echo $TelCliente ?></td>
					<td><?php echo utf8_encode($datosProducto['Producto']) ?></td>
					<td><?php echo utf8_encode($datosProducto['Marca']) ?></td>
					<td><?php echo utf8_encode($datosProducto['Modelo']) ?></td>
					<td><?php echo $datosVenta['Cantidad'] ?></td>
					<td>$ <?php echo number_format($datosVenta['PrecioLista'],2) ?></td>
					<td></td>
					<td>$ <?php echo number_format($datosVenta['PrecioVenta'],2) ?></td>
					<td></td>
					<td></td>
					<td>$ <?php echo number_format($datosVenta['Cantidad']*$datosVenta['PrecioVenta'],2) ?></td>
					<td><?php echo $Vendedor ?></td>
					<td><?php echo $datosVenta['Sucursal'] ?></td>
					<td></td>
					<td></td>
				</tr>
				<?php $Number++; } mysqli_close($MySQLi); ?>
			</tbody>
		</table><?php
	}else{

	}	
?>