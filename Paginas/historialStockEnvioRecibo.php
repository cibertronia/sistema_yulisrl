<?php
include 'includes/conexion.php';
include 'includes/date.class.php';
// error_reporting(0);
mysqli_query($MySQLi, "SET lc_time_names= 'es_BO' ");
$idUser = $_SESSION['idUser'];
$ConsltaUser = mysqli_query($MySQLi, "SELECT * FROM Usuarios WHERE idUser='$idUser' ");
$datosUser = mysqli_fetch_assoc($ConsltaUser);
$miCiudad = $datosUser['Ciudad'];

include 'includes/App/Models/Sucursal.php';
use App\Models\Sucursal;

$sucursalesModel = new Sucursal();
$sucursal = $sucursalesModel->all();
//arreglo con las class por sucursal
$sucursalesClass = [
    1 => 'bg-warning',
    2 => 'bg-primary',
    3 => 'bg-success',
    4 => 'bg-info',
    5 => 'bg-danger',
];
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <title>HISTORIAL ENVIOS</title>
    <?php include 'php/meta.php'; ?>
    <link href="assets/css/apple/app.min.css" rel="stylesheet">
    <link href="assets/plugins/ionicons/css/ionicons.min.css" rel="stylesheet">
    <link href="assets/plugins/datatables.net-bs4/css/dataTables.bootstrap4.min.css" rel="stylesheet">
    <link href="assets/plugins/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css" rel="stylesheet">
    <link href="assets/plugins/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css" rel="stylesheet">
</head>

