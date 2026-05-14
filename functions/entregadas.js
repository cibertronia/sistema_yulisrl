$(document).ready(function() {
	$("#byCantidad_").keyup(function () {
 		var Anticipo =	$(this).val();
 		var Total 	 =	$("#byTotal_").val();
 		var SaldoAnt =	Total-Anticipo;
 		$("#byAnticipo").val(Anticipo);
 		$("#SaldoAct").val((SaldoAnt).toFixed(2));
		$("#suma_Recibo").val(NumeroALetras(document.getElementById("byCantidad_").value));
	});
	$("#porCantidadCredito").keyup(function () {
 		var Anticipo =	$(this).val();
 		var Total 	 =	$("#TotalCredito").val();
 		var SaldoAnt =	Total-Anticipo;
 		$("#AnticipoCredito").val(Anticipo);
 		$("#SaldoActCredito").val((SaldoAnt).toFixed(2));
		$("#laSumaDeRecibo").val(NumeroALetras(document.getElementById("porCantidadCredito").value));
		
	});
	$(document).on('click', '.llamarDatosCotizacion', function(event) {
		event.preventDefault();
		var idCotizacion = $(this).attr("id");
		$(".tableCotizaciones").addClass('d-none');
		$(".fomrVentaCash").removeClass('d-none');
		$.ajax({
			url: 'includes/consultas.php',
			type: 'POST',
			dataType: 'json',
			data: {idCotizacionVenta: idCotizacion},
			success:function(data){
				$("#selectedMoneda").val("USD");
				//$("#cantidadLetras").val("");
				$("#cantidadLetras").focus();
				
				$("#en_Conceptode").val(data.Prod);
				$("#Code_Cotiza").val(data.CodeCotiza);
				$("#porCantidad").val(Number(data.Total).toFixed(2));
				$("#ClienteName").val(data.NameCliente);
				$("#idUserPago").val(data.idUser);
				$("#idClientePago").val(data.idCliente);
				$("#SucursalPago").val(data.Sucursal);
				$("#idCotizaPago").val(data.idCotizacion);
				$("#cantidadLetras").val(NumeroALetras(document.getElementById("porCantidad").value));
			}
		})
		//var xd=$("#porCantidad").val(Number(data.Total).toFixed(2));
		//$("#cantidadLetras").val(NumeroALetras(xd));
		//document.getElementById("cantidadLetras").value
	});
	$(document).on('click', '.closeFormVenta', function(event) {
		event.preventDefault();
		$(".tableCotizaciones").removeClass('d-none');
		$(".fomrVentaCash").addClass('d-none');
	});
	$("#selectedMoneda").change(function() {
		$("#selectedMoneda option:selected").each(function() {
			var Moneda 		=	$("#selectedMoneda option:selected").val();
			var Cantidad 	=	$("#porCantidad").val();
			var valorDolar 	=	$("#precio_Dolar").val();
			var CodeCotiza 	=	$("#Code_Cotiza").val();
			if (Moneda=='USD') {
				$("#porCantidad").val((Cantidad/valorDolar).toFixed(2));
				$("#cantidadLetras").val("");
				
				
                $.ajax({
                    url: 'includes/consultas.php',
                    type: 'POST',
                    dataType: 'json',
                    data: {idCotizacionVentaUsd: CodeCotiza},
                    success:function(data){
                        $("#en_Conceptode").val(data.Prod);
                    }
                })

			}else{
				//alert("Esta trabajando");
				$("#porCantidad").val((Cantidad*valorDolar).toFixed(2));
				$("#cantidadLetras").val("");
				
				$.ajax({
                    url: 'includes/consultas.php',
                    type: 'POST',
                    dataType: 'json',
                    data: {idCotizacionVentaBs: CodeCotiza},
                    success:function(data){
                        $("#en_Conceptode").val(data.Prod);
                    }
                })
                
			}
			$("#cantidadLetras").val(NumeroALetras(document.getElementById("porCantidad").value));
			
			
			
	 	
		
		
		});
		
		
		
		
		
	});
	$(document).on('click', '.guardarPago', function(event) {
		event.preventDefault();
		var CodeCotiza 	=	$("#Code_Cotiza").val();
		var precioDolar	=	$("#precio_Dolar").val();
		var Moneda 			=	$("#selectedMoneda option:selected").val();
		var Cantidad 		=	$("#porCantidad").val();
		var NameCliente =	$("#ClienteName").val();
		var sumaRecibo 	=	$("#cantidadLetras").val();
		var Concepto 		=	$("#en_Conceptode").val();
		if (CodeCotiza=='') {
			$(".noCodeCotiza").removeClass('d-none');
			$("#Code_Cotiza").addClass('parsley-error');
			setTimeout(function(){
				$(".noCodeCotiza").addClass('d-none');
				$("#Code_Cotiza").removeClass('parsley-error');
				$("#Code_Cotiza").focus();
			},2500);
			return false;
		}else if (precioDolar=='') {
			$(".noPrecioDolar").removeClass('d-none');
			$("#precio_Dolar").addClass('parsley-error');
			setTimeout(function(){
				$(".noPrecioDolar").addClass('d-none');
				$("#precio_Dolar").removeClass('parsley-error');
				$("#precio_Dolar").focus();
			},2500);
			return false;
		}else if (Cantidad=='') {
			$(".noCantPago").removeClass('d-none');
			$("#porCantidad").addClass('parsley-error');
			setTimeout(function(){
				$(".noCantPago").addClass('d-none');
				$("#porCantidad").removeClass('parsley-error');
				$("#porCantidad").focus();
			},2500);
			return false;
		}else if (NameCliente=='') {
			$(".noClientePago").removeClass('d-none');
			$("#ClienteName").addClass('parsley-error');
			setTimeout(function(){
				$(".noClientePago").addClass('d-none');
				$("#ClienteName").removeClass('parsley-error');
				$("#ClienteName").focus();
			},2500);
			return false;
		}else if (sumaRecibo=='') {
			$(".noSumaPago").removeClass('d-none');
			$("#cantidadLetras").addClass('parsley-error');
			setTimeout(function(){
				$(".noSumaPago").addClass('d-none');
				$("#cantidadLetras").removeClass('parsley-error');
				$("#cantidadLetras").focus();
			},2500);
			return false;
		}else if (Concepto=='') {
			$(".noConceptoPago").removeClass('d-none');
			$("#en_Conceptode").addClass('parsley-error');
			setTimeout(function(){
				$(".noConceptoPago").addClass('d-none');
				$("#en_Conceptode").removeClass('parsley-error');
				$("#en_Conceptode").focus();
			},2500);
			return false;
		}else{
			$(".savePay").removeClass('d-none');
			$(".guardarPago").attr('disabled', true);
			$.ajax({
				url: 'do.php',
				type: 'POST',
				dataType: 'html',
				data: $("#formVenta").serialize(),
			})
			.done(function(data) {				
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
					title: 'Ocurrio un error en la venta!',
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
	});
	$(document).on('click', '.cambiarComprada', function(event) {
		event.preventDefault();
		var idCotizacion = $(this).attr("id");
		$.ajax({
			url: 'do.php',
			type: 'POST',
			dataType: 'html',
			data: "action=MarcarCotcomoComprada&id="+idCotizacion,
		})
		.done(function(data) {
			$(".respuesta").html(data);
		})
		return false;
	});
	$(document).on('click', '.cambiarGenegada', function(event) {
		event.preventDefault();
		var idCotizacion = $(this).attr("id");
		$.ajax({
			url: 'do.php',
			type: 'POST',
			dataType: 'html',
			data: "action=cambiar_a_CotGenerada&id="+idCotizacion,
		})
		.done(function(data) {
			$(".respuesta").html(data);
		})
		return false;
	});
	/*	LLAMA LOS DATOS DE LA COTIZACION	*/
	$(document).on('click', '.porAbono', function(event) {
		event.preventDefault();
		var idCotizacion = $(this).attr("id");
		$(".formbyAbono").removeClass('d-none');
		$(".tableCotizaciones").addClass('d-none');
    $.ajax({
    	url: 'includes/consultas.php',
    	type: 'POST',
    	dataType: 'json',
    	data: {id: idCotizacion},
    	success:function(data){
			$("#suma_Recibo").val("");
    		$("#selectMoneda").val("USD");
    		$("#byCantidad_").val("");
    		$("#byCantidad_").focus();
    		$("#byAnticipo").val("");
    		$("#byTotal_").val(data.TOTAL);
    		$("#SaldoAct").val(data.TOTAL);
    		$("#SaldoAnt").val(data.TOTAL);
    		$("#CodeCotiza").val(data.CodeCotiza);
    		$("#idCotizacion_Recibo").val(data.idCotizacion);
    		$("#byCantidad").val("0");
    		$("#name_Cliente").val(data.NameCliente);
    		$("#id_Cliente").val(data.idCliente);
			$("#enConceptode").val(data.Prod);
    	}
    })
	});
	$(document).on('click', '.closeFormAbono', function(event) {
		event.preventDefault();
		$(".tableCotizaciones").removeClass('d-none');
		$(".formbyAbono").addClass('d-none');
	});
	/*	CAMBIAR DE MONEDA 	*/
	$("#selectMoneda").change(function() {
		$("#selectMoneda option:selected").each(function() {
			var Moneda = $("#selectMoneda option:selected").val();
			var Cantidad = $("#byTotal_").val();
			var Dolar = $("#precioDolar").val();

			if (Moneda=='Bs') {
				$("#byTotal_").val(Number(Cantidad*Dolar).toFixed(2));
				$("#SaldoAnt").val(Number(Cantidad*Dolar).toFixed(2));
				$("#SaldoAct").val(Number(Cantidad*Dolar).toFixed(2));
				$("#byAnticipo").val('');
				$("#byCantidad_").val('');
				$("#suma_Recibo").val('');

			}else{
				$("#byTotal_").val(Number(Cantidad/Dolar).toFixed(2));
				$("#SaldoAnt").val(Number(Cantidad/Dolar).toFixed(2));
				$("#SaldoAct").val(Number(Cantidad/Dolar).toFixed(2));
				$("#byAnticipo").val('');
				$("#byCantidad_").val('');
				$("#byAnticipo").val('');
				$("#suma_Recibo").val('');
			}

		});
	});
	$(document).on('click', '.guardarAbono', function(event) {
		event.preventDefault();
		var	Cantidad 	=	$("#byCantidad_").val();
		var Cliente 	=	$("#name_Cliente").val();
		var sumaRecibo=	$("#suma_Recibo").val();
		var Concepto 	=	$("#enConceptode").val();
		var Anticipo 	=	$("#byAnticipo").val();
		var SaldoAct 	=	$("#SaldoAct").val();
		var SaldoAnt 	=	$("#SaldoAnt").val();
		var Total 		=	$("#byTotal_").val();

		if (Cantidad=='') {
			$(".noCantRecibo").removeClass('d-none');
			$("#byCantidad_").addClass('parsley-error');
			setTimeout(function(){
				$(".noCantRecibo").addClass('d-none');
				$("#byCantidad_").removeClass('parsley-error');
				$("#byCantidad_").focus();
			},2000);
		}else if (Cliente=='') {
			$(".noClienteRecibo").removeClass('d-none');
			$("#name_Cliente").addClass('parsley-error');
			setTimeout(function(){
				$(".noClienteRecibo").addClass('d-none');
				$("#name_Cliente").removeClass('parsley-error');
				$("#name_Cliente").focus();
			},2000);
		}else if (sumaRecibo=='') {
			$(".noSumaRecibo").removeClass('d-none');
			$("#suma_Recibo").addClass('parsley-error');
			setTimeout(function(){
				$(".noSumaRecibo").addClass('d-none');
				$("#suma_Recibo").removeClass('parsley-error');
				$("#suma_Recibo").focus();
			},2000);
		}else if (Concepto=='') {
			$(".noConceptoRecibo").removeClass('d-none');
			$("#enConceptode").addClass('parsley-error');
			setTimeout(function(){
				$(".noConceptoRecibo").addClass('d-none');
				$("#enConceptode").removeClass('parsley-error');
				$("#enConceptode").focus();
			},2000);
		}else if (Anticipo=='') {
			$(".noAnticipoRecibo").removeClass('d-none');
			$("#byAnticipo").addClass('parsley-error');
			setTimeout(function(){
				$(".noAnticipoRecibo").addClass('d-none');
				$("#byAnticipo").removeClass('parsley-error');
				$("#byAnticipo").focus();
			},2000);
		}else if (SaldoAct=='') {
			$(".noSaldoActual").removeClass('d-none');
			$("#SaldoAct").addClass('parsley-error');
			setTimeout(function(){
				$(".noSaldoActual").addClass('d-none');
				$("#SaldoAct").removeClass('parsley-error');
				$("#SaldoAct").focus();
			},2000);
		}else if (SaldoAnt=='') {
			$(".noSaldoAnterior").removeClass('d-none');
			$("#SaldoAnt").addClass('parsley-error');
			setTimeout(function(){
				$(".noSaldoAnterior").addClass('d-none');
				$("#SaldoAnt").removeClass('parsley-error');
				$("#SaldoAnt").focus();
			},2000);
		}else if (Total=='') {
			$(".noTotalRecibo").removeClass('d-none');
			$("#byTotal_").addClass('parsley-error');
			setTimeout(function(){
				$(".noTotalRecibo").addClass('d-none');
				$("#byTotal_").removeClass('parsley-error');
				$("#byTotal_").focus();
			},2000);
		}else{
			$(".saveAbonoEfect1").removeClass('d-none');
			$(".guardarAbono").attr('disabled', true);
			$.ajax({
				url: 'do.php',
				type: 'POST',
				dataType: 'html',
				data: $("#formAbono").serialize(),
			})
			.done(function(data) {				
				$(".respuesta").html(data);
			})
			return false;
		}
	});
	/*	LLAMA LOS DATOS DE LA COTIZACION PARA EL CRÉDITO	*/
	$(document).on('click', '.alCredito', function(event) {
		event.preventDefault();
		var idCotizacion = $(this).attr("id");
		$(".formCredito").removeClass('d-none');
		$(".tableCotizaciones").addClass('d-none');
		$.ajax({
			url: 'includes/consultas.php',
			type: 'POST',
			dataType: 'json',
			data: {id: idCotizacion},
			success:function(data){
				$("#selectMonedaCredito").val("USD");
				$("#porCantidadCredito").val("");
				$("#porCantidadCredito").focus();
				$("#AnticipoCredito").val("");
				$("#TotalCredito").val(data.TOTAL);
				$("#SaldoActCredito").val(data.TOTAL);
				$("#SaldoAntCredito").val(data.TOTAL);
				$("#CCotizaCredito").val(data.CodeCotiza);
				$("#idCotizacionCredito").val(data.idCotizacion);
				$("#nameClienteCredito").val(data.NameCliente);
				$("#idClienteCredito").val(data.idCliente);
				//$("#porCantidadCredito").val("0");
				$("#ConcetoCredito").val(data.Prod);
			}
		})
	});
	$(document).on('click', '.closeFormCredito', function(event) {
		event.preventDefault();
		$(".tableCotizaciones").removeClass('d-none');
		$(".formCredito").addClass('d-none');
	});
	/*	CAMBIAR DE MONEDA 	*/
	$("#selectMonedaCredito").change(function() {
		$("#selectMonedaCredito option:selected").each(function() {
			var Moneda 	=	$("#selectMonedaCredito option:selected").val();
			var Cantidad=	$("#porCantidadCredito").val();
			var Dolar 	=	$("#preDolarCredito").val();
			var SaldoAct=	$("#SaldoActCredito").val();
			var SaldoAnt=	$("#SaldoAntCredito").val();
			var Total 	=	$("#TotalCredito").val();

			if (Moneda=='Bs') {
				$("#TotalCredito").val(Number(Total*Dolar).toFixed(2));
				$("#SaldoAntCredito").val(Number(Total*Dolar).toFixed(2));
				$("#SaldoActCredito").val(Number(Total*Dolar).toFixed(2));
				$("#AnticipoCredito").val('');
				$("#porCantidadCredito").val('');
				$("#suma_Recibo").val('');

			}else{
				$("#TotalCredito").val(Number(Total/Dolar).toFixed(2));
				$("#SaldoAntCredito").val(Number(Total/Dolar).toFixed(2));
				$("#SaldoActCredito").val(Number(Total/Dolar).toFixed(2));
				$("#AnticipoCredito").val('');
				$("#porCantidadCredito").val('');
				$("#laSumaDeRecibo").val('');
			}

		});
	});
	/*	ABONO CRÉDITO	*/
	$(document).on('click', '.formularioCredito', function(event) {
		event.preventDefault();
		var	Cantidad 	=	$("#porCantidadCredito").val();
		var Cliente 	=	$("#nameClienteCredito").val();
		var sumaRecibo 	=	$("#laSumaDeRecibo").val();
		var Concepto 	=	$("#ConcetoCredito").val();
		var Anticipo 	=	$("#AnticipoCredito").val();
		var SaldoAct 	=	$("#SaldoActCredito").val();
		var SaldoAnt 	=	$("#SaldoAntCredito").val();
		var Total 		=	$("#TotalCredito").val();

		if (Cantidad=='') {
			$(".noCantRecibo").removeClass('d-none');
			$("#porCantidadCredito").addClass('parsley-error');
			setTimeout(function(){
				$(".noCantRecibo").addClass('d-none');
				$("#porCantidadCredito").removeClass('parsley-error');
				$("#porCantidadCredito").focus();
			},2000);
		}else if (Cliente=='') {
			$(".noClienteRecibo").removeClass('d-none');
			$("#nameClienteCredito").addClass('parsley-error');
			setTimeout(function(){
				$(".noClienteRecibo").addClass('d-none');
				$("#nameClienteCredito").removeClass('parsley-error');
				$("#nameClienteCredito").focus();
			},2000);
		}else if (sumaRecibo=='') {
			$(".noSumaRecibo").removeClass('d-none');
			$("#laSumaDeRecibo").addClass('parsley-error');
			setTimeout(function(){
				$(".noSumaRecibo").addClass('d-none');
				$("#laSumaDeRecibo").removeClass('parsley-error');
				$("#laSumaDeRecibo").focus();
			},2000);
		}else if (Concepto=='') {
			$(".noConceptoRecibo").removeClass('d-none');
			$("#ConcetoCredito").addClass('parsley-error');
			setTimeout(function(){
				$(".noConceptoRecibo").addClass('d-none');
				$("#ConcetoCredito").removeClass('parsley-error');
				$("#ConcetoCredito").focus();
			},2000);
		}else if (Anticipo=='') {
			$(".noAnticipoRecibo").removeClass('d-none');
			$("#AnticipoCredito").addClass('parsley-error');
			setTimeout(function(){
				$(".noAnticipoRecibo").addClass('d-none');
				$("#AnticipoCredito").removeClass('parsley-error');
				$("#AnticipoCredito").focus();
			},2000);
		}else if (SaldoAct=='') {
			$(".noSaldoActual").removeClass('d-none');
			$("#SaldoActCredito").addClass('parsley-error');
			setTimeout(function(){
				$(".noSaldoActual").addClass('d-none');
				$("#SaldoActCredito").removeClass('parsley-error');
				$("#SaldoActCredito").focus();
			},2000);
		}else if (SaldoAnt=='') {
			$(".noSaldoAnterior").removeClass('d-none');
			$("#SaldoAntCredito").addClass('parsley-error');
			setTimeout(function(){
				$(".noSaldoAnterior").addClass('d-none');
				$("#SaldoAntCredito").removeClass('parsley-error');
				$("#SaldoAntCredito").focus();
			},2000);
		}else if (Total=='') {
			$(".noTotalRecibo").removeClass('d-none');
			$("#TotalCredito").addClass('parsley-error');
			setTimeout(function(){
				$(".noTotalRecibo").addClass('d-none');
				$("#TotalCredito").removeClass('parsley-error');
				$("#TotalCredito").focus();
			},2000);
		}else{
			$(".saveAbCredit").removeClass('d-none');
			$(".formularioCredito").attr('disabled', true);
			$.ajax({
				url: 'do.php',
				type: 'POST',
				dataType: 'json',
				data: $("#formCredito").serialize(),
			})
			.done(function(data) {
				$(".saveAbCredit").addClass('d-none');
				$(".formularioCredito").attr('disabled', false);
				// $(".respuesta").html(data);

				Swal.fire({
					type: 'success',
					title: 'Abono guardado correctamente!',
					text: data.message,
					animation: false,
					customClass: {
						popup: 'animated bounceInDown'
					}
				})
				setTimeout(function(){
					location.replace('?root=credito');
				},2000);
			})
			.fail(function(e) {				
				let error = e.responseJSON.error;
				
				Swal.fire({
					type: 'error',
					title: 'Ocurrio un error al guardar credíto!',
					text: error,
					animation: false,
					customClass: {
						popup: 'animated bounceInDown'
					}
				})
				setTimeout(function(){
					location.replace('?root=entregadas');
				},5000);
			});
			return false;
		}
	});
	$(document).on('click', '.enviarMail', function(event) {
		event.preventDefault();
		var idCotizacion = $(this).attr("id");
		$("#idCotizaMails").val(idCotizacion);
		$.ajax({
			url: 'includes/consultas.php',
			type: 'POST',
			dataType: 'json',
			data: {id: idCotizacion},
			success:function(data){
				$("#CorreoCliente").val(data.mailCliente);
				$("#idCotiza_Mail").val(idCotizacion);
			}
		})
	});
	$(document).on('click', '.Buscar', function(event) {
		event.preventDefault();
		$("#buscar").removeClass('d-none');
	});
	$(document).on('click', '.changeStatus', function(event) {
		event.preventDefault();
		var idCotizacion = $(this).attr('id');
		$(".changeStatus").attr('disabled', true);
		$.ajax({
			url: 'includes/consultas.php',
			type: 'POST',
			dataType: 'html',
			data: {idCotizacion},
			success:function(data){
				if (data=='ok') {
					Swal.fire({
						type: 'success',
						title: 'Cotización alterada!',
						animation: false,
						customClass: {
							popup: 'animated bounceInDown'
						}
					})
					setTimeout(function(){
						location.replace('?root=generadas');
					},2000);
				}
			}
		})
	});



	//evento mostrar modal y jala datos de la factura invocando consultas .php
	$(document).on('click', '.facturaComputarizada', function(event) {		
		var idCotizacion = $(this).attr('id');
		$.ajax({
			url: 'includes/consultas.php', //id cotizacion mandamos aki para obtener datos
			type: 'POST',
			dataType: 'html',
			data: {llamarDatosFactura: idCotizacion},
			success:function(data){
				$("#modalFacturacion").modal();
				$(".detallesFactura").html(data);
			}
		})
		event.preventDefault();
	});
	$(document).on('click', '.debitoComputarizada', function(event) {		
		var idCotizacion = $(this).attr('id');
		$.ajax({
			url: 'includes/consultas.php', //id cotizacion mandamos aki para obtener datos
			type: 'POST',
			dataType: 'html',
			data: {llamarDatosDebito: idCotizacion},
			success:function(data){
				$("#modalDebito").modal();
				$(".detallesDebito").html(data);
			}
		})
		event.preventDefault();
	});

	
});


document.getElementById("porCantidad").addEventListener("keyup",function(e){
   // document.getElementById("texto").innerHTML=NumeroALetras(this.value);
	document.getElementById("cantidadLetras").value=NumeroALetras(this.value);
});
 
 
function Unidades(num){
 
  switch(num)
  {
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
 
function Decenas(num){
 
  decena = Math.floor(num/10);
  unidad = num - (decena * 10);
 
  switch(decena)
  {
    case 1:
      switch(unidad)
      {
        case 0: return "DIEZ";
        case 1: return "ONCE";
        case 2: return "DOCE";
        case 3: return "TRECE";
        case 4: return "CATORCE";
        case 5: return "QUINCE";
        default: return "DIECI" + Unidades(unidad);
      }
    case 2:
      switch(unidad)
      {
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
 
function DecenasY(strSin, numUnidades){
  if (numUnidades > 0)
    return strSin + " Y " + Unidades(numUnidades)
 
  return strSin;
}//DecenasY()
 
function Centenas(num){
 
  centenas = Math.floor(num / 100);
  decenas = num - (centenas * 100);
 
  switch(centenas)
  {
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
 
function Seccion(num, divisor, strSingular, strPlural){
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
 
function Miles(num){
  divisor = 1000;
  cientos = Math.floor(num / divisor)
  resto = num - (cientos * divisor)
 
  strMiles = Seccion(num, divisor, "MIL", "MIL");
  strCentenas = Centenas(resto);
 
  if(strMiles == "")
    return strCentenas;
 
  return strMiles + " " + strCentenas;
 
  //return Seccion(num, divisor, "UN MIL", "MIL") + " " + Centenas(resto);
}//Miles()
 
function Millones(num){
  divisor = 1000000;
  cientos = Math.floor(num / divisor)
  resto = num - (cientos * divisor)
 
  strMillones = Seccion(num, divisor, "UN MILLON", "MILLONES");
  strMiles = Miles(resto);
 
  if(strMillones == "")
    return strMiles;
 
  return strMillones + " " + strMiles;
 
  //return Seccion(num, divisor, "UN MILLON", "MILLONES") + " " + Miles(resto);
}//Millones()
 
function NumeroALetras(num,centavos){
  var data = {
    numero: num,
    enteros: Math.floor(num),
    centavos: (((Math.round(num * 100)) - (Math.floor(num) * 100))),
    letrasCentavos: "",
  };
  if(centavos == undefined || centavos==false) {
    data.letrasMonedaPlural="";
    data.letrasMonedaSingular="";
  }else{
    data.letrasMonedaPlural="CENTAVOS";
    data.letrasMonedaSingular="CENTAVO";
  }
 
  if (data.centavos > 0){
    //data.letrasCentavos = "CON " + NumeroALetras(data.centavos,true);
	data.letrasCentavos = data.centavos+"/100";}else{data.letrasCentavos = "00/100";}
 
  if(data.enteros == 0)
    return "CERO " + data.letrasMonedaPlural + " " + data.letrasCentavos;
  if (data.enteros == 1)
    return Millones(data.enteros) + " " + data.letrasMonedaSingular + " " + data.letrasCentavos;
  else
    return Millones(data.enteros) + " " + data.letrasMonedaPlural + " " + data.letrasCentavos;
}