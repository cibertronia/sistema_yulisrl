<?php


function capturar_fiscales()
{
    include 'conexion.php';
    include 'date.class.php';
    try {
        $queryProductos = mysqli_query(
            $MySQLi,
            "SELECT * FROM `productos_fiscales`
ORDER BY `idProducto` ASC"
        );

        // $dataProductos = mysqli_fetch_assoc($queryProductos);
        date_default_timezone_set('America/La_Paz');
        $fechaActual = date('c');

        $productos_fiscales = [];
        $count = 0;
        while ($dataProductos = mysqli_fetch_assoc($queryProductos)) {

            $productos_fiscales[$count] = array(
                $dataProductos['idProducto'],
                $dataProductos['saldo_fisico']
            );
            $count++;
        }

        $obj_merged = (object) [];
        $obj_merged->fecha = $fechaActual;

        $obj_merged->idProducto = 0;
        $obj_merged->saldo_fisico = 1;

        $obj_merged->productos_fiscales = $productos_fiscales; //array de productos_fiscales stocks en la fecha

        $captura = json_encode($obj_merged);

        $capturaQuery = mysqli_query($MySQLi, "INSERT INTO `capturas_productos_fiscales`(`captura`, `fecha`) VALUES ('$captura','$fechaActual')");


        if ($capturaQuery) {
            echo ' fiscales capturado';
        } else {
            echo 'error captura';
        }
    } catch (Exception $e) { // Manejo de errores
        echo "Se produjo un error: " . $e->getMessage();
    }
}
