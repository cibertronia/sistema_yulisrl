<?php
include './../includes/conexion.php';
error_reporting(0);

$Meta1 = (int)$_POST['Meta1'];
$Comision1 = $_POST['Comision1'];

$Meta2 = (int)$_POST['Meta2'];
$Comision2 = $_POST['Comision2'];

$qtyPersonal = $_POST['qtyPersonal'];

$idTabla = $_POST['idTabla'];

$sql = mysqli_query($MySQLi, "UPDATE TablaComisiones SET Meta1='$Meta1',Comision1='$Comision1',Meta2='$Meta2',Comision2='$Comision2',personal_dividir='$qtyPersonal' WHERE idTabla='$idTabla'") ;

   if($sql){
    ?>

<script type="text/javascript">
Swal.fire({
    position: 'center',
    type: 'success',
    title: 'META Y COMISION EDITADOS CORRECTAMENTE',
    html: 'SUCCESS',
    showConfirmButton: false,
    animation: false,
    customClass: {
        popup: 'animated rotateIn'
    }
});
setTimeout(function() {
    location.reload();
}, 3500);
</script>
<?php

   } else{

    ?>

    <script type="text/javascript">
    Swal.fire({
        position: 'center',
        type: 'error',
        title: 'ERROR AL ACTUALIZAR',
        html: 'ERROR',
        showConfirmButton: false,
        animation: false,
        customClass: {
            popup: 'animated rotateIn'
        }
    });
    setTimeout(function() {
        location.reload();
    }, 3500);
    </script>
    <?php


   }



//echo '<script type="text/JavaScript"> location.reload(); </script>';
?>