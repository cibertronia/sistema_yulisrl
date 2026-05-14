<?php
include './../includes/conexion.php';
//error_reporting(0);

$sqlurlcucu = mysqli_query($MySQLi, "SELECT * FROM token_access");
$dataurlcucu = mysqli_fetch_assoc($sqlurlcucu) or die(mysqli_error($MySQLi));
$urlcucu=$dataurlcucu['urlcucu'];
$to = $dataurlcucu['token'];

$posId = 1;

$invoiceCode = $_POST['invoiceCode1'];

$invoiceNumber = $_POST['invoiceNumber1'];
$invoiceNumber = (int) $invoiceNumber;

$codeMotive = $_POST['codeMotive1'];
$codeMotive = (int) $codeMotive;

$tipoFactura = $_POST['tipoFactura1'];
$tipoFactura = (int) $tipoFactura;

$branchId = $_POST['branchId1'];


$clientEmail = $_POST['clientEmail1'];if (!$clientEmail) {$clientEmail = 'facturacion@yuliimport.com';}

if ($tipoFactura == 1) {
    //anular FACTURA NORMAL
    $url = $urlcucu.'/api/v1/invoice/computarized/sale/anulation';
    $ch = curl_init($url);
    $data = array(
        'posId' => $posId,
        'invoiceCode' => $invoiceCode,
        'invoiceNumber' => $invoiceNumber,
        'codeMotive' => $codeMotive,
        'branchId' => $branchId,

    );
    $payload = json_encode(($data));

    curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
    
   
    curl_setopt($ch, CURLOPT_HTTPHEADER, array("cucukey: Token $to", "Content-Type: application/json"));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $result = curl_exec($ch);
    $datos = json_decode($result);
    
    $var = $datos->{'data'};
    $icode = $var->{'invoiceCode'};

    $datosF = json_decode($result);
    $var = $datosF->{'data'};
    $varemail = $var->{'invoiceCode'};
    $save = $datosF->{'message'};

    curl_close($ch); //no tocar no es mio xd

    $alert = $datos->{'message'};
    
//FIN NORMAL

} else {
    //ANULACION DEBITO-CREDITO---------------------------

    $url = $urlcucu.'/api/v1/invoice/computarized/debit/anulation';
    $ch = curl_init($url);
    $data = array(
        'posId' => $posId,
        'invoiceCode' => $invoiceCode,
        'invoiceNumber' => $invoiceNumber,
        'codeMotive' => $codeMotive,
        'branchId' => $branchId,
    );
    $payload = json_encode($data);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);


    curl_setopt($ch, CURLOPT_HTTPHEADER, array("cucukey: Token $to", "Content-Type: application/json"));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $result = curl_exec($ch);
    $datos = json_decode($result);
    
    
    $var = $datos->{'data'};
    $icode = $var->{'invoiceCode'};

    $datosF = json_decode($result);
    $var = $datosF->{'data'};
    $varemail = $var->{'invoiceCode'};
    $save = $datosF->{'message'};

    curl_close($ch); //no tocar no es mio xd

    $alert = $datos->{'message'};

//FIN ANULACION DEBITO

}
if ($save == 'COMPLETED_SALE' or $save == 'COMPLETED_DEBIT') {
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
    $sql = mysqli_query($MySQLi, "UPDATE factura SET siatDescriptionStatus='ANULACION CONFIRMADA',siatCodeState=905 WHERE invoiceCode='$icode'") or die(mysqli_error($MySQLi));


//actualizamos productos fiscales sumando el anulado a la tabla prodfiscales
    $prodF = mysqli_query($MySQLi, "SELECT * FROM detailInvoice WHERE invoiceNumber='$invoiceNumber' and prodF='si' and branchId='$branchId' ");
    while ($dataprodF = mysqli_fetch_assoc($prodF)) {
        $detailId=$dataprodF['detailId'];//idproducto detail invoice
        $qtydevolver=(int)$dataprodF['qty'];//cantidad a devolver

        $prodFis = mysqli_query($MySQLi, "SELECT * FROM productos_fiscales WHERE idProducto='$detailId'");
        $dataprodFis = mysqli_fetch_assoc($prodFis);

        
        $idProducto=$dataprodFis['idProducto'];
        $stockActual=(int) $dataprodFis['saldo_fisico'];
        $stockNuevo=$stockActual+$qtydevolver;
        $updateProdFi = mysqli_query($MySQLi, "UPDATE productos_fiscales SET saldo_fisico='$stockNuevo' WHERE idProducto='$idProducto' ");

        //insertamos historial --------------------------------------------------------ini
        date_default_timezone_set('America/La_Paz');
        $fechaActual = date('c');
        $producto_fiscal = $dataprodFis['detalle'];
        $branchId=$dataprodF['branchId'];
        session_start();
        $idUser = $_SESSION['idUser'];
        $ConsltaUser = mysqli_query($MySQLi, "SELECT * FROM Usuarios WHERE idUser='$idUser' ");
        $datosUser = mysqli_fetch_assoc($ConsltaUser);
        $userPos = $datosUser['Nombres'] . " " . $datosUser['Apellidos'];

        $cb = 0;if ($branchId == 1) {
            $cb = $qtydevolver;
        }

        $lp = 0;if ($branchId == 2) {
            $lp = $qtydevolver;
        }

        $sc = 0;if ($branchId == 3) {
            $sc = $qtydevolver;
        }

        $tj = 0;if ($branchId == 4) {
            $tj = $qtydevolver;
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
            '$fechaActual',
            'AUMENTO STOCK-FACTURA ANULADA',
            '$idProducto',
            '$invoiceCode',
            '$invoiceNumber',
            '$branchId'

            )") or die(mysqli_error($MySQLi));
            //insertamos historial --------------------------------------------------------fin 


    }
   




}


?>


<?php
if ($save == 'COMPLETED_SALE' or $save == 'COMPLETED_DEBIT') {

    ?>
<script type="text/javascript">
Swal.fire({
    position: 'center',
    type: 'success',
    title: 'FACTURA ANULADA CORRECTAMENTE',
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
}else{
    ?>
<script type="text/javascript">
Swal.fire({
    position: 'center',
    type: 'error',
    title: 'ERROR CONEXION',
    html: 'ERROR',
    showConfirmButton: false,
    animation: false,
    customClass: {
        popup: 'animated rotateIn'
    }
});
// setTimeout(function() {
//     location.reload();
// }, 3500);
</script>
<?php  
}
?>