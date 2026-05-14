<?php
	$idUser 		=	$_SESSION['idUser'];
	$ConsltaUser=	mysqli_query($MySQLi,"SELECT * FROM Usuarios WHERE idUser='$idUser' ");
	$datosUser 	=	mysqli_fetch_assoc($ConsltaUser);
	$miCiudad 	=	$datosUser['Ciudad'];?>
<!DOCTYPE html>
<html lang="es">
	<head>
		<title>MIS CLIENTES</title>
		<?php include 'php/meta.php'; ?>
		<link href="assets/css/apple/app.min.css" rel="stylesheet">
		<link href="assets/plugins/ionicons/css/ionicons.min.css" rel="stylesheet">
		<link href="assets/plugins/datatables.net-bs4/css/dataTables.bootstrap4.min.css" rel="stylesheet">
		<link href="assets/plugins/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css" rel="stylesheet">
		<link href="assets/plugins/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css" rel="stylesheet">
		<link href="assets/plugins/datatables.net-bs4/css/dataTables.bootstrap4.min.css" rel="stylesheet">
		<link href="assets/plugins/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css" rel="stylesheet">
		<link href="assets/plugins/summernote/dist/summernote.css" rel="stylesheet">
	</head>
	<body>
		<?php include 'php/loader.php'; ?>
		<div id="page-container" class="fade page-sidebar-fixed page-header-fixed">
			<?php
				include 'php/top_menu.php';
				include 'php/left_menu_misclientes.php';
			?>
			<div id="content" class="content"><div class="respuesta"></div>

				<div class="row d-none SendMail">
					<div class="col-md-12">
						<div class="panel panel-success">
							<div class="panel-heading">
								<h4 class="panel-title">ENVIAR CORREO A: <span id="mailCliente" style="font-size: 16px"></span></h4>
								<button class="btn btn-xs btn-danger cancelarSendMailCliente">CANCELAR</button>
							</div>
							<div class="panel-body">
								<form id="sendmail">
									<div class="row">
										<div class="col">
											<input type="hidden" name="action" value="EnviarCorreoalCliente">
											<input type="hidden" name="idCliente" id="idClienteMail">
											<input type="hidden" name="Correo" id="CorreoCliente">
											<input type="hidden" name="miCiudad" value="<?php echo $miCiudad ?>">
											<input type="text" name="Nombre" id="NombreClienteMail" class="form-control" placeholder="Nombre Cliente">
											<div class="text-center text-danger d-none noNameMail">No ha ingresado un nombre</div>
										</div>
										<div class="col">
											<input type="text" name="Empresa" id="EmpresaClienteMail" class="form-control" placeholder="Nombre Empresa">
										</div>										
									</div>
									<div class="row mt-3">
										<div class="col">
											<input type="text" name="Asunto" id="AsuntoMail" class="form-control" placeholder="Asuto">
											<div class="text-center text-danger d-none noAsuntoMail">No ha ingresado un ASUNTO</div>
										</div>
										<div class="col">
											<select name="Plantilla" id="PlantiilaMail" class="form-control">
												<?php
													$queryPlantilla	=	mysqli_query($MySQLi,"SELECT * FROM Plantilla_Email WHERE Estado=1 ORDER BY Nombre ASC");
													$resultPlantilla=	mysqli_num_rows($queryPlantilla);
													if ($resultPlantilla>0) {
														echo "<option selected>Seleccione plantilla</option>";
														while ($dataPlantilla = mysqli_fetch_assoc($queryPlantilla)) {
															echo "<option value=".$dataPlantilla['id'].">".$dataPlantilla['Nombre']."</option>";
														}
													}else{
														echo "<option selected disabled>No hay plantillas disponibles</option>";
													}
												?>
											</select>
											<div class="text-center text-danger d-none noPlantillaMail">No se ha seleccionado una plantilla</div>
										</div>
										<?php
											if ($_SESSION['Rango']=='2') { ?>
												<div class="col">
													<select name="remitente" id="remitente" class="form-control">
														<option selected disabled>Seleccione remitente</option>
														<option value="ventascbba@yuliimport.com">Ventas Cochabamba</option>
														<option value="ventasscz@yuliimport.com">Ventas Santa Cruz</option>
														<option value="ventaslpz@yuliimport.com">Ventas La Paz</option>
														<option value="ventastarija@yuliimport.com">Ventas Tarija</option>
														<option value="administracion@yuliimport.com">Administración</option>
													</select>
													<div class="text-center text-danger d-none noRemitenteMail">No se ha seleccionado un remitente</div>
												</div><?php
											}
										?>
									</div>
									<div class="row mt-3 MsjMail">
										<div class="col">
											<textarea class="summernote" name="Mensaje" id="MensajeSumerNote"></textarea>
										</div>
										<div class="text-center text-danger d-none noContenidoMail">No hay contendido en el correo a enviar</div>
									</div>
									<div class="row mt-3">
										<div class="col">
											<button class="btn btn-xs btn-primary form-control enviaMailToCliente">ENVIAR CORREO &nbsp;<i class="fas d-none fa-spinner fa-pulse efectSpinner"></i></button>
										</div>
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>

				<div class="row d-none formEditCliente">
					<div class="col-md-2"></div>
					<div class="col-md-8">
						<div class="panel panel-primary">
							<div class="panel-heading">
								<h4 class="panel-title">EDITAR CLIENTE</h4>
								<button class="btn btn-xs btn-danger cancelarEditCliente">CANCELAR</button>
							</div>
							<div class="panel-body">
								<form id="editCliente">
									<div class="row  w-25 m-auto text-center">
										<div class="col">
											<label for="fechaRegistro_">Fecha de Registro</label>
											<input type="date" name="fechaRegistro" id="fechaRegistro_" class="form-control">
										</div>
									</div>
									<div class="row mt-3">
										<div class="col">
											<label for="ClinteNombres_">Nombres <span class="text-danger">*</span></label>
											<input type="hidden" name="action" value="ActualizarMiCliente">
											<input type="hidden" name="idCliente" id="idCliente">
											<input type="text" name="Nombres" id="ClinteNombres_" class="form-control" placeholder="Nombres">
											<div class="invalid-feedback">Campo nombre está vacío</div>
										</div>
										<div class="col">
											<label for="ClienteApellidos_">Apellidos <span class="text-danger">*</span></label>
											<input type="text" name="Apellidos" id="ClienteApellidos_" class="form-control" placeholder="Apellidos">
											<div class="invalid-feedback">Campo apellido está vacío</div>
										</div>
									</div>
									<div class="row mt-3">
										<div class="col">
											<label for="ClienteCell_">Teléfono celular</label>
											<input type="text" name="Celular" id="ClienteCell_" class="form-control" placeholder="Celular">
											<div class="invalid-feedback">Campo celular está vacío</div>
											<div class="text-center text-danger d-none isNaNCell_">Debe ingresar números sin espacios</div>
										</div>
										<div class="col">
											<label for="ClienteOtro_">Teléfono fijo</label>
											<input type="text" name="Otro" id="ClienteOtro_" class="form-control" placeholder="Otro">
											<div class="invalid-feedback">Campo otro está vacío</div>
											<div class="text-center text-danger d-none isNaNOtro_">Debe ingresar números sin espacios</div>
										</div>
									</div>
									<div class="row mt-3">
										<div class="col">
											<label for="ClienteCorreo_">Correo</label>
											<input type="email" name="Correo" id="ClienteCorreo_" class="form-control" placeholder="Correo">
											<div class="invalid-feedback">Campo correo está vacío</div>
										</div>
										<div class="col">
											<label for="ClienteCiudad_">Ciudad</label>
											<select name="Ciudad" id="ClienteCiudad_" class="form-control">
												<option selected="" disabled="">Seleccione Ciudad</option>
												<option value="Beni">Beni</option>
												<option value="Cochabamba">Cochabamba</option>
												<option value="Chuquiasca">Chuquiasca</option>
												<option value="La Paz">La Paz</option>
												<option value="Oruro">Oruro</option>
												<option value="Pando">Pando</option>
												<option value="Potosí">Potosí</option>
												<option value="Santa Cruz">Santa Cruz</option>
												<option value="Tarija">Tarija</option>
											</select>
											<div class="text-center text-danger d-none emptyClienteCiudad_">No ha seleccionado una opción</div>
										</div>
									</div>
									<div class="row mt-3">
										<div class="col">
											<label for="ClienteEmpresa_">Empresa</label>
											<input type="text" name="Empresa" id="ClienteEmpresa_" class="form-control" placeholder="Empresa">
											<div class="invalid-feedback">Campo empresa está vacío</div>
										</div>
										<div class="col">
											<label for="ClienteNIT_">NIT</label>
											<input type="text" name="NIT" id="ClienteNIT_" class="form-control" placeholder="NIT">
											<div class="invalid-feedback">Campo NIT está vacío</div>
										</div>
									</div>									
									
									<div class="row mt-3">
										<div class="col">
											<label for="ClienteDireccion_">Dirección</label>
											<textarea name="Direccion" id="ClienteDireccion_" cols="30" rows="4" class="form-control" placeholder="Dirección"></textarea>
											<div class="invalid-feedback">Campo dirección está vacío</div>
										</div>
										<div class="col">
											<label for="ClienteComentarios_">Comentarios</label>
											<textarea name="Comentarios" id="ClienteComentarios_" cols="30" rows="4" class="form-control" placeholder="Comentarios"></textarea>
											<div class="invalid-feedback">Campo observaciones está vacío</div>
										</div>
									</div>
									<div class="row mt-3">
										<div class="col">
											<div class="text-center text-danger mb-2 f-s-16 d-none noPhoneNum_">Debe ingresar al menos un número de teléfono</div>
											<button type="submit" class="btn btn-xs btn-success form-control upDataCliente">ACTUALIZAR CLIENTE &nbsp;<i class="fas d-none fa-spinner fa-pulse upSpinner"></i></button>
										</div>
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>

				<div class="row d-none formNewCliente">
					<div class="col-md-2"></div>
					<div class="col-md-8">
						<div class="panel panel-primary">
							<div class="panel-heading">
								<h4 class="panel-title">REGISTRAR NUEVO CLIENTE</h4>
								<button class="btn btn-xs btn-danger cancelarRegNewCliente">CANCELAR</button>
							</div>
							<div class="panel-body">
								<form id="newCliente">
									<div class="row  w-25 m-auto text-center">
										<div class="col">
											<label for="fechaRegistro">Fecha de Registro</label>
											<input type="date" name="fechaRegistro" id="fechaRegistro" class="form-control" value="<?php echo $fecha ?>">
										</div>
									</div>
									<div class="row mt-3">
										<div class="col">
											<label for="ClinteNombres">Nombres <span class="text-danger">*</span></label>
											<input type="hidden" name="action" value="RegistrarNuevoCliente">
											<input type="hidden" name="sucursal" value="<?php echo $miCiudad ?>">
											<input type="text" name="Nombres" id="ClinteNombres" class="form-control" placeholder="Nombres">
											<div class="invalid-feedback">Campo nombre está vacío</div>
										</div>
										<div class="col">
											<label for="ClienteApellidos">Apellidos <span class="text-danger">*</span></label>
											<input type="text" name="Apellidos" id="ClienteApellidos" class="form-control" placeholder="Apellidos">
											<div class="invalid-feedback">Campo apellido está vacío</div>
										</div>
									</div>
									<div class="row mt-3">
										<div class="col">
											<label for="ClienteCell">Teléfono celular</label>
											<input type="tel" name="Celular" id="ClienteCell" class="form-control" placeholder="Teléfono celular">
											<div class="invalid-feedback">Campo celular está vacío</div>
											<div class="text-danger text-center d-none incompleteCell">El número telefónico está incompleto</div>
											<div class="text-center text-danger d-none isNaNCell">Debe ingresar números sin espacios</div>
										</div>
										<div class="col">
											<label for="ClienteOtro">Teléfono fijo</label>
											<input type="tel" name="Otro" id="ClienteOtro" class="form-control" placeholder="Teléfono fijo">
											<div class="text-danger text-center d-none incompleteFijo">El número telefónico está incompleto</div>
											<div class="invalid-feedback">Campo otro está vacío</div>
											<div class="text-center text-danger d-none isNaNOtro">Debe ingresar números sin espacios</div>
										</div>
									</div>
									<div class="row mt-3">
										<div class="col">
											<label for="ClienteCorreo">Correo</label>
											<input type="email" name="Correo" id="ClienteCorreo" class="form-control" placeholder="Correo">
											<div class="invalid-feedback">Campo correo está vacío</div>
										</div>
										<div class="col">
											<label for="ClienteCiudad">Ciudad</label>
											<select name="Ciudad" id="ClienteCiudad" class="form-control">
												<option selected="" disabled="">Seleccione Ciudad</option>
												<option value="Beni">Beni</option>
												<option value="Cochabamba">Cochabamba</option>
												<option value="Chuquiasca">Chuquiasca</option>
												<option value="La Paz">La Paz</option>
												<option value="Oruro">Oruro</option>
												<option value="Pando">Pando</option>
												<option value="Potosí">Potosí</option>
												<option value="Santa Cruz">Santa Cruz</option>
												<option value="Tajira">Tajira</option>
											</select>
											<div class="text-center text-danger d-none emptyClienteCiudad">No ha seleccionado una opción</div>
										</div>
									</div>
									<div class="row mt-3">
										<div class="col">
											<label for="ClienteEmpresa">Empresa</label>
											<input type="text" name="Empresa" id="ClienteEmpresa" class="form-control" placeholder="Empresa">
											<div class="invalid-feedback">Campo empresa está vacío</div>
										</div>
										<div class="col">
											<label for="ClienteNIT">NIT</label>
											<input type="text" name="NIT" id="ClienteNIT" class="form-control" placeholder="NIT">
											<div class="invalid-feedback">Campo NIT está vacío</div>
										</div>
									</div>
									<div class="row mt-3">
										<div class="col">
											<label for="ClienteDireccion">Dirección</label>
											<textarea name="Direccion" id="ClienteDireccion" cols="30" rows="4" class="form-control" placeholder="Dirección"></textarea>
											<div class="invalid-feedback">Campo dirección está vacío</div>
										</div>
										<div class="col">
											<label for="ClienteObservaciones">Observaciones</label>
											<textarea name="Observaciones" id="ClienteObservaciones" cols="30" rows="4" class="form-control" placeholder="Observaciones"></textarea>
											<div class="invalid-feedback">Campo observaciones está vacío</div>
										</div>
									</div>
									<div class="row mt-3">
										<div class="col">
											<div class="text-center text-danger mb-2 f-s-16 d-none noPhoneNum">Debe ingresar al menos un número de teléfono</div>
											<button type="submit" class="btn btn-xs btn-success form-control regNewCliente">REGISTRAR CLIENTE &nbsp;<i class="fas d-none fa-spinner fa-pulse efecto"></i></button>
										</div>
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>

				<div class="row tableClientes">
					<div class="col-md-12">
						<div class="panel panel-success">
							<div class="panel-heading">
								<h4 class="panel-title">MI LISTA DE CLIENTES</h4>
								<div class="panel-heading-btn">
									<button class="btn btn-xs btn-primary AddNewClienteBTN">AGREGAR CLIENTE</button>&nbsp;&nbsp;&nbsp;
									<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
									<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-success" data-click="panel-reload"><i class="fa fa-redo"></i></a>
									<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>
									<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-danger" data-click="panel-remove"><i class="fa fa-times"></i></a>
								</div>
							</div>
							<div class="panel-body">
								<table id="data-table-responsive" class="table table-striped table-bordered table-td-valign-middle w-100">
									<thead>
										<tr>
											<th class="text-center">N&ordm;</th>
											<th class="text-center">Nombre</th>
											<th class="text-center">Apellido</th>
											<th class="text-center">Correo</th>
											<th class="text-center">Celular</th>
											<th class="text-center">Fijo</th>
											<th class="text-center">Empresa</th>
											<th class="text-center">Acciones</th>
										</tr>
									</thead>
									<tbody>
										<?php
											$Num = 1;
											$QueryClientes	=	mysqli_query($MySQLi,"SELECT idCliente, Nombres, Apellidos, Correo, Empresa, NIT, Celular, Otro, Ciudad, Direccion, Comentarios, DATE_FORMAT(Fecha_reg, '%d/%M/%Y')AS Fecha_Reg, Registrador, Enviados FROM Clientes WHERE Registrador='$idUser' AND Sucursal='$miCiudad' ORDER BY Fecha_Reg DESC");
											while ($dataClientes = mysqli_fetch_assoc($QueryClientes)) { ?>
												<tr class="odd gradeX">
													<td class="text-center"><?php echo $Num; ?></td>
													<td class=""><?php echo $dataClientes['Nombres'] ?></td>
													<td class=""><?php echo $dataClientes['Apellidos'] ?></td>
													<td class="">
														<?php echo $dataClientes['Correo'];
															if ($dataClientes['Enviados']>0) {
															 	echo " &nbsp;&nbsp;[<a href='#'>".$dataClientes['Enviados']."</a>]";
															}
														?>
													</td>
													<td class="text-center"><?php echo $dataClientes['Celular'] ?></td>
													<td class="text-center"><?php echo $dataClientes['Otro'] ?></td>
													<td class=""><?php echo $dataClientes['Empresa'] ?></td>
													<td class="text-center">
														<button title="Editar Cliente" id="<?php echo $dataClientes['idCliente'] ?>" class="btn btn-xs btn-indigo editCliente"><i class="fa fa-pencil-alt" style="font-size: 15px"></i></button>&nbsp;
														<?php
															if (!empty($dataClientes['Correo'])) { ?>
																<button title="Enviar Correo" id="<?php echo $dataClientes['idCliente'] ?>" class="btn btn-xs btn-danger sendMailCliente"><i class="fa fa-envelope" style="font-size: 15px"></i></button><?php
															}
															/*	CONSULTAMOS SI EL CLIENTE HA HECHO COMPRAS 	*/
															$idCliente 		=	$dataClientes['idCliente'];
															$queryCompras	=	mysqli_query($MySQLi,"SELECT * FROM Ventas WHERE idCliente='$idCliente' ");
															$resultQuery 	=	mysqli_num_rows($queryCompras);
															if ($resultQuery>0) {
																echo '
																<form action="?root=historialCliente" method="post" class="mt-1">
																	<input type="hidden" name="idCliente" value="'.$idCliente.'">
																	<button type="submit" title="Ver historial de compras" class="btn btn-xs btn-success">
																		<i class="fas fa-history" style="font-size: 15px"></i>
																	</button>
																</form>';
															}
														?>
													</td>
												</tr>
											<?php $Num++; } mysqli_close($MySQLi);										
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
		<?php include 'php/script_misclientes.php'; ?>
	</body>
</html>
<?php include 'php/fun_misclientes.php'; ?>