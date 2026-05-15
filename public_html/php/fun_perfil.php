<script type="text/javascript">
	$("#updateMyProfile").submit(function() {
		var Nombres 	=	$("#NameProfile").val();
		var Apellidos	=	$("#LastNameProfile").val();
		var Telefono 	=	$("#PhoneProfile").val();
		var Correo 		=	$("#MailProfile").val();

		if (Nombres=='') {
			$(".noName").removeClass('d-none');
			setTimeout(function(){
				$(".noName").addClass('d-none');
			},2000);
			return false;
		}else if (Apellidos=='') {
			$(".noLastName").removeClass('d-none');
			setTimeout(function(){
				$(".noLastName").addClass('d-none');
			},2000);
			return false;
		}else if (Telefono=='') {
			$(".noPhone").removeClass('d-none');
			setTimeout(function(){
				$(".noPhone").addClass('d-none');
			},2000);
			return false;
		}else if (Correo=='') {
			$(".noMail").removeClass('d-none');
			setTimeout(function(){
				$(".noMail").addClass('d-none');
			},2000);
			return false;
		}else{
			$(".editPerfil").removeClass('d-none');
			$(".ediT").attr('disabled', true);
			$.ajax({
				url: 'do.php',
				type: 'POST',
				dataType: 'html',
				data: $(updateMyProfile).serialize(),
			})
			.done(function(data) {
				$(".editPerfil").addClass('d-none');
				$(".ediT").attr('disabled', false);
				$(".respuesta").html(data);
			})
			return false;
		}
	});

	$("#changePasswordProfile").submit(function() {
		var Pass_1 	=	$("#Pswd_1").val();
		var Pass_2 	=	$("#Pswd_2").val();
		
		if (Pass_1=='') {
			$("#Pswd_1").focus();
			$("#Pswd_1").addClass('is-invalid');
			$(".notPswd1").removeClass('d-none');
			setTimeout(function(){
				$("#Pswd_1").removeClass('is-invalid');
				$(".notPswd1").addClass('d-none');
			},2500);
		}else if (Pass_2=='') {
			$("#Pswd_2").focus();
			$("#Pswd_2").addClass('is-invalid');
			$(".notPswd2").removeClass('d-none');
			setTimeout(function(){
				$("#Pswd_2").removeClass('is-invalid');
				$(".notPswd2").addClass('d-none');
			},2500);
		}else if (Pass_2!=Pass_1) {
			$("#Pswd_2").focus();
			$(".notMatchPswd").removeClass('d-none');
			setTimeout(function(){
				$(".notMatchPswd").addClass('d-none');
			},2500);
		}else{
			$(".changePswd").removeClass('d-none');
			$(".pswd").attr('disabled', true);
			$.ajax({
				url: 'do.php',
				type: 'POST',
				dataType: 'html',
				data: $(changePasswordProfile).serialize(),
			})
			.done(function(data) {
				$(".changePswd").addClass('d-none');
				$(".pswd").attr('disabled', false);
				$(".respuesta").html(data);
			})
		}
		return false;
	});
</script>