<?php
include_once './../includes/conexion.php';
//require '../vendor/autoload.php';
require './../includes/librerias/mPDF/vendor/autoload.php';
require './../includes/conexion.php';
include './../includes/date.class.php';
require './../includes/librerias/phpMailer/vendor/autoload.php';
error_reporting(0);
if (isset($_GET['ReporteEnvioStock'])) {
  $idEnvioStock = $_GET['ReporteEnvioStock'];
  if ($idEnvioStock   <  10) {
    $ReciboNum = '<span style="letter-spacing: 1px">000000' . $idEnvioStock . '</span>';
  } elseif ($idEnvioStock <  100) {
    $ReciboNum = '<span style="letter-spacing: 1px">00000' . $idEnvioStock . '</span>';
  } elseif ($idEnvioStock < 1000) {
    $ReciboNum = '<span style="letter-spacing: 1px">0000' . $idEnvioStock . '</span>';
  } elseif ($idEnvioStock < 10000) {
    $ReciboNum = '<span style="letter-spacing: 1px">000' . $idEnvioStock . '</span>';
  } elseif ($idEnvioStock < 100000) {
    $ReciboNum = '<span style="letter-spacing: 1px">00' . $idEnvioStock . '</span>';
  } elseif ($idEnvioStock < 1000000) {
    $ReciboNum = '<span style="letter-spacing: 1px">0' . $idEnvioStock . '</span>';
  } elseif ($idEnvioStock < 10000000) {
    $ReciboNum = '<span style="letter-spacing: 1px">' . $idEnvioStock . '</span>';
  }
  $mpdf   =  new \Mpdf\Mpdf([
    'mode'      =>  'utf-8',
    'format'     => [280, 216],
    'orientation'  =>  'L',
    'margin_header'  =>  0,
    'margin_footer'  =>  0,
    'margin_left'  =>  0,
    'margin_top'  =>  29,
    'margin_right'  =>  0,
    'margin_bottom'  =>  29,
  ]);
  $CSS   =  file_get_contents('css/envioStock.css');
  $mpdf->SetHTMLHeader('<img src="../assets/img/HEADER.png">');
  $sqlEnvio       = mysqli_query($MySQLi, "SELECT * FROM envio_stock WHERE idEnvio='$idEnvioStock' ") or die(mysqli_error($MySQLi) . "<br>Error en la línea: " . __LINE__);
  $dataEnvio       = mysqli_fetch_assoc($sqlEnvio);
  $claveEnvio     = $dataEnvio['clave'];

  //$origen         = $dataEnvio['id_origen'];
  $desde = $dataEnvio['desde'];
  $origen = $desde;
  // $hasta = $dataEnvio['id_destino'];
  $hasta = $dataEnvio['hasta'];

  // $q_sucursal = mysqli_query(
  //   $MySQLi,
  //   "SELECT * FROM sucursales WHERE idTienda='$origen'"
  // );
  // $d_sucursal = mysqli_fetch_assoc($q_sucursal);
  // $origen = $d_sucursal["sucursal"];

  // $q_sucursal = mysqli_query(
  //   $MySQLi,
  //   "SELECT * FROM sucursales WHERE idTienda='$hasta'"
  // );
  // $d_sucursal = mysqli_fetch_assoc($q_sucursal);
  // $hasta = $d_sucursal["sucursal"];

  $idUser         = $dataEnvio['idUser'];
  $Observaciones   = $dataEnvio['observaciones'];
  $FechaEnvio     = $dataEnvio['fecha'];
  $estado         = $dataEnvio['estado'];
  $fechaEnvio     = date("d-m-Y", strtotime($FechaEnvio));
  $HoraEnvio       = date("g:i a", strtotime($dataEnvio['hora']));

  $fecha_recibido    = $dataEnvio['fecha_recibido'];
  $fecha_recibido = ($fecha_recibido == '' || $fecha_recibido == null) ? '' : date("d-m-Y", strtotime($fecha_recibido));

  $hora_recibido = $dataEnvio['fecha_recibido'];
  $hora_recibido = ($hora_recibido == '' || $hora_recibido == null) ? '' : date("g:i a", strtotime($hora_recibido));

  $Vendedor       = $dataEnvio['encargado_envio'];
  if ($estado == 0) {
    $status   = "<span style='color:blue'>En proceso</span>";
  } elseif ($estado == 1) {
    $status   = "<span style='color:green'>Recibido</span>";
  } else {
    $status   = "<span style='color:red'>Cancelado</span>";
  }

  $subtable = '
  <div class="report-container">
  <div class="content">
  <br>
  <br>
  <table width="90%" style="margin:auto">
    <tr>
      <td width="65%" style="font-size:35px;text-align:center">MOVIMIENTO DE MERCADERIA
      </td>
      <td width="35%">
         <table width="100%" border=1>
           <tr>
             <td style="text-align:center">CIUDAD</td>
           </tr>
           <tr>
             <td style="text-align:center">' . $origen . '</td>
           </tr>
           <tr>
             <td style="text-align:center">FECHA ENVIADO<br>FECHA RECIBIDO</td>
           </tr>
           <tr>
             <td style="text-align:center">' . $fechaEnvio . "&nbsp;" . $HoraEnvio . '<br>' . $fecha_recibido . '&nbsp;' . $hora_recibido . '</td>
           </tr>
         </table>
      </td>
    </tr>
    <tr>
      <td colspan="2" style="text-align:right;font-size:30px;color:red">' . $ReciboNum . '</td>
    </tr>

    <tr>
      <td>Origen: &nbsp; ' . $origen . '</td>
    </tr>
    <tr>
      <td>Destino: &nbsp;' . $hasta . '</td>
    </tr>
    <tr>
      <td style="text-align:left">Estado: &nbsp; ' . $status . '</td>
    </tr>
  </table>
  ';
  $html = '
  <table class="tablaProductos" autosize="1" border="1" style="page-break-inside:avoid;">
  <tr>
    <td colspan="4" >' . $subtable . '</td>    
  </tr>
    <thead>
      <tr>
        <td style="width:10%;text-align:center;font-weight: bold;">CANTIDAD</td>
        <td style="width:50%;text-align:center;font-weight: bold;">DETALLE</td>
        <td style="width:20%;text-align:center;font-weight: bold;">MARCA</td>
        <td style="width:20%;text-align:center;font-weight: bold;">MODELO</td>
      </tr>
    </thead>
    <tbody>';
  $sqlClaveEnvios = mysqli_query($MySQLi, "SELECT * FROM envio_claves WHERE clave='$claveEnvio' ") or die(mysqli_error($MySQLi) . "<br>Error en la línea: " . __LINE__);
  $total_cantidad = 0;
  while ($dataClave = mysqli_fetch_assoc($sqlClaveEnvios)) {
    $html .= '
      <tr>
        <td style="text-align:center;padding:8px">' . $dataClave['cantidad'] . '</td>';
    $total_cantidad += (int)$dataClave['cantidad'];
    $idProducto = $dataClave['idProducto'];

    $sqlProducto = mysqli_query($MySQLi, "SELECT * FROM Productos WHERE idProducto='$idProducto'");
    $dataProducto = mysqli_fetch_assoc($sqlProducto);
    $ProductoNombre = $dataProducto['Producto'];
    $MarcaProducto  = $dataProducto['Marca'];
    $ModeloProducto = $dataProducto['Modelo'];
    $html .= '
        <td style="padding-left:8px">' . $ProductoNombre . '</td>
        <td style="padding-left:8px">' . $MarcaProducto . '</td>
        <td style="padding-left:8px">' . $ModeloProducto . '</td>
      </tr>
      ';
  }
  $html .= '
      <tr>
        <td style="text-align:center;padding:8px;font-weight: bold;"> ' . $total_cantidad . ' </td>
        <td style="padding-left:8px;font-weight: bold;" colspan="3"> TOTAL </td>
        
      </tr>
      <tr style="border: none;">
       
        <td style="padding-left:8px;font-weight: bold;" colspan="4">&nbsp;</td>
      </tr>
      ';
  //items adicionales
  $q_envio_extras = mysqli_query($MySQLi, "SELECT * FROM envio_extras WHERE clave='$claveEnvio' ") or die(mysqli_error($MySQLi) . "<br>Error en la línea: " . __LINE__);
  $cantidad_extras = mysqli_num_rows($q_envio_extras);
  if ($cantidad_extras > 0) {
    $html .= '	
      <tr>
        <td style="width:10%;text-align:center;font-weight: bold;">CANTIDAD</td>
        <td style="width:50%;text-align:center;font-weight: bold;">ITEMS ADICIONALES</td>
        <td style="width:20%;text-align:center;font-weight: bold;">MARCA</td>
        <td style="width:20%;text-align:center;font-weight: bold;">MODELO</td>
      </tr>
      ';
    $total_extras = 0;
    while ($d_envio_extras = mysqli_fetch_assoc($q_envio_extras)) {
      $html .= '
          <tr>
            <td style="text-align:center;padding:8px;">' . $d_envio_extras['cantidad'] . '</td>';
      $total_extras += (int)$d_envio_extras['cantidad'];
      $nombre = $d_envio_extras['nombre'];
      $marca = $d_envio_extras['marca'];
      $modelo = $d_envio_extras['modelo'];
      $precio = $d_envio_extras['precio'];

      $html .= '
            <td style="padding-left:8px">' . $nombre . '</td>
            <td style="padding-left:8px">' . $marca . '</td>
            <td style="padding-left:8px">' . $modelo . '</td>
          </tr>';
    }
    $html .= '	
    <tr>
      <td style="text-align:center;padding:8px;font-weight: bold;"> ' . $total_extras . ' </td>
      <td style="padding-left:8px;font-weight: bold;" colspan="3"> TOTAL </td>
    </tr>	';
  }

  $html .= '	  
    </tbody>
  </table>
  
  </div>
      <div class="signature-container">
        <!-- Firmas de los clientes -->
            <table style="width: 100%;margin: 0px 20px 10px 20px;font-size:14px ">
              <tr>
                <td><span style="font-size:10px">Observaciones: &nbsp; ' . $Observaciones . '</span></td>
              </tr>
            </table><br><br><br>
           
            <table style="width: 90%;margin: 0px 20px 10px 20px;font-size:14px ">
              <tr>
                <td width="50%" style="text-align:center">______________________________<br>Firma encargado origen<br>' . $Vendedor . '</td>
                <td width="50%" style="text-align:center">______________________________<br>Firma encargado recibido</td>
              </tr>
              
              
            </table>
      </div>
  </div>
  ';
  //$mpdf->SetHTMLFooter('<img src="assets/img/FOOTER.png">');
  $NamePDF =  "Reporte envio stock  " . $idEnvioStock;
  $mpdf->WriteHTML($CSS, \Mpdf\HTMLParserMode::HEADER_CSS);
  $mpdf->WriteHTML("$html", \Mpdf\HTMLParserMode::HTML_BODY);
  $mpdf->Output($NamePDF . ".pdf", "I");
}
