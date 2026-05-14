<?php
	    include 'includes/conexion.php';
	    
	    
	    // include 'includes/cronstock.php';
	    
	    
	    //2024-03-20|2|purbs|348|1|2
	    $f = $_GET['f'];
	    $c = $_GET['c'];
	    $n = $_GET['n'];
	    $p = $_GET['p'];
	    $s = $_GET['s'];	    
	    $u = $_GET['u'];	    
	    $s = "insert into compras (idproducto, fecha, idsucursal, idusuario, cantidad, detalles) values($p,'$f',$s,$u,$c,'$n')";
        mysqli_query($MySQLi,$s);

/* old cre
SELECT 'Cre', cre.Fecha, u.Correo,
case when cre.CodeCotizacion like 'C%' then -clate.Cantidad else 0 end suc1,
case when cre.CodeCotizacion like 'L%' then -clate.Cantidad else 0 end suc2,
case when cre.CodeCotizacion like 'S%' then -clate.Cantidad else 0 end suc3,
case when cre.CodeCotizacion like 'T%' then -clate.Cantidad else 0 end suc4,
cre.CodeCotizacion
FROM `Creditos` cre 
inner join Cotizaciones co on cre.idCotizacion = co.idCotizacion
inner join ClaveTemporal clate on co.Clave = clate.Clave
left  join Usuarios u on co.idUser = u.idUser
where co.Estado = 4 and clate.idproducto = $p

*/
$sql = "select * from (
SELECT 'C' tipo, c.fecha, u.Correo 
        ,case when idsucursal=1 then cantidad else 0 end suc1
        , case when idsucursal=2 then cantidad else 0 end suc2
        , case when idsucursal=3 then cantidad else 0 end suc3
        , case when idsucursal=4 then cantidad else 0 end suc4
        ,c.detalles
        FROM compras c inner join Usuarios u on u.idUser = c.idusuario where c.idproducto = $p  
        
union all

SELECT 'V', v.Fecha, u.Correo,
case when c.Code  like 'C%' then -v.Cantidad else 0 end suc1,
case when c.Code  like 'L%' then -v.Cantidad else 0 end suc2,
case when c.Code  like 'S%' then -v.Cantidad else 0 end suc3,
case when c.Code  like 'T%' then -v.Cantidad else 0 end suc4,
c.Code 
 FROM Ventas v 
    inner join Cotizaciones c on v.idCotizacion = c.idCotizacion
inner join Usuarios u on v.idUser = u.idUser    
    where v.idproducto = $p  and c.Estado not in (4,7) and v.estado = 0
 
 union all
 
SELECT	 'Cre', co.Fecha, u.Correo,
case when co.Code  like 'C%' then -clate.Cantidad else 0 end suc1,
case when co.Code  like 'L%' then -clate.Cantidad else 0 end suc2,
case when co.Code  like 'S%' then -clate.Cantidad else 0 end suc3,
case when co.Code  like 'T%' then -clate.Cantidad else 0 end suc4,
co.Code
FROM  Cotizaciones co
inner join ClaveTemporal clate on co.Clave = clate.Clave
left  join Usuarios u on co.idUser = u.idUser
where co.Estado in (4,7) and clate.idproducto = $p and co.Completada is not null


  union ALL
 
 SELECT 'E', es.fecha, u.Correo , 
 case when es.desde like 'C%' then -ec.cantidad else 0 end suc1,
 case when es.desde like 'L%' then -ec.cantidad else 0 end suc2,
 case when es.desde like 'S%' then -ec.cantidad else 0 end suc3,
 case when es.desde like 'T%' then -ec.cantidad else 0 end suc4,
 es.observaciones FROM `envio_stock` es
inner join envio_claves ec on es.clave = ec.clave and ec.idProducto =$p
inner join Usuarios u on es.idUser = u.idUser
where es.estado in (0,1)

 union ALL
 
 SELECT 'R', es.fecha, u.Correo , 
 case when es.hasta like 'C%' then ec.cantidad else 0 end suc1,
 case when es.hasta like 'L%' then ec.cantidad else 0 end suc2,
 case when es.hasta like 'S%' then ec.cantidad else 0 end suc3,
 case when es.hasta like 'T%' then ec.cantidad else 0 end suc4,
 es.observaciones FROM `envio_stock` es
inner join envio_claves ec on es.clave = ec.clave and ec.idProducto =$p
inner join Usuarios u on es.idUser = u.idUser
where es.estado = 1 
) X
order by X.fecha";


$result = mysqli_query($MySQLi, $sql);
$row = mysqli_fetch_all($result, MYSQLI_ASSOC);
mysqli_free_result($result);
    echo json_encode($row);
?>