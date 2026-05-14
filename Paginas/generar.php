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
	$miCiudad 	=	$datosUser['Ciudad'];

	include 'includes/App/Models/Sucursal.php';
	use App\Models\Sucursal;

	$sucursalesModel = new Sucursal();
	$sucursales = $sucursalesModel->all();         
?>
<!DOCTYPE html>
<html lang="es">
	<head>
		<title>GENERAR COTIZACION</title>
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
	<body><?php include 'php/loader.php'; ?>
		<div id="page-container" class="fade page-sidebar-fixed page-header-fixed" data-sucursales='<?=json_encode($sucursales)?>'><?php
			include 'php/top_menu.php';
			include 'php/left_menu_generar.php';?>			
			<div id="content" class="content"><div class="respuesta"></div>
				<!-- FORMULARIO PARA NUEVA COTIZACION -->
				<div class="row">
					<div class="col-md-2"></div>
					<div class="col-md-8">
						<div class="panel panel-inverse">
							<div class="panel-heading">
								<h4 class="panel-title">REGISTRAR NUEVA COTIZACION&nbsp;&nbsp;SUCURSAL &nbsp;&nbsp;<strong><?php echo strtoupper($miCiudad) ?></strong></h4>
								<!-- <button class="btn btn-xs btn-danger cancelarRegNewCotiza">CANCELAR</button> -->
							</div>
							<div class="panel-body">
								<div class="row d-none cotizaOK">
									<div class="col">
										<div class="alert alert-success fade show text-center">
											<strong>COTIZACION GENERADA EXITOSAMENTE!</strong>
										</div>
									</div>
								</div>
								<form id="newCotizacion">
									<div class="row text-center checkOptions">
										<div class="col-md-6">
											<label for="optionUser">Cliente Existente ?</label>
											<p>
												<span class="text-white bg-danger" style="padding: 3px;border-radius: 3px">NO</span>&nbsp;&nbsp;&nbsp;
												<input id="opttionUser" name="optionUser" checked="" type="checkbox" class="js-switch">&nbsp;&nbsp;&nbsp;
												<span class="text-white bg-success" style="padding: 3px;border-radius: 3px"> SI </span>
											</p>											
										</div>
										<div class="col-md-6 ClienteXistente">
											<label for="CotizaOldCliente">Cliente</label>
											<select name="clienteOld" id="CotizaOldCliente" class="form-control">
												<option value="0" selected disabled>Seleccione un Cliente</option>
												<?php
													$queryCl	=	mysqli_query($MySQLi,"SELECT * FROM Clientes ORDER BY Apellidos ASC");
													while ($dataCl =mysqli_fetch_assoc($queryCl)) {
														echo "<option value=".$dataCl['idCliente'] .">".$dataCl['Nombres']." ".$dataCl['Apellidos']. "</option>";
													}
												?>
											</select>
										</div>
									</div>
									<div class="row mt-1 d-none datosPersonales">
										<div class="col">
											<label for="ClienteNombre">Nombre <span class="text-danger">( * )</span></label>
											<input type="hidden" name="ClaveTemporalCotiza" id="ClaveGeneradaAleatoria" value="<?php echo $claveCotiza ?>">
											<input type="hidden" name="action" value="GenerarCotizacion">
											<input type="hidden" name="Cliente_Existente" id="Cliente_Existente" value="0">
											<input type="hidden" name="miCiudad" id="miCiudad" value="<?php echo $miCiudad ?>">
											<input type="text" name="Nombre" id="ClienteNombre" class="form-control" placeholder="Nombre">
											<div class="text-center text-danger d-none emptyCliente_Nombre">Campo nombre está vacío</div>
										</div>
										<div class="col">
											<label for="ClienteApellido">Apellido <span class="text-danger">( * )</span></label>
											<input type="text" name="Apellido" id="ClienteApellido" class="form-control" placeholder="Apellido">
											<div class="text-center text-danger d-none emptyCliente_Apellido">Campo apellido está vacío</div>
										</div>
									</div>
									<div class="row mt-1 d-none datosPersonales">
										<div class="col">
											<label for="ClienteCorreo">Correo</label>
											<input type="email" name="Correo" id="ClienteCorreo" class="form-control" placeholder="Correo electrónico">
											<div class="text-center text-danger d-none emptyCliente_Correo">Campo correo está vacío</div>
										</div>
										<div class="col">
											<label for="ClienteEmpresa">Empresa</label>
											<input type="text" name="Empresa" id="ClienteEmpresa" class="form-control" placeholder="Empresa" value="">
											<div class="text-center text-danger d-none emptyCliente_Empresa">Campo empresa está vacío</div>
										</div>
									</div>
									<div class="row mt-1 d-none datosPersonales">
										<div class="col">
											<label for="ClienteNIT">NIT</label>
											<input type="text" name="NIT" id="ClienteNIT" class="form-control" placeholder="Número de NIT" value="">
											<div class="text-center text-danger d-none emptyCliente_NIT">Campo NIT está vacío</div>
										</div>
										<div class="col">
											<label for="ClienteCiudad">Ciudad <span class="text-danger">( * )</span></label>
											<select name="Ciudad" id="ClienteCiudad_" class="form-control">
												<option disabled selected>Seleccione Ciudad</option>
												<option value="Chuquisaca">Chuquisaca</option>
												<option value="La Paz">La Paz</option>
												<option value="Cochabamba">Cochabamba</option>
												<option value="Oruro">Oruro</option>
												<option value="Potosí">Potosí</option>
												<option value="Tarija">Tarija</option>
												<option value="Santa Cruz">Santa Cruz</option>
												<option value="Beni">Beni</option>
												<option value="Pando">Pando</option>
											</select>
											<div class="text-center text-danger d-none emptyCliente_Ciudad">Campo ciudad está vacío</div>
										</div>
									</div>
									<div class="row mt-1 d-none datosPersonales">
										<div class="col">
											<label for="ClienteCell">Teléfono Celular <span class="text-danger">( * )</span></label>
											<input type="tel" name="Celular" id="ClienteCell" class="form-control" placeholder="Teléfono celular" value="">
											<div class="text-center text-danger d-none emptyCel">Celular está vacío</div>
										</div>
										<div class="col">
											<label for="ClienteOtro">Teléfono fijo</label>
											<input type="tel" name="Otro" id="ClienteOtro" class="form-control" placeholder="Teléfono otro" value="">
											<div class="text-center text-danger d-none emptyCliente_Cell">Campo teléfono otro está vacío</div>
										</div>
									</div>
									<div class="row mt-1 d-none datosPersonales">
										<div class="col">
											<label for="ClienteDireccion">Dirección</label>
											<textarea name="Direccion" id="ClienteDireccion" class="form-control" placeholder="Dirección" value=""></textarea>
											<div class="text-center text-danger d-none emptyCliente_Direccion">Campo dirección está vacío</div>
										</div>
										<div class="col">
											<label for="ClienteComentario">Comentarios</label>
											<textarea name="Comentarios" id="ClienteComentario" class="form-control" placeholder="Comentarios" value=""></textarea>
											<div class="text-center text-danger d-none emptyCliente_Comentario">Campo comentarios está vacío</div>
										</div>
									</div>
									<div class="row mt-2 creditosCliente"></div>
									<div class="row mt-1 d-none infoProducto">
										<div class="col-12">
											<label for="ClienteProducto">Producto <span class="text-danger">( * )</span></label>
											<select name="Producto" id="ClienteProducto" class="form-control" style="width: 100%;">
												<option></option>
											</select>											
											<div class="text-danger d-none noSelectProd">No ha seleccionado un producto</div>
										</div>
									</div>
									<div class="row mt-2">
									<?php foreach($sucursales as $item){ ?>
										<div class="col col-md d-none PreciosProductoSelected text-center">
											<label for="ProdExistencia_<?=$item['idSucursal']?>"><?=$item['Sucursal']?></label>
											<input type="text" id="ProdExistencia_<?=$item['idSucursal']?>" class="form-control text-center" disabled>
										</div>
									<?php }	?>
									</div>
									<div class="row mt-2 d-none PreciosProductoSelected">
										<div class="col">
											<label for="PrecioLista">Precio Lista</label>
											<input type="text" name="PrecioLista" id="PrecioLista" class="form-control" placeholder="Precio de Lista" disabled>
										</div>
										<div class="col">
											<label for="CantidadProducto">Cantidad</label>
											<input type="text" name="Cantidad" id="CantidadProducto" class="form-control">
											<div class="text-danger d-none CantidadEmpty">No ha indicado una cantidad</div>
										</div>
										<div class="col">
											<label for="PrecioEspecial">Precio Especial</label>
											<input type="text" name="PrecioEspecial" id="PrecioEspecial" class="form-control" placeholder="Precio Especial">
											<div class="text-danger d-none emptyPrecioEsp">No ha indicado el precio especial</div>
										</div>
									</div>
									<div class="row mt-3">
										<div class="col text-right">
											<button title="Agregar producto" class="btn btn-xs btn-info Add_Producto d-none PreciosProductoSelected"><i class="fa fa-plus"></i> &nbsp;&nbsp;AGREGAR PRODUCTO &nbsp;<i class="fas fa-spinner fa-pulse d-none efectAddProduct"></i></button>
										</div>
									</div>
									<div class="row mt-2 d-none showTableProd">
										<div class="col" id="respuesta"></div>
									</div>
									<div class="row mt-3 d-none datosAdicionales">
										<div class="col">
											<label for="FormaPago">Forma de Pago <span class="text-danger">( * )</span></label>
											<select name="formaPago" id="FormaPago" class="form-control">
												<option disabled selected>Forma de pago</option>
												<option value="Efectivo">Efectivo</option>
												<option value="Cheque">Cheque</option>
												<option value="Depósito">Depósito</option>
												<option value="Transferencia">Tranferencias</option>
												<option value="Pago Por QR">Pago por QR</option>
											</select>
											<div class="text-danger d-none emptyFormaPago">No ha indicado la forma de pago</div>
										</div>
										<div class="col">
											<label for="FinOferta">Fecha Límite de Oferta <span class="text-danger">( * )</span></label>
											<input type="date" name="fechaFin" id="FinOferta" class="form-control" value="<?php echo $fecha ?>">
											<div class="text-danger d-none emptyFinOferta">No ha indicado la fecha límite</div>
										</div>
										<div class="col">
											<label for="TiempoEntrega">Tiempo de entrega <span class="text-danger">( * )</span></label>
											<input type="text" name="tiempoEntrega" id="TiempoEntrega" class="form-control">
											<div class="text-danger d-none emptyTiempoEntrega">No ha indicado tiempo de entrega</div>
										</div>
									</div>
									<div class="row mt-3 d-none datosAdicionales">
										<div class="col">
											<label for="Observaciones">Observaciones</label>
											<textarea name="observaciones" id="Observaciones" rows="8" class="form-control" placeholder="Observaciones" 
											value="">Precio especial incluye impuestos de ley, entrega en tienda *Garantía fallas de fábrica los primeros 3 meses, asistencia técnica en tienda gratis por 1 año presentando la nota de entrega emitida por importadora Yuli. *No cubrimos mal uso del equipo envíos a provincias u otros de departamentos lo paga el cliente. *El precio especial es garantizado hasta la fecha del vencimiento de la cotización, pasada la fecha los precios son sujetos a modificación, rogamos actualice según el nuevo inventario. El stock de los productos es muy variable según la temporada.</textarea>
										</div>
									</div>
									<div class="row mt-3 d-none btnSaveCotiza">
										<div class="col">
											<button type="submit" class="btn btn-xs btn-primary form-control guardaNewCotiza">GUARDAR COTIZACION &nbsp;<i class="fas d-none efectSaveCotiza fa-spinner fa-pulse"></i></button>
										</div>
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>
			</div>

			<!-- Modal EDITAR DATOS CANTIDAD Y PRECIO OFERTA DE LA TABLA -->
			<div class="modal fade" id="editProdTemp">
				<div class="modal-dialog modal-sm">
					<div class="modal-content">
						<!-- <div class="modal-header">
							<h4 class="modal-title">Editar Datos del Producto</h4>
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
						</div> -->
						<div class="modal-body w-75 m-auto">
							<div class="row text-center">
								<div class="col text-center">
									<label for="CantidadProdTemp">Cantidad</label>
									<input type="hidden" name="ClaveTemp" id="Clave_Temp">
									<input type="hidden" name="id" id="idProdTemp">
									<input type="text" name="Cantidad" id="CantidadProdTemp" class="form-control text-center">
								</div>
							</div>
							<div class="row mt-2 text-center">
								<div class="col text-center">
									<label for="PrecioProdTemp">Precio Especial</label>
									<input type="text" name="PrecioEspecial" id="PrecioProdTemp" class="form-control text-center">
								</div>
							</div>
							<div class="row mt-2">
								<div class="col">
									<button class="btn btn-xs btn-success form-control actualizarProductoTemp">ACTUALIZAR PRODUCTO</button>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			
		
			<a href="javascript:;" class="btn btn-icon btn-circle btn-primary btn-scroll-to-top fade" data-click="scroll-top"><i class="fa fa-angle-up"></i></a>
			<?php include 'php/footer.php'; ?>
		</div>
		<?php include 'php/script_generar.php'; ?>
	</body>
</html>