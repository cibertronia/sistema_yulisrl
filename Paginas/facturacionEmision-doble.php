<?php
include 'includes/conexion.php';
include 'includes/conexion_yuliimport.php';
include 'includes/date.class.php';
mysqli_query($MySQLi, "SET lc_time_names= 'es_BO' ");
$idUser = $_SESSION['idUser'];
$ConsltaUser = mysqli_query($MySQLi, "SELECT * FROM Usuarios WHERE idUser='$idUser' ");
$datosUser = mysqli_fetch_assoc($ConsltaUser);

$nombreVendedor = $datosUser['Nombres'] . " " . $datosUser['Apellidos'];
$miCiudad = $datosUser['Ciudad'];
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <title>FACTURA - EMISION DOBLE</title>
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
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body><?php include 'php/loader.php'; ?>
    <div id="page-container" class="fade page-sidebar-fixed page-header-fixed">
        <?php
        include 'php/top_menu.php';
        include 'php/left_menu_facturacionEmision.php'; ?>

        <!-- FORMULARIO PREGUNTA FACTURA DOBLE-->
        <div class="content">
            <div class="row form_doble">
                <div class="col">
                    <div class="panel panel-inverse">
                        <div class="panel-heading">
                            <h4 class="panel-title">FACTURACIÓN DOBLE - EMISION DIRECTA&nbsp;&nbsp; SUCURSAL
                                &nbsp;&nbsp;<strong><?php echo strtoupper($miCiudad) ?></strong></h4>
                            <div class="panel-heading-btn">
                                &nbsp;&nbsp;
                                <a href="javascript:;" id="panelExpandButton" class="btn btn-sm btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
                                <a href="javascript:;" class="btn btn-sm btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>
                            </div>
                        </div>
                        <div class="panel-body">
                            <div class="lineas-margen-yuliimport">
                                <div class="row">
                                    <div class="col-3">
                                        <div class="card-header text-center">
                                            <h3>#1</h3>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="card-header text-center bg-custom-green">
                                            <h3>FACTURACIÓN YULIIMPORT</h3>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-1">
                                    <div class="col-1"></div>
                                    <div class="col">
                                        <label for="optionUser">Cliente Existente Yuliimport?</label>
                                        <p>
                                            <span class="text-white bg-danger">NO</span>&nbsp;&nbsp;&nbsp;
                                            <input id="checkbox_01yuli" name="checkbox_01yuli" checked="" type="checkbox" class="js-switch_01yuli">&nbsp;&nbsp;&nbsp;
                                            <span class="text-white bg-success"> SI </span>
                                        </p>
                                    </div>
                                    <div class="col-8 col_select_clientes_01yuli">
                                        <label for="select_clientes_01yuli">Lista Clientes Yuliimport</label>
                                        <select name="select_clientes_01yuli" id="select_clientes_01yuli" class="form-control">
                                            <option value="0" selected disabled>Seleccione un Cliente</option>
                                            <?php
                                            $queryCl = mysqli_query($YuliimportDB, "SELECT * FROM Clientes ORDER BY Apellidos ASC");
                                            while ($dataCl = mysqli_fetch_assoc($queryCl)) {
                                                echo "<option value=" . $dataCl['idCliente'] . ">" . $dataCl['Nombres'] . " " . $dataCl['Apellidos'] . " &nbsp;&nbsp;&nbsp;[Celular]: " . $dataCl['Celular'] . "</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>

                                <form id="datosFactura_01yuli">
                                    <div class="row ">
                                        <div class="col">
                                            <label class="form-label" for="clientReasonSocial_01yuli">
                                                Razón social -
                                                Cliente</label>
                                            <input type="text" class="form-control" id="clientReasonSocial_01yuli" name="clientReasonSocial_01yuli" oninput="actualizarclientCode()" placeholder="RAZON SOCIAL" value="YULI IMPORT & EXPORT S.R.L.">
                                        </div>
                                        <div class="col">
                                            <label for="clientDocumentType_01yuli" class="form-label">Tipo de documento
                                                - Cliente</label>
                                            <select name="clientDocumentType_01yuli" id="clientDocumentType_01yuli" class="form-control  data-parsley-required=" true">
                                                <option disabled="" selected="">Seleccione
                                                    Tipo de documento
                                                </option>
                                                <option value="1">CI - CEDULA DE IDENTIDAD</option>
                                                <option value="2">CEX - CEDULA DE IDENTIDAD DE EXTRANJERO
                                                </option>
                                                <option value="3">PAS - PASAPORTE</option>
                                                <option value="4">OD - OTRO DOCUMENTO DE IDENTIDAD</option>
                                                <option selected value="5">NIT - NÚMERO DE IDENTIFICACIÓN
                                                    TRIBUTARIA
                                                </option>
                                            </select>
                                        </div>
                                        <div class="col">
                                            <label class="form-label" for="clientNroDocument_01yuli">Número Documento -
                                                Cliente</label>
                                            <input class="form-control" name="clientNroDocument_01yuli" id="clientNroDocument_01yuli" placeholder="Número Documento" value="470359021">
                                        </div>
                                    </div>
                                    <div class="row ">
                                        <div class="col">
                                            <label for="clientEmail_01yuli" class="form-label">Email - Cliente</label>
                                            <input type="text" class="form-control" name="clientEmail_01yuli" id="clientEmail_01yuli" placeholder="EMAIL@EMAIL.COM" value="">
                                        </div>
                                        <div class="col">
                                            <label for="clientCity_01yuli" class="form-label">Ciudad Cliente</label>
                                            <input type="text" class="form-control" name="clientCity_01yuli" id="clientCity_01yuli" value="Cochabamba" placeholder="CIUDAD CLIENTE">
                                        </div>
                                        <div class="col">
                                            <label for="paramPaymentMethod_01yuli" class="form-label">Metodo de
                                                pago</label>
                                            <select name="paramPaymentMethod_01yuli" id="paramPaymentMethod_01yuli" class="form-control  data-parsley-required=" true">
                                                <option disabled="" selected="">Seleccione
                                                    Metodo De Pago
                                                </option>
                                                <option selected value="1"> EFECTIVO</option>
                                                <option value="3"> CHEQUE</option>
                                                <option value="4"> VALES</option>
                                                <option value="5"> OTROS</option>
                                                <option value="7"> TRANSFERENCIA BANCARIA</option>
                                                <option value="8"> DEPOSITO EN CUENTA</option>
                                            </select>
                                        </div>
                                        <div class="col">
                                            <label for="paramCurrency_01yuli" class="form-label">Tipo de moneda</label>
                                            <select name="paramCurrency_01yuli" id="paramCurrency_01yuli" class="form-control" data-parsley-required="true">
                                                <option selected value="1">BOLIVIANO</option>
                                            </select>
                                        </div>
                                        <div class="col d-none">
                                            <label for="clientCode_01yuli" class="form-label">Código de cliente</label>
                                            <input type="text" class="form-control" id="clientCode_01yuli" name="clientCode_01yuli" oninput='actualizarclientCode_01yuli()' placeholder="CODIGO CLIENTE" readonly>
                                        </div>
                                        <div class="col d-none">
                                            <label for="userPos_01yuli" class="form-label">Vendedor</label>
                                            <input type="text" readonly class="form-control" name="userPos_01yuli" id="userPos_01yuli" autofocus placeholder="VENDEDOR EN TURNO" value="<?php echo $nombreVendedor; ?>">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col">
                                            <input name="idCotizacion_01yuli" id="idCotizacion_01yuli" type="hidden" value="-1">
                                            <label for="ClienteProducto_01yuli"><span class="text-success" style="font-weight: bold; color: green;">LISTA PRODUCTOS FISCALES YULIIMPORT (*)</span></label>
                                        </div>
                                    </div>

                                    <div class="row mt-1 infoProducto_01yuli">
                                        <div class="col-6">
                                            <select name="ClienteProducto_01yuli" id="ClienteProducto_01yuli" onchange="actualizarclientCode();" class="form-control">
                                                <option disabled selected>Seleccione producto</option>
                                                <?php
                                                $queryProd = mysqli_query($YuliimportDB, "SELECT * FROM productos_fiscales WHERE saldo_fisico > 0 ORDER BY fecha_poliza");
                                                while ($dataProd = mysqli_fetch_assoc($queryProd)) {
                                                    echo "<option value=" . $dataProd['idProducto'] . ">" . $dataProd['fecha_poliza'] . " " . $dataProd['detalle'] . " " . $dataProd['codigo'] . " SaldoFisico[" . $dataProd['saldo_fisico'] . "]" . "</option>";
                                                }
                                                mysqli_close($YuliimportDB);
                                                ?>
                                            </select>
                                            <div class="text-danger d-none noSelectProd_01yuli">No ha seleccionado un
                                                producto Fiscal
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <button type="button" class="btn btn-block btn-success Add_ProductoEmision_01yuli d-none PreciosProductoSelected_01yuli">
                                                <i class="fa fa-plus"></i> &nbsp;&nbsp;AGREGAR PRODUCTO FISCAL
                                                A LA FACTURA YULIIMPORT &nbsp;<i class="fas fa-spinner fa-pulse d-none efectAddProduct_01yuli">
                                                </i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="row  d-none PreciosProductoSelected_01yuli">
                                        <div class="col-3">
                                            <label for="idProducto_01yuli"><strong>ID PRODUCTO</strong></label>
                                            <input type="text" id="idProducto_01yuli" class="form-control text-center" disabled>
                                        </div>
                                        <div class="col-3">
                                            <label for="fecha_poliza_01yuli"><strong>FECHA POLIZA</strong></label>
                                            <input type="text" id="fecha_poliza_01yuli" class="form-control text-center" disabled>
                                        </div>
                                        <div class="col-3">
                                            <label for="codigo_01yuli"><strong>CODIGO</strong></label>
                                            <input type="text" id="codigo_01yuli" class="form-control text-center" disabled>
                                        </div>
                                        <div class="col-3">
                                            <label for="detalle_01yuli"><strong>DETALLE</strong></label>
                                            <input type="text" id="detalle_01yuli" class="form-control text-center" disabled>
                                        </div>
                                    </div>
                                    <div class="row  d-none PreciosProductoSelected_01yuli">
                                        <div class="col">
                                            <label for="PrecioLista_01yuli"><strong>C/U PARA FACTURAR
                                                    MINIMO</strong></label>
                                            <input type="text" name="PrecioLista_01yuli" id="PrecioLista_01yuli" class="form-control" placeholder="Precio de Lista" disabled>
                                        </div>
                                        <div class="col">
                                            <label for="PrecioEspecial_01yuli"><strong>IMPORTES PARA FACTURAR </strong></label>
                                            <input type="text" name="PrecioEspecial_01yuli" id="PrecioEspecial_01yuli" class="form-control" placeholder="Precio Especial" disabled>
                                            <div class="text-danger d-none emptyPrecioEsp_01yuli">No ha indicado el
                                                precio especial
                                            </div>
                                        </div>
                                        <div class="col">
                                            <label for="ProdExistenciaCB_01yuli" style="font-weight: bold; color: red;"><strong>SALDO FISICO
                                                    (STOCK)</strong></label>
                                            <input type="text" id="ProdExistenciaCB_01yuli" class="form-control text-center" style="border: 2px solid green;" disabled>
                                        </div>
                                        <div class="col">
                                            <label for="CantidadProducto_01yuli" style="font-weight: bold; color: red;"><strong>CANTIDAD AGREGAR-FACTURA</strong></label>
                                            <input type="number" name="CantidadProducto_01yuli" id="CantidadProducto_01yuli" class="form-control text-center" style="border: 2px solid green;">
                                            <div class="text-danger d-none CantidadEmpty_01yuli">La cantidad no puede
                                                ser negativa,
                                                nulo o mayor al Stock Actual</div>
                                        </div>

                                    </div>
                                    <div class="row mt-1">
                                        <div class="col" style="font-weight: bold;">Información: <br>
                                            Los productos en la tabla de color <span style="color:green;">VERDE</span>
                                            son considerados productos fiscales de YULIIMPORT y afectan el stock de estos mismos al
                                            momento de
                                            realizar la facturación.
                                        </div>
                                    </div>
                                    <div class="row mt-1">
                                        <div class="col">
                                            <table id="table_01yuli" class="table" width="100%">
                                                <thead style="background-color: #19CC19; color: white;">
                                                    <tr>
                                                        <th scope="col" width="15%" class="text-center p-5">
                                                            <h5>Cantidad</h5>
                                                        </th>
                                                        <th scope="col" width="15%" class="text-center p-5">
                                                            <h5>CodProd</h5>
                                                        </th>
                                                        <th scope="col" width="40%" class="text-center p-5">
                                                            <h5>Producto</h5>
                                                        </th>
                                                        <th scope="col" width="15%" class="text-center p-5">
                                                            <h5>PrecioUnidad Bs</h5>
                                                        </th>
                                                        <th scope="col" width="15%" class="text-center p-5">
                                                            <h5>SubTotal Bs</h5>
                                                        </th>
                                                        <th scope="col" width="15%" class="text-center p-5">
                                                            <h5>Eliminar</h5>
                                                        </th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <!-- aki se llena con js la table_01yuli -->

                                                </tbody>
                                                <thead class="thead-light">
                                                    <tr>
                                                        <th colspan="4" class="text-right p-4 ">
                                                            <strong>
                                                                <h4>TOTAL</h4>
                                                            </strong>
                                                        </th>
                                                        <th scope="col">
                                                            <input name="total_01yuli" id="total_01yuli" class="form-control text-right" value="" readonly>
                                                        </th>
                                                        <th scope="col" class="text-left p-4">
                                                            <strong>
                                                                <h4>Bs</h4>
                                                            </strong>
                                                        </th>
                                                    </tr>
                                                </thead>
                                            </table>
                                        </div>
                                    </div>
                                </form>
                            </div>

                            <hr>

                            <div class="lineas-margen-srl">
                                <div class="row">
                                    <div class="col-3">
                                        <div class="card-header text-center">
                                            <h3>#2</h3>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="card-header text-center bg-custom-blue">
                                            <h3>FACTURACIÓN YULI SRL</h3>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-1">
                                    <div class="col-1"></div>
                                    <div class="col">
                                        <label for="optionUser">Cliente Existente Yuli SRL?</label>
                                        <p>
                                            <span class="text-white bg-danger">NO</span>&nbsp;&nbsp;&nbsp;
                                            <input id="checkbox_02srl" name="checkbox_02srl" checked="" type="checkbox" class="js-switch_02srl">&nbsp;&nbsp;&nbsp;
                                            <span class="text-white bg-success"> SI </span>
                                        </p>
                                    </div>
                                    <div class="col-8 col_select_clientes_02srl">
                                        <label for="select_clientes_02srl">Lista Clientes Yuli SRL</label>
                                        <select name="select_clientes_02srl" id="select_clientes_02srl" class="form-control">
                                            <option value="0" selected disabled>Seleccione un Cliente</option>
                                            <?php
                                            $queryCl = mysqli_query($MySQLi, "SELECT * FROM Clientes ORDER BY Apellidos ASC");
                                            while ($dataCl = mysqli_fetch_assoc($queryCl)) {
                                                echo "<option value=" . $dataCl['idCliente'] . ">" . $dataCl['Nombres'] . " " . $dataCl['Apellidos'] . " &nbsp;&nbsp;&nbsp;[Celular]: " . $dataCl['Celular'] . "</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>

                                <form id="datosFactura_02srl">
                                    <div class="row ">
                                        <div class="col">
                                            <label class="form-label" for="clientReasonSocial_02srl">
                                                Razón social -
                                                Cliente</label>
                                            <input type="text" class="form-control" id="clientReasonSocial_02srl" name="clientReasonSocial_02srl" oninput="actualizarclientCode()" placeholder="RAZON SOCIAL" value="">
                                        </div>
                                        <div class="col">
                                            <label for="clientDocumentType_02srl" class="form-label">Tipo de documento
                                                - Cliente</label>
                                            <select name="clientDocumentType_02srl" id="clientDocumentType_02srl" class="form-control  data-parsley-required=" true">
                                                <option disabled="" selected="">Seleccione
                                                    Tipo de documento
                                                </option>
                                                <option value="1">CI - CEDULA DE IDENTIDAD</option>
                                                <option value="2">CEX - CEDULA DE IDENTIDAD DE EXTRANJERO
                                                </option>
                                                <option value="3">PAS - PASAPORTE</option>
                                                <option value="4">OD - OTRO DOCUMENTO DE IDENTIDAD</option>
                                                <option selected value="5">NIT - NÚMERO DE IDENTIFICACIÓN
                                                    TRIBUTARIA
                                                </option>
                                            </select>
                                        </div>
                                        <div class="col">
                                            <label class="form-label" for="clientNroDocument_02srl">Número Documento -
                                                Cliente</label>
                                            <input class="form-control" name="clientNroDocument_02srl" id="clientNroDocument_02srl" placeholder="Número Documento" value="">
                                        </div>
                                    </div>
                                    <div class="row ">
                                        <div class="col">
                                            <label for="clientEmail_02srl" class="form-label">Email - Cliente</label>
                                            <input type="text" class="form-control" name="clientEmail_02srl" id="clientEmail_02srl" placeholder="EMAIL@EMAIL.COM" value="">
                                        </div>
                                        <div class="col">
                                            <label for="clientCity_02srl" class="form-label">Ciudad Cliente</label>
                                            <input type="text" class="form-control" name="clientCity_02srl" id="clientCity_02srl" value="" placeholder="CIUDAD CLIENTE">
                                        </div>
                                        <div class="col">
                                            <label for="paramPaymentMethod_02srl" class="form-label">Metodo de
                                                pago</label>
                                            <select name="paramPaymentMethod_02srl" id="paramPaymentMethod_02srl" class="form-control  data-parsley-required=" true">
                                                <option disabled="" selected="">Seleccione
                                                    Metodo De Pago
                                                </option>
                                                <option selected value="1"> EFECTIVO</option>
                                                <option value="3"> CHEQUE</option>
                                                <option value="4"> VALES</option>
                                                <option value="5"> OTROS</option>
                                                <option value="7"> TRANSFERENCIA BANCARIA</option>
                                                <option value="8"> DEPOSITO EN CUENTA</option>
                                            </select>
                                        </div>
                                        <div class="col">
                                            <label for="paramCurrency_02srl" class="form-label">Tipo de moneda</label>
                                            <select name="paramCurrency_02srl" id="paramCurrency_02srl" class="form-control" data-parsley-required="true">
                                                <option selected value="1">BOLIVIANO</option>
                                            </select>
                                        </div>
                                        <div class="col d-none">
                                            <label for="clientCode_02srl" class="form-label">Código de cliente</label>
                                            <input type="text" class="form-control" id="clientCode_02srl" name="clientCode_02srl" oninput='actualizarclientCode_02srl()' placeholder="CODIGO CLIENTE" readonly>
                                        </div>
                                        <div class="col d-none">
                                            <label for="userPos_02srl" class="form-label">Vendedor</label>
                                            <input type="text" readonly class="form-control" name="userPos_02srl" id="userPos_02srl" autofocus placeholder="VENDEDOR EN TURNO" value="<?php echo $nombreVendedor; ?>">
                                        </div>
                                    </div>
                                    <div class="row mt-2 d-none">
                                        <div class="col-2">
                                        </div>
                                        <div class="col-4">
                                            <button type="button" class="btn btn-block bg-custom-blue clonar_tabla_02srl">
                                                <i class="fa fa-clone"></i> &nbsp;&nbsp;CLONAR TABLA
                                            </button>
                                        </div>
                                        <div class="col-4">
                                            <button type="button" class="btn btn-block bg-custom-blue agregar_fila_vacia_02srl">
                                                <i class="fa fa-plus"></i> &nbsp;&nbsp;AGREGAR FILA VACIA
                                            </button>
                                        </div>
                                        <div class="col-2">
                                        </div>
                                    </div>
                                    <div class="row d-none">
                                        <div class="col">
                                            <input name="idCotizacion_02srl" id="idCotizacion_02srl" type="hidden" value="-1">
                                        </div>
                                    </div>
                                    <div class="row d-none">
                                        <div class="col" style="font-weight: bold;">Información: <br>
                                            Los productos en la tabla de color <span style="color:#1C3756;">AZUL</span>
                                            no afectarán el stock del sistema YULI SRL.
                                        </div>
                                    </div>
                                    <div class="row mt-1">
                                        <div class="col">
                                            <table id="table_02srl" class="table" width="100%">
                                                <thead style="background-color: #1C3756; color: white;">
                                                    <tr>
                                                        <th scope="col" width="15%" class="text-center p-5">
                                                            <h5>Cantidad</h5>
                                                        </th>
                                                        <th scope="col" width="15%" class="text-center p-5">
                                                            <h5>CodProd</h5>
                                                        </th>
                                                        <th scope="col" width="40%" class="text-center p-5">
                                                            <h5>Producto</h5>
                                                        </th>
                                                        <th scope="col" width="15%" class="text-center p-5">
                                                            <h5>PrecioUnidad Bs</h5>
                                                        </th>
                                                        <th scope="col" width="15%" class="text-center p-5">
                                                            <h5>SubTotal Bs</h5>
                                                        </th>
                                                        <th scope="col" width="15%" class="text-center p-5">
                                                            <!--h5>Eliminar</h5-->
                                                        </th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <!-- aki se llena con js la table_02srl -->

                                                </tbody>
                                                <thead class="thead-light">
                                                    <tr>
                                                        <th colspan="4" class="text-right p-4 ">
                                                            <strong>
                                                                <h4>TOTAL</h4>
                                                            </strong>
                                                        </th>
                                                        <th scope="col">
                                                            <input name="total_02srl" id="total_02srl" class="form-control text-right" value="" readonly>
                                                        </th>
                                                        <th scope="col" class="text-left p-4">
                                                            <strong>
                                                                <h4>Bs</h4>
                                                            </strong>
                                                        </th>
                                                    </tr>
                                                </thead>
                                            </table>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="row mt-4">
                                <div class="col">


                                    <button id="submitButton" class="btn btn-primary btn-block facturar_doble_01yuli_02srl" type="button">
                                        <h4>Facturar DOBLE </h4><i class="fas d-none efecto_spiner fa-spinner fa-pulse"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


            </div>

        </div>

        <a href="javascript:;" class="btn btn-icon btn-circle btn-primary btn-scroll-to-top fade" data-click="scroll-top"><i class="fa fa-angle-up"></i></a>
        <?php include 'php/footer.php'; ?>
    </div>
    <?php include 'php/script_facturacionEmision_doble.php'; ?>
</body>

</html>