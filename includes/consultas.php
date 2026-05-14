<?php
include 'conexion.php';
session_start();
error_reporting(0);
/*    CONSULTAS ABONOS ANTERIORES    */
if (isset($_POST['idCotizacionRecibos'])) {
    $idCotiza = $_POST['idCotizacionRecibos'];
    $consu_Reci = mysqli_query($MySQLi, "SELECT * FROM Creditos WHERE idCotizacion='$idCotiza' ") or die(mysqli_error($MySQLi));
    $numRecibos = mysqli_num_rows($consu_Reci);
    $verRecibo = $numRecibos - 1;
    $consultReci = mysqli_query($MySQLi, "SELECT * FROM Creditos WHERE idCotizacion='$idCotiza' LIMIT $verRecibo,1 ") or die(mysql_error()($MySQLi));
    $dataRecibo = mysqli_fetch_assoc($consultReci);
    echo json_encode($dataRecibo);
} elseif (isset($_POST['idRecibo'])) { /*    EDITA EL PRIMER ABONO    */
    $idRecibo = $_POST['idRecibo'];
    $consultReci = mysqli_query($MySQLi, "SELECT * FROM Abonos WHERE idRecibo='$idRecibo' ") or die(mysqli_error($MySQLi));
    $dataRecibo = mysqli_fetch_assoc($consultReci);
    echo json_encode($dataRecibo);
} elseif (isset($_POST['id'])) {
    $id = $_POST['id'];
    $queryCotiza = mysqli_query($MySQLi, "SELECT * FROM Cotizaciones WHERE idCotizacion='$id' ");
    $dataCotiza = mysqli_fetch_assoc($queryCotiza);
    $Sucursal = $dataCotiza['Sucursal'];
    $ClaveTemp = $dataCotiza['Clave'];
    $CodeCotiza = $dataCotiza['Code'];

    //OBTENER DATOS DEL CLIENTE
    $idCliente = $dataCotiza['idCliente'];
    $queryCliente = mysqli_query($MySQLi, "SELECT * FROM Clientes WHERE idCliente='$idCliente' ");
    $dataCliente = mysqli_fetch_assoc($queryCliente);
    $NameCliente = $dataCliente['Nombres'] . " " . $dataCliente['Apellidos'];
    $MailCliente = $dataCliente['Correo'];

    $queryClave = mysqli_query($MySQLi, "SELECT id, Clave, idProducto, Cantidad, PrecioLista, PrecioOferta, SUM(Cantidad*PrecioOferta)AS Total FROM ClaveTemporal WHERE Clave='$ClaveTemp' ");
    $dataClave = mysqli_fetch_assoc($queryClave);
    $Total = $dataClave['Total'];

    $sqlClave2 = mysqli_query($MySQLi, "SELECT * FROM ClaveTemporal WHERE Clave='$ClaveTemp' ");
    while ($data = mysqli_fetch_assoc($sqlClave2)) {           
    $idProducto = $data['idProducto'];
    $sqlProducto = mysqli_query($MySQLi, "SELECT * FROM Productos WHERE idProducto='$idProducto' ");
    $dataProducto = mysqli_fetch_assoc($sqlProducto);
    // $ProductoName = $dataProducto['Producto'] . " " . $dataProducto['Marca'] . " " . $dataProducto['Modelo'];
    $ProductoName = "CANTIDAD:".$data['Cantidad'] . "  PRODUCTO:" . $dataProducto['Producto'] . "/" . $dataProducto['Marca'] . "/" . $dataProducto['Modelo'] . "  PRECIO OFERTA:" .$data['PrecioOferta']. "  SUBTOTAL:" .number_format($data['Cantidad']*$data['PrecioOferta'],2);
    $Prod = $Prod.$ProductoName."\n";
    }

    $Respuesta = array(
        'mailCliente' => $MailCliente,
        'idCotizacion' => $id,
        'idCliente' => $idCliente,
        'TOTAL' => $Total,
        'Sucursal' => $Sucursal,
        'CodeCotiza' => $CodeCotiza,
        'NameCliente' => $NameCliente,
        'Prod' => $Prod);
    echo json_encode($Respuesta);
} elseif (isset($_POST['idCotizacionVenta'])) {
    
    $idCotizacion = $_POST['idCotizacionVenta'];
    $queryCotiza = mysqli_query($MySQLi, "SELECT Code, Clave, idUser, idCliente, Sucursal FROM Cotizaciones WHERE idCotizacion='$idCotizacion' ") or die(mysqli_error($MySQLi));
    $dataCotiza = mysqli_fetch_assoc($queryCotiza);
    $CodeCotiza = $dataCotiza['Code'];
    $Clave = $dataCotiza['Clave'];
    $idUser = $dataCotiza['idUser'];
    $idCliente = $dataCotiza['idCliente'];
    $Sucursal = $dataCotiza['Sucursal'];

    //    DATOS DEL CLIENTE
    $queryCliente = mysqli_query($MySQLi, "SELECT Nombres, Apellidos FROM Clientes WHERE idCliente='$idCliente' ") or die(mysqli_error($MySQLi));
    $dataCliente = mysqli_fetch_assoc($queryCliente);
    $NameCliente = $dataCliente['Nombres'] . " " . $dataCliente['Apellidos'];

    //    DATOS DEL USUARIO
    $queryUser = mysqli_query($MySQLi, "SELECT Nombres, Apellidos FROM Usuarios WHERE idUser='$idUser' ") or die(mysqli_error($MySQLi));
    $dataUser = mysqli_fetch_assoc($queryUser);
    $NameUser = $dataUser['Nombres'] . " " . $dataUser['Apellidos'];

    //    DATOS DE LOS PRODUCTOS
    $queryClave = mysqli_query($MySQLi, "SELECT SUM(Cantidad*PrecioOferta) AS Total FROM ClaveTemporal WHERE Clave='$Clave' ") or die(mysqli_error($MySQLi));
    while ($dataClave = mysqli_fetch_assoc($queryClave)) {
        $Total = $dataClave['Total'];
    }

    $sqlClave2 = mysqli_query($MySQLi, "SELECT * FROM ClaveTemporal WHERE Clave='$Clave' ");
    while ($data = mysqli_fetch_assoc($sqlClave2)) {           
    $idProducto = $data['idProducto'];
    $sqlProducto = mysqli_query($MySQLi, "SELECT * FROM Productos WHERE idProducto='$idProducto' ");
    $dataProducto = mysqli_fetch_assoc($sqlProducto);
    $ProductoName = "CANTIDAD:".$data['Cantidad'] . "  PRODUCTO:" . $dataProducto['Producto'] . "/" . $dataProducto['Marca'] . "/" . $dataProducto['Modelo'] . "  PRECIO OFERTA:" .$data['PrecioOferta']. "  SUBTOTAL:" .number_format($data['Cantidad']*$data['PrecioOferta'],2);
    $Prod = $Prod.$ProductoName."\n" ;
    }
    $Prod=$Prod."\n"."TOTAL:".$Total;



    $Respuesta = array(
        'idCotizacion' => $idCotizacion,
        'idUser' => $idUser,
        'idCliente' => $idCliente,
        'CodeCotiza' => $CodeCotiza,
        'Sucursal' => $Sucursal,
        'NameCliente' => $NameCliente,
        'NameUser' => $NameUser,
        'Total' => $Total,
        'Prod' => $Prod

    );
    echo json_encode($Respuesta);
} 


