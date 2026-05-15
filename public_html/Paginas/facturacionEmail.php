<?php
include './../includes/conexion.php';
error_reporting(0);

$sqlurlcucu = mysqli_query($MySQLi, "SELECT * FROM token_access");
$dataurlcucu = mysqli_fetch_assoc($sqlurlcucu) or die(mysqli_error($MySQLi));
$urlcucu=$dataurlcucu['urlcucu'];
$to = $dataurlcucu['token'];

$posId = 1;

$invoiceCode = $_POST['invoiceCode2']; 
$subject = $_POST['subject2'];if (!$subject) {$subject = 'Factura Reenviada';} 

$invoiceNumber = $_POST['invoiceNumber2'];
$invoiceNumber = (int) $invoiceNumber;

$codeMotive = $_POST['codeMotive2'];
$codeMotive = (int) $codeMotive;

$tipoFactura = $_POST['tipoFactura2'];
$tipoFactura = (int) $tipoFactura;

$branchId = $_POST['branchId2'];

$clientEmail = $_POST['clientEmail2'];if (!$clientEmail) {$clientEmail = 'abrahan.zambrana@gmail.com';}

$urle = $urlcucu.'/api/v1/email';
$che = curl_init($urle);
$datae = array(
    'invoiceCode' => $invoiceCode,
    'sendEmail' => $clientEmail,
    'subject' => $subject,
);
$payloade = json_encode(($datae));

curl_setopt($che, CURLOPT_POSTFIELDS, $payloade);
curl_setopt($che, CURLOPT_HTTPHEADER, array("cucukey: Token $to", "Content-Type: application/json"));
curl_setopt($che, CURLOPT_RETURNTRANSFER, true);
$resultEmail = curl_exec($che);

curl_close($che);

?>

<?php

?>

<script type="text/javascript">
Swal.fire({
    position: 'center',
    type: 'success',
    title: 'REENVIADO CORRECTAMENTE',
    html: 'SUCCESS',
    showConfirmButton: false,
    animation: false,
    customClass: {
        popup: 'animated rotateIn'
    }
});
setTimeout(function() {
    location.reload();
}, 3500);
</script>
<?php

//echo '<script type="text/JavaScript"> location.reload(); </script>';
?>