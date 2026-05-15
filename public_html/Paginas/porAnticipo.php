<?php
	$idUser 		=	$_SESSION['idUser'];
	$ConsltaUser=	mysqli_query($MySQLi,"SELECT * FROM Usuarios WHERE idUser='$idUser' ");
	$datosUser 	=	mysqli_fetch_assoc($ConsltaUser);
	$miCiudad 	=	$datosUser['Ciudad'];?>
<!DOCTYPE html>
<html lang="es">
	<head>
		<title>ANTICIPOS MODIFICADOS</title>
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
			include 'php/left_menu_porAnticipo.php';
			include 'functions/buscarVencidad.php';?>			
			<div id="content" class="content"><div class="respuesta"></div><?php
				if (isset($_POST['inicio'])) { 
					$Inicio 	= $_POST['inicio'];
					$Fin 			=	$_POST['fin']; ?>
					<div class="row tableCotizaciones">
						<div class="col-md-12">
							<div class="panel panel-inverse">
								<div class="panel-heading">
									<h4 class="panel-title">COTIZACIONES POR ANTICIPO DESDE <strong class="text-danger"><?php echo $Inicio ?></strong> HASTA <strong class="text-danger"><?php echo $Fin ?></strong></h4>
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
									<form  data-parsley-validate="true" class="w-75 m-auto" id="buscar" action="?root=anticipo" method="POST">
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
												<th width="30%" class="text-center">Datos</th>
												<th width="65%" class="text-center">Productos</th>
												<th width="10%" class="text-center">Acciones</th>
											</tr>
										</thead>
										<tbody> <?php $Num =	1;
										if ($_SESSION['Rango']==2) {
											$consultaCotizacion	=	mysqli_query($MySQLi,"SELECT idCotizacion, Code, Clave, idUser, idCliente, Forma_Pago, DATE_FORMAT(FinFecha_Oferta, '%d de %M, %Y') AS FinFecha_Oferta, Dias_Entrega, Comentarios, Sucursal, DATE_FORMAT(Fecha, '%d de %M, %Y') AS Fecha, DATE_FORMAT(Hora, '%h:%i:%s %p') AS Hora, DATE_FORMAT(Entregada, '%d de %M, %Y') AS Entregada FROM Cotizaciones WHERE Estado=5 AND Fecha BETWEEN '$Inicio'AND'$Fin' ORDER BY Entregada DESC");
										}else{
											$consultaCotizacion	=	mysqli_query($MySQLi,"SELECT idCotizacion, Code, Clave, idUser, idCliente, Forma_Pago, DATE_FORMAT(FinFecha_Oferta, '%d de %M, %Y') AS FinFecha_Oferta, Dias_Entrega, Comentarios, Sucursal, DATE_FORMAT(Fecha, '%d de %M, %Y') AS Fecha, DATE_FORMAT(Hora, '%h:%i:%s %p') AS Hora, DATE_FORMAT(Entregada, '%d de %M, %Y') AS Entregada FROM Cotizaciones WHERE idUser='$idUser'AND Estado=5 AND Fecha BETWEEN '$Inicio'AND'$Fin' ORDER BY Entregada DESC");
										}
										while ($dataCotizacion = mysqli_fetch_assoc($consultaCotizacion)) { 
											$IDCotiza 	=	$dataCotizacion['idCotizacion']; ?>
										<tr class="odd gradeX">
											<td class="text-center"><?php echo $Num; ?></td><?php
												$idCliente 		=	$dataCotizacion['idCliente'];
												$queryCliente	=	mysqli_query($MySQLi,"SELECT * FROM Clientes WHERE idCliente='$idCliente' ");
												$dataCliente 	=	mysqli_fetch_assoc($queryCliente);
												$idVendedor 	=	$dataCotizacion['idUser'];
												$queryVendedor	=	mysqli_query($MySQLi,"SELECT Nombres, Apellidos, Ciudad, idUser FROM Usuarios WHERE idUser='$idVendedor' ");
												$dataVendedor 	=	mysqli_fetch_assoc($queryVendedor);?>
											<td style="font-size: 10px">
												<table class="table table-success">
													<tr class="table-info">
														<td>CODIGO:</td>
														<th><?php echo $dataCotizacion['Code'] ?></th>
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
															<th><?php echo $dataCotizacion['Forma_Pago'] ?></th>
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
														<th><?php echo $dataCotizacion['Fecha'] ?></th>
													</tr>
													<tr>
														<td>Hora:</td>
														<th><?php echo $dataCotizacion['Hora'] ?></th>
													</tr>
												</table>
											</td>
											<td style="font-size: 12px;">
												<div class="text-center mt-1 mb-1" style="margin-top: -5%">OFERTA VÁLIDA HASTE EL: <span class="text-danger" style="text-transform: uppercase;"><?php echo $dataCotizacion['FinFecha_Oferta'] ?></span></div>
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
														/*	BUSCAMOS EN LA CLAVE TEMPORAL LOS DATOS DEL PRODUCTO	*/
														$ClaveTemp 	=	$dataCotizacion['Clave'];
														$sqlCotiza	=	mysqli_query($MySQLi,"SELECT * FROM ClaveTemporal WHERE Clave='$ClaveTemp' ");
														while ($dataProdTemp = mysqli_fetch_assoc($sqlCotiza)) { ?>
														<tr>
															<td class="text-center"><?php echo $dataProdTemp['Cantidad'] ?></td><?php
																$idProducto =	$dataProdTemp['idProducto'];
																$queryProd 	=	mysqli_query($MySQLi,"SELECT * FROM Productos WHERE idProducto='$idProducto' ");
																$dataProducto=	mysqli_fetch_assoc($queryProd); ?>
															<td><?php echo $dataProducto['Producto']." / " .$dataProducto['Marca']." / ".$dataProducto['Modelo'] ?></td>
															<td class="text-right">$&nbsp;<?php echo number_format($dataProdTemp['PrecioLista'],2) ?></td>
															<td class="text-right">$&nbsp;<?php echo number_format($dataProdTemp['PrecioOferta'],2) ?></td>
															<td class="text-right">$&nbsp;<?php echo number_format($dataProdTemp['PrecioOferta']*$dataProdTemp['Cantidad'],2) ?></td>
														</tr><?php }?>
														<!-- 	AQUI SE MUESTRA EL TOTAL EN $USD DE LA COTIZACION	 -->
														<tr>
															<td colspan="3"></td>
															<td class="text-center">TOTAL USD</td><?php
															$sql_Cotiza	=	mysqli_query($MySQLi,"SELECT SUM(Cantidad*PrecioOferta)AS TOTAL FROM ClaveTemporal WHERE Clave='$ClaveTemp' ");
															$datasql 	=	mysqli_fetch_assoc($sql_Cotiza) ?>
															<td class="text-right">$ <?php echo number_format($datasql['TOTAL'],2) ?></td>
														</tr><?php
														/*	BUSCAMOS SI EL ABONO FUE EN Bs	*/
														$findAbono 	=	mysqli_query($MySQLi,"SELECT Moneda, Total FROM Abonos WHERE idCotizacion='$IDCotiza' LIMIT 0,1 ")or die(mysqli_error($MySQLi)."<br>Error en la línea: ".__LINE__);
														$dataFind	=	mysqli_fetch_assoc($findAbono);
														if ($dataFind['Moneda']=='Bs') { ?>
															<tr>
																<td colspan="3"></td>
																<td class="text-center">TOTAL Bs</td>
																<td class="text-right">Bs <?php echo number_format($dataFind['Total'],2) ?></td>	
															</tr><?php
														}?>
														<!-- 	AQUI ESTAN LAS FUNCIONES DE DESCARGAR EL PDF (RECIBO)	 -->
														<?php
														/*	BUSCAMOS LOS DATOS DEL PRIMER ABONO 	*/
														$sqlAbono 	=	mysqli_query($MySQLi,"SELECT * FROM Abonos WHERE idCotizacion='$IDCotiza' ")or die(mysqli_error($MySQLi));
														$CantRecibos=	mysqli_num_rows($sqlAbono);															
														while ($dataRecibo	=	mysqli_fetch_assoc($sqlAbono)) {
															$idRecibo 		=	$dataRecibo['idRecibo'];
															$idAbono 		=	$dataRecibo['idAbono'];
															echo '
															<tr>
																<td colspan="3" class="text-right">
																	<a href="Reportes/pdf.php?idRecibo='.$idRecibo.'">
																		<button title="Descargar este recibo # '.$idRecibo.'" class="btn btn-xs btn-danger">
																			<i class="fas fa-download f-s-16"></i>
																		</button>
																	</a>&nbsp;&nbsp;';
																	if ($_SESSION['Rango']=='2') {
																		if ($CantRecibos==1) {
																			echo'
																			<a href="#" title="Editar abono (idRecibo: '.$idRecibo.')">
																				<span class="text-danger editarAbono" id="'.$idRecibo .'">Editar Abono</span>
																			</a>';
																		}
																	} echo'
																</td>
																<td class="text-center">Abonó</td>';
																if ($dataRecibo['Moneda']=='USD') {
																	$Abono 	=	$dataRecibo['anticipoUSD'];
																	echo'<td class="text-right">$ '.number_format($Abono,2) .'</td>';
																}else{
																	$Abono 	=	$dataRecibo['porAnticipo'];
																	echo'<td class="text-right">Bs '.number_format($Abono,2) .'</td>';
																} echo'
															</tr>';
														}?>
														<tr>
															<td colspan="3" class="text-right"><?php
																if ($_SESSION['Rango']=='2') {
																	if ($CantRecibos>1) {
																		$AbonoEdit	=	$CantRecibos-1;
																		$buscarAbono=	mysqli_query($MySQLi,"SELECT * FROM Abonos WHERE idCotizacion='$IDCotiza' LIMIT $AbonoEdit,1 ")or die(mysqli_error($MySQLi)."<br>Error en la línea: ".__LINE__);
																		$AbonoEditar=	mysqli_fetch_assoc($buscarAbono);
																		$thisAbonoID=	$AbonoEditar['idAbono'];
																		$thisReciboID=	$AbonoEditar['idRecibo'];
																		echo'
																		<a href="#" title="Editar último abono (idAbono: '.$thisReciboID.')">
																			<span class="text-danger ultimoAbono" id="'.$thisReciboID .'">Editar último Abono</span>
																		</a>';
																	}
																}?>
															</td>
															<td class="text-center text-danger"><strong>RESTA</strong></td><?php
															$search 	=	mysqli_query($MySQLi,"SELECT * FROM Abonos WHERE idCotizacion='$IDCotiza' ");
															$respSearch =	mysqli_num_rows($search);
															if ($respSearch>0) {
																$dataSearch =	mysqli_fetch_assoc($search);
																if ($dataSearch['Moneda']=='USD') {
																	$consult_Abono 	=	mysqli_query($MySQLi,"SELECT SUM(anticipoUSD)AS anticipoUSD, TotalUSD FROM Abonos WHERE idCotizacion='$IDCotiza' ")or die(mysqli_error($MySQLi));
																	$dataAbono 		=	mysqli_fetch_assoc($consult_Abono);
																	$Total 			=	$dataAbono['TotalUSD'];
																	$Abono 			=	$dataAbono['anticipoUSD'];
																	$Resta 			=	$Total-$Abono; ?>
																	<td class="text-right">
																		$ <?php echo number_format($Resta,2) ?>
																	</td><?php
																}else{
																	$consult_Abono 	=	mysqli_query($MySQLi,"SELECT SUM(porAnticipo)AS porAnticipo, Total FROM Abonos WHERE idCotizacion='$IDCotiza' ")or die(mysqli_error($MySQLi));
																	$dataAbono 		=	mysqli_fetch_assoc($consult_Abono);
																	$Total 			=	$dataAbono['Total'];
																	$Abono 			=	$dataAbono['porAnticipo'];
																	$Resta 			=	$Total-$Abono; ?>
																	<td class="text-right">
																		Bs <?php echo number_format($Resta,2) ?>
																	</td><?php
																}
															}else{
																echo '<td class="text-center text-danger">Error</td>';
															}?>
														</tr>
													</tbody>
												</table><?php
												$queryRecibos 	=	mysqli_query($MySQLi,"SELECT * FROM Abonos WHERE idCotizacion='$IDCotiza' ");
												$respConsulta	=	mysqli_num_rows($queryRecibos);
												$Busqueda 		=	$respConsulta-1;
												$buscarRecibo	=	mysqli_query($MySQLi,"SELECT * FROM Abonos WHERE idCotizacion='$IDCotiza' LIMIT $Busqueda,1 ")or die(mysql_error($MySQLi));
												$dataBusqueda 	=	mysqli_fetch_assoc($buscarRecibo);
												$IDAbono 		=	$dataBusqueda['idAbono'];
													//$ultminoSaldo 	=	$dataBusqueda['SaldoActual'];
													//echo $ultminoSaldo; ?>
												<div class="row mt-1">
													<div class="col text-center">
														<button class="btn btn-xs btn-primary plusABONO" title="Agregar abono (idCotizacion: <?php echo $IDCotiza ?>)" id="<?php echo $IDCotiza ?>">
															<i class="fa fa-dollar-sign"></i>&nbsp;&nbsp;AGREGAR ABONO&nbsp;&nbsp;<i class="fa fa-dollar-sign"></i>
														</button>
													</div>
												</div>												
											</td>
										</tr><?php $Num++;} mysqli_close($MySQLi);?>
										</tbody>
									</table>
								</div>
								<!-- end panel-body -->
							</div>
						</div>
					</div><?php
				}else{ ?>
					<div class="row tableCotizaciones">
						<div class="col-md-12">
							<div class="panel panel-inverse">
								<div class="panel-heading">
									<h4 class="panel-title">COTIZACIONES POR ANTICIPO <strong><?php echo strtoupper($mes) ?></strong></h4>
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
									<form  data-parsley-validate="true" class="w-75 m-auto d-none" id="buscar" action="?root=porAnticipo" method="POST">
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
												<th width="30%" class="text-center">Datos</th>
												<th width="65%" class="text-center">Productos</th>
											</tr>
										</thead>
										<tbody> <?php $Num =	1;
										if ($_SESSION['Rango']==2) {
											$consultaCotizacion	=	mysqli_query($MySQLi,"SELECT idCotizacion, Code, Clave, idUser, idCliente, Forma_Pago, DATE_FORMAT(FinFecha_Oferta, '%d de %M, %Y') AS FinFecha_Oferta, Dias_Entrega, Comentarios, Sucursal, DATE_FORMAT(Fecha, '%d de %M, %Y') AS Fecha, DATE_FORMAT(Hora, '%h:%i:%s %p') AS Hora, DATE_FORMAT(Entregada, '%d de %M, %Y') AS Entregada FROM CotMod WHERE Tipo=2 AND Fecha BETWEEN '$startBusqueda'AND'$fecha' ORDER BY Fecha DESC");
										}else{
											$consultaCotizacion	=	mysqli_query($MySQLi,"SELECT idCotizacion, Code, Clave, idUser, idCliente, Forma_Pago, DATE_FORMAT(FinFecha_Oferta, '%d de %M, %Y') AS FinFecha_Oferta, Dias_Entrega, Comentarios, Sucursal, DATE_FORMAT(Fecha, '%d de %M, %Y') AS Fecha, DATE_FORMAT(Hora, '%h:%i:%s %p') AS Hora, DATE_FORMAT(Entregada, '%d de %M, %Y') AS Entregada FROM CotMod WHERE idUser='$idUser'AND Tipo=2 AND Fecha BETWEEN '$startBusqueda'AND'$fecha' ORDER BY Fecha DESC");
										}
										while ($dataCotizacion = mysqli_fetch_assoc($consultaCotizacion)) { 
											$IDCotiza 	=	$dataCotizacion['idCotizacion']; ?>
										<tr class="odd gradeX">
											<td class="text-center"><?php echo $Num; ?></td><?php
												$idCliente 		=	$dataCotizacion['idCliente'];
												$queryCliente	=	mysqli_query($MySQLi,"SELECT * FROM Clientes WHERE idCliente='$idCliente' ");
												$dataCliente 	=	mysqli_fetch_assoc($queryCliente);
												$idVendedor 	=	$dataCotizacion['idUser'];
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
														<th><?php echo $dataCotizacion['Fecha'] ?></th>
													</tr>
													<tr>
														<td>Hora:</td>
														<th><?php echo $dataCotizacion['Hora'] ?></th>
													</tr>
												</table>
											</td>
											<td style="font-size: 12px;">
												<div class="text-center mt-1 mb-1" style="margin-top: -5%">OFERTA VÁLIDA HASTE EL: <span class="text-danger" style="text-transform: uppercase;"><?php echo $dataCotizacion['FinFecha_Oferta'] ?></span></div>
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
														/*	BUSCAMOS EN LA CLAVE TEMPORAL LOS DATOS DEL PRODUCTO	*/
														$ClaveTemp 	=	$dataCotizacion['Clave'];
														$sqlCotiza	=	mysqli_query($MySQLi,"SELECT * FROM ClaveTempMod WHERE Clave='$ClaveTemp' ");
														while ($dataProdTemp = mysqli_fetch_assoc($sqlCotiza)) { ?>
														<tr>
															<td class="text-center"><?php echo $dataProdTemp['Cantidad'] ?></td><?php
																$idProducto =	$dataProdTemp['idProducto'];
																$queryProd 	=	mysqli_query($MySQLi,"SELECT * FROM Productos WHERE idProducto='$idProducto' ");
																$dataProducto=	mysqli_fetch_assoc($queryProd); ?>
															<td><?php echo $dataProducto['Producto']." / " .$dataProducto['Marca']." / ".$dataProducto['Modelo'] ?></td>
															<td class="text-right">$&nbsp;<?php echo number_format($dataProdTemp['PrecioLista'],2) ?></td>
															<td class="text-right">$&nbsp;<?php echo number_format($dataProdTemp['PrecioOferta'],2) ?></td>
															<td class="text-right">$&nbsp;<?php echo number_format($dataProdTemp['PrecioOferta']*$dataProdTemp['Cantidad'],2) ?></td>
														</tr><?php }?>
														<!-- 	AQUI SE MUESTRA EL TOTAL EN $USD DE LA COTIZACION	 -->
														<tr>
															<td colspan="3"></td>
															<td class="text-center">TOTAL USD</td><?php
															$sql_Cotiza	=	mysqli_query($MySQLi,"SELECT SUM(Cantidad*PrecioOferta)AS TOTAL FROM ClaveTempMod WHERE Clave='$ClaveTemp' ");
															$datasql 	=	mysqli_fetch_assoc($sql_Cotiza) ?>
															<td class="text-right">$ <?php echo number_format($datasql['TOTAL'],2) ?></td>
														</tr><?php
														/*	BUSCAMOS SI EL ABONO FUE EN Bs	*/
														$findAbono 	=	mysqli_query($MySQLi,"SELECT Moneda, Total FROM AbonosModificados WHERE idCotizacion='$IDCotiza' LIMIT 0,1 ")or die(mysqli_error($MySQLi)."<br>Error en la línea: ".__LINE__);
														$dataFind	=	mysqli_fetch_assoc($findAbono);
														if ($dataFind['Moneda']=='Bs') { ?>
															<tr>
																<td colspan="3"></td>
																<td class="text-center">TOTAL Bs</td>
																<td class="text-right">Bs <?php echo number_format($dataFind['Total'],2) ?></td>	
															</tr><?php
														}?>
														<!-- 	AQUI ESTAN LAS FUNCIONES DE DESCARGAR EL PDF (RECIBO)	 -->
														<?php
														/*	BUSCAMOS LOS DATOS DEL PRIMER ABONO 	*/
														$sqlAbono 	=	mysqli_query($MySQLi,"SELECT * FROM AbonosModificados WHERE idCotizacion='$IDCotiza' ")or die(mysqli_error($MySQLi));
														$CantRecibos=	mysqli_num_rows($sqlAbono);															
														while ($dataRecibo	=	mysqli_fetch_assoc($sqlAbono)) {
															$idRecibo 		=	$dataRecibo['idRecibo'];
															$idAbono 		=	$dataRecibo['idAbono'];
															echo '
															<tr>
																<td colspan="3" class="text-right">
																	<a href="Reportes/pdf.php?idRecibo='.$idRecibo.'">
																		<button title="Descargar este recibo # '.$idRecibo.'" class="btn btn-xs btn-danger">
																			<i class="fas fa-download f-s-16"></i>
																		</button>
																	</a>&nbsp;&nbsp;
																</td>
																<td class="text-center">Abonó</td>';
																if ($dataRecibo['Moneda']=='USD') {
																	$Abono 	=	$dataRecibo['anticipoUSD'];
																	echo'<td class="text-right">$ '.number_format($Abono,2) .'</td>';
																}else{
																	$Abono 	=	$dataRecibo['porAnticipo'];
																	echo'<td class="text-right">Bs '.number_format($Abono,2) .'</td>';
																} echo'
															</tr>';
														}?>
														<tr>
															<td colspan="3" class="text-right"></td>
															<td class="text-center text-danger"><strong>RESTA</strong></td><?php
															$search 	=	mysqli_query($MySQLi,"SELECT * FROM AbonosModificados WHERE idCotizacion='$IDCotiza' ");
															$respSearch =	mysqli_num_rows($search);
															if ($respSearch>0) {
																$dataSearch =	mysqli_fetch_assoc($search);
																if ($dataSearch['Moneda']=='USD') {
																	$consult_Abono 	=	mysqli_query($MySQLi,"SELECT SUM(anticipoUSD)AS anticipoUSD, TotalUSD FROM AbonosModificados WHERE idCotizacion='$IDCotiza' ")or die(mysqli_error($MySQLi));
																	$dataAbono 		=	mysqli_fetch_assoc($consult_Abono);
																	$Total 			=	$dataAbono['TotalUSD'];
																	$Abono 			=	$dataAbono['anticipoUSD'];
																	$Resta 			=	$Total-$Abono; ?>
																	<td class="text-right">
																		$ <?php echo number_format($Resta,2) ?>
																	</td><?php
																}else{
																	$consult_Abono 	=	mysqli_query($MySQLi,"SELECT SUM(porAnticipo)AS porAnticipo, Total FROM AbonosModificados WHERE idCotizacion='$IDCotiza' ")or die(mysqli_error($MySQLi));
																	$dataAbono 		=	mysqli_fetch_assoc($consult_Abono);
																	$Total 			=	$dataAbono['Total'];
																	$Abono 			=	$dataAbono['porAnticipo'];
																	$Resta 			=	$Total-$Abono; ?>
																	<td class="text-right">
																		Bs <?php echo number_format($Resta,2) ?>
																	</td><?php
																}
															}else{
																echo '<td class="text-center text-danger">Error</td>';
															}?>
														</tr>
													</tbody>
												</table><?php
												$queryRecibos 	=	mysqli_query($MySQLi,"SELECT * FROM Abonos WHERE idCotizacion='$IDCotiza' ");
												$respConsulta	=	mysqli_num_rows($queryRecibos);
												$Busqueda 		=	$respConsulta-1;
												$buscarRecibo	=	mysqli_query($MySQLi,"SELECT * FROM Abonos WHERE idCotizacion='$IDCotiza' LIMIT $Busqueda,1 ")or die(mysql_error($MySQLi));
												$dataBusqueda 	=	mysqli_fetch_assoc($buscarRecibo);
												$IDAbono 		=	$dataBusqueda['idAbono'];
													//$ultminoSaldo 	=	$dataBusqueda['SaldoActual'];
													//echo $ultminoSaldo; ?>
												<div class="row mt-1">
													<div class="col text-center">
														<button class="btn btn-xs btn-primary plusABONO" title="Agregar abono (idCotizacion: <?php echo $IDCotiza ?>)" id="<?php echo $IDCotiza ?>">
															<i class="fa fa-dollar-sign"></i>&nbsp;&nbsp;AGREGAR ABONO&nbsp;&nbsp;<i class="fa fa-dollar-sign"></i>
														</button>
													</div>
												</div>												
											</td>											
										</tr><?php $Num++;} mysqli_close($MySQLi);?>
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
		</div><?php include 'php/script_anticipo.php'; ?>
	</body>
</html>