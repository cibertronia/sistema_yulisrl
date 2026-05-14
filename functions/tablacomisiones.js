$(document).ready(function() {
	$(document).on('click', '.editarComision', function(event) {
		event.preventDefault();
		var idTabla = $(this).attr("id");
		$(".editTabla").removeClass('d-none');
		$.ajax({
			url: 'includes/consultas.php',
			type: 'POST',
			dataType: 'json',
			data: {idTabla: idTabla},
			success:function(data){
				$("#idTabla").val(data.idTabla);
				$("#metaMinima").val(data.Meta1);
				$("#comisionMinima").val(data.Comision1);
				$("#metaMaxima").val(data.Meta2);
				$("#comisionMaxima").val(data.Comision2);
			}
		})
	});
});