<?php
include '../App/Models/Sucursal.php';

use App\Models\Sucursal;
//cabeza factura capturado por POST
error_reporting(0);
$cabeza_factura = array(
  "customer_id" => $_POST['customer_id'],
  "first_name" => $_POST['first_name'],
  "identity_document" => $_POST['identity_document'],
  "email" => $_POST['email'],
  "idCotizacion" => $_POST['idCotizacion'],
  "tipo_documento_identidad" => (int) $_POST['tipo_documento_identidad'],
  "codigo_metodo_pago" => (int) $_POST['codigo_metodo_pago'],
  "subtotal" => $_POST['subtotal'],
  "total" => $_POST['total'],
  "total_tax" => $_POST['total_tax'],
);

//array de productos capturado por POST
$array = json_decode($_POST['items']);
$items = $array->{'items'};
$items = json_encode($items);

//Enviamos a Facturar a la API
$respuesta_api = facturacionsintic($cabeza_factura, $items, 0);
if ($respuesta_api->{'response'} == 'error_nit' || $respuesta_api->{'response'} == 'error') {
  $respuesta_api = facturacionsintic($cabeza_factura, $items, 1);
}
//Si facturo normal Guardamos en BD
if ($respuesta_api->{'response'} == 'ok') {
  guardar_factura($respuesta_api, $cabeza_factura, $items);

  echo json_encode($respuesta_api);
} else {
  echo json_encode($respuesta_api);
}

//funciones......
//funcion Mandar a la api para facturar
function facturacionsintic($cabeza_factura, $array_productos, $excepcion)
{
  include './../conexion.php';
  $sqlurlyapame = mysqli_query($MySQLi, "SELECT * FROM token_access");
  $dataurlyapame = mysqli_fetch_assoc($sqlurlyapame) or die(mysqli_error($MySQLi));
  $urlyapame = $dataurlyapame['urlcucu'];
  $token = $dataurlyapame['token'];

  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $urlyapame . "/api/invoices");
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_HEADER, false);
  curl_setopt($ch, CURLOPT_POST, true);

  $customer_id = $cabeza_factura["customer_id"];
  $first_name = $cabeza_factura["first_name"];
  $identity_document = $cabeza_factura["identity_document"];
  $email = $cabeza_factura["email"];
  $tipo_documento_identidad = $cabeza_factura["tipo_documento_identidad"];
  $codigo_metodo_pago = $cabeza_factura["codigo_metodo_pago"];
  $subtotal = $cabeza_factura["subtotal"];
  $total = $cabeza_factura["total"];
  $total_tax = $cabeza_factura["total_tax"];
  $codigo_sucursal = obtener_codigo_sucursal();
  // punto_venta,

  curl_setopt(
    $ch,
    CURLOPT_POSTFIELDS,
    "{
  \"customer_id\": \"$customer_id\",
  \"customer\": \"$first_name\",
  \"nit_ruc_nif\": \"$identity_document\",
  \"subtotal\": $subtotal,
  \"total_tax\": $total_tax,
  \"discount\": \"0\",
  \"monto_giftcard\": \"0\",
  \"total\": $total,
  \"invoice_date_time\": \"\",
  \"currency_code\": \"\",
  \"codigo_sucursal\": $codigo_sucursal,
  \"punto_venta\": 0,
  \"codigo_documento_sector\": 1,
  \"tipo_documento_identidad\": $tipo_documento_identidad,
  \"codigo_metodo_pago\":  $codigo_metodo_pago,
  \"codigo_moneda\": 1,
  \"complemento\": null,
  \"numero_tarjeta\": null,
  \"tipo_cambio\": 1,
  \"tipo_factura_documento\": 1,
  \"items\": $array_productos,
  \"data\": {
    \"excepcion\":$excepcion
  }
    }"
  );

  curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    "Content-Type: application/json",
    "Authorization: Bearer $token",
  ));

  $response = curl_exec($ch);
  $response_decode = json_decode($response);
  curl_close($ch);
  //print_r($response);
  return $response_decode;
}

