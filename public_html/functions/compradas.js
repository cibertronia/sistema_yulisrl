$(document).ready(function () {
	$(document).on('click', '.llamarObservNotaEntrega', function (event) {
		event.preventDefault();
		var idNotaE = $(this).attr('id');
		$.ajax({
			url: 'includes/consultas.php',
			type: 'POST',
			dataType: 'json',
			data: { idNotadeEntrega: idNotaE },
			success: function (data) {
				$("#idNotaEntrega").val(data.idNotaE);
				if (data.Observaciones == '') {
					$("#ObservNotaEntrega").val("");
					$(".guardarObv").removeClass('d-none');
					$(".actualizarObv").addClass('d-none');
				} else {
					$("#ObservNotaEntrega").val(data.Observaciones);
					$(".actualizarObv").removeClass('d-none');
					$(".guardarObv").addClass('d-none');

				}
			}
		})
	});

	$(document).on('click', '.saveObsv', function (event) {
		event.preventDefault();
		var Obsv = $("#ObservNotaEntrega").val();
		if (Obsv == '') {
			$(".noObsv").removeClass('d-none');
			$("#ObservNotaEntrega").addClass('parsley-error');
			setTimeout(function () {
				$(".noObsv").addClass('d-none');
				$("#ObservNotaEntrega").removeClass('parsley-error');
				$("#ObservNotaEntrega").focus();
			}, 2500);
		} else {
			$.ajax({
				url: 'do.php',
				type: 'POST',
				dataType: 'html',
				data: $("#NotaComments").serialize(),
			})
				.done(function (data) {
					$(".respuesta").html(data);
				})
			return false;
		}
	});
	$(document).on('click', '.findCompras', function (event) {
		event.preventDefault();
		$("#findCompras").removeClass('d-none');
		$(".findCompras").addClass('d-none');
	});
	$(document).on('click', '.Buscar', function (event) {
		event.preventDefault();
		$("#buscar").removeClass('d-none');
	});
	$(document).on('click', '.alertDelVenta', function (event) {		
		event.preventDefault();
		var idCotizacion = $(this).attr('id');
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
		})
		.then((result) => {
			if (result.value) {
				$.ajax({
					url: 'do.php',
					type: 'POST',
					dataType: 'html',
					data: "action=eliminarVentaDirecta&idCotizacion=" + idCotizacion,
				})
				.done(function (data) {
					$(".respuesta").html(data);
				})
				return false;
			} else if (result.dismiss === Swal.DismissReason.cancel) {
				swalWithBootstrapButtons.fire(
					'Cancelado',
					'La venta no será eliminada',
					'error'
				)
			}
		})
	});
	$(document).on('click', '.openFormRecibo', function (event) {
		event.preventDefault();
		var idRecibo = $(this).attr('id');
		$(".fomrVentaCash").removeClass('d-none');
		$(".tableCotizaciones").addClass('d-none');
		$.ajax({
			url: 'includes/consultas.php',
			type: 'POST',
			dataType: 'json',
			data: { editReciboVentaCash: idRecibo },
			success: function (data) {
				$("#idRecibo").val(idRecibo);
				$("#Code_Cotiza").val(data.CodeCotizacion)
				$("#selectedMoneda").val(data.Moneda);
				$("#precio_Dolar").val(data.PrecioDolar);
				if (data.Moneda == 'USD') {
					$("#porCantidad").val(data.CantidadUSD);
				} else {
					$("#porCantidad").val(data.Cantidad);
				}
				$("#cantidadLetras").val(data.Cant_Letras);
				$("#en_Conceptode").val(data.Concepto);
				var idCliente = data.idCliente;
				$.ajax({
					url: 'includes/consultas.php',
					type: 'POST',
					dataType: 'json',
					data: { idCliente },
					success: function (data) {
						$("#ClienteName").val(data.Nombres + " " + data.Apellidos);
					}
				})
			}
		})
	});
	$(document).on('click', '.openFormRecibo2', function (event) {
		event.preventDefault();
		var idRecibo = $(this).attr('id');
		$(".fomrVentaCash").removeClass('d-none');
		$(".tableCotizaciones").addClass('d-none');
		$.ajax({
			url: 'includes/consultas.php',
			type: 'POST',
			dataType: 'json',
			data: { editReciboVentaCash: idRecibo },
			success: function (data) {
				$("#idRecibo").val(idRecibo);
				$("#Code_Cotiza").val(data.CodeCotizacion)
				$("#selectedMoneda").val(data.Moneda);
				$("#precio_Dolar").val(data.PrecioDolar);
				if (data.Moneda == 'USD') {
					$("#porCantidad").val(data.CantidadUSD);
				} else {
					$("#porCantidad").val(data.Cantidad);
				}
				$("#cantidadLetras").val(data.Cant_Letras);
				$("#en_Conceptode").val(data.Concepto);
				var idCliente = data.idCliente;
				$.ajax({
					url: 'includes/consultas.php',
					type: 'POST',
					dataType: 'json',
					data: { idCliente },
					success: function (data) {
						$("#ClienteName").val(data.Nombres + " " + data.Apellidos);
					}
				})
			}
		})
	});
	$(document).on('click', '.closeFormVenta', function (event) {
		event.preventDefault();
		$(".fomrVentaCash").addClass('d-none');
		$(".tableCotizaciones").removeClass('d-none');
	});
	$("#formVenta").submit(function (event) {
		$(".guardarPago").attr('disabled', true);
		$(".savePay").removeClass('d-none');
		$.ajax({
			url: 'do.php',
			type: 'POST',
			dataType: 'html',
			data: $("#formVenta").serialize(),
		})
			.done(function (data) {
				$(".respuesta").html(data);
			})
		return false;
	});
	$(document).on('click', '.selectAllCheck', function (event) {
		event.preventDefault();
		$("input[type=checkbox]").prop('checked', true);
		$(".selectAllCheck").after('<button class="btn btn-xs btn-info unselectAllCheck btn-block">Deseleccionar todo</button>');
		$(".selectAllCheck").remove();
	});
	$(document).on('click', '.unselectAllCheck', function (event) {
		event.preventDefault();
		$("input[type=checkbox]").prop('checked', false);
		$(".unselectAllCheck").after('<button class="btn btn-xs btn-info selectAllCheck btn-block">Seleccionar todo</button>');
		$(".unselectAllCheck").remove();
	});
	$(document).on('click', '.deleteSelcted', function (event) {
		event.preventDefault();
		var idCotizacion = $(this).attr('id');

		let resultado = "";
		$("input:checked").each(function (index, opcion) {
			resultado += $(opcion).attr('name') + ", ";
		});
		if (!resultado) {
			$("table").after('<div class="mb-3 text-center text-danger noSelectOption">SELECCIONE UNA OPCIÓN</div>');
			setTimeout(function () {
				$(".noSelectOption").remove();
			}, 2000)
			return false;
		}
		$("#productosBorrar").modal("toggle");
		$.ajax({
			url: 'do.php',
			type: 'POST',
			dataType: 'html',
			data: "action=eliminarVentaDirecta&idCotizacion=" + idCotizacion + "&idProducto=" + resultado
			,
		})
			.done(function (data) {
				$(".respuesta").html(data)
			})
		return false;
	});


	//inicio facturacion simple o doble copiar 
	$(document).on("click", ".btnFacturaModalCargarDatos", function (event) {
		event.preventDefault();

		$(".contenido_pregunta_tipo_factura").removeClass('d-none');
		$(".contenido_factura_simple").addClass('d-none');
		$(".contenido_factura_doble").addClass('d-none');


		let id = $(this).attr("id");
		$.ajax({
			url: "includes/getDataCompradas.php",
			type: "POST",
			dataType: "json",
			data: { id: id },
			success: function (data) {
				//para simple
				$("#clientReasonSocial").val(data.NombreCliente);
				$("#clientNroDocument").val(data.nitCliente);
				$("#clientCity").val(data.ciudadCliente);
				$("#clientEmail").val(data.correoCliente);
				//$("#userPos").val(data.nombreVendedor);
				// $("#branchIdName").val(data.sucursalCompra);
				$("#idCotizacion").val(data.idCotizacion);
				//para doble
				$("#clientReasonSocial_02srl").val(data.NombreCliente);
				$("#clientNroDocument_02srl").val(data.nitCliente);
				$("#clientCity_02srl").val(data.ciudadCliente);
				$("#clientEmail_02srl").val(data.correoCliente);
				//$("#userPos").val(data.nombreVendedor);
				// $("#branchIdName").val(data.sucursalCompra);
				$("#idCotizacion_02srl").val(data.idCotizacion);
				actualizarclientCode();
			},
		});


	});

	//pregunta inicio tipo de factura
	$(document).on('click', '.facturaSimple', function (event) {
		event.preventDefault();
		$(".contenido_pregunta_tipo_factura").addClass('d-none');
		$(".contenido_factura_simple").removeClass('d-none');
		$(".contenido_factura_doble").addClass('d-none');
	});
	$(document).on("click", ".facturaDoble", function (event) {
		event.preventDefault();
		$(".contenido_pregunta_tipo_factura").addClass('d-none');
		$(".contenido_factura_simple").addClass('d-none');
		$(".contenido_factura_doble").removeClass('d-none');
	});

	//factura simple----------------------------------------------
	//cliente existente si
	$("#select_clientes").select2();

	$("input:checkbox[name='optionUser']").change(function () {
		if ($(this).is(':checked')) {
			$(".col_select_clientes").removeClass('d-none');
		} else {
			$(".col_select_clientes").addClass('d-none');
			$("#clientReasonSocial").val('');
			$("#clientNroDocument").val('');
			$("#clientEmail").val('');
			$("#clientCity").val('');
			actualizarclientCode();
		}
	});
	$("#select_clientes").change(function () {
		$("#select_clientes option:selected").each(function () {
			var idCliente = $(this).val();
			$.ajax({
				url: 'includes/getDataClientes.php',
				type: 'POST',
				dataType: 'json',
				data: { idCliente },
				success: function (data) {
					$("#clientReasonSocial").val(data.Nombres + " " + data.Apellidos);
					$("#clientNroDocument").val(data.NIT);
					$("#clientEmail").val(data.Correo);
					$("#clientCity").val(data.Ciudad);
					actualizarclientCode();
				}
			})
		});
	});
	llenar();
	$(document).ready(function () {
		$("form").keypress(function (e) {
			if (e.which == 13) {
				return false;
			}
		});
	});
	$("#ClienteProducto").select2();
	$("#ClienteProducto").select2({
		theme: "classic"
	});

	//	OBTENER DATOS DEL PRODUCTO
	$("#ClienteProducto").change(function () {
		$("#ClienteProducto option:selected").each(function () {
			var idProducto = $(this).val();
			var miCiudad = $("#miCiudad").val();
			$.ajax({
				url: 'includes/getDataProductoFiscales.php',
				type: 'POST',
				dataType: 'json',
				data: { id: idProducto },
				success: function (data) {
					$(".PreciosProductoSelected").removeClass('d-none');

					$("#CantidadProducto").val('1');
					$("#PrecioEspecial").val('');
					$("#PrecioEspecial").focus();
					$("#ProdExistenciaCB").val(data.saldo_fisico);
					$("#fecha_poliza").val(data.fecha_poliza);
					$("#PrecioLista").val(data.c_u_facturar_minimo);
					$("#PrecioEspecial").val(data.importes_para_facturar);

					$("#idProducto").val(data.idProducto);
					$("#detalle").val(data.detalle);

					if (data.codigo == '' || data.codigo == null) {
						$("#codigo").val('Sin Código');
					} else { $("#codigo").val(data.codigo); }


					$(".Add_ProductoEmision").attr('disabled', false);

				}
			})
		});
	});

	$(document).on("click", ".borrar", function (event) {
		event.preventDefault();
		$(this).closest("tr").remove();
		//document.getElementById("total").value = "Modo Debito";
		// actualizarSubTotal();
		actualizarTotal();

	});

	//	AGREGAR PRODUCTO A LA TABLA factura
	$(document).on('click', '.Add_ProductoEmision', function (event) {
		event.preventDefault();
		//$(".showTableProd").removeClass('d-none');
		var idProducto = $("#ClienteProducto option:selected").val();
		var PrecioLista = $("#PrecioLista").val();
		var Cantidad = $("#CantidadProducto").val();
		var PrecioEspec = $("#PrecioEspecial").val();
		var ClaveCotiza = $("#ClaveGeneradaAleatoria").val();
		var saldo_fisico = $("#ProdExistenciaCB").val();
		if (idProducto == 'Seleccione producto') {
			$(".noSelectProd").removeClass('d-none');
			setTimeout(function () {
				$(".noSelectProd").addClass('d-none');
			}, 2000);
			//return false;
		} else if (Cantidad == '') {
			$(".CantidadEmpty").removeClass('d-none');
			setTimeout(function () {
				$(".CantidadEmpty").addClass('d-none');
			}, 2000);
			//return false;
		}
		else if (parseInt(Cantidad, 10) > parseInt(saldo_fisico, 10) || parseInt(Cantidad, 10) <= 0) {
			$(".CantidadEmpty").removeClass('d-none');
			setTimeout(function () {
				$(".CantidadEmpty").addClass('d-none');
			}, 2500);
			//return false;
		} else if (PrecioEspec == '') {
			$(".emptyPrecioEsp").removeClass('d-none');
			setTimeout(function () {
				$(".emptyPrecioEsp").addClass('d-none');
			}, 2000);
			//return false;
		} else {
			$(".efectAddProduct").removeClass('d-none');
			$(".Add_ProductoEmision").attr('disabled', true);
			agregar_prodFiscal();
			setTimeout(function () {
				$(".efectAddProduct").addClass('d-none');
				$(".Add_ProductoEmision").attr('disabled', false);
				$(".PreciosProductoSelected").addClass('d-none');
				$(".checkOptions").addClass('d-none');
				$(".showTableProd").removeClass('d-none');
				$(".datosAdicionales").removeClass('d-none');
				$(".btnSaveCotiza").removeClass('d-none');
				//$("#respuesta").html(data);
			}, 1000);




		}
	});


	$(document).on('click', '.facturarSintic', function (event) {
		event.preventDefault();
		$('.facturarSintic').attr('disabled', true);  //boton facturar desactivar
		$(".efectSaveCotiza").removeClass('d-none');

		let clientReasonSocial = $("#clientReasonSocial").val();
		let clientDocumentType = $("#clientDocumentType").val();  //nit ,ci ,pasaporte
		let clientNroDocument = $("#clientNroDocument").val();  //numero nit o nro carnet
		let clientCode = $("#clientCode").val();					//codigo unico cliente
		let clientCity = $("#clientCity").val();					//ciudad cliente(personal)

		let clientEmail = $("#clientEmail").val();		// email cliente

		let userPos = $("#userPos").val(); 					//vendedor que emitio la factura
		let paramCurrency = $("#paramCurrency").val();		//tipo de moneda 
		let paramPaymentMethod = $("#paramPaymentMethod").val(); // tipo o metodo de pago para siat


		let datosPostCliente = "clientReasonSocial=" + encodeURIComponent(clientReasonSocial) +
			"&clientDocumentType=" + clientDocumentType +
			"&clientNroDocument=" + clientNroDocument +
			"&clientCode=" + clientCode +
			"&clientCity=" + clientCity +
			"&clientEmail=" + encodeURIComponent(clientEmail);

		console.log(datosPostCliente);
		//crear cliente
		// Esta función asíncrona respuesta de api crear cliente
		//1er paso crear cliente y retornar
		var crearClienteApi = () => {
			return new Promise((resolve, reject) => {
				$.ajax({
					url: 'includes/api_facturacion/crear_cliente.php',
					type: 'POST',
					dataType: 'json',
					data: datosPostCliente,
					beforeSend: function () {
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
							timer: 25000,
						});
					},
					success: function (data) {
						if (data.response == 'ok') {
							let clienteCreado = {
								customer_id: data.customer_id,
								first_name: data.first_name,
								identity_document: data.identity_document,
								email: data.email
							}
							console.log('ClienteCreado en api');
							resolve(clienteCreado);//promesa resuelta xd
						} else {
							console.log('error crear cliente en api');
							reject('error');
						}
					}
				});
			});
		}
		clienteListo();
		async function clienteListo() {
			try {
				let clienteCreado = await crearClienteApi();
				console.log(clienteCreado);
				//mandamos para facturar
				let customer_id = clienteCreado.customer_id;
				let first_name = clienteCreado.first_name;
				let identity_document = clienteCreado.identity_document;
				let email = clienteCreado.email;
				let idCotizacion = $("#idCotizacion").val();
				let tipo_documento_identidad = parseInt($("#clientDocumentType").val());
				let codigo_metodo_pago = parseInt($("#paramPaymentMethod").val());
				let subtotal = parseFloat($("#total").val());
				let total = parseFloat($("#total").val());
				let total_tax = parseFloat(total * 0.13);


				let items = { items: capturar_carrito() }
				items = JSON.stringify(items)
				// console.log(items);

				let datosPostFactura = "customer_id=" + customer_id +
					"&first_name=" + encodeURIComponent(first_name) +
					"&identity_document=" + identity_document +
					"&email=" + encodeURIComponent(email) +
					"&idCotizacion=" + idCotizacion +
					"&tipo_documento_identidad=" + tipo_documento_identidad +
					"&codigo_metodo_pago=" + codigo_metodo_pago +
					"&subtotal=" + subtotal +
					"&total=" + total +
					"&total_tax=" + total_tax +
					"&items=" + items;

				console.log(datosPostFactura);
				$.ajax({
					url: 'includes/api_facturacion/facturacionsintic.php',
					type: 'POST',
					dataType: 'json',
					data: datosPostFactura,
					success: function (data) {
						if (data.response == 'ok') {
							console.log('Factura Exitosa');
							Swal.fire({
								type: 'success',
								title: "FACTURACIÓN EXITOSA",
								animation: false,
								customClass: {
									popup: 'animated bounceInDown'
								},
								text: "Factura Guardada Correctamente",
								timer: 5000,
							});
							setTimeout(function () {
								location.replace('?root=facturacionListado');
							}, 5000);

						} else {
							Swal.fire({
								type: 'error',
								title: "ERROR AL CONECTAR CON IMPUESTOS NACIONALES",
								animation: false,
								customClass: {
									popup: 'animated bounceInDown'
								},
								text: "Error de  Conexion Con SIAT , Intente Nuevamente",
								icon: "error",
								button: "Ok",
								timer: 25000,
							});
							$('.facturarSintic').attr('disabled', false);  //boton facturar desactivar
							$(".efectSaveCotiza").addClass('d-none');

						}

					}
				});

			} catch (error) {
				console.log(error);
				Swal.fire({
					type: 'error',
					title: "ERROR AL CONECTAR CON IMPUESTOS NACIONALES",
					animation: false,
					customClass: {
						popup: 'animated bounceInDown'
					},
					text: "Error de  Conexion Con SIAT , Intente Nuevamente",
					icon: "error",
					button: "Ok",
					timer: 25000,
				});
				$('.facturarSintic').attr('disabled', false);  //boton facturar reactivar
				$(".efectSaveCotiza").addClass('d-none');
				console.log('Fallo Crear cliente, forzando login api');
				let auth_login = "auth_login=1";
				$.ajax({
					url: 'includes/api_facturacion/auth_login.php',
					type: 'POST',
					dataType: 'json',
					data: auth_login,
					success: function (data) {
						if (data == 'ok') {
							console.log('Login correcto, token actualizado');
						} else {
							console.log('Error login, token no actualizado');
						}
					}
				});
			}
		}



	});
	//FIN facturacion simple --------------------










});

