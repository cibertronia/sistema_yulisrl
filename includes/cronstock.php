<?php
include 'includes/funcionesListaProductos.php';
mysqli_query($MySQLi, "SET lc_time_names= 'es_BO' ");


$rowP = mysqli_query($MySQLi, "SELECT idproducto, producto, Marca, Modelo, PrecioCB, PrecioLP, PrecioSC, PrecioTJ FROM Productos order by Producto desc limit 5000");    
while ($filaP = mysqli_fetch_assoc($rowP)) {
    $idp = $filaP['idproducto'];
    $pr = $filaP['producto'];    
    $prma = $filaP['Marca'];    
    $prmo = $filaP['Modelo'];    
    
     $stockC = 0;
     $stockL = 0;
     $stockS = 0;
     $stockT = 0;     
 
 
 $q = "select (select ifnull(sum(cantidad),0) from compras 
where idsucursal = 1 and idproducto = $idp and fecha <= '20340101') c1 ,


(SELECT	ifnull(sum(cantidad),0) FROM Ventas v 
    inner join Cotizaciones c on v.idCotizacion = c.idCotizacion
    where v.idproducto = $idp and v.Fecha <= '20340101'
    and v.CodeCotizacion like 'C%'  and c.Estado not in (4,7) and v.estado = 0) v1,
    
 (SELECT ifnull(sum(clate.Cantidad),0)
FROM  Cotizaciones co inner join ClaveTemporal clate on co.Clave = clate.Clave
where co.Estado in (4,7) and co.Completada is not null and clate.idProducto = $idp and co.Code like 'C%' and co.Fecha  <= '20340101') v1cre,

 
(SELECT ifnull(sum(ec.cantidad),0) FROM `envio_stock` es
inner join envio_claves ec on es.clave = ec.clave and ec.idProducto =$idp
where es.estado in ( 1,0) and es.fecha <= '20340101' and es.desde like 'C%') e1,
(SELECT ifnull(sum(ec.cantidad),0) FROM `envio_stock` es
inner join envio_claves ec on es.clave = ec.clave and ec.idProducto = $idp
where es.estado = 1 and es.fecha <= '20340101' and es.hasta like 'C%') r1,

(select ifnull(sum(cantidad),0) from compras 
where idsucursal = 2 and idproducto = $idp and fecha <= '20340101') c2 ,

(SELECT	ifnull(sum(cantidad),0) FROM Ventas v 
    inner join Cotizaciones c on v.idCotizacion = c.idCotizacion
    where v.idproducto = $idp and v.Fecha <= '20340101'
    and v.CodeCotizacion like 'L%' and c.Estado not in (4,7)  and v.estado = 0) v2,
    
    (SELECT ifnull(sum(clate.Cantidad),0)
FROM  Cotizaciones co inner join ClaveTemporal clate on co.Clave = clate.Clave
where co.Estado in (4,7) and co.Completada is not null and clate.idProducto = $idp and co.Code like 'L%' and co.Fecha  <= '20340101') v2cre,


(SELECT ifnull(sum(ec.cantidad),0) FROM `envio_stock` es
inner join envio_claves ec on es.clave = ec.clave and ec.idProducto = $idp
where es.estado in ( 1,0) and es.fecha <= '20340101' and es.desde like 'L%') e2,
(SELECT ifnull(sum(ec.cantidad),0) FROM `envio_stock` es
inner join envio_claves ec on es.clave = ec.clave and ec.idProducto = $idp
where es.estado = 1 and es.fecha <= '20340101' and es.hasta like 'L%') r2,

(select ifnull(sum(cantidad),0) from compras 
where idsucursal =3 and idproducto = $idp and fecha <= '20340101') c3 ,

(SELECT	ifnull(sum(cantidad),0) FROM Ventas v 
    inner join Cotizaciones c on v.idCotizacion = c.idCotizacion
    where v.idproducto = $idp and v.Fecha <= '20340101'
    and v.CodeCotizacion like 'S%' and c.Estado not in (4,7) and v.estado = 0) v3,

    (SELECT ifnull(sum(clate.Cantidad),0)
FROM  Cotizaciones co inner join ClaveTemporal clate on co.Clave = clate.Clave
where co.Estado in (4,7) and co.Completada is not null and clate.idProducto = $idp and co.Code like 'S%' and co.Fecha  <= '20340101') v3cre,

