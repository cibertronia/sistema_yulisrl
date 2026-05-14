<script type="text/javascript">
	$("#editUserList").submit(function() {
		var Name 	=	$("#Nombres").val();
		var LastName=	$("#Apellidos").val();
		var Telefono=	$("#Telefono").val();
		var Sucursal=	$("#Sucursal option:selected").val();
		var Correo  =	$("#Correo").val();
		var Sexo 	=	$("#Sexo option:selected").val();

		if (Name=='') {
			$("#Nombres").focus();
			$("#Nombres").addClass('is-invalid');
			setTimeout(function(){
				$("#Nombres").removeClass('is-invalid');
			},3000);
		}else if (LastName=='') {
			$("#Apellidos").focus();
			$("#Apellidos").addClass('is-invalid');
			setTimeout(function(){
				$("#Apellidos").removeClass('is-invalid');
			},3000);
		}else if (Telefono=='') {
			$("#Telefono").focus();
			$("#Telefono").addClass('is-invalid');
			setTimeout(function(){
				$("#Telefono").removeClass('is-invalid');
			},3000);
		}else if ((Telefono).length<9) {
			$("#Telefono").focus();
			$(".nimLength_").removeClass('d-none');
			setTimeout(function(){
				$(".nimLength_").addClass('d-none');
			},3000);
		}else if (Sucursal=='Seleccione Sucursal') {
			$(".emptySucursal").removeClass('d-none');
			setTimeout(function(){
				$(".emptySucursal").addClass('d-none');
			},3000);
		}else if (Correo=='') {
			$("#Correo").focus();
			$("#Correo").addClass('is-invalid');
			setTimeout(function(){
				$("#Correo").removeClass('is-invalid');
			},3000);
		}else if (Sexo=='Sexo') {
			$(".emptySexo").addClass('d-none');
			setTimeout(function(){
				$(".emptySexo").removeClass('d-none');
			},3000);
		}else{
			$.ajax({
				url: 'do.php',
				type: 'POST',
				dataType: 'html',
				data: $(editUserList).serialize(),
			})
			.done(function(data) {
				$(".respuesta").html(data);
			})
			return false;
		} return false;
	});

	$("#newUser").submit(function() {
		var Name 	=	$("#NewNombres").val();
		var LastName=	$("#NewApellidos").val();
		var Telefono=	$("#NewTelefono").val();
		var Sucursal=	$("#NewSucursal option:selected").val();
		var Correo  =	$("#NewCorreo").val();
		var Sexo 	=	$("#NewSexo option:selected").val();
		var Rango 	=	$("#NewRango option:selected").val();

		if (Name=='') {
			$("#NewNombres").focus();
			$("#NewNombres").addClass('is-invalid');
			setTimeout(function(){
				$("#NewNombres").removeClass('is-invalid');
			},3000);
		}else if (LastName=='') {
			$("#NewApellidos").focus();
			$("#NewApellidos").addClass('is-invalid');
			setTimeout(function(){
				$("#NewApellidos").removeClass('is-invalid');
			},3000);
		}else if (Telefono=='') {
			$("#NewTelefono").focus();
			$("#NewTelefono").addClass('is-invalid');
			setTimeout(function(){
				$("#NewTelefono").removeClass('is-invalid');
			},3000);
		}else if ((Telefono).length<9) {
			$("#NewTelefono").focus();
			$(".nimLength").removeClass('d-none');
			setTimeout(function(){
				$(".nimLength").addClass('d-none');
			},3000);
		}else if (Sucursal=='Seleccione Sucursal') {
			$(".emptyNewSucursal").removeClass('d-none');
			setTimeout(function(){
				$(".emptyNewSucursal").addClass('d-none');
			},3000);
		}else if (Correo=='') {
			$("#NewCorreo").focus();
			$("#NewCorreo").addClass('is-invalid');
			setTimeout(function(){
				$("#NewCorreo").removeClass('is-invalid');
			},3000);
		}else if (Sexo=='Sexo') {
			$(".emptyNewSexo").addClass('d-none');
			setTimeout(function(){
				$(".emptyNewSexo").removeClass('d-none');
			},3000);
		}else if (Rango=='Rango') {
			$(".emptyNewRango").addClass('d-none');
			setTimeout(function(){
				$(".emptyNewRango").removeClass('d-none');
			},3000);
		}else{
			$.ajax({
				url: 'do.php',
				type: 'POST',
				dataType: 'html',
				data: $(newUser).serialize(),
			})
			.done(function(data) {
				$(".respuesta").html(data);
			})
			return false;
		} return false;		
	});
</script>