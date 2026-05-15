<div id="sidebar" class="sidebar ">
	<div data-scrollbar="true" data-height="100%">
		<ul class="nav">
			<li class="nav-profile">
				<a href="javascript:;" data-toggle="nav-profile">
					<div class="cover with-shadow"></div>
					<div class="image">
						<img title="<?php echo $datosUser['Cargo'] ?>" src="assets/img/user/<?php echo $datosUser['Avatar'] ?>" alt="<?php echo $datosUser['Cargo'] ?>">
					</div>
					<div class="info">
						<b class="caret"></b><?php echo $datosUser['Nombres']." ".$datosUser['Apellidos'] ; ?>
						<small><?php echo $datosUser['Cargo']?></small>
					</div>
				</a>
			</li>
			<li>
				<ul class="nav nav-profile">
					<li>
						<a href="salir.php">
							<i class="fa fa-power-off text-white bg-danger"></i> <span class="text-white" style="letter-spacing: 1px">SALIR</span>
						</a>
					</li>
				</ul>
			</li>
		</ul>
		<ul class="nav">
			<li class="nav-header text-center f-s-16"><?php echo strtoupper($datosUser['Ciudad']) ?></li>
			<li class="">
				<a href=" /"><i class="fa fa-home bg-dark "></i><span>INICIO</span></a>
			</li><?php
			if ($_SESSION['Rango']) { ?>
				<li>						
					<a href="?root=nuevoproducto"><i class="fa fa-barcode bg-blue f-s-20"></i> 
						<span>AGREGAR PRODUCTO</span> 
					</a>
				</li>
				<li>
					<a href="?root=productos2"><i class="fa fa-barcode bg-pink f-s-20"></i>
						<span>LISTA DE PRODUCTOS</span> 
					</a>
				</li>
<?php
			if ($_SESSION['Rango']=='2') { ?>				<li>
					<a href="?root=editarproducto"><i class="fa fa-edit  f-s-20"></i>
						<span>EDITAR PRODUCTO</span> 
					</a>
				</li><?php   }
			}?>
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
                <a href="?root=misclientes"><i class="fa fa-address-card bg-red"></i>
                    <span>MIS CLIENTES </span>
                </a>
            </li>
			

			<!-- ENVIOS PRODUCTOS -->
			<li class="has-sub">
				<a href="javascript:;"><b class="caret"></b><i class="fa fa-paper-plane bg-green" style="font-size: 20px"></i> 
					<span>ENVIOS PRODUCTOS</span> 
				</a>
				<ul class="sub-menu">
					<li><a href="?root=enviar_productos"><i class="fa fa-truck text-primary" style="font-size: 20px"></i>
						<span>ENVIAR PRODUCTOS</span></a>
					</li>

					<li><a href="?root=enviar_lista"><i class="fa fa-list-ol text-orange" style="font-size: 20px"></i>
						<span>LISTA ENVIOS</span></a>
					</li>

					<li><a href="?root=enviar_recibir"><i class="fa fa-truck text-success" style="font-size: 20px"></i>
						<span>RECIBIR PRODUCTOS</span></a>
					</li>
				</ul>
			</li>


			
		  <li class="has-sub">
		  	<a href="javascript:;"><b class="caret"></b><i class="ion-ios-podium bg-gradient-orange"></i>
		  		<span>COTIZACIONES</span>
		  	</a>
		  	<ul class="sub-menu">
		  		<li>
		  			<a href="?root=generar"><i class="fas fa-sync fa-spin" style="font-size: 20px"></i>
		  				<span> &nbsp;&nbsp;&nbsp;&nbsp;GENERAR</span> 
		  			</a>
		  		</li>
		  		<li>
		  			<a href="?root=generadas"><i class="fa fa-address-book" style="font-size: 20px"></i>
		  				<span> &nbsp;&nbsp;&nbsp;&nbsp;GENERARADAS</span> 
		  			</a>
		  		</li>
		  		<li>
		  			<a href="?root=entregadas"><i class="fa fa-paper-plane" style="font-size: 20px"></i> 
		  				<span> &nbsp;&nbsp;&nbsp;&nbsp;ENTREGADAS</span> 
		  			</a>
		  		</li>
		  		<li class="has-sub">
		  			<a href="javascript:;"><b class="caret"></b><i class="fas fa-handshake" style="font-size: 20px"></i> 
		  				<span>POR ANTICIPO</span>
		  			</a>
		  			<ul class="sub-menu">
		  				<li>
		  					<a href="?root=anticipo"><i class="fas fa-sync fa-spin" style="font-size: 20px"></i>
		  						<span> &nbsp;&nbsp;&nbsp;&nbsp;EN PROCESO</span> 
		  					</a>
		  				</li>
		  				<li>
		  					<a href="?root=anticipoCancelados"><i class="fas fa-check-circle" style="font-size: 20px"></i> 
		  						<span> &nbsp;&nbsp;&nbsp;&nbsp;CANCELADOS</span> 
		  					</a>
		  				</li>
		  			</ul>
		  		</li>
		  		<li class="has-sub">
		  			<a href="javascript:;"><b class="caret"></b><i class="fab fa-cc-visa" style="font-size: 20px"></i> 
		  				<span> &nbsp;&nbsp;&nbsp;&nbsp;AL CREDITO</span> 
		  			</a>
		  			<ul class="sub-menu">
		  				<li>
		  					<a href="?root=credito"><i class="fas fa-sync fa-spin" style="font-size: 20px"></i>
		  						<span> &nbsp;&nbsp;&nbsp;&nbsp;EN PROCESO</span> 
		  					</a>
		  				</li>
		  				<li>
		  					<a href="?root=creditosCancelados"><i class="fas fa-check-circle" style="font-size: 20px"></i> 
		  						<span> &nbsp;&nbsp;&nbsp;&nbsp;CANCELADOS</span> 
		  					</a>
		  				</li>
		  			</ul>
		  		</li>
		  		<li>
		  			<a href="?root=compradas"><i class="fa fa-dollar-sign" style="font-size: 20px"></i> 
		  				<span> &nbsp;&nbsp;&nbsp;&nbsp;VENDIDAS</span> 
		  			</a>
		  		</li>
		  		<li>
		  			<a href="?root=caducadas"><i class="fa fa-clock" style="font-size: 20px"></i> 
		  				<span> &nbsp;&nbsp;&nbsp;&nbsp;VENCIDAS</span> 
		  			</a>
		  		</li>
		  	</ul>
		  </li>
			<!-- MODIFICADAS -->
			<li class="has-sub">
				<a href="javascript:;"><b class="caret"></b><i class="fas fa-exclamation-triangle" style="font-size: 20px"></i> 
					<span> &nbsp;&nbsp;&nbsp;&nbsp;MODIFICADAS</span> 
				</a>
				<ul class="sub-menu">
					<li><a href="?root=directas"><i class="fas fa-archive" style="font-size: 20px"></i>
						<span> &nbsp;&nbsp;&nbsp;&nbsp;DIRECTAS</span></a>
					</li>
					<li><a href="?root=porAnticipo"><i class="fas fa-archive" style="font-size: 20px"></i>
						<span> &nbsp;&nbsp;&nbsp;&nbsp;ANTICIPO</span></a>
					</li>
				</ul>
			</li><li class="has-sub">
				<a href="javascript:;"><b class="caret"></b><i class="fas fa-file-invoice" style="font-size: 20px"></i> 
					<span> &nbsp;&nbsp;&nbsp;&nbsp;FACTURACION</span> 
				</a>
				<ul class="sub-menu">
					<li><a href="?root=facturacionListado"><i class="fa fa-list" style="font-size: 20px"></i>
						<span> &nbsp;&nbsp;&nbsp;&nbsp;LISTADO</span></a>
					</li>
					<li><a href="?root=facturacionEmision"><i class="fa fa-print" style="font-size: 20px"></i>
						<span> &nbsp;&nbsp;&nbsp;&nbsp;EMISION DIRECTA</span></a>
					</li>
				</ul>
			</li>
