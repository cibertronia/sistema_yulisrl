<?php
	include 'includes/conexion.php';
	include 'includes/date.class.php';
	mysqli_query($MySQLi,"SET lc_time_names= 'es_BO' ");
	$idUser 	=	$_SESSION['idUser'];
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
		<title>USUARIOS</title>
		<?php include 'php/meta.php'; ?>
		<link href="assets/css/apple/app.min.css" rel="stylesheet">
		<link href="assets/plugins/ionicons/css/ionicons.min.css" rel="stylesheet">
		<link href="assets/plugins/datatables.net-bs4/css/dataTables.bootstrap4.min.css" rel="stylesheet">
		<link href="assets/plugins/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css" rel="stylesheet">
		<link href="assets/plugins/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css" rel="stylesheet">
	</head>
	<body>
		<?php include 'php/loader.php'; ?>
		<div id="page-container" class="fade page-sidebar-fixed page-header-fixed">
			<?php
				include 'php/top_menu.php';
				include 'php/left_menu_usuarios.php';
			?>
			<div id="content" class="content"><div class="respuesta"></div>
				<!-- FORMULARIO NUEVO USUARIO -->
				<div class="row d-none formNewUser">
					<div class="col-md-2"></div>
					<div class="col-md-8">
						<div class="panel panel-inverse">
							<div class="panel-heading">
								<h4 class="panel-title">REGISTRAR NUEVO VENDEDOR</h4>
								<button class="btn btn-xs btn-danger cancelarRegistro">CANCELAR</button>
							</div>
							<div class="panel-body">
								<form id="newUser">
									<input type="hidden" name="Ciudad" id="Ciudad">
									<div class="row">
										<div class="col">
											<input type="hidden" name="action" value="RegistrarNuevoUsuario">
											<input type="text" name="Nombres" id="NewNombres" class="form-control" placeholder="Nombres" maxlength="50">
											<div class="invalid-feedback">Campo nombre está vacío</div>
										</div>
										<div class="col">
											<input type="text" name="Apellidos" id="NewApellidos" class="form-control" placeholder="Apellidos" maxlength="50">
											<div class="invalid-feedback">Campo apellido está vacío</div>
										</div>
									</div>
									<div class="row mt-3">
										<div class="col">
											<input type="text" name="Telefono" id="NewTelefono" class="form-control" placeholder="Teléfono">
											<div class="invalid-feedback">Campo teléfono está vacío</div>
											<div class="text-center text-danger d-none nimLength">El número telefónico está incompleto</div>
										</div>
										<div class="col">
											<select name="Sucursal" id="NewSucursal" class="form-control">
												<option selected="" disabled="">Seleccione Sucursal</option>
												<?php foreach ($sucursales as $item) { ?>
													<option value="<?php echo $item['Sucursal']; ?>"><?php echo $item['Sucursal']; ?></option>
												<?php } ?>
											</select>
											<div class="text-center text-danger d-none emptyNewSucursal">No ha seleccionado una sucursal</div>
										</div>
									</div>
									<div class="row mt-3">
										<div class="col">
											<input type="email" name="Correo" id="NewCorreo" class="form-control" placeholder="Correo">
											<div class="invalid-feedback">Campo correo está vacío</div>
										</div>
										<div class="col">
											<select name="Sexo" id="newUserSexo" class="form-control">
												<option selected disabled>Sexo</option>
												<option value="Masculino">Masculino</option>
												<option value="Femenino">Femenino</option>
											</select>
											<div class="text-center text-danger d-none emptyNewSexo">No ha seleccionado una opción</div>
										</div>
									</div>
									<div class="row mt-3">
										<div class="col">
											<select name="Cargo" id="newUserRango" class="form-control">
												<option selected disabled>Rango</option>
												<option value="Administrador">Administrador</option>
												<option value="Vendedor">Vendedor</option>
											</select>
											<div class="text-center text-danger d-none emptyNewRango">No ha seleccionado un rango</div>
										</div>
										<div class="col">
											<div class="col">
												<button class="btn btn-xs btn-primary form-control regNewUser">REGISTRAR USUARIO</button>
												
											</div>
											<!-- <input type="text" name="Cargo" id="" class="form-control" placeholder="Cargo" maxlength="20">
											<div class="invalid-feedback">Campo cargo está vacío</div> -->
										</div>										
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>
				<!-- FORMULARIO EDITAR USUARIO -->
				<div class="row d-none editFormUser">
					<div class="col-md-2"></div>
					<div class="col-md-8">
						<div class="panel panel-inverse">
							<div class="panel-heading">
								<h4 class="panel-title">EDITAR VENDEDOR</h4>
								<button class="btn btn-xs btn-danger cancelareditRegistro">CANCELAR</button>
							</div>
							<div class="panel-body">
								<form id="editUserList">
									<div class="row">
										<div class="col">
											<label for="Nombres">Nombres</label>
											<input type="hidden" name="action" value="ActualizarUsuarioLista">
											<input type="hidden" name="idUser" id="idUser">
											<input type="text" name="Nombres" id="Nombres" class="form-control" placeholder="Nombres" maxlength="">
											<div class="invalid-feedback">Campo nombre está vacío</div>
										</div>
										<div class="col">
											<label for="Apellidos">Apellidos</label>
											<input type="text" name="Apellidos" id="Apellidos" class="form-control" placeholder="Apellidos" maxlength="">
											<div class="invalid-feedback">Campo apellido está vacío</div>
										</div>
									</div>
									<div class="row mt-3">
										<div class="col">
											<label for="Telefono">Telefono</label>
											<input type="text" name="Telefono" id="Telefono" class="form-control" placeholder="Teléfono">
											<div class="invalid-feedback">Campo teléfono está vacío</div>
											<div class="text-center text-danger d-none nimLength_">El número telefónico está incompleto</div>
										</div>
										<div class="col">
											<label for="Sucursal">Sucursal</label>
											<select name="Sucursal" id="Sucursal" class="form-control">
												<option selected="" disabled="">Seleccione Sucursal</option>
												<?php foreach ($sucursales as $item) { ?>
													<option value="<?php echo $item['Sucursal']; ?>"><?php echo $item['Sucursal']; ?></option>
												<?php } ?>
											</select>
											<div class="text-center text-danger d-none emptySucursal">No ha seleccionado una sucursal</div>
										</div>
									</div>
									<div class="row mt-3">
										<div class="col">
											<label for="Correo">Correo</label>
											<input type="email" name="Correo" id="Correo" class="form-control" placeholder="Correo">
											<div class="invalid-feedback">Campo correo está vacío</div>
										</div>
										<div class="col">
											<label for="Sexo">Sexo</label>
											<select name="Sexo" id="Sexo" class="form-control">
												<option selected="" disabled="">Sexo</option>
												<option value="Masculino">Masculino</option>
												<option value="Femenino">Femenino</option>
											</select>
											<div class="text-center text-danger d-none emptySexo">No ha seleccionado una opción</div>
										</div>
									</div>
									<div class="row mt-3">
										<div class="col">
											<label for="newUserRango_">Rango</label>
											<select name="Cargo" id="newUserRango_" class="form-control">
												<option selected="" disabled="">Rango</option>
												<option value="Administrador">Administrador</option>
												<option value="Vendedor">Vendedor</option>
												<option value="Administradora">Administradora</option>
												<option value="Vendedora">Vendedora</option>
											</select>
											<div class="text-center text-danger d-none emptySexo">No ha seleccionado una opción</div>
										</div>
										<div class="col">
											<label for="submit_">&nbsp;&nbsp;</label>
											<input class="btn btn-xs btn-primary form-control editarNewUser" id="submit" value="ACTUALIZAR USUARIO">
										</div>
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>
				<!-- TABLA DE USUARIOS -->
				<div class="row tableUsers">
					<div class="col-md-12">
						<div class="panel panel-inverse">
							<div class="panel-heading">
								<h4 class="panel-title">LISTA DE VENDEDORES</h4>
								<div class="panel-heading-btn">
									<button class="btn btn-xs btn-primary AddUserBTN">AGREGAR VENDEDOR</button>&nbsp;&nbsp;&nbsp;
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
											<th class="text-center">Nombre</th>
											<th class="text-center">Cargo</th>
											<th class="text-center">Teléfono</th>
											<th class="text-center">Sucursal</th>
											<th class="text-center">Correo</th>
											<th class="text-center">Acciones</th>
										</tr>
									</thead>
									<tbody>
										<?php
											include 'includes/conexion.php';
											$Num = 1;
											$QueryUsers	=	mysqli_query($MySQLi,"SELECT * FROM Usuarios WHERE idUser!=1 ORDER BY idUser DESC");
											while ($dataUsers = mysqli_fetch_assoc($QueryUsers)) {
										?>
										<tr class="odd gradeX">
											<td class="text-center"><?php echo $Num; ?></td>
											<td class="text-center"><?php echo $dataUsers['Nombres']." ".$dataUsers['Apellidos'] ?></td>
											<td class="text-center"><?php echo $dataUsers['Cargo'] ?></td>
											<td class="text-center"><?php echo $dataUsers['Telefono'] ?></td>
											<td class="text-center"><?php echo $dataUsers['Ciudad'] ?></td>
											<td class="text-center"><?php echo $dataUsers['Correo'] ?></td>
											<td class="text-center">
												
												<?php
													if ($dataUsers['Estado']==1) { ?>

														<button title="Deshabilitar Usuario" id="<?php echo $dataUsers['idUser'] ?>" class="btn btn-xs btn-danger offUser"><i class="fa fa-power-off" style="font-size: 15px"></i></button>&nbsp;
														<button title="Editar Usuario" id="<?php echo $dataUsers['idUser'] ?>" class="btn btn-xs btn-success editUser"><i class="ion-ios-brush" style="font-size: 15px"></i></button>&nbsp;<?php
													}else{ ?>
														<button title="Habilitar Usuario" id="<?php echo $dataUsers['idUser'] ?>" class="btn btn-xs btn-success ONUser"><i class="fa fa-power-off" style="font-size: 15px"></i></button>&nbsp;<?php
													}
												?>
												<button title="Borrar Usuario" id="<?php echo $dataUsers['idUser'] ?>" class="btn btn-xs btn-danger deleteUser"><i class="fa fa-trash" style="font-size: 15px"></i></button>
											</td>
										</tr><?php $Num++; } mysqli_close($MySQLi); ?>
									</tbody>
								</table>
							</div>
							<!-- end panel-body -->
						</div>
					</div>
				</div>
			</div>
			<a href="javascript:;" class="btn btn-icon btn-circle btn-primary btn-scroll-to-top fade" data-click="scroll-top"><i class="fa fa-angle-up"></i></a>
			<?php include 'php/footer.php';?>
		</div>
		<?php include 'php/script_usuarios.php'; ?>
	</body>
</html>
<?php include 'php/fun_usuarios.php'; ?>