elseif (isset($_POST['idCotizacionVentaBs'])) {
    
    $idCotizacion = $_POST['idCotizacionVentaBs'];
    $queryCotiza = mysqli_query($MySQLi, "SELECT Code, Clave, idUser, idCliente, Sucursal, precioDolar FROM Cotizaciones c left join precio p on p.id=1 WHERE Code ='$idCotizacion' ") or die(mysqli_error($MySQLi));
    $dataCotiza = mysqli_fetch_assoc($queryCotiza);
    $CodeCotiza = $dataCotiza['Code'];
    $Clave = $dataCotiza['Clave'];
    $idUser = $dataCotiza['idUser'];
    $idCliente = $dataCotiza['idCliente'];
    $Sucursal = $dataCotiza['Sucursal'];
    $precioDolar = $dataCotiza['precioDolar'];
    
    //    DATOS DEL CLIENTE
    $queryCliente = mysqli_query($MySQLi, "SELECT Nombres, Apellidos FROM Clientes WHERE idCliente='$idCliente' ") or die(mysqli_error($MySQLi));
    $dataCliente = mysqli_fetch_assoc($queryCliente);
    $NameCliente = $dataCliente['Nombres'] . " " . $dataCliente['Apellidos'];

    //    DATOS DEL USUARIO
    $queryUser = mysqli_query($MySQLi, "SELECT Nombres, Apellidos FROM Usuarios WHERE idUser='$idUser' ") or die(mysqli_error($MySQLi));
    $dataUser = mysqli_fetch_assoc($queryUser);
    $NameUser = $dataUser['Nombres'] . " " . $dataUser['Apellidos'];

    //    DATOS DE LOS PRODUCTOS
    $queryClave = mysqli_query($MySQLi, "SELECT SUM(Cantidad*PrecioOferta) AS Total FROM ClaveTemporal WHERE Clave='$Clave' ") or die(mysqli_error($MySQLi));
    while ($dataClave = mysqli_fetch_assoc($queryClave)) {
        $Total = $dataClave['Total'];
    }

    $sqlClave2 = mysqli_query($MySQLi, "SELECT * FROM ClaveTemporal WHERE Clave='$Clave' ");
    while ($data = mysqli_fetch_assoc($sqlClave2)) {           
        $idProducto = $data['idProducto'];
        $sqlProducto = mysqli_query($MySQLi, "SELECT * FROM Productos WHERE idProducto='$idProducto' ");
        $dataProducto = mysqli_fetch_assoc($sqlProducto);
        $ProductoName = "CANTIDAD:".$data['Cantidad'] . "  PRODUCTO:" . $dataProducto['Producto'] . "/" . $dataProducto['Marca'] . "/" . $dataProducto['Modelo'] . "  PRECIO OFERTA:" .$data['PrecioOferta'] * $precioDolar. "  SUBTOTAL:" .number_format($data['Cantidad']*$data['PrecioOferta']*$precioDolar,2);
        $Prod = $Prod.$ProductoName."\n" ;
    }
    $Prod=$Prod."\n"."TOTAL:".number_format($Total*$precioDolar,2);



    $Respuesta = array(
        'idCotizacion' => $idCotizacion,
        'idUser' => $idUser,
        'idCliente' => $idCliente,
        'CodeCotiza' => $CodeCotiza,
        'Sucursal' => $Sucursal,
        'NameCliente' => $NameCliente,
        'NameUser' => $NameUser,
        'Total' => $Total,
        'Prod' => $Prod

    );
    echo json_encode($Respuesta);
}

