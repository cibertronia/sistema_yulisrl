<?php

    include_once __DIR__ . '/../includes/App/Models/Sucursal.php';
    include 'includes/conexion.php';
    include 'includes/date.class.php';

    use App\Models\Sucursal;

	mysqli_query($MySQLi,"SET lc_time_names= 'es_BO' ");
	$idUser 	=	$_SESSION['idUser'];
	$ConsltaUser=	mysqli_query($MySQLi,"SELECT * FROM Usuarios WHERE idUser='$idUser' ");
	$datosUser 	=	mysqli_fetch_assoc($ConsltaUser);
	$miCiudad 	=	$datosUser['Ciudad'];
    $sucursalModel = new Sucursal();
    $sucursales = $sucursalModel->all();
	if ($_SESSION['Rango'] == '2') { ?>
<!DOCTYPE html>
<html lang="es">

<head>
    <title>AGREGAR PRODUCTOS</title>
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
    <?php include 'php/loader.php'; ?>
    <div id="page-container" class="fade page-sidebar-fixed page-header-fixed">
        <?php
						include 'php/top_menu.php';
						include 'php/left_menu_nvoProducto.php';
					?>
        <div id="content" class="content">
            <div class="respuesta"></div>

            <!-- 	NUEVO PRODUCTO	 -->
            <div class="row">
                
                <div class="col">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <h1 class="panel-title">REGISTRAR NUEVO PRODUCTO</h1>
                            <!-- <button class="btn btn-xs btn-danger cancelarRegNewProducto">CANCELAR</button> -->

                            <?php
			                if ($_SESSION['Rango']=='2') { ?>

                            <div class="panel-heading-btn">
                            <h4 class="panel-title">REGISTRAR CON EXCEL.CSV&nbsp;&nbsp;</h4>
                                <form method="POST"
                                    enctype="multipart/form-data" id="filesForm">
                                    <span class="btn btn-warning fileinput-button">
                                        <i class="fa fa-file"><span id="imgName">&nbsp; SELECCIONAR ARCHIVO</span>
                                        </i>
                                        <input type="file" name="dataCliente" id="file-input" class="form-control"
                                            accept=".csv" onChange="onLoadImage(event.target.files)" />

                                    </span>

                            </div>
                            &nbsp;
                            <div class="panel-heading-btn">

                                <button type="button" name="subir" onclick="uploadExcelProductosReales()"
                                    class="btn btn-info form-control fa fa-upload buttonexcel"> CARGAR SUBIR .CSV</button>


                                </form>

                            </div>

                            <?php } ?>



                        </div>
                        <div class="panel-body">
                            <form enctype="multipart/form-data" method="POST" action="ap.php"
                                data-parsley-validate="true">
                                <div class="row">
                                    <div class="col mt-11"></div>
                                    <div>
                                    <a href="./includes/plantillasCSV/plantillaSubirProductosReales.csv"><i class="btn btn-silver fa fa-download" style="font-size: 11px">&nbsp;&nbsp;Descargar Plantilla.CSV</i>
						            </a>
                                    </div>
                                </div>
                                <div class="row text-center">
                                    <div class="col">
                                        <div id="imgx"></div>
                                    </div>
                                    <div class="col">
                                        <span class="btn btn-primary fileinput-button mt-3">
                                            <i class="fa fa-plus"></i>
                                            <span>Agregar imagen</span>
                                            <input type="file" name="imagen" id="img_file"
                                                accept="image/png, image/jpeg, image/jpg" data-parsley-required="true">
                                        </span>
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col">
                                        <label for="ProdNombre">Nombre del producto</label>
                                        <input type="hidden" name="action" value="RegistrarNuevoProducto">
                                        <input type="text" name="Producto" id="ProdNombre" class="form-control"
                                            placeholder="Producto" data-parsley-required="true">
                                        <div class="text-center text-danger d-none emptyProdNombre">Campo producto está
                                            vacío</div>
                                    </div>
                                    <div class="col">
                                        <label for="ProdMarca">Marca del producto</label>
                                        <input type="text" name="Marca" id="ProdMarca" class="form-control"
                                            placeholder="Marca" data-parsley-required="true">
                                        <div class="text-center text-danger d-none emptyProdMarca">Campo marca está
                                            vacío</div>
                                    </div>
                                    <div class="col">
                                        <label for="ProdModelo">Modelo del producto</label>
                                        <input type="text" name="Modelo" id="ProdModelo" class="form-control"
                                            placeholder="Modelo" data-parsley-required="true">
                                        <div class="text-center text-danger d-none emptyProdModelo">Campo modelo está
                                            vacío</div>
                                    </div>
                                </div>
                                <div class="row  ">
                                    <div class="col ">
                                        <label for="ProdHomo">Homologación Producto</label>
                                        <select   name="ProdHomo" id="ProdHomo" class="form-control" required='true'>


                                            <option value="61182">SERVICIOS DE COMERCIO AL POR MAYOR DE OTROS EQUIPOS DE
                                                TRANSPORTE, EXCEPTO BICICLETAS, EXCEPTO LOS
                                                PRESTADOS A COMISIÓN O POR CONTRATO</option>

                                            <option value="61183">SERVICIOS DE COMERCIO AL POR MAYOR DE MAQUINARIA Y
                                                EQUIPO DE OFICINA, INCLUIDO EL MOBILIARIO DE
                                                OFICINA, EXCEPTO LOS PRESTADOS A COMISIÓN O POR CONTRATO</option>

                                            <option value="61184">SERVICIOS DE COMERCIO AL POR MAYOR DE COMPUTADORES Y
                                                DE PAQUETES DE SOFTWARE, EXCEPTO LOS
                                                PRESTADOS A COMISIÓN O POR CONTRATO</option>

                                            <option value="61185">SERVICIOS DE COMERCIO AL POR MAYOR DE EQUIPOS
                                                ELECTRÓNICOS Y DE TELECOMUNICACIONES Y SUS PARTES,
                                                EXCEPTO A COMISIÓN O POR CONTRATO</option>

                                            <option value="61186">SERVICIOS DE COMERCIO AL POR MAYOR DE MAQUINARIA Y
                                                EQUIPO PARA LA AGRICULTURA, CÉSPED Y JARDÍN,
                                                INCLUIDOS LOS TRACTORES, EXCEPTO LOS PRESTADOS A COMISIÓN O POR CONTRATO
                                            </option>

                                            <option value="48321">LENTES OBJETIVOS PARA CÁMARAS, PROYECTORES,
                                                AMPLIADORES O REDUCTORES FOTOGRÁFICOS</option>

                                            <option value="61187">SERVICIOS DE COMERCIO AL POR MAYOR DE MAQUINARIA Y
                                                EQUIPO PARA LA MINERÍA, CONSTRUCCIÓN E
                                                INGENIERÍA CIVIL, EXCEPTO LOS PRESTADOS A COMISIÓN O POR CONTRATO
                                            </option>

                                            <option value="61188">SERVICIOS DE COMERCIO AL POR MAYOR DE OTRAS
                                                MAQUINARIAS Y EQUIPOS INDUSTRIALES ESPECÍFICOS Y
                                                SUMINISTROS OPERATIVOS RELACIONADOS, EXCEPTO LOS PRESTADOS A COMISIÓN O
                                                POR CONTRATO</option>

                                            <option value="61189">SERVICIOS DE COMERCIO AL POR MAYOR DE OTRAS
                                                MAQUINARIAS Y EQUIPOS N.C.P., EXCEPTO LOS PRESTADOS A
                                                COMISIÓN O POR CONTRATO</option>

                                            <option value="99100">OTROS PRODUCTOS O SERVICIOS ALCANZADOS POR EL IVA
                                            </option>

                                            <option value="61182">SERVICIOS DE COMERCIO AL POR MAYOR DE OTROS EQUIPOS DE
                                                TRANSPORTE, EXCEPTO BICICLETAS, EXCEPTO LOS
                                                PRESTADOS A COMISIÓN O POR CONTRATO IMPORTADO</option>

                                            <option value="61183">SERVICIOS DE COMERCIO AL POR MAYOR DE MAQUINARIA Y
                                                EQUIPO DE OFICINA, INCLUIDO EL MOBILIARIO DE
                                                OFICINA, EXCEPTO LOS PRESTADOS A COMISIÓN O POR CONTRATO IMPORTADO
                                            </option>

                                            <option value="61184">SERVICIOS DE COMERCIO AL POR MAYOR DE COMPUTADORES Y
                                                DE PAQUETES DE SOFTWARE, EXCEPTO LOS
                                                PRESTADOS A COMISIÓN O POR CONTRATO IMPORTADO</option>

                                            <option value="61185">SERVICIOS DE COMERCIO AL POR MAYOR DE EQUIPOS
                                                ELECTRÓNICOS Y DE TELECOMUNICACIONES Y SUS PARTES,
                                                EXCEPTO A COMISIÓN O POR CONTRATO IMPORTADO</option>

                                            <option value="61186">SERVICIOS DE COMERCIO AL POR MAYOR DE MAQUINARIA Y
                                                EQUIPO PARA LA AGRICULTURA, CÉSPED Y JARDÍN,
                                                INCLUIDOS LOS TRACTORES, EXCEPTO LOS PRESTADOS A COMISIÓN O POR CONTRATO
                                                IMPORTADO</option>

                                            <option value="61187">SERVICIOS DE COMERCIO AL POR MAYOR DE MAQUINARIA Y
                                                EQUIPO PARA LA MINERÍA, CONSTRUCCIÓN E
                                                INGENIERÍA CIVIL, EXCEPTO LOS PRESTADOS A COMISIÓN O POR CONTRATO
                                                IMPORTADO</option>

                                            <option value="61188">SERVICIOS DE COMERCIO AL POR MAYOR DE OTRAS
                                                MAQUINARIAS Y EQUIPOS INDUSTRIALES ESPECÍFICOS Y
                                                SUMINISTROS OPERATIVOS RELACIONADOS, EXCEPTO LOS PRESTADOS A COMISIÓN O
                                                POR CONTRATO IMPORTADO</option>

                                            <option value="61189">SERVICIOS DE COMERCIO AL POR MAYOR DE OTRAS
                                                MAQUINARIAS Y EQUIPOS N.C.P., EXCEPTO LOS PRESTADOS A
                                                COMISIÓN O POR CONTRATO IMPORTADO</option>

                                            <option value="99100">OTROS PRODUCTOS O SERVICIOS ALCANZADOS POR EL IVA
                                                IMPORTADO</option>

                                            <option value="42921">HERRAMIENTAS DE MANO (INCLUSO DEL TIPO UTILIZADO EN LA
                                                AGRICULTURA, HORTICULTURA O SILVICULTURA,
                                                SIERRAS DE MANO, LIMAS, ALICATES, CIZALLAS PARA METALES, LLAVES DE
                                                HORQUILLA MANUALES, SOPLETES DE SOLDAR Y
                                                ABRAZADERAS)</option>

                                            <option value="42922">PIEZAS INTERCAMBIABLES PARA HERRAMIENTAS DE MANO O
                                                PARA MÁQUINAS HERRAMIENTAS, INCLUSO MATRICES DE
                                                EXTRUSIÓN O DIBUJO EN METAL, Y HERRAMIENTAS PARA PERFORACIÓN DE ROCAS O
                                                TIERRA; CUCHILLOS PARA MÁQUINAS, CHAPAS,
                                                VARILLAS, PUNTAS Y ARTÍCULOS SIMILARES PARA HERRAMIENTAS, SIN MONTAR, DE
                                                CARBUROS METÁLICOS SINTERIZADOS O
                                                ALEACIONES METALOCERÁMICAS (CERMET)</option>

                                            <option value="42931">CISTERNAS, BARRILES, TAMBORES, BIDONES, CAJAS Y
                                                RECIPIENTES SIMILARES (EXCEPTO PARA GAS COMPRIMIDO
                                                O LICUADO) DE HIERRO, ACERO O ALUMINIO, DE UNA CAPACIDAD NO SUPERIOR A
                                                300 LITROS, SIN DISPOSITIVOS MECÁNICOS O
                                                TÉRMICOS</option>

                                            <option value="42941">ALAMBRE RETORCIDO, CUERDAS, CABLES, TRENZAS, ESLINGAS
                                                Y ARTÍCULOS SIMILARES, DE HIERRO O ACERO,
                                                SIN AISLACIÓN ELÉCTRICA</option>

                                            <option value="42944">CLAVOS, TACHUELAS, CORCHETES (EXCEPTO CORCHETES EN
                                                TIRAS), TORNILLOS, PERNOS, TUERCAS, GANCHOS DE
                                                TORNILLO, REMACHES, PASADORES, CLAVIJAS (SUJETADOR), GOLILLAS Y
                                                ARTÍCULOS SIMILARES, DE HIERRO, ACERO, COBRE O
                                                ALUMINIO</option>

                                            <option value="42950">ALAMBRE, VARILLAS, TUBOS, CHAPAS, ELECTRODOS Y
                                                ARTÍCULOS SIMILARES, DE METALES COMUNES O DE
                                                CARBUROS METÁLICOS, CON REVESTIMIENTO O CON NÚCLEO DE MATERIAL FUNDENTE,
                                                DEL TIPO UTILIZADO PARA SOLDADURA BLANDA,
                                                SOLDADURA FUERTE O SOLDADURA AUTÓGENA O PARA DEPOSICIÓN DE METALES O DE
                                                CARBUROS METÁLICOS; ALAMBRE Y VARILLAS, DE
                                                POLVO AGLOMERADO DE METALES COMUNES, PARA METALIZACIÓN POR ASPERSIÓN
                                            </option>

                                            <option value="42992">CANDADOS Y CERRADURAS, DE METALES COMUNES; CIERRES Y
                                                ARMAZONES CON CIERRES Y CERRADURAS, DE
                                                METALES COMUNES; LLAVES Y PIEZAS PARA ESTOS ARTÍCULOS, DE METALES
                                                COMUNES; ACCESORIOS DE METALES COMUNES PARA
                                                MUEBLES, PUERTAS, TALABARTERÍA Y FINES SIMILARES</option>

                                            <option value="43211">MÁQUINAS Y MOTORES DE FUERZA HIDRÁULICA Y ACCIÓN
                                                LINEAL Y MÁQUINAS Y MOTORES DE POTENCIA NEUMÁTICA
                                                Y ACCIÓN LINEAL (CILINDROS)</option>

                                            <option value="43219">OTRAS MÁQUINAS Y MOTORES DE FUERZA HIDRÁULICA O DE
                                                POTENCIA NEUMÁTICA</option>

                                            <option value="43220">BOMBAS PARA LÍQUIDOS; ELEVADORES DE LÍQUIDOS</option>

                                            <option value="43230">BOMBAS DE AIRE O DE VACÍO; COMPRESORES DE AIRE U OTROS
                                                GASES</option>

                                            <option value="43240">GRIFOS, LLAVES, VÁLVULAS Y ACCESORIOS SIMILARES PARA
                                                TUBERÍAS, CALDERAS, ESTANQUES, CUBAS Y
                                                RECIPIENTES SIMILARES</option>

                                            <option value="43252">PARTES Y PIEZAS PARA LOS PRODUCTOS DE LA SUBCLASE
                                                43220</option>

                                            <option value="48315">DISPOSITIVOS DE CRISTAL LÍQUIDO N.C.P.; APARATOS
                                                LÁSER, EXCEPTO DIODOS DE LÁSER; OTROS APARATOS E
                                                INSTRUMENTOS ÓPTICOS N.C.P.</option>

                                            <option value="43253">PARTES Y PIEZAS PARA LOS PRODUCTOS DE LA SUBCLASE
                                                43230; PARTES Y PIEZAS PARA CAMPANAS DE
                                                VENTILACIÓN O RECICLAJE DE AIRE CON VENTILADOR INCORPORADO</option>

                                            <option value="43310">RODAMIENTOS DE BOLAS O RODILLOS</option>

                                            <option value="43320">ÁRBOLES DE TRANSMISIÓN Y MANIVELAS; CAJAS DE
                                                RODAMIENTOS Y RODAMIENTOS SIMPLES PARA EJES;
                                                ENGRANAJES Y TRENES DE ENGRANAJE; TORNILLOS DE BOLAS O DE ROSCA, CAJAS
                                                DE ENGRANAJES Y OTROS REDUCTORES DE
                                                VELOCIDAD, VOLANTES Y POLEAS; EMBRAGUES Y ACOPLAMIENTO DE ÁRBOLES,
                                                CADENAS DE ESLABONES ARTICULADOS</option>

                                            <option value="43520">GRÚAS DE BRAZO MÓVIL; GRÚAS; BASTIDORES ELEVADORES
                                                MÓVILES, CAMIONES DE PÓRTICO ALTO Y CAMIONES DE
                                                FAENA PROVISTOS DE GRÚAS</option>

                                            <option value="43915">FILTROS DE ACEITE, FILTROS DE GASOLINA Y FILTROS DE
                                                ENTRADA DE AIRE PARA MOTORES DE COMBUSTIÓN
                                                INTERNA</option>

                                            <option value="44121">CORTADORES DE CÉSPED PARA JARDINES, PARQUES O TERRENOS
                                                DE DEPORTE</option>

                                            <option value="44123">OTRAS MÁQUINAS CORTADORAS DE CÉSPED, INCLUYENDO LAS
                                                BARRAS DE CORTE PARA MONTAR SOBRE TRACTORES
                                            </option>

                                            <option value="44129">OTRAS MÁQUINAS COSECHADORAS Y TRILLADORAS, N.C.P.;
                                                PARTES Y PIEZAS PARA MÁQUINAS COSECHADORAS,
                                                TRILLADORAS Y CLASIFICADORAS</option>

                                            <option value="44149">OTROS TRACTORES AGRÍCOLAS</option>

                                            <option value="44150">APARATOS MECÁNICOS PARA PROYECTAR, DISPERSAR O
                                                PULVERIZAR LÍQUIDOS O POLVOS PARA LA AGRICULTURA U
                                                HORTICULTURA</option>

                                            <option value="44199">PARTES Y PIEZAS DE MAQUINARIAS AGRÍCOLAS N.C.P.
                                            </option>

                                            <option value="44214">MÁQUINAS HERRAMIENTAS PARA PERFORAR, TALADRAR O FRESAR
                                                METALES</option>

                                            <option value="44216">MÁQUINAS HERRAMIENTAS PARA DESBARBAR, AFILAR,
                                                RECTIFICAR, LAPIDAR, PULIR O DAR OTRO ACABADO A
                                                METALES, CARBUROS METÁLICOS SINTERIZADOS O MATERIALES METALOCERÁMICOS
                                                MEDIANTE PIEDRAS DE AMOLAR, ABRASIVOS O
                                                PRODUCTOS PARA PULIR; MÁQUINAS HERRAMIENTAS PARA CEPILLAR, CONFORMAR,
                                                RANURAR, ESCARIAR, TALLAR, RECTIFICAR, ACABAR
                                                ENGRANAJES, ASERRAR ENGRANAJES O RECORTAR ENGRANAJES Y OTRAS MÁQUINAS
                                                QUE TRABAJAN POR REMOCIÓN DE METAL, CARBUROS
                                                METÁLICOS SINTERIZADOS O MATERIALES METALOCERÁMICOS N.C.P.</option>

                                            <option value="44217">MÁQUINAS HERRAMIENTAS PARA TRABAJAR METALES POR
                                                FORJADO, MARTILLADO O ESTAMPADO CON MATRIZ;
                                                MÁQUINAS HERRAMIENTAS PARA TRABAJAR METALES POR DOBLADO, ENDEREZADO,
                                                APLANADO, CORTE, PUNZONADO O MUESCADO; OTRAS
                                                PRENSAS PARA TRABAJAR METALES O CARBUROS METÁLICOS</option>

                                            <option value="44221">MÁQUINAS HERRAMIENTAS PARA TRABAJAR PIEDRA, CERÁMICAS,
                                                HORMIGÓN, ASBESTO, CEMENTO O MATERIALES
                                                MINERALES SIMILARES O PARA TRABAJAR EL VIDRIO EN FRÍO</option>

                                            <option value="44222">MÁQUINAS HERRAMIENTAS PARA TRABAJAR MADERA, CORCHO,
                                                HUESO, EBONITA, PLÁSTICOS DUROS Y OTROS
                                                MATERIALES DUROS SIMILARES; PRENSAS PARA LA FABRICACIÓN DE TABLEROS DE
                                                PARTÍCULAS O DE FIBRAS PARA LA CONSTRUCCIÓN
                                                DE MADERA U OTROS MATERIALES LEÑOSOS, Y OTRAS MÁQUINAS PARA LA
                                                ELABORACIÓN DE MADERA O CORCHO</option>

                                            <option value="44231">HERRAMIENTAS DE USO MANUAL, NEUMÁTICAS, HIDRÁULICAS
                                                CON UN MOTOR NO ELÉCTRICO INCORPORADO</option>

                                            <option value="44232">HERRAMIENTAS ELECTROMECÁNICAS DE USO MANUAL CON MOTOR
                                                ELÉCTRICO INCORPORADO</option>

                                            <option value="44241">MAQUINARIA Y APARATOS ELÉCTRICOS PARA SOLDADURA
                                                BLANDA, SOLDADURA FUERTE O SOLDADURA AUTÓGENA;
                                                MÁQUINAS Y APARATOS ELÉCTRICOS PARA LA PULVERIZACIÓN EN CALIENTE DE
                                                METALES O CARBUROS METÁLICOS SINTERIZADOS
                                            </option>

                                            <option value="44242">MAQUINARIA Y APARATOS NO ELÉCTRICOS PARA SOLDADURA
                                                BLANDA, SOLDADURA FUERTE O SOLDADURA AUTÓGENA;
                                                MÁQUINAS Y APARATOS DE FUNCIONAMIENTO A GAS PARA TEMPLADO DE SUPERFICIES
                                            </option>

                                            <option value="44252">PARTES, PIEZAS Y ACCESORIOS PARA LOS PRODUCTOS DE LA
                                                SUBCLASE 44221; PARTES, PIEZAS Y ACCESORIOS
                                                DE MÁQUINAS Y HERRAMIENTAS PARA TRABAJAR MADERA, HUESO, PLÁSTICOS DUROS
                                                Y MATERIALES SIMILARES</option>

                                            <option value="44421">MÁQUINA EXCAVADORA Y EXCAVADORA ANGULAR,
                                                AUTOPROPULSADA</option>

                                            <option value="44425">CARGADORAS DE PALA FRONTAL, AUTOPROPULSADAS</option>

                                            <option value="44427">PALAS, EXCAVADORAS Y CARGADORAS DE PALAS MECÁNICAS,
                                                EXCEPTO CARGADORAS DE PALA FRONTAL Y
                                                MAQUINARIA CON SUPERESTRUCTURA GIRATORIA EN 360°; MÁQUINAS MOVEDORAS,
                                                CONFORMADORAS, NIVELADORAS, RASPADORAS
                                                (TRAÍLLAS), EXCAVADORAS, APISONADORAS, COMPACTADORAS, EXTRACTORAS O
                                                PERFORADORAS PARA TIERRA, MINERALES O MENAS,
                                                AUTOPROPULSADAS N.C.P.</option>

                                            <option value="44430">HINCADORES DE PILOTES Y EXTRACTORAS DE PILOTES; ARADOS
                                                DE NIEVE Y SOPLADORES DE NIEVE; OTRAS
                                                MÁQUINAS MOVEDORAS, CONFORMADORAS, NIVELADORAS, RASPADORAS (TRAÍLLAS),
                                                EXCAVADORAS, APISONADORAS, COMPACTADORAS O
                                                EXTRACTORAS PARA TIERRA, MINERALES O MENAS; NO AUTOPROPULSADAS;
                                                MAQUINARIA N.C.P. PARA OBRAS PÚBLICAS, CONSTRUCCIÓN
                                                DE EDIFICIOS U OBRAS SIMILARES</option>

                                            <option value="48311">FIBRAS ÓPTICAS Y HACES DE FIBRAS ÓPTICAS; CABLES DE
                                                FIBRA ÓPTICA (EXCEPTO LOS CONSTITUIDOS POR
                                                FIBRAS ENFUNDADAS INDIVIDUALMENTE); HOJAS Y PLACAS DE MATERIAL
                                                POLARIZADOR; LENTES, PRISMAS, ESPEJOS Y OTROS
                                                ARTÍCULOS ÓPTICOS (EXCEPTO DE VIDRIO NO TRABAJADO ÓPTICAMENTE), ÉSTE O
                                                NO MONTADO, EXCEPTO PARA LAS CÁMARAS,
                                                PROYECTORES O AMPLIADORAS O REDUCTORAS FOTOGRÁFICOS</option>

                                            <option value="44461">PARTES Y PIEZAS N.C.P. DE MÁQUINAS PARA HACER
                                                PERFORACIONES O POZOS Y DE GRÚAS DE BRAZO MÓVIL,
                                                GRÚAS, BASTIDORES ELEVADORES MÓVILES, CAMIONES DE PÓRTICO ALTO Y
                                                CAMIONES DE FAENAS PROVISTOS DE GRÚAS; PARTES Y
                                                PIEZAS N.C.P. DE MÁQUINAS MOVEDORAS, CONFORMADORAS, NIVELADORAS,
                                                RASPADORAS (TRAÍLLAS), EXCAVADORAS, APISONADORAS,
                                                COMPACTADORAS, EXTRACTORAS O PERFORADORAS PARA TIERRA, MINERALES O
                                                MENAS; PARTES Y PIEZAS DE HINCADORES DE PILOTES Y
                                                EXTRACTORES DE PILOTES; PARTES Y PIEZAS DE QUITANIEVES Y SOPLANIEVES
                                            </option>

                                            <option value="46111">MOTORES DE POTENCIA NO SUPERIOR A 37.5 W; OTROS
                                                MOTORES DE CORRIENTE CONTINUA; GENERADORES DE
                                                CORRIENTE CONTINUA</option>

                                            <option value="46112">MOTORES UNIVERSALES DE CORRIENTE CONTINUA/CORRIENTE
                                                ALTERNA, DE POTENCIA SUPERIOR A 37.5 W; OTROS
                                                MOTORES DE CORRIENTE ALTERNA; GENERADORES DE CORRIENTE ALTERNA
                                                (ALTERNADORES)</option>

                                            <option value="46113">GRUPOS ELECTRÓGENOS Y CONVERTIDORES ELÉCTRICOS
                                                ROTATIVOS</option>

                                            <option value="46131">PARTES Y PIEZAS DE MOTORES, GENERADORES Y APARATOS
                                                ELÉCTRICOS SIMILARES</option>

                                            <option value="46213">TABLEROS, CONSOLAS, ARMARIOS Y OTRAS BASES, EQUIPADAS
                                                CON APARATOS ELÉCTRICOS PARA EMPALMAR, ETC.,
                                                APARATOS PARA CONTROL ELÉCTRICO O DISTRIBUCIÓN DE ELECTRICIDAD, PARA
                                                VOLTAJES NO SUPERIORES A 1.000 V</option>

                                            <option value="46214">TABLEROS, CONSOLAS, ARMARIOS Y OTRAS BASES, EQUIPADAS
                                                CON APARATOS ELÉCTRICOS PARA EMPALMAR, ETC.,
                                                APARATOS PARA CONTROL ELÉCTRICO O DISTRIBUCIÓN DE ELECTRICIDAD, PARA
                                                VOLTAJES SUPERIORES A 1.000 V</option>

                                            <option value="34231">ELEMENTOS QUÍMICOS N.C.P.; ÁCIDOS INORGÁNICOS EXCEPTO
                                                ÁCIDO FOSFÓRICO Y ÁCIDO SULFONÍTRICO;
                                                COMPUESTOS OXIGENADOS INORGÁNICOS DEL BORO, SILICIO Y CARBÓN; COMPUESTOS
                                                HALOGENADOS O SULFUROSOS DE METALES;
                                                HIDRÓXIDO DE SODIO Y PERÓXIDO DE MAGNESIO; ÓXIDOS, HIDRÓXIDOS Y
                                                PERÓXIDOS DE ESTRONCIO O DE BARIO; HIDRÓXIDO DE
                                                ALUMINIO; HYDROZINE E HIDROXILAMINA Y SUS SALES INORGÁNICAS</option>

                                            <option value="34232">ÁCIDO FOSFÓRICO</option>

                                            <option value="35220">LISINA Y SUS ÉSTERES Y SALES DE ESTOS COMPUESTOS;
                                                ÁCIDO GLUTÁMICO Y SUS SALES; SALES E HIDRÓXIDOS
                                                DE AMONIO CUATERNARIO; LECITINAS Y OTROS FOSFOAMINOLÍPIDOS; AMIDAS
                                                ACÍCLICAS Y SUS DERIVADOS Y SALES DE ESTOS
                                                COMPUESTOS; AMIDAS CÍCLICAS (EXCEPTO UREÍNAS) Y SUS DERIVADOS Y SALES
                                            </option>

                                            <option value="35322">DETERGENTES Y PREPARACIONES PARA LAVAR</option>

                                            <option value="36260">PRENDAS Y ACCESORIOS DE VESTIR (INCLUSO GUANTES) DE
                                                CAUCHO VULCANIZADO NO ENDURECIDO</option>

                                            <option value="37195">ARTÍCULOS DE VIDRIO PARA LABORATORIO, HIGIENE Y
                                                FARMACIA; AMPOLLAS DE VIDRIO</option>

                                            <option value="38942">PLACAS Y PELÍCULAS FOTOGRÁFICAS IMPRESIONADAS Y
                                                REVELADAS, EXCEPTO PELÍCULAS CINEMATOGRÁFICAS
                                            </option>

                                            <option value="43914">MÁQUINAS Y APARATOS PARA LA FILTRACIÓN O DEPURACIÓN DE
                                                LÍQUIDOS O GASES, EXCEPTO FILTROS DE
                                                ACEITE, FILTROS DE GASOLINA Y FILTROS DE ENTRADA DE AIRE PARA MOTORES DE
                                                COMBUSTIÓN INTERNA</option>

                                            <option value="47140">VÁLVULAS Y TUBOS TERMIÓNICOS, CON CÁTODO FRÍO O CON
                                                FOTOCÁTODO (INCLUSO TUBOS DE RAYOS CATÓDICOS)
                                            </option>

                                            <option value="47160">CIRCUITOS ELECTRÓNICOS INTEGRADOS</option>

                                            <option value="48110">APARATOS BASADOS EN EL USO DE RAYOS X O DE RADIACIONES
                                                ALFA, BETA O GAMMA</option>

                                            <option value="48121">APARATOS DE DIAGNÓSTICO ELÉCTRICOS UTILIZADOS EN
                                                MEDICINA, CIRUGÍA, ODONTOLOGÍA O VETERINARIA
                                            </option>

                                            <option value="48122">APARATOS DE RAYOS ULTRAVIOLETAS O INFRARROJOS
                                                UTILIZADOS EN MEDICINA, CIRUGÍA, ODONTOLOGÍA O
                                                VETERINARIA</option>

                                            <option value="48130">OTROS INSTRUMENTOS Y APARATOS DE ODONTOLOGÍA (EXCEPTO
                                                JERINGUILLAS, AGUJAS Y ARTÍCULOS SIMILARES)
                                            </option>

                                            <option value="48150">OTROS INSTRUMENTOS Y APARATOS UTILIZADOS EN MEDICINA,
                                                CIRUGÍA O VETERINARIA (INCLUSO JERINGUILLAS,
                                                AGUJAS, CATÉTERES, CÁNULAS, INSTRUMENTOS Y APARATOS DE OFTALMOLOGÍA Y
                                                APARATOS ELECTROMÉDICOS N.C.P.)</option>

                                            <option value="48171">APARATOS ORTOPÉDICOS; FÉRULAS Y APARATOS PARA
                                                FRACTURAS; PARTES ARTIFICIALES DEL CUERPO</option>

                                            <option value="48172">AUDÍFONOS Y OTROS APARATOS QUE SE LLEVAN O IMPLANTAN
                                                EN EL CUERPO PARA COMPENSAR UN DEFECTO O UNA
                                                INCAPACIDAD</option>

                                            <option value="48180">MOBILIARIO PARA MEDICINA, CIRUGÍA, ODONTOLOGÍA O
                                                VETERINARIA; SILLONES DE PELUQUERÍA Y ASIENTOS
                                                SIMILARES CON MOVIMIENTOS DE ROTACIÓN, INCLINACIÓN Y ELEVACIÓN</option>

                                            <option value="48251">HIDRÓMETROS E INSTRUMENTOS FLOTANTES SIMILARES,
                                                TERMÓMETROS, PIRÓMETROS, BARÓMETROS, HIGRÓMETROS Y
                                                PSICRÓMETROS</option>

                                            <option value="48253">INSTRUMENTOS Y APARATOS PARA ANÁLISIS FÍSICOS O
                                                QUÍMICOS, PARA MEDIR O VERIFICAR VISCOSIDAD,
                                                POROSIDAD, DILATACIÓN, TENSIÓN SUPERFICIAL O SIMILARES, O PARA MEDIR O
                                                VERIFICAR CANTIDADES DE CALOR, SONIDO O LUZ
                                            </option>

                                            <option value="48312">ANTEOJOS, ANTIPARRAS Y ARTÍCULOS SIMILARES,
                                                CORRECTIVOS, PROTECTORES DE OTRO TIPO</option>

                                            <option value="48322">CÁMARAS FOTOGRÁFICAS (INCLUSO CINEMATOGRÁFICAS)
                                            </option>

                                            <option value="48341">PLACAS Y PELÍCULAS FOTOGRÁFICAS PLANAS Y PELÍCULA DE
                                                IMPRESIÓN INSTANTÁNEA, SENSIBILIZADAS, SIN
                                                REVELAR</option>

                                            <option value="48351">PIEZAS Y ACCESORIOS PARA LOS PRODUCTOS DE LAS SUBCLASE
                                                48314</option>

                                            <option value="48140">ESTERILIZADORES MÉDICOS, QUIRÚRGICOS O DE LABORATORIO
                                            </option>

                                            <option value="48261">MICROSCOPIOS (EXCEPTO MICROSCOPIOS ÓPTICOS) Y
                                                DIFRACTÓGRAFOS</option>

                                            <option value="48314">BINOCULARES, CATALEJOS Y OTROS TELESCOPIOS ÓPTICOS;
                                                OTROS INSTRUMENTOS ASTRONÓMICOS, EXCEPTO
                                                INSTRUMENTOS PARA LA RADIOASTRONOMÍA; MICROSCOPIOS ÓPTICOS COMPUESTOS
                                            </option>

                                            <option value="47403">PARTES Y PIEZAS PARA LOS PRODUCTOS DE LAS SUBCLASES
                                                47211 A 47213, 47311 A 47315 Y 48220</option>

                                            <option selected value="61185">MATERIALES Y EQUIPOS ELÉCTRICOS Y ELECTRÓNICOS DE BAJA
                                                TENSIÓN HASTA 1000V</option>

                                            <option value="61185">MATERIALES Y EQUIPOS ELÉCTRICOS Y ELECTRÓNICOS DE BAJA
                                                TENSIÓN HASTA 1000V IMPORTADOS</option>


                                            <option value="61185">MATERIALES Y EQUIPOS ELÉCTRICOS DE MEDIA TENSIÓN DE
                                                1001 A 35000V</option>

                                            <option value="61185">MATERIALES Y EQUIPOS ELÉCTRICOS DE MEDIA TENSIÓN DE
                                                1001 A 35000V</option>


                                            <option value="61185">MATERIALES Y EQUIPOS ELÉCTRICOS DE ALTA TENSIÓN DE
                                                35001 ADELANTE</option>

                                            <option value="61185">MATERIALES Y EQUIPOS ELÉCTRICOS DE ALTA TENSIÓN DE
                                                35001 ADELANTE IMPORTADOS</option>


                                            <option value="61185">ACCESORIOS PARA CONEXIÓN ELÉCTRICA Y HERRAMIENTAS
                                                ELÉCTRICAS</option>

                                            <option value="61185">ACCESORIOS PARA CONEXIÓN ELÉCTRICA Y HERRAMIENTAS
                                                ELÉCTRICAS IMPORTADOS</option>


                                            <option value="61185">SERVICIOS DE ASESORAMIENTO ELÉCTRICOS Y EJECUCIÓN DE
                                                OBRA</option>

                                            <option value="61185">SERVICIOS DE ASESORAMIENTO ELÉCTRICOS Y EJECUCIÓN DE
                                                OBRA IMPORTADOS</option>


                                            <option value="48282">PARTES Y ACCESORIOS PARA LOS PRODUCTOS DE LAS SUBCLASE
                                                48261</option>

                                            <option value="99763">VENTA DE INSUMOS PARA EQUIPOS DE IMPRESIÓN</option>

                                            <option value="99764">VENTA DE REPUESTOS PARA EQUIPOS DE TI</option>

                                            <option value="99765">VENTA DE INSUMOS PARA EQUIPOS DE IMPRESIÓN IMPORTADO
                                            </option>

                                            <option value="99766">VENTA DE REPUESTOS PARA EQUIPOS DE TI IMPORTADO
                                            </option>

                                            <option value="43420">HORNOS INDUSTRIALES O DE LABORATORIO, EXCEPTO HORNOS
                                                DE PANADERÍA NO ELÉCTRICOS; OTRO EQUIPO DE
                                                CALENTAMIENTO POR INDUCCIÓN O DIELÉCTRICO PARA USOS INDUSTRIALES O DE
                                                LABORATORIOS</option>

                                            <option value="43430">PARTES Y PIEZAS PARA LOS PRODUCTOS DE LAS SUBCLASES
                                                43410 Y 43420; PARTES Y PIEZAS DE HORNOS DE
                                                PANADERÍA NO ELÉCTRICOS</option>

                                            <option value="44622">MÁQUINAS PARA LAVAR ROPA, CON UNA CAPACIDAD SUPERIOR A
                                                LOS 10 KG DE ROPA SECA; MÁQUINAS PARA
                                                LIMPIAR EN SECO, MÁQUINAS PARA SECAR TEJIDOS O ARTÍCULOS TEXTILES, CON
                                                UNA CAPACIDAD SUPERIOR A LOS 10 KG DE ROPA
                                                SECA</option>

                                            <option value="44640">PARTES Y PIEZAS PARA LOS PRODUCTOS DE LA CLASE 4461;
                                                PARTES Y PIEZAS PARA LOS PRODUCTOS DE LA
                                                SUBCLASE 44621 (INCLUSO AGUJAS, MUEBLES, BASES Y CUBIERTAS PARA MÁQUINAS
                                                DE COSER); PARTES Y PIEZAS PARA LOS
                                                PRODUCTOS DE LA SUBCLASE 44622; PARTES Y PIEZAS PARA LOS PRODUCTOS DE LA
                                                SUBCLASE 44629, EXCEPTO PARTES Y PIEZAS DE
                                                MAQUINARIA PARA LA FABRICACIÓN O EL ACABADO DE FIELTRO O TEXTILES NO
                                                TEJIDOS Y HORMAS DE SOMBRERERÍA; PARTES Y
                                                PIEZAS PARA LOS PRODUCTOS DE LA SUBCLASE 44630; PARTES Y PIEZAS DE
                                                MÁQUINAS PARA LAVAR ROPA DE USO DOMÉSTICO O DE
                                                LAVANDERÍA Y MÁQUINAS PARA SECAR TEXTILES, CON UNA CAPACIDAD INFERIOR A
                                                LOS 10 KG DE ROPA SECA</option>

                                            <option value="48160">APARATOS DE MECANOTERAPIA; APARATOS DE MASAJE;
                                                APARATOS DE PRUEBAS DE APTITUD PSICOLÓGICA;
                                                APARATOS DE OZONOTERAPIA, OXIGENOTERAPIA, AEROSOL TERAPIA, RESPIRACIÓN
                                                ARTIFICIAL U OTROS APARATOS RESPIRATORIOS
                                                TERAPÉUTICOS; OTROS APARATOS RESPIRATORIOS Y MÁSCARAS DE GAS (EXCLUYENDO
                                                LAS MÁSCARAS DE PROTECCIÓN SIN MECANISMO NI
                                                FILTROS REEMPLAZABLES)</option>

                                            <option value="35310">AGENTES ORGÁNICOS TENSO ACTIVOS, EXCEPTO JABÓN
                                            </option>

                                            <option value="99795">SOPORTES MAGNETICOS (PARA REPRODUCIR IMAGEN O IMAGEN Y
                                                SONIDO)</option>

                                            <option value="99805">REPUESTOS VARIOS</option>

                                            <option value="99806">OTROS MATERIALES, REPUESTOS Y ACCESORIOS PARA EQUIPOS
                                            </option>

                                            <option value="99807">OTROS EQUIPOS Y ACCESORIOS</option>


                                        </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-3 w-78 m-auto">
                                    <div class="col text-center">
                                        <label for="ProdDescripcion">Descripción del producto</label>
                                        <textarea name="Descripcion" id="ProdDescripcion" data-parsley-required="true"
                                            cols="30" rows="5" class="form-control"
                                            placeholder="Descripción del producto."></textarea>
                                        <div class="text-center text-danger d-none emptyProdDescripcion">Campo
                                            descripción está vacío</div>
                                    </div>
                                </div>
                                <hr>

                                <div class="row mt-3">
                                <?php foreach($sucursales as $item) { ?>
                                    <div class="col col-md-6 mb-3 text-center">
                                        <span class="form-control bg-success text-white"><?= $item['title'] ?></span>
                                        <div class="row mt-2">
                                            <div class="col">
                                                <label for="ProdStock<?=$item['iniciales']?>">STOCK</label>
                                                <input type="text" name="Stock<?=$item['iniciales']?>" id="ProdStock<?=$item['iniciales']?>" class="form-control"
                                                    data-parsley-type="integer" placeholder="Stock"
                                                    data-parsley-required="false" value="0"  >
                                                <div class="text-center text-danger d-none emptyStock<?=$item['iniciales']?>">Campo stock
                                                    está vacío</div>
                                            </div>
                                            <div class="col">
                                                <label for="ProdPrecio<?=$item['iniciales']?>">PRECIO</label>
                                                <input type="number" step="0.10" min="0.00" name="Precio<?=$item['iniciales']?>" id="ProdPrecio<?=$item['iniciales']?>"
                                                    data-parsley-type="number" class="form-control" placeholder="Precio" value="0.00"
                                                    data-parsley-required="true">
                                                <div class="text-center text-danger d-none emptyPrecio<?=$item['iniciales']?>">Campo precio
                                                    está vacío</div>
                                            </div>
                                        </div>
                                        <div class="row mt-2">
                                            <div class="col">
                                                <label for="ProdObserv<?=$item['iniciales']?>">OBSERVACIONES</label>
                                                <textarea name="Observaciones<?=$item['iniciales']?>" id="ProdObserv<?=$item['iniciales']?>"
                                                    data-parsley-required="false" cols="30" rows="5"
                                                    class="form-control" placeholder="Observaciones"></textarea>
                                                <div class="text-center text-danger d-none emptyObservaciones<?=$item['iniciales']?>">Campo
                                                    observaciones está vacío</div>
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>
                                </div>

                                <div class="row mt-3">
                                    <div class="col">
                                        <input type="submit" class="btn btn-xs btn-primary form-control"
                                            value="REGISTRAR PRODUCTO">
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <a href="javascript:;" class="btn btn-icon btn-circle btn-primary btn-scroll-to-top fade"
            data-click="scroll-top"><i class="fa fa-angle-up"></i></a>
        <?php include 'php/footer.php'; ?>
    </div>
    <?php include 'php/script_productos.php'; ?>
    <script type="text/javascript">
    //$("#ProdDescripcion").summernote();
    </script>
</body>

</html>
<?php include 'php/fun_productos.php';
	}else{ ?>
<script type="text/javascript">
location.replace("?root=404");
</script><?php
	}
?>