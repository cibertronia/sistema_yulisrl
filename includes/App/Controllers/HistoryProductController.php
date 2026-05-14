<?php
namespace App\Controllers;

require_once __DIR__ . '/../Models/HistoryProduct.php';

use App\Models\HistoryProduct;

class HistoryProductController
{

    public function show(Int $idProduct)
    {
        $historyProduct = new HistoryProduct();
        $data = $historyProduct->find($idProduct);
        
        if (empty($data)) {
            http_response_code(404); // Not Found
            return json_encode(['status' => 'error', 'error' => 'El producto no tiene historial.']);
        }
        
        return json_encode(['status' => 'success', 'data' => $data]);
    }

}
?>