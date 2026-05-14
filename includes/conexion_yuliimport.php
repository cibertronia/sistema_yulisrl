<?php
$ErrorYuliimport    =    "Error Principal con la base de datos<br>En la linea:  " . __LINE__;
$YuliimportDB     =    mysqli_connect("167.86.108.223", "letimport_admin", "ES@72900968", "letimport_UPDATE") or die($ErrorYuliimport);
$YuliimportDB->set_charset("utf8");
