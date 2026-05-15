<?php
namespace App\Controllers;

require_once __DIR__ . '/../Models/Compra.php';
require_once __DIR__ . '/../Models/Product.php';
require_once __DIR__ . '/../Models/Sucursal.php';

use App\Models\Compra;
use App\Models\Product;
use App\Models\Sucursal;

class ComprasController
{
    public function store()
    {
        if(!$this->validateData()) {
            return; // Validation failed, error response already sent
        }

        $data = [
            'idproducto' => intval($_POST['idproducto']),
            'cantidad' => intval($_POST['cantidad']),
            'fecha' => $_POST['fecha'],
            'idsucursal' => intval($_POST['idsucursal']),
            'idusuario' => intval($_POST['idusuario']),
            'detalles' => isset($_POST['detalles'])
        ];

        $compras = new Compra();
        $productModel = new Product();
        $sucursalModel = new Sucursal();

        // Iniciar transacción
        $mysqli = $productModel->MySQLi; // Asegúrate de que Products expone la conexión
        $mysqli->begin_transaction();

        try {
            if (!$compras->create($data)) {
                throw new \Exception('Error al registrar la compra');
            }

            $product = $productModel->find($data['idproducto']);
            if (!$product) {
                throw new \Exception('Producto no encontrado');
            }

            $sucursal = $sucursalModel->find($data['idsucursal']);
            if (!$sucursal) {
                throw new \Exception('Sucursal no encontrada');
            }

            $updateProduct['Stock'.$sucursal['iniciales']] = $product['Stock'.$sucursal['iniciales']] + $data['cantidad'];

            if (!$productModel->update($data['idproducto'], $updateProduct)) {
                throw new \Exception('Error al actualizar el stock del producto');
            }

            if (!$productModel->updateStockTotal($data['idproducto'])) {
                throw new \Exception('Error al actualizar el stock total del producto');
            }

            $mysqli->commit();
            http_response_code(201);
            echo json_encode(['success' => true, 'message' => 'Compra registrada correctamente']);
        } catch (\Exception $e) {
            $mysqli->rollback();
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    private function validateData()
    {
        if (!isset($_POST['idproducto']) || !is_numeric($_POST['idproducto'])) {
            http_response_code(400);
            echo json_encode(['error' => 'El ID del producto es requerido y debe ser un número']);
            return;
        }

        if (!isset($_POST['cantidad']) || !is_numeric($_POST['cantidad'])) {
            http_response_code(400);
            echo json_encode(['error' => 'La cantidad es requerida y debe ser un número']);
            return;
        }

        if (!isset($_POST['fecha']) || empty($_POST['fecha'])) {
            http_response_code(400);
            echo json_encode(['error' => 'La fecha es requerida']);
            return;
        }

        if (!isset($_POST['idsucursal']) || !is_numeric($_POST['idsucursal'])) {
            http_response_code(400);
            echo json_encode(['error' => 'El ID de la sucursal es requerido y debe ser un número']);
            return;
        }

        if (!isset($_POST['idusuario']) || !is_numeric($_POST['idusuario'])) {
            http_response_code(400);
            echo json_encode(['error' => 'El ID del usuario es requerido y debe ser un número']);
            return;
        }

        if(!isset($_POST['detalles']) || !is_string($_POST['detalles']) || empty($_POST['detalles'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Los detalles de la compra son requeridos']);
            return;
        }

        return true;
    }

}
?>