<?php
include './../conexion_yuliimport.php';
error_reporting(0);
$sqlurlyapame = mysqli_query($YuliimportDB, "SELECT * FROM token_access");
$dataurlyapame = mysqli_fetch_assoc($sqlurlyapame) or die(mysqli_error($YuliimportDB));

$urlyapame = $dataurlyapame['urlcucu'];
$token = $dataurlyapame['token'];


$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $urlyapame . "/api/customers");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($ch, CURLOPT_HEADER, FALSE);

curl_setopt($ch, CURLOPT_POST, TRUE);

//Recuperando por POST los datos del modal facturacion
$clientReasonSocial = $_POST['clientReasonSocial'];
$clientDocumentType = $_POST['clientDocumentType'];
$clientNroDocument = $_POST['clientNroDocument'];//nit ruf ci pass
$identity_document=intval(preg_replace('/[^0-9]+/', '', $clientNroDocument), 10);
$clientCode = $_POST['clientCode'];
$clientCity = $_POST['clientCity'];
$clientEmail = $_POST['clientEmail'];

curl_setopt($ch, CURLOPT_POSTFIELDS, "{
  \"code\": \"$clientCode\",
  \"group_id\": -1,
  \"store_id\": 0,
  \"first_name\": \"$clientReasonSocial\",
  \"last_name\": \"\",
  \"identity_document\": $identity_document,
  \"company\": \"\",
  \"date_of_birth\": null,
  \"gender\": \"\",
  \"phone\": \"\",
  \"mobile\": \"\",
  \"fax\": \"\",
  \"email\": \"$clientEmail\",
  \"website\": \"\",
  \"address_1\": \"Direccion Null\",
  \"address_2\": \"\",
  \"zip_code\": \"\",
  \"city\": \"$clientCity\",
  \"country\": \"Bolivia\",
  \"country_code\": \"BO\",
  \"meta\": {
    \"_nit_ruc_nif\": \"$clientNroDocument\",
    \"_billing_name\": null
  }
}");

curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    "Content-Type: application/json",
    "Authorization: Bearer $token"
));

$response = curl_exec($ch);
curl_close($ch); //cerrar conexion_api curl
//llega en json y lo convertimos a objeto (decode)
$respuesta = json_decode($response);
$valor_response = $respuesta->{'response'};
$customer_id = $respuesta->{'data'}->{'customer'}->{'customer_id'};

$first_name = $respuesta->{'data'}->{'customer'}->{'first_name'};
$identity_document = $respuesta->{'data'}->{'customer'}->{'identity_document'};
$email = $respuesta->{'data'}->{'customer'}->{'email'};

$obj_merged = (object) [];
$obj_merged->response = $valor_response;
$obj_merged->customer_id = $customer_id;
$obj_merged->first_name = $first_name;
$obj_merged->identity_document = $identity_document;
$obj_merged->email = $email;

echo json_encode($obj_merged);


 //print_r($response);//
// var_dump($response);
