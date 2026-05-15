<?php
include 'includes/conexion.php';
include 'includes/date.class.php';
error_reporting(0);
mysqli_query($MySQLi, "SET lc_time_names= 'es_BO' ");
$idUser = $_SESSION['idUser'];
$ConsltaUser = mysqli_query($MySQLi, "SELECT * FROM Usuarios WHERE idUser='$idUser' ");
$datosUser = mysqli_fetch_assoc($ConsltaUser);
$miCiudad = $datosUser['Ciudad'];
if ($miCiudad == 'Cochabamba') {
    $branchId = 1;
}
if ($miCiudad == 'La Paz') {
    $branchId = 2;
}
if ($miCiudad == 'Santa Cruz') {
    $branchId = 3;
}
if ($miCiudad == 'Tarija') {
    $branchId = 4;
}

?>
<!DOCTYPE html>
<html lang="es">

<head>
    <title>REPORTES DE FACTURAS</title>
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
    <style>
        .f-s-4 {
            font-size: 4px;
        }
    </style>
</head>

<body>
    <?php include 'php/loader.php'; ?>
    <div id="page-container" class="fade page-sidebar-fixed page-header-fixed">
        <?php
        include 'php/top_menu.php';
        include 'php/left_menu_facturacionReportes.php';
        ?>
        <div id="content" class="content">
            <div class="row">
                &nbsp; &nbsp;
                <a href="?root=facturacionReportes" class="btn btn-warning">COCHABAMBA</a>&nbsp;

                <a href="?root=facturacionReportesLaPaz" class="btn btn-primary">LA PAZ</a>&nbsp;

                <a href="?root=facturacionReportesSantaCruz" class="btn btn-success">SANTA CRUZ</a>&nbsp;

                <a href="?root=facturacionReportesTarija" class="btn btn-info">TARIJA</a>&nbsp;

                <a href="?root=facturacionReportess" class="btn btn-default">TODO</a>
            </div>
            <div class="row">
                &nbsp;
            </div>
            <div class="respuesta"></div>
            <!-- FORMULARIO NUEVO USUARIO -->
            <div class="row d-none formNewUser">
                <div class="col-md-2"></div>
                <div class="col-md-8">
                    <div class="panel panel-inverse">
                        <div class="panel-heading">
                            <h4 class="panel-title">LISTADO FACTURAS</h4>
                            <button class="btn btn-xs btn-danger cancelarRegistro">CANCELAR</button>
                        </div>
                        <div class="panel-body">
                            <form id="newUser">
                                <div class="row">
                                    <div class="col">
                                        <input type="hidden" name="action" value="RegistrarNuevoUsuario">
                                        <input type="text" name="Nombres" id="NewNombres" class="form-control" placeholder="Nombres" maxlength="50">
                                        <div class="invalid-feedback">Campo nombre está vacío</div>
                                    </div>
                                    <div class="col">
                                        <input type="text" name="Apellidos" id="NewApellidos" class="form-control" placeholder="Apellidos" maxlength="50">
                                        <div class="invalid-feedback">Campo apellido está vacío</div>
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col">
                                        <input type="text" name="Telefono" id="NewTelefono" class="form-control" placeholder="Teléfono">
                                        <div class="invalid-feedback">Campo teléfono está vacío</div>
                                        <div class="text-center text-danger d-none nimLength">El número telefónico está
                                            incompleto</div>
                                    </div>
                                    <div class="col">
                                        <?php //echo sucursal() 
                                        ?>
                                        <select name="Sucursal" id="NewSucursal" class="form-control">
                                            <option selected="" disabled="">Seleccione Sucursal</option>
                                            <?php
                                            $consultSucursal = mysqli_query($MySQLi, "SELECT * FROM Sucursales ORDER BY Sucursal ASC");
                                            while ($dataSucursal = mysqli_fetch_assoc($consultSucursal)) {
                                                echo '
														<option value=' . $dataSucursal['idSucursal'] . '>' . $dataSucursal['Sucursal'] . '</option>
														';
                                            }
                                            ?>
                                        </select>
                                        <div class="text-center text-danger d-none emptyNewSucursal">No ha seleccionado
                                            una sucursal</div>
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col">
                                        <input type="email" name="Correo" id="NewCorreo" class="form-control" placeholder="Correo">
                                        <div class="invalid-feedback">Campo correo está vacío</div>
                                    </div>
                                    <div class="col">
                                        <select name="Sexo" id="newUserSexo" class="form-control">
                                            <option selected disabled>Sexo</option>
                                            <option value="Masculino">Masculino</option>
                                            <option value="Femenino">Femenino</option>
                                        </select>
                                        <div class="text-center text-danger d-none emptyNewSexo">No ha seleccionado una
                                            opción</div>
                                    </div>
                                </div>
                                <div class="row mt-3">

                                    <div class="col">
                                        <div class="col">
                                            <button class="btn btn-xs btn-primary form-control regNewUser">REGISTRAR
                                                USUARIO</button>

                                        </div>
                                        <!-- <input type="text" name="Cargo" id="" class="form-control" placeholder="Cargo" maxlength="20">
											<div class="invalid-feedback">Campo cargo está vacío</div> -->
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- FORMULARIO ANULAR FACTURA------------------------------------------------------------------------------------------ -->
            <div class="row d-none FormAnulation1">
                <div class="col-md-2"></div>
                <div class="col-md-8">
                    <div class="panel panel-inverse">
                        <div class="panel-heading">
                            <h4 class="panel-title">ANULACION DE FACTURA</h4>
                            <button class="btn btn-xs btn-danger cancelar1">CANCELAR</button>
                        </div>
                        <div class="panel-body">
                            <form id="editUserList">
                                <!--form---->
                                <div class="row">
                                    <div class="col">
                                        <label for="invoiceNumber1">NUMERO DE FACTURA</label>
                                        <input type="hidden" name="action" value="ActualizarUsuarioLista">
                                        <input type="hidden" name="id" id="id">
                                        <input type="text" name="invoiceNumber1" id="invoiceNumber1" class="form-control" placeholder="invoiceNumber1" maxlength="">
                                    </div>
                                    <div class="col">
                                        <label for="invoiceCode1">CODIGO DE FACTURA</label>
                                        <input type="hidden" name="action" value="ActualizarUsuarioLista">
                                        <input type="hidden" name="id" id="id">
                                        <input type="text" name="invoiceCode1" id="invoiceCode1" class="form-control" placeholder="invoiceCode1" maxlength="">
                                    </div>
                                    <div class="col">
                                        <label for="tipoFactura1">TIPO DE FACTURA</label>
                                        <select name="tipoFactura1" id="tipoFactura1" class="form-control">
                                            <option value="1"> COMPRA Y VENTA</option>
                                            <option value="24"> DEBITO CREDITO</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col">
                                        <label for="codeMotive1">MOTIVO ANULACION</label>
                                        <select name="codeMotive1" id="codeMotive1" class="form-control">
                                            <option selected value="1"> FACTURA MAL EMITIDA</option>
                                            <option value="3"> DATOS DE EMISION INCORRECTOS</option>
                                            <option value="2"> NOTA DE CREDITO-DEBITO MAL EMITIDA</option>
                                            <option value="4"> FACTURA O NOTA DE CREDITO-DEBITO DEVUELTA</option>
                                        </select>
                                        <div class="text-center text-danger d-none emptySexo">No ha seleccionado una
                                            opción</div>
                                    </div>
                                    <div class="col">
                                        <label for="clientEmail1">EMAIL CLIENTE</label>
                                        <input type="email" name="clientEmail1" id="clientEmail1" class="form-control" placeholder="Correo">

                                    </div>
                                    <input type="hidden" name="branchId1" id="branchId1" class="form-control">


                                </div>
                                <div class="row mt-3">

                                    <div class="col">
                                        <label for="submit_">&nbsp;&nbsp;</label>
                                        <button class="btn btn-xs btn-primary form-control btnAnular" id="submit" value="ANULAR FACTURA">ANULAR FACTURA</button>

                                    </div>
                                </div>
                            </form>
                            <div id="resp">


                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <!-- FORMULARIO REEEVIAR EMAIL------------------------------------------------------------------------------------------ -->
            <div class="row d-none editEmail2">
                <div class="col-md-2"></div>
                <div class="col-md-8">
                    <div class="panel panel-inverse">
                        <div class="panel-heading">
                            <h4 class="panel-title">REENVIO DE EMAIL</h4>
                            <button class="btn btn-xs btn-danger cancelarEmail2">CANCELAR</button>
                        </div>
                        <div class="panel-body">
                            <form id="editUserList2">
                                <!--form---->
                                <div class="row">
                                    <div class="col">
                                        <label for="clientEmail2">EMAIL CLIENTE</label>
                                        <input type="email" name="clientEmail2" id="clientEmail2" class="form-control" placeholder="Correo">

                                    </div>

                                    <div class="col">
                                        <label for="invoiceCode2">CODIGO DE FACTURA</label>
                                        <input type="hidden" name="action" value="ActualizarUsuarioLista">
                                        <input type="hidden" name="id" id="id">
                                        <input type="text" readonly name="invoiceCode2" id="invoiceCode2" class="form-control" placeholder="CODIGO FACTURA" maxlength="">

                                    </div>
                                    <select style="visibility:hidden" name="tipoFactura2" id="tipoFactura2" class="form-control">
                                        <option value="1"> COMPRA Y VENTA</option>
                                        <option value="24"> DEBITO CREDITO</option>
                                    </select>
                                </div>

                                <div class="row">
                                    <div class="col">
                                        <label for="subject2">ASUNTO</label>
                                        <input type="text" name="subject2" id="subject2" class="form-control" placeholder="ESCRIBA ASUNTO CORREO" required>
                                        <input type="hidden" name="action" value="ActualizarUsuarioLista">
                                        <input type="hidden" name="id" id="id">
                                        <input type="hidden" name="invoiceNumber2" id="invoiceNumber2" class="form-control" placeholder="NUMERO FACTURA" maxlength="">

                                    </div>

                                    <select style="visibility:hidden" name="codeMotive2" id="codeMotive2" class="form-control">

                                        <option selected value="1"> FACTURA MAL EMITIDA</option>
                                        <option value="3"> DATOS DE EMISION INCORRECTOS</option>

                                        <option value="2"> NOTA DE CREDITO-DEBITO MAL EMITIDA</option>
                                        <option value="4"> FACTURA O NOTA DE CREDITO-DEBITO DEVUELTA</option>
                                    </select>
                                    <div class="text-center text-danger d-none emptySexo">No ha seleccionado una
                                        opción</div>




                                    <input type="hidden" name="branchId2" id="branchId2" class="form-control">


                                </div>
                                <div class="row mt-3">

                                    <div class="col">
                                        <label for="submit_">&nbsp;&nbsp;</label>
                                        <button class="btn btn-xs btn-primary form-control btnEnviar2" id="submit" value="ANULAR FACTURA">ENVIAR</button>

                                    </div>
                                </div>
                            </form>
                            <div id="resp2">


                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <!-- TABLA DE FACTURAS -->
            <?php
            if (isset($_POST['inicio'])) {
                $Inicio     = $_POST['inicio'];
                $Fin             =    $_POST['fin'];
            } else {
                $Inicio = $startBusqueda; //startbuskeda = 1 del mes
                $Fin = $fecha; //fecha = hoy
            } ?>
            <div class="row tableUsers">
                <div class="col-md-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h4 class="panel-title">Lista de facturas TODO
                                &nbsp;&nbsp;&nbsp;

                                <span style="text-transform: uppercase;letter-spacing: 1px;font-size: 16px"><?php echo $mes ?></span>&nbsp;&nbsp;&nbsp;
                                =====>
                            </h4>
                            <div class="panel-heading-btn">

                                <div class="input-group input-daterange">
                                    <form action="?root=facturacionReportesTarija" method="post">
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
                        <div class="panel-body">
                            <div class="row">
                                <!-- total facturado validadas Bs -->
                                <div class="col-xl-3 col-md-6">
                                    <div class="widget widget-stats bg-blue">
                                        <div class="stats-icon"><i class="fa fa-dollar-sign" style="font-size: 65px"></i></div>
                                        <div class="stats-info">
                                            <h4>MONTO TOTAL FACTURADO <?php // echo strtoupper($mes) 
                                                                        ?></h4>
                                            <p><?php
                                                $queryVentas    =    mysqli_query($MySQLi, "SELECT
                                        SUM(amountTotal) AS amountTotal
                                    FROM
                                        factura
                                    WHERE
                                        siatCodeState = 908 AND ( DATE_FORMAT(dateEmission, '%Y-%m-%d') BETWEEN '$Inicio' AND '$Fin' )") or die(mysqli_error($MySQLi));
                                                $dataVentas        =    mysqli_fetch_assoc($queryVentas);
                                                $TotalVentas     =    $dataVentas['amountTotal'];
                                                echo "Bs " . number_format(($TotalVentas), 2); ?>
                                            </p>
                                        </div>

                                    </div>
                                </div>
                                <!-- Cantidad facturas validadas -->
                                <div class="col-xl-3 col-md-6">
                                    <div class="widget widget-stats bg-success">
                                        <div class="stats-icon"><i class="fas fa-file-invoice" style="font-size: 65px"></i>
                                        </div>
                                        <div class="stats-info">
                                            <h4>CANTIDAD FACTURAS VALIDADAS</h4>
                                            <p><?php
                                                $queryEntregadas    =    mysqli_query($MySQLi, "SELECT *
                                        FROM
                                        factura
                                    WHERE
                                        siatCodeState = 908 AND ( DATE_FORMAT(dateEmission, '%Y-%m-%d') BETWEEN '$Inicio' AND '$Fin' )");
                                                $resultGeneradas     =    mysqli_num_rows($queryEntregadas);
                                                if ($resultGeneradas > 0) {
                                                    echo $resultGeneradas;
                                                } else {
                                                    echo "0";
                                                } ?>
                                            </p>
                                        </div>

                                    </div>
                                </div>
                                <!-- facturas cantidad anuladas -->
                                <div class="col-xl-3 col-md-6">
                                    <div class="widget widget-stats bg-gray">
                                        <div class="stats-icon"><i class="fa fa-window-close" style="font-size: 65px"></i>
                                        </div>
                                        <div class="stats-info">
                                            <h4>CANTIDAD FACTURAS ANULADAS</h4>
                                            <p><?php
                                                $queryCantidadAnuladas    =    mysqli_query($MySQLi, "SELECT *
                                        FROM
                                        factura
                                    WHERE
                                         siatCodeState = 905 AND ( DATE_FORMAT(dateEmission, '%Y-%m-%d') BETWEEN '$Inicio' AND '$Fin' )");
                                                $resultCantidadAnuladas    =    mysqli_num_rows($queryCantidadAnuladas);

                                                if ($resultCantidadAnuladas > 0) {
                                                    echo $resultCantidadAnuladas;
                                                } else {
                                                    echo "0";
                                                }
                                                ?>
                                            </p>


                                            </p>
                                        </div>

                                    </div>
                                </div>
                                <!-- TOTAL PRODUCTODS FACTURADOS -->
                                <div class="col-xl-3 col-md-6">
                                    <div class="widget widget-stats bg-info">
                                        <div class="stats-icon"><i class="fa fa-shopping-bag" style="font-size: 65px"></i>
                                        </div>
                                        <div class="stats-info">
                                            <h4>CANTIDAD TOTAL DE PRODUCTOS FACTURADOS Y VALIDADOS</h4>
                                            <p><?php


                                                $totalfacturado = 0;
                                                $QueryFactura = mysqli_query($MySQLi, "SELECT* FROM detailInvoice WHERE ( DATE_FORMAT(dateEmission, '%Y-%m-%d') BETWEEN '$Inicio' AND '$Fin' ) ORDER BY invoiceNumber DESC");

                                                while ($data = mysqli_fetch_assoc($QueryFactura)) {

                                                    $nroFactura = $data['invoiceNumber'];
                                                    $qtyFactura = $data['qty'];
                                                    $QueryFacturaCabezera = mysqli_query($MySQLi, "SELECT* FROM factura WHERE   invoiceNumber='$nroFactura'  ");
                                                    $dataFacturaCabezera = mysqli_fetch_assoc($QueryFacturaCabezera);

                                                    if ($dataFacturaCabezera['siatCodeState'] == 908) {
                                                        $totalfacturado = $totalfacturado + $qtyFactura;
                                                    }
                                                }



                                                if ($totalfacturado > 0) {
                                                    echo $totalfacturado;
                                                } else {
                                                    echo "0";
                                                } ?>
                                            </p>
                                        </div>

                                    </div>
                                </div>
                            </div>
                            <table id="data-table-buttons" width="100%" class="table table-striped table-bordered table-td-valign-middle w-100">
                                <thead>
                                    <tr>
                                        <th width="3%" class="text-center">N&ordm;</th>
                                        <th width="5%" class="text-center">#FACTURA</th>
                                        <th class="text-center">RAZON SOCIAL</th>
                                        <th class="text-center">NIT CLIENTE</th>
                                        <th class="text-center">FECHA EMISION</th>
                                        <th class="text-center">VENDEDOR</th>

                                        <th class="text-center">PRODUCTO FACTURADO</th>
                                        <th width="5%" class="text-center">CANTIDAD FACTURADA</th>
                                        <th width="5%" class="text-center">PRECIO UNIDAD</th>
                                        <th class="text-center">IMPORTE FACTURADO</th>
                                        <th class="text-center">IMPORTE ANULADO</th>

                                        <th class="text-center">ESTADO DE LA FACTURA - SIAT</th>


                                        <th width="13%" class="text-center">ACCIONES</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    include 'includes/conexion.php';

                                    $sqlurlcucu = mysqli_query($MySQLi, "SELECT * FROM token_access");
                                    $dataurlcucu = mysqli_fetch_assoc($sqlurlcucu) or die(mysqli_error($MySQLi));
                                    $urlcucu = $dataurlcucu['urlcucu'];
                                    // $QueryFactura = mysqli_query($MySQLi, "SELECT*  FROM    detailInvoice      INNER JOIN factura ON detailInvoice.invoiceNumber = factura.invoiceNumber
                                    //                             WHERE
                                    //                                 factura.branchId = 4 AND(
                                    //                                     DATE_FORMAT(factura.dateEmission, '%Y-%m-%d') BETWEEN '$Inicio' AND '$Fin'
                                    //                                 )
                                    //                             ORDER BY
                                    //                                 factura.invoiceNumber
                                    //                             DESC
                                    //                                 ");
                                    // $QueryFactura = mysqli_query($MySQLi, "SELECT* FROM detailInvoice ORDER BY invoiceNumber DESC");
                                    $QueryFactura = mysqli_query($MySQLi, "SELECT* FROM detailInvoice WHERE (DATE_FORMAT(dateEmission, '%Y-%m-%d') BETWEEN '$Inicio' AND '$Fin' )  ORDER BY invoiceNumber DESC");
                                    $contador = 1;
                                    $total_cantidad_f = 0;
                                    $total_precio_unidad = 0;
                                    $total_importe_facturado = 0;
                                    $total_importe_anulado = 0;
                                    while ($data = mysqli_fetch_assoc($QueryFactura)) {


                                        $nroFactura = $data['invoiceNumber'];
                                        $QueryFacturaCabezera = mysqli_query($MySQLi, "SELECT* FROM factura WHERE  invoiceNumber='$nroFactura' ");
                                        $dataFacturaCabezera = mysqli_fetch_assoc($QueryFacturaCabezera);
                                        // $QueryFacturaCabezera = mysqli_query($MySQLi, "SELECT* FROM factura WHERE  invoiceNumber='$nroFactura' ");
                                        // $data = mysqli_fetch_assoc($QueryFacturaCabezera);
                                    ?>
                                        <tr class="odd gradeX">
                                            <td class="text-center"><?php echo $contador ?></td>
                                            <td class="text-center"><?php echo $nroFactura ?></td>
                                            <td class="text-center"><?php echo $dataFacturaCabezera['clientReasonSocial'] ?>
                                            </td>
                                            <td class="text-center"><?php echo $dataFacturaCabezera['clientNroDocument'] ?>
                                            </td>
                                            <td class="text-center"><?php echo $dataFacturaCabezera['dateEmission'] ?></td>
                                            <td class="text-center"><?php echo $dataFacturaCabezera['userCashier'] ?></td>

                                            <td class="text-center"><?php echo $data['description'] ?></td>
                                            <td class="text-center">
                                                <?php echo $data['qty'];
                                                $total_cantidad_f += (int)$data['qty'];
                                                ?>
                                            </td>
                                            <td class="text-center">
                                                <?php echo $data['priceUnit'];
                                                $total_precio_unidad += $data['priceUnit'];
                                                ?>
                                            </td>
                                            <td class="text-center"><?php

                                                                    if ($dataFacturaCabezera['siatCodeState'] == 908) {

                                                                        echo $data['subTotal'];
                                                                        $total_importe_facturado += $data['subTotal'];
                                                                    }
                                                                    ?>
                                            </td>
                                            <td class="text-center"><?php

                                                                    if ($dataFacturaCabezera['siatCodeState'] == 905) {

                                                                        echo $data['subTotal'];
                                                                        $total_importe_anulado += $data['subTotal'];
                                                                    }
                                                                    ?>
                                            </td>


                                            <td class="text-center">
                                                <?php
                                                $emision_doble = ($dataFacturaCabezera['doble_invoice_number'] > 0) ? 'Emision Doble F' . $dataFacturaCabezera['doble_invoice_number'] : '';
                                                $idCotizacion = $dataFacturaCabezera['idCotizacion'];
                                                $cadena_descripcion = '';
                                                if ($dataFacturaCabezera['idCotizacion'] != '-1') {
                                                    $q_cotizaciones = mysqli_query(
                                                        $MySQLi,
                                                        "SELECT
                                                            `Code`
                                                        FROM
                                                            `Cotizaciones`
                                                        WHERE
                                                            `idCotizacion` = '$idCotizacion';"
                                                    ) or die(mysqli_error($MySQLi));
                                                    $d_cotizaciones = mysqli_fetch_assoc($q_cotizaciones);

                                                    $cadena_descripcion .=  $d_cotizaciones['Code'] . '=F' . $dataFacturaCabezera['invoiceNumber'] . ' ' . $emision_doble . '';
                                                } else {
                                                    $cadena_descripcion .=  'EmisionDirectaF' . $dataFacturaCabezera['invoiceNumber'] . ' ' . $emision_doble . '';
                                                }

                                                echo $dataFacturaCabezera['siatDescriptionStatus'] . '  ' . $cadena_descripcion;
                                                // echo $dataFacturaCabezera['siatDescriptionStatus']

                                                ?>

                                            </td>


                                            <td class="text-center">

                                                <input type="hidden" name="invoiceCode" id="invoiceCode" value='<?php echo $dataFacturaCabezera['invoiceCode'] ?>'>


                                                <button id="<?php echo $dataFacturaCabezera['invoiceCode'] ?>" class="btn btn-xs btn-primary ver_pdf" title="Ver PDF Factura ID=<?php echo $dataFacturaCabezera['invoiceCode'] ?>"><a><i class="fa fa-file" style="font-size: 16px"></i></a></button>

                                                <?php $urlpdf = $dataFacturaCabezera['invoiceUrl'] ?>

                                                <!-- <button title="Ver PDF Factura" class="btn btn-xs "><a
                                                    href="<?php //echo $urlcucu . $urlpdf 
                                                            ?>" target="_blank"><i
                                                        class="fa fa-file"
                                                        style="font-size: 20px"></i></a></button>&nbsp; -->

                                                <!-- <button title="ANULAR FACTURA" id="" class="btn btn-xs btn-success anular"><i class="ion-ios-brush" style="font-size: 15px"></i></button>&nbsp; -->
                                                <button title="ANULAR FACTURA" id="<?php echo $dataFacturaCabezera['id'] ?>" class="btn btn-xs btn-danger btnAnular1"><i class="fa fa-times" style="font-size: 15px"></i></button>&nbsp;

                                                <button title="REENVIAR FACTURA" id="<?php echo $dataFacturaCabezera['id'] ?>" class="btn btn-xs btn-success btnEmail2"><i class="fa fa-envelope" style="font-size: 15px"></i></button>&nbsp;


                                            </td>
                                        </tr>
                                    <?php
                                        $contador++;
                                    }
                                    mysqli_close($MySQLi); ?>
                                    <tr>
                                        <th class="text-center"><?php echo $contador ?></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>

                                        <th class="text-center"><?php echo $total_cantidad_f ?></th>
                                        <th class="text-center"><?php echo $total_precio_unidad ?></th>
                                        <th class="text-center"><?php echo $total_importe_facturado ?></th>
                                        <th class="text-center"><?php echo $total_importe_anulado ?></th>

                                        <th></th>
                                        <th></th>
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
    <?php include 'php/script_usuarios.php'; ?>
</body>

</html>