<?php
include 'includes/App/Models/Sucursal.php';
use App\Models\Sucursal;

include 'includes/conexion.php';
include 'includes/date.class.php';
error_reporting(0);
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

$sucursalModel = new Sucursal();
$miSucursal = $sucursalModel->where('Sucursal', $miCiudad)[0];
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <title>ENVIAR PRODUCTOS</title>
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


            //////////targeta A///////-------------------------------------------------------------------------
            ?>
            <div class="row tableUsers">
                <div class="col-md-12">
                    <div class="panel panel-inverse">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                &nbsp;&nbsp;&nbsp;
                                <span style="text-transform: uppercase;letter-spacing: 1px;font-size: 16px">Enviar <span class="fw-300"><i>Productos</i></span></span>&nbsp;&nbsp;&nbsp;
                               
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
                                                margin-left: 80px;
                                                margin-right: 80px;
                                                padding: 20px;
                                            }
                                        </style>
                                        <div class="lineas-margen">

                                            <input type="hidden" name="clave" id="clave" value="<?php echo $clave_aleatoria; ?>">

                                            <form class="p-5">
                                                <h1 class="mb-4">ENVÍO DE MERCADERÍA</h1>
                                                <div class="row">
                                                    <div class="col-sm-6">
                                                        <h3>Sucursal origen</h3>
                                                        <div class="form-group">
                                                            <label for="sucursal_origen">Selecciona una
                                                                sucursal:</label>
                                                            <select name="sucursal_origen" id="sucursal_origen" class="form-control">
                                                                <?php
                                                                //include './../includes/conexion.php';

                                                                $q_sucursal = mysqli_query($MySQLi, "SELECT * FROM Sucursales WHERE Sucursal ='$miCiudad'");
                                                                while ($d_sucursal = mysqli_fetch_assoc($q_sucursal)) {
                                                                    echo "<option value=" . $d_sucursal['idSucursal'] . ">" .
                                                                        " [Sucursal]:  " . $d_sucursal['Sucursal'] . " " .
                                                                        "</option>";
                                                                }
                                                                ?>
                                                            </select>

                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <h3>Sucursal destino</h3>
                                                        <div class="form-group">
                                                            <label for="sucursal_destino">Selecciona una
                                                                sucursal:</label>
                                                            <select name="sucursal_destino" id="sucursal_destino" class="form-control">
                                                                <?php
                                                                //include './../includes/conexion.php';
                                                                $q_sucursal = mysqli_query($MySQLi, "SELECT * FROM Sucursales WHERE Sucursal <>'$miCiudad' ORDER BY orden ASC");
                                                                while ($d_sucursal = mysqli_fetch_assoc($q_sucursal)) {
                                                                    echo "<option value=" . $d_sucursal['idSucursal'] . ">" .
                                                                        " [Sucursal]:  " . $d_sucursal['Sucursal'] . " " .
                                                                        "</option>";
                                                                }
                                                                ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-sm-6">
                                                        <div class="form-group">
                                                            <label for="tecnico">Nombre Encargado Envio</label><span class="text-danger"> *</span>
                                                            <input type="text" id="tecnico" name="tecnico" class="form-control" value="<?php echo $nombreUsuarioDf ?>">
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <div class="form-group">
                                                            <label for="observaciones">Observaciones Envio</label>
                                                            <textarea rows="3" type="text" name="observaciones" id="observaciones" class="form-control" placeholder="Utilice este espacio si necesita agregar algun comentario."></textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                                <hr>
                                                <div class="row">
                                                    <div class="col-6">
                                                        <div class="form-group">
                                                            <button class="btn btn-primary btn-block" id="btn_agregar_repuesto_cola" type="button" data-toggle="modal" data-target="#modal_agregar_repuestos_sistema" data-dismiss="modal">
                                                                <i class="fa fa-barcode">
                                                                </i> &nbsp;&nbsp;AGREGAR PRODUCTOS DEL SISTEMA
                                                            </button>
                                                        </div>
                                                    </div>
                                                    <div class="col-6">
                                                        <div class="form-group">
                                                            <button class="btn btn-secondary btn-block" id="btn_agregar_extras" type="button" data-toggle="modal" data-target="#modal_agregar_extras" data-dismiss="modal">
                                                                <i class="fa fa-archive fa-lg">
                                                                </i> &nbsp;&nbsp;AGREGAR ELEMENTOS ADICIONALES
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                                <hr>
                                                <div class="row">
                                                    <div class="col">
                                                        <div class="resp_cola_temporal">
                                                        </div>
                                                        <div class="respuesta_terminar_envio"></div>
                                                    </div>
                                                </div>
                                                <hr>
                                                <div class="row">
                                                    <div class="col">
                                                        <div class="resp_cola_extras">
                                                        </div>
                                                        <!-- <div class="respuesta_terminar_envio"></div> -->
                                                    </div>
                                                </div>
                                                <hr>
                                                <div class="row">
                                                    <div class="col-2">
                                                        <div class="form-group">
                                                            <label>&nbsp;</label>
                                                        </div>
                                                    </div>
                                                    <div class="col">
                                                        <div class="form-group">
                                                            <label>&nbsp;</label>
                                                            <button type="button" id="terminar_envio" class="btn btn-success btn-block"><i class="fa fa-paper-plane fa-lg">
                                                                </i> &nbsp;&nbsp;PROCEDER AL ENVIO -
                                                                FINALIZAR ENVIO</button>
                                                        </div>
                                                    </div>
                                                    <div class="col-2">
                                                        <div class="form-group">
                                                            <label>&nbsp;</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
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

        <!-- Modal agregar productos del sistema para envio-->
        <div id="modal_agregar_repuestos_sistema" class="modal fade" role="dialog">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <!--=====================================
                        CABEZA DEL MODAL 2
                        ======================================-->
                    <div class="modal-header">

                        <h4 class="modal-title"> <strong><span>ENVIAR PRODUCTOS DEL SISTEMA</span></strong> </h4>

                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    </div>
                    <!--=====================================
                        CUERPO DEL MODAL 2
                        ======================================-->
                    <div class="modal-body">
                        <div class="box-body">
                            <div class="row">
                                <div class="col">

                                    <div class="form-group">
                                        <label for="producto">Lista Productos: [Producto][Nombre][Stock][Precio
                                            Venta]</label>
                                        <select name="repuestos_enviar" id="repuestos_enviar" class="form-control">
                                            <option disabled selected value="null">Seleccione Producto
                                                del Sistema a Enviar
                                            </option>
                                            <?php

                                            $inventario = 'Stock' . $miSucursal['iniciales'];
                                            $precio     = 'Precio' . $miSucursal['iniciales'];

                                            $queryRepuestos = mysqli_query($MySQLi, "SELECT * FROM Productos ORDER BY Producto ASC");
                                            while ($dataProductos = mysqli_fetch_assoc($queryRepuestos)) {

                                                $stock = $dataProductos[$inventario];
                                                if ($stock > 0) {
                                                    echo "<option value=" . $dataProductos['idProducto'] . " st=" . $stock . " " . "> " .
                                                        $dataProductos['Producto'] . " " .
                                                        $dataProductos['Marca'] . " " .
                                                        $dataProductos['Modelo'] . "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" .
                                                        " [STOCK " . $miCiudad . "]= " . $stock . "&nbsp;&nbsp;" .
                                                        " [PRECIO VENTA]= " . $dataProductos[$precio] . " " .
                                                        "</option>";
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="stock_actual">Stock Actual Del Producto:</label>
                                        <input readonly type="number" id="stock_actual" name="stock_actual" class="form-control">
                                    </div>
                                    <style>
                                        input[type="number"] {
                                            text-align: center;
                                            direction: rtl;
                                        }
                                    </style>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="cantidad">Cantidad a Enviar:</label>
                                        <input type="number" min='0' id="cantidad" name="cantidad" class="form-control">
                                    </div>
                                </div>
                            </div>

                        </div>
                        <hr>
                        <hr>
                        <div class="row mt-3">
                            <div class="col-2">
                            </div>
                            <div class="col">
                                <button type="button" id="agregar-repuesto" class="btn btn-primary form-control">Agregar
                                    Producto a la
                                    lista para Envio</button>
                            </div>
                            <div class="col-2">
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <!-- Modal agregar elementos adicionales para envio-->
        <div id="modal_agregar_extras" class="modal fade" role="dialog">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <!--=====================================
                        CABEZA DEL MODAL 2
                        ======================================-->
                    <div class="modal-header">
                        <h4 class="modal-title"> <strong><span>ENVIAR ELEMENTOS ADICIONALES</span></strong> </h4>
                        <button type="button" class="close cerrar_modal_extras" data-dismiss="modal" aria-hidden="true">×</button>
                    </div>
                    <!--=====================================
                        CUERPO DEL MODAL 2
                        ======================================-->
                    <div class="modal-body">
                        <div class="box-body">
                            <div class="row">
                                <!-- <div class="col">
                                    <div class="form-group">
                                        <label for="nombre_extra">Nombre Elemento Adicional</label>
                                        <input type="text" id="nombre_extra" name="nombre_extra" class="form-control" placeholder="Nombre del elemento Adicional que se agregara a la lista de envios">
                                    </div>
                                </div> -->
                                <div class="col">
                                    <label for="nombre_extra" class="form-label">Nombre Elemento Adicional<span class="text-danger"> (*)</span></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <label class="input-group-text" for="nombre_extra"><i class="fa fa-archive"></i></label>
                                        </div>
                                        <input type="text" name="nombre_extra" id="nombre_extra" class="form-control" placeholder="Nombre Elemento Adicional">
                                    </div>
                                </div>

                            </div>
                            <div class="row mt-2">
                                <!-- <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="cantidad_extra">Cantidad Elemento Adicional</label>
                                        <input type="number" min='1' id="cantidad_extra" name="cantidad_extra" class="form-control" value="1">
                                    </div>
                                </div> -->
                                <div class="col">
                                    <label for="cantidad_extra" class="form-label">Cantidad Elemento Adicional<span class="text-danger"> (*)</span></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <label class="input-group-text" for="cantidad_extra"><i class="fa fa-briefcase"></i></label>
                                        </div>
                                        <input type="number" min='1' value="1" name="cantidad_extra" id="cantidad_extra" class="form-control" placeholder="Cantidad">
                                    </div>
                                </div>
                                <!-- <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="precio_extra">Precio Unidad<span class="text-danger"> (Opcional)</span></label>
                                        <input type="number" min='0' id="precio_extra" name="precio_extra" class="form-control" value="0">
                                    </div>
                                </div> -->
                                <div class="col">
                                    <label for="precio_extra" class="form-label">Precio Unidad<span class="text-danger">
                                            (Opcional)</span></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <label class="input-group-text" for="precio_extra"><i class="fa fa-credit-card"></i></label>
                                        </div>
                                        <input type="number" min='0' value="0" name="precio_extra" id="precio_extra" class="form-control" placeholder="Marca">
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col">
                                    <label for="marca_extra" class="form-label">Marca<span class="text-danger">
                                            (Opcional)</span></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <label class="input-group-text" for="marca_extra"><i class="fa fa-briefcase"></i></label>
                                        </div>
                                        <input type="text" name="marca_extra" id="marca_extra" class="form-control" placeholder="Marca">
                                    </div>
                                </div>
                                <div class="col">
                                    <label for="modelo_extra" class="form-label">Modelo<span class="text-danger">
                                            (Opcional)</span></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <label class="input-group-text" for="modelo_extra"><i class="fa fa-briefcase"></i></label>
                                        </div>
                                        <input type="text" name="modelo_extra" id="modelo_extra" class="form-control" placeholder="Modelo">
                                    </div>
                                </div>

                            </div>

                        </div>
                        <hr>
                        <hr>
                        <div class="row mt-3">
                            <div class="col-1">
                            </div>
                            <div class="col">
                                <button type="button" id="btn_agregar_elemento_extra" class="btn btn-primary form-control">Agregar Elemento Adicional a
                                    lista para Envio</button>
                            </div>
                            <div class="col-1">
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <a href="javascript:;" class="btn btn-icon btn-circle btn-primary btn-scroll-to-top fade" data-click="scroll-top"><i class="fa fa-angle-up"></i></a>
        <?php include 'php/footer.php'; ?>
    </div>
    <?php include 'php/script_enviar_productos.php'; ?>
</body>

</html>