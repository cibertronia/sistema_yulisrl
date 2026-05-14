<?php
	session_start();
	if (isset($_SESSION['idUser'])) { ?>
		<?php include 'php/meta.php'; ?>
		<link href="assets/css/apple/app.min.css" rel="stylesheet">
		<link href="assets/plugins/ionicons/css/ionicons.min.css" rel="stylesheet">
		<link href="assets/plugins/datatables.net-bs4/css/dataTables.bootstrap4.min.css" rel="stylesheet">
		<link href="assets/plugins/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css" rel="stylesheet">
		<link href="assets/plugins/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css" rel="stylesheet">
		<link href="assets/plugins/blueimp-gallery/css/blueimp-gallery.min.css" rel="stylesheet">
		<link href="assets/plugins/blueimp-file-upload/css/jquery.fileupload.css" rel="stylesheet">
		<link href="assets/plugins/blueimp-file-upload/css/jquery.fileupload-ui.css" rel="stylesheet">
		<script src="https://cdn.jsdelivr.net/npm/sweetalert2@8"></script>
		<div class="respuesta"></div> <?php
		include 'includes/conexion.php';

		$idProducto =	$_POST['idProducto'];
		$Producto 	=	$_POST['Producto'];
		$Marca		=	$_POST['Marca'];
		$Modelo 	=	$_POST['Modelo'];
		$Descripcion=	$_POST['Descripcion'];

		//CENTRAL COCHABAMBA
		$StockCB	=	$_POST['StockCB'];
		$PrecioCB	=	$_POST['PrecioCB'];
		$ObservCB	=	$_POST['ObservacionesCB'];

		//SUCURSAL LA PAZ
		$StockLP	=	$_POST['StockLP'];
		$PrecioLP	=	$_POST['PrecioLP'];
		$ObservLP	=	$_POST['ObservacionesLP'];

		//SUCURSAL SANTA CRUZ
		$StockSC	=	$_POST['StockSC'];
		$PrecioSC	=	$_POST['PrecioSC'];
		$ObservSC	=	$_POST['ObservacionesSC'];

		//SUCURSAL TARIJA
		$StockTJ	=	$_POST['StockTJ'];
		$PrecioTJ	=	$_POST['PrecioTJ'];
		$ObservTJ	=	$_POST['ObservacionesTJ'];

		//STOCK TOTAL
		$StockTotal =	$StockCB+$StockLP+$StockSC+$StockTJ;
		$img	 	=	basename($_FILES['imagen']['name']);
        
		/* VERIFICAMOS SI NO SE CAMBIÓ LA IMAGEN 	*/
		if (empty($img)) { //Esto indica que el nombre de la imagen está vacío
			//	ACTUALIZAMOS SOLO LOS DATOS DEL PRODUCTO
			$changeData	=	mysqli_query($MySQLi,"UPDATE Productos SET 
				Producto='$Producto', 
				Marca='$Marca', 
				Modelo='$Modelo', 
				Descripcion='$Descripcion', 
				PrecioCB='$PrecioCB', 
				ObservacionesCB='$ObservCB', 
				PrecioLP='$PrecioLP', 
				ObservacionesLP='$ObservLP', 
				PrecioSC='$PrecioSC', 
				ObservacionesSC='$ObservSC', 
				PrecioTJ='$PrecioTJ', 
				ObservacionesTJ='$ObservTJ',
			    StockCB='$StockCB', 
				StockLP='$StockLP', 		
				StockSC='$StockSC', 		
				StockTJ='$StockTJ', 				
				StockTotal='$StockTotal' 

				WHERE idProducto='$idProducto' ")or die(mysqli_error($MySQLi)."<br>Error en la línea: ".__LINE__);
			if ($changeData) { mysqli_close($MySQLi); ?>
				<script type="text/javascript">
					Swal.fire({
					  type: 'success',
					  title: 'Producto actualizados',
					  animation: false,
					  customClass: {
					  	popup: 'animated bounceInDown'
					  }
					})
					setTimeout(function(){
						 location.replace("/?root=productos2");
						//location.replace("/?root=editarproducto");
						
						//location.reload(true);
						
					},2000)
				</script> <?php exit();
			}else{ mysqli_close($MySQLi); ?>
				<script type="text/javascript">
					Swal.fire({
					  type: 'error',
					  title: 'Error al actualizar datos',
					  animation: false,
					  customClass: {
					  	popup: 'animated shake'
					  }
					})
				</script> <?php exit();
			}
		}else{
			/*	VERIFICAMOS SI YA EXISTE EL NOMBRE DE LA IMAGEN 	*/
			$nameImagen	=	mysqli_query($MySQLi,"SELECT * FROM Productos WHERE Imagen='$img'");
			$result 	=	mysqli_num_rows($nameImagen);

			if ($result>0) { mysqli_close($MySQLi); ?>
				<script type="text/javascript">
					Swal.fire({
					  type: 'error',
					  title: 'El nombre de la imagen ya existe',
					  animation: false,
					  customClass: {
					  	popup: 'animated shake'
					  }
					})
					setTimeout(function(){
						location.replace("/?root=editarproducto");
					},1000);
				</script> <?php
			}else{
				/*	SI EL NOMBRE DE LA IMAGEN NO EXISTE, GURADAMOS LOS CAMBIOS	*/
				$ruta 		=	"Productos/";
				$ruta 		= 	$ruta . basename($_FILES['imagen']['name'], $ruta);	
				if (move_uploaded_file($_FILES['imagen']['tmp_name'], $ruta)) {
					$subirIMG	=	mysqli_query($MySQLi,"UPDATE Productos SET 
						Producto='$Producto', 
						Marca='$Marca', 
						Modelo='$Modelo', 
						Descripcion='$Descripcion', 
						PrecioCB='$PrecioCB', 
						ObservacionesCB='$ObservCB', 
						PrecioLP='$PrecioLP', 
						ObservacionesLP='$ObservLP', 
						PrecioSC='$PrecioSC', 
						ObservacionesSC='$ObservSC', 
						PrecioTJ='$PrecioTJ', 
                        StockCB='$StockCB', 
                        StockLP='$StockLP', 		
                        StockSC='$StockSC', 		
                        StockTJ='$StockTJ', 										
						ObservacionesTJ='$ObservTJ', 
						StockTotal='$StockTotal', 
						Imagen='$img' 

						WHERE idProducto='$idProducto' ")or die(mysqli_error($MySQLi)."<br>Error en la línea: ".__LINE__);
					if ($subirIMG) { //mysqli_close($MySQLi); ?>
						<script type="text/javascript">
							Swal.fire({
							  type: 'success',
							  title: 'Producto actualizado',
							  animation: false,
							  customClass: {
							  	popup: 'animated bounceInDown'
							  }
							})
							setTimeout(function(){
								location.replace("/?root=editarproducto");
							},2000)
						</script> <?php exit();
					}else{ mysqli_close($MySQLi); ?>
						<script type="text/javascript">
							Swal.fire({
							  type: 'error',
							  title: 'Update Error',
							  animation: false,
							  customClass: {
							  	popup: 'animated shake'
							  }
							})
						</script> <?php exit();
					}
				}else{ mysqli_close($MySQLi); ?>
					<script type="text/javascript">
						Swal.fire({
						  type: 'error',
						  title: 'Error al actualizar',
						  animation: false,
						  customClass: {
						  	popup: 'animated shake'
						  }
						})
					</script> <?php exit();
				}

				// SI CAMBIAN LA IMAGEN, BORRAMOS LA ANTERIOR PARA O OCUAPAR ESPACIO
				$queryIMG	=	mysqli_query($MySQLi,"SELECT * FROM Productos WHERE idProducto='$idProducto'");
				$dataIMG 	= 	mysqli_fetch_assoc($queryIMG);
				$borrar 	=	$dataIMG['Imagen'];
				/* 	BORRAMOS LA IMAGEN ANTERIOR EN LA CARPETA /Productos*/
				$files = glob("Productos/$borrar"); //obtenemos todos los nombres de los ficheros
				foreach($files as $file){
				    if(is_file($file))
				    unlink($file); //elimino el fichero
				}
				
			}
		}
	}else{
		header("Location: /?root=editarproducto ");
	}
?>