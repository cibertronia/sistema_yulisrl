$(document).ready(function () {
	// js-switch  _02srl
	let elem_02srl = document.querySelector('.js-switch_02srl');
	let init_02srl = new Switchery(elem_02srl);
	//cliente existente yuliimport
	$("input:checkbox[name='checkbox_02srl']").change(function () {
		if ($(this).is(":checked")) {
			$(".col_select_clientes_02srl").removeClass("d-none");
		} else {
			$(".col_select_clientes_02srl").addClass("d-none");
			$("#clientReasonSocial_02srl").val("");
			$("#clientNroDocument_02srl").val("");
			$("#clientEmail_02srl").val("");
			$("#clientCity_02srl").val("");
			actualizarclientCode_02srl();
		}
	});
	//cliente existente yuliimport
	$("#select_clientes_02srl").select2();
	$("#select_clientes_02srl").change(function () {
		$("#select_clientes_02srl option:selected").each(function () {
			let idCliente = $(this).val();
			$.ajax({
				url: "includes/getDataClientes.php",
				type: "POST",
				dataType: "json",
				data: { idCliente },
				success: function (data) {
					$("#clientReasonSocial_02srl").val(
						data.Nombres + " " + data.Apellidos
					);
					$("#clientNroDocument_02srl").val(data.NIT);
					$("#clientEmail_02srl").val(data.Correo);
					$("#clientCity_02srl").val(data.Ciudad);
					actualizarclientCode_02srl();
				},
			});
		});
	});
	$("#clientReasonSocial_02srl").on("input", actualizarclientCode_02srl);
	//borrar fila
	$(document).on("click", ".borrar_02srl", function (event) {
		event.preventDefault();
		$(this).closest("tr").remove();
		actualizarTotal_02srl();
	});
	//clonar tabla
	$(document).on("click", ".clonar_tabla_02srl", function (event) {
		clonar_tabla_a_02srl();
	});
	//agregar fila vacia
	$(document).on("click", ".agregar_fila_vacia_02srl", function (event) {
		agregar_fila_vacia_02srl();
	});

});
//funciones
var nf_srl = 0;//nro_filas en la tabla table_02srl
//agregar fila con datos
function agregar_fila_a_02srl(cant_prod, code, descripcion, price) {

	nf_srl++;
	let idProducto = "-1";
	let CantidadProducto = cant_prod;
	let codigo = code;
	let detalle = descripcion;
	let PrecioEspecial = price;
	let saldo_fisico = "No Fiscal";
	let c_u_facturar_minimo = "No Fiscal";

	$("#table_02srl").append(
		"<tr>" +

		"<td>" +
		//id producto fiscal yuliimport01 para descontar
		"<input type='hidden' id='" + nf_srl + "idProductoFiscal_02srl' value='" + idProducto + "'>" +
		//cantidad producto fiscal yuliimport01 para factura y descontar
		"<input class='form-control text-center' id='" + nf_srl + "qty_02srl' min='1' type='number' " +
		"onchange='actualizarSubTotal_02srl()' oninput='actualizarSubTotal_02srl()' value='" + CantidadProducto + "' readonly>" +
		"<label for='" + nf_srl + "qty'> SaldoFisico: <b>" + saldo_fisico + "</b></label>" +
		//fin celda
		"</td>" +

		"<td>" +
		//codigo producto fiscal para factura
		"<input class='form-control' id='" + nf_srl + "codeProduct_02srl'  placeholder='CODE' value='" + codigo + "'>" +
		//fin celda
		"</td>" +

		"<td>" +
		//descripcion producto fiscal para factura
		"<input class='form-control' id='" + nf_srl + "description_02srl' value='" + detalle + "'>" +
		//fin celda
		"</td>" +

		"<td>" +
		//precio_unidad producto fiscal para factura
		"<input class='form-control text-right' id = '" + nf_srl + "priceUnit_02srl' type='number' min='1' " +
		"onchange = 'actualizarSubTotal_02srl()' oninput = 'actualizarSubTotal_02srl()' value = '" + PrecioEspecial + "'>" +
		"<label for='" + nf_srl + "priceUnit'> Facturar Minimo: <b>" + c_u_facturar_minimo + "</b></label>" +
		//fin celda
		"</td>" +

		"<td>" +
		//subtotal = precio producto fiscal * cantidad
		"<input class='form-control text-right' id='" + nf_srl + "subTotal_02srl'  value='' readonly>" +
		//fin celda
		"</td>" +

		"<td>" +
		//boton eliminar fila
		"<!-- button class='btn bg-custom-blue borrar_02srl' title='" + nf_srl + " idFiscal :" + idProducto + "'>Eliminar</button-->" +
		//fin celda
		"</td>" +

		"</tr>"
	);
	actualizarSubTotal_02srl();
	actualizarTotal_02srl();
}
// agregar fila vacia
function agregar_fila_vacia_02srl() {
	nf_srl++;
	let idProducto = "-1";
	let CantidadProducto = "1";
	let codigo = "Codigo";
	let detalle = "Descripcion Producto";
	let PrecioEspecial = "1";
	let saldo_fisico = "No Fiscal";
	let c_u_facturar_minimo = "No Fiscal";

	$("#table_02srl").append(
		"<tr>" +

		"<td>" +
		//id producto fiscal yuliimport01 para descontar
		"<input type='hidden' id='" + nf_srl + "idProductoFiscal_02srl' value='" + idProducto + "'>" +
		//cantidad producto fiscal yuliimport01 para factura y descontar
		"<input class='form-control text-center' id='" + nf_srl + "qty_02srl' min='1' type='number' " +
		"onchange='actualizarSubTotal_02srl()' oninput='actualizarSubTotal_02srl()' value='1'>" +
		"<label for='" + nf_srl + "qty'> SaldoFisico: <b>" + saldo_fisico + "</b></label>" +
		//fin celda
		"</td>" +

		"<td>" +
		//codigo producto fiscal para factura
		"<input class='form-control' id='" + nf_srl + "codeProduct_02srl'  placeholder='CODE' value='" + codigo + "'>" +
		//fin celda
		"</td>" +

		"<td>" +
		//descripcion producto fiscal para factura
		"<input class='form-control' id='" + nf_srl + "description_02srl' value='" + detalle + "'>" +
		//fin celda
		"</td>" +

		"<td>" +
		//precio_unidad producto fiscal para factura
		"<input class='form-control text-right' id = '" + nf_srl + "priceUnit_02srl' type='number' min='1' " +
		"onchange = 'actualizarSubTotal_02srl()' oninput = 'actualizarSubTotal_02srl()' value = '1'>" +
		"<label for='" + nf_srl + "priceUnit'> Facturar Minimo: <b>" + c_u_facturar_minimo + "</b></label>" +
		//fin celda
		"</td>" +

		"<td>" +
		//subtotal = precio producto fiscal * cantidad
		"<input class='form-control text-right' id='" + nf_srl + "subTotal_02srl'  value='' readonly>" +
		//fin celda
		"</td>" +

		"<td>" +
		//boton eliminar fila
		"<button class='btn bg-custom-blue borrar_02srl' title='" + nf_srl + " idFiscal :" + idProducto + "'>Eliminar</button>" +
		//fin celda
		"</td>" +

		"</tr>"
	);
	actualizarSubTotal_02srl();
	actualizarTotal_02srl();
}
//clonar tabla tal cual a 02srl
function clonar_tabla_a_02srl() {
	// Borrar todo el contenido del tbody
	let tbody = document.querySelector("#table_02srl tbody");
	tbody.innerHTML = "";

	for (let i = 0; i <= nf; i++) {
		if ($("#" + i + "description_01yuli").val()) {
			let codeProduct = $("#" + i + "codeProduct_01yuli").val();
			let description = $("#" + i + "description_01yuli").val();
			let idProductoFiscal = parseInt($("#" + i + "idProductoFiscal_01yuli").val());
			let qty = parseInt($("#" + i + "qty_01yuli").val());
			let priceUnit = (parseFloat($("#" + i + "priceUnit_01yuli").val())).toFixed(2);
			priceUnit = parseFloat(priceUnit);
			let subTotal = (parseFloat($("#" + i + "subTotal_01yuli").val())).toFixed(2);
			subTotal = parseFloat(subTotal);
			agregar_fila_a_02srl(qty, codeProduct, description, priceUnit);
		}
	}
}
//agregar todas las filas de la tabla a un array carrito para facturar
function capturar_carrito_02srl() {
	let carrito = [];
	for (let i = 0; i <= nf_srl; i++) {
		if ($("#" + i + "description_02srl").val()) {
			let codeProduct = $("#" + i + "codeProduct_02srl").val();
			let description = $("#" + i + "description_02srl").val();
			let idProductoFiscal = parseInt($("#" + i + "idProductoFiscal_02srl").val());
			let qty = parseInt($("#" + i + "qty_02srl").val());
			let priceUnit = (parseFloat($("#" + i + "priceUnit_02srl").val())).toFixed(2);
			priceUnit = parseFloat(priceUnit);
			let subTotal = (parseFloat($("#" + i + "subTotal_02srl").val())).toFixed(2);
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
				prodF: 'no'
			}
			carrito.push(producto);
		}
	}
	// console.log(carrito);
	return carrito;
}
//01 primer paso crear cliente en api para yuliimport  _02srl
function primeraLlamadaCrearCliente_02srl() {
	return new Promise((resolve, reject) => {
		let clientReasonSocial = $("#clientReasonSocial_02srl").val(); //razon social
		let clientDocumentType = $("#clientDocumentType_02srl").val();  //nit ,ci ,pasaporte
		let clientNroDocument = $("#clientNroDocument_02srl").val();  //numero nit o nro carnet
		let clientCode = $("#clientCode_02srl").val();					//codigo unico cliente
		let clientCity = $("#clientCity_02srl").val();					//ciudad cliente(personal)
		let clientEmail = $("#clientEmail_02srl").val();		// email cliente

		let datosPostCliente = "clientReasonSocial=" + encodeURIComponent(clientReasonSocial) +
			"&clientDocumentType=" + clientDocumentType +
			"&clientNroDocument=" + clientNroDocument +
			"&clientCode=" + clientCode +
			"&clientCity=" + clientCity +
			"&clientEmail=" + encodeURIComponent(clientEmail);
		console.log(datosPostCliente);
		$.ajax({
			url: 'includes/api_facturacion_srl/crear_cliente.php',
			type: 'POST',
			dataType: 'json',
			data: datosPostCliente,
			beforeSend: function () {
				swall_info_conectando();
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
					resolve(clienteCreado);//promesa resuelta retorna cliente creado
				} else {
					swall_error_conexion();
					console.log('error crear cliente en api');
					reject('error');
				}
			},
			error: function (error) {
				swall_error_conexion();
				reject(error);
			}
		});
	});
}
//02 realizar factura en la api para yuliimport _02srl
function segundaLlamadaFacturar_02srl(clienteCreado, yuli_invoice_id, yuli_invoice_number) {
	return new Promise((resolve, reject) => {
		//cabeza factura
		let customer_id = clienteCreado.customer_id;
		let first_name = clienteCreado.first_name;
		let identity_document = clienteCreado.identity_document;
		let email = clienteCreado.email;
		let idCotizacion = $("#idCotizacion_02srl").val();
		let tipo_documento_identidad = parseInt($("#clientDocumentType_02srl").val());
		let codigo_metodo_pago = parseInt($("#paramPaymentMethod_02srl").val());
		let subtotal = parseFloat($("#total_02srl").val());
		let total = parseFloat($("#total_02srl").val());
		let total_tax = parseFloat(total * 0.13);

		let doble_emision = 'si';//indicativo que es doble y estan ligadas las 2 facturas
		let doble_invoice_id = yuli_invoice_id;//invoice id de la primera factura emitida en yuliimport 
		let doble_invoice_number = yuli_invoice_number;//invoice number de la primera factura emitida en yuliimport

		//array productos factura
		let items = { items: capturar_carrito_02srl() }
		items = JSON.stringify(items)
		//request
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

			"&doble_emision=" + doble_emision +
			"&doble_invoice_id=" + doble_invoice_id +
			"&doble_invoice_number=" + doble_invoice_number +

			"&items=" + items;
		console.log(datosPostFactura);
		$.ajax({
			url: 'includes/api_facturacion_srl/facturacionsintic.php',
			type: 'POST',
			dataType: 'json',
			data: datosPostFactura,
			success: function (data) {
				if (data.response == 'ok') {
					console.log('Factura Exitosa');
					swall_factura_exitosa_02srl();
					setTimeout(function () {
						//location.replace('?root=facturacionListado');
					}, 5000);
				} else {
					swall_error_conexion();
					$('.facturarSintic').attr('disabled', false);  //boton facturar reactivar
					$(".efectSaveCotiza").addClass('d-none');
				}
				resolve(data);
			},
			error: function (error) {
				reject(error);
			}
		});
	});
}

