<?php
	include 'conexion.php';
	$Clave 	=	$_POST['id'];
	$callProdTemp	=	mysqli_query($MySQLi,"SELECT * FROM ClaveTemporal WHERE Clave='$Clave' ");
	$resultCall 	=	mysqli_num_rows($callProdTemp);
	if ($resultCall>0) { ?>
		<table id="data-table" class="table table-striped table-bordered table-td-valign-middle w-100">
			<thead>
				<tr>
					<th colspan="6" class="d-none">
						<input type="text" class="form-control" name="ClaveTemporal" id="ClaveTemporal" value="<?php echo $_POST['id'] ?>">
					</th>
				</tr>
				<tr>
					<th style="padding: 3px" width="5%" class="text-center">Cant</th>
					<th style="padding: 3px" width="55%" class="text-center">Producto</th>
					<th style="padding: 3px" width="10%" class="text-center">Precio<br>Lista</th>
					<th style="padding: 3px" width="10%" class="text-center">Precio<br>Esp</th>
					<th style="padding: 3px" width="10%" class="text-center">Total</th>
					<th style="padding: 3px" width="10%" class="text-center">Acciones</th>
				</tr>
			</thead>
			<tbody><?php 
				while ($dataRegistros = mysqli_fetch_assoc($callProdTemp)) {  ?>
				<tr>								
					<td class="text-center"><?php echo $dataRegistros['Cantidad'] ?></td>
					<td class=""><?php
						$id_Producto 	=	$dataRegistros['idProducto'];
						$sqlProducto 	=	mysqli_query($MySQLi,"SELECT * FROM Productos WHERE idProducto='$id_Producto'");
						$DataProductos=	mysqli_fetch_assoc($sqlProducto);
						$Product 			=	$DataProductos['Producto'];
						$MarcProduct 	=	$DataProductos['Marca'];
						$ModeloProduct=	$DataProductos['Modelo'];
						$DescProduct 	=	$Product." / ".$MarcProduct." / ".$ModeloProduct;
						echo $DescProduct;?>
					</td>
					<td class="text-center">$ <?php echo number_format($dataRegistros['PrecioLista'],2) ?></td>
					<td class="text-center">$ <?php echo number_format($dataRegistros['PrecioOferta'],2) ?></td>
					<td class="text-center">$ <?php echo number_format($dataRegistros['PrecioOferta']*$dataRegistros['Cantidad'],2) ?></td>
					<td class="text-center"><?php
						if ($resultCall>1) { ?>
						 	<button title="Borrar Producto (<?php echo $dataRegistros['id'] ?>)" class="btn btn-xs btn-danger deleteProdTemp" id="<?php echo $dataRegistros['id'] ?>"><i class="fa fa-trash-alt"></i></button>&nbsp;<?php
						 } ?>						 
						<a href="#editProdTemp" data-toggle="modal"><button title="Editar Producto" class="btn btn-xs btn-info editProdTemporal" id="<?php echo $dataRegistros['id'] ?>"><i class="fa fa-edit"></i></button></a>
					</td>
				</tr><?php } ?>
			</tbody>
		</table> <?php
	}else{ ?>
		<table id="data-table" class="table table-striped table-bordered table-td-valign-middle w-100">
			<thead>
				<tr>
					<th style="padding: 3px" width="5%" class="text-center">Cant</th>
					<th style="padding: 3px" width="55%" class="text-center">Producto</th>
					<th style="padding: 3px" width="10%" class="text-center">Pre_Lista</th>
					<th style="padding: 3px" width="10%" class="text-center">Pre_Esp</th>
					<th style="padding: 3px" width="10%" class="text-center">Total</th>
					<th style="padding: 3px" width="10%" class="text-center">Acciones</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td colspan="6" class="text-center text-danger" style="letter-spacing: 1px"><strong>NO HAY PRODUCTOS QUE MOSTRAR</strong></td>
				</tr>
			</tbody>
		</table> <?php
	}
?>