<?php
	$idUser 		=	$_SESSION['idUser'];
	$ConsltaUser=	mysqli_query($MySQLi,"SELECT * FROM Usuarios WHERE idUser='$idUser' ");
	$datosUser 	=	mysqli_fetch_assoc($ConsltaUser);
	$miCiudad 	=	$datosUser['Ciudad'];
?>
<!DOCTYPE html>
<html lang="es">
	<head>
		<title>LOGS</title>
		<?php include 'php/meta.php'; ?>
		<link href="assets/css/apple/app.min.css" rel="stylesheet">
		<link href="assets/plugins/ionicons/css/ionicons.min.css" rel="stylesheet">
		<link href="assets/plugins/jvectormap-next/jquery-jvectormap.css" rel="stylesheet">
		<link href="assets/plugins/bootstrap-datepicker/dist/css/bootstrap-datepicker.css" rel="stylesheet">
	</head>
	<body><?php 
		include 'php/loader.php'; ?>
		<div id="page-container" class="fade page-sidebar-fixed page-header-fixed"><?php
			include 'php/top_menu.php';
			include 'php/left_menu_inicio.php';?>
			<div id="content" class="content">
			    <table class='table'>
            <thead>
                <tr>
                    <th>FECHA</th>
                    <th>USUARIO</th>
                    <th>ACCION</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    $rowP = mysqli_query($MySQLi, "SELECT * from logs order by fecha");    
                    while ($filaP = mysqli_fetch_assoc($rowP)) {
                        echo("<tr>");
                        echo("<td>". $filaP['fecha'] ."</td>");
                        echo("<td>". $filaP['usuario'] ."</td>");
                        echo("<td>". $filaP['accion'] ."</td>");                        
                        echo("</tr>");
                    }    
                ?>
            </tbody>
        </table>
			</div>
		    	
			<?php include 'php/footer.php'; ?>
		</div>
		<script src="assets/plugins/flot/jquery.flot.js"></script>
		<script src="assets/plugins/flot/jquery.flot.time.js"></script>
		<script src="assets/plugins/flot/jquery.flot.resize.js"></script>
		<script src="assets/plugins/flot/jquery.flot.pie.js"></script>
		<script src="assets/plugins/jquery-sparkline/jquery.sparkline.min.js"></script>
		<script src="assets/plugins/jvectormap-next/jquery-jvectormap.min.js"></script>
		<script src="assets/plugins/jvectormap-next/jquery-jvectormap-world-mill.js"></script>
		<script src="assets/plugins/bootstrap-datepicker/dist/js/bootstrap-datepicker.js"></script>
		<script src="assets/js/demo/dashboard.js"></script>
		<script src="https://cdn.jsdelivr.net/npm/sweetalert2@8"></script>

		<script src="assets/js/app.min.js"></script>
		<script src="assets/js/theme/apple.min.js"></script>
		<script src="assets/plugins/datatables.net/js/jquery.dataTables.min.js"></script>
		<script src="assets/plugins/datatables.net-bs4/js/dataTables.bootstrap4.min.js"></script>
		<script src="assets/plugins/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
		<script src="assets/plugins/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js"></script>
		<script src="assets/plugins/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
		<script src="assets/plugins/datatables.net-buttons-bs4/js/buttons.bootstrap4.min.js"></script>
		<script src="assets/plugins/datatables.net-buttons/js/buttons.colVis.min.js"></script>
		<script src="assets/plugins/datatables.net-buttons/js/buttons.flash.min.js"></script>
		<!-- <script src="assets/plugins/datatables.net-buttons/js/buttons.html5.min.js"></script> -->

		<!-- ORIENTACION -->
		<script src="assets/plugins/datatables.net-buttons/js/pdfHorizontal.js"></script>
		<!-- <script src="assets/plugins/datatables.net-buttons/js/pdfVertical.js"></script> -->
		<!-- ORIENTACION -->

		<script src="assets/plugins/blueimp-file-upload/js/vendor/jquery.ui.widget.js"></script>
		<script src="assets/plugins/blueimp-tmpl/js/tmpl.js"></script>
		<script src="assets/plugins/blueimp-load-image/js/load-image.all.min.js"></script>
		<script src="assets/plugins/blueimp-canvas-to-blob/js/canvas-to-blob.js"></script>
		<script src="assets/plugins/blueimp-gallery/js/jquery.blueimp-gallery.min.js"></script>
		<script src="assets/plugins/blueimp-file-upload/js/jquery.iframe-transport.js"></script>
		<script src="assets/plugins/blueimp-file-upload/js/jquery.fileupload.js"></script>
		<script src="assets/plugins/blueimp-file-upload/js/jquery.fileupload-process.js"></script>
		<script src="assets/plugins/blueimp-file-upload/js/jquery.fileupload-image.js"></script>
		<script src="assets/plugins/blueimp-file-upload/js/jquery.fileupload-audio.js"></script>
		<script src="assets/plugins/blueimp-file-upload/js/jquery.fileupload-video.js"></script>
		<script src="assets/plugins/blueimp-file-upload/js/jquery.fileupload-validate.js"></script>
		<script src="assets/plugins/blueimp-file-upload/js/jquery.fileupload-ui.js"></script>
		<script src="assets/js/demo/form-multiple-upload.demo.js"></script>
		<script src="assets/plugins/parsleyjs/dist/parsley.min.js"></script>
		<script src="assets/plugins/highlight.js/highlight.min.js"></script>
		<script src="assets/js/demo/render.highlight.js"></script>
		<script src="assets/plugins/datatables.net-buttons/js/buttons.print.min.js"></script>
		<script src="assets/plugins/pdfmake/build/pdfmake.min.js"></script>
		<script src="assets/plugins/pdfmake/build/vfs_fonts.js"></script>
		<script src="assets/plugins/jszip/dist/jszip.min.js"></script>
		<script src="assets/js/demo/table-manage-buttons.demo.js"></script>
		<script src="https://cdn.jsdelivr.net/npm/sweetalert2@8"></script>
		<script src="assets/js/jquery.mask.js"></script>


		<script type="text/javascript" src="functions/inicio.js"></script>
		<script type="text/javascript">
			$(document).ready(function() {
				$("#saveAPI").submit(function() {
					$.ajax({
						url: 'do.php',
						type: 'POST',
						dataType: 'html',
						data: $(saveAPI).serialize(),
					})
					.done(function(data) {
						$(".respuesta").html(data);
					})
					return false;
				});
			});
		</script>
	</body>
</html>