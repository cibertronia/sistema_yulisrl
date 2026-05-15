<?php
	$idUser 		=	$_SESSION['idUser'];
	$ConsltaUser=	mysqli_query($MySQLi,"SELECT * FROM Usuarios WHERE idUser='$idUser' ");
	$datosUser 	=	mysqli_fetch_assoc($ConsltaUser);
	$miCiudad 	=	$datosUser['Ciudad'];?>
<!DOCTYPE html>
<html lang="es">
	<head>
		<title>CADUCADAS</title>
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
	<body><?php 
		include 'php/loader.php'; ?>
		<div id="page-container" class="fade page-sidebar-fixed page-header-fixed"><?php
			include 'php/top_menu.php';
			include 'php/left_menu_vencidas.php';?>			
			<div id="content" class="content"><div class="respuesta"></div><?php
				if (isset($_POST['inicio'])) { 
					$Inicio 	= $_POST['inicio'];
					$Fin 			=	$_POST['fin']; ?>
					<!-- TABLA COTIZACIONES -->
					<div class="row tableCotizaciones">
						<div class="col-md-12">
							<div class="panel panel-inverse">
								<div class="panel-heading">
									<h4 class="panel-title">COTIZACIONES CADUCADAS DESDE <strong class="text-danger"><?php echo $Inicio ?></strong> HASTA <strong class="text-danger"><?php echo $Fin ?></strong></h4>
									<div class="panel-heading-btn">
										<!-- <button class="btn btn-xs btn-primary Buscar"><i class="fa fa-search"> Buscar</i></button>&nbsp;&nbsp; -->
										<!-- <button class="btn btn-xs btn-primary AddNewCotizaBTN">AGREGAR COTIZACION</button>&nbsp;&nbsp;&nbsp; -->
										<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
										<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-success" data-click="panel-reload"><i class="fa fa-redo"></i></a>
										<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>
										<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-danger" data-click="panel-remove"><i class="fa fa-times"></i></a>
									</div>
								</div>
								<div class="panel-body">
									<form  data-parsley-validate="true" class="w-75 m-auto" id="buscar" action="?root=caducadas" method="POST">
										<div class="row mb-2">
											<div class="col text-center">
												<label for="fechaInicio">Fecha de inicio</label>
												<input type="date" name="inicio" id="fechaInicio" class="form-control text-center" value="<?php echo $Inicio ?>" data-parsley-required="true">
											</div>
											<div class="col text-center">
												<label for="fechaFin">Fecha final</label>
												<input type="date" name="fin" id="fechaFin" class="form-control text-center" value="<?php echo $Fin ?>" data-parsley-required="true">
											</div>
											<div class="col">
												<label for="buscar">&nbsp;&nbsp;&nbsp;</label>
												<button type="submit" class="form-control btn btn-xs btn-primary ">Buscar &nbsp;<i class="fas fa-spinner fa-pulse d-none btn-Buscar"></i></button>
											</div>
										</div>
									</form>
									<table id="data-table-buttons" class="table table-striped table-bordered table-td-valign-middle w-100">
										<thead>
											<tr class="table-success">
												<th width="10%" class="text-center">N&ordm;</th>
												<th width="30%" class="text-center">Datos</th>
												<th width="60%" class="text-center">Productos</th>
												<!-- <th width="10%" class="text-center">Acciones</th> -->
											</tr>
										</thead>
										<tbody><?php											
											$Num = 1;
											if ($_SESSION['Rango']=='2') {
												$queryCotiza	=	mysqli_query($MySQLi,"SELECT idCotizacion, Code, Clave, idUser, idCliente, Forma_Pago, DATE_FORMAT(FinFecha_Oferta, '%d de %M, %Y')AS FinFecha_Oferta, Dias_Entrega, Comentarios, Sucursal, DATE_FORMAT(Fecha, '%d de %M, %Y')AS Fecha, DATE_FORMAT(Hora, '%h:%i:%s %p')AS Hora FROM Cotizaciones WHERE Estado=3 AND Fecha BETWEEN '$Inicio' AND '$Fin' ORDER BY Fecha ASC")or die(mysqli_error($MySQLi));
											}else{
												$queryCotiza	=	mysqli_query($MySQLi,"SELECT idCotizacion, Code, Clave, idUser, idCliente, Forma_Pago, DATE_FORMAT(FinFecha_Oferta, '%d de %M, %Y')AS FinFecha_Oferta, Dias_Entrega, Comentarios, Sucursal, DATE_FORMAT(Fecha, '%d de %M, %Y')AS Fecha, DATE_FORMAT(Hora, '%h:%i:%s %p')AS Hora FROM Cotizaciones WHERE idUser='$idUser'AND Estado=3 AND Fecha BETWEEN '$Inicio' AND '$Fin' ORDER BY Fecha ASC")or die(mysqli_error($MySQLi));
											}	
											while ($dataCotiza = mysqli_fetch_assoc($queryCotiza)) { ?>
											<tr class="odd gradeX">
												<td class="text-center"><?php echo $Num; ?></td><?php
													$idCliente 		=	$dataCotiza['idCliente'];
													$queryCliente	=	mysqli_query($MySQLi,"SELECT * FROM Clientes WHERE idCliente='$idCliente' ");
													$dataCliente 	=	mysqli_fetch_assoc($queryCliente);
													$idVendedor 	=	$dataCotiza['idUser'];
													$queryVendedor	=	mysqli_query($MySQLi,"SELECT Nombres, Apellidos, Ciudad, idUser FROM Usuarios WHERE idUser='$idVendedor' ");
													$dataVendedor 	=	mysqli_fetch_assoc($queryVendedor);?>
												<td style="font-size: 10px">
													<table class="table table-success">
														<tr class="table-info">
															<td>CODIGO:</td>
															<th><?php echo $dataCotiza['Code'] ?></th>
														</tr>
														<tr>														
															<td>Cliente:</td>
															<th><?php echo $dataCliente['Nombres']." ".$dataCliente['Apellidos'] ?></th>
														</tr><?php
														if ($dataCliente['Empresa']!='') { ?>
														<tr>
															<td>Empresa:</td>
															<th><?php echo $dataCliente['Empresa'] ?></th>
														</tr><?php }
														if ($dataCliente['Correo']!='') { ?>
														<tr>
															<td>Correo:</td>
															<th><?php echo $dataCliente['Correo'] ?></th>
														</tr><?php } ?>
														<tr>
															<td>Forma de Pago:</td>
															<th><?php echo $dataCotiza['Forma_Pago'] ?></th>
														</tr>
														<tr>
															<td>Teléfono:</td>
															<th><?php echo $dataCliente['Celular'] ?></th>
														</tr>
														<tr>														
															<td>Vendedor:</td>
															<th><?php echo $dataVendedor['Nombres']." ".$dataVendedor['Apellidos'] ?></th>
														</tr>
														<tr>
															<td>Fecha:</td>
															<th><?php echo $dataCotiza['Fecha'] ?></th>
														</tr>
														<tr>
															<td>Hora:</td>
															<th><?php echo $dataCotiza['Hora'] ?></th>
														</tr>
													</table>
												</td>
												<td style="font-size: 12px;">
													<div class="text-center mb-1" style="margin-top: -5%">VENCIÓ EL DIA: <span class="text-danger" style="text-transform: uppercase;"><?php echo $dataCotiza['FinFecha_Oferta'] ?></span></div>
													<table class="table table-success table-striped table-bordered table-td-valign-middle w-100">
														<thead>
															<tr class=" table-info">
																<td width="5%" class="text-center">Cant</td>
																<td width="50%" class="text-center">Descripción</td>
																<td width="15%" class="text-center">Pre_Lista</td>
																<td width="15%" class="text-center">Pre_Ofer</td>
																<td width="15%" class="text-center">Total</td>
															</tr>
														</thead>
														<tbody><?php
															$ClaveTemp 	=	$dataCotiza['Clave'];
															$sqlCotiza	=	mysqli_query($MySQLi,"SELECT * FROM ClaveTemporal WHERE Clave='$ClaveTemp' ");
															while ($dataProdTemp = mysqli_fetch_assoc($sqlCotiza)) { ?>
															<tr>
																<td class="text-center"><?php echo $dataProdTemp['Cantidad'] ?></td><?php
																	$idProducto =	$dataProdTemp['idProducto'];
																	$queryProd 	=	mysqli_query($MySQLi,"SELECT * FROM Productos WHERE idProducto='$idProducto' ");
																	$dataProducto=	mysqli_fetch_assoc($queryProd);?>
																<td><?php echo $dataProducto['Producto']." / " .$dataProducto['Marca']." / ".$dataProducto['Modelo'] ?></td>
																<td>$&nbsp;<?php echo number_format($dataProdTemp['PrecioLista'],2) ?></td>
																<td>$&nbsp;<?php echo number_format($dataProdTemp['PrecioOferta'],2) ?></td>
																<td>$&nbsp;<?php echo number_format($dataProdTemp['PrecioOferta']*$dataProdTemp['Cantidad'],2) ?></td>
															</tr><?php } ?>
														</tbody>
													</table>
												</td>
												<!-- <td class="text-center">															
													<button title="Marcar como entregada" id="<?php //echo $dataCotiza['idCotizacion'] ?>" class="btn btn-xs btn-success cambiarEntregada">
														<i class="fa fa-paper-plane" style="font-size: 15px"></i>
													</button>&nbsp;
													<button title="Borrar cotizacion" id="<?php //echo $dataCotiza['idProducto'] ?>" class="btn btn-xs btn-danger borrarCotizacion">
														<i class="fa fa-trash" style="font-size: 15px"></i>
													</button>&nbsp;
													<button title="Marcar como comprada" id="<?php //echo $dataCotiza['idProducto'] ?>" class="btn btn-xs btn-primary enviarCotbyMail">
														<i class="fa fa-envelope" style="font-size: 15px"></i>
													</button>
												</td> -->
											</tr><?php $Num++; } mysqli_close($MySQLi);?>										
										</tbody>
									</table>
								</div>
								<!-- end panel-body -->
							</div>
						</div>
					</div><?php
				}else{ ?>
					<!-- TABLA COTIZACIONES -->
					<div class="row tableCotizaciones">
						<div class="col-md-12">
							<div class="panel panel-inverse">
								<div class="panel-heading">
									<h4 class="panel-title">COTIZACIONES CADUCADAS <strong><?php echo strtoupper($mes) ?></strong></h4>
									<div class="panel-heading-btn">
										<button class="btn btn-xs btn-primary Buscar"><i class="fa fa-search"> Buscar</i></button>&nbsp;&nbsp;
										<!-- <button class="btn btn-xs btn-primary AddNewCotizaBTN">AGREGAR COTIZACION</button>&nbsp;&nbsp;&nbsp; -->
										<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
										<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-success" data-click="panel-reload"><i class="fa fa-redo"></i></a>
										<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>
										<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-danger" data-click="panel-remove"><i class="fa fa-times"></i></a>
									</div>
								</div>
								<div class="panel-body">
									<form  data-parsley-validate="true" class="w-75 m-auto d-none" id="buscar" action="?root=caducadas" method="POST">
										<div class="row mb-2">
											<div class="col text-center">
												<label for="fechaInicio">Fecha de inicio</label>
												<input type="date" name="inicio" id="fechaInicio" class="form-control text-center" value="<?php echo $startBusqueda ?>" data-parsley-required="true">
											</div>
											<div class="col text-center">
												<label for="fechaFin">Fecha final</label>
												<input type="date" name="fin" id="fechaFin" class="form-control text-center" value="<?php echo $fecha ?>" data-parsley-required="true">
											</div>
											<div class="col">
												<label for="buscar">&nbsp;&nbsp;&nbsp;</label>
												<button type="submit" class="form-control btn btn-xs btn-primary ">Buscar &nbsp;<i class="fas fa-spinner fa-pulse d-none btn-Buscar"></i></button>
											</div>
										</div>
									</form>
									<table id="data-table-buttons" class="table table-striped table-bordered table-td-valign-middle w-100">
										<thead>
											<tr class="table-success">
												<th width="10%" class="text-center">N&ordm;</th>
												<th width="30%" class="text-center">Datos</th>
												<th width="60%" class="text-center">Productos</th>
												<!-- <th width="10%" class="text-center">Acciones</th> -->
											</tr>
										</thead>
										<tbody><?php											
											$Num = 1;
											if ($_SESSION['Rango']=='2') {
												$queryCotiza	=	mysqli_query($MySQLi,"SELECT idCotizacion, Code, Clave, idUser, idCliente, Forma_Pago, DATE_FORMAT(FinFecha_Oferta, '%d de %M, %Y')AS FinFecha_Oferta, Dias_Entrega, Comentarios, Sucursal, DATE_FORMAT(Fecha, '%d de %M, %Y')AS Fecha, DATE_FORMAT(Hora, '%h:%i:%s %p')AS Hora FROM Cotizaciones WHERE Estado=3 AND Fecha BETWEEN '$startBusqueda' AND '$fecha' ORDER BY Fecha ASC")or die(mysqli_error($MySQLi));
											}else{
												$queryCotiza	=	mysqli_query($MySQLi,"SELECT idCotizacion, Code, Clave, idUser, idCliente, Forma_Pago, DATE_FORMAT(FinFecha_Oferta, '%d de %M, %Y')AS FinFecha_Oferta, Dias_Entrega, Comentarios, Sucursal, DATE_FORMAT(Fecha, '%d de %M, %Y')AS Fecha, DATE_FORMAT(Hora, '%h:%i:%s %p')AS Hora FROM Cotizaciones WHERE idUser='$idUser'AND Estado=3 AND Fecha BETWEEN '$startBusqueda' AND '$fecha' ORDER BY Fecha ASC")or die(mysqli_error($MySQLi));
											}	
											while ($dataCotiza = mysqli_fetch_assoc($queryCotiza)) { ?>
											<tr class="odd gradeX">
												<td class="text-center"><?php echo $Num; ?></td><?php
													$idCliente 		=	$dataCotiza['idCliente'];
													$queryCliente	=	mysqli_query($MySQLi,"SELECT * FROM Clientes WHERE idCliente='$idCliente' ");
													$dataCliente 	=	mysqli_fetch_assoc($queryCliente);
													$idVendedor 	=	$dataCotiza['idUser'];
													$queryVendedor	=	mysqli_query($MySQLi,"SELECT Nombres, Apellidos, Ciudad, idUser FROM Usuarios WHERE idUser='$idVendedor' ");
													$dataVendedor 	=	mysqli_fetch_assoc($queryVendedor);?>
												<td style="font-size: 10px">
													<table class="table table-success">
														<tr class="table-info">
															<td>CODIGO:</td>
															<th><?php echo $dataCotiza['Code'] ?></th>
														</tr>
														<tr>														
															<td>Cliente:</td>
															<th><?php echo $dataCliente['Nombres']." ".$dataCliente['Apellidos'] ?></th>
														</tr><?php
														if ($dataCliente['Empresa']!='') { ?>
														<tr>
															<td>Empresa:</td>
															<th><?php echo $dataCliente['Empresa'] ?></th>
														</tr><?php }
														if ($dataCliente['Correo']!='') { ?>
														<tr>
															<td>Correo:</td>
															<th><?php echo $dataCliente['Correo'] ?></th>
														</tr><?php } ?>
														<tr>
															<td>Forma de Pago:</td>
															<th><?php echo $dataCotiza['Forma_Pago'] ?></th>
														</tr>
														<tr>
															<td>Teléfono:</td>
															<th><?php echo $dataCliente['Celular'] ?></th>
														</tr>
														<tr>														
															<td>Vendedor:</td>
															<th><?php echo $dataVendedor['Nombres']." ".$dataVendedor['Apellidos'] ?></th>
														</tr>
														<tr>
															<td>Fecha:</td>
															<th><?php echo $dataCotiza['Fecha'] ?></th>
														</tr>
														<tr>
															<td>Hora:</td>
															<th><?php echo $dataCotiza['Hora'] ?></th>
														</tr>
													</table>
												</td>
												<td style="font-size: 12px;">
													<div class="text-center mb-1" style="margin-top: -5%">VENCIÓ EL DIA: <span class="text-danger" style="text-transform: uppercase;"><?php echo $dataCotiza['FinFecha_Oferta'] ?></span></div>
													<table class="table table-success table-striped table-bordered table-td-valign-middle w-100">
														<thead>
															<tr class=" table-info">
																<td width="5%" class="text-center">Cant</td>
																<td width="50%" class="text-center">Descripción</td>
																<td width="15%" class="text-center">Pre_Lista</td>
																<td width="15%" class="text-center">Pre_Ofer</td>
																<td width="15%" class="text-center">Total</td>
															</tr>
														</thead>
														<tbody><?php
															$ClaveTemp 	=	$dataCotiza['Clave'];
															$sqlCotiza	=	mysqli_query($MySQLi,"SELECT * FROM ClaveTemporal WHERE Clave='$ClaveTemp' ");
															while ($dataProdTemp = mysqli_fetch_assoc($sqlCotiza)) { ?>
															<tr>
																<td class="text-center"><?php echo $dataProdTemp['Cantidad'] ?></td><?php
																	$idProducto =	$dataProdTemp['idProducto'];
																	$queryProd 	=	mysqli_query($MySQLi,"SELECT * FROM Productos WHERE idProducto='$idProducto' ");
																	$dataProducto=	mysqli_fetch_assoc($queryProd);?>
																<td><?php echo $dataProducto['Producto']." / " .$dataProducto['Marca']." / ".$dataProducto['Modelo'] ?></td>
																<td>$&nbsp;<?php echo number_format($dataProdTemp['PrecioLista'],2) ?></td>
																<td>$&nbsp;<?php echo number_format($dataProdTemp['PrecioOferta'],2) ?></td>
																<td>$&nbsp;<?php echo number_format($dataProdTemp['PrecioOferta']*$dataProdTemp['Cantidad'],2) ?></td>
															</tr><?php } ?>
														</tbody>
													</table>
												</td>
												<!-- <td class="text-center">															
													<button title="Marcar como entregada" id="<?php //echo $dataCotiza['idCotizacion'] ?>" class="btn btn-xs btn-success cambiarEntregada">
														<i class="fa fa-paper-plane" style="font-size: 15px"></i>
													</button>&nbsp;
													<button title="Borrar cotizacion" id="<?php //echo $dataCotiza['idProducto'] ?>" class="btn btn-xs btn-danger borrarCotizacion">
														<i class="fa fa-trash" style="font-size: 15px"></i>
													</button>&nbsp;
													<button title="Marcar como comprada" id="<?php //echo $dataCotiza['idProducto'] ?>" class="btn btn-xs btn-primary enviarCotbyMail">
														<i class="fa fa-envelope" style="font-size: 15px"></i>
													</button>
												</td> -->
											</tr><?php $Num++; } mysqli_close($MySQLi);?>										
										</tbody>
									</table>
								</div>
								<!-- end panel-body -->
							</div>
						</div>
					</div><?php
				}?>
			</div>
			<a href="javascript:;" class="btn btn-icon btn-circle btn-primary btn-scroll-to-top fade" data-click="scroll-top"><i class="fa fa-angle-up"></i></a>
			<?php include 'php/footer.php'; ?>
		</div>
		<?php include 'php/script_caducadas.php'; ?>
	</body>
</html>