<?php
	include 'includes/conexion.php';
	include 'includes/date.class.php';
	mysqli_query($MySQLi,"SET lc_time_names= 'es_BO' ");
	$idUser 	=	$_SESSION['idUser'];
	$ConsltaUser=	mysqli_query($MySQLi,"SELECT * FROM Usuarios WHERE idUser='$idUser' ");
	$datosUser 	=	mysqli_fetch_assoc($ConsltaUser);
	$miCiudad 	=	$datosUser['Ciudad'];
	if (isset($_POST['idCliente'])) { 
		$idCliente 	=	$_POST['idCliente']; ?>
		<!DOCTYPE html>
		<html lang="es">
			<head>
				<title>HISTORIAL DE COMPRAS</title>
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
										<?php
											$queryCliente	=	mysqli_query($MySQLi,"SELECT Nombres, Apellidos FROM Clientes WHERE idCliente='$idCliente' ");
											$dataCliente 	=	mysqli_fetch_assoc($queryCliente);
											$fullNameCliente=	$dataCliente['Nombres']." ".$dataCliente['Apellidos'];
										?>
										<h4 class="panel-title">HISTORIAL DE COMPRAS DEL CLIENTE &nbsp;&nbsp;<strong style="letter-spacing: 1px"><?php echo strtoupper($fullNameCliente) ?></strong></h4>
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
												<tr class="table-success">
													<th width="5%" class="text-center">N&ordm;</th>
													<th width="25%" class="text-center">Datos</th>
													<th width="60%" class="text-center">Productos</th>
													<!-- <th width="10%" class="text-center">Acciones</th> -->
												</tr>
											</thead>
											<tbody>
												<?php											
													$Num = 1;
													if ($_SESSION['Rango']=='2') {
														$queryCotiza	=	mysqli_query($MySQLi,"SELECT idCotizacion, Code, Clave, idUser, idCliente, Forma_Pago, DATE_FORMAT(FinFecha_Oferta, '%d de %M, %Y')AS FinFecha_Oferta, Dias_Entrega, Comentarios, Sucursal, DATE_FORMAT(Fecha, '%d de %M, %Y') AS Fecha, DATE_FORMAT(Hora, '%h:%i:%s %p') AS Hora, DATE_FORMAT(Compra, '%d de %M, %Y') AS Compra FROM Cotizaciones WHERE idCliente='$idCliente' ORDER BY Fecha ASC");
														while ($dataCotiza 	=	mysqli_fetch_assoc($queryCotiza)) {
															$idCotizacion 	= 	$dataCotiza['idCotizacion'];?>
															<tr class="odd gradeX">
																<td class="text-center"><?php echo $Num; ?></td>
																<?php
																	$idCliente 		=	$dataCotiza['idCliente'];
																	$queryCliente	=	mysqli_query($MySQLi,"SELECT * FROM Clientes WHERE idCliente='$idCliente' ");
																	$dataCliente 	=	mysqli_fetch_assoc($queryCliente);

																	$idVendedor 	=	$dataCotiza['idUser'];
																	$queryVendedor	=	mysqli_query($MySQLi,"SELECT Nombres, Apellidos, Ciudad, idUser FROM Usuarios WHERE idUser='$idVendedor' ");
																	$dataVendedor 	=	mysqli_fetch_assoc($queryVendedor);
																?>
																<td style="font-size: 10px">
																	<table class="table table-success">
																		<tr class="table-info">
																			
																			<td>Cliente:</td>
																			<th><?php echo $dataCliente['Nombres']." ".$dataCliente['Apellidos'] ?></th>
																		</tr>
																		<tr">
																			<td>Empresa:</td>
																			<th><?php echo $dataCliente['Empresa'] ?></th>
																		</tr>
																		<tr>
																			<td>Correo:</td>
																			<th><?php echo $dataCliente['Correo'] ?></th>
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
																				<td width="15%" class="text-center">Pre_Lista</td>
																				<td width="15%" class="text-center">Pre_Ofer</td>
																				<td width="15%" class="text-center">Total</td>
																			</tr>
																		</thead>
																		<tbody>
																			<?php
																				$ClaveTemp 	=	$dataCotiza['Clave'];
																				$sqlCotiza	=	mysqli_query($MySQLi,"SELECT * FROM ClaveTemporal WHERE Clave='$ClaveTemp' ");
																				while ($dataProdTemp = mysqli_fetch_assoc($sqlCotiza)) {
																			?>
																			<tr>
																				<td class="text-center"><?php echo $dataProdTemp['Cantidad'] ?></td>
																				<?php
																					$idProducto =	$dataProdTemp['idProducto'];
																					$queryProd 	=	mysqli_query($MySQLi,"SELECT * FROM Productos WHERE idProducto='$idProducto' ");
																					$dataProducto=	mysqli_fetch_assoc($queryProd);
																				?>
																				<td><?php echo $dataProducto['Producto']." / " .$dataProducto['Marca']." / ".$dataProducto['Modelo'] ?></td>
																				<td>$&nbsp;<?php echo $dataProdTemp['PrecioLista'] ?></td>
																				<td>$&nbsp;<?php echo $dataProdTemp['PrecioOferta'] ?></td>
																				<td>$&nbsp;<?php echo $dataProdTemp['PrecioOferta']*$dataProdTemp['Cantidad'] ?></td>
																			</tr><?php } ?>
																			<tr>
																				<td colspan="3"></td>
																				<td class="text-center">TOTAL </td><?php
																				$sqlCotiza2 =	mysqli_query($MySQLi,"SELECT SUM(Cantidad*PrecioOferta)AS TOTAL FROM ClaveTemporal WHERE Clave='$ClaveTemp' ");
																				$dataSQL 	=	mysqli_fetch_assoc($sqlCotiza2); ?>
																				<td class="text-left">$ <?php echo $dataSQL['TOTAL'] ?></td>
																			</tr>
																		</tbody>
																	</table>												
																	<div class="row text-center">
																		<?php
																			$queryNotaE =	mysqli_query($MySQLi,"SELECT idNotaE FROM NotaEntrega WHERE idCotizacion='$idCotizacion' ")or die(mysqli_error($MySQLi));
																			$dataNota 	=	mysqli_fetch_assoc($queryNotaE);
																			$idNotaE 	=	$dataNota['idNotaE'];

																			$queryRecibo=	mysqli_query($MySQLi,"SELECT idRecibo FROM Recibos WHERE idCotizacion='$idCotizacion' ")or die(mysqli_error($MySQLi));
																			$dataRecibo =	mysqli_fetch_assoc($queryRecibo);
																			$idRecibo 	=	$dataRecibo['idRecibo'];
																		?>
																		<div class="col">
																			<a href="Reportes/pdf.php?notaEntrega=<?php echo $idNotaE ?>">
																				<button class="btn btn-primary" title="Descargar Nota de entrega">
																					<i class="fas fa-download" style="font-size: 25px"></i>
																				</button>
																			</a>																	
																		</div>																
																		<div class="col">
																			<a href="#observ_N_Entrega" data-toggle="modal">
																				<button class="btn btn-success llamarObservNotaEntrega" id="<?php echo $idNotaE ?>" title="Ingresar comentarios a la nota de entrega (<?php echo $idNotaE ?>)">
																					<i class="fas fa-sync fa-spin" style="font-size: 25px"></i>
																				</button>
																			</a>																	
																		</div>
																		<div class="col">
																			<a href="Reportes/pdf.php?ReciboCompra=<?php echo $idRecibo ?>">
																				<button class="btn btn-danger" title="Descargar Recibo">
																					<i class="fas fa-download" style="font-size: 25px"></i>
																				</button>
																			</a>
																		</div>
																	</div>
																</td>
																<!-- <td class="text-center">															
																	<button title="Marcar como comprada" id="<?php //echo $dataCotiza['idCotizacion'] ?>" class="btn btn-xs btn-success cambiarEntregada">
																		<i class="fa fa-dollar-sign" style="font-size: 15px"></i>
																	</button>&nbsp;
																	<button title="Enviar por correo" id="<?php //echo $dataCotiza['idProducto'] ?>" class="btn btn-xs btn-primary enviarEmail">
																		<i class="fa fa-envelope" style="font-size: 15px"></i>
																	</button>
																	<button title="Generar PDF" id="<?php //echo $dataCotiza['idProducto'] ?>" class="btn btn-xs btn-warning generaPDF">
																		<i class="fa fa-file-pdf" style="font-size: 15px"></i>
																	</button>&nbsp;
																</td> -->
															</tr><?php $Num++; 
														} mysqli_close($MySQLi);
													}else{
														$queryCotiza	=	mysqli_query($MySQLi,"SELECT idCotizacion, Code, Clave, idUser, idCliente, Forma_Pago, DATE_FORMAT(FinFecha_Oferta, '%d de %M, %Y')AS FinFecha_Oferta, Dias_Entrega, Comentarios, Sucursal, DATE_FORMAT(Fecha, '%d de %M, %Y') AS Fecha, DATE_FORMAT(Hora, '%h:%i:%s %p') AS Hora, DATE_FORMAT(Compra, '%d de %M, %Y') AS Compra FROM Cotizaciones WHERE idUser='$idUser'AND idCliente='$idCliente' ORDER BY Fecha ASC");
														while ($dataCotiza 	=	mysqli_fetch_assoc($queryCotiza)) {
															$idCotizacion 	= 	$dataCotiza['idCotizacion'];?>
															<tr class="odd gradeX">
																<td class="text-center"><?php echo $Num; ?></td>
																<?php
																	$idCliente 		=	$dataCotiza['idCliente'];
																	$queryCliente	=	mysqli_query($MySQLi,"SELECT * FROM Clientes WHERE idCliente='$idCliente' ");
																	$dataCliente 	=	mysqli_fetch_assoc($queryCliente);

																	$idVendedor 	=	$dataCotiza['idUser'];
																	$queryVendedor	=	mysqli_query($MySQLi,"SELECT Nombres, Apellidos, Ciudad, idUser FROM Usuarios WHERE idUser='$idVendedor' ");
																	$dataVendedor 	=	mysqli_fetch_assoc($queryVendedor);
																?>
																<td style="font-size: 10px">
																	<table class="table table-success">
																		<tr class="table-info">
																			
																			<td>Cliente:</td>
																			<th><?php echo $dataCliente['Nombres']." ".$dataCliente['Apellidos'] ?></th>
																		</tr>
																		<tr">
																			<td>Empresa:</td>
																			<th><?php echo $dataCliente['Empresa'] ?></th>
																		</tr>
																		<tr>
																			<td>Correo:</td>
																			<th><?php echo $dataCliente['Correo'] ?></th>
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
																				<td width="15%" class="text-center">Pre_Lista</td>
																				<td width="15%" class="text-center">Pre_Ofer</td>
																				<td width="15%" class="text-center">Total</td>
																			</tr>
																		</thead>
																		<tbody>
																			<?php
																				$ClaveTemp 	=	$dataCotiza['Clave'];
																				$sqlCotiza	=	mysqli_query($MySQLi,"SELECT * FROM ClaveTemporal WHERE Clave='$ClaveTemp' ");
																				while ($dataProdTemp = mysqli_fetch_assoc($sqlCotiza)) {
																			?>
																			<tr>
																				<td class="text-center"><?php echo $dataProdTemp['Cantidad'] ?></td>
																				<?php
																					$idProducto =	$dataProdTemp['idProducto'];
																					$queryProd 	=	mysqli_query($MySQLi,"SELECT * FROM Productos WHERE idProducto='$idProducto' ");
																					$dataProducto=	mysqli_fetch_assoc($queryProd);
																				?>
																				<td><?php echo $dataProducto['Producto']." / " .$dataProducto['Marca']." / ".$dataProducto['Modelo'] ?></td>
																				<td>$&nbsp;<?php echo $dataProdTemp['PrecioLista'] ?></td>
																				<td>$&nbsp;<?php echo $dataProdTemp['PrecioOferta'] ?></td>
																				<td>$&nbsp;<?php echo $dataProdTemp['PrecioOferta']*$dataProdTemp['Cantidad'] ?></td>
																			</tr><?php } ?>
																			<tr>
																				<td colspan="3"></td>
																				<td class="text-center">TOTAL </td><?php
																				$sqlCotiza2 =	mysqli_query($MySQLi,"SELECT SUM(Cantidad*PrecioOferta)AS TOTAL FROM ClaveTemporal WHERE Clave='$ClaveTemp' ");
																				$dataSQL 	=	mysqli_fetch_assoc($sqlCotiza2); ?>
																				<td class="text-left">$ <?php echo $dataSQL['TOTAL'] ?></td>
																			</tr>
																		</tbody>
																	</table>												
																	<div class="row text-center">
																		<?php
																			$queryNotaE =	mysqli_query($MySQLi,"SELECT idNotaE FROM NotaEntrega WHERE idCotizacion='$idCotizacion' ")or die(mysqli_error($MySQLi));
																			$dataNota 	=	mysqli_fetch_assoc($queryNotaE);
																			$idNotaE 	=	$dataNota['idNotaE'];

																			$queryRecibo=	mysqli_query($MySQLi,"SELECT idRecibo FROM Recibos WHERE idCotizacion='$idCotizacion' ")or die(mysqli_error($MySQLi));
																			$dataRecibo =	mysqli_fetch_assoc($queryRecibo);
																			$idRecibo 	=	$dataRecibo['idRecibo'];
																		?>
																		<div class="col">
																			<a href="Reportes/pdf.php?notaEntrega=<?php echo $idNotaE ?>">
																				<button class="btn btn-primary" title="Descargar Nota de entrega">
																					<i class="fas fa-download" style="font-size: 25px"></i>
																				</button>
																			</a>																	
																		</div>																
																		<div class="col">
																			<a href="#observ_N_Entrega" data-toggle="modal">
																				<button class="btn btn-success llamarObservNotaEntrega" id="<?php echo $idNotaE ?>" title="Ingresar comentarios a la nota de entrega (<?php echo $idNotaE ?>)">
																					<i class="fas fa-sync fa-spin" style="font-size: 25px"></i>
																				</button>
																			</a>																	
																		</div>
																		<div class="col">
																			<a href="Reportes/pdf.php?ReciboCompra=<?php echo $idRecibo ?>">
																				<button class="btn btn-danger" title="Descargar Recibo">
																					<i class="fas fa-download" style="font-size: 25px"></i>
																				</button>
																			</a>
																		</div>
																	</div>
																</td>
																<!-- <td class="text-center">															
																	<button title="Marcar como comprada" id="<?php //echo $dataCotiza['idCotizacion'] ?>" class="btn btn-xs btn-success cambiarEntregada">
																		<i class="fa fa-dollar-sign" style="font-size: 15px"></i>
																	</button>&nbsp;
																	<button title="Enviar por correo" id="<?php //echo $dataCotiza['idProducto'] ?>" class="btn btn-xs btn-primary enviarEmail">
																		<i class="fa fa-envelope" style="font-size: 15px"></i>
																	</button>
																	<button title="Generar PDF" id="<?php //echo $dataCotiza['idProducto'] ?>" class="btn btn-xs btn-warning generaPDF">
																		<i class="fa fa-file-pdf" style="font-size: 15px"></i>
																	</button>&nbsp;
																</td> -->
															</tr><?php $Num++; 
														} mysqli_close($MySQLi);
													}											
												?>										
											</tbody>
										</table>
									</div>
									<!-- end panel-body -->
								</div>
							</div>
						</div>
					</div>
					<a href="javascript:;" class="btn btn-icon btn-circle btn-primary btn-scroll-to-top fade" data-click="scroll-top"><i class="fa fa-angle-up"></i></a>
					<?php include 'php/footer.php'; ?>
				</div>
				<?php include 'php/script_compradas.php'; ?>
			</body>
		</html>
		<!-- AGREGAR COMENTARIOS A LA NOTA DE ENTREGA -->
		<div class="modal fade" id="observ_N_Entrega">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<h4 class="modal-title">NOTA DE ENTREGA</h4>
					</div>
					<div class="modal-body">
						<form id="NotaComments" data-parsley-validate="true">
							<div class="row text-center">
								<div class="col">
									<input type="hidden" name="action" value="GuardarObservacionenNotadeEntrega">
									<input type="hidden" name="idNotaEntrega" id="idNotaEntrega">
									<label for="ObservNotaEntrega">Observaciones</label>
									<textarea name="observaciones" id="ObservNotaEntrega" cols="10" rows="4" class="form-control" data-parsley-required="true"></textarea>
									<div class="text-center text-danger d-none noObsv">CAMPO OBSERVACIONES ESTÁ VACÍO</div>
								</div>
							</div>
							<div class="row mt-3 text-center d-none actualizarObv">
								<div class="col">
									<button class="btn btn-xs form-control btn-success saveObsv">ACTUALIZAR OBSERVACIONES</button>
								</div>
							</div>
							<div class="row mt-3 text-center d-none guardarObv">
								<div class="col">
									<button class="btn btn-xs form-control btn-info saveObsv">GUARDAR OBSERVACIONES</button>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div><?php
	}else{ ?>
		<script type="text/javascript">
			location.replace("?root=404");
		</script><?php
	}
?>