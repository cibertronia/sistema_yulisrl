<?php

function captura_producto_fiscales($MySQLi, $idProducto, $FechaCierre)
{
    $queryCaptura = mysqli_query(
        $MySQLi,
        "SELECT *
        FROM `capturas_productos_fiscales`
    WHERE DATE_FORMAT(fecha, '%Y-%m-%d')='$FechaCierre'  order by id desc LIMIT 1 "
    );
    $rowcount = mysqli_num_rows($queryCaptura); //si existe
    if ($rowcount > 0) {

        $data = mysqli_fetch_assoc($queryCaptura);
        $captura = (string)$data['captura'];
        $captura = json_decode($captura);

        $productos = $captura->{'productos_fiscales'}; //array con datos del producto
        $stocks = [];
        for ($i = 0; $i < count($productos); ++$i) {
            if ($productos[$i][0] == $idProducto) {
                $stocks = array(
                    'idProducto' => $productos[$i][0],
                    'stock_capturado' => $productos[$i][1],
                );
            }
        }
        return $stocks;
    } else {
        return false;
    }
}
function extractoProductoFiscal($MySQLi, $idProducto, $FechaInicio, $FechaCierre, $branchId)
{
    $q_detailinvoice = mysqli_query(
        $MySQLi,
        "SELECT
        SUM(di.qty) AS qty
    FROM
    detailInvoice di
    JOIN
        factura f ON di.invoicenumber = f.invoicenumber
    WHERE
        (
            DATE_FORMAT(di.dateEmission, '%Y-%m-%d') BETWEEN '$FechaInicio' AND '$FechaCierre'
        ) AND di.detailId = '$idProducto' AND di.branchId = '$branchId'
        AND f.siatCodeState = 908"
    ) or die(mysqli_error($MySQLi));

    $d_detailinvoice = mysqli_fetch_assoc($q_detailinvoice);
    $totalExtraido = ($d_detailinvoice['qty'] == '' || null) ? 0 : (int)$d_detailinvoice['qty'];

    return $totalExtraido;
}

function cantidadTotalFiscalFacturado($MySQLi, $idProducto, $FechaInicio, $FechaCierre)
{
    $q_detailinvoice = mysqli_query(
        $MySQLi,
        "SELECT
        SUM(di.qty) AS qty
    FROM
    detailInvoice di
    JOIN
        factura f ON di.invoicenumber = f.invoicenumber
    WHERE
        (
            DATE_FORMAT(di.dateEmission, '%Y-%m-%d') BETWEEN '$FechaInicio' AND '$FechaCierre'
        ) AND di.detailId = '$idProducto' AND f.siatCodeState = 908"
    ) or die(mysqli_error($MySQLi));
    $d_detailinvoice = mysqli_fetch_assoc($q_detailinvoice);
    $totalExtraido = ($d_detailinvoice['qty'] == '' || null) ? 0 : (int)$d_detailinvoice['qty'];

    return $totalExtraido;
}
function montoTotalFacturado($MySQLi, $idProducto, $FechaInicio, $FechaCierre)
{
    $q_detailinvoice = mysqli_query(
        $MySQLi,
        "SELECT
        round(SUM(di.subTotal),2) AS total_facturado
    FROM
    detailInvoice di
    JOIN
        factura f ON di.invoicenumber = f.invoicenumber
    WHERE
        (
            DATE_FORMAT(di.dateEmission, '%Y-%m-%d') BETWEEN '$FechaInicio' AND '$FechaCierre'
        ) AND di.detailId = '$idProducto' AND f.siatCodeState = 908"
    ) or die(mysqli_error($MySQLi));

    $d_detailinvoice = mysqli_fetch_assoc($q_detailinvoice);
    //$totalFacturado = ($d_detailinvoice['total_facturado'] == '' || null) ? 0 : (int)$d_detailinvoice['total_facturado'];
    $totalFacturado = ($d_detailinvoice['total_facturado'] == '' || null) ? 0 : $d_detailinvoice['total_facturado'];

    return $totalFacturado;
}

