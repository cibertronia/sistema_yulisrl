<?php
include 'includes/conexion.php';
include 'includes/date.class.php';
include 'includes/funcionesListaProductos.php';
mysqli_query($MySQLi, "SET lc_time_names= 'es_BO' ");
$idUser     =    $_SESSION['idUser'];
$ConsltaUser =    mysqli_query($MySQLi, "SELECT * FROM Usuarios WHERE idUser='$idUser' ");
$datosUser     =    mysqli_fetch_assoc($ConsltaUser);
$miCiudad     =    $datosUser['Ciudad'];
error_reporting(0);
if ($_SESSION['Rango']) { ?>
    <!DOCTYPE html>
    <html lang="es">

    <head>
        <title>PRODUCTOS</title>
        <?php include 'php/meta.php'; ?>
        <link href="assets/css/apple/app.min.css" rel="stylesheet">
        <link href="assets/plugins/ionicons/css/ionicons.min.css" rel="stylesheet">
        <link href="assets/plugins/datatables.net-bs4/css/dataTables.bootstrap4.min.css" rel="stylesheet">
        <link href="assets/plugins/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css" rel="stylesheet">
        <link href="assets/plugins/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css" rel="stylesheet">
        <link href="assets/plugins/blueimp-gallery/css/blueimp-gallery.min.css" rel="stylesheet">
        <link href="assets/plugins/blueimp-file-upload/css/jquery.fileupload.css" rel="stylesheet">
        <link href="assets/plugins/blueimp-file-upload/css/jquery.fileupload-ui.css" rel="stylesheet">
        <link href="assets/plugins/summernote/dist/summernote.css" rel="stylesheet">
    </head>

    <body>
        
        <div class="modal fade" id="add11" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-sm" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title" id="Label"><b>AGREGAR</b>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </h4>
                            </div>
                            <div class="modal-body">
                                <div class="form-group">
                                <label for="email" class="control-label">Fecha</label>
                                <input class="form-control" id="fecha1" value="<?php date('Y-m-d')?>" name="fecha1" type="date" required>
                </div>                
                                <div class="form-group">
                                <label for="sucursal" class="control-label">Sucursal</label>
                                <select class="form-control" id="sucursal1" name="sucursal1">
                                        <option value="1">Cochabamba</option>
                                        <option value="2">La Paz</option>
                                        <option value="3">Santa Cruz</option>
                                        <option value="4">Tarija</option>
                                </select>
                                
                </div>
                
                <div class="form-group">
                                <label for="email" class="control-label">Cantidad
                                <input class="form-control txtNum" step="1" id="cantidad1" required value="" name="cantidad1" type="number">
          </div>                      
                                
                                
                                <div class="form-group">
            <label for="email" class="control-label">Notas</label>
                            <textarea id="notas1" name="notas1" rows="3" style="width:100%" maxlength="255"></textarea>
          </div>
                
                                
                                <label id="lblmsg1" name="lblmsg1" for="email" class="control-label divMsg">*</label>                                
                            </div>    
                            <div class="modal-footer">
                                <button id="btadd11" name="btadd11" type="button" class="btn btn-primary btadd11">Agregar</button>
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                            </div>
                        </div>
                    </div>
                </div>
                
                
                
                
        <div class="modal fade" id="add1" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-xl" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title" id="Label"><b>MOVIMIENTOS</b>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </h4>
                            </div>
                            <div class="modal-body">
                                <table id="tbcompras" name="tbcompras" class="table table-striped table-bordered table-td-valign-middle">
													    <thead>
													    <tr>
													        <th>Fecha</th>
													        <th>Usuario</th>													        
													        <th>CB</th>
													        <th>LP</th>
													        <th>SC</th>
													        <th>TJ</th>
													        <th>Detalles  V=Ventas C=Compras E=Extraidos R=Recibidos</th>
													    </tr>    
													    </thead>
													    <tbody>
													        
													    </tbody>
													</table>
                            </div>    
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                            </div>
                        </div>
                    </div>
                </div>
                
        <?php include 'php/loader.php'; ?>
        <div id="page-container" class="fade page-sidebar-fixed page-header-fixed">
            <?php
            include 'php/top_menu.php';
            include 'php/left_menu_productos.php';
            ?>
            <div id="content" class="content">
                <div class="respuesta"></div>
                <?php
                if (isset($_POST['inicio'])) {
                    $INICIO = $_POST['inicio'];
                    $FIN = $_POST['fin'];
                } else {
                    $INICIO = $startBusqueda; //startbuskeda = 1 del mes
                    $FIN = $fecha; //fecha = hoy
                }
                ?>

                <!-- 	EDITAR PRODUCTO	 -->
                <div class="row d-none editProducto w-75 m-auto" id="editProducto">
                    <div class="col">
                        <div class="panel panel-primary">
                            <div class="panel-heading">
                                <h4 class="panel-title">EDITAR PRODUCTO</h4>
                                <button class="btn btn-xs btn-danger cancelarEditProducto2" id="cancelarEditProducto2" name="cancelarEditProducto2">CANCELAR</button>
                            </div>
                            <div class="panel-body">
                                <form enctype="multipart/form-data" method="POST" action="app.php" data-parsley-validate="true">
                                    <div class="row text-center w-50 m-auto">
                                        <div class="col">
                                            <div id="imgx_"></div>
                                        </div>
                                        <div class="col">
                                            <span class="btn btn-primary fileinput-button mt-4">
                                                <i class="fa fa-plus"></i>
                                                <span>Cambiar imagen</span>
                                                <input type="file" name="imagen" id="img_file_">
                                            </span>
                                        </div>
                                    </div>
                                    <div class="row mt-3">
                                        <div class="col">
                                            <label for="ProdNombre_">Nombre del producto</label>
                                            <input type="hidden" name="idUser_" id="idUser_" value="<?php echo $idUser?>">
                                            <input type="hidden" name="idProducto" id="idProducto_">
                                            <input type="text" name="Producto" id="ProdNomnbre_" class="form-control" placeholder="Producto" data-parsley-required="true">
                                            <div class="text-center text-danger d-none emptyProdNombre_">Campo producto está
                                                vacío</div>
                                        </div>
                                        <div class="col">
                                            <label for="ProdMarca_">Marca del producto</label>
                                            <input type="text" name="Marca" id="ProdMarca_" class="form-control" placeholder="Marca" data-parsley-required="true">
                                            <div class="text-center text-danger d-none emptyProdMarca_">Campo marca está
                                                vacío</div>
                                        </div>
                                        <div class="col">
                                            <label for="ProdModelo_">Modelo del producto</label>
                                            <input type="text" name="Modelo" id="ProdModelo_" class="form-control" placeholder="Modelo" data-parsley-required="true">
                                            <div class="text-center text-danger d-none emptyProdModelo_">Campo modelo está
                                                vacío</div>
                                        </div>
                                    </div>
                                    <div class="row mt-3 w-75 m-auto">
                                        <div class="col text-center">
                                            <label for="ProdDescripcion_">Descripción del producto</label>
                                            <textarea name="Descripcion" id="ProdDescripcion_" data-parsley-required="true" cols="30" rows="5" class="form-control" placeholder="Descripción"></textarea>
                                            <div class="text-center text-danger d-none emptyProductoDescripcion_">Campo
                                                descripción está vacío</div>
                                        </div>
                                    </div>

                                    <div class="row mt-3">
                                        <!-- 	CENTRAL COCHABAMBA 	-->

                                        <div class="col text-center <?php if (!($miCiudad == 'Cochabamba' || $_SESSION['Rango'] == '2')) {
                                                                        echo 'd-none';
                                                                    } ?>">
                                            <span class="form-control bg-success text-white">CENTRAL COCHABAMBA</span>
                                            <div class="row mt-2">

                                                <div class="col">
                                                    <label for="ProdStockCB_">STOCK</label>
                                                    <input type="text" value="" name="StockCB" id="ProdStockCB_" class="form-control" data-parsley-type="integer" placeholder="Stock" data-parsley-required="false" readonly>
                                                    <div class="text-center text-danger d-none emptyStockCB">Campo stock
                                                        está vacío</div>
                                                </div>

                                                <div class="col">
                                                    <label for="ProdPrecioCB_">PRECIO</label>
                                                    <input type="text" name="PrecioCB" id="ProdPrecioCB_" data-parsley-type="number" class="form-control" placeholder="Precio" data-parsley-required="true">
                                                    <div class="text-center text-danger d-none emptyPrecioCB_">Campo precio
                                                        está vacío</div>
                                                </div>
                                            </div>
                                            <div class="row mt-2">
                                                <div class="col">
                                                    <label for="ProdObservCB_">OBSERVACIONES</label>
                                                    <textarea name="ObservacionesCB" id="ProdObservCB_" data-parsley-required="false" cols="30" rows="5" class="form-control" placeholder="Observaciones"></textarea>
                                                    <div class="text-center text-danger d-none emptyObservacionesCB_">Campo
                                                        observaciones está vacío</div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- 	SUCURSAL LA PAZ	 -->
                                        <div class="col text-center <?php if (!($miCiudad == 'La Paz' || $_SESSION['Rango'] == '2')) {
                                                                        echo 'd-none';
                                                                    } ?>">
                                            <span class="form-control bg-success text-white">SUCURSAL LA PAZ</span>
                                            <div class="row mt-2">
                                                <div class="col">
                                                    <label for="ProdStockLP_">STOCK</label>
                                                    <input type="text" name="StockLP" id="ProdStockLP_" class="form-control" data-parsley-type="integer" placeholder="Stock" data-parsley-required="false" readonly>
                                                    <div class="text-center text-danger d-none emptyStockLP_">Campo stock
                                                        está vacío</div>
                                                </div>
                                                <div class="col">
                                                    <label for="ProdPrecioLP_">PRECIO</label>
                                                    <input type="text" name="PrecioLP" id="ProdPrecioLP_" data-parsley-type="number" class="form-control" placeholder="Precio" data-parsley-required="true">
                                                    <div class="text-center text-danger d-none emptyPrecioLP_">Campo precio
                                                        está vacío</div>
                                                </div>
                                            </div>
                                            <div class="row mt-2">
                                                <div class="col">
                                                    <label for="ProdObservLP_">OBSERVACIONES</label>
                                                    <textarea name="ObservacionesLP" id="ProdObservLP_" data-parsley-required="false" cols="30" rows="5" class="form-control" placeholder="Observaciones"></textarea>
                                                    <div class="text-center text-danger d-none emptyObservaciones_LP">Campo
                                                        observaciones está vacío</div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>

                                    <!-- 	SUCURSAL SANTA CRUZ	 -->
                                    <div class="row mt-3">

                                        <div class="col text-center <?php if (!($miCiudad == 'Santa Cruz' || $_SESSION['Rango'] == '2')) {
                                                                        echo 'd-none';
                                                                    } ?>">
                                            <span class="form-control bg-success text-white">SUCURSAL SANTA CRUZ</span>
                                            <div class="row mt-2">

                                                <div class="col">
                                                    <label for="ProdStockSC_">STOCK</label>
                                                    <input type="text" name="StockSC" id="ProdStockSC_" class="form-control" data-parsley-type="integer" placeholder="Stock" data-parsley-required="false" readonly>
                                                    <div class="text-center text-danger d-none emptyStockSC_">Campo stock
                                                        está vacío</div>
                                                </div>

                                                <div class="col">
                                                    <label for="ProdPrecioSC_">PRECIO</label>
                                                    <input type="text" name="PrecioSC" id="ProdPrecioSC_" data-parsley-type="number" class="form-control" placeholder="Precio" data-parsley-required="true">
                                                    <div class="text-center text-danger d-none emptyPrecioSC_">Campo precio
                                                        está vacío</div>
                                                </div>
                                            </div>
                                            <div class="row mt-2">
                                                <div class="col">
                                                    <label for="PordObserv_SC">OBSERVACIONES</label>
                                                    <textarea name="ObservacionesSC" id="ProdObserv_SC" data-parsley-required="false" cols="30" rows="5" class="form-control" placeholder="Observaciones"></textarea>
                                                    <div class="text-center text-danger d-none emptyObservacionesSC_">Campo
                                                        observaciones está vacío</div>
                                                </div>
                                            </div>
                                        </div>


                                        <!-- 	SUCURSAL TARIJA	 -->

                                        <div class="col text-center <?php if (!($miCiudad == 'Tarija' || $_SESSION['Rango'] == '2')) {
                                                                        echo 'd-none';
                                                                    } ?>">
                                            <span class="form-control bg-success text-white">SUCURSAL TARIJA</span>
                                            <div class="row mt-2">
                                                <div class="col">
                                                    <label for="ProdStockTJ_">STOCK</label>
                                                    <input type="text" name="StockTJ" id="ProdStockTJ_" class="form-control" data-parsley-type="integer" placeholder="Stock" data-parsley-required="false" readonly>
                                                    <div class="text-center text-danger d-none emptyStockTJ_">Campo stock
                                                        está vacío</div>
                                                </div>
                                                <div class="col">
                                                    <label for="ProdPrecioTJ_">PRECIO</label>
                                                    <input type="text" name="PrecioTJ" id="ProdPrecioTJ_" data-parsley-type="number" class="form-control" placeholder="Precio" data-parsley-required="true">
                                                    <div class="text-center text-danger d-none emptyPrecioTJ_">Campo precio
                                                        está vacío</div>
                                                </div>
                                            </div>
                                            <div class="row mt-2">
                                                <div class="col">
                                                    <label for="PordObserv_TJ">OBSERVACIONES</label>
                                                    <textarea name="ObservacionesTJ" id="ProdObserv_TJ" data-parsley-required="false" cols="30" rows="5" class="form-control" placeholder="Observaciones"></textarea>
                                                    <div class="text-center text-danger d-none emptyObservacionesTJ_">Campo
                                                        observaciones está vacío</div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="row mt-3">
                                        <div class="col">
                                            <button type="submit" class="btn btn-xs btn-primary form-control">ACTUALIZAR PRODUCTO &nbsp;<i class="fas d-none fa-spinner fa-pulse subFunction"></i></button>
                                        </div>
                                    </div>
                                    
                                    
                                    
                                    <div class="row mt-3">
												<div class="col">
												    
													<?php 
													if  (isset($datosUser['Cargo'][0]) && ($datosUser['Cargo'][0] == 'A' )) 
													echo ('<a class="btn btn-xs btn-primary form-control btnadd11" href="#" data-toggle="modal" data-target="#add11">AGREGAR STOCK</a>'); 
													
													?>
												</div>												
									</div>
									
									 		
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 	TABLA PRODUCTO	 -->
                <div class="row tableProductos" id="tableProductos">
                    <div class="col-md-12">
                        <div class="panel panel-inverse">
                            <div class="panel-heading">
                                <h4 class="panel-title">LISTA DE PRODUCTOS</h4>
                                <div class="panel-heading-btn">
                                    <?php

                                    if ($_SESSION['idUser'] == 1) { ?>
                                        <!-- <button class="btn btn-xs btn-danger DownProductos"><i class="fa fa-download"></i></button> -->&nbsp;<?php
                                                                                                                                                }

                                                                                                                                                    ?>
                                        &nbsp;<a href="?root=nuevoproducto" class="btn btn-xs btn-primary">AGREGAR PRODUCTO</a>
                                        <!-- <button class="btn btn-xs btn-primary AddNewProductoBTN">AGREGAR PRODUCTO</button>&nbsp;&nbsp;&nbsp; -->
                                        <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
                                        <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-success" data-click="panel-reload"><i class="fa fa-redo"></i></a>
                                        <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>
                                        <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-danger" data-click="panel-remove"><i class="fa fa-times"></i></a>
                                </div>
                            </div>
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col mt-11">
                                        <!-- <div class="divBuscarSubidaSistema">
                                        <button class="btn btn-primary BuscarSubidaSistema"><i class="fa fa-search">
                                                Buscar</i></button>&nbsp;&nbsp;
                                    </div> -->
                                        <div class="input-group input-daterange divFecha ">
                                            <form action="?root=productos2" method="post">
                                                <input required="" type="date" name="inicio" id="inicio" value="<?php echo $INICIO ?>">
                                                <input required="" type="date" name="fin" id="fin" value="<?php echo $FIN ?>">
                                                <input type="submit" class="btn btn-xs btn-primary" value="BUSCAR">
                                            </form>

                                        </div>
                                    </div>
                                    <div class="col-xl-3 col-md-5">

                                        <a class="btn btn-success btn-sm btn-block" href="Reportes/reporteListaProductos.php?reporteListaProductos=reporteListaProductos&fechaInicio=<?php echo $INICIO ?>&fechafin=<?php echo $FIN ?>" title="Historial todos los Productos en rango de fechas"><span style="color: white">
                                                EXCEL DESCARGAR</span>&nbsp;&nbsp;
                                            <i class="fa fa-download" style="color: white"></i>
                                        </a>

                                    </div>
                                </div>



                                <br>
                                <div style="overflow-x:auto;">
                                    <table id="data-table-buttons" class="table table-striped table-bordered table-td-valign-middle w-100">
                                            <thead>
                                                <tr>
                                                   <!-- <th>ID</th>-->
                                                    <th>PRODUCTO</th>
                                                    <th>MARCA</th>
                                                    <th>MODELO</th>
                                                    <th style="text-align:center;background-color: #FFF2CC">STOCK<br>CB</th>
                                                    <th>PrecioCB</th>
                                                    <th style="text-align:center;background-color: #DDEBF7">STOCK<br>LP</th>
                                                    <th>PrecioLP</th>
                                                    <th style="text-align:center;background-color: #E2EFDA">STOCK<br>SC</th>
                                                    <th>PrecioSC</th>
                                                    <th style="text-align:center;background-color: #FCE4D6">STOCK<br>TJ</th>
                                                    <th>PrecioTJ</th>
                                                    <th style="text-align:center;background-color: #FFF2CC">STOCK</th>                                                    
                                                    <th style="text-align:center;background-color: #FFF2CC">Extraidos<br>CB<br><?php echo "$INICIO/$FIN"?> </th>
                                                    <th style="text-align:center;background-color: #FFF2CC">Recepcionados<br>CB<br><?php echo "$INICIO/$FIN"?></th>
                                                    <th style="text-align:center;background-color: #DDEBF7">Extraidos<br>LP<br><?php echo "$INICIO/$FIN"?></th>
                                                    <th style="text-align:center;background-color: #DDEBF7">Recepcionados<br>LP<br><?php echo "$INICIO/$FIN"?></th>
                                                    <th style="text-align:center;background-color: #E2EFDA">Extraidos<br>SC<br><?php echo "$INICIO/$FIN"?></th>
                                                    <th style="text-align:center;background-color: #E2EFDA">Recepcionados<br>SC<br><?php echo "$INICIO/$FIN"?></th>
                                                    <th style="text-align:center;background-color: #FCE4D6">Extraidos<br>TJ<br><?php echo "$INICIO/$FIN"?></th>
                                                    <th style="text-align:center;background-color: #FCE4D6">Recepcionados<br>TJ<br><?php echo "$INICIO/$FIN"?></th>   
                                                    
                                                    <th>...</th>
                                                </tr>
                                            </thead>
                                                                                    <tbody>
                                            <?php
                                                
                                                
                                                

