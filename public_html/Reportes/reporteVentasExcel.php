<?php
	include '../includes/conexion.php';

	$StartDate		=	$_POST['start'];
	$EndDate 		=	$_POST['end'];
	$Sucursal 		=	'Cochabamba';

	$ConsultaVenta 	=	mysqli_query($MySQLi,"SELECT * FROM Ventas WHERE Fecha BETWEEN '$StartDate' AND '$EndDate ' ORDER BY Sucursal ASC ");
	$resultConsulta	=	mysqli_num_rows($ConsultaVenta);
	if ($resultConsulta >0) {
		header("Content-type: application/vnd.ms-excel; name='excel'");
		header("Content-Disposition: filename=ReporteVentas.xls");
		header("Pragma: no-cache");
		header("Expires: 0");

		$estilo = file_get_contents("css/estilo_reportexcel.php");
		echo $estilo;
		echo utf8_decode('
		<body>
			<table class="table">
				<thead>
					<tr>
						<th class="text-cente title2" colspan="20"><h2>RESUMEN DE VENTAS DEL &nbsp;&nbsp; '.$StartDate .' &nbsp;&nbsp; al &nbsp;&nbsp; '.$EndDate . '</h2></th>
					</tr>
					<tr>
						<th class="text-center title4" colspan="20"><h4>SUCURSAL &nbsp;&nbsp; '.$Sucursal. ' &nbsp;&nbsp;</h4></th>
					</tr>
					<tr class="titulos">
						<th class="th-head">Fecha</th>
						<th class="th-head">Recibo</th>
						<th class="th-head">Nota de entrege</th>
						<th class="th-head">Factura</th>
						<th class="th-head">Cliente</th>
						<th class="th-head">NIT Cliente</th>
						<th class="th-head">Teléfono Contacto</th>
						<th class="th-head">Detalle de mercadería vendida</th>
						<th class="th-head">Marca</th>
						<th class="th-head">Modelo</th>
						<th class="th-head">Cant</th>
						<th class="th-head">Precio Lista
						P/U en $USD</th>
						<th class="th-head">Descuento de venta en %(-)</th>
						<th class="th-head">Precio venta en $USD</th>
						<th class="th-head">BS</th>
						<th class="th-head">Pago de venta en Bs.</th>
						<th class="th-head">Pago de venta en $USD</th>
						<th class="th-head">Vendido por</th>
						<th class="th-head">N&ordm;</th>
						<th class="th-head">Observaciones</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td style="background-color: #87A95B"></td>
						<td style="background-color: #C57070"></td>
						<td></td>
						<td style="background-color: #87A95B"></td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
				</tbody>
			</table>
		</body>');
	}else{
		$estilo = file_get_contents("css/estilo_reportexcel.php");
		echo $estilo;
		echo '
		<meta charset="utf-8">
		<body>
			<table class="table">
				<thead>
					<tr>
						<th class="text-cente title2" colspan="20"><h2>RESUMEN DE VENTAS DEL &nbsp;&nbsp; '.$StartDate .' &nbsp;&nbsp; al &nbsp;&nbsp; '.$EndDate . '</h2></th>
					</tr>
					<tr>
						<th class="text-center title4" colspan="20"><h4>SUCURSAL &nbsp;&nbsp; '.$Sucursal. ' &nbsp;&nbsp;</h4></th>
					</tr>
					<tr class="titulos">
						<th class="th-head">Fecha</th>
						<th class="th-head">Recibo</th>
						<th class="th-head">Nota de entrege</th>
						<th class="th-head">Factura</th>
						<th class="th-head">Cliente</th>
						<th class="th-head">NIT Cliente</th>
						<th class="th-head">Teléfono Contacto</th>
						<th class="th-head">Detalle de mercadería vendida</th>
						<th class="th-head">Marca</th>
						<th class="th-head">Modelo</th>
						<th class="th-head">Cant</th>
						<th class="th-head">Precio Lista
						P/U en $USD</th>
						<th class="th-head">Descuento de venta en %(-)</th>
						<th class="th-head">Precio venta en $USD</th>
						<th class="th-head">BS</th>
						<th class="th-head">Pago de venta en Bs.</th>
						<th class="th-head">Pago de venta en $USD</th>
						<th class="th-head">Vendido por</th>
						<th class="th-head">N&ordm;</th>
						<th class="th-head">Observaciones</th>
					</tr>
				</thead>
				<tbody>
					<tr class="text-center">
						<td colspan="20"><strong class="text-danger" style="letter-spacing: 1px">NO HAY COINCIDENCIAS EN EL RANGO DE BUSQUEDA</strong></td>
					</tr>
				</tbody>
			</table>
		</body>';
	}
 ?>