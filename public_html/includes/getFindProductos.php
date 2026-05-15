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
<script src="assets/js/demo/table-manage-buttons.demo.js"></script>
<?php
	include 'conexion.php';
	$Modelo 	=	$_POST['modelo'];
	//echo $Modelo; exit();
	
	$consultaProducto	=	mysqli_query($MySQLi,"SELECT * FROM Productos WHERE Modelo LIKE '%$Modelo%'")or die(mysql_error($MySQLi));
	$resultadoConsulta	=	mysqli_num_rows($consultaProducto);
	if ($resultadoConsulta>0) {
		echo '
		<table id="data-table-buttons" class="table table-striped table-bordered table-td-valign-middle w-100">
			<thead>
				<tr class="">
					<th width="3%" class="text-center">N&ordm;</th>
					<th width="25%" class="text-center">Producto</th>
					<th width="3%" class="text-center">Stock<br>CB</th>
					<th width="10%" class="text-center">Precio<br>CB</th>
					<th width="3%" class="text-center">Stock<br>LP</th>
					<th width="10%" class="text-center">Precio<br>LP</th>
					<th width="3%" class="text-center">Stock<br>SC</th>
					<th width="10%" class="text-center">Precio<br>SC</th>
					<th width="3%" class="text-center">Stock<br>ST</th>
					<th width="10%" class="text-center">Precio<br>ST</th>
					<th width="3%" class="text-center">Stock<br>FR</th>
					<th width="10%" class="text-center">Precio<br>FR</th>
					<th width="3%" class="text-center">Stock<br>Total</th>
					<th width="10%" class="text-center">Imagen</th>
					<th width="7%" class="text-center">Acciones</th>
				</tr>
			</thead>
			<tbody>';
				$Num=1;
				while ($dataProd = mysqli_fetch_array($consultaProducto)) {
				echo '
				<tr class="odd gradeX">
					<td class="text-center">'.$Num.'</td>
					<td class="">'.$dataProd["Producto"].' / '.$dataProd["Marca"].' / '.$dataProd["Modelo"].'</td>
					<td class="text-center f-w-600">'.$dataProd["StockCB"].'</td>
					<td class="text-center">$ &nbsp;&nbsp;'.$dataProd["PrecioCB"].'</td>
					<td class="text-center">'.$dataProd["StockLP"].'</td>
					<td class="text-center f-w-600">$ &nbsp;&nbsp;'.$dataProd["PrecioLP"].'</td>
					<td class="text-center f-w-600">'.$dataProd["StockSC"].'</td>
					<td class="text-center">$ &nbsp;&nbsp;'.$dataProd["PrecioSC"].'</td>
					<td class="text-center f-w-600">'.$dataProd["StockST"].'</td>
					<td class="text-center">$ &nbsp;&nbsp;'.$dataProd["PrecioST"].'</td>
					<td class="text-center f-w-600">'.$dataProd["StockTJ"].'</td>
					<td class="text-center">$ &nbsp;&nbsp;'.$dataProd["PrecioTJ"].'</td>
					<td class="text-center f-w-600">'.$dataProd["StockTotal"].'</td>
					<td class="text-center">
						<img height="50" src="Productos/'.$dataProd["Imagen"].'" alt="'.$dataProd["Producto"].' / '.$dataProd["Marca"].' / '.$dataProd["Modelo"].'" >
					</td>
					<td class="text-center">
						<button title="Editar Producto" id="'.$dataProd["idProducto"].'" class="btn btn-xs btn-success editProdExistente">
							<i class="ion-ios-brush" style="font-size: 15px"></i>
						</button>&nbsp;
						<button class="btn btn-xs btn-danger borrarProducto" title="Borrar producto" id="'.$dataProd['idProducto'].'">
							<i class="fa fa-trash-alt" style="font-size: 15px"></i>
						</button>
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
					<th class="text-center">Producto</th>
					<th class="text-center">Stock_CB</th>
					<th class="text-center">Pre_CB</th>
					<th class="text-center">Stock_LP</th>
					<th class="text-center">Pre_LP</th>
					<th class="text-center">Stock_SC</th>
					<th class="text-center">Pre_SC</th>
					<th class="text-center">Stock_FR</th>
					<th class="text-center">Pre_FR</th>
					<th class="text-center">Stock Total</th>
					<th class="text-center">Imagen</th>
					<th class="text-center">Acciones</th>
				</tr>
			</thead>
			<tbody>
				<tr>
				 	<td colspan="13" class="text-danger text-center">NO HAY RESULTADOS QUE MOSTRAR</td>
				 </tr>
			</tbody>
		</table> ';
	}
?>