<?php
require_once '../App/Controllers/StockEnvioHistoryController.php';
use App\Controllers\StockEnvioHistoryController;

session_start();
if (!isset($_SESSION['idUser'])) {
    http_response_code(403);
    echo json_encode(['error' => 'Access denied']);
    exit;
}

$response = new StockEnvioHistoryController();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $response->store();
} else if (isset($_GET['idEnvio'])) {
    $idEnvio = intval($_GET['idEnvio']);
    echo $response->show($idEnvio);
} else {
    http_response_code(400);
    echo json_encode(['error' => 'Método no permitido']);
}
?>
