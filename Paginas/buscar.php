<?php
	include 'includes/conexion.php';
	include 'includes/date.class.php';
	mysqli_query($MySQLi,"SET lc_time_names= 'es_BO' ");
	$idUser 	=	$_SESSION['idUser'];
	$ConsltaUser=	mysqli_query($MySQLi,"SELECT * FROM Usuarios WHERE idUser='$idUser' ");
	$datosUser 	=	mysqli_fetch_assoc($ConsltaUser);
	$miCiudad 	=	$datosUser['Ciudad'];
	$INICIO 	=	$_POST['inicio'];
	$FINAL 		=	$_POST['fin'];
?>
<!DOCTYPE html>
<html lang="es">
	<head>
		<title>REPORTES ANTERIORES</title>
		<?php include 'php/meta.php'; ?>
		<link href="assets/css/apple/app.min.css" rel="stylesheet">
		<link href="assets/plugins/ionicons/css/ionicons.min.css" rel="stylesheet">
		<link href="assets/plugins/jvectormap-next/jquery-jvectormap.css" rel="stylesheet">
		<link href="assets/plugins/bootstrap-datepicker/dist/css/bootstrap-datepicker.css" rel="stylesheet">
		<link href="assets/plugins/datatables.net-bs4/css/dataTables.bootstrap4.min.css" rel="stylesheet">
		<link href="assets/plugins/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css" rel="stylesheet">
		<link href="assets/plugins/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css" rel="stylesheet">
		<link href="assets/plugins/blueimp-gallery/css/blueimp-gallery.min.css" rel="stylesheet">
		<link href="assets/plugins/blueimp-file-upload/css/jquery.fileupload.css" rel="stylesheet">
		<link href="assets/plugins/blueimp-file-upload/css/jquery.fileupload-ui.css" rel="stylesheet">
		<link href="assets/plugins/select2/dist/css/select2.min.css" rel="stylesheet">
		<link rel="stylesheet" href="assets/switchery/switchery.css">
		<style>
			.f-s-4{
				font-size: 4px;
			}
		</style>
	</head>
	<body>
		<?php include 'php/loader.php'; ?>
		<div id="page-container" class="fade page-sidebar-fixed page-header-fixed">
			<?php
				include 'php/top_menu.php';
				include 'php/left_menu.php';
			?>			
			<div id="content" class="content"><div class="respuesta"></div>
				<div class="row tableCotizaciones">
					<div class="col-md-12">
						<div class="panel panel-inverse">
							<div class="panel-heading">
								<h4 class="panel-title">REPORTE VENTAS &nbsp;&nbsp;
									<!-- <span style="text-transform: uppercase;letter-spacing: 1px;font-size: 16px"><?php echo $mes?></span> -->
								</h4>
								<div class="panel-heading-btn">
									<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
									<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-success" data-click="panel-reload"><i class="fa fa-redo"></i></a>
									<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>
									<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-danger" data-click="panel-remove"><i class="fa fa-times"></i></a>
								</div>
							</div>
							<div class="panel-body">
								
									<table id="data-table-buttons" class="table table-striped table-bordered table-td-valign-middle w-100">
										<thead>
											<tr>
												<th class="text-center">N&ordm;</th>
												<th class="text-center">FECHA</th>
												<th class="text-center">RECIBO</th>
												<th class="text-center">CODIGO</th>
												<th class="text-center">FACTURA</th>
												<th class="text-center">CLIENTE</th>
												<th class="text-center">NIT</th>
												<th class="text-center">TELEFONO</th>
												<th class="text-center">PRODUCTO</th>
												<th class="text-center">MARCA</th>
												<th class="text-center">MODELO</th>
												<th class="text-center">CANT</th>
												<th class="text-center">PRE_LISTA</th>
												<th class="text-center">DESC</th>
												<th class="text-center">PRE_VENTA</th>
												<th class="text-center">Bs</th>
												<th class="text-center">PAGO_VENTA Bs</th>
												<th class="text-center">PAGO_VENTA USD</th>
												<th class="text-center">VENDEDOR</th>
												<th class="text-center">SUCURSAL</th>
												<th class="text-center">No.</th>
												<th class="text-center">OBSERVACIONES</th>
											</tr>
										</thead>
										<tbody>
											<?php
												$Number 	=	1;
												$queryVentas=	mysqli_query($MySQLi,"SELECT idVenta, Cotizacion, idUser, idCliente, idProducto, Cantidad, PrecioLista, PrecioVenta, Sucursal, DATE_FORMAT(Fecha, '%d-%m-%Y') AS Fecha FROM Ventas WHERE Fecha BETWEEN '$INICIO' AND '$FINAL' ORDER BY Sucursal ASC ");
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
														$TelCliente	=	'VACÍO';
													}elseif ($datosCliente['Otro']=='') {
														$TelCliente	=	$datosCliente['Celular'];
													}elseif ($datosCliente['Celular']=='') {
														$TelCliente	=	$datosCliente['Otro'];
													}else{
														$TelCliente	=	$datosCliente['Celular']." / ".$datosCliente['Otro'];
													}
													$NameCliente 	=	$datosCliente['Nombres']." ".$datosCliente['Apellidos'];

													$idUsuario		=	$datosVenta['idUser'];
													$consultUsuario	=	mysqli_query($MySQLi,"SELECT Nombres, Apellidos FROM Usuarios WHERE idUser='$idUsuario' ");
													$datosUsuario	=	mysqli_fetch_assoc($consultUsuario);
													$Vendedor 		=	$datosUsuario['Nombres']." ".$datosUsuario['Apellidos'];

													$idProducto 	=	$datosVenta['idProducto'];
													$consultProducto=	mysqli_query($MySQLi,"SELECT Producto, Marca, Modelo FROM Productos WHERE idProducto='$idProducto' ");
													$datosProducto	=	mysqli_fetch_assoc($consultProducto);
												?>
												<td><?php echo $NameCliente ?></td>
												<td><?php echo $datosCliente['NIT'] ?></td>
												<td><?php echo $TelCliente ?></td>
												<td><?php echo $datosProducto['Producto'] ?></td>
												<td><?php echo $datosProducto['Marca'] ?></td>
												<td><?php echo $datosProducto['Modelo'] ?></td>
												<td><?php echo $datosVenta['Cantidad'] ?></td>
												<td>$ <?php echo $datosVenta['PrecioLista'] ?></td>
												<td></td>
												<td>$ <?php echo $datosVenta['PrecioVenta'] ?></td>
												<td></td>
												<td></td>
												<td>$ <?php echo $datosVenta['Cantidad']*$datosVenta['PrecioVenta'] ?></td>
												<td><?php echo $Vendedor ?></td>
												<td><?php echo $datosVenta['Sucursal'] ?></td>
												<td></td>
												<td></td>
											</tr>
											<?php $Number++; } mysqli_close($MySQLi); ?>
										</tbody>
									</table>
								</div>
							</div>
							<!-- end panel-body -->
						</div>
					</div>
				</div>
			</div>
			<a href="javascript:;" class="btn btn-icon btn-circle btn-primary btn-scroll-to-top fade" data-click="scroll-top"><i class="fa fa-angle-up"></i></a>
			<?php include 'php/footer.php'; ?>
		</div>
		<?php include 'php/script_reportes.php'; ?>
	</body>
</html>