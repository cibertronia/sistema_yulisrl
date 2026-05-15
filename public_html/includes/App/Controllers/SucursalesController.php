<?php
namespace App\Controllers;
require_once __DIR__ . '/../Models/Sucursal.php';
use App\Models\Sucursal;

class SucursalesController
{
    public function index()
    {
        $sucursales = new Sucursal(); 
        echo json_encode(['data' => $sucursales->all()]);
    }
}
?>