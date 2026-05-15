<?php
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

  "doble_emision" => $_POST['doble_emision'],
  "doble_invoice_id" => $_POST['doble_invoice_id'],
  "doble_invoice_number" => $_POST['doble_invoice_number'],
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
  // $codigo_sucursal = 0;
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
  $siatDescriptionStatus = ($cabeza_factura["idCotizacion"] == '-1') ? "Validada - Emision Directa Doble" : "VALIDADA";
  $countItems = count($respuesta_api->{'data'}->{'items'}); //no hay en la api ... contar manual
  $invoiceXml = ''; //no hay en la api ver de donde extraer
  $idCotizacion = $cabeza_factura["idCotizacion"];
  $branchId = (int) $respuesta_api->{'data'}->{'codigo_sucursal'};
  // if ($branchId == 0) {
  //   $branchId = 1;
  // }
  //cambio por ir cambiando de api en api y su cambio de sucursal
  switch ($branchId) {
    case 0: //llego 0 cocha y tiene que ser 1
      $branchId = 1; //cocha
      break;
    case 1: //llego 1 la paz  y tiene que ser 2
      $branchId = 2; //la paz
      break;
    case 4: //llego 4 y tiene que ser 3
      $branchId = 3; //santa cruz
      break;
    case 3: //llego 3 tarija y tiene que ser 4
      $branchId = 4; //tarija
      break;


    default:
      echo "Opción no válida";
  }

  $exceptionCode = $respuesta_api->{'data'}->{'data'}->{'excepcion'};
  $clientEmail = $cabeza_factura["email"];
  $tipoFactura = $respuesta_api->{'data'}->{'tipo_factura_documento'};

  $doble_emision = $cabeza_factura["doble_emision"]; //'si'
  $doble_invoice_id = $cabeza_factura["doble_invoice_id"];
  $doble_invoice_number = $cabeza_factura["doble_invoice_number"];

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
exceptionCode,clientEmail,tipoFactura,
doble_emision,doble_invoice_id,doble_invoice_number)
VALUES ('$nitEmissor','$clientReasonSocial','$clientNroDocument',
'$invoiceCode','$cuf','$invoiceNumber',
'$qrCode','$invoiceUrl','$dateEmission','$amountTotal','$amountTotalDiscount','$amountTotalCurrency',
'$userCashier','$siatCodeState','$siatCodeReception','$siatDescriptionStatus','$countItems','$invoiceXml','$idCotizacion','$branchId',
'$exceptionCode','$clientEmail','$tipoFactura',
'$doble_emision','$doble_invoice_id','$doble_invoice_number')");

  //guardamos en sistema yuli01 yuliimport el id_invoice y invoice_number de srl02 para que las 2 facturas esten relacionadas
  if ($sqlInsertFactura) {
    include './../conexion_yuliimport.php';
    $update_factura_yuliimport = mysqli_query(
      $YuliimportDB,
      "UPDATE
     `factura`
 SET
     `doble_emision` = 'si',
     `doble_invoice_id` = '$invoiceCode',
     `doble_invoice_number` = '$invoiceNumber'
 WHERE
     `invoiceCode` = '$doble_invoice_id' AND `invoiceNumber` = '$doble_invoice_number'"
    );



    // mysqli_close($YuliimportDB);
  }

  //update productos fiscales restando del stok los que ya facturamos
  $datosDecodeado = json_decode($array_productos);
  //print_r($datosDecodeado);
  foreach ($datosDecodeado as $row) {
    //updatemos los productosfiscales
    if ($row->prodF == 'si') {
      //son inventados no entran actualizarse ni historial fiscales

    }
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
  //guardamos el producto fiscal que llego de yuli01 el listado productosfiscales srl de srl02 si ya existe registramos en historial
  guardar_producto_fiscal_en_02srl($doble_invoice_id, $branchId, $doble_invoice_number, $userCashier, $dateEmission, $idCotizacion, $invoiceCode, $invoiceNumber);
}

