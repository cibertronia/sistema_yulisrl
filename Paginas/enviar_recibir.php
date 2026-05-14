<?php
include 'includes/conexion.php';
include 'includes/date.class.php';
//error_reporting(0);
mysqli_query($MySQLi, "SET lc_time_names= 'es_BO' ");
$idUser = $_SESSION['idUser'];
$ConsltaUser = mysqli_query($MySQLi, "SELECT * FROM Usuarios WHERE idUser='$idUser' ");
$datosUser = mysqli_fetch_assoc($ConsltaUser);
$miCiudad = $datosUser['Ciudad'];
$nombreUsuarioDf = $datosUser['Nombres'] . ' ' . $datosUser['Apellidos'];
function aleatorio()
{
    $code     =    uniqid();
    $code     =    substr($code, -10);
    return $code;
}
$alert         =    aleatorio();
$clave_aleatoria =    md5(date("d/m/Y g:i:s") . $alert);

?>
<!DOCTYPE html>
<html lang="es">

<head>
    <title>RECIBIR PRODUCTOS</title>
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
    <!-- <link href="assets/plugins/select2/dist/css/select2.min.css" rel="stylesheet"> -->
    <link href="assets/select2oscuro/css/select2.min.css" rel="stylesheet" />

    <link rel="stylesheet" href="assets/switchery/switchery.css">
</head>

