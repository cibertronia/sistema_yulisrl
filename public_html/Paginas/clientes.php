<?php
	$idUser 		=	$_SESSION['idUser'];
	$ConsltaUser=	mysqli_query($MySQLi,"SELECT * FROM Usuarios WHERE idUser='$idUser' ");
	$datosUser 	=	mysqli_fetch_assoc($ConsltaUser);
	$miCiudad 	=	$datosUser['Ciudad']; 

	include 'includes/App/Models/Sucursal.php';
	use App\Models\Sucursal;

	$sucursalModel = new Sucursal();
	$sucursales = $sucursalModel->all();
	
	?>
	<!DOCTYPE html>
	<html lang="es">
		<head>
			<title>CLIENTES</title>
			<?php include 'php/meta.php'; ?>
			<link href="assets/css/apple/app.min.css" rel="stylesheet">
			<link href="assets/plugins/ionicons/css/ionicons.min.css" rel="stylesheet">
			<link href="assets/plugins/datatables.net-bs4/css/dataTables.bootstrap4.min.css" rel="stylesheet">
			<link href="assets/plugins/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css" rel="stylesheet">
			<link href="assets/plugins/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css" rel="stylesheet">
			<link href="assets/plugins/summernote/dist/summernote.css" rel="stylesheet">
		</head>
		<body><?php include 'php/loader.php'; ?>
			<div id="page-container" class="fade page-sidebar-fixed page-header-fixed"><?php
				include 'php/top_menu.php';
				include 'php/left_menu_clientes.php';?>
				<div id="content" class="content"><div class="respuesta"></div>
					<!-- SEND MAIL -->
					<div class="row d-none SendMail">
						<div class="col-md-12">
							<div class="panel panel-inverse">
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
											</div>
											<div class="col">
												<input type="text" name="Empresa" id="EmpresaClienteMail" class="form-control" placeholder="Nombre Empresa">
											</div>										
										</div>
										<div class="row mt-3">
											<div class="col">
												<input type="text" name="Asunto" class="form-control" placeholder="Asuto">
												<div class="text-center text-danger d-none noAsuntoMail">No ha ingresado un ASUNTO</div>
											</div>
											<div class="col">
												<select name="Plantilla" id="PlantiilaMail" class="form-control">
													<?php
														$queryPlantilla	=	mysqli_query($MySQLi,"SELECT * FROM Plantilla_Email WHERE Estado=1 ORDER BY Nombre ASC");
														$resultPlantilla=	mysqli_num_rows($queryPlantilla);
														if ($resultPlantilla>0) {
															echo "<option selected disabled>Seleccione plantilla</option>";
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
											<div class="col">
												<select name="remitente" id="remitente" class="form-control">
													<option selected disabled>Seleccione remitente</option>
													<option value="ventascbba@yuliimport.com">Ventas Cochabamba</option>
													<option value="ventasscz@yuliimport.com">Ventas Santa Cruz</option>
													<option value="ventaslpz@yuliimport.com">Ventas La Paz</option>
													<option value="ventastarija@yuliimport.com">Ventas Tarija</option>
													<option value="administracion@yuliimport.com">Administración</option>
												</select>
											</div>
										</div>
										<div class="row mt-3 MsjMail">
											<div class="col">
												<textarea class="summernote" name="Mensaje" id="MensajeSumerNote"></textarea>
											</div>
											<div class="text-center text-danger d-none noContenidoMail">No hay contendido en el correo a enviar</div>
										</div>
										<div class="row mt-3">
											<div class="col">
												<input type="submit" class="btn btn-xs btn-primary form-control" value="ENVIAR CORREO">
											</div>
										</div>
									</form>
								</div>
							</div>
						</div>
					</div>
					<!-- EDIT CLIENTES -->
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
													<option value="Tajira">Tajira</option>
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
												<input type="submit" class="btn btn-xs btn-success form-control upDataCliente" value="ACTUALIZAR CLIENTE">
											</div>
										</div>
									</form>
								</div>
							</div>
						</div>
					</div>
					<!-- NEW CLIENTE -->
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
												<input type="submit" class="btn btn-xs btn-success form-control regNewCliente" value="REGISTRAR CLIENTE">
											</div>
										</div>
									</form>
								</div>
							</div>
						</div>
					</div><?php
					if (isset($_GET['Sucursal'])) {
						$Sucursal = $_GET['Sucursal']; ?>
						<div class="row tableClientes">
							<div class="col-md-12">
								<div class="panel panel-inverse">
									<div class="panel-heading">
										<h4 class="panel-title">CLIENTES <?php echo strtoupper($mes) ?></h4>
										<div class="panel-heading-btn">
											<button class="btn btn-xs btn-primary Buscar"><i class="fa fa-search"> Buscar</i></button>&nbsp;&nbsp;
											<button class="btn btn-xs btn-primary AddNewClienteBTN">AGREGAR CLIENTE</button>&nbsp;&nbsp;&nbsp;
											<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
											<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-success" data-click="panel-reload"><i class="fa fa-redo"></i></a>
											<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>
											<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-danger" data-click="panel-remove"><i class="fa fa-times"></i></a>
										</div>
									</div>
									<div class="panel-body">
										<form  data-parsley-validate="true" class="w-75 m-auto d-none" id="buscar" action="?root=clientes" method="POST">
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
												<tr class="">
													<th width="5%" class="text-center">N&ordm;</th>
													<th width="10%" class="text-center">Nombre</th>
													<th width="10%" class="text-center">Apellido</th>
													<th width="20%" class="text-center">Correo</th>
													<th width="5%" class="text-center">Celular</th>
													<th width="5%" class="text-center">Fijo</th>
													<th width="10%" class="text-center">Empresa</th>
													<th width="10%" class="text-center">Registro</th>
													<th width="25%" class="text-center">Acciones</th>
												</tr>
											</thead>
											<tbody><?php
												$Num=1;
												$consultaClientes	=	mysqli_query($MySQLi,"SELECT * FROM Clientes WHERE Sucursal='$Sucursal' AND Fecha_Reg BETWEEN '$startBusqueda'AND'$fecha' ")or die(mysql_error($MySQLi)."<br>Error en la línea: ".__LINE__);
												while ($dataCliente = mysqli_fetch_assoc($consultaClientes)) { ?>
												<tr>
												 	<td><?php echo $Num ?></td>
												 	<td><?php echo $dataCliente['Nombres']  ?></td>
												 	<td><?php echo $dataCliente['Apellidos']  ?></td>
												 	<td><?php echo $dataCliente['Correo']  ?></td>
												 	<td><?php echo $dataCliente['Celular']  ?></td>
												 	<td><?php echo $dataCliente['Otro']  ?></td>
												 	<td><?php echo $dataCliente['Empresa']  ?></td>
												 	<td><?php echo $dataCliente['Sucursal']  ?></td>
												 	<td class="text-center">
												 		<button class="btn btn-xs btn-success editCliente" id="<?php echo $dataCliente['idCliente'] ?>"><i class="fa fa-edit"></i>
												 		</button>&nbsp;<?php
														if (!empty($dataCliente['Correo'])) {
															echo '<button title="Enviar Correo" id="'.$dataCliente['idCliente'].'" class="btn btn-xs btn-danger sendMailCliente"><i class="fa fa-envelope" style="font-size: 15px"></i></button>';
														}
														/*	CONSULTAMOS SI EL CLIENTE HA HECHO COMPRAS 	*/
														$idCliente 		=	$dataCliente['idCliente'];
														$queryCompras	=	mysqli_query($MySQLi,"SELECT * FROM Ventas WHERE idCliente='$idCliente' ");
														$resultQuery 	=	mysqli_num_rows($queryCompras);
														if ($resultQuery>0) { ?>
															<form target="_blank" action="?root=historialCliente" method="post" class="mt-1">
																<input type="hidden" name="idCliente" value="<?php echo $idCliente ?>">
																<button type="submit" title="Ver historial de compras" class="btn btn-xs btn-success">
																	<i class="fas fa-history" style="font-size: 15px"></i>
																</button>&nbsp;
															</form><?php
														}?>
														<button class="btn btn-danger btn-xs callDataCliente" data-toggle="modal" data-target="#AlertofAdmin" id="<?php echo $dataCliente['idCliente'] ?>" title="Borrar Cliente (<?php echo $dataCliente['idCliente'] ?>)"><i class="fa fa-trash-alt"></i></button>
											 		</td>
												 </tr><?php
												 $Num++;} mysqli_close($MySQLi); ?>
											</tbody>
										</table>
									</div>
								</div>
							</div>
						</div><?php
					}elseif (isset($_POST['inicio'])) {
						$Inicio = $_POST['inicio'];
						$Fin 		= $_POST['fin'];
						$Sucursal=$_POST['sucursal']; ?>
						<div class="row tableClientes">
							<div class="col-md-12">
								<div class="panel panel-inverse">
									<div class="panel-heading">
										<h4 class="panel-title">CLIENTES <?php echo strtoupper($Sucursal) ?> DESDE <span class="text-danger"><?php echo $Inicio ?></span> HASTA <span class="text-danger"><?php echo $Fin ?></span></h4>
										<div class="panel-heading-btn">
											<button class="btn btn-xs btn-primary Buscar"><i class="fa fa-search"> Buscar</i></button>&nbsp;&nbsp;
											<button class="btn btn-xs btn-primary AddNewClienteBTN">AGREGAR CLIENTE</button>&nbsp;&nbsp;&nbsp;
											<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
											<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-success" data-click="panel-reload"><i class="fa fa-redo"></i></a>
											<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>
											<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-danger" data-click="panel-remove"><i class="fa fa-times"></i></a>
										</div>
									</div>
									<div class="panel-body">
										<form  data-parsley-validate="true" class="w-75 m-auto d-none" id="buscar" action="?root=clientes" method="POST">
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
											<tr class="">
												<th width="5%" class="text-center">N&ordm;</th>
												<th width="10%" class="text-center">Nombre</th>
												<th width="10%" class="text-center">Apellido</th>
												<th width="20%" class="text-center">Correo</th>
												<th width="5%" class="text-center">Celular</th>
												<th width="5%" class="text-center">Fijo</th>
												<th width="10%" class="text-center">Empresa</th>
												<th width="10%" class="text-center">Registro</th>
												<th width="25%" class="text-center">Acciones</th>
											</tr>
										</thead>
										<tbody><?php
											$Num=1;
											$consultaClientes	=	mysqli_query($MySQLi,"SELECT * FROM Clientes WHERE Sucursal='$Sucursal' AND Fecha_Reg BETWEEN '$Inicio'AND'$Fin' ")or die(mysql_error($MySQLi)."<br>Error en la línea: ".__LINE__);
											while ($dataCliente = mysqli_fetch_assoc($consultaClientes)) { ?>
											<tr>
											 	<td><?php echo $Num ?></td>
											 	<td><?php echo $dataCliente['Nombres']  ?></td>
											 	<td><?php echo $dataCliente['Apellidos']  ?></td>
											 	<td><?php echo $dataCliente['Correo']  ?></td>
											 	<td><?php echo $dataCliente['Celular']  ?></td>
											 	<td><?php echo $dataCliente['Otro']  ?></td>
											 	<td><?php echo $dataCliente['Empresa']  ?></td>
											 	<td><?php echo $dataCliente['Sucursal']  ?></td>
											 	<td class="text-center">
											 		<button class="btn btn-xs btn-success editCliente" id="<?php echo $dataCliente['idCliente'] ?>"><i class="fa fa-edit"></i>
											 		</button>&nbsp;<?php
													if (!empty($dataCliente['Correo'])) {
														echo '<button title="Enviar Correo" id="'.$dataCliente['idCliente'].'" class="btn btn-xs btn-danger sendMailCliente"><i class="fa fa-envelope" style="font-size: 15px"></i></button>';
													}
													/*	CONSULTAMOS SI EL CLIENTE HA HECHO COMPRAS 	*/
													$idCliente 		=	$dataCliente['idCliente'];
													$queryCompras	=	mysqli_query($MySQLi,"SELECT * FROM Ventas WHERE idCliente='$idCliente' ");
													$resultQuery 	=	mysqli_num_rows($queryCompras);
													if ($resultQuery>0) { ?>
														<form target="_blank" action="?root=historialCliente" method="post" class="mt-1">
															<input type="hidden" name="idCliente" value="<?php echo $idCliente ?>">
															<button type="submit" title="Ver historial de compras" class="btn btn-xs btn-success">
																<i class="fas fa-history" style="font-size: 15px"></i>
															</button>&nbsp;
														</form><?php
													}?>
													<button class="btn btn-danger btn-xs callDataCliente" data-toggle="modal" data-target="#AlertofAdmin" id="<?php echo $dataCliente['idCliente'] ?>" title="Borrar Cliente (<?php echo $dataCliente['idCliente'] ?>)"><i class="fa fa-trash-alt"></i></button>
										 		</td>
											 </tr><?php
											 $Num++;} mysqli_close($MySQLi); ?>
										</tbody>
									</table>
									</div>
								</div>
							</div>
						</div><?php
					}else{
						if ($_SESSION['Rango']=='2') { ?>
							<div class="row tableClientes">
								<div class="col-md-12">
									<div class="panel panel-inverse">
										<div class="panel-heading">
											<h4 class="panel-title">LISTA DE CLIENTES</h4>
											<div class="panel-heading-btn">
												<button class="btn btn-xs btn-primary AddNewClienteBTN">AGREGAR CLIENTE</button>&nbsp;&nbsp;&nbsp;
												<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
												<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-success" data-click="panel-reload"><i class="fa fa-redo"></i></a>
												<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>
												<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-danger" data-click="panel-remove"><i class="fa fa-times"></i></a>
											</div>
										</div>
										<div class="panel-body">
											<form id="findClienteAdmin" style="width: 75%; margin: 0 auto">
												<div class="row">												
													<div class="col">
														<label for="bySucursal">Seleccione Sucursal</label>
														<select name="sucursal" id="bySucursal" class="form-control">
															<option selected disabled>Sucursal</option>
															<?php foreach($sucursales as $item){ ?>
																<option value="<?php echo $item['Sucursal'] ?>"><?php echo strtoupper($item['Sucursal']) ?></option><?php
															} ?>
															<option value="TODAS">TODAS</option>
														</select>
														<div class="text-center text-danger d-none noSucursalFind">No ha seleccionado la sucursal.</div>
													</div>
													<!-- search name, lastname, product name or model machine -->
													<div class="col">
														<label for="input-search">Buscar Nombre, Apellido, Equipo o Modelo</label>
														<input type="text" name="search" id="input-search" class="form-control" placeholder="Ingresar el dato a buscar">
														<div class="text-center text-danger d-none noDataFind">Ingresar el dato a buscar.</div>
													</div>
													<div class="col">
														<label for="btnFind">&nbsp;&nbsp;&nbsp;</label>
														<button title="Buscar" type="submit" class="btn btn-xs btn-danger form-control buscarCliente">BUSCAR</button>
													</div>
												</div>
											</form><br>
											<div id="respuestaFind">
												
											</div>
										</div>
										<!-- end panel-body -->
									</div>
								</div>
							</div><?php
						}else{  ?>
							<script type="text/javascript">
								location.replace(" ?root=404 ")	;
							</script><?php
						}
					} ?>
				</div>
				<a href="javascript:;" class="btn btn-icon btn-circle btn-primary btn-scroll-to-top fade" data-click="scroll-top"><i class="fa fa-angle-up"></i></a>
				<?php include 'php/footer.php'; ?>
			</div>
			<?php include 'php/script_clientes.php'; ?>
			<!-- Modal -->
			<div class="modal fade" id="AlertofAdmin" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
			  <div class="modal-dialog" role="document">
			    <div class="modal-content">
			      <div class="modal-header">
			        <h5 class="modal-title" id="exampleModalLabel">AVISO DEL PROGRAMADOR</h5>
			        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
			          <span aria-hidden="true">&times;</span>
			        </button>
			      </div>
			      <div class="modal-body">
			        <div class="row">
			        	<div class="col">
			        	  <p>Está apunto de borrar al cliente:<br><strong id="nameUsuario"></strong>,<br>Tome en cuenta lo siguiente:<br>Al borrar este cliente, se borraran todas sus compras efectuadas deltro del sistema.</p>
			        	</div>
			        </div>
			        <div class="row mt-1">
			        	<div class="col">
			        		<input type="hidden" id="idClienteCall">
			        		<button class="btn btn-danger btn-block delCliente">BORRAR</button>
			        	</div>
			        	<div class="col">
			        		<button class="btn btn-info btn-block" data-dismiss="modal">CANCELAR</button>
			        	</div>
			        </div>
			      </div>
			    </div>
			  </div>
			</div>
		</body>
	</html>
	<?php include 'php/fun_clientes.php';
?>