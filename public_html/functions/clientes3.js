$(document).ready(function () {
	//buscar cliente con mas cantidad de unidades comprar
	//mostrar luego
	$(document).on('click', '.botonBuscar', function (event) {

		let inicio = $("#inicio").val();
		let fin = $("#fin").val();
		let datosPOST =
			"inicio=" + inicio +
			"&fin=" + fin;

		console.log(datosPOST);
		$.ajax({
			url: "includes/ajax/getClienteCantidades.php",
			type: "POST",
			dataType: "json",
			data: datosPOST,
			success: function (data) {
				$(".respuesta").html(data);
			},
		});


	});
	$('#myModal').on('shown.bs.modal', function () {
		$('#myInput').trigger('focus')
	})
});

/*
función asíncrona para poder usar async y await cómodamente
*/
(async () => {
	let inicio = $("#inicio").val();
	let fin = $("#fin").val();
	let sucursal = $("#sucursalpost").val();
	if (sucursal != '') {
		if (sucursal == 'Cochabamba') { $("#Sucursal").val('1'); }
		if (sucursal == 'La Paz') { $("#Sucursal").val('2'); }
		if (sucursal == 'Santa Cruz') { $("#Sucursal").val('3'); }
		if (sucursal == 'Tarija') { $("#Sucursal").val('4'); }
	}

	let datosPOST =
		"inicio=" + inicio +
		"&fin=" + fin +
		"&sucursal=" + sucursal;
	console.log(datosPOST);
	$.ajax({
		url: "includes/ajax/getClienteHabitual.php",
		type: "POST",
		dataType: "json",
		data: datosPOST,
		success: function (data) {
			$(".respuesta").html(data);
			const respuesta = data;
			const $grafica = document.querySelector("#grafica");
			const etiquetas = respuesta.etiquetas;
			const datosVentas2020 = {
				label: "Clientes habituales que vienen cada vez sin importar la cantidad y el valor",
				// Pasar los datos igualmente desde PHP
				data: respuesta.datos, // <- Aquí estamos pasando el valor traído usando AJAX
				backgroundColor: [
					'rgba(255,99,132,0.5)',
					'rgba(54,162,235,0.5)',
					'rgba(255,206,86,0.5)',

					'rgba(82,231,72,0.5)',
					'rgba(239,60,206,0.5)',
					'rgba(60,239,222,0.5)',
					'rgba(237,250,11,0.5)',

					'rgba(75,192,192,0.5)',
					'rgba(153,102,255,0.5)',
					'rgba(255,159,64,0.5)'], // Color de fondo
				borderColor: [
					'rgba(255,99,132,1)',
					'rgba(54,162,235,1)',
					'rgba(255,206,86,1)',

					'rgba(82,231,72,1)',
					'rgba(239,60,206,1)',
					'rgba(60,239,222,1)',
					'rgba(237,250,11,1)',

					'rgba(75,192,192,1)',
					'rgba(153,102,255,1)',
					'rgba(255,159,64,1)'
				], // Color del borde
				borderWidth: 1.5, // Ancho del borde
			};
			new Chart($grafica, {
				type: 'doughnut', // Tipo de gráfica
				data: {
					labels: etiquetas,
					datasets: [
						datosVentas2020,
						// Aquí más datos...
					]
				},
				options: {
					scales: {
						yAxes: [{
							ticks: {
								beginAtZero: true
							}
						}],
					},
				}
			});
		},
	});

})();
