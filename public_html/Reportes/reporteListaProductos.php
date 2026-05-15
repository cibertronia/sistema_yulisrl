<?php
include '../includes/App/Models/Product.php';
include '../includes/App/Models/Sucursal.php';

use App\Models\Product;
use App\Models\Sucursal;

require '../includes/librerias/mPDF/vendor/autoload.php';
require '../includes/conexion.php';
include '../includes/date.class.php';
require '../includes/librerias/phpMailer/vendor/autoload.php';
// include '../includes/funcionesListaProductos.php';

// use PHPMailer\PHPMailer\PHPMailer;
// use PHPMailer\PHPMailer\SMTP;

// error_reporting(0);
// $mail = new PHPMailer(true);
mysqli_query($MySQLi, "SET lc_time_names= 'es_BO' ");
if (isset($_GET['reporteListaProductos']) and isset($_GET['fechaInicio']) and isset($_GET['fechafin'])) {
    $Sucursal = $_GET['reporteListaProductos'];
    $INICIO = $_GET['fechaInicio'];
    $FIN = $_GET['fechafin'];

    header("Content-type: application/vnd.ms-excel; name='excel'");
    header("Content-Disposition: filename=PRODUCTOS_" . $INICIO . "__" . $FIN . ".xls");
    header("Pragma: no-cache");
    header("Pragma: no-cache");
    header("Expires: 0");

    $productModel = new Product();
    $sucursalModel = new Sucursal();
    $productos = $productModel->getWithMovimientos($INICIO, $FIN);
?>

    <table border="1" style="font-family: Arial narrow">
        <thead>
            <tr>
                <th colspan="14" style="text-align: left;">
                    <h3>HISTORIAL PRODUCTOS DESDE EL <span style="color: green"><?php echo $INICIO ?></span> HASTA EL <span style="color: red"><?php echo $FIN ?></span></h3>
                </th>
            </tr>
            <tr>
                <th style="text-align:left;">N&ordm;</th>
                <th style="text-align:left;">Nombre</th>
                <th style="text-align:left;">Marca</th>
                <th style="text-align:left;">Modelo</th>
                <th style="text-align:center;background-color: #FFF2CC">Stock<br>CB</th>
                <th style="text-align:left;">Precio USD&nbsp;<br>CB</th>

                <th style="text-align:center;background-color: #DDEBF7">Stock<br>LP</th>
                <th style="text-align:left;">Precio USD&nbsp;<br>LP</th>

                <th style="text-align:center;background-color: #E2EFDA">Stock<br>SC</th>
                <th style="text-align:left;">Precio USD&nbsp;<br>SC</th>

                <th style="text-align:center;background-color: #e2d8e4">Stock<br>ST</th>
                <th style="text-align:left;">Precio USD&nbsp;<br>ST</th>

                <!-- <th style="text-align:center;background-color: #FCE4D6">Stock<br>TJ</th> -->
                <th style="text-align:center;background-color: #FCE4D6">Stock<br>FR</th>
                <!-- <th style="text-align:left;">Precio USD&nbsp;<br>TJ</th> -->
                <th style="text-align:left;">Precio USD&nbsp;<br>FR</th>

                <th style="text-align:center;background-color: #FFA500">Stock<br>Total</th>

                <th style="text-align:center; background-color: #FFF2CC">Extraidos
                    <br><?php echo "$INICIO" . '<br>/ ' . "$FIN"; ?> <br> CB
                </th>
                <th style="text-align:center; background-color: #FFF2CC">Recepcionados
                    <br><?php echo "$INICIO" . '<br>/ ' . "$FIN"; ?> <br> CB
                </th>

                <th style="text-align:center; background-color: #DDEBF7">Extraidos
                    <br><?php echo "$INICIO" . '<br>/ ' . "$FIN"; ?> <br> LP
                </th>
                <th style="text-align:center; background-color: #DDEBF7">Recepcionados
                    <br><?php echo "$INICIO" . '<br>/ ' . "$FIN"; ?> <br> LP
                </th>

                <th style="text-align:center; background-color: #E2EFDA">Extraidos
                    <br><?php echo "$INICIO" . '<br>/ ' . "$FIN"; ?> <br> SC
                </th>
                <th style="text-align:center; background-color: #E2EFDA">Recepcionados
                    <br><?php echo "$INICIO" . '<br>/ ' . "$FIN"; ?> <br> SC
                </th>

                <th style="text-align:center; background-color: #e2d8e4">Extraidos
                    <br><?php echo "$INICIO" . '<br>/ ' . "$FIN"; ?> <br> ST
                </th>
                <th style="text-align:center; background-color: #e2d8e4">Recepcionados
                    <br><?php echo "$INICIO" . '<br>/ ' . "$FIN"; ?> <br> ST
                </th>

                <th style="text-align:center; background-color: #FCE4D6">Extraidos
                    <br><?php echo "$INICIO" . '<br>/ ' . "$FIN"; ?> <br> FR
                </th>
                <th style="text-align:center; background-color: #FCE4D6">Recepcionados
                    <br><?php echo "$INICIO" . '<br>/ ' . "$FIN"; ?> <br> FR
                </th>

            </tr>
        </thead>
        <tbody>
        <?php 

        $sucursales = $sucursalModel->all();
        foreach ($sucursales as $item){
            ${'totalStock'.$item['iniciales']}      = 0;
            ${'totalPrecio'.$item['iniciales']}     = 0;
            ${'totalExtraido'.$item['iniciales']}   = 0;
            ${'totalRecibido'.$item['iniciales']}   = 0;
        }

        $totalStockTotal = 0;

        foreach($productos as $producto){ ?>
            <tr>
                <td><?= $producto['idProducto'] ?></td>
                <td><?= $producto['Producto'] ?></td>
                <td><?= $producto['Marca'] ?></td>
                <td><?= $producto['Modelo'] ?></td>
                <td style="text-align:center;background-color: #FFF2CC"><?= $producto['StockCB'] ?></td>
                <td><?= $producto['PrecioCB'] ?></td>
                <td style="text-align:center;background-color: #DDEBF7"><?= $producto['StockLP'] ?></td>
                <td><?= $producto['PrecioLP'] ?></td>
                <td style="text-align:center;background-color: #E2EFDA"><?= $producto['StockSC'] ?></td>
                <td><?= $producto['PrecioSC'] ?></td>
                <td style="text-align:center;background-color: #e2d8e4"><?= $producto['StockST'] ?></td>
                <td><?= $producto['PrecioST'] ?></td>
                <td style="text-align:center;background-color: #FCE4D6"><?= $producto['StockTJ'] ?></td>
                <td><?= $producto['PrecioTJ'] ?></td>
                <td style="text-align:center;background-color: #FFA500"><?= $producto['StockTotal'] ?></td>
                <td style="text-align:center;background-color: #FFF2CC"><?= $producto['extractCB'] ?></td>
                <td style="text-align:center;background-color: #FFF2CC"><?= $producto['receiveCB'] ?></td>
                <td style="text-align:center;background-color: #DDEBF7"><?= $producto['extractLP'] ?></td>
                <td style="text-align:center;background-color: #DDEBF7"><?= $producto['receiveLP'] ?></td>
                <td style="text-align:center;background-color: #E2EFDA"><?= $producto['extractSC'] ?></td>
                <td style="text-align:center;background-color: #E2EFDA"><?= $producto['receiveSC'] ?></td>
                <td style="text-align:center;background-color: #e2d8e4"><?= $producto['extractST'] ?></td>
                <td style="text-align:center;background-color: #e2d8e4"><?= $producto['receiveST'] ?></td>
                <td style="text-align:center;background-color: #FCE4D6"><?= $producto['extractTJ'] ?></td>
                <td style="text-align:center;background-color: #FCE4D6"><?= $producto['receiveTJ'] ?></td>
            </tr>
            <?php 
            // sumar los totales de stock por sucursal
            foreach ($sucursales as $item) {
                ${'totalStock' . $item['iniciales']}    += $producto['Stock' . $item['iniciales']];
                ${'totalPrecio' . $item['iniciales']}   += $producto['Precio' . $item['iniciales']];
                ${'totalExtraido' . $item['iniciales']} += $producto['extract' . $item['iniciales']];
                ${'totalRecibido' . $item['iniciales']} += $producto['receive' . $item['iniciales']];
            }
            //sumar el total de stock
            $totalStockTotal += $producto['StockTotal'];
        } ?>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td><b>Totales:</b></td>
                <td style="text-align:center;background-color: #FFF2CC"><?= $totalStockCB ?></td>
                <td><?= $totalPrecioCB ?></td>
                <td style="text-align:center;background-color: #DDEBF7"><?= $totalStockLP ?></td>
                <td><?= $totalPrecioLP ?></td>
                <td style="text-align:center;background-color: #E2EFDA"><?= $totalStockSC ?></td>
                <td><?= $totalPrecioSC ?></td>
                <td style="text-align:center;background-color: #e2d8e4"><?= $totalStockST ?></td>
                <td><?= $totalPrecioST ?></td>
                <td style="text-align:center;background-color: #FCE4D6"><?= $totalStockTJ ?></td>
                <td><?= $totalPrecioTJ ?></td>
                <td style="text-align:center;background-color: #FFA500"><?= $totalStockTotal ?></td>
                <td style="text-align:center;background-color: #FFF2CC"><?= $totalExtraidoCB ?></td>
                <td style="text-align:center;background-color: #FFF2CC"><?= $totalRecibidoCB ?></td>
                <td style="text-align:center;background-color: #DDEBF7"><?= $totalExtraidoLP ?></td>
                <td style="text-align:center;background-color: #DDEBF7"><?= $totalRecibidoLP ?></td>
                <td style="text-align:center;background-color: #E2EFDA"><?= $totalExtraidoSC ?></td>
                <td style="text-align:center;background-color: #E2EFDA"><?= $totalRecibidoSC ?></td>
                <td style="text-align:center;background-color: #e2d8e4"><?= $totalExtraidoST ?></td>
                <td style="text-align:center;background-color: #e2d8e4"><?= $totalRecibidoST ?></td>
                <td style="text-align:center;background-color: #FCE4D6"><?= $totalExtraidoTJ ?></td>
                <td style="text-align:center;background-color: #FCE4D6"><?= $totalRecibidoTJ ?></td>
            </tr>

        </tbody>
    </table>
<?php
}
?>