(SELECT ifnull(sum(ec.cantidad),0) FROM `envio_stock` es
inner join envio_claves ec on es.clave = ec.clave and ec.idProducto = $idp
where es.estado in ( 1,0) and es.fecha <= '20340101' and es.desde like 'S%') e3,
(SELECT ifnull(sum(ec.cantidad),0) FROM `envio_stock` es
inner join envio_claves ec on es.clave = ec.clave and ec.idProducto = $idp
where es.estado = 1 and es.fecha <= '20340101' and es.hasta like 'S%') r3,

(select ifnull(sum(cantidad),0) from compras 
where idsucursal = 4 and idproducto = $idp and fecha <= '20340101') c4 ,

(SELECT	ifnull(sum(cantidad),0) FROM Ventas v 
    inner join Cotizaciones c on v.idCotizacion = c.idCotizacion
    where v.idproducto = $idp and v.Fecha <= '20340101'
    and v.CodeCotizacion like 'T%'  and c.Estado not in (4,7) and v.estado = 0) v4,

    (SELECT ifnull(sum(clate.Cantidad),0)
FROM  Cotizaciones co inner join ClaveTemporal clate on co.Clave = clate.Clave
where co.Estado in (4,7) and co.Completada is not null and clate.idProducto = $idp and co.Code like 'T%' and co.Fecha  <= '20340101') v4cre,

(SELECT ifnull(sum(ec.cantidad),0) FROM `envio_stock` es
inner join envio_claves ec on es.clave = ec.clave and ec.idProducto = $idp
where es.estado in ( 1,0) and es.fecha <= '20340101' and es.desde like 'T%') e4,
(SELECT ifnull(sum(ec.cantidad),0) FROM `envio_stock` es
inner join envio_claves ec on es.clave = ec.clave and ec.idProducto = $idp
where es.estado = 1 and es.fecha <= '20340101' and es.hasta like 'T%') r4
";


    $rowStock = mysqli_query($MySQLi, $q   );
    while ($filaStock = mysqli_fetch_assoc($rowStock)) {        
        $stockc1 = $filaStock['c1'];
        $stockv1 = $filaStock['v1'] + $filaStock['v1cre'];
        $stocke1 = $filaStock['e1'];
        $stockr1 = $filaStock['r1'];
        $stockc2 = $filaStock['c2'];
        $stockv2 = $filaStock['v2'] + $filaStock['v2cre'];
        $stocke2 = $filaStock['e2'];
        $stockr2 = $filaStock['r2'];
        $stockc3 = $filaStock['c3'];
        $stockv3 = $filaStock['v3'] + $filaStock['v3cre'];
        $stocke3 = $filaStock['e3'];
        $stockr3 = $filaStock['r3'];        
        $stockc4 = $filaStock['c4'];
        $stockv4 = $filaStock['v4'] + $filaStock['v4cre'];
        $stocke4 = $filaStock['e4'];
        $stockr4 = $filaStock['r4'];                
        break;
    }
$stockIni = $stockc1-$stockv1-$stocke1+$stockr1+$stockc2-$stockv2-$stocke2+$stockr2+$stockc3-$stockv3-$stocke3+$stockr3+$stockc4-$stockv4-$stocke4+$stockr4; 
$stockIni1 = $stockc1-$stockv1-$stocke1+$stockr1; 
$stockIni2 = $stockc2-$stockv2-$stocke2+$stockr2; 
$stockIni3 = $stockc3-$stockv3-$stocke3+$stockr3; 
$stockIni4 = $stockc4-$stockv4-$stocke4+$stockr4; 
mysqli_query($MySQLi, "update Productos set StockCB = $stockIni1, StockLP = $stockIni2, StockSC = $stockIni3, StockTJ = $stockIni4 where idProducto = $idp");

//    $srowStock = mysqli_query($MySQLi, "update Productos set StockCB=$stockIni1, StockLP=$stockIni2, StockSC=$stockIni3, StockTJ=$stockIni4, StockTotal=$stockIni where idProducto=$idp"   );
 
}    



                                                
?>