//inicio metodos para factur SIMPLE
function capturar_carrito() {

	let numero_fiscales = $("#correlativo").val();
	let carrito = [];
	for (let i = 100; i <= numero_fiscales; i++) {
		if ($("#" + i + "description").val()) {

			let codeProduct = $("#" + i + "codeProduct").val();
			let description = $("#" + i + "description").val();
			let idProductoFiscal = parseInt($("#" + i + "idProductoFiscal").val());
			let qty = parseInt($("#" + i + "qty").val());
			let priceUnit = (parseFloat($("#" + i + "priceUnit").val())).toFixed(2);
			priceUnit = parseFloat(priceUnit);
			let subTotal = (parseFloat($("#" + i + "subTotal").val())).toFixed(2);
			subTotal = parseFloat(subTotal);
			let producto = {
				product_id: 0, //defecto api
				product_code: codeProduct,
				product_name: description,
				price: priceUnit,
				quantity: qty,

				total: subTotal,
				unidad_medida: 62, //defecto api
				numero_serie: "",
				numero_imei: "",
				codigo_producto_sin: 99794, //defecto api

				codigo_actividad: "465000", //defecto api
				discount: 0,

				idProductoFiscal: idProductoFiscal,
				prodF: 'si'
			}
			carrito.push(producto);
		}
	}
	// console.log(carrito);
	return carrito;

}
function actualizarclientCode() {
	let iniciales = "";
	let clientReasonSocial = document.getElementById("clientReasonSocial").value;
	for (x = 0; x < clientReasonSocial.length; x++) {
		if (x == 0) {
			iniciales = iniciales + clientReasonSocial.charAt(x);
		}
		if (clientReasonSocial.charAt(x + 1) != ' ') {
			if (clientReasonSocial.charAt(x) == ' ') {
				iniciales = iniciales + clientReasonSocial.charAt(x + 1);
			}
		}
	}
	document.getElementById("clientCode").value = "CLIENT-" + iniciales +
		clientReasonSocial.length;
}

