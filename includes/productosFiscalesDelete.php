<?php
session_start();
$Action 	=	filter_var($_POST['action'],FILTER_SANITIZE_FULL_SPECIAL_CHARS);
switch ($Action) {
    case 'BorrarProductoFiscal':
    if (isset($_SESSION['idUser'])) {
        if ($_SESSION['Rango']) {
            include 'conexion.php';
            $idProducto 	=	$_POST['id'];
            // $queryProducto 	=	mysqli_query($MySQLi,"SELECT * FROM productos_fiscales WHERE idProducto='$idProducto' ");
            // $dataProducto 	=	mysqli_fetch_assoc($queryProducto);
            // $nameImagen 	=	$dataProducto['Imagen'];

            // /* 	BORRAMOS LA IMAGEN EN LA CARPETA /Productos   */
            // $files = glob("Productos/$nameImagen");
            // foreach($files as $file){
            //     if(is_file($file))
            //     unlink($file); //elimino el fichero
            // }
            $delProducto 	=	mysqli_query($MySQLi,"DELETE FROM productos_fiscales WHERE idProducto='$idProducto' ")or die(mysqli_error($MySQLi)."<br>Error en la línea: ".__LINE__);
            if ($delProducto) { mysqli_close($MySQLi); ?>
<script type="text/javascript">
Swal.fire({
type: 'success',
title: 'Producto Fiscal Eliminado',
animation: false,
customClass: {
popup: 'animated bounceInDown'
}
});
setTimeout(function() {
location.reload();
})
</script><?php
            }else{ mysqli_close($MySQLi); ?>
<script type="text/javascript">
Swal.fire({
type: 'error',
title: 'Error al Eliminar Producto Fiscal',
animation: false,
customClass: {
popup: 'animated shake'
}
})
</script><?php
            }
        }else{
            mysqli_close($MySQLi);
            session_destroy(); ?>
<script type="text/javascript">
Swal.fire({
type: 'error',
title: 'SIN PRIVILEGIOS',
animation: false,
customClass: {
popup: 'animated shake'
}
})
setTimeout(function() {
location.reload();
}, 2500);
</script><?php
        }
    }else{
        mysqli_close($MySQLi);
        session_destroy(); ?>
<script type="text/javascript">
Swal.fire({
position: 'center',
type: 'error',
title: 'SESIÓN EXPIRADA',
showConfirmButton: false,
timer: 2500,
})
setTimeout(function() {
location.reload();
}, 2500);
</script><?php
    }
    break;
}
