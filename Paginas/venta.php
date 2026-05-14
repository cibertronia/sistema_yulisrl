<?php
	$idUser 		=	$_SESSION['idUser'];
	$ConsltaUser=	mysqli_query($MySQLi,"SELECT * FROM Usuarios WHERE idUser='$idUser' ");
	$datosUser 	=	mysqli_fetch_assoc($ConsltaUser);
	$miCiudad 	=	$datosUser['Ciudad'];
	if (isset($_POST['inicio'])) { 
		$INICIO 	=	$_POST['inicio'];
		$FINAL 		=	$_POST['fin'];?>
		<!DOCTYPE html>
		<html lang="es">
			<head>
				<title>VENTAS ANTERIORES</title>
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
			</head>
			<body>
				<?php include 'php/loader.php'; ?>
				<div id="page-container" class="fade page-sidebar-fixed page-header-fixed"><?php
					include 'php/top_menu.php';
					include 'php/left_menu_misVentas.php';?>			
					<div id="content" class="content"><div class="respuesta"></div>
						<div class="row tableCotizaciones">
							<div class="col-md-12">
								<div class="panel panel-inverse">
									<div class="panel-heading">
										<h4 class="panel-title">TABLA VENTAS &nbsp;&nbsp;
											<!-- <span style="text-transform: uppercase;letter-spacing: 1px;font-size: 16px"><?php echo $mes?></span>&nbsp;&nbsp;&nbsp;SUCURSAL 
											<span style="text-transform: uppercase;letter-spacing: 1px;font-size: 16px"><?php echo $datosUser['Ciudad'] ?></span> -->
										</h4>
										<div class="panel-heading-btn">
											<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
											<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-success" data-click="panel-reload"><i class="fa fa-redo"></i></a>
											<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>
											<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-danger" data-click="panel-remove"><i class="fa fa-times"></i></a>
										</div>
									</div>
									<div class="panel-body">
										<div class="text-danger text-center d-none noFechaInicio" style="letter-spacing: 1px">NO HA INDICADO LA FECHA DE INICIO</div>
										<div class="text-danger text-center d-none noFechaFinal" style="letter-spacing: 1px">NO HA INDICADO LA FECHA FINAL</div>
										<table id="data-table-buttons" class="table table-striped table-bordered table-td-valign-middle w-100">
											<thead>
												<tr>
													<th class="text-center">N&ordm;</th>
													<th class="text-center">FECHA</th>
													<th class="text-center">CÓDIGO</th>
													<th class="text-center">N&ordm;<br>RECIBO</th>
													<th class="text-center">NOTA<br>ENTREGA</th>
													<th class="text-center">CLIENTE</th>
													<th class="text-center">NIT</th>
													<th class="text-center">TELÉFONO</th>
													<th class="text-center">PRODUCTO</th>
													<th class="text-center">MARCA</th>
													<th class="text-center">MODELO</th>
													<th class="text-center">CANTIDAD</th>
													<th class="text-center">MONEDA</th>
													<th class="text-center">PRECIO<br>DÓLAR</th>
													<th class="text-center">PRECIO<br>LISTA<br>USD</th>
													<th class="text-center">DESC</th>
													<th class="text-center">PRECIO<br>VENTA<br>USD</th>
													<th class="text-center">PRECIO<br>VENTA<br>Bs</th>
													<th class="text-center">PAGO<br>VENTA<br>USD</th>
													<th class="text-center">PAGO<br>VENTA<br>Bs</th>
												</tr>
											</thead>
											<tbody><?php
												$Number		=	1;										
												$sqlVentas	=	mysqli_query($MySQLi,"SELECT idVenta, idCotizacion, CodeCotizacion, idUser, idCliente, idRecibo, idEntrega, idProducto, Cantidad, Moneda, PrecioDolar, PrecioListaUSD, PrecioListaBs, PrecioVentaUSD, PrecioVentaBs, Sucursal, DATE_FORMAT(Fecha, '%d-%m-%Y')AS Fecha, TotalVentaUS, TotalVentaBs FROM Ventas WHERE idUser='$idUser' AND Fecha BETWEEN '$INICIO' AND '$FINAL' ORDER BY Fecha ")or die(mysql_error($MySQLi));
												while ($dataVenta = mysqli_fetch_assoc($sqlVentas)) {?>
												<tr>
													<td><?php echo $Number ?></td>
													<td><?php echo $dataVenta['Fecha'] ?></td>
													<td><?php echo $dataVenta['CodeCotizacion'] ?></td>
													<td class="text-center"><?php echo $dataVenta['idRecibo'] ?></td>
													<td class="text-center"><?php echo $dataVenta['idEntrega'] ?></td><?php
														$idCliente	=	$dataVenta['idCliente'];
														$queryClient=	mysqli_query($MySQLi,"SELECT * FROM Clientes WHERE idCliente='$idCliente' ");
														$dataCliente=	mysqli_fetch_assoc($queryClient);
														$NameCliente=	$dataCliente['Nombres']." ".$dataCliente['Apellidos'];
														$NIT 		=	$dataCliente['NIT'];
														$Telefono 	=	$dataCliente['Celular']."<br>".$dataCliente['Otro'];
														$idProducto	=	$dataVenta['idProducto'];
														$queryProduc=	mysqli_query($MySQLi,"SELECT * FROM Productos WHERE idProducto='$idProducto' ");
														$dataProduct=	mysqli_fetch_assoc($queryProduc);
														$NameProduct=	$dataProduct['Producto'];
														$MarcaProduc=	$dataProduct['Marca'];
														$ModelProduc=	$dataProduct['Modelo'];?>
													<td><?php echo $NameCliente ?></td>
													<td><?php echo $NIT ?></td>
													<td><?php echo $Telefono ?></td>
													<td><?php echo $NameProduct ?></td>
													<td><?php echo $MarcaProduc ?></td>
													<td><?php echo $ModelProduc ?></td>
													<td class="text-center"><?php echo $dataVenta['Cantidad'] ?></td>
													<td class="text-center"><?php echo $dataVenta['Moneda'] ?></td>
													<td class="text-center"><?php
														if ($dataVenta['Moneda']=='Bs') {
															echo $dataVenta['PrecioDolar'];
														}else{ echo "";}?>
													</td>
													<td><?php echo $dataVenta['PrecioListaUSD'] ?></td>
													<td><?php  ?></td>
													<td><?php echo $dataVenta['PrecioVentaUSD'] ?></td>
													<td><?php echo $dataVenta['PrecioVentaBs'] ?></td>
													<td><?php echo $dataVenta['TotalVentaUS'] ?></td>
													<td><?php echo $dataVenta['TotalVentaBs'] ?></td>
												</tr>
												<?php $Number++; } mysqli_close($MySQLi); ?>
											</tbody>
										</table>
									</div>
								</div>
							</div>
						</div>
					</div>
					<a href="javascript:;" class="btn btn-icon btn-circle btn-primary btn-scroll-to-top fade" data-click="scroll-top"><i class="fa fa-angle-up"></i></a>
					<?php include 'php/footer.php'; ?>
				</div>
				<?php include 'php/script_ventas.php'; ?>
			</body>
		</html><?php
	}else{
		
	}
	