<?php
session_start();
include './../includes/conexion.php';
error_reporting(0);
$sqlPrecioDolar = mysqli_query($MySQLi, "SELECT * FROM precio ");
$dolarBd = mysqli_fetch_assoc($sqlPrecioDolar);
$dolar=$dolarBd['precioDolar'];

$sqlurlcucu = mysqli_query($MySQLi, "SELECT * FROM token_access");
$dataurlcucu = mysqli_fetch_assoc($sqlurlcucu) or die(mysqli_error($MySQLi));
$urlcucu=$dataurlcucu['urlcucu'];
$to = $dataurlcucu['token'];
$email_automatico = $dataurlcucu['email_automatico'];

$idCotizacion = $_POST["idCotizacion"];

$clientNroDocument = $_POST["clientNroDocument"];
$clientReasonSocial = $_POST["clientReasonSocial"];if (!$clientReasonSocial) {$clientReasonSocial = "CONTROL TRIBUTARIO";}
$clientDocumentType = (int) $_POST["clientDocumentType"];
$clientCode = $_POST["clientCode"];
$clientCity = $_POST["clientCity"];
$userPos = $_POST["userPos"];
$paramCurrency = (int) $_POST["paramCurrency"];
$paramPaymentMethod = (int) $_POST["paramPaymentMethod"];
$additionalDiscount = $_POST["additionalDiscount"];
$clientEmail = $_POST["clientEmail"];if (!$clientEmail) {
    $clientEmail = $email_automatico;

}
//print_r($_SESSION["carrito"]);
$branchIdName = $_POST["branchIdName"]; //HIDDEN
$branchId; //JALADO DE LA BASE
if ($branchIdName == 'Cochabamba') {
    $branchId = 1;
}
if ($branchIdName == 'La Paz') {
    $branchId = 2;
}
if ($branchIdName == 'Santa Cruz') {
    $branchId = 3;
}
if ($branchIdName == 'Tarija') {
    $branchId = 4;
}

$tipoFactura = 1;
#modal actualizacion los cotizados
$array2 = array();
for ($i = 0; $i < count($_SESSION["carrito"]); $i++) {
    if ($_POST[$i . 'description']) {

    $qty = (int)$_POST[$i . 'qty'];
    $_SESSION["carrito"][$i]['qty'] = $qty;

    $description = $_POST[$i . 'description'];
    $_SESSION["carrito"][$i]['description'] = $description;

    $codeProduct = $_POST[$i . 'codeProduct'];
    $_SESSION["carrito"][$i]['codeProduct'] = $codeProduct;

    $priceUnit = $_POST[$i . 'priceUnit'];
    $_SESSION["carrito"][$i]['priceUnit'] = number_format(($priceUnit), 2, ".", "");

    $subTotal = $_POST[$i . 'subTotal'];
    $_SESSION["carrito"][$i]['subTotal'] = $subTotal;

    $_SESSION["carrito"][$i]['prodF'] = 'no';

    $array2[] = $_SESSION["carrito"][$i];
    }

}
#modal actualizacion los fiscales

$correlativo = (int)$_POST['correlativo'];
for ($i = 100; $i <= $correlativo; $i++) {
    if ($_POST[$i . 'description']) {
   
    $_SESSION["carrito"][$i]['activityEconomic'] = '465000'; 
    $_SESSION["carrito"][$i]['unitMeasure'] = 62;
    $_SESSION["carrito"][$i]['codeProductSin'] = '99794';//cambiar en produccion por "yuli": "99794"

    $codeProduct = $_POST[$i . 'codeProduct'];
    $_SESSION["carrito"][$i]['codeProduct'] = $codeProduct;

    $description = $_POST[$i . 'description'];
    $_SESSION["carrito"][$i]['description'] = $description;    
    
    $idProducto = (int)$_POST[$i . 'idProductoFiscal'];
    $_SESSION["carrito"][$i]['idProducto'] = $idProducto;

    $qty = (int)$_POST[$i . 'qty'];
    $_SESSION["carrito"][$i]['qty'] = $qty;

    $priceUnit = $_POST[$i . 'priceUnit'];
    $_SESSION["carrito"][$i]['priceUnit'] = number_format(($priceUnit), 2, ".", "");

    $subTotal = $_POST[$i . 'subTotal'];
    $_SESSION["carrito"][$i]['subTotal'] = $subTotal;

    $_SESSION["carrito"][$i]['prodF'] = 'si';

    $array2[] = $_SESSION["carrito"][$i];
    }

}


$datos = $_SESSION["carrito"];
//print_r($datos);
#REGENERACION TOKEN

