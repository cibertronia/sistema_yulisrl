<?php
$idUser = $_SESSION['idUser'];
$ConsltaUser = mysqli_query($MySQLi, "SELECT * FROM Usuarios WHERE idUser='$idUser' ");
$datosUser = mysqli_fetch_assoc($ConsltaUser);
$miCiudad = $datosUser['Ciudad'];
error_reporting(0); ?>
<!DOCTYPE html>
<html lang="es">

<head>
    <title>MIS VENTAS</title>
    <?php include 'php/meta.php'; ?>
    <link href="assets/css/apple/app.min.css" rel="stylesheet">
    <link href="assets/plugins/ionicons/css/ionicons.min.css" rel="stylesheet">
    <link href="assets/plugins/jvectormap-next/jquery-jvectormap.css" rel="stylesheet">
    <link href="assets/plugins/bootstrap-datepicker/dist/css/bootstrap-datepicker.css" rel="stylesheet">
    <link href="assets/plugins/datatables.net-bs4/css/dataTables.bootstrap4.min.css" rel="stylesheet">
    <link href="assets/plugins/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css" rel="stylesheet">
    <link href="assets/plugins/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css" rel="stylesheet">
    <link href="assets/plugins/blueimp-gallery/css/blueimp-gallery.min.css" rel="stylesheet">
    <link href="assets/plugins/blueimp-file-upload/css/jquery.fileupload.css" rel="stylesheet">
    <link href="assets/plugins/blueimp-file-upload/css/jquery.fileupload-ui.css" rel="stylesheet">
    <link href="assets/plugins/select2/dist/css/select2.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/switchery/switchery.css">
</head>

