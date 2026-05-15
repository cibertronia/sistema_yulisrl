<?php
require_once '../App/Controllers/HistoryProductController.php';
use App\Controllers\HistoryProductController;

// Validar que existe la sesion y que el usuario tiene permisos
session_start(); 
if (!isset($_SESSION['idUser'])) {
    http_response_code(403); // Forbidden
    echo json_encode(['error' => 'Access denied']);
    exit;
}

$response = new HistoryProductController();

if (isset($_GET['idProducto'])) {
    $idProducto = intval($_GET['idProducto']);
    echo $response->show($idProducto);
} else {
    http_response_code(400); // Bad Request
    echo json_encode(['error' => 'Metodo no permitido']);
}