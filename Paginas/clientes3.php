<?php
$idUser = $_SESSION['idUser'];
$ConsltaUser = mysqli_query($MySQLi, "SELECT * FROM Usuarios WHERE idUser='$idUser' ");
$datosUser = mysqli_fetch_assoc($ConsltaUser);
$miCiudad = $datosUser['Ciudad'];
error_reporting(0);
if ($_SESSION['Rango']=='2') {
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <title>CLIENTES HABITUALES</title>
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
    <!-- <script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.4/dist/Chart.min.js"></script> -->


</head>

<body><?php include 'php/loader.php';?>
    <div id="page-container" class="fade page-sidebar-fixed page-header-fixed"><?php
include 'php/top_menu.php';
include 'php/left_menu_clientes1.php';?>
        <div id="content" class="content">
            <div class="respuesta"></div>
            <?php
$Sucursal='';
if (isset($_POST['inicio'])) {
    $Inicio = $_POST['inicio'];
    $Fin = $_POST['fin'];
    $Sucursal=$_POST['Sucursal'];
    $queryClientes = querySiHayPost($Inicio, $Fin,$Sucursal );
    $btncolor = btnColorPost($_POST['Sucursal']);
} else {
    $Inicio = $startBusqueda; //startbuskeda = 1 del mes
    $Fin = $fecha; //fecha = hoy
    $queryClientes = queryNoPost($Inicio, $Fin);
    $btncolor = 'inverse';
}
if($btncolor == '' || $Sucursal==''){
    $btncolor= 'inverse';
    $Sucursal='Todas Las Sucursales';}
    //echo $queryClientes;
    $Sucursal=  nombreSucursal($Sucursal);

?>
            <input type="hidden" name="sucursalpost" id="sucursalpost" value="<?php echo $Sucursal ?>">
            <div class="row tableCotizaciones">
                <div class="col-md-12">

                    <div class="panel panel-<?php echo $btncolor ?>">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <span style="text-transform: uppercase;letter-spacing: 1px;font-size: 16px">Clientes habituales que vienen cada
                                 vez <br> sin importar la cantidad y el valor ==> <span
                                        style="text-transform: uppercase;letter-spacing: 1px;font-size: 18px">
                                        <?php
                                        if($Sucursal==''){
                                            echo 'Todas Las Sucursales';
                                        }else{echo $Sucursal;}
                                         ?></span></span>&nbsp;


                            </h4>
                            <div class="panel-heading-btn">
                                &nbsp;&nbsp;
                                <div class="input-group input-daterange">
                                    <form action="?root=clientes3" method="post">
                                        <input required="" type="date" name="inicio" id="inicio"
                                            value="<?php echo $Inicio ?>">
                                        <input required="" type="date" name="fin" id="fin" value="<?php echo $Fin ?>">
                                        <!-- <button class="btn btn-xs btn-success botonBuscar" value="BUSCAR">BUSCAR</button> -->
                                        <input type="submit" class="btn btn-xs btn-success" value="BUSCAR">

                                </div>
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
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
                            <div class="row">

                                <!-- <a class="btn btn-success btn-sm btn-block"
                                        href="Reportes/reporteClientesCantidadComprada.php?reporteClientesCantidadComprada=<?php //echo $Sucursal ?>&fechaInicio=<?php //echo $Inicio ?>&fechafin=<?php //echo $Fin ?>"
                                        title="CLIENTES QUE COMPRARON EN MÁS CANTIDAD DE UNIDADES"><span
                                            style="color: white">
                                            EXCEL DESCARGAR</span>&nbsp;&nbsp;
                                        <i class="fa fa-download" style="color: white"></i>
                                    </a> -->

                                <div class="col-4">

                                </div>

                                <div class="col-5">
                                </div>
                                <div class="col-3">

                                    <select name="Sucursal" id="Sucursal" class="form-control">
                                        <option selected="" disabled="" value='todas_Las_Sucursales'>Seleccione
                                            Sucursal
                                        </option>
                                        <option value='todas_Las_Sucursales'>Todas Las Sucursales</option>
                                        <?php
$consultSucursal = mysqli_query($MySQLi, "SELECT * FROM Sucursales ORDER BY Sucursal ASC");
while ($dataSucursal = mysqli_fetch_assoc($consultSucursal)) {
    echo '<option value=' . $dataSucursal['idSucursal'] . '>' . $dataSucursal['Sucursal'] . '</option>';}?>
                                    </select>
                                    </form>
                                </div>
                            </div>
                            <br>
                            <hr>
                            <div class="row">
                                <div class="col-1"></div>
                                <div class="col-lg-9">
                                    <canvas id="grafica"></canvas>
                                    <!-- <script type="text/javascript" src="../script.js"></script> -->
                                </div>
                                <div class="col-2"></div>

                            </div>
                            <hr>
                            <div class="box-body">
                                <div class="row">
                                    <div class="col-3">
                                    </div>

                                    <div class="col-6">
                                        <a class="btn btn-success btn-sm btn-block"
                                            href="Reportes/reporteClientesHabituales.php?reporteClientesHabituales=<?php echo $Sucursal ?>&fechaInicio=<?php echo $Inicio ?>&fechafin=<?php echo $Fin ?>"
                                            title="clientes habituales que vienen cada vez sin importar la cantidad y el valor"><span
                                                style="color: white">
                                                EXCEL DESCARGAR</span>&nbsp;&nbsp;
                                            <i class="fa fa-download" style="color: white"></i>
                                        </a>
                                    </div>
                                    <div class="col-3">
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col">
                                        <table id="data-table-buttons"
                                            class="table table-striped table-bordered table-td-valign-middle w-100">
                                            <thead>
                                                <tr>
                                                    <th class="text-center"
                                                        style="text-align: center;background-color: #FDE9D9">
                                                        N&ordm;</th>
                                                    <!-- <th class="text-center" style="text-align: center;background-color: #FDE9D9">
                                            idCliente</th> -->

                                                    <th class="text-center"
                                                        style="text-align: center;background-color: #FDE9D9">
                                                        Nombre</th>
                                                    <th class="text-center"
                                                        style="text-align: center;background-color: #FDE9D9">
                                                        Apellido</th>
                                                    <th class="text-center"
                                                        style="text-align: center;background-color: #FDE9D9">
                                                        Correo</th>
                                                    <th class="text-center"
                                                        style="text-align: center;background-color: #FDE9D9">
                                                        Celular</th>
                                                    <th class="text-center"
                                                        style="text-align: center;background-color: #FDE9D9">
                                                        Fijo</th>
                                                    <th class="text-center"
                                                        style="text-align: center;background-color: #FDE9D9">
                                                        Empresa</th>
                                                
                                                    <th class="text-center"
                                                        style="text-align: center;background-color: #FDE9D9">
                                                        Compras Realizadas <br> sin importar <br>La cantidad y el Valor</th>
                                                    <th class="text-center"
                                                        style="text-align: center;background-color: #FDE9D9">
                                                        Sucursal</th>


                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php

// (1 clientes que compraron en mas cantidad unidad)
//mayor cantidad comprada por sucursal de fecha a fecha
$Number = 1;
$queryClientesCantidad = mysqli_query($MySQLi, $queryClientes);
while ($dataVenta = mysqli_fetch_assoc($queryClientesCantidad)) {
    $idCliente = $dataVenta['idCliente'];
    $QueryClientes = mysqli_query($MySQLi, "SELECT * FROM Clientes WHERE idCliente='$idCliente'");
    $dataClientes = mysqli_fetch_assoc($QueryClientes);
    ?>
                                                <tr>
                                                    <td><?php echo $Number ?></td>
                                                    <!-- <td><?php // echo $dataVenta['idCliente'] ?></td> -->
                                                    <td class=""><?php echo $dataClientes['Nombres'] ?></td>
                                                    <td class=""><?php echo $dataClientes['Apellidos'] ?></td>
                                                    <td class=""><?php echo $dataClientes['Correo']; ?></td>
                                                    <td class="text-center"><?php echo $dataClientes['Celular'] ?></td>
                                                    <td class="text-center"><?php echo $dataClientes['Otro'] ?></td>
                                                    <td><?php echo $dataClientes['Empresa'] ?></td>
                                                    <td style="text-align: center;background-color: #D8E4BC">
                                                        <?php echo $dataVenta['cantidad_habitual'] ?></td>
                                                    <td class=""><?php echo $dataVenta['Sucursal'] ?></td>


                                                </tr>
                                                <?php $Number++;}
;?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col"></div>
                                <div class="col-6">
                                    <!-- <button type="button" class="btn btn-primary btn-block" data-toggle="modal"
                                        data-target=".bd-example-modal-lg">Mostrar Lista Completa&nbsp;&nbsp;&nbsp;
                                        <i class="fa fa-list-ol" style="color: white"></i></button> -->

                                </div>
                                <div class="col"></div>
                            </div>

                        </div>
                        <!-- end panel-body -->
                    </div>

                </div>
            </div>

            <div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
                aria-hidden="true">
                <div class="modal-dialog modal-lg" style="max-width: 1300px!important;">
                    <div class="modal-content">
                        <div class="modal-header btn-<?php echo $btncolor ?>">
                            <h4 class="panel-title">
                                <span style="text-transform: uppercase;letter-spacing: 1px;font-size: 16px">CLIENTES QUE
                                    COMPRARON <br> EN MÁS CANTIDAD DE UNIDADES ==> <span
                                        style="text-transform: uppercase;letter-spacing: 1px;font-size: 18px">
                                        <?php
                                        if($Sucursal==''){
                                            echo 'Todas Las Sucursales';
                                        }else{echo $Sucursal;}
                                         ?></span></span>&nbsp;


                            </h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">

                            <div class="modal-footer">
                                <!-- <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button> -->
                            </div>


                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>



    <a href="javascript:;" class="btn btn-icon btn-circle btn-primary btn-scroll-to-top fade" data-click="scroll-top"><i
            class="fa fa-angle-up"></i></a>
    <?php include 'php/footer.php';?>
    </div>
    <?php include 'php/script_clientes3.php';?>

</body>

</html>

<?php
}else{ ?>
<script type="text/javascript">
location.replace("?root=404");
</script>
<?php
    }

