$(document).ready(function() {
	$(document).on('click', '.buscarVenta', function(event) {
		event.preventDefault();

		alert("Esta función se modificará"); return false;
		/*var Inicio 	=	$("#FechaInicio").val();
		var Fin 	=	$("#FechaFin").val();
		
		if (Inicio=='') {
			$(".noFechaInicio").removeClass('d-none');
			setTimeout(function(){
				$(".noFechaInicio").addClass('d-none');
			},2000);
		}else if (Fin=='') {
			$(".noFechaFinal").removeClass('d-none');
			setTimeout(function(){
				$(".noFechaFinal").addClass('d-none');
			},2000);
		}else{
			$("#Tabla_idVentas").html("");
			$.ajax({
				url: 'do.php',
				type: 'POST',
				dataType: 'html',
				data: "action=BuscarVentasporFecha&inicio="+Inicio+"&fin="+Fin,
			})
			.done(function(data) {
				$("#Tabla_idVentas").html(data);
			})
			return false;			
		}*/
	});
});