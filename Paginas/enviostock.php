<?php
	function aleatorio(){
		$code 	=	uniqid();
		$code 	=	substr($code, -10);
		return $code;
	}
	$alert 		=	aleatorio();
	$claveCotiza=	md5(date("d/m/Y g:i:s").$alert);
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
				<title>ENVIO DE MERCADERIA</title>
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
					include 'php/left_menu_EnviarProducto.php';?>
					<div id="content" class="content">
						<input type="hidden" id="idUserOk" value="<?php echo $idUser; ?>">
						<input type="hidden" id="idCiudadUser" value="<?php echo $miCiudad; ?>">
						<input type="hidden" id="ClaveGeneradaAleatoria" value="<?php echo $claveCotiza ?>">
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
											<button type="button" class="btn btn-xs btn-primary Buscar"><i class="fa fa-search"></i></button>&nbsp;
											&nbsp;<button id="btn-enviarProducto" class="btn btn-xs btn-primary" >ENVIAR &nbsp;<i class="fa fa-truck"></i></button>&nbsp;&nbsp;
											<button class="btn btn-xs btn-danger d-none btn-CancelarEnvio" >CANCELAR</button>&nbsp;&nbsp;
										</div>
									</div>
									<div class="panel-body">
										<form  data-parsley-validate="true" class="w-75 m-auto d-none" id="buscar" action="?root=enviostock" method="POST">
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
								    <div class="row d-none cargandoProductos">
						        	<div class="col text-center">CARGANDO LISTA DE PRODUCTOS</div>
						        </div>
										<div class="row " id="forenvios">
											<div class="col-lg-4 col-md-12 col-sm-12">
												<label for="selectProducto" class="form-label">Seleccione el Producto</label>
												<select id="selectProducto" class="form-control"></select>
												<div class="text-center text-danger d-none noSelectProducto">SELECCIONE UN PRODUCTO</div>
											</div>
											<div class="col-lg-4 col-md-12 col-sm-12 d-none cargandoSucursales text-center">C A R G A N D O</div>
											<div class="col-lg-4 col-md-12 col-sm-12 d-none" id="Sucursales">
												<label for="Sucursal" class="form-label">Seleccione la Sucursal</label>
												<select id="Sucursal" class="form-control"></select>
											</div>
											<div class="col-lg-2 col-md-6 col-sm-6 d-none divStock">
											    <label for="inpStock" claass="form-label">Cantidad</label>
											    <input type="number" id="inpStock" class="form-control">
											</div>
											<div class="col-lg-2 col-md-6 col-sm-6 d-none divStock">
										    <label for="sendStock" class="form-label" >&nbsp; &nbsp;</label>
										    <button type="button"  id="sendStock" class="btn btn-primary btn-block">Enviar</button>
											</div>
										</div>
									</div>
									<div class="row d-none tablaStockTemp" style="margin-top: 3px">
										<div class="col" id="tablaStockTemp"></div>
									</div>
									<input type="hidden" id="claveOculta">
									<div class="row tablaEnvioStock">
										<div class="col">
											<table id="data-table-responsive" class="table table-striped table-bordered table-td-valign-middle w-100"><div class="respuesta"></div>
												<thead>
													<tr>
														<th width="5%" class="text-center">N&ordm;</th>
														<th width="15%" class="text-center">Destino</th>
														<th width="15%" class="text-center">Vendedor</th>
														<th width="15%" class="text-center">Fecha</th>
														<th width="15%" class="text-center">Estado</th>
														<th width="35%" class="text-center">Acciones</th>
													</tr>
												</thead><?php
												if (isset($_POST['inicio'])) {
										  		$sqlEnvios 		= mysqli_query($MySQLi, "SELECT * FROM envioStock WHERE desde='$miCiudad' AND fecha BETWEEN '$Inicio' AND '$Fin' ")or die(mysqli_error($MySQLi)."<br>Error en la linea: ".__LINE__);
										    }else{
										    	$sqlEnvios 		= mysqli_query($MySQLi, "SELECT * FROM envioStock WHERE desde='$miCiudad' AND fecha BETWEEN '$startBusqueda' AND '$fecha' ")or die(mysqli_error($MySQLi)."<br>Error en la linea: ".__LINE__);
										    } ?>
												<tbody><?php
								       		$idNumber 		= 1;
									    		while($dataEnvio = mysqli_fetch_assoc($sqlEnvios)){
									        	echo'<tr>
									        	<td class="text-center">'.$idNumber.'</td>
									        	<td>'.$dataEnvio["hasta"].'</td>';
									       		$idVendedor  = $dataEnvio["idUser"];
									        	$sqlVendedor = mysqli_query($MySQLi,"SELECT Nombres,Apellidos FROM Usuarios WHERE idUser='$idVendedor' ");
									        	$dataVendedor = mysqli_fetch_assoc($sqlVendedor);
									        	$Vendedor = $dataVendedor["Nombres"].' '.$dataVendedor["Apellidos"]; echo'
									        	<td>'.$Vendedor.'</td>';
									        	//$thisFecha = $dataEnvio['fecha'];
									        	//$fechaFormato = date("d-m-Y g:i a", strtotime($thisFecha)); 
									        	echo'
									        	<td class="text-center">'.$dataEnvio['fecha']." ".$dataEnvio['hora'] .'</td>';
									        	if($dataEnvio["estado"]==0) {
									        		echo'<td><button class="btn btn-block btn-info" style="pointer-events: none;">Procesando</button></td>';
									        	}elseif($dataEnvio["estado"]==1) {
														  echo'<td><button class="btn btn-block btn-success">Completado</button></td>';
									        	}else{
									        		echo'<td><button class="btn btn-block btn-danger">Cancelado</button></td>';
									        	} echo'</td>
									        	<td class="text-center">';
									            $idEnvio = $dataEnvio["idEnvio"];
									            if ($dataEnvio['estado']==0) {
									             	echo'<button class="btn btn-sm btn-danger cancelarTodoEnvio" type="button" id='.$idEnvio.' title="Cancelar envio" ><i class="fa fa-trash"></i></button>&nbsp;';
									             } echo'
									            <!--<button class="btn btn-sm btn-warning verEnvio"><i class="fa fa-eye"></i></button>&nbsp;&nbsp;-->
									            <a target="_blank" href="Reportes/pdf.php?ReporteEnvioStock='.$idEnvio.'" class="btn btn-sm btn-success" title="Descargar reporte de envio PDF" ><i class="fa fa-file-pdf"></i></a>
									        	</td></ tr>'; $idNumber++;
									    		} ?>
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
				<?php include 'php/script_envioproductos.php';?>
				<div class="modal fade" id="addMasProductos" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
					<div class="modal-dialog">
						<div class="modal-content">
							<div class="modal-header">
								<h4 class="modal-title" id="exampleModalLabel">¿Desea agregar más productos?</h4>
								<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
							</div>
							<div class="modal-body">
								<div class="row">
									<div class="col-6">
										<button class="btn btn-block btn-success btnSi" type="button" >SÍ</button>
									</div>
									<div class="col-6">
										<button class="btn btn-block btn-danger btnNo" type="button" >NO</button>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="modal fade" id="solicitudStock" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
					<div class="modal-dialog">
						<div class="modal-content">
							<div class="modal-header">
								<h4 class="modal-title" id="exampleModalLabel">Housto, tenemos un problema!!</h4>
							</div>
							<div class="modal-body">
								<div class="row">
									<div class="col text-center h2"><span class="text-danger">
										La cantidad que deseas enviar es mayor al stock disponible de esta sucursal, intenta nuevamente.</span>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="modal fade" id="addComentarios" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
					<div class="modal-dialog">
						<div class="modal-content">
							<div class="modal-header">
								<h4 class="modal-title" id="exampleModalLabel">¿Desea agregar alguna observación?</h4>
								<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
							</div>
							<div class="modal-body">
								<div class="row">
									<div class="col-6 pregunta">
										<button class="btn btn-block btn-success btn-Si" type="button" >SÍ</button>
									</div>
									<div class="col-6 pregunta">
										<button class="btn btn-block btn-danger btn-No" type="button" >NO</button>
									</div>
									<div class="col d-none observaciones">
										<textarea id="Observaciones" cols="30" rows="3" class="form-control" placeholder="Ingrese la observación u observaciones"></textarea>
									</div>
								</div>
								<div class="row mt-2">
									<div class="col d-none observaciones">
										<button class="btn btn-block btn-success btnCompletar" type="button" >Completar Envío</button>
									</div>
								</div>
							</div>
							
						</div>
					</div>
				</div>
				<div class="modal fade" id="modalRestoreEnvioStock" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
					<div class="modal-dialog">
						<div class="modal-content">
							<div class="modal-header">
								<h4 class="modal-title" id="exampleModalLabel">Notificacion de sistema</h4>
								<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
							</div>
							<div class="modal-body">
								<div class="row">
									<div class="col text-center">
										<h2 class="text-success">
											EL ENVIO FUE CANCELADO CORRECTAMENTE.
										</h2>
									</div>									
								</div>
							</div>
						</div>
					</div>
				</div>
			</body>
		</html>