function actualizarSubTotal() {
	let count = document.getElementById("count").value;
	count = parseFloat(count);
	let subtotal = 0;
	let sum = 0;

	for (x = 0; x <= count; x++) {

		if (document.getElementById(x + "priceUnit") != null) {
			let price = parseFloat(document.getElementById(x + "priceUnit").value);
			let qty = parseInt(document.getElementById(x + "qty").value);
			document.getElementById(x + "subTotal").value = (price * qty).toFixed(2);
		}

	}
	for (x = 100; x <= correlativo; x++) {

		if (document.getElementById(x + "priceUnit") != null) {
			let price = parseFloat(document.getElementById(x + "priceUnit").value);
			let qty = parseInt(document.getElementById(x + "qty").value);
			document.getElementById(x + "subTotal").value = (price * qty).toFixed(2);
		}

	}
	actualizarTotal();
}

function actualizarTotal() {
	let count = document.getElementById("count").value;
	count = parseFloat(count);

	let sum = 0;
	for (x = 0; x <= count; x++) {
		if (document.getElementById(x + "subTotal") != null) {
			sum = sum + parseFloat(document.getElementById(x + "subTotal").value);
		}

	}
	for (x = 100; x <= correlativo; x++) {
		if (document.getElementById(x + "subTotal") != null) {
			sum = sum + parseFloat(document.getElementById(x + "subTotal").value);
		}

	}

	document.getElementById("total").value = sum.toFixed(2);
}

