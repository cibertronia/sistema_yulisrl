<?php
include 'includes/conexion.php';
include 'includes/date.class.php';
include 'includes/funcionesListaProductos.php';
mysqli_query($MySQLi, "SET lc_time_names= 'es_BO' ");
$idUser     =    $_SESSION['idUser'];
$ConsltaUser =    mysqli_query($MySQLi, "SELECT * FROM Usuarios WHERE idUser='$idUser' ");
$datosUser     =    mysqli_fetch_assoc($ConsltaUser);
$miCiudad     =    $datosUser['Ciudad'];
error_reporting(0);
if ($_SESSION['Rango']) { ?>
    <!DOCTYPE html>
    <html lang="es">

    <head>
        <title>PRODUCTOS</title>
        <?php include 'php/meta.php'; ?>
        <link href="assets/css/apple/app.min.css" rel="stylesheet">
        <link href="assets/plugins/ionicons/css/ionicons.min.css" rel="stylesheet">
        <link href="assets/plugins/datatables.net-bs4/css/dataTables.bootstrap4.min.css" rel="stylesheet">
        <link href="assets/plugins/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css" rel="stylesheet">
        <link href="assets/plugins/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css" rel="stylesheet">
        <link href="assets/plugins/blueimp-gallery/css/blueimp-gallery.min.css" rel="stylesheet">
        <link href="assets/plugins/blueimp-file-upload/css/jquery.fileupload.css" rel="stylesheet">
        <link href="assets/plugins/blueimp-file-upload/css/jquery.fileupload-ui.css" rel="stylesheet">
        <link href="assets/plugins/summernote/dist/summernote.css" rel="stylesheet">
    </head>

    <body>
        <?php include 'php/loader.php'; ?>
        <div id="page-container" class="fade page-sidebar-fixed page-header-fixed">
            <?php
            include 'php/top_menu.php';
            include 'php/left_menu_productos.php';
            ?>
            <div id="content" class="content">
                <div class="respuesta"></div>
                <?php
                if (isset($_POST['inicio'])) {
                    $INICIO = $_POST['inicio'];
                    $FIN = $_POST['fin'];
                } else {
                    $INICIO = $startBusqueda; //startbuskeda = 1 del mes
                    $FIN = $fecha; //fecha = hoy
                }
                ?>

                <!-- 	EDITAR PRODUCTO	 -->
                <div class="row d-none editProducto w-75 m-auto">
                    <div class="col">
                        <div class="panel panel-primary">
                            <div class="panel-heading">
                                <h4 class="panel-title">EDITAR PRODUCTO</h4>
                                <button class="btn btn-xs btn-danger cancelarEditProducto">CANCELAR</button>
                            </div>
                            <div class="panel-body">
                                <form enctype="multipart/form-data" method="POST" action="app.php" data-parsley-validate="true">
                                    <div class="row text-center w-50 m-auto">
                                        <div class="col">
                                            <div id="imgx_"></div>
                                        </div>
                                        <div class="col">
                                            <span class="btn btn-primary fileinput-button mt-4">
                                                <i class="fa fa-plus"></i>
                                                <span>Cambiar imagen</span>
                                                <input type="file" name="imagen" id="img_file_">
                                            </span>
                                        </div>
                                    </div>
                                    <div class="row mt-3">
                                        <div class="col">
                                            <label for="ProdNombre_">Nombre del producto</label>
                                            <input type="hidden" name="idProducto" id="idProducto_">
                                            <input type="text" name="Producto" id="ProdNomnbre_" class="form-control" placeholder="Producto" data-parsley-required="true">
                                            <div class="text-center text-danger d-none emptyProdNombre_">Campo producto está
                                                vacío</div>
                                        </div>
                                        <div class="col">
                                            <label for="ProdMarca_">Marca del producto</label>
                                            <input type="text" name="Marca" id="ProdMarca_" class="form-control" placeholder="Marca" data-parsley-required="true">
                                            <div class="text-center text-danger d-none emptyProdMarca_">Campo marca está
                                                vacío</div>
                                        </div>
                                        <div class="col">
                                            <label for="ProdModelo_">Modelo del producto</label>
                                            <input type="text" name="Modelo" id="ProdModelo_" class="form-control" placeholder="Modelo" data-parsley-required="true">
                                            <div class="text-center text-danger d-none emptyProdModelo_">Campo modelo está
                                                vacío</div>
                                        </div>
                                    </div>
                                    <div class="row mt-3 w-75 m-auto">
                                        <div class="col text-center">
                                            <label for="ProdDescripcion_">Descripción del producto</label>
                                            <textarea name="Descripcion" id="ProdDescripcion_" data-parsley-required="true" cols="30" rows="5" class="form-control" placeholder="Descripción"></textarea>
                                            <div class="text-center text-danger d-none emptyProductoDescripcion_">Campo
                                                descripción está vacío</div>
                                        </div>
                                    </div>

                                    <div class="row mt-3">
                                        <!-- 	CENTRAL COCHABAMBA 	-->

                                        <div class="col text-center <?php if (!($miCiudad == 'Cochabamba' || $_SESSION['Rango'] == '2')) {
                                                                        echo 'd-none';
                                                                    } ?>">
                                            <span class="form-control bg-success text-white">CENTRAL COCHABAMBA</span>
                                            <div class="row mt-2">

                                                <div class="col">
                                                    <label for="ProdStockCB_">STOCK</label>
                                                    <input type="text" name="StockCB" id="ProdStockCB_" class="form-control" data-parsley-type="integer" placeholder="Stock" data-parsley-required="true" <?php if ($_SESSION['Rango'] == '1') {
                                                                                                                                                                                                                echo 'readonly';
                                                                                                                                                                                                            } ?>>
                                                    <div class="text-center text-danger d-none emptyStockCB">Campo stock
                                                        está vacío</div>
                                                </div>

                                                <div class="col">
                                                    <label for="ProdPrecioCB_">PRECIO</label>
                                                    <input type="text" name="PrecioCB" id="ProdPrecioCB_" data-parsley-type="number" class="form-control" placeholder="Precio" data-parsley-required="true">
                                                    <div class="text-center text-danger d-none emptyPrecioCB_">Campo precio
                                                        está vacío</div>
                                                </div>
                                            </div>
                                            <div class="row mt-2">
                                                <div class="col">
                                                    <label for="ProdObservCB_">OBSERVACIONES</label>
                                                    <textarea name="ObservacionesCB" id="ProdObservCB_" data-parsley-required="false" cols="30" rows="5" class="form-control" placeholder="Observaciones"></textarea>
                                                    <div class="text-center text-danger d-none emptyObservacionesCB_">Campo
                                                        observaciones está vacío</div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- 	SUCURSAL LA PAZ	 -->
                                        <div class="col text-center <?php if (!($miCiudad == 'La Paz' || $_SESSION['Rango'] == '2')) {
                                                                        echo 'd-none';
                                                                    } ?>">
                                            <span class="form-control bg-success text-white">SUCURSAL LA PAZ</span>
                                            <div class="row mt-2">
                                                <div class="col">
                                                    <label for="ProdStockLP_">STOCK</label>
                                                    <input type="text" name="StockLP" id="ProdStockLP_" class="form-control" data-parsley-type="integer" placeholder="Stock" data-parsley-required="true" <?php if ($_SESSION['Rango'] == '1') {
                                                                                                                                                                                                                echo 'readonly';
                                                                                                                                                                                                            } ?>>
                                                    <div class="text-center text-danger d-none emptyStockLP_">Campo stock
                                                        está vacío</div>
                                                </div>
                                                <div class="col">
                                                    <label for="ProdPrecioLP_">PRECIO</label>
                                                    <input type="text" name="PrecioLP" id="ProdPrecioLP_" data-parsley-type="number" class="form-control" placeholder="Precio" data-parsley-required="true">
                                                    <div class="text-center text-danger d-none emptyPrecioLP_">Campo precio
                                                        está vacío</div>
                                                </div>
                                            </div>
                                            <div class="row mt-2">
                                                <div class="col">
                                                    <label for="ProdObservLP_">OBSERVACIONES</label>
                                                    <textarea name="ObservacionesLP" id="ProdObservLP_" data-parsley-required="false" cols="30" rows="5" class="form-control" placeholder="Observaciones"></textarea>
                                                    <div class="text-center text-danger d-none emptyObservaciones_LP">Campo
                                                        observaciones está vacío</div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>

                                    <!-- 	SUCURSAL SANTA CRUZ	 -->
                                    <div class="row mt-3">

                                        <div class="col text-center <?php if (!($miCiudad == 'Santa Cruz' || $_SESSION['Rango'] == '2')) {
                                                                        echo 'd-none';
                                                                    } ?>">
                                            <span class="form-control bg-success text-white">SUCURSAL SANTA CRUZ</span>
                                            <div class="row mt-2">

                                                <div class="col">
                                                    <label for="ProdStockSC_">STOCK</label>
                                                    <input type="text" name="StockSC" id="ProdStockSC_" class="form-control" data-parsley-type="integer" placeholder="Stock" data-parsley-required="true" <?php if ($_SESSION['Rango'] == '1') {
                                                                                                                                                                                                                echo 'readonly';
                                                                                                                                                                                                            } ?>>
                                                    <div class="text-center text-danger d-none emptyStockSC_">Campo stock
                                                        está vacío</div>
                                                </div>

                                                <div class="col">
                                                    <label for="ProdPrecioSC_">PRECIO</label>
                                                    <input type="text" name="PrecioSC" id="ProdPrecioSC_" data-parsley-type="number" class="form-control" placeholder="Precio" data-parsley-required="true">
                                                    <div class="text-center text-danger d-none emptyPrecioSC_">Campo precio
                                                        está vacío</div>
                                                </div>
                                            </div>
                                            <div class="row mt-2">
                                                <div class="col">
                                                    <label for="PordObserv_SC">OBSERVACIONES</label>
                                                    <textarea name="ObservacionesSC" id="ProdObserv_SC" data-parsley-required="false" cols="30" rows="5" class="form-control" placeholder="Observaciones"></textarea>
                                                    <div class="text-center text-danger d-none emptyObservacionesSC_">Campo
                                                        observaciones está vacío</div>
                                                </div>
                                            </div>
                                        </div>


                                        <!-- 	SUCURSAL TARIJA	 -->

                                        <div class="col text-center <?php if (!($miCiudad == 'Tarija' || $_SESSION['Rango'] == '2')) {
                                                                        echo 'd-none';
                                                                    } ?>">
                                            <span class="form-control bg-success text-white">SUCURSAL TARIJA</span>
                                            <div class="row mt-2">
                                                <div class="col">
                                                    <label for="ProdStockTJ_">STOCK</label>
                                                    <input type="text" name="StockTJ" id="ProdStockTJ_" class="form-control" data-parsley-type="integer" placeholder="Stock" data-parsley-required="true" <?php if ($_SESSION['Rango'] == '1') {
                                                                                                                                                                                                                echo 'readonly';
                                                                                                                                                                                                            } ?>>
                                                    <div class="text-center text-danger d-none emptyStockTJ_">Campo stock
                                                        está vacío</div>
                                                </div>
                                                <div class="col">
                                                    <label for="ProdPrecioTJ_">PRECIO</label>
                                                    <input type="text" name="PrecioTJ" id="ProdPrecioTJ_" data-parsley-type="number" class="form-control" placeholder="Precio" data-parsley-required="true">
                                                    <div class="text-center text-danger d-none emptyPrecioTJ_">Campo precio
                                                        está vacío</div>
                                                </div>
                                            </div>
                                            <div class="row mt-2">
                                                <div class="col">
                                                    <label for="PordObserv_TJ">OBSERVACIONES</label>
                                                    <textarea name="ObservacionesTJ" id="ProdObserv_TJ" data-parsley-required="false" cols="30" rows="5" class="form-control" placeholder="Observaciones"></textarea>
                                                    <div class="text-center text-danger d-none emptyObservacionesTJ_">Campo
                                                        observaciones está vacío</div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="row mt-3">
                                        <div class="col">
                                            <button type="submit" class="btn btn-xs btn-primary form-control">ACTUALIZAR
                                                PRODUCTO &nbsp;<i class="fas d-none fa-spinner fa-pulse subFunction"></i></button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 	TABLA PRODUCTO	 -->
                <div class="row tableProductos">
                    <div class="col-md-12">
                        <div class="panel panel-inverse">
                            <div class="panel-heading">
                                <h4 class="panel-title">LISTA DE PRODUCTOS</h4>
                                <div class="panel-heading-btn">
                                    <?php

                                    if ($_SESSION['idUser'] == 1) { ?>
                                        <!-- <button class="btn btn-xs btn-danger DownProductos"><i class="fa fa-download"></i></button> -->&nbsp;<?php
                                                                                                                                                }

                                                                                                                                                    ?>
                                        &nbsp;<a href="?root=nuevoproducto" class="btn btn-xs btn-primary">AGREGAR PRODUCTO</a>
                                        <!-- <button class="btn btn-xs btn-primary AddNewProductoBTN">AGREGAR PRODUCTO</button>&nbsp;&nbsp;&nbsp; -->
                                        <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
                                        <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-success" data-click="panel-reload"><i class="fa fa-redo"></i></a>
                                        <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>
                                        <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-danger" data-click="panel-remove"><i class="fa fa-times"></i></a>
                                </div>
                            </div>
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col mt-11">
                                        <!-- <div class="divBuscarSubidaSistema">
                                        <button class="btn btn-primary BuscarSubidaSistema"><i class="fa fa-search">
                                                Buscar</i></button>&nbsp;&nbsp;
                                    </div> -->
                                        <div class="input-group input-daterange divFecha ">
                                            <form action="?root=productos" method="post">
                                                <input required="" type="date" name="inicio" value="<?php echo $INICIO ?>">
                                                <input required="" type="date" name="fin" value="<?php echo $FIN ?>">
                                                <input type="submit" class="btn btn-xs btn-primary" value="BUSCAR">
                                            </form>

                                        </div>
                                    </div>
                                    <div class="col-xl-3 col-md-5">

                                        <a class="btn btn-success btn-sm btn-block" href="Reportes/reporteListaProductos.php?reporteListaProductos=reporteListaProductos&fechaInicio=<?php echo $INICIO ?>&fechafin=<?php echo $FIN ?>" title="Historial todos los Productos en rango de fechas"><span style="color: white">
                                                EXCEL DESCARGAR</span>&nbsp;&nbsp;
                                            <i class="fa fa-download" style="color: white"></i>
                                        </a>

                                    </div>
                                </div>



                                <br>
                                <div style="overflow-x:auto;">
                                    <table id="data-table-buttons" class="table table-striped table-bordered table-td-valign-middle w-100">
                                        <thead>
                                            <tr>
                                                <th width="3%" class="text-center">N&ordm;</th>
                                                <th width="15%" class="text-center">Nombre</th>
                                                <th width="15%" class="text-center">Marca</th>
                                                <th width="15%" class="text-center">Modelo</th>
                                                <th style="text-align:center;background-color: #FFF2CC">Stock<br>CB</th>
                                                <th width="10%" class="text-center">Precio USD&nbsp;<br>CB</th>

                                                <th style="text-align:center;background-color: #DDEBF7">Stock<br>LP</th>
                                                <th width="10%" class="text-center">Precio USD&nbsp;<br>LP</th>

                                                <th style="text-align:center;background-color: #E2EFDA">Stock<br>SC</th>
                                                <th width="10%" class="text-center">Precio USD&nbsp;<br>SC</th>

                                                <th style="text-align:center;background-color: #FCE4D6">Stock<br>TJ</th>
                                                <th width="10%" class="text-center">Precio USD&nbsp;<br>TJ</th>

                                                <th style="text-align:center;background-color: #FFA500">Stock<br>Total</th>

                                                <th style="text-align:center; background-color: #FFF2CC">Extraidos
                                                    <br><?php echo "$INICIO" . '<br>/ ' . "$FIN"; ?> <br> CB
                                                </th>
                                                <th style="text-align:center; background-color: #FFF2CC">Recepcionados
                                                    <br><?php echo "$INICIO" . '<br>/ ' . "$FIN"; ?> <br> CB
                                                </th>

                                                <th style="text-align:center; background-color: #DDEBF7">Extraidos
                                                    <br><?php echo "$INICIO" . '<br>/ ' . "$FIN"; ?> <br> LP
                                                </th>
                                                <th style="text-align:center; background-color: #DDEBF7">Recepcionados
                                                    <br><?php echo "$INICIO" . '<br>/ ' . "$FIN"; ?> <br> LP
                                                </th>

                                                <th style="text-align:center; background-color: #E2EFDA">Extraidos
                                                    <br><?php echo "$INICIO" . '<br>/ ' . "$FIN"; ?> <br> SC
                                                </th>
                                                <th style="text-align:center; background-color: #E2EFDA">Recepcionados
                                                    <br><?php echo "$INICIO" . '<br>/ ' . "$FIN"; ?> <br> SC
                                                </th>

                                                <th style="text-align:center; background-color: #FCE4D6">Extraidos
                                                    <br><?php echo "$INICIO" . '<br>/ ' . "$FIN"; ?> <br> TJ
                                                </th>
                                                <th style="text-align:center; background-color: #FCE4D6">Recepcionados
                                                    <br><?php echo "$INICIO" . '<br>/ ' . "$FIN"; ?> <br> TJ
                                                </th>

                                                <th width="10%" class="text-center">Imagen</th>
                                                <th width="7%" class="text-center">Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php

                                            $Num = 1;
                                            $totalextractoCB = 0;
                                            $totalRecepcionCB = 0;

                                            $totalextractoLP = 0;
                                            $totalRecepcionLP = 0;

                                            $totalextractoSC = 0;
                                            $totalRecepcionSC = 0;

                                            $totalextractoTJ = 0;
                                            $totalRecepcionTJ = 0;



                                            $totalCapturaCB = 0;  //1
                                            $totalCapturaLP = 0; //2
                                            $totalCapturaSC = 0; //3
                                            $totalCapturaTJ = 0; //4
                                            $totalCapturaStockTotal = 0; //5

                                            $queryProductos    =    mysqli_query($MySQLi, "SELECT * FROM Productos ORDER BY Producto ASC");
                                            while ($dataProductos = mysqli_fetch_assoc($queryProductos)) {
                                                $idProducto = $dataProductos['idProducto'];

                                            ?>
                                                <tr class="odd gradeX">
                                                    <td class="text-center"><?php echo $Num; ?></td>
                                                    <td class="">
                                                        <?php echo $dataProductos['Producto'] ?>
                                                    </td>
                                                    <td class="">
                                                        <?php echo $dataProductos['Marca'] ?>
                                                    </td>
                                                    <td class="">
                                                        <?php echo $dataProductos['Modelo'] ?>
                                                    </td>

                                                    <?php
                                                    if ($FIN != $fecha) {
                                                        $capturaProducto = captura_producto($MySQLi, $idProducto, $FIN);
                                                    }
                                                    ?>
                                                    <td style="text-align:center;background-color:#FFF2CC">
                                                        <?php
                                                        if ($FIN == $fecha) {
                                                            echo $dataProductos['StockCB'];
                                                        } else {
                                                            if ($capturaProducto) {
                                                                echo $capturaProducto['StockCB'];
                                                                $totalCapturaCB += $capturaProducto['StockCB'];
                                                            } else {
                                                                echo 'No existe Registro del stock en la fecha ' . $FIN;
                                                            }
                                                        }

                                                        ?></td>
                                                    <td class="text-center"><?php echo $dataProductos['PrecioCB'] ?></td>

                                                    <td style="text-align:center;background-color:#DDEBF7">
                                                        <?php

                                                        if ($FIN == $fecha) {
                                                            echo $dataProductos['StockLP'];
                                                        } else {
                                                            if ($capturaProducto) {
                                                                echo $capturaProducto['StockLP'];
                                                                $totalCapturaLP += $capturaProducto['StockLP'];
                                                            } else {
                                                                echo 'No existe Registro del stock en la fecha ' . $FIN;
                                                            }
                                                        }

                                                        ?>
                                                    </td>
                                                    <td class="text-center f-w-600"><?php echo $dataProductos['PrecioLP'] ?>
                                                    </td>

                                                    <td style="text-align:center;background-color:#E2EFDA">
                                                        <?php

                                                        if ($FIN == $fecha) {
                                                            echo $dataProductos['StockSC'];
                                                        } else {
                                                            if ($capturaProducto) {
                                                                echo $capturaProducto['StockSC'];
                                                                $totalCapturaSC += $capturaProducto['StockSC'];
                                                            } else {
                                                                echo 'No existe Registro del stock en la fecha ' . $FIN;
                                                            }
                                                        }
                                                        ?>

                                                    </td>
                                                    <td class="text-center"><?php echo $dataProductos['PrecioSC'] ?></td>

                                                    <td style="text-align:center;background-color: #FCE4D6">
                                                        <?php

                                                        if ($FIN == $fecha) {
                                                            echo $dataProductos['StockTJ'];
                                                        } else {
                                                            if ($capturaProducto) {
                                                                echo $capturaProducto['StockTJ'];
                                                                $totalCapturaTJ += $capturaProducto['StockTJ'];
                                                            } else {
                                                                echo 'No existe Registro del stock en la fecha ' . $FIN;
                                                            }
                                                        }

                                                        ?>



                                                    </td>
                                                    <td class="text-center"><?php echo $dataProductos['PrecioTJ'] ?></td>

                                                    <td style="text-align:center;background-color: <?php echo (($dataProductos['StockTotal'] <= 5 && $FIN == $fecha) || ($capturaProducto['StockTotal'] <= 5 && $FIN != $fecha)) ? '#F04E4E' : '#FFA500'; ?>">

                                                        <?php
                                                        if ($FIN == $fecha) {
                                                            echo $dataProductos['StockTotal'];
                                                        } else {
                                                            if ($capturaProducto) {
                                                                echo $capturaProducto['StockTotal'];
                                                                $totalCapturaStockTotal += $capturaProducto['StockTotal'];
                                                            } else {
                                                                echo 'No existe Registro del stock en la fecha ' . $FIN;
                                                            }
                                                        }

                                                        ?>

                                                    </td>



                                                    <?php
                                                    $extractoCB = extractoProducto($MySQLi, $idProducto, $INICIO, $FIN, 'Cochabamba');
                                                    $totalextractoCB = $totalextractoCB + $extractoCB;
                                                    $recepcionadoCB = recepcionProducto($MySQLi, $idProducto, $INICIO, $FIN, 'Cochabamba');
                                                    $totalRecepcionCB += $recepcionadoCB;

                                                    $extractoLP = extractoProducto($MySQLi, $idProducto, $INICIO, $FIN, 'La Paz');
                                                    $totalextractoLP = $totalextractoLP + $extractoLP;
                                                    $recepcionadoLP = recepcionProducto($MySQLi, $idProducto, $INICIO, $FIN, 'La Paz');
                                                    $totalRecepcionLP += $recepcionadoLP;

                                                    $extractoSC = extractoProducto($MySQLi, $idProducto, $INICIO, $FIN, 'Santa Cruz');
                                                    $totalextractoSC = $totalextractoSC + $extractoSC;
                                                    $recepcionadoSC = recepcionProducto($MySQLi, $idProducto, $INICIO, $FIN, 'Santa Cruz');
                                                    $totalRecepcionSC += $recepcionadoSC;

                                                    $extractoTJ = extractoProducto($MySQLi, $idProducto, $INICIO, $FIN, 'Tarija');
                                                    $totalextractoTJ = $totalextractoTJ + $extractoTJ;
                                                    $recepcionadoTJ = recepcionProducto($MySQLi, $idProducto, $INICIO, $FIN, 'Tarija');
                                                    $totalRecepcionTJ += $recepcionadoTJ;
                                                    ?>
                                                    <td style="text-align:center; background-color: #FFF2CC">
                                                        <?php echo $extractoCB * -1; ?>
                                                    </td>
                                                    <td style="text-align:center; background-color: #FFF2CC">
                                                        <?php echo $recepcionadoCB; ?>
                                                    </td>


                                                    <td style="text-align:center; background-color: #DDEBF7">
                                                        <?php echo $extractoLP * -1; ?>
                                                    </td>
                                                    <td style="text-align:center; background-color: #DDEBF7">
                                                        <?php echo $recepcionadoLP; ?>
                                                    </td>


                                                    <td style="text-align:center; background-color: #E2EFDA">
                                                        <?php echo $extractoSC * -1; ?>
                                                    </td>
                                                    <td style="text-align:center; background-color: #E2EFDA">
                                                        <?php echo $recepcionadoSC; ?>
                                                    </td>


                                                    <td style="text-align:center; background-color: #FCE4D6">
                                                        <?php echo $extractoTJ * -1; ?>
                                                    </td>
                                                    <td style="text-align:center; background-color: #FCE4D6">
                                                        <?php echo $recepcionadoTJ; ?>
                                                    </td>


                                                    <td class="text-center"><img height="50" src="Productos/<?php echo $dataProductos['Imagen'] ?>" alt="<?php echo $dataProductos['Producto'] . " / " . $dataProductos['Marca'] . " / " . $dataProductos['Modelo'] ?>">
                                                    </td>
                                                    <td class="text-center">
                                                        <button title="Editar Producto" id="<?php echo $dataProductos['idProducto'] ?>" class="btn btn-xs btn-success editProdExistente">
                                                            <i class="ion-ios-brush" style="font-size: 15px"></i>
                                                        </button>&nbsp;

                                                        <?php if ($_SESSION['Rango'] == '2') { ?>
                                                            <button class="btn btn-xs btn-danger borrarProducto" title="Borrar producto" id="<?php echo $dataProductos['idProducto'] ?>">
                                                                <i class="fa fa-trash-alt" style="font-size: 15px"></i>
                                                            </button>
                                                        <?php  } ?>


                                                    </td>
                                                </tr>
                                            <?php $Num++;
                                            }
                                            ?>

                                            <tr class="odd gradeX">
                                                <td class="text-center"><?php echo $Num; ?></td>
                                                <td class=""></td>
                                                <td class=""></td>
                                                <td class=""><strong>TOTALES</strong></td>
                                                <?php
                                                $queryTotales    =    mysqli_query(
                                                    $MySQLi,
                                                    "SELECT
                                        SUM(StockCB) AS totalStockCB,
                                        SUM(PrecioCB) AS totalPrecioCB,
                                        
                                        SUM(StockLP) AS totalStockLP,
                                        SUM(PrecioLP) AS totalPrecioLP,
                                        
                                        SUM(StockSC) AS totalStockSC,
                                        SUM(PrecioSC) AS totalPrecioSC,
                                        
                                        SUM(StockTJ) AS totalStockTJ,
                                        SUM(PrecioTJ) AS totalPrecioTJ,
                                        
                                        SUM(StockTotal) AS GranTotalStockTotal
                                        FROM
                                        Productos"
                                                );
                                                $dataTotales = mysqli_fetch_assoc($queryTotales);

                                                ?>
                                                <td style="text-align:center;background-color: #FFF2CC"><strong>
                                                        <?php
                                                        if ($FIN == $fecha) {
                                                            echo $dataTotales['totalStockCB'];
                                                        } else {
                                                            echo $totalCapturaCB;
                                                        }


                                                        ?>
                                                    </strong></td>
                                                <td class="text-center"><?php echo $dataTotales['totalPrecioCB'] ?></td>

                                                <td style="text-align:center;background-color: #DDEBF7"><strong>
                                                        <?php
                                                        if ($FIN == $fecha) {
                                                            echo $dataTotales['totalStockLP'];
                                                        } else {
                                                            echo $totalCapturaLP;
                                                        }



                                                        ?></strong></td>
                                                <td class="text-center f-w-600"><?php echo $dataTotales['totalPrecioLP'] ?>
                                                </td>

                                                <td style="text-align:center;background-color: #E2EFDA"><strong>
                                                        <?php
                                                        if ($FIN == $fecha) {
                                                            echo $dataTotales['totalStockSC'];
                                                        } else {
                                                            echo $totalCapturaSC;
                                                        }

                                                        ?>
                                                    </strong></td>
                                                <td class="text-center"><?php echo $dataTotales['totalPrecioSC'] ?></td>

                                                <td style="text-align:center;background-color: #FCE4D6"><strong>

                                                        <?php
                                                        if ($FIN == $fecha) {
                                                            echo $dataTotales['totalStockTJ'];
                                                        } else {
                                                            echo $totalCapturaTJ;
                                                        }




                                                        ?>
                                                    </strong></td>
                                                <td class="text-center"><?php echo $dataTotales['totalPrecioTJ'] ?></td>

                                                <td style="text-align:center;background-color: #FFA500"><strong>
                                                        <?php
                                                        if ($FIN == $fecha) {
                                                            echo $dataTotales['GranTotalStockTotal'];
                                                        } else {
                                                            echo $totalCapturaStockTotal;
                                                        }


                                                        ?></strong></td>

                                                <td style="text-align:center; background-color: #FFF2CC">
                                                    <?php echo $totalextractoCB * -1; ?></td>
                                                <td style="text-align:center; background-color: #FFF2CC">
                                                    <?php echo $totalRecepcionCB; ?></td>

                                                <td style="text-align:center; background-color: #DDEBF7">
                                                    <?php echo $totalextractoLP * -1; ?></td>
                                                <td style="text-align:center; background-color: #DDEBF7">
                                                    <?php echo $totalRecepcionLP; ?></td>

                                                <td style="text-align:center; background-color: #E2EFDA">
                                                    <?php echo $totalextractoSC * -1; ?></td>
                                                <td style="text-align:center; background-color: #E2EFDA">
                                                    <?php echo $totalRecepcionSC; ?></td>

                                                <td style="text-align:center; background-color: #FCE4D6">
                                                    <?php echo $totalextractoTJ * -1; ?></td>
                                                <td style="text-align:center; background-color: #FCE4D6">
                                                    <?php echo $totalRecepcionTJ; ?></td>

                                                <td class="text-center"></td>
                                                <td class="text-center"></td>


                                            </tr>

                                        </tbody>
                                    </table>
                                </div>
                                <!-- <ul class="pagination">
											<li class="page-item disabled">
												<a href="#" class="page-link">«</a>
											</li>											
											<?php
                                            // for ($i=1; $i <= $TotalPaginas; $i++) {
                                            // 	echo '<li class="page-item active">';
                                            // 	echo '<a href="?root=productos&Pagina='.$i.'"  class="page-link">'.$i.'</a>&nbsp;</li>';
                                            // }
                                            ?>
											<li class="page-item"><a href="#" class="page-link">»</a></li>											
										</ul>										
																			</div> -->
                                <!-- end panel-body -->
                            </div>
                        </div>
                    </div>
                </div>
                <a href="javascript:;" class="btn btn-icon btn-circle btn-primary btn-scroll-to-top fade" data-click="scroll-top"><i class="fa fa-angle-up"></i></a>
                <?php include 'php/footer.php'; ?>
            </div>
            <?php include 'php/script_productos.php'; ?>
            <script type="text/javascript">
                $(".subFunction").click(function() {
                    alert("Hola");
                });
            </script>
    </body>

    </html>
<?php include 'php/fun_productos.php';
} else { ?>
    <script type="text/javascript">
        location.replace("?root=404");
    </script><?php
            }
                ?>