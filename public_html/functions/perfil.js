$(document).ready(function() {
	$("#PhoneProfile").mask("0000-0000");
	$(document).on('click', '.editMyProfile', function(event) {
		event.preventDefault();
		$(".profileUsers").removeClass('d-none');
	});
	$(document).on('click', '.cancellProfile', function(event) {
		event.preventDefault();
		$(".profileUsers").addClass('d-none');
	});
});