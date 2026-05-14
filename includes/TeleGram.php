<?php
	function alertStockLow($FullNameProd, $RemanenteProd, $Sucursal) {
		include 'conexion.php';
		include 'date.class.php';
		$queryToken	=	mysqli_query($MySQLi,"SELECT * FROM TeleGram ")or die(mysqli_error($MySQLi));
		while ( $dataToken  =	mysqli_fetch_assoc($queryToken)) {

			$bottoken   	=	"859696188:AAH4arQRIO6weNq8hlflxaG46aMgK2FpdGc";
			$website    	=	"https://api.telegram.org/bot".$bottoken;
			$update     	=	file_get_contents('php://input');
			$update     	=	json_decode($update, TRUE);
			$ApiUser 		=	$dataToken['Api'];			

			//	CONSULTAMOS LOS DATOS DEL USUARIO
			$idUserTeleGram =	$dataToken['idUser'];
			$queryUserTG 	=	mysqli_query($MySQLi,"SELECT * FROM Usuarios WHERE idUser='$idUserTeleGram' ");
			$dataUserTeleG	=	mysqli_fetch_assoc($queryUserTG);
			$NameUserTeleG	=	$dataUserTeleG['Nombres']." ".$dataUserTeleG['Apellidos'];

			//	ENVIAMOS EL MENSAJE A TODOS LOS ADMINISTRADORES			
			$chat_id        =   $ApiUser;
            $Respuesta      =   $NameUserTeleG.",\nTe informamos que el producto:\n<strong>".$FullNameProd ."</strong>\na generado una alerta de <strong>Stock bajo</strong>, con un remanente de \n<strong>".$RemanenteProd."</strong> productos.\n\nLa venta se dió en: <strong>".$Sucursal."</strong>.\n\nMensaje enviado automaticamente desde el sistema de inventarios el día: ".$Fecha." a las: ".$hora;
            
            $url = $website."/sendMessage?chat_id=".$chat_id ."&parse_mode=HTML&text=".urlencode($Respuesta);
            file_get_contents($url);
		}
	}	
?>