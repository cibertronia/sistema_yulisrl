<?php

require __DIR__ . '/includes/App/Controllers/ProductsController.php';
use App\Controllers\ProductsController;

	session_start();
	error_reporting(0);
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
		$Producto 	=	$_POST['Producto'];
		$Marca		=	$_POST['Marca'];
		$Modelo 	=	$_POST['Modelo'];
		
		$ProdHomo 	=	$_POST['ProdHomo'];
		//$activityEconomic="465000";

		$Descripcion=	$_POST['Descripcion'];

		// //CENTRAL COCHABAMBA
		// $StockCB	=	$_POST['StockCB'];if (!$StockCB) {$StockCB =0;}
		// $PrecioCB	=	$_POST['PrecioCB'];if (!$PrecioCB) {$PrecioCB =0;}
		// $ObservCB	=	$_POST['ObservacionesCB'];if (!$ObservCB) {$ObservCB ='Sin Observacion';}

		// //SUCURSAL LA PAZ
		// $StockLP	=	$_POST['StockLP'];if (!$StockLP) {$StockLP =0;}
		// $PrecioLP	=	$_POST['PrecioLP'];if (!$PrecioLP) {$PrecioLP =0;}
		// $ObservLP	=	$_POST['ObservacionesLP'];if (!$ObservLP) {$ObservLP ='Sin Observacion';}

		// //SUCURSAL SANTA CRUZ
		// $StockSC	=	$_POST['StockSC'];if (!$StockSC) {$StockSC =0;}
		// $PrecioSC	=	$_POST['PrecioSC'];if (!$PrecioSC) {$PrecioSC =0;}
		// $ObservSC	=	$_POST['ObservacionesSC'];if (!$ObservSC) {$ObservSC ='Sin Observacion';}

		// //SUCURSAL TARIJA
		// $StockTJ	=	$_POST['StockTJ'];if (!$StockTJ) {$StockTJ =0;}
		// $PrecioTJ	=	$_POST['PrecioTJ'];if (!$PrecioTJ) {$PrecioTJ =0;}
		// $ObservTJ	=	$_POST['ObservacionesTJ'];if (!$ObservTJ) {$ObservTJ ='Sin Observacion';}

		// //STOCK TOTAL
		// $StockTotal =	$StockCB+$StockLP+$StockSC+$StockTJ;

		// $ruta 		=	"Productos/";
		// $ruta 		= 	$ruta . basename($_FILES['imagen']['name'], $ruta);	
		// $img	 	=	basename($_FILES['imagen']['name']);		

		/* 	VERIFICAMOS SI EL NOMBRE DE LA IMAGEN YA EXISTE 	*/
		$queryCo	=	mysqli_query($MySQLi,"SELECT * FROM Productos WHERE Imagen='$img'");
		$result 	=	mysqli_num_rows($queryCo);	
		
		$queryModelo	=	mysqli_query($MySQLi,"SELECT * FROM Productos WHERE Modelo='$Modelo'");
		$resultMo 	=	mysqli_num_rows($queryModelo);	

		if ($result>0 || $resultMo>0) { mysqli_close($MySQLi); ?>
			<script type="text/javascript">
				Swal.fire({
				  type: 'error',
				  title: 'EL PRODUCTO YA EXISTE - POR FAVOR REVISAR Y EDITAR EL STOCK',
				  animation: false,
				  customClass: {
				  	popup: 'animated shake'
				  }
				})
				setTimeout(function(){
					location.replace("/?root=editarproducto");
				},5000);
			</script> <?php
		}else{
			$response = new ProductsController();
			$newProducto = $response->store();
			
			if ($newProducto['success']) { ?>
				<script type="text/javascript">
					Swal.fire({
					  type: 'success',
					  title: 'Producto Agregado',
					  animation: false,
					  customClass: {
						  popup: 'animated bounceInDown'
					  }
					})
					setTimeout(function(){
						location.replace("/?root=productos2");
					},2000)
				</script> <?php exit();
			}else{ mysqli_close($MySQLi); ?>
				<script type="text/javascript">
					Swal.fire({
					  type: 'error',
					  title: 'Upload Error',
					  animation: false,
					  customClass: {
						  popup: 'animated shake'
					  }
					})

				</script> <?php exit();
			}
		}
	}else{
		header("Location: /?root=editarproducto ");
	}
?>