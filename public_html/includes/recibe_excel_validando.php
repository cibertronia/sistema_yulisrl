<?php
require('conexion.php');
//require 'config.php';
try {
$tipo = $_FILES['dataCliente']['type'];
$tamanio = $_FILES['dataCliente']['size'];
$archivotmp = $_FILES['dataCliente']['tmp_name'];
$lineas = file($archivotmp);
$i = 0;
date_default_timezone_set('America/La_Paz');
$fechaActual = date('c');

foreach ($lineas as $linea) {
    $cantidad_registros = count($lineas);
    $cantidad_regist_agregados = ($cantidad_registros - 1);

    if ($i != 0) {

        $datos = explode(";", $linea);

        $fecha_poliza = !empty($datos[0]) ? ($datos[0]) : '';
        $codigo = !empty($datos[1]) ? ($datos[1]) : '';
        $detalle = !empty($datos[2]) ? ($datos[2]) : '';
        $detalle =utf8_encode($detalle);

        $saldo_fisico = !empty($datos[3]) ? ($datos[3]) : '';
        $c_u_facturar_minimo =  !empty($datos[4]) ? ($datos[4]) : '';
        $importes_para_facturar =!empty($datos[5]) ? ($datos[5]) : '';

        //eliminamos "." puntos en los numeros
        $c_u_facturar_minimo=str_replace('.','',$c_u_facturar_minimo);
        $importes_para_facturar=str_replace('.','',$importes_para_facturar);

        //cambiamos "," comas por puntos para guardar correctamente
        $c_u_facturar_minimo=str_replace(',','.',$c_u_facturar_minimo);
        $importes_para_facturar=str_replace(',','.',$importes_para_facturar);

        $c_u_facturar_minimo=number_format(($c_u_facturar_minimo), 2, ".", "");
        $importes_para_facturar=number_format(($importes_para_facturar), 2, ".", "");



      
        if (!empty($detalle)) {
            $checkemail_duplicidad = ("SELECT codigo FROM productos_fiscales WHERE codigo='" . ($codigo) . "' ");
            $ca_dupli = mysqli_query($MySQLi, $checkemail_duplicidad);
            $cant_duplicidad = mysqli_num_rows($ca_dupli);
        }

        //No existe Registros Duplicados
        // if ($cant_duplicidad == 0) {
            
// codesin'99795'  cambiar por  99794 en produccion
//cambiar en produccion por "yuli": "99794"
            $insertarData = "INSERT INTO productos_fiscales(
                                            codeProductSin,
                                            fecha_poliza,
                                            codigo,
                                            detalle,
                                            saldo_fisico,
                                            c_u_facturar_minimo,
                                            importes_para_facturar,
                                            fecha_subido_sistema
                                            
                                            ) VALUES(
                                            '99794', 
                                            '$fecha_poliza',
                                            '$codigo',
                                            '$detalle',
                                            '$saldo_fisico',
                                            '$c_u_facturar_minimo',
                                            '$importes_para_facturar',
                                            '$fechaActual'
                                            )";
            $query=mysqli_query($MySQLi, $insertarData);

            

                                    // }
    /**Caso Contrario actualizo el o los Registros ya existentes*/
        // else {
        //     $updateData = ("UPDATE productos_fiscales SET
        // fecha_poliza='" . $fecha_poliza . "',
		// codigo='" . $codigo . "',
        // detalle='" . $detalle . "'
        // WHERE codigo='" . $codigo . "'
        //                     ");
        //     $result_update = mysqli_query($MySQLi, $updateData);
        // }
    }
    $i++;
}
if($query){
    ?>
    <script type="text/javascript">
    Swal.fire({
        type: 'success',
        title: 'IMPORTADO CORRECTAMENTE',
    })
    setTimeout(function() {
        location.reload();
    }, 4500);
    </script>

<?php


}else{  ?>
    <script type="text/javascript">
    Swal.fire({
        type: 'error',
        title: 'ERROR AL IMPORTAR ARCHIVO',
    })
    setTimeout(function() {
        location.reload();
    }, 4500);
    </script>
<?php
}

} catch (Error $e) {
    echo 'Error Encontrado: ',  $e->getMessage(), "\n";
    echo    '<script type="text/javascript">
    Swal.fire({
        type: "error",
        title: "ARCHIVO NO SELECCIONADO",
    })
    setTimeout(function() {
        location.reload();
    }, 2500);
    </script>';
}

