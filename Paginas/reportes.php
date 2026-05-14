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

	$sucursalesModel = new Sucursal();
	$sucursales = $sucursalesModel->all();    
?>
<!DOCTYPE html>
<html lang="es">
	<head>
		<title>REPORTES DE VENTA</title>
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
		<style>
			.f-s-4{
				font-size: 4px;
			}
		</style>
	</head>
	<body>
		<?php include 'php/loader.php'; ?>
		<div id="page-container" class="fade page-sidebar-fixed page-header-fixed">
			<?php
				include 'php/top_menu.php';
				include 'php/left_menu_reportes.php';
			?>			
			<div id="content" class="content"><div class="respuesta"></div>

				<!-- REPORTE DE VENTAS INSTANTÁNEAS -->
				<div class="row ReporteVentas">
					<div class="col-md-12">
						<div class="panel panel-inverse">
							<div class="panel-heading">
								<h4 class="panel-title">REPORTE VENTAS &nbsp;&nbsp;
									<span style="text-transform: uppercase;letter-spacing: 1px;font-size: 16px"><?php echo $mes?></span>
								</h4>
								<div class="panel-heading-btn">
									<!-- <div class="input-group input-daterange">
										<form target="_blank" action="?root=buscar" method="post">
											<input type="date" name="inicio" required>
											<input type="date" name="fin" required>
											<input type="submit" class="btn btn-xs btn-danger" value="BUSCAR">
										</form>
									</div> -->&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
									<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
									<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-success" data-click="panel-reload"><i class="fa fa-redo"></i></a>
									<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>
									<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-danger" data-click="panel-remove"><i class="fa fa-times"></i></a>
								</div>
							</div>
							<div class="panel-body">
								<form action="Reportes/pdf.php" method="post" style="width: 80%; margin: 0 auto">
									<div class="row">
										<div class="col">
											<label for="Sucursal">Sucursal</label>
											<select name="sucursal" id="Sucursal" class="form-control" required="">
												<option value="">Sucursal</option>
												<?php foreach ($sucursales as $sucursal): ?>
												<option value="<?php echo $sucursal['Sucursal']; ?>"><?php echo $sucursal['Sucursal']; ?></option>
												<?php endforeach; ?>
											</select>
											<div class="text-center text-danger d-none noSelectSucursal">no ha seleccionado una sucursal.</div>
										</div>
										<div class="col text-center">
											<label for="fechaInicio">Fecha inicio</label>
											<input type="date" name="start" id="fechaInicio" class="form-control" value="<?php echo $startBusqueda ?>" required="">
											<div class="text-center text-danger d-none noStartDate">no ha indicado la fecha de iicio.</div>
										</div>
										<div class="col text-center">
											<label for="fechafin">Fecha fin</label>
											<input type="date" name="end" id="fechafin" class="form-control" value="<?php echo $fecha ?>" required="">
											<div class="text-center text-danger d-none noEndDate">no ha indicado la fecha de cierre.</div>
										</div>
										<div class="col">
											<label for="btnFind">&nbsp;&nbsp;&nbsp;</label>
											<button title="Generar reporte" type="submit" class="btn btn-xs btn-danger form-control">GENERAR REPORTE</button>
										</div>
									</div>
								</form>
								<div id="respuestaFindReporte"></div>
							</div>
							<!-- end panel-body -->
						</div>
					</div>
				</div>

				<!-- REPORTE DE ABONOS (VENTAS AL CRÉDITO) -->
				<div class="row ReporteCreditos">
					<div class="col-md-12">
						<div class="panel panel-info">
							<div class="panel-heading">
								<h4 class="panel-title">REPORTE DE ABONOS (VENTAS AL CRÉDITO) &nbsp;&nbsp;
									<span style="text-transform: uppercase;letter-spacing: 1px;font-size: 16px"><?php echo $mes?></span>
								</h4>
								<div class="panel-heading-btn">
									<!-- <div class="input-group input-daterange">
										<form target="_blank" action="?root=buscar" method="post">
											<input type="date" name="inicio" required>
											<input type="date" name="fin" required>
											<input type="submit" class="btn btn-xs btn-danger" value="BUSCAR">
										</form>
									</div> -->&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
									<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
									<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-success" data-click="panel-reload"><i class="fa fa-redo"></i></a>
									<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>
									<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-danger" data-click="panel-remove"><i class="fa fa-times"></i></a>
								</div>
							</div>
							<div class="panel-body">
								<form action="Reportes/pdf.php" method="post" style="width: 80%; margin: 0 auto">
									<div class="row">
										<div class="col">
											<label for="Sucursal">Sucursal</label>
											<input type="hidden" name="AbonosCreditos" value="AbonosCreditos">
											<select name="Sucursal" id="Sucursal" class="form-control" required="">
												<option value="">Sucursal</option>
												<?php foreach ($sucursales as $sucursal): ?>
												<option value="<?php echo $sucursal['Sucursal']; ?>"><?php echo $sucursal['Sucursal']; ?></option>
												<?php endforeach; ?>
											</select>
											<div class="text-center text-danger d-none noSelectSucursal">no ha seleccionado una sucursal.</div>
										</div>
										<div class="col text-center">
											<label for="fechaInicio">Fecha inicio</label>
											<input type="date" name="Start" id="fechaInicio" class="form-control" value="<?php echo $startBusqueda ?>" required="">
											<div class="text-center text-danger d-none noStartDate">no ha indicado la fecha de iicio.</div>
										</div>
										<div class="col text-center">
											<label for="fechafin">Fecha fin</label>
											<input type="date" name="End" id="fechafin" class="form-control" value="<?php echo $fecha ?>" required="">
											<div class="text-center text-danger d-none noEndDate">no ha indicado la fecha de cierre.</div>
										</div>
										<div class="col">
											<label for="btnFind">&nbsp;&nbsp;&nbsp;</label>
											<button title="Generar reporte" type="submit" class="btn btn-xs btn-danger form-control">GENERAR REPORTE</button>
										</div>
									</div>
								</form>
								<div id="respuestaFindReporte"></div>
							</div>
							<!-- end panel-body -->
						</div>
					</div>
				</div>
	
				<!-- REPORTE DE ABONOS (VENTAS POR ANTICIPO) -->
				<div class="row ReporteAbonos">
					<div class="col-md-12">
						<div class="panel panel-success">
							<div class="panel-heading">
								<h4 class="panel-title">REPORTE DE ABONOS (VENTAS POR ANTICIPO) &nbsp;&nbsp;
									<span style="text-transform: uppercase;letter-spacing: 1px;font-size: 16px"><?php echo $mes?></span>
								</h4>
								<div class="panel-heading-btn"> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
									<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
									<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-success" data-click="panel-reload"><i class="fa fa-redo"></i></a>
									<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>
									<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-danger" data-click="panel-remove"><i class="fa fa-times"></i></a>
								</div>
							</div>
							<div class="panel-body">
								<form action="Reportes/pdf.php" method="post" style="width: 80%; margin: 0 auto">
									<div class="row">
										<div class="col">
											<label for="Sucursal">Sucursal</label>
											<input type="hidden" name="AbonosAnticipos" value="AbonosAnticipos">
											<select name="Sucursal" id="Sucursal" class="form-control" required="">
												<option value="">Sucursal</option>
												<?php foreach ($sucursales as $sucursal): ?>
												<option value="<?php echo $sucursal['Sucursal']; ?>"><?php echo $sucursal['Sucursal']; ?></option>
												<?php endforeach; ?>
											</select>
											<div class="text-center text-danger d-none noSelectSucursal">no ha seleccionado una sucursal.</div>
										</div>
										<div class="col text-center">
											<label for="fechaInicio">Fecha inicio</label>
											<input type="date" name="Start" id="fechaInicio" class="form-control" value="<?php echo $startBusqueda ?>" required="">
											<div class="text-center text-danger d-none noStartDate">no ha indicado la fecha de iicio.</div>
										</div>
										<div class="col text-center">
											<label for="fechafin">Fecha fin</label>
											<input type="date" name="End" id="fechafin" class="form-control" value="<?php echo $fecha ?>" required="">
											<div class="text-center text-danger d-none noEndDate">no ha indicado la fecha de cierre.</div>
										</div>
										<div class="col">
											<label for="btnFind">&nbsp;&nbsp;&nbsp;</label>
											<button title="Generar reporte" type="submit" class="btn btn-xs btn-danger form-control">GENERAR REPORTE</button>
										</div>
									</div>
								</form>
								<div id="respuestaFindReporte"></div>
							</div>
							<!-- end panel-body -->
						</div>
					</div>
				</div>
			</div>
			<a href="javascript:;" class="btn btn-icon btn-circle btn-primary btn-scroll-to-top fade" data-click="scroll-top"><i class="fa fa-angle-up"></i></a>
			<?php include 'php/footer.php'; ?>
		</div>
		<?php include 'php/script_reportes.php'; ?>
	</body>
</html>