$(document).ready(function () {
	//bloqueo enter no submit
	$(document).ready(function () {
		$("form").keypress(function (e) {
			if (e.which == 13) {
				return false;
			}
		});
	});
	// js-switch  _01yuli
	let elem_01yuli = document.querySelector('.js-switch_01yuli');
	let init_01yuli = new Switchery(elem_01yuli);
	//cliente existente yuliimport
	$("input:checkbox[name='checkbox_01yuli']").change(function () {
		if ($(this).is(":checked")) {
			$(".col_select_clientes_01yuli").removeClass("d-none");
		} else {
			$(".col_select_clientes_01yuli").addClass("d-none");
			$("#clientReasonSocial_01yuli").val("");
			$("#clientNroDocument_01yuli").val("");
			$("#clientEmail_01yuli").val("");
			$("#clientCity_01yuli").val("");
			actualizarclientCode_01yuli();
		}
	});
	//cliente existente yuliimport
	$("#select_clientes_01yuli").select2();
	$("#select_clientes_01yuli").change(function () {
		$("#select_clientes_01yuli option:selected").each(function () {
			let idCliente = $(this).val();
			$.ajax({
				url: "includes/api_facturacion_yuliimport/get_clientes_yuliimport.php",
				type: "POST",
				dataType: "json",
				data: { idCliente },
				success: function (data) {
					$("#clientReasonSocial_01yuli").val(
						data.Nombres + " " + data.Apellidos
					);
					$("#clientNroDocument_01yuli").val(data.NIT);
					$("#clientEmail_01yuli").val(data.Correo);
					$("#clientCity_01yuli").val(data.Ciudad);
					actualizarclientCode_01yuli();
				},
			});
		});
	});
	$("#clientReasonSocial_01yuli").on("input", actualizarclientCode_01yuli);
	// productos fiscales select
	$("#ClienteProducto_01yuli").select2();
	$("#ClienteProducto_01yuli").select2({
		theme: "classic",
	});

	// ajax productos_fiscales
	$("#ClienteProducto_01yuli").change(function () {
		$("#ClienteProducto_01yuli option:selected").each(function () {
			let idProducto = $(this).val();
			$.ajax({
				url: "includes/api_facturacion_yuliimport/get_productos_fiscales.php",
				type: "POST",
				dataType: "json",
				data: { id: idProducto },
				success: function (data) {
					$(".PreciosProductoSelected_01yuli").removeClass("d-none");
					$("#CantidadProducto_01yuli").val("1");
					$("#PrecioEspecial_01yuli").val("");
					$("#PrecioEspecial_01yuli").focus();
					$("#ProdExistenciaCB_01yuli").val(data.saldo_fisico);
					$("#fecha_poliza_01yuli").val(data.fecha_poliza);
					$("#PrecioLista_01yuli").val(data.c_u_facturar_minimo);
					$("#PrecioEspecial_01yuli").val(data.importes_para_facturar);
					$("#idProducto_01yuli").val(data.idProducto);
					$("#detalle_01yuli").val(data.detalle);
					if (data.codigo == "" || data.codigo == null) {
						$("#codigo_01yuli").val("Sin Código");
					} else {
						$("#codigo_01yuli").val(data.codigo);
					}
					$(".Add_ProductoEmision_01yuli").attr("disabled", false);
				},
			});
		});
	});
	// agregar productos_fiscales a la tabla yuliimport
	$(document).on("click", ".Add_ProductoEmision_01yuli", function (event) {
		event.preventDefault();
		let idProducto = $("#ClienteProducto_01yuli option:selected").val();
		let PrecioLista = $("#PrecioLista_01yuli").val();
		let Cantidad = $("#CantidadProducto_01yuli").val();
		let PrecioEspec = $("#PrecioEspecial_01yuli").val();
		let ClaveCotiza = $("#ClaveGeneradaAleatoria_01yuli").val();
		let saldo_fisico = $("#ProdExistenciaCB_01yuli").val();
		if (idProducto == "Seleccione producto") {
			$(".noSelectProd_01yuli").removeClass("d-none");
			setTimeout(function () {
				$(".noSelectProd_01yuli").addClass("d-none");
			}, 2000);
		} else if (Cantidad == "") {
			$(".CantidadEmpty_01yuli").removeClass("d-none");
			setTimeout(function () {
				$(".CantidadEmpty_01yuli").addClass("d-none");
			}, 2000);
		} else if (
			parseInt(Cantidad, 10) > parseInt(saldo_fisico, 10) ||
			parseInt(Cantidad, 10) <= 0
		) {
			$(".CantidadEmpty_01yuli").removeClass("d-none");
			setTimeout(function () {
				$(".CantidadEmpty_01yuli").addClass("d-none");
			}, 2500);
		} else if (PrecioEspec == "") {
			$(".emptyPrecioEsp_01yuli").removeClass("d-none");
			setTimeout(function () {
				$(".emptyPrecioEsp_01yuli").addClass("d-none");
			}, 2000);
		} else {
			$(".efectAddProduct_01yuli").removeClass("d-none");
			$(".Add_ProductoEmision_01yuli").attr("disabled", true);
			agregar_fila_01yuli();
			clonar_tabla_a_02srl();//clonaryuli01 a la tabla de abajo srl02
			setTimeout(function () {
				$(".efectAddProduct_01yuli").addClass("d-none");
				$(".Add_ProductoEmision_01yuli").attr("disabled", false);
				$(".PreciosProductoSelected_01yuli").addClass("d-none");
				$(".checkOptions_01yuli").addClass("d-none");
				$(".showTableProd_01yuli").removeClass("d-none");
				$(".datosAdicionales_01yuli").removeClass("d-none");
				$(".btnSaveCotiza_01yuli").removeClass("d-none");
			}, 500);
		}
	});
	$(document).on("click", ".borrar_01yuli", function (event) {
		event.preventDefault();
		$(this).closest("tr").remove();
		actualizarTotal_01yuli();
		clonar_tabla_a_02srl();//clonaryuli01 a la tabla de abajo srl02
	});


});
//funciones
// function agregar producto_fiscal tabla  table_01yuli
var nf = 0;//nro_filas en la tabla table_01yuli
function agregar_fila_01yuli() {
	nf++;
	let idProducto = document.getElementById("idProducto_01yuli").value;
	let CantidadProducto = document.getElementById("CantidadProducto_01yuli").value;
	let codigo = document.getElementById("codigo_01yuli").value;
	let detalle = document.getElementById("detalle_01yuli").value;
	let PrecioEspecial = document.getElementById("PrecioEspecial_01yuli").value;
	let saldo_fisico = document.getElementById("ProdExistenciaCB_01yuli").value;
	let c_u_facturar_minimo = document.getElementById("PrecioLista_01yuli").value;

	$("#table_01yuli").append(
		"<tr>" +

		"<td>" +
		//id producto fiscal yuliimport01 para descontar
		"<input type='hidden' id='" + nf + "idProductoFiscal_01yuli' value='" + idProducto + "'>" +
		//cantidad producto fiscal yuliimport01 para factura y descontar
		"<input class='form-control text-center' id='" + nf + "qty_01yuli' min='1' max='" + saldo_fisico + "' type='number' " +
		"onchange='actualizarSubTotal_01yuli()' oninput='actualizarSubTotal_01yuli()' value='" + CantidadProducto + "'>" +
		"<label for='" + nf + "qty'> SaldoFisico: <b>" + saldo_fisico + "</b></label>" +
		//fin celda
		"</td>" +

		"<td>" +
		//codigo producto fiscal para factura
		"<input class='form-control' id='" + nf + "codeProduct_01yuli'  placeholder='CODE' value='" + codigo + "'>" +
		//fin celda
		"</td>" +

		"<td>" +
		//descripcion producto fiscal para factura
		"<input class='form-control' id='" + nf + "description_01yuli' value='" + detalle + "'>" +
		//fin celda
		"</td>" +

		"<td>" +
		//precio_unidad producto fiscal para factura
		"<input class='form-control text-right' id = '" + nf + "priceUnit_01yuli' type='number' min='" + c_u_facturar_minimo + "' " +
		"onchange = 'actualizarSubTotal_01yuli()' oninput = 'actualizarSubTotal_01yuli()' value = '" + PrecioEspecial + "'>" +
		"<label for='" + nf + "priceUnit'> Facturar Minimo: <b>" + c_u_facturar_minimo + "</b></label>" +
		//fin celda
		"</td>" +

		"<td>" +
		//subtotal = precio producto fiscal * cantidad
		"<input class='form-control text-right' id='" + nf + "subTotal_01yuli'  value='' readonly>" +
		//fin celda
		"</td>" +

		"<td>" +
		//boton eliminar fila
		"<button class='btn btn-success borrar_01yuli' title='" + nf + " idFiscal :" + idProducto + "'>Eliminar</button>" +
		//fin celda
		"</td>" +

		"</tr>"
	);
	actualizarSubTotal_01yuli();
	actualizarTotal_01yuli();
}
//agregar todas las filas de la tabla a un array carrito para facturar
function capturar_carrito_01yuli() {
	let carrito = [];
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
//01 primer paso crear cliente en api para yuliimport  _01yuli
function primeraLlamadaCrearCliente_01yuli() {
	return new Promise((resolve, reject) => {
		let clientReasonSocial = $("#clientReasonSocial_01yuli").val(); //razon social
		let clientDocumentType = $("#clientDocumentType_01yuli").val();  //nit ,ci ,pasaporte
		let clientNroDocument = $("#clientNroDocument_01yuli").val();  //numero nit o nro carnet
		let clientCode = $("#clientCode_01yuli").val();					//codigo unico cliente
		let clientCity = $("#clientCity_01yuli").val();					//ciudad cliente(personal)
		let clientEmail = $("#clientEmail_01yuli").val();		// email cliente

		let datosPostCliente = "clientReasonSocial=" + encodeURIComponent(clientReasonSocial) +
			"&clientDocumentType=" + clientDocumentType +
			"&clientNroDocument=" + clientNroDocument +
			"&clientCode=" + clientCode +
			"&clientCity=" + clientCity +
			"&clientEmail=" + encodeURIComponent(clientEmail);
		console.log(datosPostCliente);
		$.ajax({
			url: 'includes/api_facturacion_yuliimport/crear_cliente.php',
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
//02 realizar factura en la api para yuliimport _01yuli
function segundaLlamadaFacturar_01yuli(clienteCreado) {
	return new Promise((resolve, reject) => {
		//cabeza factura
		let customer_id = clienteCreado.customer_id;
		let first_name = clienteCreado.first_name;
		let identity_document = clienteCreado.identity_document;
		let email = clienteCreado.email;
		let idCotizacion = $("#idCotizacion_01yuli").val();
		let tipo_documento_identidad = parseInt($("#clientDocumentType_01yuli").val());
		let codigo_metodo_pago = parseInt($("#paramPaymentMethod_01yuli").val());
		let subtotal = parseFloat($("#total_01yuli").val());
		let total = parseFloat($("#total_01yuli").val());
		let total_tax = parseFloat(total * 0.13);
		//array productos factura
		let items = { items: capturar_carrito_01yuli() }
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
			"&items=" + items;
		console.log(datosPostFactura);
		$.ajax({
			url: 'includes/api_facturacion_yuliimport/facturacionsintic.php',
			type: 'POST',
			dataType: 'json',
			data: datosPostFactura,
			success: function (data) {
				if (data.response == 'ok') {
					console.log('Factura Exitosa');
					swall_factura_exitosa_01yuli();
					setTimeout(function () {
						// location.replace('?root=facturacionListado');
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

function actualizarSubTotal_01yuli() {
	for (x = 0; x <= nf; x++) {
		if (document.getElementById(x + "priceUnit_01yuli") != null) {
			let price = parseFloat(document.getElementById(x + "priceUnit_01yuli").value);
			let qty = parseInt(document.getElementById(x + "qty_01yuli").value);
			document.getElementById(x + "subTotal_01yuli").value = (price * qty).toFixed(2);
		}
	}
	actualizarTotal_01yuli();
	clonar_tabla_a_02srl();//clonaryuli01 a la tabla de abajo srl02
}

function actualizarTotal_01yuli() {
	let sum = 0;
	for (x = 0; x <= nf; x++) {
		if (document.getElementById(x + "subTotal_01yuli") != null) {
			sum = sum + parseFloat(document.getElementById(x + "subTotal_01yuli").value);
		}
	}
	document.getElementById("total_01yuli").value = sum.toFixed(2);
}
//function actualizar code client
function actualizarclientCode_01yuli() {
	let iniciales = "";
	let clientReasonSocial = document.getElementById(
		"clientReasonSocial_01yuli"
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
	document.getElementById("clientCode_01yuli").value =
		"CLIENT-" + iniciales + clientReasonSocial.length;
}
//function actualizar code client
function swall_error_conexion() {
	Swal.fire({
		type: 'error',
		title: "ERROR CONEXIÓN",
		animation: false,
		customClass: {
			popup: 'animated bounceInDown'
		},
		text: "Error de  Conexion , Intente Nuevamente",
		icon: "error",
		button: "Ok",
	});
}
function swall_info_conectando() {
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
	});
}
function swall_factura_exitosa_01yuli() {
	Swal.fire({
		type: 'success',
		title: "FACTURACIÓN YULIIMPORT EXITOSA",
		animation: true,
		customClass: {
			popup: 'animated bounceInDown'
		},
		text: "Factura Yuliimport Guardada Correctamente",
	});
}

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

