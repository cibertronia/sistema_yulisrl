$(document).ready(function () {
	/*	CONSULA ABONOS ANTERIORES PARA AGREGARLOS A ESTE NUEVO	*/
	$(document).on('click', '.plusABONO', function (event) {
		event.preventDefault();
		var idCotizacion = $(this).attr("id");
		$("#suma_Recibo").val("");
		$("#enConceptodeRecibo").val("");
		$(".tablaAbonoAnticipo").removeClass('d-none');
		$(".tableCotizaciones").addClass('d-none');
		$("#byCantidad_").val("");
		$("#byAnticipo").val("");
		$.ajax({
			url: 'includes/consultas.php',
			type: 'POST',
			dataType: 'json',
			data: { idCotizacionRecibosAbonos: idCotizacion },
			success: function (data) {
				$("#idVendedor").val(data.idUser);
				$("#CodeCotiza").val(data.CodeCotizacion);
				$("#PrecioDolar").val(data.PrecioDolar);
				$("#idMoneda_Recibo").val(data.Moneda);
				$("#idCliente_abono").val(data.idCliente);
				$("#idCotizacion_Recibo").val(data.idCotizacion);
				$("#selectMoneda").val(data.Moneda);
				$("#byCantidad_").focus();
				$("#name_ClienteRecibo").val(data.Cliente);
				//$("#suma_Recibo").val(data.LaCantidadDe);
				$("#enConceptodeRecibo").val(data.EnConceptoDe);
				$("#SaldoActualRecibo").val(data.SaldoActual);
				$("#SaldoAnterior").val(data.SaldoAnterior);
				if (data.Moneda == 'USD') {
					$("#byTotal_").val(data.TotalUSD);
				} else {
					$("#byTotal_").val(data.Total);
				}
			}
		})
	});
	$("#byCantidad_").keyup(function () {
		var Cantidad = $(this).val();
		$("#byAnticipo").val(Cantidad);
		var SaldoActual = $("#SaldoActualRecibo").val();
		$("#SaldoActualRecibo").addClass('d-none');
		$("#SaldoActual_Recibo").removeClass('d-none');
		$("#SaldoActual_Recibo").val(SaldoActual - Cantidad);
		$("#SaldoAnterior").val(SaldoActual);
		$("#suma_Recibo").val(NumeroALetras(document.getElementById("byCantidad_").value));
	});
	$(document).on('click', '.closeTablaAnticipo', function (event) {
		event.preventDefault();
		$(".tablaAbonoAnticipo").addClass('d-none');
		$(".tableCotizaciones").removeClass('d-none');

		$("#SaldoActualRecibo").removeClass('d-none');
		$("#SaldoActual_Recibo").addClass('d-none');
		$("#SaldoActual_Recibo").val("");
		$("#SaldoAnterior").val("");
	});
	$("#selectMoneda").change(function () {
		$("#selectMoneda option:selected").each(function () {
			var Moneda = $("#selectMoneda option:selected").val();
			var Cantidad = $("#byTotal_").val();
			var Dolar = $("#precioDolar").val();

			if (Moneda == 'Bs') {
				$("#byTotal_").val(Number(Cantidad * Dolar).toFixed(2));
				$("#byCantidad_").val('');
				$("#byAnticipo").val('');
				$("#SaldoAnterior").val(Number(Cantidad * Dolar).toFixed(2));
				$("#SaldoActual").val('');
				$("#suma_Recibo").val('');

			} else {
				//$("#byCantidad_").val(Number(Cantidad/Dolar).toFixed(2));
				$("#byTotal_").val(Number(Cantidad / Dolar).toFixed(2));
				$("#byCantidad_").val('');
				$("#byAnticipo").val('');
				$("#SaldoAnterior").val(Number(Cantidad / Dolar).toFixed(2));
				$("#SaldoActual").val('');
				$("#suma_Recibo").val('');
			}

		});
	});
	/*	EDITAR EL PRIMER ABONO	*/
	$(document).on('click', '.editarAbono', function (event) {
		event.preventDefault();
		var idRecibo = $(this).attr("id");
		$(".tablaPrimerAbono").removeClass('d-none');
		$(".tableCotizaciones").addClass('d-none');
		$.ajax({
			url: 'includes/consultas.php',
			type: 'POST',
			dataType: 'json',
			data: { idRecibo: idRecibo },
			success: function (data) {
				$("#CodeCotiza_").val(data.CodeCotizacion);
				$("#precioDolar").val(data.PrecioDolar);
				$("#idRecibo").val(data.idRecibo);
				$("#idAbono1").val(data.idAbono);
				$("#idCliente1").val(data.idCliente);
				$("#selectMoneda_").val(data.Moneda);
				$("#selectMoneda_ option:selected").val(data.Moneda);
				if (data.Moneda == 'USD') {
					$("#by_Anticipo").val(data.anticipoUSD);
					$("#by_Cantidad").val(data.anticipoUSD);
					$("#by_Total").val(data.TotalUSD);
				} else {
					$("#by_Anticipo").val(data.porAnticipo);
					$("#by_Cantidad").val(data.porAnticipo);
					$("#by_Total").val(data.Total);
				}
				$("#nameCliente").val(data.Cliente);
				$("#sumaRecibo").val(data.LaCantidadDe);
				$("#en_Conceptode").val(data.EnConceptoDe);
				$("#SaldoAct").val(data.SaldoActual);
				$("#SaldoAnt").val(data.SaldoAnterior);
				$("#idCotizacion1").val(data.idCotizacion);
				if (data.Moneda == 'USD') {
					$("#Cant_Abono").html("<span class='text-danger'>$</span> " + data.anticipoUSD + " <span class='text-danger'>Dólares</span>");
				} else {
					$("#Cant_Abono").html("<span class='text-danger'>Bs</span> " + data.porAnticipo + " <span class='text-danger'>Bolivianos</span>");
				}
			}
		})
	});
	$(document).on('click', '.closePrimerAbono', function (event) {
		event.preventDefault();
		$(".tablaPrimerAbono").addClass('d-none');
		$(".tableCotizaciones").removeClass('d-none');
	});
	/*	EDITA LOS DATOS DEL SEGUNDO ABONO EN ADELANTE (EL ULTIMO ABONO REALIZADO)	*/
	$(document).on('click', '.ultimoAbono', function (event) {
		event.preventDefault();
		var idRecibo = $(this).attr("id");
		$(".tableCotizaciones").addClass('d-none');
		$(".tablaUltimoAbono").removeClass('d-none');
		$("#by_Cantidad_").val("");
		$("#by_Anticipo_").val("");
		$.ajax({
			url: 'includes/consultas.php',
			type: 'POST',
			dataType: 'json',
			data: { idRecibo: idRecibo },
			success: function (data) {
				$("#codeCotizacion").val(data.CodeCotizacion);
				$("#id_recibo").val(data.idRecibo);
				$("#id_abono").val(data.idAbono);
				$("#select_Moneda").val(data.Moneda);
				$("#Precio_Dolar").val(data.PrecioDolar);
				if (data.Moneda == 'USD') {
					$("#by_Cantidad_").val(data.anticipoUSD);
					$("#by_Anticipo_").val(data.anticipoUSD);
					$("#by_Total_").val(data.TotalUSD);
				} else {
					$("#by_Cantidad_").val(data.porAnticipo);
					$("#by_Anticipo_").val(data.porAnticipo);
					$("#by_Total_").val(data.Total);
				}
				$("#idCliente2").val(data.idCliente);
				$("#name_Cliente_").val(data.Cliente);
				$("#_sumaRecibo").val(data.LaCantidadDe);
				$("#en_Conceptode_").val(data.EnConceptoDe);
				$("#Saldo_actual").val(data.SaldoActual);
				$("#Saldo_Act").val(data.SaldoActual);
				$("#Saldo_Ant").val(data.SaldoAnterior);
				$("#idCotizacion2").val(data.idCotizacion);
				$("#moneda2").val(data.Moneda);
			}
		})
	});

	$(document).on('click', '.closeUltimoAbono', function (event) {
		event.preventDefault();
		$(".tableCotizaciones").removeClass('d-none');
		$(".tablaUltimoAbono").addClass('d-none');
		$("#Saldo_actual").removeClass('d-none');
		// $("#Saldo_Act").addClass('d-none');
		$("#Saldo_Ant").val("");
		$("#SaldoActualRecibo").val("");
	});
	$("#by_Cantidad_").keyup(function () {
		var Cantidad = $(this).val(); 			//Cantidad será igual a la cantidad que se escriba en el input       		
		$("#by_Anticipo_").val(Cantidad);		//el anticipo será igual a la cantidad que se escriba en el input CANTIDAD
		// $("#Saldo_Ant").val("");						//el la cantidad en letras se modificará cada vez que se modifique el campo CANTIDAD
		// var SaldoActual = $("#Saldo_actual").val();
		let SaldoAnterior = $("#Saldo_Ant").val();
		$("#Saldo_Act").val(SaldoAnterior - Cantidad);

		// $("#Saldo_Act").val(SaldoAnterior);
		// $("#Saldo_Ant").val(SaldoActual);

		// $("#Saldo_actual").addClass('d-none');
		// $("#Saldo_Act").removeClass('d-none');
		$("#_sumaRecibo").val(NumeroALetras(document.getElementById("by_Cantidad_").value));

	});
	$("#selectMoneda_").change(function () {
		$("#selectMoneda_ option:selected").each(function () {
			var Moneda = $("#selectMoneda_ option:selected").val();
			var Dolar = $("#precioDolar").val();
			var Cantidad = $("#by_Total").val();
			var Anticipo = $("#by_Anticipo").val();
			var SaldoAnterior = $("#SaldoAnt").val();
			var SaldoActual = $("#SaldoAct").val();

			if (Moneda == 'Bs') {
				$("#Cant_Abono").html("<span class='text-danger'>Bs </span>" + Number((Anticipo * Dolar).toFixed(2)) + " <span class='text-danger'> Bolivianos</span>");
				$("#by_Total").val(Number(Cantidad * Dolar).toFixed(2));
				$("#by_Anticipo").val(Number(Anticipo * Dolar).toFixed(2));
				$("#SaldoAct").val(Number(SaldoActual * Dolar).toFixed(2));
				$("#SaldoAnt").val(Number(SaldoAnterior * Dolar).toFixed(2));
				$("#by_Cantidad").val(Number(Anticipo * Dolar).toFixed(2));
				$("#sumaRecibo").val('');

			} else {
				$("#Cant_Abono").html("<span class='text-danger'>$ </span>" + Number((Anticipo / Dolar).toFixed(2)) + " <span class='text-danger'> Dólares</span>");
				$("#by_Total").val(Number(Cantidad / Dolar).toFixed(2));
				$("#by_Anticipo").val(Number(Anticipo / Dolar).toFixed(2));
				$("#SaldoAct").val(Number(SaldoActual / Dolar).toFixed(2));
				$("#SaldoAnt").val(Number(SaldoAnterior / Dolar).toFixed(2));
				$("#by_Cantidad").val(Number(Anticipo / Dolar).toFixed(2));
				$("#sumaRecibo").val('');
			}

			$("#sumaRecibo").val(NumeroALetras(document.getElementById("by_Cantidad").value));


		});
	});
	/*	ACTUALIZANDO LOS DATOS DEL PRIMER ABONO	*/
	$(document).on('click', '.updatePrimerAbono', function (event) {
		event.preventDefault();
		$(".updatePay").removeClass('d-none');
		$(".updatePrimerAbono").attr('disabled', true);
		$.ajax({
			url: 'do.php',
			type: 'POST',
			dataType: 'html',
			data: $("#formPrmimerAbono").serialize(),
		})
			.done(function (data) {
				$(".updatePay").addClass('d-none');
				$(".updatePrimerAbono").attr('disabled', false);
				$(".respuesta").html(data);
			})
		return false;
	});
	/*	ACTUALIZANDO LOS DATOS de seundo o mas ABONO	*/
	$(document).on('click', '.updateRecibo', function (event) {
		event.preventDefault();
		$(".updateRecibo").attr('disabled', true);
		$(".updateLastPay").removeClass('d-none');
		$.ajax({
			url: 'do.php',
			type: 'POST',
			dataType: 'html',
			data: $("#form_ReciboUPD").serialize(),
		})
			.done(function (data) {
				$(".updateRecibo").attr('disabled', false);
				$(".updateLastPay").addClass('d-none');
				$(".respuesta").html(data);
			})
		return false;
	});
	$(document).on('click', '.addNewAbono', function (event) {
		event.preventDefault();
		var cantidad = $("#byCantidad_").val();
		var nameCliente = $("#name_ClienteRecibo").val();
		var laSumade = $("#suma_Recibo").val();
		var enConceptode = $("#enConceptodeRecibo").val();
		var anticipo = $("#byAnticipo").val();
		var saldoAnt = $("#SaldoAnterior").val();
		var saldoAct = $("#SaldoActual_Recibo").val();
		var total = $("#byTotal_").val();
		if (cantidad == '') {
			$(".noCantRecibo").removeClass('d-none');
			$("#byCantidad_").addClass('parsley-error')
			setTimeout(function () {
				$(".noCantRecibo").addClass('d-none');
				$("#byCantidad_").removeClass('parsley-error');
				$("#byCantidad_").focus();
			}, 2000);
		} else if (nameCliente == '') {
			$("#name_Cliente").addClass('parsley-error')
			$(".noCantRecibo").removeClass('d-none');
			setTimeout(function () {
				$("#name_Cliente").removeClass('parsley-error')
				$(".noCantRecibo").addClass('d-none');
				$("#name_Cliente").focus();
			}, 2000);
		} else if (laSumade == '') {
			$("#suma_Recibo").addClass('parsley-error')
			$(".noSumaRecibo").removeClass('d-none');
			setTimeout(function () {
				$("#suma_Recibo").removeClass('parsley-error')
				$(".noSumaRecibo").addClass('d-none');
				$("#suma_Recibo").focus();
			}, 2000);
		} else if (enConceptode == '') {
			$("#enConceptode").addClass('parsley-error')
			$(".noConceptoRecibo").removeClass('d-none');
			setTimeout(function () {
				$("#enConceptode").removeClass('parsley-error')
				$(".noConceptoRecibo").addClass('d-none');
				$("#enConceptode").focus();
			}, 2000);
		} else if (anticipo == '') {
			$("#byAnticipo").addClass('parsley-error')
			$(".noAnticipoRecibo").removeClass('d-none');
			setTimeout(function () {
				$("#byAnticipo").removeClass('parsley-error')
				$(".noAnticipoRecibo").addClass('d-none');
				$("#byAnticipo").focus();
			}, 2000);
		} else if (saldoAnt == '') {
			$("#SaldoAnterior").addClass('parsley-error')
			$(".noSaldoAnterior").removeClass('d-none');
			setTimeout(function () {
				$("#SaldoAnterior").removeClass('parsley-error')
				$(".noSaldoAnterior").addClass('d-none');
				$("#SaldoAnterior").focus();
			}, 2000);
		} else if (saldoAct == '') {
			$("#SaldoActual").addClass('parsley-error')
			$(".noSaldoActual").removeClass('d-none');
			setTimeout(function () {
				$("#SaldoActual").removeClass('parsley-error')
				$(".noSaldoActual").addClass('d-none');
				$("#SaldoActual").focus();
			}, 2000);
		} else if (total == '') {
			$("#byTotal_").addClass('parsley-error')
			$(".noTotalRecibo").removeClass('d-none');
			setTimeout(function () {
				$("#byTotal_").removeClass('parsley-error')
				$(".noTotalRecibo").addClass('d-none');
				$("#byTotal_").focus();
			}, 2000);
		} else {
			$(".newPay").removeClass('d-none');
			$(".addNewAbono").attr('disabled', true);
			$.ajax({
				url: 'do.php',
				type: 'POST',
				dataType: 'html',
				data: $("#formPlus_Abono").serialize(),
			})
			.done(function (data) {
				$(".respuesta").html(data);
			})
			.fail(function(e) {
				let errorObj;
				if (e.responseText) {
					errorObj =  JSON.parse(e.responseText);
				} else {
					errorObj = { error: "Ocurrió un error desconocido" };
				}
				
				Swal.fire({
					type: 'error',
					title: 'Error al procesar el abono!',
					text: errorObj.error,
					animation: false,
					customClass: {
						popup: 'animated bounceInDown'
					}
				})
				setTimeout(function(){
					location.replace('?root=entregadas');
				},3500);				
			})
			return false;
		}
		return false;
	});
	$(document).on('click', '.Buscar', function (event) {
		event.preventDefault();
		$("#buscar").removeClass('d-none');
	});
	$(document).on('click', '.borrarVentaAnticipo', function (event) {
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
						data: "action=BorrarVentaporAnticipo&idCotizacion=" + idCotizacion,
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

	// FORM EDITAR ABONO INICIAL
	$("#by_Cantidad").keyup(function() {
		var Cantidad = $(this).val(); //Cantidad será igual a la cantidad que se escriba en el input       		
		$("#by_Anticipo").val(Cantidad); //el anticipo será igual a la cantidad que se escriba en el input CANTIDAD		
		let anterior = $("#SaldoAnt").val(); //el la cantidad en letras se modificará cada vez que se modifique el campo CANTIDAD
		$("#SaldoAct").val(anterior - Cantidad);
		$("#sumaRecibo").val(NumeroALetras(document.getElementById("by_Cantidad").value));
	});

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



//generar literal de numerico -------------------------------------------
document.getElementById("porCantidad").addEventListener("keyup", function (e) {
	// document.getElementById("texto").innerHTML=NumeroALetras(this.value);
	document.getElementById("cantidadLetras").value = NumeroALetras(this.value);
});


function Unidades(num) {

	switch (num) {
		case 1: return "UN";
		case 2: return "DOS";
		case 3: return "TRES";
		case 4: return "CUATRO";
		case 5: return "CINCO";
		case 6: return "SEIS";
		case 7: return "SIETE";
		case 8: return "OCHO";
		case 9: return "NUEVE";
	}

	return "";
}

function Decenas(num) {

	decena = Math.floor(num / 10);
	unidad = num - (decena * 10);

	switch (decena) {
		case 1:
			switch (unidad) {
				case 0: return "DIEZ";
				case 1: return "ONCE";
				case 2: return "DOCE";
				case 3: return "TRECE";
				case 4: return "CATORCE";
				case 5: return "QUINCE";
				default: return "DIECI" + Unidades(unidad);
			}
		case 2:
			switch (unidad) {
				case 0: return "VEINTE";
				default: return "VEINTI" + Unidades(unidad);
			}
		case 3: return DecenasY("TREINTA", unidad);
		case 4: return DecenasY("CUARENTA", unidad);
		case 5: return DecenasY("CINCUENTA", unidad);
		case 6: return DecenasY("SESENTA", unidad);
		case 7: return DecenasY("SETENTA", unidad);
		case 8: return DecenasY("OCHENTA", unidad);
		case 9: return DecenasY("NOVENTA", unidad);
		case 0: return Unidades(unidad);
	}
}//Unidades()

function DecenasY(strSin, numUnidades) {
	if (numUnidades > 0)
		return strSin + " Y " + Unidades(numUnidades)

	return strSin;
}//DecenasY()

function Centenas(num) {

	centenas = Math.floor(num / 100);
	decenas = num - (centenas * 100);

	switch (centenas) {
		case 1:
			if (decenas > 0)
				return "CIENTO " + Decenas(decenas);
			return "CIEN";
		case 2: return "DOSCIENTOS " + Decenas(decenas);
		case 3: return "TRESCIENTOS " + Decenas(decenas);
		case 4: return "CUATROCIENTOS " + Decenas(decenas);
		case 5: return "QUINIENTOS " + Decenas(decenas);
		case 6: return "SEISCIENTOS " + Decenas(decenas);
		case 7: return "SETECIENTOS " + Decenas(decenas);
		case 8: return "OCHOCIENTOS " + Decenas(decenas);
		case 9: return "NOVECIENTOS " + Decenas(decenas);
	}

	return Decenas(decenas);
}//Centenas()

function Seccion(num, divisor, strSingular, strPlural) {
	cientos = Math.floor(num / divisor)
	resto = num - (cientos * divisor)

	letras = "";

	if (cientos > 0)
		if (cientos > 1)
			letras = Centenas(cientos) + " " + strPlural;
		else
			letras = strSingular;

	if (resto > 0)
		letras += "";

	return letras;
}//Seccion()

function Miles(num) {
	divisor = 1000;
	cientos = Math.floor(num / divisor)
	resto = num - (cientos * divisor)

	strMiles = Seccion(num, divisor, "MIL", "MIL");
	strCentenas = Centenas(resto);

	if (strMiles == "")
		return strCentenas;

	return strMiles + " " + strCentenas;

	//return Seccion(num, divisor, "UN MIL", "MIL") + " " + Centenas(resto);
}//Miles()

function Millones(num) {
	divisor = 1000000;
	cientos = Math.floor(num / divisor)
	resto = num - (cientos * divisor)

	strMillones = Seccion(num, divisor, "UN MILLON", "MILLONES");
	strMiles = Miles(resto);

	if (strMillones == "")
		return strMiles;

	return strMillones + " " + strMiles;

	//return Seccion(num, divisor, "UN MILLON", "MILLONES") + " " + Miles(resto);
}//Millones()

function NumeroALetras(num, centavos) {
	var data = {
		numero: num,
		enteros: Math.floor(num),
		centavos: (((Math.round(num * 100)) - (Math.floor(num) * 100))),
		letrasCentavos: "",
	};
	if (centavos == undefined || centavos == false) {
		data.letrasMonedaPlural = "";
		data.letrasMonedaSingular = "";
	} else {
		data.letrasMonedaPlural = "CENTAVOS";
		data.letrasMonedaSingular = "CENTAVO";
	}

	if (data.centavos > 0) {
		//data.letrasCentavos = "CON " + NumeroALetras(data.centavos,true);
		data.letrasCentavos = data.centavos + "/100";
	} else { data.letrasCentavos = "00/100"; }

	if (data.enteros == 0)
		return "CERO " + data.letrasMonedaPlural + " " + data.letrasCentavos;
	if (data.enteros == 1)
		return Millones(data.enteros) + " " + data.letrasMonedaSingular + " " + data.letrasCentavos;
	else
		return Millones(data.enteros) + " " + data.letrasMonedaPlural + " " + data.letrasCentavos;
}

