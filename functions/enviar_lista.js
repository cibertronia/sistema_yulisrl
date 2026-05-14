$(function () {
	$(document).on('click', '.cancelarTodoEnvio', function (event) {
		var idEnvio = $(this).attr("id");		
		const swalWithBootstrapButtons = Swal.mixin({
			customClass: {
				confirmButton: 'btn btn-success',
				cancelButton: 'btn btn-danger mx-2'
			},
			buttonsStyling: false
		})
		swalWithBootstrapButtons.fire({
			title: 'Estás seguro?',
			html: "Si continuas, El proceso de envio sera cancelado y el stock volvera a su respectiva sucursal de origen.",
			type: 'warning',
			showCancelButton: true,
			confirmButtonText: 'Sí, Cancelar proceso!',
			cancelButtonText: 'No, cancelar!',
			reverseButtons: true
		}).then((result) => {
			if (result.value) {
				$.ajax({
					url: 'includes/envios_recibir/acciones_enviar_repuestos.php',
					type: 'POST',
					dataType: 'json',
					data: {
						action: "cancelarProcesoStock",
						idEnvio: idEnvio
					},
					success: function (data) {
						if (data == 'ok') {
							Swal.fire({
								type: 'success',
								title: 'Cancelado Correctamente',
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
								text: 'Error al cancelar',
								type: 'error',
								confirmButtonText: 'Ok'
							})
							setTimeout(function () {
								location.replace("?root=enviar_lista");//cambiar
							}, 2500);
						}

					},
					error: function (xhr, status, error) {
						Swal.fire({
							title: 'Error',
							text: 'Error al cancelar el envio',
							type: 'error',
							confirmButtonText: 'Ok'
						})
						setTimeout(function () {
							location.replace("?root=enviar_lista");//cambiar
						}, 2500);
					}
				
				})

			} else if (
				/* Read more about handling dismissals below */
				result.dismiss === Swal.DismissReason.cancel
			) {
				swalWithBootstrapButtons.fire(
					'Cancelado',
					'El envio continuar&aacute; su proceso normalmente',
					'error'
				)
			}
		})
		event.preventDefault();
	});



});


