$(document).ready(function() {
	$("#ClienteCell").mask("0000-0000");
	$("#ClienteOtro").mask("000-0000");
	$("#ClienteCell_").mask("0000-0000");
	$("#ClienteOtro_").mask("000-0000");

	//BUSCAR CLIENTES POR APELLIDO
	$(document).on('click', '.buscarCliente', function(event) {
		event.preventDefault();
		const search 	=	$("#input-search").val();
		var Sucursal 	=	$("#bySucursal option:selected").val();

		// Limpiar validaciones
		$("#input-search").removeClass('is-invalid');
		$("#bySucursal").removeClass('is-invalid');
		$(".noDataFind").addClass('d-none');
		$(".noSucursalFind").addClass('d-none');

		// validar que se ingrese datos de busqueda
		if (search=='') {
			$(".noDataFind").removeClass('d-none');
			$("#input-search").focus().addClass('is-invalid');
			 return false;
		} else if (Sucursal=='Sucursal') {
			$(".noSucursalFind").removeClass('d-none');
			$("#bySucursal").focus().addClass('is-invalid');
			return false;
		} else {
			$.ajax({
				url: 'includes/getFindClientes.php',
				type: 'GET',
				dataType: 'html',
				data: $("#findClienteAdmin").serialize(),
			})
			.done(function(data) {
				$("#respuestaFind").html(data);
			})
			return false;
		}

		// if (Apellido=='') {
		// 	$(".noApellido").removeClass('d-none');
		// 	setTimeout(function(){
		// 		$(".noApellido").addClass('d-none');
		// 	},2000); return false;
		// }
		// }else{

		// }		
	});

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

	$(document).on('click', '.cancelarEditCliente', function(event) {
		event.preventDefault();
		$(".formEditCliente").addClass('d-none');
		$(".tableClientes").removeClass('d-none');
	});

	$(document).on('click', '.Buscar', function(event) {
		event.preventDefault();
		$("#buscar").removeClass('d-none');
	});
	$(document).on('click', '.delCliente', function(event) {
		event.preventDefault();
		var idCliente = $("#idClienteCall").val();
		$.ajax({
			url: 'do.php',
			type: 'POST',
			dataType: 'html',
			data: "action=BorrarClienteCall&idCliente="+idCliente,
		})
		.done(function(data) {
			$(".respuesta").html(data);
		})
		return false;
	});
	$(document).on('click', '.callDataCliente', function(event) {
		event.preventDefault();
		var idCliente = $(this).attr('id');
		$.ajax({
			url: 'includes/getDataClientes.php',
			type: 'POST',
			dataType: 'json',
			data: {idCliente},
			success:function(data){
				$("#nameUsuario").text(data.Nombres+" "+data.Apellidos);
				$("#idClienteCall").val(idCliente);
			}
		})
	});
});