elseif (isset($_POST['idCotizacionVentaUsd'])) {
    $idCotizacion = $_POST['idCotizacionVentaUsd'];
    $queryCotiza = mysqli_query($MySQLi, "SELECT Code, Clave, idUser, idCliente, Sucursal, precioDolar FROM Cotizaciones c left join precio p on p.id=1 WHERE Code ='$idCotizacion' ") or die(mysqli_error($MySQLi));
    $dataCotiza = mysqli_fetch_assoc($queryCotiza);
    $CodeCotiza = $dataCotiza['Code'];
    $Clave = $dataCotiza['Clave'];
    $idUser = $dataCotiza['idUser'];
    $idCliente = $dataCotiza['idCliente'];
    $Sucursal = $dataCotiza['Sucursal'];
    $precioDolar = $dataCotiza['precioDolar'];
    
    //    DATOS DEL CLIENTE
    $queryCliente = mysqli_query($MySQLi, "SELECT Nombres, Apellidos FROM Clientes WHERE idCliente='$idCliente' ") or die(mysqli_error($MySQLi));
    $dataCliente = mysqli_fetch_assoc($queryCliente);
    $NameCliente = $dataCliente['Nombres'] . " " . $dataCliente['Apellidos'];

    //    DATOS DEL USUARIO
    $queryUser = mysqli_query($MySQLi, "SELECT Nombres, Apellidos FROM Usuarios WHERE idUser='$idUser' ") or die(mysqli_error($MySQLi));
    $dataUser = mysqli_fetch_assoc($queryUser);
    $NameUser = $dataUser['Nombres'] . " " . $dataUser['Apellidos'];

    //    DATOS DE LOS PRODUCTOS
    $queryClave = mysqli_query($MySQLi, "SELECT SUM(Cantidad*PrecioOferta) AS Total FROM ClaveTemporal WHERE Clave='$Clave' ") or die(mysqli_error($MySQLi));
    while ($dataClave = mysqli_fetch_assoc($queryClave)) {
        $Total = $dataClave['Total'];
    }

    $sqlClave2 = mysqli_query($MySQLi, "SELECT * FROM ClaveTemporal WHERE Clave='$Clave' ");
    while ($data = mysqli_fetch_assoc($sqlClave2)) {           
        $idProducto = $data['idProducto'];
        $sqlProducto = mysqli_query($MySQLi, "SELECT * FROM Productos WHERE idProducto='$idProducto' ");
        $dataProducto = mysqli_fetch_assoc($sqlProducto);
        $ProductoName = "CANTIDAD:".$data['Cantidad'] . "  PRODUCTO:" . $dataProducto['Producto'] . "/" . $dataProducto['Marca'] . "/" . $dataProducto['Modelo'] . "  PRECIO OFERTA:" .$data['PrecioOferta'] . "  SUBTOTAL:" .number_format($data['Cantidad']*$data['PrecioOferta'],2);
        $Prod = $Prod.$ProductoName."\n" ;
    }
    $Prod=$Prod."\n"."TOTAL:".number_format($Total,2);



    $Respuesta = array(
        'idCotizacion' => $idCotizacion,
        'idUser' => $idUser,
        'idCliente' => $idCliente,
        'CodeCotiza' => $CodeCotiza,
        'Sucursal' => $Sucursal,
        'NameCliente' => $NameCliente,
        'NameUser' => $NameUser,
        'Total' => $Total,
        'Prod' => $Prod

    );
    echo json_encode($Respuesta);    

}