function querySiHayPost($Inicio, $Fin, $Sucursal)
{
    $query = '';
    switch ($Sucursal) {
        case '1':
            $Sucursal = 'Cochabamba';
            break;
        case '2':
            $Sucursal = 'La Paz';
            break;
        case '3':
            $Sucursal = 'Santa Cruz';
            break;
        case '4':
            $Sucursal = 'Tarija';
            break;
        default: //todas las sucursales creamos query
            $query = "SELECT
            idCliente,Sucursal,
            COUNT(`idCliente`) AS 'cantidad_habitual'
            FROM
            Ventas
            WHERE
            Fecha BETWEEN '$Inicio' AND '$Fin'
            GROUP BY
            idCliente
            ORDER BY `cantidad_habitual` DESC";
            break;
    }
    if ($query == '') {
        $query = "SELECT
        idCliente,Sucursal,
        COUNT(`idCliente`) AS 'cantidad_habitual'
        FROM
        Ventas
        WHERE
        Fecha BETWEEN '$Inicio' AND '$Fin' AND Sucursal ='$Sucursal'
        GROUP BY
        idCliente
        ORDER BY `cantidad_habitual` DESC";
    }
    return $query;
}
function queryNoPost($Inicio, $Fin)
{
    $query = "SELECT
    idCliente,Sucursal,
    COUNT(`idCliente`) AS 'cantidad_habitual'
    FROM
    Ventas
    WHERE
    Fecha BETWEEN '$Inicio' AND '$Fin'
    GROUP BY
    idCliente
    ORDER BY `cantidad_habitual` DESC";
    return $query;
}
function btnColorPost($sucursal)
{
    $btncolor = '';
    switch ($sucursal) {
        case '1':
            $btncolor = 'warning';
            break;
        case '2':
            $btncolor = 'primary';
            break;
        case '3':
            $btncolor = 'success';
            break;
        case '4':
            $btncolor = 'info';
            break;
        default:
            $btncolor = 'inverse';
            break;
    }
    return $btncolor;

}
function nombreSucursal($sucursal)
{
    $nombre = '';
    switch ($sucursal) {
        case '1':
            $nombre = 'Cochabamba';
            break;
        case '2':
            $nombre = 'La Paz';
            break;
        case '3':
            $nombre = 'Santa Cruz';
            break;
        case '4':
            $nombre = 'Tarija';
            break;
        default:
            $nombre = '';
            break;
    }
    return $nombre;

}
?>