//validar nitCliente
if ($clientDocumentType == 5) {
    $endpoint = '/api/v1/codes/nit';
    $data = array(
        'posId' => 1,
        'nit' => $clientNroDocument,
    );
    $url = $urlcucu.$endpoint . '?' . http_build_query($data);
    $ch = curl_init($url);
    
    curl_setopt($ch, CURLOPT_POSTFIELDS, 1);
    curl_setopt($ch, CURLOPT_HTTPHEADER,
        array("cucukey: Token $to",
            "Content-Type: application/json",
        ));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $result = curl_exec($ch);
    curl_close($ch);
    $descri = json_decode($result);
    $var = $descri->data[0];
    $code = $var->{'description'};
    if ($code == 'NIT ACTIVO') {
        $exceptionCode = 0;
    } else {
        $exceptionCode = 1;
    }
} else {
    $exceptionCode = 0;
}
//datos para generar factura
$posId = 1;
$additionalDiscount = $_POST['additionalDiscount'];
$url = $urlcucu.'/api/v1/invoice/computarized/sale';
$ch = curl_init($url);

$datos = $_SESSION["carrito"];
$encodeado = json_encode($array2);
$data = array(
    "posId" => $posId,
    "branchId" => $branchId,
    "clientReasonSocial" => $clientReasonSocial,
    "clientDocumentType" => $clientDocumentType,
    "clientNroDocument" => $clientNroDocument,
    "exceptionCode" => $exceptionCode,
    "clientCode" => $clientCode,
    "paramPaymentMethod" => $paramPaymentMethod,
    "userPos" => $userPos,
    "typeInvoice" => 1,
    "paramCurrency" => 1,
    "clientCity" => $clientCity,
    "clientEmail" => $clientEmail,
    "additionalDiscount" => $additionalDiscount,
    "detailInvoice" => $array2,

);

$payload = json_encode(($data));
// echo 'payloadxdxdxd----------------------';
// print_r($payload);
// echo 'array2 del payloadxdxdxd----------------------';
// print_r($array2);

curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
curl_setopt($ch, CURLOPT_HTTPHEADER, array("cucukey: Token $to", "Content-Type: application/json"));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//execute the POST request
$result = curl_exec($ch);
$datosF = json_decode($result);
// echo 'result-----------------------';
// print_r($datosF);
$var = $datosF->{'data'};
$varemail = $var->{'invoiceCode'};
$invoiceNumber = $var->{'invoiceNumber'};
$dateEmission=$var->{'dateEmission'};
$save = $datosF->{'message'};
//print_r($save);
if ($save == 'COMPLETED_SALE') {
    $urle = $urlcucu.'/api/v1/email';
    $che = curl_init($urle);
    $datae = array(
        'invoiceCode' => $varemail,
        'sendEmail' => $clientEmail,
        'subject' => "CORREO ENVIADO AUTOMATICAMENTE",
    );
    $payloade = json_encode(($datae));
    curl_setopt($che, CURLOPT_POSTFIELDS, $payloade);
    curl_setopt($che, CURLOPT_HTTPHEADER, array("cucukey: Token $to", "Content-Type: application/json"));
    curl_setopt($che, CURLOPT_RETURNTRANSFER, true);
    $resultEmail = curl_exec($che);
    curl_close($che);
    // segundo email automatoico

    if($clientEmail != $email_automatico){

    $urle1 = $urlcucu.'/api/v1/email';
    $che1 = curl_init($urle1);
    $datae1 = array(
        'invoiceCode' => $varemail,
        'sendEmail' => $email_automatico,
        'subject' => "CORREO ENVIADO AUTOMATICAMENTE",
    );
    $payloade1 = json_encode(($datae1));
    curl_setopt($che1, CURLOPT_POSTFIELDS, $payloade1);
    curl_setopt($che1, CURLOPT_HTTPHEADER, array("cucukey: Token $to", "Content-Type: application/json"));
    curl_setopt($che1, CURLOPT_RETURNTRANSFER, true);
    $resultEmail1 = curl_exec($che1);
    curl_close($che1);}
//////////////////////////
    $sqltoken = mysqli_query($MySQLi, "INSERT INTO factura (
		nitEmissor,
		clientReasonSocial,
		clientNroDocument,
		invoiceCode,
		cuf,
		invoiceNumber,
		qrCode,
		invoiceUrl,
		dateEmission,
		amountTotal,
		amountTotalDiscount,
		amountTotalCurrency,
		userCashier,
		siatCodeState,
		siatCodeReception,
		siatDescriptionStatus,
		countItems,
		invoiceXml,
        idCotizacion,
        branchId,
        exceptionCode,
        clientEmail,
        tipoFactura
		)
				VALUES
				(
					'$var->nitEmissor',
					'$clientReasonSocial',
					'$clientNroDocument',
					'$var->invoiceCode',
					'$var->cuf',
					'$var->invoiceNumber',
					'$var->qrCode',
					'$var->invoiceUrl',
					'$var->dateEmission',
					'$var->amountTotal',
					'$var->amountTotalDiscount',
					'$var->amountTotalCurrency',
					'$var->userCashier',
					'$var->siatCodeState',
					'$var->siatCodeReception',
					'$var->siatDescriptionStatus',
					'$var->countItems',
					'$var->invoiceXml',
                    '$idCotizacion',
                    '$branchId',
                    '$exceptionCode',
                    '$clientEmail',
                    '$tipoFactura'

				)") or die(mysqli_error($MySQLi));


//update productos fiscales restando del stok los que ya facturamos

                        $detail = $var->{'detailInvoice'};
                        //print_r($detail);
                        //echo "----------<br>";
                        //print_r($datos);
                        //echo "----------<br>--------";
                        $datosEncodeado=json_encode($array2);//print_r($datosEncodeado);
                        //echo "----------<br>--";
                        $datosDecodeado=json_decode($datosEncodeado);//print_r($datosDecodeado);

                

               
                foreach ($datosDecodeado as $row) {
                    //updatemos los productosfiscales
                    if($row->prodF=='si'){
                    //actualizamos productos fiscales--------------------------------------------------------ini
                    $prodFis = mysqli_query($MySQLi, "SELECT * FROM productos_fiscales WHERE idProducto='$row->idProducto'");
                    $dataprodFis = mysqli_fetch_assoc($prodFis);
                    $stockActual=(int) $dataprodFis['saldo_fisico'];
                    $stockNuevo=$stockActual-$row->qty;
                    $updateProdFi = mysqli_query($MySQLi, "UPDATE productos_fiscales SET saldo_fisico='$stockNuevo' WHERE idProducto='$row->idProducto' ");
                    //actualizamos productos fiscales --------------------------------------------------------fin 

                    //insertamos historial --------------------------------------------------------ini
                    $producto_fiscal = $dataprodFis['detalle'];
                    $cb = 0;if ($branchId == 1) {
                        $cb = $row->qty*-1;
                    }

                    $lp = 0;if ($branchId == 2) {
                        $lp = $row->qty*-1;
                    }

                    $sc = 0;if ($branchId == 3) {
                        $sc = $row->qty*-1;
                    }

                    $tj = 0;if ($branchId == 4) {
                        $tj = $row->qty*-1;
                    }

                    $sql_insert = mysqli_query($MySQLi, "INSERT INTO historial_stock_productos_fiscales(

                    producto_fiscal,
                    inicial,
                    cb,
                    lp,
                    sc,
                    tj,
                    final,
                    vendedor,
                    dateEmission,
                    descripcion,
                    idProducto,
                    invoiceCode,
                    invoiceNumber,
                    branchId

                    )
                    VALUES(

                    '$producto_fiscal',
                    '$stockActual',
                    '$cb',
                    '$lp',
                    '$sc',
                    '$tj',
                    '$stockNuevo',
                    '$userPos',
                    '$var->dateEmission',
                    'DESCUENTO STOCK-FACTURACION VENDIDAS',
                    '$row->idProducto',
                    '$var->invoiceCode',
                    '$var->invoiceNumber',
                    '$branchId'

                    )") or die(mysqli_error($MySQLi));
                    //insertamos historial --------------------------------------------------------fin 
                    }

                    #CAMBIO AQUÍ: usamos una variable $sql para más claridad y aplicamos lo dicho en (2)
                    $idTotal=$row->qty*$row->priceUnit;
                     $sql = mysqli_query($MySQLi, "INSERT INTO detailInvoice
                        (
                            detailId,
                            activityEconomic,
                            codeProductSin,

                            codeProduct,
                            description,
                            qty,

                            unitMeasure,
                            priceUnit,
                            subTotal,
                            
                            invoiceNumber,
                            idCotizacion,
                            branchId,
                            dateEmission,

                            prodF

                        )
                        VALUES
                        (
                            '$row->idProducto',
                            '$row->activityEconomic',
                            '$row->codeProductSin',

                            '$row->codeProduct',
                            '$row->description',
                            '$row->qty',
                            
                            '$row->unitMeasure',
                            '$row->priceUnit',
                            
                            '$idTotal',

                            '$invoiceNumber',


                            '$idCotizacion',
                            '$branchId',
                            '$dateEmission',

                            '$row->prodF'

                        )") or die(mysqli_error($MySQLi));
                    
                }





}

unset($_SESSION["carrito"]);

//header("Location: /?root=entregadas");
$alert = $datosF->{'message'};

//include 'php/script_compradas.php';
?>



<?php
if($alert=='COMPLETED_SALE'){

    echo'<script type="text/javascript">
    alert("FACTURACION EXITOSA - REVISE EL LISTADO DE FACTURAS");
    window.location.href="/?root=facturacionListado";
    </script>
    
    ';
    
}else{

    echo'<script type="text/javascript">
        alert("ERROR CONEXION - INTENTE NUEVAMENTE");
        window.location.href="/?facturacionListado";
        </script>';

}

header("Location: /?root=compradas");


?>