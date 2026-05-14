<?php
require_once '../App/Controllers/ProductsController.php';
use App\Controllers\ProductsController;

$response = new ProductsController();
// Validar que existe la sesion y que el usuario tiene permisos
session_start(); 
if (!isset($_SESSION['idUser'])) {
    http_response_code(403); // Forbidden
    echo json_encode(['error' => 'Access denied']);
    exit;
}

// To handle specific product requests, you can uncomment the following lines
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $response->show($id);
} else if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id']) && isset($_POST['_method']) && $_POST['_method'] === 'PUT') {
    $id = intval($_POST['id']);
    $response->update($id);
} else if (isset($_GET['action']) && $_GET['action'] === 'getWithMovimientos' && isset($_GET['fInicial']) && isset($_GET['fFinal'])) {
    $response->getWithMovimientos($_GET['fInicial'], $_GET['fFinal']);
} else if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $response->index();
} else {
    http_response_code(405); // Method Not Allowed
    echo json_encode(['error' => 'Method not allowed']);
}