function obtener_codigo_sucursal()
{
  include './../conexion.php';
  session_start();
  $idUser = $_SESSION['idUser'];
  $queryUser = mysqli_query($MySQLi, "SELECT Ciudad FROM Usuarios WHERE idUser='$idUser' ") or die(mysqli_error($MySQLi));
  $dataUser = mysqli_fetch_assoc($queryUser);
  $Ciudad = $dataUser['Ciudad'];
  if ($Ciudad == 'Cochabamba') {
    $codigo_sucursal = 0;
  }
  if ($Ciudad == 'La Paz') {
    $codigo_sucursal = 1;
  }
  if ($Ciudad == 'Tarija') {
    $codigo_sucursal = 3;
  }
  if ($Ciudad == 'Santa Cruz') {
    $codigo_sucursal = 4;
  }

  return $codigo_sucursal;
}

// Funcion guardar el productofiscal 01yuli a 02yulisrl en su inventario fiscal srl02
function guardar_producto_fiscal_en_02srl($invoice_id01, $branchId02, $invoiceNumber01, $vendedor02, $dateEmission02, $idCotizacion02, $invoice_id02, $invoiceNumber02)
{
  include './../conexion.php';
  include './../conexion_yuliimport.php';
  try {

    //buscamos la factura en 01yuli
    //idProductoFiscal de los nuevos registros vacio
    $array_idProductoFiscal02 = [];
    $q_detailInvoice01 = mysqli_query($YuliimportDB, "SELECT * FROM detailInvoice WHERE invoiceNumber='$invoiceNumber01' and prodF='si'");
    while ($d_detailInvoice01 = mysqli_fetch_assoc($q_detailInvoice01)) {
      $detailId01 = $d_detailInvoice01['detailId']; //idproducto detail invoice01

      //tabla productos fiscales 01 yuli sacamos datos
      $prodFis01 = mysqli_query($YuliimportDB, "SELECT * FROM productos_fiscales WHERE idProducto='$detailId01'");
      $dataprodFis01 = mysqli_fetch_assoc($prodFis01);
      $idProducto01 = $dataprodFis01['idProducto'];

      //buscamos si en 02srl existe ese fiscal para ver si insertar o updatear
      $q_existe_fiscal02 = mysqli_query($MySQLi, "SELECT * FROM productos_fiscales WHERE idProductoFiscal01yuli='$idProducto01'");
      $d_existe_fiscal02 = mysqli_fetch_assoc($q_existe_fiscal02);

      $rowcount = mysqli_num_rows($q_existe_fiscal02);
      if ($rowcount > 0) { //si existe solo metemos al historial no hay que updatear porque es
        // eproducto entrante se resta al mismo tiempo igual que nada
        //solo registrar el movimiento
        $producto_fiscal_existente02 = $d_existe_fiscal02['detalle'];
        $inicial_existente02 = (int) $d_existe_fiscal02['saldo_fisico'];
        $cantidad_facturada01 = (int)$d_detailInvoice01['qty'];
        $final_existente02 = $inicial_existente02 + $cantidad_facturada01;
        $idProducto_existente02 = (int)$d_existe_fiscal02['idProducto'];

        $array_idProductoFiscal02[] = $idProducto_existente02;


        //se agrego al producto fiscal ya existente
        // insertar_historial_fiscales02srl(
        //   '+',
        //   $producto_fiscal_existente02,
        //   $inicial_existente02,
        //   $cantidad_facturada01,
        //   $final_existente02,
        //   $vendedor02,
        //   $dateEmission02,
        //   'Se agrego la cantidad de ' . $cantidad_facturada01 . ' proveniente de la facturacion en HUIWU',
        //   $idProducto_existente02,
        //   $invoice_id02,
        //   $invoiceNumber02,
        //   $branchId02,
        //   $idCotizacion02
        // );
        //se desconto por facturar ese mismo instante **hacer pensar**

      } else { //si no existe recien se inserta con valor 0 saldo_fisico
        $codeProductSin02 = $dataprodFis01['codeProductSin'];
        $fecha_poliza02 = $dataprodFis01['fecha_poliza'];
        $codigo02 = $dataprodFis01['codigo'];

        $detalle02 = $dataprodFis01['detalle'];
        $saldo_fisico02 = 0; //es nuevo y se facturo al instante tonces es 0
        $c_u_facturar_minimo02 = $dataprodFis01['c_u_facturar_minimo'];
        $importes_para_facturar02 = $dataprodFis01['importes_para_facturar'];

        $fecha_subido_sistema02 = $dateEmission02;
        $idProductoFiscal01yuli = $dataprodFis01['idProducto']; //id productofiscal de yulii01


        //insertamos el nuevo producto fiscal a srl02 que llega de yuliimport01
        $q_productos_fiscales = mysqli_query($MySQLi, "INSERT INTO productos_fiscales (
        codeProductSin,fecha_poliza,
        codigo,detalle,
        saldo_fisico,c_u_facturar_minimo,
        importes_para_facturar,fecha_subido_sistema,
        idProductoFiscal01yuli
        )
        VALUES ('$codeProductSin02','$fecha_poliza02',
        '$codigo02','$detalle02',
        '$saldo_fisico02','$c_u_facturar_minimo02',
        '$importes_para_facturar02','$fecha_subido_sistema02',
        '$idProductoFiscal01yuli')");

        //updateamos en srl02 el detailInvoice para que apunte a este producto fiscal nuevo
        $idProducto_nueva02 = mysqli_insert_id($MySQLi);
        $array_idProductoFiscal02[] = $idProducto_nueva02;

        // $cantidad_nueva = (int)$d_detailInvoice01['qty'];
        //       $final_nueva = $cantidad_nueva;
        // Obtener el ID del último registro insertado


        // insertar_historial_fiscales02srl(
        //   '+',
        //   $detalle,
        //   '0',
        //   $cantidad_nueva,
        //   $final_nueva,
        //   $vendedor02,
        //   $dateEmission,
        //   'Producto Fiscal Facturado en HuiWu insertado nuevo en Fiscales SRL',
        //   $idProducto_nueva,
        //   $invoice_id,
        //   $invoiceNumber,
        //   $branchId,
        //   $idCotizacion
        // );
        // insertar_historial_fiscales02srl(
        //   '-',
        //   $detalle,
        //   $final_nueva,
        //   $cantidad_nueva,
        //   '0',
        //   $vendedor02,
        //   $dateEmission,
        //   'Descuento Facturacion Doble',
        //   $idProducto_nueva,
        //   $invoice_id,
        //   $invoiceNumber,
        //   $branchId,
        //   $idCotizacion
        // );

      }
    }
    //insertamos los id
    $count = 0;
    $q_detailInvoice02 = mysqli_query($MySQLi, "SELECT * FROM detailInvoice WHERE invoiceNumber='$invoiceNumber02'");
    while ($d_detailInvoice02 = mysqli_fetch_assoc($q_detailInvoice02)) {
      $id = $d_detailInvoice02['id'];
      $detailId = $array_idProductoFiscal02[$count];
      $q_update02 = mysqli_query($MySQLi, "UPDATE `detailInvoice` SET `detailId`='$detailId',`prodF`='si' WHERE `id`='$id';");
      $count = $q_update02 ? $count + 1 : $count;
    }
  } catch (Exception $e) {
    // Manejo de la excepción
    //echo "Se ha producido una excepción: " . $e->getMessage();
  }
}

