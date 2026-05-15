<?php

namespace App\Models;

require_once __DIR__ . '../../../conexion.php';

class Product
{
    public $MySQLi;
    private $table = 'Productos';
    private $query;

    public function __construct()
    {
        global $MySQLi;
        $this->MySQLi = $MySQLi;
    }

    public function create(array $data)
    {
        $columns = implode(", ", array_keys($data));
        $values = implode("', '", array_map(function ($value) {
            return mysqli_real_escape_string($this->MySQLi, $value);
        }, array_values($data)));

        $this->query = "INSERT INTO " . $this->table . " ($columns) VALUES ('$values')";

        if (mysqli_query($this->MySQLi, $this->query)) {
            return mysqli_insert_id($this->MySQLi);
        } else {
            return false;
        }
    }

    public function all()
    {
        $this->query = "SELECT * FROM " . $this->table;

        if (isset($_GET['stock'])) {
            $this->query .= " WHERE StockTotal >= " . intval($_GET['stock']);
        }

        if (isset($_GET['order'])) {
            $this->query .= " ORDER BY " . mysqli_real_escape_string($this->MySQLi, $_GET['order']);
        }


        $response = mysqli_query($this->MySQLi, $this->query);

        // enviar todos los productos
        $products = [];
        while ($row = mysqli_fetch_assoc($response)) {
            $products[] = $row;
        }

        return $products;
    }

    public function find(int $id)
    {
        $this->query = "SELECT * FROM " . $this->table . " WHERE idProducto = '$id'";
        $response = mysqli_query($this->MySQLi, $this->query);

        if ($row = mysqli_fetch_assoc($response)) {
            return $row;
        } else {
            return null; // or throw an exception
        }
    }

