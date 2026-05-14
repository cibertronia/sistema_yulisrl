$(document).ready(function() {

	$(document).on('click', '.buscarReporte', function(event) {
		event.preventDefault();

		alert("Esta función se modificará"); return false;
		var Inicio 	=	$("#reporteInicio").val();
		var Fin 	=	$("#reporteFin").val();
		
		if (Inicio=='') {
			$(".noReportInicio").removeClass('d-none');
			setTimeout(function(){
				$(".noReportInicio").addClass('d-none');
			},2000);
		}else if (Fin=='') {
			$(".noReportFinal").removeClass('d-none');
			setTimeout(function(){
				$(".noReportFinal").addClass('d-none');
			},2000);
		}else{
			$("#tablaReportes").html("");
			//$(".tablaReportes").addClass('f-s-4');
			$.ajax({
				url: 'do.php',
				type: 'POST',
				dataType: 'html',
				data: "action=BuscarReporteporFecha&inicio="+Inicio+"&fin="+Fin,
			})
			.done(function(data) {
				$("#tablaReportes").html(data);
			})
			return false;			
		}
	});

});