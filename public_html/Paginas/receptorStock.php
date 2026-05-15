<?php	
	include 'includes/conexion.php';
	include 'includes/date.class.php';
	mysqli_query($MySQLi,"SET lc_time_names= 'es_BO' ");
	$idUser 	=	$_SESSION['idUser'];
	$ConsltaUser=	mysqli_query($MySQLi,"SELECT * FROM Usuarios WHERE idUser='$idUser' ");
	$datosUser 	=	mysqli_fetch_assoc($ConsltaUser);
	$miCiudad 	=	$datosUser['Ciudad'];?>
	<!DOCTYPE html>
		<html lang="es">
			<head>
				<title>RECEPTOR DE MERCADERIA</title>
				<?php include 'php/meta.php'; ?>
				<link href="assets/css/apple/app.min.css" rel="stylesheet">
				<link href="assets/plugins/ionicons/css/ionicons.min.css" rel="stylesheet">
				<link href="assets/plugins/datatables.net-bs4/css/dataTables.bootstrap4.min.css" rel="stylesheet">
				<link href="assets/plugins/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css" rel="stylesheet">
				<link href="assets/plugins/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css" rel="stylesheet">
				<link href="assets/plugins/datatables.net-bs4/css/dataTables.bootstrap4.min.css" rel="stylesheet">
				<link href="assets/plugins/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css" rel="stylesheet">
				<link href="assets/plugins/blueimp-gallery/css/blueimp-gallery.min.css" rel="stylesheet">
				<link href="assets/plugins/blueimp-file-upload/css/jquery.fileupload.css" rel="stylesheet">
				<link href="assets/plugins/blueimp-file-upload/css/jquery.fileupload-ui.css" rel="stylesheet">
				<link href="assets/plugins/summernote/dist/summernote.css" rel="stylesheet">
				<link href="assets/plugins/select2/dist/css/select2.min.css" rel="stylesheet">
			</head>
			<body>
				<?php include 'php/loader.php'; ?>
				<div id="page-container" class="fade page-sidebar-fixed page-header-fixed"><?php
					include 'php/top_menu.php';
					include 'php/left_menu_RecibirProducto.php';?>
					<div id="content" class="content">
						<input type="hidden" id="idUserOk" value="<?php echo $idUser; ?>">
						<input type="hidden" id="idCiudadUser" value="<?php echo $miCiudad; ?>">
						<!-- 	TABLA PRODUCTO -->
						<div class="row tableProductos">
							<div class="col-md-12">
								<div class="panel panel-inverse">
									<div class="panel-heading"><?php
										if (isset($_POST['inicio'])) {
											$Inicio 	= $_POST['inicio'];
											$Fin 			=	$_POST['fin'];?>
											<input type="hidden" id="fechaInicio" value="<?= $Inicio?>">
											<input type="hidden" id="fechaFin" value="<?= $Fin?>">
											<h4 class="panel-title">LISTA DE ENVIOS DESDE <strong class="text-danger"><?php echo $Inicio ?></strong> HASTA <strong class="text-danger"><?php echo $Fin ?></strong></h4><?php
										}else{ ?>
											<input type="hidden" id="fechaInicio" value="<?= $startBusqueda ?>">
											<input type="hidden" id="fechaFin" value="<?= $fecha?>">
											<h4 class="panel-title">LISTA DE ENVIOS <?php echo strtoupper($mes) ?></h4><?php
										}?>										
										<div class="panel-heading-btn">
											<button type="button" class="btn btn-xs btn-primary Buscar"><i class="fa fa-search"></i></button>
										</div>
									</div>
									<div class="panel-body">
										<form  data-parsley-validate="true" class="w-75 m-auto d-none" id="buscar" action="?root=receptorStock" method="POST">
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
									</div>
									<div class="row d-none tablaStockTemp" style="margin-top: 3px">
										<div class="col" id="tablaStockTemp"></div>
									</div>
									<div class="row">
										<div class="col"><div class="respuesta"></div>
											<table id="data-table-responsive" class="table table-striped table-bordered table-td-valign-middle w-100">
												<thead>
													<tr>
														<th class="text-center">N&ordm;</th>
														<th class="text-center">Sucusal</th>
														<th class="text-center">Vendedor</th>
														<th class="text-center">Fecha</th>
														<th class="text-center">Estado</th>
														<th class="text-center">Acciones</th>
													</tr>
												</thead><?php
												if (isset($_POST['inicio'])) {
													$sqlEnvios = mysqli_query($MySQLi, "SELECT * FROM envioStock WHERE hasta='$miCiudad' AND fecha BETWEEN '$Inicio' AND '$Fin' ORDER BY estado ASC")or die(mysqli_error($MySQLi)."<br>Error en la linea: ".__LINE__);
												}else{
													$sqlEnvios = mysqli_query($MySQLi, "SELECT * FROM envioStock WHERE hasta='$miCiudad' AND fecha BETWEEN '$startBusqueda' AND '$fecha' ORDER BY estado ASC");
												}
												$idNumber 		= 1;
												?>
												<tbody><?php
												while($dataEnvio = mysqli_fetch_assoc($sqlEnvios)){
								        	echo'<tr>
								        	<td class="text-center">'.$idNumber.'</td>
								        	<td>'.$dataEnvio["desde"].'</td>';
								       		$idVendedor  = $dataEnvio["idUser"];
								        	$sqlVendedor = mysqli_query($MySQLi,"SELECT Nombres,Apellidos FROM Usuarios WHERE idUser='$idVendedor' ");
								        	$dataVendedor = mysqli_fetch_assoc($sqlVendedor);
								        	$Vendedor = $dataVendedor["Nombres"].' '.$dataVendedor["Apellidos"]; echo'
								        	<td>'.$Vendedor.'</td>';
								        	$thisFecha = $dataEnvio['fecha'];
								        	$fechaFormato = date("d-m-Y", strtotime($thisFecha));
								        	echo'
								        	<td class="text-center">'.$fechaFormato." &nbsp;&nbsp;&nbsp; ".$dataEnvio['hora'] .'</td>';
								        	if($dataEnvio["estado"]==0) {
								        		echo'<td><button class="btn btn-block btn-info">En ruta</button></td>';
								        	}elseif($dataEnvio["estado"]==1) {
													  echo'<td><button class="btn btn-block btn-success">Recibido</button></td>';
								        	}else{
								        		echo'<td><button class="btn btn-block btn-danger">Cancelado</button></td>';
								        	} echo'</td>
								        	<td class="text-center">';
								            $idEnvio = $dataEnvio["idEnvio"];
								            if ($dataEnvio['estado']==0) {
								            	echo' 
								            <button class="btn btn-sm btn-success recibirProducto" type="button" id='.$idEnvio.' title="Confirmar recepci&oacute;n del envio" ><i class="fa fa-check"></i></button>';
								            } echo'
								            <a target="_blank" href="Reportes/pdf.php?ReporteEnvioStock='.$idEnvio.'" class="btn btn-sm btn-info" title="Descargar reporte de envio PDF" ><i class="fa fa-file-pdf"></i></a>
								        	</td></ tr>'; $idNumber++;
								    		}?>
												</tbody>
											</table>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<a href="javascript:;" class="btn btn-icon btn-circle btn-primary btn-scroll-to-top fade" data-click="scroll-top"><i class="fa fa-angle-up"></i></a>
					<?php include 'php/footer.php'; ?>
				</div>
				<?php include 'php/script_receptorProductos.php';?>
			</body>
		</html>