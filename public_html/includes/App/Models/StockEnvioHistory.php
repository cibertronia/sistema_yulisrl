<?php
namespace App\Models;
require_once __DIR__ . '../../../conexion.php';

class StockEnvioHistory
{
    private $MySQLi;
    private $table = 'historial_stock_envios';
    private $query;

    public function __construct()
    {
        global $MySQLi;
        $this->MySQLi = $MySQLi;
    }

    public function insert(array $data): bool
    {
        $columns = implode(", ", array_keys($data));
        $values = implode("', '", array_map(function ($value) {
            return mysqli_real_escape_string($this->MySQLi, $value);
        }, array_values($data)));
        $this->query = "INSERT INTO " . $this->table . " ($columns) VALUES ('$values')";
        if (mysqli_query($this->MySQLi, $this->query)) {
            return true;
        } else {
            return false;
        }
    }

    public function findByEnvioId(int $idEnvio): array
    {
        $this->query = "SELECT * FROM " . $this->table . " WHERE idEnvio = $idEnvio ORDER BY id DESC";
        $result = mysqli_query($this->MySQLi, $this->query);
        if (!$result) {
            return [];
        }
        $data = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $data[] = $row;
        }
        return $data;
    }
}
?>
