$(document).ready(function() {
	$('#ClienteCell').mask('0000-0000');
	$('#ClienteCell_').mask('0000-0000');
	$("#ClienteOtro").mask('000-0000');
	$("#ClienteOtro_").mask('000-0000');

	//BUSCAR CLIENTES POR APELLIDO
	$(document).on('click', '.buscarCliente', function(event) {
		event.preventDefault();

		var Apellido 	=	$("#byApellido").val();
		var Sucursal 	=	$("#bySucursal option:selected").val();

		if (Apellido=='') {
			$(".noApellido").removeClass('d-none');
			setTimeout(function(){
				$(".noApellido").addClass('d-none');
			},2000); return false;
		}else if (Sucursal=='Sucursal') {
			$(".noSucursalFind").removeClass('d-none');
			setTimeout(function(){
				$(".noSucursalFind").addClass('d-none');
			},2000); return false;
		}else{
			$.ajax({
				url: 'includes/getFindClientes.php',
				type: 'POST',
				dataType: 'html',
				data: $("#findClienteAdmin").serialize(),
			})
			.done(function(data) {
				$("#respuestaFind").html(data);
			})
			return false;
		}		
	});

	//REGISTRAR NUEVO CLIENTE
	$(document).on('click', '.regNewCliente', function(event) {
		event.preventDefault();
		var Nombres 	=	$("#ClinteNombres").val();
		var Apellidos 	=	$("#ClienteApellidos").val();
		var Celular 	=	$("#ClienteCell").val();
		var Oficina 	=	$("#ClienteOficina").val();
		var Casa 		=	$("#ClienteCasa").val();
		var Otro 		=	$("#ClienteOtro").val();
		var Correo 		=	$("#ClienteCorreo").val();
		var Ciudad 	    =	$("#ClienteCiudad option:selected").val();

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
		}else if (Ciudad=='Seleccione Ciudad') {
			$(".emptyClienteCiudad").removeClass('d-none');
			setTimeout(function(){
				$(".emptyClienteCiudad").addClass('d-none');
			},3000);
		}else{
			$(".efecto").removeClass('d-none');
			$(".regNewCliente").attr('disabled', true);
			$.ajax({
				url: 'do.php',
				type: 'POST',
				dataType: 'html',
				data: $("#newCliente").serialize(),
			})
			.done(function(data) {
				$(".efecto").addClass('d-none');
				$(".regNewCliente").attr('disabled', false);
				$(".respuesta").html(data);
			})
			return false;
		}
	});

	//LLAMAR LOS DATOS DEL CLIENTE SELECCIONADO
	$(document).on('click', '.editCliente', function(event) {
		event.preventDefault();
		$(".formEditCliente").removeClass('d-none');
		$(".tableClientes").addClass('d-none');
		var idCliente = $(this).attr("id");
		$.ajax({
			url: 'includes/getDataClientes.php',
			type: 'POST',
			dataType: 'json',
			data: {idClienteEdit: idCliente},
			success:function(data){
				$("#fechaRegistro_").val(data.Fecha_Reg);
				$("#idCliente").val(data.idCliente);
				$("#ClinteNombres_").val(data.Nombres);
				$("#ClienteApellidos_").val(data.Apellidos);
				$("#ClienteEmpresa_").val(data.Empresa);
				$("#ClienteNIT_").val(data.NIT);
				$("#ClienteCell_").val(data.Celular);
				$("#ClienteOtro_").val(data.Otro);
				$("#ClienteCorreo_").val(data.Correo);
				$("#ClienteCiudad_").val(data.Ciudad);
				$("#ClienteCiudad_ option:selected").html(data.Ciudad);
				$("#ClienteDireccion_").val(data.Direccion);
				$("#ClienteComentarios_").val(data.Comentarios);
			}
		})
	});

	//ACTUALIZAR DATOS DEL CLIENTE
	$(document).on('click', '.upDataCliente', function(event) {
		event.preventDefault();
		var FechaReg 	=	$("#fechaRegistro_").val();
		var Nombres 	=	$("#ClinteNombres_").val();
		var Apellidos 	=	$("#ClienteApellidos_").val();
		var Ciudad 		=	$("#ClienteCiudad_ option:selected").val();

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
		}else{
			$(".upSpinner").removeClass('d-none');
			$(".upDataCliente").attr('disabled', true);
			$.ajax({
				url: 'do.php',
				type: 'POST',
				dataType: 'html',
				data: $("#editCliente").serialize(),
			})
			.done(function(data) {
				$(".upSpinner").addClass('d-none');
				$(".upDataCliente").attr('disabled', false);
				$(".respuesta").html(data);
			})
			return false;
		}
	});

	//LLAMAR LOS DATOS DEL CLIENTE PARA EL FORMULARIO DE ENVIO DE CORREO
	$(document).on('click', '.sendMailCliente', function(event) {
		event.preventDefault();
		var idCliente = $(this).attr("id");
		$(".tableClientes").addClass('d-none');
		$(".SendMail").removeClass('d-none');
		$.ajax({
			url: 'includes/getDataClientes.php',
			type: 'POST',
			dataType: 'json',
			data: {idClienteMail: idCliente},
			success:function(data){
				$("#CorreoCliente").val(data.Correo);
				$("#mailCliente").html(data.Correo);
				$("#idClienteMail").val(data.idCliente);
				$("#NombreClienteMail").val(data.Nombres+" "+data.Apellidos);
				$("#EmpresaClienteMail").val(data.Empresa);
			}
		})
	});

	//LLAMAMOS LA PLANTILLA HTML
	$("#PlantiilaMail").change(function() {
		$("#PlantiilaMail option:selected").each(function() {
			var idPlantilla = 	$(this).val();
			var idCliente 	=	$("#idClienteMail").val();
			$.ajax({
				url: 'includes/getDataPlantillaMail.php',
				type: 'POST',
				dataType: 'html',
				data: {id: idPlantilla, idCliente: idCliente},
				success:function(data){
					$("#MensajeSumerNote").summernote('code', data);
				}
			})
		});
	});

	//BOTONES
	$(document).on('click', '.cancelarSendMailCliente', function(event) {
		event.preventDefault();
		$(".SendMail").addClass('d-none');
		$(".tableClientes").removeClass('d-none');
	});
	$(document).on('click', '.AddNewClienteBTN', function(event) {
		event.preventDefault();
		$(".formNewCliente").removeClass('d-none');
		$(".tableClientes").addClass('d-none');
	});
	$(document).on('click', '.cancelarRegNewCliente', function(event) {
		event.preventDefault();
		$(".formNewCliente").addClass('d-none');
		$(".tableClientes").removeClass('d-none');
	});

	$(document).on('click', '.cancelarEditCliente', function(event) {
		event.preventDefault();
		$(".formEditCliente").addClass('d-none');
		$(".tableClientes").removeClass('d-none');
	});

	$(document).on('click', '.enviaMailToCliente', function(event) {
		event.preventDefault();
		var Nombre  = $("#NombreClienteMail").val();
		var Asunto  = $("#AsuntoMail").val();
		var Plantilla= $("#PlantiilaMail").val();

		if (Nombre=='') {
			$(".noNameMail").removeClass('d-none');
			$('.MsjMail').addClass('d-none');
			setTimeout(function(){
				$(".noNameMail").addClass('d-none');
				$('.MsjMail').removeClass('d-none');
			},2500);
			return false;
		}else if (Asunto=='') {
			$(".noAsuntoMail").removeClass('d-none');
			$('.MsjMail').addClass('d-none');
			setTimeout(function(){
				$(".noAsuntoMail").addClass('d-none');
				$('.MsjMail').removeClass('d-none');
			},2500);
			return false;
		}else if (Plantilla=='Seleccione plantilla') {
			$(".noPlantillaMail").removeClass('d-none');
			$('.MsjMail').addClass('d-none');
			setTimeout(function(){
				$(".noPlantillaMail").addClass('d-none');
				$('.MsjMail').removeClass('d-none');
			},2500);
			return false;
		}else{
			$(".efectSpinner").removeClass('d-none');
			$(".enviaMailToCliente").attr('disabled', true);
			$.ajax({
				url: 'do.php',
				type: 'POST',
				dataType: 'html',
				data: $(sendmail).serialize(),
			})
			.done(function(data) {
				$(".efectSpinner").addClass('d-none');
				$(".enviaMailToCliente").attr('disabled', false);
				$(".respuesta").html(data);
			})
			return false;
		}
	});
});