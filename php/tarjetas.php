<?php
	if ($_SESSION['Rango']=='2') { ?>
		<div class="panel panel-primary" data-sortable-id="ui-modal-notification-2">
			
			<div class="panel-heading"><H2><strong>&nbsp;&nbsp;YULI SRL</strong></H2>
				<h1 class="panel-title"></h1>
				<div class="panel-heading-btn text-center">
					Precio Dolar <strong>&nbsp;&nbsp;Bs&nbsp;&nbsp;</strong>
					<form id="savePrecioDolar" class="">
						<input type="hidden" name="action" value="actualizarDolar">
						<input type="text" name="precio" id="precio" class="form-control-sm text-center text-danger" value="<?php precioDolar($MySQLi) ?>">
						&nbsp;&nbsp;<input type="submit" class="btn btn-sm btn-inverse" value="ACTUALIZAR">&nbsp;&nbsp;
					</form>
				</div>
			</div>
			<div class="panel-body"><div class="respuesta"></div><?php
				$findSucursal = mysqli_query($MySQLi,"SELECT *FROM Sucursales ORDER BY `Sucursales`.`orden` ASC ");
				while ($dataSucu= mysqli_fetch_assoc($findSucursal)) {
				 	$Sucursal 	= $dataSucu['Sucursal'];
				 	$title 		= $dataSucu['title'];
					?>
				 	<div class="row">
						<div class="col text-center">
							<h3><?php echo $title ?></h3>
						</div>
					</div>
					<div class="row">
						<!-- VENTAS DEL MES -->
						<div class="col-xl-3 col-md-6">
							<div class="widget widget-stats bg-success">
								<div class="stats-icon"><i class="fa fa-dollar-sign" style="font-size: 65px"></i></div>
								<div class="stats-info">
									<h4>TOTAL VENTAS <?php echo strtoupper($mes) ?></h4>
									<p><?php
										$queryVentas	=	mysqli_query($MySQLi,"SELECT IFNULL(SUM(TotalVentaUS), 0)AS TotalVentaUS FROM Ventas WHERE Sucursal='$Sucursal' AND Fecha BETWEEN '$startBusqueda' AND  '$fecha' ")or die(mysqli_error($MySQLi));
										$dataVentas		=	mysqli_fetch_assoc($queryVentas);
										$TotalVentas 	=	$dataVentas['TotalVentaUS'];
										echo "$ ". number_format(($TotalVentas),2);?>
									</p>
								</div>
								<div class="stats-link">
									<a href="?root=reportes">Ver Detalles <i class="fa fa-arrow-alt-circle-right"></i></a>
								</div>
							</div>
						</div>
						<!-- COTIZACIONES DEL MES -->
						<div class="col-xl-3 col-md-6">
							<div class="widget widget-stats bg-info">
								<div class="stats-icon"><i class="fa fa-chart-line" style="font-size: 65px"></i></div>
								<div class="stats-info">
									<h4>COTIZACIONES ENTREGADAS</h4>
									<p><?php
										$queryEntregadas	=	mysqli_query($MySQLi,"SELECT idCotizacion FROM Cotizaciones WHERE Estado=1 AND Fecha BETWEEN '$startBusqueda' AND '$fecha' AND Sucursal='$Sucursal' ");
										$resultGeneradas 	=	mysqli_num_rows($queryEntregadas);
										if ($resultGeneradas>0) {
											echo $resultGeneradas;
										}else{
											echo "0";
										}?>
									</p>	
								</div>
								<div class="stats-link">
									<a href="?root=entregadas">Ver Detalles <i class="fa fa-arrow-alt-circle-right"></i></a>
								</div>
							</div>
						</div>
						<!-- PRODUCTOS VENDIDO -->
						<div class="col-xl-3 col-md-6">
							<div class="widget widget-stats bg-orange">
								<div class="stats-icon"><i class="fa fa-chart-pie" style="font-size: 65px"></i></div>
								<div class="stats-info">
									<h4>PRODUCTOS VENDIDOS</h4>
									<p><?php
										$queryCompradas	=	mysqli_query($MySQLi,"SELECT SUM(Cantidad) AS TotalVentas FROM Ventas WHERE Fecha BETWEEN '$startBusqueda' AND '$fecha' AND Sucursal='$Sucursal' AND Estado=0 ");
										$resultCompradas=	mysqli_num_rows($queryCompradas);
										if ($resultCompradas>0) {
											$datosVentas=	mysqli_fetch_assoc($queryCompradas);
											$TotalVentas=	$datosVentas['TotalVentas'];
											if ($TotalVentas=='') {
												echo "0";
											}else{
												echo $TotalVentas;
											}
										}else{
											echo "0";
										}?>
									</p>
								</div>
								<div class="stats-link">
									<a href="?root=reportes">Ver Detalles <i class="fa fa-arrow-alt-circle-right"></i></a>
								</div>
							</div>
						</div>
						<!-- CLIENTES DEL MES -->
						<div class="col-xl-3 col-md-6">
							<div class="widget widget-stats bg-red">
								<div class="stats-icon"><i class="fa fa-users" style="font-size: 65px"></i></div>
								<div class="stats-info">
									<h4>CLIENTES REGISTRADOS</h4>
									<p><?php
										$queryClientes	=	mysqli_query($MySQLi,"SELECT * FROM Clientes WHERE  Sucursal='$Sucursal' AND Fecha_Reg BETWEEN '$startBusqueda' AND '$fecha' ");
										$resultClientes	=	mysqli_num_rows($queryClientes);
										if ($resultClientes>0) {
											echo $resultClientes;
										}else{
											echo "0";
										}?>
									</p>	
								</div>
								<div class="stats-link">
									<a href="?root=clientes&Sucursal=<?php echo $Sucursal ?>">Ver Detalles <i class="fa fa-arrow-alt-circle-right"></i></a>
								</div>
							</div>
						</div>
					</div><?php
				} ?>
			</div>
		</div> <?php
	}else{ ?>
		<div class="row">
			<div class="col-xl-3 col-md-6">
				<div class="widget widget-stats bg-success">
					<div class="stats-icon"><i class="fa fa-dollar-sign" style="font-size: 65px"></i></div>
					<div class="stats-info">
						<h4>MIS VENTAS <?php echo strtoupper($mes) ?></h4>
						<p><?php
							$queryVentas	=	mysqli_query($MySQLi,"SELECT SUM(TotalVentaUS)AS TotalVentaUS FROM Ventas WHERE idUser='$idUser' AND Fecha BETWEEN '$startBusqueda' AND  '$fecha' ")or die(mysqli_error($MySQLi));
							$dataVentas		=	mysqli_fetch_assoc($queryVentas);
							$TotalVentas 	=	($dataVentas['TotalVentaUS']);
							$GranTotal 		=	$TotalVentas;	//+$TotalCredit;//+$TotalAbonos;
							echo "$ ". number_format(($GranTotal),2);?>
						</p>
					</div>
					<div class="stats-link">
						<a href="?root=ventas">Ver Detalles <i class="fa fa-arrow-alt-circle-right"></i></a>
					</div>
				</div>
			</div>
			<div class="col-xl-3 col-md-6">
				<div class="widget widget-stats bg-info">
					<div class="stats-icon"><i class="fa fa-chart-line" style="font-size: 65px"></i></div>
					<div class="stats-info">
						<h4>COTIZACIONES ENTREGADAS</h4>
						<p><?php
							$queryEntregadas	=	mysqli_query($MySQLi,"SELECT idCotizacion FROM Cotizaciones WHERE Estado=1 AND idUser='$idUser' AND Fecha BETWEEN '$startBusqueda' AND '$fecha' ");
							$resultGeneradas 	=	mysqli_num_rows($queryEntregadas);
							if ($resultGeneradas>0) {
								echo $resultGeneradas;
							}else{
								echo "0";
							} ?>
						</p>	
					</div>
					<div class="stats-link">
						<a href="?root=entregadas">Ver Detalles <i class="fa fa-arrow-alt-circle-right"></i></a>
					</div>
				</div>
			</div>			
			<!-- PRODUCTOS VENDIDOS -->
			<div class="col-xl-3 col-md-6">
				<div class="widget widget-stats bg-orange">
					<div class="stats-icon"><i class="fa fa-chart-pie" style="font-size: 65px"></i></div>
					<div class="stats-info">
						<h4>PRODUCTOS VENDIDOS</h4>
						<p><?php
							$queryCompradas	=	mysqli_query($MySQLi,"SELECT SUM(Cantidad) AS TotalVentas FROM Ventas WHERE Fecha BETWEEN '$startBusqueda' AND '$fecha' AND idUser='$idUser' AND Estado=0 ");
							$resultCompradas=	mysqli_num_rows($queryCompradas);
							if ($resultCompradas>0) {
								$datosVentas=	mysqli_fetch_assoc($queryCompradas);
								$TotalVentas=	$datosVentas['TotalVentas'];
								if ($TotalVentas=='') {
									echo "0";
								}else{
									echo $TotalVentas;
								}
							} ?>
						</p>
					</div>
					<div class="stats-link">
						<a href="?root=ventas">Ver Detalles <i class="fa fa-arrow-alt-circle-right"></i></a>
					</div>
				</div>
			</div>
			<div class="col-xl-3 col-md-6">
				<div class="widget widget-stats bg-red">
					<div class="stats-icon"><i class="fa fa-users" style="font-size: 65px"></i></div>
					<div class="stats-info">
						<h4>MIS CLIENTES</h4>
						<p><?php
							$queryClientes	=	mysqli_query($MySQLi,"SELECT * FROM Clientes WHERE Registrador='$idUser' ");
							$resultClientes	=	mysqli_num_rows($queryClientes);
							if ($resultClientes>0) {
								echo $resultClientes;
							}else{
								echo "0";
							} ?>
						</p>	
					</div>
					<div class="stats-link">
						<a href="?root=misclientes">Ver Detalles <i class="fa fa-arrow-alt-circle-right"></i></a>
					</div>
				</div>
			</div>
		</div>
		<div class="panel panel-primary" id="mainBusqueda" data-sortable-id="ui-modal-notification-2">
			<div class="panel-heading">
				<h4 class="panel-title">MI COMISIÓN DE VENTAS DEL MES DE  &nbsp;&nbsp;
					<span style="text-transform: uppercase;letter-spacing: 1px;font-size: 16px"><?php echo $mes?></span>
				</h4>
				<div class="panel-heading-btn">
					<button class="btn btn-xs btn-primary buscarComisiones" title="Buscar Comisiones" id="<?php echo $idUser ?>"><i class="fa fa-search"> Buscar</i></button>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
					<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-success" data-click="panel-reload"><i class="fa fa-redo"></i></a>
					<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>
					<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-danger" data-click="panel-remove"><i class="fa fa-times"></i></a>
				</div>
			</div>
			<div class="panel-body">
				<form  data-parsley-validate="true" class="w-75 m-auto d-none" id="searchComisiones">
					<div class="row">
						<div class="col text-center">
							<label for="fechaInicio">Fecha de inicio</label>
							<input type="hidden" name="action" value="buscarMis_Comisiones">
							<input type="hidden" name="idUser" value="<?php echo $idUser ?>">
							<input type="hidden" name="sucursal" value="<?php echo $miCiudad ?>">
							<input type="date" name="inicio" id="fechaInicio" class="form-control text-center" value="<?php echo $startBusqueda ?>" data-parsley-required="true">
						</div>
						<div class="col text-center">
							<label for="fechaFin">Fecha final</label>
							<input type="date" name="fin" id="fechaFin" class="form-control text-center" value="<?php echo $fecha ?>" data-parsley-required="true">
						</div>
						<div class="col">
							<label for="buscar">&nbsp;&nbsp;&nbsp;</label>
							<button type="submit" class="form-control btn btn-xs btn-primary buscarMisComisiones">Buscar &nbsp;<i class="fas fa-spinner fa-pulse d-none btn-Buscar"></i></button>
						</div>
					</div>
				</form><br>
				<div class="row " id="showComision">
					<div class="col-md-4"></div>
					<div class="col-md-4 text-center">
						<label for="miComision">TU COMISIÓN ES DE:</label>
						<input type="text" name="comision" id="miComision" class="form-control text-center text-danger">
					</div>
				</div>
			</div>
		</div>

		<div class="panel panel-primary d-none" id="resultBusqueda" data-sortable-id="ui-modal-notification-2">
			<div class="panel-heading">
				<h4 class="panel-title">MI COMISIÓN DE VENTAS DESDE EL &nbsp;&nbsp;
					<span style="text-transform: uppercase;letter-spacing: 1px;font-size: 16px"><?php echo $mes?></span>
				</h4>
				<div class="panel-heading-btn">
					<button class="btn btn-xs btn-primary buscarComisiones" title="Buscar Comisiones" id="<?php echo $idUser ?>"><i class="fa fa-search"> Buscar</i></button>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
					<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-success" data-click="panel-reload"><i class="fa fa-redo"></i></a>
					<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>
					<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-danger" data-click="panel-remove"><i class="fa fa-times"></i></a>
				</div>
			</div>
			<div class="panel-body">
				<form  data-parsley-validate="true" class="w-75 m-auto d-none" id="searchComisiones">
					<div class="row">
						<div class="col text-center">
							<label for="fechaInicio">Fecha de inicio</label>
							<input type="date" name="inicio" id="fechaInicio" class="form-control text-center" data-parsley-required="true" required="">
						</div>
						<div class="col text-center">
							<label for="fechaFin">Fecha final</label>
							<input type="date" name="fin" id="fechaFin" class="form-control text-center" data-parsley-required="true" required="">
						</div>
						<div class="col">
							<label for="buscar">&nbsp;&nbsp;&nbsp;</label>
							<input type="submit" value="Buscar" class="form-control btn btn-xs btn-primary">
						</div>
					</div>
				</form>
			</div>
		</div><?php
	}	
?>