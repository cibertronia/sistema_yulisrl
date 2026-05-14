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
		<title>TABLA DE METAS</title>
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
				<div class="row tablaComisiones">
					<div class="col-md-7">
						<div class="panel panel-inverse">
							<div class="panel-heading">
								<h4 class="panel-title">TABLA DE COMISIONES</h4>
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
											<th class="text-center">N&ordm;</th>
											<th class="text-center">SUCURSAL</th>
											<th class="text-center">META<br>MINIMA</th>
											<th class="text-center">COMISIÓN<br>MÍNIMA</th>
											<th class="text-center">META<br>MÁXIMA</th>
											<th class="text-center">COMISIÓN<br>MÁXIMA</th>
											<th class="text-center">ACCIONES</th>
										</tr>
									</thead>
									<tbody>
										<?php
											$Num =	1;
											$queryComisiones	=	mysqli_query($MySQLi,"SELECT * FROM TablaComisiones ORDER BY Sucursal ASC");
											while ($data = mysqli_fetch_assoc($queryComisiones)) { ?>
										<tr>
											<td><?php echo $Num ?></td>
											<td><?php echo $data['Sucursal'] ?></td>
											<td class="text-center">$ <?php echo number_format($data['Meta1']) ?></td>
											<td class="text-center"><?php echo $data['Comision1'] ?>&nbsp;&nbsp;%</td>
											<td class="text-center">$ <?php echo number_format($data['Meta2']) ?></td>
											<td class="text-center"><?php echo $data['Comision2'] ?>&nbsp;&nbsp;%</td>
											<td class="text-center">
												<button title="Editar Comisión" class="btn btn-xs btn-primary editarComision" id="<?php echo $data['idTabla'] ?>">
													<i class="fa fa-edit"></i>
												</button>
											</td>
										</tr><?php $Num++;} mysqli_close($MySQLi); ?>
									</tbody>
								</table>
							</div>
						</div>
					</div>

					<div class="col-md-5 editTabla d-none">
						<div class="panel panel-inverse">
							<div class="panel-heading">
								<h4 class="panel-title">EDITAR TABLA DE METAS</h4>
								<div class="panel-heading-btn">
									<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
									<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-success" data-click="panel-reload"><i class="fa fa-redo"></i></a>
									<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>
									<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-danger" data-click="panel-remove"><i class="fa fa-times"></i></a>
								</div>
							</div>
							<div class="panel-body">
								<form id="updateMeta" data-parsley-validate="true">
									<div class="row">
										<div class="col text-center">
											<label for="metaMinima">META MÍNIMA</label>
											<input type="hidden" name="idTabla" id="idTabla">
											<input type="hidden" name="action" value="Actualizar Metas y Comisiones">
											<input type="text" name="metaMinima" id="metaMinima" class="form-control text-center" placeholder="meta mínima" data-parsley-required="true">
										</div>
										<div class="col text-center">
											<label for="comisionMinima">COMISION MÍNIMA</label>
											<input type="text" name="comisionMinima" id="comisionMinima" class="form-control text-center" placeholder="comisión mínima" data-parsley-required="true">
										</div>
									</div>
									<div class="row mt-3">
										<div class="col text-center">
											<label for="metaMaxima">META MÁXIMA</label>
											<input type="text" name="metaMaxima" id="metaMaxima" class="form-control text-center" placeholder="meta máxima" data-parsley-required="true">
										</div>
										<div class="col text-center">
											<label for="comisionMaxima">COMISION MÁXIMA</label>
											<input type="text" name="comisionMaxima" id="comisionMaxima" class="form-control text-center" placeholder="comisión mínima" data-parsley-required="true">
										</div>
									</div>
									<div class="row mt-1">
										<div class="col">
											<label for="button">&nbsp;&nbsp;&nbsp;&nbsp;</label>
											<input type="submit" class="btn btn-xs btn-primary form-control actualizarMetas" id="button" value="Actualizar Comisión">
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
		<?php include 'php/script_comisiones.php'; ?>
	</body>
</html>
<script type="text/javascript">
	$("#updateMeta").submit(function(){
		$.ajax({
			url: 'do.php',
			type: 'POST',
			dataType: 'html',
			data: $(updateMeta).serialize(),
		})
		.done(function(data) {
			$(".respuesta").html(data);
		})
		return false;
	});
</script>