<body><?php include 'php/loader.php'; ?>
    <div id="page-container" class="fade page-sidebar-fixed page-header-fixed">
        <?php
        include 'php/top_menu.php';
        include 'php/left_menu_misVentas.php'; ?>
        <div id="content" class="content">
            <div class="respuesta"></div>
            <?php
            if (isset($_POST['inicio'])) {
                $Inicio = $_POST['inicio'];
                $Fin = $_POST['fin'];
            } else {
                $Inicio = $startBusqueda; //startbuskeda = 1 del mes
                $Fin = $fecha; //fecha = hoy
            }
            
            $tipo = "V";
            if (isset($_POST['tipo'])) 
                $tipo = $_POST['tipo'];
            ?>
            <div class="row tableCotizaciones">
                <div class="col-md-12">
                    <div class="panel panel-inverse">
                        <div class="panel-heading">
                            <h4 class="panel-title">TABLA VENTAS &nbsp;&nbsp;
                                <span style="text-transform: uppercase;letter-spacing: 1px;font-size: 16px"><?php echo $mes ?></span>&nbsp;&nbsp;&nbsp;
                                =====>
                                <span style="text-transform: uppercase;letter-spacing: 1px;font-size: 16px"><?php echo $datosUser['Ciudad'] ?></span>
                            </h4>
                            <div class="panel-heading-btn">
                                <div class="input-group input-daterange">
                                    <form action="?root=ventas" method="post">
                                        <select id="tipo" name="tipo">
                                                <option value="V" <?php echo( ( (!isset($_POST['tipo'])) || (isset($_POST['tipo']) && ($_POST['tipo'] == 'V'))) ? ' selected' : '' ) ?> >VENTAS</option>
                                                <option value="C" <?php echo( ((isset($_POST['tipo']) && ($_POST['tipo'] == 'C'))) ? ' selected' : '' ) ?> >CREDITOS</option>
                                                <!-- <option value="A" <?php// echo( ((isset($_POST['tipo']) && ($_POST['tipo'] == 'A'))) ? ' selected' : '' ) ?> >ABONOS</option> -->
                                            </select>
                                        <input required="" type="date" name="inicio" value="<?php echo $Inicio ?>">
                                        <input required="" type="date" name="fin" value="<?php echo $Fin ?>">
                                        <input type="submit" class="btn btn-xs btn-danger" value="BUSCAR">
                                    </form>
                                </div>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
                                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-success" data-click="panel-reload"><i class="fa fa-redo"></i></a>
                                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>
                                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-danger" data-click="panel-remove"><i class="fa fa-times"></i></a>
                            </div>
                        </div>
                        <br>
                        <div class="col-xl-3 col-md-3">

                            <a class="btn btn-success btn-sm btn-block" 
                                href="Reportes/reporteMisVentas.php?reporteMisVentas=reporteMisVentas&fechaInicio=<?php echo $Inicio ?>&fechafin=<?php echo $Fin ?>&tipo=<?php echo $tipo ?>" title="Historial todos los Productos en rango de fechas"><span style="color: white">
                                    EXCEL DESCARGAR</span>&nbsp;&nbsp;
                                <i class="fa fa-download" style="color: white"></i>
                            </a>

                        </div>
                        <div class="panel-body">
                            <div class="text-danger text-center d-none noFechaInicio" style="letter-spacing: 1px">NO HA
                                INDICADO LA FECHA DE INICIO</div>
                            <div class="text-danger text-center d-none noFechaFinal" style="letter-spacing: 1px">NO HA
                                INDICADO LA FECHA FINAL</div>
                            <table id="data-table-buttons" class="table table-striped table-bordered table-td-valign-middle w-100">
                                <thead>
                                    <tr>
                                        <th class="text-center" style="text-align: center;background-color: #FDE9D9">
                                            N&ordm;</th>
                                        <th class="text-center" style="text-align: center;background-color: #FDE9D9">
                                            FECHA</th>
                                        <th class="text-center" style="text-align: center;background-color: #FDE9D9">
                                            CODIGO</th>
                                        <th class="text-center" style="text-align: center;background-color: #FDE9D9">
                                            N&ordm;<br>RECIBO</th>
                                        <th class="text-center" style="text-align: center;background-color: #FDE9D9">
                                            NOTA<br>ENTREGA</th>
                                        <th class="text-center" style="text-align: center;background-color: #FDE9D9">
                                            NUMERO
                                            FACTURA</th>
                                        <th class="text-center" style="text-align: center;background-color: #FDE9D9">
                                            CLIENTE</th>
                                        <th class="text-center" style="text-align: center;background-color: #FDE9D9">NIT
                                        </th>
                                        <th class="text-center" style="text-align: center;background-color: #FDE9D9">
                                            TELEFONO
                                        </th>
                                        <th class="text-center" style="text-align: center;background-color: #FDE9D9">
                                            PRODUCTO
                                        </th>
                                        <th class="text-center" style="text-align: center;background-color: #FDE9D9">
                                            MARCA</th>
                                        <th class="text-center" style="text-align: center;background-color: #FDE9D9">
                                            MODELO</th>
                                        <th class="text-center" style="text-align: center;background-color: #FDE9D9">
                                            CANTIDAD
                                        </th>
                                        <th class="text-center" style="text-align: center;background-color: #FDE9D9">
                                            MONEDA</th>
                                        <th class="text-center" style="text-align: center;background-color: #FDE9D9">
                                            PRECIO<br>DOLAR</th>
                                        <th class="text-center" style="text-align: center;background-color: #FDE9D9">
                                            PRECIO<br>LISTA<br>USD</th>
                                        <th class="text-center" style="text-align: center;background-color: #FDE9D9">
                                            DESC</th>
                                        <th class="text-center" style="text-align: center;background-color: #FDE9D9">
                                            PRECIO<br>VENTA<br>USD</th>
                                        <th class="text-center" style="text-align: center;background-color: #FDE9D9">
                                            PRECIO<br>VENTA<br>Bs</th>
                                        <th class="text-center" style="text-align: center;background-color: #FDE9D9">
                                            PAGO<br>VENTA<br>USD</th>
                                        <th class="text-center" style="text-align: center;background-color: #FDE9D9">
                                            PAGO<br>VENTA<br>Bs</th>
                                        <th class="text-center" style="text-align: center;background-color: #FDE9D9">
                                            IMPORTE
                                            FACTURA</th>
                                        <th class="text-center" style="text-align: center;background-color: #FDE9D9">
                                            OBSERVACIONES</th>
                                    </tr>
                                </thead>
                                <tbody><?php

                                        $CantidadTotal = 0;
                                        $PrecioListaUSDTotal = 0;

                                        $PrecioVentaUSDTotal = 0;
                                        $PrecioVentaBsTotal = 0;

                                        $TotalVentaUSTotal = 0;
                                        $TotalVentaBsTotal = 0;

                                        $Number = 1;
                                        if ($tipo == "V")
                                        $sql = "
                                        SELECT v.idVenta,v.Estado, v.idCotizacion, v.CodeCotizacion, v.idUser, v.idCliente, v.idRecibo, v.idEntrega, v.idProducto, v.Cantidad, v.Moneda, v.PrecioDolar, 
                                        v.PrecioListaUSD, v.PrecioListaBs, v.PrecioVentaUSD, v.PrecioVentaBs, v.Sucursal, DATE_FORMAT(v.Fecha, '%d-%m-%Y')AS Fecha, v.TotalVentaUS, v.TotalVentaBs 
                                        FROM Ventas v left join Cotizaciones c on c.idCotizacion = v.idCotizacion
                                        WHERE v.idUser='$idUser' AND v.Fecha BETWEEN '$Inicio' AND '$Fin' and c.estado <> 7
                                        ";

                                        else if ($tipo == "C")
                                        $sql = "
                                        SELECT Cotizaciones.Estado, Cotizaciones.idCotizacion, Cotizaciones.Code as CodeCotizacion, 
                                        Creditos.idUser, Creditos.idCliente, Creditos.idRecibo, Creditos.Moneda, Creditos.PrecioDolar, Creditos.TotalUSD as PrecioListaUSD, Creditos.Total as PrecioListaBs, Creditos.TotalUSD as PrecioVentaUSD, Creditos.Total as PrecioVentaBs, Creditos.Sucursal, DATE_FORMAT(Creditos.Fecha, '%d-%m-%Y')AS Fecha, Creditos.AbonoUSD as TotalVentaUS, Creditos.porAbono as TotalVentaBs, 
                                        NotaEntrega.idNotaE as idEntrega, ClaveTemporal.idProducto, ClaveTemporal.Cantidad
                                        FROM Creditos 
                                        left join Cotizaciones on Creditos.idCotizacion = Cotizaciones.idCotizacion
                                        LEFT JOIN NotaEntrega ON Cotizaciones.idCotizacion = NotaEntrega.idCotizacion
                                        LEFT JOIN ClaveTemporal ON Cotizaciones.Clave = ClaveTemporal.Clave
                                        WHERE Creditos.idUser='$idUser' AND Creditos.Fecha BETWEEN '$Inicio' AND '$Fin'
                                        ";
                                        else if ($tipo == "A")
                                        $sql = "
                                        SELECT a.idAbono,a.Estado, c.idCotizacion, a.CodeCotizacion, a.idUser, a.idCliente, a.idRecibo, 0 idEntrega, 0 idProducto, 0 Cantidad, a.Moneda, a.PrecioDolar, 
                                        0 PrecioListaUSD, 0 PrecioListaBs, a.TotalUSD PrecioVentaUSD, a.total PrecioVentaBs, a.Sucursal, DATE_FORMAT(a.Fecha, '%d-%m-%Y')AS Fecha, 
                                        a.porAnticipo / a.precioDolar TotalVentaUS, 
                                        a.porAnticipo TotalVentaBs from Abonos a left join Cotizaciones c on c.idCotizacion = a.idCotizacion 
                                        WHERE a.idUser='$idUser' and  a.Fecha BETWEEN '$Inicio' AND '$Fin';
                                        ";
                                         
                                        else if ($tipo == "AAA")
                                        $sql = "
                                        SELECT v.idVenta,v.Estado, v.idCotizacion, v.CodeCotizacion, v.idUser, v.idCliente, v.idRecibo, v.idEntrega, v.idProducto, v.Cantidad, v.Moneda, v.PrecioDolar, 
                                        v.PrecioListaUSD, v.PrecioListaBs, v.PrecioVentaUSD, v.PrecioVentaBs, v.Sucursal, DATE_FORMAT(v.Fecha, '%d-%m-%Y')AS Fecha, v.TotalVentaUS, v.TotalVentaBs 
                                        FROM Ventas v left join Cotizaciones c on c.idCotizacion = v.idCotizacion
                                        WHERE v.idUser='$idUser' AND v.Fecha BETWEEN '$Inicio' AND '$Fin' 
                                        ";
                                        $sqlVentas = mysqli_query($MySQLi, $sql);
                                        while ($dataVenta = mysqli_fetch_assoc($sqlVentas)) { ?>
                                        <tr>
                                            <td><?php echo $Number ?></td>
                                            <td><?php echo $dataVenta['Fecha'] ?></td>
                                            <td><?php echo $dataVenta['CodeCotizacion'] ?></td>
                                            <td class="text-center"><?php echo $dataVenta['idRecibo'] ?></td>
                                            <td class="text-center"><?php echo $dataVenta['idEntrega'] ?></td><?php
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
                                            <td style="text-align: left;">
                                                <?php echo $dataFacturaCabezera['invoiceNumber'] ?>
                                            </td>
                                            <td style="text-align: left;">
                                                <?php echo mb_convert_encoding($NameCliente, 'HTML-ENTITIES', 'UTF-8'); ?>
                                            </td>
                                            <td style="text-align: left;"><?php echo $NIT ?></td>
                                            <td style="text-align: left;"><?php echo $Telefono ?></td>
                                            <td style="text-align: left;">
                                                <?php echo mb_convert_encoding($NameProduct, 'HTML-ENTITIES', 'UTF-8'); ?>
                                            </td>
                                            <td style="text-align: left;">
                                                <?php echo mb_convert_encoding($MarcaProduc, 'HTML-ENTITIES', 'UTF-8'); ?>
                                            </td>
                                            <td style="text-align: left;">
                                                <?php echo mb_convert_encoding($ModelProduc, 'HTML-ENTITIES', 'UTF-8'); ?>
                                            </td>
                                            <td style="text-align: left;">
                                                <?php
                                                // echo $dataVenta['Cantidad']; 
                                                // $CantidadTotal+=$dataVenta['Cantidad'];



                                                if ($dataVenta['Estado'] == '1') {
                                                    echo '0';
                                                } else {
                                                    echo $dataVenta['Cantidad'];
                                                    $CantidadTotal += $dataVenta['Cantidad'];
                                                }



                                                ?>
                                            </td>
                                            <td style="text-align: left;"><?php echo $dataVenta['Moneda'] ?></td>
                                            <td style="text-align: left;"><?php
                                                                            if ($dataVenta['Moneda'] == 'Bs') {
                                                                                echo $dataVenta['PrecioDolar'];
                                                                            } else {
                                                                                echo "";
                                                                            } ?>
                                            </td>
                                            <td style="text-align: left;">
                                                <?php
                                                echo $dataVenta['PrecioListaUSD'];
                                                $PrecioListaUSDTotal += $dataVenta['PrecioListaUSD'];
                                                ?>
                                            </td>
                                            <td><?php ?></td>
                                            <td style="text-align: left;background-color: #D8E4BC">
                                                <?php echo $dataVenta['PrecioVentaUSD'];
                                                $PrecioVentaUSDTotal += $dataVenta['PrecioVentaUSD'];
                                                ?>
                                            </td>
                                            <td style="text-align: left;background-color: #DBC2F4">
                                                <?php
                                                echo $dataVenta['PrecioVentaBs'];
                                                $PrecioVentaBsTotal += $dataVenta['PrecioVentaBs'];
                                                ?>
                                            </td>
                                            <td style="text-align: left;background-color: #D8E4BC">
                                                <?php
                                                echo $dataVenta['TotalVentaUS'];
                                                $TotalVentaUSTotal += $dataVenta['TotalVentaUS'];
                                                ?>
                                            </td>
                                            <td style="text-align: left;background-color: #FDE9D9">
                                                <?php
                                                echo $dataVenta['TotalVentaBs'];
                                                $TotalVentaBsTotal += $dataVenta['TotalVentaBs'];
                                                ?>
                                            </td>
                                            <td style="text-align: left;background-color: #FFFF00">
                                                <?php  ?></td>

                                            <td>
                                                <?php echo $dataFacturaCabezera['siatDescriptionStatus']; ?></td>
                                        </tr>
                                    <?php $Number++;
                                        }
                                        //mysqli_close($MySQLi);
                                    ?>
                                    <tr>
                                        <th style="background-color: #FDE9D9">
                                            <?php echo $Number; ?>
                                        </th>
                                        <th style="text-align: left;background-color: #FDE9D9">
                                        </th>
                                        <th style="text-align: left;background-color: #FDE9D9">
                                        </th>
                                        <th style="text-align: left;background-color: #FDE9D9">
                                        </th>
                                        <th style="text-align: left;background-color: #FDE9D9">
                                        </th>
                                        <th style="text-align: left;background-color: #FDE9D9">
                                        </th>
                                        <th style="text-align: left;background-color: #FDE9D9">
                                        </th>
                                        <th style="text-align: left;background-color: #FDE9D9">
                                        </th>
                                        <th style="text-align: left;background-color: #FDE9D9">
                                        </th>
                                        <th style="text-align: left;background-color: #FDE9D9">
                                        </th>
                                        <th style="text-align: left;background-color: #FDE9D9">
                                        </th>
                                        <th style="text-align: left;background-color: #FDE9D9">
                                            Totales
                                        </th>
                                        <th style="text-align: left;background-color: #FDE9D9">
                                            <?php echo $CantidadTotal; ?>
                                        </th>
                                        <th style="text-align: left;background-color: #FDE9D9"></th>
                                        <th style="text-align: left;background-color: #FDE9D9"></th>
                                        <th style="text-align: left;background-color: #FDE9D9">
                                            <?php
                                            echo $PrecioListaUSDTotal;
                                            ?>
                                        </th>
                                        <th style="text-align: left;background-color: #FDE9D9"></th>
                                        <th style="text-align: left;background-color: #FDE9D9">
                                            <?php
                                            echo $PrecioVentaUSDTotal;
                                            ?>
                                        </th>
                                        <th style="text-align: left;background-color: #FDE9D9">
                                            <?php
                                            echo $PrecioVentaBsTotal;
                                            ?>
                                        </th>
                                        <th style="text-align: left;background-color: #FDE9D9">
                                            <?php
                                            echo $TotalVentaUSTotal;
                                            ?>
                                        </th>
                                        <th style="text-align: left;background-color: #FDE9D9">
                                            <?php
                                            echo $TotalVentaBsTotal;
                                            ?>
                                        </th>
                                        <th style="text-align: left;background-color: #FDE9D9"></th>
                                        <th style="text-align: left;background-color: #FDE9D9"></th>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <!-- end panel-body -->
                    </div>
                </div>
            </div>

        </div>
        <a href="javascript:;" class="btn btn-icon btn-circle btn-primary btn-scroll-to-top fade" data-click="scroll-top"><i class="fa fa-angle-up"></i></a>
        <?php include 'php/footer.php'; ?>
    </div>
    <?php include 'php/script_ventas.php'; ?>
</body>

</html>