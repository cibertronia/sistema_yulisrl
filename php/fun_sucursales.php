<script type="text/javascript">
	$("#mySucursal").submit(function() {
		var Sucu = $("#SucuNomnbre").val();
		if (Sucu=='') {
			$(".emptySucuNombre").removeClass('d-none');
			setTimeout(function(){
				$(".emptySucuNombre").addClass('d-none');
			},2000);
		}else{
			$.ajax({
				url: 'do.php',
				type: 'POST',
				dataType: 'html',
				data: $(mySucursal).serialize(),
			})
			.done(function(data) {
				$(".respuesta").html(data);
			})
			return false;
		}
		return false;
	});

	$("#editMySucu").submit(function() {
		var Sucu = $("#SucuNomnbre_").val();
		if (Sucu=='') {
			$(".emptySucuNombre_").removeClass('d-none');
			setTimeout(function(){
				$(".emptySucuNombre_").addClass('d-none');
			},2000)
		}else{
			$.ajax({
				url: 'do.php',
				type: 'POST',
				dataType: 'html',
				data: $(editMySucu).serialize(),
			})
			.done(function(data) {
				$(".respuesta").html(data);
			})
			return false;
		}
		return false;
	});
</script>