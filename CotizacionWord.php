<?php
	include 'HTMLtoWord/Class.php';
	$word	 =	new HTML_TO_DOC();
	//header("Content-type: application/vnd.ms-word");
	//header("Content-Description: attachment; filename=Reporte.doc");

	//require 'mPDF/vendor/autoload.php';
	require 'includes/conexion.php';
	include 'includes/date.class.php';
	mysqli_query($MySQLi,"SET lc_time_names= 'es_BO' ");
	$idCotizacion	=	1;//$_POST['id'];

	//OBTENEMOS LOS DATOS DE LA COTIZACION
	$queryCotiza	=	mysqli_query($MySQLi,"SELECT Code, Clave, idUser, idCliente, Forma_Pago, DATE_FORMAT(FinFecha_Oferta, '%d de %M de %Y') AS FinFecha_Oferta , Dias_Entrega, Comentarios, Sucursal, DATE_FORMAT(Fecha, '%d de %M de %Y') AS Fecha FROM Cotizaciones WHERE idCotizacion='$idCotizacion' ");
	$dataCotiza		=	mysqli_fetch_assoc($queryCotiza);
	$CodigoCotiza 	=	$dataCotiza['Code'];
	$ClaveCotizacion=	$dataCotiza['Clave'];
	$idUser 		=	$dataCotiza['idUser'];
	$idCliente 		=	$dataCotiza['idCliente'];
	$FormaPago		=	$dataCotiza['Forma_Pago'];
	$FinOferta 		=	$dataCotiza['FinFecha_Oferta'];
	$Entrega 		=	$dataCotiza['Dias_Entrega'];
	$Comentarios 	=	$dataCotiza['Comentarios'];
	$Sucursal 		=	$dataCotiza['Sucursal'];
	//$Fecha 			=	$dataCotiza['Fecha'];

	//OBTENEMOS LOS DATOS DEL CLIENTE
	$queryCliente 	=	mysqli_query($MySQLi,"SELECT * FROM Clientes WHERE idCliente='$idCliente' ");
	$dataCliente 	=	mysqli_fetch_assoc($queryCliente);
	$FullNameCliente=	$dataCliente['Nombres']." ".$dataCliente['Apellidos'];
	$CorreoCliente 	=	$dataCliente['Correo'];
	$EmpresaCliente	=	$dataCliente['Empresa'];

	//OBTENEMOS LOS DATOS DEL USUARIO
	$queryUsuario 	=	mysqli_query($MySQLi,"SELECT * FROM Usuarios WHERE idUser='$idUser' ");
	$dataUsuario 	=	mysqli_fetch_assoc($queryUsuario);
	$FullNameUsuario=	$dataUsuario['Nombres']." ".$dataUsuario['Apellidos'];
	$CiudadUsuario 	=	$dataUsuario['Ciudad'];
	$TelefonoUsuario=	$dataUsuario['Telefono'];

	// $mpdf 	=	new \Mpdf\Mpdf([
	// 	'mode'			=>	'utf-8',
	// 	'format' 		=> [280, 216],
	// 	'orientation'	=>	'L',
	// 	'margin_header'	=>	0,
	// 	'margin_footer'	=>	0,
	// 	'margin_left'	=>	0,
	// 	'margin_top'	=>	27,
	// 	'margin_right'	=>	0,
	// 	'margin_bottom'	=>	10,

	// ]);
	//$CSS 	=	file_get_contents('assets/css/estilo.css');

	// $mpdf->SetHTMLHeader('<img src="assets/img/HEADER.png">');
	// $mpdf->SetHTMLFooter('<img src="assets/img/FOOTER.png">');
	echo '
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
	$NumPaginas 		=	round($cantidadClaves/$cantidadTablas);
	

	while ( $dataClave  =	mysqli_fetch_assoc($queryProducto)) {

		$idProducto 	=	$dataClave['idProducto'];
		$CantidadProduct=	$dataClave['Cantidad'];
		$PrecioLista 	=	$dataClave['PrecioLista'];
		$PrecioVenta 	=	$dataClave['PrecioOferta'];

		$ConsultaProduct=	mysqli_query($MySQLi,"SELECT * FROM Productos WHERE idProducto='$idProducto' ");
		$infoPro 		=	mysqli_fetch_array($ConsultaProduct);
		$Description	=	$infoPro['Descripcion'];
		$ImagenProducto =	$infoPro['Imagen'];
		$NameProducto 	=	$infoPro['Producto'];
		$MarcaProducto 	= 	$infoPro['Marca'];
		$ModeloProducto =	$infoPro['Modelo'];
		$Total 			=	number_format(($CantidadProduct*$PrecioVenta),2);
		echo '<br><br><img src="https://yapame.com.sv/wp-content/uploads/2019/08/estatambien-600x600.jpg" alt=""  width="200" height="200">
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
					<img src="https://sistema.yuliimport.com/Productos/'.$ImagenProducto .'" alt="" width="200" height="200"></td>
				</tr>
				<tr class="amarillo">
					<td colspan="2" class="footer">Cantidad: '.$CantidadProduct .'</td>
					<td colspan="2" class="footer">Precio Lista: $ '.number_format($PrecioLista,2) .'</td>
					<td colspan="2" class="footer verde" style="color: #fff">Precio Especial: $ '.number_format($PrecioVenta,2) .'</td>
					<td colspan="2" class="footer verde" style="color: #fff">Total: $ '.$Total .'</td>
				</tr>
			</tbody>
		</table>';
	} echo '
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

	
	// $NamePDF=	"Detalle Cotización";
	// $mpdf->WriteHTML($CSS, \Mpdf\HTMLParserMode::HEADER_CSS);
	// $mpdf->WriteHTML($html, \Mpdf\HTMLParserMode::HTML_BODY);
	// $mpdf->Output($NamePDF." Fecha ( ".($Fecha) ." ).pdf", "I");
	//$mpdf->AddPage();
?>