<?php

namespace App\Models;
require_once __DIR__ . '/../../conexion.php';

class Sucursal 
{
    private $MySQLi;
    private $table = 'Sucursales';
    private $query;

    public function __construct()
    {
        global $MySQLi;
        if (!$MySQLi) {
            include __DIR__ . '/../../conexion.php';
        }
        $this->MySQLi = $MySQLi;
    }

    public function all()
    {
        $query = "SELECT * FROM " . $this->table . " ORDER BY orden ASC";
        $response = mysqli_query($this->MySQLi, $query);

        // enviar todas las sucursales
        $sucursales = [];
        while ($row = mysqli_fetch_assoc($response)) {
            $sucursales[] = $row;
        }
        
        return $sucursales;
    }

    public function find(int $id)
    {
        $this->query = "SELECT * FROM " . $this->table . " WHERE idSucursal = '$id'";
        $response = mysqli_query($this->MySQLi, $this->query);

        if ($row = mysqli_fetch_assoc($response)) {
            return $row;
        } else {
            return null; // or throw an exception
        }    
    }

    public function where(string $column, string $value): array
    {
        $this->query = "SELECT * FROM " . $this->table . " WHERE $column = '$value'";
        $response = mysqli_query($this->MySQLi, $this->query);

        // enviar todas las sucursales
        $sucursales = [];
        while ($row = mysqli_fetch_assoc($response)) {
            $sucursales[] = $row;
        }
        
        return $sucursales;
    }
}
?>