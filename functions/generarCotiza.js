$(document).ready(function() {	
	//$("#ClienteCiudad_").select2();
	$("#ClienteProducto").select2();
	$("#ClienteProducto_").select2();
	$("#CotizaOldCliente").select2();
	$(document).on('click', '.AddNewCotizaBTN', function(event) {
		event.preventDefault();
		$(".tableCotizaciones").addClass('d-none');
		$(".formNewCotizacion").removeClass('d-none');
		$("#ClienteNombre").focus();
	});

	$(document).on('click', '.cancelarRegNewCotiza', function(event) {
		event.preventDefault();
		$(".tableCotizaciones").removeClass('d-none');
		$(".formNewCotizacion").addClass('d-none');
	});

	$("input:checkbox[name='optionUser']").change(function() {
		if ($(this).is(':checked')) {
			$(".ClienteXistente").removeClass('d-none');
			$(".datosPersonales").addClass('d-none');			
			$(".infoProducto").addClass('d-none');
		}else{
			$(".ClienteXistente").addClass('d-none');
			$(".datosPersonales").removeClass('d-none');
			$(".infoProducto").removeClass('d-none');
			$("#ClienteNombre").focus();
			$("#ClienteNombre").val('');
			$("#ClienteApellido").val('');
			$("#ClienteCorreo").val('');
			$("#ClienteEmpresa").val('');
			$("#ClienteNIT").val('');
			//$("#ClienteCiudad_").val('');
			$("#ClienteCell").val('');
			$("#ClienteOficina").val('');
			$("#ClienteCasa").val('');
			$("#ClienteOtro").val('');
			$("#ClienteDireccion").val('');
			$("#ClienteComentario").val('');
		}
	});

	$("#CotizaOldCliente").change(function() {
		$("#CotizaOldCliente option:selected").each(function() {
			var idCliente = $(this).val();
			$.ajax({
				url: 'includes/getDataClientes.php',
				type: 'POST',
				dataType: 'json',
				data: {idCliente: idCliente},
				success:function(data){
					$(".datosPersonales").removeClass('d-none');
					$(".infoProducto").removeClass('d-none');

					$("#Cliente_Existente").val(data.idCliente);
					$("#ClienteNombre").val(data.Nombres);
					$("#ClienteApellido").val(data.Apellidos);
					$("#ClienteCorreo").val(data.Correo);
					$("#ClienteEmpresa").val(data.Empresa);
					$("#ClienteNIT").val(data.NIT);
					$("#ClienteCiudad_").val(data.Ciudad);
					$("#ClienteCiudad_ option:selected").html(data.Ciudad);
					$("#ClienteCell").val(data.Celular);
					$("#ClienteOficina").val(data.Oficina);
					$("#ClienteCasa").val(data.Casa);
					$("#ClienteOtro").val(data.Otro);
					$("#ClienteDireccion").val(data.Direccion);
					$("#ClienteComentario").val(data.Comentarios);
				}
			})
		});
	});

	$("#ClienteProducto").change(function() {
		$("#ClienteProducto option:selected").each(function() {
			var idProducto 	=	$(this).val();
			var miCiudad 	=	$("#miCiudad").val();

			$.ajax({
				url: 'includes/getDataProducto.php',
				type: 'POST',
				dataType: 'json',
				data: {id: idProducto},
				success:function(data){
					$(".PreciosProductoSelected").removeClass('d-none');
					$("#ProdExistencia").val(data.Stock);
					$("#CantidadProducto").val('1');
					$("#PrecioEspecial").val('');
					$("#PrecioEspecial").focus();
					if (miCiudad=='La Paz') {
						$("#PrecioLista").val(data.Precio_1);
					}else{
						$("#PrecioLista").val(data.Precio_2);
					}					
				}
			})
		});
	});

	$("#ClienteProducto_").change(function() {
		$("#ClienteProducto_ option:selected").each(function() {
			var idProducto 	=	$(this).val();
			var miCiudad 	=	$("#miCiudad_").val();

			$.ajax({
				url: 'includes/getDataProducto.php',
				type: 'POST',
				dataType: 'json',
				data: {id: idProducto},
				success:function(data){
					$(".datosAdicionalesTemp").removeClass('d-none');
					$(".masProdTemp").removeClass('d-none');
					$("#ProdExistencia_").val(data.Stock);
					$("#CantidadProducto_").val('1');
					$("#Precio_Especial").val('');
					$("#Precio_Especial").focus();
					if (miCiudad=='La Paz') {
						$("#PrecioLista_").val(data.Precio_1);
					}else{
						$("#PrecioLista_").val(data.Precio_2);
					}					
				}
			})
		});
	});

	$(document).on('click', '.Add_Producto', function(event) {
		event.preventDefault();
		//$(".showTableProd").removeClass('d-none');
		var idProducto = $("#ClienteProducto option:selected").val();
		var PrecioLista= $("#PrecioLista").val();
		var Cantidad   = $("#CantidadProducto").val();
		var PrecioEspec= $("#PrecioEspecial").val();
		var ClaveCotiza = $("#ClaveGeneradaAleatoria").val();

		if (idProducto=='') {
			$(".noSelectProd").removeClass('d-none');
			setTimeout(function(){
				$(".noSelectProd").addClass('d-none');
			},2000);
			//return false;
		}else if (Cantidad=='') {
			$(".CantidadEmpty").removeClass('d-none');
			setTimeout(function(){
				$(".CantidadEmpty").addClass('d-none');
			},2000);
			//return false;
		}else if (PrecioEspec=='') {
			$(".emptyPrecioEsp").removeClass('d-none');
			setTimeout(function(){
				$(".emptyPrecioEsp").addClass('d-none');
			},2000);
			//return false;
		}else{
			$.ajax({
				url: 'do.php',
				type: 'POST',
				dataType: 'html',
				data: "action=GuardarProductoTemporal&idProducto="+idProducto+"&PrecioLista="+PrecioLista+
				"&Cantidad="+Cantidad+"&PrecioEspecial="+PrecioEspec+"&ClaveTemp="+ClaveCotiza,
			})
			.done(function(data) {
				$(".PreciosProductoSelected").addClass('d-none');
				$(".checkOptions").addClass('d-none');
				$(".showTableProd").removeClass('d-none');
				$(".datosAdicionales").removeClass('d-none');
				$(".btnSaveCotiza").removeClass('d-none');
				$("#respuesta").html(data);
				//Despues de montar los valores, vaciamos los input
				// $("#PrecioLista").val('');
				// $("#CantidadProducto").val();
				// $("#PrecioEspecial").val();
			})
		}
	});

	$(document).on('click', '.masProdTemp', function(event) {
		event.preventDefault();
		var idProducto 	=	$("#ClienteProducto_ option:selected").val();
		var PrecioLista =	$("#PrecioLista_").val();
		var Cantidad 	=	$("#CantidadProducto_").val();
		var PrecioEspec =	$("#Precio_Especial").val();
		var ClaveCotiza	=	$("#ClaveTemp_oral").val();

		if (Cantidad=='') {
			$(".Cantidad_Empty").removeClass('d-none');
			setTimeout(function(){
				$(".Cantidad_Empty").addClass('d-none');
			},2000);
		}else if (PrecioEspec=='') {
			$(".emptyPrecio_Esp").removeClass('d-none');
			setTimeout(function(){
				$(".emptyPrecio_Esp").addClass('d-none');
			},2000);
		}else{
			$.ajax({
				url: 'do.php',
				type: 'POST',
				dataType: 'html',
				data: "action=GuardarOtroProductoTemporal&idProducto="+idProducto+
				"&PrecioLista="+PrecioLista+"&Cantidad="+Cantidad+"&PrecioEspecial="
				+PrecioEspec+"&ClaveTemp="+ClaveCotiza,
			})
			.done(function(data) {
				$(".datosAdicionalesTemp").addClass('d-none');
				$(".masProdTemp").addClass('d-none');
				$("#respuesta_").html(data);
			})
		}
	});

	$(document).on('click', '.delProdTemp', function(event) {
		event.preventDefault();
		var idTemp = $(this).attr("id");
		var ClaveCotiza = $("#ClaveGeneradaAleatoria").val();
		$.ajax({
			url: 'do.php',
			type: 'POST',
			dataType: 'html',
			data: "action=BorrarProductoTemporal&id="+idTemp+"&Clave="+ClaveCotiza,
		})
		.done(function(data) {
			$("#respuesta").html(data);
		})
	});

	$(document).on('click', '.delProdTemp_', function(event) {
		event.preventDefault();
		var idTemp = $(this).attr("id");
		var ClaveCotiza = $("#ClaveTemp_oral").val();
		$.ajax({
			url: 'do.php',
			type: 'POST',
			dataType: 'html',
			data: "action=BorrarProductoTemporal_&id="+idTemp+"&Clave="+ClaveCotiza,
		})
		.done(function(data) {
			$("#respuesta_").html(data);
		})
	});

	$(document).on('click', '.editProdTemporal', function(event) {
		event.preventDefault();
		var idTemp = $(this).attr("id");
		$.ajax({
			url: 'includes/getDataProdTemporal.php',
			type: 'POST',
			dataType: 'json',
			data: {id: idTemp},
			success:function(data){
				$("#idProdTemp").val(data.id);
				$("#Clave_Temp").val(data.Clave);
				$("#CantidadProdTemp").val(data.Cantidad);
				$("#PrecioProdTemp").val(data.PrecioOferta);
			}
		})
	});

	$(document).on('click', '.editProdTemporal_', function(event) {
		event.preventDefault();
		var idTemp = $(this).attr("id");
		$.ajax({
			url: 'includes/getDataProdTemporal.php',
			type: 'POST',
			dataType: 'json',
			data: {id: idTemp},
			success:function(data){
				$("#idProdTemp_").val(data.id);
				$("#Clave_Temp_").val(data.Clave);
				$("#CantidadProdTemp_").val(data.Cantidad);
				$("#PrecioProdTemp_").val(data.PrecioOferta);
			}
		})
	});

	$(document).on('click', '.actualizarProductoTemp', function(event) {
		event.preventDefault();
		var id = $("#idProdTemp").val();
		var Cantidad = $("#CantidadProdTemp").val();
		var PrecioEspecial = $("#PrecioProdTemp").val();
		var ClaveTemp = $("#Clave_Temp").val();
		$.ajax({
			url: 'do.php',
			type: 'POST',
			dataType: 'html',
			data: "action=ActualizarProdTemp&id="+id+"&Cantidad="+Cantidad+"&PrecioEspecial="+PrecioEspecial+"&ClaveTemp="+ClaveTemp,
		})
		.done(function(data) {
			$("#respuesta").html(data);
		})
		return false;
	});

	$(document).on('click', '.actualizarProductoTemp_', function(event) {
		event.preventDefault();
		var id = $("#idProdTemp_").val();
		var Cantidad = $("#CantidadProdTemp_").val();
		var PrecioEspecial = $("#PrecioProdTemp_").val();
		var ClaveTemp = $("#Clave_Temp_").val();
		$.ajax({
			url: 'do.php',
			type: 'POST',
			dataType: 'html',
			data: "action=ActualizarProdTemp_&id="+id+"&Cantidad="+Cantidad+"&PrecioEspecial="+PrecioEspecial+"&ClaveTemp="+ClaveTemp,
		})
		.done(function(data) {
			$("#respuesta_").html(data);
		})
		return false;
	});

	$(document).on('click', '.guardaNewCotiza', function(event) {
		event.preventDefault();
		var ClienteNombre	=	$("#ClienteNombre").val();
		var ClienteApellido	=	$("#ClienteApellido").val();
		var ClienteCorreo 	=	$("#ClienteCorreo").val();
		var ClienteCiudad	=	$("#ClienteCiudad_ option:selected").val();
		var FormaPago 		=	$("#FormaPago option:selected").val();
		var FinOferta 		=	$("#FinOferta").val();
		var TiempoEntrega 	=	$("#TiempoEntrega").val();

		if (ClienteNombre=='') {
			$(".emptyCliente_Nombre").removeClass('d-none');
			$(".infoProducto").addClass('d-none');
			$(".PreciosProductoSelected").addClass('d-none');
			$(".datosAdicionales").addClass('d-none');
			setTimeout(function(){
				$(".emptyCliente_Nombre").addClass('d-none');
				$(".infoProducto").removeClass('d-none');
				$(".PreciosProductoSelected").removeClass('d-none');
				$(".datosAdicionales").removeClass('d-none');
				$("#ClienteNombre").focus();
			},2000);
		}else if (ClienteApellido=='') {
			$(".emptyCliente_Apellido").removeClass('d-none');
			$(".infoProducto").addClass('d-none');
			$(".PreciosProductoSelected").addClass('d-none');
			$(".datosAdicionales").addClass('d-none');
			setTimeout(function(){
				$(".emptyCliente_Apellido").addClass('d-none');
				$(".infoProducto").removeClass('d-none');
				$(".PreciosProductoSelected").removeClass('d-none');
				$(".datosAdicionales").removeClass('d-none');
				$("#ClienteApellido").focus();
			},2000);
		}else if (ClienteCorreo=='') {
			$(".emptyCliente_Correo").removeClass('d-none');
			$(".infoProducto").addClass('d-none');
			$(".PreciosProductoSelected").addClass('d-none');
			$(".datosAdicionales").addClass('d-none');
			setTimeout(function(){
				$(".emptyCliente_Correo").addClass('d-none');
				$(".infoProducto").removeClass('d-none');
				$(".PreciosProductoSelected").removeClass('d-none');
				$(".datosAdicionales").removeClass('d-none');
				$("#ClienteCorreo").focus();
			},2000);
		}else if (ClienteCiudad == 'Seleccione Ciudad') {
			$(".emptyCliente_Ciudad").removeClass('d-none');
			$(".infoProducto").addClass('d-none');
			$(".PreciosProductoSelected").addClass('d-none');
			$(".datosAdicionales").addClass('d-none');
			setTimeout(function(){
				$(".emptyCliente_Ciudad").addClass('d-none');
				$(".infoProducto").removeClass('d-none');
				$(".PreciosProductoSelected").removeClass('d-none');
				$(".datosAdicionales").removeClass('d-none');
			},2000);
		}else if (FormaPago=='Forma de Pago') {
			$(".emptyFormaPago").removeClass('d-none');
			setTimeout(function(){
				$(".emptyFormaPago").addClass('d-none');
			},2000);
		}else if (FinOferta=='') {
			$(".emptyFinOferta").removeClass('d-none');
			setTimeout(function(){
				$(".emptyFinOferta").addClass('d-none');
			},2000);
		}else if (TiempoEntrega=='') {
			$(".emptyTiempoEntrega").removeClass('d-none');
			setTimeout(function(){
				$(".emptyTiempoEntrega").addClass('d-none');
			},2000);
		}else{
			$.ajax({
				url: 'do.php',
				type: 'POST',
				dataType: 'html',
				data: $("#newCotizacion").serialize(),	//"action=GenerarCotizacionn&NombreCliente="+ClienteNombre,
			})
			.done(function(data) {
				//$("#newCotizacion").addClass('d-none');
				//$(".cotizaOK").removeClass('d-none');
				$(".respuesta").html(data);
			})
			return false;
		}
	});

	$(document).on('click', '.editaSeccion', function(event) {
		event.preventDefault();
		$(".tableCotizaciones").addClass('d-none');
		$(".editProducto").removeClass('d-none');
		var Clave = $(this).attr('id');
		$.ajax({
			url: 'includes/getDataCotTemp.php',
			type: 'POST',
			dataType: 'html',
			data: {id: Clave},
		})
		.done(function(data) {
			$("#respuesta_").html(data);
		});
	});

	$(document).on('click', '.cancelarEditProducto', function(event) {
		event.preventDefault();
		$(".tableCotizaciones").removeClass('d-none');
		$(".editProducto").addClass('d-none');
	});

	$(document).on('click', '.editOptions', function(event) {
		event.preventDefault();
		$(".tableCotizaciones").addClass('d-none');
		$(".editOptionsProd").removeClass('d-none');
		var Clave = $(this).attr('id');	
		
		$.ajax({
			url: 'includes/getDataCotizacion.php',
			type: 'POST',
			dataType: 'json',
			data: {id: Clave},
			success:function(data){
				$("#FormaPago_").val(data.Forma_Pago);
				$("#FinOferta_").val(data.FinFecha_Oferta);
				$("#TiempoEntrega_").val(data.Dias_Entrega);
				$("#Observaciones_").val(data.Comentarios);
				$("#Clave_TempCotza").val(data.Clave);
			}
		})
	});

	$(document).on('click', '.cancelarOtherOptions', function(event) {
		event.preventDefault();
		$(".tableCotizaciones").removeClass('d-none');
		$(".editOptionsProd").addClass('d-none');
	});

	$(document).on('click', '.endProcess', function(event) {
		event.preventDefault();
		location.reload();
	});

	$(document).on('click', '.updataDataProd', function(event) {
		event.preventDefault();
		var FechaPago = $("#FormaPago_ option:selected").val();
		var FechaLimit= $("#FinOferta_").val();
		var TiempoEnt = $("#TiempoEntrega_").val();
		if (FechaPago=='Forma de Pago') {
			$(".empty_FormaPago").removeClass('d-none');
			setTimeout(function(){
				$(".empty_FormaPago").addClass('d-none');
			},2000);
		}else if (FechaLimit=='') {
			$(".empty_FinOferta").removeClass('d-none');
			setTimeout(function(){
				$(".empty_FinOferta").addClass('d-none');
			},2000);
		}else if (TiempoEnt=='') {
			$(".empty_TiempoEntrega").removeClass('d-none');
			setTimeout(function(){
				$(".empty_TiempoEntrega").addClass('d-none');
			},2000);
		}else{
			$.ajax({
			    url: 'do.php',
			    type: 'POST',
			    dataType: 'html',
			    data: $("#datosComp").serialize(),
		    })
		    .done(function(data) {
			    $(".respuesta").html(data);
		    })
		}		
		return false;
	});

	$(document).on('click', '.cambiarEntregada', function(event) {
		event.preventDefault();
		var idCotizacion = $(this).attr("id");
		$.ajax({
			url: 'do.php',
			type: 'POST',
			dataType: 'html',
			data: "action=CambiarEstadoCotiEntregada&id="+idCotizacion,
		})
		.done(function(data) {
			$(".respuesta").html(data);
		})
		return false;		
	});

	$(document).on('click', '.borrarCotizacion', function(event) {
		event.preventDefault();
		var idCotizacion = $(this).attr("id");
		$.ajax({
			url: 'do.php',
			type: 'POST',
			dataType: 'html',
			data: "action=borrarCotizacion&id="+idCotizacion,
		})
		.done(function(data) {
			$(".respuesta").html(data);
		})
		return false;		
	});

	$(document).on('click', '.enviarEmail', function(event) {
		event.preventDefault();
		alert("Estamos trabajando en esta función");
		return false;
	});
});