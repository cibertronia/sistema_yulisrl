<?php
require_once __DIR__ . '/../App/Models/Product.php';
require_once __DIR__ . '/../App/Models/Sucursal.php';
require_once __DIR__ . '/../App/Models/StockEnvioHistory.php';

use App\Models\StockEnvioHistory;
use App\Models\Product;
use App\Models\Sucursal;

session_start();
//error_reporting(0);
include './../conexion.php';
include './../date.class.php';

$Acciones = filter_var($_POST['action'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
switch ($Acciones) {
    case 'agregar_repuesto_cola':

        $clave = $_POST['clave'];
        $idProducto = $_POST['id_producto'];
        $cantidad = $_POST['cantidad'];

        $insertDatos = mysqli_query(
            $MySQLi,
            "INSERT INTO `envio_claves` (`clave`, `idProducto`, `cantidad`)
             VALUES ('$clave','$idProducto','$cantidad')"
        ) or die(mysqli_error($MySQLi) . "<br>Error en la línea: " . __LINE__);

        listar_envio_temporal($MySQLi, $clave);

        break;
    case 'remover_repuesto_temporal':
        $clave = $_POST['clave'];
        $idClave  = $_POST['id_clave'];

        $delete_repuesto = mysqli_query(
            $MySQLi,
            "DELETE FROM envio_claves
            WHERE idClave  = '$idClave '"
        );

        listar_envio_temporal($MySQLi, $clave);

        break;
    case 'guardar_envio_stock':
        if (isset($_SESSION['idUser'])) {
            $clave = $_POST['clave'];
            $idUser = $_SESSION['idUser'];

            $modelSucursal = new Sucursal();
            $origen = $modelSucursal->find($_POST['id_origen']);
            $destino = $modelSucursal->find($_POST['id_destino']);

            $desde = $origen['Sucursal'];
            $hasta = $destino['Sucursal'];
            $inventario = 'Stock' . $origen['iniciales']; // StockCB, StockLP, StockSC, StockTJ, StockST

            $encargado_envio = $_POST['tecnico'];
            $observaciones = $_POST['observaciones'];

            // Iniciar transacción global
            $MySQLi->begin_transaction();
            try {
                //GUARDAR ENVIO
                $q_guardar_envio = mysqli_query(
                    $MySQLi,
                    "INSERT INTO `envio_stock`(`clave`, `idUser`, `desde`, `hasta`,`encargado_envio`,`observaciones`, `fecha`, `hora`, `estado`) 
                    VALUES ('$clave','$idUser','$desde','$hasta','$encargado_envio','$observaciones','$fecha','$Hora','0')"
                );
                if (!$q_guardar_envio) {
                    throw new Exception('Error al guardar el envío');
                }

                //ACTUALIZAR INVENTARIO
                $q_claves = mysqli_query($MySQLi, "SELECT * FROM envio_claves WHERE clave='$clave'");
                while ($d_claves = mysqli_fetch_assoc($q_claves)) {
                    $idProducto = $d_claves['idProducto'];
                    $q_inventario = mysqli_query($MySQLi, "SELECT * FROM Productos WHERE idProducto='$idProducto'");
                    $d_inventario = mysqli_fetch_assoc($q_inventario);

                    $stock_actual = $d_inventario[$inventario]; //cantidad en inventario actual
                    $cantidad = $d_claves['cantidad']; //cantidad extraer o enviar
                    $stock_nuevo = $stock_actual - $cantidad; //nuevo stock

                    // Validar que el stock no sea negativo
                    if ($stock_nuevo < 0) {
                        throw new Exception('No hay suficiente stock para el producto: ' . $d_inventario['Producto'] . ' (' . $idProducto . ') en ' . $desde . '. Stock actual: ' . $stock_actual . ', solicitado: ' . $cantidad);
                    }

                    //actualizamos
                    $productoModel = new Product();
                    if(!$productoModel->update($idProducto, [$inventario => $stock_nuevo])){
                        throw new Exception('Error al actualizar el stock del producto');
                    }
                    if(!$productoModel->updateStockTotal($idProducto)){
                        throw new Exception('Error al actualizar el stock total del producto');
                    }

                    // Guardar en historial
                    $historyModel = new StockEnvioHistory();
                    $historialData = [
                        'idProducto'            => $idProducto,
                        'producto'              => $d_inventario['Producto'],
                        'inicial'               => $stock_actual,
                        $origen['iniciales']    => $cantidad * -1, // Restamos porque es un envío
                        'final'                 => $stock_nuevo,
                        'vendedor'              => $encargado_envio,
                        'dateEmission'          => date('Y-m-d H:i:s'),
                        'descripcion'           => 'DESCUENTO STOCK EN ' . $desde . ' POR ENVIO A: ' . $hasta,
                        'sucursal'              => $desde,
                    ];
                    if (!$historyModel->insert($historialData)) {
                        throw new Exception('Error al guardar el historial de stock');
                    }
                }

                // Si todo fue bien, commit
                $MySQLi->commit();
                echo json_encode('ok');
            } catch (Exception $e) {
                // Si algo falla, rollback y notificar
                $MySQLi->rollback();
                echo json_encode(['error' => $e->getMessage()]);
            }
        }
        break;
    case 'cancelarProcesoStock':
        if (isset($_SESSION['idUser'])) {
            $idEnvio = $_POST['idEnvio'];
            $sqlEnvioStock = mysqli_query($MySQLi, "SELECT * FROM envio_stock WHERE idEnvio='$idEnvio' AND estado='0' ");
            $cantidad_registros = mysqli_num_rows($sqlEnvioStock);
            if ($cantidad_registros > 0) {
                $dataEnvio = mysqli_fetch_assoc($sqlEnvioStock);
                $clave = $dataEnvio['clave'];
                $vendedor = $dataEnvio["encargado_envio"];
                
                $modelSucursal = new Sucursal();
                $desdeArr = $modelSucursal->where('Sucursal', $dataEnvio['desde']);
                $desde = $desdeArr[0]['iniciales']; // Obtenemos las iniciales de la sucursal origen
                $inventario = 'Stock' . $desde;

                // Iniciar transacción global
                $MySQLi->begin_transaction();
                try {
                    $sqlClaves = mysqli_query($MySQLi, "SELECT * FROM envio_claves WHERE clave='$clave'");
                    while ($dataClave = mysqli_fetch_assoc($sqlClaves)) {
                        $idProducto = $dataClave['idProducto'];
                        $sqlProducto = mysqli_query($MySQLi, "SELECT * FROM Productos WHERE idProducto='$idProducto'");
                        $data_Producto = mysqli_fetch_assoc($sqlProducto);

                        $stockActual = $data_Producto[$inventario]; //cantidad en inventario actual
                        $cantidad = $dataClave['cantidad']; //cantidad devolver
                        $stock_nuevo = $stockActual + $cantidad;

                        //actualizar
                        $productoModel = new Product();
                        if(!$productoModel->update($idProducto, [$inventario => $stock_nuevo])){
                            throw new \Exception('Error al actualizar el stock del producto');
                        }
                        if(!$productoModel->updateStockTotal($idProducto)){
                            throw new \Exception('Error al actualizar el stock total del producto');
                        }

                        // Guardar en historial
                        $historyModel = new StockEnvioHistory();
                        $historialData = [
                            'idProducto'            => $idProducto,
                            'producto'              => $data_Producto['Producto'],
                            'inicial'               => $stockActual,
                            $desde                  => $cantidad, // Se suma porque es devolución
                            'final'                 => $stock_nuevo,
                            'vendedor'              => $vendedor,
                            'dateEmission'          => date('Y-m-d H:i:s'),
                            'descripcion'           => 'DEVOLUCIÓN DE STOCK EN ' . $dataEnvio['desde'] . ' POR CANCELACIÓN DE ENVÍO',
                            'sucursal'              => $dataEnvio['desde'],
                        ];
                        if (!$historyModel->insert($historialData)) {
                            throw new \Exception('Error al guardar el historial de stock');
                        }
                    }
                    $q_update_envio = mysqli_query($MySQLi, "UPDATE envio_stock SET estado=2 WHERE idEnvio='$idEnvio' ");

                    if ($q_update_envio) {
                        $MySQLi->commit();
                        echo json_encode('ok');
                    } else {
                        throw new \Exception('Error al actualizar el estado del envío');
                    }
                } catch (\Exception $e) {
                    $MySQLi->rollback();
                    echo json_encode(['error' => $e->getMessage()]);
                }
            } else {
                echo json_encode('error');
            }
        }
        break;
    case 'confirmarEnvioStock': //trabajar aki
        if (isset($_SESSION['idUser'])) {
            $idEnvio = $_POST['idEnvio'];
            $q_envios = mysqli_query($MySQLi, "SELECT * FROM envio_stock WHERE idEnvio='$idEnvio' AND estado='0'");
            $cantidad_registros = mysqli_num_rows($q_envios);
            if ($cantidad_registros > 0) {
                $d_envios = mysqli_fetch_assoc($q_envios);

                $clave = $d_envios['clave'];

                $modelSucursal = new Sucursal();
                $hastaArr = $modelSucursal->where('Sucursal', $d_envios['hasta']);
                $hasta = $hastaArr[0]['iniciales']; // Obtenemos las iniciales de la sucursal destino
                $inventario = 'Stock' . $hasta;

                // Iniciar transacción global
                $MySQLi->begin_transaction();
                try {
                    $q_claves = mysqli_query($MySQLi, "SELECT * FROM envio_claves WHERE clave='$clave'");
                    while ($d_claves = mysqli_fetch_assoc($q_claves)) {
                        $idProducto = $d_claves['idProducto'];
                        $sqlProducto = mysqli_query($MySQLi, "SELECT * FROM Productos WHERE idProducto='$idProducto'");
                        $data_Producto = mysqli_fetch_assoc($sqlProducto);

                        $stockActual = $data_Producto[$inventario]; //cantidad en inventario actual
                        $cantidad = $d_claves['cantidad']; //cantidad adicionar por recepcion
                        $stock_nuevo = $stockActual + $cantidad; //nuevo stock

                        $productoModel = new Product();
                        if(!$productoModel->update($idProducto, [$inventario => $stock_nuevo])){
                            throw new \Exception('Error al actualizar el stock del producto');
                        }

                        if(!$productoModel->updateStockTotal($idProducto)){
                            throw new \Exception('Error al actualizar el stock total del producto');
                        }

                        // Guardar en historial
                        $historyModel = new StockEnvioHistory();
                        $historialData = [
                            'idProducto'            => $idProducto,
                            'producto'              => $data_Producto['Producto'],
                            'inicial'               => $stockActual,
                            $hasta                  => $cantidad, // Se suma porque es recepción
                            'final'                 => $stock_nuevo,
                            'vendedor'              => $d_envios['encargado_envio'],
                            'dateEmission'          => date('Y-m-d H:i:s'),
                            'descripcion'           => 'RECEPCIÓN DE STOCK EN ' . $d_envios['hasta'] . ' POR ENVÍO DESDE: ' . $d_envios['desde'],
                            'sucursal'              => $d_envios['hasta'],
                        ];
                        if (!$historyModel->insert($historialData)) {
                            throw new \Exception('Error al guardar el historial de stock');
                        }
                    }

                    date_default_timezone_set('America/La_Paz');
                    $fecha_recibido = date('c');

                    $q_guardar_envio = mysqli_query($MySQLi, "UPDATE envio_stock SET estado='1', fecha_recibido='$fecha_recibido' WHERE idEnvio='$idEnvio'");
                    if ($q_guardar_envio) {
                        $MySQLi->commit();
                        echo json_encode(['success' => 'ok']);
                    } else {
                        throw new \Exception('Error al actualizar el estado del envío');
                    }
                } catch (\Exception $e) {
                    $MySQLi->rollback();
                    echo json_encode(['error' => $e->getMessage()]);
                }
            } else {
                echo json_encode(['error' => 'No hay envíos pendientes con esta clave.']);
            }
        }
    break;

    case 'agregar_elemento_extra_cola':

        $clave = $_POST['clave'];
        $nombre = $_POST['nombre'];
        $cantidad = $_POST['cantidad'];
        $precio = $_POST['precio'];
        $marca = $_POST['marca'];
        $modelo = $_POST['modelo'];

        $insertDatos = mysqli_query(
            $MySQLi,
            "INSERT INTO `envio_extras`(`clave`, `nombre`, `cantidad`,`precio`,`marca`,`modelo`)
                 VALUES ('$clave','$nombre','$cantidad','$precio','$marca','$modelo')"
        ) or die(mysqli_error($MySQLi) . "<br>Error en la línea: " . __LINE__);

        listar_elementos_extras_temporales($MySQLi, $clave);

        break;
    case 'remover_elemento_extra_temporal':
        $clave = $_POST['clave'];
        $id = $_POST['id'];

        $delete_repuesto = mysqli_query(
            $MySQLi,
            "DELETE FROM envio_extras
                WHERE id = '$id'"
        );
        listar_elementos_extras_temporales($MySQLi, $clave);
        break;

    default:
        //alert_peticionDesconocida();
        break;
}
function listar_envio_temporal($MySQLi, $clave)
{
?>
    <h3>Lista Productos-Sistema para ser enviados:</h3>
    <table id="tabla_temporales" class="table table-striped table-bordered table-td-valign-middle w-100">
        <thead>
            <tr>
                <th class="text-center btn-primary">N&ordm;</th>
                <th class="text-center btn-primary">Nombre Producto</th>
                <th class="text-center btn-primary">Cantidad a Enviar</th>
                <th class="text-center btn-primary">Eliminar de la lista</th>
            </tr>
        </thead>
        <tbody><?php
                $num = 1;
                $tot = 0;
                $Q_Service = mysqli_query($MySQLi, "SELECT * FROM envio_claves WHERE clave='$clave'");
                $cantidad_temporales = mysqli_num_rows($Q_Service);
                while ($dataRegistros = mysqli_fetch_assoc($Q_Service)) { ?>
                <tr>
                    <td class="text-center"><?php echo $num;
                                            $num++; ?></td>
                    <td>
                        <?php
                        $idProducto = $dataRegistros['idProducto'];
                        $queryRepuestos = mysqli_query($MySQLi, "SELECT * FROM Productos WHERE idProducto='$idProducto'");
                        $dataProductos = mysqli_fetch_assoc($queryRepuestos);
                        echo $dataProductos['Producto'] . " " .
                            $dataProductos['Marca'] . " " .
                            $dataProductos['Modelo'];

                        ?>
                    </td>
                    <td class="text-center"><?= $dataRegistros['cantidad'] ?></td>
                    <?php $tot += $dataRegistros['cantidad']; ?>

                    <td class="text-center">
                        <button id="<?= $dataRegistros['idClave'] ?>" class="btn btn-danger btn-xs btn-icon rounded-circle waves-effect waves-themed remover_repuesto_temporal" data-template="<div class=&quot;tooltip&quot; role=&quot;tooltip&quot;><div class=&quot;tooltip-inner bg-danger-500&quot;></div></div>" data-toggle="tooltip" title="Remover Repuesto De La Lista <?= $dataRegistros['idClave'] ?>" data-original-title="">
                            <i class="ni ni-ban"></i>
                        </button>
                    </td>
                </tr>
            <?php }


            ?>
        </tbody>
        <tfoot><tr><td></td><td class="text-center btn-default">TOTAL</td><td class="text-center btn-default"><?php echo ($tot);?></td><td></td></tr></tfoot>
    </table>
    <input type="hidden" id="cantidad_temporales" name="cantidad_temporales" value="<?php echo $cantidad_temporales; ?>">
<?php
}

function listar_elementos_extras_temporales($MySQLi, $clave)
{
?>
    <h3>Lista Elementos Adicionales para ser enviados:</h3>
    <table id="tabla_temporales" class="table table-striped table-bordered table-td-valign-middle w-100">
        <thead>
            <tr>
                <th class="text-center btn-secondary">N&ordm;</th>
                <th class="text-center btn-secondary">Nombre Elemento Adicional</th>
                <th class="text-center btn-secondary">Cantidad</th>
                <th class="text-center btn-secondary">Precio Unidad<span class="text-warning"> (Opcional)</span></th>
                <th class="text-center btn-secondary">Eliminar de la lista</th>
            </tr>
        </thead>
        <tbody><?php
                $num = 1;
                $Q_Service = mysqli_query($MySQLi, "SELECT * FROM envio_extras WHERE clave='$clave'");
                $cantidad_extras = mysqli_num_rows($Q_Service);
                while ($dataRegistros = mysqli_fetch_assoc($Q_Service)) { ?>
                <tr>
                    <td class="text-center"><?php echo $num;
                                            $num++; ?></td>
                    <td>
                        <?php
                        echo  $dataRegistros['nombre'] . ' ' . $dataRegistros['marca'] . ' ' . $dataRegistros['modelo'];
                        ?>
                    </td>
                    <td class="text-center"><?= $dataRegistros['cantidad'] ?></td>
                    <td class="text-center"><?= $dataRegistros['precio'] ?></td>

                    <td class="text-center">
                        <button id="<?= $dataRegistros['id'] ?>" class="btn btn-danger btn-xs btn-icon rounded-circle waves-effect waves-themed remover_elemento_extra_temporal" data-template="<div class=&quot;tooltip&quot; role=&quot;tooltip&quot;><div class=&quot;tooltip-inner bg-danger-500&quot;></div></div>" data-toggle="tooltip" title="Remover Repuesto De La Lista <?= $dataRegistros['id'] ?>" data-original-title="">
                            <i class="ni ni-ban"></i>
                        </button>
                    </td>
                </tr>
            <?php }


            ?>
        </tbody>
    </table>
    <input type="hidden" id="cantidad_extras" name="cantidad_extras" value="<?php echo $cantidad_extras; ?>">
<?php
}
