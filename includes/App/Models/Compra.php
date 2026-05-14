<?php

namespace App\Models;
require_once __DIR__ . '../../../conexion.php';
class Compra 
{
    private $MySQLi;
    private $table = 'compras';

    public function __construct()
    {
        global $MySQLi;
        $this->MySQLi = $MySQLi;
    }

    public function create($data)
    {
        $query = "INSERT INTO " . $this->table . " (idproducto, cantidad, fecha, idsucursal, idusuario, detalles) 
        VALUES ('{$data['idproducto']}', '{$data['cantidad']}', '{$data['fecha']}', '{$data['idsucursal']}', '{$data['idusuario']}', '{$data['detalles']}')";
    
        if (mysqli_query($this->MySQLi, $query)) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function delete($idproducto, $fecha, $idsucursal, $idusuario)
    {
        $query = "DELETE FROM " . $this->table . " WHERE idproducto = '$idproducto' AND fecha = '$fecha' AND idsucursal = '$idsucursal' AND idusuario = '$idusuario'";
        
        if (mysqli_query($this->MySQLi, $query)) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

}