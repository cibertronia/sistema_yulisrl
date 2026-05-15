<?php
require_once '../App/Controllers/ComprasController.php';
use App\Controllers\ComprasController;

$response = new ComprasController();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $response->store();
} else {
    http_response_code(405); // Method Not Allowed
    echo json_encode(['error' => 'Método no permitido']);
}

?>