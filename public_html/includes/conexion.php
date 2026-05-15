<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

$Error = "Error Principal con la base de datos<br>En la linea:  " . __LINE__;
if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
    $_log_dir = 'C:/laragon/tmp';
    $db_host = "localhost";
    $db_user = "root";
    $db_pass = "";
    $db_name = "yulisrl_sistema";
} else {
    $_log_dir = '/home/cibertronia/domains/sistema-yulisrl.cibertronia.cloud/tmp';
    $db_host = "localhost";
    $db_user = "cibertronia";
    $db_pass = "J83Bdct64n61ftUjurUPXXK5i";
    $db_name = "sistema_yulisrl_bd";
}

if (!is_dir($_log_dir)) { @mkdir($_log_dir, 0777, true); }
ini_set('log_errors', 1);
ini_set('error_log', $_log_dir . '/debug_errors.log');

$MySQLi = mysqli_connect($db_host, $db_user, $db_pass, $db_name);

$MySQLi->set_charset("utf8");


$sqlProductosAll = "select (select ifnull(sum(cantidad),0) from compras 
where idsucursal = 1 and idproducto = idpidp and fecha <= 'finfin') c1 ,
(SELECT ifnull(sum(clate.Cantidad),0)
FROM  Cotizaciones co inner join ClaveTemporal clate on co.Clave = clate.Clave
where 
((co.Estado = 0 and co.Entregada is not null)  
or (co.Estado = 2)
or (co.Estado = 1)
)
and clate.idProducto = ipdidp and co.Code like 'C%' and co.Fecha  <= 'finfin') v1,
    
 (SELECT ifnull(sum(clate.Cantidad),0)
FROM  Cotizaciones co inner join ClaveTemporal clate on co.Clave = clate.Clave
where co.Estado in (4,7) and co.Completada is not null and clate.idProducto = ipdidp and co.Code like 'C%' and co.Fecha  <= 'finfin') v1cre,

 
(SELECT ifnull(sum(ec.cantidad),0) FROM `envio_stock` es
inner join envio_claves ec on es.clave = ec.clave and ec.idProducto =ipdidp
where es.estado in ( 1,0) and es.fecha <= 'finfin' and es.desde like 'C%') e1,
(SELECT ifnull(sum(ec.cantidad),0) FROM `envio_stock` es
inner join envio_claves ec on es.clave = ec.clave and ec.idProducto = ipdidp
where es.estado = 1 and es.fecha <= 'finfin' and es.hasta like 'C%') r1,

(select ifnull(sum(cantidad),0) from compras 
where idsucursal = 2 and idproducto = ipdidp and fecha <= 'finfin') c2 ,

(SELECT ifnull(sum(clate.Cantidad),0)
FROM  Cotizaciones co inner join ClaveTemporal clate on co.Clave = clate.Clave
where 
((co.Estado = 0 and co.Entregada is not null)  
or (co.Estado = 2)
or (co.Estado = 1)
)
and clate.idProducto = ipdidp and co.Code like 'L%' and co.Fecha  <= 'finfin') v2,
    (SELECT ifnull(sum(clate.Cantidad),0)
FROM  Cotizaciones co inner join ClaveTemporal clate on co.Clave = clate.Clave
where co.Estado in (4,7) and co.Completada is not null and clate.idProducto = ipdidp and co.Code like 'L%' and co.Fecha  <= 'finfin') v2cre,


(SELECT ifnull(sum(ec.cantidad),0) FROM `envio_stock` es
inner join envio_claves ec on es.clave = ec.clave and ec.idProducto = ipdidp
where es.estado in ( 1,0) and es.fecha <= 'finfin' and es.desde like 'L%') e2,
(SELECT ifnull(sum(ec.cantidad),0) FROM `envio_stock` es
inner join envio_claves ec on es.clave = ec.clave and ec.idProducto = ipdidp
where es.estado = 1 and es.fecha <= 'finfin' and es.hasta like 'L%') r2,

(select ifnull(sum(cantidad),0) from compras 
where idsucursal =3 and idproducto = ipdidp and fecha <= 'finfin') c3 ,

(SELECT ifnull(sum(clate.Cantidad),0)
FROM  Cotizaciones co inner join ClaveTemporal clate on co.Clave = clate.Clave
where 
((co.Estado = 0 and co.Entregada is not null)  
or (co.Estado = 2)
or (co.Estado = 1)
)
and clate.idProducto = ipdidp and co.Code like 'S%' and co.Fecha  <= 'finfin') v3,    

    (SELECT ifnull(sum(clate.Cantidad),0)
FROM  Cotizaciones co inner join ClaveTemporal clate on co.Clave = clate.Clave
where co.Estado in (4,7) and co.Completada is not null and clate.idProducto = ipdidp and co.Code like 'S%' and co.Fecha  <= 'finfin') v3cre,

(SELECT ifnull(sum(ec.cantidad),0) FROM `envio_stock` es
inner join envio_claves ec on es.clave = ec.clave and ec.idProducto = ipdidp
where es.estado in ( 1,0) and es.fecha <= 'finfin' and es.desde like 'S%') e3,
(SELECT ifnull(sum(ec.cantidad),0) FROM `envio_stock` es
inner join envio_claves ec on es.clave = ec.clave and ec.idProducto = ipdidp
where es.estado = 1 and es.fecha <= 'finfin' and es.hasta like 'S%') r3,

(select ifnull(sum(cantidad),0) from compras 
where idsucursal = 4 and idproducto = ipdidp and fecha <= 'finfin') c4 ,

(SELECT ifnull(sum(clate.Cantidad),0)
FROM  Cotizaciones co inner join ClaveTemporal clate on co.Clave = clate.Clave
where 
((co.Estado = 0 and co.Entregada is not null)  
or (co.Estado = 2)
or (co.Estado = 1)
)
and clate.idProducto = ipdidp and co.Code like 'T%' and co.Fecha  <= 'finfin') v4,

    (SELECT ifnull(sum(clate.Cantidad),0)
FROM  Cotizaciones co inner join ClaveTemporal clate on co.Clave = clate.Clave
where co.Estado in (4,7) and co.Completada is not null and clate.idProducto = ipdidp and co.Code like 'T%' and co.Fecha  <= 'finfin') v4cre,

(SELECT ifnull(sum(ec.cantidad),0) FROM `envio_stock` es
inner join envio_claves ec on es.clave = ec.clave and ec.idProducto = ipdidp
where es.estado in ( 1,0) and es.fecha <= 'finfin' and es.desde like 'T%') e4,
(SELECT ifnull(sum(ec.cantidad),0) FROM `envio_stock` es
inner join envio_claves ec on es.clave = ec.clave and ec.idProducto = ipdidp
where es.estado = 1 and es.fecha <= 'finfin' and es.hasta like 'T%') r4";