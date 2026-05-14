<?php
	$idUser 		=	$_SESSION['idUser'];
	$ConsltaUser=	mysqli_query($MySQLi,"SELECT * FROM Usuarios WHERE idUser='$idUser' ");
	$datosUser 	=	mysqli_fetch_assoc($ConsltaUser);
	$miCiudad 	=	$datosUser['Ciudad']; ?>
<!DOCTYPE html>
<html lang="es">
	<head>
		<title>COMPRAS MODIFICADAS</title>
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
	<body><?php include 'php/loader.php'; ?>
		<div id="page-container" class="fade page-sidebar-fixed page-header-fixed"><?php
			include 'php/top_menu.php';
			include 'php/left_menu_directas.php'; ?>			
			<div id="content" class="content"><div class="respuesta"></div><?php
				if (isset($_POST['inicio'])) { 
					$Inicio 	= $_POST['inicio'];
					$Fin 			=	$_POST['fin']; ?>
					<!-- TABLA COTIZACIONES -->
					<div class="row tableCotizaciones">
						<div class="col-md-12">
							<div class="panel panel-inverse">
								<div class="panel-heading">
									<h4 class="panel-title">COTIZACIONES MODIFICADAS DESDE <strong class="text-danger"><?php echo $Inicio ?></strong> HASTA <strong class="text-danger"><?php echo $Fin ?></strong></h4>
									<div class="panel-heading-btn">
										<!-- <button class="btn btn-xs btn-primary Buscar"><i class="fa fa-search"> Buscar</i></button>&nbsp;&nbsp; -->
										<!-- <button class="btn btn-xs btn-primary findCompras"><i class="fa fa-search"> &nbsp;&nbsp;BUSCAR</i></button>&nbsp;&nbsp;&nbsp; -->
										<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
										<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-success" data-click="panel-reload"><i class="fa fa-redo"></i></a>
										<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>
										<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-danger" data-click="panel-remove"><i class="fa fa-times"></i></a>
									</div>
								</div>
								<div class="panel-body">
									<form  data-parsley-validate="true" class="w-75 m-auto" id="buscar" action="?root=directas" method="POST">
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
												<th width="5%" class="text-center">N&ordm;</th>
												<th width="25%" class="text-center">Datos</th>
												<th width="60%" class="text-center">Productos</th>
												<!-- <th width="10%" class="text-center">Acciones</th> -->
											</tr>
										</thead>
										<tbody><?php											
											$Num = 1;
											if ($_SESSION['Rango']=='2') {
												$queryCotiza	=	mysqli_query($MySQLi,"SELECT idCotizacion, Code, Clave, idUser, idCliente, Forma_Pago, DATE_FORMAT(FinFecha_Oferta, '%d de %M, %Y')AS FinFecha_Oferta, Dias_Entrega, Comentarios, Sucursal, DATE_FORMAT(Fecha, '%d de %M, %Y') AS Fecha, DATE_FORMAT(Hora, '%h:%i:%s %p') AS Hora, DATE_FORMAT(Compra, '%d de %M, %Y') AS Compra FROM Cotizaciones WHERE Compra BETWEEN '$Inicio'AND'$Fin'AND Estado=2 ORDER BY Compra DESC");
											}else{
												$queryCotiza	=	mysqli_query($MySQLi,"SELECT idCotizacion, Code, Clave, idUser, idCliente, Forma_Pago, DATE_FORMAT(FinFecha_Oferta, '%d de %M, %Y')AS FinFecha_Oferta, Dias_Entrega, Comentarios, Sucursal, DATE_FORMAT(Fecha, '%d de %M, %Y') AS Fecha, DATE_FORMAT(Hora, '%h:%i:%s %p') AS Hora, DATE_FORMAT(Compra, '%d de %M, %Y') AS Compra FROM Cotizaciones WHERE idUser='$idUser'AND Compra BETWEEN '$Inicio'AND'$Fin'AND Estado=2 ORDER BY Compra DESC");
											}
											while ($dataCotiza 	=	mysqli_fetch_assoc($queryCotiza)) {
												// 	ESTA ES EL ID DE LA COTIZACION
												$idCotizacion 	= 	$dataCotiza['idCotizacion'];
												$idCliente 		=	$dataCotiza['idCliente'];
												$idVendedor 	=	$dataCotiza['idUser'];?>
											<tr class="odd gradeX">
												<td class="text-center"><?php echo $Num; ?></td><?php
													/*	BUSCAMOS LOS DATOS DEL CLIENTE	*/
													$queryCliente	=	mysqli_query($MySQLi,"SELECT * FROM Clientes WHERE idCliente='$idCliente' ");
													$dataCliente 	=	mysqli_fetch_assoc($queryCliente);
													/*	BUSCAMOS LOS DATOS DEL VENDEDOR	*/
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
													<div class="text-center mt-1 mb-1" style="margin-top: -5%">VENDIDA EL DÍA: <span class="text-danger" style="text-transform: uppercase;"><?php echo $dataCotiza['Compra'] ?></span></div>
													<table class="table table-success table-striped table-bordered table-td-valign-middle w-100">
														<thead>
															<tr class=" table-info">
																<td width="5%" class="text-center">Cant</td>
																<td width="50%" class="text-center">Descripción</td>
																<td width="15%" class="text-center">Precio<br>Lista</td>
																<td width="15%" class="text-center">Precio<br>Oferta</td>
																<td width="15%" class="text-center">Total</td>
															</tr>
														</thead>
														<tbody>
															<?php
																/*	BUSCAMOS LOS DATOS DE CADA PRODUCTO SEGUN LA CLAVE TEMPORAL	*/
																$ClaveTemp 	=	$dataCotiza['Clave'];
																$sqlCotiza	=	mysqli_query($MySQLi,"SELECT * FROM ClaveTemporal WHERE Clave='$ClaveTemp' ");
																while ($dataProdTemp = mysqli_fetch_assoc($sqlCotiza)) {
															?>
															<tr>
																<!-- INSERTAMOS LOS VALORES DE CADA PRODUCTO -->
																<td class="text-center"><?php echo $dataProdTemp['Cantidad'] ?></td>
																<?php
																	$idProducto =	$dataProdTemp['idProducto'];
																	$queryProd 	=	mysqli_query($MySQLi,"SELECT * FROM Productos WHERE idProducto='$idProducto' ");
																	$dataProducto=	mysqli_fetch_assoc($queryProd);
																?>
																<td><?php echo $dataProducto['Producto']." / " .$dataProducto['Marca']." / ".$dataProducto['Modelo'] ?></td>
																<td class="text-right">$&nbsp;<?php echo number_format($dataProdTemp['PrecioLista'],2) ?></td>
																<td class="text-right">$&nbsp;<?php echo number_format($dataProdTemp['PrecioOferta'],2) ?></td>
																<td class="text-right">$&nbsp;<?php echo number_format($dataProdTemp['PrecioOferta']*$dataProdTemp['Cantidad'],2) ?></td>
															</tr><?php } ?>

															<!-- 	MOSTRAREMOS EL TOTAL DE LA SUMATORIA DE PRODUCTOS	 -->
															<!-- 	MOSTRAREMOS EL TOTAL DE LA SUMATORIA DE PRODUCTOS	 -->
															<tr>
																<td colspan="3"></td>
																<td class="text-center">TOTAL USD</td><?php
																$sqlCotiza2 =	mysqli_query($MySQLi,"SELECT SUM(Cantidad*PrecioOferta)AS TOTAL FROM ClaveTemporal WHERE Clave='$ClaveTemp' ");
																$dataSQL 	=	mysqli_fetch_assoc($sqlCotiza2); ?>
																<td class="text-right">$ <?php echo number_format($dataSQL['TOTAL'],2) ?></td>
															</tr>
															<tr><?php
																$sqlPrecioDolar = mysqli_query($MySQLi,"SELECT * FROM Ventas WHERE idCotizacion='$idCotizacion' ");
																$dataPrecioDolar= mysqli_fetch_assoc($sqlPrecioDolar);
																$PreDolar_cotiza= $dataPrecioDolar['PrecioDolar'];
																$TotalenBs 			= $dataSQL['TOTAL']*$PreDolar_cotiza; ?>
																<td colspan="3"></td>
																<td class="text-center">TOTAL Bs</td>
																<td class="text-right">Bs <?php echo number_format($TotalenBs,2) ?></td>
															</tr>
														</tbody>
													</table>
													<!-- 	ÁREA DE BOTONES PARA GENERAR NOTA DE ABONO, OBSERVACIONES Y RECIBOS	 -->
													<div class="row text-center"><?php
														/*	OBTENEMOS EL ID DE LA NOTA DE ENTREGA	*/
														$queryNotaE =	mysqli_query($MySQLi,"SELECT idNotaE FROM NotaEntrega WHERE idCotizacion='$idCotizacion' ")or die(mysqli_error($MySQLi));
														$dataNota 	=	mysqli_fetch_assoc($queryNotaE);
														$idNotaE 	=	$dataNota['idNotaE'];//ESTE ES EL ID DE LA NOTA DE ENTREGA

														/*	BUSCAMOS EL ID DEL RECIBO	*/
														$queryRecibo=	mysqli_query($MySQLi,"SELECT idRecibo FROM Recibos WHERE idCotizacion='$idCotizacion' ")or die(mysqli_error($MySQLi));
														$dataRecibo =	mysqli_fetch_assoc($queryRecibo);
														$idRecibo 	=	$dataRecibo['idRecibo'];// ESTE ES EL ID DEL RECIBO 

														/*******************************************************************/
														/******************** ELIMINAR VENTA DIRECTA ***********************/
														/*******************************************************************/
														if ($_SESSION['Rango']=='2') { ?>
															<div class="col">
																<button class="btn btn-danger alertDelVenta" id="<?php echo $idCotizacion ?>" title="Eliminar venta directa (<?php echo $idCotizacion ?>)"><i class="fa fa-trash-alt" style="font-size: 25px"></i></button>
															</div><?php
														} ?>														
														<div class="col">
															<a href="Reportes/pdf.php?notaEntrega=<?php echo $idNotaE ?>">
																<button class="btn btn-primary" title="Descargar Nota de entrega (<?php echo $idNotaE ?>)">
																	<i class="fas fa-download" style="font-size: 25px"></i>
																</button>
															</a>
														</div><!-- AQUÍ TERMINA LA NOTA DE ENTREGA -->

														<div class="col">
															<a href="#observ_N_Entrega" data-toggle="modal">
																<button class="btn btn-success llamarObservNotaEntrega" id="<?php echo $idNotaE ?>" title="Ingresar comentarios a la nota de entrega (<?php echo $idNotaE ?>)">
																	<i class="fas fa-sync fa-spin" style="font-size: 25px"></i>
																</button>
															</a>																	
														</div><?php

														/*******************************************************************/
														/************************ EDITAR RECIBO ****************************/
														/*******************************************************************/
														if ($_SESSION['Rango']=='2') { ?>
															<div class="col">
																<button  class="btn btn-secondary openFormRecibo" id="<?php echo $idRecibo ?>" title="Editar recibo (<?php echo $idRecibo ?>)"><i class="fa fa-edit" style="font-size: 25px"></i></button>
															</div><?php
														} ?>
														<div class="col">
															<a href="Reportes/pdf.php?ReciboCompra=<?php echo $idRecibo ?>">
																<button class="btn btn-danger" title="Descargar Recibo (<?php echo $idRecibo ?>)">
																	<i class="fas fa-download" style="font-size: 25px"></i>
																</button>
															</a>
														</div>
													</div>
												</td>
											</tr><?php $Num++; } ?>
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
									<h4 class="panel-title">COTIZACIONES MODIFICADAS EN <strong><?php echo strtoupper($mes) ?></strong></h4>
									<div class="panel-heading-btn">
										<button class="btn btn-xs btn-primary Buscar"><i class="fa fa-search"> Buscar</i></button>&nbsp;&nbsp;
										<!-- <button class="btn btn-xs btn-primary findCompras"><i class="fa fa-search"> &nbsp;&nbsp;BUSCAR</i></button>&nbsp;&nbsp;&nbsp; -->
										<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
										<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-success" data-click="panel-reload"><i class="fa fa-redo"></i></a>
										<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>
										<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-danger" data-click="panel-remove"><i class="fa fa-times"></i></a>
									</div>
								</div>
								<div class="panel-body">
									<form  data-parsley-validate="true" class="w-75 m-auto d-none" id="buscar" action="?root=directas" method="POST">
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
												<th width="5%" class="text-center">N&ordm;</th>
												<th width="25%" class="text-center">Datos</th>
												<th width="60%" class="text-center">Productos</th>
												<!-- <th width="10%" class="text-center">Acciones</th> -->
											</tr>
										</thead>
										<tbody><?php											
											$Num = 1;
											if ($_SESSION['Rango']=='2') {
												$queryCotiza	=	mysqli_query($MySQLi,"SELECT idCotizacion, Code, Clave, idUser, idCliente, Forma_Pago, DATE_FORMAT(FinFecha_Oferta, '%d de %M, %Y')AS FinFecha_Oferta, Dias_Entrega, Comentarios, Sucursal, DATE_FORMAT(Fecha, '%d de %M, %Y') AS Fecha, DATE_FORMAT(Hora, '%h:%i:%s %p') AS Hora, DATE_FORMAT(Compra, '%d de %M, %Y') AS Compra, Tipo FROM CotMod WHERE Compra BETWEEN '$startBusqueda'AND'$fecha'AND Tipo='1' ORDER BY Compra DESC");
											}else{
												$queryCotiza	=	mysqli_query($MySQLi,"SELECT idCotizacion, Code, Clave, idUser, idCliente, Forma_Pago, DATE_FORMAT(FinFecha_Oferta, '%d de %M, %Y')AS FinFecha_Oferta, Dias_Entrega, Comentarios, Sucursal, DATE_FORMAT(Fecha, '%d de %M, %Y') AS Fecha, DATE_FORMAT(Hora, '%h:%i:%s %p') AS Hora, DATE_FORMAT(Compra, '%d de %M, %Y') AS Compra, Tipo FROM CotMod WHERE idUser='$idUser'AND Compra BETWEEN '$startBusqueda'AND'$fecha'AND Tipo='1' ORDER BY Compra DESC");
											}
											while ($dataCotiza 	=	mysqli_fetch_assoc($queryCotiza)) {
												// 	ESTA ES EL ID DE LA COTIZACION
												$idCotizacion =	$dataCotiza['idCotizacion'];
												$idCliente 		=	$dataCotiza['idCliente'];
												$idVendedor 	=	$dataCotiza['idUser'];?>
											<tr class="odd gradeX">
												<td class="text-center"><?php echo $Num; ?></td><?php
													/*	BUSCAMOS LOS DATOS DEL CLIENTE	*/
													$queryCliente	=	mysqli_query($MySQLi,"SELECT * FROM Clientes WHERE idCliente='$idCliente' ");
													$dataCliente 	=	mysqli_fetch_assoc($queryCliente);
													/*	BUSCAMOS LOS DATOS DEL VENDEDOR	*/
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
													<div class="text-center mt-1 mb-1" style="margin-top: -5%">VENDIDA EL DÍA: <span class="text-danger" style="text-transform: uppercase;"><?php echo $dataCotiza['Compra'] ?></span></div>
													<table class="table table-success table-striped table-bordered table-td-valign-middle w-100">
														<thead>
															<tr class=" table-info">
																<td width="5%" class="text-center">Cant</td>
																<td width="50%" class="text-center">Descripción</td>
																<td width="15%" class="text-center">Precio<br>Lista</td>
																<td width="15%" class="text-center">Precio<br>Oferta</td>
																<td width="15%" class="text-center">Total</td>
															</tr>
														</thead>
														<tbody><?php
															/*	BUSCAMOS LOS DATOS DE CADA PRODUCTO SEGUN LA CLAVE TEMPORAL	*/
															$ClaveTemp 	=	$dataCotiza['Clave'];
															$sqlCotiza	=	mysqli_query($MySQLi,"SELECT * FROM ClaveTempMod WHERE Clave='$ClaveTemp' ");
															while ($dataProdTemp = mysqli_fetch_assoc($sqlCotiza)) { ?>
															<tr>
																<!-- INSERTAMOS LOS VALORES DE CADA PRODUCTO -->
																<td class="text-center"><?php echo $dataProdTemp['Cantidad'] ?></td>
																<?php
																	$idProducto =	$dataProdTemp['idProducto'];
																	$queryProd 	=	mysqli_query($MySQLi,"SELECT * FROM Productos WHERE idProducto='$idProducto' ");
																	$dataProducto=	mysqli_fetch_assoc($queryProd);
																?>
																<td><?php echo $dataProducto['Producto']." / " .$dataProducto['Marca']." / ".$dataProducto['Modelo'] ?></td>
																<td class="text-right">$&nbsp;<?php echo number_format($dataProdTemp['PrecioLista'],2) ?></td>
																<td class="text-right">$&nbsp;<?php echo number_format($dataProdTemp['PrecioOferta'],2) ?></td>
																<td class="text-right">$&nbsp;<?php echo number_format($dataProdTemp['PrecioOferta']*$dataProdTemp['Cantidad'],2) ?></td>
															</tr><?php } ?>

															<!-- 	MOSTRAREMOS EL TOTAL DE LA SUMATORIA DE PRODUCTOS	 -->
															<tr>
																<td colspan="3"></td>
																<td class="text-center">TOTAL USD</td><?php
																$sqlCotiza2 =	mysqli_query($MySQLi,"SELECT SUM(Cantidad*PrecioOferta)AS TOTAL FROM ClaveTempMod WHERE Clave='$ClaveTemp' ");
																$dataSQL 	=	mysqli_fetch_assoc($sqlCotiza2); ?>
																<td class="text-right">$ <?php echo number_format($dataSQL['TOTAL'],2) ?></td>
															</tr>
															<tr><?php
																$sqlPrecioDolar = mysqli_query($MySQLi,"SELECT * FROM VentasModificadas WHERE idCotizacion='$idCotizacion' ");
																$dataPrecioDolar= mysqli_fetch_assoc($sqlPrecioDolar);
																$PreDolar_cotiza= $dataPrecioDolar['PrecioDolar'];
																$TotalenBs 			= $dataSQL['TOTAL']*$PreDolar_cotiza; ?>
																<td colspan="3"></td>
																<td class="text-center">TOTAL Bs</td>
																<td class="text-right">Bs <?php echo number_format($TotalenBs,2) ?></td>
															</tr>
														</tbody>
													</table>
													<!-- 	ÁREA DE BOTONES PARA GENERAR NOTA DE ABONO, OBSERVACIONES Y RECIBOS	 -->
													<div class="row text-center"><?php
														/*	OBTENEMOS EL ID DE LA NOTA DE ENTREGA	*/
														$queryNotaE =	mysqli_query($MySQLi,"SELECT idNotaE FROM NotaEntrega WHERE idCotizacion='$idCotizacion' AND Estado=1 ")or die(mysqli_error($MySQLi));
														$dataNota 	=	mysqli_fetch_assoc($queryNotaE);
														$idNotaE 		=	$dataNota['idNotaE'];//ESTE ES EL ID DE LA NOTA DE ENTREGA

														/*	BUSCAMOS EL ID DEL RECIBO	*/
														$queryRecibo=	mysqli_query($MySQLi,"SELECT idRecibo FROM Recibos WHERE idCotizacion='$idCotizacion' AND Estado=1 ")or die(mysqli_error($MySQLi));
														$dataRecibo =	mysqli_fetch_assoc($queryRecibo);
														$idRecibo 	=	$dataRecibo['idRecibo'];// ESTE ES EL ID DEL RECIBO ?>

														
														<div class="col">
															<a href="Reportes/pdf.php?notaEntrega=<?php echo $idNotaE ?>">
																<button class="btn btn-primary" title="Descargar Nota de entrega (<?php echo $idNotaE ?>)">
																	<i class="fas fa-download" style="font-size: 25px"></i>
																</button>
															</a>
														</div><!-- AQUÍ TERMINA LA NOTA DE ENTREGA -->

														<div class="col">
															<a href="#observ_N_Entrega" data-toggle="modal">
																<button class="btn btn-success llamarObservNotaEntrega" id="<?php echo $idNotaE ?>" title="Ingresar comentarios a la nota de entrega (<?php echo $idNotaE ?>)">
																	<i class="fas fa-sync fa-spin" style="font-size: 25px"></i>
																</button>
															</a>																	
														</div>
														<div class="col">
															<a href="Reportes/pdf.php?ReciboCompra=<?php echo $idRecibo ?>">
																<button class="btn btn-danger" title="Descargar Recibo (<?php echo $idRecibo ?>)">
																	<i class="fas fa-download" style="font-size: 25px"></i>
																</button>
															</a>
														</div>
													</div>
												</td>
											</tr><?php $Num++; } ?>
										</tbody>
									</table>
								</div>
								<!-- end panel-body -->
							</div>
						</div>
					</div><?php
				} ?>
			</div>
			<a href="javascript:;" class="btn btn-icon btn-circle btn-primary btn-scroll-to-top fade" data-click="scroll-top"><i class="fa fa-angle-up"></i></a>
			<?php include 'php/footer.php'; ?>
		</div>
		<?php include 'php/script_directas.php'; ?>		
	</body>
</html>