function cotizaciones_facturadas_prod_fiscal($MySQLi, $idProducto, $FechaInicio, $FechaCierre)
{
    $q_detailinvoice = mysqli_query(
        $MySQLi,
        "SELECT
        di.idCotizacion AS idCotizacion , di.invoiceNumber AS invoiceNumber
    FROM
    detailInvoice di
    JOIN
        factura f ON di.invoicenumber = f.invoicenumber
    WHERE
    (
        DATE_FORMAT(di.dateEmission, '%Y-%m-%d') BETWEEN '$FechaInicio' AND '$FechaCierre'
    ) AND di.detailId  = '$idProducto' AND f.siatCodeState = 908"
    ) or die(mysqli_error($MySQLi));

    $rowcount = mysqli_num_rows($q_detailinvoice); //si existe

    if ($rowcount > 0) {
        $cadena_cotizaciones = '';
        $cadena_emisiones_directas = '';
        $nro_emision_directas = 0;
        while ($d_detailinvoice = mysqli_fetch_assoc($q_detailinvoice)) {
            $idCotizacion = $d_detailinvoice['idCotizacion'];
            $invoiceNumber = $d_detailinvoice['invoiceNumber'];

            //vemos si es doble o no en la tabla factura
            $q_factura = mysqli_query(
                $MySQLi,
                "SELECT * FROM `factura` WHERE `invoiceNumber` = '$invoiceNumber';"
            ) or die(mysqli_error($MySQLi));
            $d_factura = mysqli_fetch_assoc($q_factura);

            $emision_doble = ($d_factura['doble_invoice_number'] > 0) ? 'Emision Doble' : '';

            if ($idCotizacion != '-1') {
                $q_cotizaciones = mysqli_query(
                    $MySQLi,
                    "SELECT
                    `Code`
                FROM
                    `Cotizaciones`
                WHERE
                    `idCotizacion` = '$idCotizacion';"
                ) or die(mysqli_error($MySQLi));
                $d_cotizaciones = mysqli_fetch_assoc($q_cotizaciones);

                $cadena_cotizaciones .=  $d_cotizaciones['Code'] . '=F' . $invoiceNumber . ' ' . $emision_doble . '';
            } else {
                $cadena_emisiones_directas .=  'EmisionDirectaF' . $invoiceNumber . ' ' . $emision_doble . '';
            }
        }
    }
    // $cadena_cotizaciones = ($nro_emision_directas > 0) ? $cadena_cotizaciones . ' Emision Directa=' . $nro_emision_directas  : $cadena_cotizaciones;
    $cadena_junto = $cadena_cotizaciones . ' ' . $cadena_emisiones_directas;
    if ($cadena_junto[0] == '=') $cadena_junto[0] = ' ';
    return $cadena_junto;
}

function numeros_facturadas_prod_fiscal_yuliimport_doble($MySQLi, $idProducto, $FechaInicio, $FechaCierre)
{
    $q_detailinvoice = mysqli_query(
        $MySQLi,
        "SELECT
        di.idCotizacion AS idCotizacion , di.invoiceNumber AS invoiceNumber
    FROM
    detailInvoice di
    JOIN
        factura f ON di.invoicenumber = f.invoicenumber
    WHERE
    (
        DATE_FORMAT(di.dateEmission, '%Y-%m-%d') BETWEEN '$FechaInicio' AND '$FechaCierre'
    ) AND di.detailId  = '$idProducto' AND f.siatCodeState = 908"
    ) or die(mysqli_error($MySQLi));

    $rowcount = mysqli_num_rows($q_detailinvoice); //si existe

    if ($rowcount > 0) {
        $cadena_facturas_yuliimport = '';

        while ($d_detailinvoice = mysqli_fetch_assoc($q_detailinvoice)) {
            $idCotizacion = $d_detailinvoice['idCotizacion'];
            $invoiceNumber = $d_detailinvoice['invoiceNumber'];

            //vemos si es doble o no en la tabla factura
            $q_factura = mysqli_query(
                $MySQLi,
                "SELECT * FROM `factura` WHERE `invoiceNumber` = '$invoiceNumber';"
            ) or die(mysqli_error($MySQLi));
            $d_factura = mysqli_fetch_assoc($q_factura);

            $doble_invoice_number = ($d_factura['doble_invoice_number'] > 0) ? $d_factura['doble_invoice_number'] : '';

            $cadena_facturas_yuliimport .=    $doble_invoice_number . ' ';
        }
    }

    return $cadena_facturas_yuliimport;
}