    public function update(int $id, array $data)
    {
        $query = "UPDATE " . $this->table . " SET ";
        foreach ($data as $key => $value) {
            $query .= "$key = '" . mysqli_real_escape_string($this->MySQLi, $value) . "', ";
        }
        $query = rtrim($query, ', ') . " WHERE idProducto = '$id'";

        if (mysqli_query($this->MySQLi, $query)) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function updateStockTotal(int $id)
    {
        $sucursalModel = new Sucursal();
        $sucursales = $sucursalModel->all();

        $query = "SELECT ";
        foreach ($sucursales as $sucursal) {
            $query .= "IFNULL(Stock" . $sucursal['iniciales'] . ", 0) AS Stock" . $sucursal['iniciales'] . ", ";
        }
        $query = rtrim($query, ', ') . " FROM " . $this->table . " WHERE idProducto = '$id'";

        $result = mysqli_query($this->MySQLi, $query);

        if ($row = mysqli_fetch_assoc($result)) {
            $stockTotal = 0;
            foreach ($sucursales as $sucursal) {
                $stockTotal += isset($row['Stock' . $sucursal['iniciales']]) ? $row['Stock' . $sucursal['iniciales']] : 0;
            }
            $update = "UPDATE " . $this->table . " SET StockTotal = '$stockTotal' WHERE idProducto = '$id'";
            mysqli_query($this->MySQLi, $update);
            // validar que la actualización fue exitosa
            if (mysqli_affected_rows($this->MySQLi) > 0) {
                return true;
            }
        }
        return false;
    }

    public function getWithMovimientos(string $fInicial, string $fFinal)
    {
        // Validar fechas
        if (strtotime($fInicial) === false || strtotime($fFinal) === false) {
            return false; // o lanzar una excepción
        }

        //obtener consulta de Migrations\Stock.sql
        $this->query = "       
            SELECT
                products.idProducto,
                products.Producto,
                products.Marca,
                products.Modelo,
                products.Imagen,

                products.StockCB,
                products.StockLP,
                products.StockSC,
                products.StockTJ,
                products.StockST,

                products.StockTotal,

                products.PrecioCB,
                products.PrecioLP, 
                products.PrecioSC,
                products.PrecioTJ,
                products.PrecioST,

                products.receiveCB,
                products.receiveLP,
                products.receiveSC,
                products.receiveTJ,
                products.receiveST,

                products.extractCB,
                products.extractLP,
                products.extractSC,
                products.extractTJ,
                products.extractST

            FROM (
                SELECT
                    p.idProducto,
                    p.Producto,
                    p.Marca,
                    p.Modelo,
                    p.Imagen,

                    p.StockCB,
                    p.StockLP,
                    p.StockSC,
                    p.StockTJ,
                    p.StockST,

                    p.StockTotal,

                    -- PRECIOS POR SUCURSAL
                    p.PrecioCB,
                    p.PrecioLP,
                    p.PrecioSC,
                    p.PrecioTJ,
                    p.PrecioST,

                    -- STOCK COCHABAMBA
                    (
                        IFNULL((
                            SELECT SUM(c.cantidad)
                            FROM compras c
                            WHERE c.idproducto = p.idProducto
                            AND c.idsucursal = 1
                            AND c.fecha BETWEEN '{$fInicial}' AND '{$fFinal}'
                        ), 0)
                        + IFNULL((
                            SELECT SUM(ec2.cantidad)
                            FROM envio_claves ec2
                            INNER JOIN envio_stock es2 ON es2.clave = ec2.clave
                            WHERE ec2.idProducto = p.idProducto
                            AND es2.estado = 1
                            AND es2.fecha BETWEEN '{$fInicial}' AND '{$fFinal}'
                            AND es2.hasta LIKE 'C%'
                        ), 0)
                    ) AS receiveCB,

                    -- STOCK LA PAZ
                    (
                        IFNULL((
                            SELECT SUM(c.cantidad)
                            FROM compras c
                            WHERE c.idproducto = p.idProducto
                            AND c.idsucursal = 2
                            AND c.fecha BETWEEN  '{$fInicial}' AND '{$fFinal}'
                        ), 0)
                        
                        + IFNULL((
                            SELECT SUM(ec2.cantidad)
                            FROM envio_claves ec2
                            INNER JOIN envio_stock es2 ON es2.clave = ec2.clave
                            WHERE ec2.idProducto = p.idProducto
                            AND es2.estado = 1
                            AND es2.fecha BETWEEN  '{$fInicial}' AND '{$fFinal}'
                            AND es2.hasta LIKE 'L%'
                        ), 0)
                    ) AS receiveLP,

                    -- STOCK SANTA CRUZ
                    (
                        IFNULL((
                            SELECT SUM(c.cantidad)
                            FROM compras c
                            WHERE c.idproducto = p.idProducto
                            AND c.idsucursal = 3
                            AND c.fecha BETWEEN  '{$fInicial}' AND '{$fFinal}'
                        ), 0)
                        
                        + IFNULL((
                            SELECT SUM(ec2.cantidad)
                            FROM envio_claves ec2
                            INNER JOIN envio_stock es2 ON es2.clave = ec2.clave
                            WHERE ec2.idProducto = p.idProducto
                            AND es2.estado = 1
                            AND es2.fecha BETWEEN  '{$fInicial}' AND '{$fFinal}'
                            AND es2.hasta LIKE 'Santa Cruz'
                        ), 0)
                    ) AS receiveSC,

                    -- STOCK FERIAS
                    (
                        IFNULL((
                            SELECT SUM(c.cantidad)
                            FROM compras c
                            WHERE c.idproducto = p.idProducto
                            AND c.idsucursal = 4
                            AND c.fecha BETWEEN '{$fInicial}' AND '{$fFinal}'
                        ), 0)
                        
                        + IFNULL((
                            SELECT SUM(ec2.cantidad)
                            FROM envio_claves ec2
                            INNER JOIN envio_stock es2 ON es2.clave = ec2.clave
                            WHERE ec2.idProducto = p.idProducto
                            AND es2.estado = 1
                            AND es2.fecha BETWEEN  '{$fInicial}' AND '{$fFinal}'
                            AND (LEFT(es2.hasta,1) IN ('F','T'))
                        ), 0)
                    ) AS receiveTJ,

                    -- STOCK SANTA CRUZ TROMPILLO
                    (
                        IFNULL((
                            SELECT SUM(c.cantidad)
                            FROM compras c
                            WHERE c.idproducto = p.idProducto
                            AND c.idsucursal = 5
                            AND c.fecha BETWEEN  '{$fInicial}' AND '{$fFinal}'
                        ), 0)
                    
                        + IFNULL((
                            SELECT SUM(ec2.cantidad)
                            FROM envio_claves ec2
                            INNER JOIN envio_stock es2 ON es2.clave = ec2.clave
                            WHERE ec2.idProducto = p.idProducto
                            AND es2.estado = 1
                            AND es2.fecha BETWEEN  '{$fInicial}' AND '{$fFinal}'
                            AND es2.hasta LIKE '%Trompillo'
                        ), 0)
                    ) AS receiveST,
                    (
                        - IFNULL((
                            SELECT SUM(v.cantidad)
                            FROM Ventas v
                            INNER JOIN Cotizaciones coti ON v.idCotizacion = coti.idCotizacion
                            WHERE v.idproducto = p.idProducto
                            AND v.Fecha BETWEEN '{$fInicial}' AND '{$fFinal}'
                            AND v.CodeCotizacion LIKE 'C%'
                            AND coti.Estado NOT IN (4,7)
                            AND v.estado = 0
                        ), 0)
                        - IFNULL((
                            SELECT SUM(clate.Cantidad)
                            FROM ClaveTemporal clate
                            INNER JOIN Cotizaciones co ON co.Clave = clate.Clave
                            WHERE clate.idProducto = p.idProducto
                            AND co.Estado IN (4,7)
                            AND co.Code LIKE 'C%'
                            AND co.Fecha BETWEEN '{$fInicial}' AND '{$fFinal}'
                            AND co.Completada IS NOT NULL
                        ), 0)
                        - IFNULL((
                            SELECT SUM(ec1.cantidad)
                            FROM envio_claves ec1
                            INNER JOIN envio_stock es1 ON es1.clave = ec1.clave
                            WHERE ec1.idProducto = p.idProducto
                            AND es1.estado IN (1,0)
                            AND es1.fecha BETWEEN '{$fInicial}' AND '{$fFinal}'
                            AND es1.desde LIKE 'C%'
                        ), 0)
                    ) AS extractCB,
                    (
                        - IFNULL((
                            SELECT SUM(v.cantidad)
                            FROM Ventas v
                            INNER JOIN Cotizaciones coti ON v.idCotizacion = coti.idCotizacion
                            WHERE v.idproducto = p.idProducto
                            AND v.Fecha BETWEEN '{$fInicial}' AND '{$fFinal}'
                            AND v.CodeCotizacion LIKE 'L%'
                            AND coti.Estado NOT IN (4,7)
                            AND v.estado = 0
                        ), 0)
                        - IFNULL((
                            SELECT SUM(clate.Cantidad)
                            FROM ClaveTemporal clate
                            INNER JOIN Cotizaciones co ON co.Clave = clate.Clave
                            WHERE clate.idProducto = p.idProducto
                            AND co.Estado IN (4,7)
                            AND co.Code LIKE 'L%'
                            AND co.Fecha BETWEEN '{$fInicial}' AND '{$fFinal}'
                            AND co.Completada IS NOT NULL
                        ), 0)
                        - IFNULL((
                            SELECT SUM(ec1.cantidad)
                            FROM envio_claves ec1
                            INNER JOIN envio_stock es1 ON es1.clave = ec1.clave
                            WHERE ec1.idProducto = p.idProducto
                            AND es1.estado IN (1,0)
                            AND es1.fecha BETWEEN '{$fInicial}' AND '{$fFinal}'
                            AND es1.desde LIKE 'L%'
                        ), 0)
                    ) AS extractLP,
                    ( 
                        - IFNULL((
                            SELECT SUM(v.cantidad)
                            FROM Ventas v
                            INNER JOIN Cotizaciones coti ON v.idCotizacion = coti.idCotizacion
                            WHERE v.idproducto = p.idProducto
                            AND v.Fecha BETWEEN '{$fInicial}' AND '{$fFinal}'
                            AND v.CodeCotizacion LIKE 'SC%'
                            AND coti.Estado NOT IN (4,7)
                            AND v.estado = 0
                        ), 0)
                        - IFNULL((
                            SELECT SUM(clate.Cantidad)
                            FROM ClaveTemporal clate
                            INNER JOIN Cotizaciones co ON co.Clave = clate.Clave
                            WHERE clate.idProducto = p.idProducto
                            AND co.Estado IN (4,7)
                            AND co.Code LIKE 'SC%'
                            AND co.Fecha BETWEEN '{$fInicial}' AND '{$fFinal}'
                            AND co.Completada IS NOT NULL
                        ), 0)
                        - IFNULL((
                            SELECT SUM(ec1.cantidad)
                            FROM envio_claves ec1
                            INNER JOIN envio_stock es1 ON es1.clave = ec1.clave
                            WHERE ec1.idProducto = p.idProducto
                            AND es1.estado IN (1,0)
                            AND es1.fecha BETWEEN '{$fInicial}' AND '{$fFinal}'
                            AND es1.desde LIKE 'Santa Cruz'
                        ), 0)
                    ) AS extractSC,
                    ( 
                        - IFNULL((
                            SELECT SUM(v.cantidad)
                            FROM Ventas v
                            INNER JOIN Cotizaciones coti ON v.idCotizacion = coti.idCotizacion
                            WHERE v.idproducto = p.idProducto
                            AND v.Fecha BETWEEN '{$fInicial}' AND '{$fFinal}'
                            AND (LEFT(v.CodeCotizacion,1) IN ('F','T'))
                            AND coti.Estado NOT IN (4,7)
                            AND v.estado = 0
                        ), 0)
                        - IFNULL((
                            SELECT SUM(clate.Cantidad)
                            FROM ClaveTemporal clate
                            INNER JOIN Cotizaciones co ON co.Clave = clate.Clave
                            WHERE clate.idProducto = p.idProducto
                            AND co.Estado IN (4,7)
                            AND (LEFT(co.Code,1) IN ('F','T'))
                            AND co.Fecha BETWEEN '{$fInicial}' AND '{$fFinal}'
                            AND co.Completada IS NOT NULL
                        ), 0)
                        - IFNULL((
                            SELECT SUM(ec1.cantidad)
                            FROM envio_claves ec1
                            INNER JOIN envio_stock es1 ON es1.clave = ec1.clave
                            WHERE ec1.idProducto = p.idProducto
                            AND es1.estado IN (1,0)
                            AND es1.fecha BETWEEN '{$fInicial}' AND '{$fFinal}'
                            AND (LEFT(es1.desde,1) IN ('F','T'))
                        ), 0)
                    ) AS extractTJ,
                    (
                        - IFNULL((
                                SELECT SUM(v.cantidad)
                                FROM Ventas v
                                INNER JOIN Cotizaciones coti ON v.idCotizacion = coti.idCotizacion
                                WHERE v.idproducto = p.idProducto
                                AND v.Fecha BETWEEN '{$fInicial}' AND '{$fFinal}'
                                AND v.CodeCotizacion LIKE 'ST%'
                                AND coti.Estado NOT IN (4,7)
                                AND v.estado = 0
                            ), 0)
                        - IFNULL((
                            SELECT SUM(clate.Cantidad)
                            FROM ClaveTemporal clate
                            INNER JOIN Cotizaciones co ON co.Clave = clate.Clave
                            WHERE clate.idProducto = p.idProducto
                                AND co.Estado IN (4,7)
                                AND co.Code LIKE 'ST%'
                                AND co.Fecha BETWEEN '{$fInicial}' AND '{$fFinal}'
                                AND co.Completada IS NOT NULL
                        ), 0)
                        - IFNULL((
                            SELECT SUM(ec1.cantidad)
                            FROM envio_claves ec1
                            INNER JOIN envio_stock es1 ON es1.clave = ec1.clave
                            WHERE ec1.idProducto = p.idProducto
                                AND es1.estado IN (1,0)
                                AND es1.fecha BETWEEN '{$fInicial}' AND '{$fFinal}'
                                AND es1.desde LIKE '%Trompillo'
                        ), 0)
                    ) AS extractST
                FROM Productos p
            ) as products
            ORDER BY products.Producto;
        ";

        $response = mysqli_query($this->MySQLi, $this->query);

        // enviar todos los productos
        $products = [];
        while ($row = mysqli_fetch_assoc($response)) {
            $products[] = $row;
        }

        return $products;
    }
}