function actualizarCantidad(fila) {
	let count = document.getElementById("count").value;
	//let price = parseFloat(document.getElementById(x + "precioUnidad").value);
	// document.getElementById(fila + "qty").value = 1;
	count = parseFloat(count);
	actualizarSubTotal();
	actualizarTotal();
}

var correlativo = 100;
function agregar_prodFiscal() {
	correlativo++;
	var username = $('#username').val();
	var myLabel = $('#miLabel');


	let idProducto = document.getElementById("idProducto").value;
	let CantidadProducto = document.getElementById("CantidadProducto").value;
	let codigo = document.getElementById("codigo").value;

	let detalle = document.getElementById("detalle").value;

	let PrecioEspecial = document.getElementById("PrecioEspecial").value;

	let saldo_fisico = document.getElementById("ProdExistenciaCB").value;
	let c_u_facturar_minimo = document.getElementById("PrecioLista").value;

	$('#tableProductosVendidos').append(
		"<tr  id='fila" + correlativo + "'>" +
		"<td>" +
		"<input type='hidden' name='" + correlativo + "idProductoFiscal' id='" + correlativo + "idProductoFiscal' value='" + idProducto + "'>" +
		//id del producto fiscal
		"<input class='form-control text-center' min='1' max='" + saldo_fisico + "' type='number'  name='" + correlativo + "qty'" +
		"id='" + correlativo + "qty' saldo_fisico='" + saldo_fisico + "' onchange='actualizarSubTotal()' oninput='actualizarSubTotal()' value='" + CantidadProducto + "'>" +
		"<label for='" + correlativo + "qty'> SaldoFisico: <b>" + saldo_fisico + "</b></label>" +
		"</td>" +
		"<td>" +
		"<input class='form-control' id='" + correlativo +
		"codeProduct'  placeholder='CODE' value='" + codigo + "'>" +
		"</td>" +
		"<td>" +
		"<input class='form-control' id='" + correlativo +
		"description' placeholder='DescripcionProducto' value='" + detalle + "'>" +
		"</td>" +
		"<td>" +
		"<input class='form-control text-right' type='number' min='" + c_u_facturar_minimo + "' c_u_facturar_minimo='" + c_u_facturar_minimo + "' placeholder='PrecioUnidad' name='" +
		correlativo + "priceUnit' id='" + correlativo +
		"priceUnit' onchange='actualizarSubTotal()' oninput='actualizarSubTotal()' value='" + PrecioEspecial + "'>" +
		"<label for='" + correlativo + "priceUnit'> Facturar Minimo: <b>" + c_u_facturar_minimo + "</b></label>" +
		"</td>" +
		"<td>" +
		"<input class='form-control text-right' readonly name='" +
		correlativo + "subTotal' id='" + correlativo +
		"subTotal'  value='' >" +
		"</td>" +
		"<td>" +
		"<button class='btn btn-info borrar' title='" + correlativo + "-" + idProducto +
		"' onClick='eliminarxdxd(" + correlativo + ")'>Eliminar</button>" +
		"</td>" +
		"</tr>");

	document.getElementById("correlativo").value = correlativo;
	actualizarSubTotal();
	actualizarTotal();
}
function llenar() {
	//json
	let data = {
		"idCotizacion": "-1",
		"sucursalCompra": "-1",
		"codigoCotizacion": "-1",
		"idCliente": "-1",
		"idUsuario": "-1",
		"clave": "-1",
		"NombreCliente": "",
		"nitCliente": "",
		"ciudadCliente": "",
		"correoCliente": "",
		"nombreVendedor": "",
		"productosVendidos": [{
			"activityEconomic": "465000",
			"unitMeasure": 62,
			"codeProductSin": "6118519",
			"codeProduct": "",
			"description": "",
			"qty": 1,
			"priceUnit": "0",
			"idProducto": "-1"
		}], "dataTotal": "0"
	}
	$('#tableProductosVendidos').empty();
	var DatosJson = JSON.parse(JSON.stringify(data));
	console.log(DatosJson.productosVendidos.length);

	//	fiscales productos ocultar inicio
	$(".Add_ProductoEmision").attr('disabled', true);
	$(".efectAddProduct").addClass('d-none');
	$(".PreciosProductoSelected").addClass('d-none');
	$(".checkOptions").addClass('d-none');
	$(".showTableProd").removeClass('d-none');
	$(".datosAdicionales").removeClass('d-none');
	$(".btnSaveCotiza").removeClass('d-none');
	//	fiscales productos ocultar fin

	$("#tableProductosVendidos").append(
		'<thead class="thead-dark">' +
		'<tr>' +
		'<th scope="col" width="15%" class="text-center p-5">' +
		'<h5>Cantidad' +
		'</th>' +
		'<th scope="col" width="15%" class="text-center p-5">' +
		'<h5>CodProd' +
		'</th>' +
		'<th scope="col" width="40%" class="text-center p-5">' +
		'<h5>Producto' +
		'</th>' +
		'<th scope="col" width="15%" class="text-center p-5">' +
		'<h5>PrecioUnidad Bs' +
		'</th>' +
		'<th scope="col" width="15%" class="text-center p-5">' +
		'<h5>SubTotal Bs' +
		'</th>' +
		'<th scope="col" width="15%" class="text-center p-5">' +
		'<h5>Eliminar' +
		'</th>' +
		'</tr>' +
		'</thead>' +
		'<tbody>');
	let count = 0;
	for (i = 0; i < DatosJson.productosVendidos.length; i++) {

		count = i;
	}
	$("#tableProductosVendidos").append(
		'<thead class="thead-light">' +
		'<tr>' +
		'<th colspan="4" class="text-right p-4 "><strong><h4>TOTAL</h4></strong></th>' +
		'<th scope="col">' +
		'<input class="form-control text-right" readonly name="total" id="total" value="' + DatosJson.dataTotal + '">' +
		'<input name="count" id="count" type="hidden" value="' + count + '">' +
		'<input name="correlativo" id="correlativo" type="hidden" value="">' +
		'</th>' +
		'<th scope="col" class="text-left p-4 "><strong><h4>Bs</h4></strong></th>' +
		'</tr>' +
		'</thead>' +
		'</tbody>');
	actualizarclientCode();
	actualizarSubTotal();


}
//fin metodos facturas simple