function no_existe_captura_inicio($MySQLi, $idProducto, $Inicio, $Fin, $saldo_fisico)
{
    //inicial producto su stock mas antiguo con rango de fechas
    $queryStockMasAntiguo =    mysqli_query(
        $MySQLi,
        "SELECT
inicial
FROM
historial_stock_productos_fiscales
WHERE
idProducto = '$idProducto' AND dateEmission =(
SELECT
    MIN(dateEmission)
FROM
    historial_stock_productos_fiscales
WHERE
    idProducto = '$idProducto' 
    AND(DATE_FORMAT(dateEmission, '%Y-%m-%d') BETWEEN '$Inicio' AND '$Fin'))"
    );

    $dataStockMasAntiguo    =    mysqli_fetch_assoc($queryStockMasAntiguo);


    if ($dataStockMasAntiguo['inicial'] == null) { //no entro a historial
        //1er caso tomamos el primer inicio de su derecha 
        $queryStockMasAntiguo =    mysqli_query(
            $MySQLi,
            "SELECT
    inicial
    FROM
    historial_stock_productos_fiscales
    WHERE
    idProducto = '$idProducto' AND dateEmission =(
    SELECT
        MIN(dateEmission)
    FROM
        historial_stock_productos_fiscales
    WHERE
        idProducto = '$idProducto' 
        AND(DATE_FORMAT(dateEmission, '%Y-%m-%d') BETWEEN '$Inicio' AND '2040-01-01'))"
        );

        $dataStockMasAntiguo    =    mysqli_fetch_assoc($queryStockMasAntiguo);
    }
    if ($dataStockMasAntiguo['inicial'] == null) {
        //2DO CASO sigue sin existir tomamos de su izquierda el max final mas cercano que sera nuestro inicial
        $queryStockMasAntiguo =    mysqli_query(
            $MySQLi,
            "SELECT
    final as 'inicial'
    FROM
    historial_stock_productos_fiscales
    WHERE
    idProducto = '$idProducto' AND dateEmission =(
    SELECT
        MAX(dateEmission)
    FROM
        historial_stock_productos_fiscales
    WHERE
        idProducto = '$idProducto' 
        AND(DATE_FORMAT(dateEmission, '%Y-%m-%d') BETWEEN  '2000-01-01' AND '$Inicio'))"
        );
        $dataStockMasAntiguo    =    mysqli_fetch_assoc($queryStockMasAntiguo);
    }
    if ($dataStockMasAntiguo['inicial'] == null) {
        //3er caso SIGUE SIN EXISTIR nunca de los nunca entro al historial TOMAMOS EL STOCK NOMAS
        //$GranTotalInicial=$GranTotalInicial+$dataProductos['saldo_fisico'];//tomamos su stock
        // echo $dataProductos['saldo_fisico'];
        return $saldo_fisico;
        // $minuendo=$dataProductos['saldo_fisico'];
    }
    if ($dataStockMasAntiguo['inicial'] != null) {
        // $GranTotalInicial=$GranTotalInicial+$dataStockMasAntiguo['inicial'];//si entro a historial
        return $dataStockMasAntiguo['inicial']; //tomamos su valor mas antiguo
        // $minuendo = $dataStockMasAntiguo['inicial'];
    }
}
function no_existe_captura_fin($MySQLi, $idProducto, $Inicio, $Fin, $saldo_fisico)
{
    $queryStockActualFechaFin =    mysqli_query(
        $MySQLi,
        "SELECT
						            	final
						            	FROM
						            	historial_stock_productos_fiscales
						            	WHERE
						            	idProducto = '$idProducto' AND dateEmission =(
						            	SELECT
						            		MAX(dateEmission)
						            	FROM
						            		historial_stock_productos_fiscales
						            	WHERE
						            		idProducto = '$idProducto' 
						            		AND(DATE_FORMAT(dateEmission, '%Y-%m-%d') BETWEEN '$Inicio' AND '$Fin'))"
    );

    $dataStockFechaFin    =    mysqli_fetch_assoc($queryStockActualFechaFin);

    if ($dataStockFechaFin['final'] == null) {
        //1er caso tomamos el primer final de su izqauierda 
        $queryStockActualFechaFin =    mysqli_query(
            $MySQLi,
            "SELECT
                                            final
                                            FROM
                                            historial_stock_productos_fiscales
                                            WHERE
                                            idProducto = '$idProducto' AND dateEmission =(
                                            SELECT
                                                MAX(dateEmission)
                                            FROM
                                                historial_stock_productos_fiscales
                                            WHERE
                                                idProducto = '$idProducto' 
                                                AND(DATE_FORMAT(dateEmission, '%Y-%m-%d') BETWEEN '2000-01-01' AND '$Fin'))"
        );
        $dataStockFechaFin    =    mysqli_fetch_assoc($queryStockActualFechaFin);
    }
    if ($dataStockFechaFin['final'] == null) {
        //2do caso de la derecha
        $queryStockActualFechaFin =    mysqli_query(
            $MySQLi,
            "SELECT
                                            inicial as 'final'
                                            FROM
                                            historial_stock_productos_fiscales
                                            WHERE
                                            idProducto = '$idProducto' AND dateEmission =(
                                            SELECT
                                                MIN(dateEmission)
                                            FROM
                                                historial_stock_productos_fiscales
                                            WHERE
                                                idProducto = '$idProducto' 
                                                AND(DATE_FORMAT(dateEmission, '%Y-%m-%d') BETWEEN '$Fin' AND '2040-01-01' ))"
        );
        $dataStockFechaFin    =    mysqli_fetch_assoc($queryStockActualFechaFin);
    }

    if ($dataStockFechaFin['final'] == null) { //sino existe tomamos del stock producutso fiscale
        // $GranTotalFinal = $GranTotalFinal + $dataProductos['saldo_fisico'];
        // echo $dataProductos['saldo_fisico'];
        return $saldo_fisico;
        // $sustraendo = $dataProductos['saldo_fisico'];
    }
    if ($dataStockFechaFin['final'] != null) {
        // $GranTotalFinal = $GranTotalFinal + $dataStockFechaFin['final'];
        return $dataStockFechaFin['final'];
        // $sustraendo = $dataStockFechaFin['final'];
    }
}
