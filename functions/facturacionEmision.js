$(document).ready(function () {
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
	//	OBTENER DATOS DEL PRODUCTO
	llenar();
	$(document).on('click', '.facturaEmisionDirectaJS', function (event) {
		event.preventDefault();
		var ClienteNombre = $("#ClienteNombre").val();
		var ClienteApellido = $("#ClienteApellido").val();

		var ClienteCorreo = $("#ClienteCorreo").val();
		var ClienteCiudad = $("#ClienteCiudad_ option:selected").val();

		$('#submitButton').attr('disabled', true);

		let count = document.getElementById("count").value;
		count = parseFloat(count);
		let bandera = 0;
		let mensaje = '';
		let bandera_qty = 0;
		let mensaje_qty = '';
		for (x = 0; x < count; x++) {
			let name = document.getElementById(x + "description").value;
			let value = parseFloat(document.getElementById(x + "priceUnit").value);
			let c_u_facturar_minimo = parseFloat(document.getElementById(x + "priceUnit").getAttribute("c_u_facturar_minimo"));

			if (value < c_u_facturar_minimo) {

				mensaje = mensaje + 'PrecioUnidadBs=' + value + ' de ' + name + ' No puede ser menor a : ' + c_u_facturar_minimo + '\n';
				bandera++;
			}

			let value_qty = parseFloat(document.getElementById(x + "qty").value);
			let saldo_fisico = parseFloat(document.getElementById(x + "qty").getAttribute("saldo_fisico"));

			if (value_qty > saldo_fisico) {

				mensaje_qty = mensaje_qty + 'Cantidad=' + value_qty + ' de ' + name + ' No puede ser Mayor al SaldoFisico(Stock): ' + saldo_fisico + '\n';
				bandera_qty++;
			}
		}


		if (ClienteCiudad == 'Seleccione Ciudad') {
			$(".emptyCliente_Ciudad").removeClass('d-none');
			$(".infoProducto").addClass('d-none');
			$(".PreciosProductoSelected").addClass('d-none');
			$(".datosAdicionales").addClass('d-none');
			setTimeout(function () {
				$(".emptyCliente_Ciudad").addClass('d-none');
				$(".infoProducto").removeClass('d-none');
				$(".PreciosProductoSelected").removeClass('d-none');
				$(".datosAdicionales").removeClass('d-none');
			}, 2000);
		}
		else if (bandera > 0) {
			// console.log('varios datos errones');
			Swal.fire({
				type: 'error',
				title: "Error Precio Unidad menor a Facturar Minimo",
				animation: false,
				customClass: {
					popup: 'animated bounceInDown'
				},
				text: mensaje,
				icon: "success",
				button: "Ok",
			})
			setTimeout(function () {
				$('#submitButton').attr('disabled', false);
			}, 5000);
		} else if (bandera_qty > 0) {
			// console.log('varios datos errones');
			Swal.fire({
				type: 'error',
				title: "Error Cantidad Mayor al SaldoFisico(Stock)",
				animation: false,
				customClass: {
					popup: 'animated bounceInDown'
				},
				text: mensaje_qty,
				icon: "success",
				button: "Ok",
			})
			setTimeout(function () {
				$('#submitButton').attr('disabled', false);
			}, 5000);
		} else {
			$('#submitButton').attr('disabled', true);
			$(".efectSaveCotiza").removeClass('d-none');

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
				url: 'Paginas/facturacionEmisionDirectaDo.php',
				type: 'POST',
				dataType: 'html',
				data: $("#newCotizacion").serialize(),	//"action=GenerarCotizacionn&NombreCliente="+ClienteNombre,
			})
				.done(function (data) {
					$(".efectSaveCotiza").addClass('d-none');
					$(".guardaNewCotiza").attr('disabled', false);
					//$("#newCotizacion").addClass('d-none');
					//$(".cotizaOK").removeClass('d-none');
					$(".respuesta").html(data);
				})
			return false;
		}
	});

	//Inicio copiar todo de compradas Facturacion aqui tal cual 

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
			}, 2000);




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
							timer: 15000,
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
								timer: 15000,
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
					timer: 15000,
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


	//fin copiar todo de compradasfacturacion aqui tal cual 
	//pregunta inicio simple
	$(document).on('click', '.facturaSimple', function (event) {
		event.preventDefault();
		$(".form_doble").addClass('d-none');
		$(".form_simple").removeClass('d-none');
		$(".form_pregunta").addClass('d-none');
	});
	$(document).on("click", ".facturaDoble", function (event) {
		event.preventDefault();
		location.replace('?root=facturacionEmision-doble');

	});
	//pregunta inicio simple fin
});

//inicio 2 _metodos para facturar copiar tal cual
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
//final 2 _metodos para facturar copiar tal cual



//función asíncrona que se ejecuta automatico para validar token en la bd
(async () => {
	let datosPOST =
		"token_validar=" + "token_validar";
	console.log(datosPOST);
	$.ajax({
		url: "includes/api_facturacion/token_validar.php",
		type: "POST",
		dataType: "json",
		data: datosPOST,
		success: function (data) {
			console.log(data);
		},
	});

})();