<body>
    <?php include 'php/loader.php'; ?>
    <div id="page-container" class="fade page-sidebar-fixed page-header-fixed">
        <?php
        include 'php/top_menu.php';
        include 'php/left_menu_enviar_productos.php';
        ?>
        <div id="content" class="content">

            <!-- TABLA DE FACTURAS -->
            <?php
            if (isset($_POST['inicio'])) {
                $Inicio     = $_POST['inicio'];
                $Fin             =    $_POST['fin'];
            } else {
                $Inicio = $startBusqueda; //startbuskeda = 1 del mes
                $Fin = $fecha; //fecha = hoy
            }

            ?>
            <div class="row tableUsers">
                <div class="col-md-12">
                    <div class="panel panel-inverse">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                &nbsp;&nbsp;&nbsp;
                                <span style="text-transform: uppercase;letter-spacing: 1px;font-size: 16px">Recibir <span class="fw-300"><i>Productos </i></span> - Lista Recibidos</span>&nbsp;&nbsp;&nbsp;

                            </h4>
                            <div class="panel-heading-btn">
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
                                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-success" data-click="panel-reload"><i class="fa fa-redo"></i></a>
                                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>
                                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-danger" data-click="panel-remove"><i class="fa fa-times"></i></a>
                            </div>
                        </div>
                        <div class="panel-body">
                            <div class="row">

                                <div class="col">
                                    <div id="registrados_lista" class="panel">

                                        <style>
                                            .lineas-margen {
                                                border: 2px dashed #ccc;
                                                margin-top: 0px;
                                                margin-left: 10px;
                                                margin-right: 10px;
                                                padding: 20px;
                                            }
                                        </style>
                                        <div class="lineas-margen">
                                            <div class="row">
                                                <div class="respuesta"></div>
                                                <div class="col">
                                                    <div id="registrados_lista" class="panel">
                                                        <div class="panel-hdr"><?php
                                                                                if (isset($_POST['inicio'])) {
                                                                                    $Inicio = $_POST['inicio'];
                                                                                    $Fin = $_POST['fin'];
                                                                                } else {
                                                                                    $Inicio = $startBusqueda; //startbuskeda = 1 del mes
                                                                                    $Fin = $fecha; //fecha = hoy
                                                                                } ?>

                                                        </div>
                                                        <div class="panel-container">
                                                            <div class="panel-content">
                                                                <form class="w-75 m-auto" id="buscar" action="?root=enviar_recibir" method="POST">
                                                                    <div class="row mb-2">
                                                                        <div class="col text-center">
                                                                            <label for="fechaInicio">Fecha de
                                                                                inicio</label>
                                                                            <input type="hidden" name="sucursal" value="<?php echo $miCiudad; ?>">
                                                                            <input type="date" name="inicio" id="fechaInicio" class="form-control text-center" value="<?php echo $Inicio ?>" data-parsley-required="true">
                                                                        </div>
                                                                        <div class="col text-center">
                                                                            <label for="fechaFin">Fecha final</label>
                                                                            <input type="date" name="fin" id="fechaFin" class="form-control text-center" value="<?php echo $Fin ?>" data-parsley-required="true">
                                                                        </div>
                                                                        <div class="col">
                                                                            <label for="buscar">&nbsp;&nbsp;&nbsp;</label>
                                                                            <button type="submit" class="form-control btn btn-xs btn-primary ">Buscar
                                                                                &nbsp;<i class="fas fa-spinner fa-pulse d-none btn-Buscar"></i></button>
                                                                        </div>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                            <hr>
                                                            
                                                            <table id="data-table-buttons" class="table table-striped table-bordered table-td-valign-middle w-100">
                                                                <thead>
                                                                    <tr>
                                                                        <th class="text-center">N&ordm;</th>
                                                                        <th class="text-left">Surcusal Origen</th>
                                                                        <th class="text-center">Encargado Envio</th>
                                                                        <th class="text-center">Fecha Envio</th>
                                                                        <th class="text-center">Estado</th>
                                                                        <th class="text-center">Acciones</th>
                                                                    </tr>
                                                                </thead><?php

                                                                        $sqlEnvios = mysqli_query($MySQLi, "SELECT * FROM envio_stock WHERE hasta='$miCiudad' AND fecha BETWEEN '$Inicio' AND '$Fin'") or die(mysqli_error($MySQLi) . "<br>Error en la linea: " . __LINE__);

                                                                        $idNumber         = 1;
                                                                        ?>
                                                                <tbody><?php
                                                                        while ($dataEnvio = mysqli_fetch_assoc($sqlEnvios)) {
                                                                            echo '<tr>
								        	<td class="text-center">' . $idNumber . '</td>
								        	<td>';

                                                                            // $idTienda = $dataEnvio["id_origen"];
                                                                            // $q_sucursal = mysqli_query(
                                                                            //     $MySQLi,
                                                                            //     "SELECT * FROM sucursales WHERE idTienda='$idTienda'"
                                                                            // );
                                                                            // $d_sucursal = mysqli_fetch_assoc($q_sucursal);

                                                                            // echo  $d_sucursal["sucursal"];

                                                                            echo  $dataEnvio["desde"];

                                                                            $Vendedor  = $dataEnvio["encargado_envio"];

                                                                            echo '
                                            </td>
                                            <td>' . $Vendedor . '</td>';
                                                                            $thisFecha = $dataEnvio['fecha'];
                                                                            $fechaFormato = date("d-m-Y", strtotime($thisFecha));
                                                                            echo '
								        	<td class="text-center">' . $fechaFormato . " &nbsp;&nbsp;&nbsp; " . $dataEnvio['hora'] . '</td>';
                                                                            if ($dataEnvio["estado"] == 0) {
                                                                                echo '<td><button class="btn btn-block btn-info">En ruta</button></td>';
                                                                            } elseif ($dataEnvio["estado"] == 1) {
                                                                                echo '<td><button class="btn btn-block btn-success">Recibido</button></td>';
                                                                            } else {
                                                                                echo '<td><button class="btn btn-block btn-danger">Cancelado</button></td>';
                                                                            }
                                                                            echo '</td>
								        	<td class="text-center">';
                                                                            $idEnvio = $dataEnvio["idEnvio"];
                                                                            if ($dataEnvio['estado'] == 0) {
                                                                                echo ' 
								            <button class="btn btn-sm btn-success recibirProducto" type="button" id=' . $idEnvio . ' title="Confirmar recepci&oacute;n del envio ' . $idEnvio . '" >Confirmar Recepci&oacute;n <i class="fa fa-check"></i></button>';
                                                                            }
                                                                            echo '
								            <a target="_blank" href="Reportes/reporte_envio.php?ReporteEnvioStock=' . $idEnvio . '" class="btn btn-sm btn-info" title="Descargar reporte de envio PDF ' . $idEnvio . '" ><i class="fa fa-file-pdf"></i></a>
								        	</td></ tr>';
                                                                            $idNumber++;
                                                                        } ?>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>


                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <!-- end panel-body -->
                    </div>
                </div>
            </div>
        </div>

        <a href="javascript:;" class="btn btn-icon btn-circle btn-primary btn-scroll-to-top fade" data-click="scroll-top"><i class="fa fa-angle-up"></i></a>
        <?php include 'php/footer.php'; ?>
    </div>
    <?php include 'php/script_enviar_recibir.php'; ?>
</body>

</html>