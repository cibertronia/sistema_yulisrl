<?php
if (isset($_POST['start']) and isset($_POST['end'])) {
    $Start = $_POST['start'];
    $End = $_POST['end'];
    include 'includes/conexion.php';
    include 'includes/date.class.php';
    mysqli_query($MySQLi, "SET lc_time_names= 'es_BO' ");
    $idUser = $_SESSION['idUser'];
    $ConsltaUser = mysqli_query($MySQLi, "SELECT * FROM Usuarios WHERE idUser='$idUser' ");
    $datosUser = mysqli_fetch_assoc($ConsltaUser);
    $miCiudad = $datosUser['Ciudad'];

    $sqlPrecioDolar = mysqli_query($MySQLi, "SELECT * FROM precio ");
    $dolarBd = mysqli_fetch_assoc($sqlPrecioDolar);

    ?>

<!DOCTYPE html>
<html lang="es">

<head>
    <title>TABLA DE METAS</title>
    <?php include 'php/meta.php';?>
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

<body>
    <?php include 'php/loader.php';?>
    <div id="page-container" class="fade page-sidebar-fixed page-header-fixed">
        <?php
include 'php/top_menu.php';
    //include 'php/left_menu.php';
	include 'php/left_menu_comisiones.php';
    ?>
        <div id="content" class="content">
            <div class="respuesta"></div>
            <div class="row tablaComisiones">

                <div class="col-md-12">
                    <div class="panel panel-inverse">
                        <div class="panel-heading">
                            <h4 class="panel-title">TABLA DE COMISIONES DESDE EL <span
                                    class="text-danger"><?php echo $Start ?></span> HASTA <span
                                    class="text-danger"><?php echo $End ?></span></h4>
                            <div class="panel-heading-btn">
                                <button class="btn btn-xs btn-primary Busqueda">
                                    <i class="fa fa-search">Buscar</i>
                                </button>&nbsp;&nbsp;
                                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default"
                                    data-click="panel-expand"><i class="fa fa-expand"></i></a>
                                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-success"
                                    data-click="panel-reload"><i class="fa fa-redo"></i></a>
                                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning"
                                    data-click="panel-collapse"><i class="fa fa-minus"></i></a>
                                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-danger"
                                    data-click="panel-remove"><i class="fa fa-times"></i></a>
                            </div>
                        </div>
                        <div class="panel-body">
                            <form id="busqueda" action="?root=historialComisiones" method="post"
                                class="w-75 m-auto d-none">
                                <div class="row">
                                    <div class="col text-center">
                                        <label for="fechaInicio">Fecha inicio</label>
                                        <input type="date" name="start" id="fechaInicio" class="form-control"
                                            value="<?php echo $startBusqueda ?>">
                                        <div class="text-center text-danger d-none noStartDate">no ha indicado la fecha
                                            de iicio.</div>
                                    </div>
                                    <div class="col text-center">
                                        <label for="fechafin">Fecha fin</label>
                                        <input type="date" name="end" id="fechafin" class="form-control"
                                            value="<?php echo $fecha ?>">
                                        <div class="text-center text-danger d-none noEndDate">no ha indicado la fecha de
                                            cierre.</div>
                                    </div>
                                    <div class="col">
                                        <label for="btnFind">&nbsp;&nbsp;&nbsp;</label>
                                        <button title="Generar reporte" type="submit"
                                            class="btn btn-xs btn-danger form-control">BUSCAR</button>
                                    </div>
                                </div>
                            </form><br>
                            <table id="data-table-buttons"
                                class="table table-striped table-bordered table-td-valign-middle w-100">
                                <thead>
                                    <tr class="table-success">
                                        <th class="text-center">N&ordm;</th>
                                        <th class="text-center">SUCURSAL</th>
                                        <th class="text-center"><strong> USD</strong> VENTAS<br>ACTUALES</th>
                                        <th class="text-center"><strong> USD</strong> TOTAL<br>FACTURADO </th>
                                        <th class="text-center"><strong> Bs</strong> TOTAL<br>FACTURADO </th>

                                        <th class="text-center"><strong>USD</strong> META<br>ACTUAL-VENTAS</th>
                                        <th class="text-center"><strong>%</strong> COMISION<br>ACTUAL-VENTAS</th>
                                        <th class="text-center"><strong>#NRO</strong> PERSONAL<br>A DIVIDIR COMISION
                                        </th>
                                        <th class="text-center"><strong>USD</strong> TOTAL<br>COMISION-VENTAS</th>
                                        <th class="text-center">ACCIONES</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- CARGAMOS TODAS LAS VENTAS Y ABONOS -->
                                    <?php
$Num = 1;
    $SucursalCB = "Cochabamba";
    $queryVentasCB = mysqli_query($MySQLi, "SELECT SUM(TotalVentaUS)AS TotalVentaUS FROM Ventas WHERE Sucursal='$SucursalCB' AND Fecha BETWEEN '$Start'AND'$End' ") or die(mysqli_error($MySQLi));
    $dataCB = mysqli_fetch_assoc($queryVentasCB);
    $TotalVentaCB = $dataCB['TotalVentaUS'];

    // $queryAbonosCB = mysqli_query($MySQLi, "SELECT SUM(anticipoUSD)AS anticipoUSD FROM Abonos WHERE Sucursal='$SucursalCB'AND Fecha BETWEEN '$Start'AND '$End' ");
    // $dataAbonosCB = mysqli_fetch_assoc($queryAbonosCB);
    // $TotalAbonosCB = $dataAbonosCB['anticipoUSD'];

    $TotalGeneral1 = $TotalVentaCB;?>
                                    <tr>
                                        <td><?php echo $Num ?></td>
                                        <td><?php echo $SucursalCB ?></td>
                                        <td><?php echo number_format($TotalGeneral1, 2, ".", "") ?></td>
                                        <?php
$queryVentas = mysqli_query($MySQLi, "SELECT
																							SUM(amountTotal) AS amountTotal
																						FROM
																							factura
																						WHERE
																							branchId = 1 AND siatCodeState = 908 AND ( DATE_FORMAT(dateEmission, '%Y-%m-%d') BETWEEN '$Start' AND '$End' )") or die(mysqli_error($MySQLi));
    $dataVentas = mysqli_fetch_assoc($queryVentas);
    $TotalVentas = $dataVentas['amountTotal'];
    ?>
                                        <td><?php echo number_format(($TotalVentas / $dolarBd['precioDolar']), 2, ".", ""); ?>
                                        </td>
                                        <td><?php echo number_format(($TotalVentas), 2, ".", ""); ?></td>
                                        <?php
/*    CONSULTAMOS LA TABLA DE COMISIONES    */
    $queryComisionesCB = mysqli_query($MySQLi, "SELECT * FROM TablaComisiones WHERE Sucursal='$SucursalCB' ");
    $dataComisionesCB = mysqli_fetch_assoc($queryComisionesCB);
    ?>
                                        <?php
if ($TotalGeneral1 >= $dataComisionesCB['Meta2']) {
        $percent = $dataComisionesCB['Comision2'] . " &nbsp;%";
        $comi = $TotalGeneral1 * ($dataComisionesCB['Comision2'] / 100);
        $meta = $dataComisionesCB['Meta2'];
    } elseif ($TotalGeneral1 >= $dataComisionesCB['Meta1']) {
        $percent = $dataComisionesCB['Comision1'] . " &nbsp;%";
        $comi = $TotalGeneral1 * ($dataComisionesCB['Comision1'] / 100);
        $meta = $dataComisionesCB['Meta1'];
    } else {
        $comi = 0;
        $percent = "0 &nbsp;%";
        $meta = $dataComisionesCB['Meta1'];

    }
    ?>
                                        <td>
                                            <?php echo $meta ?>
                                        </td>
                                        <td>
                                            <?php echo $percent ?>
                                        </td>
                                        <td><?php echo $dataComisionesCB['personal_dividir'] ?></td>

                                        <td><?php
                                        $totalcomi=$comi;
                                        echo $comi ?></td>
                                        <td class="text-center">
                                            <a href="Reportes/pdf.php?Sucursal=<?php echo $SucursalCB ?>&fechaInicio=<?php echo $Start ?>&fechafin=<?php echo $End ?>"
                                                class="btn btn-xs btn-danger mostrarPagos"
                                                title="Mostrar pagos de comisiones <?php echo $SucursalCB ?>">
                                                <i class="fa fa-download"></i>
                                            </a>
                                            <button title="Editar Comision Cochabamba #1" id="1"
                                                class="btn btn-xs btn-primary btnAnular1"><i class="fa fa-cog"
                                                    aria-hidden="true" style="font-size: 15px"></i></button>
                                        </td>
                                    </tr> <?php
$Num = 2;
    $SucursalLP = "La Paz";
    $queryVentasLP = mysqli_query($MySQLi, "SELECT SUM(TotalVentaUS)AS TotalVentaUS FROM Ventas WHERE Sucursal='$SucursalLP' AND Fecha BETWEEN '$Start'AND'$End' ") or die(mysqli_error($MySQLi));
    $dataLP = mysqli_fetch_assoc($queryVentasLP);
    $TotalVentaLP = $dataLP['TotalVentaUS'];

    // $queryAbonosLP = mysqli_query($MySQLi, "SELECT SUM(anticipoUSD)AS anticipoUSD FROM Abonos WHERE Sucursal='$SucursalLP'AND Fecha BETWEEN '$Start'AND '$End' ");
    // $dataAbonosLP = mysqli_fetch_assoc($queryAbonosLP);
    // $TotalAbonosLP = $dataAbonosLP['anticipoUSD'];

    $TotalGeneral2 = $TotalVentaLP;?>
                                    <tr>
                                        <td><?php echo $Num ?></td>
                                        <td><?php echo $SucursalLP ?></td>
                                        <td><?php echo number_format($TotalGeneral2, 2, ".", "")?></td>
                                        <?php
$queryVentas = mysqli_query($MySQLi, "SELECT
                                        SUM(amountTotal) AS amountTotal
                                    FROM
                                        factura
                                    WHERE
                                        branchId = 2 AND siatCodeState = 908 AND ( DATE_FORMAT(dateEmission, '%Y-%m-%d') BETWEEN '$Start' AND '$End' )") or die(mysqli_error($MySQLi));
    $dataVentas = mysqli_fetch_assoc($queryVentas);
    $TotalVentas = $dataVentas['amountTotal'];
    ?>
                                        <td><?php echo number_format(($TotalVentas / $dolarBd['precioDolar']), 2, ".", ""); ?>
                                        </td>
                                        <td><?php echo number_format(($TotalVentas), 2, ".", ""); ?></td>
                                        <?php
/*    CONSULTAMOS LA TABLA DE COMISIONES    */
    $queryComisionesLP = mysqli_query($MySQLi, "SELECT * FROM TablaComisiones WHERE Sucursal='$SucursalLP' ");
    $dataComisionesLP = mysqli_fetch_assoc($queryComisionesLP);
    ?>
                                        <?php
if ($TotalGeneral2 >= $dataComisionesLP['Meta2']) {
        $comi = $TotalGeneral2 * ($dataComisionesLP['Comision2'] / 100);
        $percent = $dataComisionesLP['Comision2'] . " &nbsp;%";
        $meta = $dataComisionesLP['Meta2'];
    } elseif ($TotalGeneral2 >= $dataComisionesLP['Meta1']) {
        $comi = $TotalGeneral2 * ($dataComisionesLP['Comision1'] / 100);
        $percent = $dataComisionesLP['Comision1'] . " &nbsp;%";
        $meta = $dataComisionesLP['Meta1'];
    } else {
        $comi = 0;
        $percent = "0 &nbsp;%";
        $meta = $dataComisionesLP['Meta1'];

    }
    ?>


                                        <td><?php echo $meta ?></td>
                                        <td><?php echo $percent ?></td>
                                        <td><?php echo $dataComisionesLP['personal_dividir'] ?></td>
                                        <td>

                                            <?php 
                                            $totalcomi=$totalcomi+$comi;
                                            
                                            echo $comi ?>
                                        </td>


                                        <td class="text-center">
                                            <a href="Reportes/pdf.php?Sucursal=<?php echo $SucursalLP ?>&fechaInicio=<?php echo $Start ?>&fechafin=<?php echo $End ?>"
                                                class="btn btn-xs btn-danger mostrarPagos"
                                                title="Mostrar pagos de comisiones <?php echo $SucursalLP ?>">
                                                <i class="fa fa-download"></i>
                                            </a>
                                            <button title="Editar Comision La Paz #2" id="2"
                                                class="btn btn-xs btn-primary btnAnular1"><i class="fa fa-cog"
                                                    aria-hidden="true" style="font-size: 15px"></i></button>
                                        </td>
                                    </tr> <?php
$Num = 3;
    $SucursalSC = "Santa Cruz";
    $queryVentasSC = mysqli_query($MySQLi, "SELECT SUM(TotalVentaUS)AS TotalVentaUS FROM Ventas WHERE Sucursal='$SucursalSC' AND Fecha BETWEEN '$Start'AND'$End' ") or die(mysqli_error($MySQLi));
    $dataSC = mysqli_fetch_assoc($queryVentasSC);
    $TotalVentaSC = $dataSC['TotalVentaUS'];

    // $queryAbonosSC = mysqli_query($MySQLi, "SELECT SUM(anticipoUSD)AS anticipoUSD FROM Abonos WHERE Sucursal='$SucursalSC'AND Fecha BETWEEN '$Start'AND '$End' ");
    // $dataAbonosSC = mysqli_fetch_assoc($queryAbonosSC);
    // $TotalAbonosSC = $dataAbonosSC['anticipoUSD'];

    $TotalGeneral3 = $TotalVentaSC;?>
                                    <tr>
                                        <td><?php echo $Num ?></td>
                                        <td><?php echo $SucursalSC ?></td>
                                        <td><?php echo number_format($TotalGeneral3, 2, ".", "") ?></td>
                                        <?php
$queryVentas = mysqli_query($MySQLi, "SELECT
                                        SUM(amountTotal) AS amountTotal
                                    FROM
                                        factura
                                    WHERE
                                        branchId = 3 AND siatCodeState = 908 AND ( DATE_FORMAT(dateEmission, '%Y-%m-%d') BETWEEN '$Start' AND '$End' )") or die(mysqli_error($MySQLi));
    $dataVentas = mysqli_fetch_assoc($queryVentas);
    $TotalVentas = $dataVentas['amountTotal'];
    ?>
                                        <td><?php echo number_format(($TotalVentas / $dolarBd['precioDolar']), 2, ".", ""); ?>
                                        </td>
                                        <td><?php echo number_format(($TotalVentas), 2, ".", ""); ?></td>
                                        <?php
/*    CONSULTAMOS LA TABLA DE COMISIONES    */
    $queryComisionesSC = mysqli_query($MySQLi, "SELECT * FROM TablaComisiones WHERE Sucursal='$SucursalSC' ");
    $dataComisionesSC = mysqli_fetch_assoc($queryComisionesSC);
    ?>

                                        <?php
if ($TotalGeneral3 >= $dataComisionesSC['Meta2']) {
    $percent = $dataComisionesSC['Comision2'] . " &nbsp;%";
    $comi = $TotalGeneral3 * ($dataComisionesSC['Comision2'] / 100);
    $meta=$dataComisionesSC['Meta2'];
} elseif ($TotalGeneral3 >= $dataComisionesSC['Meta1']) {
    $percent = $dataComisionesSC['Comision1'] . " &nbsp;%";
    $comi = $TotalGeneral3 * ($dataComisionesSC['Comision1'] / 100);
    $meta=$dataComisionesSC['Meta1'];
} else {
    $comi = 0;
    $percent = "0 &nbsp;%";
    $meta=$dataComisionesSC['Meta1'];
    
}
?>

                                        <td>
                                            <?php echo $meta ?>
                                        </td>
                                        <td><?php echo $percent ?></td>
                                        <td><?php echo $dataComisionesSC['personal_dividir'] ?></td>
                                        <td><?php
                                        $totalcomi=$totalcomi+$comi;
                                         echo $comi ?></td>

                                        <td class="text-center">
                                            <a href="Reportes/pdf.php?Sucursal=<?php echo $SucursalSC ?>&fechaInicio=<?php echo $Start ?>&fechafin=<?php echo $End ?>"
                                                class="btn btn-xs btn-danger mostrarPagos"
                                                title="Mostrar pagos de comisiones <?php echo $SucursalSC ?>">
                                                <i class="fa fa-download"></i>
                                            </a>
                                            <button title="Editar Comision Santa Cruz #3" id="3"
                                                class="btn btn-xs btn-primary btnAnular1"><i class="fa fa-cog"
                                                    aria-hidden="true" style="font-size: 15px"></i></button>
                                        </td>
                                    </tr> <?php
$Num = 4;
    $SucursalTJ = "Ferias";
    $queryVentasTJ = mysqli_query($MySQLi, "SELECT SUM(TotalVentaUS)AS TotalVentaUS FROM Ventas WHERE Sucursal='$SucursalTJ' AND Fecha BETWEEN '$Start'AND'$End' ") or die(mysqli_error($MySQLi));
    $dataTJ = mysqli_fetch_assoc($queryVentasTJ);
    $TotalVentaTJ = $dataTJ['TotalVentaUS'];

    // $queryAbonosTJ = mysqli_query($MySQLi, "SELECT SUM(anticipoUSD)AS anticipoUSD FROM Abonos WHERE Sucursal='$SucursalTJ'AND Fecha BETWEEN '$Start'AND '$End' ");
    // $dataAbonosTJ = mysqli_fetch_assoc($queryAbonosTJ);
    // $TotalAbonosTJ = $dataAbonosTJ['anticipoUSD'];

    $TotalGeneral4 = $TotalVentaTJ;?>
                                    <tr>
                                        <td><?php echo $Num ?></td>
                                        <td><?php echo $SucursalTJ ?></td>
                                        <td><?php echo number_format($TotalGeneral4, 2, ".", "") ?></td>
                                        <?php
$queryVentas = mysqli_query($MySQLi, "SELECT
                                        SUM(amountTotal) AS amountTotal
                                    FROM
                                        factura
                                    WHERE
                                        branchId = 4 AND siatCodeState = 908 AND ( DATE_FORMAT(dateEmission, '%Y-%m-%d') BETWEEN '$Start' AND '$End' )") or die(mysqli_error($MySQLi));
    $dataVentas = mysqli_fetch_assoc($queryVentas);
    $TotalVentas = $dataVentas['amountTotal'];
    ?>
                                        <td><?php echo number_format(($TotalVentas / $dolarBd['precioDolar']), 2, ".", ""); ?>
                                        </td>
                                        <td><?php echo number_format(($TotalVentas), 2, ".", ""); ?></td>
                                        <?php
/*    CONSULTAMOS LA TABLA DE COMISIONES    */
    $queryComisionesTJ = mysqli_query($MySQLi, "SELECT * FROM TablaComisiones WHERE Sucursal='$SucursalTJ' ");
    $dataComisionesTJ = mysqli_fetch_assoc($queryComisionesTJ);
    ?>


                                        <?php
if ($TotalGeneral4 >= $dataComisionesTJ['Meta2']) {
    $percent = $dataComisionesTJ['Comision2'] . " &nbsp;%";
    $comi = $TotalGeneral4 * ($dataComisionesTJ['Comision2'] / 100);
    $meta=$dataComisionesTJ['Meta2'];
} elseif ($TotalGeneral4 >= $dataComisionesTJ['Meta1']) {
    $percent = $dataComisionesTJ['Comision1'] . " &nbsp;%";
    $comi = $TotalGeneral4 * ($dataComisionesTJ['Comision1'] / 100);
    $meta=$dataComisionesTJ['Meta1'];
} else {
    $comi = 0;
    $percent = "0 &nbsp;%";
    $meta=$dataComisionesTJ['Meta1'];
}
?>
                                        <td><?php echo $meta ?></td>


                                        <td>
                                            <?php echo $percent ?>
                                        </td>
                                        <td><?php echo $dataComisionesTJ['personal_dividir'] ?></td>
                                        <td><?php 
                                        $totalcomi=$totalcomi+$comi;
                                        
                                        echo $comi ?></td>

                                        <td class="text-center">
                                            <a href="Reportes/pdf.php?Sucursal=<?php echo $SucursalTJ ?>&fechaInicio=<?php echo $Start ?>&fechafin=<?php echo $End ?>"
                                                class="btn btn-xs btn-danger mostrarPagos"
                                                title="Mostrar pagos de comisiones <?php echo $SucursalTJ ?>">
                                                <i class="fa fa-download"></i>
                                            </a>
                                            <button title="Editar Comision Tarija #4" id="4"
                                                class="btn btn-xs btn-primary btnAnular1"><i class="fa fa-cog"
                                                    aria-hidden="true" style="font-size: 15px"></i></button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            5
                                        </td>
                                        <th class="text-right">
                                            TOTAL
                                        </th>

                                        <th>
                                            <?php  echo number_format(($TotalGeneral1+$TotalGeneral2+$TotalGeneral3+$TotalGeneral4), 2, ".", "") ?>
                                        </th>
                                        <th>
                                            <?php
										$queryVentas	=	mysqli_query($MySQLi,"SELECT
                                        SUM(amountTotal) AS amountTotal
                                    FROM
                                        factura
                                    WHERE
                                        siatCodeState = 908 AND ( DATE_FORMAT(dateEmission, '%Y-%m-%d') BETWEEN '$Start' AND '$End' )")or die(mysqli_error($MySQLi));
										$dataVentas		=	mysqli_fetch_assoc($queryVentas);
										$TotalVentasBS 	=	$dataVentas['amountTotal'];
                                        $TotalVentasUSD 	=	$dataVentas['amountTotal']/$dolarBd['precioDolar'];
										//echo "Bs ". number_format(($TotalVentas),2);
                                        echo number_format($TotalVentasUSD, 2, ".", "");
                                            ?>
                                        </th>
                                        <th>
                                            <?php  echo number_format($TotalVentasBS, 2, ".", "");?>

                                        </th>
                                        <th></th>
                                        <th></th>
                                        <th></th>

                                        <th>
                                            <?php  echo number_format($totalcomi, 2, ".", "");?>
                                        </th>
                                        <th>

                                        </th>
                                    </tr>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row d-none FormAnulation1">
                <div class="col-md-2"></div>
                <div class="col-md-8">
                    <div class="panel panel-inverse">
                        <div class="panel-heading">

                            <h4 class="panel-title" id="sucursal" name="sucursal"></h4>
                            <button class="btn btn-xs btn-danger cancelar1">CANCELAR</button>
                        </div>
                        <div class="panel-body">
                            <form id="editComision">
                                <div class="row">
                                    <h5 class="panel-title center">PRIMERA META Y COMISION</h5>
                                </div>
                                <!--form---->
                                <div class="row">
                                    <div class="col">
                                        <label for="Meta1">META</label>
                                        <input type="number" name="Meta1" id="Meta1" min="0" class="form-control"
                                            placeholder="1RA META" step="1" required>
                                    </div>

                                    <div class="col">
                                        <label for="Comision1">COMISION #1</label>
                                        <input type="number" name="Comision1" id="Comision1" class="form-control"
                                            placeholder="Porcentaje Comision" min="0" max="9.9" step="0.1" required>
                                        <div class="text-danger d-none emptyComision1">La comision no puede ser mayor al
                                            9.9%</div>
                                    </div>

                                </div>
                                &nbsp;
                                <div class="row">
                                    <h5 class="panel-title center">SEGUNDA META Y COMISION</h5>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <label for="Meta2">META</label>
                                        <input type="number" name="Meta2" id="Meta2" min="0" class="form-control"
                                            placeholder="2DA META" step="1" required>
                                    </div>
                                    <div class="col">
                                        <label for="Comision2">COMISION #2</label>
                                        <input type="number" name="Comision2" id="Comision2" class="form-control"
                                            placeholder="Porcentaje Comision" min="0" max="9.9" step="0.1" required>
                                        <div class="text-danger d-none emptyComision2" >La comision no puede ser mayor al
                                            9.9%</div>
                                    </div>

                                </div>
                                &nbsp;
                                <div class="row">
                                    <h5 class="panel-title center">#NUMERO DE PERSONAL A DIVIDIR LA COMISION</h5>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <label for="qtyPersonal">CANTIDAD DE PERSONAL</label>
                                        <input type="number" name="qtyPersonal" id="qtyPersonal" min="1" max="50"
                                            class="form-control" placeholder="#NUMERO DE PERSONAL A DIVIDIR" step="1" required>
                                        <div class="text-danger d-none emptyqtyPersonal">La cantidad no puede ser vacio
                                            o nulo</div>
                                    </div>
                                    <div class="col">
                                    </div>

                                </div>
                                <div class="row mt-3">

                                    <div class="col">
                                        <input type="hidden" name="idTabla" id="idTabla">

                                        <label for="submit">&nbsp;&nbsp;</label>
                                        <button class="btn btn-xs btn-primary form-control btnAplicarCambios"
                                            id="submit" value="EDITAR CAMBIOS">APLICAR CAMBIOS</button>

                                    </div>
                                </div>
                            </form>
                            <div id="resp">


                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <a href="javascript:;" class="btn btn-icon btn-circle btn-primary btn-scroll-to-top fade"
            data-click="scroll-top"><i class="fa fa-angle-up"></i></a>
        <?php include 'php/footer.php';?>
    </div>
    <?php include 'php/script_comisiones.php';?>
</body>

</html><?php
} else {?>
<script type="text/javascript">
location.replace("?root=404");
</script><?php
}
?>