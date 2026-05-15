<?php
	// Filtrar clientes por nombre, apellido o modelo de maquina por get
	if (isset($_GET['search']) AND isset($_GET['sucursal'])) { ?>

		<script src="assets/plugins/datatables.net/js/jquery.dataTables.min.js"></script>
		<script src="assets/plugins/datatables.net-bs4/js/dataTables.bootstrap4.min.js"></script>
		<script src="assets/plugins/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
		<script src="assets/plugins/datatables.net-buttons-bs4/js/buttons.bootstrap4.min.js"></script>
		<script src="assets/plugins/datatables.net-buttons/js/buttons.colVis.min.js"></script>
		<script src="assets/plugins/datatables.net-buttons/js/buttons.flash.min.js"></script>
		<script src="assets/plugins/datatables.net-buttons/js/pdfHorizontal.js"></script>
		<script src="assets/plugins/datatables.net-buttons/js/buttons.print.min.js"></script>
		<script src="assets/plugins/pdfmake/build/pdfmake.min.js"></script>
		<script src="assets/plugins/pdfmake/build/vfs_fonts.js"></script>
		<script src="assets/plugins/jszip/dist/jszip.min.js"></script>
		<script src="assets/js/demo/table-manage-buttons.demo.js"></script> <?php
		include 'conexion.php';

		$search 	=	$_GET['search'];
		$Sucursal 	=	$_GET['sucursal'];

		if ($Sucursal=='TODAS') {
			// Consulta para buscar clientes por nombre, apellido o modelo de productos comprados Clientes->Ventas->Productos
			$stmt = $MySQLi->prepare("
				(SELECT DISTINCT c.* FROM Clientes c 
				WHERE c.Nombres LIKE ? OR c.Apellidos LIKE ?)
				UNION
				(SELECT DISTINCT c.* FROM Clientes c
				INNER JOIN Ventas v ON c.idCliente = v.idCliente
				INNER JOIN Productos p ON v.idProducto = p.idProducto
				WHERE p.Modelo LIKE ? OR p.Producto LIKE ?)
				ORDER BY Apellidos DESC");
			$searchParam = "%$search%";
			$stmt->bind_param("ssss", $searchParam, $searchParam, $searchParam, $searchParam);
			$stmt->execute();
			$consultaClientes = $stmt->get_result();
			$resultadoConsulta	=	mysqli_num_rows($consultaClientes);
			if ($resultadoConsulta>0) {
				echo '
				<table id="data-table-buttons" class="table table-striped table-bordered table-td-valign-middle w-100">
					<thead>
						<tr class="">
							<th width="5%" class="text-center">N&ordm;</th>
							<th width="10%" class="text-center">Nombre</th>
							<th width="10%" class="text-center">Apellido</th>
							<th width="20%" class="text-center">Correo</th>
							<th width="5%" class="text-center">Celular</th>
							<th width="5%" class="text-center">Fijo</th>
							<th width="10%" class="text-center">Empresa</th>
							<th width="10%" class="text-center">Registro</th>
							<th width="25%" class="text-center">Acciones</th>
						</tr>
					</thead>
					<tbody>';
						$Num=1;
						while ($dataCliente = mysqli_fetch_array($consultaClientes)) {
						echo '
						<tr>
						 	<td>'.$Num.'</td>
						 	<td>'.$dataCliente['Nombres'] .'</td>
						 	<td>'.$dataCliente['Apellidos'] .'</td>
						 	<td>'.$dataCliente['Correo'] .'</td>
						 	<td>'.$dataCliente['Celular'] .'</td>
						 	<td>'.$dataCliente['Otro'] .'</td>
						 	<td>'.$dataCliente['Empresa'] .'</td>
						 	<td>'.$dataCliente['Sucursal'] .'</td>
						 	<td class="text-center">
						 		<button class="btn btn-xs btn-success editCliente" id="'.$dataCliente['idCliente'].'"><i class="fa fa-edit"></i>
						 		</button>&nbsp';
								if (!empty($dataCliente['Correo'])) {
									echo '<button title="Enviar Correo" id="'.$dataCliente['idCliente'].'" class="btn btn-xs btn-danger sendMailCliente"><i class="fa fa-envelope" style="font-size: 15px"></i></button>';
								}
								/*	CONSULTAMOS SI EL CLIENTE HA HECHO COMPRAS 	*/
								$idCliente 		=	$dataCliente['idCliente'];
								$queryCompras	=	mysqli_query($MySQLi,"SELECT * FROM Ventas WHERE idCliente='$idCliente' ");
								$resultQuery 	=	mysqli_num_rows($queryCompras);
								if ($resultQuery>0) {
									echo '
									<form target="_blank" action="?root=historialCliente" method="post" class="mt-1">
										<input type="hidden" name="idCliente" value="'.$idCliente.'">
										<button type="submit" title="Ver historial de compras" class="btn btn-xs btn-success">
											<i class="fas fa-history" style="font-size: 15px"></i>
										</button>&nbsp;
									</form>';
								}
								echo'
								<button class="btn btn-danger btn-xs callDataCliente" data-toggle="modal" data-target="#AlertofAdmin" id='.$dataCliente['idCliente'].' title="Borrar Cliente ('.$dataCliente['idCliente'].'"><i class="fa fa-trash-alt"></i></button>
					 		</td>
						 </tr>';
						 $Num++;} mysqli_close($MySQLi);
						 echo '
					</tbody>
				</table> ';
			}else{
				echo '
				<table id="data-table-buttons" class="table table-striped table-bordered table-td-valign-middle w-100">
					<thead>
						<tr class="">
							<th width="5%" class="text-center">N&ordm;</th>
							<th width="10%" class="text-center">Nombre</th>
							<th width="10%" class="text-center">Apellido</th>
							<th width="20%" class="text-center">Correo</th>
							<th width="5%" class="text-center">Celular</th>
							<th width="5%" class="text-center">Fijo</th>
							<th width="10%" class="text-center">Empresa</th>
							<th width="10%" class="text-center">Registro</th>
							<th width="25%" class="text-center">Acciones</th>
						</tr>
					</thead>
					<tbody>
						<tr>
						 	<td colspan="8" class="text-danger text-center">NO HAY RESULTADOS QUE MOSTRAR</td>
						 </tr>
					</tbody>
				</table> ';
			}
		}else{
			$stmt = $MySQLi->prepare("
				(SELECT DISTINCT c.* FROM Clientes c 
				WHERE (c.Nombres LIKE ? OR c.Apellidos LIKE ?) AND c.Sucursal = ?)
				UNION
				(SELECT DISTINCT c.* FROM Clientes c
				INNER JOIN Ventas v ON c.idCliente = v.idCliente
				INNER JOIN Productos p ON v.idProducto = p.idProducto
				WHERE (p.Modelo LIKE ? OR p.Producto LIKE ?) AND c.Sucursal = ?)
				ORDER BY Apellidos DESC");
			$searchParam = "%$search%";
			$searchSucursal = $Sucursal;
			$stmt->bind_param("ssssss", $searchParam, $searchParam, $searchSucursal, $searchParam, $searchParam, $searchSucursal);
			$stmt->execute();
			$consultaClientes = $stmt->get_result();
			$resultadoConsulta	=	mysqli_num_rows($consultaClientes);
			if ($resultadoConsulta>0) {
				echo '
				<table id="data-table-buttons" class="table table-striped table-bordered table-td-valign-middle w-100">
					<thead>
						<tr class="">
							<th class="text-center">N&ordm;</th>
							<th class="text-center">Nombre</th>
							<th class="text-center">Apellido</th>
							<th class="text-center">Correo</th>
							<th class="text-center">Celular</th>
							<th class="text-center">Fijo</th>
							<th class="text-center">Empresa</th>
							<th class="text-center">Registro</th>
							<th class="text-center">Acciones</th>
						</tr>
					</thead>
					<tbody>';
						$Num=1;
						while ($dataCliente = mysqli_fetch_array($consultaClientes)) {
						echo '
						<tr>
						 	<td>'.$Num.'</td>
						 	<td>'.$dataCliente['Nombres'] .'</td>
						 	<td>'.$dataCliente['Apellidos'] .'</td>
						 	<td>'.$dataCliente['Correo'] .'</td>
						 	<td>'.$dataCliente['Celular'] .'</td>
						 	<td>'.$dataCliente['Otro'] .'</td>
						 	<td>'.$dataCliente['Empresa'] .'</td>
						 	<td>'.$dataCliente['Sucursal'] .'</td>
						 	<td class="text-center">
						 		<button class="btn btn-xs btn-success editCliente" id="'.$dataCliente['idCliente'].'"><i class="fa fa-edit"></i></button>&nbsp';
								if (!empty($dataCliente['Correo'])) {
									echo '<button title="Enviar Correo" id="'.$dataCliente['idCliente'].'" class="btn btn-xs btn-danger sendMailCliente"><i class="fa fa-envelope" style="font-size: 15px"></i></button>';
								}
								/*	CONSULTAMOS SI EL CLIENTE HA HECHO COMPRAS 	*/
								$idCliente 		=	$dataCliente['idCliente'];
								$queryCompras	=	mysqli_query($MySQLi,"SELECT * FROM Ventas WHERE idCliente='$idCliente' ");
								$resultQuery 	=	mysqli_num_rows($queryCompras);
								if ($resultQuery>0) {
									echo '
									<form target="_blank" action="?root=historialCliente" method="post" class="mt-1">
										<input type="hidden" name="idCliente" value="'.$idCliente.'">
										<button type="submit" title="Ver historial de compras" class="btn btn-xs btn-success">
											<i class="fas fa-history" style="font-size: 15px"></i>
										</button>
									</form>';
								} echo'
								<button class="btn btn-danger btn-xs callDataCliente" data-toggle="modal" data-target="#AlertofAdmin" id='.$dataCliente['idCliente'].' title="Borrar Cliente ('.$dataCliente['idCliente'].'"><i class="fa fa-trash-alt"></i></button>
					 		</td>
						 </tr>';
						 $Num++;} mysqli_close($MySQLi);
						 echo '
					</tbody>
				</table> ';
			}else{
				echo '
				<table id="data-table-buttons" class="table table-striped table-bordered table-td-valign-middle w-100">
					<thead>
						<tr class="">
							<th class="text-center">N&ordm;</th>
							<th class="text-center">Nombre</th>
							<th class="text-center">Apellido</th>
							<th class="text-center">Correo</th>
							<th class="text-center">Teléfono</th>
							<th class="text-center">Empresa</th>
							<th class="text-center">CI</th>
							<th class="text-center">Acciones</th>
						</tr>
					</thead>
					<tbody>
						<tr>
						 	<td colspan="8" class="text-danger text-center">NO HAY RESULTADOS QUE MOSTRAR</td>
						 </tr>
					</tbody>
				</table> ';
			}
		}
	}
?>