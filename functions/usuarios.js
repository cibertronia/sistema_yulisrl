$(document).ready(function () {
	$("#NewTelefono").mask('0000-0000');
	$("#Telefono").mask('0000-0000');
	$(document).on('click', '.AddUserBTN', function (event) {
		event.preventDefault();
		$(".formNewUser").removeClass('d-none');
		$(".tableUsers").addClass('d-none');
	});
	$(document).on('click', '.cancelarRegistro', function (event) {
		event.preventDefault();
		$(".formNewUser").addClass('d-none');
		$(".tableUsers").removeClass('d-none');
	});

	$(document).on('click', '.editUser', function (event) {
		event.preventDefault();
		$(".tableUsers").addClass('d-none');
		$(".editFormUser").removeClass('d-none');
		var idUser = $(this).attr("id");
		$.ajax({
			url: 'includes/getDataUsers.php',
			type: 'POST',
			dataType: 'json',
			data: { idUserEdit: idUser },
			success: function (data) {
				$("#idUser").val(data.idUser);
				$("#Nombres").val(data.Nombres);
				$("#Apellidos").val(data.Apellidos);
				$("#Telefono").val(data.Telefono);

				//seleccionar la opción correcta en el select
				$("#Sucursal").val(data.Ciudad);

				$("#Correo").val(data.Correo);
				$("#Sexo").val(data.Sexo);
				$("#Sexo option:selected").html(data.Sexo);
				$("#newUserRango_").val(data.Cargo);
				$("#newUserRango_ option:selected").html(data.Cargo);
			}
		})
	});

	$(document).on('click', '.cancelareditRegistro', function (event) {
		event.preventDefault();
		$(".tableUsers").removeClass('d-none');
		$(".editFormUser").addClass('d-none');
	});

	$(document).on('click', '.cancelarAnular', function (event) {
		event.preventDefault();

		$(".cochabamba").removeClass('d-none');
		$(".lapaz").removeClass('d-none');
		$(".santacruz").removeClass('d-none');
		$(".tarija").removeClass('d-none');
		$(".editFormUserAd").addClass('d-none');


	});


	$(document).on('click', '.offUser', function (event) {
		event.preventDefault();
		var idUser = $(this).attr("id");
		$.ajax({
			url: 'do.php',
			type: 'POST',
			dataType: 'html',
			data: "action=DeshabilitarUsuario&id=" + idUser,
		})
			.done(function (data) {
				$(".respuesta").html(data);
			})
		return false;
	});

	$(document).on('click', '.ONUser', function (event) {
		event.preventDefault();
		var idUser = $(this).attr("id");
		$.ajax({
			url: 'do.php',
			type: 'POST',
			dataType: 'html',
			data: "action=HabilitarUsuario&id=" + idUser,
		})
			.done(function (data) {
				$(".respuesta").html(data);
			})
		return false;
	});

	$(document).on('click', '.deleteUser', function (event) {
		event.preventDefault();
		var idUser = $(this).attr("id");
		const swalWithBootstrapButtons = Swal.mixin({
			customClass: {
				confirmButton: 'btn btn-success',
				cancelButton: 'btn btn-danger'
			},
			buttonsStyling: false
		})

		swalWithBootstrapButtons.fire({
			title: 'Estás seguro?',
			html: "Si continuas, no podrás deshacer los cambios.",
			type: 'warning',
			showCancelButton: true,
			confirmButtonText: 'Sí, borrar!',
			cancelButtonText: 'No, cancelar!',
			reverseButtons: true
		}).then((result) => {
			if (result.value) {
				$.ajax({
					url: 'do.php',
					type: 'POST',
					dataType: 'html',
					data: "action=BorrarUsuario&id=" + idUser,
				})
					.done(function (data) {
						$(".respuesta").html(data);
					})
				return false;
			} else if (
				/* Read more about handling dismissals below */
				result.dismiss === Swal.DismissReason.cancel
			) {
				swalWithBootstrapButtons.fire(
					'Cancelado',
					'El usuario ya no será borrado',
					'error'
				)
			}
		})
	});

	$(document).on('click', '.regNewUser', function (event) {
		event.preventDefault();
		var Name = $("#NewNombres").val();
		var LastName = $("#NewApellidos").val();
		var Telefono = $("#NewTelefono").val();
		var Sucursal = $("#NewSucursal option:selected").val();
		var Correo = $("#NewCorreo").val();
		var Sexo = $("#newUserSexo option:selected").val();
		var Rango = $("#newUserRango option:selected").val();
		$("#Ciudad").val($("#NewSucursal option:selected").text());

		if (Name == '') {
			$("#NewNombres").focus();
			$("#NewNombres").addClass('is-invalid');
			setTimeout(function () {
				$("#NewNombres").removeClass('is-invalid');
			}, 3000);
		} else if (LastName == '') {
			$("#NewApellidos").focus();
			$("#NewApellidos").addClass('is-invalid');
			setTimeout(function () {
				$("#NewApellidos").removeClass('is-invalid');
			}, 3000);
		} else if (Telefono == '') {
			$("#NewTelefono").focus();
			$("#NewTelefono").addClass('is-invalid');
			setTimeout(function () {
				$("#NewTelefono").removeClass('is-invalid');
			}, 3000);
		} else if ((Telefono).length < 9) {
			$("#NewTelefono").focus();
			$(".nimLength").removeClass('d-none');
			setTimeout(function () {
				$(".nimLength").addClass('d-none');
			}, 3000);
		} else if (Sucursal == 'Seleccione Sucursal') {
			$(".emptyNewSucursal").removeClass('d-none');
			setTimeout(function () {
				$(".emptyNewSucursal").addClass('d-none');
			}, 3000);
		} else if (Correo == '') {
			$("#NewCorreo").focus();
			$("#NewCorreo").addClass('is-invalid');
			setTimeout(function () {
				$("#NewCorreo").removeClass('is-invalid');
			}, 3000);
		} else if (Sexo == 'Sexo') {
			$(".emptyNewSexo").addClass('d-none');
			setTimeout(function () {
				$(".emptyNewSexo").removeClass('d-none');
			}, 3000);
		} else if (Rango == 'Rango') {
			$(".emptyNewRango").addClass('d-none');
			setTimeout(function () {
				$(".emptyNewRango").removeClass('d-none');
			}, 3000);
		} else {
			$(".regNewUser").attr('disabled', true);
			$.ajax({
				url: 'do.php',
				type: 'POST',
				dataType: 'html',
				data: $("#newUser").serialize(),
			})
				.done(function (data) {
					$(".respuesta").html(data);
				})
			//return false;
		} return false;
	});

	$(document).on('click', '.editarNewUser', function (event) {
		event.preventDefault();
		var Name = $("#Nombres").val();
		var LastName = $("#Apellidos").val();
		var Telefono = $("#Telefono").val();
		var Sucursal = $("#Sucursal option:selected").val();
		var Correo = $("#Correo").val();
		var Sexo = $("#Sexo option:selected").val();

		if (Name == '') {
			$("#Nombres").focus();
			$("#Nombres").addClass('is-invalid');
			setTimeout(function () {
				$("#Nombres").removeClass('is-invalid');
			}, 3000);
		} else if (LastName == '') {
			$("#Apellidos").focus();
			$("#Apellidos").addClass('is-invalid');
			setTimeout(function () {
				$("#Apellidos").removeClass('is-invalid');
			}, 3000);
		} else if (Telefono == '') {
			$("#Telefono").focus();
			$("#Telefono").addClass('is-invalid');
			setTimeout(function () {
				$("#Telefono").removeClass('is-invalid');
			}, 3000);
		} else if ((Telefono).length < 9) {
			$("#Telefono").focus();
			$(".nimLength_").removeClass('d-none');
			setTimeout(function () {
				$(".nimLength_").addClass('d-none');
			}, 3000);
		} else if (Sucursal == 'Seleccione Sucursal') {
			$(".emptySucursal").removeClass('d-none');
			setTimeout(function () {
				$(".emptySucursal").addClass('d-none');
			}, 3000);
		} else if (Correo == '') {
			$("#Correo").focus();
			$("#Correo").addClass('is-invalid');
			setTimeout(function () {
				$("#Correo").removeClass('is-invalid');
			}, 3000);
		} else if (Sexo == 'Sexo') {
			$(".emptySexo").addClass('d-none');
			setTimeout(function () {
				$(".emptySexo").removeClass('d-none');
			}, 3000);
		} else {
			$.ajax({
				url: 'do.php',
				type: 'POST',
				dataType: 'html',
				data: $("#editUserList").serialize(),
			})
				.done(function (data) {
					$(".respuesta").html(data);
				})
			return false;
		} return false;
	});

	$(document).on('click', '.btnAnular', function (event) {
		event.preventDefault();

		var id = $(this).attr("id");
		$(".btnAnular").attr('disabled', true);
		Swal.fire({
			type: 'info',
			title: "CONECTANDO CON IMPUESTOS NACIONALES",
			animation: false,
			customClass: {
				popup: 'animated bounceInDown'
			},
			text: "La Conexion Con SIAT Puede Demorar Varios Segundos Espere Por Favor",
			icon: "success",
			button: "Ok",
			timer: 15000,
		})

		$.ajax({
			type: "POST",
			dataType: 'json',
			url: 'includes/api_facturacion/anular_factura.php',
			data: $("#editUserList").serialize(),
			success: function (data) {
				if (data == 'ok') {
					console.log('Anulación Exitosa');
					Swal.fire({
						type: 'success',
						title: "ANULACIÓN EXITOSA",
						animation: false,
						customClass: {
							popup: 'animated bounceInDown'
						},
						text: "Factura Anulada Correctamente",
						timer: 5000,
					});
					setTimeout(function () {
						location.replace('?root=facturacionListado');
					}, 5000);

				} else {
					Swal.fire({
						type: 'error',
						title: "ERROR",
						animation: false,
						customClass: {
							popup: 'animated bounceInDown'
						},
						text: "Puede que la factura ya este anulada , Intente Nuevamente",
						icon: "error",
						button: "Ok",
						timer: 15000,
					});
					setTimeout(function () {
						location.replace('?root=facturacionListado');
					}, 5000);
				}
			}

		})
	});
	$(document).on('click', '.btnEnviar2', function (event) {
		event.preventDefault();

		var id = $(this).attr("id");

		$.ajax({
			type: "POST",
			url: 'Paginas/facturacionEmail.php',
			data: $("#editUserList2").serialize(),
			success: function (data) {
				$('#resp2').html(data);

			}

		})
	});
	$(document).on('click', '.emailAd', function (event) {
		event.preventDefault();

		$(".cochabamba").addClass('d-none');
		$(".lapaz").addClass('d-none');
		$(".santacruz").addClass('d-none');
		$(".tarija").addClass('d-none');
		$(".editEmailAd").removeClass('d-none');
		var id = $(this).attr("id");
		$.ajax({
			url: 'includes/getDataFactura.php',
			type: 'POST',
			dataType: 'json',
			data: { id: id },
			success: function (data) {
				$("#invoiceNumber").val(data.invoiceNumber);
				$("#invoiceCode").val(data.invoiceCode);
				$("#clientEmail").val(data.clientEmail);
				$("#tipoFactura").val(data.tipoFactura);
				$("#branchId").val(data.branchId);

			}
		})
	});
	$(document).on('click', '.cancelarEmailAd', function (event) {
		event.preventDefault();
		$(".editEmailAd").addClass('d-none');
		$(".cochabamba").removeClass('d-none');
		$(".lapaz").removeClass('d-none');
		$(".santacruz").removeClass('d-none');
		$(".tarija").removeClass('d-none');
		$(".editFormUserAd").addClass('d-none');


	});
	$(document).on('click', '.btnEnviarAd', function (event) {
		event.preventDefault();

		var id = $(this).attr("id");

		$.ajax({
			type: "POST",
			url: 'Paginas/facturacionEmail.php',
			data: $("#editUserList").serialize(),
			success: function (data) {
				$('#resp').html(data);

			}

		})
	});
	$(document).on('click', '.btnAnular1', function (event) {
		event.preventDefault();
		$(".tableUsers").addClass('d-none');
		$(".FormAnulation1").removeClass('d-none');
		var id = $(this).attr("id");
		$.ajax({
			url: 'includes/getDataFactura.php',
			type: 'POST',
			dataType: 'json',
			data: { id: id },
			success: function (data) {
				$("#invoiceNumber1").val(data.invoiceNumber);
				$("#invoiceCode1").val(data.invoiceCode);
				$("#clientEmail1").val(data.clientEmail);
				$("#tipoFactura1").val(data.tipoFactura);
				$("#branchId1").val(data.branchId);

			}
		})
	});
	$(document).on('click', '.cancelar1', function (event) {
		event.preventDefault();
		$(".tableUsers").removeClass('d-none');
		$(".FormAnulation1").addClass('d-none');
	});

	$(document).on('click', '.btnEmail2', function (event) {
		event.preventDefault();
		$(".tableUsers").addClass('d-none');
		$(".editEmail2").removeClass('d-none');
		var id = $(this).attr("id");
		$.ajax({
			url: 'includes/getDataFactura.php',
			type: 'POST',
			dataType: 'json',
			data: { id: id },
			success: function (data) {

				$("#invoiceCode2").val(data.invoiceCode);
				$("#clientEmail2").val(data.clientEmail);

			}
		})
	});
	$(document).on('click', '.cancelarEmail2', function (event) {
		event.preventDefault();
		$(".tableUsers").removeClass('d-none');
		$(".editEmail2").addClass('d-none');


	});
	$(document).on('click', '.btnAnularCocha1', function (event) {
		event.preventDefault();

		$(".cochabamba1").addClass('d-none');
		$(".lapaz").addClass('d-none');
		$(".santacruz").addClass('d-none');
		$(".tarija").addClass('d-none');
		$(".editFormUserAd").removeClass('d-none');
		var id = $(this).attr("id");
		$.ajax({
			url: 'includes/getDataFactura.php',
			type: 'POST',
			dataType: 'json',
			data: { id: id },
			success: function (data) {
				$("#invoiceNumber").val(data.invoiceNumber);
				$("#invoiceCode").val(data.invoiceCode);
				$("#clientEmail").val(data.clientEmail);
				$("#tipoFactura").val(data.tipoFactura);
				$("#branchId").val(data.branchId);

			}
		})
	});

	//VER FACTURA
	$(document).on('click', '.ver_pdf', function (event) {
		let invoiceCode = $(this).attr("id");
		let url = './Paginas/factura_pdf.php?invoiceCode=' + invoiceCode;
		console.log('invoiceCode');
		console.log(invoiceCode);
		console.log(url);
		window.open(url, '_blank');
		//document.getElementById('pdfFactura').submit();

	});
	
	$(document).on('click', '.imprimirFacturas', function (event) {
		// Obtener valores de las fechas
		const fechaInicio = document.getElementById('fechaInicio').value;
		const fechaFin = document.getElementById('fechaFin').value;
		const selectElement = document.getElementById('ssucursal');
  
  		// Obtener el valor seleccionado
  		const selectedValue = selectElement.value;

		// Validar que las fechas no estén vacías
		if (!fechaInicio || !fechaFin) {
			alert('Por favor, selecciona ambas fechas.');
			return;
		}

		// URL del servicio (modifica con la URL real)
		const url = `https://joinpdfs.yulisrl.com/api/facturas?inicio=${fechaInicio}&fin=${fechaFin}&sucursal=${selectedValue}`;
		window.open(url, '_blank');
		// Consumir el servicio con fetch
	});

	// TABLA DE FACTURAS
	const rango = document.getElementById('page-container').getAttribute('data-rango');
	const domTable = (rango === '2') ? '<"row"<"col-sm-5"B><"col-sm-7"fr>>t<"row"<"col-sm-5"i><"col-sm-7"p>>' : '<"row"<"col-sm-5"><"col-sm-7"f>>t<"row"<"col-sm-5"i><"col-sm-7"p>>';
	const tablaFacturas = $('#data-table-buttons-facturas').DataTable({
		dom: domTable,
		buttons: [
			{ extend: 'copy', className: 'btn-sm' },
			{ extend: 'csv', className: 'btn-sm' },
			{ extend: 'excel', className: 'btn-sm' },
			{ extend: 'pdf', className: 'btn-sm' },
			{ extend: 'print', className: 'btn-sm' }
		],
		responsive: true
	});
});

//función asíncrona verificar-validar token en la bd
(async () => {
	let datosPOST = "token_validar=" + "token_validar";
	console.log('verificando token srl');
	try {
		let response = await $.ajax({
			url: "includes/api_facturacion/token_validar.php",
			type: "POST",
			dataType: "json",
			data: datosPOST,
		});
		console.log(response);
	} catch (error) {
		console.error("Error en la llamada AJAX:", error);
	}
})();

//función asíncrona verificar-validar token en la bd yuliimport 01
(async () => {
	let datosPOST = "token_validar=" + "token_validar";
	console.log('verificando token Yuliimport');
	try {
		let response = await $.ajax({
			url: "includes/api_facturacion_yuliimport/token_validar.php",
			type: "POST",
			dataType: "json",
			data: datosPOST,
		});
		console.log(response);
	} catch (error) {
		console.error("Error en la llamada AJAX:", error);
	}
})();