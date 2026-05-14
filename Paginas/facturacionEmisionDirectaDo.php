<?php
session_start();
include './../includes/conexion.php';
include './../includes/date.class.php';
include './../includes/TeleGram.php';
include './../includes/global.php';
require './../includes/librerias/phpMailer/vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
error_reporting(0);
$mail = new PHPMailer(true);
$Action = filter_var($_POST['action'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$sqlPrecioDolar = mysqli_query($MySQLi, "SELECT * FROM precio ");
$bddolar = mysqli_fetch_assoc($sqlPrecioDolar);
$PrecioDolar = $bddolar['precioDolar'];

$sqlurlcucu = mysqli_query($MySQLi, "SELECT * FROM token_access");
$dataurlcucu = mysqli_fetch_assoc($sqlurlcucu) or die(mysqli_error($MySQLi));
$urlcucu = $dataurlcucu['urlcucu'];
$to = $dataurlcucu['token'];
$email_automatico = $dataurlcucu['email_automatico'];

$now = date_create('now', timezone_open('America/La_Paz'))->format('Y-m-d H:i:s');

switch ($Action) {
    case 'GuardarProductoTemporalEmision':
        if (isset($_SESSION['idUser'])) {
            $ClaveTemporal = $_POST['ClaveTemp'];
            $idProducto = $_POST['idProducto'];
            $idProducto = $_POST['idProducto'];
            $PrecioLista = $_POST['PrecioLista'];
            $Cantidad = $_POST['Cantidad'];
            $PrecioEspecial = $_POST['PrecioEspecial'];

            //Recuperamos los datos del producto
            $queryProducto = mysqli_query($MySQLi, "SELECT * FROM productos_fiscales WHERE idProducto='$idProducto' ");
            $dataProducto = mysqli_fetch_assoc($queryProducto);
            $NameProducto = $dataProducto['Producto'];
            $NameMarca = $dataProducto['Marca'];
            $NameModelo = $dataProducto['Modelo'];
            $FullNameProd = $dataProducto['detalle'];
            $insertCotiza = mysqli_query($MySQLi, "INSERT INTO ClaveTemporal (Clave, idProducto, Cantidad, PrecioLista, PrecioOferta) VALUES ('$ClaveTemporal', '$idProducto', '$Cantidad', '$PrecioLista', '$PrecioEspecial') ");

            //consultamos los registros temporales generados
            $queryRegTem = mysqli_query($MySQLi, "SELECT * FROM ClaveTemporal WHERE Clave='$ClaveTemporal' ORDER BY id DESC ");
            $resulRegTem = mysqli_num_rows($queryRegTem);

            // Si la consulta encuentra registros ...
            echo "<script> actualizarclientCode(); </script>";
            echo "<script> actualizarTotal(); </script>";
            echo "<script>actualizarSubTotal(); </script>";

            $factura .= ' <div class="row mt-4">
                <div class="col">
                    <table class="table"  width="100%">
                        <thead class="thead-dark">
                            <tr>
                                <th scope="col" width="14%" class="text-center p-5"><h5>Cantidad</th>
                                <th scope="col" width="14%" class="text-center p-5"><h5>CodProd</th>
                                <th scope="col" width="35%" class="text-center p-5"><h5>Producto</th>
                                <th scope="col" width="13%" class="text-center p-5"><h5>PrecioUnidad Bs</th>


                                <th scope="col" width="13%" class="text-center p-5"><h5>SubTotal Bs</th>
                                <th scope="col" width="10%" class="text-center p-5"><h5>Eliminar</th>
                            </tr>
                        </thead>
                        <tbody>';
            $sqlPrecioDolar = mysqli_query($MySQLi, "SELECT * FROM precio ");
            $dolarBd = mysqli_fetch_assoc($sqlPrecioDolar);

            //session_start();
            $datos = array();

            $_SESSION["carrito"] = [];

            // $sqlClave = mysqli_query($MySQLi, "SELECT * FROM ClaveTemporal WHERE Clave='$clave' ");

            $count = 0;
            while ($data = mysqli_fetch_assoc($queryRegTem)) {

                $idClaveTemp = $data['id'];
                $idProducto = $data['idProducto'];
                $sqlProducto = mysqli_query($MySQLi, "SELECT * FROM productos_fiscales WHERE idProducto='$idProducto' ");
                $dataProducto = mysqli_fetch_assoc($sqlProducto);
                $codeProduct = $dataProducto['codigo'];
                $codeProductSin = $dataProducto['codeProductSin'];
                $ProductoName = $dataProducto['detalle'];

                $saldo_fisico = (int) $dataProducto['saldo_fisico']; //maximo que se puede jalar al carrito
                $c_u_facturar_minimo = (int) $dataProducto['c_u_facturar_minimo']; //minimo para facturar c_u_facturar_minimo

                $qty = $data['Cantidad'];
                $priceUnit = number_format(($data['PrecioOferta']), 2, ".", "");

                $factura .= '
                                        <tr>
                                            <td>
                                            <input class="form-control text-center" min="1" max="' . $saldo_fisico . '" type="number"  name="' . $count . 'qty" id="' . $count . 'qty" saldo_fisico="' . $saldo_fisico . '"  onchange="actualizarSubTotal()" oninput="actualizarSubTotal()" value="' . $data['Cantidad'] . '">
                                            <label for="' . $count . 'qty"> SaldoFisico: <b>' . $saldo_fisico . '</b></label>
                                            </td>'; //CANTIDAD INPUT qty

                $factura .= '           <td><input class="form-control" name="' . $count . 'codeProduct" value="' . $codeProduct . '"></td>
                                            <td><input class="form-control" name="' . $count . 'description" id="' . $count . 'description" value="' . $ProductoName . '" ></td>'; //NOMBRE PRODUCTO INPUT

                $factura .= '           <td>

                                                <input type="number" class="form-control text-right"  min="' . $c_u_facturar_minimo . '" name="' . $count . 'priceUnit" id="' . $count . 'priceUnit" c_u_facturar_minimo="' . $c_u_facturar_minimo . '" onchange="actualizarCantidad(' . $count . ',' . $c_u_facturar_minimo . ')" oninput="actualizarCantidad(' . $count . ',' . $c_u_facturar_minimo . ')" value="' . $priceUnit . '">
                                                <label for="' . $count . 'priceUnit">Facturar Minimo:' . $c_u_facturar_minimo . '</label>

                                        </td>
                                            <td ><input class="form-control text-right" readonly  name="' . $count . 'subTotal" id="' . $count . 'subTotal"  value="" ></td>
                                            <td class="text-center p-5"> <input type="button" class="btn btn-info deleteProdTempEmision" id="' . $idClaveTemp . '" value="Eliminar" title="' . $idClaveTemp . '" ></td>

                                        </tr>';

                $datos[$count] = array(
                    'activityEconomic' => '465000',
                    'unitMeasure' => 62,
                    'codeProductSin' => $codeProductSin,
                    // 'codeProductSin' => 99795,
                    'codeProduct' => $codeProduct,
                    'description' => $ProductoName,
                    'qty' => (int) $qty,
                    'priceUnit' => $priceUnit,
                    'idProducto' => $idProducto,

                );
                $count++;

            }

            $_SESSION["carrito"] = $datos;
            //print_r($datos);

            $factura .= '       <thead class="thead-light">
                                    <tr>
                                        <th colspan="4" class="text-right p-4 "><strong><h4>TOTAL</h4></strong></th>';
            $sqlClave2 = mysqli_query($MySQLi, "SELECT SUM(cantidad*PrecioOferta)AS total FROM ClaveTemporal WHERE Clave='$clave' ");
            $dataTotal = mysqli_fetch_assoc($sqlClave2);
            $factura .= '
                                        <th scope="col">
                                                    <input class="form-control text-right" readonly name="total" id="total" value="' . number_format($dataTotal['total'] * $dolarBd['precioDolar'], 2, ".", "") . '">
                                                    <input name="count" id="count" type="hidden" value="' . $count . '">

                                        </th>
                                        <th scope="col" class="text-left p-4 "><strong><h4>Bs</h4></strong></th>
                                    </tr></thead>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="row mt-4">
                        <div class="col">

                        </div>
                    </div>
                    </form>
                    ';
            //print("CARRITO /n");
            //print_r($_SESSION["carrito"]);
            echo $factura; //formulario factura llenado mostramos
            //echo $nitCliente;

        } else {mysqli_close($MySQLi);
            session_destroy();?>
<script type="text/javascript">
Swal.fire({
    type: 'error',
    title: 'Sesión caducada',
})
setTimeout(function() {
    location.reload();
}, 2500);
</script> <?php
}
        break;

    case 'FacturaEmisionDirecta':
        if (isset($_SESSION['idUser'])) {

            //cabezera usando true
            $idUser = $_SESSION['idUser'];

            $miCiudad = $_POST['miCiudad'];

            $Cliente_Existente = $_POST['Cliente_Existente'];

            $NombreCliente = $_POST['Nombre'];

            $ApellidoCliente = $_POST['Apellido'];

            $clientEmail = $_POST['Correo'];if (!$clientEmail) {
                $clientEmail = $email_automatico;
            }

            $clientNroDocument = $_POST['NIT'];

            $clientCity = $_POST['Ciudad'];

            $clientReasonSocial = "$NombreCliente $ApellidoCliente";if (!$clientReasonSocial) {$clientReasonSocial = "CONTROL TRIBUTARIO";}

            ?>


<?php

            $clientDocumentType = (int) $_POST["clientDocumentType"];

            $clientCode = $_POST["clientCode"];

            $userPos = $_POST["userPos"]; //pendiente base

            $paramCurrency = (int) $_POST["paramCurrency"];

            $paramPaymentMethod = (int) $_POST["paramPaymentMethod"];
            $FormaPagoCliente = 'Efectivo';

            echo "<script> actualizarclientCode(); </script>";
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
            #modal actualizacion si editaron desde el modal con tabla actualizamos el carrito

            for ($i = 0; $i < count($_SESSION["carrito"]); $i++) {

                $qty = (int) $_POST[$i . 'qty'];
                $_SESSION["carrito"][$i]['qty'] = $qty;

                $description = $_POST[$i . 'description'];
                $_SESSION["carrito"][$i]['description'] = $description;

                $codeProduct = $_POST[$i . 'codeProduct'];
                $_SESSION["carrito"][$i]['codeProduct'] = $codeProduct;

                $priceUnit = $_POST[$i . 'priceUnit'];
                $_SESSION["carrito"][$i]['priceUnit'] = number_format(($priceUnit), 2, ".", "");

                $subTotal = $_POST[$i . 'subTotal'];
                $_SESSION["carrito"][$i]['subTotal'] = $subTotal;

            }

            $datos = ($_SESSION["carrito"]);
            //print('datos para facturar');
            //print_r($datos);

            $Aleatorio = uniqid();
            $Aleatorio = substr($Aleatorio, -4);
            $Aleatorio = strtoupper($Aleatorio);
            if ($miCiudad == 'Santa Cruz') {
                $CCode = 'SC';
            } elseif ($miCiudad == 'Cochabamba') {
                $CCode = 'CB';
            } elseif ($miCiudad == 'La Paz') {
                $CCode = 'LP';
            } else {
                $CCode = 'TJ';
            }
            $Code = $CCode . "-" . date("y") . date('m') . date('d') . "-" . $Aleatorio;

            //Si el Cliente no existe, lo Guardamos en la base de datos------------------------------------NO EXISTE ----------CLIENTE
            if ($Cliente_Existente == '0') {
                $insertNewCliente = mysqli_query($MySQLi, "INSERT INTO Clientes (Nombres, Apellidos, Correo, Empresa, NIT, Celular, Otro, Ciudad, Direccion, Comentarios, Fecha_Reg, Registrador, Sucursal) VALUES ('$NombreCliente', '$ApellidoCliente', '$clientEmail', '$EmpresaCliente', '$clientNroDocument', '$CelularCliente', '$FijoCliente', '$clientCity', '$DireccionCliente', '$Observaciones', '$fecha', '$idUser', '$miCiudad') ") or die(mysqli_error($MySQLi));
                if ($insertNewCliente) {
                    // llamamos la clave temporales generada

                    $ClaveTemp = $_POST['ClaveTemporalCotiza'];
                    //validar nitCliente
                    if ($clientDocumentType == 5) {
                        $endpoint = '/api/v1/codes/nit';
                        $data = array(
                            'posId' => 1,
                            'nit' => $clientNroDocument,
                        );
                        $url = $urlcucu . $endpoint . '?' . http_build_query($data);
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

                    $url = $urlcucu . '/api/v1/invoice/computarized/sale';
                    $ch = curl_init($url);
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

                        "detailInvoice" => $datos,

                    );
                    unset($_SESSION["carrito"]);

                    $payload = json_encode(($data));
                    //print_r($payload);

                    curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
                    curl_setopt($ch, CURLOPT_HTTPHEADER, array("cucukey: Token $to", "Content-Type: application/json"));
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//execute the POST request
                    $result = curl_exec($ch);
                    $datosF = json_decode($result);
//print_r($datosF);
                    $var = $datosF->{'data'};
                    $varemail = $var->{'invoiceCode'};
                    $invoiceNumber = $var->{'invoiceNumber'};
                    $dateEmission = $var->{'dateEmission'};
                    $save = $datosF->{'message'};
//print_r($save);
                    if ($save == 'COMPLETED_SALE') {
                        $urle = $urlcucu . '/api/v1/email';
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

                        if ($clientEmail != $email_automatico) {

                            $urle1 = $urlcucu . '/api/v1/email';
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

                    '$branchId',
                    '$exceptionCode',
                    '$clientEmail',
                    '$tipoFactura'

				)") or die(mysqli_error($MySQLi));

                        $detail = $var->{'detailInvoice'};
                        //print_r($detail);
                        //echo "----------<br>";
                        //print_r($datos);
                        //echo "----------<br>--------";
                        $datosEncodeado = json_encode($datos); //print_r($datosEncodeado);
                        //echo "----------<br>--";
                        $datosDecodeado = json_decode($datosEncodeado); //print_r($datosDecodeado);

                        // $productosTemp = mysqli_query($MySQLi, "SELECT * FROM ClaveTemporal WHERE Clave='$ClaveTemporal' ORDER BY id DESC ");
                        // $dataprodTemp = mysqli_fetch_assoc($productosTemp);

                        foreach ($datosDecodeado as $row) {
                            #CAMBIO AQUÍ: usamos una variable $sql para más claridad y aplicamos lo dicho en (2)
                            $idTotal = $row->qty * $row->priceUnit;

                            //actualizamos productos fiscales--------------------------------------------------------ini
                            $prodFis = mysqli_query($MySQLi, "SELECT * FROM productos_fiscales WHERE idProducto='$row->idProducto'");
                            $dataprodFis = mysqli_fetch_assoc($prodFis);
                            $stockActual = (int) $dataprodFis['saldo_fisico'];
                            $stockNuevo = $stockActual - $row->qty;
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
                                'DESCUENTO STOCK-FACTURACION EMISION DIRECTA',
                                '$row->idProducto',
                                '$var->invoiceCode',
                                '$var->invoiceNumber',
                                '$branchId'

                                )") or die(mysqli_error($MySQLi));
                            //insertamos historial --------------------------------------------------------fin

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
                            '$branchId',
                            '$dateEmission',
                            'si'




                        )") or die(mysqli_error($MySQLi));

                        }

                    }

                    $alert = $datosF->{'message'};

                    $newCallCli = mysqli_query($MySQLi, "SELECT * FROM Clientes WHERE NIT='$clientNroDocument' ");
                    //$resultCliente = mysqli_num_rows($callNewCliente);

                    $dataNewCall = mysqli_fetch_assoc($newCallCli);
                    $NewCliente = $dataNewCall['idCliente'];

                    // $insertCotizacion = mysqli_query($MySQLi, "INSERT INTO Cotizaciones (Code, Clave, idUser,
                    // idCliente, Forma_Pago, FinFecha_Oferta, Dias_Entrega, Comentarios, Sucursal, Fecha, Hora,
                    // Entregada,Compra) VALUES ('$Code', '$ClaveTemp', '$idUser', '$NewCliente', '$FormaPagoCliente',
                    // '$fecha', '$TiempoEntrega', '$Observaciones', '$miCiudad', '$fecha', '$Hora','$now','$fecha') ") or die(mysqli_error($MySQLi));

                    $sqlCotizacion = mysqli_query($MySQLi, "SELECT * FROM Cotizaciones WHERE Clave='$ClaveTemp' ");
                    $dataCotiza = mysqli_fetch_assoc($sqlCotizacion);

                    //$sqlcotizacion= mysqli_query($MySQLi, "SELECT * FROM Cotizaciones WHERE Clave='$Clave'");
                    //$dataCotizacion=mysqli_fetch_assoc($queryCotiza);
                    $idCoti = (int) $dataCotiza['idCotizacion'];
                    $idCliente = $dataCotiza['idCliente'];
                    $CodeCotiza = $dataCotiza['Code'];

                    $Moneda = 'Bs';
                    //ac

                    // $update = mysqli_query($MySQLi, "UPDATE Cotizaciones SET Estado=2 WHERE idCotizacion='$idCoti'");
                    // $ChangeStatus = mysqli_query($MySQLi, "UPDATE Cotizaciones SET Estado=2, Compra='$fecha' WHERE idCotizacion='$idCoti' ");

                    $ChangeFactura = mysqli_query($MySQLi, "UPDATE factura SET siatDescriptionStatus='Validada - Emision Directa' WHERE invoiceNumber='$var->invoiceNumber' ");
                    // $ChangeDetailInvoice = mysqli_query($MySQLi, "UPDATE detailInvoice SET idCotizacion=$idCoti WHERE invoiceNumber='$var->invoiceNumber' ");
                    //$ChangeClienteNit = mysqli_query($MySQLi, "UPDATE Clientes SET NIT='$clientNroDocument' WHERE idCliente='$idCliente' ");

                    //    LLAMAMOS LOS PRODUCTOS DE LA COTIZACION CON LA CLAVE TEMPORAL
                    $queryProductos = mysqli_query($MySQLi, "SELECT * FROM detailInvoice WHERE idCotizacion='$idCoti' ");
                    // while ($dataProducto = mysqli_fetch_assoc($queryProductos)) {
                    //     $idProducto = (int) $dataProducto['detailId'];
                    //     $CantidadPro = $dataProducto['qty'];
                    //     $PrecioLista = $dataProducto['priceUnit'];
                    //     $PrecioListaBs = $dataProducto['priceUnit'];
                    //     $PrecioVenta = $dataProducto['PrecioOferta']; //este es el precio en dólares por default
                    //     $PrecioVentaBs = $PrecioVenta * $PrecioDolar;
                    //     $TotalVentaUS = $CantidadPro * $PrecioVenta;
                    //     $TotalVentaBs = $CantidadPro * $PrecioVentaBs;
                    //     $Sucursal = $_POST['miCiudad']; //    Sucursal
                    //     $sqlProductos = mysqli_query($MySQLi, "SELECT * FROM Productos WHERE idProducto='$idProducto' ");
                    //     $stockProductos = mysqli_fetch_assoc($sqlProductos);

                    // }

                    if ($save == 'COMPLETED_SALE') {mysqli_close($MySQLi);?>
<script type="text/javascript">
Swal.fire({
    type: 'success',
    title: 'Facturación Exitosa',
    animation: false,
    customClass: {
        popup: 'animated bounceInDown'
    },
    text: "Factura y Cliente Guardados",
    timer: 5000,
})
setTimeout(function() {
    location.replace("?root=facturacionEmision");
}, 2000);
</script><?php
} else {mysqli_close($MySQLi);?>
<script type="text/javascript">
Swal.fire({
    type: 'error',
    title: 'Error De Conexion Intente De Nuevo ',
    timer: 5000,
})
setTimeout(function() {
    location.replace("?root=facturacionEmision");
}, 2000);
</script> <?php
}
                } else {mysqli_close($MySQLi);?>
<script type="text/javascript">

</script> <?php exit();
                }

//----------------------------------------------------- CLIENTE EXISTENTE ----------------------------------------------------------------------------------
            } else {
                $updateCliente = mysqli_query($MySQLi, "UPDATE Clientes SET  Correo='$clientEmail', NIT='$clientNroDocument' WHERE idCliente='$Cliente_Existente' ") or die(mysqli_error($MySQLi));
                if ($updateCliente) {
                    // llamamos la clave temporales generada
                    $ClaveTemp = $_POST['ClaveTemporalCotiza'];

                    //validar nitCliente
                    if ($clientDocumentType == 5) {
                        $endpoint = '/api/v1/codes/nit';
                        $data = array(
                            'posId' => 1,
                            'nit' => $clientNroDocument,
                        );
                        $url = $urlcucu . $endpoint . '?' . http_build_query($data);
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

                    $url = $urlcucu . '/api/v1/invoice/computarized/sale';
                    $ch = curl_init($url);
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

                        "detailInvoice" => $datos,

                    );

                    $payload = json_encode(($data));
// print_r($payload);

                    curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
                    curl_setopt($ch, CURLOPT_HTTPHEADER, array("cucukey: Token $to", "Content-Type: application/json"));
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//execute the POST request
                    $result = curl_exec($ch);
                    $datosF = json_decode($result);

                    $var = $datosF->{'data'};
                    $varemail = $var->{'invoiceCode'};
                    $invoiceNumber = $var->{'invoiceNumber'};
                    $dateEmission = $var->{'dateEmission'};
                    $save = $datosF->{'message'};
// print_r($datosF);
                    if ($save == 'COMPLETED_SALE') {
                        $urle = $urlcucu . '/api/v1/email';
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

                        if ($clientEmail != $email_automatico) {

                            $urle1 = $urlcucu . '/api/v1/email';
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

                    '$branchId',
                    '$exceptionCode',
                    '$clientEmail',
                    '$tipoFactura'

				)") or die(mysqli_error($MySQLi));

                        $detail = $var->{'detailInvoice'};
                        //print_r($detail);
                        //echo "----------<br>";
                        //print_r($datos);
                        //echo "----------<br>--------";
                        $datosEncodeado = json_encode($datos); //print_r($datosEncodeado);
                        //echo "----------<br>--";
                        $datosDecodeado = json_decode($datosEncodeado); //print_r($datosDecodeado);

                        foreach ($datosDecodeado as $row) {
                            #CAMBIO AQUÍ: usamos una variable $sql para más claridad y aplicamos lo dicho en (2)
                            $idTotal = $row->qty * $row->priceUnit;

                            //actualizamos productos fiscales--------------------------------------------------------ini
                            $prodFis = mysqli_query($MySQLi, "SELECT * FROM productos_fiscales WHERE idProducto='$row->idProducto'");
                            $dataprodFis = mysqli_fetch_assoc($prodFis);
                            $stockActual = (int) $dataprodFis['saldo_fisico'];
                            $stockNuevo = $stockActual - $row->qty;
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
                            'DESCUENTO STOCK-FACTURACION EMISION DIRECTA',
                            '$row->idProducto',
                            '$var->invoiceCode',
                            '$var->invoiceNumber',
                            '$branchId'

                            )") or die(mysqli_error($MySQLi));
                            //insertamos historial --------------------------------------------------------fin

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
                            '$branchId',
                            '$dateEmission',
                            'si'



                        )") or die(mysqli_error($MySQLi));

                        }

                    }

                    $alert = $datosF->{'message'};

                    // $Clave = $data['Clave'];
                    // $idProd = $data['idProducto'];
                    // $Cantidad = $data['Cantidad'];
                    // $PreList = $data['PrecioLista'];
                    // $PreOfer = $data['PrecioOferta'];

                    ?>

<?php

//                     $insertCotizacion = mysqli_query($MySQLi, "INSERT INTO Cotizaciones (Code, Clave, idUser,
// idCliente, Forma_Pago, FinFecha_Oferta, Dias_Entrega, Comentarios, Sucursal, Fecha, Hora,
// Entregada,Compra) VALUES ('$Code', '$ClaveTemp', '$idUser', '$Cliente_Existente', '$FormaPagoCliente',
// '$fecha', '$TiempoEntrega', '$Observaciones', '$miCiudad', '$fecha', '$Hora','$now','$fecha') ") or die(mysqli_error($MySQLi));

                    $sqlCotizacion = mysqli_query($MySQLi, "SELECT * FROM Cotizaciones WHERE Clave='$ClaveTemp' ");
                    $dataCotiza = mysqli_fetch_assoc($sqlCotizacion);

                    //$sqlcotizacion= mysqli_query($MySQLi, "SELECT * FROM Cotizaciones WHERE Clave='$Clave'");
                    //$dataCotizacion=mysqli_fetch_assoc($queryCotiza);
                    $idCoti = (int) $dataCotiza['idCotizacion'];
                    $idCliente = $dataCotiza['idCliente'];
                    $CodeCotiza = $dataCotiza['Code'];

                    $Moneda = 'Bs';

                    // $update = mysqli_query($MySQLi, "UPDATE Cotizaciones SET Estado=2 WHERE idCotizacion='$idCoti'");
                    // $ChangeStatus = mysqli_query($MySQLi, "UPDATE Cotizaciones SET Estado=2, Compra='$fecha' WHERE idCotizacion='$idCoti' ");

                    $ChangeFactura = mysqli_query($MySQLi, "UPDATE factura SET siatDescriptionStatus='Validada - Emision Directa' WHERE invoiceNumber='$var->invoiceNumber' ");
                    // $ChangeDetailInvoice = mysqli_query($MySQLi, "UPDATE detailInvoice SET idCotizacion=$idCoti WHERE invoiceNumber='$var->invoiceNumber' ");
                    $ChangeClienteNit = mysqli_query($MySQLi, "UPDATE Clientes SET NIT='$clientNroDocument' WHERE idCliente='$idCliente' ");

                    //    LLAMAMOS LOS PRODUCTOS DE LA COTIZACION CON LA CLAVE TEMPORAL
                    $queryProductos = mysqli_query($MySQLi, "SELECT * FROM detailInvoice WHERE idCotizacion='$idCoti' ");
                    while ($dataProducto = mysqli_fetch_assoc($queryProductos)) {
                        $idProducto = (int) $dataProducto['detailId'];
                        $CantidadPro = $dataProducto['qty'];
                        $PrecioLista = $dataProducto['priceUnit'];
                        $PrecioListaBs = $dataProducto['priceUnit'];
                        $PrecioVenta = $dataProducto['PrecioOferta'];
                        $PrecioVentaBs = $PrecioVenta * $PrecioDolar;
                        $TotalVentaUS = $CantidadPro * $PrecioVenta;
                        $TotalVentaBs = $CantidadPro * $PrecioVentaBs;
                        $Sucursal = $_POST['miCiudad']; //    Sucursal
                        $sqlProductos = mysqli_query($MySQLi, "SELECT * FROM Productos WHERE idProducto='$idProducto' ");
                        $stockProductos = mysqli_fetch_assoc($sqlProductos);

                    }

                    if ($save == 'COMPLETED_SALE') {mysqli_close($MySQLi);?>
<script type="text/javascript">
Swal.fire({
    type: 'success',
    title: 'Facturación Exitosa',
    animation: false,
    customClass: {
        popup: 'animated bounceInDown'
    },
    text: "Factura Guardada Correctamente",
    timer: 5000,
})
setTimeout(function() {
    location.replace("?root=facturacionEmision");
}, 2000);
</script><?php
} else {mysqli_close($MySQLi);?>
<script type="text/javascript">
Swal.fire({
    type: 'error',
    title: 'Error De Conexion Intente De Nuevo ',
    timer: 5000,
})
setTimeout(function() {
    location.replace("?root=facturacionEmision");
}, 2000);
</script> <?php
}
                } else {mysqli_close($MySQLi);?>
<script type="text/javascript">

</script> <?php
}
            }
        } else {mysqli_close($MySQLi);
            session_destroy();?>
<script type="text/javascript">

</script> <?php
}
        break;

    case 'BorrarProductoTemporal':
        if (isset($_SESSION['idUser'])) {
            $idProductoTemp = $_POST['id'];
            $ClaveTemporal = $_POST['Clave'];

            $delProdTemp = mysqli_query($MySQLi, "DELETE FROM ClaveTemporal WHERE id='$idProductoTemp' ");

//consultamos los registros temporales generados
            $queryRegTem = mysqli_query($MySQLi, "SELECT * FROM ClaveTemporal WHERE Clave='$ClaveTemporal' ORDER BY id DESC ");
            $resulRegTem = mysqli_num_rows($queryRegTem);

// Si la consulta encuentra registros ...
            echo "<script> actualizarclientCode(); </script>";
            echo "<script> actualizarTotal(); </script>";
            echo "<script>actualizarSubTotal(); </script>";

            $factura .= ' <div class="row mt-4">
    <div class="col">
        <table class="table"  width="100%">
            <thead class="thead-dark">
                <tr>
                    <th scope="col" width="14%" class="text-center p-5"><h5>Cantidad</th>
                    <th scope="col" width="14%" class="text-center p-5"><h5>CodProd</th>
                    <th scope="col" width="35%" class="text-center p-5"><h5>Producto</th>
                    <th scope="col" width="13%" class="text-center p-5"><h5>PrecioUnidad Bs</th>


                    <th scope="col" width="13%" class="text-center p-5"><h5>SubTotal Bs</th>
                    <th scope="col" width="10%" class="text-center p-5"><h5>Eliminar</th>
                </tr>
            </thead>
            <tbody>';
            $sqlPrecioDolar = mysqli_query($MySQLi, "SELECT * FROM precio ");
            $dolarBd = mysqli_fetch_assoc($sqlPrecioDolar);

//session_start();
            $datos = array();

            $_SESSION["carrito"] = [];

// $sqlClave = mysqli_query($MySQLi, "SELECT * FROM ClaveTemporal WHERE Clave='$clave' ");

            $count = 0;
            while ($data = mysqli_fetch_assoc($queryRegTem)) {

                $idClaveTemp = $data['id'];
                $idProducto = $data['idProducto'];
                $sqlProducto = mysqli_query($MySQLi, "SELECT * FROM productos_fiscales WHERE idProducto='$idProducto' ");
                $dataProducto = mysqli_fetch_assoc($sqlProducto);
                $codeProduct = $dataProducto['codigo'];
                $codeProductSin = $dataProducto['codeProductSin'];
                $ProductoName = $dataProducto['detalle'];

                $saldo_fisico = (int) $dataProducto['saldo_fisico']; //maximo que se puede jalar al carrito
                $c_u_facturar_minimo = (int) $dataProducto['c_u_facturar_minimo']; //minimo para facturar c_u_facturar_minimo

                $qty = $data['Cantidad'];
                $priceUnit = number_format(($data['PrecioOferta']), 2, ".", "");

                $factura .= '
                                        <tr>
                                            <td>
                                            <input class="form-control text-center" min="1" max="' . $saldo_fisico . '" type="number"  name="' . $count . 'qty" id="' . $count . 'qty" saldo_fisico="' . $saldo_fisico . '"  onchange="actualizarSubTotal()" oninput="actualizarSubTotal()" value="' . $data['Cantidad'] . '">
                                            <label for="' . $count . 'qty"> SaldoFisico: <b>' . $saldo_fisico . '</b></label>
                                            </td>'; //CANTIDAD INPUT qty

                $factura .= '           <td><input class="form-control" name="' . $count . 'codeProduct" value="' . $codeProduct . '"></td>
                                            <td><input class="form-control" name="' . $count . 'description" id="' . $count . 'description" value="' . $ProductoName . '" ></td>'; //NOMBRE PRODUCTO INPUT

                $factura .= '           <td>

                                                <input type="number" class="form-control text-right"  min="' . $c_u_facturar_minimo . '" name="' . $count . 'priceUnit" id="' . $count . 'priceUnit" c_u_facturar_minimo="' . $c_u_facturar_minimo . '" onchange="actualizarCantidad(' . $count . ',' . $c_u_facturar_minimo . ')" oninput="actualizarCantidad(' . $count . ',' . $c_u_facturar_minimo . ')" value="' . $priceUnit . '">
                                                <label for="' . $count . 'priceUnit">Facturar Minimo:' . $c_u_facturar_minimo . '</label>

                                        </td>
                                            <td ><input class="form-control text-right" readonly  name="' . $count . 'subTotal" id="' . $count . 'subTotal"  value="" ></td>
                                            <td class="text-center p-5"> <input type="button" class="btn btn-info deleteProdTempEmision" id="' . $idClaveTemp . '" value="Eliminar" title="' . $idClaveTemp . '" ></td>

                                        </tr>';

                $datos[$count] = array(
                    'activityEconomic' => '465000',
                    'unitMeasure' => 62,
                    'codeProductSin' => $codeProductSin,
                    // 'codeProductSin' => 99795,
                    'codeProduct' => $codeProduct,
                    'description' => $ProductoName,
                    'qty' => (int) $qty,
                    'priceUnit' => $priceUnit,
                    'idProducto' => $idProducto,

                );
                $count++;

            }

            $_SESSION["carrito"] = $datos;
//print_r($datos);

            $factura .= '       <thead class="thead-light">
                        <tr>
                            <th colspan="4" class="text-right p-4 "><strong><h4>TOTAL</h4></strong></th>';
            $sqlClave2 = mysqli_query($MySQLi, "SELECT SUM(cantidad*PrecioOferta)AS total FROM ClaveTemporal WHERE Clave='$clave' ");
            $dataTotal = mysqli_fetch_assoc($sqlClave2);
            $factura .= '
                            <th scope="col">
                                        <input class="form-control text-right" readonly name="total" id="total" value="' . number_format($dataTotal['total'] * $dolarBd['precioDolar'], 2, ".", "") . '">
                                        <input name="count" id="count" type="hidden" value="' . $count . '">

                            </th>
                            <th scope="col" class="text-left p-4 "><strong><h4>Bs</h4></strong></th>
                        </tr></thead>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="row mt-4">
            <div class="col">

            </div>
        </div>
        </form>
        ';

            echo $factura; //formulario factura llenado mostramos
//echo $nitCliente;
//print_r($_SESSION["carrito"]);
//echo " gaagaaa";

        } else {mysqli_close($MySQLi);
            session_destroy();?>
<script type="text/javascript">
Swal.fire({
    type: 'error',
    title: 'Sesión caducada',
})
setTimeout(function() {
    location.reload();
}, 2500);
</script> <?php
}
        break;

    default: ?>
<script type="text/javascript">


</script><?php
break;

}
?>