$(function () {

	//$('#repuestos_enviar').select2();
	$('#repuestos_enviar').select2({
		dropdownParent: $('#modal_agregar_repuestos_sistema .modal-body')
	});
	$('#sucursal_origen').select2();
	$('#sucursal_destino').select2();
	$(".tooltip").hide();

	$("#repuestos_enviar").change(function () {
		$("#repuestos_enviar option:selected").each(function () {
			$('#cantidad').val('1');
			let id_repuesto = $(this).val();
			console.log(id_repuesto);
			// Obtener el valor del atributo data-stock-select del option seleccionado
			let stockSeleccionado = $('#repuestos_enviar option:selected').attr('st');
			console.log(stockSeleccionado); // Mostrará el valor de data-stock-select del option seleccionado
			$("#stock_actual").val(stockSeleccionado);
		});
	});

	$('#agregar-repuesto').click(function () {
		let stock_actual = $('#stock_actual').val();

		let clave = $('#clave').val();
		let id_producto = $('#repuestos_enviar').val();
		let cantidad = $('#cantidad').val();

		if (parseInt(cantidad) > parseInt(stock_actual) || parseInt(cantidad) <= 0) {
			// cerramos modal
			let closeButton = document.querySelector('.close[data-dismiss="modal"]');
			closeButton.click();// Simula el clic en el botón de cierre
			Swal.fire({
				title: 'Error',
				text: 'La cantidad a enviar no puede ser mayor al stock actual',
				icon: 'error',
				confirmButtonText: 'Ok'
			});
			return false; // Salimos de la función si la validación falla
		}
		else if ((cantidad) == '' || stock_actual == '') {
			// cerramos modal
			let closeButton = document.querySelector('.close[data-dismiss="modal"]');
			closeButton.click();// Simula el clic en el botón de cierre
			Swal.fire({
				title: 'Error',
				text: 'Error Al Agregar a la Cola',
				icon: 'error',
				confirmButtonText: 'Ok'
			});
			return false; // Salimos de la función si la validación falla
		}
		else {
			let datosPOST = "action=agregar_repuesto_cola" +
				"&clave=" + clave +
				"&id_producto=" + id_producto +
				"&cantidad=" + cantidad;

			console.log(datosPOST);
			$(".resp_cola_temporal").html('');
			$.ajax({
				url: 'includes/envios_recibir/acciones_enviar_repuestos.php',
				type: 'POST',
				dataType: 'html',
				data: datosPOST,
				success: function (data) {
					$(".resp_cola_temporal").html(data);
					$('#repuestos_enviar').val('null');
					$('#repuestos_enviar').change();
					$('#cantidad').val('1');
					$('#stock_actual').val('');

					// cerramos modal
					let closeButton = document.querySelector('.close[data-dismiss="modal"]');
					closeButton.click();// Simula el clic en el botón de cierre
				}
			})
		}
	});

	$(document).on('click', '.remover_repuesto_temporal', function (event) {

		let clave = $("#clave").val();
		let id_clave = $(this).attr('id');

		let datosPOST = "action=remover_repuesto_temporal" +
			"&clave=" + clave +
			"&id_clave=" + id_clave;

		$.ajax({
			url: 'includes/envios_recibir/acciones_enviar_repuestos.php',
			type: 'POST',
			dataType: 'html',
			data: datosPOST,
			success: function (data) {
				$(".resp_cola_temporal").html(data);
			}
		})

		event.preventDefault();
	});
	$('#terminar_envio').click(function () {

		let clave = $("#clave").val();
		let id_origen = $("#sucursal_origen").val();
		let id_destino = $("#sucursal_destino").val();

		let tecnico = $("#tecnico").val();
		let observaciones = $("#observaciones").val();
		let cantidad_temporales = $("#cantidad_temporales").val();

		if (tecnico == '') {
			Swal.fire({
				title: 'Error',
				text: 'Ingrese Técnico Encargado',
				type: 'error',
				confirmButtonText: 'Ok'
			});
			return false; // Salimos de la función si la validación falla
		}
		else if ((cantidad_temporales == '' || cantidad_temporales == null || cantidad_temporales == undefined || parseInt(cantidad_temporales) == 0)) {
			Swal.fire({
				title: 'Error',
				text: 'No hay productos en la lista de envio',
				type: 'error',
				confirmButtonText: 'Ok'
			});
			return false; // Salimos de la función si la validación falla
		}
		else {
			swal.fire({
				title: "ENVIAR TODOS LOS PRODUCTOS EN LA LISTA ?",
				text: "Asegurese que todos los productos de la lista son correctos",
				type: 'info',
				showCancelButton: true,
				confirmButtonText: "Sí, Enviar",
				cancelButtonText: "Cancelar",
			})
			.then(resultado => {
				if (resultado.value) {
					$("#terminar_envio").attr('disabled', true);
					let datosPOST = "action=guardar_envio_stock" +
						"&clave=" + clave +
						"&id_origen=" + id_origen +
						"&id_destino=" + id_destino +
						"&tecnico=" + tecnico +
						"&observaciones=" + observaciones;

					$(".respuesta_terminar_envio").html('');
					console.log(datosPOST);
					$.ajax({
						url: 'includes/envios_recibir/acciones_enviar_repuestos.php',
						type: 'POST',
						dataType: 'json',
						data: datosPOST,
						success: function (data) {
							if (data == 'ok') {
								Swal.fire({
									type: 'success',
									title: 'Envio Completado',
									animation: true,
									customClass: {
										popup: 'animated bounceInDown'
									}
								})
								setTimeout(function () {
									location.replace("?root=enviar_lista");//cambiar
								}, 2500);
							} else {
								
								Swal.fire({
									title: 'Error',
									text: data.error,
									type: 'error',
									confirmButtonText: 'Ok'
								})
								setTimeout(function () {
									location.replace("?root=enviar_productos");//cambiar
								}, 8000);
							}

						},
						error: function (xhr, status, error) {
							console.log(error);
							
							setTimeout(function () {
								location.replace("?root=enviar_productos");//cambiar
							}, 6000);
							Swal.fire({
								title: 'Error',
								text: 'Error al completar envio',
								type: 'error',
								confirmButtonText: 'Ok'
							})
						}
					})
					return false;

				}
			});
		}



	});

	//agregar elemento extra temporal
	$('#btn_agregar_elemento_extra').click(function () {
		let clave = $('#clave').val();
		let nombre = $('#nombre_extra').val();
		let cantidad = $('#cantidad_extra').val();
		let precio = $('#precio_extra').val();
		precio = (precio == '') ? 0 : precio;

		let marca = $('#marca_extra').val();
		marca = (marca == '') ? ' ' : marca;
		let modelo = $('#modelo_extra').val();
		modelo = (modelo == '') ? ' ' : modelo;

		if ((cantidad) == '' || nombre == '') {
			// cerramos modal
			let closeButton = document.querySelector('.cerrar_modal_extras[data-dismiss="modal"]');
			closeButton.click();// Simula el clic en el botón de cierre
			Swal.fire({
				title: 'Error',
				text: 'Error Al Agregar a la Cola',
				icon: 'error',
				confirmButtonText: 'Ok'
			});
			return false; // Salimos de la función si la validación falla
		}
		else {
			let datosPOST = "action=agregar_elemento_extra_cola" +
				"&clave=" + clave +
				"&nombre=" + nombre +
				"&cantidad=" + cantidad +
				"&precio=" + precio +
				"&marca=" + marca +
				"&modelo=" + modelo;
			console.log(datosPOST);
			$(".resp_cola_extras").html('');
			$.ajax({
				url: 'includes/envios_recibir/acciones_enviar_repuestos.php',
				type: 'POST',
				dataType: 'html',
				data: datosPOST,
				success: function (data) {
					$(".resp_cola_extras").html(data);
					$('#nombre_extra').val('');
					$('#cantidad_extra').val('1');
					$('#precio_extra').val('0');
					$('#marca_extra').val('');
					$('#modelo_extra').val('');
					// cerramos modal
					let closeButton = document.querySelector('.cerrar_modal_extras[data-dismiss="modal"]');
					closeButton.click();// Simula el clic en el botón de cierre
				}
			})
		}
	});

	$(document).on('click', '.remover_elemento_extra_temporal', function (event) {

		let clave = $("#clave").val();
		let id = $(this).attr('id');

		let datosPOST = "action=remover_elemento_extra_temporal" +
			"&clave=" + clave +
			"&id=" + id;

		$.ajax({
			url: 'includes/envios_recibir/acciones_enviar_repuestos.php',
			type: 'POST',
			dataType: 'html',
			data: datosPOST,
			success: function (data) {
				$(".resp_cola_extras").html(data);
			}
		})

		event.preventDefault();
	});



});