elseif (isset($_POST['idNotadeEntrega'])) {
    $idNotaE = $_POST['idNotadeEntrega'];
    $queryNota = mysqli_query($MySQLi, "SELECT idNotaE, Observaciones FROM NotaEntrega WHERE idNotaE='$idNotaE' ");
    $dataNota = mysqli_fetch_assoc($queryNota);
    echo json_encode($dataNota);
} elseif (isset($_POST['idTabla'])) {
    $idTabla = $_POST['idTabla'];
    $queryMeta = mysqli_query($MySQLi, "SELECT * FROM TablaComisiones WHERE idTabla='$idTabla' ");
    $dataMeta = mysqli_fetch_assoc($queryMeta);
    echo json_encode($dataMeta);
} elseif (isset($_POST['idCotizaNotaE'])) {
    $idCotizacion = $_POST['idCotizaNotaE'];
    $queryNotaE = mysqli_query($MySQLi, "SELECT * FROM NotaEntrega WHERE idCotizacion='$idCotizacion' ");
    $dataNotaE = mysqli_fetch_assoc($queryNotaE);
    echo json_encode($dataNotaE);
} elseif (isset($_POST['idReciboCredito'])) {
    $idRecibo = $_POST['idReciboCredito'];
    $consultReci = mysqli_query($MySQLi, "SELECT * FROM Creditos WHERE idRecibo='$idRecibo' ") or die(mysqli_error($MySQLi));
    $dataRecibo = mysqli_fetch_assoc($consultReci);
    echo json_encode($dataRecibo);
} elseif (isset($_POST['idCotizacionRecibosAbonos'])) {
    $idCotiza = $_POST['idCotizacionRecibosAbonos'];
    $consu_Reci = mysqli_query($MySQLi, "SELECT * FROM Abonos WHERE idCotizacion='$idCotiza' ") or die(mysqli_error($MySQLi));
    $numRecibos = mysqli_num_rows($consu_Reci);
    $verRecibo = $numRecibos - 1;
    $consultReci = mysqli_query($MySQLi, "SELECT * FROM Abonos WHERE idCotizacion='$idCotiza' LIMIT $verRecibo,1 ") or die(mysql_error()($MySQLi));
    $dataRecibo = mysqli_fetch_assoc($consultReci);
    echo json_encode($dataRecibo);
} elseif (isset($_POST['idCotizacion_Recibos'])) {
    $idCotiza = $_POST['idCotizacion_Recibos'];
    $consu_Reci = mysqli_query($MySQLi, "SELECT * FROM Recibos WHERE idCotizacion='$idCotiza' ") or die(mysqli_error($MySQLi));
    $numRecibos = mysqli_num_rows($consu_Reci);
    $verRecibo = $numRecibos - 1;
    $consultReci = mysqli_query($MySQLi, "SELECT * FROM Creditos WHERE idCotizacion='$idCotiza' LIMIT $verRecibo,1 ") or die(mysql_error()($MySQLi));
    //$dataRecibo    =    mysqli_fetch_assoc($consu_Reci);
    $dataRecibo = mysqli_fetch_assoc($consultReci);
    echo json_encode($dataRecibo);
} elseif (isset($_POST['editReciboVentaCash'])) {
    $idRecibo = $_POST['editReciboVentaCash'];
    $queryReci = mysqli_query($MySQLi, "SELECT * FROM Recibos WHERE idRecibo='$idRecibo' ");
    $dataReci = mysqli_fetch_assoc($queryReci);
    echo json_encode($dataReci);
} elseif (isset($_POST['idCliente'])) {
    $idCliente = $_POST['idCliente'];
    $queryC = mysqli_query($MySQLi, "SELECT * FROM Clientes WHERE idCliente='$idCliente' ");
    $dataC = mysqli_fetch_assoc($queryC);
    echo json_encode($dataC);
} elseif (isset($_POST['idCotizacionModificar'])) {
    $idCotizacion = $_POST['idCotizacionModificar'];
    $queryCotiza = mysqli_query($MySQLi, "SELECT * FROM Cotizaciones WHERE idCotizacion='$idCotizacion' ");
    $dataCotiza = mysqli_fetch_assoc($queryCotiza);
    $ClaveTemporal = $dataCotiza['Clave'];
    $callProdTemp = mysqli_query($MySQLi, "SELECT * FROM ClaveTemporal WHERE Clave='$ClaveTemporal' ");
    echo '
		<table class="table table-striped w-100">
			<thead>
				<tr>
					<th class="text-center" width="10%">Cant</th>
					<th class="text-center" width="80%">Producto</th>
					<th class="text-center" width="10%">Acciones</th>
				</tr>';
    while ($dataRegistros = mysqli_fetch_assoc($callProdTemp)) {echo '
				<tr>
					<td class="text-center">' . $dataRegistros['Cantidad'] . '</td>';
        $id_Producto = $dataRegistros['idProducto'];
        $sqlProducto = mysqli_query($MySQLi, "SELECT * FROM Productos WHERE idProducto='$id_Producto'");
        $DataProductos = mysqli_fetch_assoc($sqlProducto);
        $Product = $DataProductos['Producto'];
        $MarcProduct = $DataProductos['Marca'];
        $ModeloProduct = $DataProductos['Modelo'];
        $DescProduct = $Product . " / " . $MarcProduct . " / " . $ModeloProduct;
        echo '
					<td>' . $DescProduct . '</td>
					<td class="text-center"><input type="checkbox" name=' . $id_Producto . ' ></td>
				</tr>	';}
    echo '
			</thead>
		</table>
		<div class="row">
			<div class="col">
				<button class="btn btn-xs btn-info selectAllCheck btn-block" id=' . $idCotizacion . '>Seleccionar todo</button>
			</div>
			<div class="col">
				<button class="btn btn-xs btn-danger deleteSelcted btn-block" id=' . $idCotizacion . '>Borrar seleccionados</button>
			</div>
		</div>';
} elseif (isset($_POST['cuentaProductos'])) {
    $idCotizacion = $_POST['cuentaProductos'];
    $queryCotiza = mysqli_query($MySQLi, "SELECT * FROM Cotizaciones WHERE idCotizacion='$idCotizacion' ");
    $dataCotiza = mysqli_fetch_assoc($queryCotiza);
    $ClaveTemporal = $dataCotiza['Clave'];
    $callProdTemp = mysqli_query($MySQLi, "SELECT * FROM ClaveTemporal WHERE Clave='$ClaveTemporal' ");
    $resultCall = mysqli_num_rows($callProdTemp);
    if ($resultCall == 1) {?>
<script type="text/javascript">
thBootstrapButtons = Swal.mixin({
    customClass: {
        confirmButton: 'btn btn-success',
        cancelButton: 'btn btn-danger'
    },
    buttonsStyling: false
})
swalWithBootstrapButtons.fire({
        title: 'Estás seguro?',
        html: "Si continuas, no podrás deshacer los cambios.",
        type: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sí, borrar!',
        cancelButtonText: 'No, cancelar!',
        reverseButtons: true
    })
    .then((result) => {
        if (result.value) {
            $.ajax({
                    url: 'do.php',
                    type: 'POST',
                    dataType: 'html',
                    data: "action=eliminarVentaDirecta&id=" + idCotizacion,
                })
                .done(function(data) {
                    $(".respuesta").html(data);
                })
            return false;
        } else if (result.dismiss === Swal.DismissReason.cancel) {
            swalWithBootstrapButtons.fire(
                'Cancelado',
                'La venta no será eliminada',
                'error'
            )
        }
    });
</script><?php
} else {
        echo "tabla";
    }
} elseif (isset($_POST['Clave'])) {
    $Clave = $_POST['Clave'];
    $queryClave = mysqli_query($MySQLi, "SELECT SUM(PrecioOferta*Cantidad)AS Total FROM ClaveTemporal WHERE Clave='$Clave' ");
    $dataClave = mysqli_fetch_assoc($queryClave);
    $Total = $dataClave['Total'];
    $queryClave2 = mysqli_query($MySQLi, "SELECT SUM(PrecioOferta*Cantidad)AS Total FROM ClaveTempMod WHERE Clave='$Clave' ");
    $dataClave2 = mysqli_fetch_assoc($queryClave2);
    $Total2 = $dataClave2['Total'];
    if ($Total >= $Total2) {
        echo 1;
    } else {
        echo 0;
    }
} elseif (isset($_POST['idCotizacion'])) {
    $idCotizacion = $_POST['idCotizacion'];
    $update = mysqli_query($MySQLi, "UPDATE Cotizaciones SET Estado=0 WHERE idCotizacion='$idCotizacion' ");
    if ($update) {
        echo "ok";
    }
} elseif (isset($_POST['correoClientexCotizaID'])) {
    $idCotizacion = $_POST['correoClientexCotizaID'];
    $Q_Cotizacion = mysqli_query($MySQLi, "SELECT * FROM Cotizaciones WHERE idCotizacion='$idCotizacion' ");
    $dataCotiza = mysqli_fetch_assoc($Q_Cotizacion);
    $idCliente = $dataCotiza['idCliente'];
    $Q_Cliente = mysqli_query($MySQLi, "SELECT * FROM Clientes WHERE idCliente='$idCliente' ");
    $dataCliente = mysqli_fetch_assoc($Q_Cliente);
    echo json_encode($dataCliente);
} elseif (isset($_POST['ConsultaProductoStock'])) {
    $Ciudad = $_POST['ConsultaProductoStock'];
    echo '<option disabled selected value=0>Seleccione un Producto</option>';
    if ($Ciudad == 'Cochabamba') {
        $Stock = 'StockCB';
        $ConsulProducto = mysqli_query($MySQLi, "SELECT idProducto, Producto, Marca, Modelo, StockCB FROM Productos WHERE StockCB>0 ORDER BY Producto ASC ");
        while ($Datos = mysqli_fetch_assoc($ConsulProducto)) {
            echo '<option value=' . $Datos["idProducto"] . ' >' . $Datos["Producto"] . ' ' . $Datos["Modelo"] . ' [Disponibles ' . $Datos["StockCB"] . ']</option>';
        }
    } elseif ($Ciudad == 'La Paz') {
        $Stock = 'StockLP';
        $ConsulProducto = mysqli_query($MySQLi, "SELECT idProducto, Producto, Marca, Modelo, StockLP FROM Productos WHERE StockLP>0 ORDER BY Producto ASC ");
        while ($Datos = mysqli_fetch_assoc($ConsulProducto)) {
            echo '<option value=' . $Datos["idProducto"] . ' >' . $Datos["Producto"] . ' ' . $Datos["Modelo"] . ' [Disponibles ' . $Datos["StockLP"] . ']</option>';
        }
    } elseif ($Ciudad == 'Santa Cruz') {
        $Stock = 'StockSC';
        $ConsulProducto = mysqli_query($MySQLi, "SELECT idProducto, Producto, Marca, Modelo, StockSC FROM Productos WHERE StockSC>0 ORDER BY Producto ASC ");
        while ($Datos = mysqli_fetch_assoc($ConsulProducto)) {
            echo '<option value=' . $Datos["idProducto"] . ' >' . $Datos["Producto"] . ' ' . $Datos["Modelo"] . ' [Disponibles ' . $Datos["StockSC"] . ']</option>';
        }
    } elseif ($Ciudad == 'Tarija') {
        $Stock = 'StockTJ';
        $ConsulProducto = mysqli_query($MySQLi, "SELECT idProducto, Producto, Marca, Modelo, StockTJ FROM Productos WHERE StockTJ>0 ORDER BY Producto ASC ");
        while ($Datos = mysqli_fetch_assoc($ConsulProducto)) {
            echo '<option value=' . $Datos["idProducto"] . ' >' . $Datos["Producto"] . ' ' . $Datos["Modelo"] . ' [Disponibles ' . $Datos["StockTJ"] . ']</option>';
        }
    }
} elseif (isset($_POST['BuscaSucursal'])) {
    $Sucursal = $_POST['BuscaSucursal'];
    echo '<option disabled selected value=0>Seleccione una Sucursal</option>';
    $ConsulSucursal = mysqli_query($MySQLi, "SELECT * FROM Sucursales WHERE Sucursal!='$Sucursal' ORDER BY Sucursal ASC ");
    while ($Datos = mysqli_fetch_assoc($ConsulSucursal)) {
        echo '<option value=' . $Datos["Sucursal"] . ' >' . $Datos["Sucursal"] . '</option>';
    }
} elseif (isset($_POST['comparaStock'])) {
    $cantidad = $_POST['comparaStock'];
    $sucursal = $_POST['sucursal'];
    $idProducto = $_POST['idProducto'];
    $consulta = mysqli_query($MySQLi, "SELECT $sucursal FROM Productos WHERE idProducto='$idProducto' ");
    $result = mysqli_fetch_assoc($consulta);
    $resultado = $result[$sucursal];
    $Resultado = (int) $resultado;
    echo $Resultado;
} elseif (isset($_POST['ConsultaClaveTemporal'])) {
    $idEnvio = $_POST['ConsultaClaveTemporal'];
    $sqlClave = mysqli_query($MySQLi, "SELECT clave FROM clavesEnvioStock WHERE idClave='$idEnvio' ");
    $resultClave = mysqli_fetch_assoc($sqlClave);
    echo $resultClave['clave'];
    //------------------------------------factura_------------------------------------------------
} elseif (isset($_POST['llamarDatosFactura'])) {
    include 'date.class.php';
    $idCotizacion = $_POST['llamarDatosFactura'];
    $sqlCotizacion = mysqli_query($MySQLi, "SELECT * FROM Cotizaciones WHERE idCotizacion='$idCotizacion' ");
    $dataCotiza = mysqli_fetch_assoc($sqlCotizacion);
    $sucursalCompra = $dataCotiza['Sucursal'];
    $codigoCotizacion = $dataCotiza['Code'];
    $idCliente = $dataCotiza['idCliente'];
    $idUsuario = $dataCotiza['idUser'];
    $clave = $dataCotiza['Clave'];
    $sqlCliente = mysqli_query($MySQLi, "SELECT * FROM Clientes WHERE idCliente='$idCliente' ");
    $dataCliente = mysqli_fetch_assoc($sqlCliente);
    $NombreCliente = $dataCliente['Nombres'] . ' ' . $dataCliente['Apellidos'];
    $nitCliente = $dataCliente['NIT'];
    $sqlUsuario = mysqli_query($MySQLi, "SELECT * FROM Usuarios WHERE idUser='$idUsuario' ");
    $dataUsuario = mysqli_fetch_assoc($sqlUsuario);
    $nombreVendedor = $dataUsuario['Nombres'] . " " . $dataUsuario['Apellidos'];
    $sqlClave = mysqli_query($MySQLi, "SELECT * FROM ClaveTemporal WHERE Clave='$clave' ");

    //procedimiento verificar nitCliente

    $ciudadCliente = $dataCliente['Ciudad'];
    $correoCliente = $dataCliente['Correo'];

    echo "<script> actualizarclientCode(); </script>";
    echo "<script> actualizarTotal(); </script>";
    echo "<script>actualizarSubTotal(); </script>";
    
    
    $factura = '<form method="post" action="./../Paginas/facturacion.php" id="datosFactura"><input type="hidden" name="action" value="facturaElectronica">
		<div class="row mt-2">
			<div class="col">
				<label class="form-label" for="clientReasonSocial">Razón social - Cliente</label>
				<input type="text" class="form-control" id="clientReasonSocial" name="clientReasonSocial"
                oninput="actualizarclientCode()" placeholder="RAZON SOCIAL" value="' . $NombreCliente . '">

			</div>

			<div class="col">
            <label for="clientDocumentType" class="form-label">Tipo de documento - Cliente</label>
            <select name="clientDocumentType" id="clientDocumentType"
            class="form-control  data-parsley-required="true">
            <option disabled="" selected="" >Seleccione
                    Tipo de documento
                </option>
            <option value="1">CI - CEDULA DE IDENTIDAD</option>
            <option value="2">CEX - CEDULA DE IDENTIDAD DE EXTRANJERO</option>
            <option value="3">PAS - PASAPORTE</option>
            <option value="4">OD - OTRO DOCUMENTO DE IDENTIDAD</option>
            <option selected value="5">NIT - NÚMERO DE IDENTIFICACIÓN TRIBUTARIA</option>
        </select>
			</div>
            <div class="col">
            <label class="form-label" for="clientNroDocument">Numero Documento - Cliente</label>
				<input class="form-control" name="clientNroDocument" id="clientNroDocument" value="' . $nitCliente . '" >
			</div>
		</div>
		<div class="row mt-2">
			<div class="col">
            <label for="clientCode" class="form-label">Código de cliente</label>
            <input type="text" class="form-control" id="clientCode" name="clientCode"
                placeholder="CODIGO CLIENTE" readonly>
			</div>
			<div class="col">
            <label for="clientCity" class="form-label">Ciudad Cliente</label>
            <input type="text" class="form-control" name="clientCity" value="' . $ciudadCliente . '"
                placeholder="CIUDAD CLIENTE">
			</div>
            <div class="col">
            <label for="clientEmail" class="form-label">Email - Cliente</label>
            <input type="text" class="form-control" name="clientEmail" placeholder="EMAIL@EMAIL.COM" value="' . $correoCliente . '">
			</div>
		</div>


        <div class="row mt-2">
        <div class="col">

        <label for="userPos" class="form-label">Vendedor</label>
                <input type="text" readonly class="form-control" name="userPos" autofocus  placeholder="VENDEDOR EN TURNO" value="' . $nombreVendedor . '">


        </div>
        <div class="col">

        <label for="paramCurrency" class="form-label">Tipo de moneda</label>
        <select name="paramCurrency" id="paramCurrency" class="form-control" data-parsley-required="true" >
		<option selected value="1">BOLIVIANO</option>
		</select>


        </div>
        <div class="col">
        <label for="paramPaymentMethod" class="form-label">Metodo de pago</label>
        <select name="paramPaymentMethod" id="paramPaymentMethod"
            class="form-control  data-parsley-required="true">
            <option disabled="" selected="" >Seleccione
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

    <button type="button" class="btn btn-primary" onclick="miFuncion()">AGREGAR PRODUCTO </button>

    <input type="hidden" value="0" step="0.1" min="0" class="form-control text-right" name="additionalDiscount" required>

    <input name="branchIdName" type="hidden" value="' . $sucursalCompra . '">
    <input name="idCotizacion" type="hidden" value="' . $idCotizacion . '">

   

    


    </div>



    </div>

        </div>



		<div class="row mt-4">
			<div class="col">
				<table id="tableUsuario" class="table"  width="100%">
					<thead class="thead-dark">
						<tr>
							<th scope="col" width="15%" class="text-center p-5"><h5>Cantidad</th>
                            <th scope="col" width="15%" class="text-center p-5"><h5>CodProd</th>
							<th scope="col" width="40%" class="text-center p-5"><h5>Producto</th>
                            <th scope="col" width="15%" class="text-center p-5"><h5>PrecioUnidad Bs</th>

                            
							<th scope="col" width="15%" class="text-center p-5"><h5>SubTotal Bs</th>
                            <th scope="col" width="15%" class="text-center p-5"><h5>Eliminar</th>
						</tr>
					</thead>
					<tbody>';
    $sqlPrecioDolar = mysqli_query($MySQLi, "SELECT * FROM precio ");
    $dolarBd = mysqli_fetch_assoc($sqlPrecioDolar);

    //session_start();
    $datos = array();
 
        $_SESSION["carrito"] = [];
    

    $count = 0;
    while ($data = mysqli_fetch_assoc($sqlClave)) {$factura .= '
							<tr>
								<th scope="row"><input class="form-control mb-2 text-center" min="1" type="number"  name="' . $count . 'qty" id="' . $count . 'qty"  onchange="actualizarSubTotal()" oninput="actualizarSubTotal()" value="' . $data['Cantidad'] . '"></th>'; //CANTIDAD INPUT qty
        $idProducto = $data['idProducto'];
        $sqlProducto = mysqli_query($MySQLi, "SELECT * FROM Productos WHERE idProducto='$idProducto' ");
        $dataProducto = mysqli_fetch_assoc($sqlProducto);
        $codeProduct = $dataProducto['Modelo'];
        $codeProductSin = $dataProducto['codeProductSin'];
        $ProductoName = $dataProducto['Producto'] . " " . $dataProducto['Marca'] . " " . $dataProducto['Modelo'];

        $qty = $data['Cantidad'];
        $priceUnit = number_format(($data['PrecioOferta']* $dolarBd['precioDolar']), 2, ".", "");


    

        //$Precio = (number_format((($data['Cantidad'] * $data['PrecioOferta']) * $dolarBd['precioDolar']), 2, ".", ""));
        //$priceUnit = (number_format(($priceUnit * $dolarBd['precioDolar']), 2, ".", ""));
          
        $factura .= '           <td><input class="form-control" name="' . $count . 'codeProduct" value="' .$codeProduct. '"></td>
								<td><input class="form-control" name="' . $count . 'description" value="' . $ProductoName . '"></td>'; //NOMBRE PRODUCTO INPUT

                                
        $factura .= '           <td><input type="number "class="form-control text-right" name="'.$count.'priceUnit" id="' . $count . 'priceUnit" oninput="actualizarCantidad(' . $count . ')" value="'.$priceUnit.'"></td>  
                                <td ><input class="form-control text-right" readonly name="'.$count.'subTotal" id="'.$count.'subTotal"  value="" ></td>
                                <td class="text-center">
                                

                                <input type="button" class="btn btn-danger borrar" title="'.$count.'" value="Eliminar" >
                                
                                </td>
								
							</tr>
                            

                            ';
                            
        $datos[$count] = array(
            'activityEconomic' => '465000',
            'unitMeasure' => 62,
            'codeProductSin' => $codeProductSin,
            'codeProduct' => $codeProduct,
            'description' => $ProductoName,
            'qty' => (int) $qty,
            'priceUnit' => $priceUnit,
            'idProducto' => $idProducto
        );
        $count++;

    }
    

    $_SESSION["carrito"] = $datos;

    $factura .= '       <thead class="thead-light">
						<tr>
							<th colspan="4" class="text-right p-4 "><strong><h4>TOTAL</h4></strong></th>';
    $sqlClave2 = mysqli_query($MySQLi, "SELECT SUM(cantidad*PrecioOferta)AS total FROM ClaveTemporal WHERE Clave='$clave' ");
    $dataTotal = mysqli_fetch_assoc($sqlClave2);
    
    $factura .= '
							<th scope="col">
                                        <input class="form-control text-right" readonly name="total" id="total" value="' . number_format($dataTotal['total'] * $dolarBd['precioDolar'], 2, ".", "") . '">
                                        <input name="count" id="count" type="hidden" value="' . $count . '">
                                        <input name="correlativo" id="correlativo" type="hidden" value="">
                                        
                            </th>
                            <th scope="col" class="text-left p-4 "><strong><h4>Bs</h4></strong></th>
						</tr></thead>
					</tbody>
				</table>
			</div>
		</div>
		<div class="row mt-4">
			<div class="col">


				<button type="submit" onClick="desactivarClick()" id="submitButton" class="btn btn-primary btn-block facturar" type="button"><h4>Facturar</h4><i
                class="fas d-none efectSaveCotiza fa-spinner fa-pulse"></i></button>
			</div>
		</div>
		<div class="row mt-4">
			<div class="col">
				<div class="respuestaFactura text-center h1 text-success"></div>
			</div>
		</div></form>
		';
    //print_r($_SESSION["carrito"]);
    echo $factura; //formulario factura llenado mostramos
    //echo $nitCliente;
}

//------------------------------------------------------------------------------------------------------------------------//
elseif (isset($_POST['llamarDatosDebito'])) {

    include 'date.class.php';
    
    $idCotizacion = $_POST['llamarDatosDebito'];
    $sqlCotizacion = mysqli_query($MySQLi, "SELECT * FROM Cotizaciones WHERE idCotizacion='$idCotizacion' ");
    $dataCotiza = mysqli_fetch_assoc($sqlCotizacion);
    $sucursalCompra = $dataCotiza['Sucursal'];
    $codigoCotizacion = $dataCotiza['Code'];
    $idCliente = $dataCotiza['idCliente'];
    $idUsuario = $dataCotiza['idUser'];
    $clave = $dataCotiza['Clave'];
    $sqlCliente = mysqli_query($MySQLi, "SELECT * FROM Clientes WHERE idCliente='$idCliente' ");
    $dataCliente = mysqli_fetch_assoc($sqlCliente);
    $NombreCliente = $dataCliente['Nombres'] . ' ' . $dataCliente['Apellidos'];
    $nitCliente = $dataCliente['NIT'];
    $sqlUsuario = mysqli_query($MySQLi, "SELECT * FROM Usuarios WHERE idUser='$idUsuario' ");
    $dataUsuario = mysqli_fetch_assoc($sqlUsuario);
    $nombreVendedor = $dataUsuario['Nombres'] . " " . $dataUsuario['Apellidos'];
    $sqlClave = mysqli_query($MySQLi, "SELECT * FROM ClaveTemporal WHERE Clave='$clave' ");

    $sqlFactura = mysqli_query($MySQLi, "SELECT * FROM factura WHERE idCotizacion='$idCotizacion' and siatCodeState=908 ");
    $dataFactura = mysqli_fetch_assoc($sqlFactura);
    $codigoFactura = $dataFactura['invoiceCode'];
    $numeroFactura = $dataFactura['invoiceNumber'];
    $exceptionCode = $dataFactura['exceptionCode'];

    //procedimiento verificar nitCliente

    $ciudadCliente = $dataCliente['Ciudad'];
    $correoCliente = $dataCliente['Correo'];

    echo "<script> actualizarclientCode(); </script>";
    echo "<script> actualizarTotal(); </script>";
    echo "<script>actualizarSubTotal(); </script>";

    $factura = '<form method="post" action="./../Paginas/facturacionDebitoCredito.php" id="datosFactura"><input type="hidden" name="action" value="facturaElectronica">
        <div class="row mt-2">
            <div class="col">
                <label for="userPos" class="form-label">Vendedor</label>
                <input type="text" readonly class="form-control" name="userPos" autofocus  placeholder="VENDEDOR EN TURNO" value="' . $nombreVendedor . '">
            </div>
            <div class="col">
            </div>

            
		</div>
		<div class="row mt-2">
			<div class="col">
				<label class="form-label" for="invoiceCode">CODIGO FACTURA</label>
				<input type="text" class="form-control" id="invoiceCode" name="invoiceCode" placeholder="CODIGO FACTURA" value="' . $codigoFactura . '">
			</div>
            
            <div class="col">
				<label class="form-label" for="invoiceNumber">NUMERO FACTURA</label>
				<input type="text" class="form-control" id="invoiceNumber" name="invoiceNumber" placeholder="NUMERO FACTURA" value="' . $numeroFactura . '">
			</div>
		</div>
		

        <div class="row mt-2">
            <div class="col">
               
                <input name="branchIdName"  type="hidden" value="' . $sucursalCompra . '" >

               
                <input name="idCotizacion"  type="hidden" value="' . $idCotizacion . '" >

                
                <input name="clientReasonSocial"  type="hidden" value="' . $NombreCliente . '" >

				<input name="exceptionCode" type="hidden" id="exceptionCode" value="' . $exceptionCode . '" >

                <label for="clientEmail" class="form-label">Email - Cliente</label>
                <input type="text" class="form-control" name="clientEmail" placeholder="EMAIL@EMAIL.COM" value="' . $correoCliente . '">
            </div>
            <div class="col">
            <label for="clientNroDocument" class="form-label">NroDocumento - Cliente</label>
            <input class="form-control" readonly name="clientNroDocument" id="clientNroDocument" value="' . $nitCliente . '" >
			</div>
    </div>
        </div>
		<div class="row mt-4">
			<div class="col">
				<table id="dataTable" class="table"  width="100%">
					<thead class="thead-dark">
						<tr>
                        <th scope="col" width="15%" class="text-center p-5"><h5>Cantidad</th>
                        <th scope="col" width="15%" class="text-center p-5"><h5>CodProd</th>
                        <th scope="col" width="40%" class="text-center p-5"><h5>Producto</th>
                        <th scope="col" width="15%" class="text-center p-5"><h5>PrecioUnidad Bs</th>                       
                        <th scope="col" width="15%" class="text-center p-5"><h5>SubTotal Bs</th>
                        
                            <th scope="col" width="15%" class="text-center p-5"><h5>Eliminar</th>
						</tr>
					</thead>
					<tbody>';
    $sqlPrecioDolar = mysqli_query($MySQLi, "SELECT * FROM precio ");
    $dolarBd = mysqli_fetch_assoc($sqlPrecioDolar);
    //session_start();
    $datos = array();
    
        $_SESSION["carrito"] = [];
    
    $count = 0;
    while ($data = mysqli_fetch_assoc($sqlClave)) {$factura .= '
							<tr>
								<th scope="row"><input class="form-control mb-2 text-center" min="1" type="number"  name="' . $count . 'qty" id="' . $count . 'qty"  onchange="actualizarSubTotal()" oninput="actualizarSubTotal()" value="' . $data['Cantidad'] . '"></th>
                                
                                
                                
                                '; //CANTIDAD INPUT qty
        $idProducto = $data['idProducto'];
        $sqlProducto = mysqli_query($MySQLi, "SELECT * FROM Productos WHERE idProducto='$idProducto' ");
        $dataProducto = mysqli_fetch_assoc($sqlProducto);

        $priceUnit =  number_format(($data['PrecioOferta']* $dolarBd['precioDolar']), 2, ".", "");
        $qty = $data['Cantidad'];
        $codeProduct = $dataProducto['Modelo'];
        $codeProductSin = $dataProducto['codeProductSin'];

        
        $ProductoName = $dataProducto['Producto'] . " " . $dataProducto['Marca'] . " " . $dataProducto['Modelo'];
        $factura .= '
        <td><input class="form-control" name="' . $count . 'codeProduct" value="' .$codeProduct. '"></td>
                             
        <td><input class="form-control" name="' . $count . 'description" value="' . $ProductoName . '"></td>'; //NOMBRE PRODUCTO INPUT

        //$Precio = (number_format((($data['Cantidad'] * $data['PrecioOferta']) * $dolarBd['precioDolar']), 2, ".", ""));

        $factura .= '
       
        <td><input type="number "class="form-control text-right" name="'.$count.'priceUnit" id="' . $count . 'priceUnit" oninput="actualizarCantidad(' . $count . ')" value="'.$priceUnit.'"></td>  
        <td ><input class="form-control text-right"   name="'.$count.'subTotal" id="'.$count.'subTotal"  value="" ></td>

                                <td class="text-center">
                                

                                <input type="button" class="btn btn-danger borrar"  value="Eliminar" >
                                
                                </td>

							</tr>';
        $datos[$count] = array(
            'activityEconomic' => '465000',
            'unitMeasure' => 62,
            'codeProductSin' => $codeProductSin,
            'codeProduct' => $codeProduct,
            'description' => $ProductoName,
            'qty' => (int) $qty,
            'priceUnit' => $priceUnit,
            'amountDiscount' => 0,
            'detailId' => 1,
            'returnProduct' => true,
        );
        $count++;
    }

    $_SESSION["carrito"] = $datos;

    $factura .= '       <thead class="thead-light">
						<tr>
							<th colspan="4" class="text-right p-4 "><strong><h4>TOTAL</h4></strong></th>';
    $sqlClave2 = mysqli_query($MySQLi, "SELECT SUM(cantidad*PrecioOferta)AS total FROM ClaveTemporal WHERE Clave='$clave' ");
    $dataTotal = mysqli_fetch_assoc($sqlClave2);
    $factura .= '
							<th scope="col"><input class="form-control text-right" readonly name="total" id="total" value="' . number_format($dataTotal['total'] * $dolarBd['precioDolar'], 2, ".", "") . '"></th>
						</tr></thead>
                        <input name="count" id="count" type="hidden" value="' . $count . '">
					</tbody>
				</table>
			</div>
		</div>
		<div class="row mt-4">
			<div class="col">


				<button type="submit" class="btn btn-primary btn-block facturar" type="button"><h4>GENERAR NOTA DEBITO-CREDITO</button>
			</div>
		</div>
		<div class="row mt-4">
			<div class="col">
				<div class="respuestaFactura text-center h1 text-success"></div>
			</div>
		</div></form>
		';
    

    echo $factura; //formulario factura llenado mostramos
    //echo $nitCliente;
//------------------------------------------------ANULAR FACTURA------------------------------------------------
}
?>