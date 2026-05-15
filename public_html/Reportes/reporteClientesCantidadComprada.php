<?php
require '../includes/librerias/mPDF/vendor/autoload.php';
require '../includes/conexion.php';
include '../includes/date.class.php';
require '../includes/librerias/phpMailer/vendor/autoload.php';

session_start();
$idUser = $_SESSION['idUser'];
$ConsltaUser = mysqli_query($MySQLi, "SELECT * FROM Usuarios WHERE idUser='$idUser' ");
$datosUser = mysqli_fetch_assoc($ConsltaUser);
$miCiudad = $datosUser['Ciudad'];

error_reporting(0);

mysqli_query($MySQLi, "SET lc_time_names= 'es_BO' ");
if (isset($_GET['reporteClientesCantidadComprada']) and isset($_GET['fechaInicio']) and isset($_GET['fechafin'])) {
    $Sucursal = $_GET['reporteClientesCantidadComprada'];
    $Inicio = $_GET['fechaInicio'];
    $Fin = $_GET['fechafin'];

    header("Content-type: application/vnd.ms-excel; name='excel'");
    header("Content-Disposition: filename=reporteClientesCantidadComprada" . $Inicio . "__" . $Fin . ".xls");
    header("Pragma: no-cache");
    header("Pragma: no-cache");
    header("Expires: 0");

    if ($Sucursal == 'todas_Las_Sucursales' || $Sucursal == '') {
        $Sucursal = 'todas_Las_Sucursales';
        $queryClientesCantidad = mysqli_query($MySQLi,
            "SELECT
            idCliente,Sucursal,
            SUM(Cantidad) AS 'cantidad_total_comprada'
            FROM
            `Ventas`
            WHERE
            Fecha BETWEEN '$Inicio' AND '$Fin'
            GROUP BY
            `idCliente`
            ORDER BY cantidad_total_comprada DESC");
    } else {

        $queryClientesCantidad = mysqli_query($MySQLi,
            "SELECT
            idCliente,Sucursal,
            SUM(Cantidad) AS 'cantidad_total_comprada'
            FROM
            `Ventas`
            WHERE
            Fecha BETWEEN '$Inicio' AND '$Fin' AND Sucursal ='$Sucursal'
            GROUP BY
            `idCliente`
            ORDER BY cantidad_total_comprada DESC");

    }
    ?>

<table border="1">
    <thead>
        <tr>
            <th colspan="9" style="text-align: left;">
                <h3><span style="text-transform: uppercase;letter-spacing: 1px;font-size: 16px">CLIENTES QUE
                        COMPRARON <br> EN MAS CANTIDAD DE UNIDADES ==> <span
                            style="text-transform: uppercase;letter-spacing: 1px;font-size: 18px">
                            <?php echo strtoupper ( $Sucursal ) .'   ____'   . $Inicio . "__" . $Fin ?></span></span>
                </h3>
            </th>
        </tr>
        <tr>
            <th class="text-center" style="text-align: center;background-color: #FDE9D9">
                N&ordm;</th>
            <th class="text-center" style="text-align: center;background-color: #FDE9D9">
                Nombre</th>
            <th class="text-center" style="text-align: center;background-color: #FDE9D9">
                Apellido</th>
            <th class="text-center" style="text-align: center;background-color: #FDE9D9">
                Correo</th>
            <th class="text-center" style="text-align: center;background-color: #FDE9D9">
                Celular</th>
            <th class="text-center" style="text-align: center;background-color: #FDE9D9">
                Fijo</th>
            <th class="text-center" style="text-align: center;background-color: #FDE9D9">
                Empresa</th>
            <th class="text-center" style="text-align: center;background-color: #FDE9D9">
                Cantidad <br> U/Producto <br>Comprados</th>
            <th class="text-center" style="text-align: center;background-color: #FDE9D9">
                Sucursal</th>


        </tr>
    </thead>
    <tbody><?php

$Number = 1;

while ($dataVenta = mysqli_fetch_assoc($queryClientesCantidad)) {
    $idCliente = $dataVenta['idCliente'];
    $QueryClientes = mysqli_query($MySQLi, "SELECT * FROM Clientes WHERE idCliente='$idCliente'");
    $dataClientes = mysqli_fetch_assoc($QueryClientes);
    ?>
        <tr>
            <td><?php echo $Number ?></td>
            <!-- <td><?php // echo $dataVenta['idCliente'] ?></td> -->
            <td class=""><?php echo mb_convert_encoding($dataClientes['Nombres'], 'HTML-ENTITIES', 'UTF-8'); ?></td>
            <td class=""><?php echo mb_convert_encoding($dataClientes['Apellidos'], 'HTML-ENTITIES', 'UTF-8'); ?></td>
            <td class=""><?php echo mb_convert_encoding($dataClientes['Correo'], 'HTML-ENTITIES', 'UTF-8'); ?></td>
            <td class="text-center"><?php echo $dataClientes['Celular'] ?></td>
            <td class="text-center"><?php echo $dataClientes['Otro'] ?></td>
            <td><?php echo mb_convert_encoding($dataClientes['Empresa'], 'HTML-ENTITIES', 'UTF-8'); ?></td>
            <td style="text-align: center;background-color: #D8E4BC">
                <?php echo $dataVenta['cantidad_total_comprada'] ?></td>
            <td class=""><?php echo $dataVenta['Sucursal'] ?></td>


        </tr>
        <?php $Number++;}
//mysqli_close($MySQLi);?>

    </tbody>
</table>

<?php mysqli_close($MySQLi);

}