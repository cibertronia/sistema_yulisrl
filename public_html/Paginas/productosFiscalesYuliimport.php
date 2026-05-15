<?php
include 'includes/conexion.php';
include 'includes/conexion_yuliimport.php';
include 'includes/date.class.php';
mysqli_query($YuliimportDB, "SET lc_time_names= 'es_BO' ");

$idUser     =    $_SESSION['idUser'];
$ConsltaUser =    mysqli_query($MySQLi, "SELECT * FROM Usuarios WHERE idUser='$idUser' ");
$datosUser     =    mysqli_fetch_assoc($ConsltaUser);
$miCiudad     =    $datosUser['Ciudad'];
mysqli_close($MySQLi);
error_reporting(0);

if ($_SESSION['Rango']) { ?>
    <!DOCTYPE html>
    <html lang="es">

    <head>
        <title>FISCALES YULIIMPORT</title>
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
                <?php
                if (isset($_POST['inicio'])) {
                    $Inicio = $_POST['inicio'];
                    $Fin = $_POST['fin'];
                } else {
                    $Inicio = $startBusqueda; //startbuskeda = 1 del mes
                    $Fin = $fecha; //fecha = hoy
                }
                ?>
                <!-- 	TABLA PRODUCTO	 -->
                <div class="row tableProductos">
                    <div class="col-md-12">
                        <div class="panel panel-success">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <span style="text-transform: uppercase;letter-spacing: 1px;font-size: 16px">
                                        &nbsp;&nbsp;
                                        LISTA DE PRODUCTOS FISCALES YULIIMPORT
                                    </span>
                                </h4>
                            </div>
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col mt-11">
                                        <div class="divBuscarSubidaSistema">
                                            <button class="btn btn-primary BuscarSubidaSistema"><i class="fa fa-search">
                                                    Buscar</i></button>&nbsp;&nbsp;
                                        </div>
                                        <div class="input-group input-daterange divFecha d-none">
                                            <form action="?root=productosFiscalesYuliimport" method="post">
                                                <input required="" type="date" name="inicio" value="<?php echo $Inicio ?>">
                                                <input required="" type="date" name="fin" value="<?php echo $Fin ?>">
                                                <input type="submit" class="btn btn-xs btn-primary" value="BUSCAR">
                                            </form>

                                        </div>
                                    </div>

                                </div>
                                <br>
                                <table id="data-table-buttons" class="table table-striped table-bordered table-td-valign-middle w-100">
                                    <thead>
                                        <tr>
                                            <th width="3%" class="text-center">N&ordm;</th>
                                            <th width="10%" class="text-center">IdProducto</th>
                                            <th width="10%" class="text-center">FECHA POLIZA</th>
                                            <th width="10%" class="text-center">CODIGO</th>
                                            <th width="20%" class="text-center">DETALLE</th>
                                            <th width="10%" class="text-center btn-success">SALDO FISICO <br>(ACTUAL)</th>

                                            <th width="5%" class="text-center btn-primary">SaldoFisicoInicial
                                                <br><?php echo $Inicio; ?>
                                            </th>
                                            <th width="5%" class="text-center btn-primary">SaldoFisicoFinal
                                                <br><?php echo $Fin; ?>
                                            </th>
                                            <th width="7%" class="text-center btn-primary">TotalExtraido
                                                <br><?php echo $Inicio; ?><br> <?php echo ($Fin); ?>
                                            </th>

                                            <th width="20%" class="text-center">C/U PARA FACTURAR MINIMO</th>
                                            <th width="20%" class="text-center">IMPORTES PARA FACTURAR</th>
                                            <th width="20%" class="text-center">FechaSubida<br>AlSistema</th>
                                            <!-- <th width="7%" class="text-center">Acciones</th> -->

                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $query = "SELECT * FROM productos_fiscales ORDER BY idProducto ASC";
                                        $queryProductos    =    mysqli_query($YuliimportDB, $query);

                                        $Num = 1;

                                        $GranTotalSaldoFisico = 0;

                                        $GranTotalInicial = 0;
                                        $GranTotalFinal = 0;

                                        $GranTotalMinuendo = 0;
                                        $GranTotalSustraendo = 0;
                                        $GrantTotalExtraendo = 0;


                                        while ($dataProductos = mysqli_fetch_assoc($queryProductos)) {
                                            $idProducto = $dataProductos['idProducto'];

                                        ?>
                                            <tr class="odd gradeX">
                                                <td class="text-center"><?php echo $Num; ?></td>
                                                <td class="text-center"> <?php echo $dataProductos['idProducto']; ?> </td>
                                                <td class="text-center"> <?php echo $dataProductos['fecha_poliza']; ?> </td>
                                                <td class="text-center"> <?php echo $dataProductos['codigo']; ?> </td>
                                                <td class=""> <?php
                                                                echo $dataProductos['detalle']; ?> </td>
                                                <td class="text-center">
                                                    <?php

                                                    echo $dataProductos['saldo_fisico'];
                                                    $GranTotalSaldoFisico = $GranTotalSaldoFisico + $dataProductos['saldo_fisico'];
                                                    ?>

                                                </td>

                                                <td class="text-center">
                                                    <?php
                                                    //inicial producto su stock mas antiguo con rango de fechas
                                                    $queryStockMasAntiguo =    mysqli_query(
                                                        $YuliimportDB,
                                                        "SELECT
						            	inicial
						            	FROM
						            	historial_stock_productos_fiscales
						            	WHERE
						            	idProducto = '$idProducto' AND dateEmission =(
						            	SELECT
						            		MIN(dateEmission)
						            	FROM
						            		historial_stock_productos_fiscales
						            	WHERE
						            		idProducto = '$idProducto' 
						            		AND(DATE_FORMAT(dateEmission, '%Y-%m-%d') BETWEEN '$Inicio' AND '$Fin'))"
                                                    );

                                                    $dataStockMasAntiguo    =    mysqli_fetch_assoc($queryStockMasAntiguo);


                                                    if ($dataStockMasAntiguo['inicial'] == null) { //no entro a historial
                                                        //1er caso tomamos el primer inicio de su derecha 
                                                        $queryStockMasAntiguo =    mysqli_query(
                                                            $YuliimportDB,
                                                            "SELECT
                                            inicial
                                            FROM
                                            historial_stock_productos_fiscales
                                            WHERE
                                            idProducto = '$idProducto' AND dateEmission =(
                                            SELECT
                                                MIN(dateEmission)
                                            FROM
                                                historial_stock_productos_fiscales
                                            WHERE
                                                idProducto = '$idProducto' 
                                                AND(DATE_FORMAT(dateEmission, '%Y-%m-%d') BETWEEN '$Inicio' AND '2040-01-01'))"
                                                        );

                                                        $dataStockMasAntiguo    =    mysqli_fetch_assoc($queryStockMasAntiguo);
                                                    }
                                                    if ($dataStockMasAntiguo['inicial'] == null) {
                                                        //2DO CASO sigue sin existir tomamos de su izquierda el max final mas cercano que sera nuestro inicial
                                                        $queryStockMasAntiguo =    mysqli_query(
                                                            $YuliimportDB,
                                                            "SELECT
                                            final as 'inicial'
                                            FROM
                                            historial_stock_productos_fiscales
                                            WHERE
                                            idProducto = '$idProducto' AND dateEmission =(
                                            SELECT
                                                MAX(dateEmission)
                                            FROM
                                                historial_stock_productos_fiscales
                                            WHERE
                                                idProducto = '$idProducto' 
                                                AND(DATE_FORMAT(dateEmission, '%Y-%m-%d') BETWEEN  '2000-01-01' AND '$Inicio'))"
                                                        );
                                                        $dataStockMasAntiguo    =    mysqli_fetch_assoc($queryStockMasAntiguo);
                                                    }
                                                    if ($dataStockMasAntiguo['inicial'] == null) {
                                                        //3er caso SIGUE SIN EXISTIR nunca de los nunca entro al historial TOMAMOS EL STOCK NOMAS
                                                        $GranTotalInicial = $GranTotalInicial + $dataProductos['saldo_fisico']; //tomamos su stock
                                                        echo $dataProductos['saldo_fisico'];
                                                        $minuendo = $dataProductos['saldo_fisico'];
                                                    }
                                                    if ($dataStockMasAntiguo['inicial'] != null) {
                                                        $GranTotalInicial = $GranTotalInicial + $dataStockMasAntiguo['inicial']; //si entro a historial
                                                        echo $dataStockMasAntiguo['inicial']; //tomamos su valor mas antiguo
                                                        $minuendo = $dataStockMasAntiguo['inicial'];
                                                    }                                        ?>
                                                </td>

                                                <td class="text-center">
                                                    <?php
                                                    //final producto CON RANGO DE FECHAS
                                                    $queryStockActualFechaFin =    mysqli_query(
                                                        $YuliimportDB,
                                                        "SELECT
						            	final
						            	FROM
						            	historial_stock_productos_fiscales
						            	WHERE
						            	idProducto = '$idProducto' AND dateEmission =(
						            	SELECT
						            		MAX(dateEmission)
						            	FROM
						            		historial_stock_productos_fiscales
						            	WHERE
						            		idProducto = '$idProducto' 
						            		AND(DATE_FORMAT(dateEmission, '%Y-%m-%d') BETWEEN '$Inicio' AND '$Fin'))"
                                                    );

                                                    $dataStockFechaFin    =    mysqli_fetch_assoc($queryStockActualFechaFin);

                                                    if ($dataStockFechaFin['final'] == null) {
                                                        //1er caso tomamos el primer final de su izqauierda 
                                                        $queryStockActualFechaFin =    mysqli_query(
                                                            $YuliimportDB,
                                                            "SELECT
                                            final
                                            FROM
                                            historial_stock_productos_fiscales
                                            WHERE
                                            idProducto = '$idProducto' AND dateEmission =(
                                            SELECT
                                                MAX(dateEmission)
                                            FROM
                                                historial_stock_productos_fiscales
                                            WHERE
                                                idProducto = '$idProducto' 
                                                AND(DATE_FORMAT(dateEmission, '%Y-%m-%d') BETWEEN '2000-01-01' AND '$Fin'))"
                                                        );
                                                        $dataStockFechaFin    =    mysqli_fetch_assoc($queryStockActualFechaFin);
                                                    }
                                                    if ($dataStockFechaFin['final'] == null) {
                                                        //2do caso de la derecha
                                                        $queryStockActualFechaFin =    mysqli_query(
                                                            $YuliimportDB,
                                                            "SELECT
                                            inicial as 'final'
                                            FROM
                                            historial_stock_productos_fiscales
                                            WHERE
                                            idProducto = '$idProducto' AND dateEmission =(
                                            SELECT
                                                MIN(dateEmission)
                                            FROM
                                                historial_stock_productos_fiscales
                                            WHERE
                                                idProducto = '$idProducto' 
                                                AND(DATE_FORMAT(dateEmission, '%Y-%m-%d') BETWEEN '$Fin' AND '2040-01-01' ))"
                                                        );
                                                        $dataStockFechaFin    =    mysqli_fetch_assoc($queryStockActualFechaFin);
                                                    }

                                                    if ($dataStockFechaFin['final'] == null) { //sino existe tomamos del stock producutso fiscale
                                                        $GranTotalFinal = $GranTotalFinal + $dataProductos['saldo_fisico'];
                                                        echo $dataProductos['saldo_fisico'];
                                                        $sustraendo = $dataProductos['saldo_fisico'];
                                                    }
                                                    if ($dataStockFechaFin['final'] != null) {
                                                        $GranTotalFinal = $GranTotalFinal + $dataStockFechaFin['final'];
                                                        echo $dataStockFechaFin['final'];
                                                        $sustraendo = $dataStockFechaFin['final'];
                                                    }

                                                    ?>
                                                </td>
                                                <td class="text-center">
                                                    <?php
                                                    $TotalExtraido = $minuendo - $sustraendo;
                                                    if ($TotalExtraido > 0) {
                                                        echo $TotalExtraido;
                                                    } else {

                                                        $TotalExtraido = 0;
                                                        echo $TotalExtraido;
                                                    }

                                                    $GrantTotalExtraendo = $GrantTotalExtraendo + $TotalExtraido;

                                                    ?>
                                                </td>



                                                <td class="text-center"> <?php echo $dataProductos['c_u_facturar_minimo']; ?>
                                                </td>
                                                <td class="text-center"> <?php echo $dataProductos['importes_para_facturar']; ?>
                                                </td>
                                                <td class="text-center"> <?php echo $dataProductos['fecha_subido_sistema']; ?>
                                                </td>
                                            </tr>
                                            <?php $Num++;  ?>
                                        <?php  }

                                        mysqli_close($YuliimportDB); ?>

                                        <tr>
                                            <th width="10%" class="text-center"><?php echo $Num; ?></th>
                                            <th width="10%" class="text-center"></th>
                                            <th width="10%" class="text-center"></th>
                                            <th width="20%" class="text-center"></th>
                                            <th width="20%" class="text-center">Totales: </th>
                                            <th width="10%" class="text-center btn-success">
                                                <?php echo $GranTotalSaldoFisico ?></th>

                                            <th width="5%" class="text-center btn-primary"><?php echo $GranTotalInicial ?>
                                            </th>
                                            <th width="5%" class="text-center btn-primary"><?php echo $GranTotalFinal ?>
                                            </th>
                                            <th width="7%" class="text-center btn-primary">
                                                <?php echo $GrantTotalExtraendo ?> </th>

                                            <th width="20%" class="text-center"></th>
                                            <th width="20%" class="text-center"></th>
                                            <th width="20%" class="text-center"></th>
                                            <th width="7%" class="text-center"></th>

                                        </tr>

                                    </tbody>
                                </table>

                                <!-- end panel-body -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <a href="javascript:;" class="btn btn-icon btn-circle btn-primary btn-scroll-to-top fade" data-click="scroll-top"><i class="fa fa-angle-up"></i></a>
        <?php include 'php/footer.php'; ?>

        <?php include 'php/script_productos_fiscales.php'; ?>
    </body>

    </html>
<?php 
} 
?>