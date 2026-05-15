<?php
error_reporting(0);
$invoice_id = (int)$_POST['invoiceCode1'];
$motivo_id = (int)$_POST['codeMotive1'];
$branchId = $_POST['branchId1'];
$invoiceNumber = (int)$_POST['invoiceNumber1'];


$respuesta_api = AnularFactura($invoice_id, $motivo_id);
if ($respuesta_api->{'response'} == 'ok') {
  restaurar_fiscales($invoice_id, $branchId, $invoiceNumber);
  echo json_encode('ok');
}else{
  echo json_encode('error');
}


function AnularFactura($invoice_id, $motivo_id)
{
  include './../conexion_yuliimport.php';
  $sqlurlyapame = mysqli_query($YuliimportDB, "SELECT * FROM token_access");
  $dataurlyapame = mysqli_fetch_assoc($sqlurlyapame) or die(mysqli_error($YuliimportDB));

  $urlyapame = $dataurlyapame['urlcucu'];
  $token = $dataurlyapame['token'];

  $ch = curl_init();
  $url = $urlyapame . "/api/invoices/" . $invoice_id . "/void";
  curl_setopt($ch, CURLOPT_URL, $url);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
  curl_setopt($ch, CURLOPT_HEADER, FALSE);

  curl_setopt($ch, CURLOPT_POST, TRUE);

  curl_setopt($ch, CURLOPT_POSTFIELDS, "{
  \"invoice_id\": $invoice_id,
  \"motivo_id\": $motivo_id
}");

  curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    "Content-Type: application/json",
    "Authorization: Bearer $token"
  ));

  $response = curl_exec($ch);
  curl_close($ch);
  // print_r($response);
  $response_decode = json_decode($response);
  return $response_decode;
}
function restaurar_fiscales($invoice_id, $branchId, $invoiceNumber)
{
  include './../conexion_yuliimport.php';
    //aki iva una copia de reenvio a la jefa xd
    //905 = anulada
    //updateamos la factura en la BD
    $sql = mysqli_query($YuliimportDB,
     "UPDATE factura SET siatDescriptionStatus='ANULACION CONFIRMADA',siatCodeState=905 WHERE invoiceCode='$invoice_id' AND invoiceNumber='$invoiceNumber'") or die(mysqli_error($YuliimportDB));

    //actualizamos productos fiscales sumando el anulado a la tabla prodfiscales
    $prodF = mysqli_query($YuliimportDB, "SELECT * FROM detailInvoice WHERE invoiceNumber='$invoiceNumber' and prodF='si' and branchId='$branchId' ");
    while ($dataprodF = mysqli_fetch_assoc($prodF)) {
      $detailId = $dataprodF['detailId']; //idproducto detail invoice
      $qtydevolver = (int)$dataprodF['qty']; //cantidad a devolver

      $prodFis = mysqli_query($YuliimportDB, "SELECT * FROM productos_fiscales WHERE idProducto='$detailId'");
      $dataprodFis = mysqli_fetch_assoc($prodFis);


      $idProducto = $dataprodFis['idProducto'];
      $stockActual = (int) $dataprodFis['saldo_fisico'];
      $stockNuevo = $stockActual + $qtydevolver;
      $updateProdFi = mysqli_query($YuliimportDB, "UPDATE productos_fiscales SET saldo_fisico='$stockNuevo' WHERE idProducto='$idProducto' ");

      //insertamos historial --------------------------------------------------------ini
      date_default_timezone_set('America/La_Paz');
      $fechaActual = date('c');
      $producto_fiscal = $dataprodFis['detalle'];
      $branchId = $dataprodF['branchId'];
      session_start();
      $idUser = $_SESSION['idUser'];
      $ConsltaUser = mysqli_query($YuliimportDB, "SELECT * FROM Usuarios WHERE idUser='$idUser' ");
      $datosUser = mysqli_fetch_assoc($ConsltaUser);
      $userPos = $datosUser['Nombres'] . " " . $datosUser['Apellidos'];

      $cb = 0;
      if ($branchId == 1) {
        $cb = $qtydevolver;
      }

      $lp = 0;
      if ($branchId == 2) {
        $lp = $qtydevolver;
      }

      $sc = 0;
      if ($branchId == 3) {
        $sc = $qtydevolver;
      }

      $tj = 0;
      if ($branchId == 4) {
        $tj = $qtydevolver;
      }
      $sql_insert = mysqli_query($YuliimportDB, "INSERT INTO historial_stock_productos_fiscales(
  
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
            '$fechaActual',
            'AUMENTO STOCK-FACTURA ANULADA',
            '$idProducto',
            '$invoice_id',
            '$invoiceNumber',
            '$branchId'
  
            )") or die(mysqli_error($YuliimportDB));
      //insertamos historial --------------------------------------------------------fin 


    }
//   if($updateProdFi){
// return true;
//   }else{
//     return false;
//   }
}
