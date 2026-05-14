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
		<title>SUCURSALES</title>
		<?php include 'php/meta.php'; ?>
		<link href="assets/css/apple/app.min.css" rel="stylesheet">
		<link href="assets/plugins/ionicons/css/ionicons.min.css" rel="stylesheet">
		<link href="assets/plugins/datatables.net-bs4/css/dataTables.bootstrap4.min.css" rel="stylesheet">
		<link href="assets/plugins/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css" rel="stylesheet">
		<link href="assets/plugins/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css" rel="stylesheet">
		<link href="assets/plugins/blueimp-gallery/css/blueimp-gallery.min.css" rel="stylesheet">
		<link href="assets/plugins/blueimp-file-upload/css/jquery.fileupload.css" rel="stylesheet">
		<link href="assets/plugins/blueimp-file-upload/css/jquery.fileupload-ui.css" rel="stylesheet">
	</head>
	<body>
		<?php include 'php/loader.php'; ?>
		<div id="page-container" class="fade page-sidebar-fixed page-header-fixed">
			<?php
				include 'php/top_menu.php';
				include 'php/left_menu.php';
			?>
			<div id="content" class="content"><div class="respuesta"></div>
				<div class="row">
					<div class="col-md-6">
						<div class="panel panel-inverse">
							<div class="panel-heading">
								<h4 class="panel-title">LISTA DE SUCURSALES</h4>
								<div class="panel-heading-btn">
									<button class="btn btn-xs btn-primary AddNewSucursalBTN">AGREGAR SUCURSAL</button>&nbsp;&nbsp;&nbsp;
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
											<th class="text-center">Sucursal</th>
											<th class="text-center">Acciones</th>
										</tr>
									</thead>
									<tbody>
										<?php
											include 'includes/conexion.php';
											$Num = 1;
											$querySucursal	=	mysqli_query($MySQLi,"SELECT * FROM Sucursales ORDER BY Sucursal ASC");
											while ($dataSucursal = mysqli_fetch_assoc($querySucursal)) {
										?>
										<tr class="odd gradeX">
											<td class="text-center"><?php echo $Num; ?></td>
											<td class="text-center"><?php echo $dataSucursal['Sucursal'] ?></td>
											<td class="text-center">
												<button title="Editar Sucursal" id="<?php echo $dataSucursal['idSucursal'] ?>" class="btn btn-xs btn-success editSucursalExist">
													<i class="ion-ios-brush" style="font-size: 15px"></i>
												</button>&nbsp;&nbsp;
											</td>
										</tr><?php $Num++; } mysqli_close($MySQLi); ?>
									</tbody>
								</table>
							</div>
							<!-- end panel-body -->
						</div>
					</div>
					<div class="col-md-6 d-none AddNewScursal">
						<div class="panel panel-inverse">
							<div class="panel-heading">
								<h4 class="panel-title">REGISTRAR NUEVA SUCURSAL</h4>
								<button class="btn btn-xs btn-danger cancelarRegNewSucursal">CANCELAR</button>
							</div>
							<div class="panel-body">
								<form id="mySucursal" data-parsley-validate="false">
									<div class="row">
										<div class="col">
											<input type="hidden" name="action" value="RegistrarNuevaSucursal">
											<input type="text" name="Sucursal" id="SucuNomnbre" class="form-control" placeholder="Sucursal" data-parsley-required="false">
											<div class="text-center text-danger d-none emptySucuNombre">Campo sucursal está vacío</div>
										</div>
									</div>
									<div class="row mt-3">
										<div class="col">
											<input type="submit" class="btn btn-xs btn-primary form-control" value="REGISTRAR SUCURSAL">
										</div>
									</div>
								</form>
							</div>
						</div>
					</div>
					<div class="col-md-6 d-none editSucursal">
						<div class="panel panel-inverse">
							<div class="panel-heading">
								<h4 class="panel-title">EDITAR SUCURSAL</h4>
								<button class="btn btn-xs btn-danger cancelarEditSucu">CANCELAR</button>
							</div>
							<div class="panel-body">
								<form id="editMySucu" data-parsley-validate="false">									
									<div class="row">
										<div class="col">
											<input type="hidden" name="action" value="EditarmySucursal">
											<input type="hidden" name="idSucursal" id="idSucursal_">
											<input type="text" name="Sucursal" id="SucuNomnbre_" class="form-control" placeholder="Sucursal" data-parsley-required="false">
											<div class="text-center text-danger d-none emptySucuNombre_">Campo sucursal está vacío</div>
										</div>
									</div>
									<div class="row mt-3">
										<div class="col">
											<input type="submit" class="btn btn-xs btn-primary form-control" value="ACTUALIZAR SUCURSAL">
										</div>
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>
			</div>
			<a href="javascript:;" class="btn btn-icon btn-circle btn-primary btn-scroll-to-top fade" data-click="scroll-top"><i class="fa fa-angle-up"></i></a>
			<?php include 'php/footer.php'; ?>
		</div>
		<?php include 'php/script_sucursals.php'; ?>
	</body>
</html>
<?php include 'php/fun_sucursales.php'; ?>