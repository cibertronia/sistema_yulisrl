<script type="text/javascript">
	$("#newCliente").submit(function() {
		var Nombres 	=	$("#ClinteNombres").val();
		var Apellidos 	=	$("#ClienteApellidos").val();
		var Celular 	=	$("#ClienteCell").val();
		var Oficina 	=	$("#ClienteOficina").val();
		var Casa 		=	$("#ClienteCasa").val();
		var Otro 		=	$("#ClienteOtro").val();
		var Correo 		=	$("#ClienteCorreo").val();
		var Sucursal 	=	$("#ClienteCiudad option:selected").val();

		if (Nombres=='') {
			$("#ClinteNombres").focus();
			$("#ClinteNombres").addClass('is-invalid');
			setTimeout(function(){
				$("#ClinteNombres").removeClass('is-invalid');
			},3000);
		}else if (Apellidos=='') {
			$("#ClienteApellidos").focus();
			$("#ClienteApellidos").addClass('is-invalid');
			setTimeout(function(){
				$("#ClienteApellidos").removeClass('is-invalid');
			},3000);
		}else if (Celular=='' && Oficina=='' && Casa=='' && Otro=='') {
			$(".noPhoneNum").removeClass('d-none');
			setTimeout(function(){
				$(".noPhoneNum").addClass('d-none');
			},3000);
		}else if (Correo=='') {
			$("#ClienteCorreo").focus();
			$("#ClienteCorreo").addClass('is-invalid');
			setTimeout(function(){
				$("#ClienteCorreo").removeClass('is-invalid');
			},3000);
		}else if (Sucursal=='Seleccione Ciudad') {
			$(".emptyClienteCiudad").removeClass('d-none');
			setTimeout(function(){
				$(".emptyClienteCiudad").addClass('d-none');
			},3000);
		}else{
			$.ajax({
				url: 'do.php',
				type: 'POST',
				dataType: 'html',
				data: $(newCliente).serialize(),
			})
			.done(function(data) {
				$(".respuesta").html(data);
			})
			return false;
		}
		return false;
	});

	$("#editCliente").submit(function() {
		var Nombres 	=	$("#ClinteNombres_").val();
		var Apellidos 	=	$("#ClienteApellidos_").val();
		var Celular 	=	$("#ClienteCell_").val();
		var Oficina 	=	$("#ClienteOficina_").val();
		var Casa 		=	$("#ClienteCasa_").val();
		var Otro 		=	$("#ClienteOtro_").val();
		var Correo 		=	$("#ClienteCorreo_").val();
		var Sucursal 	=	$("#ClienteCiudad_ option:selected").val();

		if (Nombres=='') {
			$("#ClinteNombres_").focus();
			$("#ClinteNombres_").addClass('is-invalid');
			setTimeout(function(){
				$("#ClinteNombres_").removeClass('is-invalid');
			},3000);
		}else if (Apellidos=='') {
			$("#ClienteApellidos_").focus();
			$("#ClienteApellidos_").addClass('is-invalid');
			setTimeout(function(){
				$("#ClienteApellidos_").removeClass('is-invalid');
			},3000);
		}else if (Celular=='' && Oficina=='' && Casa=='' && Otro=='') {
			$(".noPhoneNum_").removeClass('d-none');
			setTimeout(function(){
				$(".noPhoneNum_").addClass('d-none');
			},3000);
		}/*else if (Correo=='') {
			$("#ClienteCorreo_").focus();
			$("#ClienteCorreo_").addClass('is-invalid');
			setTimeout(function(){
				$("#ClienteCorreo_").removeClass('is-invalid');
			},3000);
		}*/else if (Sucursal=='Seleccione Ciudad') {
			$(".emptyClienteCiudad_").removeClass('d-none');
			setTimeout(function(){
				$(".emptyClienteCiudad_").addClass('d-none');
			},3000);
		}else{
			$.ajax({
				url: 'do.php',
				type: 'POST',
				dataType: 'html',
				data: $(editCliente).serialize(),
			})
			.done(function(data) {
				$(".respuesta").html(data);
			})
			return false;
		}
		return false;
	});

	$("#sendmail").submit(function() {
		$.ajax({
			url: 'do.php',
			type: 'POST',
			dataType: 'html',
			data: $(sendmail).serialize(),
		})
		.done(function(data) {
			$(".respuesta").html(data);
		})
		return false;
	});
</script>