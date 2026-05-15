<?php
namespace App\Controllers;

require_once __DIR__ . '/../Models/Product.php';
require_once __DIR__ . '/../Models/Sucursal.php';
require_once __DIR__ . '/../Models/HistoryProduct.php';
require_once __DIR__ . '/../Models/Compra.php';

use App\Models\Compra;
use App\Models\HistoryProduct;
use App\Models\Product;
use App\Models\Sucursal;

class ProductsController
{
    public function index()
    {
        $products = new Product(); 
        echo json_encode(['data' => $products->all()]);
    }

    public function store()
    {
        $respuesta = [
            'success' => false,
            'message' => 'Error al crear el producto',
        ];
        
        // Validando datos requeridos
        $data = [];
        if ($_POST['Producto'] && $_POST['Marca'] && $_POST['Modelo'] && $_POST['Descripcion']) {
            $data['Producto'] = $_POST['Producto'];
            $data['Marca'] = $_POST['Marca'];
            $data['Modelo'] = $_POST['Modelo'];
            $data['Descripcion'] = $_POST['Descripcion'];
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Missing required fields']);
            return;
        }

        if (isset($_FILES['imagen'])) {
            $image = $_FILES['imagen'];
            if ($this->validateImage($image)) {
                $ruta 		=	__DIR__ . '/../../../Productos/';
                $ruta 		= 	$ruta . basename($_FILES['imagen']['name'], $ruta);	
                // Move the uploaded file to the desired directory
                if(move_uploaded_file($_FILES['imagen']['tmp_name'], $ruta)){
                    $data['Imagen'] = basename($_FILES['imagen']['name']); // Store the image path in the data array
                } else {
                    http_response_code(500);
                    echo json_encode(['error' => 'Failed to upload image']);
                    return;
                }
            } else {
                http_response_code(400);
                echo json_encode(['error' => 'Invalid image']);
                return;
            }
        }

        $data['StockTotal'] = 0; // Initialize StockTotal
        // Validate stock for each branch
        $sucursalModel = new Sucursal();
        $sucursales = $sucursalModel->all();
        foreach ($sucursales as $sucursal) {
            $initials = $sucursal['iniciales'];
            if (isset($_POST['Stock' . $initials]) && $_POST['Stock' . $initials] !== '' && $this->validateStock($_POST['Stock' . $initials])) {
                $data['Stock' . $initials] = $_POST['Stock' . $initials];
                $data['StockTotal'] += $data['Stock' . $initials];
            }
            if (isset($_POST['Precio' . $initials]) && $_POST['Precio' . $initials] !== '') {
                $data['Precio' . $initials] = $_POST['Precio' . $initials];
            }
            $data['Observaciones' . $initials] = (isset($_POST['Observaciones' . $initials]) && $_POST['Observaciones' . $initials] !== '') ? $_POST['Observaciones' . $initials] :'Sin Observación';
        }

        $data['codeProductSin'] = $_POST['codeProductSin'] ?? '';

        $products = new Product();
        //iniciarmos trasaction
        $MySQLi = $products->MySQLi;
        
        $MySQLi->begin_transaction();
        try {    
            $newProduct = $products->create($data);

            $compraModel = new Compra();

            foreach ($sucursales as $sucursal) {
                $initials = $sucursal['iniciales'];
                // Check if the POST data for the current branch exists and is not empty
                if (isset($_POST['Stock' . $initials]) && $_POST['Stock' . $initials] !== '' && $_POST['Stock' . $initials] > 0) {
                    $data = [
                        'idproducto'    => $newProduct,
                        'fecha'         => date('Y-m-d H:i:s'),
                        'idsucursal'    => $sucursal['idSucursal'],
                        'idusuario'     => $_SESSION['idUser'],
                        'cantidad'      => $_POST['Stock' . $initials],
                        'detalles'      => 'Saldo inicial al crear producto',
                    ];

                    if (!$compraModel->create($data)) {
                        $MySQLi->rollback();
                        http_response_code(500);
                        echo json_encode(['error' => 'Error al crear la compra inicial']);
                        return;
                    }
                }
            }
            $MySQLi->commit();
            $respuesta['success'] = true;
            $respuesta['message'] = 'Producto creado correctamente.';
            $respuesta['data']['id'] = $newProduct; // Include the created product data

            
        } catch (\Throwable $th) {
            $MySQLi->rollback();
            http_response_code(500);
            $respuesta['error'] = 'Error al crear el producto: ' . $th->getMessage();
            echo json_encode($respuesta);
        }

        if ($newProduct) {
            http_response_code(201);
            //validar si la peticion es ajax
            if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
                echo json_encode($respuesta);
            } else {
                return $respuesta; // Return the response for non-AJAX requests
            }
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Failed to create product']);
        }
    }

    public function show($id)
    {
        $products = new Product();
        $product = $products->find($id);
        
        if ($product) {
            echo json_encode(['data' => $product]);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Product not found']);
        }
    }

    public function update($id)
    {
        $respuesta = [
            'success' => false,
            'message' => 'Error al actualizar el producto',
        ];
        // Validate the post data here if necessary
        $data = [];

        if ($_POST['Producto'] && $_POST['Marca'] && $_POST['Modelo'] && $_POST['Descripcion']) {
            $data['Producto'] = $_POST['Producto'];
            $data['Marca'] = $_POST['Marca'];
            $data['Modelo'] = $_POST['Modelo'];
            $data['Descripcion'] = $_POST['Descripcion'];
        } else {
            throw new \Exception('Missing required fields');
        }
        if (isset($_FILES['imagen'])) {
            $image = $_FILES['imagen'];
            if ($this->validateImage($image)) {
                $ruta 		=	__DIR__ . '/../../../Productos/';
                $ruta 		= 	$ruta . basename($_FILES['imagen']['name'], $ruta);	
                // Move the uploaded file to the desired directory
               if(move_uploaded_file($_FILES['imagen']['tmp_name'], $ruta)){
                    $data['Imagen'] = basename($_FILES['imagen']['name']); // Store the image path in the data array
                    if (isset($_POST['image_file']) && $_POST['image_file'] != '') {
                        // If an old image exists, delete it
                        if(!$this->deleteImage($_POST['image_file'])) {
                            $respuesta['message'] = "Eliminar la imagen anterior quedo pendiente. ";
                        }
                    }
                } else {
                    http_response_code(500);
                    echo json_encode(['error' => 'Failed to upload image']);
                    return;
               }
            } else {
                http_response_code(400);
                echo json_encode(['error' => 'Invalid image']);
            }
        }

        $sucursalModel = new Sucursal();
        $sucursales = $sucursalModel->all();
        foreach ($sucursales as $sucursal) {
            $initials = $sucursal['iniciales'];
            // Check if the POST data for the current branch exists and is not empty
            if (isset($_POST['Stock' . $initials]) && $_POST['Stock' . $initials] !== '' && $this->validateStock($_POST['Stock' . $initials])) {
                $data['Stock' . $initials] = $_POST['Stock' . $initials];
            }
            if (isset($_POST['Precio' . $initials]) && $_POST['Precio' . $initials] !== '') {
                $data['Precio' . $initials] = $_POST['Precio' . $initials];
            }
            if (isset($_POST['Observaciones' . $initials]) && $_POST['Observaciones' . $initials] !== '') {
                $data['Observaciones' . $initials] = $_POST['Observaciones' . $initials];
            }
        }

        $products = new Product();
        if($products->update($id, $data)){
            $respuesta['success'] = true;
            $respuesta['message'] = 'Producto actualizado correctamente.';
            if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
                echo json_encode($respuesta);
            } else {
                return true; // Return the response for non-AJAX requests
            }
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Failed to update product']);
        }
        
    }

    public function sale($id, $sucursal, $count, $vendedor, $descripcion)
    {
        $this->validateStock($count);

        $products = new Product();
        $product = $products->find($id);

        if (!$product) {
            throw new \Exception('Producto no encontrado');
        }

        // Check stock for the specific branch
        $sucursalModel = new Sucursal();
        $getSucursal = $sucursalModel->where('Sucursal', $sucursal)[0];
        $stockKey = 'Stock' . $getSucursal['iniciales'];
        if (!isset($product[$stockKey]) || $product[$stockKey] < $count) {
            throw new \Exception('Insuficiente stock de ' . $product['Producto']);
        }

        $stock_nuevo = $product[$stockKey] - $count;
        if(!$products->update($id, [$stockKey => $stock_nuevo])){
            throw new \Exception('Error al actualizar el stock del producto');
        }
        if(!$products->updateStockTotal($id)){
            throw new \Exception('Error al actualizar el stock total del producto');
        }

        //insertar historal de producto
        $historialData = [
            'Producto'      => $product['Producto'],
            'inicial'       => $product[$stockKey],
            'final'         => $stock_nuevo,
            'vendedor'      => $vendedor,
            'dateEmission'  => date('c'),
            'descripcion'   => $descripcion,
            'idProducto'    => $id,
            'sucursal'      => $sucursal,
        ];

        $sucursales = $sucursalModel->all();
        foreach ($sucursales as $item) {
            $initials = strtolower($item['iniciales']);
            $historialData[$initials] = $item['Sucursal'] == $sucursal ? $count * -1 : 0;
        }

        $historial = new HistoryProduct();
        if (!$historial->insert($historialData)) {
            throw new \Exception('Error al insertar el historial de stock del producto');
        }

        return true;
    }

    public function getWithMovimientos(string $fechaInicio, string $fechaFin)
    {
        $products = new Product();
        $movimientos = $products->getWithMovimientos($fechaInicio, $fechaFin);
        
        if ($movimientos) {
            echo json_encode(['data' => $movimientos]);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'No movements found']);
        }
    }

    private function validateImage($image)
    {
        // validar formatos de imagen y tamaño max 10mb
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        $maxSize = 10 * 1024 * 1024; // 10 MB 

        if (!in_array($image['type'], $allowedTypes)) {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid image type']);
            return false;
        }
        if ($image['size'] > $maxSize) {
            http_response_code(400);
            echo json_encode(['error' => 'Image size exceeds 10 MB']);
            return false;
        }
        // Check file type, size, etc.
        return true; // Placeholder for actual validation
    }

    private function deleteImage($image)
    {
        $imagePath = __DIR__ . '/../../../Productos/' . basename($image);
        // Delete the image file from the server
        if (file_exists($imagePath)) {
            return unlink($imagePath);
        }
    }

    private function validateStock($stock)
    {
        // Validate stock input
        if (!is_numeric($stock) || $stock < 0) {
            throw new \Exception('Error en cantidad solicitada: '. $stock);
        }
        return true;
    }
}
?>