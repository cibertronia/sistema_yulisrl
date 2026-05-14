<?php
	require '../includes/librerias/mPDF/vendor/autoload.php';
	require '../includes/conexion.php';
	include '../includes/date.class.php';
	require '../includes/librerias/phpMailer/vendor/autoload.php';
	use PHPMailer\PHPMailer\PHPMailer;
	use PHPMailer\PHPMailer\SMTP;
	use PHPMailer\PHPMailer\Exception;
	error_reporting(0);
	$mail 	=	new PHPMailer(true);
	mysqli_query($MySQLi,"SET lc_time_names= 'es_BO' ");
	if (isset($_POST['idReporteCotizacion'])) {
		$idCotizacion	=	$_POST['idReporteCotizacion'];
		$url 					= $_SERVER['HTTP_HOST'];
		//OBTENEMOS LOS DATOS DE LA COTIZACION
		$queryCotiza	=	mysqli_query($MySQLi,"SELECT Code, Clave, idUser, idCliente, Forma_Pago, DATE_FORMAT(FinFecha_Oferta, '%d de %M de %Y') AS FinFecha_Oferta , Dias_Entrega, Comentarios, Sucursal, DATE_FORMAT(Fecha, '%d de %M de %Y') AS Fecha FROM Cotizaciones WHERE idCotizacion='$idCotizacion' ");
		$dataCotiza		=	mysqli_fetch_assoc($queryCotiza);
		$CodigoCotiza =	$dataCotiza['Code'];
		$ClaveCotizacion=	$dataCotiza['Clave'];
		$idUser 			=	$dataCotiza['idUser'];
		$idCliente 		=	$dataCotiza['idCliente'];
		$FormaPago		=	$dataCotiza['Forma_Pago'];
		$FinOferta 		=	$dataCotiza['FinFecha_Oferta'];
		$Entrega 			=	$dataCotiza['Dias_Entrega'];
		$Comentarios 	=	$dataCotiza['Comentarios'];
		$Sucursal 		=	$dataCotiza['Sucursal'];
		$Fecha 			=	$dataCotiza['Fecha'];

		//OBTENEMOS LOS DATOS DEL CLIENTE
		$queryCliente =	mysqli_query($MySQLi,"SELECT * FROM Clientes WHERE idCliente='$idCliente' ");
		$dataCliente 	=	mysqli_fetch_assoc($queryCliente);
		$FullNameCliente=	$dataCliente['Nombres']." ".$dataCliente['Apellidos'];
		$CorreoCliente=	$dataCliente['Correo'];
		$EmpresaCliente	=	$dataCliente['Empresa'];
		$celCliente	=	$dataCliente['Celular'];

		//OBTENEMOS LOS DATOS DEL USUARIO
		$queryUsuario =	mysqli_query($MySQLi,"SELECT * FROM Usuarios WHERE idUser='$idUser' ");
		$dataUsuario 	=	mysqli_fetch_assoc($queryUsuario);
		$FullNameUsuario=	$dataUsuario['Nombres']." ".$dataUsuario['Apellidos'];
		$CiudadUsuario 	=	$dataUsuario['Ciudad'];
		$TelefonoUsuario=	$dataUsuario['Telefono'];

		//OBTENEMOS PRECIO DEL DOLAR BD
		$sqlPrecioDolar = mysqli_query($MySQLi, "SELECT * FROM precio ");
		$dolarBd = mysqli_fetch_assoc($sqlPrecioDolar);
		//$priceUnit = number_format(($data['PrecioOferta']* $dolarBd['precioDolar']), 2, ".", "");

		$mpdf 	=	new \Mpdf\Mpdf([
			'mode'			=>	'utf-8',
			'format' 		=> [280, 216],
			'orientation'	=>	'L',
			'margin_header'	=>	0,
			'margin_footer'	=>	0,
			'margin_left'	=>	0,
			'margin_top'	=>	27,
			'margin_right'	=>	0,
			'margin_bottom'	=>	45,
		]);
		$CSS 	=	file_get_contents('css/reporteCotizacion.css');
		$mpdf->SetHTMLHeader('<img src="../assets/img/HEADER.png">');
		//$mpdf->SetHTMLFooter('<img src="assets/img/FOOTER.png">');
		$html 	=	'
		<table class="info">
			<tbody>
				<tr>
					<td class="text-left fs-16">
						Señor/a: <strong>'.$FullNameCliente .'</strong><br>
						Empresa: <strong>'.$EmpresaCliente .'</strong><br>
						Teléfono: <strong>'.$celCliente .'</strong><br>
						<a target="_blank" href="mailto:'.$CorreoCliente .'" style="text-decoration: none;">'.$CorreoCliente .'</a>
					</td>
					<td class="text-right">
						<h2 style="color: red">'.$CodigoCotiza .'</h2><br>
						'.$Fecha/*strtoupper($Fecha)*/ .'<br>
					</td>
				</tr>
			</tbody>
		</table>';
		$queryProducto		=	mysqli_query($MySQLi,"SELECT * FROM ClaveTemporal WHERE Clave='$ClaveCotizacion' ");
		// $cantidadClaves 	=	mysqli_num_rows($queryProducto);
		// $cantidadTablas 	=	2;
		// $NumPaginas 			=	round($cantidadClaves/$cantidadTablas);
		$TotalVenta 			= 0;
		while ( $dataClave=	mysqli_fetch_assoc($queryProducto)) {
			$idProducto 		=	$dataClave['idProducto'];
			$CantidadProduct=	$dataClave['Cantidad'];
			$PrecioLista 		=	$dataClave['PrecioLista'];
			$PrecioVenta 		=	$dataClave['PrecioOferta'];
			$SubTotal 	    = $CantidadProduct*$PrecioVenta;
			$TotalVenta 		= $TotalVenta+$SubTotal;
			$ConsultaProduct=	mysqli_query($MySQLi,"SELECT * FROM Productos WHERE idProducto='$idProducto' ");
			$infoPro 				=	mysqli_fetch_array($ConsultaProduct);
			$Description		=	$infoPro['Descripcion'];
			$ImagenProducto =	$infoPro['Imagen'];
			$imagenHTML 		= htmlspecialchars($ImagenProducto);
			$NameProducto 	=	$infoPro['Producto'];
			$MarcaProducto 	= $infoPro['Marca'];
			$ModeloProducto =	$infoPro['Modelo'];
			$Total 					=	$CantidadProduct*$PrecioVenta;
			$ruta           = "Productos/".$imagenHTML;
			$html .='
			<table class="SaltoTabla" border="1">
				<thead>
					<tr class="verde">
						<td class="text-left nameProducto" colspan="4">Producto: <strong>'.$NameProducto .'</strong></td>
						<td class="text-left nameProducto" colspan="2">Marca: <strong>'.$MarcaProducto .'</strong></td>
						<td class="text-left nameProducto" colspan="2">Modelo: <strong>'.$ModeloProducto .'</strong></td>
					</tr>
					<tr class="azul">
						<th style="padding: 5px" colspan="6" width="70%">Descripción</th>
						<th style="padding: 5px" colspan="2" width="30%">Imagen</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td style="padding: 15px" width="65%" colspan="5">'.$Description .'</td>
						<td style="padding: 15px" width="35%" colspan="3" class="text-center">
							<img src="https://sistema.yulisrl.com/'.$ruta.'" width="100px" height="100px" />
						</td>
					</tr>
					<tr class="amarillo">
						<td colspan="2" class="footer">Cantidad: '.$CantidadProduct .'</td>
						
						<td colspan="2" class="footer">
						Precio Lista: <br>
						<!--  <strong>$</strong> '.number_format($PrecioLista,2) .'	<br>	-->							
							<strong>Bs</strong> '.number_format($PrecioLista*$dolarBd["precioDolar"],2) .'
						</td>	

						<td colspan="2" class="footer verde" >
							<strong>Precio Especial: <br>
							<!-- 	$ '.number_format($PrecioVenta,2) .' <br>   -->	
							Bs '.number_format($PrecioVenta*$dolarBd["precioDolar"],2) .'</strong>
						</td>

						<td colspan="2" class="footer verde" style="color:red" >
							<strong>Total: <br>
							<!-- $ '.number_format($Total,2) .'<br> -->
							Bs '.number_format($Total*$dolarBd["precioDolar"],2) .'</strong>
						</td>
					</tr>
				</tbody>
			</table> ';		
		}
		
		$html.='
		<table class="infoFooter">
			<tr>
				<td>PRECIO TOTAL GENERAL: &nbsp;&nbsp; <strong> <!-- USD &nbsp;&nbsp;'.number_format($TotalVenta,2) .' &nbsp;&nbsp; / -->	&nbsp;&nbsp;Bs &nbsp;&nbsp;'.number_format($TotalVenta*$dolarBd["precioDolar"],2) .'</strong></td>
			</tr>
			<tr>
				<td>VALIDEZ DE LA OFERTA: hasta el <strong style="color: red">'.$FinOferta .'</strong></td>
			</tr>
			<tr>
				<td>TIEMPO DE ENTREGA: '.$Entrega .'</td>
			</tr>
			<tr>
				<td>FORMA DE PAGO: '.$FormaPago.'</td>
			</tr>
			<tr>
				<td>COMENTARIOS: '.$Comentarios .'</td>
			</tr>
		</table>
		<p  class="infoFooter2">
			<strong style="letter-spacing: 1px;">Cualquier consulta o requerimiento no dude en comunicarse con nosotros</strong>  <br>
			Atte: <strong style="font-family: cursive;">'.$FullNameUsuario .'</strong><br>
			Asesor de Ventas  <br>
			Teléfono: <span style="margin-top: 15px"><img src="../assets/img/whatsapp.png" alt="Logo WhatsApp" style="width: 20px; height: 20px;"></span> &nbsp;<strong>'.$TelefonoUsuario .'</strong>
		</p>';
		
		$NamePDF=	"Cotización ".$idCotizacion;
		$mpdf->WriteHTML($CSS, \Mpdf\HTMLParserMode::HEADER_CSS);
		$mpdf->WriteHTML($html, \Mpdf\HTMLParserMode::HTML_BODY);
		$mpdf->Output($NamePDF.".pdf", "I");
	}elseif (isset($_GET['ReciboCompra'])) {
		$idRecibo	=	$_GET['ReciboCompra'];
		//	CONSULTAMOS LOS DATOS DEL RECIBO
		$sql = "SELECT r.idRecibo, r.idCotizacion, r.CodeCotizacion, r.idUser, r.idCliente, concat(c.nombres,' ',c.apellidos) Cliente, r.Moneda, r.PrecioDolar,
		r.Cantidad, r.CantidadUSD, r.Cant_Letras, r.Concepto, 
		DATE_FORMAT(r.Fecha, '%W, %d de %M de %Y') AS Fecha, r.Sucursal, r.Tipo 
		FROM Recibos r inner join Clientes c on r.idCliente = c.idCliente WHERE idRecibo='$idRecibo' ";
		$ConsRecibo 	=	mysqli_query($MySQLi,$sql)or die(mysqli_error($MySQLi).", en la línea: ".__LINE__." de ".__FILE__);
		$dataRecibo 	=	mysqli_fetch_assoc($ConsRecibo);
		
        $sql = "SELECT max(idRecibo) idRecibo	FROM Recibos WHERE idCotizacion= " . $dataRecibo['idCotizacion'];
		$ConsRecibo 	=	mysqli_query($MySQLi,$sql)or die(mysqli_error($MySQLi).", en la línea: ".__LINE__." de ".__FILE__);
		$dataRecibo 	=	mysqli_fetch_assoc($ConsRecibo);
		$idRecibo = $dataRecibo['idRecibo'];

        $sql = "SELECT r.idRecibo, r.idCotizacion, r.CodeCotizacion, r.idUser, r.idCliente, concat(c.nombres,' ',c.apellidos) Cliente, r.Moneda, r.PrecioDolar,
		r.Cantidad, r.CantidadUSD, r.Cant_Letras, r.Concepto, 
		DATE_FORMAT(r.Fecha, '%W, %d de %M de %Y') AS Fecha, r.Sucursal, r.Tipo 
		FROM Recibos r inner join Clientes c on r.idCliente = c.idCliente WHERE idRecibo='$idRecibo' ";
		$ConsRecibo 	=	mysqli_query($MySQLi,$sql)or die(mysqli_error($MySQLi).", en la línea: ".__LINE__." de ".__FILE__);
		$dataRecibo 	=	mysqli_fetch_assoc($ConsRecibo);

		//$idRecibo 		=	$dataRecibo['idRecibo'];
		$Sucursal 		=	$dataRecibo['Sucursal'];
		$idUser 		=	$dataRecibo['idUser'];
		$idCliente  = $dataRecibo['idCliente'];
		$nameCliente 	=	"<span style='letter-spacing: 1px;font-size:14px'>".$dataRecibo['Cliente']."</span>";
		$Moneda 		=	$dataRecibo['Moneda'];
		$PrecioDolar 	=	$dataRecibo['PrecioDolar'];
		$LaCantidadDe 	=	"<span style='letter-spacing: 1px;font-size:14px'>".$dataRecibo['Cant_Letras']."</span>";
		$EnConceptoDe 	=	"<span style='letter-spacing: 1px;font-size:14px'>".$dataRecibo['Concepto']."</span>";
		if ($Moneda=='USD') {
			$PorAnticipo 	=	$dataRecibo['CantidadUSD'];
		}else{
			$PorAnticipo 	=	$dataRecibo['Cantidad'];
		}
		
		$Saldo 			=	$dataRecibo['SaldoActual'];
		$Total 			=	$dataRecibo['Total'];
		$FechaRecibo 	=	"<span style='letter-spacing: 1px;font-size:14px'>".$dataRecibo['Fecha']."</span>";

		$anticipoAnterior=	$dataRecibo['anticipoAnterior'];
		if ($idRecibo 	<	10) {
			$ReciboNum='<span style="letter-spacing: 1px">000000'.$idRecibo.'</span>';
		}elseif ($idRecibo<	100) {
			$ReciboNum='<span style="letter-spacing: 1px">00000'.$idRecibo.'</span>';
		}elseif ($idRecibo< 1000) {
			$ReciboNum='<span style="letter-spacing: 1px">0000'.$idRecibo.'</span>';
		}elseif ($idRecibo< 10000) {
			$ReciboNum='<span style="letter-spacing: 1px">000'.$idRecibo.'</span>';
		}elseif ($idRecibo< 100000) {
			$ReciboNum='<span style="letter-spacing: 1px">00'.$idRecibo.'</span>';
		}elseif ($idRecibo< 1000000) {
			$ReciboNum='<span style="letter-spacing: 1px">0'.$idRecibo.'</span>';
		}elseif ($idRecibo< 10000000) {
			$ReciboNum='<span style="letter-spacing: 1px">'.$idRecibo.'</span>';
		}
		//	BUSCAMOS LOS DATOS DEL CLIENTE
		// $consultCotiza	=	mysqli_query($MySQLi,"SELECT * FROM Cotizaciones WHERE idCotizacion='$idCotizacion' ");
		// $dataCotiza 	=	mysqli_fetch_assoc($consultCotiza);
		// $idCliente 		=	$dataCotiza['idCliente'];

		$consultCliente =	mysqli_query($MySQLi,"SELECT * FROM Clientes WHERE idCliente='$idCliente' ");
		$dataCliente 	=	mysqli_fetch_assoc($consultCliente);
		$NombreEmpresa= $dataCliente['Empresa'];


		// $ConCotiza		=	mysqli_query($MySQLi,"SELECT * FROM Cotizaciones WHERE idCotizacion='$idCotizacion' ");
		// $dataCotiza 	=	mysqli_fetch_assoc($ConCotiza);
		// $ClaveCotiza 	=	$dataCotiza['Clave'];



		$mpdf 	=	new \Mpdf\Mpdf([
			'mode'			=>	'utf-8',
			'format' 		=> [280, 216],
			'orientation'	=>	'L',
			'margin_header'	=>	0,
			'margin_footer'	=>	0,
			'margin_left'	=>	0,
			'margin_top'	=>	27,
			'margin_right'	=>	0,
			'margin_bottom'	=>	45,
		]);
		$CSS 	=	file_get_contents('css/recibo.css');
		$mpdf->SetHTMLHeader('<img src="../assets/img/HEADER.png">');
		$mpdf->SetHTMLFooter('<img src="../assets/img/FOOTER.png">');
		$html 	=	'
		<body>
		 	<header>
		 		<div class="nav">
		 			<!-- <img src="../assets/img/" alt="Logo Yuliimport">
		 			<table width="65%">
						<tr>
							<td width="">
								CENTRAL COCHABAMBA
							 </td>
							<td width="">C. Tumusla Nº 107 entre Heroínas y Colombia acera oeste. Telf: <img src="../assets/img/whatsapp.png" alt="Logo WhatsApp" style="width: 10px; height: 10px;"> 6178-2188. Servicio técnico <img src="../assets/img/whatsapp.png" alt="Logo WhatsApp" style="width: 10px; height: 10px;"> 7034-3588. Email: ventascbba@yuliimport.com
							</td>
						</tr>
						<tr>
							<td width="">
								SUCURSAL SANTA CRUZ
							 </td>
							<td width="">Av. Mutualista entre Los Tordos y Cuquisas Local Comercial 1. Telf: <img src="../assets/img/whatsapp.png" alt="Logo WhatsApp" style="width: 10px; height: 10px;"> 6071-8588. Servicio técnico <img src="../assets/img/whatsapp.png" alt="Logo WhatsApp" style="width: 10px; height: 10px;"> 7786-8828. Email: ventasscz@yuliimport.com
							</td>
						</tr>
						<tr>
							<td width="">
								SUCURSAL LA PAZ
							 </td>
							<td width="">Av. Sucre Nº 930 entre Yanacocha y Genaro Sanjinez Edif. Torre Norita. Telf: <img src="../assets/img/whatsapp.png" alt="Logo WhatsApp" style="width: 10px; height: 10px;"> 7030-6788. Servicio técnico <img src="../assets/img/whatsapp.png" alt="Logo WhatsApp" style="width: 10px; height: 10px;"> 7978-8088. Email: ventaslpz@yuliimport.com
							</td>
						</tr>
						<tr>
							<td width="">
								SUCURSAL TARIJA
							 </td>
							<td width="">C.Cochabamba entre Núñez del Prado y Venezuela N° 1053. Telf: <img src="../assets/img/whatsapp.png" alt="Logo WhatsApp" style="width: 10px; height: 10px;"> 6178-1888. Servicio técnico <img src="../assets/img/whatsapp.png" alt="Logo WhatsApp" style="width: 10px; height: 10px;"> 7038-1880. Email: ventastarija@yuliimport.com
							</td>
						</tr>
					</table> -->
		 			<table class="tableNav">
		 				<tr>
		 					<th width="50%" class="text-left"><h1>RECIBO N&ordm; <span class="text-danger">'.$ReciboNum .'</span></h1></th>
		 					<th width="50%" class="text-right">';
		 					if ($Moneda=='USD') {
		 						$html.='<h1>$ USD <strong class="porAnticipo"> &nbsp;&nbsp;&nbsp;&nbsp;'.number_format($PorAnticipo,2) .' &nbsp;&nbsp;&nbsp;&nbsp;</strong></h1>';
		 					}else{
		 						$html.='<h1>Bs. <strong class="porAnticipo"> &nbsp;&nbsp;&nbsp;&nbsp;'.number_format($PorAnticipo,2) .' &nbsp;&nbsp;&nbsp;&nbsp;</strong></h1>';
		 					} $html.='	
		 					</th>
		 					}
		 				</tr>
		 			</table>
		 		</div>
		 	</header>
		 	<section>';
		 		if ($Sucursal=='Cochabamba') { $html.='
		 			<p class="text-center sucursal"><strong>CENTRAL '.strtoupper($Sucursal) .'</strong></p>';
		 		}else{ $html.='
		 			<p class="text-center sucursal"><strong>SUCURSAL '.strtoupper($Sucursal) .'</strong></p>';
		 		}
		 		if ($NombreEmpresa=='') { $html.='		 		
		 		<p><strong>Recibí de: </strong> &nbsp;&nbsp;&nbsp;'.$nameCliente .'</p>';
		 		}else{ $html.='		 		
			 		<p><strong>Recibí de: </strong> &nbsp;&nbsp;&nbsp;'.$nameCliente .'</p>
			 		<p><strong>Empresa: </strong> &nbsp;&nbsp;&nbsp;'.$NombreEmpresa .'</p> ';
		 		} 
		 		if ($Moneda=='USD') {
		 			$html.='<p><strong>La Cantidad de: </strong> &nbsp;&nbsp;&nbsp;'.$LaCantidadDe .' <span style="font-size:14px;letter-spacing:1px">dólares</span></p>';
		 		}else{
		 			$html.='<p><strong>La Cantidad de: </strong> &nbsp;&nbsp;&nbsp;'.$LaCantidadDe .' <span style="font-size:14px;letter-spacing:1px">bolivianos</span></p>';
		 		}$html.='
		 		<p><strong>Por concepto de: </strong> &nbsp;&nbsp;&nbsp;'.$EnConceptoDe .'</p>
		 		<p></p>
		 		<p class="text-center">'.$FechaRecibo.'</p>
		 		<p></p>
		 		<!-- <p class="text-center">
		 			<strong>Anticipo</strong> &nbsp;&nbsp;<strong class="porAnticipo" style="padding: !important 25px;"> &nbsp;&nbsp;&nbsp;&nbsp;'.number_format($PorAnticipo,2) .' &nbsp;&nbsp;&nbsp;&nbsp;</strong>&nbsp;&nbsp;&nbsp;&nbsp;
		 			<strong>Saldo</strong> &nbsp;&nbsp;<strong class="porAnticipo"> &nbsp;&nbsp;&nbsp;&nbsp;'.number_format($Saldo,2) .' &nbsp;&nbsp;&nbsp;&nbsp;</strong>&nbsp;&nbsp;&nbsp;&nbsp;
		 			<strong>Total</strong> &nbsp;&nbsp;<strong class="porAnticipo"> &nbsp;&nbsp;&nbsp;&nbsp;'.number_format($Total,2) .' &nbsp;&nbsp;&nbsp;&nbsp;</strong>&nbsp;&nbsp;&nbsp;&nbsp;
		 		</p>-->
		 		<p></p><p></p>
		 		<p>
		 			<table>
		 				<tr>
		 					<td width="25%">---------------------------------------------</td>
		 					<td width="25%"></td>
		 					<td width="25%"></td>
		 					<td width="25%">---------------------------------------------</td>
		 				</tr>
		 				<tr>
		 					<td width="25%" class="text-center">Recibí conforme efectivo</td>
		 					<td width="25%"></td>
		 					<td width="25%"></td>
		 					<td width="25%" class="text-center">Entregué conforme efectivo</td>
		 				</tr>
		 			</table>
		 		</p>
		 	</section>
		 	<footer>
		 		<strong>IMPORTANTE: </strong>No se aceptan cambios ni devoluciones de mercadería o dinero.
		 	</footer>
		 </body> ';
		$NamePDF	=	"Recibo No. ".$idRecibo;
		$mpdf->WriteHTML($CSS, \Mpdf\HTMLParserMode::HEADER_CSS);
		$mpdf->WriteHTML($html, \Mpdf\HTMLParserMode::HTML_BODY);
		$mpdf->Output($NamePDF.".pdf", "I");
	}elseif (isset($_POST['start']) AND isset($_POST['end']) AND isset($_POST['sucursal'])) {
		$FechaInicio 	=	$_POST['start'];
		$FechaCierre 	=	$_POST['end'];
		$Sucursal 		=	$_POST['sucursal'];

		$queryVentas 	=	mysqli_query($MySQLi,"SELECT idVenta, Estado, idCotizacion, CodeCotizacion, idUser, idCliente, idRecibo, idEntrega, idProducto, Cantidad, Moneda, PrecioDolar, PrecioListaUSD, PrecioListaBs, PrecioVentaUSD, PrecioVentaBs, Sucursal, DATE_FORMAT(Fecha, '%d-%m-%Y') AS Fecha, TotalVentaUS, TotalVentaBs FROM Ventas WHERE Fecha BETWEEN '$FechaInicio' AND '$FechaCierre' AND Sucursal='$Sucursal'  ORDER BY Fecha ASC ")or die(mysqli_error($MySQLi)."<br>Error en la línea: ".__LINE__);
		$resultQueryV 	=	mysqli_num_rows($queryVentas);
		if ($resultQueryV>0) {
			header("Content-type: application/vnd.ms-excel; name='excel'");
			header("Content-Disposition: filename=Reporte_ventas.xls");
			header("Pragma: no-cache");
			header("Pragma: no-cache");
			header("Expires: 0"); ?>
<table border="1">
    <thead>
        <tr>
            <th colspan="24" style="text-align: center;">
                <h3>REPORTE DE VENTAS DESDE EL <span style="color: green"><?php echo $FechaInicio ?></span> HASTA EL
                    <span style="color: red"><?php echo $FechaCierre ?></span>
                </h3>
            </th>
        </tr>
        <tr>
            <th style="text-align: center;color:#fff;background-color: #97D086">N&ordm;</th>
            <th style="text-align: center;color:#fff;background-color: #97D086">FECHA</th>
            <th style="text-align: center;color:#fff;background-color: #97D086">RECIBO</th>
            <th style="text-align: center;color:#fff;background-color: #97D086">CODIGO</th>
            <th style="text-align: center;color:#fff;background-color: #97D086">NOTA<br>ENTREGA</th>
            <th style="text-align: center;color:#fff;background-color: #97D086">NUMERO<br>FACTURA</th>
            <th style="text-align: center;color:#fff;background-color: #97D086">CLIENTE</th>
            <th style="text-align: center;color:#fff;background-color: #97D086">NIT</th>
            <th style="text-align: center;color:#fff;background-color: #97D086">TELEFONO</th>
            <th style="text-align: center;color:#fff;background-color: #97D086">PRODUCTO</th>
            <th style="text-align: center;color:#fff;background-color: #97D086">MARCA</th>
            <th style="text-align: center;color:#fff;background-color: #97D086">MODELO</th>
            <th style="text-align: center;color:#fff;background-color: #97D086">MONEDA</th>
            <th style="text-align: center;color:#fff;background-color: #97D086">TIPO DE<br>CAMBIO</th>
            <th style="text-align: center;color:#fff;background-color: #97D086">CANTIDAD</th>
            <th style="text-align: center;color:#fff;background-color: #97D086">PRECIO<br>LISTA<br>USD</th>
            <th style="text-align: center;color:#fff;background-color: #97D086">DESC</th>
            <th style="text-align: center;color:#fff;background-color: #97D086">PRECIO<br>VENTA<br>USD</th>
            <th style="text-align: center;color:#fff;background-color: #97D086">PRECIO<br>VENTA<br>Bs</th>
            <th style="text-align: center;color:#fff;background-color: #97D086">PAGO<br>VENTA<br>USD</th>
            <th style="text-align: center;color:#fff;background-color: #97D086">PAGO<br>VENTA<br>Bs</th>
            <th style="text-align: center;color:#fff;background-color: #97D086">IMPORTE<br>FACTURA</th>
            <th style="text-align: center;color:#fff;background-color: #97D086">VENDEDOR</th>
            <th style="text-align: center;color:#fff;background-color: #97D086">SUCURSAL</th>
            <th style="text-align: center;color:#fff;background-color: #97D086">No.</th>
            <th style="text-align: center;color:#fff;background-color: #97D086">OBSERVACIONES</th>
        </tr>
    </thead>
    <tbody><?php
					$Number 	=	1;
					$cantidad=0;
					$precioListaUsd=0;
					$precioVentaUsd=0;
					$precioVentaBs=0;

					$pagoVentaUsd=0;
					$pagoVentaBs=0;
					$importeFactura=0;
				
					while ($datosVenta = mysqli_fetch_assoc($queryVentas)) { ?>
        <tr>
            <td style="text-align: center"><?php echo $Number ?></td>
            <td><?php echo $datosVenta['Fecha'] ?></td>
            <td style="text-align: center;background-color: #DEE97E"><?php echo $datosVenta['idRecibo'] ?></td>
            <td><?php echo $datosVenta['CodeCotizacion'] ?></td>
            <td style="text-align: center;background-color: #DEE97E"><?php echo $datosVenta['idEntrega'] ?></td><?php
							$idCliente			=	$datosVenta['idCliente'];
							$consultCliente =	mysqli_query($MySQLi,"SELECT * FROM Clientes WHERE idCliente='$idCliente' ");
							$datosCliente		=	mysqli_fetch_assoc($consultCliente);
							if ($datosCliente['Celular']=='' AND $datosCliente['Otro']=='') {
								$TelCliente		=	'VAC&Iacute;O';
							}elseif ($datosCliente['Otro']=='') {
								$TelCliente		=	$datosCliente['Celular'];
							}elseif ($datosCliente['Celular']=='') {
								$TelCliente		=	$datosCliente['Otro'];
							}else{
								$TelCliente		=	$datosCliente['Celular']." / ".$datosCliente['Otro'];
							}$NameCliente 	=	mb_convert_encoding($datosCliente['Nombres'], 'HTML-ENTITIES', 'UTF-8')." ".mb_convert_encoding($datosCliente['Apellidos'], 'HTML-ENTITIES', 'UTF-8');
							$idUsuario			=	$datosVenta['idUser'];
							$consultUsuario	=	mysqli_query($MySQLi,"SELECT * FROM Usuarios WHERE idUser='$idUsuario' ");
							$datosUsuario		=	mysqli_fetch_assoc($consultUsuario);
							$Vendedor 			=	utf8_decode($datosUsuario['Nombres']." ".$datosUsuario['Apellidos']);
							$idProducto 		=	$datosVenta['idProducto'];
							$consultProducto=	mysqli_query($MySQLi,"SELECT * FROM Productos WHERE idProducto='$idProducto' ");
							$datosProducto	=	mysqli_fetch_assoc($consultProducto);

							$ModelProduc=	$datosProducto['Modelo'];
							$idCotizacion = $datosVenta['idCotizacion'];
							$Factura=	mysqli_query($MySQLi,"SELECT * FROM detailInvoice WHERE idCotizacion='$idCotizacion' and detailId='$idProducto' ORDER BY invoiceNumber DESC");
							$dataFactura=	mysqli_fetch_assoc($Factura);

							$FacturaCabezera=	mysqli_query($MySQLi,"SELECT * FROM factura WHERE idCotizacion='$idCotizacion' ORDER BY invoiceNumber DESC  ");
							$dataFacturaCabezera=	mysqli_fetch_assoc($FacturaCabezera);


							
							?>

            <td><?php echo $dataFacturaCabezera['invoiceNumber']  ?></td>
            <td>
			<?php echo mb_convert_encoding($NameCliente, 'HTML-ENTITIES', 'UTF-8'); ?>
			</td>



            <td><?php
							if ($datosCliente['NIT']=='') {
								echo "No proporcion&oacute;";
							}else{
								echo $datosCliente['NIT'];
							} ?>
            </td>
            <td><?php echo $TelCliente ?></td>


            <td><?php  
						
						if ($dataFacturaCabezera['siatDescriptionStatus']=='Validada - Emision Directa') {
							echo utf8_decode($dataFactura['description']);
						}else{
							
							echo mb_convert_encoding($datosProducto['Producto'], 'HTML-ENTITIES', 'UTF-8');
						}
						
						
						?></td>
            <td><?php 
						
						if ($dataFacturaCabezera['siatDescriptionStatus']=='Validada - Emision Directa') {
							echo "No Proporcionado";
						}else{
						
							echo mb_convert_encoding($datosProducto['Marca'], 'HTML-ENTITIES', 'UTF-8'); 
						}
						
						
						?></td>
            <td>
			<?php 					
				echo mb_convert_encoding($datosProducto['Modelo'], 'HTML-ENTITIES', 'UTF-8'); 
			
			?>
			</td>
            <?php
							if ($datosVenta['Moneda']=='USD') {
								echo '<td style="background-color: #DEA4BF;text-align:center">'. ($datosVenta['Moneda']).'</td>';
							}else{
								echo '<td style="background-color: #DEA4BF;text-align:center">'. ($datosVenta['Moneda']).'</td>';
							}
						?>
            <!-- PRIO DEL DOLAR -->
            <td style="text-align: center;">
                <?php
								//echo $datosVenta['PrecioDolar'];
								echo number_format(($datosVenta['PrecioDolar']), 2 );
								// if ($datosVenta['Moneda']=='USD') {
								// 	echo "";
								// }else{
								// 	echo $datosVenta['PrecioDolar'];
								// }
							?>
            </td>
            <td style="text-align: center;">
			<?php

			if($datosVenta['Estado']=='1'){
				echo '0';

			}else{
				echo $datosVenta['Cantidad'] ;
				$cantidad+=$datosVenta['Cantidad'];
			}
			 
			 ?>
			 </td>
            <td style="text-align: right;">
			<?php
			
			//echo $datosVenta['PrecioListaUSD'];
			echo number_format(($datosVenta['PrecioListaUSD']), 2 );
			$precioListaUsd+=$datosVenta['PrecioListaUSD'];
			?>
			</td>
            <td></td>
            <!-- PRECIO VENTA EN USD -->
            <td style="text-align: right;"><?php
							//echo $datosVenta['PrecioVentaUSD'];
							echo number_format(($datosVenta['PrecioVentaUSD']), 2 );
							$precioVentaUsd+=$datosVenta['PrecioVentaUSD'];
							
							//echo number_format($datosVenta['PrecioVentaUSD'],2);
								/*if ($datosVenta['Moneda']=='USD') {
									echo $datosVenta['PrecioVentaUSD'];
								}else{
									echo $datosVenta['PrecioVentaUSD'];
								}*/
							?>
            </td>
            <!-- PRECIO VENTA EN BS -->
            <td style="text-align: right;">
                <?php
								if ($datosVenta['Moneda']=='Bs') {
									// echo $datosVenta['PrecioVentaBs'];
									echo number_format(($datosVenta['PrecioVentaBs']), 2 );
								}else{
									//echo "";
									// echo $datosVenta['PrecioVentaBs'];
									echo number_format(($datosVenta['PrecioVentaBs']), 2  );
								}
								$precioVentaBs+=$datosVenta['PrecioVentaBs'];
				
							
							?>
            </td>
            <!-- PAGO VENTA EN USD -->
            <td style="text-align: right;">
                <?php 
								if ($datosVenta['Moneda']=='USD') {
									// echo $datosVenta['TotalVentaUS'];
									echo number_format(($datosVenta['TotalVentaUS']), 2 );
									// $PrecioVentaUSD	=	$datosVenta['PrecioVentaUSD'];
									// $Cantidad 		=	$datosVenta['Cantidad'];
									// $PagoenUSD 		=	$PrecioVentaUSD*$Cantidad;
									// echo $PagoenUSD;
								}else{
									//echo "";
									// echo $datosVenta['TotalVentaUS'];
									echo number_format(($datosVenta['TotalVentaUS']), 2 );
									// $PrecioVentaUSD	=	$datosVenta['PrecioVentaUSD'];
									// $Cantidad 		=	$datosVenta['Cantidad'];
									// $PagoenUSD 		=	$PrecioVentaUSD*$Cantidad;
									// echo $PagoenUSD;
								}
								$pagoVentaUsd+=$datosVenta['TotalVentaUS'];
							
							?>
            </td>
            <!-- PAGO VENTA EN BS -->
            <td style="text-align: right;">
                <?php 
								if ($datosVenta['Moneda']=='Bs') {
									// echo $datosVenta['TotalVentaBs'];
									echo number_format(($datosVenta['TotalVentaBs']), 2 );
									// $PrecioVentaBs	=	$datosVenta['PrecioVentaBs'];
									// $Cantidad 		=	$datosVenta['Cantidad'];
									// $PagoenBs 		=	$PrecioVentaBs*$Cantidad;
									// echo $PagoenBs;
									$pagoVentaBs+=$datosVenta['TotalVentaBs'];
									
								}else{
									//echo "";
									// echo $datosVenta['PrecioVentaBs'];
									// echo $datosVenta['TotalVentaBs'];
									echo number_format(($datosVenta['TotalVentaBs']), 2 );
									// $pagoVentaBs+=$datosVenta['PrecioVentaBs'];
									$pagoVentaBs+=$datosVenta['TotalVentaBs'];
								}

								
							?>
            </td>
            <td>
				<?php
				
				 ?>
			</td>
            <td><?php echo $Vendedor ?></td>
            <td><?php echo $datosVenta['Sucursal'] ?></td>
            <td></td>
            <td><?php echo 	$dataFacturaCabezera['siatDescriptionStatus'];?></td>
        </tr>
        <?php $Number++; }
		
		//mysqli_close($MySQLi); ?>
		<tr>
				<th></th>
				<th></th>
				<th></th>
				<th></th>
				<th></th>
				<th></th>
				<th></th>
				<th></th>
				<th></th>
				<th></th>
				<th></th>
				<th></th>
				<th></th>
				<th></th>

            <th style="text-align: center;background-color: #A2ABC7">
			<?php
			 	echo $cantidad;	
			 ?>
			</th>
            <th style="text-align: right;background-color: #A2ABC7">
			<?php 
			// echo $precioListaUsd;
			echo number_format(($precioListaUsd), 2 );
			?>
			</th>
			
            <th style="text-align: center;background-color: #A2ABC7"></th>

            <th style="text-align: right;background-color: #A2ABC7">
			<?php 
			//echo $precioVentaUsd;
			echo number_format(($precioVentaUsd), 2 );
			?>
			</th>
            <th style="text-align: right;background-color: #A2ABC7">
			<?php
			// echo $precioVentaBs;
			echo number_format(($precioVentaBs), 2 );
			  ?>
			</th>
            <th style="text-align: right;background-color: #A2ABC7">
			<?php
			// echo $pagoVentaUsd;
			echo number_format(($pagoVentaUsd), 2 );
			?>
			</th>
            <th style="text-align: right;background-color: #A2ABC7">
			<?php
			// echo $pagoVentaBs;
			echo number_format(($pagoVentaBs), 2 );
			?>
			</th>
            <th style="text-align: center;">
			<?php ?>
			</th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
        </tr>
    </tbody>
</table><?php
		}else{
			header("Content-type: application/vnd.ms-excel; name='excel'");
			header("Content-Disposition: filename=Reporte_ventas.xls");
			header("Pragma: no-cache");
			header("Pragma: no-cache");
			header("Expires: 0"); ?>
<table border="1">
    <thead>
        <tr>
            <th colspan="24" style="text-align: center;">
                <h3>Reporte de Ventas</h3>
            </th>
        </tr>
        <tr>
            <th style="text-align: center;color:#fff;background-color: #97D086">N&ordm;</th>
            <th style="text-align: center;color:#fff;background-color: #97D086">FECHA</th>
            <th style="text-align: center;color:#fff;background-color: #97D086">RECIBO</th>
            <th style="text-align: center;color:#fff;background-color: #97D086">CODIGO</th>
            <th style="text-align: center;color:#fff;background-color: #97D086">NOTA<br>ENTREGA</th>

            <th style="text-align: center;color:#fff;background-color: #97D086">CLIENTE</th>
            <th style="text-align: center;color:#fff;background-color: #97D086">NIT</th>
            <th style="text-align: center;color:#fff;background-color: #97D086">TELEFONO</th>
            <th style="text-align: center;color:#fff;background-color: #97D086">PRODUCTO</th>
            <th style="text-align: center;color:#fff;background-color: #97D086">MARCA</th>
            <th style="text-align: center;color:#fff;background-color: #97D086">MODELO</th>
            <th style="text-align: center;color:#fff;background-color: #97D086">MONEDA</th>
            <th style="text-align: center;color:#fff;background-color: #97D086">TIPO DE<br>CAMBIO</th>
            <th style="text-align: center;color:#fff;background-color: #97D086">CANTIDAD</th>
            <th style="text-align: center;color:#fff;background-color: #97D086">PRECIO<br>LISTA<br>USD</th>
            <th style="text-align: center;color:#fff;background-color: #97D086">DESC</th>
            <th style="text-align: center;color:#fff;background-color: #97D086">PRECIO<br>VENTA<br>USD</th>
            <th style="text-align: center;color:#fff;background-color: #97D086">PRECIO<br>VENTA<br>Bs</th>
            <th style="text-align: center;color:#fff;background-color: #97D086">PAGO<br>VENTA<br>USD</th>
            <th style="text-align: center;color:#fff;background-color: #97D086">PAGO<br>VENTA<br>Bs</th>

            <th style="text-align: center;color:#fff;background-color: #97D086">VENDEDOR</th>
            <th style="text-align: center;color:#fff;background-color: #97D086">SUCURSAL</th>
            <th style="text-align: center;color:#fff;background-color: #97D086">No.</th>
            <th style="text-align: center;color:#fff;background-color: #97D086">OBSERVACIONES</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td colspan="24" style="letter-spacing: 1px;text-align: center;color: red">
                <h2>NO HAY RESULTADOS QUE MOSTRAR</h2>
            </td>
        </tr>
    </tbody>
</table><?php
		}
	}elseif (isset($_GET['idRecibo'])) {
		$idRecibo	=	$_GET['idRecibo'];
		//	CONSULTAMOS LOS DATOS DEL RECIBO
		$ConsRecibo 	=	mysqli_query($MySQLi,"SELECT idRecibo, idCotizacion, CodeCotizacion, idUser, idCliente, Cliente, Moneda, PrecioDolar, Cantidad, CantidadUSD, Cant_Letras, Concepto, DATE_FORMAT(Fecha, '%W, %d de %M de %Y')AS Fecha, Sucursal, Tipo, SaldoAnterior, SaldoActual, Total, TotalUSD FROM Recibos WHERE idRecibo='$idRecibo' ")or die(mysqli_error($MySQLi).", en la línea: ".__LINE__." de ".__FILE__);
		$dataRecibo 	=	mysqli_fetch_assoc($ConsRecibo);

		//$idRecibo 		=	$dataRecibo['idRecibo'];
		$Sucursal 		=	$dataRecibo['Sucursal'];
		$idUser 		=	$dataRecibo['idUser'];
		$idCliente 	= $dataRecibo['idCliente'];
		$nameCliente 	=	"<span style='letter-spacing: 1px;font-size:14px'>".$dataRecibo['Cliente']."</span>";
		$Moneda 		=	$dataRecibo['Moneda'];
		$PrecioDolar 	=	$dataRecibo['PrecioDolar'];
		$LaCantidadDe 	=	"<span style='letter-spacing: 1px;font-size:14px'>".$dataRecibo['Cant_Letras']."</span>";
		$EnConceptoDe 	=	"<span style='letter-spacing: 1px;font-size:14px'>".$dataRecibo['Concepto']."</span>";
		if ($Moneda=='USD') {
			$PorAnticipo 	=	$dataRecibo['CantidadUSD'];
			$Total 			=	$dataRecibo['TotalUSD'];
		}else{
			$PorAnticipo 	=	$dataRecibo['Cantidad'];
			$Total 			=	$dataRecibo['Total'];
		}
		$Saldo 			=	$dataRecibo['SaldoActual'];		
		$FechaRecibo 	=	"<span style='letter-spacing: 1px;font-size:14px'>".$dataRecibo['Fecha']."</span>";

		$anticipoAnterior=	$dataRecibo['anticipoAnterior'];
		if ($idRecibo 	<	10) {
			$ReciboNum='<span style="letter-spacing: 1px">000000'.$idRecibo.'</span>';
		}elseif ($idRecibo<	100) {
			$ReciboNum='<span style="letter-spacing: 1px">00000'.$idRecibo.'</span>';
		}elseif ($idRecibo< 1000) {
			$ReciboNum='<span style="letter-spacing: 1px">0000'.$idRecibo.'</span>';
		}elseif ($idRecibo< 10000) {
			$ReciboNum='<span style="letter-spacing: 1px">000'.$idRecibo.'</span>';
		}elseif ($idRecibo< 100000) {
			$ReciboNum='<span style="letter-spacing: 1px">00'.$idRecibo.'</span>';
		}elseif ($idRecibo< 1000000) {
			$ReciboNum='<span style="letter-spacing: 1px">0'.$idRecibo.'</span>';
		}elseif ($idRecibo< 10000000) {
			$ReciboNum='<span style="letter-spacing: 1px">'.$idRecibo.'</span>';
		}
		//	BUSCAMOS LOS DATOS DEL CLIENTE
		// $consultCotiza	=	mysqli_query($MySQLi,"SELECT * FROM Cotizaciones WHERE idCotizacion='$idCotizacion' ");
		// $dataCotiza 	=	mysqli_fetch_assoc($consultCotiza);
		// $idCliente 		=	$dataCotiza['idCliente'];

		$consultCliente =	mysqli_query($MySQLi,"SELECT * FROM Clientes WHERE idCliente='$idCliente' ");
		$dataCliente 	=	mysqli_fetch_assoc($consultCliente);
		$nameEmpresa 	= $dataCliente['Empresa'];


		// $ConCotiza		=	mysqli_query($MySQLi,"SELECT * FROM Cotizaciones WHERE idCotizacion='$idCotizacion' ");
		// $dataCotiza 	=	mysqli_fetch_assoc($ConCotiza);
		// $ClaveCotiza 	=	$dataCotiza['Clave'];



		$mpdf 	=	new \Mpdf\Mpdf([
			'mode'			=>	'utf-8',
			'format' 		=> [280, 216],
			'orientation'	=>	'L',
			'margin_header'	=>	0,
			'margin_footer'	=>	0,
			'margin_left'	=>	0,
			'margin_top'	=>	27,
			'margin_right'	=>	0,
			'margin_bottom'	=>	45,
		]);
		$CSS 	=	file_get_contents('css/recibo.css');

		$mpdf->SetHTMLHeader('<img src="../assets/img/HEADER.png">');
		$mpdf->SetHTMLFooter('<img src="../assets/img/FOOTER.png">');


		$html 	=	'
		<body>
		 	<header>
		 		<div class="nav">
		 			<table class="tableNav">
		 				<tr>
		 					<th width="50%" class="text-left"><h1>RECIBO N&ordm; <span class="text-danger">'.$ReciboNum .'</span></h1></th>
		 					<th width="50%" class="text-right">';
		 					if ($Moneda=='USD') {
		 						$html.='<h1>$ USD <strong class="porAnticipo"> &nbsp;&nbsp;&nbsp;&nbsp;'.number_format($PorAnticipo,2) .' &nbsp;&nbsp;&nbsp;&nbsp;</strong></h1>';
		 					}else{
		 						$html.='<h1>Bs. <strong class="porAnticipo"> &nbsp;&nbsp;&nbsp;&nbsp;'.number_format($PorAnticipo,2) .' &nbsp;&nbsp;&nbsp;&nbsp;</strong></h1>';
		 					} $html.='	
		 					</th>
		 				</tr>
		 			</table>
		 		</div>
		 	</header>
		 	<section>';
		 		if ($Sucursal=='Cochabamba') { $html.='
		 			<p class="text-center sucursal"><strong>CENTRAL '.strtoupper($Sucursal) .'</strong></p>';
		 		}else{ $html.='
		 			<p class="text-center sucursal"><strong>SUCURSAL '.strtoupper($Sucursal) .'</strong></p>';
		 		}
		 		if ($nameEmpresa!='') {
		 			$html.='
		 		<p><strong>Recibí de: </strong> &nbsp;&nbsp;&nbsp;'.$nameCliente .'</p>
		 		<p><strong>Empresa: </strong> &nbsp;&nbsp; '.$nameEmpresa.'</p>';
		 		}else{
		 			$html.='
		 			<p><strong>Recibí de: </strong> &nbsp;&nbsp;&nbsp;'.$nameCliente .'</p>';
		 		}
		 		if ($Moneda=='USD') {
		 			$html.='<p><strong>La Cantidad de: </strong> &nbsp;&nbsp;&nbsp;'.$LaCantidadDe .' <span style="font-size:14px;letter-spacing:1px">dólares</span></p>';
		 		}else{
		 			$html.='<p><strong>La Cantidad de: </strong> &nbsp;&nbsp;&nbsp;'.$LaCantidadDe .' <span style="font-size:14px;letter-spacing:1px">bolivianos</span></p>';
		 		}$html.='
		 		<p><strong>Por concepto de: </strong> &nbsp;&nbsp;&nbsp;'.$EnConceptoDe .'</p>
		 		<p></p>
		 		<p class="text-center">'.$FechaRecibo.'</p>
		 		<p></p>
		 		<p class="text-center">
		 			<strong>Abono</strong> &nbsp;&nbsp;<strong class="porAnticipo" style="padding: !important 25px;"> &nbsp;&nbsp;&nbsp;&nbsp;'.number_format($PorAnticipo,2) .' &nbsp;&nbsp;&nbsp;&nbsp;</strong>&nbsp;&nbsp;&nbsp;&nbsp;
		 			<strong>Saldo</strong> &nbsp;&nbsp;<strong class="porAnticipo"> &nbsp;&nbsp;&nbsp;&nbsp;'.number_format($Saldo,2) .' &nbsp;&nbsp;&nbsp;&nbsp;</strong>&nbsp;&nbsp;&nbsp;&nbsp;
		 			<strong>Total</strong> &nbsp;&nbsp;<strong class="porAnticipo"> &nbsp;&nbsp;&nbsp;&nbsp;'.number_format($Total,2) .' &nbsp;&nbsp;&nbsp;&nbsp;</strong>&nbsp;&nbsp;&nbsp;&nbsp;
		 		</p>
		 		<p></p><p></p>
		 		<p>
		 			<table>
		 				<tr>
		 					<td width="25%">---------------------------------------------</td>
		 					<td width="25%"></td>
		 					<td width="25%"></td>
		 					<td width="25%">---------------------------------------------</td>
		 				</tr>
		 				<tr>
		 					<td width="25%" class="text-center">Recibí conforme efectivo</td>
		 					<td width="25%"></td>
		 					<td width="25%"></td>
		 					<td width="25%" class="text-center">Entregué conforme efectivo</td>
		 				</tr>
		 			</table>
		 		</p>
		 	</section>
		 	<footer>
		 		<strong>IMPORTANTE: </strong>No se aceptan cambios ni devoluciones de mercadería o dinero.
		 	</footer>
		 </body> ';
		$NamePDF	=	"Recibo No. ".$idRecibo;
		$mpdf->WriteHTML($CSS, \Mpdf\HTMLParserMode::HEADER_CSS);
		$mpdf->WriteHTML($html, \Mpdf\HTMLParserMode::HTML_BODY);
		$mpdf->Output($NamePDF.".pdf", "I");
	}elseif (isset($_GET['notaEntrega'])) {
		$idNotaEntrega 	=	$_GET['notaEntrega'];
		//$idCotizacion 	=	$_GET['notaEntrega'];
		$consultNotaE	=	mysqli_query($MySQLi,"SELECT idNotaE, idUser, idCliente, idCotizacion, DATE_FORMAT(Fecha, '%d de %M de %Y') AS Fecha, Sucursal, Observaciones FROM NotaEntrega WHERE idNotaE='$idNotaEntrega' ")or die(mysqli_error($MySQLi));
		$dataNotaE 		=	mysqli_fetch_assoc($consultNotaE);
		$idNotaEntrega	=	$dataNotaE['idNotaE'];
		$idUser 		=	$dataNotaE['idUser'];
		$idCliente 		=	$dataNotaE['idCliente'];
		$idCotizacion 	=	$dataNotaE['idCotizacion'];
		$Sucursal 		=	$dataNotaE['Sucursal'];
		$Observaciones 	=	$dataNotaE['Observaciones'];
		$FechaNotaEntre =	$dataNotaE['Fecha'];

		/*	CONSULTAMOS LOS DATOS DEL CLIENTE 	*/
		$consultCliente	=	mysqli_query($MySQLi,"SELECT Nombres, Apellidos, Empresa, Direccion, Celular, Otro, Ciudad FROM Clientes WHERE idCliente='$idCliente' ")or die(mysqli_error($MySQLi));
		$dataCliente 	=	mysqli_fetch_assoc($consultCliente);
		$NombreCliente 	=	$dataCliente['Nombres']." ".$dataCliente['Apellidos'];
		$DireccionClient=	$dataCliente['Direccion'];
		$CellCliente	=	$dataCliente['Celular'];
		$FijoCliente 	=	$dataCliente['Otro'];
		$EmpresaCliente= $dataCliente['Empresa'];

		// VALIDACION PARA FERIAS 2025
		$Sucursal = ($Sucursal != 'Ferias') ? $Sucursal : '<br/>';

		if ($idNotaEntrega 	<	10) {
			$ReciboNum='<span style="letter-spacing: 1px">000000'.$idNotaEntrega.'</span>';
		}elseif ($idNotaEntrega<	100) {
			$ReciboNum='<span style="letter-spacing: 1px">00000'.$idNotaEntrega.'</span>';
		}elseif ($idNotaEntrega< 1000) {
			$ReciboNum='<span style="letter-spacing: 1px">0000'.$idNotaEntrega.'</span>';
		}elseif ($idNotaEntrega< 10000) {
			$ReciboNum='<span style="letter-spacing: 1px">000'.$idNotaEntrega.'</span>';
		}elseif ($idNotaEntrega< 100000) {
			$ReciboNum='<span style="letter-spacing: 1px">00'.$idNotaEntrega.'</span>';
		}elseif ($idNotaEntrega< 1000000) {
			$ReciboNum='<span style="letter-spacing: 1px">0'.$idNotaEntrega.'</span>';
		}elseif ($idNotaEntrega< 10000000) {
			$ReciboNum='<span style="letter-spacing: 1px">'.$idNotaEntrega.'</span>';
		}
		
		$mpdf 	=	new \Mpdf\Mpdf([
			'mode'			=>	'utf-8',
			'format' 		=> [280, 216],
			'orientation'	=>	'L',
			'margin_header'	=>	0,
			'margin_footer'	=>	0,
			'margin_left'	=>	0,
			'margin_top'	=>	30,
			'margin_right'	=>	0,
			'margin_bottom'	=>	45,

		]);
		$CSS 	=	file_get_contents('css/NotaEntrega.css');

		$mpdf->SetHTMLHeader('<img src="../assets/img/HEADER.png">');
		$mpdf->SetHTMLFooter('<img src="../assets/img/FOOTER.png">');

		$html 	=	'
		<body>
		 	<header>
	 			<table class="header">
	 				<tr>		 				
	 					<td width="75%" class="text-center"><h1>NOTA DE ENTREGA - GARANTÍA </h1></td>	 					
	 					<td width="25%" class="text-right">
			 				<table class="top-right">
			 					<tr>
			 						<td class="ciudad">CIUDAD</td>
			 					</tr>
			 					<tr>
			 						<td class="nameCiudad fs-14">'. $Sucursal .'</td>
			 					</tr>
			 					<tr>
			 						<td class="ciudad">FECHA</td>
			 					</tr>
			 					<tr>
			 						<td class="nameCiudad">'.$FechaNotaEntre.'</td>
			 					</tr>
			 				</table>
		 				</td>
	 				</tr>	 				
	 			</table>
	 			<table class="header">
	 				<tr>
	 					<td width="100%" class="text-right"><h1 class=numNota>'.$ReciboNum.'</h1></td>
 					</tr>
	 			</table>
				<table class="name">
					<tbody>';
						if ($EmpresaCliente=='') { $html.='
							<tr>
								<td>Nombre: </td>
								<td colspan="3">'.$NombreCliente.'</td>
							</tr>
							<tr>
								<td width="15%">Dirección: </td>
								<td width="60%">'.$DireccionClient.'</td>
								<td width="10%">Teléfono: </td>
								<td width="15%">'.$CellCliente .'</td>
							</tr>';
						}else{ $html.='
							<tr>
								<td>Nombre: </td>
								<td>'.$NombreCliente.'</td>
								<td>Empresa: </td>
								<td>'.$EmpresaCliente.'</td>
							</tr>
							<tr>
								<td>Dirección: </td>
								<td>'.$DireccionClient.'</td>
								<td>Teléfono: </td>
								<td>'.$CellCliente .'</td>
							</tr>';
						} $html.='
					</tbody>
				</table>
				<table class="contenido">
					<tr class="">
						<th class="" width="12%">CANTIDAD</th>
						<th class="" width="64%">DETALLE</th>
						<th class="" width="12%">MARCA</th>
						<th class="" width="12%">MODELO</th>
					</tr>';
					/*	CONSULTAMOS LOS PRODUCTOS DE LA COTIZACION	*/
					$queryCotiza 	=	mysqli_query($MySQLi,"SELECT Clave FROM Cotizaciones WHERE idCotizacion='$idCotizacion' ")or die(mysqli_error($MySQLi));
					$dataCotiza 	=	mysqli_fetch_assoc($queryCotiza);
					$ClaveCotizacion=	$dataCotiza['Clave'] ;

					$queryProductos =	mysqli_query($MySQLi,"SELECT idProducto, Cantidad FROM ClaveTemporal WHERE Clave='$ClaveCotizacion' ")or die(mysqli_error($MySQLi));
					while ($dataProductos = mysqli_fetch_assoc($queryProductos)) {
						$idProducto =	$dataProductos['idProducto'];
						$CantProduct=	$dataProductos['Cantidad'];
						$html.='
						<tr>
							<td class="listado">'.$CantProduct.'</td>';
							$sqlProduct =	mysqli_query($MySQLi,"SELECT * FROM Productos WHERE idProducto='$idProducto' ")or die(mysqli_error($MySQLi));
							$dataProduct=	mysqli_fetch_assoc($sqlProduct);
							$Producto 	=	$dataProduct['Producto'];
							$Marca 		=	$dataProduct['Marca'];
							$Modelo 	=	$dataProduct['Modelo'];

							$html.='
							<td class="listado">'.$Producto.'</td>
							<td class="listado">'.$Marca .'</td>
							<td class="listado">'.$Modelo.'</td>
						</tr>';
					} $html.='			
					<tr>
						<td colspan="4" class="listado observaciones">OBSERVACIONES: '.$Observaciones.'</td>
					</tr>
				</table>
				<div class="footer">
					<strong>GARANTÍA</strong>: 3 (TRES) MESES DAÑOS DE FÁBRICA.
				</div>
				<div class="footer">
					<strong>IMPORTANTE</strong>: NO SE CUBRE MALA MANIPULACIÓN DE LOS EQUIPOS. NO SE ACEPTAN CAMBIOS NI DEVOLUCIÓN DE DINERO O EQUIPOS..
				</div>
				<table class=firmas>
					<tr>
						<td width="50%" class="text-center">________________________________________</td>
						<td width="50%" class="text-center">________________________________________</td>
					</tr>
					<tr>
						<td width="50%" class="text-center">FIRMA CLIENTE</td>
						<td width="50%" class="text-center">VENDEDOR(A)</td>
					</tr>
				</table>
		 	</header>
		 </body> ';
		$NamePDF=	"Nota de entrega No. ".$idNotaEntrega;
		$mpdf->WriteHTML($CSS, \Mpdf\HTMLParserMode::HEADER_CSS);
		$mpdf->WriteHTML($html, \Mpdf\HTMLParserMode::HTML_BODY);
		$mpdf->Output($NamePDF.".pdf", "I");
	}elseif (isset($_GET['Sucursal'])AND isset($_GET['fechaInicio'])AND isset($_GET['fechafin'])) {
		$Sucursal 	=	$_GET['Sucursal'];
		$INICIO 		=	$_GET['fechaInicio'];
		$CIERRE 		=	$_GET['fechafin'];

		header("Content-type: application/vnd.ms-excel; name='excel'");
		header("Content-Disposition: filename=Reporte_pagoComisiones.xls");
		header("Pragma: no-cache");
		header("Pragma: no-cache");
		header("Expires: 0");

		$queryVentas	=	mysqli_query($MySQLi,"SELECT SUM(TotalVentaUS)AS TotalVentaUS FROM Ventas WHERE Sucursal='$Sucursal' AND Fecha BETWEEN '$INICIO'AND'$CIERRE' ")or die(mysqli_error($MySQLi));
		$data			=	mysqli_fetch_assoc($queryVentas);
		$TotalVenta 	=	$data['TotalVentaUS'];
		/*$queryAbonos 	=	mysqli_query($MySQLi,"SELECT SUM(anticipoUSD)AS anticipoUSD FROM Abonos WHERE Sucursal='$Sucursal'AND Fecha BETWEEN '$INICIO'AND '$CIERRE' ");
		$dataAbonos 	=	mysqli_fetch_assoc($queryAbonos);
		$TotalAbonos 	=	$dataAbonos['anticipoUSD'];*/
		$TotalGeneral 	=	$TotalVenta;

		/*	BUSCAMOS LOS VENDEDORES DE LA SUCURSAL	*/
		$queryUsuarios 	=	mysqli_query($MySQLi,"SELECT * FROM Usuarios WHERE Ciudad='$Sucursal' AND Rango=1 ")or die(mysqli_error($MySQLi));
		$resultUsuarios	=	mysqli_num_rows($queryUsuarios);//Cantidad de empleados

		/*	CONSULTAMOS LA TABLA DE COMISIONES	*/
		$queryComisiones=	mysqli_query($MySQLi,"SELECT * FROM TablaComisiones WHERE Sucursal='$Sucursal' ");
		$dataComisiones	=	mysqli_fetch_assoc($queryComisiones);

		if ($TotalGeneral>=$dataComisiones['Meta2']) {
			$Comision 	=	$dataComisiones['Comision2'];
			$PagoUsers 	=	$TotalVenta/$resultUsuarios;
			/*$PagoUsers 	=	number_format(($TotalGeneral/$resultUsuarios),2);*/ ?>

<table border="1">
    <thead>
        <tr>
            <th colspan="6" style="text-align: center;">
                <h3>REPORTE PAGO DE COMISIONES DESDE EL <span style="color: green"><?php echo $INICIO ?></span> HASTA EL
                    <span style="color: red"><?php echo $CIERRE ?></span>
                </h3>
            </th>
        </tr>
        <tr>
            <th style="text-align: center;color:#fff;background-color: #97D086">N&ordm;</th>
            <th style="text-align: center;color:#fff;background-color: #97D086">VENDEDOR</th>
            <th style="text-align: center;color:#fff;background-color: #97D086">SUCURSAL</th>
            <th style="text-align: center;color:#fff;background-color: #97D086"><strong>USD</strong><br>VENTAS</th>
            <th style="text-align: center;color:#fff;background-color: #97D086">COMISION</th>
            <th style="text-align: center;color:#fff;background-color: #97D086">PAGO</th>
        </tr>
    </thead>
    <tbody> <?php
							$Number = 1;
							$numeroPersonal=$dataComisiones['personal_dividir'];
							// while ($dataUsuarios = mysqli_fetch_assoc($queryUsuarios)) { 
								while ($numeroPersonal>=$Number) { 
								$NameUser 	=	$dataUsuarios['Nombres']." ".$datosUsuario['Apellidos'] ?>
        <tr>
            <td style="text-align: center;"><?php echo $Number ?></td>
            <td><?php
						//echo $NameUser 
						echo 'Vendedor #Nro '.$Number;
						 
						 ?></td>
            <td><?php echo $Sucursal ?></td>
            <td>$ <?php echo number_format($TotalVenta,2) ?></td>
            <?php
							$PagoComision = $TotalVenta*($Comision/100) ;
							//$PagoVendedor = number_format(($PagoComision/$resultUsuarios),2);
							$PagoVendedor = number_format(($PagoComision/$numeroPersonal),2);
						?>
            <td style="text-align: center;"><?php echo $Comision ?> &nbsp;%</td>
            <td style="text-align: right;">$ <?php echo number_format($PagoVendedor,2); //$PagoUsers ?></td>
        </tr>
        <?php $Number++; }?>


    </tbody>
</table><?php mysqli_close($MySQLi);
		}elseif ($TotalGeneral>=$dataComisiones['Meta1']) {
			$Comision 	=	$dataComisiones['Comision1'];
			$PagoUsers 	=	$TotalVenta/$resultUsuarios; ?>

<table border="1">
    <thead>
        <tr>
            <th colspan="6" style="text-align: center;">
                <h3>REPORTE PAGO DE COMISIONES DESDE EL <span style="color: green"><?php echo $INICIO ?></span> HASTA EL
                    <span style="color: red"><?php echo $CIERRE ?></span>
                </h3>
            </th>
        </tr>
        <tr>
            <th style="text-align: center;color:#fff;background-color: #97D086">N&ordm;</th>
            <th style="text-align: center;color:#fff;background-color: #97D086">VENDEDOR</th>
            <th style="text-align: center;color:#fff;background-color: #97D086">SUCURSAL</th>
            <th style="text-align: center;color:#fff;background-color: #97D086">TOTAL<br>VENTAS</th>
            <th style="text-align: center;color:#fff;background-color: #97D086">COMISION</th>
            <th style="text-align: center;color:#fff;background-color: #97D086">PAGO</th>
        </tr>
    </thead>
    <tbody> <?php
							$Number = 1;
							$numeroPersonal=$dataComisiones['personal_dividir'];
							// while ($dataUsuarios = mysqli_fetch_assoc($queryUsuarios)) { 
								while ($numeroPersonal>=$Number) { 
								$NameUser 	=	$dataUsuarios['Nombres']." ".$datosUsuario['Apellidos'] ?>
        <tr>
            <td style="text-align: center;"><?php echo $Number ?></td>
            <td><?php 
						
						//echo $NameUser
						echo 'Vendedor #Nro '.$Number;
						
						
						?></td>
            <td><?php echo $Sucursal ?></td>
            <td>$ <?php echo number_format($TotalVenta,2) ?></td>
            <?php
							$PagoComision = $TotalVenta*($Comision/100);
							$PagoVendedor = number_format(($PagoComision/$numeroPersonal),2);
						?>
            <td style="text-align: center;"><?php echo $Comision ?> &nbsp;%</td>
            <td style="text-align: right;">$ <?php echo number_format($PagoVendedor,2); //$PagoUsers ?></td>
        </tr><?php $Number++; }?>
    </tbody>
</table><?php mysqli_close($MySQLi);
		}else{ ?>
<table border="1">
    <thead>
        <tr>
            <th colspan="6" style="text-align: center;">
                <h3>REPORTE PAGO DE COMISIONES DESDE EL <span style="color: green"><?php echo $INICIO ?></span> HASTA EL
                    <span style="color: red"><?php echo $CIERRE ?></span>
                </h3>
            </th>
        </tr>
        <tr>
            <th style="text-align: center;color:#fff;background-color: #97D086">N&ordm;</th>
            <th style="text-align: center;color:#fff;background-color: #97D086">VENDEDOR</th>
            <th style="text-align: center;color:#fff;background-color: #97D086">SUCURSAL</th>
            <th style="text-align: center;color:#fff;background-color: #97D086">TOTAL<br>VENTAS</th>
            <th style="text-align: center;color:#fff;background-color: #97D086">COMISION</th>
            <th style="text-align: center;color:#fff;background-color: #97D086">PAGO</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td colspan="6" style="text-align: center;color: red">NO ALCANZARON LA META DE VENTAS</td>
        </tr>
    </tbody>
</table><?php
		}
	}elseif (isset($_POST['action'])) {
		$miCiudad 				=	$_POST['sucursal'];
		$CorreoCliente		=	$_POST['correo'];
		$Asunto 					=	$_POST['asunto'];
		$Mensaje 					=	$_POST['mensaje'];
		$idCotizacion			=	$_POST['idCotizacion'];
		//OBTENEMOS LOS DATOS DE LA COTIZACION
		$queryCotiza			=	mysqli_query($MySQLi,"SELECT Code, Clave, idUser, idCliente, Forma_Pago, DATE_FORMAT(FinFecha_Oferta, '%d de %M de %Y') AS FinFecha_Oferta , Dias_Entrega, Comentarios, Sucursal, DATE_FORMAT(Fecha, '%d de %M de %Y') AS Fecha FROM Cotizaciones WHERE idCotizacion='$idCotizacion' ");
		$dataCotiza				=	mysqli_fetch_assoc($queryCotiza);
		$CodigoCotiza 		=	$dataCotiza['Code'];
		$ClaveCotizacion	=	$dataCotiza['Clave'];
		$idUser 					=	$dataCotiza['idUser'];
		$idCliente 				=	$dataCotiza['idCliente'];
		$FormaPago				=	$dataCotiza['Forma_Pago'];
		$FinOferta 				=	$dataCotiza['FinFecha_Oferta'];
		$Entrega 					=	$dataCotiza['Dias_Entrega'];
		$Comentarios 			=	$dataCotiza['Comentarios'];
		$Sucursal 				=	$dataCotiza['Sucursal'];
		//$Fecha 					=	$dataCotiza['Fecha'];

		//OBTENEMOS LOS DATOS DEL CLIENTE
		$queryCliente 		=	mysqli_query($MySQLi,"SELECT * FROM Clientes WHERE idCliente='$idCliente' ");
		$dataCliente 			=	mysqli_fetch_assoc($queryCliente);
		$FullNameCliente	=	$dataCliente['Nombres']." ".$dataCliente['Apellidos'];
		$CorreoCliente		=	$dataCliente['Correo'];
		$EmpresaCliente		=	$dataCliente['Empresa'];

		//OBTENEMOS LOS DATOS DEL USUARIO
		$queryUsuario 		=	mysqli_query($MySQLi,"SELECT * FROM Usuarios WHERE idUser='$idUser' ");
		$dataUsuario 			=	mysqli_fetch_assoc($queryUsuario);
		$FullNameUsuario	=	$dataUsuario['Nombres']." ".$dataUsuario['Apellidos'];
		$CiudadUsuario 		=	$dataUsuario['Ciudad'];
		$TelefonoUsuario	=	$dataUsuario['Telefono'];
		$mpdf 						=	new \Mpdf\Mpdf([
			'mode'					=>	'utf-8',
			'format' 				=> [280, 216],
			'orientation'		=>	'L',
			'margin_header'	=>	0,
			'margin_footer'	=>	0,
			'margin_left'		=>	0,
			'margin_top'		=>	27,
			'margin_right'	=>	0,
			'margin_bottom'	=>	45,
		]);
		$CSS 	=	file_get_contents('css/reporteCotizacion.css');
		$mpdf->SetHTMLHeader('<img src="../assets/img/HEADER.png">');
		$html 	=	'
		<table class="info">
			<tbody>
				<tr>
					<td class="text-left fs-16">
						Señor/a: <strong>'.$FullNameCliente .'</strong><br>
						Empresa: <strong>'.$EmpresaCliente .'</strong><br>
						<a target="_blank" href="mailto:'.$CorreoCliente .'" style="text-decoration: none;">'.$CorreoCliente .'</a>
					</td>
					<td class="text-right">
						<h2 style="color: red">'.$CodigoCotiza .'</h2><br>
						'.$Fecha/*strtoupper($Fecha)*/ .'<br>
					</td>
				</tr>
			</tbody>
		</table>';
		$queryProducto		=	mysqli_query($MySQLi,"SELECT * FROM ClaveTemporal WHERE Clave='$ClaveCotizacion' ");
		$cantidadClaves 	=	mysqli_num_rows($queryProducto);
		$cantidadTablas 	=	2;
		$NumPaginas 			=	round($cantidadClaves/$cantidadTablas);

		while ( $dataClave=	mysqli_fetch_assoc($queryProducto)) {

			$idProducto 		=	$dataClave['idProducto'];
			$CantidadProduct=	$dataClave['Cantidad'];
			$PrecioLista 		=	$dataClave['PrecioLista'];
			$PrecioVenta 		=	$dataClave['PrecioOferta'];

			$ConsultaProduct=	mysqli_query($MySQLi,"SELECT * FROM Productos WHERE idProducto='$idProducto' ");
			$infoPro 				=	mysqli_fetch_array($ConsultaProduct);
			$Description		=	$infoPro['Descripcion'];
			$ImagenProducto =	$infoPro['Imagen'];
			$NameProducto 	=	$infoPro['Producto'];
			$MarcaProducto 	= 	$infoPro['Marca'];
			$ModeloProducto =	$infoPro['Modelo'];
			$Total 					=	$CantidadProduct*$PrecioVenta;
			$html .='
			<table class="SaltoTabla" border="1">
				<thead>
					<tr class="verde">
						<td class="text-left nameProducto" colspan="4">Producto: <strong>'.$NameProducto .'</strong></td>
						<td class="text-left nameProducto" colspan="2">Marca: <strong>'.$MarcaProducto .'</strong></td>
						<td class="text-left nameProducto" colspan="2">Modelo: <strong>'.$ModeloProducto .'</strong></td>
					</tr>
					<tr class="azul">
						<th style="padding: 5px" colspan="6" width="70%">Descripción</th>
						<th style="padding: 5px" colspan="2" width="30%">Imagen</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td style="padding: 15px" width="65%" colspan="5">'.$Description .'</td>
						<td style="padding: 15px" width="35%" colspan="3" class="text-center"><img src="https://sistema.yulisrl.com/Productos/'.$ImagenProducto .'" alt="" width="100"></td>
					</tr>
					<tr class="amarillo">
						<td colspan="2" class="footer">Cantidad: '.$CantidadProduct .'</td>
						<td colspan="2" class="footer">Precio Lista: $ '.number_format($PrecioLista,2) .'</td>
						<td colspan="2" class="footer verde" style="color: #fff">Precio Especial: $ '.number_format($PrecioVenta,2) .'</td>
						<td colspan="2" class="footer verde" style="color: #fff">Total: $ '.number_format($Total,2) .'</td>
					</tr>
				</tbody>
			</table> ';		
		}
		$html.='
		<table class="infoFooter">		
			<tr>
				<td>VALIDEZ DE LA OFERTA: hasta el <strong style="color: red">'.$FinOferta .'</strong></td>
			</tr>
			<tr>
				<td>TIEMPO DE ENTREGA: '.$Entrega .'</td>
			</tr>
			<tr>
				<td>COMENTARIOS: '.$Comentarios .'</td>
			</tr>
		</table>
		<p  class="infoFooter2">
			<strong style="letter-spacing: 1px;">Cualquier consulta o requerimiento no dude en comunicarse con nosotros</strong>  <br>
			Atte: <strong style="font-family: cursive;">'.$FullNameUsuario .'</strong><br>
			Asesor de Ventas  <br>
			Teléfono: <strong>'.$TelefonoUsuario .'</strong>
		</p>';
		
		$NamePDF=	utf8_decode("Cotización ".$CodigoCotiza);
		$mpdf->WriteHTML($CSS, \Mpdf\HTMLParserMode::HEADER_CSS);
		$mpdf->WriteHTML($html, \Mpdf\HTMLParserMode::HTML_BODY);
		$mpdf->Output($NamePDF.".pdf");
		
		/*	TRATAREMOS DE ENVIAR EL CORREO	*/
		
		try {
			$mail->SMTPDebug = 0; 		//SMTP::DEBUG_SERVER;                      // Enable verbose debug output
			$mail->isSMTP();                                            // Send using SMTP
			$mail->Host       = 'mail.yulisrl.com';                    // Set the SMTP server to send through
			$mail->SMTPAuth   = true;                                   // Enable SMTP authentication
	
			$queryMails	=	mysqli_query($MySQLi,"SELECT * FROM CuentasMail WHERE Sucursal='$miCiudad' ");
				$dataMails 	=	mysqli_fetch_assoc($queryMails);
	
				$mail->Username   = $dataMails['Correo'];                     // SMTP username
				$mail->Password   = $dataMails['Password'];                   // SMTP password
				$mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;	          // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` also accepted
				$mail->Port       = 465;                                      // TCP port to connect to
	
				//Recipients
				$mail->setFrom($dataMails['Correo'], 'Depto de Ventas '.$miCiudad );
				$mail->addAddress($CorreoCliente);     // Add a recipient
				//$mail->addAddress('ellen@example.com');               // Name is optional
				$mail->addReplyTo($dataMails['Correo'], 'Depto de Ventas '.$miCiudad );
				//$mail->addCC('cc@example.com');
				$mail->addBCC($dataMails['Correo'], 'Depto de Ventas '.$miCiudad );
	
				// Attachments
				$mail->addAttachment($NamePDF.".pdf");         // Add attachments
				//$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
	
				// Content
				$mail->Charset = 'utf-8';
				$mail->isHTML(true);                                  // Set email format to HTML
				$mail->Subject = utf8_decode($Asunto);
				$mail->Body    = utf8_decode($Mensaje);
				//$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
	
				$mail->send();
				/*	AGREGAMOS EL REGISTRO DEL ENVIO DE CORREO A LA DB	*/
				$QuienEnvia 	=	$dataMails['Correo'];
				mysqli_query($MySQLi,"INSERT INTO Log_Correos (idUser, idCliente, Asunto, Mensaje, Para, Desde, Sucursal, Tipo) VALUES ('$idUser', '$idCliente', '$Asunto', '$Mensaje', '$CorreoCliente', '$QuienEnvia', '$miCiudad', 'Cotiza') ")or die(mysqli_error($MySQLi));
	
				$borrar 	=	$NamePDF.".pdf";
				/* 	BORRAMOS EL ARCHIVO PDF	*/
				$files = glob($borrar);		//Borramos el fichero con esenombre
				foreach($files as $file){
					if(is_file($file))
					unlink($file); //elimino el fichero
				} mysqli_close($MySQLi); ?>
				<!DOCTYPE html>
				<html lang="en">
					<head>
						<meta charset="UTF-8">
						<title>Correo enviado</title>
						<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
					</head>
					<body>
						<div class="container mt-5">
							<div class="row mt-5">
								<div class="col-md-4"></div>
								<div class="col-md-4 text-center m-auto">
									<div class="alert alert-success" role="alert">
									  El correo ha sido enviadocorrectamente a: <br><?php echo $CorreoCliente ?>
									</div>
								</div>
							</div>
						</div>
						<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
						<script type="text/javascript">
							setTimeout(function(){
								//window.close();
								window.history.back(-1);
							},3000);
						</script>	
					</body>
				</html><?php
			} catch (Exception $e) {
		    echo "El correo no fué enviado debido a un error: {$mail->ErrorInfo}<br>Error en la línea: ".__LINE__;
		}
	}elseif (isset($_POST['AbonosCreditos'])) {
		$Sucursal 	=	$_POST['Sucursal'];
		$INICIO 	=	$_POST['Start'];
		$FIN 		=	$_POST['End'];

		$queryAbonos=	mysqli_query($MySQLi,"SELECT idRecibo, idUser, idCliente, Cliente, Sucursal, idCotizacion, CodeCotizacion, Moneda, PrecioDolar, LaCantidadDe, EnConceptoDe, porAbono, AbonoUSD, SaldoAnterior, SaldoActual, Total, TotalUSD, DATE_FORMAT(Fecha, '%d-%m-%Y')AS Fecha FROM Creditos WHERE Sucursal='$Sucursal'AND Fecha BETWEEN '$INICIO' AND '$FIN' ")or die(mysqli_error($MySQLi)."<br>Error en la línea: ".__LINE__);
		$resultQV 	=	mysqli_num_rows($queryAbonos);
		if ($resultQV>0) {
			header("Content-type: application/vnd.ms-excel; name='excel'");
			header("Content-Disposition: filename=Reporte_abonos(Creditos).xls");
			header("Pragma: no-cache");
			header("Pragma: no-cache");
			header("Expires: 0"); ?>
<table border="1">
    <thead>
        <tr>
            <th colspan="15" style="text-align: center;">
                <h3>REPORTE DE ABONOS (Ventas al <?php echo utf8_decode("Crédito") ?>) DESDE EL <span
                        style="color: green"><?php echo $INICIO ?></span> HASTA EL <span
                        style="color: red"><?php echo $FIN ?></span></h3>
            </th>
        </tr>
        <tr>
            <th style="text-align: center;color:#fff;background-color: #97D086">N&ordm;</th>
            <th style="text-align: center;color:#fff;background-color: #97D086">FECHA</th>
            <th style="text-align: center;color:#fff;background-color: #97D086">RECIBO</th>
            <th style="text-align: center;color:#fff;background-color: #97D086">CODIGO DE LA<br>COTIZACION</th>
            <th style="text-align: center;color:#fff;background-color: #97D086">CLIENTE</th>
            <th style="text-align: center;color:#fff;background-color: #97D086">PRODUCTO(s)</th>
            <th style="text-align: center;color:#fff;background-color: #97D086">NIT</th>
            <th style="text-align: center;color:#fff;background-color: #97D086">TELEFONO</th>
            <th style="text-align: center;color:#fff;background-color: #97D086">MONEDA</th>
            <th style="text-align: center;color:#fff;background-color: #97D086">PRECIO<br>DOLAR</th>
            <th style="text-align: center;color:#fff;background-color: #97D086">TOTAL
                <?php echo utf8_decode("CRÉDITO") ?><br>EN USD</th>
            <th style="text-align: center;color:#fff;background-color: #97D086">TOTAL
                <?php echo utf8_decode("CRÉDITO") ?><br>EN BS</th>
            <th style="text-align: center;color:#fff;background-color: #97D086">ABONO<br>EN<br>USD</th>
            <th style="text-align: center;color:#fff;background-color: #97D086">ABONO<br>EN<br>Bs</th>
            <th style="text-align: center;color:#fff;background-color: #97D086">VENDEDOR</th>
            <th style="text-align: center;color:#fff;background-color: #97D086">SUCURSAL</th>
        </tr>
    </thead>
    <tbody>
        <?php
						$Number 	=	1;
						while ($dataAbono = mysqli_fetch_assoc($queryAbonos)) {
					?>
        <tr>
            <td style="text-align: center">
                &nbsp;&nbsp;&nbsp;&nbsp;<?php echo $Number ?> &nbsp;&nbsp;&nbsp;&nbsp;
            </td>

            <td>
                &nbsp;&nbsp;&nbsp;&nbsp;<?php echo $dataAbono['Fecha'] ?> &nbsp;&nbsp;&nbsp;&nbsp;
            </td>

            <td style="text-align: center;background-color: #DEE97E">
                &nbsp;&nbsp;&nbsp;&nbsp;<?php echo $dataAbono['idRecibo'] ?> &nbsp;&nbsp;&nbsp;&nbsp;
            </td>

            <td>
                &nbsp;&nbsp;&nbsp;&nbsp;<?php echo $dataAbono['CodeCotizacion'] ?> &nbsp;&nbsp;&nbsp;&nbsp;
            </td>

            <?php
							/*	DATOS DEL CLIENTE	*/
							$idCliente		=	$dataAbono['idCliente'];
							$consultCliente =	mysqli_query($MySQLi,"SELECT * FROM Clientes WHERE idCliente='$idCliente' ");
							$datosCliente	=	mysqli_fetch_assoc($consultCliente);
							if ($datosCliente['Celular']=='' AND $datosCliente['Otro']=='') {
								$TelCliente	=	' &nbsp;&nbsp;&nbsp;&nbsp;VAC&icute;O &nbsp;&nbsp;&nbsp;&nbsp;';
							}elseif ($datosCliente['Otro']=='') {
								$TelCliente	=	' &nbsp;&nbsp;&nbsp;&nbsp;'.$datosCliente['Celular'].' &nbsp;&nbsp;&nbsp;&nbsp;';
							}elseif ($datosCliente['Celular']=='') {
								$TelCliente	=	' &nbsp;&nbsp;&nbsp;&nbsp;'.$datosCliente['Otro'].' &nbsp;&nbsp;&nbsp;&nbsp;';
							}else{
								$TelCliente	=	' &nbsp;&nbsp;&nbsp;&nbsp;'.$datosCliente['Celular']." / ".$datosCliente['Otro'].' &nbsp;&nbsp;&nbsp;&nbsp;';
							}
							$NameCliente 	=	' &nbsp;&nbsp;&nbsp;&nbsp;'.$datosCliente['Nombres'] ." ".$datosCliente['Apellidos'] .' &nbsp;&nbsp;&nbsp;&nbsp;';

							/*	DATOS DEL VENDEDOR	*/
							$idUsuario		=	$dataAbono['idUser'];
							$consultUsuario	=	mysqli_query($MySQLi,"SELECT Nombres, Apellidos FROM Usuarios WHERE idUser='$idUsuario' ");
							$datosUsuario	=	mysqli_fetch_assoc($consultUsuario);
							$Vendedor 		=	' &nbsp;&nbsp;&nbsp;&nbsp;'.$datosUsuario['Nombres']." ".$datosUsuario['Apellidos'] .' &nbsp;&nbsp;&nbsp;&nbsp;';
						?>
            <td><?php echo utf8_decode($NameCliente) ?></td>
            <td>
                <?php
								$queryIDCot	=	mysqli_query($MySQLi,"SELECT Clave FROM Cotizaciones WHERE idCotizacion=".$dataAbono['idCotizacion']." ");
								$dataClave	=	mysqli_fetch_assoc($queryIDCot);
								$ClaveCot 	=	$dataClave['Clave'];
								$queryClaveT=	mysqli_query($MySQLi,"SELECT * FROM ClaveTemporal WHERE Clave='$ClaveCot'	 ");
								while ($dataPro = mysqli_fetch_assoc($queryClaveT)) {
									$idProductoCot=	$dataPro['idProducto'];
									$CantidadPCot = $dataPro['Cantidad'];
									$PrecioListCo = $dataPro['PrecioLista'];
									$PrecioOfeCot = $dataPro['PrecioOferta'];
									$queryFindPro =	mysqli_query($MySQLi,"SELECT * FROM Productos WHERE idProducto=".$idProductoCot." ");
									$dataDetalle 	=	mysqli_fetch_assoc($queryFindPro);
									echo $CantidadPCot." - ".utf8_decode($dataDetalle['Producto']." / ".$dataDetalle['Marca']." / ".$dataDetalle['Modelo']."<br>");
								}
							?>
            </td>
            <td>
                <?php
								if ($datosUsuario['NIT']=='') {
									echo " &nbsp;&nbsp;&nbsp;&nbsp;No proporcion&oacute; &nbsp;&nbsp;&nbsp;&nbsp;";
								}else{
									echo ' &nbsp;&nbsp;&nbsp;&nbsp;'.$datosUsuario['NIT'].' &nbsp;&nbsp;&nbsp;&nbsp;';
								}
							?>
            </td>
            <td><?php echo $TelCliente ?></td>
            <?php
							/*	MONEDA	*/
							if ($dataAbono['Moneda']=='USD') {
								echo '<td style="background-color: #DEA4BF;text-align:center">'.$dataAbono['Moneda'].'</td>';
							}else{
								echo '<td style="background-color: #72B0F5;text-align:center">'.$dataAbono['Moneda'].'</td>';
							}
						?>
            <td style="text-align: right;">
                <?php
								/*	PRECIO DOLAR	*/
								if ($dataAbono['Moneda']=='USD') {
									echo "";
								}else{
									// echo $dataAbono['PrecioDolar'];
									echo number_format(($dataAbono['PrecioDolar']), 2 );
								}
							?>
            </td>
            <td> <?php
							if ($dataAbono['TotalUSD']=='') {
								echo "";
							}else{
								// echo $dataAbono['TotalUSD'];
								echo number_format(($dataAbono['TotalUSD']), 2);
							} ?>
            </td>
            <td> <?php
							if ($dataAbono['Total']=='') {
								echo "";
							}else{
						 		// echo $dataAbono['Total'];
								echo number_format(($dataAbono['Total']), 2 );
							}?>
            </td>
            <td>
                <?php
								if ($dataAbono['Moneda']=='USD') {
									$AbonoenUSD		=	$dataAbono['AbonoUSD'];
									// $PrecioVentaUSD	=	$dataAbono['PrecioVentaUSD'];
									// $Cantidad 		=	$dataAbono['Cantidad'];
									// $PagoenUSD 		=	$PrecioVentaUSD*$Cantidad;
									// echo $AbonoenUSD;
									echo number_format(($AbonoenUSD), 2 );
								}else{
									echo "";
								}
							?>
            </td>
            <td>
                <?php
								if ($dataAbono['Moneda']=='Bs') {
									$AbonoenBs 		=	$dataAbono['porAbono'];
									// $PrecioVentaBs	=	$dataAbono['PrecioVentaBs'];
									// $Cantidad 		=	$dataAbono['Cantidad'];
									// $PagoenBs 		=	$PrecioVentaBs*$Cantidad;
									// echo $AbonoenBs;
									echo number_format(($AbonoenBs), 2 );
								}else{
									echo "";
								}
							?>
            </td>
            <td> &nbsp;&nbsp;&nbsp;&nbsp;<?php echo utf8_decode($Vendedor) ?> &nbsp;&nbsp;&nbsp;&nbsp;</td>
            <td> &nbsp;&nbsp;&nbsp;&nbsp;<?php echo $dataAbono['Sucursal'] ?> &nbsp;&nbsp;&nbsp;&nbsp;</td>
        </tr>
        <?php $Number++; } mysqli_close($MySQLi); ?>
    </tbody>
</table><?php
		}else{
			header("Content-type: application/vnd.ms-excel; name='excel'");
			header("Content-Disposition: filename=Reporte_abonos(Creditos).xls");
			header("Pragma: no-cache");
			header("Pragma: no-cache");
			header("Expires: 0"); ?>
<table border="1">
    <thead>
        <tr>
            <th colspan="15" style="text-align: center;">
                <h3>REPORTE DE ABONOS (Ventas al <?php echo utf8_decode("Crédito")?>)</h3>
            </th>
        </tr>
        <tr>
            <th style="text-align: center;color:#fff;background-color: #97D086">N&ordm;</th>
            <th style="text-align: center;color:#fff;background-color: #97D086">FECHA</th>
            <th style="text-align: center;color:#fff;background-color: #97D086">RECIBO</th>
            <th style="text-align: center;color:#fff;background-color: #97D086">CODIGO DE LA<br>COTIZACION</th>
            <th style="text-align: center;color:#fff;background-color: #97D086">CLIENTE</th>
            <!-- <th style="text-align: center;color:#fff;background-color: #97D086">PRODUCTO(s)</th> -->
            <th style="text-align: center;color:#fff;background-color: #97D086">NIT</th>
            <th style="text-align: center;color:#fff;background-color: #97D086">TELEFONO</th>
            <th style="text-align: center;color:#fff;background-color: #97D086">MONEDA</th>
            <th style="text-align: center;color:#fff;background-color: #97D086">PRECIO<br>DOLAR</th>
            <th style="text-align: center;color:#fff;background-color: #97D086">TOTAL
                <?php echo utf8_decode("CRÉDITO") ?><br>EN USD</th>
            <th style="text-align: center;color:#fff;background-color: #97D086">TOTAL
                <?php echo utf8_decode("CRÉDITO") ?><br>EN BS</th>
            <th style="text-align: center;color:#fff;background-color: #97D086">ABONO<br>EN<br>USD</th>
            <th style="text-align: center;color:#fff;background-color: #97D086">ABONO<br>EN<br>Bs</th>
            <th style="text-align: center;color:#fff;background-color: #97D086">VENDEDOR</th>
            <th style="text-align: center;color:#fff;background-color: #97D086">SUCURSAL</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td colspan="15" style="letter-spacing: 1px;text-align: center;color: red">
                <h2>NO HAY RESULTADOS QUE MOSTRAR</h2>
            </td>
        </tr>
    </tbody>
</table><?php
		}
	}elseif (isset($_POST['AbonosAnticipos'])) {
		$Sucursal 	=	$_POST['Sucursal'];
		$INICIO 	=	$_POST['Start'];
		$FIN 		=	$_POST['End'];

		$queryAbonos=	mysqli_query($MySQLi,"SELECT idRecibo, idUser, idCliente, Cliente, Sucursal, idCotizacion, CodeCotizacion, Moneda, PrecioDolar, LaCantidadDe, EnConceptoDe, porAnticipo, anticipoUSD, SaldoAnterior, SaldoActual, Total, TotalUSD, DATE_FORMAT(Fecha, '%d-%m-%Y')AS Fecha FROM Abonos WHERE Sucursal='$Sucursal'AND Fecha BETWEEN '$INICIO' AND '$FIN' ")or die(mysqli_error($MySQLi)."<br>Error en la línea: ".__LINE__);
		$resultQV 	=	mysqli_num_rows($queryAbonos);
		if ($resultQV>0) {
			header("Content-type: application/vnd.ms-excel; name='excel'");
			header("Content-Disposition: filename=Reporte_abonos(Anticipo).xls");
			header("Pragma: no-cache");
			header("Pragma: no-cache");
			header("Expires: 0"); ?>
<table border="1">
    <thead>
        <tr>
            <th colspan="15" style="text-align: center;">
                <h3>REPORTE DE ABONOS (Ventas por Anticipo) DESDE EL <span
                        style="color: green"><?php echo $INICIO ?></span> HASTA EL <span
                        style="color: red"><?php echo $FIN ?></span></h3>
            </th>
        </tr>
        <tr>
            <th style="text-align: center;color:#fff;background-color: #97D086">N&ordm;</th>
            <th style="text-align: center;color:#fff;background-color: #97D086">FECHA</th>
            <th style="text-align: center;color:#fff;background-color: #97D086">RECIBO</th>
            <th style="text-align: center;color:#fff;background-color: #97D086">CODIGO DE LA<br>COTIZACION</th>
            <th style="text-align: center;color:#fff;background-color: #97D086">CLIENTE</th>
            <th style="text-align: center;color:#fff;background-color: #97D086">PRODUCTO(s)</th>
            <th style="text-align: center;color:#fff;background-color: #97D086">NIT</th>
            <th style="text-align: center;color:#fff;background-color: #97D086">TELEFONO</th>
            <th style="text-align: center;color:#fff;background-color: #97D086">MONEDA</th>
            <th style="text-align: center;color:#fff;background-color: #97D086">PRECIO<br>DOLAR</th>
            <th style="text-align: center;color:#fff;background-color: #97D086">TOTAL<br>EN USD</th>
            <th style="text-align: center;color:#fff;background-color: #97D086">TOTAL<br>EN BS</th>
            <th style="text-align: center;color:#fff;background-color: #97D086">ABONO<br>EN<br>USD</th>
            <th style="text-align: center;color:#fff;background-color: #97D086">ABONO<br>EN<br>Bs</th>
            <th style="text-align: center;color:#fff;background-color: #97D086">VENDEDOR</th>
            <th style="text-align: center;color:#fff;background-color: #97D086">SUCURSAL</th>
        </tr>
    </thead>
    <tbody>
        <?php
						$Number 	=	1;
						while ($dataAbono = mysqli_fetch_assoc($queryAbonos)) {
					?>
        <tr>
            <td style="text-align: center">
                &nbsp;&nbsp;&nbsp;&nbsp;<?php echo $Number ?> &nbsp;&nbsp;&nbsp;&nbsp;
            </td>

            <td>
                &nbsp;&nbsp;&nbsp;&nbsp;<?php echo $dataAbono['Fecha'] ?> &nbsp;&nbsp;&nbsp;&nbsp;
            </td>

            <td style="text-align: center;background-color: #DEE97E">
                &nbsp;&nbsp;&nbsp;&nbsp;<?php echo $dataAbono['idRecibo'] ?> &nbsp;&nbsp;&nbsp;&nbsp;
            </td>

            <td>
                &nbsp;&nbsp;&nbsp;&nbsp;<?php echo $dataAbono['CodeCotizacion'] ?> &nbsp;&nbsp;&nbsp;&nbsp;
            </td>

            <?php
							/*	DATOS DEL CLIENTE	*/
							$idCliente		=	$dataAbono['idCliente'];
							$consultCliente =	mysqli_query($MySQLi,"SELECT * FROM Clientes WHERE idCliente='$idCliente' ");
							$datosCliente	=	mysqli_fetch_assoc($consultCliente);
							if ($datosCliente['Celular']=='' AND $datosCliente['Otro']=='') {
								$TelCliente	=	' &nbsp;&nbsp;&nbsp;&nbsp;VAC&icute;O &nbsp;&nbsp;&nbsp;&nbsp;';
							}elseif ($datosCliente['Otro']=='') {
								$TelCliente	=	' &nbsp;&nbsp;&nbsp;&nbsp;'.$datosCliente['Celular'].' &nbsp;&nbsp;&nbsp;&nbsp;';
							}elseif ($datosCliente['Celular']=='') {
								$TelCliente	=	' &nbsp;&nbsp;&nbsp;&nbsp;'.$datosCliente['Otro'].' &nbsp;&nbsp;&nbsp;&nbsp;';
							}else{
								$TelCliente	=	' &nbsp;&nbsp;&nbsp;&nbsp;'.$datosCliente['Celular']." / ".$datosCliente['Otro'].' &nbsp;&nbsp;&nbsp;&nbsp;';
							}
							$NameCliente 	=	' &nbsp;&nbsp;&nbsp;&nbsp;'.$datosCliente['Nombres']." ".$datosCliente['Apellidos'].' &nbsp;&nbsp;&nbsp;&nbsp;';

							/*	DATOS DEL VENDEDOR	*/
							$idUsuario		=	$dataAbono['idUser'];
							$consultUsuario	=	mysqli_query($MySQLi,"SELECT Nombres, Apellidos FROM Usuarios WHERE idUser='$idUsuario' ");
							$datosUsuario	=	mysqli_fetch_assoc($consultUsuario);
							$Vendedor 		=	' &nbsp;&nbsp;&nbsp;&nbsp;'.$datosUsuario['Nombres']." ".$datosUsuario['Apellidos'].' &nbsp;&nbsp;&nbsp;&nbsp;';
						?>
            <td><?php echo utf8_decode($NameCliente) ?></td>
            <td>
                <?php
								$queryIDCot	=	mysqli_query($MySQLi,"SELECT CodeCotizacion FROM Abonos WHERE idCotizacion=".$dataAbono['idCotizacion']." ");
								$dataClave	=	mysqli_fetch_assoc($queryIDCot);
								$CodeCot 	=	$dataClave['CodeCotizacion'];

								$queryClave = 	mysqli_query($MySQLi,"SELECT * FROM Cotizaciones WHERE Code='$CodeCot' ");
								$dataClaveCo=	mysqli_fetch_assoc($queryClave);
								$ClaveCot 	=	$dataClaveCo['Clave'];


								$queryClaveT=	mysqli_query($MySQLi,"SELECT * FROM ClaveTemporal WHERE Clave='$ClaveCot'	 ");
								while ($dataPro = mysqli_fetch_assoc($queryClaveT)) {
									$idProductoCot=	$dataPro['idProducto'];
									$CantidadPCot = $dataPro['Cantidad'];
									$PrecioListCo = $dataPro['PrecioLista'];
									$PrecioOfeCot = $dataPro['PrecioOferta'];
									$queryFindPro =	mysqli_query($MySQLi,"SELECT * FROM Productos WHERE idProducto=".$idProductoCot." ");
									$dataDetalle 	=	mysqli_fetch_assoc($queryFindPro);
									echo $CantidadPCot." - ".utf8_decode($dataDetalle['Producto']." / ".$dataDetalle['Marca']." / ".$dataDetalle['Modelo']."<br>");
								}
							?>
            </td>
            <td>
                <?php
								if ($datosUsuario['NIT']=='') {
									echo " &nbsp;&nbsp;&nbsp;&nbsp;No proporcion&oacute; &nbsp;&nbsp;&nbsp;&nbsp;";
								}else{
									echo ' &nbsp;&nbsp;&nbsp;&nbsp;'.$datosUsuario['NIT'].' &nbsp;&nbsp;&nbsp;&nbsp;';
								}
							?>
            </td>
            <td><?php echo $TelCliente ?></td>
            <?php
							/*	MONEDA	*/
							if ($dataAbono['Moneda']=='USD') {
								echo '<td style="background-color: #DEA4BF;text-align:center">'.$dataAbono['Moneda'].'</td>';
							}else{
								echo '<td style="background-color: #72B0F5;text-align:center">'.$dataAbono['Moneda'].'</td>';
							}
						?>
            <td style="text-align: right;">
                <?php
								/*	PRECIO DOLAR	*/
								if ($dataAbono['Moneda']=='USD') {
									echo "";
								}else{
									// echo $dataAbono['PrecioDolar'];
									echo number_format(($dataAbono['PrecioDolar']), 2 );
								}
							?>
            </td>
            <td> <?php
							if ($dataAbono['TotalUSD']=='0') {
								echo "";
							}else{
								// echo $dataAbono['TotalUSD'];
								echo number_format(($dataAbono['TotalUSD']), 2 );
							} ?>
            </td>
            <td> <?php
							if ($dataAbono['Total']=='0') {
								echo "";
							}else{
						 		// echo $dataAbono['Total'];
								echo number_format(($dataAbono['Total']), 2 );
							}?>
            </td>
            <td>
                <?php
								if ($dataAbono['Moneda']=='USD') {
									$AbonoenUSD		=	$dataAbono['anticipoUSD'];
									// $PrecioVentaUSD	=	$dataAbono['PrecioVentaUSD'];
									// $Cantidad 		=	$dataAbono['Cantidad'];
									// $PagoenUSD 		=	$PrecioVentaUSD*$Cantidad;
									// echo $AbonoenUSD;
									echo number_format(($AbonoenUSD), 2 );
								}else{
									echo "";
								}
							?>
            </td>
            <td>
                <?php
								if ($dataAbono['Moneda']=='Bs') {
									$AbonoenBs 		=	$dataAbono['porAnticipo'];
									// $PrecioVentaBs	=	$dataAbono['PrecioVentaBs'];
									// $Cantidad 		=	$dataAbono['Cantidad'];
									// $PagoenBs 		=	$PrecioVentaBs*$Cantidad;
									// echo $AbonoenBs;
									echo number_format(($AbonoenBs), 2 );
								}else{
									echo "";
								}
							?>
            </td>
            <td> &nbsp;&nbsp;&nbsp;&nbsp;<?php echo utf8_decode($Vendedor) ?> &nbsp;&nbsp;&nbsp;&nbsp;</td>
            <td> &nbsp;&nbsp;&nbsp;&nbsp;<?php echo $dataAbono['Sucursal'] ?> &nbsp;&nbsp;&nbsp;&nbsp;</td>
        </tr>
        <?php $Number++; } mysqli_close($MySQLi); ?>
    </tbody>
</table><?php
		}else{
			header("Content-type: application/vnd.ms-excel; name='excel'");
			header("Content-Disposition: filename=Reporte_abonos(Anticipo).xls");
			header("Pragma: no-cache");
			header("Pragma: no-cache");
			header("Expires: 0"); ?>
<table border="1">
    <thead>
        <tr>
            <th colspan="15" style="text-align: center;">
                <h3>REPORTE DE ABONOS (Ventas al <?php echo utf8_decode("Crédito")?>)</h3>
            </th>
        </tr>
        <tr>
            <th style="text-align: center;color:#fff;background-color: #97D086">N&ordm;</th>
            <th style="text-align: center;color:#fff;background-color: #97D086">FECHA</th>
            <th style="text-align: center;color:#fff;background-color: #97D086">RECIBO</th>
            <th style="text-align: center;color:#fff;background-color: #97D086">CODIGO DE LA<br>COTIZACION</th>
            <th style="text-align: center;color:#fff;background-color: #97D086">CLIENTE</th>
            <!-- <th style="text-align: center;color:#fff;background-color: #97D086">PRODUCTO(s)</th> -->
            <th style="text-align: center;color:#fff;background-color: #97D086">NIT</th>
            <th style="text-align: center;color:#fff;background-color: #97D086">TELEFONO</th>
            <th style="text-align: center;color:#fff;background-color: #97D086">MONEDA</th>
            <th style="text-align: center;color:#fff;background-color: #97D086">PRECIO<br>DOLAR</th>
            <th style="text-align: center;color:#fff;background-color: #97D086">TOTAL<br>EN USD</th>
            <th style="text-align: center;color:#fff;background-color: #97D086">TOTAL<br>EN BS</th>
            <th style="text-align: center;color:#fff;background-color: #97D086">ABONO<br>EN<br>USD</th>
            <th style="text-align: center;color:#fff;background-color: #97D086">ABONO<br>EN<br>Bs</th>
            <th style="text-align: center;color:#fff;background-color: #97D086">VENDEDOR</th>
            <th style="text-align: center;color:#fff;background-color: #97D086">SUCURSAL</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td colspan="15" style="letter-spacing: 1px;text-align: center;color: red">
                <h2>NO HAY RESULTADOS QUE MOSTRAR</h2>
            </td>
        </tr>
    </tbody>
</table><?php
		}
	}elseif (isset($_GET['NotaE_idCotiza'])) {
		$idCotizacion 	=	$_GET['NotaE_idCotiza'];
		//$idCotizacion 	=	$_GET['notaEntrega'];
		$consultNotaE	=	mysqli_query($MySQLi,"SELECT idNotaE, idUser, idCliente, idCotizacion, DATE_FORMAT(Fecha, '%d de %M de %Y') AS Fecha, Sucursal, Observaciones FROM NotaEntrega WHERE idCotizacion='$idCotizacion' ")or die(mysqli_error($MySQLi));
		$dataNotaE 		=	mysqli_fetch_assoc($consultNotaE);
		$idNotaEntrega	=	$dataNotaE['idNotaE'];
		$idUser 		=	$dataNotaE['idUser'];
		$idCliente 		=	$dataNotaE['idCliente'];
		//$idCotizacion 	=	$dataNotaE['idCotizacion'];
		$Sucursal 		=	$dataNotaE['Sucursal'];
		$Observaciones 	=	$dataNotaE['Observaciones'];
		$FechaNotaEntre =	$dataNotaE['Fecha'];

		/*	CONSULTAMOS LOS DATOS DEL CLIENTE 	*/
		$consultCliente	=	mysqli_query($MySQLi,"SELECT Nombres, Apellidos, Empresa, Direccion, Celular, Otro FROM Clientes WHERE idCliente='$idCliente' ")or die(mysqli_error($MySQLi));
		$dataCliente 	=	mysqli_fetch_assoc($consultCliente);
		$NombreCliente 	=	$dataCliente['Nombres']." ".$dataCliente['Apellidos'];
		$DireccionClient=	$dataCliente['Direccion'];
		$CellCliente	=	$dataCliente['Celular'];
		$FijoCliente 	=	$dataCliente['Otro'];
		$EmpresaCliente= $dataCliente['Empresa'];

		// VALIDACION PARA FERIAS 2025
		$Sucursal = ($Sucursal != 'Ferias') ? $Sucursal : '<br/>';

		if ($idNotaEntrega 	<	10) {
			$ReciboNum='<span style="letter-spacing: 1px">000000'.$idNotaEntrega.'</span>';
		}elseif ($idNotaEntrega<	100) {
			$ReciboNum='<span style="letter-spacing: 1px">00000'.$idNotaEntrega.'</span>';
		}elseif ($idNotaEntrega< 1000) {
			$ReciboNum='<span style="letter-spacing: 1px">0000'.$idNotaEntrega.'</span>';
		}elseif ($idNotaEntrega< 10000) {
			$ReciboNum='<span style="letter-spacing: 1px">000'.$idNotaEntrega.'</span>';
		}elseif ($idNotaEntrega< 100000) {
			$ReciboNum='<span style="letter-spacing: 1px">00'.$idNotaEntrega.'</span>';
		}elseif ($idNotaEntrega< 1000000) {
			$ReciboNum='<span style="letter-spacing: 1px">0'.$idNotaEntrega.'</span>';
		}elseif ($idNotaEntrega< 10000000) {
			$ReciboNum='<span style="letter-spacing: 1px">'.$idNotaEntrega.'</span>';
		}
		
		$mpdf 	=	new \Mpdf\Mpdf([
			'mode'			=>	'utf-8',
			'format' 		=> [280, 216],
			'orientation'	=>	'L',
			'margin_header'	=>	0,
			'margin_footer'	=>	0,
			'margin_left'	=>	0,
			'margin_top'	=>	30,
			'margin_right'	=>	0,
			'margin_bottom'	=>	45,

		]);
		$CSS 	=	file_get_contents('css/NotaEntrega.css');

		$mpdf->SetHTMLHeader('<img src="../assets/img/HEADER.png">');
		$mpdf->SetHTMLFooter('<img src="../assets/img/FOOTER.png">');

		$html 	=	'
		<body>
		 	<header>
	 			<table class="header">
	 				<tr>		 				
	 					<td width="75%" class="text-center"><h1>NOTA DE ENTREGA - GARANTÍA</h1></td>	 					
	 					<td width="25%" class="text-right">
			 				<table class="top-right">
			 					<tr>
			 						<td class="ciudad">CIUDAD</td>
			 					</tr>
			 					<tr>
			 						<td class="nameCiudad fs-14">'.$Sucursal.'</td>
			 					</tr>
			 					<tr>
			 						<td class="ciudad">FECHA</td>
			 					</tr>
			 					<tr>
			 						<td class="nameCiudad">'.$FechaNotaEntre.'</td>
			 					</tr>
			 				</table>
		 				</td>
	 				</tr>	 				
	 			</table>
		 		<table class="header">
	 				<tr>
	 					<td width="100%" class="text-right"><h1 class=numNota>'.$ReciboNum.'</h1></td>
 					</tr>
	 			</table>
				<table class="name">
					<tbody>';
						if ($EmpresaCliente=='') { $html.='
							<tr>
								<td>Nombre: </td>
								<td colspan="3">'.$NombreCliente.'</td>
							</tr>
							<tr>
								<td width="15%">Dirección: </td>
								<td width="60%">'.$DireccionClient.'</td>
								<td width="10%">Teléfono: </td>
								<td width="15%">'.$CellCliente .'</td>
							</tr>';
						}else{ $html.='
							<tr>
								<td>Nombre: </td>
								<td>'.$NombreCliente.'</td>
								<td>Empresa: </td>
								<td>'.$EmpresaCliente.'</td>
							</tr>
							<tr>
								<td>Dirección: </td>
								<td>'.$DireccionClient.'</td>
								<td>Teléfono: </td>
								<td>'.$CellCliente .'</td>
							</tr>';
						} $html.='
					</tbody>
				</table>
				<table class="contenido">
					<tr class="">
						<th class="" width="13%">CANTIDAD</th>
						<th class="" width="61%">DETALLE</th>
						<th class="" width="13%">MARCA</th>
						<th class="" width="13%">MODELO</th>
					</tr>';
					/*	CONSULTAMOS LOS PRODUCTOS DE LA COTIZACION	*/
					$queryCotiza 	=	mysqli_query($MySQLi,"SELECT Clave FROM Cotizaciones WHERE idCotizacion='$idCotizacion' ")or die(mysqli_error($MySQLi));
					$dataCotiza 	=	mysqli_fetch_assoc($queryCotiza);
					$ClaveCotizacion=	$dataCotiza['Clave'] ;

					$queryProductos =	mysqli_query($MySQLi,"SELECT idProducto, Cantidad FROM ClaveTemporal WHERE Clave='$ClaveCotizacion' ")or die(mysqli_error($MySQLi));
					while ($dataProductos = mysqli_fetch_assoc($queryProductos)) {
						$idProducto =	$dataProductos['idProducto'];
						$CantProduct=	$dataProductos['Cantidad'];
						$html.='
						<tr>
							<td class="listado">'.$CantProduct.'</td>';
							$sqlProduct =	mysqli_query($MySQLi,"SELECT * FROM Productos WHERE idProducto='$idProducto' ")or die(mysqli_error($MySQLi));
							$dataProduct=	mysqli_fetch_assoc($sqlProduct);
							$Producto 	=	$dataProduct['Producto'];
							$Marca 		=	$dataProduct['Marca'];
							$Modelo 	=	$dataProduct['Modelo'];

							$html.='
							<td class="listado">'.$Producto.'</td>
							<td class="listado">'.$Marca .'</td>
							<td class="listado">'.$Modelo.'</td>
						</tr>';
					} $html.='			
					<tr>
						<td colspan="4" class="listado observaciones">OBSERVACIONES: '.$Observaciones.'</td>
					</tr>
				</table>
				<div class="footer">
					<strong>GARANTÍA</strong>: 06 (SEIS) MESES CONTRA DEFECTOS DE FABRICACIÓN Y NO ASÍTEMAS ELÉCTRICOS (MOTOR, SENSORES, VÁLVULAS, TERMOSTATOS, ETC) NI POR MAL USO O MALA MANIPULACIÓN DE LOS EQUIPOS.
				</div>
				<div class="footer">
					<strong>IMPORTANTE</strong>: NO SE ACEPTAN CAMBIOS NI DEVOLUCIONES DE MERCADERÍA O DINERO
				</div>
				<table class=firmas>
					<tr>
						<td width="50%" class="text-center">________________________________________</td>
						<td width="50%" class="text-center">________________________________________</td>
					</tr>
					<tr>
						<td width="50%" class="text-center">FIRMA CLIENTE</td>
						<td width="50%" class="text-center">VENDEDOR(A)</td>
					</tr>
				</table>
		 	</header>
		 </body> ';
		$NamePDF=	"Nota de entrega No. ".$idNotaEntrega;
		$mpdf->WriteHTML($CSS, \Mpdf\HTMLParserMode::HEADER_CSS);
		$mpdf->WriteHTML($html, \Mpdf\HTMLParserMode::HTML_BODY);
		$mpdf->Output($NamePDF.".pdf", "I");
	}elseif (isset($_GET['ReporteEnvioStock'])) {
		$idEnvioStock = $_GET['ReporteEnvioStock'];
		if ($idEnvioStock 	<	10) {
			$ReciboNum='<span style="letter-spacing: 1px">000000'.$idEnvioStock.'</span>';
		}elseif ($idEnvioStock<	100) {
			$ReciboNum='<span style="letter-spacing: 1px">00000'.$idEnvioStock.'</span>';
		}elseif ($idEnvioStock< 1000) {
			$ReciboNum='<span style="letter-spacing: 1px">0000'.$idEnvioStock.'</span>';
		}elseif ($idEnvioStock< 10000) {
			$ReciboNum='<span style="letter-spacing: 1px">000'.$idEnvioStock.'</span>';
		}elseif ($idEnvioStock< 100000) {
			$ReciboNum='<span style="letter-spacing: 1px">00'.$idEnvioStock.'</span>';
		}elseif ($idEnvioStock< 1000000) {
			$ReciboNum='<span style="letter-spacing: 1px">0'.$idEnvioStock.'</span>';
		}elseif ($idEnvioStock< 10000000) {
			$ReciboNum='<span style="letter-spacing: 1px">'.$idEnvioStock.'</span>';
		}
		$mpdf 	=	new \Mpdf\Mpdf([
			'mode'			=>	'utf-8',
			'format' 		=> [280, 216],
			'orientation'	=>	'L',
			'margin_header'	=>	0,
			'margin_footer'	=>	0,
			'margin_left'	=>	0,
			'margin_top'	=>	27,
			'margin_right'	=>	0,
			'margin_bottom'	=>	45,
		]);
		$CSS 	=	file_get_contents('css/envioStock.css');
		$mpdf->SetHTMLHeader('<img src="../assets/img/HEADER.png">');
		$sqlEnvio 			= mysqli_query($MySQLi,"SELECT * FROM envioStock WHERE idEnvio='$idEnvioStock' ")or die(mysqli_error($MySQLi)."<br>Error en la línea: ".__LINE__);
		$dataEnvio 			= mysqli_fetch_assoc($sqlEnvio);
		$claveEnvio 		= $dataEnvio['clave'];
		$origen 				= $dataEnvio['desde'];
		$hasta 					= $dataEnvio['hasta'];
		$idUser 				= $dataEnvio['idUser'];
		$Observaciones 	= $dataEnvio['observaciones'];
		$FechaEnvio 		= $dataEnvio['fecha'];
		$estado 				= $dataEnvio['estado'];
		$fechaEnvio 		= date("d-m-Y",strtotime($FechaEnvio));
		$HoraEnvio 			= $dataEnvio['hora'];
		$sqlVendedor 		= mysqli_query($MySQLi,"SELECT Nombres,Apellidos FROM Usuarios WHERE idUser='$idUser' ")or die(mysqli_error($MySQLi)."<br>Error en la línea: ".__LINE__);
		$dataVendedor 	= mysqli_fetch_assoc($sqlVendedor);
		$Vendedor 			= $dataVendedor['Nombres']." ".$dataVendedor['Apellidos'];
		if ($estado == 0) {
			$status 	= "<span style='color:blue'>En proceso</span>";
		}elseif ($estado==1) {
			$status 	= "<span style='color:green'>Recibido</span>";
		}else{
			$status 	= "<span style='color:red'>Cancelado</span>";
		}

		$subtable='
		<br>
		<br>
		<table width="90%" style="margin:auto">
			<tr>
				<td width="65%" style="font-size:35px;text-align:center">MOVIMIENTO DE MERCADERIA
				</td>
				<td width="35%">
	 				<table width="100%" border=1>
	 					<tr>
	 						<td style="text-align:center">CIUDAD</td>
	 					</tr>
	 					<tr>
	 						<td style="text-align:center">'.$origen .'</td>
	 					</tr>
	 					<tr>
	 						<td style="text-align:center">FECHA</td>
	 					</tr>
	 					<tr>
	 						<td style="text-align:center">'.$fechaEnvio." &nbsp; ".$HoraEnvio .'</td>
	 					</tr>
	 				</table>
				</td>
			</tr>
			<tr>
				<td colspan="2" style="text-align:right;font-size:30px;color:red">'.$ReciboNum.'</td>
			</tr>
	
			<tr>
				<td>Origen: &nbsp; '.$origen.'</td>
			</tr>
			<tr>
				<td>Destino: &nbsp; '.$hasta.'</td>
			</tr>
			<tr>
				<td style="text-align:left">Estado: &nbsp; '.$status.'</td>
			</tr>
		</table>
		
		
		
		
		';


		$html = '
		
		<table class="tablaProductos" autosize="1" border="1" style="page-break-inside:avoid;">

		<tr>
			<td colspan="4" >'.$subtable.'</td>    
		</tr>
			<thead>
				<tr>
					<td style="width:10%;text-align:center;background-color:green;color:#fff">Cantidad</td>
					<td style="width:50%;text-align:center;background-color:green;color:#fff">DETALLE</td>
					<td style="width:20%;text-align:center;background-color:green;color:#fff">MARCA</td>
					<td style="width:20%;text-align:center;background-color:green;color:#fff">MODELO</td>
				</tr>
			</thead>
			<tbody>';
				$sqlClaveEnvios = mysqli_query($MySQLi,"SELECT * FROM clavesEnvioStock WHERE clave='$claveEnvio' ")or die(mysqli_error($MySQLi)."<br>Error en la línea: ".__LINE__);
				$total_cantidad=0;
				while ($dataClave = mysqli_fetch_assoc($sqlClaveEnvios)) { $html.='
				<tr>
					<td style="text-align:center;padding:8px">'.$dataClave['cantidad'] .'</td>';
					$total_cantidad+=(int)$dataClave['cantidad'];
					$idProducto = $dataClave['idProducto'];
					$sqlProducto = mysqli_query($MySQLi,"SELECT Producto,Marca,Modelo FROM Productos WHERE idProducto='$idProducto' ")or die(mysqli_error($MySQLi)."<br>Error en la línea: ".__LINE__);
					$dataProducto = mysqli_fetch_assoc($sqlProducto);
					$ProductoNombre = $dataProducto['Producto'];
					$MarcaProducto  = $dataProducto['Marca'];
					$ModeloProducto = $dataProducto['Modelo']; $html.='
					<td style="padding-left:8px">'.$ProductoNombre .'</td>
					<td style="padding-left:8px">'.$MarcaProducto .'</td>
					<td style="padding-left:8px">'.$ModeloProducto .'</td>
				</tr>';
			} $html.='				
				<tr>
					<td style="padding:8px"> &nbsp; </td>
					<td style="padding:8px"> &nbsp; </td>
					<td style="padding:8px"> &nbsp; </td>
					<td style="padding:8px"> &nbsp; </td>
				</tr>
				<tr>
					<td style="text-align:center;padding:8px"> '.$total_cantidad .' </td>
					<td style="padding-left:8px"> TOTAL </td>
					<td style="text-align:center;padding:8px"> &nbsp; </td>
					<td style="padding:8px"> &nbsp; </td>
				</tr>
			</tbody>
		</table><br>
		<table style="width: 100%;margin: 0px 20px 10px 20px;font-size:14px ">
			<tr>
				<td><span style="font-size:10px">Observaciones: &nbsp; '.$Observaciones.'</span></td>
			</tr>
		</table><br><br>
		<table style="width: 90%;margin: 0px 20px 10px 20px;font-size:14px ">
			<tr>
				<td width="50%" style="text-align:center">______________________________</td>
				<td width="50%" style="text-align:center">______________________________</td>
			</tr>
			<tr>
				<td style="text-align:center"> &nbsp;&nbsp; Firma Técnico origen</td>
				<td style="text-align:center"> &nbsp;&nbsp; Firma Técnico recibido</td>
			</tr>
			<tr>
				<td style="text-align:center"> &nbsp;&nbsp;&nbsp;&nbsp; '.$Vendedor.'</td>
				<td style="text-align:center"> &nbsp;&nbsp;&nbsp;&nbsp; </td>
			</tr>
		</table>';
		//$mpdf->SetHTMLFooter('<img src="assets/img/FOOTER.png">');
		$NamePDF=	"Reporte envio stock  ".$idEnvioStock;
		$mpdf->WriteHTML($CSS, \Mpdf\HTMLParserMode::HEADER_CSS);
		$mpdf->WriteHTML("$html", \Mpdf\HTMLParserMode::HTML_BODY);
		$mpdf->Output($NamePDF.".pdf", "I");
	}
	//excel reportes productos fiscales con fecha inicio y fin CON RANGO DE FECHAS
	elseif (isset($_GET['historial_productos_fiscales_completo_con_fechas'])AND isset($_GET['fechaInicio'])AND isset($_GET['fechafin'])) {
		$Sucursal 	=	$_GET['historial_productos_fiscales_completo_con_fechas'];
		$INICIO 		=	$_GET['fechaInicio'];
		$FIN 		=	$_GET['fechafin'];

		header("Content-type: application/vnd.ms-excel; name='excel'");
		header("Content-Disposition: filename=ListadoGeneralRangoFechas".$INICIO."__".$FIN.".xls");
		header("Pragma: no-cache");
		header("Pragma: no-cache");
		header("Expires: 0");

		 ?>

<table border="1">
    <thead>
        <tr>
            <th colspan="14" style="text-align: center;">
                <h3>HISTORIAL PRODUCTOS FISCALES DESDE EL <span style="color: green"><?php echo $INICIO ?></span> HASTA
                    EL <span style="color: red"><?php echo $FIN ?></span></h3>
            </th>
        </tr>
        <tr>
            <th width="5%" class="text-center">N&ordm;</th>
            <th width="5%" class="text-center">idProducto</th>
            <th class="text-center">PRODUCTO FISCAL <br>DETALLE</th>
            <th width="5%" class="text-center">INICIAL</th>
            <th width="5%" class="text-center btn-warning"><span style="color: orange">CB</span></th>
            <th width="5%" class="text-center btn-primary"><span style="color: blue">LP</span></th>
            <th width="5%" class="text-center btn-success"><span style="color: green">SC</span></th>
            <th width="5%" class="text-center btn-danger"><span style="color: red">ST</span></th>
            <th width="5%" class="text-center btn-info"><span style="color: #40CFFF">TJ</span></th>
            <th width="5%" class="text-center">FINAL</th>
            <th class="text-center">VENDEDOR</th>
            <th class="text-center">FECHA</th>
            <th class="text-center">#FACTURA</th>
            <th colspan="2" class="text-center">DESCRIPCION</th>
        </tr>
    </thead>
    <tbody>
        <?php		
		$query="SELECT * FROM productos_fiscales ORDER BY idProducto ASC";
		$queryProductos	=	mysqli_query($MySQLi,$query);
		$Num=1;
		$GranTotalInicial=0;
		$GranTotalFinal=0;
		while ($dataProducto = mysqli_fetch_assoc($queryProductos)) {
						
					$idProducto = $dataProducto['idProducto'];
					//inicial final cb,lp,sc,tj
					$queryHistorialProductos =	mysqli_query($MySQLi,
					"SELECT
					MAX(inicial) AS inicial,
    				MIN(final) AS final,
					SUM(cb) AS cb,
					SUM(lp) AS lp,
					SUM(sc) AS sc,
					SUM(st) AS st,
					SUM(tj) AS tj
					FROM
						historial_stock_productos_fiscales
					WHERE
						idProducto = '$idProducto' AND(
					DATE_FORMAT(dateEmission, '%Y-%m-%d') BETWEEN '$INICIO' AND '$FIN')");

					$dataHistorialProductos	=	mysqli_fetch_assoc($queryHistorialProductos);

					//query vendedor,dateEmission,invoiceNumber,descripcion
					$queryVendedores =	mysqli_query($MySQLi,
					"SELECT
					vendedor,dateEmission,invoiceNumber,descripcion
					FROM
					historial_stock_productos_fiscales
					WHERE
						idProducto = '$idProducto' AND(
					DATE_FORMAT(dateEmission, '%Y-%m-%d') BETWEEN '$INICIO' AND '$FIN')");

					$vendedorArray=[];
					$dateEmissionArray=[];
					$invoiceNumberArray=[];
					$descripcionArray=[];

					while($dataVendedores	=	mysqli_fetch_assoc($queryVendedores)){
						$vendedorArray[]= $dataVendedores['vendedor'];
						$dateEmissionArray[]= $dataVendedores['dateEmission'];
						$invoiceNumberArray[]= $dataVendedores['invoiceNumber'];
						$descripcionArray[]= $dataVendedores['descripcion'];
					}
		
					?>
        <tr>
            <td class="text-center"><?php echo $Num ?></td>
            <td class="text-center"><?php echo $dataProducto['idProducto']; ?></td>
            <td class="text-center"><?php echo utf8_decode($dataProducto['detalle']); ?></td>
            <td class="text-center">
                <?php 
							//inicial producto su stock mas antiguo con rango de fechas
							$queryStockMasAntiguo =	mysqli_query($MySQLi,
							"SELECT
							inicial
							FROM
							historial_stock_productos_fiscales
							WHERE
							idProducto = '$idProducto' AND dateEmission =(
							SELECT
								MIN(dateEmission)
							FROM
								historial_stock_productos_fiscales
							WHERE
								idProducto = '$idProducto' 
								AND(DATE_FORMAT(dateEmission, '%Y-%m-%d') BETWEEN '$INICIO' AND '$FIN'))");

							$dataStockMasAntiguo	=	mysqli_fetch_assoc($queryStockMasAntiguo);
							
							if($dataStockMasAntiguo['inicial']==null){//no entro a historial en ese rango
								//1er caso tomamos el primer inicio de su derecha 
								$queryStockMasAntiguo =	mysqli_query($MySQLi,
								"SELECT
								inicial
								FROM
								historial_stock_productos_fiscales
								WHERE
								idProducto = '$idProducto' AND dateEmission =(
								SELECT
									MIN(dateEmission)
								FROM
									historial_stock_productos_fiscales
								WHERE
									idProducto = '$idProducto' 
									AND(DATE_FORMAT(dateEmission, '%Y-%m-%d') BETWEEN '$INICIO' AND '2040-01-01'))");
	
								$dataStockMasAntiguo	=	mysqli_fetch_assoc($queryStockMasAntiguo);
							}
							if($dataStockMasAntiguo['inicial']==null){
								//2DO CASO sigue sin existir tomamos de su izquierda el max final mas cercano que sera nuestro inicial
								$queryStockMasAntiguo =	mysqli_query($MySQLi,
								"SELECT
								final as 'inicial'
								FROM
								historial_stock_productos_fiscales
								WHERE
								idProducto = '$idProducto' AND dateEmission =(
								SELECT
									MAX(dateEmission)
								FROM
									historial_stock_productos_fiscales
								WHERE
									idProducto = '$idProducto' 
									AND(DATE_FORMAT(dateEmission, '%Y-%m-%d') BETWEEN  '2000-01-01' AND '$INICIO'))");
	
								$dataStockMasAntiguo	=	mysqli_fetch_assoc($queryStockMasAntiguo);
								}
							if($dataStockMasAntiguo['inicial']==null){
								//3er caso SIGUE SIN EXISTIR nunca de los nunca entro al historial TOMAMOS EL STOCK NOMAS
								$GranTotalInicial=$GranTotalInicial+$dataProducto['saldo_fisico'];//tomamos su stock caso final
								echo $dataProducto['saldo_fisico'];
							}
								$GranTotalInicial=$GranTotalInicial+$dataStockMasAntiguo['inicial'];//si entro a historial
								echo $dataStockMasAntiguo['inicial']; //tomamos su valor mas antiguo
						?>
            </td>
            <td class="text-center">
                <?php 
							if($dataHistorialProductos['cb']==null){
								echo '0';
							}else{
							echo $dataHistorialProductos['cb'];
							}
							 ?>
            </td>
            <td class="text-center">
                <?php 
							if($dataHistorialProductos['lp']==null){
								echo '0';
							}else{
							echo $dataHistorialProductos['lp'];
							}
							 ?>
            </td>
            <td class="text-center">
                <?php 
							if($dataHistorialProductos['sc']==null){
								echo '0';
							}else{
							echo $dataHistorialProductos['sc'];
							}
							 ?>
            </td>
			<td class="text-center">
                <?php 
							if($dataHistorialProductos['st']==null){
								echo '0';
							}else{
							echo $dataHistorialProductos['st'];
							}
							 ?>
            </td>
            <td class="text-center">
                <?php 
							if($dataHistorialProductos['tj']==null){
								echo '0';
							}else{
							echo $dataHistorialProductos['tj'];
							}
							 ?>
            </td>
            <td class="text-center">
                <?php 
							//final producto CON RANGO DE FECHAS
							$queryStockActualFechaFin =	mysqli_query($MySQLi,
							"SELECT
							final
							FROM
							historial_stock_productos_fiscales
							WHERE
							idProducto = '$idProducto' AND dateEmission =(
							SELECT
								MAX(dateEmission)
							FROM
								historial_stock_productos_fiscales
							WHERE
								idProducto = '$idProducto' 
								AND(DATE_FORMAT(dateEmission, '%Y-%m-%d') BETWEEN '$INICIO' AND '$FIN'))");

							$dataStockFechaFin	=	mysqli_fetch_assoc($queryStockActualFechaFin);

							if($dataStockFechaFin['final']==null){
								//1er caso tomamos el primer final de su izqauierda 
								$queryStockActualFechaFin =	mysqli_query($MySQLi,
								"SELECT
								final
								FROM
								historial_stock_productos_fiscales
								WHERE
								idProducto = '$idProducto' AND dateEmission =(
								SELECT
									MAX(dateEmission)
								FROM
									historial_stock_productos_fiscales
								WHERE
									idProducto = '$idProducto' 
									AND(DATE_FORMAT(dateEmission, '%Y-%m-%d') BETWEEN '2000-01-01' AND '$FIN'))");
	
								$dataStockFechaFin	=	mysqli_fetch_assoc($queryStockActualFechaFin);
	
							}
							if($dataStockFechaFin['final']==null){
								//2do caso de la derecha
								$queryStockActualFechaFin =	mysqli_query($MySQLi,
								"SELECT
								inicial as 'final'
								FROM
								historial_stock_productos_fiscales
								WHERE
								idProducto = '$idProducto' AND dateEmission =(
								SELECT
									MIN(dateEmission)
								FROM
									historial_stock_productos_fiscales
								WHERE
									idProducto = '$idProducto' 
									AND(DATE_FORMAT(dateEmission, '%Y-%m-%d') BETWEEN '$FIN' AND '2040-01-01' ))");
	
								$dataStockFechaFin	=	mysqli_fetch_assoc($queryStockActualFechaFin);

							}
							if($dataStockFechaFin['final']==null){
								//3er caso nunca entro al historial tonces mostramos stock nomas
								$GranTotalFinal=$GranTotalFinal+$dataProducto['saldo_fisico'];
								echo $dataProducto['saldo_fisico'];
							}

					
								$GranTotalFinal=$GranTotalFinal+$dataStockFechaFin['final'];
								echo $dataStockFechaFin['final']; 
							?>
            </td>
            <td class="text-center">
                <?php
							if($vendedorArray==null){echo '';}
							else {
								foreach ($vendedorArray as $value) {
									echo $value . '<br>';
								}
							}								
							 ?>
            </td>
            <td class="text-center">
                <?php
							if($dateEmissionArray==null){echo '';}
							else {
								foreach ($dateEmissionArray as $value) {
									echo $value . '<br>';
								}
							}								
							?>
            </td>
            <td class="text-center">
                <?php
							if($invoiceNumberArray==null){echo '';}
							else {
								foreach ($invoiceNumberArray as $value) {
									echo $value . '<br>';
								}
							}								
							?>
            </td>
            <td colspan="2" class="text-center">
                <?php
							if($descripcionArray==null){echo '';}
							else {
								foreach ($descripcionArray as $value) {
									echo $value . '<br>';
								}
							}
							?>
            </td>
        </tr>
        <?php $Num++;
			} ?>
        <tr class="odd gradeX">
            <td class="text-center"><?php echo $Num ?></td>

            <th colspan="2" class="text-center">TOTAL</th>

            <td class="text-center">
                <strong>
                    <?php  
						//GranTotal inicial		
                        // $queryTotalIncial	=	mysqli_query($MySQLi,"SELECT SUM(inicial) AS inicial FROM historial_stock_productos_fiscales WHERE (DATE_FORMAT(dateEmission, '%Y-%m-%d') BETWEEN '$INICIO' AND '$FIN') ")or die(mysqli_error($MySQLi));
                        // $dataVentas		=	mysqli_fetch_assoc($queryTotalIncial);
                        // $TotalInicial 	=	$dataVentas['inicial'];
                        echo $GranTotalInicial;
                                ?>
                </strong>
            </td>
            <td class="text-center">
                <strong><span style="color: orange">
                        <?php  
                        $queryTotalIncial	=	mysqli_query($MySQLi,"SELECT SUM(cb) AS cb FROM historial_stock_productos_fiscales WHERE (DATE_FORMAT(dateEmission, '%Y-%m-%d') BETWEEN '$INICIO' AND '$FIN') ")or die(mysqli_error($MySQLi));
                        $dataVentas		=	mysqli_fetch_assoc($queryTotalIncial);
                        $TotalInicial 	=	$dataVentas['cb'];
                        echo $TotalInicial;
                                ?>
                    </span></strong>
            </td>
            <td class="text-center">
                <strong><span style="color: blue">
                        <?php  
                        $queryTotalIncial	=	mysqli_query($MySQLi,"SELECT SUM(lp) AS lp FROM historial_stock_productos_fiscales WHERE (DATE_FORMAT(dateEmission, '%Y-%m-%d') BETWEEN '$INICIO' AND '$FIN') ")or die(mysqli_error($MySQLi));
                        $dataVentas		=	mysqli_fetch_assoc($queryTotalIncial);
                        $TotalInicial 	=	$dataVentas['lp'];
                        echo $TotalInicial;
                                ?>
                    </span></strong>
            </td>
            <td class="text-center">
                <strong><span style="color: green">
                        <?php  
                        $queryTotalIncial	=	mysqli_query($MySQLi,"SELECT SUM(sc) AS sc FROM historial_stock_productos_fiscales WHERE (DATE_FORMAT(dateEmission, '%Y-%m-%d') BETWEEN '$INICIO' AND '$FIN') ")or die(mysqli_error($MySQLi));
                        $dataVentas		=	mysqli_fetch_assoc($queryTotalIncial);
                        $TotalInicial 	=	$dataVentas['sc'];
                        echo $TotalInicial;
                                ?>
                    </span></strong>
            </td>
			<td class="text-center">
                <strong><span style="color: red">
                        <?php  
                        $queryTotalIncial	=	mysqli_query($MySQLi,"SELECT SUM(st) AS st FROM historial_stock_productos_fiscales WHERE (DATE_FORMAT(dateEmission, '%Y-%m-%d') BETWEEN '$INICIO' AND '$FIN') ")or die(mysqli_error($MySQLi));
                        $dataVentas		=	mysqli_fetch_assoc($queryTotalIncial);
                        $TotalInicial 	=	$dataVentas['st'];
                        echo $TotalInicial;
                                ?>
                    </span></strong>
            </td>
            <td class="text-center">
                <strong><span style="color: #40CFFF">
                        <?php  
                        $queryTotalIncial	=	mysqli_query($MySQLi,"SELECT SUM(tj) AS tj FROM historial_stock_productos_fiscales WHERE (DATE_FORMAT(dateEmission, '%Y-%m-%d') BETWEEN '$INICIO' AND '$FIN') ")or die(mysqli_error($MySQLi));
                        $dataVentas		=	mysqli_fetch_assoc($queryTotalIncial);
                        $TotalInicial 	=	$dataVentas['tj'];
                        echo $TotalInicial;
                                ?>
                    </span></strong>
            </td>
            <td class="text-center">
                <strong>
                    <?php 
						//GranTotalFinal 
                        // $queryTotalFinal	=	mysqli_query($MySQLi,"SELECT SUM(final) AS final FROM historial_stock_productos_fiscales WHERE (DATE_FORMAT(dateEmission, '%Y-%m-%d') BETWEEN '$INICIO' AND '$FIN') ")or die(mysqli_error($MySQLi));
                        // $dataVentas		=	mysqli_fetch_assoc($queryTotalFinal);
                        // $TotalFinal 	=	$dataVentas['final'];
                        echo $GranTotalFinal;
                                ?>
                </strong>
            </td>
            <td class="text-center"></td>
            <td class="text-center"></td>
            <td class="text-center"></td>
            <td colspan="2" class="text-center"></td>

        </tr>


    </tbody>
</table><?php mysqli_close($MySQLi);
		
	}
		//excel reportes productos fiscales sin fecha- totalidad
	elseif (isset($_GET['historial_productos_fiscales_completo_sin_fechas'])) {
		$Sucursal 	=	$_GET['historial_productos_fiscales_completo_sin_fechas'];
		$INICIO 		=	'2020-01-01';
		$FIN 		=	'2040-01-01';

		header("Content-type: application/vnd.ms-excel; name='excel'");
		header("Content-Disposition: filename=ListadoGeneralHistorialFiscales.xls");
		header("Pragma: no-cache");
		header("Pragma: no-cache");
		header("Expires: 0");

		 ?>

<table border="1">
    <thead>
        <tr>
            <th colspan="14" style="text-align: center;">
                <h3>LISTADO GENERAL HISTORIAL PRODUCTOS FISCALES </h3>
            </th>
        </tr>
        <tr>
            <th width="5%" class="text-center">N&ordm;</th>
            <th width="5%" class="text-center">idProducto</th>
            <th class="text-center">PRODUCTO FISCAL <br>DETALLE</th>
            <th width="5%" class="text-center">INICIAL</th>
            <th width="5%" class="text-center btn-warning"><span style="color: orange">CB</span></th>
            <th width="5%" class="text-center btn-primary"><span style="color: blue">LP</span></th>
            <th width="5%" class="text-center btn-success"><span style="color: green">SC</span></th>
            <th width="5%" class="text-center btn-danger"><span style="color: red">ST</span></th>
            <th width="5%" class="text-center btn-info"><span style="color: #40CFFF">TJ</span></th>
            <th width="5%" class="text-center">FINAL</th>
            <th class="text-center">VENDEDOR</th>
            <th class="text-center">FECHA</th>
            <th class="text-center">#FACTURA</th>
            <th colspan="2" class="text-center">DESCRIPCION</th>
        </tr>
    </thead>
    <tbody>
        <?php		
		$query="SELECT * FROM productos_fiscales ORDER BY idProducto ASC";
		$queryProductos	=	mysqli_query($MySQLi,$query);
		$Num=1;
		$GranTotalInicial=0;
		$GranTotalFinal=0;
		while ($dataProducto = mysqli_fetch_assoc($queryProductos)) {
						
					$idProducto = $dataProducto['idProducto'];
					//inicial final cb,lp,sc,tj
					$queryHistorialProductos =	mysqli_query($MySQLi,
					"SELECT
					MAX(inicial) AS inicial,
    				MIN(final) AS final,
					SUM(cb) AS cb,
					SUM(lp) AS lp,
					SUM(sc) AS sc,
					SUM(st) AS st,
					SUM(tj) AS tj
					FROM
						historial_stock_productos_fiscales
					WHERE
						idProducto = '$idProducto' AND(
					DATE_FORMAT(dateEmission, '%Y-%m-%d') BETWEEN '$INICIO' AND '$FIN')");

					$dataHistorialProductos	=	mysqli_fetch_assoc($queryHistorialProductos);

					//query vendedor,dateEmission,invoiceNumber,descripcion
					$queryVendedores =	mysqli_query($MySQLi,
					"SELECT
					vendedor,dateEmission,invoiceNumber,descripcion
					FROM
					historial_stock_productos_fiscales
					WHERE
						idProducto = '$idProducto' AND(
					DATE_FORMAT(dateEmission, '%Y-%m-%d') BETWEEN '$INICIO' AND '$FIN')");

					$vendedorArray=[];
					$dateEmissionArray=[];
					$invoiceNumberArray=[];
					$descripcionArray=[];

					while($dataVendedores	=	mysqli_fetch_assoc($queryVendedores)){
						$vendedorArray[]= $dataVendedores['vendedor'];
						$dateEmissionArray[]= $dataVendedores['dateEmission'];
						$invoiceNumberArray[]= $dataVendedores['invoiceNumber'];
						$descripcionArray[]= $dataVendedores['descripcion'];
					}
		
					?>
        <tr>
            <td class="text-center"><?php echo $Num ?></td>
            <td class="text-center"><?php echo $dataProducto['idProducto']; ?></td>
            <td class="text-center"><?php echo $dataProducto['detalle']; ?></td>
            <td class="text-center">
                <?php 
							//inicial producto su stock mas antiguo

							$queryStockMasAntiguo =	mysqli_query($MySQLi,
							"SELECT
							inicial
							FROM
							historial_stock_productos_fiscales
							WHERE
							idProducto = '$idProducto' AND dateEmission =(
							SELECT
								MIN(dateEmission)
							FROM
								historial_stock_productos_fiscales
							WHERE
								idProducto = '$idProducto')");

							$dataStockMasAntiguo	=	mysqli_fetch_assoc($queryStockMasAntiguo);
							//$inicialMasAntiguo=$dataStockMasAntiguo['inicial'];

							if($dataStockMasAntiguo['inicial']==null){//no entro a historial
								$GranTotalInicial=$GranTotalInicial+$dataProducto['saldo_fisico'];//tomamos su stock
								echo $dataProducto['saldo_fisico'];
							}
							else{
								$GranTotalInicial=$GranTotalInicial+$dataStockMasAntiguo['inicial'];//si entro a historial
								echo $dataStockMasAntiguo['inicial']; }//tomamos su valor mas antiguo
							?>
            </td>
            <td class="text-center">
                <?php 
							if($dataHistorialProductos['cb']==null){
								echo '0';
							}else{
							echo $dataHistorialProductos['cb'];
							}
							 ?>
            </td>
            <td class="text-center">
                <?php 
							if($dataHistorialProductos['lp']==null){
								echo '0';
							}else{
							echo $dataHistorialProductos['lp'];
							}
							 ?>
            </td>
            <td class="text-center">
                <?php 
							if($dataHistorialProductos['sc']==null){
								echo '0';
							}else{
							echo $dataHistorialProductos['sc'];
							}
							 ?>
            </td>
			<td class="text-center">
                <?php 
							if($dataHistorialProductos['st']==null){
								echo '0';
							}else{
							echo $dataHistorialProductos['st'];
							}
							 ?>
            </td>
            <td class="text-center">
                <?php 
							if($dataHistorialProductos['tj']==null){
								echo '0';
							}else{
							echo $dataHistorialProductos['tj'];
							}
							 ?>
            </td>
            <td class="text-center">
                <?php 
							//final producto su stock actual como quedo en la bd prodfiscales
								$GranTotalFinal=$GranTotalFinal+$dataProducto['saldo_fisico'];
								echo $dataProducto['saldo_fisico'];
						
							?>
            </td>
            <td class="text-center">
                <?php
							if($vendedorArray==null){echo '';}
							else {
								foreach ($vendedorArray as $value) {
									echo $value . '<br>';
								}
							}								
							 ?>
            </td>
            <td class="text-center">
                <?php
							if($dateEmissionArray==null){echo '';}
							else {
								foreach ($dateEmissionArray as $value) {
									echo $value . '<br>';
								}
							}								
							?>
            </td>
            <td class="text-center">
                <?php
							if($invoiceNumberArray==null){echo '';}
							else {
								foreach ($invoiceNumberArray as $value) {
									echo $value . '<br>';
								}
							}								
							?>
            </td>
            <td colspan="2" class="text-center">
                <?php
							if($descripcionArray==null){echo '';}
							else {
								foreach ($descripcionArray as $value) {
									echo $value . '<br>';
								}
							}
							?>
            </td>
        </tr>
        <?php $Num++;
			} ?>
        <tr class="odd gradeX">
            <td class="text-center"><?php echo $Num ?></td>

            <th colspan="2" class="text-center">TOTAL</th>

            <td class="text-center">
                <strong>
                    <?php  
						//GranTotal inicial		
                        // $queryTotalIncial	=	mysqli_query($MySQLi,"SELECT SUM(inicial) AS inicial FROM historial_stock_productos_fiscales WHERE (DATE_FORMAT(dateEmission, '%Y-%m-%d') BETWEEN '$INICIO' AND '$FIN') ")or die(mysqli_error($MySQLi));
                        // $dataVentas		=	mysqli_fetch_assoc($queryTotalIncial);
                        // $TotalInicial 	=	$dataVentas['inicial'];
                        echo $GranTotalInicial;
                                ?>
                </strong>
            </td>
            <td class="text-center">
                <strong><span style="color: orange">
                        <?php  
                        $queryTotalIncial	=	mysqli_query($MySQLi,"SELECT SUM(cb) AS cb FROM historial_stock_productos_fiscales WHERE (DATE_FORMAT(dateEmission, '%Y-%m-%d') BETWEEN '$INICIO' AND '$FIN') ")or die(mysqli_error($MySQLi));
                        $dataVentas		=	mysqli_fetch_assoc($queryTotalIncial);
                        $TotalInicial 	=	$dataVentas['cb'];
                        echo $TotalInicial;
                                ?>
                    </span></strong>
            </td>
            <td class="text-center">
                <strong><span style="color: blue">
                        <?php  
                        $queryTotalIncial	=	mysqli_query($MySQLi,"SELECT SUM(lp) AS lp FROM historial_stock_productos_fiscales WHERE (DATE_FORMAT(dateEmission, '%Y-%m-%d') BETWEEN '$INICIO' AND '$FIN') ")or die(mysqli_error($MySQLi));
                        $dataVentas		=	mysqli_fetch_assoc($queryTotalIncial);
                        $TotalInicial 	=	$dataVentas['lp'];
                        echo $TotalInicial;
                                ?>
                    </span></strong>
            </td>
            <td class="text-center">
                <strong><span style="color: green">
                        <?php  
                        $queryTotalIncial	=	mysqli_query($MySQLi,"SELECT SUM(sc) AS sc FROM historial_stock_productos_fiscales WHERE (DATE_FORMAT(dateEmission, '%Y-%m-%d') BETWEEN '$INICIO' AND '$FIN') ")or die(mysqli_error($MySQLi));
                        $dataVentas		=	mysqli_fetch_assoc($queryTotalIncial);
                        $TotalInicial 	=	$dataVentas['sc'];
                        echo $TotalInicial;
                                ?>
                    </span></strong>
            </td>
			<td class="text-center">
                <strong><span style="color: red">
                        <?php  
                        $queryTotalIncial	=	mysqli_query($MySQLi,"SELECT SUM(st) AS st FROM historial_stock_productos_fiscales WHERE (DATE_FORMAT(dateEmission, '%Y-%m-%d') BETWEEN '$INICIO' AND '$FIN') ")or die(mysqli_error($MySQLi));
                        $dataVentas		=	mysqli_fetch_assoc($queryTotalIncial);
                        $TotalInicial 	=	$dataVentas['st'];
                        echo $TotalInicial;
                                ?>
                    </span></strong>
            </td>
            <td class="text-center">
                <strong><span style="color: #40CFFF">
                        <?php  
                        $queryTotalIncial	=	mysqli_query($MySQLi,"SELECT SUM(tj) AS tj FROM historial_stock_productos_fiscales WHERE (DATE_FORMAT(dateEmission, '%Y-%m-%d') BETWEEN '$INICIO' AND '$FIN') ")or die(mysqli_error($MySQLi));
                        $dataVentas		=	mysqli_fetch_assoc($queryTotalIncial);
                        $TotalInicial 	=	$dataVentas['tj'];
                        echo $TotalInicial;
                                ?>
                    </span></strong>
            </td>
            <td class="text-center">
                <strong>
                    <?php 
						//GranTotalFinal 
                        // $queryTotalFinal	=	mysqli_query($MySQLi,"SELECT SUM(final) AS final FROM historial_stock_productos_fiscales WHERE (DATE_FORMAT(dateEmission, '%Y-%m-%d') BETWEEN '$INICIO' AND '$FIN') ")or die(mysqli_error($MySQLi));
                        // $dataVentas		=	mysqli_fetch_assoc($queryTotalFinal);
                        // $TotalFinal 	=	$dataVentas['final'];
                        echo $GranTotalFinal;
                                ?>
                </strong>
            </td>
            <td class="text-center"></td>
            <td class="text-center"></td>
            <td class="text-center"></td>
            <td colspan="2" class="text-center"></td>

        </tr>


    </tbody>
</table><?php mysqli_close($MySQLi);
		
	}
	//   historial productos reales --------------------------------------------------------------------------------------------------------------
	//------------------------------------------------------PRODUCTOS REALES HISTORIAL CON RANGO DE FECHAS--------------------------------------
	//excel reportes productos reales con fecha inicio y fin CON RANGO DE FECHAS
	elseif (isset($_GET['historial_productos_completo_con_fechas'])AND isset($_GET['fechaInicio'])AND isset($_GET['fechafin'])) {
		$Sucursal 	=	$_GET['historial_productos_completo_con_fechas'];
		$INICIO 		=	$_GET['fechaInicio'];
		$FIN 		=	$_GET['fechafin'];

		header("Content-type: application/vnd.ms-excel; name='excel'");
		header("Content-Disposition: filename=ListadoProductosRangoFechas".$INICIO."__".$FIN.".xls");
		header("Pragma: no-cache");
		header("Pragma: no-cache");
		header("Expires: 0");

		 ?>

<table border="1">
    <thead>
        <tr>
            <th colspan="14" style="text-align: center;">
                <h3>HISTORIAL PRODUCTOS DESDE EL <span style="color: green"><?php echo $INICIO ?></span> HASTA EL <span
                        style="color: red"><?php echo $FIN ?></span></h3>
            </th>
        </tr>
        <tr>
            <th width="5%" class="text-center">N&ordm;</th>
            <!-- <th width="5%" class="text-center">idProducto</th> -->
            <th class="text-center">PRODUCTO</th>

            <th class="text-center">MARCA</th>
            <th class="text-center">MODELO</th>

            <th colspan="4" width="5%" class="text-center">INICIAL</th>

            <th width="5%" class="text-center btn-warning"><span style="color: orange">CB</span></th>
            <th width="5%" class="text-center btn-primary"><span style="color: blue">LP</span></th>
            <th width="5%" class="text-center btn-success"><span style="color: green">SC</span></th>
            <th width="5%" class="text-center btn-success"><span style="color: red">ST</span></th>
            <th width="5%" class="text-center btn-info"><span style="color: #40CFFF">TJ</span></th>

            <th colspan="4" width="5%" class="text-center">FINAL</th>
            <th class="text-center">VENDEDOR</th>
            <th class="text-center">FECHA</th>
            <!-- <th class="text-center">#FACTURA</th> -->
            <th colspan="2" class="text-center">DESCRIPCION</th>
        </tr>
    </thead>
    <tbody>
        <?php		
		$query="SELECT * FROM Productos ORDER BY StockTotal ASC";
		$queryProductos	=	mysqli_query($MySQLi,$query);
		$Num=1;

		$GranTotalInicial=0;

		$GranTotalInicialCB=0;
		$GranTotalInicialLP=0;
		$GranTotalInicialSC=0;
		$GranTotalInicialST=0;
		$GranTotalInicialTJ=0;

		$GranTotalFinal=0;

		$GranTotalFinalCB=0;
		$GranTotalFinalLP=0;
		$GranTotalFinalSC=0;
		$GranTotalFinalST=0;
		$GranTotalFinalTJ=0;
		while ($dataProducto = mysqli_fetch_assoc($queryProductos)) {
						
					$idProducto = $dataProducto['idProducto'];
					//inicial final cb,lp,sc,tj
					$queryHistorialProductos =	mysqli_query($MySQLi,
					"SELECT
					MAX(inicial) AS inicial,
    				MIN(final) AS final,
					SUM(cb) AS cb,
					SUM(lp) AS lp,
					SUM(sc) AS sc,
					SUM(st) AS st,
					SUM(tj) AS tj
					FROM
						historial_stock_productos
					WHERE
						idProducto = '$idProducto' AND(
					DATE_FORMAT(dateEmission, '%Y-%m-%d') BETWEEN '$INICIO' AND '$FIN')");

					$dataHistorialProductos	=	mysqli_fetch_assoc($queryHistorialProductos);

					//query vendedor,dateEmission,invoiceNumber,descripcion
					$queryVendedores =	mysqli_query($MySQLi,
					"SELECT
					vendedor,dateEmission,sucursal,descripcion
					FROM
					historial_stock_productos
					WHERE
						idProducto = '$idProducto' AND(
					DATE_FORMAT(dateEmission, '%Y-%m-%d') BETWEEN '$INICIO' AND '$FIN')");

					$vendedorArray=[];
					$dateEmissionArray=[];
					$sucursalArray=[];
					$descripcionArray=[];

					while($dataVendedores	=	mysqli_fetch_assoc($queryVendedores)){
						$vendedorArray[]= $dataVendedores['vendedor'];
						$dateEmissionArray[]= $dataVendedores['dateEmission'];
						$sucursalArray[]= $dataVendedores['sucursal'];
						$descripcionArray[]= $dataVendedores['descripcion'];
					}
		
					?>
        <tr>
            <td class="text-center"><?php echo $Num ?></td>
            <!-- <td class="text-center"><?php //echo $dataProducto['idProducto']; ?></td> -->

            <td class="text-center"><?php echo utf8_decode($dataProducto['Producto'] ); ?></td>
            <td class="text-center"><?php echo utf8_decode($dataProducto['Marca'] ); ?></td>
            <td class="text-center"><?php echo utf8_decode($dataProducto['Modelo'] ); ?></td>

            <td class="text-center">
                <?php 
							//inicial producto su stock mas antiguo con rango de fechas
							//cocha
							$queryStockMasAntiguo =	mysqli_query($MySQLi,
							"SELECT
							inicial
							FROM
							historial_stock_productos
							WHERE
							idProducto = '$idProducto' AND dateEmission =(
							SELECT
								MIN(dateEmission)
							FROM
								historial_stock_productos
							WHERE
								idProducto = '$idProducto' 
								AND(DATE_FORMAT(dateEmission, '%Y-%m-%d') BETWEEN '$INICIO' AND '$FIN') 
								AND sucursal='Cochabamba'
								)");

							$dataStockMasAntiguo	=	mysqli_fetch_assoc($queryStockMasAntiguo);
							

							if($dataStockMasAntiguo['inicial']==null){//no entro a historial
								//1er caso
								$queryStockMasAntiguo =	mysqli_query($MySQLi,
								"SELECT
								inicial
								FROM
								historial_stock_productos
								WHERE
								idProducto = '$idProducto' AND dateEmission =(
								SELECT
									MIN(dateEmission)
								FROM
									historial_stock_productos
								WHERE
									idProducto = '$idProducto' 
									AND(DATE_FORMAT(dateEmission, '%Y-%m-%d') BETWEEN '$INICIO' AND '2040-01-01') 
									AND sucursal='Cochabamba'
									)");
	
								$dataStockMasAntiguo	=	mysqli_fetch_assoc($queryStockMasAntiguo);

							}
							if($dataStockMasAntiguo['inicial']==null){//no entro a historial
								//2do caso
								$queryStockMasAntiguo =	mysqli_query($MySQLi,
								"SELECT
								final as 'inicial'
								FROM
								historial_stock_productos
								WHERE
								idProducto = '$idProducto' AND dateEmission =(
								SELECT
									MAX(dateEmission)
								FROM
									historial_stock_productos
								WHERE
									idProducto = '$idProducto' 
									AND(DATE_FORMAT(dateEmission, '%Y-%m-%d') BETWEEN '2000-01-01' AND '$INICIO' ) 
									AND sucursal='Cochabamba'
									)");
	
								$dataStockMasAntiguo	=	mysqli_fetch_assoc($queryStockMasAntiguo);

							}

							if($dataStockMasAntiguo['inicial']==null){
							//3er caso nunca de los nunca entro al historial y tomamos el stock
								$GranTotalInicial=$GranTotalInicial+$dataProducto['StockCB'];//tomamos su stock
								$GranTotalInicialCB=$GranTotalInicialCB+$dataProducto['StockCB'];//SOLO CBA
								echo $dataProducto['StockCB'];
							}
							if($dataStockMasAntiguo['inicial']!=null){
								//4to caso si entro al historial en rango 
								$GranTotalInicial=$GranTotalInicial+$dataStockMasAntiguo['inicial'];//si entro a historial
								$GranTotalInicialCB=$GranTotalInicialCB+$dataStockMasAntiguo['inicial'];
								 echo $dataStockMasAntiguo['inicial']; //tomamos su valor mas antiguo
							}

			?>
            </td>
            <td>
                <?php
							//la paz
							//inicial producto su stock mas antiguo con rango de fechas
							$queryStockMasAntiguo =	mysqli_query($MySQLi,
							"SELECT
							inicial
							FROM
							historial_stock_productos
							WHERE
							idProducto = '$idProducto' AND dateEmission =(
							SELECT
								MIN(dateEmission)
							FROM
								historial_stock_productos
							WHERE
								idProducto = '$idProducto' 
								AND(DATE_FORMAT(dateEmission, '%Y-%m-%d') BETWEEN '$INICIO' AND '$FIN') 
								AND sucursal='La Paz'
								)");

							$dataStockMasAntiguo	=	mysqli_fetch_assoc($queryStockMasAntiguo);
							

							if($dataStockMasAntiguo['inicial']==null){
								//1ercaso
								$queryStockMasAntiguo =	mysqli_query($MySQLi,
								"SELECT
								inicial
								FROM
								historial_stock_productos
								WHERE
								idProducto = '$idProducto' AND dateEmission =(
								SELECT
									MIN(dateEmission)
								FROM
									historial_stock_productos
								WHERE
									idProducto = '$idProducto' 
									AND(DATE_FORMAT(dateEmission, '%Y-%m-%d') BETWEEN '$INICIO' AND '2040-01-01') 
									AND sucursal='La Paz'
									)");
	
								$dataStockMasAntiguo	=	mysqli_fetch_assoc($queryStockMasAntiguo);

							}
							if($dataStockMasAntiguo['inicial']==null){
								//2do caso la paz
								$queryStockMasAntiguo =	mysqli_query($MySQLi,
								"SELECT
								final as 'inicial'
								FROM
								historial_stock_productos
								WHERE
								idProducto = '$idProducto' AND dateEmission =(
								SELECT
									MAX(dateEmission)
								FROM
									historial_stock_productos
								WHERE
									idProducto = '$idProducto' 
									AND(DATE_FORMAT(dateEmission, '%Y-%m-%d') BETWEEN '2000-01-01' AND '$INICIO') 
									AND sucursal='La Paz'
									)");
	
								$dataStockMasAntiguo	=	mysqli_fetch_assoc($queryStockMasAntiguo);
								
							}


							if($dataStockMasAntiguo['inicial']==null){//no entro a historial
							//3er caso
								$GranTotalInicial=$GranTotalInicial+$dataProducto['StockLP'];//tomamos su stock
								$GranTotalInicialLP=$GranTotalInicialLP+$dataProducto['StockLP'];
								echo $dataProducto['StockLP'];
							}
							if($dataStockMasAntiguo['inicial']!=null){
								//4to caso
								$GranTotalInicial=$GranTotalInicial+$dataStockMasAntiguo['inicial'];//si entro a historial
								$GranTotalInicialLP=$GranTotalInicialLP+$dataStockMasAntiguo['inicial'];
								echo $dataStockMasAntiguo['inicial']; //tomamos su valor mas antiguo
							}


			?>
            </td>
            <td>
                <?php
							///-----------------------------SANTA CRUZ
								
							//inicial producto su stock mas antiguo con rango de fechas
							$queryStockMasAntiguo =	mysqli_query($MySQLi,
							"SELECT
							inicial
							FROM
							historial_stock_productos
							WHERE
							idProducto = '$idProducto' AND dateEmission =(
							SELECT
								MIN(dateEmission)
							FROM
								historial_stock_productos
							WHERE
								idProducto = '$idProducto' 
								AND(DATE_FORMAT(dateEmission, '%Y-%m-%d') BETWEEN '$INICIO' AND '$FIN') 
								AND sucursal='Santa Cruz'
								)");

							$dataStockMasAntiguo	=	mysqli_fetch_assoc($queryStockMasAntiguo);

							if($dataStockMasAntiguo['inicial']==null){
								//1er caso santa cruz
								$queryStockMasAntiguo =	mysqli_query($MySQLi,
							"SELECT
							inicial
							FROM
							historial_stock_productos
							WHERE
							idProducto = '$idProducto' AND dateEmission =(
							SELECT
								MIN(dateEmission)
							FROM
								historial_stock_productos
							WHERE
								idProducto = '$idProducto' 
								AND(DATE_FORMAT(dateEmission, '%Y-%m-%d') BETWEEN '$INICIO' AND '2040-01-01') 
								AND sucursal='Santa Cruz'
								)");

							$dataStockMasAntiguo	=	mysqli_fetch_assoc($queryStockMasAntiguo);

							}
							if($dataStockMasAntiguo['inicial']==null){
								//2do caso santacruz
								$queryStockMasAntiguo =	mysqli_query($MySQLi,
								"SELECT
								final as 'inicial'
								FROM
								historial_stock_productos
								WHERE
								idProducto = '$idProducto' AND dateEmission =(
								SELECT
									MAX(dateEmission)
								FROM
									historial_stock_productos
								WHERE
									idProducto = '$idProducto' 
									AND(DATE_FORMAT(dateEmission, '%Y-%m-%d') BETWEEN '2000-01-01' AND '$INICIO') 
									AND sucursal='Santa Cruz'
									)");
	
								$dataStockMasAntiguo	=	mysqli_fetch_assoc($queryStockMasAntiguo);
								

							}

							
							if($dataStockMasAntiguo['inicial']==null){//no entro a historial
								//3er caso
								$GranTotalInicial=$GranTotalInicial+$dataProducto['StockSC'];//tomamos su stock
								$GranTotalInicialSC=$GranTotalInicialSC+$dataProducto['StockSC'];
								echo $dataProducto['StockSC'];
							}
							if($dataStockMasAntiguo['inicial']!=null){
								//4to caso
								$GranTotalInicial=$GranTotalInicial+$dataStockMasAntiguo['inicial'];//si entro a historial
								$GranTotalInicialSC=$GranTotalInicialSC+$dataStockMasAntiguo['inicial'];
								echo $dataStockMasAntiguo['inicial']; }//tomamos su valor mas antiguo

				?>
            </td>
			<!-- ST TROMPILLO -->
			 <td>
                <?php
					//inicial producto su stock mas antiguo con rango de fechas
					$queryStockMasAntiguo =	mysqli_query($MySQLi,
					"SELECT
					inicial
					FROM
					historial_stock_productos
					WHERE
					idProducto = '$idProducto' AND dateEmission =(
					SELECT
						MIN(dateEmission)
					FROM
						historial_stock_productos
					WHERE
						idProducto = '$idProducto' 
						AND(DATE_FORMAT(dateEmission, '%Y-%m-%d') BETWEEN '$INICIO' AND '$FIN') 
						AND sucursal='Santa Cruz Trompillo'
						)");

					$dataStockMasAntiguo	=	mysqli_fetch_assoc($queryStockMasAntiguo);

					if($dataStockMasAntiguo['inicial']==null){
					//1er caso 
					$queryStockMasAntiguo =	mysqli_query($MySQLi,
					"SELECT
					inicial
					FROM
					historial_stock_productos
					WHERE
					idProducto = '$idProducto' AND dateEmission =(
					SELECT
						MIN(dateEmission)
					FROM
						historial_stock_productos
					WHERE
						idProducto = '$idProducto' 
						AND(DATE_FORMAT(dateEmission, '%Y-%m-%d') BETWEEN '$INICIO' AND '2040-01-01') 
						AND sucursal='Santa Cruz Trompillo'
						)");

					$dataStockMasAntiguo	=	mysqli_fetch_assoc($queryStockMasAntiguo);

					}
					if($dataStockMasAntiguo['inicial']==null){
						//2do caso
						$queryStockMasAntiguo =	mysqli_query($MySQLi,
						"SELECT
						final as 'inicial'
						FROM
						historial_stock_productos
						WHERE
						idProducto = '$idProducto' AND dateEmission =(
						SELECT
							MAX(dateEmission)
						FROM
							historial_stock_productos
						WHERE
							idProducto = '$idProducto' 
							AND(DATE_FORMAT(dateEmission, '%Y-%m-%d') BETWEEN '2000-01-01' AND '$INICIO') 
							AND sucursal='Santa Cruz Trompillo'
							)");

						$dataStockMasAntiguo	=	mysqli_fetch_assoc($queryStockMasAntiguo);
						

					}

					
					if($dataStockMasAntiguo['inicial']==null){//no entro a historial
						//3er caso
						$GranTotalInicial=$GranTotalInicial+$dataProducto['StockST'];//tomamos su stock
						$GranTotalInicialSC=$GranTotalInicialSC+$dataProducto['StockST'];
						echo $dataProducto['StockST'];
					}
					if($dataStockMasAntiguo['inicial']!=null){
						//4to caso
						$GranTotalInicial=$GranTotalInicial+$dataStockMasAntiguo['inicial'];//si entro a historial
						$GranTotalInicialSC=$GranTotalInicialSC+$dataStockMasAntiguo['inicial'];
						echo $dataStockMasAntiguo['inicial']; }//tomamos su valor mas antiguo

				?>
            </td>
            <td>
                <?php

								///-----------------------------TARIJA
								
								//inicial producto su stock mas antiguo con rango de fechas
								$queryStockMasAntiguo =	mysqli_query($MySQLi,
								"SELECT
								inicial
								FROM
								historial_stock_productos
								WHERE
								idProducto = '$idProducto' AND dateEmission =(
								SELECT
									MIN(dateEmission)
								FROM
									historial_stock_productos
								WHERE
									idProducto = '$idProducto' 
									AND(DATE_FORMAT(dateEmission, '%Y-%m-%d') BETWEEN '$INICIO' AND '$FIN') 
									AND sucursal='Tarija'
									)");
	
								$dataStockMasAntiguo	=	mysqli_fetch_assoc($queryStockMasAntiguo);

								if($dataStockMasAntiguo['inicial']==null){
									//1er caso
								$queryStockMasAntiguo =	mysqli_query($MySQLi,
								"SELECT
								inicial
								FROM
								historial_stock_productos
								WHERE
								idProducto = '$idProducto' AND dateEmission =(
								SELECT
									MIN(dateEmission)
								FROM
									historial_stock_productos
								WHERE
									idProducto = '$idProducto' 
									AND(DATE_FORMAT(dateEmission, '%Y-%m-%d') BETWEEN '$INICIO' AND '2040-01-01') 
									AND sucursal='Tarija'
									)");
	
								$dataStockMasAntiguo	=	mysqli_fetch_assoc($queryStockMasAntiguo);

								}
								if($dataStockMasAntiguo['inicial']==null){
									// 2do caso
								$queryStockMasAntiguo =	mysqli_query($MySQLi,
								"SELECT
								final as 'inicial'
								FROM
								historial_stock_productos
								WHERE
								idProducto = '$idProducto' AND dateEmission =(
								SELECT
									MAX(dateEmission)
								FROM
									historial_stock_productos
								WHERE
									idProducto = '$idProducto' 
									AND(DATE_FORMAT(dateEmission, '%Y-%m-%d') BETWEEN '2000-01-01' AND '$INICIO') 
									AND sucursal='Tarija'
									)");
	
								$dataStockMasAntiguo	=	mysqli_fetch_assoc($queryStockMasAntiguo);
								}
								if($dataStockMasAntiguo['inicial']==null){//no entro a historial
										//3ER CASO
									$GranTotalInicial=$GranTotalInicial+$dataProducto['StockTJ'];//tomamos su stock
									$GranTotalInicialTJ=$GranTotalInicialTJ+$dataProducto['StockTJ'];
									echo $dataProducto['StockTJ'];
								}
								if($dataStockMasAntiguo['inicial']!=null){
									//4to caso
									$GranTotalInicial=$GranTotalInicial+$dataStockMasAntiguo['inicial'];//si entro a historial
									$GranTotalInicialTJ=$GranTotalInicialTJ+$dataStockMasAntiguo['inicial'];
									echo $dataStockMasAntiguo['inicial']; }//tomamos su valor mas antiguo

						
			?>
            </td>

            <td class="text-center">
                <strong>
                    <?php 
							if($dataHistorialProductos['cb']==null){
								echo '0';
							}else{
							echo $dataHistorialProductos['cb'];
							}
							 ?>
                </strong>
            </td>
            <td class="text-center">
                <strong>
                    <?php 
							if($dataHistorialProductos['lp']==null){
								echo '0';
							}else{
							echo $dataHistorialProductos['lp'];
							}
							 ?>
                </strong>
            </td>
            <td class="text-center">
                <strong>
                    <?php 
							if($dataHistorialProductos['sc']==null){
								echo '0';
							}else{
							echo $dataHistorialProductos['sc'];
							}
							 ?>
                </strong>
            </td>
			<td class="text-center">
                <strong>
                    <?php 
							if($dataHistorialProductos['st']==null){
								echo '0';
							}else{
							echo $dataHistorialProductos['st'];
							}
							 ?>
                </strong>
            </td>
            <td class="text-center">
                <strong>
                    <?php 
							if($dataHistorialProductos['tj']==null){
								echo '0';
							}else{
							echo $dataHistorialProductos['tj'];
							}
							 ?>
                </strong>
            </td>

            <td class="text-center">
                <?php 
							//final producto CON RANGO DE FECHAS
							$queryStockActualFechaFin =	mysqli_query($MySQLi,
							"SELECT
							final
							FROM
							historial_stock_productos
							WHERE
							idProducto = '$idProducto' AND dateEmission =(
							SELECT
								MAX(dateEmission)
							FROM
								historial_stock_productos
							WHERE
								idProducto = '$idProducto' 
								AND(DATE_FORMAT(dateEmission, '%Y-%m-%d') BETWEEN '$INICIO' AND '$FIN')
								AND sucursal='Cochabamba'
								)");

							$dataStockFechaFin	=	mysqli_fetch_assoc($queryStockActualFechaFin);
							
							if($dataStockFechaFin['final']==null){
								// 1er caso cba
								$queryStockActualFechaFin =	mysqli_query($MySQLi,
								"SELECT
								final
								FROM
								historial_stock_productos
								WHERE
								idProducto = '$idProducto' AND dateEmission =(
								SELECT
									MAX(dateEmission)
								FROM
									historial_stock_productos
								WHERE
									idProducto = '$idProducto' 
									AND(DATE_FORMAT(dateEmission, '%Y-%m-%d') BETWEEN '2000-01-01' AND '$FIN')
									AND sucursal='Cochabamba'
									)");
	
								$dataStockFechaFin	=	mysqli_fetch_assoc($queryStockActualFechaFin);

							}
							if($dataStockFechaFin['final']==null){
								// 2do caso cba
								$queryStockActualFechaFin =	mysqli_query($MySQLi,
								"SELECT
								inicial as 'final'
								FROM
								historial_stock_productos
								WHERE
								idProducto = '$idProducto' AND dateEmission =(
								SELECT
									MIN(dateEmission)
								FROM
									historial_stock_productos
								WHERE
									idProducto = '$idProducto' 
									AND(DATE_FORMAT(dateEmission, '%Y-%m-%d') BETWEEN '$FIN' AND '2040-01-01')
									AND sucursal='Cochabamba'
									)");
	
								$dataStockFechaFin	=	mysqli_fetch_assoc($queryStockActualFechaFin);

							}


							if($dataStockFechaFin['final']==null){//sino existe tomamos del stock producutso fiscale
								// 3er caso
								$GranTotalFinal=$GranTotalFinal+$dataProducto['StockCB'];
								$GranTotalFinalCB=$GranTotalFinalCB+$dataProducto['StockCB'];
								echo $dataProducto['StockCB'];
							}
							if($dataStockFechaFin['final']!=null){
								// 4to caso
								$GranTotalFinal=$GranTotalFinal+$dataStockFechaFin['final'];
								$GranTotalFinalCB=$GranTotalFinalCB+$dataStockFechaFin['final'];
								echo $dataStockFechaFin['final']; }
				?>
            </td>
            <td>
                <?php 
							//final producto CON RANGO DE FECHAS lapaz
							$queryStockActualFechaFin =	mysqli_query($MySQLi,
							"SELECT
							final
							FROM
							historial_stock_productos
							WHERE
							idProducto = '$idProducto' AND dateEmission =(
							SELECT
								MAX(dateEmission)
							FROM
								historial_stock_productos
							WHERE
								idProducto = '$idProducto' 
								AND(DATE_FORMAT(dateEmission, '%Y-%m-%d') BETWEEN '$INICIO' AND '$FIN')
								AND sucursal='La Paz'
								)");

							$dataStockFechaFin	=	mysqli_fetch_assoc($queryStockActualFechaFin);

							if($dataStockFechaFin['final']==null){
								// 1ER CASO
								$queryStockActualFechaFin =	mysqli_query($MySQLi,
								"SELECT
								final
								FROM
								historial_stock_productos
								WHERE
								idProducto = '$idProducto' AND dateEmission =(
								SELECT
									MAX(dateEmission)
								FROM
									historial_stock_productos
								WHERE
									idProducto = '$idProducto' 
									AND(DATE_FORMAT(dateEmission, '%Y-%m-%d') BETWEEN '2000-01-01' AND '$FIN')
									AND sucursal='La Paz'
									)");
	
								$dataStockFechaFin	=	mysqli_fetch_assoc($queryStockActualFechaFin);


							}
							if($dataStockFechaFin['final']==null){
								// 2DO CASO
								$queryStockActualFechaFin =	mysqli_query($MySQLi,
								"SELECT
								inicial as 'final'
								FROM
								historial_stock_productos
								WHERE
								idProducto = '$idProducto' AND dateEmission =(
								SELECT
									MIN(dateEmission)
								FROM
									historial_stock_productos
								WHERE
									idProducto = '$idProducto' 
									AND(DATE_FORMAT(dateEmission, '%Y-%m-%d') BETWEEN '$FIN' AND '2040-01-01')
									AND sucursal='La Paz'
									)");
	
								$dataStockFechaFin	=	mysqli_fetch_assoc($queryStockActualFechaFin);
							}

							if($dataStockFechaFin['final']==null){//sino existe tomamos del stock producutso fiscale
								$GranTotalFinal=$GranTotalFinal+$dataProducto['StockLP'];
								$GranTotalFinalLP=$GranTotalFinalLP+$dataProducto['StockLP'];
								echo $dataProducto['StockLP'];
							}
							if($dataStockFechaFin['final']!=null){
								$GranTotalFinal=$GranTotalFinal+$dataStockFechaFin['final'];
								$GranTotalFinalLP=$GranTotalFinalLP+$dataStockFechaFin['final'];
								echo $dataStockFechaFin['final']; }
				?>
            </td>
            <td>
                <?php 
							//final producto CON RANGO DE FECHAS santacruz
							$queryStockActualFechaFin =	mysqli_query($MySQLi,
							"SELECT
							final
							FROM
							historial_stock_productos
							WHERE
							idProducto = '$idProducto' AND dateEmission =(
							SELECT
								MAX(dateEmission)
							FROM
								historial_stock_productos
							WHERE
								idProducto = '$idProducto' 
								AND(DATE_FORMAT(dateEmission, '%Y-%m-%d') BETWEEN '$INICIO' AND '$FIN')
								AND sucursal='Santa Cruz'
								)");

							$dataStockFechaFin	=	mysqli_fetch_assoc($queryStockActualFechaFin);
							if($dataStockFechaFin['final']==null){
							// 1ER CASO
							$queryStockActualFechaFin =	mysqli_query($MySQLi,
							"SELECT
							final
							FROM
							historial_stock_productos
							WHERE
							idProducto = '$idProducto' AND dateEmission =(
							SELECT
								MAX(dateEmission)
							FROM
								historial_stock_productos
							WHERE
								idProducto = '$idProducto' 
								AND(DATE_FORMAT(dateEmission, '%Y-%m-%d') BETWEEN '2000-01-01' AND '$FIN')
								AND sucursal='Santa Cruz'
								)");

							$dataStockFechaFin	=	mysqli_fetch_assoc($queryStockActualFechaFin);
							}
							if($dataStockFechaFin['final']==null){
								// 2DO CASO
								$queryStockActualFechaFin =	mysqli_query($MySQLi,
								"SELECT
								inicial as 'final'
								FROM
								historial_stock_productos
								WHERE
								idProducto = '$idProducto' AND dateEmission =(
								SELECT
									MIN(dateEmission)
								FROM
									historial_stock_productos
								WHERE
									idProducto = '$idProducto' 
									AND(DATE_FORMAT(dateEmission, '%Y-%m-%d') BETWEEN '$FIN' AND '2040-01-01')
									AND sucursal='Santa Cruz'
									)");
	
								$dataStockFechaFin	=	mysqli_fetch_assoc($queryStockActualFechaFin);

							}

							if($dataStockFechaFin['final']==null){//sino existe tomamos del stock producutso fiscale
								$GranTotalFinal=$GranTotalFinal+$dataProducto['StockSC'];
								$GranTotalFinalSC=$GranTotalFinalSC+$dataProducto['StockSC'];
								echo $dataProducto['StockSC'];
							}
							if($dataStockFechaFin['final']!=null){
								$GranTotalFinal=$GranTotalFinal+$dataStockFechaFin['final'];
								$GranTotalFinalSC=$GranTotalFinalSC+$dataStockFechaFin['final'];
								echo $dataStockFechaFin['final']; }
				?>
            </td>
			<!-- sucursal trompillo -->
			<td>
                <?php 
							//final producto CON RANGO DE FECHAS
							$queryStockActualFechaFin =	mysqli_query($MySQLi,
							"SELECT
							final
							FROM
							historial_stock_productos
							WHERE
							idProducto = '$idProducto' AND dateEmission =(
							SELECT
								MAX(dateEmission)
							FROM
								historial_stock_productos
							WHERE
								idProducto = '$idProducto' 
								AND(DATE_FORMAT(dateEmission, '%Y-%m-%d') BETWEEN '$INICIO' AND '$FIN')
								AND sucursal='Santa Cruz Trompillo'
								)");

							$dataStockFechaFin	=	mysqli_fetch_assoc($queryStockActualFechaFin);
							if($dataStockFechaFin['final']==null){
							// 1ER CASO
							$queryStockActualFechaFin =	mysqli_query($MySQLi,
							"SELECT
							final
							FROM
							historial_stock_productos
							WHERE
							idProducto = '$idProducto' AND dateEmission =(
							SELECT
								MAX(dateEmission)
							FROM
								historial_stock_productos
							WHERE
								idProducto = '$idProducto' 
								AND(DATE_FORMAT(dateEmission, '%Y-%m-%d') BETWEEN '2000-01-01' AND '$FIN')
								AND sucursal='Santa Cruz Trompillo'
								)");

							$dataStockFechaFin	=	mysqli_fetch_assoc($queryStockActualFechaFin);
							}
							if($dataStockFechaFin['final']==null){
								// 2DO CASO
								$queryStockActualFechaFin =	mysqli_query($MySQLi,
								"SELECT
								inicial as 'final'
								FROM
								historial_stock_productos
								WHERE
								idProducto = '$idProducto' AND dateEmission =(
								SELECT
									MIN(dateEmission)
								FROM
									historial_stock_productos
								WHERE
									idProducto = '$idProducto' 
									AND(DATE_FORMAT(dateEmission, '%Y-%m-%d') BETWEEN '$FIN' AND '2040-01-01')
									AND sucursal='Santa Cruz Trompillo'
									)");
	
								$dataStockFechaFin	=	mysqli_fetch_assoc($queryStockActualFechaFin);

							}

							if($dataStockFechaFin['final']==null){//sino existe tomamos del stock producutso fiscale
								$GranTotalFinal=$GranTotalFinal+$dataProducto['StockST'];
								$GranTotalFinalSC=$GranTotalFinalSC+$dataProducto['StockST'];
								echo $dataProducto['StockST'];
							}
							if($dataStockFechaFin['final']!=null){
								$GranTotalFinal=$GranTotalFinal+$dataStockFechaFin['final'];
								$GranTotalFinalSC=$GranTotalFinalSC+$dataStockFechaFin['final'];
								echo $dataStockFechaFin['final']; }
				?>
            </td>
            <td>
                <?php 
							//final producto CON RANGO DE FECHAS tarija
							$queryStockActualFechaFin =	mysqli_query($MySQLi,
							"SELECT
							final
							FROM
							historial_stock_productos
							WHERE
							idProducto = '$idProducto' AND dateEmission =(
							SELECT
								MAX(dateEmission)
							FROM
								historial_stock_productos
							WHERE
								idProducto = '$idProducto' 
								AND(DATE_FORMAT(dateEmission, '%Y-%m-%d') BETWEEN '$INICIO' AND '$FIN')
								AND sucursal='Tarija'
								)");

							$dataStockFechaFin	=	mysqli_fetch_assoc($queryStockActualFechaFin);

							if($dataStockFechaFin['final']==null){
								// 1ERCASO
								$queryStockActualFechaFin =	mysqli_query($MySQLi,
								"SELECT
								final
								FROM
								historial_stock_productos
								WHERE
								idProducto = '$idProducto' AND dateEmission =(
								SELECT
									MAX(dateEmission)
								FROM
									historial_stock_productos
								WHERE
									idProducto = '$idProducto' 
									AND(DATE_FORMAT(dateEmission, '%Y-%m-%d') BETWEEN '2000-01-01' AND '$FIN')
									AND sucursal='Tarija'
									)");
	
								$dataStockFechaFin	=	mysqli_fetch_assoc($queryStockActualFechaFin);

							}
							if($dataStockFechaFin['final']==null){
								// 2DO CASO
								$queryStockActualFechaFin =	mysqli_query($MySQLi,
								"SELECT
								inicial as 'final'
								FROM
								historial_stock_productos
								WHERE
								idProducto = '$idProducto' AND dateEmission =(
								SELECT
									MIN(dateEmission)
								FROM
									historial_stock_productos
								WHERE
									idProducto = '$idProducto' 
									AND(DATE_FORMAT(dateEmission, '%Y-%m-%d') BETWEEN '$FIN' AND '2040-01-01')
									AND sucursal='Tarija'
									)");
	
								$dataStockFechaFin	=	mysqli_fetch_assoc($queryStockActualFechaFin);

							}

							if($dataStockFechaFin['final']==null){//sino existe tomamos del stock producutso fiscale
								$GranTotalFinal=$GranTotalFinal+$dataProducto['StockTJ'];
								$GranTotalFinalTJ=$GranTotalFinalTJ+$dataProducto['StockTJ'];
								echo $dataProducto['StockTJ'];
							}
							if($dataStockFechaFin['final']!=null)
							{	$GranTotalFinal=$GranTotalFinal+$dataStockFechaFin['final'];
								$GranTotalFinalTJ=$GranTotalFinalTJ+$dataStockFechaFin['final'];
								echo $dataStockFechaFin['final']; }
				?>


            </td>

            <td class="text-center">
                <?php
							if($vendedorArray==null){echo '';}
							else {
								foreach ($vendedorArray as $value) {
									echo $value . '<br>';
								}
							}								
							 ?>
            </td>
            <td class="text-center">
                <?php
							if($dateEmissionArray==null){echo '';}
							else {
								foreach ($dateEmissionArray as $value) {
									echo $value . '<br>';
								}
							}								
							?>
            </td>
            <!-- <td class="text-center"> -->
            <!-- </td> -->
            <td colspan="2" class="text-center">
                <?php
							if($descripcionArray==null){echo '';}
							else {
								foreach ($descripcionArray as $value) {
									echo $value . '<br>';
								}
							}
							?>
            </td>
        </tr>
        <?php $Num++;
			} ?>
        <tr class="odd gradeX">
            <td class="text-center"><?php echo $Num ?></td>

            <th class="text-center">TOTAL</th>
            <th class="text-center"></th>
            <th class="text-center"></th>

            <td>
                <strong>
                    <?php  
                        //echo $GranTotalInicial;
						echo $GranTotalInicialCB;
                    ?>
                </strong>
            </td>
            <td>
                <strong>
                    <?php	echo $GranTotalInicialLP;	?>
                </strong>
            </td>
            <td>
                <strong>
                    <?php	echo $GranTotalInicialSC;	?>
                </strong>
            </td>
			 <td>
                <strong>
                    <?php	echo $GranTotalInicialST;	?>
                </strong>
            </td>
            <td>
                <strong>
                    <?php	echo $GranTotalInicialTJ;	?>
                </strong>
            </td>
            <td class="text-center">
                <strong><span style="color: orange">
                        <?php  
                        $queryTotalIncial	=	mysqli_query($MySQLi,"SELECT SUM(cb) AS cb FROM historial_stock_productos WHERE (DATE_FORMAT(dateEmission, '%Y-%m-%d') BETWEEN '$INICIO' AND '$FIN') ")or die(mysqli_error($MySQLi));
                        $dataVentas		=	mysqli_fetch_assoc($queryTotalIncial);
                        $TotalInicial 	=	$dataVentas['cb'];
                        echo $TotalInicial;
                                ?>
                    </span></strong>
            </td>
            <td class="text-center">
                <strong><span style="color: blue">
                        <?php  
                        $queryTotalIncial	=	mysqli_query($MySQLi,"SELECT SUM(lp) AS lp FROM historial_stock_productos WHERE (DATE_FORMAT(dateEmission, '%Y-%m-%d') BETWEEN '$INICIO' AND '$FIN') ")or die(mysqli_error($MySQLi));
                        $dataVentas		=	mysqli_fetch_assoc($queryTotalIncial);
                        $TotalInicial 	=	$dataVentas['lp'];
                        echo $TotalInicial;
                                ?>
                    </span></strong>
            </td>
            <td class="text-center">
                <strong><span style="color: green">
                        <?php  
                        $queryTotalIncial	=	mysqli_query($MySQLi,"SELECT SUM(sc) AS sc FROM historial_stock_productos WHERE (DATE_FORMAT(dateEmission, '%Y-%m-%d') BETWEEN '$INICIO' AND '$FIN') ")or die(mysqli_error($MySQLi));
                        $dataVentas		=	mysqli_fetch_assoc($queryTotalIncial);
                        $TotalInicial 	=	$dataVentas['sc'];
                        echo $TotalInicial;
                                ?>
                    </span></strong>
            </td>
			 <td class="text-center">
                <strong><span style="color: red">
                        <?php  
                        $queryTotalIncial	=	mysqli_query($MySQLi,"SELECT SUM(st) AS st FROM historial_stock_productos WHERE (DATE_FORMAT(dateEmission, '%Y-%m-%d') BETWEEN '$INICIO' AND '$FIN') ")or die(mysqli_error($MySQLi));
                        $dataVentas		=	mysqli_fetch_assoc($queryTotalIncial);
                        $TotalInicial 	=	$dataVentas['st'];
                        echo $TotalInicial;
                                ?>
                    </span></strong>
            </td>
            <td class="text-center">
                <strong><span style="color: #40CFFF">
                        <?php  
                        $queryTotalIncial	=	mysqli_query($MySQLi,"SELECT SUM(tj) AS tj FROM historial_stock_productos WHERE (DATE_FORMAT(dateEmission, '%Y-%m-%d') BETWEEN '$INICIO' AND '$FIN') ")or die(mysqli_error($MySQLi));
                        $dataVentas		=	mysqli_fetch_assoc($queryTotalIncial);
                        $TotalInicial 	=	$dataVentas['tj'];
                        echo $TotalInicial;
                                ?>
                    </span></strong>
            </td>
            <td class="text-center">
                <strong>
                    <?php 
                        // echo $GranTotalFinal;
						echo $GranTotalFinalCB;
                    ?>
                </strong>
            </td>
            <td>
                <strong>
                    <?php	echo $GranTotalFinalLP;	?>
                </strong>
            </td>
            <td>
                <strong>
                    <?php	echo $GranTotalFinalSC;	?>
                </strong>
            </td>
			<td>
                <strong>
                    <?php	echo $GranTotalFinalST;	?>
                </strong>
            </td>
            <td>
                <strong>
                    <?php	echo $GranTotalFinalTJ;	?>
                </strong>
            </td>
            <td class="text-center"></td>
            <td class="text-center"></td>



        </tr>


    </tbody>
</table>

<?php mysqli_close($MySQLi);
		
	}
		//excel reportes productos REALES sin fecha- totalidad--------------------------------------------------
	elseif (isset($_GET['historial_productos_completo_sin_fechas'])) {
		$Sucursal 	=	$_GET['historial_productos_completo_sin_fechas'];
		$INICIO 		=	'2020-01-01';
		$FIN 		=	'2040-01-01';

		header("Content-type: application/vnd.ms-excel; name='excel'");
		header("Content-Disposition: filename=ListadoGeneralHistorialFiscales.xls");
		header("Pragma: no-cache");
		header("Pragma: no-cache");
		header("Expires: 0");

		 ?>

<table border="1">
    <thead>
        <tr>
            <th colspan="17" style="text-align: center;">
                <h3>LISTADO GENERAL - HISTORIAL PRODUCTOS</span></h3>
            </th>
        </tr>
        <tr>
            <th width="5%" class="text-center">N&ordm;</th>
            <!-- <th width="5%" class="text-center">idProducto</th> -->
            <th class="text-center">PRODUCTO</th>
            <th class="text-center">MARCA</th>
            <th class="text-center">MODELO</th>
            <th colspan="4" width="5%" class="text-center">INICIAL</th>

            <th width="5%" class="text-center btn-warning"><span style="color: orange">CB</span></th>
            <th width="5%" class="text-center btn-primary"><span style="color: blue">LP</span></th>
            <th width="5%" class="text-center btn-success"><span style="color: green">SC</span></th>
            <th width="5%" class="text-center btn-danger"><span style="color: red">ST</span></th>
            <th width="5%" class="text-center btn-info"><span style="color: #40CFFF">TJ</span></th>

            <th colspan="4" width="5%" class="text-center">FINAL</th>
            <th class="text-center">VENDEDOR</th>
            <th class="text-center">FECHA</th>
            <!-- <th class="text-center">#FACTURA</th> -->
            <th colspan="2" class="text-center">DESCRIPCION</th>
        </tr>
    </thead>
    <tbody>
        <?php		
		$query="SELECT * FROM Productos ORDER BY StockTotal ASC";
		$queryProductos	=	mysqli_query($MySQLi,$query);
		$Num=1;

		$GranTotalInicial=0;

		$GranTotalInicialCB=0;
		$GranTotalInicialLP=0;
		$GranTotalInicialSC=0;
		$GranTotalInicialST=0;
		$GranTotalInicialTJ=0;

		$GranTotalFinal=0;

		$GranTotalFinalCB=0;
		$GranTotalFinalLP=0;
		$GranTotalFinalSC=0;
		$GranTotalFinalST=0;
		$GranTotalFinalTJ=0;
		while ($dataProducto = mysqli_fetch_assoc($queryProductos)) {
						
					$idProducto = $dataProducto['idProducto'];
					//inicial final cb,lp,sc,tj
					$queryHistorialProductos =	mysqli_query($MySQLi,
					"SELECT
					MAX(inicial) AS inicial,
    				MIN(final) AS final,
					SUM(cb) AS cb,
					SUM(lp) AS lp,
					SUM(sc) AS sc,
					SUM(st) AS st,
					SUM(tj) AS tj
					FROM
						historial_stock_productos
					WHERE
						idProducto = '$idProducto' AND(
					DATE_FORMAT(dateEmission, '%Y-%m-%d') BETWEEN '$INICIO' AND '$FIN')");

					$dataHistorialProductos	=	mysqli_fetch_assoc($queryHistorialProductos);

					//query vendedor,dateEmission,invoiceNumber,descripcion
					$queryVendedores =	mysqli_query($MySQLi,
					"SELECT
					vendedor,dateEmission,sucursal,descripcion
					FROM
					historial_stock_productos
					WHERE
						idProducto = '$idProducto' AND(
					DATE_FORMAT(dateEmission, '%Y-%m-%d') BETWEEN '$INICIO' AND '$FIN')");

					$vendedorArray=[];
					$dateEmissionArray=[];
					$sucursalArray=[];
					$descripcionArray=[];

					while($dataVendedores	=	mysqli_fetch_assoc($queryVendedores)){
						$vendedorArray[]= $dataVendedores['vendedor'];
						$dateEmissionArray[]= $dataVendedores['dateEmission'];
						$sucursalArray[]= $dataVendedores['sucursal'];
						$descripcionArray[]= $dataVendedores['descripcion'];
					}
		
					?>
        <tr>
            <td class="text-center"><?php echo $Num ?></td>
            <!-- <td class="text-center"><?php //echo $dataProducto['idProducto']; ?></td> -->

            <td class="text-center"><?php echo utf8_decode($dataProducto['Producto'] ); ?></td>
            <td class="text-center"><?php echo utf8_decode($dataProducto['Marca'] ); ?></td>
            <td class="text-center"><?php echo utf8_decode($dataProducto['Modelo'] ); ?></td>
            <td class="text-center">
                <?php 
							//inicial producto su stock mas antiguo con rango de fechas
							//cocha
							$queryStockMasAntiguo =	mysqli_query($MySQLi,
							"SELECT
							inicial
							FROM
							historial_stock_productos
							WHERE
							idProducto = '$idProducto' AND dateEmission =(
							SELECT
								MIN(dateEmission)
							FROM
								historial_stock_productos
							WHERE
								idProducto = '$idProducto' 
								AND(DATE_FORMAT(dateEmission, '%Y-%m-%d') BETWEEN '$INICIO' AND '$FIN') 
								AND sucursal='Cochabamba'
								)");

							$dataStockMasAntiguo	=	mysqli_fetch_assoc($queryStockMasAntiguo);
							

							if($dataStockMasAntiguo['inicial']==null){//no entro a historial

								$GranTotalInicial=$GranTotalInicial+$dataProducto['StockCB'];//tomamos su stock
								$GranTotalInicialCB=$GranTotalInicialCB+$dataProducto['StockCB'];//SOLO CBA
								echo $dataProducto['StockCB'];
								
							}
							else{
								$GranTotalInicial=$GranTotalInicial+$dataStockMasAntiguo['inicial'];//si entro a historial
								$GranTotalInicialCB=$GranTotalInicialCB+$dataStockMasAntiguo['inicial'];
								 echo $dataStockMasAntiguo['inicial']; }//tomamos su valor mas antiguo

			?>
            </td>
            <td>
                <?php
							//la paz
							//inicial producto su stock mas antiguo con rango de fechas
							$queryStockMasAntiguo =	mysqli_query($MySQLi,
							"SELECT
							inicial
							FROM
							historial_stock_productos
							WHERE
							idProducto = '$idProducto' AND dateEmission =(
							SELECT
								MIN(dateEmission)
							FROM
								historial_stock_productos
							WHERE
								idProducto = '$idProducto' 
								AND(DATE_FORMAT(dateEmission, '%Y-%m-%d') BETWEEN '$INICIO' AND '$FIN') 
								AND sucursal='La Paz'
								)");

							$dataStockMasAntiguo	=	mysqli_fetch_assoc($queryStockMasAntiguo);
							

							if($dataStockMasAntiguo['inicial']==null){//no entro a historial

								$GranTotalInicial=$GranTotalInicial+$dataProducto['StockLP'];//tomamos su stock
								$GranTotalInicialLP=$GranTotalInicialLP+$dataProducto['StockLP'];
								echo $dataProducto['StockLP'];
							}
							else{
								$GranTotalInicial=$GranTotalInicial+$dataStockMasAntiguo['inicial'];//si entro a historial
								$GranTotalInicialLP=$GranTotalInicialLP+$dataStockMasAntiguo['inicial'];
								echo $dataStockMasAntiguo['inicial']; }//tomamos su valor mas antiguo


			?>
            </td>
            <td>
                <?php
							///-----------------------------SANTA CRUZ
								
							//inicial producto su stock mas antiguo con rango de fechas
							$queryStockMasAntiguo =	mysqli_query($MySQLi,
							"SELECT
							inicial
							FROM
							historial_stock_productos
							WHERE
							idProducto = '$idProducto' AND dateEmission =(
							SELECT
								MIN(dateEmission)
							FROM
								historial_stock_productos
							WHERE
								idProducto = '$idProducto' 
								AND(DATE_FORMAT(dateEmission, '%Y-%m-%d') BETWEEN '$INICIO' AND '$FIN') 
								AND sucursal='Santa Cruz'
								)");

							$dataStockMasAntiguo	=	mysqli_fetch_assoc($queryStockMasAntiguo);
							

							if($dataStockMasAntiguo['inicial']==null){//no entro a historial

								$GranTotalInicial=$GranTotalInicial+$dataProducto['StockSC'];//tomamos su stock
								$GranTotalInicialSC=$GranTotalInicialSC+$dataProducto['StockSC'];
								echo $dataProducto['StockSC'];
							}
							else{
								$GranTotalInicial=$GranTotalInicial+$dataStockMasAntiguo['inicial'];//si entro a historial
								$GranTotalInicialSC=$GranTotalInicialSC+$dataStockMasAntiguo['inicial'];
								echo $dataStockMasAntiguo['inicial']; }//tomamos su valor mas antiguo

				?>
            </td>
			<!-- Sucursal trompillo -->
			 <td>
                <?php
					///-----------------------------SANTA CRUZ
						
					//inicial producto su stock mas antiguo con rango de fechas
					$queryStockMasAntiguo =	mysqli_query($MySQLi,
					"SELECT
					inicial
					FROM
					historial_stock_productos
					WHERE
					idProducto = '$idProducto' AND dateEmission =(
					SELECT
						MIN(dateEmission)
					FROM
						historial_stock_productos
					WHERE
						idProducto = '$idProducto' 
						AND(DATE_FORMAT(dateEmission, '%Y-%m-%d') BETWEEN '$INICIO' AND '$FIN') 
						AND sucursal='Santa Cruz Trompillo'
						)");

					$dataStockMasAntiguo	=	mysqli_fetch_assoc($queryStockMasAntiguo);
					

					if($dataStockMasAntiguo['inicial']==null){//no entro a historial

						$GranTotalInicial=$GranTotalInicial+$dataProducto['StockST'];//tomamos su stock
						$GranTotalInicialSC=$GranTotalInicialSC+$dataProducto['StockST'];
						echo $dataProducto['StockST'];
					}
					else{
						$GranTotalInicial=$GranTotalInicial+$dataStockMasAntiguo['inicial'];//si entro a historial
						$GranTotalInicialSC=$GranTotalInicialSC+$dataStockMasAntiguo['inicial'];
						echo $dataStockMasAntiguo['inicial']; }//tomamos su valor mas antiguo

				?>
            </td>
            <td>
                <?php

								///-----------------------------TARIJA
								
								//inicial producto su stock mas antiguo con rango de fechas
								$queryStockMasAntiguo =	mysqli_query($MySQLi,
								"SELECT
								inicial
								FROM
								historial_stock_productos
								WHERE
								idProducto = '$idProducto' AND dateEmission =(
								SELECT
									MIN(dateEmission)
								FROM
									historial_stock_productos
								WHERE
									idProducto = '$idProducto' 
									AND(DATE_FORMAT(dateEmission, '%Y-%m-%d') BETWEEN '$INICIO' AND '$FIN') 
									AND sucursal='Tarija'
									)");
	
								$dataStockMasAntiguo	=	mysqli_fetch_assoc($queryStockMasAntiguo);
								
	
								if($dataStockMasAntiguo['inicial']==null){//no entro a historial
	
									$GranTotalInicial=$GranTotalInicial+$dataProducto['StockTJ'];//tomamos su stock
									$GranTotalInicialTJ=$GranTotalInicialTJ+$dataProducto['StockTJ'];
									echo $dataProducto['StockTJ'];
								}
								else{
									$GranTotalInicial=$GranTotalInicial+$dataStockMasAntiguo['inicial'];//si entro a historial
									$GranTotalInicialTJ=$GranTotalInicialTJ+$dataStockMasAntiguo['inicial'];
									echo $dataStockMasAntiguo['inicial']; }//tomamos su valor mas antiguo

						
			?>
            </td>

            <td class="text-center">
                <strong>
                    <?php 
							if($dataHistorialProductos['cb']==null){
								echo '0';
							}else{
							echo $dataHistorialProductos['cb'];
							}
							 ?>
                </strong>
            </td>
            <td class="text-center">
                <strong>
                    <?php 
							if($dataHistorialProductos['lp']==null){
								echo '0';
							}else{
							echo $dataHistorialProductos['lp'];
							}
							 ?>
                </strong>
            </td>
            <td class="text-center">
                <strong>
                    <?php 
							if($dataHistorialProductos['sc']==null){
								echo '0';
							}else{
							echo $dataHistorialProductos['sc'];
							}
							 ?>
                </strong>
            </td>
			<td class="text-center">
                <strong>
                    <?php 
							if($dataHistorialProductos['st']==null){
								echo '0';
							}else{
							echo $dataHistorialProductos['st'];
							}
							 ?>
                </strong>
            </td>
            <td class="text-center">
                <strong>
                    <?php 
							if($dataHistorialProductos['tj']==null){
								echo '0';
							}else{
							echo $dataHistorialProductos['tj'];
							}
							 ?>
                </strong>
            </td>

            <td class="text-center">
                <?php 
							//final producto CON RANGO DE FECHAS
							$queryStockActualFechaFin =	mysqli_query($MySQLi,
							"SELECT
							final
							FROM
							historial_stock_productos
							WHERE
							idProducto = '$idProducto' AND dateEmission =(
							SELECT
								MAX(dateEmission)
							FROM
								historial_stock_productos
							WHERE
								idProducto = '$idProducto' 
								AND(DATE_FORMAT(dateEmission, '%Y-%m-%d') BETWEEN '$INICIO' AND '$FIN')
								AND sucursal='Cochabamba'
								)");

							$dataStockFechaFin	=	mysqli_fetch_assoc($queryStockActualFechaFin);

							if($dataStockFechaFin['final']==null){//sino existe tomamos del stock producutso fiscale
								$GranTotalFinal=$GranTotalFinal+$dataProducto['StockCB'];
								$GranTotalFinalCB=$GranTotalFinalCB+$dataProducto['StockCB'];
								echo $dataProducto['StockCB'];
							}else
							{	$GranTotalFinal=$GranTotalFinal+$dataStockFechaFin['final'];
								$GranTotalFinalCB=$GranTotalFinalCB+$dataStockFechaFin['final'];
								echo $dataStockFechaFin['final']; }
				?>
            </td>
            <td>
                <?php 
							//final producto CON RANGO DE FECHAS lapaz
							$queryStockActualFechaFin =	mysqli_query($MySQLi,
							"SELECT
							final
							FROM
							historial_stock_productos
							WHERE
							idProducto = '$idProducto' AND dateEmission =(
							SELECT
								MAX(dateEmission)
							FROM
								historial_stock_productos
							WHERE
								idProducto = '$idProducto' 
								AND(DATE_FORMAT(dateEmission, '%Y-%m-%d') BETWEEN '$INICIO' AND '$FIN')
								AND sucursal='La Paz'
								)");

							$dataStockFechaFin	=	mysqli_fetch_assoc($queryStockActualFechaFin);

							if($dataStockFechaFin['final']==null){//sino existe tomamos del stock producutso fiscale
								$GranTotalFinal=$GranTotalFinal+$dataProducto['StockLP'];
								$GranTotalFinalLP=$GranTotalFinalLP+$dataProducto['StockLP'];
								echo $dataProducto['StockLP'];
							}else
							{	$GranTotalFinal=$GranTotalFinal+$dataStockFechaFin['final'];
								$GranTotalFinalLP=$GranTotalFinalLP+$dataStockFechaFin['final'];
								echo $dataStockFechaFin['final']; }
				?>
            </td>
            <td>
                <?php 
							//final producto CON RANGO DE FECHAS santacruz
							$queryStockActualFechaFin =	mysqli_query($MySQLi,
							"SELECT
							final
							FROM
							historial_stock_productos
							WHERE
							idProducto = '$idProducto' AND dateEmission =(
							SELECT
								MAX(dateEmission)
							FROM
								historial_stock_productos
							WHERE
								idProducto = '$idProducto' 
								AND(DATE_FORMAT(dateEmission, '%Y-%m-%d') BETWEEN '$INICIO' AND '$FIN')
								AND sucursal='Santa Cruz'
								)");

							$dataStockFechaFin	=	mysqli_fetch_assoc($queryStockActualFechaFin);

							if($dataStockFechaFin['final']==null){//sino existe tomamos del stock producutso fiscale
								$GranTotalFinal=$GranTotalFinal+$dataProducto['StockSC'];
								$GranTotalFinalSC=$GranTotalFinalSC+$dataProducto['StockSC'];
								echo $dataProducto['StockSC'];
							}else
							{	$GranTotalFinal=$GranTotalFinal+$dataStockFechaFin['final'];
								$GranTotalFinalSC=$GranTotalFinalSC+$dataStockFechaFin['final'];
								echo $dataStockFechaFin['final']; }
				?>
            </td>
			<!-- Sucursal trompillo -->
			<td>
                <?php 
							//final producto CON RANGO DE FECHAS 
							$queryStockActualFechaFin =	mysqli_query($MySQLi,
							"SELECT
							final
							FROM
							historial_stock_productos
							WHERE
							idProducto = '$idProducto' AND dateEmission =(
							SELECT
								MAX(dateEmission)
							FROM
								historial_stock_productos
							WHERE
								idProducto = '$idProducto' 
								AND(DATE_FORMAT(dateEmission, '%Y-%m-%d') BETWEEN '$INICIO' AND '$FIN')
								AND sucursal='Santa Cruz Trompillo'
								)");

							$dataStockFechaFin	=	mysqli_fetch_assoc($queryStockActualFechaFin);

							if($dataStockFechaFin['final']==null){//sino existe tomamos del stock producutso fiscale
								$GranTotalFinal=$GranTotalFinal+$dataProducto['StockST'];
								$GranTotalFinalSC=$GranTotalFinalSC+$dataProducto['StockST'];
								echo $dataProducto['StockST'];
							}else
							{	$GranTotalFinal=$GranTotalFinal+$dataStockFechaFin['final'];
								$GranTotalFinalSC=$GranTotalFinalSC+$dataStockFechaFin['final'];
								echo $dataStockFechaFin['final']; }
				?>
            </td>
            <td>
                <?php 
							//final producto CON RANGO DE FECHAS tarija
							$queryStockActualFechaFin =	mysqli_query($MySQLi,
							"SELECT
							final
							FROM
							historial_stock_productos
							WHERE
							idProducto = '$idProducto' AND dateEmission =(
							SELECT
								MAX(dateEmission)
							FROM
								historial_stock_productos
							WHERE
								idProducto = '$idProducto' 
								AND(DATE_FORMAT(dateEmission, '%Y-%m-%d') BETWEEN '$INICIO' AND '$FIN')
								AND sucursal='Tarija'
								)");

							$dataStockFechaFin	=	mysqli_fetch_assoc($queryStockActualFechaFin);

							if($dataStockFechaFin['final']==null){//sino existe tomamos del stock producutso fiscale
								$GranTotalFinal=$GranTotalFinal+$dataProducto['StockTJ'];
								$GranTotalFinalTJ=$GranTotalFinalTJ+$dataProducto['StockTJ'];
								echo $dataProducto['StockTJ'];
							}else
							{	$GranTotalFinal=$GranTotalFinal+$dataStockFechaFin['final'];
								$GranTotalFinalTJ=$GranTotalFinalTJ+$dataStockFechaFin['final'];
								echo $dataStockFechaFin['final']; }
				?>


            </td>

            <td class="text-center">
                <?php
							if($vendedorArray==null){echo '';}
							else {
								foreach ($vendedorArray as $value) {
									echo $value . '<br>';
								}
							}								
							 ?>
            </td>
            <td class="text-center">
                <?php
							if($dateEmissionArray==null){echo '';}
							else {
								foreach ($dateEmissionArray as $value) {
									echo $value . '<br>';
								}
							}								
							?>
            </td>
            <!-- <td class="text-center"> -->
            <!-- </td> -->
            <td colspan="2" class="text-center">
                <?php
							if($descripcionArray==null){echo '';}
							else {
								foreach ($descripcionArray as $value) {
									echo $value . '<br>';
								}
							}
							?>
            </td>
        </tr>
        <?php $Num++;
			} ?>
        <tr class="odd gradeX">
            <td class="text-center"><?php echo $Num ?></td>

            <th class="text-center">TOTAL</th>
            <th class="text-center"></th>
            <th class="text-center"></th>

            <td>
                <strong>
                    <?php  
                        //echo $GranTotalInicial;
						echo $GranTotalInicialCB;
                    ?>
                </strong>
            </td>
            <td>
                <strong>
                    <?php	echo $GranTotalInicialLP;	?>
                </strong>
            </td>
            <td>
                <strong>
                    <?php	echo $GranTotalInicialSC;	?>
                </strong>
            </td>
			<td>
                <strong>
                    <?php	echo $GranTotalInicialST;	?>
                </strong>
            </td>
            <td>
                <strong>
                    <?php	echo $GranTotalInicialTJ;	?>
                </strong>
            </td>
            <td class="text-center">
                <strong><span style="color: orange">
                        <?php  
                        $queryTotalIncial	=	mysqli_query($MySQLi,"SELECT SUM(cb) AS cb FROM historial_stock_productos WHERE (DATE_FORMAT(dateEmission, '%Y-%m-%d') BETWEEN '$INICIO' AND '$FIN') ")or die(mysqli_error($MySQLi));
                        $dataVentas		=	mysqli_fetch_assoc($queryTotalIncial);
                        $TotalInicial 	=	$dataVentas['cb'];
                        echo $TotalInicial;
                                ?>
                    </span></strong>
            </td>
            <td class="text-center">
                <strong><span style="color: blue">
                        <?php  
                        $queryTotalIncial	=	mysqli_query($MySQLi,"SELECT SUM(lp) AS lp FROM historial_stock_productos WHERE (DATE_FORMAT(dateEmission, '%Y-%m-%d') BETWEEN '$INICIO' AND '$FIN') ")or die(mysqli_error($MySQLi));
                        $dataVentas		=	mysqli_fetch_assoc($queryTotalIncial);
                        $TotalInicial 	=	$dataVentas['lp'];
                        echo $TotalInicial;
                                ?>
                    </span></strong>
            </td>
            <td class="text-center">
                <strong><span style="color: green">
                        <?php  
                        $queryTotalIncial	=	mysqli_query($MySQLi,"SELECT SUM(sc) AS sc FROM historial_stock_productos WHERE (DATE_FORMAT(dateEmission, '%Y-%m-%d') BETWEEN '$INICIO' AND '$FIN') ")or die(mysqli_error($MySQLi));
                        $dataVentas		=	mysqli_fetch_assoc($queryTotalIncial);
                        $TotalInicial 	=	$dataVentas['sc'];
                        echo $TotalInicial;
                                ?>
                    </span></strong>
            </td>
			<td class="text-center">
                <strong><span style="color: green">
                        <?php  
                        $queryTotalIncial	=	mysqli_query($MySQLi,"SELECT SUM(st) AS st FROM historial_stock_productos WHERE (DATE_FORMAT(dateEmission, '%Y-%m-%d') BETWEEN '$INICIO' AND '$FIN') ")or die(mysqli_error($MySQLi));
                        $dataVentas		=	mysqli_fetch_assoc($queryTotalIncial);
                        $TotalInicial 	=	$dataVentas['st'];
                        echo $TotalInicial;
                                ?>
                    </span></strong>
            </td>
            <td class="text-center">
                <strong><span style="color: #40CFFF">
                        <?php  
                        $queryTotalIncial	=	mysqli_query($MySQLi,"SELECT SUM(tj) AS tj FROM historial_stock_productos WHERE (DATE_FORMAT(dateEmission, '%Y-%m-%d') BETWEEN '$INICIO' AND '$FIN') ")or die(mysqli_error($MySQLi));
                        $dataVentas		=	mysqli_fetch_assoc($queryTotalIncial);
                        $TotalInicial 	=	$dataVentas['tj'];
                        echo $TotalInicial;
                                ?>
                    </span></strong>
            </td>
            <td class="text-center">
                <strong>
                    <?php 
                        // echo $GranTotalFinal;
						echo $GranTotalFinalCB;
                    ?>
                </strong>
            </td>
            <td>
                <strong>
                    <?php	echo $GranTotalFinalLP;	?>
                </strong>
            </td>
            <td>
                <strong>
                    <?php	echo $GranTotalFinalSC;	?>
                </strong>
            </td>
			<td>
                <strong>
                    <?php	echo $GranTotalFinalST;	?>
                </strong>
            </td>
            <td>
                <strong>
                    <?php	echo $GranTotalFinalTJ;	?>
                </strong>
            </td>
            <td class="text-center"></td>
            <td class="text-center"></td>



        </tr>


    </tbody>
</table>





<?php mysqli_close($MySQLi);
		
	}
	
	//// ---------------------------REPORTES ENVIOS CON RANGO DE FECHAS
	//excel reportes ENVIOS reales con fecha inicio y fin CON RANGO DE FECHAS
	elseif (isset($_GET['historial_envios_completo_con_fechas'])AND isset($_GET['fechaInicio'])AND isset($_GET['fechafin'])) {
		$Sucursal 	=	$_GET['historial_envios_completo_con_fechas'];
		$INICIO 		=	$_GET['fechaInicio'];
		$FIN 		=	$_GET['fechafin'];

		header("Content-type: application/vnd.ms-excel; name='excel'");
		header("Content-Disposition: filename=ListadoProductosRangoFechas".$INICIO."__".$FIN.".xls");
		header("Pragma: no-cache");
		header("Pragma: no-cache");
		header("Expires: 0");

		 ?>

<table border="1">
    <thead>
        <tr>
            <th colspan="14" style="text-align: center;">
                <h3>HISTORIAL ENVIOS DESDE EL <span style="color: green"><?php echo $INICIO ?></span> HASTA EL <span
                        style="color: red"><?php echo $FIN ?></span></h3>
            </th>
        </tr>
        <tr>
            <th width="5%" class="text-center">N&ordm;</th>
            <!-- <th width="5%" class="text-center">idProducto</th> -->
            <th class="text-center">PRODUCTO </th>
            <th class="text-center">MARCA</th>
            <th class="text-center">MODELO</th>
            <th colspan="4" width="5%" class="text-center">INICIAL</th>

            <th width="5%" class="text-center btn-warning"><span style="color: orange">CB</span></th>
            <th width="5%" class="text-center btn-primary"><span style="color: blue">LP</span></th>
            <th width="5%" class="text-center btn-success"><span style="color: green">SC</span></th>
            <th width="5%" class="text-center btn-info"><span style="color: #40CFFF">TJ</span></th>

            <th colspan="4" width="5%" class="text-center">FINAL</th>
            <th class="text-center">VENDEDOR</th>
            <th class="text-center">FECHA</th>
            <!-- <th class="text-center">#FACTURA</th> -->
            <th colspan="2" class="text-center">DESCRIPCION</th>
        </tr>
    </thead>
    <tbody>
        <?php		
		$query="SELECT * FROM Productos ORDER BY StockTotal ASC";
		$queryProductos	=	mysqli_query($MySQLi,$query);
		$Num=1;

		$GranTotalInicial=0;

		$GranTotalInicialCB=0;
		$GranTotalInicialLP=0;
		$GranTotalInicialSC=0;
		$GranTotalInicialTJ=0;

		$GranTotalFinal=0;

		$GranTotalFinalCB=0;
		$GranTotalFinalLP=0;
		$GranTotalFinalSC=0;
		$GranTotalFinalTJ=0;
		while ($dataProducto = mysqli_fetch_assoc($queryProductos)) {
						
					$idProducto = $dataProducto['idProducto'];
					//inicial final cb,lp,sc,tj
					$queryHistorialProductos =	mysqli_query($MySQLi,
					"SELECT
					MAX(inicial) AS inicial,
    				MIN(final) AS final,
					SUM(cb) AS cb,
					SUM(lp) AS lp,
					SUM(sc) AS sc,
					SUM(tj) AS tj
					FROM
					historial_stock_envios
					WHERE
						idProducto = '$idProducto' AND(
					DATE_FORMAT(dateEmission, '%Y-%m-%d') BETWEEN '$INICIO' AND '$FIN')");

					$dataHistorialProductos	=	mysqli_fetch_assoc($queryHistorialProductos);

					//query vendedor,dateEmission,invoiceNumber,descripcion
					$queryVendedores =	mysqli_query($MySQLi,
					"SELECT
					vendedor,dateEmission,sucursal,descripcion
					FROM
					historial_stock_envios
					WHERE
						idProducto = '$idProducto' AND(
					DATE_FORMAT(dateEmission, '%Y-%m-%d') BETWEEN '$INICIO' AND '$FIN')");

					$vendedorArray=[];
					$dateEmissionArray=[];
					$sucursalArray=[];
					$descripcionArray=[];

					while($dataVendedores	=	mysqli_fetch_assoc($queryVendedores)){
						$vendedorArray[]= $dataVendedores['vendedor'];
						$dateEmissionArray[]= $dataVendedores['dateEmission'];
						$sucursalArray[]= $dataVendedores['sucursal'];
						$descripcionArray[]= $dataVendedores['descripcion'];
					}
		
					?>
        <tr>
            <td class="text-center"><?php echo $Num ?></td>
            <!-- <td class="text-center"><?php //echo $dataProducto['idProducto']; ?></td> -->

            <td class="text-center"><?php echo utf8_decode($dataProducto['Producto'] ); ?></td>
            <td class="text-center"><?php echo utf8_decode($dataProducto['Marca'] ); ?></td>
            <td class="text-center"><?php echo utf8_decode($dataProducto['Modelo'] ); ?></td>
            <td class="text-center">
                <?php 
							//inicial producto su stock mas antiguo con rango de fechas
							//cocha
							$queryStockMasAntiguo =	mysqli_query($MySQLi,
							"SELECT
							inicial
							FROM
							historial_stock_envios
							WHERE
							idProducto = '$idProducto' AND dateEmission =(
							SELECT
								MIN(dateEmission)
							FROM
							historial_stock_envios
							WHERE
								idProducto = '$idProducto' 
								AND(DATE_FORMAT(dateEmission, '%Y-%m-%d') BETWEEN '$INICIO' AND '$FIN') 
								AND sucursal='Cochabamba'
								)");

							$dataStockMasAntiguo	=	mysqli_fetch_assoc($queryStockMasAntiguo);
							

							if($dataStockMasAntiguo['inicial']==null){//no entro a historial

								$GranTotalInicial=$GranTotalInicial+$dataProducto['StockCB'];//tomamos su stock
								$GranTotalInicialCB=$GranTotalInicialCB+$dataProducto['StockCB'];//SOLO CBA
								echo $dataProducto['StockCB'];
								
							}
							else{
								$GranTotalInicial=$GranTotalInicial+$dataStockMasAntiguo['inicial'];//si entro a historial
								$GranTotalInicialCB=$GranTotalInicialCB+$dataStockMasAntiguo['inicial'];
								 echo $dataStockMasAntiguo['inicial']; }//tomamos su valor mas antiguo

			?>
            </td>
            <td>
                <?php
							//la paz
							//inicial producto su stock mas antiguo con rango de fechas
							$queryStockMasAntiguo =	mysqli_query($MySQLi,
							"SELECT
							inicial
							FROM
							historial_stock_envios
							WHERE
							idProducto = '$idProducto' AND dateEmission =(
							SELECT
								MIN(dateEmission)
							FROM
							historial_stock_envios
							WHERE
								idProducto = '$idProducto' 
								AND(DATE_FORMAT(dateEmission, '%Y-%m-%d') BETWEEN '$INICIO' AND '$FIN') 
								AND sucursal='La Paz'
								)");

							$dataStockMasAntiguo	=	mysqli_fetch_assoc($queryStockMasAntiguo);
							

							if($dataStockMasAntiguo['inicial']==null){//no entro a historial

								$GranTotalInicial=$GranTotalInicial+$dataProducto['StockLP'];//tomamos su stock
								$GranTotalInicialLP=$GranTotalInicialLP+$dataProducto['StockLP'];
								echo $dataProducto['StockLP'];
							}
							else{
								$GranTotalInicial=$GranTotalInicial+$dataStockMasAntiguo['inicial'];//si entro a historial
								$GranTotalInicialLP=$GranTotalInicialLP+$dataStockMasAntiguo['inicial'];
								echo $dataStockMasAntiguo['inicial']; }//tomamos su valor mas antiguo


			?>
            </td>
            <td>
                <?php
///-----------------------------SANTA CRUZ
								
							//inicial producto su stock mas antiguo con rango de fechas
							$queryStockMasAntiguo =	mysqli_query($MySQLi,
							"SELECT
							inicial
							FROM
							historial_stock_envios
							WHERE
							idProducto = '$idProducto' AND dateEmission =(
							SELECT
								MIN(dateEmission)
							FROM
							historial_stock_envios
							WHERE
								idProducto = '$idProducto' 
								AND(DATE_FORMAT(dateEmission, '%Y-%m-%d') BETWEEN '$INICIO' AND '$FIN') 
								AND sucursal='Santa Cruz'
								)");

							$dataStockMasAntiguo	=	mysqli_fetch_assoc($queryStockMasAntiguo);
							

							if($dataStockMasAntiguo['inicial']==null){//no entro a historial

								$GranTotalInicial=$GranTotalInicial+$dataProducto['StockSC'];//tomamos su stock
								$GranTotalInicialSC=$GranTotalInicialSC+$dataProducto['StockSC'];
								echo $dataProducto['StockSC'];
							}
							else{
								$GranTotalInicial=$GranTotalInicial+$dataStockMasAntiguo['inicial'];//si entro a historial
								$GranTotalInicialSC=$GranTotalInicialSC+$dataStockMasAntiguo['inicial'];
								echo $dataStockMasAntiguo['inicial']; }//tomamos su valor mas antiguo

			?>
            </td>
            <td>
                <?php

								///-----------------------------TARIJA
								
								//inicial producto su stock mas antiguo con rango de fechas
								$queryStockMasAntiguo =	mysqli_query($MySQLi,
								"SELECT
								inicial
								FROM
								historial_stock_envios
								WHERE
								idProducto = '$idProducto' AND dateEmission =(
								SELECT
									MIN(dateEmission)
								FROM
								historial_stock_envios
								WHERE
									idProducto = '$idProducto' 
									AND(DATE_FORMAT(dateEmission, '%Y-%m-%d') BETWEEN '$INICIO' AND '$FIN') 
									AND sucursal='Tarija'
									)");
	
								$dataStockMasAntiguo	=	mysqli_fetch_assoc($queryStockMasAntiguo);
								
	
								if($dataStockMasAntiguo['inicial']==null){//no entro a historial
	
									$GranTotalInicial=$GranTotalInicial+$dataProducto['StockTJ'];//tomamos su stock
									$GranTotalInicialTJ=$GranTotalInicialTJ+$dataProducto['StockTJ'];
									echo $dataProducto['StockTJ'];
								}
								else{
									$GranTotalInicial=$GranTotalInicial+$dataStockMasAntiguo['inicial'];//si entro a historial
									$GranTotalInicialTJ=$GranTotalInicialTJ+$dataStockMasAntiguo['inicial'];
									echo $dataStockMasAntiguo['inicial']; }//tomamos su valor mas antiguo

						
			?>
            </td>

            <td class="text-center">
                <strong>
                    <?php 
							if($dataHistorialProductos['cb']==null){
								echo '0';
							}else{
							echo $dataHistorialProductos['cb'];
							}
							 ?>
                </strong>
            </td>
            <td class="text-center">
                <strong>
                    <?php 
							if($dataHistorialProductos['lp']==null){
								echo '0';
							}else{
							echo $dataHistorialProductos['lp'];
							}
							 ?>
                </strong>
            </td>
            <td class="text-center">
                <strong>
                    <?php 
							if($dataHistorialProductos['sc']==null){
								echo '0';
							}else{
							echo $dataHistorialProductos['sc'];
							}
							 ?>
                </strong>
            </td>
            <td class="text-center">
                <strong>
                    <?php 
							if($dataHistorialProductos['tj']==null){
								echo '0';
							}else{
							echo $dataHistorialProductos['tj'];
							}
							 ?>
                </strong>
            </td>

            <td class="text-center">
                <?php 
							//final producto CON RANGO DE FECHAS
							$queryStockActualFechaFin =	mysqli_query($MySQLi,
							"SELECT
							final
							FROM
							historial_stock_envios
							WHERE
							idProducto = '$idProducto' AND dateEmission =(
							SELECT
								MAX(dateEmission)
							FROM
							historial_stock_envios
							WHERE
								idProducto = '$idProducto' 
								AND(DATE_FORMAT(dateEmission, '%Y-%m-%d') BETWEEN '$INICIO' AND '$FIN')
								AND sucursal='Cochabamba'
								)");

							$dataStockFechaFin	=	mysqli_fetch_assoc($queryStockActualFechaFin);

							if($dataStockFechaFin['final']==null){//sino existe tomamos del stock producutso fiscale
								$GranTotalFinal=$GranTotalFinal+$dataProducto['StockCB'];
								$GranTotalFinalCB=$GranTotalFinalCB+$dataProducto['StockCB'];
								echo $dataProducto['StockCB'];
							}else
							{	$GranTotalFinal=$GranTotalFinal+$dataStockFechaFin['final'];
								$GranTotalFinalCB=$GranTotalFinalCB+$dataStockFechaFin['final'];
								echo $dataStockFechaFin['final']; }
				?>
            </td>
            <td>
                <?php 
							//final producto CON RANGO DE FECHAS lapaz
							$queryStockActualFechaFin =	mysqli_query($MySQLi,
							"SELECT
							final
							FROM
							historial_stock_envios
							WHERE
							idProducto = '$idProducto' AND dateEmission =(
							SELECT
								MAX(dateEmission)
							FROM
							historial_stock_envios
							WHERE
								idProducto = '$idProducto' 
								AND(DATE_FORMAT(dateEmission, '%Y-%m-%d') BETWEEN '$INICIO' AND '$FIN')
								AND sucursal='La Paz'
								)");

							$dataStockFechaFin	=	mysqli_fetch_assoc($queryStockActualFechaFin);

							if($dataStockFechaFin['final']==null){//sino existe tomamos del stock producutso fiscale
								$GranTotalFinal=$GranTotalFinal+$dataProducto['StockLP'];
								$GranTotalFinalLP=$GranTotalFinalLP+$dataProducto['StockLP'];
								echo $dataProducto['StockLP'];
							}else
							{	$GranTotalFinal=$GranTotalFinal+$dataStockFechaFin['final'];
								$GranTotalFinalLP=$GranTotalFinalLP+$dataStockFechaFin['final'];
								echo $dataStockFechaFin['final']; }
				?>
            </td>
            <td>
                <?php 
							//final producto CON RANGO DE FECHAS santacruz
							$queryStockActualFechaFin =	mysqli_query($MySQLi,
							"SELECT
							final
							FROM
							historial_stock_envios
							WHERE
							idProducto = '$idProducto' AND dateEmission =(
							SELECT
								MAX(dateEmission)
							FROM
							historial_stock_envios
							WHERE
								idProducto = '$idProducto' 
								AND(DATE_FORMAT(dateEmission, '%Y-%m-%d') BETWEEN '$INICIO' AND '$FIN')
								AND sucursal='Santa Cruz'
								)");

							$dataStockFechaFin	=	mysqli_fetch_assoc($queryStockActualFechaFin);

							if($dataStockFechaFin['final']==null){//sino existe tomamos del stock producutso fiscale
								$GranTotalFinal=$GranTotalFinal+$dataProducto['StockSC'];
								$GranTotalFinalSC=$GranTotalFinalSC+$dataProducto['StockSC'];
								echo $dataProducto['StockSC'];
							}else
							{	$GranTotalFinal=$GranTotalFinal+$dataStockFechaFin['final'];
								$GranTotalFinalSC=$GranTotalFinalSC+$dataStockFechaFin['final'];
								echo $dataStockFechaFin['final']; }
				?>
            </td>
            <td>
                <?php 
							//final producto CON RANGO DE FECHAS tarija
							$queryStockActualFechaFin =	mysqli_query($MySQLi,
							"SELECT
							final
							FROM
							historial_stock_envios
							WHERE
							idProducto = '$idProducto' AND dateEmission =(
							SELECT
								MAX(dateEmission)
							FROM
							historial_stock_envios
							WHERE
								idProducto = '$idProducto' 
								AND(DATE_FORMAT(dateEmission, '%Y-%m-%d') BETWEEN '$INICIO' AND '$FIN')
								AND sucursal='Tarija'
								)");

							$dataStockFechaFin	=	mysqli_fetch_assoc($queryStockActualFechaFin);

							if($dataStockFechaFin['final']==null){//sino existe tomamos del stock producutso fiscale
								$GranTotalFinal=$GranTotalFinal+$dataProducto['StockTJ'];
								$GranTotalFinalTJ=$GranTotalFinalTJ+$dataProducto['StockTJ'];
								echo $dataProducto['StockTJ'];
							}else
							{	$GranTotalFinal=$GranTotalFinal+$dataStockFechaFin['final'];
								$GranTotalFinalTJ=$GranTotalFinalTJ+$dataStockFechaFin['final'];
								echo $dataStockFechaFin['final']; }
				?>


            </td>

            <td class="text-center">
                <?php
							if($vendedorArray==null){echo '';}
							else {
								foreach ($vendedorArray as $value) {
									echo $value . '<br>';
								}
							}								
							 ?>
            </td>
            <td class="text-center">
                <?php
							if($dateEmissionArray==null){echo '';}
							else {
								foreach ($dateEmissionArray as $value) {
									echo $value . '<br>';
								}
							}								
							?>
            </td>
            <!-- <td class="text-center"> -->
            <!-- </td> -->
            <td colspan="2" class="text-center">
                <?php
							if($descripcionArray==null){echo '';}
							else {
								foreach ($descripcionArray as $value) {
									echo $value . '<br>';
								}
							}
							?>
            </td>
        </tr>
        <?php $Num++;
			} ?>
        <tr class="odd gradeX">
            <td class="text-center"><?php echo $Num ?></td>

            <th class="text-center">TOTAL</th>
            <th class="text-center"></th>
            <th class="text-center"></th>

            <td>
                <strong>
                    <?php  
                        //echo $GranTotalInicial;
						echo $GranTotalInicialCB;
                    ?>
                </strong>
            </td>
            <td>
                <strong>
                    <?php	echo $GranTotalInicialLP;	?>
                </strong>
            </td>
            <td>
                <strong>
                    <?php	echo $GranTotalInicialSC;	?>
                </strong>
            </td>
            <td>
                <strong>
                    <?php	echo $GranTotalInicialTJ;	?>
                </strong>
            </td>
            <td class="text-center">
                <strong><span style="color: orange">
                        <?php  
                        $queryTotalIncial	=	mysqli_query($MySQLi,"SELECT SUM(cb) AS cb FROM historial_stock_envios WHERE (DATE_FORMAT(dateEmission, '%Y-%m-%d') BETWEEN '$INICIO' AND '$FIN') ")or die(mysqli_error($MySQLi));
                        $dataVentas		=	mysqli_fetch_assoc($queryTotalIncial);
                        $TotalInicial 	=	$dataVentas['cb'];
                        echo $TotalInicial;
                                ?>
                    </span></strong>
            </td>
            <td class="text-center">
                <strong><span style="color: blue">
                        <?php  
                        $queryTotalIncial	=	mysqli_query($MySQLi,"SELECT SUM(lp) AS lp FROM historial_stock_envios WHERE (DATE_FORMAT(dateEmission, '%Y-%m-%d') BETWEEN '$INICIO' AND '$FIN') ")or die(mysqli_error($MySQLi));
                        $dataVentas		=	mysqli_fetch_assoc($queryTotalIncial);
                        $TotalInicial 	=	$dataVentas['lp'];
                        echo $TotalInicial;
                                ?>
                    </span></strong>
            </td>
            <td class="text-center">
                <strong><span style="color: green">
                        <?php  
                        $queryTotalIncial	=	mysqli_query($MySQLi,"SELECT SUM(sc) AS sc FROM historial_stock_envios WHERE (DATE_FORMAT(dateEmission, '%Y-%m-%d') BETWEEN '$INICIO' AND '$FIN') ")or die(mysqli_error($MySQLi));
                        $dataVentas		=	mysqli_fetch_assoc($queryTotalIncial);
                        $TotalInicial 	=	$dataVentas['sc'];
                        echo $TotalInicial;
                                ?>
                    </span></strong>
            </td>
            <td class="text-center">
                <strong><span style="color: #40CFFF">
                        <?php  
                        $queryTotalIncial	=	mysqli_query($MySQLi,"SELECT SUM(tj) AS tj FROM historial_stock_envios WHERE (DATE_FORMAT(dateEmission, '%Y-%m-%d') BETWEEN '$INICIO' AND '$FIN') ")or die(mysqli_error($MySQLi));
                        $dataVentas		=	mysqli_fetch_assoc($queryTotalIncial);
                        $TotalInicial 	=	$dataVentas['tj'];
                        echo $TotalInicial;
                                ?>
                    </span></strong>
            </td>
            <td class="text-center">
                <strong>
                    <?php 
                        // echo $GranTotalFinal;
						echo $GranTotalFinalCB;
                    ?>
                </strong>
            </td>
            <td>
                <strong>
                    <?php	echo $GranTotalFinalLP;	?>
                </strong>
            </td>
            <td>
                <strong>
                    <?php	echo $GranTotalFinalSC;	?>
                </strong>
            </td>
            <td>
                <strong>
                    <?php	echo $GranTotalFinalTJ;	?>
                </strong>
            </td>
            <td class="text-center"></td>
            <td class="text-center"></td>



        </tr>


    </tbody>
</table>

<?php mysqli_close($MySQLi);
		
	}
		//excel reportes REPORTES ENVIOS CON RANGO DE FECHAS sin fecha- totalidad--------------------------------------------------
	elseif (isset($_GET['historial_envios_completo_sin_fechas'])) {
		$Sucursal 	=	$_GET['historial_envios_completo_sin_fechas'];
		$INICIO 		=	'2020-01-01';
		$FIN 		=	'2040-01-01';

		header("Content-type: application/vnd.ms-excel; name='excel'");
		header("Content-Disposition: filename=ListadoGeneralHistorialFiscales.xls");
		header("Pragma: no-cache");
		header("Pragma: no-cache");
		header("Expires: 0");

		 ?>

<table border="1">
    <thead>
        <tr>
            <th colspan="17" style="text-align: center;">
                <h3>LISTADO GENERAL HISTORIAL ENVIOS - RECIBOS</span></h3>
            </th>
        </tr>
        <tr>
            <th width="5%" class="text-center">N&ordm;</th>
            <!-- <th width="5%" class="text-center">idProducto</th> -->
            <th class="text-center">PRODUCTO </th>
            <th class="text-center">MARCA</th>
            <th class="text-center">MODELO</th>
            <th colspan="4" width="5%" class="text-center">INICIAL</th>

            <th width="5%" class="text-center btn-warning"><span style="color: orange">CB</span></th>
            <th width="5%" class="text-center btn-primary"><span style="color: blue">LP</span></th>
            <th width="5%" class="text-center btn-success"><span style="color: green">SC</span></th>
            <th width="5%" class="text-center btn-info"><span style="color: #40CFFF">TJ</span></th>

            <th colspan="4" width="5%" class="text-center">FINAL</th>
            <th class="text-center">VENDEDOR</th>
            <th class="text-center">FECHA</th>
            <!-- <th class="text-center">#FACTURA</th> -->
            <th colspan="2" class="text-center">DESCRIPCION</th>
        </tr>
    </thead>
    <tbody>
        <?php		
		$query="SELECT * FROM Productos ORDER BY StockTotal ASC";
		$queryProductos	=	mysqli_query($MySQLi,$query);
		$Num=1;

		$GranTotalInicial=0;

		$GranTotalInicialCB=0;
		$GranTotalInicialLP=0;
		$GranTotalInicialSC=0;
		$GranTotalInicialTJ=0;

		$GranTotalFinal=0;

		$GranTotalFinalCB=0;
		$GranTotalFinalLP=0;
		$GranTotalFinalSC=0;
		$GranTotalFinalTJ=0;
		while ($dataProducto = mysqli_fetch_assoc($queryProductos)) {
						
					$idProducto = $dataProducto['idProducto'];
					//inicial final cb,lp,sc,tj
					$queryHistorialProductos =	mysqli_query($MySQLi,
					"SELECT
					MAX(inicial) AS inicial,
    				MIN(final) AS final,
					SUM(cb) AS cb,
					SUM(lp) AS lp,
					SUM(sc) AS sc,
					SUM(tj) AS tj
					FROM
					historial_stock_envios
					WHERE
						idProducto = '$idProducto' AND(
					DATE_FORMAT(dateEmission, '%Y-%m-%d') BETWEEN '$INICIO' AND '$FIN')");

					$dataHistorialProductos	=	mysqli_fetch_assoc($queryHistorialProductos);

					//query vendedor,dateEmission,invoiceNumber,descripcion
					$queryVendedores =	mysqli_query($MySQLi,
					"SELECT
					vendedor,dateEmission,sucursal,descripcion
					FROM
					historial_stock_envios
					WHERE
						idProducto = '$idProducto' AND(
					DATE_FORMAT(dateEmission, '%Y-%m-%d') BETWEEN '$INICIO' AND '$FIN')");

					$vendedorArray=[];
					$dateEmissionArray=[];
					$sucursalArray=[];
					$descripcionArray=[];

					while($dataVendedores	=	mysqli_fetch_assoc($queryVendedores)){
						$vendedorArray[]= $dataVendedores['vendedor'];
						$dateEmissionArray[]= $dataVendedores['dateEmission'];
						$sucursalArray[]= $dataVendedores['sucursal'];
						$descripcionArray[]= $dataVendedores['descripcion'];
					}
		
					?>
        <tr>
            <td class="text-center"><?php echo $Num ?></td>
            <!-- <td class="text-center"><?php //echo $dataProducto['idProducto']; ?></td> -->

            <td class="text-center"><?php echo utf8_decode($dataProducto['Producto'] ); ?></td>
            <td class="text-center"><?php echo utf8_decode($dataProducto['Marca'] ); ?></td>
            <td class="text-center"><?php echo utf8_decode($dataProducto['Modelo'] ); ?></td>
            <td class="text-center">
                <?php 
							//inicial producto su stock mas antiguo con rango de fechas
							//cocha
							$queryStockMasAntiguo =	mysqli_query($MySQLi,
							"SELECT
							inicial
							FROM
							historial_stock_envios
							WHERE
							idProducto = '$idProducto' AND dateEmission =(
							SELECT
								MIN(dateEmission)
							FROM
							historial_stock_envios
							WHERE
								idProducto = '$idProducto' 
								AND(DATE_FORMAT(dateEmission, '%Y-%m-%d') BETWEEN '$INICIO' AND '$FIN') 
								AND sucursal='Cochabamba'
								)");

							$dataStockMasAntiguo	=	mysqli_fetch_assoc($queryStockMasAntiguo);
							

							if($dataStockMasAntiguo['inicial']==null){//no entro a historial

								$GranTotalInicial=$GranTotalInicial+$dataProducto['StockCB'];//tomamos su stock
								$GranTotalInicialCB=$GranTotalInicialCB+$dataProducto['StockCB'];//SOLO CBA
								echo $dataProducto['StockCB'];
								
							}
							else{
								$GranTotalInicial=$GranTotalInicial+$dataStockMasAntiguo['inicial'];//si entro a historial
								$GranTotalInicialCB=$GranTotalInicialCB+$dataStockMasAntiguo['inicial'];
								 echo $dataStockMasAntiguo['inicial']; }//tomamos su valor mas antiguo

			?>
            </td>
            <td>
                <?php
							//la paz
							//inicial producto su stock mas antiguo con rango de fechas
							$queryStockMasAntiguo =	mysqli_query($MySQLi,
							"SELECT
							inicial
							FROM
							historial_stock_envios
							WHERE
							idProducto = '$idProducto' AND dateEmission =(
							SELECT
								MIN(dateEmission)
							FROM
							historial_stock_envios
							WHERE
								idProducto = '$idProducto' 
								AND(DATE_FORMAT(dateEmission, '%Y-%m-%d') BETWEEN '$INICIO' AND '$FIN') 
								AND sucursal='La Paz'
								)");

							$dataStockMasAntiguo	=	mysqli_fetch_assoc($queryStockMasAntiguo);
							

							if($dataStockMasAntiguo['inicial']==null){//no entro a historial

								$GranTotalInicial=$GranTotalInicial+$dataProducto['StockLP'];//tomamos su stock
								$GranTotalInicialLP=$GranTotalInicialLP+$dataProducto['StockLP'];
								echo $dataProducto['StockLP'];
							}
							else{
								$GranTotalInicial=$GranTotalInicial+$dataStockMasAntiguo['inicial'];//si entro a historial
								$GranTotalInicialLP=$GranTotalInicialLP+$dataStockMasAntiguo['inicial'];
								echo $dataStockMasAntiguo['inicial']; }//tomamos su valor mas antiguo


			?>
            </td>
            <td>
                <?php
///-----------------------------SANTA CRUZ
								
							//inicial producto su stock mas antiguo con rango de fechas
							$queryStockMasAntiguo =	mysqli_query($MySQLi,
							"SELECT
							inicial
							FROM
							historial_stock_envios
							WHERE
							idProducto = '$idProducto' AND dateEmission =(
							SELECT
								MIN(dateEmission)
							FROM
							historial_stock_envios
							WHERE
								idProducto = '$idProducto' 
								AND(DATE_FORMAT(dateEmission, '%Y-%m-%d') BETWEEN '$INICIO' AND '$FIN') 
								AND sucursal='Santa Cruz'
								)");

							$dataStockMasAntiguo	=	mysqli_fetch_assoc($queryStockMasAntiguo);
							

							if($dataStockMasAntiguo['inicial']==null){//no entro a historial

								$GranTotalInicial=$GranTotalInicial+$dataProducto['StockSC'];//tomamos su stock
								$GranTotalInicialSC=$GranTotalInicialSC+$dataProducto['StockSC'];
								echo $dataProducto['StockSC'];
							}
							else{
								$GranTotalInicial=$GranTotalInicial+$dataStockMasAntiguo['inicial'];//si entro a historial
								$GranTotalInicialSC=$GranTotalInicialSC+$dataStockMasAntiguo['inicial'];
								echo $dataStockMasAntiguo['inicial']; }//tomamos su valor mas antiguo

			?>
            </td>
            <td>
                <?php

								///-----------------------------TARIJA
								
								//inicial producto su stock mas antiguo con rango de fechas
								$queryStockMasAntiguo =	mysqli_query($MySQLi,
								"SELECT
								inicial
								FROM
								historial_stock_envios
								WHERE
								idProducto = '$idProducto' AND dateEmission =(
								SELECT
									MIN(dateEmission)
								FROM
								historial_stock_envios
								WHERE
									idProducto = '$idProducto' 
									AND(DATE_FORMAT(dateEmission, '%Y-%m-%d') BETWEEN '$INICIO' AND '$FIN') 
									AND sucursal='Tarija'
									)");
	
								$dataStockMasAntiguo	=	mysqli_fetch_assoc($queryStockMasAntiguo);
								
	
								if($dataStockMasAntiguo['inicial']==null){//no entro a historial
	
									$GranTotalInicial=$GranTotalInicial+$dataProducto['StockTJ'];//tomamos su stock
									$GranTotalInicialTJ=$GranTotalInicialTJ+$dataProducto['StockTJ'];
									echo $dataProducto['StockTJ'];
								}
								else{
									$GranTotalInicial=$GranTotalInicial+$dataStockMasAntiguo['inicial'];//si entro a historial
									$GranTotalInicialTJ=$GranTotalInicialTJ+$dataStockMasAntiguo['inicial'];
									echo $dataStockMasAntiguo['inicial']; }//tomamos su valor mas antiguo

						
			?>
            </td>

            <td class="text-center">
                <strong>
                    <?php 
							if($dataHistorialProductos['cb']==null){
								echo '0';
							}else{
							echo $dataHistorialProductos['cb'];
							}
							 ?>
                </strong>
            </td>
            <td class="text-center">
                <strong>
                    <?php 
							if($dataHistorialProductos['lp']==null){
								echo '0';
							}else{
							echo $dataHistorialProductos['lp'];
							}
							 ?>
                </strong>
            </td>
            <td class="text-center">
                <strong>
                    <?php 
							if($dataHistorialProductos['sc']==null){
								echo '0';
							}else{
							echo $dataHistorialProductos['sc'];
							}
							 ?>
                </strong>
            </td>
            <td class="text-center">
                <strong>
                    <?php 
							if($dataHistorialProductos['tj']==null){
								echo '0';
							}else{
							echo $dataHistorialProductos['tj'];
							}
							 ?>
                </strong>
            </td>

            <td class="text-center">
                <?php 
							//final producto CON RANGO DE FECHAS
							$queryStockActualFechaFin =	mysqli_query($MySQLi,
							"SELECT
							final
							FROM
							historial_stock_envios
							WHERE
							idProducto = '$idProducto' AND dateEmission =(
							SELECT
								MAX(dateEmission)
							FROM
							historial_stock_envios
							WHERE
								idProducto = '$idProducto' 
								AND(DATE_FORMAT(dateEmission, '%Y-%m-%d') BETWEEN '$INICIO' AND '$FIN')
								AND sucursal='Cochabamba'
								)");

							$dataStockFechaFin	=	mysqli_fetch_assoc($queryStockActualFechaFin);

							if($dataStockFechaFin['final']==null){//sino existe tomamos del stock producutso fiscale
								$GranTotalFinal=$GranTotalFinal+$dataProducto['StockCB'];
								$GranTotalFinalCB=$GranTotalFinalCB+$dataProducto['StockCB'];
								echo $dataProducto['StockCB'];
							}else
							{	$GranTotalFinal=$GranTotalFinal+$dataStockFechaFin['final'];
								$GranTotalFinalCB=$GranTotalFinalCB+$dataStockFechaFin['final'];
								echo $dataStockFechaFin['final']; }
				?>
            </td>
            <td>
                <?php 
							//final producto CON RANGO DE FECHAS lapaz
							$queryStockActualFechaFin =	mysqli_query($MySQLi,
							"SELECT
							final
							FROM
							historial_stock_envios
							WHERE
							idProducto = '$idProducto' AND dateEmission =(
							SELECT
								MAX(dateEmission)
							FROM
							historial_stock_envios
							WHERE
								idProducto = '$idProducto' 
								AND(DATE_FORMAT(dateEmission, '%Y-%m-%d') BETWEEN '$INICIO' AND '$FIN')
								AND sucursal='La Paz'
								)");

							$dataStockFechaFin	=	mysqli_fetch_assoc($queryStockActualFechaFin);

							if($dataStockFechaFin['final']==null){//sino existe tomamos del stock producutso fiscale
								$GranTotalFinal=$GranTotalFinal+$dataProducto['StockLP'];
								$GranTotalFinalLP=$GranTotalFinalLP+$dataProducto['StockLP'];
								echo $dataProducto['StockLP'];
							}else
							{	$GranTotalFinal=$GranTotalFinal+$dataStockFechaFin['final'];
								$GranTotalFinalLP=$GranTotalFinalLP+$dataStockFechaFin['final'];
								echo $dataStockFechaFin['final']; }
				?>
            </td>
            <td>
                <?php 
							//final producto CON RANGO DE FECHAS santacruz
							$queryStockActualFechaFin =	mysqli_query($MySQLi,
							"SELECT
							final
							FROM
							historial_stock_envios
							WHERE
							idProducto = '$idProducto' AND dateEmission =(
							SELECT
								MAX(dateEmission)
							FROM
							historial_stock_envios
							WHERE
								idProducto = '$idProducto' 
								AND(DATE_FORMAT(dateEmission, '%Y-%m-%d') BETWEEN '$INICIO' AND '$FIN')
								AND sucursal='Santa Cruz'
								)");

							$dataStockFechaFin	=	mysqli_fetch_assoc($queryStockActualFechaFin);

							if($dataStockFechaFin['final']==null){//sino existe tomamos del stock producutso fiscale
								$GranTotalFinal=$GranTotalFinal+$dataProducto['StockSC'];
								$GranTotalFinalSC=$GranTotalFinalSC+$dataProducto['StockSC'];
								echo $dataProducto['StockSC'];
							}else
							{	$GranTotalFinal=$GranTotalFinal+$dataStockFechaFin['final'];
								$GranTotalFinalSC=$GranTotalFinalSC+$dataStockFechaFin['final'];
								echo $dataStockFechaFin['final']; }
				?>
            </td>
            <td>
                <?php 
							//final producto CON RANGO DE FECHAS tarija
							$queryStockActualFechaFin =	mysqli_query($MySQLi,
							"SELECT
							final
							FROM
							historial_stock_envios
							WHERE
							idProducto = '$idProducto' AND dateEmission =(
							SELECT
								MAX(dateEmission)
							FROM
							historial_stock_envios
							WHERE
								idProducto = '$idProducto' 
								AND(DATE_FORMAT(dateEmission, '%Y-%m-%d') BETWEEN '$INICIO' AND '$FIN')
								AND sucursal='Tarija'
								)");

							$dataStockFechaFin	=	mysqli_fetch_assoc($queryStockActualFechaFin);

							if($dataStockFechaFin['final']==null){//sino existe tomamos del stock producutso fiscale
								$GranTotalFinal=$GranTotalFinal+$dataProducto['StockTJ'];
								$GranTotalFinalTJ=$GranTotalFinalTJ+$dataProducto['StockTJ'];
								echo $dataProducto['StockTJ'];
							}else
							{	$GranTotalFinal=$GranTotalFinal+$dataStockFechaFin['final'];
								$GranTotalFinalTJ=$GranTotalFinalTJ+$dataStockFechaFin['final'];
								echo $dataStockFechaFin['final']; }
				?>


            </td>

            <td class="text-center">
                <?php
							if($vendedorArray==null){echo '';}
							else {
								foreach ($vendedorArray as $value) {
									echo $value . '<br>';
								}
							}								
							 ?>
            </td>
            <td class="text-center">
                <?php
							if($dateEmissionArray==null){echo '';}
							else {
								foreach ($dateEmissionArray as $value) {
									echo $value . '<br>';
								}
							}								
							?>
            </td>
            <!-- <td class="text-center"> -->
            <!-- </td> -->
            <td colspan="2" class="text-center">
                <?php
							if($descripcionArray==null){echo '';}
							else {
								foreach ($descripcionArray as $value) {
									echo $value . '<br>';
								}
							}
							?>
            </td>
        </tr>
        <?php $Num++;
			} ?>
        <tr class="odd gradeX">
            <td class="text-center"><?php echo $Num ?></td>

            <th class="text-center">TOTAL</th>
            <th class="text-center"></th>
            <th class="text-center"></th>

            <td>
                <strong>
                    <?php  
                        //echo $GranTotalInicial;
						echo $GranTotalInicialCB;
                    ?>
                </strong>
            </td>
            <td>
                <strong>
                    <?php	echo $GranTotalInicialLP;	?>
                </strong>
            </td>
            <td>
                <strong>
                    <?php	echo $GranTotalInicialSC;	?>
                </strong>
            </td>
            <td>
                <strong>
                    <?php	echo $GranTotalInicialTJ;	?>
                </strong>
            </td>
            <td class="text-center">
                <strong><span style="color: orange">
                        <?php  
                        $queryTotalIncial	=	mysqli_query($MySQLi,"SELECT SUM(cb) AS cb FROM historial_stock_envios WHERE (DATE_FORMAT(dateEmission, '%Y-%m-%d') BETWEEN '$INICIO' AND '$FIN') ")or die(mysqli_error($MySQLi));
                        $dataVentas		=	mysqli_fetch_assoc($queryTotalIncial);
                        $TotalInicial 	=	$dataVentas['cb'];
                        echo $TotalInicial;
                                ?>
                    </span></strong>
            </td>
            <td class="text-center">
                <strong><span style="color: blue">
                        <?php  
                        $queryTotalIncial	=	mysqli_query($MySQLi,"SELECT SUM(lp) AS lp FROM historial_stock_envios WHERE (DATE_FORMAT(dateEmission, '%Y-%m-%d') BETWEEN '$INICIO' AND '$FIN') ")or die(mysqli_error($MySQLi));
                        $dataVentas		=	mysqli_fetch_assoc($queryTotalIncial);
                        $TotalInicial 	=	$dataVentas['lp'];
                        echo $TotalInicial;
                                ?>
                    </span></strong>
            </td>
            <td class="text-center">
                <strong><span style="color: green">
                        <?php  
                        $queryTotalIncial	=	mysqli_query($MySQLi,"SELECT SUM(sc) AS sc FROM historial_stock_envios WHERE (DATE_FORMAT(dateEmission, '%Y-%m-%d') BETWEEN '$INICIO' AND '$FIN') ")or die(mysqli_error($MySQLi));
                        $dataVentas		=	mysqli_fetch_assoc($queryTotalIncial);
                        $TotalInicial 	=	$dataVentas['sc'];
                        echo $TotalInicial;
                                ?>
                    </span></strong>
            </td>
            <td class="text-center">
                <strong><span style="color: #40CFFF">
                        <?php  
                        $queryTotalIncial	=	mysqli_query($MySQLi,"SELECT SUM(tj) AS tj FROM historial_stock_envios WHERE (DATE_FORMAT(dateEmission, '%Y-%m-%d') BETWEEN '$INICIO' AND '$FIN') ")or die(mysqli_error($MySQLi));
                        $dataVentas		=	mysqli_fetch_assoc($queryTotalIncial);
                        $TotalInicial 	=	$dataVentas['tj'];
                        echo $TotalInicial;
                                ?>
                    </span></strong>
            </td>
            <td class="text-center">
                <strong>
                    <?php 
                        // echo $GranTotalFinal;
						echo $GranTotalFinalCB;
                    ?>
                </strong>
            </td>
            <td>
                <strong>
                    <?php	echo $GranTotalFinalLP;	?>
                </strong>
            </td>
            <td>
                <strong>
                    <?php	echo $GranTotalFinalSC;	?>
                </strong>
            </td>
            <td>
                <strong>
                    <?php	echo $GranTotalFinalTJ;	?>
                </strong>
            </td>
            <td class="text-center"></td>
            <td class="text-center"></td>



        </tr>


    </tbody>
</table>





<?php mysqli_close($MySQLi);
		
	}	
	
	
?>