<body>
    <?php include 'php/loader.php'; ?>
    <div id="page-container" class="fade page-sidebar-fixed page-header-fixed">
        <?php
        include 'php/top_menu.php';
        include 'php/left_menu_historial_stock.php';
        ?>
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
            ?>
            <div class="row tableUsers">
                <div class="col-md-12">
                    <div class="panel panel-success">
                        <div class="panel-heading">
                            <h4 class="panel-title"
                                style="text-transform: uppercase;letter-spacing: 1px;font-size: 16px">HISTORIAL
                                STOCK PRODUCTOS ENVIADOS - RECIBIDOS

                                &nbsp;&nbsp;&nbsp;

                                <span
                                    style="text-transform: uppercase;letter-spacing: 1px;font-size: 16px"><?php echo $mes ?></span>&nbsp;&nbsp;&nbsp;
                                =====>
                            </h4>
                            <div class="panel-heading-btn">

                                <div class="input-group input-daterange">
                                    <form action="?root=historialStockEnvioRecibo" method="post">
                                        <input required="" type="date" name="inicio" value="<?php echo $Inicio ?>">
                                        <input required="" type="date" name="fin" value="<?php echo $Fin ?>">
                                        <input type="submit" class="btn btn-xs btn-danger" value="BUSCAR">
                                    </form>

                                </div>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

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
                            <?php $granTotal=0;   ?>

                            <div class="row">

                                <?php foreach ($sucursal as $item) { ?>
                                <div class="col-xl-4 col-md-6">
                                    <div class="widget widget-stats <?= $sucursalesClass[$item['idSucursal']] ?>">
                                        <div class="stats-icon"><i class="fa fa-barcode" style="font-size: 65px"></i>
                                        </div>
                                        <div class="stats-info">
                                            <h4><?=$item['Sucursal']?></h4>
                                            <p><?php
                                                $inicial = strtolower($item['iniciales']);
                                                $queryVentas	=	mysqli_query($MySQLi,"SELECT SUM($inicial) AS amountTotal FROM historial_stock_envios WHERE (DATE_FORMAT(dateEmission, '%Y-%m-%d') BETWEEN '$Inicio' AND '$Fin') ")or die(mysqli_error($MySQLi));
                                                $dataVentas		=	mysqli_fetch_assoc($queryVentas);
                                                $TotalVentas 	=	$dataVentas['amountTotal'];
                                                ${$inicial} = $TotalVentas;
                                                $granTotal=$granTotal+$TotalVentas;
                                                echo "Descuento Stock <br>". ($TotalVentas);?>
                                            </p>
                                        </div>

                                    </div>
                                </div>
                                <?php } ?>
                            </div>


                            <div class="row">
                                <div class="col-xl-3"></div>
                                <div class="col-xl-3 col-md-6">
                                    <br>
                                    <a class="btn btn-secondary btn-sm btn-block"
                                        href="Reportes/pdf.php?historial_envios_completo_sin_fechas=sinfechas"
                                        title="Historial todos los Envios "><span style="color: white">
                                            EXCEL LISTADO GENERAL<br> ENVIOS</span>&nbsp;&nbsp;
                                        <i class="fa fa-download" style="color: white"></i>
                                    </a>
                                </div>

                                <div class="col-xl-3 col-md-6">
                                    <br>
                                    <a class="btn btn-secondary btn-sm btn-block"
                                        href="Reportes/pdf.php?historial_envios_completo_con_fechas=confechas&fechaInicio=<?php echo $Inicio ?>&fechafin=<?php echo $Fin ?>"
                                        title="Historial todos los Envios en rango de fechas"><span
                                            style="color: white">
                                            EXCEL LISTADO ENVIOS<br>CON RANGO DE FECHAS</span>&nbsp;&nbsp;
                                        <i class="fa fa-download" style="color: white"></i>
                                    </a>

                                </div>
                            </div>
                            <br>

                            <table id="data-table-buttons" width="100%"
                                class="table table-striped table-bordered table-td-valign-middle w-100">
                                <thead>
                                    <tr>
                                        <th width="5%" class="text-center">N&ordm;</th>
                                        <!-- <th width="5%" class="text-center">idProducto</th> -->
                                        <th class="text-center">PRODUCTO</th>
                                        <th class="text-center">MARCA</th>
                                        <th class="text-center">MODELO</th>
                                        <th width="5%" class="text-center">INICIAL</th>
                                        <?php foreach ($sucursal as $item) { ?>
                                        <th width="5%" class="text-center text-light <?= $sucursalesClass[$item['idSucursal']] ?>"><?= $item['iniciales'] ?></th>
                                        <?php } ?>
                                        <th width="5%" class="text-center">FINAL</th>
                                        <th class="text-center">VENDEDOR</th>
                                        <th class="text-center">FECHA</th>
                                        <th class="text-center">DESCRIPCION</th>
                                        <!-- <th width="13%" class="text-center">ACCIONES</th> -->
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    include 'includes/conexion.php';

                                    $sqlurlcucu = mysqli_query($MySQLi, "SELECT * FROM token_access");
                                    $dataurlcucu = mysqli_fetch_assoc($sqlurlcucu) or die(mysqli_error($MySQLi));
                                    $urlcucu = $dataurlcucu['urlcucu'];

                                    $Num = 1;
                                    $QueryFactura = mysqli_query($MySQLi, "SELECT* FROM historial_stock_envios WHERE (DATE_FORMAT(dateEmission, '%Y-%m-%d') BETWEEN '$Inicio' AND '$Fin') ");
                                    while ($data = mysqli_fetch_assoc($QueryFactura)) {
                                    ?>
                                    <tr class="odd gradeX">
                                        <td class="text-center"><?php echo $Num ?></td>
                                        <!-- <td class="text-center"><?php //echo $data['idProducto'] ?></td> -->
                                        <td class="text-center"><?php echo $data['producto'] ?></td>

                                        <td class="text-center">
                                            <?php
                                            $idProducto=$data['idProducto'];
                                            $dataProducto = mysqli_query($MySQLi, "SELECT* FROM Productos WHERE idProducto='$idProducto' ");
                                            $dataProducto = mysqli_fetch_assoc($dataProducto);

                                            echo $dataProducto['Marca'] ?>
                                        </td>
                                        <td class="text-center">
                                            <?php echo $dataProducto['Modelo'] ?>
                                        </td>


                                        <td class="text-center"><?php echo $data['inicial'] ?></td>
                                        <?php foreach ($sucursal as $item) { ?>
                                        <td class="text-center"><?= $data[strtolower($item['iniciales'])] ?></td>
                                        <?php } ?>
                                        <td class="text-center"><?php echo $data['final'] ?></td>
                                        <td class="text-center"><?php echo $data['vendedor'] ?> </td>
                                        <td class="text-center"><?php echo $data['dateEmission'] ?> </td>
                                        <td class="text-center"><?php echo $data['descripcion'] ?> </td>
                                    </tr>
                                    <?php
                                        $Num++;
                                    }
                                    mysqli_close($MySQLi); ?>

                                    <tr class="odd gradeX">
                                        <td class="text-center"><?php echo $Num ?></td>
                                        <!-- <td class="text-center"><?php //echo $data['idProducto'] ?></td> -->
                                        <th class="text-center">TOTAL</th>
                                        <td class="text-center"></td>
                                        <td class="text-center"></td>
                                        <td class="text-center"></td>
                                        <?php foreach ($sucursal as $item) { 
                                            $inicial = strtolower($item['iniciales']);
                                        ?>
                                        <td class="text-center"><?= ${$inicial} ?></td>
                                        <?php } ?>
                                        <td class="text-center"></td>
                                        <td class="text-center"></td>
                                        <td class="text-center"></td>
                                        <td class="text-center"></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <!-- end panel-body -->
                    </div>
                </div>
            </div>
        </div>
        <a href="javascript:;" class="btn btn-icon btn-circle btn-primary btn-scroll-to-top fade"
            data-click="scroll-top"><i class="fa fa-angle-up"></i></a>
        <?php include 'php/footer.php'; ?>
    </div>
    <?php include 'php/script_usuarios.php'; ?>
</body>

</html>