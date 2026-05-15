<?php

// require_once __DIR__ . './App/Models/Sucursal.php';
// require_once __DIR__ . './App/Models/Product.php';
// require_once __DIR__ . './App/Models/HistoryProduct.php';

use App\Models\Sucursal;
use App\Models\Product;
use App\Models\HistoryProduct;

	function precioDolar($MySQLi) {
		$queryDolar	=	mysqli_query($MySQLi,"SELECT * FROM precio");
		$dataDolar 	= mysqli_fetch_assoc($queryDolar);
		$PrecioDolar= $dataDolar['precioDolar'];
		echo $PrecioDolar;
	}
	function notaCredito($MySQLi,$idCotizacion,$idVendedor,$idCliente,$Sucursal,$fecha) {
		$sqlVentas  = mysqli_query($MySQLi,"SELECT SUM(TotalVentaUS)AS USD, SUM(TotalVentaBS)AS Bs FROM Ventas WHERE idCotizacion='$idCotizacion'AND Estado=0 ");
		if (!$sqlVentas) throw new Exception(mysqli_error($MySQLi));
		$dataVentas = mysqli_fetch_assoc($sqlVentas);
		$USD 				= $dataVentas['USD'];
		$Bs 				= $dataVentas['Bs'];
		$insertCred = mysqli_query($MySQLi,"INSERT INTO notasCredito (idUser, idCliente, MontoUSD, MontoBs, Fecha) VALUES ('$idVendedor', '$idCliente', '$USD', '$Bs', '$fecha')");
		if (!$insertCred) throw new Exception(mysqli_error($MySQLi));
    	return true;
	}
	function copiarVentaCash($MySQLi,$idCotizacion) { //no funcional
		$sqlVentas	= mysqli_query($MySQLi,"SELECT * FROM Ventas WHERE idCotizacion='$idCotizacion' ");
		while ($dataVentas = mysqli_fetch_assoc($sqlVentas)) {
			mysqli_query($MySQLi,"INSERT INTO VentasModificadas (idCotizacion, CodeCotizacion, idUser, idCliente, idRecibo, idEntrega, idProducto, Cantidad, Moneda, PrecioDolar, PrecioListaUSD, PrecioListaBs, PrecioVentaUSD, PrecioVentaBs) VALUES () ");
		}
	}
	function copiarTablas($MySQLi,$idCotizacion) {
		$sqlCotizacion 	= mysqli_query($MySQLi,"SELECT * FROM Cotizaciones WHERE idCotizacion='$idCotizacion' ");
		if (!$sqlCotizacion) throw new Exception(mysqli_error($MySQLi));
		$data   				= mysqli_fetch_assoc($sqlCotizacion);
		$Code 					= $data['Code'];
		$Clave 					= $data['Clave'];
		$idUser 				= $data['idUser'];
		$idCliente 			= $data['idCliente'];
		$Forma_Pago 		= $data['Forma_Pago'];
		$FinFecha_Oferta= $data['FinFecha_Oferta'];
		$Dias_Entrega 	= $data['Dias_Entrega'];
		$Comentarios 		= $data['Comentarios'];
		$Sucursal 			= $data['Sucursal'];
		$Fecha 					= $data['Fecha'];
		$Hora 					= $data['Hora'];
		$Entregada 			= $data['Entregada'];
		$Compra 	 			= $data['Compra'];
		$insertTabla = mysqli_query($MySQLi, "INSERT INTO CotMod (idCotizacion, Code, Clave, idUser, idCliente, Forma_Pago, FinFecha_Oferta, Dias_Entrega, Comentarios, Sucursal, Fecha, Hora, Entregada, Compra) VALUES ('$idCotizacion', '$Code', '$Clave', '$idUser', '$idCliente', '$Forma_Pago', '$FinFecha_Oferta', '$Dias_Entrega', '$Comentarios', '$Sucursal', '$Fecha', '$Hora', '$Entregada', " . ($Compra == NULL ?  "NULL)" : "'$Compra')") );
		if (!$insertTabla) throw new Exception(mysqli_error($MySQLi));
		$sqlClavesTemp  = mysqli_query($MySQLi,"SELECT * FROM ClaveTemporal WHERE Clave='$Clave' ");
		if (!$sqlClavesTemp) throw new Exception(mysqli_error($MySQLi));
		while ($dataClav= mysqli_fetch_assoc($sqlClavesTemp)) {
			$ClaveTemp 		= $dataClav['Clave'];
			$idProducto 	= $dataClav['idProducto'];
			$Cantidad 		= $dataClav['Cantidad'];
			$PrecioLista  = $dataClav['PrecioLista'];
			$PrecioOferta = $dataClav['PrecioOferta'];
			$insertClaves = mysqli_query($MySQLi, "INSERT INTO ClaveTempMod (Clave, idProducto, Cantidad, PrecioLista, PrecioOferta) VALUES ('$ClaveTemp', '$idProducto', '$Cantidad', '$PrecioLista', '$PrecioOferta') ");
			if (!$insertClaves) throw new Exception(mysqli_error($MySQLi));
		}
		/*	CONSULTAMOS LA VENTA A MODIFICAR	*/
		$queryVenta 		= mysqli_query($MySQLi,"SELECT * FROM Ventas WHERE idCotizacion='$idCotizacion' AND Estado=0 ");
		if (!$queryVenta) throw new Exception(mysqli_error($MySQLi));
		while ($dataVenta=mysqli_fetch_assoc($queryVenta)) {
			$idRecibo 			= $dataVenta['idRecibo'];
			$idNotaEntrega  	= $dataVenta['idEntrega'];
			$Moneda 			= $dataVenta['Moneda'];
			$PrecioDolar    	= $dataVenta['PrecioDolar'];
			$PreListaUSD		= $dataVenta['PrecioListaUSD'];
			$PreListaBs 		= $dataVenta['PrecioListaBs'];
			$PreVentaUSD 		= $dataVenta['PrecioVentaUSD'];
			$PreVentaBs 		= $dataVenta['PrecioVentaBs'];
			$TotVentaUSD 		= $dataVenta['TotalVentaUS'];
			$TotVentaBs 		= $dataVenta['TotalVentaBs'];
			/*	CONSULTAMOS LA NUEVA COTIZACION GENERADA	*/
			$queryCotizaMod = mysqli_query($MySQLi,"SELECT * FROM CotMod WHERE Clave='$Clave'AND Tipo=1 ");
			if (!$queryCotizaMod) throw new Exception(mysqli_error($MySQLi));
			while ($dataCot = mysqli_fetch_assoc($queryCotizaMod)) {
				$newIDCotiza  = $dataCot['idCotizacion'];
				$insertVentaMod = mysqli_query($MySQLi, "INSERT INTO VentasModificadas (idCotizacion, CodeCotizacion, idUser, idCliente, idRecibo, idEntrega, idProducto, Cantidad, Moneda, PrecioDolar, PrecioListaUSD, PrecioListaBs, PrecioVentaUSD, PrecioVentaBs, Sucursal, Fecha, TotalVentaUS, TotalVentaBs) VALUES ('$newIDCotiza', '$Code', '$idUser', '$idCliente', '$idRecibo', '$idNotaEntrega', '$idProducto', '$Cantidad', '$Moneda', '$PrecioDolar', '$PreListaUSD', '$PreListaBs', '$PreVentaUSD', '$PreVentaBs', '$Sucursal', '$Fecha', '$TotVentaUSD', '$TotVentaBs' ) ");
				if (!$insertVentaMod) throw new Exception(mysqli_error($MySQLi));
			}
		}
	}
	function copiarTablas_Abonos($MySQLi,$idCotizacion) {
		$sqlCotizacion 	= mysqli_query($MySQLi,"SELECT * FROM Cotizaciones WHERE idCotizacion='$idCotizacion' ");
		$data   				= mysqli_fetch_assoc($sqlCotizacion);
		$Code 					= $data['Code'];
		$Clave 					= $data['Clave'];
		$idUser 				= $data['idUser'];
		$idCliente 			= $data['idCliente'];
		$Forma_Pago 		= $data['Forma_Pago'];
		$FinFecha_Oferta= $data['FinFecha_Oferta'];
		$Dias_Entrega 	= $data['Dias_Entrega'];
		$Comentarios 		= $data['Comentarios'];
		$Sucursal 			= $data['Sucursal'];
		$Fecha 					= $data['Fecha'];
		$Hora 					= $data['Hora'];
		$Entregada 			= $data['Entregada'];
		$insertTabla 		= mysqli_query($MySQLi,"INSERT INTO CotMod (idCotizacion, Code, Clave, idUser, idCliente, Forma_Pago, FinFecha_Oferta, Dias_Entrega, Comentarios, Sucursal, Fecha, Hora, Entregada, Tipo) VALUES ('$idCotizacion', '$Code', '$Clave', '$idUser', '$idCliente', '$Forma_Pago', '$FinFecha_Oferta', '$Dias_Entrega', '$Comentarios', '$Sucursal', '$Fecha', '$Hora', '$Entregada',2) ")or die(mysqli_error($MySQLi)."<br>Error en la línea: ".__LINE__);
		$sqlClavesTemp  = mysqli_query($MySQLi,"SELECT * FROM ClaveTemporal WHERE Clave='$Clave' ");
		while ($dataClav= mysqli_fetch_assoc($sqlClavesTemp)) {
			$ClaveTemp 		= $dataClav['Clave'];
			//$ClaveTempMod = $ClaveTemp."mod";
			$idProducto 	= $dataClav['idProducto'];
			$Cantidad 		= $dataClav['Cantidad'];
			$PrecioLista  = $dataClav['PrecioLista'];
			$PrecioOferta = $dataClav['PrecioOferta'];
			$insertClaves = mysqli_query($MySQLi,"INSERT INTO ClaveTempMod (Clave, idProducto, Cantidad, PrecioLista, PrecioOferta) VALUES ('$ClaveTemp', '$idProducto', '$Cantidad', '$PrecioLista', '$PrecioOferta') ")or die(mysqli_error($MySQLi)."<br>Error en la línea: ".__LINE__);
		}
	}
	function modificarVentaCash($MySQLi,$idCotizacion) {
		$result = mysqli_query($MySQLi, "UPDATE Ventas SET TotalVentaUS= 0, TotalVentaBs= 0, Estado=1 WHERE idCotizacion='$idCotizacion' AND Estado=0");
		if (!$result) throw new Exception(mysqli_error($MySQLi));
		return true;
	}
	function modificarAbonos($MySQLi,$idCotizacion) {
		mysqli_query($MySQLi,"UPDATE Abonos SET porAnticipo= 0, anticipoUSD= 0, Estado=1 WHERE idCotizacion='$idCotizacion' AND Estado=0");
	}
	function modificarRecibosAbonos($MySQLi,$idCotizacion){
		mysqli_query($MySQLi,"UPDATE Recibos SET Estado=1 WHERE idCotizacion='$idCotizacion'AND Estado=0 ");
	}
	function devolverProductos($MySQLi,$Sucursal,$Clave, $tipo = 'VENTA') {
		$queryClaveTemp = mysqli_query($MySQLi,"SELECT * FROM ClaveTemporal WHERE Clave='$Clave' ");
		if (!$queryClaveTemp) throw new Exception(mysqli_error($MySQLi));

		date_default_timezone_set('America/La_Paz');
		$fechaActual = date('c');//requerido
		$descripcion = "RETORNO STOCK-ELIMINAR " . $tipo;//requerido
		if (session_status() !== PHP_SESSION_ACTIVE) session_start();		
		$idUser = $_SESSION['idUser'];
		$ConsltaUser = mysqli_query($MySQLi, "SELECT * FROM Usuarios WHERE idUser='$idUser' ");
		if (!$ConsltaUser) throw new Exception(mysqli_error($MySQLi));
		$datosUser = mysqli_fetch_assoc($ConsltaUser);
		$userPos = $datosUser['Nombres'] . " " . $datosUser['Apellidos'];//requerido

		$SucursalModel = new Sucursal(); 
		$getSucursal = $SucursalModel->where('Sucursal', $Sucursal);
		$iniciales = $getSucursal[0]['iniciales'];

		$ProductoModel = new Product();

		while ($dataCla = mysqli_fetch_assoc($queryClaveTemp)) {
			$idProducto 	= $dataCla['idProducto'];
			$Cantidad 		= $dataCla['Cantidad'];
			
			// Actualizamos el stock de productos
			$producto = $ProductoModel->find($idProducto);
			$stockInicial = $producto['Stock' . $iniciales];
			$producto['Stock' . $iniciales] += $Cantidad;
			$producto['StockTotal'] += $Cantidad;

			$ProductoModel->update($idProducto, $producto);

			// Insertamos el registro de devolución
			$historialData = [
				'Producto'      => $producto['Producto'],
				'inicial'       => $stockInicial,
				'final'         => $producto['Stock' . $iniciales],
				'vendedor'      => $userPos,
				'dateEmission'  => $fechaActual,
				'descripcion'   => $descripcion,
				'idProducto'    => $idProducto,
				'sucursal'      => $Sucursal,
			];

			$sucursales = $SucursalModel->all();
			foreach ($sucursales as $item) {
				$initials = strtolower($item['iniciales']);
				$historialData[$initials] = $item['Sucursal'] == $Sucursal ? $Cantidad : 0;
			}

			$historial = new HistoryProduct();
			if (!$historial->insert($historialData)) {
				throw new \Exception('Error al insertar el historial de stock del producto');
			}

		}
		return true; // o manejar el éxito de otra manera
	}
	function changeStatusCoti($MySQLi,$idCotizacion) {
		$sqlUpdate 		= mysqli_query($MySQLi,"UPDATE Cotizaciones SET Estado=0 WHERE idCotizacion='$idCotizacion' ");
	}
	function obtenerTotalAbonado($MySQLi, $idCotizacion){
		$TotalAbonos		= mysqli_query($MySQLi,"SELECT SUM(porAnticipo)AS Bs, SUM(anticipoUSD)AS USD, Moneda, PrecioDolar, idUser, idCliente FROM Abonos WHERE idCotizacion='$idCotizacion'AND Estado=0 ");
		$dataAbonos 		= mysqli_fetch_assoc($TotalAbonos);
		$anticipoUSD		= $dataAbonos['USD'];
		$anticipoBs			= $dataAbonos['Bs'];
		$Moneda 				= $dataAbonos['Moneda'];
		$PrecioDolar 		= $dataAbonos['PrecioDolar'];
		$idCliente 			= $dataAbonos['idCliente'];
		$Cliente 				= $dataAbonos['Cliente'];
		$idVendedor 		= $dataAbonos['idUser'];
		$Sucursal 			= $dataAbonos['Sucursal'];
		$CodeCotizacion = $dataAbonos['CodeCotizacion'];
		/*	CREAMOS LA NOTA DE CRÉDITO A FAVOR DEL CLIENTE 	*/
		if ($Moneda   	=='USD') {
			$USD 					= $anticipoUSD;
			$Bs 					= $anticipoUSD*$PrecioDolar;
		}else{
			$USD 					= $anticipoBs/$PrecioDolar;
			$Bs 					= $anticipoBs;
		}
		//mysqli_query($MySQLi,"INSERT INTO notasCredito (idUser, idCliente, MontoUSD, MontoBs, Fecha) VALUES ('$idVendedor', '$idCliente', '$USD', '$Bs', '$fecha') ");
	}
?>