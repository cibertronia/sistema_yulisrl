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
	if ($_SESSION['Rango']=='2') { ?>
		<!DOCTYPE html>
		<html lang="es">
			<head>
				<title>EDITAR PRODUCTOS</title>
				<?php include 'php/meta.php'; ?>
				<link href="assets/css/apple/app.min.css" rel="stylesheet">
				<link href="assets/plugins/ionicons/css/ionicons.min.css" rel="stylesheet">
				<link href="assets/plugins/datatables.net-bs4/css/dataTables.bootstrap4.min.css" rel="stylesheet">
				<link href="assets/plugins/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css" rel="stylesheet">
				<link href="assets/plugins/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css" rel="stylesheet">
				<link href="assets/plugins/blueimp-gallery/css/blueimp-gallery.min.css" rel="stylesheet">
				<link href="assets/plugins/blueimp-file-upload/css/jquery.fileupload.css" rel="stylesheet">
				<link href="assets/plugins/blueimp-file-upload/css/jquery.fileupload-ui.css" rel="stylesheet">
				<link href="assets/plugins/summernote/dist/summernote.css" rel="stylesheet">
			</head>
			<body>
				<?php include 'php/loader.php'; ?>
				
				
				<div class="modal fade" id="add1" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-sm" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title" id="Label"><b>AGREGAR</b>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </h4>
                            </div>
                            <div class="modal-body">
                                <div class="form-group">
									<label for="email" class="control-label">Fecha</label>
									<input class="form-control" id="fecha1" value="<?php date('Y-m-d')?>" name="fecha1" type="date" required>
                				</div>                
                                <div class="form-group">
									<label for="sucursal" class="control-label">Sucursal</label>
									<select class="form-control" id="sucursal1" name="sucursal1">
										<?php
										foreach ($sucursales as $item) {
											if (($miCiudad == $item['Sucursal'] || $_SESSION['Rango'] == '2')) {
												echo "<option value='" . $item['idSucursal'] . "'>" . $item['Sucursal'] . "</option>";
											}
										}
										?>
									</select>
								</div>
                
								<div class="form-group">
									<label for="email" class="control-label">Cantidad
									<input class="form-control txtNum" step="1" id="cantidad1" required value="" name="cantidad1" type="number">
								</div>                      

                        		<input type="hidden" id="idusuario" value="<?php echo $idUser ?>">                                
                                
                                <div class="form-group">
            						<label for="email" class="control-label">Notas</label>
                            		<textarea id="notas1" name="notas1" rows="3" style="width:100%" maxlength="255"></textarea>
          						</div>

                                <label id="lblmsg1" name="lblmsg1" for="email" class="control-label divMsg">*</label>                                
                            </div>    
                            <div class="modal-footer">
                                <button id="btadd11" name="btadd1" type="button" class="btn btn-primary btadd1">Agregar</button>
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                            </div>
                        </div>
                    </div>
                </div>
                
                
                    
				<div id="page-container" class="fade page-sidebar-fixed page-header-fixed" data-sucursales='<?= json_encode($sucursales) ?>'>
					<?php
						include 'php/top_menu.php';
						include 'php/left_menu_editProducto.php';
							
					?>
					<div id="content" class="content"><div class="respuesta"></div>						

						<!-- 	EDITAR PRODUCTO	 -->
						<div class="row d-none editProducto w-75 m-auto" id="editProducto">
							<div class="col">
								<div class="panel panel-inverse">
									<div class="panel-heading">
										<h4 class="panel-title">EDITAR PRODUCTO</h4>
										<button class="btn btn-xs btn-danger cancelarEditProducto">CANCELAR</button>
									</div>
									<div class="panel-body">
										<form enctype="multipart/form-data" id="product-edit-form" action="/includes/api/products.php" data-parsley-validate="true">
											<input type="hidden" name="id" id="id">
                                    		<input type="hidden" name="_method">
                                    		<input type="hidden" name="image_file">
											<div class="row text-center w-50 m-auto">
												<div class="col">
													<div id="imgx_"></div>
												</div>
												<div class="col">
													<span class="btn btn-primary fileinput-button mt-4">
						                                <i class="fa fa-plus"></i>
						                                <span>Cambiar imagen</span>
						                                <input type="file" name="imagen" id="img_file_" accept="image/png, image/jpeg, image/jpg" >
						                            </span>
												</div>
											</div>
											<div class="row mt-3">
												<div class="col">
													<label for="ProdNombre_">Nombre del producto</label>
													<input type="hidden" name="idProducto" id="idProducto_">
													<input type="hidden" name="idUser_" id="idUser_" value="<?php echo $idUser?>">
													
													<input type="text" name="Producto" id="ProdNomnbre_" class="form-control" placeholder="Producto" data-parsley-required="true">
													<div class="text-center text-danger d-none emptyProdNombre_">Campo producto está vacío</div>
												</div>
												<div class="col">
													<label for="ProdMarca_">Marca del producto</label>
													<input type="text" name="Marca" id="ProdMarca_" class="form-control" placeholder="Marca"data-parsley-required="true">
													<div class="text-center text-danger d-none emptyProdMarca_">Campo marca está vacío</div>
												</div>
												<div class="col">
													<label for="ProdModelo_">Modelo del producto</label>
													<input type="text" name="Modelo" id="ProdModelo_" class="form-control" placeholder="Modelo"data-parsley-required="true">
													<div class="text-center text-danger d-none emptyProdModelo_">Campo modelo está vacío</div>
												</div>
											</div>
											<div class="row mt-3 w-75 m-auto">
												<div class="col text-center">
													<label for="ProdDescripcion_">Descripción del producto</label>
													<textarea name="Descripcion" id="ProdDescripcion_" data-parsley-required="true" cols="30" rows="5" class="form-control" placeholder="Descripción"></textarea>
													<div class="text-center text-danger d-none emptyProductoDescripcion_">Campo descripción está vacío</div>
												</div>
											</div>

											<!-- SUCURSALES -->
											<div class="row mt-3">
												<?php
													foreach ($sucursales as $item) {
														if (($miCiudad == $item['Sucursal'] || $_SESSION['Rango'] == '2')) {
												?>
												<div class="col col-md-6 mb-4">
													<span class="form-control bg-success text-white text-center">
														<?php echo $item['title'] ?>
													</span>
													<div class="row mt-2">
														<div class="col col-md-6">
															<label for="ProdStock_<?=$item['idSucursal']?>">STOCK</label>
															<input type="text" value="" name="Stock_<?=$item['idSucursal']?>" id="ProdStock_<?=$item['idSucursal']?>" class="form-control" data-parsley-type="integer" placeholder="Stock" data-parsley-required="false" readonly>
															<div class="text-center text-danger d-none emptyStock_<?=$item['idSucursal']?>">Campo stock
																está vacío</div>
														</div>
														<div class="col col-md-6">
															<label for="ProdPrecio_<?=$item['idSucursal']?>">PRECIO</label>
															<input type="text" name="Precio_<?=$item['idSucursal']?>" id="ProdPrecio_<?=$item['idSucursal']?>" data-parsley-type="number" class="form-control" placeholder="Precio" data-parsley-required="true">
															<div class="text-center text-danger d-none emptyPrecio_<?=$item['idSucursal']?>">Campo precio
																está vacío</div>

														</div>
														<div class="col text-center mt-2">
															<label for="ProdObserv_<?=$item['idSucursal']?>">OBSERVACIONES</label>
															<textarea name="Observaciones_<?=$item['idSucursal']?>" id="ProdObserv_<?=$item['idSucursal']?>" data-parsley-required="false" cols="30" rows="4" class="form-control" placeholder="Observaciones"></textarea>
														</div>
													</div>
												</div>
												<?php
														}
													}
												?>
											</div>


											<div class="row mt-3">
												<div class="col">
													<button type="submit" class="btn btn-xs btn-primary form-control">ACTUALIZAR PRODUCTO</button>
												</div>
											</div>
											<div class="row mt-3">
												<div class="col">
													<a class="btn btn-xs btn-primary form-control btnadd1" href="#" data-toggle="modal" data-target="#add1">AGREGAR STOCK</a>
												</div>												
											</div>
											<div class="row mt-3">
												<div class="col">
													<table id="tbcompras" name="tbcompras" class="table table-striped table-bordered table-td-valign-middle w-100">
													    <thead>
													    <tr>
													        <th>Fecha</th>
													        <th>Usuario</th>													        
													        <th>CB</th>
													        <th>LP</th>
													        <th>SC</th>
													        <th>FR</th>
													        <th>Detalles  V=Ventas C=Compras E=Extraidos R=Recibidos</th>
													    </tr>    
													    </thead>
													    <tbody>
													        
													    </tbody>
													</table>
												</div>
											</div>
										</form>
									</div>
								</div>
							</div>
						</div>

						<!-- 	TABLA PRODUCTO	 -->
						<div class="row tableProductos">
							<div class="col-md-12">
								<div class="panel panel-inverse">
									<div class="panel-heading">
										<h4 class="panel-title">EDITAR PRODUCTO</h4>
										<div class="panel-heading-btn">
											&nbsp;<a href="?root=nuevoproducto" class="btn btn-xs btn-primary">AGREGAR PRODUCTO</a>
											<!-- <button class="btn btn-xs btn-primary AddNewProductoBTN">AGREGAR PRODUCTO</button>&nbsp;&nbsp;&nbsp; -->
											<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
											<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-success" data-click="panel-reload"><i class="fa fa-redo"></i></a>
											<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>
											<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-danger" data-click="panel-remove"><i class="fa fa-times"></i></a>
										</div>
									</div>
									<div class="panel-body">
										<form id="findProducto" style="width: 50%; margin: 0 auto">
											<div class="row">
												
												<div class="col text-center">
													<label for="byModelo">Buscar Producto por Modelo</label>
													<!-- <input type="hidden" name="action" value="MostrarClientes"> -->
													<input type="text" name="modelo" id="byModelo" class="form-control" placeholder="ingrese el modelo">
													<div class="text-center text-danger d-none noModeloProd">El campo modelo no puede quedar vacío.</div>
												</div>
												<div class="col">
													<label for="btnFind">&nbsp;&nbsp;&nbsp;</label>
													<button title="Buscar producto" type="submit" class="btn btn-xs btn-danger form-control buscarProductoBtn">BUSCAR PRODUCTO</button>
												</div>
											</div>
										</form><br>
										<div id="respuestaFindProducto"></div><br><br>
										<div class="row">
											<div class="col">
												<?php
													$consulTotalProductos	=	mysqli_query($MySQLi,"SELECT * FROM Productos");
													$totalProductosRegist 	=	mysqli_num_rows($consulTotalProductos);
													echo "PRODUCTOS REGISTRADOS : <strong class='text-danger' style='letter-spacing: 1px'>".$totalProductosRegist."</strong>" ;
												?>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<a href="javascript:;" class="btn btn-icon btn-circle btn-primary btn-scroll-to-top fade" data-click="scroll-top"><i class="fa fa-angle-up"></i></a>
					<?php include 'php/footer.php'; ?>
				</div>
				<?php include 'php/script_productos.php'; ?>
				
			</body>
		</html>
		<script>
			$(document).ready(function () {				
				$("#add1").on("hidden.bs.modal", function (e) {
					$("#fecha1").val("");
					$("#cantidad1").val("");
					$("#notas1").val("");
					$("#lblmsg1").text("");
				});

			});
		</script>
		<?php include 'php/fun_productos.php';
	}else{ ?>
		<script type="text/javascript">
			location.replace("?root=404");
		</script>
		
		
		
		<?php
	}
?>