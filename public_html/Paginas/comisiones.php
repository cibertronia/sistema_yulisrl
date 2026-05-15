<?php
include 'includes/conexion.php';
include 'includes/date.class.php';
mysqli_query($MySQLi, "SET lc_time_names= 'es_BO' ");
$idUser = $_SESSION['idUser'];
$ConsltaUser = mysqli_query($MySQLi, "SELECT * FROM Usuarios WHERE idUser='$idUser' ");
$datosUser = mysqli_fetch_assoc($ConsltaUser);
$miCiudad = $datosUser['Ciudad'];

$sqlPrecioDolar = mysqli_query($MySQLi, "SELECT * FROM precio ");
$dolarBd = mysqli_fetch_assoc($sqlPrecioDolar);

include 'includes/App/Models/Sucursal.php';
use App\Models\Sucursal;

$sucursalesModel = new Sucursal();
$sucursales = $sucursalesModel->all();    
if (isset($_POST['start']) and isset($_POST['end'])) {
    $startBusqueda = $_POST['start'];
    $fecha = $_POST['end'];
}
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
    <div id="page-container" class="fade page-sidebar-fixed page-header-fixed" data-sucursales='<?= json_encode($sucursales) ?>'>
        <?php
include 'php/top_menu.php';
include 'php/left_menu_comisiones.php';
?>
        <div id="content" class="content">
            <div class="respuesta"></div>
            <div class="row tablaComisiones">

                <div class="col-md-12">
                    <div class="panel panel-inverse">
                        <div class="panel-heading">
                            <?php 
                            if (isset($_POST['start']) and isset($_POST['end'])) {
                                $Start = $_POST['start'];
                                $End = $_POST['end'];                                
                            ?>
                            <h4 class="panel-title">TABLA DE COMISIONES DESDE EL <span
                                    class="text-danger"><?php echo $Start ?></span> HASTA <span
                                    class="text-danger"><?php echo $End ?></span></h4>
                            <?php } else { ?>
                            <h4 class="panel-title">TABLA DE COMISIONES DEL MES DE
                            <?php echo strtoupper($mes) ?></h4>
                            <?php } ?>
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
                            <form id="busqueda" action="?root=comisiones" method="post"
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
                                        <button title="Buscar" type="submit"
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
                                    <!-- CARGAMOS TODAS LAS VENTAS Y ABONOS (COMPRAS AL CRÉDITO Y POR ABONO) POR SUCURSAL-->
                                    <?php 
                                    $consecutivo = 1;
                                    $totalVentas = 0;
                                    $totalComisiones = 0;
                                    foreach($sucursales as $item) { ?>
                                        <tr>
                                            <!-- Numero consecutivo -->
                                            <td><?= $consecutivo ?></td>

                                            <!-- Sucursal -->
                                            <td><?= $item['Sucursal'] ?></td>

                                            <!-- Ventas actuales -->
                                            <?php
                                                $queryVentas = mysqli_query($MySQLi, "SELECT SUM(TotalVentaUS) AS TotalVentaUS FROM Ventas WHERE Sucursal='{$item['Sucursal']}' AND Fecha BETWEEN '$startBusqueda' AND  '$fecha' ") or die(mysqli_error($MySQLi));
                                                $dataVentas = mysqli_fetch_assoc($queryVentas);
                                                ${'TotalVentas'.$item['iniciales']} = $dataVentas['TotalVentaUS'];
                                            ?>
                                            <td><?= number_format(${'TotalVentas'.$item['iniciales']}, 2, ".", "") ?></td>

                                            <!-- Total Facturado USD-->
                                            <?php
                                                // ------------------------------------- OJO EN LA CONSULA -------------------------------------------//
                                                // La condición de branchId debe coincidir con el idSucursal de la tabla Sucursales no con el campo codigo_factura.
                                                // Ya que el guardar la factura en el sistema Yuli se guarda el idSucursal en branchId
                                                $queryVentas = mysqli_query($MySQLi, "SELECT SUM(amountTotal) AS amountTotal FROM factura WHERE branchId = {$item['idSucursal']} AND siatCodeState = 908 AND ( DATE_FORMAT(dateEmission, '%Y-%m-%d') BETWEEN '$startBusqueda' AND '$fecha' )") or die(mysqli_error($MySQLi));
                                                $dataVentas = mysqli_fetch_assoc($queryVentas);
                                                $TotalVentasFactura = $dataVentas['amountTotal'];
                                            ?>
                                            <td><?= number_format(($TotalVentasFactura / $dolarBd['precioDolar']), 2, ".", "") ?></td>

                                            <!-- Total Facturado Bs-->
                                            <td><?= number_format(($TotalVentasFactura), 2, ".", ""); ?></td>

                                            <!-- Meta USD -->
                                            <?php 
                                                $queryComisiones = mysqli_query($MySQLi, "SELECT * FROM TablaComisiones WHERE Sucursal='{$item['Sucursal']}' ");
                                                $dataComisiones = mysqli_fetch_assoc($queryComisiones);
                                                $porcentaje = 0;
                                                $comision = 0;
                                                if (${'TotalVentas'.$item['iniciales']} >= $dataComisiones['Meta2']) {
                                                    $meta = $dataComisiones['Meta2'];
                                                    $porcentaje = $dataComisiones['Comision2'];
                                                    $comision = ${'TotalVentas'.$item['iniciales']} * ($dataComisiones['Comision2'] / 100);
                                                } elseif (${'TotalVentas'.$item['iniciales']} >= $dataComisiones['Meta1']) {
                                                    $meta = $dataComisiones['Meta1'];
                                                    $porcentaje = $dataComisiones['Comision1'];
                                                    $comision = ${'TotalVentas'.$item['iniciales']} * ($dataComisiones['Comision1'] / 100);
                                                } else {
                                                    $meta = $dataComisiones['Meta1'];
                                                }
                                            ?>
                                            <td><?= $meta ?></td>

                                            <!-- Porcentaje Comision -->
                                            <td><?= $porcentaje ?> %</td>

                                            <!-- Personal a dividir comision -->
                                            <td><?= $dataComisiones['personal_dividir'] ?></td>

                                            <!-- Total Comision USD -->
                                            <td><?= $comision ?></td>

                                            <!-- Acciones -->
                                            <td class="text-center">
                                                <a href="Reportes/pdf.php?Sucursal=<?= $item['Sucursal'] ?>&fechaInicio=<?php echo $startBusqueda ?>&fechafin=<?php echo $fecha ?>"
                                                    class="btn btn-xs btn-danger mostrarPagos"
                                                    title="Mostrar pagos de comisiones <?= $item['Sucursal'] ?>">
                                                    <i class="fa fa-download"></i>
                                                </a>
                                                <button title="Editar Comision <?= $item['Sucursal'] ?> #<?= $item['idSucursal'] ?>" id="<?= $item['idSucursal'] ?>"
                                                    class="btn btn-xs btn-primary btnAnular1"><i class="fa fa-cog"
                                                    aria-hidden="true" style="font-size: 15px"></i>
                                                </button>
                                            </td>
                                        </tr>
                                        
                                    <?php 
                                        $consecutivo++;
                                        $totalVentas += ${'TotalVentas'.$item['iniciales']};
                                        $totalComisiones += $comision;
                                    } ?> 

                                    <!-- FILA DE TOTRALES -->
                                    <tr>
                                        <td><?= $consecutivo ?></td>
                                        <th class="text-right">
                                            TOTAL
                                        </th>

                                        <th><?= number_format(($totalVentas), 2, ".", "") ?></th>
                                        <th>
                                            <?php
                                            $queryVentas	=	mysqli_query($MySQLi,"SELECT
                                            SUM(amountTotal) AS amountTotal
                                            FROM
                                                factura
                                            WHERE
                                                siatCodeState = 908 AND ( DATE_FORMAT(dateEmission, '%Y-%m-%d') BETWEEN '$startBusqueda' AND '$fecha' )")or die(mysqli_error($MySQLi));
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

                                        <th><?= number_format($totalComisiones, 2, ".", "") ?></th>
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
                                        <div class="text-danger d-none emptyComision2">La comision no puede ser mayor al
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
                                            class="form-control" placeholder="#NUMERO DE PERSONAL A DIVIDIR" step="1">
                                        <div class="text-danger d-none emptyqtyPersonal">La cantidad no puede ser vacio o nulo</div>
                                    </div>
                                    <div class="col">
                                    </div>

                                </div>
                                <div class="row mt-3">

                                    <div class="col">
                                        <input type="hidden" name="idTabla" id="idTabla">

                                        <label for="submit">&nbsp;&nbsp;</label>
                                        <div class="text-danger d-none emptyButton">LOS CAMPOS NO PUEDEN SER NULOS O VACIOS</div>
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

</html><?php mysqli_close($MySQLi);?>