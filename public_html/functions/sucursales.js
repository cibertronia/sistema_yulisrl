$(document).ready(function() {
	$(document).on('click', '.AddNewSucursalBTN', function(event) {
		event.preventDefault();
		$(".AddNewScursal").removeClass('d-none');
		$(".editSucursal").addClass('d-none');
	});

	$(document).on('click', '.cancelarRegNewSucursal', function(event) {
		event.preventDefault();
		$(".AddNewScursal").addClass('d-none');
	});

	$(document).on('click', '.editSucursalExist', function(event) {
		event.preventDefault();
		var idSucursal = $(this).attr('id');

		$(".editSucursal").removeClass('d-none');
		$(".AddNewScursal").addClass('d-none');

		$.ajax({
			url: 'includes/getDataSucursal.php',
			type: 'POST',
			dataType: 'json',
			data: {id: idSucursal},
			success:function(data){
				$("#idSucursal_").val(data.idSucursal);
				$("#SucuNomnbre_").val(data.Sucursal);
			}
		})
		
	});

	$(document).on('click', '.cancelarEditSucu', function(event) {
		event.preventDefault();
		$(".editSucursal").addClass('d-none');
	});
});