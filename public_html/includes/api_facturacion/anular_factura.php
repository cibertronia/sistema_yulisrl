<?php
error_reporting(0);
include '../App/Models/Sucursal.php';
use App\Models\Sucursal;

$invoice_id = (int)$_POST['invoiceCode1'];
$motivo_id = (int)$_POST['codeMotive1'];
$branchId = $_POST['branchId1'];
$invoiceNumber = (int)$_POST['invoiceNumber1'];





$respuesta_api = AnularFactura($invoice_id, $motivo_id);
if ($respuesta_api->{'response'} == 'ok') {
  restaurar_fiscales($invoice_id, $branchId, $invoiceNumber);

  //parte yuliimport inicio
  include './../conexion.php';
  //obtenemos de srl ,su doble_emision doble_invoice_id doble_invoice_number	para mandar anular en yuliimport
  $q_factura = mysqli_query($MySQLi, "SELECT * FROM factura WHERE invoiceCode='$invoice_id' AND invoiceNumber='$invoiceNumber' AND siatCodeState ='905'");
  $d_factura = mysqli_fetch_assoc($q_factura);

  $doble_emision = $d_factura['doble_emision'];
  $doble_invoice_id = $d_factura['doble_invoice_id'];
  $doble_invoice_number = $d_factura['doble_invoice_number'];
  //si esta factura fue emitida de forma doble preguntamos y anulamos su factura relacionada en yuliimport
  if ($doble_emision == 'si') {
    //anular y restaurar en yuliimport 01
    $respuesta_api_yuliimport = anular_en_yuliimport($doble_invoice_id, $motivo_id);
    if ($respuesta_api_yuliimport->{'response'} == 'ok') {
      restaurar_en_yuliimport($doble_invoice_id, 1, $doble_invoice_number);
    }
  }
  //parte yuliimport fin

  echo json_encode('ok');
} else {
  echo json_encode('error');
}


function AnularFactura($invoice_id, $motivo_id)
{
  include './../conexion.php';
  $sqlurlyapame = mysqli_query($MySQLi, "SELECT * FROM token_access");
  $dataurlyapame = mysqli_fetch_assoc($sqlurlyapame) or die(mysqli_error($MySQLi));

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
  include './../conexion.php';
  //aki iva una copia de reenvio a la jefa xd
  //905 = anulada
  //updateamos la factura en la BD
  $sql = mysqli_query(
    $MySQLi,
    "UPDATE factura SET siatDescriptionStatus='ANULACION CONFIRMADA',siatCodeState=905 WHERE invoiceCode='$invoice_id' AND invoiceNumber='$invoiceNumber'"
  ) or die(mysqli_error($MySQLi));

  $sucursalModel = new Sucursal();



  //actualizamos productos fiscales sumando el anulado a la tabla prodfiscales
  $prodF = mysqli_query($MySQLi, "SELECT * FROM detailInvoice WHERE invoiceNumber='$invoiceNumber' and prodF='si' and branchId='$branchId' ");
  while ($dataprodF = mysqli_fetch_assoc($prodF)) {
    $detailId = $dataprodF['detailId']; //idproducto detail invoice
    $qtydevolver = (int)$dataprodF['qty']; //cantidad a devolver

    $prodFis = mysqli_query($MySQLi, "SELECT * FROM productos_fiscales WHERE idProducto='$detailId'");
    $dataprodFis = mysqli_fetch_assoc($prodFis);

    if ($dataprodFis['idProductoFiscal01yuli'] == null || $dataprodFis['idProductoFiscal01yuli'] == '' || $dataprodFis['idProductoFiscal01yuli'] <= 0) {

      $idProducto = $dataprodFis['idProducto'];
      $stockActual = (int) $dataprodFis['saldo_fisico'];
      $stockNuevo = $stockActual + $qtydevolver;
      $updateProdFi = mysqli_query($MySQLi, "UPDATE productos_fiscales SET saldo_fisico='$stockNuevo' WHERE idProducto='$idProducto' ");

      //insertamos historial --------------------------------------------------------ini
      date_default_timezone_set('America/La_Paz');
      $fechaActual = date('c');
      $producto_fiscal = $dataprodFis['detalle'];
      $branchId = $dataprodF['branchId'];
      session_start();
      $idUser = $_SESSION['idUser'];
      $ConsltaUser = mysqli_query($MySQLi, "SELECT * FROM Usuarios WHERE idUser='$idUser' ");
      $datosUser = mysqli_fetch_assoc($ConsltaUser);
      $userPos = $datosUser['Nombres'] . " " . $datosUser['Apellidos'];

      $sucursales = $sucursalModel->all();
      foreach ($sucursales as $sucursal) {
        $iniciales = strtolower($sucursal['iniciales']);
        ${$iniciales} = 0; // Inicializamos las variables para cada sucursal
        if ($sucursal['idSucursal'] == $branchId) {
          ${$iniciales} = $qtydevolver; // Asignamos el stock a la sucursal correspondiente
        }
      }

      $sql_insert = mysqli_query($MySQLi, "INSERT INTO historial_stock_productos_fiscales(
  
            producto_fiscal,
            inicial,
            cb,
            lp,
            sc,
            tj,
            st,
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
            '$st',
            '$stockNuevo',
            '$userPos',
            '$fechaActual',
            'AUMENTO STOCK-FACTURA ANULADA',
            '$idProducto',
            '$invoice_id',
            '$invoiceNumber',
            '$branchId'
  
            )") or die(mysqli_error($MySQLi));
      //insertamos historial --------------------------------------------------------fin 

    }
  }
}


#anular en yuli01 , restaurar sus fiscales y agregar al historial todo en bd yuliimport
function anular_en_yuliimport($invoice_id, $motivo_id)
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
function restaurar_en_yuliimport($invoice_id, $branchId, $invoiceNumber)
{
  include './../conexion_yuliimport.php';
  //updateamos la factura en la BD
  $sql = mysqli_query(
    $YuliimportDB,
    "UPDATE factura SET siatDescriptionStatus='ANULACION CONFIRMADA',siatCodeState=905 WHERE invoiceCode='$invoice_id' AND invoiceNumber='$invoiceNumber'"
  ) or die(mysqli_error($YuliimportDB));

  $sucursalModel = new Sucursal();

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
    $branchId = 1;

    $userPos = 'User Yuli Srl';

    $sucursales = $sucursalModel->all();
    foreach ($sucursales as $sucursal) {
      $iniciales = strtolower($sucursal['iniciales']);
      ${$iniciales} = 0; // Inicializamos las variables para cada sucursal
      if ($sucursal['idSucursal'] == $branchId) {
        ${$iniciales} = $qtydevolver; // Asignamos el stock a la sucursal correspondiente
      }
    }

    $sql_insert = mysqli_query($YuliimportDB, "INSERT INTO historial_stock_productos_fiscales(
  
            producto_fiscal,
            inicial,
            cb,
            lp,
            sc,
            tj,
            st,
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
            '$st',
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
