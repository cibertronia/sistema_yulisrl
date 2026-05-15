<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
if (isset($_SESSION['idUser']) && (isset($_GET['inicio']) || isset($_GET['q']))) {
    
    include './../includes/conexion.php';
    $sqlurlyapame = mysqli_query($MySQLi, "SELECT * FROM token_access");
    $dataurlyapame = mysqli_fetch_assoc($sqlurlyapame) or die(mysqli_error($MySQLi));

    $urlyapame = $dataurlyapame['urlcucu'];
    $token = $dataurlyapame['token'];

    // Refactored logic: prefer clean parameters, fallback to decoded query for backward compatibility (but sanitized)
    if (isset($_GET['inicio']) && isset($_GET['fin'])) {
        $Inicio = mysqli_real_escape_string($MySQLi, $_GET['inicio']);
        $Fin = mysqli_real_escape_string($MySQLi, $_GET['fin']);
        $ssucursal = isset($_GET['sucursal']) ? (int)$_GET['sucursal'] : 0;
        
        if ($ssucursal == 0) {
            $qryF = "SELECT f.invoiceCode FROM detailInvoice di inner join factura f on f.invoiceNumber = di.invoiceNumber  WHERE (DATE_FORMAT(di.dateEmission, '%Y-%m-%d') BETWEEN '$Inicio' AND '$Fin' )  ORDER BY di.invoiceNumber DESC";
        } else {
            $qryF = "SELECT f.invoiceCode FROM detailInvoice di inner join factura f on f.invoiceNumber = di.invoiceNumber  WHERE di.branchId=$ssucursal AND (DATE_FORMAT(di.dateEmission, '%Y-%m-%d') BETWEEN '$Inicio' AND '$Fin' )  ORDER BY di.invoiceNumber DESC";
        }
    } else {
        // Fallback to old method but limit what can be executed
        $qryF = base64_decode($_GET['q']);
        // Basic check to ensure it's a SELECT query
        if (stripos(trim($qryF), 'SELECT') !== 0) {
            die("Acceso no autorizado a la base de datos.");
        }
    }
    
    $QueryFactura = mysqli_query($MySQLi, $qryF) or die(mysqli_error($MySQLi));
    
    // Windows friendly paths
    $pdfDir = __DIR__ . DIRECTORY_SEPARATOR . 'pdfs' . DIRECTORY_SEPARATOR;
    if (!is_dir($pdfDir)) {
        mkdir($pdfDir, 0777, true);
    }

    $files = glob($pdfDir . '*'); 
    foreach($files as $file){ 
        if(is_file($file)) unlink($file); 
    }
    
    ini_set('memory_limit', '512M');

    $fileArray = array();

    while ($data = mysqli_fetch_assoc($QueryFactura)) {    
        $invoiceCode = $data['invoiceCode'];
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $urlyapame . "/api/invoices/" . $invoiceCode . "/pdf?tpl=");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            "Content-Type: application/json",
            "Authorization: Bearer $token"
        ));
        $response = curl_exec($ch);
        curl_close($ch);

        $response_decode = json_decode($response);
        if (isset($response_decode->data->buffer)) {
            $pdf64 = $response_decode->data->buffer;
            $pdf64 = str_replace(chr(92), '', $pdf64);
            $filePath = $pdfDir . $invoiceCode . '.pdf';
            file_put_contents($filePath, base64_decode($pdf64));
            $fileArray[] = $invoiceCode . '.pdf';
        }
    }

    if (empty($fileArray)) {
        die("No se encontraron facturas para procesar.");
    }

    // Ghostscript merging - Adjusted for Windows
    // Note: User needs Ghostscript installed (gs.exe)
    $outputFile = $pdfDir . 'merged_all.pdf';
    $cmd = "gs -q -dNOPAUSE -dBATCH -sDEVICE=pdfwrite -sOutputFile=" . escapeshellarg($outputFile);
    foreach($fileArray as $file) {
        $cmd .= " " . escapeshellarg($pdfDir . $file);
    }

    // Attempt execution
    $output = shell_exec($cmd . " 2>&1");
    
    if (file_exists($outputFile)) {
        header('Content-Type: application/pdf');
        header('Content-Disposition: inline; filename="facturas_unificadas.pdf"');
        readfile($outputFile);
    } else {
        echo "<h3>Error al unir los PDFs.</h3>";
        echo "<p>Asegúrese de que Ghostscript esté instalado en el sistema.</p>";
        if ($output) {
            echo "<pre>Detalles del error: $output</pre>";
        }
    }

} else {
    session_destroy();
    echo "Sesión expirada o parámetros inválidos.";
} ?>
