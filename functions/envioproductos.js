$(document).ready(function() {
	$("#selectProducto").select2();  
  $("#forenvios").addClass('d-none');
  $("#claveOculta").val('');
  $("#Observaciones").val('');

  $(document).on('click', '.Buscar', function(event) {
    event.preventDefault();
    $("#buscar").removeClass('d-none');
  });  
	$(document).on('click', '#btn-enviarProducto', function(event) {
    $(".btn-CancelarEnvio").removeClass('d-none');//SE MUESTRA EL BOTON CANCELAR
    $("#btn-enviarProducto").addClass('d-none');//SE OCULTA EL BOTON ENVIAR
    $(".cargandoProductos").removeClass("d-none");
    $(".tablaEnvioStock").addClass("d-none");
    $("#selectProducto").val(0);
		var Ciudad  = $('#idCiudadUser').val();
		var idUser  = $('#idUserOk').val();
		$.ajax({
			url: 'includes/consultas.php',
			type: 'POST',
			dataType: 'html',
			data: {ConsultaProductoStock:Ciudad},
			success: function(data) {
		    $(".cargandoProductos").addClass("d-none");
				$('#selectProducto').html(data);
				$("#forenvios").removeClass("d-none");
      }
		});
		event.preventDefault();		
	});
	$("#selectProducto").change(function(event) {
		$("#selectProducto option:selected").each(function(index, el) {
	    var Ciudad   = $('#idCiudadUser').val();
      $("#Sucursal").val(0);
      $(".cargandoSucursales").removeClass("d-none");
			$.ajax({
				url: 'includes/consultas.php',
				type: 'POST',
				dataType: 'html',
				data: {BuscaSucursal:Ciudad},
				success: function(data) { 
					$("#Sucursal").html(data);
					$(".cargandoSucursales").addClass("d-none");
					$("#Sucursales").removeClass('d-none');          
				}
			});
		});
	});
	$("#Sucursal").change(function(event){
    $("#Sucursal option:selected").each(function(index, el){
      $("#inpStock").val(1);
      $(".divStock").removeClass("d-none");
      $("#inpStock").focus();
    });
	});
  $(document).on('click', '.btn-CancelarEnvio', function(event) {
    $(".btn-CancelarEnvio").addClass('d-none');
    $(".tablaEnvioStock").removeClass('d-none');
    $("#btn-enviarProducto").removeClass('d-none');
    $("#forenvios").addClass('d-none');
    event.preventDefault();
  });
	$(document).on('click', '#sendStock', function(event) {
    $(".btn-CancelarEnvio").addClass('d-none');
		var cantidad 	  = $("#inpStock").val();
    var cantidad    = parseInt(cantidad);
    var sucursal 	  = $("#idCiudadUser").val();
    var idProducto  = $("#selectProducto").val(); 
    if (idProducto==null) {
      $(".noSelectProducto").removeClass('d-none');
      setTimeout(function(){
        $(".noSelectProducto").addClass('d-none');
      },2500);
      return false;
    }
    if (sucursal    == "Cochabamba") {
      sucursal      = "StockCB";
    } else if(sucursal == "Santa Cruz") {
      sucursal      = "StockSC";
    } else if(sucursal == "La Paz") {          
      sucursal      = "StockLP";
    } else { sucursal = "StockTJ"; }
    $.ajax({  
    	url: "includes/consultas.php",
    	type: 'POST',
    	dataType: 'html',
    	data: {comparaStock: cantidad, sucursal:sucursal, idProducto:idProducto},
    	success:function(data){
        if (data<cantidad){
          $("#solicitudStock").modal();
          setTimeout(function(){
            $("#solicitudStock").modal('hide');
          },2500);
        }else{
          $("#addMasProductos").modal({backdrop: 'static', keyboard: false});
        }
      }
    });
	  event.preventDefault();
	});
  $(document).on('click', '.btnNo', function(event) {
    $("#addMasProductos").modal("hide");
    var clave          = $("#ClaveGeneradaAleatoria").val();
    var idProducto     = $("#selectProducto").val();
    var cantidad       = $("#inpStock").val();
    var idUser         = $("#idUserOk").val();
    var sucursal       = $("#idCiudadUser").val();
    var destino        = $("#Sucursal").val();
    var observaciones  = $("#Observaciones").val();
    if (destino        == "Santa") {
      destino          = "Santa Cruz";
    }else if (destino  == "Cochabamba") {
      destino          = "Cochabamba";
    }else if (destino  == "Tarija") {
      destino          = "Tarija";
    } else if (destino  == "La") {
      destino          = "La Paz";
    }
    $.ajax({
      url: 'do.php',
      type: 'POST',
      dataType: 'html',
      data: {action: 'guardarEnvioStock',clave:clave,idProducto:idProducto,cantidad:cantidad,idUser:idUser,sucursal:sucursal,destino:destino,observaciones:observaciones},
      success:function(result){
        $(".tablaStockTemp").removeClass('d-none');
        $("#tablaStockTemp").html(result);
      }
    })
    setTimeout(function(){
      $("#addComentarios").modal({backdrop:'static', keyboard: false});
    },500);
    event.preventDefault();
  });
  $(document).on('click', '.btnSi', function(event) {
    $("#addMasProductos").modal("hide");
    var clave 			   = $("#ClaveGeneradaAleatoria").val();
  	var idProducto 	   = $("#selectProducto").val();
  	var cantidad		   = $("#inpStock").val();
  	var idUser 			   = $("#idUserOk").val();
  	var sucursal 		   = $("#idCiudadUser").val();
  	var destino 		   = $("#Sucursal").val();
  	var observaciones  = $("#Observaciones").val();
    if (destino        == "Santa") {
      destino          = "Santa Cruz";
    }else if (destino  == "Cochabamba") {
      destino          = "Cochabamba";
    }else if (destino  == "Tarija") {
      destino          = "Tarija";
    } else if (destino  == "La") {
      destino          = "La Paz";
    }
  	$.ajax({
  		url: 'do.php',
  		type: 'POST',
  		dataType: 'html',
  		data: {action: 'guardarEnvioStock',clave:clave,idProducto:idProducto,cantidad:cantidad,idUser:idUser,sucursal:sucursal,destino:destino,observaciones:observaciones},
  	})
  	.done(function(data) {
  		$(".tablaStockTemp").removeClass('d-none');
      $("#tablaStockTemp").html(data);
      $.ajax({
      url: 'includes/consultas.php',
      type: 'POST',
      dataType: 'html',
      data: {ConsultaProductoStock:sucursal},
      success: function(data) {
        $('#selectProducto').html(data);
      }
    });
      $("#Sucursal").attr('disabled', true);
    	//$("#Sucursales").addClass('d-none');
    	$("#inpStock").val(1);
  	})
    event.preventDefault();
  });
  $(document).on('click', '.btn-Si', function(event) {
    $(".pregunta").addClass('d-none');
    $(".observaciones").removeClass('d-none')
    event.preventDefault();
  });
  $(document).on('click', '.btnCompletar', function(event) {
  	var clave      = $("#ClaveGeneradaAleatoria").val();
  	var observaciones = $("#Observaciones").val();
    if (observaciones=='') {
      $('.btnCompletar').after('<div class="text-center text-danger mt-3 h3 noComentario">INGRESE UNA OBSERVACION O COMENTARIO</div>');
      setTimeout(function(){
        $('.noComentario').remove()
      },2500);
      return false;
    }
    $("#addComentarios").modal("hide");
    $("#forenvios").addClass("d-none");
    $("#Sucursales").addClass("d-none");
    $(".divStock").addClass("d-none");
    $(".tablaStockTemp").removeClass('d-none');
  	$.ajax({
  		url: 'do.php',
  		type: 'POST',
  		dataType: 'html',
  		data: {action: 'agregarObservacionEnvioStock',clave:clave,observaciones:observaciones},
  	})
  	.done(function(data) {
	    $("#tablaStockTemp").html(data);
	    setTimeout(function(){
        location.reload();
	    },500);
  	});
  	event.preventDefault();
  });
  $(document).on('click', '.btn-No', function(event) {
    $("#addComentarios").modal('hide');
    var clave = $("#ClaveGeneradaAleatoria").val();
    $.ajax({
      url: 'do.php',
      type: 'POST',
      dataType: 'html',
      data: {action: 'descontarStockEnviado', clave:clave},
    })
    .done(function(data) {
      $(".respuesta").html(data);
    })
    setTimeout(function(){
      location.reload();
    },500);    
    event.preventDefault();
  });
  function mostrarEnviosStock(){
    var ciudad  = $("#idCiudadUser").val();
    var inicio  = $("#fechaInicio").val();
    var fin     = $("#fechaFin").val();
    $.ajax({
      url: 'do.php',
      type: 'POST',
      dataType: 'html',
      data: {action: 'mostrarTablaEnvios',ciudad:ciudad,inicio:inicio,fin:fin},
    })
    .done(function(data) {
      $("#tablaEnvios").html(data);
    });
  }
  //mostrarEnviosStock();
  $(document).on('click', '.cancelarEnvio', function(event) {
    var idEnvioStock  = $(this).attr("id");
    $.ajax({
      url: 'includes/consultas.php',
      type: 'POST',
      dataType: 'html',
      data: {ConsultaClaveTemporal: idEnvioStock},
      success:function(respuesta){
        $("#claveOculta").val(respuesta);
      }
    })
    const swalWithBootstrapButtons = Swal.mixin({
      customClass: {
        confirmButton: 'btn btn-success',
        cancelButton: 'btn btn-danger mx-2'
      },
      buttonsStyling: false
    })
    swalWithBootstrapButtons.fire({
      title: 'Estás seguro?',
      html: "Si continuas, el envio ser&aacute; cancelado y no podrás deshacer los cambios.",
      type: 'warning',
      showCancelButton: true,
      confirmButtonText: 'Sí, Cancelar envio!',
      cancelButtonText: 'No, cancelar!',
      reverseButtons: true
    }).then((result) => {
      if (result.value) {
        var estaClave = $("#claveOculta").val();
        $.ajax({
        url: 'do.php',
        type: 'POST',
        dataType: 'html',
        data: "action=cancelarEnvioStock&id="+idEnvioStock+"&clave="+estaClave,
      })
      .done(function(data) {
        $("#tablaStockTemp").html(data);
      })
      return false;
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
  $(document).on('click', '.cancelarTodoEnvio', function(event) {
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
      html: "Si continuas, TODO el proceso de envio sera cancelado y el stock volvera a su respectivo lugar de origen.<br>No habra vuelta atras.",
      type: 'warning',
      showCancelButton: true,
      confirmButtonText: 'Sí, Cancelar proceso!',
      cancelButtonText: 'No, cancelar!',
      reverseButtons: true
    }).then((result) => {
      if (result.value) {
        $.ajax({
        url: 'do.php',
        type: 'POST',
        dataType: 'html',
        data: "action=cancelarProcesoStock&idEnvio="+idEnvio,
      })
      .done(function(data) {
        $(".respuesta").html(data);
        setTimeout(function(){
          location.reload();
        },2500);
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