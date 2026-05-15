<div id="header" class="header navbar-default">
	<div class="navbar-header">
		<a href="#" class="navbar-brand">
			<span class="navbar-logo">
				<!-- <i class="ion-ios-cloud"></i> -->
				<img src="assets/img/logo.png" alt="Importadora YULI" width="60%">
			</span> <!-- <b>Color</b> Admin -->
		</a>
		<button type="button" class="navbar-toggle" data-click="sidebar-toggled">
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
		</button>
	</div>
	<ul class="navbar-nav navbar-right">
		<?php
			// $ch =	curl_init();
			// curl_setopt($ch, CURLOPT_URL, 'https://www.cotizacion.co/bolivia/precio-del-dolar.php');
			// curl_setopt($ch, CURLOPT_USERAGENT, 'Mozila/4.0 (compatible; MISE 5.01; Windows NT 5.0)');
			// curl_setopt($ch, CURLOPT_HTTPHEADER, array("Accept-Language: es-es,en"));
			// curl_setopt($ch, CURLOPT_TIMEOUT, 10);
			// curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
			// curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

			// /*	GUARDA VALORES DE LA PAGINA */
			// $result =	curl_exec($ch);
			// $error 	=	curl_error($ch);
			// curl_close($ch);

			// /*Parsear los datos*/
			// preg_match_all("(<span id=\"cotizaciondeldia-dol-ch\">(.*)</p>)siU", $result, $respuesta);
			// $PrecioDolar 	=	$respuesta[1][0];

			// $updateDolar	=	mysqli_query($MySQLi,"UPDATE PrecioDolar SET Precio='$PrecioDolar' ");
		?>
		<!-- <span class="mt-2 text-danger"><h3>Precio dólar:</h3></span> -->
		<li class="navbar-form">
			<!-- <form action="#" method="POST">
				<div class="form-group">
					<input type="text" class="form-control text-center text-danger f-s-16" name="precioDolar" id="PrecioDolar" placeholder="Precio del dólar" value="<?php //echo $PrecioDolar ?>">
					<button type="button" class="btn btn-search"><i class="ion-ios-search"></i></button>
				</div>
			</form> -->
		</li>
		<li class="dropdown navbar-user">			
			<a href="#" class="dropdown-toggle" data-toggle="dropdown">
				<img title="<?php echo $datosUser['Cargo'] ?>" src="assets/img/user/<?php echo $datosUser['Avatar'] ?>" alt="<?php echo $datosUser['Cargo'] ?>">
				<!-- <img src="assets/img/user/male.png" alt=""> --> 
				<span class="d-none d-md-inline" style="color: #fff"><?php echo $datosUser['Nombres']." ".$datosUser['Apellidos'] ; ?></span> <b class="caret"></b>
			</a>
			<div class="dropdown-menu dropdown-menu-right">
				<a href="?root=perfil" class="dropdown-item">
					<i class="fa fa-user text-primary f-s-20" style="letter-spacing: 1PX"></i>&nbsp;&nbsp;MI PERFIL
				</a>
				<a href="?root=ventas" class="dropdown-item">
					<i class="fa fa-dollar-sign text-primary f-s-20" style="letter-spacing: 1PX"></i>&nbsp;&nbsp;MIS VENTAS
				</a>
				<!-- <a href="javascript:;" class="dropdown-item"><span class="badge badge-danger pull-right">2</span> Inbox</a> -->
				<!-- <a href="javascript:;" class="dropdown-item">Calendar</a> -->
				<!-- <a href="javascript:;" class="dropdown-item">Setting</a> -->
				<!-- <div class="dropdown-divider"></div> -->
				<a href="salir.php" class="dropdown-item">
					<i class="fa fa-power-off text-danger f-s-20" style="letter-spacing: 1px"></i>&nbsp;&nbsp;SALIR
				</a>
				<!-- <a href="salir.php" class="dropdown-item text-center">
					<button class="btn btn-danger" style="letter-spacing: 1px;">SALIR <i class="fa fa-power-off"></i></button>
				</a> -->
			</div>
		</li>
	</ul>
	<!-- end header navigation right -->
</div>