$(document).ready(function() {
	//$("#Aviso").modal();
	$("#apiTele").mask("000000000");
	$(document).on('click', '.buscarComisiones', function(event) {
		event.preventDefault();
		$("#searchComisiones").removeClass('d-none');
		// var idUser = $(this).attr("id");
		// alert(idUser);
	});

	$(document).on('click', '.buscarMisComisiones', function(event) {
		event.preventDefault();
		$(".btn-Buscar").removeClass('d-none');
		$(".buscarMisComisiones").attr('disabled', true);
		$.ajax({
			url: 'do.php',
			type: 'POST',
			dataType: 'html',
			data: $("#searchComisiones").serialize(),
		})
		.done(function(data) {
			$(".btn-Buscar").addClass('d-none');
			$(".buscarMisComisiones").attr('disabled', false);
			$("#miComision").val(data);
		})
		return false;
	});
	$("#savePrecioDolar").submit(function(event) {
		$.ajax({
			url: 'do.php',
			type: 'POST',
			dataType: 'html',
			data: $("#savePrecioDolar").serialize(),
		})
		.done(function(data) {
			$(".respuesta").html(data);
		})
		return false;
	});
});