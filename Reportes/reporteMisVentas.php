<?php
require '../includes/librerias/mPDF/vendor/autoload.php';
require '../includes/conexion.php';
include '../includes/date.class.php';
require '../includes/librerias/phpMailer/vendor/autoload.php';
include '../includes/funcionesListaProductos.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
session_start();
$idUser = $_SESSION['idUser'];
$ConsltaUser = mysqli_query($MySQLi, "SELECT * FROM Usuarios WHERE idUser='$idUser' ");
$datosUser = mysqli_fetch_assoc($ConsltaUser);
$miCiudad = $datosUser['Ciudad'];

error_reporting(0);
$mail = new PHPMailer(true);
mysqli_query($MySQLi, "SET lc_time_names= 'es_BO' ");
if (isset($_GET['reporteMisVentas']) and isset($_GET['fechaInicio']) and isset($_GET['fechafin'])) {
    
    $tipo = "T";
    if (isset($_GET['tipo']))
        $tipo = $_GET['tipo'];
    if ($tipo == "V")
        $tit = "";
    else if ($tipo == "C")
        $tit = " Credito ";
    else
        $tit = " Todas ";        
    $Sucursal = $_GET['reporteMisVentas'];
    $Inicio = $_GET['fechaInicio'];
    $Fin = $_GET['fechafin'];

    header("Content-type: application/vnd.ms-excel; name='excel'");
    header("Content-Disposition: filename=MisVentas_" . $Inicio . "__" . $Fin . ".xls");
    header("Pragma: no-cache");
    header("Pragma: no-cache");
    header("Expires: 0");
    ?>

<table border="1">
    <thead>
        <tr>
            <th colspan="23" style="text-align: left;">
                <h3>Ventas <b><?php echo ("$tit"); ?></b> - Usuario: <?php echo $datosUser['Nombres']; ?>&nbsp;<?php echo $datosUser['Apellidos']; ?>
                    <span style="color: green"><?php echo $Inicio ?></span> Hasta el <span
                        style="color: red"><?php echo $Fin ?></span>
                </h3>
            </th>
        </tr>
        <tr>
            <th style="text-align: left;background-color: #FDE9D9">N&ordm;</th>
            <th style="text-align: left;background-color: #FDE9D9">FECHA</th>
            <th style="text-align: left;background-color: #FDE9D9">CODIGO</th>
            <th style="text-align: left;background-color: #FDE9D9">N&ordm;<br>RECIBO
            </th>
            <th style="text-align: left;background-color: #FDE9D9">NOTA<br>ENTREGA</th>
            <th style="text-align: left;background-color: #FDE9D9">NUMERO FACTURA</th>
            <th style="text-align: left;background-color: #FDE9D9">CLIENTE</th>
            <th style="text-align: left;background-color: #FDE9D9">NIT</th>
            <th style="text-align: left;background-color: #FDE9D9">TELEFONO</th>
            <th style="text-align: left;background-color: #FDE9D9">PRODUCTO</th>
            <th style="text-align: left;background-color: #FDE9D9">MARCA</th>
            <th style="text-align: left;background-color: #FDE9D9">MODELO</th>
            <th style="text-align: left;background-color: #FDE9D9">CANTIDAD</th>
            <th style="text-align: left;background-color: #FDE9D9">MONEDA</th>
            <th style="text-align: left;background-color: #FDE9D9">PRECIO<br>DOLAR</th>
            <th style="text-align: left;background-color: #FDE9D9">
                PRECIO<br>LISTA<br>USD</th>
            <th style="text-align: left;background-color: #FDE9D9">DESC</th>
            <th style="text-align: left;background-color: #FDE9D9">
                PRECIO<br>VENTA<br>USD</th>
            <th style="text-align: left;background-color: #FDE9D9">
                PRECIO<br>VENTA<br>Bs</th>
            <th style="text-align: left;background-color: #DAEEF3">PAGO<br>VENTA<br>USD
            </th>
            <th style="text-align: left;background-color: #DAEEF3">PAGO<br>VENTA<br>Bs
            </th>
            <th style="text-align: left;background-color: #FDE9D9">IMPORTE FACTURA</th>
            <th style="text-align: left;background-color: #FDE9D9">OBSERVACIONES</th>
        </tr>
    </thead>
    <tbody><?php
                                $Number = 1;
                                $CantidadTotal=0;
                                $PrecioListaUSDTotal=0;

                                $PrecioVentaUSDTotal=0;
                                $PrecioVentaBsTotal=0;

                                $TotalVentaUSTotal=0;
                                $TotalVentaBsTotal=0;
    if ($tipo == "V")  
$sql = "SELECT v.idVenta, v.Estado, v.idCotizacion, v.CodeCotizacion, v.idUser, v.idCliente, v.idRecibo, v.idEntrega, v.idProducto, v.Cantidad, v.Moneda, 
v.PrecioDolar, v.PrecioListaUSD, v.PrecioListaBs, v.PrecioVentaUSD, v.PrecioVentaBs, v.Sucursal, DATE_FORMAT(v.Fecha, '%d-%m-%Y')AS Fecha, v.TotalVentaUS, 
v.TotalVentaBs FROM Ventas v 
left join Cotizaciones c on c.idCotizacion = v.idCotizacion
WHERE v.idUser='$idUser' AND v.Fecha BETWEEN '$Inicio' AND '$Fin' and c.estado <> 7";
 
    else if ($tipo == "C")  
    $sql = "SELECT Cotizaciones.Estado, Cotizaciones.idCotizacion, Cotizaciones.Code, Creditos.idUser, Creditos.idCliente, Creditos.idRecibo, Creditos.Moneda, Creditos.PrecioDolar, Creditos.TotalUSD as PrecioListaUSD, Creditos.Total as PrecioListaBs, Creditos.TotalUSD as PrecioVentaUSD, Creditos.Total as PrecioVentaBs, Creditos.Sucursal, DATE_FORMAT(Creditos.Fecha, '%d-%m-%Y')AS Fecha, Creditos.AbonoUSD as TotalVentaUS, Creditos.porAbono as TotalVentaBs, NotaEntrega.idNotaE, ClaveTemporal.idProducto, ClaveTemporal.Cantidad
                                        FROM Creditos 
                                        left join Cotizaciones on Creditos.idCotizacion = Cotizaciones.idCotizacion
                                        LEFT JOIN NotaEntrega ON Cotizaciones.idCotizacion = NotaEntrega.idCotizacion
                                        LEFT JOIN ClaveTemporal ON Cotizaciones.Clave = ClaveTemporal.Clave
                                        WHERE Creditos.idUser='$idUser' AND Creditos.Fecha BETWEEN '$Inicio' AND '$Fin'";  
else if ($tipo == "A")
                                        $sql = "
                                        SELECT a.idAbono,a.Estado, c.idCotizacion, a.CodeCotizacion, a.idUser, a.idCliente, a.idRecibo, 0 idEntrega, 0 idProducto, 0 Cantidad, a.Moneda, a.PrecioDolar, 
                                        0 PrecioListaUSD, 0 PrecioListaBs, a.TotalUSD PrecioVentaUSD, a.total PrecioVentaBs, a.Sucursal, DATE_FORMAT(a.Fecha, '%d-%m-%Y')AS Fecha, 
                                        a.porAnticipo / a.precioDolar TotalVentaUS, 
                                        a.porAnticipo TotalVentaBs from Abonos a left join Cotizaciones c on c.idCotizacion = a.idCotizacion 
                                        WHERE a.idUser='$idUser' and  a.Fecha BETWEEN '$Inicio' AND '$Fin'
                                        ";
    else  
    $sql = "SELECT idVenta, Estado, idCotizacion, CodeCotizacion, idUser, idCliente, idRecibo, idEntrega, idProducto, Cantidad, Moneda, PrecioDolar, PrecioListaUSD, PrecioListaBs, PrecioVentaUSD, PrecioVentaBs, Sucursal, DATE_FORMAT(Fecha, '%d-%m-%Y')AS Fecha, TotalVentaUS, TotalVentaBs FROM Ventas WHERE idUser='$idUser' AND Fecha BETWEEN '$Inicio' AND '$Fin' ";
    $sqlVentas = mysqli_query($MySQLi, $sql);
     
    while ($dataVenta = mysqli_fetch_assoc($sqlVentas)) {?>
        <tr>
            <td style="text-align: left;"><?php echo $Number ?></td>
            <td style="text-align: left;"><?php echo $dataVenta['Fecha'] ?></td>
            <td style="text-align: left;"><?php echo $dataVenta['CodeCotizacion'] ?></td>
            <td style="text-align: center;"><?php echo $dataVenta['idRecibo'] ?></td>
            <td style="text-align: center;"><?php echo $dataVenta['idEntrega'] ?></td><?php
$idCliente = $dataVenta['idCliente'];
        $queryClient = mysqli_query($MySQLi, "SELECT * FROM Clientes WHERE idCliente='$idCliente' ");
        $dataCliente = mysqli_fetch_assoc($queryClient);
        $NameCliente = $dataCliente['Nombres'] . " " . $dataCliente['Apellidos'];
        $NIT = $dataCliente['NIT'];
        $Telefono = $dataCliente['Celular'] . "<br>" . $dataCliente['Otro'];
        $idProducto = $dataVenta['idProducto'];
        $queryProduc = mysqli_query($MySQLi, "SELECT * FROM Productos WHERE idProducto='$idProducto' ");
        $dataProduct = mysqli_fetch_assoc($queryProduc);
        $NameProduct = $dataProduct['Producto'];
        $MarcaProduc = $dataProduct['Marca'];
        $ModelProduc = $dataProduct['Modelo'];

        $idCotizacion = $dataVenta['idCotizacion'];

        $Factura = mysqli_query($MySQLi, "SELECT * FROM detailInvoice WHERE idCotizacion='$idCotizacion' and detailId='$idProducto' ORDER BY invoiceNumber DESC");
        $dataFactura = mysqli_fetch_assoc($Factura);

        $FacturaCabezera = mysqli_query($MySQLi, "SELECT * FROM factura WHERE idCotizacion='$idCotizacion' ORDER BY invoiceNumber DESC  ");
        $dataFacturaCabezera = mysqli_fetch_assoc($FacturaCabezera);

        ?>
            <td style="text-align: center;"><?php echo $dataFacturaCabezera['invoiceNumber'] ?>
            </td>
            <td style="text-align: left;">
                <?php echo mb_convert_encoding($NameCliente, 'HTML-ENTITIES', 'UTF-8'); ?></td>
            <td style="text-align: left;"><?php echo $NIT ?></td>
            <td style="text-align: left;"><?php echo $Telefono ?></td>
            <td style="text-align: left;">
                <?php echo mb_convert_encoding($NameProduct, 'HTML-ENTITIES', 'UTF-8'); ?></td>
            <td style="text-align: left;">
                <?php echo mb_convert_encoding($MarcaProduc, 'HTML-ENTITIES', 'UTF-8'); ?></td>
            <td style="text-align: left;">
                <?php echo mb_convert_encoding($ModelProduc, 'HTML-ENTITIES', 'UTF-8'); ?></td>
            <td style="text-align: center;">
            <?php
            // echo $dataVenta['Cantidad']; 
            // $CantidadTotal+=$dataVenta['Cantidad'];

                if($dataVenta['Estado']=='1'){
                    echo '0';

                }else{
                    echo $dataVenta['Cantidad'] ;
                    $CantidadTotal+=$dataVenta['Cantidad'];
                }
                
            ?>
            </td>
            <td style="text-align: center;"><?php echo $dataVenta['Moneda'] ?></td>
            <td style="text-align: right;"><?php
            if ($dataVenta['Moneda'] == 'Bs') {
            // echo $dataVenta['PrecioDolar'];
            echo number_format(($dataVenta['PrecioDolar']), 2 );
            } else {echo "";}?>
            </td>
            <td style="text-align: right;">
            <?php 
            echo number_format(($dataVenta['PrecioListaUSD']), 2);
            $PrecioListaUSDTotal+=$dataVenta['PrecioListaUSD']; 
            ?>
            </td>
            <td><?php ?></td>
            <td style="text-align: right;background-color: #D8E4BC">
            <?php echo number_format(($dataVenta['PrecioVentaUSD']), 2 );
            $PrecioVentaUSDTotal+=$dataVenta['PrecioVentaUSD'];
            ?>
            </td>
            <td style="text-align: right;">
            <?php 
            echo number_format(($dataVenta['PrecioVentaBs']), 2 ); 
            $PrecioVentaBsTotal+=$dataVenta['PrecioVentaBs']; 
            ?>
            </td>
            <td style="text-align: right;background-color: #D8E4BC">
            <?php 
            echo number_format(($dataVenta['TotalVentaUS']), 2 );
            $TotalVentaUSTotal+=$dataVenta['TotalVentaUS'];
            ?>
            </td>
            <td style="text-align: right;background-color: #FDE9D9">
            <?php
            echo number_format(($dataVenta['TotalVentaBs']), 2 );
            $TotalVentaBsTotal+=$dataVenta['TotalVentaBs']; 
            ?>
            </td>
            <td style="text-align: left;background-color: #FFFF00">
            <?php  ?></td>

            <td>
                <?php echo $dataFacturaCabezera['siatDescriptionStatus']; ?></td>
        </tr>
        <?php $Number++;}  ?>
        <tr>
            <th  style="background-color: #FDE9D9">
                <?php echo $Number;?>
            </th>
            <th  style="text-align: left;background-color: #FDE9D9">
            </th>
            <th  style="text-align: center;background-color: #FDE9D9">
            </th>
            <th  style="text-align: center;background-color: #FDE9D9">
            </th>
            <th  style="text-align: center;background-color: #FDE9D9">
            </th>
            <th  style="text-align: left;background-color: #FDE9D9">
            </th>
            <th  style="text-align: left;background-color: #FDE9D9">
            </th>
            <th  style="text-align: left;background-color: #FDE9D9">
            </th>
            <th  style="text-align: left;background-color: #FDE9D9">
            </th>
            <th  style="text-align: left;background-color: #FDE9D9">
            </th>
            <th  style="text-align: left;background-color: #FDE9D9">
            </th>
            <th style="text-align: left;background-color: #FDE9D9">
                Totales
            </th>
            <th  style="text-align: center;background-color: #FDE9D9">
                <?php echo $CantidadTotal;?>
            </th>
            <th  style="text-align: left;background-color: #FDE9D9"></th>
            <th  style="text-align: left;background-color: #FDE9D9"></th>
            <th style="text-align: right;background-color: #FDE9D9">
                <?php
                echo number_format(($PrecioListaUSDTotal), 2 );
                ?>
            </th>
            <th  style="text-align: left;background-color: #FDE9D9"></th>
            <th style="text-align: right;background-color: #FDE9D9">
                <?php
                echo number_format(($PrecioVentaUSDTotal), 2 );
                ?>
            </th>
            <th style="text-align: right;background-color: #FDE9D9">
                <?php
                echo number_format(($PrecioVentaBsTotal), 2 );
                ?>
            </th>
            <th style="text-align: right;background-color: #FDE9D9">
                <?php
                echo number_format(($TotalVentaUSTotal), 2 );
                ?>
            </th>
            <th style="text-align: right;background-color: #FDE9D9">
                <?php
                echo number_format(($TotalVentaBsTotal), 2 );
                ?>
            </th>
            <th  style="text-align: left;background-color: #FDE9D9"></th>
            <th  style="text-align: left;background-color: #FDE9D9"></th>
        </tr>

    </tbody>
</table>

<?php mysqli_close($MySQLi);

}