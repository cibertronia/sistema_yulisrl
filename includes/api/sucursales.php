<?php
require_once '../App/Controllers/SucursalesController.php';
use App\Controllers\SucursalesController;

$response = new SucursalesController();
$response->index();

?>