$rowP = mysqli_query($MySQLi, "SELECT idproducto, producto, Marca, Modelo, PrecioCB, PrecioLP, PrecioSC, PrecioTJ , imagen FROM Productos order by Producto desc limit 50000");    
while ($filaP = mysqli_fetch_assoc($rowP)) {
    $idp = $filaP['idproducto'];
    $pr = $filaP['producto'];    
    $prma = $filaP['Marca'];    
    $prmo = $filaP['Modelo'];    
    
if (false) {    
    $qry = 'SELECT * FROM capturas_productos where captura like \'%["' . $idp . '","%\' and captura not like \'%["' . $idp . '","0","0","0","0","0"%\' order by fecha asc';
    $row = mysqli_query($MySQLi, 
        $qry
    );
        $stockIniC = 0;
        $stockIniL = 0;
        $stockIniS = 0;
        $stockIniT = 0;
    while ($fila = mysqli_fetch_assoc($row)) {
     
            
        $captura = (string)$fila['captura'];
        $captura = json_decode($captura);

        $productos = $captura->{'productos'}; //array con datos del producto
        $fecha = $captura->{'fecha'}; //array con datos del producto        
        for ($i = 0; $i < count($productos); ++$i) {
            if ($productos[$i][0] == $idp) {
                $stockIniC = $productos[$i][1];
                $stockIniL = $productos[$i][2];
                $stockIniS = $productos[$i][3];
                $stockIniT = $productos[$i][4];
            
            echo "<tr>";
            echo "<td>" . $fecha . "</td>";                        
            echo "<td>" . $idp . $pr . "</td>";                        
            echo "<td>" . $stockIniC . "</td>";                        
            echo("<td>" . $stockIniL . "</td>");                            
            echo("<td>" . $stockIniS . "</td>");                            
            echo("<td>" . $stockIniT . "</td>");                            
            echo "</tr>";
            
                //if (($stockIniC >=0) && ($stockIniL >=0) && ($stockIniS >=0) && ($stockIniC >=0))
                //    break;
            }
        }
     }
}      
     // 
/* old v1cre (SELECT ifnull(sum(clate.Cantidad),0)s
FROM `Creditos` cre 
inner join Cotizaciones co on cre.idCotizacion = co.idCotizacion
inner join ClaveTemporal clate on co.Clave = clate.Clave
where co.Estado = 4 and clate.idProducto = $idp and cre.CodeCotizacion like 'C%' and cre.Fecha <= '$FIN') v1cre,

(SELECT ifnull(sum(clate.Cantidad),0)
FROM  Cotizaciones co inner join ClaveTemporal clate on co.Clave = clate.Clave
where 
((co.Estado = 0 and co.Entregada is not null)  
or (co.Estado = 2)
or (co.Estado = 1)
)
and clate.idProducto = $idp and co.Code like 'C%' and co.Fecha  <= '$FIN') v1,

*/
if (true) {
     $stockC = 0;
     $stockL = 0;
     $stockS = 0;
     $stockT = 0;     
$q = "select (select ifnull(sum(cantidad),0) from compras 
where idsucursal = 1 and idproducto = $idp and fecha <= '$FIN') c1 ,


(SELECT	ifnull(sum(cantidad),0) FROM Ventas v 
    inner join Cotizaciones c on v.idCotizacion = c.idCotizacion
    where v.idproducto = $idp and v.Fecha <= '$FIN'
    and v.CodeCotizacion like 'C%'  and c.Estado not in (4,7) and v.estado = 0) v1,
    
 (SELECT ifnull(sum(clate.Cantidad),0)
FROM  Cotizaciones co inner join ClaveTemporal clate on co.Clave = clate.Clave
where co.Estado in (4,7) and clate.idProducto = $idp and co.Code like 'C%' and co.Fecha  <= '$FIN') v1cre,

 
(SELECT ifnull(sum(ec.cantidad),0) FROM `envio_stock` es
inner join envio_claves ec on es.clave = ec.clave and ec.idProducto =$idp
where es.estado in ( 1,0) and es.fecha <= '$FIN' and es.desde like 'C%') e1,
(SELECT ifnull(sum(ec.cantidad),0) FROM `envio_stock` es
inner join envio_claves ec on es.clave = ec.clave and ec.idProducto = $idp
where es.estado = 1 and es.fecha <= '$FIN' and es.hasta like 'C%') r1,

(select ifnull(sum(cantidad),0) from compras 
where idsucursal = 2 and idproducto = $idp and fecha <= '$FIN') c2 ,

(SELECT	ifnull(sum(cantidad),0) FROM Ventas v 
    inner join Cotizaciones c on v.idCotizacion = c.idCotizacion
    where v.idproducto = $idp and v.Fecha <= '$FIN'
    and v.CodeCotizacion like 'L%' and c.Estado not in (4,7)  and v.estado = 0) v2,
    
    (SELECT ifnull(sum(clate.Cantidad),0)
FROM  Cotizaciones co inner join ClaveTemporal clate on co.Clave = clate.Clave
where co.Estado in (4,7) and clate.idProducto = $idp and co.Code like 'L%' and co.Fecha  <= '$FIN') v2cre,


(SELECT ifnull(sum(ec.cantidad),0) FROM `envio_stock` es
inner join envio_claves ec on es.clave = ec.clave and ec.idProducto = $idp
where es.estado in ( 1,0) and es.fecha <= '$FIN' and es.desde like 'L%') e2,
(SELECT ifnull(sum(ec.cantidad),0) FROM `envio_stock` es
inner join envio_claves ec on es.clave = ec.clave and ec.idProducto = $idp
where es.estado = 1 and es.fecha <= '$FIN' and es.hasta like 'L%') r2,

(select ifnull(sum(cantidad),0) from compras 
where idsucursal =3 and idproducto = $idp and fecha <= '$FIN') c3 ,

(SELECT	ifnull(sum(cantidad),0) FROM Ventas v 
    inner join Cotizaciones c on v.idCotizacion = c.idCotizacion
    where v.idproducto = $idp and v.Fecha <= '$FIN'
    and v.CodeCotizacion like 'S%' and c.Estado not in (4,7) and v.estado = 0) v3,

    (SELECT ifnull(sum(clate.Cantidad),0)
FROM  Cotizaciones co inner join ClaveTemporal clate on co.Clave = clate.Clave
where co.Estado in (4,7) and clate.idProducto = $idp and co.Code like 'S%' and co.Fecha  <= '$FIN') v3cre,

(SELECT ifnull(sum(ec.cantidad),0) FROM `envio_stock` es
inner join envio_claves ec on es.clave = ec.clave and ec.idProducto = $idp
where es.estado in ( 1,0) and es.fecha <= '$FIN' and es.desde like 'S%') e3,
(SELECT ifnull(sum(ec.cantidad),0) FROM `envio_stock` es
inner join envio_claves ec on es.clave = ec.clave and ec.idProducto = $idp
where es.estado = 1 and es.fecha <= '$FIN' and es.hasta like 'S%') r3,

(select ifnull(sum(cantidad),0) from compras 
where idsucursal = 4 and idproducto = $idp and fecha <= '$FIN') c4 ,

(SELECT	ifnull(sum(cantidad),0) FROM Ventas v 
    inner join Cotizaciones c on v.idCotizacion = c.idCotizacion
    where v.idproducto = $idp and v.Fecha <= '$FIN'
    and v.CodeCotizacion like 'T%'  and c.Estado not in (4,7) and v.estado = 0) v4,

    (SELECT ifnull(sum(clate.Cantidad),0)
FROM  Cotizaciones co inner join ClaveTemporal clate on co.Clave = clate.Clave
where co.Estado in (4,7) and clate.idProducto = $idp and co.Code like 'T%' and co.Fecha  <= '$FIN') v4cre,

(SELECT ifnull(sum(ec.cantidad),0) FROM `envio_stock` es
inner join envio_claves ec on es.clave = ec.clave and ec.idProducto = $idp
where es.estado in ( 1,0) and es.fecha <= '$FIN' and es.desde like 'T%') e4,
(SELECT ifnull(sum(ec.cantidad),0) FROM `envio_stock` es
inner join envio_claves ec on es.clave = ec.clave and ec.idProducto = $idp
where es.estado = 1 and es.fecha <= '$FIN' and es.hasta like 'T%') r4
";



    $rowStock = mysqli_query($MySQLi, $q   );
    while ($filaStock = mysqli_fetch_assoc($rowStock)) {        
        $stockc1 = $filaStock['c1'];
        $stockv1 = $filaStock['v1'] + $filaStock['v1cre'];
        $stocke1 = $filaStock['e1'];
        $stockr1 = $filaStock['r1'];
        $stockc2 = $filaStock['c2'];
        $stockv2 = $filaStock['v2cre'] ;
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
    //
    

// old v1
//(SELECT	ifnull(sum(cantidad),0) FROM Ventas v 
    //inner join Cotizaciones c on v.idCotizacion = c.idCotizacion
    //where v.idproducto = $idp and v.Fecha  between '$INICIO' and '$FIN'
    //and v.CodeCotizacion like 'C%') v1,
$q = "select (select ifnull(sum(cantidad),0) from compras 
where idsucursal = 1 and idproducto = $idp and fecha between '$INICIO' and '$FIN') c1 ,

(select ifnull(sum((cantidad)),0) from compras 
where idsucursal = 1 and idproducto = $idp and fecha between '$INICIO' and '$FIN' and cantidad > 0) c1Ing ,

(select ifnull(sum((abs(cantidad))),0) from compras 
where idsucursal = 1 and idproducto = $idp and fecha between '$INICIO' and '$FIN' and cantidad < 0) c1Egr ,


(SELECT	ifnull(sum(cantidad),0) FROM Ventas v 
    inner join Cotizaciones c on v.idCotizacion = c.idCotizacion
    where v.idproducto = $idp and v.Fecha between '$INICIO' and '$FIN'
    and v.CodeCotizacion like 'C%' and c.Estado not in (4,7) and v.estado = 0) v1,
    
    (SELECT ifnull(sum(clate.Cantidad),0)
FROM  Cotizaciones co inner join ClaveTemporal clate on co.Clave = clate.Clave
where co.Estado in (4,7) and clate.idProducto = $idp and co.Code like 'C%' and co.Fecha between '$INICIO' and '$FIN') v1cre,

    
(SELECT ifnull(sum(ec.cantidad),0) FROM `envio_stock` es
inner join envio_claves ec on es.clave = ec.clave and ec.idProducto =$idp
where es.estado = 1 and es.fecha  between '$INICIO' and '$FIN' and es.desde like 'C%') e1,
(SELECT ifnull(sum(ec.cantidad),0) FROM `envio_stock` es
inner join envio_claves ec on es.clave = ec.clave and ec.idProducto = $idp
where es.estado = 1 and es.fecha  between '$INICIO' and '$FIN' and es.hasta like 'C%') r1,

(select ifnull(sum(cantidad),0) from compras 
where idsucursal = 2 and idproducto = $idp and fecha  between '$INICIO' and '$FIN') c2 ,

(select ifnull(sum((cantidad)),0) from compras 
where idsucursal = 2 and idproducto = $idp and fecha between '$INICIO' and '$FIN' and cantidad > 0) c2Ing ,

(select ifnull(sum((cantidad)),0) from compras 
where idsucursal = 2 and idproducto = $idp and fecha between '$INICIO' and '$FIN' and cantidad < 0) c2Egr ,


(SELECT	ifnull(sum(cantidad),0) FROM Ventas v 
    inner join Cotizaciones c on v.idCotizacion = c.idCotizacion
    where v.idproducto = $idp and v.Fecha between '$INICIO' and '$FIN'
    and v.CodeCotizacion like 'L%'  and c.Estado not in (4,7) and v.estado = 0) v2,
    
    (SELECT ifnull(sum(clate.Cantidad),0)
FROM  Cotizaciones co inner join ClaveTemporal clate on co.Clave = clate.Clave
where co.Estado in (4,7) and clate.idProducto = $idp and co.Code like 'L%' and co.Fecha between '$INICIO' and '$FIN') v2cre,

(SELECT ifnull(sum(ec.cantidad),0) FROM `envio_stock` es
inner join envio_claves ec on es.clave = ec.clave and ec.idProducto = $idp
where es.estado = 1 and es.fecha  between '$INICIO' and '$FIN' and es.desde like 'L%') e2,
(SELECT ifnull(sum(ec.cantidad),0) FROM `envio_stock` es
inner join envio_claves ec on es.clave = ec.clave and ec.idProducto = $idp
where es.estado = 1 and es.fecha  between '$INICIO' and '$FIN' and es.hasta like 'L%') r2,

(select ifnull(sum(cantidad),0) from compras 
where idsucursal =3 and idproducto = $idp and fecha  between '$INICIO' and '$FIN') c3 ,

(select ifnull(sum((cantidad)),0) from compras 
where idsucursal = 3 and idproducto = $idp and fecha between '$INICIO' and '$FIN' and cantidad > 0) c3Ing ,

(select ifnull(sum((cantidad)),0) from compras 
where idsucursal = 3 and idproducto = $idp and fecha between '$INICIO' and '$FIN' and cantidad < 0) c3Egr ,

(SELECT	ifnull(sum(cantidad),0) FROM Ventas v 
    inner join Cotizaciones c on v.idCotizacion = c.idCotizacion
    where v.idproducto = $idp and v.Fecha between '$INICIO' and '$FIN'
    and v.CodeCotizacion like 'S%' and c.Estado not in (4,7) and v.estado = 0) v3,
    

        (SELECT ifnull(sum(clate.Cantidad),0)
FROM  Cotizaciones co inner join ClaveTemporal clate on co.Clave = clate.Clave
where co.Estado in (4,7) and clate.idProducto = $idp and co.Code like 'S%' and co.Fecha between '$INICIO' and '$FIN') v3cre,
(SELECT ifnull(sum(ec.cantidad),0) FROM `envio_stock` es
inner join envio_claves ec on es.clave = ec.clave and ec.idProducto = $idp
where es.estado = 1 and es.fecha  between '$INICIO' and '$FIN' and es.desde like 'S%') e3,
(SELECT ifnull(sum(ec.cantidad),0) FROM `envio_stock` es
inner join envio_claves ec on es.clave = ec.clave and ec.idProducto = $idp
where es.estado = 1 and es.fecha  between '$INICIO' and '$FIN' and es.hasta like 'S%') r3,

(select ifnull(sum(cantidad),0) from compras 
where idsucursal = 4 and idproducto = $idp and fecha  between '$INICIO' and '$FIN') c4 ,

(select ifnull(sum((cantidad)),0) from compras 
where idsucursal = 4 and idproducto = $idp and fecha between '$INICIO' and '$FIN' and cantidad > 0) c4Ing ,

(select ifnull(sum((cantidad)),0) from compras 
where idsucursal = 4 and idproducto = $idp and fecha between '$INICIO' and '$FIN' and cantidad < 0) c4Egr ,


(SELECT	ifnull(sum(cantidad),0) FROM Ventas v 
    inner join Cotizaciones c on v.idCotizacion = c.idCotizacion
    where v.idproducto = $idp and v.Fecha between '$INICIO' and '$FIN'
    and v.CodeCotizacion like 'T%' and c.Estado not in (4,7) and v.estado = 0) v4,
    

    (SELECT ifnull(sum(clate.Cantidad),0)
FROM  Cotizaciones co inner join ClaveTemporal clate on co.Clave = clate.Clave
where co.Estado in (4,7) and clate.idProducto = $idp and co.Code like 'T%' and co.Fecha between '$INICIO' and '$FIN') v4cre,    

(SELECT ifnull(sum(ec.cantidad),0) FROM `envio_stock` es
inner join envio_claves ec on es.clave = ec.clave and ec.idProducto = $idp
where es.estado = 1 and es.fecha  between '$INICIO' and '$FIN' and es.desde like 'T%') e4,
(SELECT ifnull(sum(ec.cantidad),0) FROM `envio_stock` es
inner join envio_claves ec on es.clave = ec.clave and ec.idProducto = $idp
where es.estado = 1 and es.fecha  between '$INICIO' and '$FIN' and es.hasta like 'T%') r4
";
    $rowStock = mysqli_query($MySQLi, $q   );
    while ($filaStock = mysqli_fetch_assoc($rowStock)) {        
        $stockc1b = $filaStock['c1'];
        
        $stockc1Ingb = $filaStock['c1Ing'];
        $stockc2Ingb = $filaStock['c2Ing'];        
        $stockc3Ingb = $filaStock['c3Ing'];        
        $stockc4Ingb = $filaStock['c4Ing'];        

        $stockc1Egrb = $filaStock['c1Egr'];
        $stockc2Egrb = $filaStock['c2Egr'];        
        $stockc3Egrb = $filaStock['c3Egr'];        
        $stockc4Egrb = $filaStock['c4Egr'];                
        
        $stockv1b = $filaStock['v1'] + $filaStock['v1cre'];
        $stocke1b = $filaStock['e1'];
        $stockr1b = $filaStock['r1'];
        $stockc2b = $filaStock['c2'];
        $stockv2b = $filaStock['v2'] + $filaStock['v2cre'];
        $stocke2b = $filaStock['e2'];
        $stockr2b = $filaStock['r2'];
        $stockc3b = $filaStock['c3'];
        $stockv3b = $filaStock['v3'] + $filaStock['v3cre'];
        $stocke3b = $filaStock['e3'];
        $stockr3b = $filaStock['r3'];        
        $stockc4b = $filaStock['c4'];
        $stockv4b = $filaStock['v4'] + $filaStock['v4cre'];
        $stocke4b = $filaStock['e4'];
        $stockr4b = $filaStock['r4'];                
        break;
    }

$stockFin1 = $stockc1b-$stockv1b-$stocke1b+$stockr1b;
$stockFin2 = $stockc2b-$stockv2b-$stocke2b+$stockr2b;
$stockFin3 = $stockc3b-$stockv3b-$stocke3b+$stockr3b;
$stockFin4 = $stockc4b-$stockv4b-$stocke4b+$stockr4b;
$stockFin = $stockIni + $stockFin1 + $stockFin2 + $stockFin3 + $stockFin4;
            echo "<tr>";                        
            //echo "<td>" . $idp . "</td>";                      
            echo "<td>" . $pr . "</td>";
            echo "<td>" . $prma . "</td>";
            echo "<td>" . $prmo . "</td>";
            echo "<td style='text-align:center;background-color: #FFF2CC'>" . $stockIni1 . "</td>";                 
            echo "<td>" . $filaP['PrecioCB'] . "</td>";
            echo("<td style='text-align:center;background-color: #DDEBF7'>" . $stockv2b  . "</td>");                            
            echo "<td>" . $filaP['PrecioLP'] . "</td>";
            echo("<td style='text-align:center;background-color: #E2EFDA'>" . $stockIni3 . "</td>");                            
            echo "<td>" . $filaP['PrecioSC'] . "</td>";
            echo("<td style='text-align:center;background-color: #FCE4D6'>" . $stockIni4 . "</td>");                            
            echo "<td>" . $filaP['PrecioTJ'] . "</td>";
            $color = $stockIni <= 5 ? "danger" : "default";
            echo "<td style='text-align:center;background-color: #FFA500'>" . "<button class='btn btn-xs btn-$color form-control btnadd1' data-id='" . $idp . "' >" . $stockIni . "</button></td>";               
            
    
            echo "<td style='text-align:center;background-color: #FFF2CC'>" .    (- ($stockv1b+$stocke1b) + (-$stockc1Egrb))  . "</td>";
            echo "<td style='text-align:center;background-color: #FFF2CC'>" . ($stockr1b            +   $stockc1Ingb  ) . "</td>";            
            
            $ext = (- ($stockv2b+$stocke2b) + (-$stockc2Egrb));
            if ($ext == 1) $ext = -1;
            echo "<td  style='text-align:center;background-color:#DDEBF7'>" .    $ext . "</td>";
            echo "<td style='text-align:center;background-color: #DDEBF7'>" . ($stockr2b            +   $stockc2Ingb ) . "</td>";            
            
            echo "<td  style='text-align:center;background-color:#E2EFDA'>" .    (- ($stockv3b+$stocke3b) + (-$stockc3Egrb)) . "</td>";
            echo "<td style='text-align:center;background-color: #E2EFDA'>" . ($stockr3b            +   $stockc3Ingb ) . "</td>";            
            
            echo "<td style='text-align:center;background-color: #FCE4D6'>" .    (- ($stockv4b+$stocke4b) + (-$stockc4Egrb)) . "</td>";
            echo "<td style='text-align:center;background-color: #FCE4D6'>" . ($stockr4b            +   $stockc4Ingb ) . "</td>";                        

echo "<td>";

echo ('<img height="50" src="Productos/');
echo ($filaP['imagen']);
echo ('">');

//if  (isset($datosUser['Cargo'][0]) && ($datosUser['Cargo'][0] == 'A' )) 
echo"<button title=\"Editar Producto\" id=\"" .  $idp . "\" class=\"btn btn-xs btn-success editProdExistente\"><i class=\"ion-ios-brush\" style=\"font-size: 15px\"></i></button>&nbsp;";

if  (isset($datosUser['Cargo'][0]) && ($datosUser['Cargo'][0] == 'A' )) echo"<button title=\"Borrar Producto\" data-id=\"" .  $idp . "\" class=\"btn btn-xs  btn-danger btnBorrarProd\"><i class=\"ion-ios-trash\" style=\"font-size: 15px\"></i></button>&nbsp;";
                                                        
            echo "</td></tr>";
}    

}


                                                
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                                <!-- <ul class="pagination">
											<li class="page-item disabled">
												<a href="#" class="page-link">«</a>
											</li>											
											<?php
                                            // for ($i=1; $i <= $TotalPaginas; $i++) {
                                            // 	echo '<li class="page-item active">';
                                            // 	echo '<a href="?root=productos&Pagina='.$i.'"  class="page-link">'.$i.'</a>&nbsp;</li>';
                                            // }
                                            ?>
											<li class="page-item"><a href="#" class="page-link">»</a></li>											
										</ul>										
																			</div> -->
                                <!-- end panel-body -->
                            </div>
                        </div>
                    </div>
                </div>
                <a href="javascript:;" class="btn btn-icon btn-circle btn-primary btn-scroll-to-top fade" data-click="scroll-top"><i class="fa fa-angle-up"></i></a>
                <?php include 'php/footer.php'; ?>
            </div>
            <?php include 'php/script_productos.php'; ?>
            <script type="text/javascript">
                $(".subFunction").click(function() {
                    alert("Hola");
                });
            </script>
    </body>

    </html>
