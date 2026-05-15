<?php
	require '../includes/librerias/mPDF/vendor/autoload.php';
	require '../includes/conexion.php';
	include '../includes/date.class.php';
	mysqli_query($MySQLi,"SET lc_time_names= 'es_BO' ");
	if (isset($_GET['idCotizacion'])) {
		$mpdf 	=	new \Mpdf\Mpdf([
			'mode'			=>	'utf-8',
			'format' 		=> [280, 216],
			'orientation'	=>	'L',
			'margin_header'	=>	0,
			'margin_footer'	=>	0,
			'margin_left'	=>	0,
			'margin_top'	=>	27,
			'margin_right'	=>	0,
			'margin_bottom'	=>	10,

		]);
		$CSS 	=	file_get_contents('../assets/css/estilo.css');

		$mpdf->SetHTMLHeader('<img src="../assets/img/HEADER.png">');
		$mpdf->SetHTMLFooter('<img src="../assets/img/FOOTER.png">');


		$html 	=	'
		<body>
		 	<h2>Esta es una cotización</h2>
		 </body> ';
		$NamePDF=	"Detalle Cotización";
		$mpdf->WriteHTML($CSS, \Mpdf\HTMLParserMode::HEADER_CSS);
		$mpdf->WriteHTML($html, \Mpdf\HTMLParserMode::HTML_BODY);
		$mpdf->Output($NamePDF." Fecha ( ".($Fecha) ." ).pdf", "D");
	}elseif (isset($_GET['idNEntrega'])) {
		$mpdf 	=	new \Mpdf\Mpdf([
			'mode'			=>	'utf-8',
			'format' 		=> [280, 216],
			'orientation'	=>	'L',
			'margin_header'	=>	0,
			'margin_footer'	=>	0,
			'margin_left'	=>	0,
			'margin_top'	=>	27,
			'margin_right'	=>	0,
			'margin_bottom'	=>	10,

		]);
		$CSS 	=	file_get_contents('../assets/css/estilo.css');
		$html 	=	'
		<body>
		 	<h2>Nota de entrega</h2>
		 </body> ';
		$NamePDF=	"Nota de entrega";
		$mpdf->WriteHTML($CSS, \Mpdf\HTMLParserMode::HEADER_CSS);
		$mpdf->WriteHTML($html, \Mpdf\HTMLParserMode::HTML_BODY);
		$mpdf->Output($NamePDF." Fecha ( ".($Fecha) ." ).pdf", "D");
	}elseif (isset($_GET['idNotaE'])) {
		$mpdf 	=	new \Mpdf\Mpdf([
			'mode'			=>	'utf-8',
			'format' 		=> [280, 216],
			'orientation'	=>	'L',
			'margin_header'	=>	0,
			'margin_footer'	=>	0,
			'margin_left'	=>	0,
			'margin_top'	=>	0,
			'margin_right'	=>	0,
			'margin_bottom'	=>	0,

		]);
		//$CSS 	=	file_get_contents('../assets/css/estilo.css');
		//$mpdf->SetHTMLHeader('<img src="../assets/img/HEADER.png">');
		//$mpdf->SetHTMLFooter('<img src="../assets/img/FOOTER.png">');


		$html 	=	'
		<body>
			<div class="container">
				<header>
					<div class="row numOrder">
						<div class="col mt-3">
							<h1>N&ordm; &nbsp;&nbsp;0000001</h1>
						</div>
					</div>
					<div class="row">
						<div class="col mt-3">
							<img src="https://sistema.yuliimport.com/assets/img/logo.png" width="200px" alt="">	
						</div>
						<div class="col mt-5 text-center">
							<h3><strong>NOTA DE ENTREGA</strong></h3>
						</div>
						<div class="col mt-3 text-right">
							<div class="ciudad">CIUDAD</div>
							<div class="ciudadInside"><strong>Cochabamba</strong></div>
							<div class="ciudad">FECHA</div>
							<div class="ciudadInside"><strong>12/12/2019</strong></div>
						</div>
					</div>
					
					<div class="row mt-5 text-left" style="font-size: 8px">
						<div class="col text-left">
							<table width="100%">
								<tr>
									<td width="25%">Central Cochabamba: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Lorem ipsum dolor sit amet, consectetur adipisicing elit. Impedit, suscipit!</td>
									<td width="25%">Central Cochabamba: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Lorem ipsum dolor sit amet, consectetur adipisicing elit. Amet, consectetur.</td>
									<td width="25%">Central Cochabamba: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Lorem ipsum dolor sit amet, consectetur adipisicing elit. Dignissimos, impedit.</td>
									<td width="25%">Central Cochabamba: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Lorem ipsum dolor sit amet, consectetur adipisicing elit. Earum, ratione.</td>
								</tr>
							</table>
						</div>
					</div>
					<div class="row mt-3">
						<div class="col">
							<table width="100%">
								<tr>
									<td colspan="3">Nombre: </td>
								</tr>
								<tr>
									<td colspan="2">Dirección: </td>
									<td >Teléfono: </td>
								</tr>
							</table>
						</div>
					</div>
				</header>
				<!-- área del ercibo -->
				<section>
					<div class="row tabla">
						<div class="col">
							<table  class="w-100 table-bordered table table-hover mt-3 text-success">
								<thead>
									<tr>
										<th class="text-center" width="10%">CANTIDAD</th>
										<th class="text-center" width="60%">DETALLE</th>
										<th class="text-center" width="15%">MARCA</th>
										<th class="text-center" width="15%">MODELO</th>
									</tr>								
								</thead>
								<tbody>
									<tr>
										<td width="10%"></td>
										<td width="60%"></td>
										<td width="15%"></td>
										<td width="15%"></td>
									</tr>
									<tr>
										<td width="10%"></td>
										<td width="60%"></td>
										<td width="15%"></td>
										<td width="15%"></td>
									</tr>
									<tr>
										<td width="10%"></td>
										<td width="60%"></td>
										<td width="15%"></td>
										<td width="15%"></td>
									</tr>
									<tr>
										<td width="10%"></td>
										<td width="60%"></td>
										<td width="15%"></td>
										<td width="15%"></td>
									</tr>
									<tr>
										<td width="10%"></td>
										<td width="60%"></td>
										<td width="15%"></td>
										<td width="15%"></td>
									</tr>
								</tbody>
							</table>
							<div class="mb-3">Observaciones: </div>
						</div>
					</div>
					<div class="importante" style="font-size: 8px">
						<strong>GARANTÍA: </strong> 06 (SEIS) MESES CONTRA DEFECTOS DE FABRICACIÓN Y NO ASÍTEMAS ELÉCTRICOS (MOTOR, SENSORES, VÁLVULAS, TERMOSTATOS, ETC) NI POR MAL USO O MALA MANIPULACIÓN  DE LOS EQUIPOS. <br><strong>IMPORTANTE: </strong>NO SE ACEPTAN CAMBIOS NI DEVOLUCIONES DE MERCADERÍA O DINERO
					</div>
					<div class="row  mt-5 footer" style="margin-bottom: -10px">
						<div class="col text-left">
							----------------------------------------
						</div>
						<div class="col text-right">
							----------------------------------------
						</div>
					</div>
					<div class="row footerLine" style="font-size: 10px">
						<div class="col text-left">
							<span>FIRMA CLIENTE</span>
						</div>
						<div class="col text-right mr-4">
							<span>VENDEDOR(A)</span>
						</div>
					</div>
				</section>
			</div>
		</body>';
		$NamePDF=	"Detalle Cotización";
		$mpdf->WriteHTML($CSS, \Mpdf\HTMLParserMode::HEADER_CSS);
		$mpdf->WriteHTML($html, \Mpdf\HTMLParserMode::HTML_BODY);
		$mpdf->Output($NamePDF." Fecha ( ".($Fecha) ." ).pdf", "D");
	}
?>