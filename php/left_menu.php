<div id="sidebar" class="sidebar ">
    <div data-scrollbar="true" data-height="100%">
        <ul class="nav">
            <li class="nav-profile">
                <a href="javascript:;" data-toggle="nav-profile">
                    <div class="cover with-shadow"></div>
                    <div class="image">
                        <img title="<?php echo $datosUser['Cargo'] ?>"
                            src="assets/img/user/<?php echo $datosUser['Avatar'] ?>"
                            alt="<?php echo $datosUser['Cargo'] ?>">
                    </div>
                    <div class="info">
                        <b class="caret"></b>
                        <?php echo $datosUser['Nombres']." ".$datosUser['Apellidos'] ; ?>
                        <small>
                            <?php echo $datosUser['Cargo']?>
                        </small>
                    </div>
                </a>
            </li>
            <li>
                <ul class="nav nav-profile">
                    <!-- <li><a href="javascript:;"><i class="ion-ios-cog"></i> Settings</a></li>
					<li><a href="javascript:;"><i class="ion-ios-share-alt"></i> Send Feedback</a></li>
					<li><a href="javascript:;"><i class="ion-ios-help"></i> Helps</a></li> -->
                    <li>
                        <a href="salir.php">
                            <i class="fa fa-power-off text-white bg-danger"></i> <span class="text-white"
                                style="letter-spacing: 1px">SALIR</span>
                        </a>
                    </li>
                </ul>
            </li>
        </ul>
        <!-- bg-gradient-aqua -->
        <ul class="nav">
            <li class="nav-header text-center f-s-16"><?php echo strtoupper($datosUser['Ciudad']) ?></li>
            <?php
				if ($_SESSION['Rango']=='2') { ?>
            <li class="">
                <a href=" /">
                    <i class="fa fa-home bg-dark "></i>
                    <span>INICIO
                        <!-- <span class="label label-theme">NEW</span> -->
                    </span>
                </a>
            </li>
            <li>
                <a href="?root=nuevoproducto">
                    <i class="fa fa-barcode bg-blue f-s-20"></i>
                    <span>AGREGAR PRODUCTO
                        <!-- <span class="label label-theme">NEW</span> -->
                    </span>
                </a>
            </li>
            <li>
                <a href="?root=productos2">
                    <i class="fa fa-barcode bg-pink f-s-20"></i>
                    <span>LISTA DE PRODUCTOS
                        <!-- <span class="label label-theme">NEW</span> -->
                    </span>
                </a>
            </li>
            <?php
			if ($_SESSION['Rango']=='2') { ?>
            <li>
                <a href="?root=editarproducto">
                    <i class="fa fa-edit  f-s-20"></i>
                    <span>EDITAR PRODUCTO
                        <!-- <span class="label label-theme">NEW</span> -->
                    </span>
                </a>
            </li> <?php } ?>
            <?php
			if ($_SESSION['Rango']) { ?>

<li class="has-sub">
				<a href="javascript:;"><b class="caret"></b><i class="fa fa-archive bg-blue f-s-20" style="font-size: 20px"></i> 
					<span>PRODUCTOS FISCALES</span> 
				</a>
				<ul class="sub-menu">
					<li><a href="?root=productosFiscales"><i class="fa fa-archive text-primary" style="font-size: 20px"></i>
						<span>FISCALES SRL</span></a>
					</li>

					<li><a href="?root=productosFiscalesYuliimport"><i class="fa fa-archive text-success" style="font-size: 20px"></i>
						<span>FISCALES YULIIMPORT</span></a>
					</li>
				
					<li><a href="?root=productosFiscalesHistorial"><i class="fa fa-clock text-primary" style="font-size: 20px"></i>
						<span style="font-size: 10px">HISTORIAL FISCALES SRL</span></a>
					</li>
 
				</ul>
			</li>

            <?php                         } ?>
            <li>
                <a href="?root=misclientes">
                    <i class="fa fa-address-card bg-red"></i>
                    <span>MIS CLIENTES
                        <!-- <span class="label label-theme">NEW</span> -->
                    </span>
                </a>
            </li>


            <li class="has-sub">
                <a href="javascript:;">
                    <b class="caret"></b>
                    <i class="ion-ios-podium bg-gradient-orange"></i>
                    <span>COTIZACIONES</span>
                </a>
                <ul class="sub-menu">
                    <li>
                        <a href="?root=generar">
                            <i class="fas fa-sync fa-spin" style="font-size: 20px"></i>
                            <span> &nbsp;&nbsp;&nbsp;&nbsp;GENERAR
                                <!-- <span class="label label-theme">NEW</span> -->
                            </span>
                        </a>
                    </li>
                    <li>
                        <a href="?root=generadas">
                            <i class="fa fa-address-book" style="font-size: 20px"></i>
                            <span> &nbsp;&nbsp;&nbsp;&nbsp;GENERARADAS
                                <!-- <span class="label label-theme">NEW</span> -->
                            </span>
                        </a>
                    </li>
                    <li>
                        <a href="?root=entregadas">
                            <i class="fa fa-paper-plane" style="font-size: 20px"></i>
                            <span> &nbsp;&nbsp;&nbsp;&nbsp;ENTREGADAS
                                <!-- <span class="label label-theme">NEW</span> -->
                            </span>
                        </a>
                    </li>
                    <li class="has-sub">
                        <a href="javascript:;">
                            <b class="caret"></b>
                            <i class="fas fa-handshake" style="font-size: 20px"></i>
                            <span>POR ANTICIPO</span>
                        </a>
                        <ul class="sub-menu">
                            <li>
                                <a href="?root=anticipo">
                                    <i class="fas fa-sync fa-spin" style="font-size: 20px"></i>
                                    <span> &nbsp;&nbsp;&nbsp;&nbsp;EN PROCESO
                                        <!-- <span class="label label-theme">NEW</span> -->
                                    </span>
                                </a>
                            </li>
                            <li>
                                <a href="?root=anticipoCancelados">
                                    <i class="fas fa-check-circle" style="font-size: 20px"></i>
                                    <span> &nbsp;&nbsp;&nbsp;&nbsp;CANCELADOS
                                        <!-- <span class="label label-theme">NEW</span> -->
                                    </span>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="has-sub">
                        <a href="javascript:;">
                            <b class="caret"></b>
                            <i class="fab fa-cc-visa" style="font-size: 20px"></i>
                            <span> &nbsp;&nbsp;&nbsp;&nbsp;AL CREDITO
                                <!-- <span class="label label-theme">NEW</span> -->
                            </span>
                        </a>
                        <ul class="sub-menu">
                            <li>
                                <a href="?root=credito">
                                    <i class="fas fa-sync fa-spin" style="font-size: 20px"></i>
                                    <span> &nbsp;&nbsp;&nbsp;&nbsp;EN PROCESO
                                        <!-- <span class="label label-theme">NEW</span> -->
                                    </span>
                                </a>
                            </li>
                            <li>
                                <a href="?root=creditosCancelados">
                                    <i class="fas fa-check-circle" style="font-size: 20px"></i>
                                    <span> &nbsp;&nbsp;&nbsp;&nbsp;CANCELADOS
                                        <!-- <span class="label label-theme">NEW</span> -->
                                    </span>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a href="?root=compradas">
                            <i class="fa fa-dollar-sign" style="font-size: 20px"></i>
                            <span> &nbsp;&nbsp;&nbsp;&nbsp;VENDIDAS
                                <!-- <span class="label label-theme">NEW</span> -->
                            </span>
                        </a>
                    </li>
                    <li>
                        <a href="?root=caducadas">
                            <i class="fa fa-clock" style="font-size: 20px"></i>
                            <span> &nbsp;&nbsp;&nbsp;&nbsp;VENCIDAS
                                <!-- <span class="label label-theme">NEW</span> -->
                            </span>
                        </a>
                    </li>
                    <li>
                        <a href="?root=caducadascopy">
                            <i class="fa fa-clock" style="font-size: 20px"></i>
                            <span> &nbsp;&nbsp;&nbsp;&nbsp;LISTADO FACTURAS
                                <!-- <span class="label label-theme">NEW</span> -->
                            </span>
                        </a>
                    </li>
                </ul>
            </li>
<?php include 'php/left_ventas.php'; ?>

            <li>
                <a href="?root=perfil">
                    <i class="fa fa-user" style="font-size: 20px"></i>
                    <span> &nbsp;&nbsp;MI PERFIL
                        <!-- <span class="label label-theme">NEW</span> -->
                    </span>
                </a>
            </li>

            <?php
			if ($_SESSION['Rango']=='2') { ?>

            <li class="has-sub">
                <a href="javascript:;"><b class="caret"></b><i class="fa fa-bookmark bg-gradient-success"
                        style="font-size: 20px"></i>
                    <span> &nbsp;REPORTES CLIENTES</span>
                </a>
                <ul class="sub-menu">
                    <li><a href="?root=clientes1"><i class="fa fa-male" style="font-size: 20px"></i>
                            <span> &nbsp;CANTIDAD/UNIDAD <br> COMPRADA</span></a>
                    </li>
                    <li><a href="?root=clientes2"><i class="fa fa-female" style="font-size: 20px"></i>
                            <span> &nbsp;CANTIDAD/DINERO <br> COMPRADO </span></a>
                    </li>
                    <li><a href="?root=clientes3"><i class="fa fa-user-secret" style="font-size: 20px"></i>
                            <span> &nbsp;HABITUALES</span></a>
                    </li>
                    <li><a href="?root=clientes4"><i class="fa fa-user-circle" style="font-size: 20px"></i>
                            <span> &nbsp;NUEVOS COMPRADORES</span></a>
                    </li>
                </ul>
            </li>

            <?php } ?>



            <li class="has-sub">
                <a href="javascript:;">
                    <b class="caret"></b>
                    <i class="fa fa-cogs bg-gradient-orange"></i>
                    <span>ADMINISTRACION</span>
                </a>
                <ul class="sub-menu">

                    <li>
                        <a href="?root=clientes">
                            <i class="fa fa-address-card" style="font-size: 20px"></i>
                            <span>&nbsp;&nbsp;&nbsp;LISTA CLIENTES
                                <!-- <span class="label label-theme">NEW</span> -->
                            </span>
                        </a>
                    </li>
                    <li>
                        <a href="?root=usuarios">
                            <i class="fa fa-users" style="font-size: 20px"></i>
                            <span> &nbsp;&nbsp;LISTA USUARIOS
                                <!-- <span class="label label-theme">NEW</span> -->
                            </span>
                        </a>
                    </li>
                    <li>
                        <a href="?root=reportes">
                            <i class="fa fa-chart-line text-white" style="font-size: 20px"></i>
                            <span> &nbsp;&nbsp;REPORTE VENTAS
                                <!-- <span class="label label-theme">NEW</span> -->
                            </span>
                        </a>
                    </li>
                    <li>
                        <a href="?root=comisiones">
                            <i class="fa fa-chart-line text-white" style="font-size: 20px"></i>
                            <span> &nbsp;&nbsp;COMISIONES
                                <!-- <span class="label label-theme">NEW</span> -->
                            </span>
                        </a>
                    </li>
                    <li class="d-none">
                        <a href="?root=sucursales">
                            <i class="fa fa-building  text-white" style="font-size: 20px"></i>
                            <span> &nbsp;&nbsp;SUCURSALES
                                <!-- <span class="label label-theme">NEW</span> -->
                            </span>
                        </a>
                    </li>
                    <li class="d-none">
                        <a href="?root=cuentasmail">
                            <i class="fa fa-envelope  text-white" style="font-size: 20px"></i>
                            <span> &nbsp;&nbsp;CUENTAS MAIL
                                <!-- <span class="label label-theme">NEW</span> -->
                            </span>
                        </a>
                    </li>
                </ul>
            </li>

            <li class="nav-header text-center d-none">PRUEBA DE BOTONES</li>

            <li class="d-none">
                <a href="Reportes/pdf.php?idCotizacion=1" style="display: none;">
                    <i class="fa fa-file-pdf bg-green"></i>
                    <span>REPORTE PDF
                        <!-- <span class="label label-theme">NEW</span> -->
                    </span>
                </a>
            </li>
            <li class="d-none">
                <a href="Reportes/pdf.php?idEntregaN=1">
                    <i class="fa fa-file-pdf bg-green"></i>
                    <span>NOTA DE ENTREGA
                        <!-- <span class="label label-theme">NEW</span> -->
                    </span>
                </a>
            </li>
            <li class="d-none">
                <a href="Reportes/excel.php?idReporte=1">
                    <i class="fa fa-file-excel bg-warning"></i>
                    <span>REPORTE EXCEL
                        <!-- <span class="label label-theme">NEW</span> -->
                    </span>
                </a>
            </li><?php
				}else{ ?>
            <li class="">
                <a href=" / ">
                    <i class="fa fa-home bg-dark "></i>
                    <span>INICIO
                        <!-- <span class="label label-theme">NEW</span> -->
                    </span>
                </a>
            </li>
            <li>
                <a href="?root=misclientes">
                    <i class="fa fa-address-card bg-red"></i>
                    <span>MIS CLIENTES
                        <!-- <span class="label label-theme">NEW</span> -->
                    </span>
                </a>
            </li>
            <li class="has-sub">
                <a href="javascript:;">
                    <b class="caret"></b>
                    <i class="ion-ios-podium bg-gradient-orange"></i>
                    <span>COTIZACIONES</span>
                </a>
                <ul class="sub-menu">
                    <li>
                        <a href="?root=generar">
                            <i class="fas fa-sync fa-spin" style="font-size: 20px"></i>
                            <span> &nbsp;&nbsp;&nbsp;&nbsp;GENERAR
                                <!-- <span class="label label-theme">NEW</span> -->
                            </span>
                        </a>
                    </li>
                    <li>
                        <a href="?root=generadas">
                            <i class="fa fa-address-book" style="font-size: 20px"></i>
                            <span> &nbsp;&nbsp;&nbsp;&nbsp;GENERARADAS
                                <!-- <span class="label label-theme">NEW</span> -->
                            </span>
                        </a>
                    </li>
                    <li>
                        <a href="?root=entregadas">
                            <i class="fa fa-paper-plane" style="font-size: 20px"></i>
                            <span> &nbsp;&nbsp;&nbsp;&nbsp;ENTREGADAS
                                <!-- <span class="label label-theme">NEW</span> -->
                            </span>
                        </a>
                    </li>
                    <li class="has-sub">
                        <a href="javascript:;">
                            <b class="caret"></b>
                            <i class="fas fa-handshake" style="font-size: 20px"></i>
                            <span>POR ANTICIPO</span>
                        </a>
                        <ul class="sub-menu">
                            <li>
                                <a href="?root=anticipo">
                                    <i class="fas fa-sync fa-spin" style="font-size: 20px"></i>
                                    <span> &nbsp;&nbsp;&nbsp;&nbsp;EN PROCESO
                                        <!-- <span class="label label-theme">NEW</span> -->
                                    </span>
                                </a>
                            </li>
                            <li>
                                <a href="?root=anticipoCancelados">
                                    <i class="fas fa-check-circle" style="font-size: 20px"></i>
                                    <span> &nbsp;&nbsp;&nbsp;&nbsp;CANCELADOS
                                        <!-- <span class="label label-theme">NEW</span> -->
                                    </span>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="has-sub">
                        <a href="javascript:;">
                            <b class="caret"></b>
                            <i class="fab fa-cc-visa" style="font-size: 20px"></i>
                            <span> &nbsp;&nbsp;&nbsp;&nbsp;AL CREDITO
                                <!-- <span class="label label-theme">NEW</span> -->
                            </span>
                        </a>
                        <ul class="sub-menu">
                            <li>
                                <a href="?root=credito">
                                    <i class="fas fa-sync fa-spin" style="font-size: 20px"></i>
                                    <span> &nbsp;&nbsp;&nbsp;&nbsp;EN PROCESO
                                        <!-- <span class="label label-theme">NEW</span> -->
                                    </span>
                                </a>
                            </li>
                            <li>
                                <a href="?root=creditosCancelados">
                                    <i class="fas fa-check-circle" style="font-size: 20px"></i>
                                    <span> &nbsp;&nbsp;&nbsp;&nbsp;CANCELADOS
                                        <!-- <span class="label label-theme">NEW</span> -->
                                    </span>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a href="?root=compradas">
                            <i class="fa fa-dollar-sign" style="font-size: 20px"></i>
                            <span> &nbsp;&nbsp;&nbsp;&nbsp;VENDIDAS
                                <!-- <span class="label label-theme">NEW</span> -->
                            </span>
                        </a>
                    </li>
                    <li>
                        <a href="?root=caducadas">
                            <i class="fa fa-clock" style="font-size: 20px"></i>
                            <span> &nbsp;&nbsp;&nbsp;&nbsp;VENCIDAS
                                <!-- <span class="label label-theme">NEW</span> -->
                            </span>
                        </a>
                    </li>
                    <li>
                        <a href="?root=caducadascopy">
                            <i class="fa fa-clock" style="font-size: 20px"></i>
                            <span> &nbsp;&nbsp;&nbsp;&nbsp;LISTADO FACTURAS
                                <!-- <span class="label label-theme">NEW</span> -->
                            </span>
                        </a>
                    </li>
                </ul>
            </li>
            <li>
                <a href="?root=ventas">
                    <i class="fa fa-dollar-sign bg-indigo"></i>
                    <span>MIS VENTAS
                        <!-- <span class="label label-theme">NEW</span> -->
                    </span>
                </a>
            </li>
            <li>
                <a href="?root=perfil">
                    <i class="fa fa-user" style="font-size: 20px"></i>
                    <span> &nbsp;&nbsp;MI PERFIL
                        <!-- <span class="label label-theme">NEW</span> -->
                    </span>
                </a>
            </li>


            <?php
			if ($_SESSION['Rango']=='2') { ?>

            <li class="has-sub">
                <a href="javascript:;"><b class="caret"></b><i class="fa fa-bookmark bg-gradient-success"
                        style="font-size: 20px"></i>
                    <span> &nbsp;REPORTES CLIENTES</span>
                </a>
                <ul class="sub-menu">
                    <li><a href="?root=clientes1"><i class="fa fa-male" style="font-size: 20px"></i>
                            <span> &nbsp;CANTIDAD/UNIDAD <br> COMPRADA</span></a>
                    </li>
                    <li><a href="?root=clientes2"><i class="fa fa-female" style="font-size: 20px"></i>
                            <span> &nbsp;CANTIDAD/DINERO <br> COMPRADO </span></a>
                    </li>
                    <li><a href="?root=clientes3"><i class="fa fa-user-secret" style="font-size: 20px"></i>
                            <span> &nbsp;HABITUALES</span></a>
                    </li>
                    <li><a href="?root=clientes4"><i class="fa fa-user-circle" style="font-size: 20px"></i>
                            <span> &nbsp;NUEVOS COMPRADORES</span></a>
                    </li>
                </ul>
            </li>

            <?php } ?>



            <?php
			if ($_SESSION['Rango']=='2') { ?>

            <li class="has-sub">
                <a href="javascript:;"><b class="caret"></b><i class="fa fa-calendar bg-info"
                        style="font-size: 20px"></i>
                    <span> &nbsp;HISTORIAL STOCK</span>
                </a>
                <ul class="sub-menu">
                    <li><a href="?root=historialStockProductos"><i class="fa fa-barcode" style="font-size: 20px"></i>
                            <span> &nbsp;PRODUCTOS</span></a>
                    </li>
                    <li><a href="?root=historialStockProductosFiscales"><i class="fa fa-archive"
                                style="font-size: 20px"></i>
                            <span> &nbsp;PRODUCTOS &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;FISCALES</span></a>
                    </li>
                    <li><a href="?root=historialStockEnvioRecibo"><i class="fa fa-truck" style="font-size: 20px"></i>
                            <span> &nbsp;ENVIOS-RECIBOS</span></a>
                    </li>
                </ul>
            </li>

            <?php } ?>








            <?php
				}
			?>
        </ul>
    </div>
</div>
<div class="sidebar-bg"></div>