function actualizarSubTotal_02srl() {
	for (x = 0; x <= nf_srl; x++) {
		if (document.getElementById(x + "priceUnit_02srl") != null) {
			let price = parseFloat(document.getElementById(x + "priceUnit_02srl").value);
			let qty = parseInt(document.getElementById(x + "qty_02srl").value);
			document.getElementById(x + "subTotal_02srl").value = (price * qty).toFixed(2);
		}
	}
	actualizarTotal_02srl();
}

function actualizarTotal_02srl() {
	let sum = 0;
	for (x = 0; x <= nf_srl; x++) {
		if (document.getElementById(x + "subTotal_02srl") != null) {
			sum = sum + parseFloat(document.getElementById(x + "subTotal_02srl").value);
		}
	}
	document.getElementById("total_02srl").value = sum.toFixed(2);
}
//function actualizar code client
function actualizarclientCode_02srl() {
	let iniciales = "";
	let clientReasonSocial = document.getElementById(
		"clientReasonSocial_02srl"
	).value;
	for (x = 0; x < clientReasonSocial.length; x++) {
		if (x == 0) {
			iniciales = iniciales + clientReasonSocial.charAt(x);
		}
		if (clientReasonSocial.charAt(x + 1) != " ") {
			if (clientReasonSocial.charAt(x) == " ") {
				iniciales = iniciales + clientReasonSocial.charAt(x + 1);
			}
		}
	}
	document.getElementById("clientCode_02srl").value =
		"CLIENT-" + iniciales + clientReasonSocial.length;
}

function swall_factura_exitosa_02srl() {
	Swal.fire({
		type: 'success',
		title: "FACTURACIÓN YULI SRL EXITOSA",
		animation: true,
		customClass: {
			popup: 'animated bounceInDown'
		},
		text: "Factura Yuli SRL Guardada Correctamente",
	});
}

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

