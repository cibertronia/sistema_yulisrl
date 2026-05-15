<?php
namespace App\Models;
require_once __DIR__ . '../../../conexion.php';

class HistoryProduct
{
    private $MySQLi;
    private $table = 'historial_stock_productos';
    private $query;

    public function __construct()
    {
        global $MySQLi;
        $this->MySQLi = $MySQLi;
    }

    public function insert(array $data): bool
    {
        $columns = implode(", ", array_keys($data));
        $values = implode("', '", array_values($data));
        
        $this->query = "INSERT INTO " . $this->table . " ($columns) VALUES ('$values')";
        
        if (mysqli_query($this->MySQLi, $this->query)) {
            return true;
        } else {
            return false; // or handle the error as needed
        }
    }

    public function find(int $idProducto): array 
    {
        $this->query = "SELECT * FROM " . $this->table . " WHERE idProducto = $idProducto ORDER BY id DESC";
        
        $result = mysqli_query($this->MySQLi, $this->query);

        // var_dump($this->query);
        if (!$result) {
            return []; // or handle the error as needed
        }
        
        $data = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $data[] = $row;
        }
        
        return $data;
        
    }
}

?>