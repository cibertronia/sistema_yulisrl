<?php
error_reporting(0);
session_start();
if (isset($_SESSION['idUser'])) {
  include './../includes/conexion.php';
  $sqlurlyapame = mysqli_query($MySQLi, "SELECT * FROM token_access");
  $dataurlyapame = mysqli_fetch_assoc($sqlurlyapame) or die(mysqli_error($MySQLi));

  $urlyapame = $dataurlyapame['urlcucu'];
  $token = $dataurlyapame['token'];

  //recuperamos ID get
  $invoiceCode = $_GET['invoiceCode'];
  // iniciar peticion api
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $urlyapame . "/api/invoices/" . $invoiceCode . "/pdf?tpl=");
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
  curl_setopt($ch, CURLOPT_HEADER, FALSE);

  curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    "Content-Type: application/json",
    "Authorization: Bearer $token"
  ));
  $response = curl_exec($ch);
  curl_close($ch);

  $response_decode = json_decode($response);
  $pdf64 = $response_decode->{'data'}->{'buffer'};
  $pdf64 = str_replace(chr(92), '', $pdf64);
  //print_r($response);

} else {
  session_destroy();
} ?>
<!DOCTYPE html>
<html>

<head>
  <title>FACTURA PDF</title>
</head>

<body bgcolor="gray">
  <iframe src="data:application/pdf;base64,<?php echo $pdf64 ?>" style="width:100%; height:1080px;" frameborder="0"></iframe>
</body>

</html>


