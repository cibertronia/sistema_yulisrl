<?php
include 'includes/conexion.php';
include 'includes/date.class.php';
include 'functions/functions_productosFiscalesHistorial.php';

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
        <title>HISTORIAL FISCALES SRL</title>
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
            include 'php/left_menu_productos_fiscales.php';
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
                $INICIO = $Inicio;
                $FIN = $Fin;
                ?>

                <!-- 	TABLA PRODUCTO	 -->
                <div class="row tableProductos">
                    <div class="col-md-12">
                        <div class="panel panel-info">
                            <div class="panel-heading">
                                <h4 class="panel-title"><span style="text-transform: uppercase;letter-spacing: 1px;font-size: 16px">&nbsp;&nbsp;HISTORIAL
                                        PRODUCTOS FISCALES</span></h4>
                                <div class="panel-heading-btn">

                                </div>
                            </div>
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col mt-11">
                                        <div class="input-group input-daterange divFecha">
                                            <form action="?root=productosFiscalesHistorial" method="post">
                                                <input required="" type="date" name="inicio" value="<?php echo $Inicio ?>">
                                                <input required="" type="date" name="fin" value="<?php echo $Fin ?>">
                                                <input type="submit" class="btn btn-xs btn-primary" value="BUSCAR">
                                            </form>
                                        </div>
                                    </div>
                                    <div class="col-xl-3 col-md-5">

                                        <a class="btn btn-success btn-sm btn-block" href="Reportes/reporteProductosFiscalesHistorial.php?fechaInicio=<?php echo $INICIO ?>&fechafin=<?php echo $FIN ?>" title="Descargar Excel"><span style="color: white">
                                                EXCEL DESCARGAR</span>&nbsp;&nbsp;
                                            <i class="fa fa-download" style="color: white"></i>
                                        </a>

                                    </div>
                                </div>
                                <br>
                                <table id="data-table-buttons" class="table table-striped table-bordered table-td-valign-middle w-100">
                                    <thead>
                                        <tr>
                                            <th width="3%" class="text-center">N&ordm;</th>
                                            <th width="3%" class="text-center">Id<br> Producto</th>
                                            <!-- <th width="10%" class="text-center">FECHA POLIZA</th> -->
                                            <th width="5%" class="text-center">CODIGO</th>
                                            <th width="5%" class="text-center">DETALLE</th>
                                            <th width="5%" class="text-center">SALDO FISICO <br>(ACTUAL)</th>
                                            <th width="5%" class="text-center">INICIAL <br>(Stock en la fecha: <?php echo $Inicio; ?> )</th>

                                            <th width="5%" style="text-align:center; background-color: #FFF2CC">CB</th>
                                            <th width="5%" style="text-align:center; background-color: #DDEBF7">LP</th>
                                            <th width="5%" style="text-align:center; background-color: #E2EFDA">SC</th>
                                            <th width="5%" style="text-align:center; background-color: #e2d8e4">ST</th>
                                            <th width="5%" style="text-align:center; background-color: #FCE4D6">TJ</th>
                                            <th width="5%" class="text-center">FINAL <br>(Stock en la fecha: <?php echo $Fin; ?> )</th>

                                            <th width="5%" class="text-center">C/U <br>FACTURAR<br> MINIMO</th>
                                            <th width="5%" class="text-center">IMPORTES <br>PARA<br> FACTURAR</th>

                                            <th width="5%" class="text-center">CANTIDAD <br>TOTAL <br>FACTURADO</th>
                                            <th width="5%" class="text-center">MONTO <br>TOTAL<br> FACTURADO</th>
                                            <th width="5%" class="text-center">#DOCUMENTO SRL</th>
                                            <th width="5%" class="text-center">#DOCUMENTO YULIIMPORT</th>

                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $Num = 1;
                                        $total_saldo_fisico = 0;
                                        $total_inicial = 0;
                                        $total_cb = 0;
                                        $total_lp = 0;
                                        $total_sc = 0;
                                        $total_st = 0;
                                        $total_tj = 0;
                                        $total_final = 0;

                                        $total_c_u_facturar_minimo = 0;
                                        $total_importes_facturar_minimo = 0;
                                        $total_cantidad_total_facturado = 0;
                                        $total_monto_total_facturado = 0;


                                        $query = "SELECT * FROM productos_fiscales ORDER BY idProducto ASC";
                                        $queryProductos    =    mysqli_query($MySQLi, $query);

                                        while ($dataProductos = mysqli_fetch_assoc($queryProductos)) {
                                            $idProducto = $dataProductos['idProducto'];
                                        ?>
                                        <? 
                                        $cb = extractoProductoFiscal($MySQLi, $idProducto, $INICIO, $FIN, 1);
                                        $lp = extractoProductoFiscal($MySQLi, $idProducto, $INICIO, $FIN, 2);
                                        $sc = extractoProductoFiscal($MySQLi, $idProducto, $INICIO, $FIN, 3);
                                        $tj = extractoProductoFiscal($MySQLi, $idProducto, $INICIO, $FIN, 4);
                                        $st = extractoProductoFiscal($MySQLi, $idProducto, $INICIO, $FIN, 5);
                                        $all = 0;
                                        if ($FIN != $fecha) {
                                            $capturaProducto = captura_producto_fiscales($MySQLi, $idProducto, $FIN);
                                        }
                                        if ($FIN == $fecha) {
                                            $all = $dataProductos['saldo_fisico'];
                                        } else {
                                            if ($capturaProducto) {
                                                $all = $capturaProducto['stock_capturado'];
                                            } else {
                                                // echo 'No existe Registro del stock fiscal en la fecha ' . $FIN;
                                                // echo 'revisar bien ';
                                                $all = no_existe_captura_fin($MySQLi, $idProducto, $INICIO, $FIN, $dataProductos['saldo_fisico']);
                                            }
                                        }
                                       ?>
                                        
                                            <tr class="odd gradeX">
                                                <td class="text-center"><?php echo $Num; ?></td>
                                                <td class="text-center"> <?php echo $dataProductos['idProducto']; ?> </td>
                                                <!-- <td class="text-center"> <?php echo $dataProductos['fecha_poliza']; ?> </td> -->
                                                <td class="text-center"> <?php echo $dataProductos['codigo']; ?> </td>

                                                <td style="background-color: <?php
                                                if ($dataProductos['idProducto'] == 328 || $dataProductos['idProducto'] == 343)
                                                    echo '#FF9500';                                                
                                                else
                                                    echo ( $dataProductos['idProducto'] == 343 || $dataProductos['idProductoFiscal01yuli'] == null || $dataProductos['idProductoFiscal01yuli'] == '' || $dataProductos['idProductoFiscal01yuli'] <= 0) ? '' : '#FF9500';
                                                                                ?>">
                                                    <?php
                                                    echo $dataProductos['detalle'];
                                                    ?>
                                                </td>
                                                <td class="text-center">
                                                    <?php
                                                    echo $dataProductos['saldo_fisico'];
                                                    $total_saldo_fisico += $dataProductos['saldo_fisico'];
                                                    ?>

                                                </td>
                                                <td style="text-align:center;">
                                                    <?php
                                                    if ($INICIO != $fecha) {
                                                        $capturaProducto = captura_producto_fiscales($MySQLi, $idProducto, $INICIO);
                                                    }
                                                    if ($INICIO == $fecha) {
                                                        echo $dataProductos['saldo_fisico'];
                                                        $total_inicial += $dataProductos['saldo_fisico'];
                                                    } else {
                                                        if ($capturaProducto) {
                                                            echo $capturaProducto['stock_capturado'];
                                                            $total_inicial += $capturaProducto['stock_capturado'];
                                                        } else {
                                                            // echo 'No existe Registro del stock fiscal en la fecha ' . $INICIO;
                                                            // echo 'revisar ';
                                                            $no_inicio = no_existe_captura_inicio($MySQLi, $idProducto, $INICIO, $FIN, $dataProductos['saldo_fisico']);
                                                            echo $no_inicio;
                                                            $total_inicial += (int)$no_inicio;
                                                        }
                                                    }
                                                    ?>
                                                </td>
                                                <td class="text-center">
                                                    <?php
                                                    $cb = extractoProductoFiscal($MySQLi, $idProducto, $INICIO, $FIN, 1);
                                                    echo $cb;
                                                    $total_cb += $cb;
                                                    ?>
                                                </td>
                                                <td class="text-center">
                                                    <?php
                                                    $lp = extractoProductoFiscal($MySQLi, $idProducto, $INICIO, $FIN, 2);
                                                    echo $lp;
                                                    $total_lp += $lp;
                                                    ?>
                                                </td>
                                                <td class="text-center">
                                                    <?php
                                                    $sc = extractoProductoFiscal($MySQLi, $idProducto, $INICIO, $FIN, 3);
                                                    echo $sc;
                                                    $total_sc += $sc;
                                                    ?>
                                                </td>
                                                <td class="text-center">
                                                    <?php
                                                    $st = extractoProductoFiscal($MySQLi, $idProducto, $INICIO, $FIN, 5);
                                                    echo $st;
                                                    $total_sc += $st;
                                                    ?>
                                                </td>
                                                <td class="text-center">
                                                    <?php
                                                    $tj = extractoProductoFiscal($MySQLi, $idProducto, $INICIO, $FIN, 4);
                                                    echo $tj;
                                                    $total_tj += $tj;
                                                    ?>
                                                </td>
                                                <td class="text-center">
                                                    <?php
                                                    if ($FIN != $fecha) {
                                                        $capturaProducto = captura_producto_fiscales($MySQLi, $idProducto, $FIN);
                                                    }
                                                    if ($FIN == $fecha) {
                                                        echo $dataProductos['saldo_fisico'];
                                                        $total_final += $dataProductos['saldo_fisico'];
                                                    } else {
                                                        if ($capturaProducto) {
                                                            echo $capturaProducto['stock_capturado'];
                                                            $total_final += $capturaProducto['stock_capturado'];
                                                        } else {
                                                            // echo 'No existe Registro del stock fiscal en la fecha ' . $FIN;
                                                            // echo 'revisar bien ';
                                                            $no_final = no_existe_captura_fin($MySQLi, $idProducto, $INICIO, $FIN, $dataProductos['saldo_fisico']);
                                                            echo $no_final;
                                                            $total_final += (int)$no_final;
                                                        }
                                                    }
                                                    ?>
                                                </td>
                                                <td class="text-center">
                                                    <?php
                                                    echo $dataProductos['c_u_facturar_minimo'];
                                                    $total_c_u_facturar_minimo += $dataProductos['c_u_facturar_minimo'];
                                                    ?>
                                                </td>
                                                <td class="text-center">
                                                    <?php
                                                    echo $dataProductos['importes_para_facturar'];
                                                    $total_importes_facturar_minimo += $dataProductos['importes_para_facturar'];
                                                    ?>
                                                </td>
                                                <td class="text-center">
                                                    <?php
                                                    $qty_prod_facturado = cantidadTotalFiscalFacturado($MySQLi, $idProducto, $INICIO, $FIN);
                                                    echo $qty_prod_facturado;
                                                    $total_cantidad_total_facturado += $qty_prod_facturado;
                                                    ?>
                                                </td>
                                                <td class="text-center">
                                                    <?php
                                                    $montoTotalFacturado = montoTotalFacturado($MySQLi, $idProducto, $INICIO, $FIN);
                                                    echo $montoTotalFacturado;
                                                    $total_monto_total_facturado += $montoTotalFacturado;
                                                    ?>
                                                </td>
                                                <td class="text-center">
                                                    <?php
                                                    $cotizaciones_facturadas_prod_fiscal = cotizaciones_facturadas_prod_fiscal($MySQLi, $idProducto, $INICIO, $FIN);
                                                    echo $cotizaciones_facturadas_prod_fiscal;
                                                    ?>
                                                </td>
                                                <td class="text-center">
                                                    <?php
                                                    $numeros_facturadas_prod_fiscal_yuliimport_doble = numeros_facturadas_prod_fiscal_yuliimport_doble($MySQLi, $idProducto, $INICIO, $FIN);
                                                    echo $numeros_facturadas_prod_fiscal_yuliimport_doble
                                                    ?>
                                                </td>
                                            </tr>
                                            
                                            <?php $Num++;  ?>
                                        <?php  }

                                        mysqli_close($MySQLi); ?>
                                        <tr>
                                            <th width="5%" class="text-center"><?php echo $Num; ?></th>
                                            <th width="5%" class="text-center"></th>
                                            <!-- <th width="10%" class="text-center"></th> -->
                                            <th width="5%" class="text-center"></th>
                                            <th width="5%" class="text-center">Totales: </th>
                                            <th width="5%" class="text-center btn-success"><?php echo $total_saldo_fisico ?></th>

                                            <th width="5%" class="text-center btn-primary"><?php echo $total_inicial ?></th>
                                            <th width="5%" class="text-center"><?php echo $total_cb ?></th>
                                            <th width="5%" class="text-center"><?php echo $total_lp ?></th>
                                            <th width="5%" class="text-center"><?php echo $total_sc ?></th>
                                            <th width="5%" class="text-center"><?php echo $total_st ?></th>
                                            <th width="5%" class="text-center"><?php echo $total_tj ?></th>
                                            <th width="5%" class="text-center btn-primary"><?php echo $total_final ?></th>

                                            <th width="5%" class="text-center"><?php echo $total_c_u_facturar_minimo ?></th>
                                            <th width="5%" class="text-center"><?php echo $total_importes_facturar_minimo ?></th>
                                            <th width="5%" class="text-center"><?php echo $total_cantidad_total_facturado ?></th>
                                            <th width="5%" class="text-center"><?php echo $total_monto_total_facturado ?></th>

                                            <th width="5%" class="text-center"></th>
                                            <th width="5%" class="text-center"></th>
                                        </tr>
                                    </tbody>
                                </table>

                                <!-- end panel-body -->
                            </div>
                        </div>
                    </div>
                </div>
                <a href="javascript:;" class="btn btn-icon btn-circle btn-primary btn-scroll-to-top fade" data-click="scroll-top"><i class="fa fa-angle-up"></i></a>
                <?php include 'php/footer.php'; ?>
            </div>
            <?php include 'php/script_productosFiscalesHistorial.php'; ?>
    </body>

    </html>
<?php //include 'php/fun_productos.php';
} else { ?>
    <script type="text/javascript">
        location.replace("?root=404");
    </script>
<?php
}
?>