<?php
    date_default_timezone_set('America/La_Paz');
    $dias           =   array("Domingo","Lunes","Martes","Miercoles","Jueves","Viernes","Sábado");
    $meses          =   array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
    $dia            =   $dias[date('w')];
    $diaNumero      =   date('d');
    $mes            =   $meses[date('n')-1];
    $mesNumero      =   date('m');
    $Year           =   date('Y');
    $Fecha          =   $dia.", ".$diaNumero." de ".$mes." de ".$Year;
    $fecha          =   date("Y-m-d");
    $Hora           =   date("H:i:s");
    $startBusqueda  =   date("$Year-$mesNumero-01");
    //echo $Hora;

    $hora 			= 	date('g:i a');
	// $Hora 			=	strtotime ( '-4 hour' , strtotime ($hora));
	// $Hora 			=	date ('g:i a' , $Hora);
    $Registro       =   date("Y-m-d g:i");