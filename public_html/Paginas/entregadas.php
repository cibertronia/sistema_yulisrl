<?php
	$idUser 		=	$_SESSION['idUser'];
	$ConsltaUser=	mysqli_query($MySQLi,"SELECT * FROM Usuarios WHERE idUser='$idUser' ");
	$datosUser 	=	mysqli_fetch_assoc($ConsltaUser);
	$miCiudad 	=	$datosUser['Ciudad'];?>
<!DOCTYPE html>
<html lang="es">

<head>
    <title>ENTREGADAS</title>
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

<body><?php 
		include 'php/loader.php'; ?>
    <div id="page-container" class="fade page-sidebar-fixed page-header-fixed"><?php
			include 'php/top_menu.php';
			include 'php/left_menu_entregadas.php';?>
        <div id="content" class="content">
            <div class="respuesta"></div><?php
				if (isset($_POST['inicio'])) {
				 	$Inicio 	= $_POST['inicio'];
					$Fin 			=	$_POST['fin']; ?>
            <!-- TABLA COTIZACIONES -->
            <div class="row tableCotizaciones">
                <div class="col-md-12">
                    <div class="panel panel-inverse">
                        <div class="panel-heading">
                            <h4 class="panel-title">COTIZACIONES ENTREGADAS DESDE <strong
                                    class="text-danger"><?php echo $Inicio ?></strong> HASTA <strong
                                    class="text-danger"><?php echo $Fin ?></strong></h4>
                            <div class="panel-heading-btn">
                                <!-- <button class="btn btn-xs btn-primary Buscar"><i class="fa fa-search"> Buscar</i></button>&nbsp;&nbsp; -->
                                <!-- <button class="btn btn-xs btn-primary AddNewCotizaBTN">AGREGAR COTIZACION</button>&nbsp;&nbsp;&nbsp; -->
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
                            <form data-parsley-validate="true" class="w-75 m-auto" id="buscar" action="?root=entregadas"
                                method="POST">
                                <div class="row mb-2">
                                    <div class="col text-center">
                                        <label for="fechaInicio">Fecha de inicio</label>
                                        <input type="date" name="inicio" id="fechaInicio"
                                            class="form-control text-center" value="<?php echo $Inicio ?>"
                                            data-parsley-required="true">
                                    </div>
                                    <div class="col text-center">
                                        <label for="fechaFin">Fecha final</label>
                                        <input type="date" name="fin" id="fechaFin" class="form-control text-center"
                                            value="<?php echo $Fin ?>" data-parsley-required="true">
                                    </div>
                                    <div class="col">
                                        <label for="buscar">&nbsp;&nbsp;&nbsp;</label>
                                        <button type="submit" class="form-control btn btn-xs btn-primary ">Buscar
                                            &nbsp;<i class="fas fa-spinner fa-pulse d-none btn-Buscar"></i></button>
                                    </div>
                                </div>
                            </form>
                            <table id="data-table-buttons"
                                class="table table-striped table-bordered table-td-valign-middle w-100">
                                <thead>
                                    <tr class="table-success">
                                        <th width="5%" class="text-center">N&ordm;</th>
                                        <th width="25%" class="text-center">Datos</th>
                                        <th width="60%" class="text-center">Productos</th>
                                        <th width="10%" class="text-center">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody> <?php $Num =	1;
											if ($_SESSION['Rango']==2) {
												$consultaCotizacion	=	mysqli_query($MySQLi,"SELECT idCotizacion, Code, Clave, idUser, idCliente, Forma_Pago, DATE_FORMAT(FinFecha_Oferta, '%d de %M, %Y') AS FinFecha_Oferta, Dias_Entrega, Comentarios, Sucursal, DATE_FORMAT(Fecha, '%d de %M, %Y') AS Fecha, DATE_FORMAT(Hora, '%h:%i:%s %p') AS Hora, DATE_FORMAT(Entregada, '%d de %M, %Y') AS Entregada FROM Cotizaciones WHERE Estado=1 AND Fecha BETWEEN '$Inicio'AND '$Fin' ORDER BY Entregada DESC");
											}else{
												$consultaCotizacion	=	mysqli_query($MySQLi,"SELECT idCotizacion, Code, Clave, idUser, idCliente, Forma_Pago, DATE_FORMAT(FinFecha_Oferta, '%d de %M, %Y') AS FinFecha_Oferta, Dias_Entrega, Comentarios, Sucursal, DATE_FORMAT(Fecha, '%d de %M, %Y') AS Fecha, DATE_FORMAT(Hora, '%h:%i:%s %p') AS Hora, DATE_FORMAT(Entregada, '%d de %M, %Y') AS Entregada FROM Cotizaciones WHERE idUser='$idUser'AND Estado=1 AND Fecha BETWEEN '$Inicio'AND '$Fin' ORDER BY Entregada DESC");
											}
											while ($dataCotizacion = mysqli_fetch_assoc($consultaCotizacion)) { ?>
                                    <tr class="odd gradeX">
                                        <td class="text-center"><?php echo $Num; ?></td><?php
													$idCliente 		=	$dataCotizacion['idCliente'];
													$queryCliente	=	mysqli_query($MySQLi,"SELECT * FROM Clientes WHERE idCliente='$idCliente' ");
													$dataCliente 	=	mysqli_fetch_assoc($queryCliente);
													$idVendedor 	=	$dataCotizacion['idUser'];
													$queryVendedor	=	mysqli_query($MySQLi,"SELECT Nombres, Apellidos, Ciudad, idUser FROM Usuarios WHERE idUser='$idVendedor' ");
													$dataVendedor 	=	mysqli_fetch_assoc($queryVendedor); ?>
                                        <td style="font-size: 10px">
                                            <table class="table table-success">
                                                <tr class="table-info">
                                                    <td>CODIGO:</td>
                                                    <th><?php echo $dataCotizacion['Code'] ?></th>
                                                </tr>
                                                <tr>
                                                    <td>Cliente:</td>
                                                    <th><?php echo $dataCliente['Nombres']." ".$dataCliente['Apellidos'] ?>
                                                    </th>
                                                </tr>
                                                <tr">
                                                    <td>Empresa:</td>
                                                    <th><?php echo $dataCliente['Empresa'] ?></th>
                                    </tr>
                                    <tr>
                                        <td>Correo:</td>
                                        <th><?php echo $dataCliente['Correo'] ?></th>
                                    </tr>
                                    <tr>
                                        <td>Teléfono:</td>
                                        <th><?php echo $dataCliente['Celular'] ?></th>
                                    </tr>
                                    <tr>
                                        <td>Vendedor:</td>
                                        <th><?php echo $dataVendedor['Nombres']." ".$dataVendedor['Apellidos'] ?></th>
                                    </tr>
                                    <tr>
                                        <td>Fecha:</td>
                                        <th><?php echo $dataCotizacion['Fecha'] ?></th>
                                    </tr>
                                    <tr>
                                        <td>Hora:</td>
                                        <th><?php echo $dataCotizacion['Hora'] ?></th>
                                    </tr>
                            </table>
                            </td>
                            <td style="font-size: 12px;">
                                <div class="text-center mt-1 mb-1" style="margin-top: -5%">OFERTA VÁLIDA HASTE EL: <span
                                        class="text-danger"
                                        style="text-transform: uppercase;"><?php echo $dataCotizacion['FinFecha_Oferta'] ?></span>
                                </div>
                                <table
                                    class="table table-success table-striped table-bordered table-td-valign-middle w-100">
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
															$ClaveTemp 	=	$dataCotizacion['Clave'];
															$sqlCotiza	=	mysqli_query($MySQLi,"SELECT * FROM ClaveTemporal WHERE Clave='$ClaveTemp' ");
															while ($dataProdTemp = mysqli_fetch_assoc($sqlCotiza)) { ?>
                                        <tr>
                                            <td class="text-center"><?php echo $dataProdTemp['Cantidad'] ?></td><?php
																	$idProducto =	$dataProdTemp['idProducto'];
																	$queryProd 	=	mysqli_query($MySQLi,"SELECT * FROM Productos WHERE idProducto='$idProducto' ");
																	$dataProducto=	mysqli_fetch_assoc($queryProd); ?>
                                            <td><?php echo $dataProducto['Producto']." / " .$dataProducto['Marca']." / ".$dataProducto['Modelo'] ?>
                                            </td>
                                            <td class="text-right">
                                                $&nbsp;<?php echo number_format($dataProdTemp['PrecioLista'],2) ?></td>
                                            <td class="text-right">
                                                $&nbsp;<?php echo number_format($dataProdTemp['PrecioOferta'],2) ?></td>
                                            <td class="text-right">
                                                $&nbsp;<?php echo number_format($dataProdTemp['PrecioOferta']*$dataProdTemp['Cantidad'],2) ?>
                                            </td>
                                        </tr><?php } ?>
                                        <tr>
                                            <td colspan="3"></td>
                                            <td class="text-center">TOTAL</td><?php
																$sql_Cotiza	=	mysqli_query($MySQLi,"SELECT SUM(Cantidad*PrecioOferta)AS TOTAL FROM ClaveTemporal WHERE Clave='$ClaveTemp' ");
																$datasql 	=	mysqli_fetch_assoc($sql_Cotiza) ?>
                                            <td class="text-right">$ <?php echo number_format($datasql['TOTAL'],2) ?>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                <div class="text-center m-auto" style="width: 100%">
                                    <div class="row">
                                        <!-- 	REPORTE PDF 	-->
                                        <div class="col">
                                            <form target="_blank" action="Reportes/pdf.php" method="post" class="mt-1">
                                                <input type="hidden" name="idReporteCotizacion"
                                                    value="<?php echo $dataCotizacion['idCotizacion'] ?>">
                                                <button class="btn btn-primary" title="Generar PDF">
                                                    <i class="fa fa-file-pdf" style="font-size: 25px"></i>
                                                </button>
                                            </form>
                                        </div>
                                        
                                        <!-- 	VENTA INSTANTÁNEA	 -->
                                        <div class="col">
                                            <button class="btn btn-primary llamarDatosCotizacion mt-1"
                                                id="<?php echo $dataCotizacion['idCotizacion'] ?>"
                                                title="Marcar como Vendida Cash"><i class="fa fa-dollar-sign"
                                                    style="font-size: 25px"></i>
                                            </button>
                                        </div>
                                        <!-- 	COMPRA POR ABONO	 -->
                                        <div class="col">
                                            <form action="#" class="mt-1">
                                                <button type="button" title="Compra por anticipo"
                                                    class="btn btn-primary porAbono"
                                                    id="<?php echo $dataCotizacion['idCotizacion'] ?>">
                                                    <i class="fas fa-handshake" style="font-size: 25px"></i>
                                                </button>
                                            </form>
                                        </div>
                                        <!-- 	COMPRA AL CRÉDITO	 -->
                                        <div class="col">
                                            <form action="#" class="mt-1">
                                                <button type="button" title="Compra al crédito"
                                                    class="btn btn-primary alCredito"
                                                    id="<?php echo $dataCotizacion['idCotizacion'] ?>">
                                                    <i class="fab fa-cc-visa" style="font-size: 25px"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="text-center"><?php
													if ($dataCliente['Correo']!='') { ?>
                                <button class="btn btn-xs btn-danger enviarMail" data-target="#sendMail"
                                    data-toggle="modal" title="Enviar Cotizacion por correo"
                                    id="<?php echo $dataCotizacion['idCotizacion'] ?>">
                                    <i class="fa fa-envelope" style="font-size: 15px"></i>
                                </button><?php
													}
													/*	CONSULTAMOS CUANTOS CORREO SE ENVIARON	*/
													$queryMailSent	=	mysqli_query($MySQLi,"SELECT * FROM Log_Correos WHERE idCliente='$idCliente' AND Tipo='Cotiza' ");
													$dataMailSent 	=	mysqli_num_rows($queryMailSent);
													if ($dataMailSent>0) { ?>
                                &nbsp;<button title="Cantidad de cotizaciones enviadas al correo"
                                    class="btn btn-xs btn-primary">
                                    <span style="font-size: 15px"><?php echo $dataMailSent ?></span>
                                </button><?php
													}?>
                            </td>
                            </tr><?php $Num++;}  ?>
                            </tbody>
                            </table>
                        </div>
                        <!-- end panel-body -->
                    </div>
                </div>
            </div><?php
				}else{ ?>
            <!-- TABLA COTIZACIONES -->
            <div class="row tableCotizaciones">
                <div class="col-md-12">
                    <div class="panel panel-inverse">
                        <div class="panel-heading">
                            <h4 class="panel-title">COTIZACIONES ENTREGADAS
                                <strong><?php echo strtoupper($mes) ?></strong>
                            </h4>
                            <div class="panel-heading-btn">
                                <button class="btn btn-xs btn-primary Buscar"><i class="fa fa-search">
                                        Buscar</i></button>&nbsp;&nbsp;
                                <!-- <button class="btn btn-xs btn-primary AddNewCotizaBTN">AGREGAR COTIZACION</button>&nbsp;&nbsp;&nbsp; -->
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
                            <form data-parsley-validate="true" class="w-75 m-auto d-none" id="buscar"
                                action="?root=entregadas" method="POST">
                                <div class="row mb-2">
                                    <div class="col text-center">
                                        <label for="fechaInicio">Fecha de inicio</label>
                                        <input type="date" name="inicio" id="fechaInicio"
                                            class="form-control text-center" value="<?php echo $startBusqueda ?>"
                                            data-parsley-required="true">
                                    </div>
                                    <div class="col text-center">
                                        <label for="fechaFin">Fecha final</label>
                                        <input type="date" name="fin" id="fechaFin" class="form-control text-center"
                                            value="<?php echo $fecha ?>" data-parsley-required="true">
                                    </div>
                                    <div class="col">
                                        <label for="buscar">&nbsp;&nbsp;&nbsp;</label>
                                        <button type="submit" class="form-control btn btn-xs btn-primary ">Buscar
                                            &nbsp;<i class="fas fa-spinner fa-pulse d-none btn-Buscar"></i></button>
                                    </div>
                                </div>
                            </form>
                            <table id="data-table-buttons"
                                class="table table-striped table-bordered table-td-valign-middle w-100">
                                <thead>
                                    <tr class="table-success">
                                        <th width="5%" class="text-center">N&ordm;</th>
                                        <th width="25%" class="text-center">Datos</th>
                                        <th width="60%" class="text-center">Productos</th>
                                        <th width="10%" class="text-center">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody> <?php $Num =	1;
											if ($_SESSION['Rango']==2) {
												$consultaCotizacion	=	mysqli_query($MySQLi,"SELECT idCotizacion, Code, Clave, idUser, idCliente, Forma_Pago, DATE_FORMAT(FinFecha_Oferta, '%d de %M, %Y') AS FinFecha_Oferta, Dias_Entrega, Comentarios, Sucursal, DATE_FORMAT(Fecha, '%d de %M, %Y') AS Fecha, DATE_FORMAT(Hora, '%h:%i:%s %p') AS Hora, DATE_FORMAT(Entregada, '%d de %M, %Y') AS Entregada FROM Cotizaciones WHERE Estado=1 AND Fecha BETWEEN '$startBusqueda'AND '$fecha' ORDER BY Entregada DESC");
											}else{
												$consultaCotizacion	=	mysqli_query($MySQLi,"SELECT idCotizacion, Code, Clave, idUser, idCliente, Forma_Pago, DATE_FORMAT(FinFecha_Oferta, '%d de %M, %Y') AS FinFecha_Oferta, Dias_Entrega, Comentarios, Sucursal, DATE_FORMAT(Fecha, '%d de %M, %Y') AS Fecha, DATE_FORMAT(Hora, '%h:%i:%s %p') AS Hora, DATE_FORMAT(Entregada, '%d de %M, %Y') AS Entregada FROM Cotizaciones WHERE idUser='$idUser'AND Estado=1 AND Fecha BETWEEN '$startBusqueda'AND '$fecha' ORDER BY Entregada DESC");
											}
											while ($dataCotizacion = mysqli_fetch_assoc($consultaCotizacion)) { ?>
                                    <tr class="odd gradeX">
                                        <td class="text-center"><?php echo $Num; ?></td><?php
													$idCotizacion = $dataCotizacion['idCotizacion'];
													$idCliente 		=	$dataCotizacion['idCliente'];
													$queryCliente	=	mysqli_query($MySQLi,"SELECT * FROM Clientes WHERE idCliente='$idCliente' ");
													$dataCliente 	=	mysqli_fetch_assoc($queryCliente);
													$idVendedor 	=	$dataCotizacion['idUser'];
													$queryVendedor	=	mysqli_query($MySQLi,"SELECT Nombres, Apellidos, Ciudad, idUser FROM Usuarios WHERE idUser='$idVendedor' ");
													$dataVendedor 	=	mysqli_fetch_assoc($queryVendedor); ?>
                                        <td style="font-size: 10px">
                                            <table class="table table-success">
                                                <tr class="table-info">
                                                    <td>CODIGO:</td>
                                                    <th><?php echo $dataCotizacion['Code'] ?></th>
                                                </tr>
                                                <tr>
                                                    <td>Cliente:</td>
                                                    <th><?php echo $dataCliente['Nombres']." ".$dataCliente['Apellidos'] ?>
                                                    </th>
                                                </tr><?php
														if ($dataCliente['Empresa']!='') { ?>
                                                <tr>
                                                    <td>Empresa:</td>
                                                    <th><?php echo $dataCliente['Empresa'] ?></th>
                                                </tr><?php }
														if ($dataCliente['Correo']!='') { ?>
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
                                                    <th><?php echo $dataVendedor['Nombres']." ".$dataVendedor['Apellidos'] ?>
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
                                            </table>
                                        </td>
                                        <td style="font-size: 12px;">
                                            <div class="text-center mt-1 mb-1" style="margin-top: -5%">OFERTA VÁLIDA
                                                HASTE EL: <span class="text-danger"
                                                    style="text-transform: uppercase;"><?php echo $dataCotizacion['FinFecha_Oferta'] ?></span>
                                            </div>
                                            <table
                                                class="table table-success table-striped table-bordered table-td-valign-middle w-100">
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
															$ClaveTemp 	=	$dataCotizacion['Clave'];
															$sqlCotiza	=	mysqli_query($MySQLi,"SELECT * FROM ClaveTemporal WHERE Clave='$ClaveTemp' ");
															$totError = 0;
															$disabled = "";
															while ($dataProdTemp = mysqli_fetch_assoc($sqlCotiza)) { ?>
                                                    <tr>
                                                    <?php
																	$idProducto =	$dataProdTemp['idProducto'];
																	$queryProd 	=	mysqli_query($MySQLi,"SELECT * FROM Productos WHERE idProducto='$idProducto' ");
																	$dataProducto=	mysqli_fetch_assoc($queryProd); 
																	$color = 0;
																	if ($dataProducto['StockTotal'] < $dataProdTemp['Cantidad']) {
																	    $totError++;
																	    $color = 1;
															}
																	?>
                                                        <td class="text-center"  <?php if ($color == 1) echo 'style="color:red"';  ?> ><?php echo $dataProdTemp['Cantidad'] ?>
                                                        </td>
                                                        <td><?php echo $dataProducto['Producto']." / " .$dataProducto['Marca']." / ".$dataProducto['Modelo'] ?>
                                                        </td>
                                                        <td class="text-right">
                                                            $&nbsp;<?php echo number_format($dataProdTemp['PrecioLista'],2) ?>
                                                        </td>
                                                        <td class="text-right">
                                                            $&nbsp;<?php echo number_format($dataProdTemp['PrecioOferta'],2) ?>
                                                        </td>
                                                        <td class="text-right">
                                                            $&nbsp;<?php echo number_format($dataProdTemp['PrecioOferta']*$dataProdTemp['Cantidad'],2) ?>
                                                        </td>
                                                    </tr><?php } ?>
                                                    <tr>
                                                        <td colspan="3"></td>
                                                        <td class="text-center">TOTAL</td><?php
																$sql_Cotiza	=	mysqli_query($MySQLi,"SELECT SUM(Cantidad*PrecioOferta)AS TOTAL FROM ClaveTemporal WHERE Clave='$ClaveTemp' ");
																$datasql 	=	mysqli_fetch_assoc($sql_Cotiza) ?>
                                                        <td class="text-right">$
                                                            <?php echo number_format($datasql['TOTAL'],2) ?></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                            <div class="text-center m-auto" style="width: 100%">
                                                <div class="row">
                                                    <!-- 	REPORTE PDF 	-->
                                                    <div class="col">
                                                        <form target="_blank" action="Reportes/pdf.php" method="post"
                                                            class="mt-1">
                                                            <input type="hidden" name="idReporteCotizacion"
                                                                value="<?php echo $dataCotizacion['idCotizacion'] ?>">
                                                            <button class="btn btn-primary" title="Generar PDF">
                                                                <i class="fa fa-file-pdf" style="font-size: 25px"></i>
                                                            </button>
                                                        </form>
                                                    </div>
                                                    
                                                    <!-- 	VENTA INSTANTÁNEA	 -->
                                                    <?php 
                                                    if ($totError > 0)
                                                        $disabled = "disabled";
                                                    ?>
                                                    <div class="col">
                                                        <button class="btn btn-primary llamarDatosCotizacion mt-1"
                                                            id="<?php echo $dataCotizacion['idCotizacion'] ?>"
                                                            title="Marcar como Vendida Cash" <?php echo $disabled ?>><i
                                                                class="fa fa-dollar-sign" style="font-size: 25px"></i>
                                                                
                                                        </button>
                                                    </div>
                                                    <!-- 	COMPRA POR ABONO	 -->
                                                    <div class="col">
                                                        <form action="#" class="mt-1">
                                                            <button type="button" title="Compra por anticipo"
                                                                class="btn btn-primary porAbono"
                                                                id="<?php echo $dataCotizacion['idCotizacion'] ?>">
                                                                <i class="fas fa-handshake" style="font-size: 25px"></i>
                                                            </button>
                                                        </form>
                                                    </div>
                                                    <!-- 	COMPRA AL CRÉDITO	 -->
                                                    <div class="col">
                                                        <form action="#" class="mt-1">
                                                            <button type="button" title="Compra al crédito"
                                                                class="btn btn-primary alCredito"
                                                                id="<?php echo $dataCotizacion['idCotizacion'] ?>">
                                                                <i class="fab fa-cc-visa" style="font-size: 25px"></i>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center"><?php
													if ($_SESSION['Rango']=='2') { ?>
                                            <button class="btn btn-xs btn-info changeStatus"
                                                id="<?php echo $idCotizacion ?>"
                                                title="Cambiar a estado generada (<?php echo $idCotizacion ?>)"><i
                                                    class="fas fa-lg fa-fw fa-undo-alt"></i></button><?php
													}
													if ($dataCliente['Correo']!='') { ?>
                                            <!-- <button class="btn btn-xs btn-danger enviarMail" data-target="#sendMail" data-toggle="modal" title="Enviar Cotizacion por correo" id="<?php echo $dataCotizacion['idCotizacion'] ?>">
															<i class="fa fa-envelope" style="font-size: 15px"></i>
														</button> --><?php
													}
													/*	CONSULTAMOS CUANTOS CORREO SE ENVIARON	*/
													$queryMailSent	=	mysqli_query($MySQLi,"SELECT * FROM Log_Correos WHERE idCliente='$idCliente' AND Tipo='Cotiza' ");
													$dataMailSent 	=	mysqli_num_rows($queryMailSent);
													if ($dataMailSent>0) { ?>
                                            &nbsp;<button title="Cantidad de cotizaciones enviadas al correo"
                                                class="btn btn-xs btn-primary">
                                                <span style="font-size: 15px"><?php echo $dataMailSent ?></span>
                                            </button><?php
													}?>
                                        </td>
                                    </tr><?php $Num++;}  ?>
                                </tbody>
                            </table>
                        </div>
                        <!-- end panel-body -->
                    </div>
                </div>
            </div><?php
				} ?>
            <!-- FORMULARIO RECIBO VENTA DIRECTA -->
            <div class="row fomrVentaCash d-none w-50 m-auto">
                <div class="col-md-12">
                    <div class="panel panel-inverse">
                        <div class="panel-heading">
                            <h4 class="panel-title">FORMULARIO RECIBO VENTA</h4>
                            <div class="panel-heading-btn">
                                <button class="btn btn-danger btn-xs closeFormVenta"> CERRAR </button>&nbsp;&nbsp;
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
                            <form id="formVenta" data-parsley-validate="true">
                                <div class="row text-center">
                                    <div class="col text-center">
                                        <input type="hidden" name="idCotizacion" id="idCotizaPago">
                                        <input type="hidden" name="vendedor" value="<?= $datosUser['Nombres'] . ' ' . $datosUser['Apellidos'] ?>">
                                        <input type="hidden" name="idUser" id="idUserPago">
                                        <input type="hidden" name="idCliente" id="idClientePago">
                                        <input type="hidden" name="Sucursal" id="SucursalPago">
                                        <input type="hidden" name="action" value="GuardarPgo">
                                        <label for="CodeCotiza"><strong>Código de la cotización</strong></label>
                                        <input type="text" name="CodeCotiza" id="Code_Cotiza"
                                            class="form-control text-center">
                                        <div class="text-center text-danger d-none noCodeCotiza">CODIGO COTIZACION VACÍO
                                        </div>
                                    </div>
                                    <div class="col text-center">
                                        <label for="precioDolar"><strong>Precio Dólar</strong></label>
                                        <input type="text" name="dolar" id="precio_Dolar"
                                            class="form-control text-center" value="<?php precioDolar($MySQLi) ?>">
                                        <div class="text-center text-danger d-none noPrecioDolar">PRECIO DOLAR VACÍO
                                        </div>
                                    </div>
                                </div>
                                <div class="row text-center mt-3">
                                    <div class="col">
                                        <label for="selectMoneda"><strong>MONEDA</strong></label>
                                        <select name="moneda" id="selectedMoneda" class="form-control"
                                            data-parsley-required="true">
                                            <option selected value="USD">USD</option>
                                            <option value="Bs">Bs</option>
                                        </select>
                                    </div>
                                    <div class="col">
                                        <label for="byCantidad_"><strong>POR LA CANTIDAD DE:</strong></label>
                                        <input type="text" name="cantidad" id="porCantidad" autocomplete="off"
                                            class="form-control text-center" placeholder="ingresa el monto">
                                        <div class="text-center text-danger d-none noCantPago">NO HA INGRESADO LA
                                            CANTIDAD</div>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col text-left">
                                        <strong>RECIBÍ DE: </strong>
                                    </div>
                                    <div class="col">
                                        <input type="text" name="recibide" id="ClienteName" class="form-control"
                                            placeholder="Nombre del Cliente" data-parsley-required="true">
                                        <div class="text-center text-danger d-none noClientePago">NO HAY CLIENTE</div>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col text-left">
                                        <strong>LA SUMA DE: </strong>
                                    </div>
                                    <div class="col">
                                        <textarea name="lasumade" id="cantidadLetras" cols="1" rows="1"
                                            class="form-control" data-parsley-required="true"></textarea>
                                        <!-- <input type="text" name="lasumade" id="suma_Recibo" class="form-control" placeholder="Cantidad en letras" > -->
                                        <div class="text-center text-danger d-none noSumaPago">NO HA INGRESADO LA SUMA
                                        </div>
                                    </div>

                                    <!--scripts numero a literal-->
                                    

                                </div>
                                <div class="row mt-2">
                                    <div class="col text-center">
                                        <label for="enConceptode"><strong>EN CONCEPTO DE:</strong></label>
                                        <textarea name="concepto" id="en_Conceptode" class="form-control" cols="30"
                                            rows="5" placeholder="Descripción" data-parsley-required="true"></textarea>
                                        <div class="text-center text-danger d-none noConceptoPago">NO HA INGRESADO EL
                                            CONCEPTO</div>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col">
                                        <button type="submit"
                                            class="btn btn-xs btn-info form-control guardarPago">GUARDAR PAGO &nbsp;<i
                                                class="fas fa-spinner fa-pulse d-none savePay"></i></button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <!-- end panel-body -->
                    </div>
                </div>
            </div>
            <!-- AGREGAR EL PRIMER ABONO -->
            <div class="row formbyAbono d-none w-50 m-auto">
                <div class="col-md-12">
                    <div class="panel panel-inverse">
                        <div class="panel-heading">
                            <h4 class="panel-title">FORMULARIO RECIBO ANTICIPO</h4>
                            <div class="panel-heading-btn">
                                <button class="btn btn-danger btn-xs closeFormAbono"> CERRAR </button>&nbsp;&nbsp;
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
                            <form id="formAbono" data-parsley-validate="true">
                                <div class="row">
                                    <div class="col text-center">
                                        <label for="CodeCotiza"><strong>Código de la cotización</strong></label>
                                        <input type="text" name="CodeCotiza" id="CodeCotiza"
                                            class="form-control text-center">
                                    </div>
                                    <div class="col text-center">
                                        <label for="precioDolar"><strong>Precio Dólar</strong></label>
                                        <input type="text" name="dolar" id="precioDolar"
                                            class="form-control text-center" value="<?php precioDolar($MySQLi) ?>">
                                    </div>
                                </div>
                                <div class="row text-center mt-3">
                                    <div class="col">
                                        <label for="selectMoneda"><strong>MONEDA</strong></label>
                                        <input type="hidden" name="action" value="Guardar primer abono">
                                        <input type="hidden" name="idCliente" id="id_Cliente">
                                        <input type="hidden" name="idUser" value="<?php echo $idUser ?>">
                                        <input type="hidden" name="miCiudad" value="<?php echo $miCiudad ?>">
                                        <input type="hidden" name="idCotizacion" id="idCotizacion_Recibo">
                                        <select name="moneda" id="selectMoneda" class="form-control"
                                            data-parsley-required="true">
                                            <option selected value="USD">USD</option>
                                            <option value="Bs">Bs</option>
                                        </select>
                                    </div>
                                    <div class="col">
                                        <label for="byCantidad_"><strong>POR LA CANTIDAD DE:</strong></label>
                                        <input type="text" name="cantidad" id="byCantidad_" autocomplete="off"
                                            class="form-control text-center" placeholder="ingresa el monto">
                                        <div class="text-center text-danger d-none noCantRecibo">NO HA INGRESADO LA
                                            CANTIDAD</div>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col text-left">
                                        <strong>RECIBÍ DE: </strong>
                                    </div>
                                    <div class="col">
                                        <input type="text" name="recibide" id="name_Cliente" class="form-control"
                                            placeholder="Nombre del Cliente" data-parsley-required="true">
                                        <div class="text-center text-danger d-none noClienteRecibo">NO HAY CLIENTE</div>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col text-left">
                                        <strong>LA SUMA DE: </strong>
                                    </div>
                                    <div class="col">
                                        <textarea name="lasumade" id="suma_Recibo" cols="1" rows="1"
                                            class="form-control" data-parsley-required="true"></textarea>
                                        <!-- <input type="text" name="lasumade" id="suma_Recibo" class="form-control" placeholder="Cantidad en letras" > -->
                                        <div class="text-center text-danger d-none noSumaRecibo">NO HA INGRESADO LA SUMA
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col text-center">
                                        <label for="enConceptode"><strong>EN CONCEPTO DE:</strong></label>
                                        <textarea name="concetpde" id="enConceptode" class="form-control" cols="30"
                                            rows="5" placeholder="Descripción del Abono"
                                            data-parsley-required="true"></textarea>
                                        <div class="text-center text-danger d-none noConceptoRecibo">NO HA INGRESADO EL
                                            CONCEPTO</div>
                                    </div>
                                </div>
                                <div class="row mt-2 text-center">
                                    <div class="col">
                                        <label for="byAnticipo"><strong>ANTICIPO</strong></label>
                                        <input type="text" name="anticipo" id="byAnticipo"
                                            class="form-control text-center" data-parsley-required="true"
                                            data-parsley-type="number">
                                        <div class="text-center text-danger d-none noAnticipoRecibo">Vacío</div>
                                    </div>
                                    <div class="col">
                                        <label for="SaldoAct"><strong>SALDO ACTUAL</strong></label>
                                        <input type="text" name="saldoAct" id="SaldoAct"
                                            class="form-control text-center" data-parsley-required="true"
                                            data-parsley-type="number">
                                        <div class="text-center text-danger d-none noSaldoActual">Vacío</div>
                                    </div>
                                </div>
                                <div class="row mt-2 text-center">
                                    <div class="col">
                                        <label for="SaldoAnt"><strong>SALDO ANTERIOR</strong></label>
                                        <input type="text" name="saldoAnt" id="SaldoAnt"
                                            class="form-control text-center" data-parsley-required="true"
                                            data-parsley-type="number">
                                        <div class="text-center text-danger d-none noSaldoAnterior">Vacío</div>
                                    </div>
                                    <div class="col">
                                        <label for="byTotal_"><strong>TOTAL</strong></label>
                                        <input type="text" name="total" id="byTotal_" class="form-control text-center"
                                            data-parsley-required="true" data-parsley-type="number">
                                        <div class="text-center text-danger d-none noTotalRecibo">Vacío</div>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col">
                                        <button type="submit"
                                            class="btn btn-xs btn-info form-control guardarAbono">GUARDAR ABONO &nbsp;<i
                                                class="fas fa-spinner fa-pulse saveAbonoEfect1 d-none"></i></button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- AGREGAR EL PRIMER ABONO (VENTA AL CRÉDITO) -->
            <div class="row formCredito d-none w-50 m-auto">
                <div class="col-md-12">
                    <div class="panel panel-inverse">
                        <div class="panel-heading">
                            <h4 class="panel-title">FORMULARIO RECIBO CRÉDITO</h4>
                            <div class="panel-heading-btn">
                                <button class="btn btn-danger btn-xs closeFormCredito"> CERRAR </button>&nbsp;&nbsp;
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
                            <form id="formCredito" data-parsley-validate="true">
                                <div class="row">
                                    <div class="col text-center">
                                        <label for="CCotizaCredito"><strong>Código de la cotización</strong></label>
                                        <input type="text" name="CodeCotiza" id="CCotizaCredito"
                                            class="form-control text-center">
                                    </div>
                                    <div class="col text-center">
                                        <label for="preDolarCredito"><strong>Precio Dólar</strong></label>
                                        <input type="text" name="dolar" id="preDolarCredito"
                                            class="form-control text-center" value="<?php precioDolar($MySQLi) ?>">
                                    </div>
                                </div>
                                <div class="row text-center mt-3">
                                    <div class="col">
                                        <label for="selectMonedaCredito"><strong>MONEDA</strong></label>
                                        <input type="hidden" name="action" value="GuardaraAbonoCredito">
                                        <input type="hidden" name="idCliente" id="idClienteCredito">
                                        <input type="hidden" name="idUser" value="<?php echo $idUser ?>">
                                        <input type="hidden" name="miCiudad" value="<?php echo $miCiudad ?>">
                                        <input type="hidden" name="idCotizacion" id="idCotizacionCredito">
                                        <input type="hidden" name="vendedor" value="<?= $datosUser['Nombres'] . ' ' . $datosUser['Apellidos'] ?>">
                                        <select name="moneda" id="selectMonedaCredito" class="form-control"
                                            data-parsley-required="true">
                                            <option selected value="USD">USD</option>
                                            <option value="Bs">Bs</option>
                                        </select>
                                    </div>
                                    <div class="col">
                                        <label for="porCantidadCredito"><strong>POR LA CANTIDAD DE:</strong></label>
                                        <input type="text" name="cantidad" id="porCantidadCredito" autocomplete="off"
                                            class="form-control text-center" placeholder="ingresa el monto">
                                        <div class="text-center text-danger d-none noCantRecibo">NO HA INGRESADO LA
                                            CANTIDAD</div>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col text-left">
                                        <strong>RECIBÍ DE: </strong>
                                    </div>
                                    <div class="col">
                                        <input type="text" name="recibide" id="nameClienteCredito" class="form-control"
                                            placeholder="Nombre del Cliente" data-parsley-required="true">
                                        <div class="text-center text-danger d-none noClienteRecibo">NO HAY CLIENTE</div>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col text-left">
                                        <strong>LA SUMA DE: </strong>
                                    </div>
                                    <div class="col">
                                        <textarea name="lasumade" id="laSumaDeRecibo" cols="1" rows="1"
                                            class="form-control" data-parsley-required="true"></textarea>
                                        <!-- <input type="text" name="lasumade" id="suma_Recibo" class="form-control" placeholder="Cantidad en letras" > -->
                                        <div class="text-center text-danger d-none noSumaRecibo">NO HA INGRESADO LA SUMA
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col text-center">
                                        <label for="ConcetoCredito"><strong>EN CONCEPTO DE:</strong></label>
                                        <textarea name="concetpde" id="ConcetoCredito" class="form-control" cols="30"
                                            rows="5" placeholder="Descripción del Abono"
                                            data-parsley-required="true"></textarea>
                                        <div class="text-center text-danger d-none noConceptoRecibo">NO HA INGRESADO EL
                                            CONCEPTO</div>
                                    </div>
                                </div>
                                <div class="row mt-2 text-center">
                                    <div class="col">
                                        <label for="AnticipoCredito"><strong>ANTICIPO</strong></label>
                                        <input type="text" name="anticipo" id="AnticipoCredito"
                                            class="form-control text-center" data-parsley-required="true"
                                            data-parsley-type="number">
                                        <div class="text-center text-danger d-none noAnticipoRecibo">Vacío</div>
                                    </div>
                                    <div class="col">
                                        <label for="SaldoActCredito"><strong>SALDO ACTUAL</strong></label>
                                        <input type="text" name="saldoAct" id="SaldoActCredito"
                                            class="form-control text-center" data-parsley-required="true"
                                            data-parsley-type="number">
                                        <div class="text-center text-danger d-none noSaldoActual">Vacío</div>
                                    </div>
                                </div>
                                <div class="row mt-2 text-center">
                                    <div class="col">
                                        <label for="SaldoAntCredito"><strong>SALDO ANTERIOR</strong></label>
                                        <input type="text" name="saldoAnt" id="SaldoAntCredito"
                                            class="form-control text-center" data-parsley-required="true"
                                            data-parsley-type="number">
                                        <div class="text-center text-danger d-none noSaldoAnterior">Vacío</div>
                                    </div>
                                    <div class="col">
                                        <label for="TotalCredito"><strong>TOTAL</strong></label>
                                        <input type="text" name="total" id="TotalCredito"
                                            class="form-control text-center" data-parsley-required="true"
                                            data-parsley-type="number">
                                        <div class="text-center text-danger d-none noTotalRecibo">Vacío</div>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col">
                                        <button type="submit"
                                            class="btn btn-xs btn-info form-control formularioCredito">GUARDAR ABONO
                                            &nbsp;<i class="fas fa-spinner fa-pulse d-none saveAbCredit"></i></button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Modal envicar cotizacion por correo -->
            <div class="modal fade" id="sendMail">
                <div class="modal-dialog modal-sm">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Enviar correo</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        </div>
                        <div class="modal-body">
                            <form id="sendMailAndCotiza" action="Reportes/pdf.php" method="POST">
                                <div class="row">
                                    <div class="col">
                                        <label for="Correo">Correo</label>
                                        <input type="text" name="idCotizacion" id="idCotiza_Mail">
                                        <input type="hidden" name="action" value="sendMailCotizacion">
                                        <input type="hidden" name="sucursal" value="<?php echo $miCiudad ?>">
                                        <input type="email" name="correo" id="Correo" class="form-control"
                                            value="<?php echo $dataCliente['Correo'] ?>" required="">
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col">
                                        <label for="Asunto">Asunto</label>
                                        <input type="text" name="asunto" id="Asunto" class="form-control" required="">
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col">
                                        <label for="Mensaje">Mensaje</label>
                                        <textarea name="mensaje" id="Mensaje" cols="30" rows="5" class="form-control"
                                            required=""></textarea>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col">
                                        <button type="submit" class="btn btn-block btn-primary tbn-change">Enviar
                                            Cotización &nbsp;<i
                                                class="fas fa-spinner fa-pulse d-none btn-sendCotiza"></i></button>
                                    </div>
                                </div>
                            </form>
                            <!-- <div class="row text-center">
									<div class="col">
										<label for="CantidadProdTemp_">Cantidad</label>
										<input type="hidden" name="ClaveTemp" id="Clave_Temp_">
										<input type="hidden" name="id" id="idProdTemp_">
										<input type="text" name="Cantidad" id="CantidadProdTemp_" class="form-control">
									</div>
								</div>
								<div class="row mt-2 text-center">
									<div class="col">
										<label for="PrecioProdTemp_">Precio Especial</label>
										<input type="text" name="PrecioEspecial" id="PrecioProdTemp_" class="form-control">
									</div>
								</div>
								<div class="row mt-2">
									<div class="col">
										<button class="btn btn-xs btn-info form-control actualizarProductoTemp_">ACTUALIZAR PRODUCTO</button>
									</div>
								</div> -->
                        </div>
                    </div>
                </div>
            </div>
            <!-- Modal Facturazion -->
            <div class="modal fade " id="modalFacturacion">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content ">
                        <div class="modal-header ">
                            <h4 class="modal-title w-100 text-center"><strong> FACTURA ELECTRONICA</strong></h4>
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        </div>
                        <div class="modal-body">


                            <div class="detallesFactura">
                                <!-- aki se esta imprimiendo los inputs factura -->
                                <script>
                                function actualizarclientCode() {
                                    let iniciales = "";
                                    let clientReasonSocial = document.getElementById("clientReasonSocial").value;
                                    for (x = 0; x < clientReasonSocial.length; x++) {
                                        if (x == 0) {
                                            iniciales = iniciales + clientReasonSocial.charAt(x);
                                        }
                                        if (clientReasonSocial.charAt(x + 1) != ' ') {
                                            if (clientReasonSocial.charAt(x) == ' ') {
                                                iniciales = iniciales + clientReasonSocial.charAt(x + 1);
                                            }
                                        }
                                    }
                                    document.getElementById("clientCode").value = "CLIENT-" + iniciales +
                                        clientReasonSocial.length;
                                }
                                </script>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <a href="javascript:;" class="btn btn-icon btn-circle btn-primary btn-scroll-to-top fade"
            data-click="scroll-top"><i class="fa fa-angle-up"></i></a>
        <?php include 'php/footer.php'; ?>
    </div>
    <?php include 'php/script_entregadas.php'; ?>
</body>

</html>