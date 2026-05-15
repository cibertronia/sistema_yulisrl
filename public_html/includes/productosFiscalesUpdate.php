<?php
session_start();
if (isset($_SESSION['idUser'])) {
    include 'conexion.php';
    $idUser = $_SESSION['idUser'];
    $ConsltaUser = mysqli_query($MySQLi, "SELECT * FROM Usuarios WHERE idUser='$idUser' ");
    $datosUser = mysqli_fetch_assoc($ConsltaUser);
    $userPos = $datosUser['Nombres'] . " " . $datosUser['Apellidos'];
    $Ciudad= $datosUser['Ciudad'];

    ?>
<?php include './../php/meta.php';?>
<link href="assets/css/apple/app.min.css" rel="stylesheet">
<link href="assets/plugins/ionicons/css/ionicons.min.css" rel="stylesheet">
<link href="assets/plugins/datatables.net-bs4/css/dataTables.bootstrap4.min.css" rel="stylesheet">
<link href="assets/plugins/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css" rel="stylesheet">
<link href="assets/plugins/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css" rel="stylesheet">
<link href="assets/plugins/blueimp-gallery/css/blueimp-gallery.min.css" rel="stylesheet">
<link href="assets/plugins/blueimp-file-upload/css/jquery.fileupload.css" rel="stylesheet">
<link href="assets/plugins/blueimp-file-upload/css/jquery.fileupload-ui.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@8"></script>
<div class="respuesta"></div>
<?php

    $idProducto = $_POST['idProducto'];

    $detalle = $_POST['detalle'];
    $codigo = $_POST['codigo'];

    $fecha_poliza = $_POST['fecha_poliza'];
    $saldo_fisico = $_POST['saldo_fisico'];

    $c_u_facturar_minimo = $_POST['c_u_facturar_minimo'];
    $importes_para_facturar = $_POST['importes_para_facturar'];

    //insertamos historial --------------------------------------------------------ini
    $prodFis = mysqli_query($MySQLi, "SELECT * FROM productos_fiscales WHERE idProducto='$idProducto'");
    $dataprodFis = mysqli_fetch_assoc($prodFis);
    $detalle_actual = $dataprodFis['detalle'];
    $codigo_actual = $dataprodFis['codigo'];
    $fecha_poliza_actual = $dataprodFis['fecha_poliza'];
    $saldo_fisico_actual = $dataprodFis['saldo_fisico'];
    $c_u_facturar_minimo_actual = $dataprodFis['c_u_facturar_minimo'];
    $importes_para_facturar_actual = $dataprodFis['importes_para_facturar'];
    date_default_timezone_set('America/La_Paz');
    $fechaActual = date('c');
    if ($detalle_actual != $detalle) {

        $sql_insert = mysqli_query($MySQLi, "INSERT INTO historial_stock_productos_fiscales(
        producto_fiscal, inicial, cb, lp, sc, tj, final,
        vendedor, dateEmission, descripcion, idProducto
        )
        VALUES(
        '$detalle_actual', '0', '0','0',  '0', '0', '0',
        '$userPos','$fechaActual', 'Detalle se cambio a : $detalle','$idProducto'
        )") or die(mysqli_error($MySQLi) . "<br>Error en la línea: " . __LINE__);
        $detalle_actual=$detalle;

    }
    if ($codigo_actual != $codigo) {
        $sql_insert = mysqli_query($MySQLi, "INSERT INTO historial_stock_productos_fiscales(
        producto_fiscal, inicial, cb, lp, sc, tj, final,
        vendedor, dateEmission, descripcion, idProducto
        )
        VALUES(
        '$detalle_actual', '0', '0','0',  '0', '0', '0',
        '$userPos','$fechaActual', 'Codigo se cambio a : $codigo','$idProducto'
        )") or die(mysqli_error($MySQLi) . "<br>Error en la línea: " . __LINE__);
    }
    if ($fecha_poliza_actual != $fecha_poliza) {
        $sql_insert = mysqli_query($MySQLi, "INSERT INTO historial_stock_productos_fiscales(
        producto_fiscal, inicial, cb, lp, sc, tj, final,
        vendedor, dateEmission, descripcion, idProducto
        )
        VALUES(
        '$detalle_actual', '0', '0','0',  '0', '0', '0',
        '$userPos','$fechaActual', 'Fecha_poliza se cambio a : $fecha_poliza','$idProducto'
        )") or die(mysqli_error($MySQLi) . "<br>Error en la línea: " . __LINE__);
    }
    if ($saldo_fisico_actual != $saldo_fisico) {
        
        //se adiciono o se resto stock calculamos la diferencia -resultado para cb,lp,sc,tj
        $resultado=$saldo_fisico-$saldo_fisico_actual;
        $cb = 0;
		$lp = 0;
		$sc = 0;
		$tj = 0;

        if ($Ciudad 		==	'Cochabamba') {
            $cb = $resultado;
        }elseif ($Ciudad==	'La Paz') {
            $lp = $resultado;
        }elseif ($Ciudad==	'Santa Cruz') {
            $sc = $resultado;
        }else{
            $tj = $resultado;
        }

        $sql_insert = mysqli_query($MySQLi, "INSERT INTO historial_stock_productos_fiscales(
        producto_fiscal, inicial, cb, lp, sc, tj, final,
        vendedor, dateEmission, descripcion, idProducto
        )
        VALUES(
        '$detalle_actual', '$saldo_fisico_actual', '$cb','$lp',  '$sc', '$tj', '$saldo_fisico',
        '$userPos','$fechaActual', 'El Stock(SaldoFisico) se cambio a : $saldo_fisico','$idProducto'
        )") or die(mysqli_error($MySQLi) . "<br>Error en la línea: " . __LINE__);
    }
    if ($c_u_facturar_minimo_actual != $c_u_facturar_minimo) {
        $sql_insert = mysqli_query($MySQLi, "INSERT INTO historial_stock_productos_fiscales(
        producto_fiscal, inicial, cb, lp, sc, tj, final,
        vendedor, dateEmission, descripcion, idProducto
        )
        VALUES(
        '$detalle_actual', '0', '0','0',  '0', '0', '0',
        '$userPos','$fechaActual', 'C/U Facturar Minimo se cambio a : $c_u_facturar_minimo','$idProducto'
        )") or die(mysqli_error($MySQLi) . "<br>Error en la línea: " . __LINE__);
    }
    if ($importes_para_facturar_actual != $importes_para_facturar) {
        $sql_insert = mysqli_query($MySQLi, "INSERT INTO historial_stock_productos_fiscales(
        producto_fiscal, inicial, cb, lp, sc, tj, final,
        vendedor, dateEmission, descripcion, idProducto
        )
        VALUES(
        '$detalle_actual', '0', '0','0',  '0', '0', '0',
        '$userPos','$fechaActual', 'Importes Para Facturar se cambio a : $importes_para_facturar','$idProducto'
        )") or die(mysqli_error($MySQLi) . "<br>Error en la línea: " . __LINE__);
    }
    

    //insertamos historial --------------------------------------------------------fin

    $changeData = mysqli_query($MySQLi, "UPDATE productos_fiscales SET
				detalle='$detalle',
				codigo='$codigo',
				fecha_poliza='$fecha_poliza',
				saldo_fisico='$saldo_fisico',
				c_u_facturar_minimo='$c_u_facturar_minimo',
				importes_para_facturar='$importes_para_facturar'
				                        WHERE idProducto='$idProducto' ") or die(mysqli_error($MySQLi) . "<br>Error en la línea: " . __LINE__);

    if ($changeData) {mysqli_close($MySQLi);?>

<script type="text/javascript">
Swal.fire({
    type: 'success',
    title: 'Producto Actualizado',
    animation: false,
    customClass: {
        popup: 'animated bounceInDown'
    }
})
setTimeout(function() {
    location.replace("/?root=productosFiscales");
}, 2000)
</script>

<?php exit();
    } else {mysqli_close($MySQLi);?>

<script type="text/javascript">
Swal.fire({
    type: 'error',
    title: 'Error al actualizar datos',
    animation: false,
    customClass: {
        popup: 'animated shake'
    }
})
</script>

<?php exit();
    }
}

?>