function insertar_historial_fiscales02srl(
  $signo,
  $producto_fiscal,
  $inicial,
  $cantidad,
  $final,
  $vendedor,
  $dateEmission,
  $descripcion,
  $idProducto,
  $invoiceCode,
  $invoiceNumber,
  $branchId,
  $idCotizacion
) {
  include './../conexion.php';
  //insertamos historial --------------------------------------------------------ini
  $uno = ($signo == '-') ? -1 : 1;
  $cb = 0;
  if ($branchId == 1) {
    $cb = $cantidad * $uno;
  }

  $lp = 0;
  if ($branchId == 2) {
    $lp = $cantidad * $uno;
  }

  $sc = 0;
  if ($branchId == 3) {
    $sc = $cantidad * $uno;
  }

  $tj = 0;
  if ($branchId == 4) {
    $tj = $cantidad * $uno;
  };
  // //continuar xd diproducto
  $descripcion_historial = ($idCotizacion == '-1') ? "DESCUENTO STOCK-FACTURACION EMISION DIRECTA" : "DESCUENTO STOCK-FACTURACION VENDIDAS";
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
    '$inicial',
    '$cb',
    '$lp',
    '$sc',
    '$tj',
    '$final',
    '$vendedor',
    '$dateEmission',
    '$descripcion_historial',
    '$idProducto',
    '$invoiceCode',
    '$invoiceNumber',
    '$branchId'

    )") or die(mysqli_error($MySQLi));
  // //insertamos historial --------------------------------------------------------fin
}