<?php include 'php/fun_productos.php';
} else { ?>
    <script type="text/javascript">
        location.replace("?root=404");
    </script><?php
            }
                ?>
                
                <script>
        $(document).ready(function () {
            
               $("#add11").on("hidden.bs.modal", function (e) {
                $("#fecha1").val("");
                $("#cantidad1").val("");
                $("#notas1").val("");
                $("#lblmsg1").text("");
                console.log("cerrrar");
            });

            function addc(sucursal) {
                console.log("ok 1");
                var p1 = $("#fecha1").val();
                var p2 = $("#cantidad1").val();
                var p3 = $("#notas1").val();
                var p4 = $("#idProducto_").val();
                var p5 = $("#sucursal1").val();;
                var p6 = $("#idUser_").val();
                if (p1 == "") {
                    $("#lblmsg1").text("Ingrese una fecha actual válida.");
                    $("#fecha1").focus();
                }
                else if (isNaN(p2) || !isFinite(p2) || (p2 == 0)) {
                    $("#lblmsg1").text("Ingrese un monto válido.");
                    $("#cantidad1").focus();
                }
                else if (p3 == "") {
                    $("#lblmsg1").text("Ingrese el detalle.");
                    $("#notas1").focus();
                }
                else if (confirm('¿Seguro de agregar?')) {
                    
                    console.log('all.php?f=' + p1 + '&c=' + p2 + '&n=' + p3 + '&p=' + p4 + '&s=' + p5 + '&u=' + p6);
                    
                    $("#btadd11").prop("disabled",true);
                    $.ajax({
                        type: "GET", //we are using GET method to get all record from the server
                        url: 'all.php?f=' + p1 + '&c=' + p2 + '&n=' + p3 + '&p=' + p4 + '&s=' + p5 + '&u=' + p6, // get the route value
                        success: function (response) {//once the request successfully process to the server side it will return result here
                            location.reload(true);
                        }
                    });
                }
        }
        
            $(document).on("click", "button.btadd11" , function($this) {
                addc(1);
            });
            
            $(document).on("click", "button.cancelarEditProducto2" , function($this) {
                location.reload(true);
            });
            
            $(document).on("click", "button.btnadd1" , function($this) {
                var idp = $(this).data('id');
                $.ajax({
                        type: "GET", //we are using GET method to get all record from the server
                        url: 'all.php?p=' + idp , // get the route value
                        success: function (response) {//once the request successfully process to the server side it will return result here
                            $('#add1').modal('show')
                            response = JSON.parse(response);
                            var html = "";
                            $("#tbcompras tbody").empty();
                            // Check if there is available records
                            var t1 = 0;
                            var t2 = 0;
                            var t3 = 0;
                            var t4 = 0;
                            var t = 0;
                            var b1 = b2 = "";
                            $.each(response, function(key,value) {
                                if ((value.fecha >= $("#inicio").val()) && (value.fecha <= $("#fin").val())) {
                                    b1 = "<b>";
                                    b2 = "</b>";
                                }
                                else {
                                    b1 = "";
                                    b2 = "";
                                }
                                html = '<tr>';
                                html += "<td>" + b1 + value.fecha  + b2 + "</td>";
                                html += "<td>" + b1 + value.Correo + b2 + "</td>";
                                html += "<td>" + b1 + value.suc1   + b2 +  "</td>";
                                html += "<td>" + b1 + value.suc2   + b2 +  "</td>";
                                html += "<td>" + b1 + value.suc3   + b2 +  "</td>";
                                html += "<td>" + b1 + value.suc4   + b2 +  "</td>";                                
                                html += "<td>" + b1 + value.tipo + '. ' + value.detalles+  b2 + "</td>";
                                html += '</tr>';
                                $("#tbcompras").append(html);
                                t1 = t1 + parseInt(value.suc1);
                                t2 = t2 + parseInt(value.suc2);
                                t3 = t3 + parseInt(value.suc3);
                                t4 = t4 + parseInt(value.suc4);
                            });
                            t = (t1+t2+t3+t4);
                            html = '<tr>';
                                html += "<td>" + "</td>";
                                html += "<td>" + "TOTAL"+ "</td>";
                                html += "<td>" + t1+ "</td>";
                                html += "<td>" + t2+ "</td>";
                                html += "<td>" + t3+ "</td>";
                                html += "<td>" + t4+ "</td>";                                
                                html += "<td>" + t+ "</td>";
                                html += '</tr>';
                                $("#tbcompras").append(html);
                        }
                    });
             
            });
            
            $(document).on("click", "button.btnBorrarProd" , function($this) {
                if (confirm('¿Seguro de borrar?')) {
                    var idProducto = $(this).data('id');
                    console.log("action=BorrarProductoLista&id=" + idProducto);
                     $.ajax({
                    url: 'do.php',
                    type: 'POST',
                    dataType: 'html',
                    data: "action=BorrarProductoLista&id="+idProducto,
                    })
                    .done(function(data) {
                    $(".respuesta").html(data);
                        location.reload();
                    }) 
                }
	        });
    
        });
    </script>