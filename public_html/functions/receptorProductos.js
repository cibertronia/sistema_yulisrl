$(function() {
	$(document).on('click', '.Buscar', function(event) {
    event.preventDefault();
    $("#buscar").removeClass('d-none');
  });
  function mostrarRecibosStock(){
    var ciudad  = $("#idCiudadUser").val();
    var inicio  = $("#fechaInicio").val();
    var fin     = $("#fechaFin").val();
    $.ajax({
      url: 'do.php',
      type: 'POST',
      dataType: 'html',
      data: {action: 'mostrarTablaRecibos',ciudad:ciudad,inicio:inicio,fin:fin},
    })
    .done(function(data) {
      $("#tablaRecibos").html(data);
    });
  }
  //mostrarRecibosStock();
  $(document).on('click', '.recibirProducto', function(event) {
    var idEnvio = $(this).attr('id');
    const swalWithBootstrapButtons = Swal.mixin({
      customClass: {
        confirmButton: 'btn btn-success',
        cancelButton: 'btn btn-danger mx-2'
      },
      buttonsStyling: false
    })
    swalWithBootstrapButtons.fire({
      title: 'Estás seguro?',
      html: "Estás a punto de confirmar que el envi&oacute; ha sido recibido.",
      type: 'warning',
      showCancelButton: true,
      confirmButtonText: 'Sí, confirmar!',
      cancelButtonText: 'No, cancelar!',
      reverseButtons: true
    }).then((result) => {
      if (result.value) {
        $.ajax({
        url: 'do.php',
        type: 'POST',
        dataType: 'html',
        data: "action=confirmarEnvioStock&idEnvio="+idEnvio,
      })
      .done(function(data) {
        $(".respuesta").html(data);
      })
      return false;
      } else if (
        /* Read more about handling dismissals below */
        result.dismiss === Swal.DismissReason.cancel
      ) {
        swalWithBootstrapButtons.fire(
          'Cancelado',
          'El envio no será confirmado.',
          'error'
        )
      }
    })
    event.preventDefault();
  });
});