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
if (isset($_GET['reporteClientesNuevos']) and isset($_GET['fechaInicio']) and isset($_GET['fechafin'])) {
    $Sucursal = $_GET['reporteClientesNuevos'];
    $Inicio = $_GET['fechaInicio'];
    $Fin = $_GET['fechafin'];

    header("Content-type: application/vnd.ms-excel; name='excel'");
    header("Content-Disposition: filename=reporteClientesNuevos" . $Inicio . "__" . $Fin . ".xls");
    header("Pragma: no-cache");
    header("Pragma: no-cache");
    header("Expires: 0");

    if ($Sucursal == 'todas_Las_Sucursales' || $Sucursal == '') {
        $Sucursal = 'todas_Las_Sucursales';
        $queryClientes = mysqli_query($MySQLi,
            "SELECT
            *
            FROM
            `Clientes`
            WHERE
            Fecha_Reg BETWEEN '$Inicio' AND '$Fin' ORDER BY `Fecha_Reg` DESC");
    } else {

        $queryClientes = mysqli_query($MySQLi,
            "SELECT
            *
            FROM
            `Clientes`
            WHERE
            Fecha_Reg BETWEEN '$Inicio' AND '$Fin' AND Sucursal ='$Sucursal' ORDER BY `Fecha_Reg` DESC");

    }
    ?>

<table border="1">
    <thead>
        <tr>
            <th colspan="9" style="text-align: left;">
                <h3><span style="text-transform: uppercase;letter-spacing: 1px;font-size: 16px">CLIENTES NUEVOS EN EL
                        MES
                        <br> ==> <span style="text-transform: uppercase;letter-spacing: 1px;font-size: 18px">
                            <?php echo strtoupper ( $Sucursal ).'   ____'   . $Inicio . "__" . $Fin ?></span></span>
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
                Fecha Registro</th>
            <th class="text-center" style="text-align: center;background-color: #FDE9D9">
                Sucursal</th>


        </tr>
    </thead>
    <tbody><?php

$Number = 1;
// $queryClientesCantidad = mysqli_query($MySQLi, $queryClientes);
while ($dataVenta = mysqli_fetch_assoc($queryClientes)) {
    $idCliente = $dataVenta['idCliente'];
    $QueryCompras = mysqli_query($MySQLi, "SELECT * FROM Ventas WHERE idCliente='$idCliente'");
    $nroCompras = mysqli_num_rows($QueryCompras);
    if($nroCompras>0){
    ?>
        <tr>
            <td><?php echo $Number ?></td>
            <!-- <td><?php // echo $dataVenta['idCliente'] ?></td> -->
            <td class=""><?php echo mb_convert_encoding($dataVenta['Nombres'], 'HTML-ENTITIES', 'UTF-8'); ?></td>
            <td class=""><?php echo mb_convert_encoding($dataVenta['Apellidos'], 'HTML-ENTITIES', 'UTF-8'); ?></td>
            <td class=""><?php echo mb_convert_encoding($dataVenta['Correo'], 'HTML-ENTITIES', 'UTF-8'); ?></td>
            <td class="text-center"><?php echo $dataVenta['Celular'] ?></td>
            <td class="text-center"><?php echo $dataVenta['Otro'] ?></td>
            <td><?php echo mb_convert_encoding($dataVenta['Empresa'], 'HTML-ENTITIES', 'UTF-8'); ?></td>
            <td style="text-align: center;background-color: #D8E4BC">
                <?php echo $dataVenta['Fecha_Reg'] ?></td>

            <td class=""><?php echo $dataVenta['Sucursal'] ?></td>


        </tr>
        <?php $Number++;}
        
    }
//mysqli_close($MySQLi);?>

    </tbody>
</table>

<?php mysqli_close($MySQLi);

}