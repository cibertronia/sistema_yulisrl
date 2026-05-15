<?php
session_start();
include './../includes/conexion.php';
error_reporting(0);

$sqlurlcucu = mysqli_query($MySQLi, "SELECT * FROM token_access");
$dataurlcucu = mysqli_fetch_assoc($sqlurlcucu) or die(mysqli_error($MySQLi));
$urlcucu=$dataurlcucu['urlcucu'];
$to = $dataurlcucu['token'];


$sqlPrecioDolar = mysqli_query($MySQLi, "SELECT * FROM precio ");
$dolarBd = mysqli_fetch_assoc($sqlPrecioDolar);
$dolar = $dolarBd['precioDolar'];

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

        $array2[] = $_SESSION["carrito"][$i];
    }

}


#REGENERACION TOKEN

//datos para generar factura
//cabezera debitocredito
$posId = 1;

$userPos = $_POST['userPos'];
$invoiceNumber = (int) $_POST['invoiceNumber'];
$invoiceCode = $_POST['invoiceCode'];
$idCotizacion = $_POST["idCotizacion"];
$clientNroDocument = $_POST["clientNroDocument"];
$clientReasonSocial = $_POST["clientReasonSocial"];if (!$clientReasonSocial) {$clientReasonSocial = "CONTROL TRIBUTARIO";}
$clientEmail = $_POST["clientEmail"];if (!$clientEmail) {$clientEmail = "abrahan.zambrana@gmail.com";}
$exceptionCode = (int) $_POST['exceptionCode'];
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
$tipoFactura = 24;
$datos = $_SESSION["carrito"];
$encodeado = json_encode($array2);

//array de detail invoice
$data = array(
    "posId" => $posId,
    "branchId" => $branchId,
    "userPos" => $userPos,
    "exceptionCode" => $exceptionCode,
    "invoiceNumber" => $invoiceNumber,
    "invoiceCode" => $invoiceCode,
    "detailInvoice" => $array2,
);



$url = $urlcucu.'/api/v1/invoice/computarized/debit';
$ch = curl_init($url);

$payload = json_encode(($data));
curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
curl_setopt($ch, CURLOPT_HTTPHEADER,
    array("cucukey: Token $to", "Content-Type: application/json"));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//execute the POST request
$result = curl_exec($ch);

$datosF = json_decode($result);
//print_r($datosF);
$var = $datosF->{'data'};
$varemail = $var->{'invoiceCode'};
$save = $datosF->{'message'};

if ($save == 'COMPLETED_DEBIT') {
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

		userCashier,
		siatCodeState,
		siatCodeReception,
		siatDescriptionStatus,
		countItems,
		invoiceXml,
        idCotizacion,
		branchId,
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

					'$var->userCashier',
					'$var->siatCodeState',
					'$var->siatCodeReception',
					'$var->siatDescriptionStatus',
					'$var->countItems',
					'$var->invoiceXml',
                    '$idCotizacion',
					'$branchId',
                    '$tipoFactura'

				)") or die(mysqli_error($MySQLi));

}

unset($_SESSION["carrito"]);

$alert = $datosF->{'message'};
?>
<script src="https://cdn.bootcss.com/jquery/3.3.1/jquery.js"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script type="text/javascript">
<?php
echo "var jsvar ='$alert';";
?>
if ($(document).ready(function() {
        if (jsvar == "COMPLETED_DEBIT") {
            swal({
                title: "NOTA DEBITO-CREDITO EXITOSA",
                text: jsvar,
                icon: "success",
                button: "Ok",
                timer: 5000
            });
        } else {
            swal({
                title: "ERROR",
                text: jsvar,
                icon: "error",
                button: "Ok",
                timer: 5000
            });
        }

    })) {

    setTimeout(function() {
        window.location.href = "/?root=compradas";
    }, 4000);
}
</script>';