function guardar_factura($respuesta_api, $cabeza_factura, $array_productos)
{
  include './../conexion.php';

  $nitEmissor = $respuesta_api->{'data'}->{'nit_emisor'};
  $clientReasonSocial = $respuesta_api->{'data'}->{'customer'};
  $clientNroDocument = $respuesta_api->{'data'}->{'nit_ruc_nif'};
  $invoiceCode = $respuesta_api->{'data'}->{'invoice_id'}; //revisar bien
  $cuf = $respuesta_api->{'data'}->{'cuf'};

  $invoiceNumber = (int) $respuesta_api->{'data'}->{'invoice_number'};
  $qrCode = (string) $respuesta_api->{'data'}->{'siat_url'};
  $invoiceUrl = $respuesta_api->{'data'}->{'print_url'};
  $dateEmission = $respuesta_api->{'data'}->{'creation_date'};
  $amountTotal = $respuesta_api->{'data'}->{'total'};

  $amountTotalDiscount = $respuesta_api->{'data'}->{'discount'};
  $amountTotalCurrency = $respuesta_api->{'data'}->{'total'};

  session_start();
  $idUser = $_SESSION['idUser'];
  $queryUser = mysqli_query($MySQLi, "SELECT Nombres, Apellidos FROM Usuarios WHERE idUser='$idUser' ") or die(mysqli_error($MySQLi));
  $dataUser = mysqli_fetch_assoc($queryUser);
  $userCashier = $dataUser['Nombres'] . ' ' . $dataUser['Apellidos']; //generar pensar bien

  $siatCodeState = '908'; //no hay en la respuesta pensar
  $siatCodeReception = $respuesta_api->{'data'}->{'siat_id'};

  //$siatDescriptionStatus = 'VALIDADA'; // revisar
  $siatDescriptionStatus = ($cabeza_factura["idCotizacion"] == '-1') ? "Validada - Emision Directa" : "VALIDADA";
  $countItems = count($respuesta_api->{'data'}->{'items'}); //no hay en la api ... contar manual
  $invoiceXml = ''; //no hay en la api ver de donde extraer
  $idCotizacion = $cabeza_factura["idCotizacion"];
  $codigo_sucursal = (int) $respuesta_api->{'data'}->{'codigo_sucursal'};

  $sucursalModel = new Sucursal();
  $sucursal = $sucursalModel->where('codigo_factura', $codigo_sucursal);

  $branchId = (int) $sucursal[0]['idSucursal'];

  $exceptionCode = $respuesta_api->{'data'}->{'data'}->{'excepcion'};
  $clientEmail = $cabeza_factura["email"];
  $tipoFactura = $respuesta_api->{'data'}->{'tipo_factura_documento'};

  $sqlInsertFactura = mysqli_query($MySQLi, "INSERT INTO factura (
nitEmissor,clientReasonSocial,
clientNroDocument,invoiceCode,
cuf,invoiceNumber,
qrCode,invoiceUrl,
dateEmission,amountTotal,
amountTotalDiscount,amountTotalCurrency,
userCashier,siatCodeState,
siatCodeReception,siatDescriptionStatus,
countItems,invoiceXml,
idCotizacion,branchId,
exceptionCode,clientEmail,tipoFactura)
VALUES ('$nitEmissor','$clientReasonSocial','$clientNroDocument',
'$invoiceCode','$cuf','$invoiceNumber',
'$qrCode','$invoiceUrl','$dateEmission','$amountTotal','$amountTotalDiscount','$amountTotalCurrency',
'$userCashier','$siatCodeState','$siatCodeReception','$siatDescriptionStatus','$countItems','$invoiceXml','$idCotizacion','$branchId','$exceptionCode','$clientEmail','$tipoFactura')");

  //update productos fiscales restando del stok los que ya facturamos
  // $detail = $respuesta_api->{'data'}->{'items'};
  //$datosEncodeado=json_encode($array_productos);
  $datosDecodeado = json_decode($array_productos);
  //print_r($datosDecodeado);
  foreach ($datosDecodeado as $row) {
    //updatemos los productosfiscales
    if ($row->prodF == 'si') {
      //actualizamos productos fiscales--------------------------------------------------------ini
      $prodFis = mysqli_query($MySQLi, "SELECT * FROM productos_fiscales WHERE idProducto='$row->idProductoFiscal'");
      $dataprodFis = mysqli_fetch_assoc($prodFis);
      $stockActual = (int) $dataprodFis['saldo_fisico'];
      $stockNuevo = $stockActual - $row->quantity;
      $updateProdFi = mysqli_query($MySQLi, "UPDATE productos_fiscales SET saldo_fisico='$stockNuevo' WHERE idProducto='$row->idProductoFiscal' ");
      //actualizamos productos fiscales --------------------------------------------------------fin

      //insertamos historial --------------------------------------------------------ini
      $producto_fiscal = $dataprodFis['detalle'];
      $sucursales = $sucursalModel->all();
      foreach ($sucursales as $sucursal) {
        $iniciales = strtolower($sucursal['iniciales']);
        ${$iniciales} = 0; // Inicializamos las variables para cada sucursal
        if ($sucursal['idSucursal'] == $branchId) {
          ${$iniciales} = $row->quantity * -1; // Asignamos el stock actual a la sucursal correspondiente
        }
      }
      // //continuar xd diproducto
      $descripcion_historial = ($cabeza_factura["idCotizacion"] == '-1') ? "DESCUENTO STOCK-FACTURACION EMISION DIRECTA" : "DESCUENTO STOCK-FACTURACION VENDIDAS";
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
        '$userCashier',
        '$dateEmission',
        '$descripcion_historial',
        '$row->idProductoFiscal',
        '$invoiceCode',
        '$invoiceNumber',
        '$branchId'

        )") or die(mysqli_error($MySQLi));
      // //insertamos historial --------------------------------------------------------fin
    }
    #CAMBIO AQUÍ: usamos una variable $sql para más claridad y aplicamos lo dicho en (2)
    $idTotal = $row->quantity * $row->price;
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
       '$row->idProductoFiscal',
       '$row->codigo_actividad',
       '$row->codigo_producto_sin',

       '$row->product_code',
       '$row->product_name',
       '$row->quantity',
       
       '$row->unidad_medida',
       '$row->price',
       
       '$idTotal',

       '$invoiceNumber',


       '$idCotizacion',
       '$branchId',
       '$dateEmission',

       '$row->prodF'

   )") or die(mysqli_error($MySQLi));
  }
}
function obtener_codigo_sucursal()
{
  include './../conexion.php';
  session_start();
  $idUser = $_SESSION['idUser'];
  $queryUser = mysqli_query($MySQLi, "SELECT Ciudad FROM Usuarios WHERE idUser='$idUser' ") or die(mysqli_error($MySQLi));
  $dataUser = mysqli_fetch_assoc($queryUser);
  $Ciudad = $dataUser['Ciudad'];

  $sucursalesModel = new Sucursal();
  $sucursalUser = $sucursalesModel->where('Sucursal', $Ciudad);
  $codigo_sucursal = $sucursalUser[0]['codigo_factura'];
  return $codigo_sucursal;
}
