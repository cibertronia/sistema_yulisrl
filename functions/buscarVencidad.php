<!-- BUSCAMOS COTIZACIONES VENCIDAS PARA CAMBIARLAS A CADUCADAS (ESTADO # 3) -->
<?php
	$buscarCot	=	mysqli_query($MySQLi,"SELECT * FROM Cotizaciones WHERE Estado=1 AND FinFecha_Oferta<'$fecha' ");
	$resultBusq =	mysqli_num_rows($buscarCot);
	if ($resultBusq>0) {
		while ($dataBusqueda=	mysqli_fetch_assoc($buscarCot)) {
			$idCotizacion	=	$dataBusqueda['idCotizacion'];
			$CaducarCotiza	=	mysqli_query($MySQLi,"UPDATE Cotizaciones SET Estado=3 WHERE idCotizacion='$idCotizacion' ");
		}
	}
?>