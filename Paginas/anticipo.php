<?php
$idUser         =    $_SESSION['idUser'];
$ConsltaUser =    mysqli_query($MySQLi, "SELECT * FROM Usuarios WHERE idUser='$idUser' ");
$datosUser     =    mysqli_fetch_assoc($ConsltaUser);
// include 'includes/conexion_yuliimport.php';
$nombreVendedor = $datosUser['Nombres'] . " " . $datosUser['Apellidos'];
$miCiudad     =    $datosUser['Ciudad']; ?>
<!DOCTYPE html>
<html lang="es">

<head>
    <title>POR ANTICIPO</title>
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
    <link href="assets/plugins/select2/dist/css/select2.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/switchery/switchery.css">
</head>

<body><?php include 'php/loader.php'; ?>
    <div id="page-container" class="fade page-sidebar-fixed page-header-fixed"><?php
                                                                                include 'php/top_menu.php';
                                                                                include 'php/left_menu_anticipo.php';
                                                                                include 'functions/buscarVencidad.php'; ?>
        <div id="content" class="content">
            <div class="respuesta"></div><?php
                                            if (isset($_POST['inicio'])) {
                                                $Inicio     = $_POST['inicio'];
                                                $Fin             =    $_POST['fin']; ?>
                <div class="row tableCotizaciones">
                    <div class="col-md-12">
                        <div class="panel panel-inverse">
                            <div class="panel-heading">
                                <h4 class="panel-title">COTIZACIONES POR ANTICIPO DESDE <strong class="text-danger"><?php echo $Inicio ?></strong> HASTA <strong class="text-danger"><?php echo $Fin ?></strong></h4>
                                <div class="panel-heading-btn">
                                    <!-- <button class="btn btn-xs btn-primary Buscar"><i class="fa fa-search"> Buscar</i></button>&nbsp;&nbsp; -->
                                    <!-- <button class="btn btn-xs btn-primary AddNewCotizaBTN">AGREGAR COTIZACION</button>&nbsp;&nbsp;&nbsp; -->
                                    <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
                                    <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-success" data-click="panel-reload"><i class="fa fa-redo"></i></a>
                                    <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>
                                    <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-danger" data-click="panel-remove"><i class="fa fa-times"></i></a>
                                </div>
                            </div>
                            <div class="panel-body">
                                <form data-parsley-validate="true" class="w-75 m-auto" id="buscar" action="?root=anticipo" method="POST">
                                    <div class="row mb-2">
                                        <div class="col text-center">
                                            <label for="fechaInicio">Fecha de inicio</label>
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
                                <table id="data-table-buttons" class="table table-striped table-bordered table-td-valign-middle w-100">
                                    <thead>
                                        <tr class="table-success">
                                            <th width="5%" class="text-center">N&ordm;</th>
                                            <th width="30%" class="text-center">Datos</th>
                                            <th width="65%" class="text-center">Productos</th>
                                            <th width="10%" class="text-center">Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody> <?php $Num =    1;
                                                if ($_SESSION['Rango'] == 2) {
                                                    $consultaCotizacion    =    mysqli_query($MySQLi, "SELECT idCotizacion, Code, Clave, idUser, idCliente, Forma_Pago, DATE_FORMAT(FinFecha_Oferta, '%d de %M, %Y') AS FinFecha_Oferta, Dias_Entrega, Comentarios, Sucursal, DATE_FORMAT(Fecha, '%d de %M, %Y') AS Fecha, DATE_FORMAT(Hora, '%h:%i:%s %p') AS Hora, DATE_FORMAT(Entregada, '%d de %M, %Y') AS Entregada FROM Cotizaciones WHERE Estado=5 AND Fecha BETWEEN '$Inicio'AND'$Fin' ORDER BY Entregada DESC");
                                                } else {
                                                    $consultaCotizacion    =    mysqli_query($MySQLi, "SELECT idCotizacion, Code, Clave, idUser, idCliente, Forma_Pago, DATE_FORMAT(FinFecha_Oferta, '%d de %M, %Y') AS FinFecha_Oferta, Dias_Entrega, Comentarios, Sucursal, DATE_FORMAT(Fecha, '%d de %M, %Y') AS Fecha, DATE_FORMAT(Hora, '%h:%i:%s %p') AS Hora, DATE_FORMAT(Entregada, '%d de %M, %Y') AS Entregada FROM Cotizaciones WHERE idUser='$idUser'AND Estado=5 AND Fecha BETWEEN '$Inicio'AND'$Fin' ORDER BY Entregada DESC");
                                                }
                                                while ($dataCotizacion = mysqli_fetch_assoc($consultaCotizacion)) {
                                                    $IDCotiza     =    $dataCotizacion['idCotizacion']; ?>
                                            <tr class="odd gradeX">
                                                <td class="text-center"><?php echo $Num; ?></td>
                                                <?php
                                                    $idCliente         =    $dataCotizacion['idCliente'];
                                                    $queryCliente    =    mysqli_query($MySQLi, "SELECT * FROM Clientes WHERE idCliente='$idCliente' ");
                                                    $dataCliente     =    mysqli_fetch_assoc($queryCliente);
                                                    $idVendedor     =    $dataCotizacion['idUser'];
                                                    $queryVendedor    =    mysqli_query($MySQLi, "SELECT Nombres, Apellidos, Ciudad, idUser FROM Usuarios WHERE idUser='$idVendedor' ");
                                                    $dataVendedor     =    mysqli_fetch_assoc($queryVendedor); ?>
                                                <td style="font-size: 10px">
                                                    <table class="table table-success">
                                                        <tr class="table-info">
                                                            <td>CODIGO:</td>
                                                            <th><?php echo $dataCotizacion['Code'] ?></th>
                                                        </tr>
                                                        <tr>
                                                            <td>Cliente:</td>
                                                            <th><?php echo $dataCliente['Nombres'] . " " . $dataCliente['Apellidos'] ?>
                                                            </th>
                                                        </tr><?php
                                                                if ($dataCliente['Empresa'] != '') { ?>
                                                            <tr>
                                                                <td>Empresa:</td>
                                                                <th><?php echo $dataCliente['Empresa'] ?></th>
                                                            </tr><?php }
                                                                if ($dataCliente['Correo'] != '') { ?>
                                                            <tr>
                                                                <td>Correo:</td>
                                                                <th><?php echo $dataCliente['Correo'] ?></th>
                                                            </tr><?php } ?>
                                                        <tr>
                                                            <td>Forma de Pago:</td>
                                                            <th><?php echo $dataCotizacion['Forma_Pago'] ?></th>
                                                        </tr>
                                                        <tr>
                                                            <td>Teléfono:</td>
                                                            <th><?php echo $dataCliente['Celular'] ?></th>
                                                        </tr>
                                                        <tr>
                                                            <td>Vendedor:</td>
                                                            <th><?php echo $dataVendedor['Nombres'] . " " . $dataVendedor['Apellidos'] ?>
                                                            </th>
                                                        </tr>
                                                        <tr>
                                                            <td>Fecha:</td>
                                                            <th><?php echo $dataCotizacion['Fecha'] ?></th>
                                                        </tr>
                                                        <tr>
                                                            <td>Hora:</td>
                                                            <th><?php echo $dataCotizacion['Hora'] ?></th>
                                                        </tr>
                                                        <tr>
                                                            <th></th>
                                                        </tr>
                                                    </table>
                                                    <div class="row mt-2">

                                                        <div class="col text-center f-s-20">

                                                            <!-- <button class="btn btn-primary mt-1 facturaComputarizada"
                                                        id="<?php //echo $IDCotiza 
                                                            ?>"
                                                        title="EMITIR FACTURA ELECTRONICA idCotizacion=<?php //echo $IDCotiza 
                                                                                                        ?>"><i
                                                            class="fas fa-file-invoice" style="font-size: 25px"></i>
                                                    </button> -->
                                                            &nbsp;&nbsp;
                                                            <?php $idCotizacion = $IDCotiza; ?>
                                                            <button class="btn btn-info btn-xs mt-1 btnFacturaModalCargarDatos" title="EMITIR FACTURA-<?php echo $idCotizacion; ?>" data-toggle="modal" data-target="#modalFacturaFR" data-dismiss="modal" id="<?php echo $idCotizacion; ?>"><i class="fas fa-file-invoice" style="font-size: 25px"></i>
                                                            </button>
                                                        </div>

                                                    </div>

                                                </td>
                                                <td style="font-size: 12px;">
                                                    <div class="text-center mt-1 mb-1" style="margin-top: -5%">OFERTA VÁLIDA
                                                        HASTE EL: <span class="text-danger" style="text-transform: uppercase;"><?php echo $dataCotizacion['FinFecha_Oferta'] ?></span>
                                                    </div>
                                                    <table class="table table-success table-striped table-bordered table-td-valign-middle w-100">
                                                        <thead>
                                                            <tr class=" table-info">
                                                                <td width="5%" class="text-center">Cant</td>
                                                                <td width="50%" class="text-center">Descripción</td>
                                                                <td width="15%" class="text-center">Precio<br>Lista</td>
                                                                <td width="15%" class="text-center">Precio<br>Oferta</td>
                                                                <td width="15%" class="text-center">Total</td>
                                                            </tr>
                                                        </thead>
                                                        <tbody><?php
                                                                /*	BUSCAMOS EN LA CLAVE TEMPORAL LOS DATOS DEL PRODUCTO	*/
                                                                $ClaveTemp     =    $dataCotizacion['Clave'];
                                                                $sqlCotiza    =    mysqli_query($MySQLi, "SELECT * FROM ClaveTemporal WHERE Clave='$ClaveTemp' ");
                                                                while ($dataProdTemp = mysqli_fetch_assoc($sqlCotiza)) { ?>
                                                                <tr>
                                                                    <td class="text-center"><?php echo $dataProdTemp['Cantidad'] ?>
                                                                    </td><?php
                                                                            $idProducto =    $dataProdTemp['idProducto'];
                                                                            $queryProd     =    mysqli_query($MySQLi, "SELECT * FROM Productos WHERE idProducto='$idProducto' ");
                                                                            $dataProducto =    mysqli_fetch_assoc($queryProd); ?>
                                                                    <td><?php echo $dataProducto['Producto'] . " / " . $dataProducto['Marca'] . " / " . $dataProducto['Modelo'] ?>
                                                                    </td>
                                                                    <td class="text-right">
                                                                        $&nbsp;<?php echo number_format($dataProdTemp['PrecioLista'], 2) ?>
                                                                    </td>
                                                                    <td class="text-right">
                                                                        $&nbsp;<?php echo number_format($dataProdTemp['PrecioOferta'], 2) ?>
                                                                    </td>
                                                                    <td class="text-right">
                                                                        $&nbsp;<?php echo number_format($dataProdTemp['PrecioOferta'] * $dataProdTemp['Cantidad'], 2) ?>
                                                                    </td>
                                                                </tr><?php } ?>
                                                            <!-- 	AQUI SE MUESTRA EL TOTAL EN $USD DE LA COTIZACION	 -->
                                                            <tr>
                                                                <td colspan="3"></td>
                                                                <td class="text-center">TOTAL USD</td><?php
                                                                                                        $sql_Cotiza    =    mysqli_query($MySQLi, "SELECT SUM(Cantidad*PrecioOferta)AS TOTAL FROM ClaveTemporal WHERE Clave='$ClaveTemp' ");
                                                                                                        $datasql     =    mysqli_fetch_assoc($sql_Cotiza) ?>
                                                                <td class="text-right">$
                                                                    <?php echo number_format($datasql['TOTAL'], 2) ?></td>
                                                            </tr><?php
                                                                    /*	BUSCAMOS SI EL ABONO FUE EN Bs	*/
                                                                    $findAbono     =    mysqli_query($MySQLi, "SELECT Moneda, Total FROM Abonos WHERE idCotizacion='$IDCotiza' LIMIT 0,1 ") or die(mysqli_error($MySQLi) . "<br>Error en la línea: " . __LINE__);
                                                                    $dataFind    =    mysqli_fetch_assoc($findAbono);
                                                                    if ($dataFind['Moneda'] == 'Bs') { ?>
                                                                <tr>
                                                                    <td colspan="3"></td>
                                                                    <td class="text-center">TOTAL Bs</td>
                                                                    <td class="text-right">Bs
                                                                        <?php echo number_format($dataFind['Total'], 2) ?></td>
                                                                </tr><?php
                                                                    } ?>
                                                            <!-- 	AQUI ESTAN LAS FUNCIONES DE DESCARGAR EL PDF (RECIBO)	 -->
                                                            <?php
                                                            /*	BUSCAMOS LOS DATOS DEL PRIMER ABONO 	*/
                                                            $sqlAbono     =    mysqli_query($MySQLi, "SELECT * FROM Abonos WHERE idCotizacion='$IDCotiza' ") or die(mysqli_error($MySQLi));
                                                            $CantRecibos =    mysqli_num_rows($sqlAbono);
                                                            while ($dataRecibo    =    mysqli_fetch_assoc($sqlAbono)) {
                                                                $idRecibo         =    $dataRecibo['idRecibo'];
                                                                $idAbono         =    $dataRecibo['idAbono'];
                                                                echo '
															<tr>
																<td colspan="3" class="text-right">
																	<a href="Reportes/pdf.php?idRecibo=' . $idRecibo . '">
																		<button title="Descargar este recibo # ' . $idRecibo . '" class="btn btn-xs btn-danger">
																			<i class="fas fa-download f-s-16"></i>
																		</button>
																	</a>&nbsp;&nbsp;';
                                                                if ($_SESSION['Rango'] == '2') {
                                                                    if ($CantRecibos == 1) {
                                                                        echo '
																			<a href="#" title="Editar abono (idRecibo: ' . $idRecibo . ')">
																				<span class="text-danger editarAbono" id="' . $idRecibo . '">Editar Abono</span>
																			</a>';
                                                                    }
                                                                }
                                                                echo '
																</td>
																<td class="text-center">Abonó</td>';
                                                                if ($dataRecibo['Moneda'] == 'USD') {
                                                                    $Abono     =    $dataRecibo['anticipoUSD'];
                                                                    echo '<td class="text-right">$ ' . number_format($Abono, 2) . '</td>';
                                                                } else {
                                                                    $Abono     =    $dataRecibo['porAnticipo'];
                                                                    echo '<td class="text-right">Bs ' . number_format($Abono, 2) . '</td>';
                                                                }
                                                                echo '
															</tr>';
                                                            } ?>
                                                            <tr>
                                                                <td colspan="3" class="text-right"><?php
                                                                                                    if ($_SESSION['Rango'] == '2') {
                                                                                                        if ($CantRecibos > 1) {
                                                                                                            $AbonoEdit    =    $CantRecibos - 1;
                                                                                                            $buscarAbono =    mysqli_query($MySQLi, "SELECT * FROM Abonos WHERE idCotizacion='$IDCotiza' LIMIT $AbonoEdit,1 ") or die(mysqli_error($MySQLi) . "<br>Error en la línea: " . __LINE__);
                                                                                                            $AbonoEditar =    mysqli_fetch_assoc($buscarAbono);
                                                                                                            $thisAbonoID =    $AbonoEditar['idAbono'];
                                                                                                            $thisReciboID =    $AbonoEditar['idRecibo'];
                                                                                                            echo '
																		<a href="#" title="Editar último abono (idAbono: ' . $thisReciboID . ')">
																			<span class="text-danger ultimoAbono" id="' . $thisReciboID . '">Editar último Abono</span>
																		</a>';
                                                                                                        }
                                                                                                    } ?>
                                                                </td>
                                                                <td class="text-center text-danger"><strong>RESTA</strong></td><?php
                                                                                                                                $search     =    mysqli_query($MySQLi, "SELECT * FROM Abonos WHERE idCotizacion='$IDCotiza' ");
                                                                                                                                $respSearch =    mysqli_num_rows($search);
                                                                                                                                if ($respSearch > 0) {
                                                                                                                                    $dataSearch =    mysqli_fetch_assoc($search);
                                                                                                                                    if ($dataSearch['Moneda'] == 'USD') {
                                                                                                                                        $consult_Abono     =    mysqli_query($MySQLi, "SELECT SUM(anticipoUSD)AS anticipoUSD, TotalUSD FROM Abonos WHERE idCotizacion='$IDCotiza' ") or die(mysqli_error($MySQLi));
                                                                                                                                        $dataAbono         =    mysqli_fetch_assoc($consult_Abono);
                                                                                                                                        $Total             =    $dataAbono['TotalUSD'];
                                                                                                                                        $Abono             =    $dataAbono['anticipoUSD'];
                                                                                                                                        $Resta             =    $Total - $Abono; ?>
                                                                        <td class="text-right">
                                                                            $ <?php echo number_format($Resta, 2) ?>
                                                                        </td><?php
                                                                                                                                    } else {
                                                                                                                                        $consult_Abono     =    mysqli_query($MySQLi, "SELECT SUM(porAnticipo)AS porAnticipo, Total FROM Abonos WHERE idCotizacion='$IDCotiza' ") or die(mysqli_error($MySQLi));
                                                                                                                                        $dataAbono         =    mysqli_fetch_assoc($consult_Abono);
                                                                                                                                        $Total             =    $dataAbono['Total'];
                                                                                                                                        $Abono             =    $dataAbono['porAnticipo'];
                                                                                                                                        $Resta             =    $Total - $Abono; ?>
                                                                        <td class="text-right">
                                                                            Bs <?php echo number_format($Resta, 2) ?>
                                                                        </td><?php
                                                                                                                                    }
                                                                                                                                } else {
                                                                                                                                    echo '<td class="text-center text-danger">Error</td>';
                                                                                                                                } ?>
                                                            </tr>
                                                        </tbody>
                                                    </table><?php
                                                            $queryRecibos     =    mysqli_query($MySQLi, "SELECT * FROM Abonos WHERE idCotizacion='$IDCotiza' ");
                                                            $respConsulta    =    mysqli_num_rows($queryRecibos);
                                                            $Busqueda         =    $respConsulta - 1;
                                                            $buscarRecibo    =    mysqli_query($MySQLi, "SELECT * FROM Abonos WHERE idCotizacion='$IDCotiza' LIMIT $Busqueda,1 ") or die(mysql_error($MySQLi));
                                                            $dataBusqueda     =    mysqli_fetch_assoc($buscarRecibo);
                                                            $IDAbono         =    $dataBusqueda['idAbono'];
                                                            //$ultminoSaldo 	=	$dataBusqueda['SaldoActual'];
                                                            //echo $ultminoSaldo; 
                                                            ?>
                                                    <div class="row mt-1">
                                                        <div class="col text-center">
                                                            <button class="btn btn-xs btn-primary plusABONO" title="Agregar abono (idCotizacion: <?php echo $IDCotiza ?>)" id="<?php echo $IDCotiza ?>">
                                                                <i class="fa fa-dollar-sign"></i>&nbsp;&nbsp;AGREGAR
                                                                ABONO&nbsp;&nbsp;<i class="fa fa-dollar-sign"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </td><?php
                                                        if ($_SESSION['Rango'] == '2') { ?>
                                                    <td class="text-center"><button class="btn btn-danger btn-xs borrarVentaAnticipo" title="Borrar venta por anticipo(<?php echo $IDCotiza ?>)" id="<?php echo $IDCotiza ?>"><i class="fas fa-trash-alt"></i></button>
                                                    </td><?php
                                                        } ?>
                                            </tr><?php $Num++;
                                                } ?>
                                    </tbody>
                                </table>
                            </div>
                            <!-- end panel-body -->
                        </div>
                    </div>
                </div><?php
                                            } else { ?>
                <div class="row tableCotizaciones">
                    <div class="col-md-12">
                        <div class="panel panel-inverse">
                            <div class="panel-heading">
                                <h4 class="panel-title">COTIZACIONES POR ANTICIPO
                                    <strong><?php echo strtoupper($mes) ?></strong>
                                </h4>
                                <div class="panel-heading-btn">
                                    <button class="btn btn-xs btn-primary Buscar"><i class="fa fa-search">
                                            Buscar</i></button>&nbsp;&nbsp;
                                    <!-- <button class="btn btn-xs btn-primary AddNewCotizaBTN">AGREGAR COTIZACION</button>&nbsp;&nbsp;&nbsp; -->
                                    <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
                                    <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-success" data-click="panel-reload"><i class="fa fa-redo"></i></a>
                                    <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>
                                    <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-danger" data-click="panel-remove"><i class="fa fa-times"></i></a>
                                </div>
                            </div>
                            <div class="panel-body">
                                <form data-parsley-validate="true" class="w-75 m-auto d-none" id="buscar" action="?root=anticipo" method="POST">
                                    <div class="row mb-2">
                                        <div class="col text-center">
                                            <label for="fechaInicio">Fecha de inicio</label>
                                            <input type="date" name="inicio" id="fechaInicio" class="form-control text-center" value="<?php echo $startBusqueda ?>" data-parsley-required="true">
                                        </div>
                                        <div class="col text-center">
                                            <label for="fechaFin">Fecha final</label>
                                            <input type="date" name="fin" id="fechaFin" class="form-control text-center" value="<?php echo $fecha ?>" data-parsley-required="true">
                                        </div>
                                        <div class="col">
                                            <label for="buscar">&nbsp;&nbsp;&nbsp;</label>
                                            <button type="submit" class="form-control btn btn-xs btn-primary ">Buscar
                                                &nbsp;<i class="fas fa-spinner fa-pulse d-none btn-Buscar"></i></button>
                                        </div>
                                    </div>
                                </form>
                                <table id="data-table-buttons" class="table table-striped table-bordered table-td-valign-middle w-100">
                                    <thead>
                                        <tr class="table-success">
                                            <th width="5%" class="text-center">N&ordm;</th>
                                            <th width="30%" class="text-center">Datos</th>
                                            <th width="65%" class="text-center">Productos</th><?php
                                                                                                if ($_SESSION['Rango'] == '2') { ?>
                                                <th width="10%" class="text-center">Acciones</th><?php
                                                                                                } ?>
                                        </tr>
                                    </thead>
                                    <tbody> <?php $Num =    1;
                                                if ($_SESSION['Rango'] == 2) {
                                                    $consultaCotizacion    =    mysqli_query($MySQLi, "SELECT idCotizacion, Code, Clave, idUser, idCliente, Forma_Pago, DATE_FORMAT(FinFecha_Oferta, '%d de %M, %Y') AS FinFecha_Oferta, Dias_Entrega, Comentarios, Sucursal, DATE_FORMAT(Fecha, '%d de %M, %Y') AS Fecha, DATE_FORMAT(Hora, '%h:%i:%s %p') AS Hora, DATE_FORMAT(Entregada, '%d de %M, %Y') AS Entregada FROM Cotizaciones WHERE Estado=5 AND Fecha BETWEEN '$startBusqueda'AND'$fecha' ORDER BY Entregada DESC");
                                                } else {
                                                    $consultaCotizacion    =    mysqli_query($MySQLi, "SELECT idCotizacion, Code, Clave, idUser, idCliente, Forma_Pago, DATE_FORMAT(FinFecha_Oferta, '%d de %M, %Y') AS FinFecha_Oferta, Dias_Entrega, Comentarios, Sucursal, DATE_FORMAT(Fecha, '%d de %M, %Y') AS Fecha, DATE_FORMAT(Hora, '%h:%i:%s %p') AS Hora, DATE_FORMAT(Entregada, '%d de %M, %Y') AS Entregada FROM Cotizaciones WHERE idUser='$idUser'AND Estado=5 AND Fecha BETWEEN '$startBusqueda'AND'$fecha' ORDER BY Entregada DESC");
                                                }
                                                while ($dataCotizacion = mysqli_fetch_assoc($consultaCotizacion)) {
                                                    $IDCotiza     =    $dataCotizacion['idCotizacion']; ?>
                                            <tr class="odd gradeX">
                                                <td class="text-center"><?php echo $Num; ?></td><?php
                                                                                                $idCliente         =    $dataCotizacion['idCliente'];
                                                                                                $queryCliente    =    mysqli_query($MySQLi, "SELECT * FROM Clientes WHERE idCliente='$idCliente' ");
                                                                                                $dataCliente     =    mysqli_fetch_assoc($queryCliente);
                                                                                                $idVendedor     =    $dataCotizacion['idUser'];
                                                                                                $queryVendedor    =    mysqli_query($MySQLi, "SELECT Nombres, Apellidos, Ciudad, idUser FROM Usuarios WHERE idUser='$idVendedor' ");
                                                                                                $dataVendedor     =    mysqli_fetch_assoc($queryVendedor); ?>
                                                <td style="font-size: 10px">
                                                    <table class="table table-success">
                                                        <tr class="table-info">
                                                            <td>CODIGO:</td>
                                                            <th><?php echo $dataCotizacion['Code'] ?></th>
                                                        </tr>
                                                        <tr>
                                                            <td>Cliente:</td>
                                                            <th><?php echo $dataCliente['Nombres'] . " " . $dataCliente['Apellidos'] ?>
                                                            </th>
                                                        </tr><?php
                                                                if ($dataCliente['Empresa'] != '') { ?>
                                                            <tr>
                                                                <td>Empresa:</td>
                                                                <th><?php echo $dataCliente['Empresa'] ?></th>
                                                            </tr><?php }
                                                                if ($dataCliente['Correo'] != '') { ?>
                                                            <tr>
                                                                <td>Correo:</td>
                                                                <th><?php echo $dataCliente['Correo'] ?></th>
                                                            </tr><?php } ?>
                                                        <tr>
                                                            <td>Forma de Pago:</td>
                                                            <th><?php echo $dataCotizacion['Forma_Pago'] ?></th>
                                                        </tr>
                                                        <tr>
                                                            <td>Teléfono:</td>
                                                            <th><?php echo $dataCliente['Celular'] ?></th>
                                                        </tr>
                                                        <tr>
                                                            <td>Vendedor:</td>
                                                            <th><?php echo $dataVendedor['Nombres'] . " " . $dataVendedor['Apellidos'] ?>
                                                            </th>
                                                        </tr>
                                                        <tr>
                                                            <td>Fecha:</td>
                                                            <th><?php echo $dataCotizacion['Fecha'] ?></th>
                                                        </tr>
                                                        <tr>
                                                            <td>Hora:</td>
                                                            <th><?php echo $dataCotizacion['Hora'] ?></th>
                                                        </tr>
                                                        <tr>
                                                            <th></th>
                                                        </tr>
                                                    </table>
                                                    <div class="row mt-2">

                                                        <div class="col text-center f-s-20">

                                                            <!-- <button class="btn btn-primary mt-1 facturaComputarizada"
                                                        id="<?php //echo $IDCotiza 
                                                            ?>"
                                                        title="EMITIR FACTURA ELECTRONICA idCotizacion=<?php //echo $IDCotiza 
                                                                                                        ?>"><i
                                                            class="fas fa-file-invoice" style="font-size: 25px"></i>
                                                    </button> -->
                                                            &nbsp;&nbsp;
                                                            <?php $idCotizacion = $IDCotiza; ?>
                                                            <button class="btn btn-info btn-xs mt-1 btnFacturaModalCargarDatos" title="EMITIR FACTURA-<?php echo $idCotizacion; ?>" data-toggle="modal" data-target="#modalFacturaFR" data-dismiss="modal" id="<?php echo $idCotizacion; ?>"><i class="fas fa-file-invoice" style="font-size: 25px"></i>
                                                            </button>
                                                        </div>

                                                    </div>

                                                </td>
                                                <td style="font-size: 12px;">
                                                    <div class="text-center mt-1 mb-1" style="margin-top: -5%">OFERTA VÁLIDA
                                                        HASTE EL: <span class="text-danger" style="text-transform: uppercase;"><?php echo $dataCotizacion['FinFecha_Oferta'] ?></span>
                                                    </div>
                                                    <table class="table table-success table-striped table-bordered table-td-valign-middle w-100">
                                                        <thead>
                                                            <tr class=" table-info">
                                                                <td width="5%" class="text-center">Cant</td>
                                                                <td width="50%" class="text-center">Descripción</td>
                                                                <td width="15%" class="text-center">Precio<br>Lista</td>
                                                                <td width="15%" class="text-center">Precio<br>Oferta</td>
                                                                <td width="15%" class="text-center">Total</td>
                                                            </tr>
                                                        </thead>
                                                        <tbody><?php
                                                                /*	BUSCAMOS EN LA CLAVE TEMPORAL LOS DATOS DEL PRODUCTO	*/
                                                                $ClaveTemp     =    $dataCotizacion['Clave'];
                                                                $sqlCotiza    =    mysqli_query($MySQLi, "SELECT * FROM ClaveTemporal WHERE Clave='$ClaveTemp' ");
                                                                while ($dataProdTemp = mysqli_fetch_assoc($sqlCotiza)) { ?>
                                                                <tr>
                                                                    <td class="text-center"><?php echo $dataProdTemp['Cantidad'] ?>
                                                                    </td><?php
                                                                            $idProducto =    $dataProdTemp['idProducto'];
                                                                            $queryProd     =    mysqli_query($MySQLi, "SELECT * FROM Productos WHERE idProducto='$idProducto' ");
                                                                            $dataProducto =    mysqli_fetch_assoc($queryProd); ?>
                                                                    <td><?php echo $dataProducto['Producto'] . " / " . $dataProducto['Marca'] . " / " . $dataProducto['Modelo'] ?>
                                                                    </td>
                                                                    <td class="text-right">
                                                                        $&nbsp;<?php echo number_format($dataProdTemp['PrecioLista'], 2) ?>
                                                                    </td>
                                                                    <td class="text-right">
                                                                        $&nbsp;<?php echo number_format($dataProdTemp['PrecioOferta'], 2) ?>
                                                                    </td>
                                                                    <td class="text-right">
                                                                        $&nbsp;<?php echo number_format($dataProdTemp['PrecioOferta'] * $dataProdTemp['Cantidad'], 2) ?>
                                                                    </td>
                                                                </tr><?php } ?>
                                                            <!-- 	AQUI SE MUESTRA EL TOTAL EN $USD DE LA COTIZACION	 -->
                                                            <tr>
                                                                <td colspan="3"></td>
                                                                <td class="text-center">TOTAL USD</td><?php
                                                                                                        $sql_Cotiza    =    mysqli_query($MySQLi, "SELECT SUM(Cantidad*PrecioOferta)AS TOTAL FROM ClaveTemporal WHERE Clave='$ClaveTemp' ");
                                                                                                        $datasql     =    mysqli_fetch_assoc($sql_Cotiza) ?>
                                                                <td class="text-right">$
                                                                    <?php echo number_format($datasql['TOTAL'], 2) ?></td>
                                                            </tr><?php
                                                                    /*	BUSCAMOS SI EL ABONO FUE EN Bs	*/
                                                                    $findAbono     =    mysqli_query($MySQLi, "SELECT Moneda, Total FROM Abonos WHERE idCotizacion='$IDCotiza' LIMIT 0,1 ") or die(mysqli_error($MySQLi) . "<br>Error en la línea: " . __LINE__);
                                                                    $dataFind    =    mysqli_fetch_assoc($findAbono);
                                                                    if ($dataFind['Moneda'] == 'Bs') { ?>
                                                                <tr>
                                                                    <td colspan="3"></td>
                                                                    <td class="text-center">TOTAL Bs</td>
                                                                    <td class="text-right">Bs
                                                                        <?php echo number_format($dataFind['Total'], 2) ?></td>
                                                                </tr><?php
                                                                    } ?>
                                                            <!-- 	AQUI ESTAN LAS FUNCIONES DE DESCARGAR EL PDF (RECIBO)	 -->
                                                            <?php
                                                            /*	BUSCAMOS LOS DATOS DEL PRIMER ABONO 	*/
                                                            $sqlAbono     =    mysqli_query($MySQLi, "SELECT * FROM Abonos WHERE idCotizacion='$IDCotiza' ") or die(mysqli_error($MySQLi));
                                                            $CantRecibos =    mysqli_num_rows($sqlAbono);
                                                            while ($dataRecibo    =    mysqli_fetch_assoc($sqlAbono)) {
                                                                $idRecibo         =    $dataRecibo['idRecibo'];
                                                                $idAbono         =    $dataRecibo['idAbono'];
                                                                echo '
															<tr>
																<td colspan="3" class="text-right">
																	<a href="Reportes/pdf.php?idRecibo=' . $idRecibo . '">
																		<button title="Descargar este recibo # ' . $idRecibo . '" class="btn btn-xs btn-danger">
																			<i class="fas fa-download f-s-16"></i>
																		</button>
																	</a>&nbsp;&nbsp;';
                                                                if ($_SESSION['Rango'] == '2') {
                                                                    if ($CantRecibos == 1) {
                                                                        echo '
																			<a href="#" title="Editar abono (idRecibo: ' . $idRecibo . ')">
																				<span class="text-danger editarAbono" id="' . $idRecibo . '">Editar Abono</span>
																			</a>';
                                                                    }
                                                                }
                                                                echo '
																</td>
																<td class="text-center">Abonó</td>';
                                                                if ($dataRecibo['Moneda'] == 'USD') {
                                                                    $Abono     =    $dataRecibo['anticipoUSD'];
                                                                    echo '<td class="text-right">$ ' . number_format($Abono, 2) . '</td>';
                                                                } else {
                                                                    $Abono     =    $dataRecibo['porAnticipo'];
                                                                    echo '<td class="text-right">Bs ' . number_format($Abono, 2) . '</td>';
                                                                }
                                                                echo '
															</tr>';
                                                            } ?>
                                                            <tr>
                                                                <td colspan="3" class="text-right"><?php
                                                                                                    if ($_SESSION['Rango'] == '2') {
                                                                                                        if ($CantRecibos > 1) {
                                                                                                            $AbonoEdit    =    $CantRecibos - 1;
                                                                                                            $buscarAbono =    mysqli_query($MySQLi, "SELECT * FROM Abonos WHERE idCotizacion='$IDCotiza' LIMIT $AbonoEdit,1 ") or die(mysqli_error($MySQLi) . "<br>Error en la línea: " . __LINE__);
                                                                                                            $AbonoEditar =    mysqli_fetch_assoc($buscarAbono);
                                                                                                            $thisAbonoID =    $AbonoEditar['idAbono'];
                                                                                                            $thisReciboID =    $AbonoEditar['idRecibo'];
                                                                                                            echo '
																		<a href="#" title="Editar último abono (idAbono: ' . $thisReciboID . ')">
																			<span class="text-danger ultimoAbono" id="' . $thisReciboID . '">Editar último Abono</span>
																		</a>';
                                                                                                        }
                                                                                                    } ?>
                                                                </td>
                                                                <td class="text-center text-danger"><strong>RESTA</strong></td><?php
                                                                                                                                $search     =    mysqli_query($MySQLi, "SELECT * FROM Abonos WHERE idCotizacion='$IDCotiza' ");
                                                                                                                                $respSearch =    mysqli_num_rows($search);
                                                                                                                                if ($respSearch > 0) {
                                                                                                                                    $dataSearch =    mysqli_fetch_assoc($search);
                                                                                                                                    if ($dataSearch['Moneda'] == 'USD') {
                                                                                                                                        $consult_Abono     =    mysqli_query($MySQLi, "SELECT SUM(anticipoUSD)AS anticipoUSD, TotalUSD FROM Abonos WHERE idCotizacion='$IDCotiza' ") or die(mysqli_error($MySQLi));
                                                                                                                                        $dataAbono         =    mysqli_fetch_assoc($consult_Abono);
                                                                                                                                        $Total             =    $dataAbono['TotalUSD'];
                                                                                                                                        $Abono             =    $dataAbono['anticipoUSD'];
                                                                                                                                        $Resta             =    $Total - $Abono; ?>
                                                                        <td class="text-right">
                                                                            $ <?php echo number_format($Resta, 2) ?>
                                                                        </td><?php
                                                                                                                                    } else {
                                                                                                                                        $consult_Abono     =    mysqli_query($MySQLi, "SELECT SUM(porAnticipo)AS porAnticipo, Total FROM Abonos WHERE idCotizacion='$IDCotiza' ") or die(mysqli_error($MySQLi));
                                                                                                                                        $dataAbono         =    mysqli_fetch_assoc($consult_Abono);
                                                                                                                                        $Total             =    $dataAbono['Total'];
                                                                                                                                        $Abono             =    $dataAbono['porAnticipo'];
                                                                                                                                        $Resta             =    $Total - $Abono; ?>
                                                                        <td class="text-right">
                                                                            Bs <?php echo number_format($Resta, 2) ?>
                                                                        </td><?php
                                                                                                                                    }
                                                                                                                                } else {
                                                                                                                                    echo '<td class="text-center text-danger">Error</td>';
                                                                                                                                } ?>
                                                            </tr>
                                                        </tbody>
                                                    </table><?php
                                                            $queryRecibos     =    mysqli_query($MySQLi, "SELECT * FROM Abonos WHERE idCotizacion='$IDCotiza' ");
                                                            $respConsulta    =    mysqli_num_rows($queryRecibos);
                                                            $Busqueda         =    $respConsulta - 1;
                                                            $buscarRecibo    =    mysqli_query($MySQLi, "SELECT * FROM Abonos WHERE idCotizacion='$IDCotiza' LIMIT $Busqueda,1 ") or die(mysql_error($MySQLi));
                                                            $dataBusqueda     =    mysqli_fetch_assoc($buscarRecibo);
                                                            $IDAbono         =    $dataBusqueda['idAbono'];
                                                            //$ultminoSaldo 	=	$dataBusqueda['SaldoActual'];
                                                            //echo $ultminoSaldo; 
                                                            ?>
                                                    <div class="row mt-1">
                                                        <div class="col text-center">
                                                            <button class="btn btn-xs btn-primary plusABONO" title="Agregar abono (idCotizacion: <?php echo $IDCotiza ?>)" id="<?php echo $IDCotiza ?>">
                                                                <i class="fa fa-dollar-sign"></i>&nbsp;&nbsp;AGREGAR
                                                                ABONO&nbsp;&nbsp;<i class="fa fa-dollar-sign"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </td><?php
                                                        if ($_SESSION['Rango'] == '2') { ?>
                                                    <td class="text-center"><button class="btn btn-danger btn-xs borrarVentaAnticipo" title="Borrar venta por anticipo(<?php echo $IDCotiza ?>)" id="<?php echo $IDCotiza ?>"><i class="fas fa-trash-alt"></i></button>
                                                    </td><?php
                                                        } ?>
                                            </tr><?php $Num++;
                                                } ?>
                                    </tbody>
                                </table>
                            </div>
                            <!-- end panel-body -->
                        </div>
                    </div>
                </div><?php
                                            } ?>
            <div class="row tablaAbonoAnticipo w-50 m-auto d-none">
                <div class="col-md-12">
                    <div class="panel panel-inverse">
                        <div class="panel-heading">
                            <h4 class="panel-title">FORMULARIO DE ABONO</h4>
                            <div class="panel-heading-btn">
                                <button class="btn btn-danger btn-xs closeTablaAnticipo"> CERRAR </button>&nbsp;&nbsp;
                                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
                                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-success" data-click="panel-reload"><i class="fa fa-redo"></i></a>
                                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>
                                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-danger" data-click="panel-remove"><i class="fa fa-times"></i></a>
                            </div>
                        </div>
                        <div class="panel-body">
                            <form id="formPlus_Abono" data-parsley-validate="true">
                                <div class="row">
                                    <div class="col text-center">
                                        <label for="CodeCotiza"><strong>Código de la cotización</strong></label>
                                        <input type="text" name="CodeCotiza" id="CodeCotiza" class="form-control text-center">
                                    </div>
                                    <div class="col text-center">
                                        <label for="precioDolar"><strong>Precio Dólar</strong></label>
                                        <input type="text" name="dolar" id="PrecioDolar" class="form-control text-center">
                                    </div>
                                </div>
                                <div class="row text-center mt-3">
                                    <div class="col">
                                        <label for="selectMoneda"><strong>MONEDA</strong></label>
                                        <input type="hidden" name="action" value="Agregar nuevo abono">
                                        <input type="hidden" name="moneda" id="idMoneda_Recibo">
                                        <input type="hidden" name="idCliente" id="idCliente_abono">
                                        <input type="hidden" name="idVendedor" id="idVendedor">
                                        <input type="hidden" name="miCiudad" value="<?php echo $miCiudad ?>">
                                        <input type="hidden" name="idCotizacion" id="idCotizacion_Recibo">
                                        <input type="hidden" name="vendedor" value="<?= $nombreVendedor ?>">
                                        <select name="moneda" id="selectMoneda" class="form-control" data-parsley-required="true" disabled="">
                                            <option selected value="USD">USD</option>
                                            <option value="Bs">Bs</option>
                                        </select>
                                    </div>
                                    <div class="col">
                                        <label for="byCantidad_"><strong>POR LA CANTIDAD DE:</strong></label>
                                        <input type="text" name="cantidad" id="byCantidad_" class="form-control text-center" placeholder="ingresa el monto" data-parsley-required="true">
                                        <div class="text-center text-danger d-none noCantRecibo">NO HA INGRESADO LA
                                            CANTIDAD</div>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col text-center">
                                        <strong>RECIBÍ DE:</strong>
                                    </div>
                                    <div class="col">
                                        <input type="text" name="recibide" id="name_ClienteRecibo" class="form-control" placeholder="Nombre del Cliente" data-parsley-required="true">
                                        <div class="text-center text-danger d-none noClienteRecibo">NO HAY CLIENTE</div>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col text-center">
                                        <strong>LA SUMA DE:</strong>
                                    </div>
                                    <div class="col">
                                        <input type="text" name="lasumade" id="suma_Recibo" class="form-control" placeholder="Cantidad en letras" data-parsley-required="true">
                                        <div class="text-center text-danger d-none noSumaRecibo">NO HA INGRESADO LA
                                            CANTIDAD</div>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col text-center">
                                        <label for="enConceptode"><strong>EN CONCEPTO DE:</strong></label>
                                        <textarea name="concetpde" id="enConceptodeRecibo" class="form-control" cols="30" rows="5" placeholder="Descripción del Abono" data-parsley-required="true"></textarea>
                                        <div class="text-center text-danger d-none noConceptoRecibo">NO HA INGRESADO EL
                                            CONCEPTO</div>
                                    </div>
                                </div>
                                <div class="row mt-2 text-center">
                                    <div class="col">
                                        <label for="byAnticipo"><strong>ANTICIPO</strong></label>
                                        <input type="text" name="anticipo" id="byAnticipo" class="form-control text-center" data-parsley-required="true" data-parsley-type="number">
                                        <div class="text-center text-danger d-none noAnticipoRecibo">Vacío</div>
                                    </div>
                                    <div class="col">
                                        <label for="SaldoActual"><strong>SALDO ACTUAL</strong></label>
                                        <input type="text" id="SaldoActualRecibo" class="form-control text-center">
                                        <input type="text" name="saldoActual" id="SaldoActual_Recibo" class="form-control d-none text-center" data-parsley-required="true" data-parsley-type="number">
                                        <div class="text-center text-danger d-none noSaldoActual">Vacío</div>
                                    </div>
                                </div>
                                <div class="row mt-2 text-center">
                                    <div class="col">
                                        <label for="SaldoAnterior"><strong>SALDO ANTERIOR</strong></label>
                                        <input type="text" name="saldoAnterior" id="SaldoAnterior" class="form-control text-center" data-parsley-required="true" data-parsley-type="number">
                                        <div class="text-center text-danger d-none noSaldoAnterior">Vacío</div>
                                    </div>
                                    <div class="col">
                                        <label for="byTotal_"><strong>TOTAL</strong></label>
                                        <input type="text" name="total" id="byTotal_" class="form-control text-center" data-parsley-required="true" data-parsley-type="number">
                                        <div class="text-center text-danger d-none noTotalRecibo">Vacío</div>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col">
                                        <button type="submit" class="btn btn-xs btn-info form-control addNewAbono">AGREGAR NUEVO ABANO
                                            &nbsp;<i class="fas fa-spinner fa-pulse d-none newPay"></i></button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row tablaUltimoAbono  w-50 m-auto d-none">
                <div class="col-md-12">
                    <div class="panel panel-inverse">
                        <div class="panel-heading">
                            <h4 class="panel-title">FORMULARIO EDITAR ULTIMO RECIBO</h4>
                            <div class="panel-heading-btn">
                                <button class="btn btn-danger btn-xs closeUltimoAbono "> CERRAR </button>&nbsp;&nbsp;
                                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
                                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-success" data-click="panel-reload"><i class="fa fa-redo"></i></a>
                                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>
                                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-danger" data-click="panel-remove"><i class="fa fa-times"></i></a>
                            </div>
                        </div>
                        <div class="panel-body">
                            <form id="form_ReciboUPD" data-parsley-validate="true">
                                <input type="hidden" name="idUser" value="<?php echo $idUser ?>">
                                <input type="hidden" name="idCliente" id="idCliente2">
                                <input type="hidden" name="idCotizacion" id="idCotizacion2">
                                <input type="hidden" name="moneda" id="moneda2">
                                <div class="row">
                                    <div class="col text-center">
                                        <label for="codeCotizacion"><strong>Código de la cotización</strong></label>
                                        <input type="text" name="CodeCotiza" id="codeCotizacion" class="form-control text-center">
                                    </div>
                                    <div class="col text-center">
                                        <label for="precioDolar"><strong>Precio Dólar</strong></label>
                                        <input type="text" name="dolar" id="Precio_Dolar" class="form-control text-center">
                                    </div>
                                </div>
                                <div class="row text-center mt-3">
                                    <div class="col">
                                        <label for="selectMoneda_"><strong>MONEDA</strong></label>
                                        <input type="hidden" name="action" value="actualizar datos del Recibo">
                                        <input type="hidden" name="idRecibo" id="id_recibo">
                                        <input type="hidden" name="idAbono" id="id_abono">
                                        <select name="moneda" id="select_Moneda" class="form-control" data-parsley-required="true" disabled>
                                            <option value="USD">USD</option>
                                            <option value="Bs">Bs</option>
                                        </select>
                                    </div>
                                    <div class="col">
                                        <label for="by_Cantidad_"><strong>POR LA CANTIDAD DE:</strong></label>
                                        <input type="text" name="cantidad" id="by_Cantidad_" autocomplete="off" class="form-control text-center" placeholder="ingresa el monto" data-parsley-required="true">
                                        <div class="text-center text-danger d-none noCantRecibo">NO HA INGRESADO LA
                                            CANTIDAD</div>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col text-left">
                                        <strong>RECIBÍ DE: </strong>
                                    </div>
                                    <div class="col">
                                        <input type="text" name="recibide" id="name_Cliente_" class="form-control" placeholder="Nombre del Cliente" data-parsley-required="true">
                                        <div class="text-center text-danger d-none noClienteRecibo">NO HAY CLIENTE</div>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col text-left">
                                        <strong>LA SUMA DE: </strong>
                                    </div>
                                    <div class="col">
                                        <textarea name="lasumade" id="_sumaRecibo" cols="1" rows="1" class="form-control" data-parsley-required="true"></textarea>
                                        <!-- <input type="text" name="lasumade" id="suma_Recibo" class="form-control" placeholder="Cantidad en letras" > -->
                                        <div class="text-center text-danger d-none noSumaRecibo">NO HA INGRESADO LA SUMA
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col text-center">
                                        <label for="en_Conceptode"><strong>EN CONCEPTO DE:</strong></label>
                                        <textarea name="concetpde" id="en_Conceptode_" class="form-control" cols="30" rows="5" placeholder="Descripción del Abono" data-parsley-required="true"></textarea>
                                        <div class="text-center text-danger d-none noConceptoRecibo">NO HA INGRESADO EL
                                            CONCEPTO</div>
                                    </div>
                                </div>
                                <div class="row mt-2 text-center">
                                    <div class="col">
                                        <label for="by_Anticipo_"><strong>ANTICIPO</strong></label>
                                        <input type="text" name="anticipo" id="by_Anticipo_" class="form-control text-center" data-parsley-required="true" data-parsley-type="number">
                                        <div class="text-center text-danger d-none noAnticipoRecibo">Vacío</div>
                                    </div>
                                    <div class="col">
                                        <label for="Saldo_Act"><strong>SALDO ACTUAL</strong></label>
                                        <!-- <input type="text" name="saldoAct1" id="Saldo_actual" class="form-control text-center"> -->
                                        <input type="text" name="saldoAct" id="Saldo_Act" class="form-control text-center" data-parsley-required="true" data-parsley-type="number">
                                        <div class="text-center text-danger d-none noSaldoActual">Vacío</div>
                                    </div>
                                </div>
                                <div class="row mt-2 text-center">
                                    <div class="col">
                                        <label for="Saldo_Ant"><strong>SALDO ANTERIOR</strong></label>
                                        <input type="text" name="saldoAnt" id="Saldo_Ant" class="form-control text-center" data-parsley-required="true" data-parsley-type="number">
                                        <div class="text-center text-danger d-none noSaldoAnterior">Vacío</div>
                                    </div>
                                    <div class="col">
                                        <label for="by_Total"><strong>TOTAL</strong></label>
                                        <input type="text" name="total" id="by_Total_" class="form-control text-center" data-parsley-required="true" data-parsley-type="number">
                                        <div class="text-center text-danger d-none noTotalRecibo">Vacío</div>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col">
                                        <button type="submit" class="btn btn-xs btn-info form-control updateRecibo">ACTUALIZAR RECIBO
                                            &nbsp;<i class="fas fa-spinner fa-pulse d-none updateLastPay"></i></button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row tablaPrimerAbono  w-50 m-auto d-none">
                <div class="col-md-12">
                    <div class="panel panel-inverse">
                        <div class="panel-heading">
                            <h4 class="panel-title">EDITAR ABONO POR <span id="Cant_Abono"></span></h4>
                            <div class="panel-heading-btn">
                                <button class="btn btn-danger btn-xs closePrimerAbono "> CERRAR </button>&nbsp;&nbsp;
                                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
                                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-success" data-click="panel-reload"><i class="fa fa-redo"></i></a>
                                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>
                                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-danger" data-click="panel-remove"><i class="fa fa-times"></i></a>
                            </div>
                        </div>
                        <div class="panel-body">
                            <form id="formPrmimerAbono" data-parsley-validate="true">
                                <div class="row">
                                    <div class="col text-center">
                                        <label for="CodeCotiza_"><strong>Código de la cotización</strong></label>
                                        <input type="text" name="CodeCotiza" id="CodeCotiza_" class="form-control text-center">
                                    </div>
                                    <div class="col text-center">
                                        <label for="precioDolar"><strong>Precio Dólar</strong></label>
                                        <input type="text" name="dolar" id="precioDolar" class="form-control text-center">
                                    </div>
                                </div>
                                <div class="row text-center mt-3">
                                    <div class="col">
                                        <label for="selectMoneda_"><strong>MONEDA</strong></label>
                                        <input type="hidden" name="action" value="actualizar datos del Recibo">
                                        <input type="hidden" name="idUser" value="<?php echo $idUser ?>">
                                        <input type="hidden" name="idRecibo" id="idRecibo">
                                        <input type="hidden" name="idAbono" id="idAbono1">
                                        <input type="hidden" name="idCliente" id="idCliente1">
                                        <input type="hidden" name="idCotizacion" id="idCotizacion1">
                                        <select name="moneda" id="selectMoneda_" class="form-control" data-parsley-required="true">
                                            <option selected value="USD">USD</option>
                                            <option value="Bs">Bs</option>
                                        </select>
                                    </div>
                                    <div class="col">
                                        <label for="by_Cantidad"><strong>POR LA CANTIDAD DE:</strong></label>
                                        <input type="text" name="cantidad" id="by_Cantidad" autocomplete="off" class="form-control text-center" placeholder="ingresa el monto" data-parsley-required="true">
                                        <div class="text-center text-danger d-none noCantRecibo">NO HA INGRESADO LA
                                            CANTIDAD</div>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col text-left">
                                        <strong>RECIBÍ DE: </strong>
                                    </div>
                                    <div class="col">
                                        <input type="text" name="recibide" id="nameCliente" class="form-control" placeholder="Nombre del Cliente" data-parsley-required="true">
                                        <div class="text-center text-danger d-none noClienteRecibo">NO HAY CLIENTE</div>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col text-left">
                                        <strong>LA SUMA DE: </strong>
                                    </div>
                                    <div class="col">
                                        <textarea name="lasumade" id="sumaRecibo" cols="1" rows="1" class="form-control" data-parsley-required="true"></textarea>
                                        <!-- <input type="text" name="lasumade" id="suma_Recibo" class="form-control" placeholder="Cantidad en letras" > -->
                                        <div class="text-center text-danger d-none noSumaRecibo">NO HA INGRESADO LA SUMA
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col text-center">
                                        <label for="en_Conceptode"><strong>EN CONCEPTO DE:</strong></label>
                                        <textarea name="concetpde" id="en_Conceptode" class="form-control" cols="30" rows="5" placeholder="Descripción del Abono" data-parsley-required="true"></textarea>
                                        <div class="text-center text-danger d-none noConceptoRecibo">NO HA INGRESADO EL
                                            CONCEPTO</div>
                                    </div>
                                </div>
                                <div class="row mt-2 text-center">
                                    <div class="col">
                                        <label for="by_Anticipo"><strong>ANTICIPO</strong></label>
                                        <input type="text" name="anticipo" id="by_Anticipo" class="form-control text-center" data-parsley-required="true" data-parsley-type="number">
                                        <div class="text-center text-danger d-none noAnticipoRecibo">Vacío</div>
                                    </div>
                                    <div class="col">
                                        <label for="SaldoAct"><strong>SALDO ACTUAL</strong></label>
                                        <input type="text" name="saldoAct" id="SaldoAct" class="form-control text-center" data-parsley-required="true" data-parsley-type="number">
                                        <!-- <input type="text" id="SaldoActualReciboEdit" class="form-control d-none text-center"> -->
                                        <div class="text-center text-danger d-none noSaldoActual">Vacío</div>
                                    </div>
                                </div>
                                <div class="row mt-2 text-center">
                                    <div class="col">
                                        <label for="SaldoAnt"><strong>SALDO ANTERIOR</strong></label>
                                        <input type="text" name="saldoAnt" id="SaldoAnt" class="form-control text-center" data-parsley-required="true" data-parsley-type="number">
                                        <div class="text-center text-danger d-none noSaldoAnterior">Vacío</div>
                                    </div>
                                    <div class="col">
                                        <label for="by_Total"><strong>TOTAL</strong></label>
                                        <input type="text" name="total" id="by_Total" class="form-control text-center" data-parsley-required="true" data-parsley-type="number">
                                        <div class="text-center text-danger d-none noTotalRecibo">Vacío</div>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col">
                                        <button type="submit" class="btn btn-xs btn-info form-control updatePrimerAbono">ACTUALIZAR RECIBO
                                            &nbsp;<i class="fas fa-spinner fa-pulse d-none updatePay"></i></button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            

            <!-- Modal FACTURACION PANEL -->
            <div id="modalFacturaFR" class="modal fade" role="dialog">
                <div class="modal-dialog modal-lg" style="max-width: 1250px!important;">
                    <div class="modal-content contenido_pregunta_tipo_factura">
                        <!--=====================================
                        CABEZA DEL MODAL 2
                        ======================================-->
                        <div class="modal-header btn-primary">
                            <h4 class="modal-title">FACTURACIÓN</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        </div>
                        <!--=====================================
                        CUERPO DEL MODAL 2
                        ======================================-->
                        <div class="modal-body">
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
                        </div>
                    </div>

                    <div class="modal-content d-none contenido_factura_simple">
                        <!--=====================================
                        CABEZA DEL MODAL 2
                        ======================================-->
                        <div class="modal-header btn-primary">
                            <h4 class="modal-title">FACTURACIÓN SIMPLE</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        </div>
                        <!--=====================================
                        CUERPO DEL MODAL 2
                        ======================================-->
                        <div class="modal-body">
                            <!-- FORMULARIO SIMPLE FACTURACION 00 YULI SRL-->
                            <div class="row form_simple">
                                <div class="col">

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
                                                        // mysqli_close($MySQLi);
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

                    <div class="modal-content d-none contenido_factura_doble">
                        <!--=====================================
                        CABEZA DEL MODAL 2
                        ======================================-->
                        <div class="modal-header">
                            <h4 class="modal-title">FACTURACIÓN DOBLE</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        </div>
                        <!--=====================================
                        CUERPO DEL MODAL 2
                        ======================================-->
                        <div class="modal-body">
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
                                        </div>
                                        <div class="col-2">
                                        </div>
                                    </div>
                                    <div class="row">
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
                                                            <h5></h5>
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
    <?php include 'php/script_anticipo.php'; ?>
</body>

</html>