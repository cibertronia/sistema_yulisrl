<?php
	/*header("Content-type: application/vnd.ms-excel");
	header("Content-Disposition: attachment; filename=Reporte.xls");*/

    header("Content-type: application/vnd.ms-word");
    header("Content-Disposition: attachment; filename=Reporte.doc");
?>
<!-- <style>
    .contenedor {
        width: 75%;
        margin: 0 auto;
    }

    .logo {
        text-align: center;
        width: 100px
    }

    p {
        margin-left: 10%;
        font-size: 16px
    }
    body{
        width: 7in;
        height: 9in;
    }
</style>
<meta charset='UTF-8'> -->

<body>
	<img src='https://sistema.yuliimport.com/assets/img/logo.png' alt='Logo Yuli import' style="width: 6cm">            
    <div class='contenedor'>
        <div class='logo'>
        </div>
        <p>El Producto: ".$FullNameProd ." está bajo el límite configurado de 10 artículos o menos.<br>
        Esta alerta fué generada por la Venta de la Sucursal <strong>".$Sucursal ."</strong><br><br><br>Mensaje enviado desde el Sistema Automatizado el d&iacute;a:<br>".$Fecha."<br>".$hora."</p>
    </div>
</body>