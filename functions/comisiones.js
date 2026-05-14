$(document).ready(function() {
	$(document).on('click', '.Busqueda', function(event) {
		event.preventDefault();
		$("#busqueda").removeClass('d-none');
	});

	$(document).on('click', '.btnAnular1', function (event) {
		event.preventDefault();
		$(".tablaComisiones").addClass('d-none');
		$(".FormAnulation1").removeClass('d-none');
		
		var id = $(this).attr("id");
		// const sucursales = JSON.parse($('#page-container').data('sucursales'));
		const sucursales = JSON.parse(document.getElementById('page-container').getAttribute('data-sucursales'));

		let sucu = sucursales.find(item => item.idSucursal === id)?.Sucursal || 'Sucursal no encontrada';
		
		$.ajax({
			url: 'includes/getDataComision.php',
			type: 'POST',
			dataType: 'json',
			data: { id: id },
			success: function (data) {
				$("#Meta1").val(data.Meta1);
				$("#Comision1").val(data.Comision1);
				$("#Meta2").val(data.Meta2);
				$("#Comision2").val(data.Comision2);
				$("#qtyPersonal").val(data.personal_dividir);
				document.getElementById("sucursal").innerHTML = "EDITAR META Y COMISION ---> " + sucu.toUpperCase();
				$("#idTabla").val(id);
			}
		})
	});

	$(document).on('click', '.cancelar1', function (event) {
		event.preventDefault();
		$(".tablaComisiones").removeClass('d-none');
		$(".FormAnulation1").addClass('d-none');
	});

	$(document).on('click', '.btnAplicarCambios', function (event) {
		event.preventDefault();
		$(".btnAplicarCambios").attr('disabled', true);
		
		var id = $(this).attr("id");
		var Comision1 = $("#Comision1").val();
		var Meta1= $("#Meta1").val();
		var Comision2 = $("#Comision2").val();
		var Meta2= $("#Meta2").val();
		var qtyPersonal=$("#qtyPersonal").val();
		if (Comision1 > 9.9 || Comision1==null ) {
			setTimeout(function () {
				$(".emptyButton").removeClass('d-none');
				$(".btnAplicarCambios").attr('disabled', false);
			}, 3000);
		} 
		else if (Meta1 <0 || Meta1==null ) {
			setTimeout(function () {
				$(".emptyButton").removeClass('d-none');
				$(".btnAplicarCambios").attr('disabled', false);
			}, 3000);
		}
		else if (Comision2 > 9.9 || Comision2==null ) {
			setTimeout(function () {
				$(".emptyButton").removeClass('d-none');
				$(".btnAplicarCambios").attr('disabled', false);
			}, 3000);
		}
		else if (Meta2 <0 || Meta2==null ) {
			setTimeout(function () {
				$(".emptyButton").removeClass('d-none');
				$(".btnAplicarCambios").attr('disabled', false);
			}, 3000);
		}
		else if (qtyPersonal <=0 || qtyPersonal==null  ) {
			setTimeout(function () {
				$(".emptyButton").removeClass('d-none');
				$(".btnAplicarCambios").attr('disabled', false);
			}, 3000);
		}else {
		$.ajax({
			type: "POST",
			url: 'Paginas/comisionesActualizar.php',
			data: $("#editComision").serialize(),
			success: function (data) {
				$('#resp').html(data);
			
			}

		})}return false;
	});













});