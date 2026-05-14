<?php
	function aleatorio(){
		$code 	=	uniqid();
		$code 	=	substr($code, -10);
		return $code;
	}
	$alert 			=	aleatorio();
	$claveCotiza=	md5(date("d/m/Y g:i:s").$alert);
	$idUser 		=	$_SESSION['idUser'];
	$ConsltaUser=	mysqli_query($MySQLi,"SELECT * FROM Usuarios WHERE idUser='$idUser' ");
	$datosUser 	=	mysqli_fetch_assoc($ConsltaUser);
	$miCiudad 	=	$datosUser['Ciudad'];
?>
<!DOCTYPE html>
<html lang="es">
	<head>
		<title>COTIZACIONES GENERADAS</title>
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
			include 'php/left_menu_generadas.php';?>			
			<div id="content" class="content"><div class="respuesta"></div>
				<!-- EDITAR TABLA PRODUCTOS -->
				<div class="row d-none editProducto">
					<div class="col-md-2"></div>
					<div class="col-md-8">
						<div class="panel panel-inverse">
							<div class="panel-heading">
								<h4 class="panel-title">EDITAR PRODUCTO</h4>
								<button class="btn btn-xs btn-danger d-none cancelarEditProducto">CANCELAR</button>
							</div>
							<div class="panel-body">
								<div class="row">
									<div class="col-md-4">
										<label for="ClienteProducto_">Producto <span class="text-danger">( * )</span></label>

										<input type="hidden" name="miCiudad" id="miCiudad_" value="<?php echo $miCiudad ?>">
										<input type="hidden" name="ClaveTempCotza" id="ClaveTempCotza">
										<select name="Producto" id="ClienteProducto_" class="form-control">
											<option disabled selected>Seleccione producto</option>
											<?php
												$queryProd_ 	=	mysqli_query($MySQLi,"SELECT * FROM Productos ORDER BY Producto ASC");
												while ($dataProd_=mysqli_fetch_assoc($queryProd_)) {
													echo "<option value=".$dataProd_['idProducto'] .">".$dataProd_['Producto']." / ".$dataProd_['Marca']." / ".$dataProd_['Modelo']."</option>";
												}
											?>
										</select>
										
										<div class="text-danger d-none noSelect_Prod">No ha seleccionado un producto</div>
									</div>
									<div class="col-md-2 d-none datosAdicionalesTemp">
										<label for="ProdExistenciaCB">Cochabamba</label>
										<input type="text" id="ProdExistenciaCB" class="form-control text-center" disabled>
									</div>
									<div class="col-md-2 d-none datosAdicionalesTemp">
										<label for="ProdExistenciaLP">La Paz</label>
										<input type="text" id="ProdExistenciaLP" class="form-control text-center" disabled>
									</div>
									<div class="col-md-2 d-none datosAdicionalesTemp">
										<label for="ProdExistenciaSC">Santa Cruz</label>
										<input type="text" id="ProdExistenciaSC" class="form-control text-center" disabled>
									</div>
									<div class="col-md-2 d-none datosAdicionalesTemp">
										<label for="ProdExistenciaTJ">Tarija</label>
										<input type="text" id="ProdExistenciaTJ" class="form-control text-center" disabled>
									</div>
								</div>
								<div class="row mt-1 d-none datosAdicionalesTemp">
									<div class="col">
										<label for="PrecioLista_">Precio Lista</label>
										<input type="text" name="PrecioLista" id="PrecioLista_" class="form-control" placeholder="Precio de Lista" disabled>
									</div>
									<div class="col d-none datosAdicionalesTemp">
										<label for="CantidadProducto_">Cantidad</label>
										<input type="text" name="Cantidad" id="CantidadProducto_" class="form-control" placeholder="Cantidad">
										<div class="text-danger d-none Cantidad_Empty">No ha indicado una cantidad</div>
									</div>
									<div class="col d-none datosAdicionalesTemp">
										<label for="PrecioEspecial_">Precio Especial</label>
										<input type="text" name="PrecioEspecial" id="Precio_Especial" class="form-control" placeholder="Precio Especial">
										<div class="text-danger d-none emptyPrecio_Esp">No ha indicado el precio especial</div>
									</div>
								</div>
								<div class="row mt-3">
									<div class="col text-right">
										<button title="Agregar producto" class="btn btn-xs btn-info d-none masProdTemp"><i class="fa fa-plus"></i> &nbsp;&nbsp;AGREGAR PRODUCTO &nbsp;<i class="fas fa-spinner fa-pulse d-none efectAddProductEdit"></i></button>
									</div>
								</div>
								<div class="row mt-3">
									<div class="col" id="respuesta_"></div>
								</div>
								<div class="row">
									<div class="col">
										<button class="btn btn-xs btn-success endProcess pull-right" style="letter-spacing: 1px">TERMINAR PROCESO</button>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<!-- EDITAR FORMA DE PAGO, FECHA OFERTA Y OTROS DE TABLA PRODUCTOS -->
				<div class="row d-none editOptionsProd">
					<div class="col-md-2"></div>
					<div class="col-md-8">
						<div class="panel panel-inverse">
							<div class="panel-heading">
								<h4 class="panel-title">EDITAR PRODUCTO</h4>
								<button class="btn btn-xs btn-danger cancelarOtherOptions">CANCELAR</button>
							</div>
							<div class="panel-body">
								<form id="datosComp">									
									<div class="row">
										<div class="col">
											<label for="FormaPago_">Forma de Pago <span class="text-danger">( * )</span></label>
											<input type="hidden" name="action" value="datosComplementarios">
											<input type="hidden" name="Clave" id="Clave_TempCotza">
											<select name="formaPago" id="FormaPago_" class="form-control">
												<option disabled selected>Forma de Pago</option>
												<option value="Efectivo">Efectivo</option>
												<option value="Cheque">Cheque</option>
												<option value="Depósito">Depósito</option>
												<option value="Transferencia">Transferencias</option>
											</select>
											<div class="text-danger d-none empty_FormaPago">No ha indicado la forma de pago</div>
										</div>
										<div class="col">
											<label for="FinOferta_">Fecha Límite de Oferta <span class="text-danger">( * )</span></label>
											<input type="date" name="fechaFin" id="FinOferta_" class="form-control">
											<div class="text-danger d-none empty_FinOferta">No ha indicado la fecha límite</div>
										</div>
										<div class="col">
											<label for="TiempoEntrega_">Tiempo de entrega <span class="text-danger">( * )</span></label>
											<input type="text" name="tiempoEntrega" id="TiempoEntrega_" class="form-control">
											<div class="text-danger d-none empty_TiempoEntrega">No ha indicado tiempo de entrega</div>
										</div>
									</div>
									<div class="row mt-3">
										<div class="col">
											<label for="Observaciones_">Observaciones</label>
											<textarea name="observaciones" id="Observaciones_" class="form-control" placeholder="Observaciones" value=""></textarea>
										</div>
									</div>
									<div class="row mt-3">
										<div class="col">
											<button type="submit" class="btn btn-xs btn-primary form-control updataDataProd">ACTUALIZAR DATOS &nbsp;<i class="fas fa-spinner fa-pulse d-none efectUpdataDatos"></i></button>
										</div>
									</div>
								</form>
							</div>
						</div>
					</div>
				</div><?php
				if (isset($_POST['inicio'])) {
					$Inicio 	= $_POST['inicio'];
					$Fin 			=	$_POST['fin']; ?>
					<!-- TABLA COTIZACIONES -->
					<div class="row tableCotizaciones">
						<div class="col-md-12">
							<div class="panel panel-inverse">
								<div class="panel-heading">
									<h4 class="panel-title">COTIZACIONES GENERADAS DESDE <strong class="text-danger"><?php echo $Inicio ?></strong> HASTA <strong class="text-danger"><?php echo $Fin ?></strong></h4>
									<div class="panel-heading-btn">
										<!-- <button class="btn btn-xs btn-primary Buscar"><i class="fa fa-search"> Buscar</i></button>&nbsp;&nbsp; -->
										<a href="?root=generar">
											<button class="btn btn-xs btn-primary">GENERAR COTIZACION</button>
										</a>&nbsp;&nbsp;&nbsp;
										<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
										<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-success" data-click="panel-reload"><i class="fa fa-redo"></i></a>
										<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>
										<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-danger" data-click="panel-remove"><i class="fa fa-times"></i></a>
									</div>
								</div>
								<div class="panel-body">
									<form  data-parsley-validate="true" class="w-75 m-auto" id="buscar" action="?root=generadas" method="POST">
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
												<th width="10%" class="text-center">Acciones</th>
											</tr>
										</thead>
										<tbody><?php											
											$Num = 1;
											if ($_SESSION['Rango']=='2') {
												$queryCotiza	=	mysqli_query($MySQLi,"SELECT idCotizacion, Code, Clave, idUser, idCliente, Forma_Pago,  DATE_FORMAT(Fecha, '%d de %M, %Y') AS Fecha, DATE_FORMAT(Hora, '%h:%i:%s %p') AS Hora, DATE_FORMAT(FinFecha_Oferta, '%d de %M, %Y') AS FinFecha_Oferta FROM Cotizaciones WHERE Estado=0 AND Fecha BETWEEN'$Inicio'AND'$Fin'  ORDER BY idCotizacion DESC");
											}else{
												$queryCotiza	=	mysqli_query($MySQLi,"SELECT idCotizacion, Code, Clave, idUser, idCliente, Forma_Pago,  DATE_FORMAT(Fecha, '%d de %M, %Y') AS Fecha, DATE_FORMAT(Hora, '%h:%i:%s %p') AS Hora, DATE_FORMAT(FinFecha_Oferta, '%d de %M, %Y') AS FinFecha_Oferta FROM Cotizaciones WHERE idUser='$idUser'AND Estado=0 AND Fecha BETWEEN'$Inicio'AND'$Fin'  ORDER BY idCotizacion DESC");
											}
											while ($dataCotiza = mysqli_fetch_assoc($queryCotiza)) { ?>
												<tr class="odd gradeX">
													<td class="text-center"><?php echo $Num; ?></td><?php
													$idCliente 			=	$dataCotiza['idCliente'];
													$queryCliente		=	mysqli_query($MySQLi,"SELECT * FROM Clientes WHERE idCliente='$idCliente' ");
													$dataCliente 		=	mysqli_fetch_assoc($queryCliente);
													$idVendedor 		=	$dataCotiza['idUser'];
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
														<div class="text-center mb-1">OFERTA VÁLIDA HASTE EL: <span class="text-danger" style="text-transform: uppercase;"><?php echo $dataCotiza['FinFecha_Oferta'] ?></span></div>
														<table class="table table-success table-striped table-bordered table-td-valign-middle w-100 productosCot">
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
																$ClaveTemp 	=	$dataCotiza['Clave'];
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
																	<td class="text-right">$&nbsp;<?php echo number_format($dataProdTemp['PrecioOferta']*$dataProdTemp['Cantidad'],2)  ?></td>
																</tr><?php } 
																	$sqltotal 	=	mysqli_query($MySQLi,"SELECT SUM(Cantidad*PrecioOferta) AS Total FROM ClaveTemporal WHERE Clave='$ClaveTemp' ");
																	$dataTotal 	=	mysqli_fetch_assoc($sqltotal); ?>
																<tr>
																	<td colspan="3"></td>
																	<td class="text-center">Total USD</td>
																	<td class="text-right">$ <?php echo number_format($dataTotal['Total'],2) ?></td>
																</tr>
															</tbody>
														</table>
														<div class="text-center">															
															<a href="" style="letter-spacing: 1px;color: red">
																[<span id="<?php echo $ClaveTemp ?>" class="editaSeccion">EDITA TABLA PRODUCTOS</span>]
															</a>&nbsp;
															<a href="" style="letter-spacing: 1px;color: red">
																[<span id="<?php echo $ClaveTemp ?>" class="editOptions">EDITAR OPCIONES</span>]
															</a>&nbsp;
															<form target="_blank" action="Reportes/pdf.php" method="post" class="mt-1">
																<input type="hidden" name="idReporteCotizacion" value="<?php echo $dataCotiza['idCotizacion'] ?>">
																<button class="btn btn-xs btn-primary">
																	GENERAR <i class="fa fa-file-pdf" style="font-size: 15px"></i> PDF
																</button>
															</form>
														</div>
													</td>
													<td class="text-center">
														<button title="Marcar como cotzación entregada" id="<?php echo $dataCotiza['idCotizacion'] ?>" class="btn btn-xs btn-success cambiarEntregada">
															<i class="fas fa-paper-plane iEntregada" style="font-size: 15px"></i>
														</button>&nbsp;<?php
														if ($dataCliente['Correo']!='') { ?>
															<button class="btn btn-xs btn-danger enviarMail" data-target="#sendMail" data-toggle="modal" title="Enviar Cotizacion por correo" id="<?php echo $dataCotiza['idCotizacion'] ?>">
																<i class="fa fa-envelope" style="font-size: 15px"></i>
															</button><?php
														}
														/*	CONSULTAMOS CUANTOS CORREO SE ENVIARON	*/
														$queryMailSent	=	mysqli_query($MySQLi,"SELECT * FROM Log_Correos WHERE idCliente='$idCliente' AND Tipo='Cotiza' ");
														$dataMailSent 	=	mysqli_num_rows($queryMailSent);
														if ($dataMailSent>0) { ?>
															&nbsp;<button title="Cantidad de cotizaciones enviadas al correo" class="btn btn-xs btn-primary">
																<span style="font-size: 15px"><?php echo $dataMailSent ?></span>
															</button> <?php
														}
														if ($_SESSION['Rango']=='2') {
														 	echo '&nbsp;<button class="btn btn-xs btn-danger delCotizacion" title="Borrar Cotización" id='.$dataCotiza['idCotizacion'].'><i class="fa fa-trash-alt" style="font-size: 15px"></i></button>';
														} ?>														
													</td>
												</tr><?php $Num++;
											} ?>										
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
									<h4 class="panel-title">COTIZACIONES GENERADAS <strong><?php echo strtoupper($mes) ?></strong></h4>
									<div class="panel-heading-btn">
										<button class="btn btn-xs btn-primary Buscar"><i class="fa fa-search"> Buscar</i></button>&nbsp;&nbsp;
										<a href="?root=generar">
											<button class="btn btn-xs btn-primary">GENERAR COTIZACION</button>
										</a>&nbsp;&nbsp;&nbsp;
										<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
										<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-success" data-click="panel-reload"><i class="fa fa-redo"></i></a>
										<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>
										<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-danger" data-click="panel-remove"><i class="fa fa-times"></i></a>
									</div>
								</div>
								<div class="panel-body">
									<form  data-parsley-validate="true" class="w-75 m-auto d-none" id="buscar" action="?root=generadas" method="POST">
										<div class="row mb-2">
											<div class="col text-center">
												<label for="fechaInicio">Fecha de inicio</label>
												<input type="hidden" name="sucursal" value="<?php echo $Sucursal ?>">
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
												<th width="10%" class="text-center">Acciones</th>
											</tr>
										</thead>
										<tbody><?php											
											$Num = 1;
											if ($_SESSION['Rango']=='2') {
												$queryCotiza	=	mysqli_query($MySQLi,"SELECT idCotizacion, Code, Clave, idUser, idCliente, Forma_Pago,  DATE_FORMAT(Fecha, '%d de %M, %Y') AS Fecha, DATE_FORMAT(Hora, '%h:%i:%s %p') AS Hora, DATE_FORMAT(FinFecha_Oferta, '%d de %M, %Y') AS FinFecha_Oferta FROM Cotizaciones WHERE Estado=0 AND Fecha BETWEEN'$startBusqueda'AND'$fecha'  ORDER BY idCotizacion DESC");
											}else{
												$queryCotiza	=	mysqli_query($MySQLi,"SELECT idCotizacion, Code, Clave, idUser, idCliente, Forma_Pago,  DATE_FORMAT(Fecha, '%d de %M, %Y') AS Fecha, DATE_FORMAT(Hora, '%h:%i:%s %p') AS Hora, DATE_FORMAT(FinFecha_Oferta, '%d de %M, %Y') AS FinFecha_Oferta FROM Cotizaciones WHERE idUser='$idUser'AND Estado=0 AND Fecha BETWEEN'$startBusqueda'AND'$fecha'  ORDER BY idCotizacion DESC");
											}
											while ($dataCotiza = mysqli_fetch_assoc($queryCotiza)) { ?>
												<tr class="odd gradeX">
													<td class="text-center"><?php echo $Num; ?></td><?php
													$idCliente 			=	$dataCotiza['idCliente'];
													$queryCliente		=	mysqli_query($MySQLi,"SELECT * FROM Clientes WHERE idCliente='$idCliente' ");
													$dataCliente 		=	mysqli_fetch_assoc($queryCliente);
													$idVendedor 		=	$dataCotiza['idUser'];
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
														<div class="text-center mb-1">OFERTA VÁLIDA HASTE EL: <span class="text-danger" style="text-transform: uppercase;"><?php echo $dataCotiza['FinFecha_Oferta'] ?></span></div>
														<table class="table table-success table-striped table-bordered table-td-valign-middle w-100 productosCot">
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
																$ClaveTemp 	=	$dataCotiza['Clave'];
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
																	<td class="text-right">$&nbsp;<?php echo number_format($dataProdTemp['PrecioOferta']*$dataProdTemp['Cantidad'],2)  ?></td>
																</tr><?php } 
																	$sqltotal 	=	mysqli_query($MySQLi,"SELECT SUM(Cantidad*PrecioOferta) AS Total FROM ClaveTemporal WHERE Clave='$ClaveTemp' ");
																	$dataTotal 	=	mysqli_fetch_assoc($sqltotal); ?>
																<tr>
																	<td colspan="3"></td>
																	<td class="text-center">Total USD</td>
																	<td class="text-right">$ <?php echo number_format($dataTotal['Total'],2) ?></td>
																</tr>
															</tbody>
														</table>														
														<div class="text-center">
															<a href="" style="letter-spacing: 1px;color: red">
																[<span id="<?php echo $ClaveTemp ?>" class="editaSeccion">EDITA TABLA PRODUCTOS</span>]
															</a>&nbsp;
															<a href="" style="letter-spacing: 1px;color: red">
																[<span id="<?php echo $ClaveTemp ?>" class="editOptions">EDITAR OPCIONES</span>]
															</a>&nbsp;
															<form target="_blank" action="Reportes/pdf.php" method="post" class="mt-1">
																<input type="hidden" name="idReporteCotizacion" value="<?php echo $dataCotiza['idCotizacion'] ?>">
																<button class="btn btn-xs btn-primary">
																	GENERAR <i class="fa fa-file-pdf" style="font-size: 15px"></i> PDF
																</button>
															</form>
														</div>
													</td>
													<td class="text-center">
														<button title="Marcar como cotzación entregada" id="<?php echo $dataCotiza['idCotizacion'] ?>" class="btn btn-xs btn-success cambiarEntregada">
															<i class="fas fa-paper-plane iEntregada" style="font-size: 15px"></i>
														</button>&nbsp;<?php
														if ($dataCliente['Correo']!='') { ?>
															<button class="btn btn-xs btn-danger enviarMail" data-target="#sendMail" data-toggle="modal" title="Enviar Cotizacion por correo" id="<?php echo $dataCotiza['idCotizacion'] ?>">
																<i class="fa fa-envelope" style="font-size: 15px"></i>
															</button><?php
														}
														/*	CONSULTAMOS CUANTOS CORREO SE ENVIARON	*/
														$queryMailSent	=	mysqli_query($MySQLi,"SELECT * FROM Log_Correos WHERE idCliente='$idCliente' AND Tipo='Cotiza' ");
														$dataMailSent 	=	mysqli_num_rows($queryMailSent);
														if ($dataMailSent>0) { ?>
															&nbsp;<button title="Cantidad de cotizaciones enviadas al correo" class="btn btn-xs btn-primary">
																<span style="font-size: 15px"><?php echo $dataMailSent ?></span>
															</button> <?php
														}
														if ($_SESSION['Rango']=='2') {
														 	echo '&nbsp;<button class="btn btn-xs btn-danger delCotizacion" title="Borrar Cotización" id='.$dataCotiza['idCotizacion'].'><i class="fa fa-trash-alt" style="font-size: 15px"></i></button>';
														} ?>														
													</td>
												</tr><?php $Num++;
											} ?>										
										</tbody>
									</table>
								</div>
								<!-- end panel-body -->
							</div>
						</div>
					</div><?php
				} ?>				
			</div>
			<!-- Modal envicar cotizacion por correo -->
			<div class="modal fade" id="sendMail">
				<div class="modal-dialog modal-sm">
					<div class="modal-content">
						<div class="modal-header">
							<h4 class="modal-title">Enviar correo</h4>
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
						</div>
						<div class="modal-body">
							<form id="sendMailAndCotiza" action="Reportes/pdf.php" method="POST">
								<div class="row">
									<div class="col">
										<label for="Correo">Correo</label>
										<input type="hidden" name="idCotizacion" id="idCotizaMail">
										<input type="hidden" name="action" value="sendMailCotizacion">
										<input type="hidden" name="sucursal" value="<?php echo $miCiudad ?>">
										<input type="email" name="correo" id="Correo" class="form-control" required="">
									</div>
								</div>
								<div class="row mt-2">
									<div class="col">
										<label for="Asunto">Asunto</label>
										<input type="text" name="asunto" id="Asunto" class="form-control" required="">
									</div>
								</div>
								<div class="row mt-2">
									<div class="col">
										<label for="Mensaje">Mensaje</label>
										<textarea name="mensaje" id="Mensaje" cols="30" rows="5" class="form-control" required=""></textarea>
									</div>
								</div>
								<div class="row mt-2">
									<div class="col">
										<button type="submit" class="btn btn-block btn-primary tbn-change">Enviar Cotización &nbsp;<i class="fas fa-spinner fa-pulse d-none btn-sendCotiza"></i></button>
									</div>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
			<!-- Modal editar producto temporal -->
			<div class="modal fade" id="editProdTemp_">
				<div class="modal-dialog modal-sm">
					<div class="modal-content">
						<div class="modal-header">
							<h4 class="modal-title">Editar Producto</h4>
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
						</div>
						<div class="modal-body">
							<div class="row text-center">
								<div class="col">
									<label for="CantidadProdTemp_">Cantidad</label>
									<input type="hidden" name="ClaveTemp" id="Clave_Temp_">
									<input type="hidden" name="id" id="idProdTemp_">
									<input type="text" name="Cantidad" id="CantidadProdTemp_" class="form-control">
								</div>
							</div>
							<div class="row mt-2 text-center">
								<div class="col">
									<label for="PrecioProdTemp_">Precio Especial</label>
									<input type="text" name="PrecioEspecial" id="PrecioProdTemp_" class="form-control">
								</div>
							</div>
							<div class="row mt-2">
								<div class="col">
									<button class="btn btn-xs btn-info form-control actualizarProductoTemp_">ACTUALIZAR PRODUCTO</button>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<!-- Modal ecitar producto temporal (Cantidad y Precio)-->
			<div class="modal fade" id="editProdTemp">
				<div class="modal-dialog modal-sm">
					<div class="modal-content">
						<!-- <div class="modal-header">
							<h4 class="modal-title">Editar Datos del Producto</h4>
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
						</div> -->
						<div class="modal-body">
							<div class="row text-center">
								<div class="col">
									<label for="CantidadProdTemp">Cantidad</label>
									<input type="hidden" name="ClaveTemp" id="Clave_Temp">
									<input type="hidden" name="id" id="idProdTemp">
									<input type="text" name="Cantidad" id="CantidadProdTemp" class="form-control text-center">
								</div>
							</div>
							<div class="row mt-2 text-center">
								<div class="col">
									<label for="PrecioProdTemp">Precio Especial</label>
									<input type="text" name="PrecioEspecial" id="PrecioProdTemp" class="form-control text-center">
								</div>
							</div>
							<div class="row mt-2">
								<div class="col">
									<button class="btn btn-xs btn-info form-control actualizarProductoTemp_">ACTUALIZAR PRODUCTO</button>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>			
			<a href="javascript:;" class="btn btn-icon btn-circle btn-primary btn-scroll-to-top fade" data-click="scroll-top"><i class="fa fa-angle-up"></i></a>
			<?php include 'php/footer.php'; ?>
		</div>
		<?php include 'php/script_generadas.php'; ?>
		<script type="text/javascript">
			$("#sendMailAndCotiza").submit(function() {
				$(".btn-sendCotiza").removeClass('d-none');
				$(".tbn-change").attr('disabled', true);
			});
		</script>
	</body>
</html>