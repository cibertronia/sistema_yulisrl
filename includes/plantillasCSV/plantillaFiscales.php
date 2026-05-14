<?php
header("Content-disposition: attachment; filename=plantillaSubirProductosFiscales.csv");
header("Content-type: application/csv");
readfile("./includes/plantillasCSV/plantillaSubirProductosFiscales.csv");
?>