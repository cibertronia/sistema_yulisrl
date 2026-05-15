<?php
require '../includes/librerias/mPDF/vendor/autoload.php';
require '../includes/conexion.php';
include '../includes/date.class.php';
require '../includes/librerias/phpMailer/vendor/autoload.php';
include '../functions/functions_productosFiscalesHistorial.php';

error_reporting(0);

mysqli_query($MySQLi, "SET lc_time_names= 'es_BO' ");
if (isset($_GET['fechaInicio']) and isset($_GET['fechafin'])) {
    $INICIO = $_GET['fechaInicio'];
    $FIN = $_GET['fechafin'];

    header("Content-type: application/vnd.ms-excel; name='excel'");
    header("Content-Disposition: filename=ProductosFiscalesHistorial " . $INICIO . "__" . $FIN . ".xls");
    header("Pragma: no-cache");
    header("Pragma: no-cache");
    header("Expires: 0");
?>

    <table border="1" style="font-family: Arial narrow">
        <thead>
            <tr>
                <th colspan="14" style="text-align: left;">
                    <h3>HISTORIAL PRODUCTOS FISCALES DESDE EL <span style="color: green"><?php echo $INICIO ?></span> HASTA EL <span style="color: red"><?php echo $FIN ?></span></h3>
                </th>
            </tr>
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
            // $st = extractoProductoFiscal($MySQLi, $idProducto, $INICIO, $FIN, 5);
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
                        if ( $dataProductos['idProducto'] == 343 ||  $dataProductos['idProducto'] == 328)
                        echo '#FF9500';
                        else
                                                    echo ($dataProductos['idProductoFiscal01yuli'] == null || $dataProductos['idProductoFiscal01yuli'] == '' || $dataProductos['idProductoFiscal01yuli'] <= 0 ) ? '' : '#FF9500';
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
                        $total_st += $st;
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
                        if (($INICIO == '2023-10-01') && ($FIN == '2023-10-31') && ($idProducto == 37))
                                                    echo "TJ-231004-3809=F41 Emision Doble";
                                                    else
                        echo $cotizaciones_facturadas_prod_fiscal
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
            <?php  }           ?>
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

<?php mysqli_close($MySQLi);
}
