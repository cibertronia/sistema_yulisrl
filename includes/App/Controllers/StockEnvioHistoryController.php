<?php
namespace App\Controllers;

require_once __DIR__ . '/../Models/StockEnvioHistory.php';
require_once __DIR__ . '/../Models/Sucursal.php';

use App\Models\StockEnvioHistory;
use App\Models\Sucursal;

class StockEnvioHistoryController
{
    public function show(Int $idEnvio)
    {
        $history = new StockEnvioHistory();
        $data = $history->findByEnvioId($idEnvio);
        if (empty($data)) {
            http_response_code(404);
            return json_encode(['status' => 'error', 'error' => 'No hay historial para este producto.']);
        }
        return json_encode(['status' => 'success', 'data' => $data]);
    }

    public function store()
    {
        $input = $_POST;
        if (!isset($input['idProducto']) || !isset($input['descripcion']) || !isset($input['sucursal'])) {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'error' => 'Faltan datos requeridos.']);
            return;
        }
        $history = new StockEnvioHistory();
        $data = [
            'producto'      => isset($input['producto']) ? $input['producto'] : '',
            'inicial'       => isset($input['inicial']) ? $input['inicial'] : 0,
            'final'         => isset($input['final']) ? $input['final'] : 0,
            'vendedor'      => isset($input['vendedor']) ? $input['vendedor'] : '',
            'dateEmission'  => date('Y-m-d H:i:s'),
            'descripcion'   => $input['descripcion'],
            'idProducto'    => intval($input['idProducto']),
            'sucursal'      => $input['sucursal'],
        ];

        // Agregamos las columnas de stock por sucursal
        $sucursal = new Sucursal();
        $sucursales = $sucursal->all();
        foreach ($sucursales as $s) {
            $iniciales = strtolower($s['iniciales']);
            $data[$iniciales] = isset($input[$iniciales]) ? intval($input[$iniciales]) : 0;
        }

        if ($history->insert($data)) {
            http_response_code(201);
            echo json_encode(['status' => 'success', 'message' => 'Historial guardado.']);
        } else {
            http_response_code(500);
            echo json_encode(['status' => 'error', 'error' => 'No se pudo guardar el historial.']);
        }
    }
}
?>
