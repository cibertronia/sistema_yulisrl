<?php
header("Content-disposition: attachment; filename=plantillaSubirProductosReales.csv");
header("Content-type: application/csv");
readfile("./includes/plantillasCSV/plantillaSubirProductosReales.csv");
?>