$(document).ready(function () {
	//Click Evento Facturar Doble 01 yuli y 02 srl
	$(document).on('click', '.facturar_doble_01yuli_02srl', function (event) {

		event.preventDefault();
		if (validarCampos()) {
			$('.facturar_doble_01yuli_02srl').attr('disabled', true);  //boton facturar desactivar
			$(".efecto_spiner").removeClass('d-none');

			(async () => {
				try {
					const cliente_creado_01yuli = await primeraLlamadaCrearCliente_01yuli();
					const factura_creada_01yuli = await segundaLlamadaFacturar_01yuli(cliente_creado_01yuli);

					//invoice_id de la factura creada en 01yuli
					let yuli_invoice_id = factura_creada_01yuli.data.invoice_id;
					//numero de factura de la factura creada en 01yuli
					let yuli_invoice_number = factura_creada_01yuli.data.invoice_number;

					if (factura_creada_01yuli.response == 'ok') {
						// Pausa durante 7 segundos (7000 milisegundos)
						await new Promise(resolve => setTimeout(() => {
							console.log("Han pasado 7 segundos");
							resolve();
						}, 7000));

						const cliente_creado_02srl = await primeraLlamadaCrearCliente_02srl();
						const factura_creada_02srl = await segundaLlamadaFacturar_02srl(cliente_creado_02srl, yuli_invoice_id, yuli_invoice_number);
						console.log(factura_creada_02srl);

						// Pausa durante 7 segundos (7000 milisegundos)
						await new Promise(resolve => setTimeout(() => {
							if (factura_creada_02srl.response == 'ok') {
								console.log("doble factura ok");
								swall_factura_doble_exitosa();
								setTimeout(function () {
									location.replace('?root=facturacionListado');
								}, 8000);

							} else {
								console.log('Error 2da factura srl02');
								swall_error_conexion();
								setTimeout(function () {
									location.reload();
								}, 15000);
							}
							resolve();
						}, 7000));
					} else {
						console.log('Error 1ra factura yuliimport01');
						swall_error_conexion();
						setTimeout(function () {
							location.reload();
						}, 8000);
					}
				} catch (error) {
					console.error("Ocurrió un error:", error);
					setTimeout(function () {
						location.reload();
					}, 8000);
				}
			})();
		}
	});
});

function swall_factura_doble_exitosa() {
	Swal.fire({
		type: 'success',
		title: "FACTURACIÓN DOBLE EXITOSA",
		animation: true,
		customClass: {
			popup: 'animated bounceInDown'
		},
		text: "Factura Yuliimport y Yuli SRL Guardadas Correctamente",
	});
}

function validarCampos() {
	let validado = false;
	// Obtener los valores de los campos
	let clientReasonSocial = document.getElementById('clientReasonSocial_01yuli').value.trim();
	let clientNroDocument = document.getElementById('clientNroDocument_01yuli').value.trim();
	let clientEmail = document.getElementById('clientEmail_01yuli').value.trim();
	let clientCity = document.getElementById('clientCity_01yuli').value.trim();
	let total_01yuli = document.getElementById('total_01yuli');
	total_01yuli = parseFloat(total_01yuli.value);

	let clientReasonSocial_02srl = document.getElementById('clientReasonSocial_02srl').value.trim();
	let clientNroDocument_02srl = document.getElementById('clientNroDocument_02srl').value.trim();
	let clientEmail_02srl = document.getElementById('clientEmail_02srl').value.trim();
	let clientCity_02srl = document.getElementById('clientCity_02srl').value.trim();
	let total_02srl = document.getElementById('total_02srl');
	total_02srl = parseFloat(total_02srl.value);

	// Verificar si algún campo está vacío
	if (clientReasonSocial === '') {
		mostrarError('Razón social - Cliente Yuliimport');
	} else if (clientNroDocument === '') {
		mostrarError('Número Documento - Cliente Yuliimport');
	} else if (clientEmail === '') {
		mostrarError('Email - Cliente Yuliimport');
	} else if (clientCity === '') {
		mostrarError('Ciudad - Cliente Yuliimport');
	} else if (clientReasonSocial_02srl === '') {
		mostrarError('Razón social - Cliente Yuli SRL');
	} else if (clientNroDocument_02srl === '') {
		mostrarError('Número Documento - Cliente Yuli SRL');
	} else if (clientEmail_02srl === '') {
		mostrarError('Email - Cliente Yuli SRL');
	} else if (clientCity_02srl === '') {
		mostrarError('Ciudad - Cliente Yuli SRL');
	} else if (!total_01yuli || total_01yuli <= 0) {
		mostrarError('Tabla de Facturación Yuliimport');
	} else if (!total_02srl || total_02srl <= 0) {
		mostrarError('Tabla de Facturación Yuli Srl');
	}
	else {
		console.log('Todos los campos estan correctos');
		validado = true;
	}
	return validado;
}

function mostrarError(campo) {
	Swal.fire({
		icon: 'error',
		title: 'Error',
		text: `Falta llenar el campo: ${campo}`,
		button: "Ok",
	});
}









