$(function () {
	$(document).on('click', '.recibirProducto', function (event) {
		let idEnvio = $(this).attr('id');
		const swalWithBootstrapButtons = Swal.mixin({
			customClass: {
				confirmButton: 'btn btn-success',
				cancelButton: 'btn btn-danger mx-2'
			},
			buttonsStyling: false
		})
		swalWithBootstrapButtons.fire({
			title: 'Estás seguro?',
			html: "Estás a punto de confirmar que el envi&oacute; ha sido recibido.",
			type: 'warning',
			showCancelButton: true,
			confirmButtonText: 'Sí, confirmar!',
			cancelButtonText: 'No, cancelar!',
			reverseButtons: true
		}).then((result) => {
			if (result.value) {
				$.ajax({
					url: 'includes/envios_recibir/acciones_enviar_repuestos.php',
					type: 'POST',
					dataType: 'json',
					data: "action=confirmarEnvioStock&idEnvio=" + idEnvio,
					success: function (data) {
						if (data.success == 'ok') {
							Swal.fire({
								type: 'success',
								title: 'Recepción Exitosa',
								animation: true,
								customClass: {
									popup: 'animated bounceInDown'
								}
							})
							setTimeout(function () {
								// location.replace("?root=envios_recibir");//cambiar
								location.reload();
							}, 2500);
						} else {
							Swal.fire({
								title: 'Error',
								text: 'Error al recepcionar',
								type: 'error',
								confirmButtonText: 'Ok'
							})
							setTimeout(function () {
								// location.replace("?root=envios_recibir");//cambiar
								location.reload();
							}, 2500);
						}

					}
				})

				return false;
			} else if (
				/* Read more about handling dismissals below */
				result.dismiss === Swal.DismissReason.cancel
			) {
				swalWithBootstrapButtons.fire(
					'Cancelado',
					'El envio no será confirmado.',
					'error'
				)
			}
		})
		event.preventDefault();
	});



});


