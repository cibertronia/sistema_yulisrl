<?php
	function precioDolar(){
		$ch =	curl_init();
		curl_setopt($ch, CURLOPT_URL, 'https://www.cotizacion.co/bolivia/precio-del-dolar.php');
		curl_setopt($ch, CURLOPT_USERAGENT, 'Mozila/4.0 (compatible; MISE 5.01; Windows NT 5.0)');
		curl_setopt($ch, CURLOPT_HTTPHEADER, array("Accept-Language: es-es,en"));
		curl_setopt($ch, CURLOPT_TIMEOUT, 10);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

		/*	GUARDA VALORES DE LA PAGINA */
		$result =	curl_exec($ch);
		$error 	=	curl_error($ch);
		curl_close($ch);

		/*Parsear los datos*/
		preg_match_all('<div class="carousel-caption"><h2 class="pricetxt">(.*)</h2></div>', $result, $respuesta);
		$PrecioDolar 	=	$respuesta[1][0];
	}
?>