<?php include 'php/left_ventas.php'; ?>
			
			<li>
				<a href="?root=perfil"><i class="fa fa-user" style="font-size: 20px"></i> 
					<span > &nbsp;&nbsp;MI PERFIL </span> 
				</a>
			</li>



			<?php
			if ($_SESSION['Rango']=='2') { ?>	

			<li class="has-sub">
				<a href="javascript:;"><b class="caret"></b><i class="fa fa-bookmark bg-gradient-success" style="font-size: 20px"></i> 
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
				<a href="javascript:;"><b class="caret"></b><i class="fa fa-calendar bg-info" style="font-size: 20px"></i> 
					<span> &nbsp;HISTORIAL STOCK</span> 
				</a>
				<ul class="sub-menu">
					<li><a href="?root=historialStockProductos"><i class="fa fa-barcode" style="font-size: 20px"></i>
						<span> &nbsp;PRODUCTOS</span></a>
					</li>
					<li><a href="?root=historialStockProductosFiscales"><i class="fa fa-archive" style="font-size: 20px"></i>
						<span> &nbsp;PRODUCTOS &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;FISCALES</span></a>
					</li>
					<li><a href="?root=historialStockEnvioRecibo"><i class="fa fa-truck" style="font-size: 20px"></i>
						<span> &nbsp;ENVIOS-RECIBOS</span></a>
					</li>
				</ul>
			</li>

			<?php } ?>
			
			<?php
			if ($_SESSION['Rango']=='2') { ?>
				<li class="has-sub active">
					<a href="javascript:;"><b class="caret"></b><i class="fa fa-cogs bg-gradient-orange"></i>
						<span>ADMINISTRACION</span>
					</a>
					<ul class="sub-menu">
						<li>
							<a href="?root=clientes"><i class="fa fa-address-card"  style="font-size: 20px"></i> 
								<span>&nbsp;&nbsp;&nbsp;LISTA CLIENTES </span> 
							</a>
						</li>
						<li class="active">
							<a href="?root=usuarios"><i class="fa fa-users" style="font-size: 20px"></i> 
								<span> &nbsp;&nbsp;LISTA USUARIOS</span> 
							</a>
						</li>
						<li>
							<a href="?root=reportes"><i class="fa fa-chart-line text-white" style="font-size: 20px"></i>
								<span> &nbsp;&nbsp;REPORTE VENTAS</span> 
							</a>
						</li>						<li>
							<a href="?root=facturacionReportes"><i class="fa fa-list-alt" style="font-size: 20px"></i>
								<span> &nbsp;&nbsp;REPORTE FACTURAS</span> 
							</a>
						</li>
						<li>
							<a href="?root=comisiones"><i class="fa fa-chart-line text-white" style="font-size: 20px"></i>
								<span> &nbsp;&nbsp;COMISIONES</span> 
							</a>
						</li>					
						<li class="d-none">
							<a href="?root=sucursales"><i class="fa fa-building  text-white" style="font-size: 20px"></i>
								<span> &nbsp;&nbsp;SUCURSALES</span> 
							</a>
						</li>
						<li class="d-none">
							<a href="?root=cuentasmail"><i class="fa fa-envelope  text-white" style="font-size: 20px"></i>
								<span> &nbsp;&nbsp;CUENTAS MAIL</span> 
							</a>
						</li>
					</ul>
				</li><?php
		  } ?>
		</ul>
	</div>
</div>
<div class="sidebar-bg"></div>