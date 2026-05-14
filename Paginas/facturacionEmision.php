<?php

include 'includes/conexion.php';
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
    <title>FACTURA - EMISION DIRECTA</title>
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
    <title>Pregunta de Facturación</title>
    <!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"> -->


</head>

<body><?php include 'php/loader.php'; ?>
    <div id="page-container" class="fade page-sidebar-fixed page-header-fixed">
        <?php
        include 'php/top_menu.php';
        include 'php/left_menu_facturacionEmision.php'; ?>

        <div class="content">
            <!-- FORMULARIO PREGUNTA FACTURA DOBLE-->
            <div class="row form_pregunta">
                <div class="col-md-7 offset-md-2 ">
                    <div class="card">
                        <div class="card-header text-center">
                            <h3>Seleccione una opción de Facturación</h3>
                        </div>
                        <div class="card-body text-center">
                            <div class="text-center">

                                <p>Elija la opción de factura que se adapte a sus necesidades:</p>
                            </div>
                            <div class="options">
                                <button class="option btn-lg facturaSimple" id="facturaSimple">Factura Simple</button>
                                <button class="option btn-lg facturaDoble" id="facturaDoble">Factura Doble</button>
                            </div>
                            <hr>

                            <div class="row">
                                <div class="col-1"></div>
                                <div class="col">
                                    <br>
                                    <div class="invoice">
                                        <div class="paper">
                                            <div class="details">
                                                <div class="header">&nbsp; Factura</div>
                                                <div class="body">Número: 1010<br>Fecha:
                                                    <?php
                                                    $currentDate = date('Y-m-d');
                                                    echo $currentDate; ?>
                                                    <br>Total:
                                                    Bs250.00
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col">
                                    <div style="background-color: #f5f5f5; border-radius: 10px; text-align: center;">
                                        <p style="font-size: 15px; color: #333; font-weight: bold;">&#128161;
                                            Información &#128161;</p>
                                        <p style="font-size: 15px; color: #666;">La "Factura Simple" emite una factura
                                            directa usando un formulario para la importadora YuliSRL.</p>
                                        <p style="font-size: 15px; color: #666;">La "Factura Doble" crea dos facturas
                                            separadas, una para Yuliimport y otra para YuliSRL, utilizando dos
                                            formularios distintos en la misma página.</p>
                                    </div>
                                </div>
                                <div class="col-1"></div>

                            </div>
                        </div>



                    </div>
                </div>
            </div>
            <!-- FORMULARIO SIMPLE FACTURACION 00 YULI SRL-->
            <div class="row form_simple d-none">
                <div class="respuesta"></div>
                <div class="col">
                    <div class="panel panel-inverse">
                        <div class="panel-heading">
                            <h4 class="panel-title">FACTURA SIMPLE - EMISION DIRECTA&nbsp;&nbsp; SUCURSAL
                                &nbsp;&nbsp;<strong><?php echo strtoupper($miCiudad) ?></strong></h4>
                            <!-- <button class="btn btn-xs btn-danger cancelarRegNewCotiza">CANCELAR</button> -->
                            <div class="panel-heading-btn">

                                &nbsp;&nbsp;

                                <a href="javascript:;" class="btn btn-sm btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
                                <a href="javascript:;" class="btn btn-sm btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>
                                <!-- <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-danger"
                                    data-click="panel-remove"><i class="fa fa-times"></i></a> -->
                            </div>
                        </div>
                        <div class="panel-body">

                            <div class="card-header text-center bg-custom-blue">
                                <h3>FACTURACIÓN YULI SRL</h3>
                            </div>

                            <br>
                            <div class="lineas-margen">

                                <div class="row mt-2">
                                    <div class="col-1">
                                    </div>
                                    <div class="col">
                                        <label for="optionUser">Cliente Existente ?</label>
                                        <p>
                                            <span class="text-white bg-danger">NO</span>&nbsp;&nbsp;&nbsp;
                                            <input id="opttionUser" name="optionUser" checked="" type="checkbox" class="js-switch">&nbsp;&nbsp;&nbsp;
                                            <span class="text-white bg-success"> SI </span>
                                        </p>
                                    </div>
                                    <div class="col-8 col_select_clientes">
                                        <label for="select_clientes">Lista Clientes</label>
                                        <select name="select_clientes" id="select_clientes" class="form-control">
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
                                <hr>

                                <form id="datosFactura">


                                    <div class="row mt-2">
                                        <div class="col">
                                            <label class="form-label" for="clientReasonSocial">Razón social -
                                                Cliente</label>
                                            <input type="text" class="form-control" id="clientReasonSocial" name="clientReasonSocial" oninput="actualizarclientCode()" placeholder="RAZON SOCIAL" value="">

                                        </div>
                                        <div class="col">
                                            <label for="clientDocumentType" class="form-label">Tipo de documento
                                                -
                                                Cliente</label>
                                            <select name="clientDocumentType" id="clientDocumentType" class="form-control  data-parsley-required=" true">
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
                                            <label class="form-label" for="clientNroDocument">Número Documento -
                                                Cliente</label>
                                            <input class="form-control" name="clientNroDocument" id="clientNroDocument" placeholder="Número Documento" value="">
                                        </div>
                                    </div>
                                    <div class="row mt-2">
                                        <div class="col">
                                            <label for="clientCode" class="form-label">Código de cliente</label>
                                            <input type="text" class="form-control" id="clientCode" name="clientCode" placeholder="CODIGO CLIENTE" readonly>
                                        </div>
                                        <div class="col">
                                            <label for="clientCity" class="form-label">Ciudad Cliente</label>
                                            <input type="text" class="form-control" name="clientCity" id="clientCity" value="" placeholder="CIUDAD CLIENTE">
                                        </div>
                                        <div class="col">
                                            <label for="clientEmail" class="form-label">Email - Cliente</label>
                                            <input type="text" class="form-control" name="clientEmail" id="clientEmail" placeholder="EMAIL@EMAIL.COM" value="">
                                        </div>
                                    </div>
                                    <div class="row mt-2">
                                        <div class="col">
                                            <label for="userPos" class="form-label">Vendedor</label>
                                            <input type="text" readonly class="form-control" name="userPos" id="userPos" autofocus placeholder="VENDEDOR EN TURNO" value="<?php echo $nombreVendedor; ?>">
                                        </div>
                                        <div class="col">
                                            <label for="paramCurrency" class="form-label">Tipo de moneda</label>
                                            <select name="paramCurrency" id="paramCurrency" class="form-control" data-parsley-required="true">
                                                <option selected value="1">BOLIVIANO</option>
                                            </select>
                                        </div>
                                        <div class="col">
                                            <label for="paramPaymentMethod" class="form-label">Metodo de
                                                pago</label>
                                            <select name="paramPaymentMethod" id="paramPaymentMethod" class="form-control  data-parsley-required=" true">
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
                                    </div>
                                    <div class="row mt-2">
                                    </div>
                                    <div class="row mt-2">
                                        <div class="col">
                                            <input name="branchIdName" id="branchIdName" type="hidden" value="<?php echo $miCiudad; ?>">
                                            <input name="idCotizacion" id="idCotizacion" type="hidden" value="-1">
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row mt-2 infoProducto">
                                        <div class="col-md-6">
                                            <label for="ClienteProducto">PRODUCTOS FISCALES <span class="text-info">(
                                                    *
                                                    )</span></label>
                                            <select name="Producto" id="ClienteProducto" onchange="actualizarclientCode();" class="form-control">
                                                <option disabled selected>Seleccione producto</option>
                                                <?php
                                                $queryProd = mysqli_query($MySQLi, "SELECT * FROM productos_fiscales WHERE saldo_fisico > 0 ORDER BY fecha_poliza");
                                                while ($dataProd = mysqli_fetch_assoc($queryProd)) {
                                                    echo "<option value=" . $dataProd['idProducto'] . ">" . $dataProd['fecha_poliza'] . " " . $dataProd['detalle'] . " " . $dataProd['codigo'] . " SaldoFisico[" . $dataProd['saldo_fisico'] . "]" . "</option>";
                                                }
                                                mysqli_close($MySQLi);
                                                ?>
                                            </select>
                                            <div class="text-danger d-none noSelectProd">No ha seleccionado un
                                                producto
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <br><br>
                                            <button title="Agregar producto fiscal a la factura" type="button" class="btn btn-xs btn-info Add_ProductoEmision d-none PreciosProductoSelected"><i class="fa fa-plus"></i> &nbsp;&nbsp;AGREGAR PRODUCTO FISCAL
                                                A LA
                                                FACTURA &nbsp;<i class="fas fa-spinner fa-pulse d-none efectAddProduct"></i></button>
                                        </div>
                                    </div>
                                    <div class="row mt-2 d-none PreciosProductoSelected">
                                        <div class="col-md-3 d-none  PreciosProductoSelected text-center">
                                            <label for="idProducto"><strong>ID PRODUCTO</strong></label>
                                            <input type="text" id="idProducto" class="form-control text-center" disabled>
                                        </div>
                                        <div class="col-md-3 d-none  PreciosProductoSelected text-center">
                                            <label for="fecha_poliza"><strong>FECHA POLIZA</strong></label>
                                            <input type="text" id="fecha_poliza" class="form-control text-center" disabled>
                                        </div>
                                        <div class="col-md-3 d-none  PreciosProductoSelected text-center">
                                            <label for="codigo"><strong>CODIGO</strong></label>
                                            <input type="text" id="codigo" class="form-control text-center" disabled>
                                        </div>
                                        <div class="col-md-3 d-none">
                                            <label for="detalle"><strong>DETALLE</strong></label>
                                            <input type="hidden" id="detalle" class="form-control text-center" disabled>
                                        </div>
                                        <div class="col-md-3 d-none PreciosProductoSelected text-center">
                                            <label for="ProdExistenciaCB"><strong>SALDO FISICO (STOCK)
                                                </strong></label>
                                            <input type="text" id="ProdExistenciaCB" class="form-control text-center" disabled>
                                        </div>
                                    </div>
                                    <div class="row mt-2 d-none PreciosProductoSelected">
                                        <div class="col">
                                            <label for="PrecioLista"><strong>C/U PARA FACTURAR
                                                    MINIMO</strong></label>
                                            <input type="text" name="PrecioLista" id="PrecioLista" class="form-control" placeholder="Precio de Lista" disabled>
                                        </div>

                                        <div class="col">
                                            <label for="PrecioEspecial"><strong>IMPORTES PARA FACTURAR </label>
                                            <input type="text" name="PrecioEspecial" id="PrecioEspecial" class="form-control" placeholder="Precio Especial" disabled>
                                            <div class="text-danger d-none emptyPrecioEsp">No ha indicado el
                                                precio
                                                especial
                                            </div>
                                        </div>
                                        <div class="col">
                                            <label for="CantidadProducto"><strong>CANTIDAD</strong></label>
                                            <input type="number" name="Cantidad" id="CantidadProducto" class="form-control">
                                            <div class="text-danger d-none CantidadEmpty">La cantidad no puede
                                                ser
                                                negativa, nulo o mayor al Stock</div>
                                        </div>
                                    </div>
                                    <div class="row mt-4">
                                        <div class="col">Información: <br>
                                            Los productos en la tabla de color <span style="color:aqua;">CELESTE</span>
                                            son considerados productos_fiscales y afectan el stock de estos
                                            mismos
                                            al
                                            momento de realizar la facturación.
                                        </div>
                                    </div>
                                    <div class="row mt-4">
                                        <div class="col">
                                            <table id="tableProductosVendidos" class="table" width="100%">
                                                <!-- aki se llena con js la tabla -->
                                            </table>
                                        </div>
                                    </div>

                                    <div class="row mt-4">
                                        <div class="col">
                                            <button id="submitButton" class="btn btn-primary btn-block facturarSintic" type="button">
                                                <h4>Facturar</h4><i class="fas d-none efectSaveCotiza fa-spinner fa-pulse"></i>
                                            </button>
                                        </div>

                                    </div>


                                </form>
                            </div>

                        </div>
                    </div>
                </div>

            </div>           

        </div>

        <a href="javascript:;" class="btn btn-icon btn-circle btn-primary btn-scroll-to-top fade" data-click="scroll-top"><i class="fa fa-angle-up"></i></a>
        <?php echo "<script> actualizarclientCode(); </script>"; ?>

        <?php include 'php/footer.php'; ?>
    </div>
    <?php include 'php/script_facturacionEmision.php'; ?>
</body>

</html>