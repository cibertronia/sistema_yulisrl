<?php
	include 'includes/conexion.php';
	include 'includes/date.class.php';
	mysqli_query($MySQLi,"SET lc_time_names= 'es_BO' ");
	$idUser 	=	$_SESSION['idUser'];
	$ConsltaUser=	mysqli_query($MySQLi,"SELECT * FROM Usuarios WHERE idUser='$idUser' ");
	$datosUser 	=	mysqli_fetch_assoc($ConsltaUser);
	$miCiudad 	=	$datosUser['Ciudad'];
?>
<!DOCTYPE html>
<html lang="es">
	<head>
		<title>PERFIL</title>
		<?php include 'php/meta.php'; ?>
		<link href="assets/plugins/superbox/superbox.min.css" rel="stylesheet">
		<link href="assets/plugins/lity/dist/lity.min.css" rel="stylesheet">
		<link href="assets/css/apple/app.min.css" rel="stylesheet">
		<link href="assets/plugins/ionicons/css/ionicons.min.css" rel="stylesheet">
		<link href="assets/plugins/jvectormap-next/jquery-jvectormap.css" rel="stylesheet">
		<link href="assets/plugins/bootstrap-datepicker/dist/css/bootstrap-datepicker.css" rel="stylesheet">
		<link href="assets/plugins/bootstrap-datepicker/dist/css/bootstrap-datepicker3.css" rel="stylesheet">
		<link href="assets/plugins/ion-rangeslider/css/ion.rangeSlider.min.css" rel="stylesheet">
		<link href="assets/plugins/bootstrap-colorpicker/dist/css/bootstrap-colorpicker.min.css" rel="stylesheet">
		<link href="assets/plugins/bootstrap-timepicker/css/bootstrap-timepicker.min.css" rel="stylesheet">
		<link href="assets/plugins/@danielfarrell/bootstrap-combobox/css/bootstrap-combobox.css" rel="stylesheet">
		<link href="assets/plugins/bootstrap-select/dist/css/bootstrap-select.min.css" rel="stylesheet">
		<link href="assets/plugins/tag-it/css/jquery.tagit.css" rel="stylesheet">
		<link href="assets/plugins/bootstrap-daterangepicker/daterangepicker.css" rel="stylesheet">
		<link href="assets/plugins/select2/dist/css/select2.min.css" rel="stylesheet">
		<link href="assets/plugins/eonasdan-bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.min.css" rel="stylesheet">
		<link href="assets/plugins/bootstrap-colorpalette/css/bootstrap-colorpalette.css" rel="stylesheet">
		<link href="assets/plugins/jquery-simplecolorpicker/jquery.simplecolorpicker.css" rel="stylesheet">
		<link href="assets/plugins/jquery-simplecolorpicker/jquery.simplecolorpicker-fontawesome.css" rel="stylesheet">
		<link href="assets/plugins/jquery-simplecolorpicker/jquery.simplecolorpicker-glyphicons.css" rel="stylesheet">
	</head>
	<body>
		<?php include 'php/loader.php'; ?>
		<div id="page-container" class="fade page-sidebar-fixed page-header-fixed">
			<?php
				include 'php/top_menu.php';
				include 'php/left_menu_perfil.php';
			?>
			<div id="content" class="content content-full-width">
				<div class="profile">
					<div class="profile-header">
						<div class="profile-header-cover"></div>
						<div class="profile-header-content">
							<div class="profile-header-img">
								<img title="<?php echo $datosUser['Cargo'] ?>" src="assets/img/user/<?php echo $datosUser['Avatar'] ?>" alt="<?php echo $datosUser['Cargo'] ?>">
							</div>
							<div class="profile-header-info">
								<h4 class="mt-0 mb-1">
									<?php echo $datosUser['Nombres']." ".$datosUser['Apellidos'] ?>
								</h4>
								<p class="mb-2">
									<?php echo $datosUser['Cargo'] ?>										
								</p>
								<button class="btn btn-xs btn-yellow editMyProfile">EDITAR PERFIL</button>
							</div>
						</div>
						<ul class="profile-header-tab nav nav-tabs">
							<li class="nav-item">
								<a href="#profile-post" class="nav-link active" data-toggle="tab"></a>
							</li>
						</ul>
					</div>
				</div><div class="respuesta"></div>
				<div class="profile-content">
					<div class="tab-content p-0">
						<div class="tab-pane fade show active" id="profile-post">
							<!-- <ul class="timeline">
							</ul> -->
							<div class="row profileUsers">
								<div class="col-12 col-md-8">
									<div class="panel panel-inverse">
										<div class="panel-heading">
											<h4 class="panel-title">MI PERFIL DE USUARIO</h4>
										</div>										
										<div class="panel-body">
											<div class="row d-none noChangesPerfil">
												<div class="col">
													<div class="alert alert-danger fade show text-center">
														No hemos detectado ningún cambio.
													</div>
												</div>
											</div>
											<div class="row d-none ChangePerfilDone">
												<div class="col">
													<div class="alert alert-success fade show text-center">
														Tu Perfil se actualizó correctamente.
													</div>
												</div>
											</div>
											<div class="row d-none ChangePerfilError">
												<div class="col">
													<div class="alert alert-danger fade show text-center">
														ERROR AL INTENTAR ACTUALIZAR TU PERFIL. <br>
														Notifica al Administrador!!.
													</div>
												</div>
											</div>

											<form id="updateMyProfile">
												<div class="row">
													<div class="col">
														<input type="hidden" name="action" value="ActualizarMiPerfil">
														<label for="NameProfile">Nombres</label>
														<input type="text" name="Nombres" id="NameProfile" class="form-control" placeholder="Nombres" value="<?php echo $datosUser['Nombres'] ?>">
														<div class="text-danger text-center d-none noName">EL CAMPO NOMBRE ESTÁ VACÍO.</div>
													</div>
													<div class="col">
														<label for="LastNameProfile">Apellidos</label>
														<input type="text" name="Apellidos" id="LastNameProfile" class="form-control" placeholder="Apellidos" value="<?php echo $datosUser['Apellidos'] ?>" >
														<div class="text-danger text-center d-none noLastName">EL CAMPO APELLIDOS ESTÁ VACÍO.</div>
													</div>
												</div>
												<div class="row mt-3">
													<div class="col">
														<label for="SucProfile">Sucursal</label>
														<input type="text" name="Sucursal" id="SucProfile" value="<?php echo $datosUser['Ciudad'] ?>" class="form-control" disabled>
													</div>
													<div class="col">
														<label for="PhoneProfile">Telefono</label>
														<input type="text" name="Telefono" id="PhoneProfile" class="form-control" data-parsley-type="text" placeholder="Teléfono" value="<?php echo $datosUser['Telefono'] ?>" >
														<div class="text-danger text-center d-none noPhone">EL CAMPO TELÉFONO ESTÁ VACÍO.</div>
													</div>
												</div>
												<div class="row mt-3">
													<div class="col">
														<label for="MailProfile">Correo Electronico</label>
														<input type="email" name="Correo" id="MailProfile" class="form-control" data-parsley-type="email" placeholder="Correo" value="<?php echo $datosUser['Correo'] ?>" >
														<div class="text-danger text-center d-none noMail">EL CAMPO CORREO ESTÁ VACÍO.</div>
													</div>
													<div class="col mt-4">
														<button type="submit" class="form-control btn btn-xs btn-primary ediT">EDITAR MI PERFIL &nbsp;<i class="fas d-none fa-spinner fa-pulse editPerfil"></i></button>
													</div>
												</div>
											</form>
										</div>
									</div>
								</div>

								<?php if ($_SESSION['Rango'] == '2') { ?>
								<div class="col-12 col-md-4">
									<div class="panel panel-inverse">
										<div class="panel-heading">
											<h4 class="panel-title">CAMBIAR CONTRASEÑA</h4>
										</div>
										<div class="panel-body">
											<div class="row d-none noChangePswd">
												<div class="col">
													<div class="alert alert-danger fade show text-center">
														No hay cambios que hacer.<br>La contraseña es la misma que tus registros.
													</div>
												</div>
											</div>
											<form id="changePasswordProfile">
												<div class="row">
													<div class="col">
														<input type="hidden" name="action" value="CambiarContrasena">
														<label for="Pswd_1">Contraseña</label>
														<input data-toggle="password" name="pswd1" id="Pswd_1" value="<?php echo $_SESSION['Contrasena'] ?>" data-placement="after" class="form-control" type="password" placeholder="Contraseña">
														<div class="text-center text-danger d-none notPswd1">Contrasena requerida</div>
													</div>
												</div>
												<div class="row mt-3">
													<div class="col">
														<label for="Pswd_2">Confirmar Contraseña</label>
														<input data-toggle="password" name="" id="Pswd_2" value="<?php echo $_SESSION['Contrasena'] ?>" data-placement="after" class="form-control" type="password" placeholder="Repetir Contraseña">
														<div class="text-center text-danger d-none notPswd2">Contraseña requerida</div>
														<div class="text-center text-danger d-none notMatchPswd">Esta contraseña no coincide</div>
													</div>
												</div>
												<div class="row mt-3">
													<div class="col">
														<button type="submit" class="form-control btn btn-xs btn-primary pswd">CAMBIAR CONTRASEÑA &nbsp;<i class="fas d-none fa-spinner fa-pulse changePswd"></i></button>
													</div>
												</div>
												<div class="row mt-3">
													<div class="col">
													</div>
												</div>
											</form>
										</div>
									</div>
								</div>
								<?php } ?>
							</div>
						</div>
					</div>
				</div>
			</div>
			<a href="javascript:;" class="btn btn-icon btn-circle btn-primary btn-scroll-to-top fade" data-click="scroll-top"><i class="fa fa-angle-up"></i></a>
			<?php include 'php/footer.php'; mysqli_close($MySQLi); ?>
		</div>
		<?php include 'php/script_perfil.php'; ?>
	</body>
</html>
<?php include 'php/fun_perfil.php'; ?>