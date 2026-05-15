<?php
require 'conexion.php';
//require 'config.php';
try {
    $tipo = $_FILES['dataCliente']['type'];
    $tamanio = $_FILES['dataCliente']['size'];
    $archivotmp = $_FILES['dataCliente']['tmp_name'];
    $lineas = file($archivotmp);
    $i = 0;
    date_default_timezone_set('America/La_Paz');
    $fechaActual = date('d/m/y h:i:s A');

    foreach ($lineas as $linea) {
        $cantidad_registros = count($lineas);
        $cantidad_regist_agregados = ($cantidad_registros - 1);

        if ($i != 0) {

            $datos = explode(";", $linea);

            $Producto = !empty($datos[0]) ? ($datos[0]) : '';
            $Marca = !empty($datos[1]) ? ($datos[1]) : '';
            $Modelo = !empty($datos[2]) ? ($datos[2]) : '';
            $Descripcion = !empty($datos[3]) ? ($datos[3]) : '';
            // $Imagen = !empty($datos[4]) ? ($datos[4]) : '';

            $StockCB = !empty($datos[4]) ? ($datos[4]) : '';
            $PrecioCB = !empty($datos[5]) ? ($datos[5]) : '';
            $ObservacionesCB = !empty($datos[6]) ? ($datos[6]) : '';
            $StockLP = !empty($datos[7]) ? ($datos[7]) : '';
            $PrecioLP = !empty($datos[8]) ? ($datos[8]) : '';

            $ObservacionesLP = !empty($datos[9]) ? ($datos[9]) : '';
            $StockSC = !empty($datos[10]) ? ($datos[10]) : '';
            $PrecioSC = !empty($datos[11]) ? ($datos[11]) : '';
            $ObservacionesSC = !empty($datos[12]) ? ($datos[12]) : '';
            $StockTJ = !empty($datos[13]) ? ($datos[13]) : '';

            $PrecioTJ = !empty($datos[14]) ? ($datos[14]) : '';
            $ObservacionesTJ = !empty($datos[15]) ? ($datos[15]) : '';

            // $StockTotal = !empty($datos[16]) ? ($datos[16]) : '';
            // $codeProductSin = !empty($datos[18]) ? ($datos[18]) : '';

            //aplicamos utf8 a las cadenas de textos
            $Producto = utf8_encode($Producto);
            $Marca = utf8_encode($Marca);
            $Modelo = utf8_encode($Modelo);
            $Descripcion = utf8_encode($Descripcion);
            $ObservacionesCB = utf8_encode($ObservacionesCB);
            $ObservacionesLP = utf8_encode($ObservacionesLP);
            $ObservacionesSC = utf8_encode($ObservacionesSC);
            $ObservacionesTJ = utf8_encode($ObservacionesTJ);

            //convertimos a entero los stok
            $StockCB = (int) $StockCB;
            $StockLP = (int) $StockLP;
            $StockSC = (int) $StockSC;
            $StockTJ = (int) $StockTJ;
            $StockTotal = $StockCB + $StockLP + $StockSC + $StockTJ;

            //eliminamos "." puntos en los numeros          
            $PrecioCB = str_replace('.', '', $PrecioCB);
            $PrecioLP = str_replace('.', '', $PrecioLP);
            $PrecioSC = str_replace('.', '', $PrecioSC);
            $PrecioTJ = str_replace('.', '', $PrecioTJ);

            //cambiamos "," comas por puntos "." para guardar correctamente
            $PrecioCB = str_replace(',', '.', $PrecioCB);
            $PrecioLP = str_replace(',', '.', $PrecioLP);
            $PrecioSC = str_replace(',', '.', $PrecioSC);
            $PrecioTJ = str_replace(',', '.', $PrecioTJ);


            // if (!empty($detalle)) {
            //     $checkemail_duplicidad = ("SELECT codigo FROM productos_fiscales WHERE codigo='" . ($codigo) . "' ");
            //     $ca_dupli = mysqli_query($MySQLi, $checkemail_duplicidad);
            //     $cant_duplicidad = mysqli_num_rows($ca_dupli);
            // }

            //No existe Registros Duplicados
            // if ($cant_duplicidad == 0) {

            // codesin'99795'  cambiar por  99794 en produccion
            //cambiar en produccion por "yuli": "99794"
            $insertarData = "INSERT INTO Productos(

                Producto,
                Marca,
                Modelo,
                Descripcion,


                StockCB,
                PrecioCB,
                ObservacionesCB,
                StockLP,
                PrecioLP,

                ObservacionesLP,
                StockSC,
                PrecioSC,
                ObservacionesSC,
                StockTJ,

                PrecioTJ,
                ObservacionesTJ,
                StockTotal,
                codeProductSin

            )
            VALUES(

                '$Producto',
                '$Marca',
                '$Modelo',
                '$Descripcion',


                '$StockCB',
                '$PrecioCB',
                '$ObservacionesCB',
                '$StockLP',
                '$PrecioLP',

                '$ObservacionesLP',
                '$StockSC',
                '$PrecioSC',
                '$ObservacionesSC',
                '$StockTJ',

                '$PrecioTJ',
                '$ObservacionesTJ',
                '$StockTotal',
                '99794'

            )";
            $query = mysqli_query($MySQLi, $insertarData);

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
    if ($query) {
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

    } else {?>
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
    echo 'Error Encontrado: ', $e->getMessage(), "\n";
    echo '<script type="text/javascript">
    Swal.fire({
        type: "error",
        title: "ARCHIVO NO SELECCIONADO",
    })
    setTimeout(function() {
        location.reload();
    }, 2500);
    </script>';
}