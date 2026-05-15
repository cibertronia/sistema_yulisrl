$(document).ready(function() {
	actualizarCabecerasFechas();

	const rango = JSON.parse(document.getElementById('page-container').getAttribute('data-rango'));
	const userCiudad = $('#page-container').data('usercity');
	const sucursales = $('#page-container').data('sucursales');
	const miSucursal = sucursales.find(sucursal => sucursal.Sucursal === userCiudad);
	const columnsToHide = filterColumsSucursal(miSucursal, rango);	

	// Variables para el manejo de moneda
	let precioDolar = parseFloat($('#page-container').data('preciodolar'));
	let isUSD = true; // Estado inicial en USD
	let originalPrices = {}; // Objeto para almacenar los precios originales en USD
	let monedaActual = 'USD';
	$('.moneda').text(`(${monedaActual})`);

	
	// Si el usuario es admin (rango 2), mostrar botones de exportación
	const domTableProductos = (rango==2)?'<"row"<"col-sm-5"B><"col-sm-7"f>>rt<"row"<"col-sm-5"i><"col-sm-7"p>>':'<"row"<"col-sm-5"><"col-sm-7"f>>rt<"row"<"col-sm-5"i><"col-sm-7"p>>';	

	//OCULTA LA TABLA DE PRODUCTOS Y MUESTRA EL FORMULARIO DE AGREGAR NUEVO PRODUCTO
	$(document).on('click', '.AddNewProductoBTN', function(event) {
		event.preventDefault();
		$(".tableProductos").addClass('d-none');
		$(".formNewProducto").removeClass('d-none');
	});

	//OCULTA EL FORMULARIO DE NUEVO PRODUCTOS Y REGRESA A LA TABLA PRODUCTOS
	$(document).on('click', '.cancelarRegNewProducto', function(event) {
		event.preventDefault();
		$(".tableProductos").removeClass('d-none');
		$(".formNewProducto").addClass('d-none');
	});

	//LLAMA LOS DATOS DEL PRODUCTO A EDITAR
	$(document).on('click', '.editProdExistente', function(event) {
		event.preventDefault();
		//vaciar los campos e imagen que esten dentro de #editProducto
		$("#editProducto input").val('');
		$("#editProducto textarea").val('');
		$("#editProducto img").remove();

		var idProducto 	=	$(this).attr("id");
		$.ajax({
			// 
			url: 'includes/api/products.php',
			type: 'GET',
			dataType: 'json',
			data: {id: idProducto},
			success:function(data){
				const producto = data['data'];				
				//cargar los datos del producto en los inputs de #editProducto
				$("#editProducto input[name='id']").val(producto.idProducto);
				$("#editProducto input[name='_method']").val('PUT');
				$("#editProducto input[name='Producto']").val(producto.Producto);
				$("#editProducto input[name='Marca']").val(producto.Marca);
				$("#editProducto input[name='Modelo']").val(producto.Modelo);
				$("#editProducto input[name='image_file']").val(producto.Imagen);
				$("#editProducto textarea[name='Descripcion']").val(producto.Descripcion);
				// cargar imagen del producto
				$("#imgx_").after("<img src='Productos/"+producto.Imagen+"' alt='Imagen' height='150px'>");

				loadDataSucursales(producto);

				$(".editProducto").removeClass('d-none');
				$(".tableProductos").addClass('d-none');
			}
		})
	});

	//OCULTA EL FORMULARIO DE EDITAR PRODUCTO Y MUESTRA LA TABLA PRODUCTOS
	$(document).on('click', '.cancelarEditProducto', function(event) {
		event.preventDefault();
		$(".editProducto").addClass('d-none');
		$(".tableProductos").removeClass('d-none');
	});

	//BUSCAR PRODUCTO POR MODELO
	$(document).on('click', '.buscarProductoBtn', function(event) {
		event.preventDefault();

		var Modelo 	=	$("#byModelo").val();

		if (Modelo=='') {
			$(".noModeloProd").removeClass('d-none');
			setTimeout(function(){
				$(".noModeloProd").addClass('d-none');
			},2000); return false;
		}else{
			$.ajax({
				url: 'includes/getFindProductos.php',
				type: 'POST',
				dataType: 'html',
				data: $("#findProducto").serialize(),
			})
			.done(function(data) {
				$("#respuestaFindProducto").html(data);
			})
			return false;
		}		
	});

	$(document).on('click', '.borrarProducto', function(event) {
		event.preventDefault();
		var idProducto = $(this).attr("id");

		const swalWithBootstrapButtons = Swal.mixin({
		  customClass: {
		    confirmButton: 'btn btn-success',
		    cancelButton: 'btn btn-danger'
		  },
		  buttonsStyling: false
		})

		swalWithBootstrapButtons.fire({
		  title: 'Estás seguro?',
		  html: "Si el producto que deseas borrar ha sido utilizado anteriormente en alguna cotización, el sistema generará un error en las cotizaciones que incluian este producto, ya que este, buscará el producto guardado anteriormente y al no existir, habrá conflicto.<br>Si continuas, no podrás deshacer los cambios.",
		  type: 'warning',
		  showCancelButton: true,
		  confirmButtonText: 'Sí, borrar!',
		  cancelButtonText: 'No, cancelar!',
		  reverseButtons: true
		}).then((result) => {
		  if (result.value) {
		  	$.ajax({
				url: 'do.php',
				type: 'POST',
				dataType: 'html',
				data: "action=BorrarProductoLista&id="+idProducto,
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
		      'El Producto ya no será borrado',
		      'error'
		    )
		  }
		})
	});

	$(document).on('click', '.DownProductos', function(event) {
		event.preventDefault();
		$.post('do.php', "action=DescargarTablaProductos", function(data) {
			/*optional stuff to do after success */
		});
	});

	$(document).on('click', '.BuscarSubidaSistema', function (event) {
		event.preventDefault();
		$(".divBuscarSubidaSistema").addClass('d-none');
		$(".divFecha").removeClass('d-none');
		
	});

	var tableProductos = $('#table-products').DataTable({
		dom: domTableProductos,
		buttons: [
			{ extend: 'copy', className: 'btn-sm' },
			{ extend: 'csv', className: 'btn-sm' },
			{ extend: 'excel', className: 'btn-sm' },
			{ extend: 'pdf', className: 'btn-sm' },
			{ extend: 'print', className: 'btn-sm' }
		],
		responsive: true,
		ajax: {
			url: 'includes/api/products.php',
			type: 'GET',
			data: function (d) {
				d.action = 'getWithMovimientos';
				d.fInicial = $('#inicio').val();
				d.fFinal = $('#fin').val();
			},
			dataSrc: function (json) {
				// Guardar los precios originales en USD
				json.data.forEach(function(row) {
					originalPrices[row.idProducto] = {
						PrecioCB: row.PrecioCB,
						PrecioLP: row.PrecioLP,
						PrecioSC: row.PrecioSC,
						PrecioST: row.PrecioST,
						PrecioTJ: row.PrecioTJ
					};
				});
				return json.data;
			}
		},
		processing: true,

		//agregar clases a las cabeceras columnas
		columnDefs: [
			{ className: 'text-center bg-cb', targets: [3, 14, 15] },
			{ className: 'text-center bg-lp', targets: [5, 16, 17] },
			{ className: 'text-center bg-sc', targets: [7, 18, 19] },
			{ className: 'text-center bg-st', targets: [9, 20, 21] },
			{ className: 'text-center bg-fr', targets: [11, 22, 23] },
			{ className: 'text-center bg-total', targets: [13] },
			{ className: 'text-center', targets: [1, 2, 4, 6, 8, 10, 12] },
			{ visible: false, targets: columnsToHide },
		],

		ordering: false,

		columns: [
			{ data: 'Producto' },
			{ data: 'Marca' },
			{ data: 'Modelo' },
			{ data: 'StockCB' },
			{ 
				data: 'PrecioCB', 
				render: function(data, type){
					return formatPrice(data, type);
				}
			},
			{ data: 'StockLP' },
			{ 
				data: 'PrecioLP',
				render: function(data, type){
					return formatPrice(data, type);
				}
			},
			{ data: 'StockSC' },
			{ 
				data: 'PrecioSC',
				render: function(data, type){
					return formatPrice(data, type);
				}
			},
			{ data: 'StockST' },
			{ 
				data: 'PrecioST', 
				render: function(data, type){
					return formatPrice(data, type);
				}
			},
			{ data: 'StockTJ' },
			{ 
				data: 'PrecioTJ',
				render: function(data, type){
					return formatPrice(data, type);
				}
			},
			{ 
				data: 'StockTotal', 
				render: function(data, type, row){
					let color = 'default';
					if (data < 5) {
						color = 'danger';
					}
					return `<button class='btn btn-xs btn-${ color } form-control btnadd1' data-id='${row.idProducto}'>${ data }</button>`;
				}
			},
			{ data: 'extractCB' },
			{ data: 'receiveCB' },
			{ data: 'extractLP' },
			{ data: 'receiveLP' },
			{ data: 'extractSC' },
			{ data: 'receiveSC' },			
			{ data: 'extractST' },
			{ data: 'receiveST' },
			{ data: 'extractTJ' },
			{ data: 'receiveTJ' },			
			{
				data: null,
				render: function(data, type, row) {
					let html = `<img height="50" src="Productos/${row.Imagen}" alt="${row.Producto}">
							<button class="btn btn-primary editProdExistente" id="${row.idProducto}">Editar</button>`;
					if (window.userRango == 2) {
						html += `<button class="btn btn-danger borrarProducto" id="${row.idProducto}">Borrar</button>`;
					}

					return html;					
				}
			}
		]

	});
	
	//evento al enviar el formulario product-edit-form
	$(document).on('submit', '#product-edit-form', function(event) {
		event.preventDefault();

		// crear data del formulario para enviar por AJAX
		var formData = new FormData();
		formData.append('id', $("#editProducto input[name='id']").val());
		formData.append('_method', $("#editProducto input[name='_method']").val());
		formData.append('Producto', $("#editProducto input[name='Producto']").val());
		formData.append('Marca', $("#editProducto input[name='Marca']").val());
		formData.append('Modelo', $("#editProducto input[name='Modelo']").val());
		formData.append('Descripcion', $("#editProducto textarea[name='Descripcion']").val());
		formData.append('image_file', $("#editProducto input[name='image_file']").val());
		// agregar la imagen del producto si existe
		if ($("#editProducto input[name='imagen']")[0].files.length > 0){
			formData.append('imagen', $("#editProducto input[name='imagen']")[0].files[0]);
		}
		
		// const sucursales = JSON.parse(document.getElementById('page-container').getAttribute('data-sucursales'));

		sucursales.forEach(function(sucursal) {
			if ($("#ProdStock_" + sucursal.idSucursal).val()) {
				formData.append('Precio' + sucursal.iniciales, $("#ProdPrecio_" + sucursal.idSucursal).val());
			}
			if ($("#ProdPrecio_" + sucursal.idSucursal).val()) {
				formData.append('Observaciones' + sucursal.iniciales, $("#ProdObserv_" + sucursal.idSucursal).val());
			}
		});

		$.ajax({
			url: 'includes/api/products.php',
			type: 'POST',
			data: formData,
			contentType: false,
			processData: false,
			dataType: 'json',
			success: function(response) {			
				if (response.success) {
					setTimeout(function() {
						location.reload();
					}, 2000);

					Swal.fire({
						title: 'Éxito',
						text: response.message,
						type: "success"
					}).then(() => {
						location.reload();
					});
				} else {
					Swal.fire({
						title: 'Error',
						text: response.message,
						type: 'error'
					});
				}
			},
			error: function(xhr, status, error) {
				console.error('Error:', error);
				Swal.fire({
					title: 'Error',
					text: 'Ocurrió un error al procesar la solicitud.',
					type: 'error'
				});
			}
		});
	});

	//evento al enviar el formulario buscarRangoFechas
	$(document).on('submit', '#buscarRangoFechas', function(event) {
		event.preventDefault();
		var fInicial = $("#inicio").val();
		var fFinal = $('#fin').val();

		if (fInicial === '' || fFinal === '') {
			Swal.fire({
				type: 'warning',
				title: 'Por favor, selecciona un rango de fechas válido.',
				showConfirmButton: true
			});
			return false;
		}

		// Actualizar la tabla con el nuevo rango de fechas
		// tableProductos.ajax.url('includes/api/products.php').parameter.load();
		tableProductos.ajax.url('includes/api/products.php').load();
	});

	//evento al hacer click en el boton de download-excel
	$(document).on('click', '#download-excel', function(event) {
		event.preventDefault();
		var fInicial = $("#inicio").val();
		var fFinal = $('#fin').val();

		if (fInicial === '' || fFinal === '') {
			Swal.fire({
				type: 'warning',
				title: 'Por favor, selecciona un rango de fechas válido.',
				showConfirmButton: true
			});
			return false;
		}

		window.location.href = `Reportes/reporteListaProductos.php?reporteListaProductos=reporteListaProductos&fechaInicio=${fInicial}&fechafin=${fFinal}&order=Producto`;
	});

	//agregar fechas en cabecera de la tabla mediante la clase .inicio y .fin
	function actualizarCabecerasFechas(){
		var inicio = $("#inicio").val();
		var fin = $("#fin").val();
		
		$(".inicio").text(inicio);
		$(".fin").text(fin);
		$('.dtr-details .inicio').text(inicio);
		$('.dtr-details .fin').text(fin);
	}

	tableProductos.on('draw', function () {
		setTimeout(actualizarCabecerasFechas, 50);

		monedaActual = isUSD ? 'USD' : 'Bs';
		// // Actualizar las cabeceras de precios
		$('.moneda').text(`(${monedaActual})`);
	});

	tableProductos.on('responsive-display', function () {
		setTimeout(actualizarCabecerasFechas, 50);

		monedaActual = isUSD ? 'USD' : 'Bs';
		// // Actualizar las cabeceras de precios
		$('.moneda').text(`(${monedaActual})`);
	});

	function addc() {
		var p1 = $("#fecha1").val();
		var p2 = $("#cantidad1").val();
		var p3 = $("#notas1").val();
		var p4 = $("#id").val();
		var p5 = $("#sucursal1").val();;
		var p6 = $("#idusuario").val();
		if (p1 == "") {
			$("#lblmsg1").text("Ingrese una fecha actual válida.");
			$("#fecha1").focus();
		}
		else if (isNaN(p2) || !isFinite(p2) || (p2 == 0)) {
			$("#lblmsg1").text("Ingrese un monto válido.");
			$("#cantidad1").focus();
		}
		else if (p3 == "") {
			$("#lblmsg1").text("Ingrese el detalle.");
			$("#notas1").focus();
		}
		else {
			let sucursalName = $("#sucursal1 option:selected").text();
			Swal.fire({
				title: '¿Está seguro de registrar el movimiento?',
				html: "Fecha: " + p1 + "\nSucursal: " + sucursalName + "<br>\nCantidad: <b>" + p2 + "</b>\nDetalles: " + p3,
				type: 'warning',
				showCancelButton: true,
				confirmButtonColor: '#3085d6',
				cancelButtonColor: '#d33',
				confirmButtonText: 'Sí, registrar movimiento',
				cancelButtonText: 'Cancelar'
			}).then((result) => {                        
				if (result.value) {
					//Creamos la data para enviar
					let data = new FormData();
					data.append('idproducto', p4);
					data.append('fecha', p1);
					data.append('idsucursal', p5);
					data.append('idusuario', p6);
					data.append('cantidad', p2);
					data.append('detalles', p3);
					// Enviamos la data al servidor
					$.ajax({
						url: 'includes/api/compras.php',
						type: 'POST',
						data: data,
						processData: false,
						contentType: false,
						dataType: 'json',
						success: function (response) {
							if (response.success) {
								setTimeout(function() {
									location.reload();
								}, 2000);                                   

								Swal.fire({
									title: 'Movimiento registrado correctamente',
									type: 'success',
									confirmButtonText: 'Aceptar',
									allowOutsideClick: false
								}).then((result) => {
									if (result.value) {
										$("#add11").modal('hide');
										$("#lblmsg1").text("");
										$("#fecha1").val("");
										$("#cantidad1").val("");
										$("#notas1").val("");
										location.reload();
									}
								});
								
							} else {
								$("#lblmsg1").text("Error al registrar el movimiento: " + response.error);
							}
						},
						error: function (xhr, status, error) {
							Swal.fire({
								title: 'Error al registrar el movimiento',
								text: 'Por favor, intente nuevamente.',
								type: 'error',
								confirmButtonText: 'Aceptar'
							});
						}        
					});
				}
			})

		}
	}

	$(document).on("click", "#btadd11", addc);

	$(document).on("click", "button.btnadd1" , function($this) {
		let id = $(this).data('id');

		// eliminar la tabla si existe
		if ($.fn.DataTable.isDataTable('#tblHistorial')) {
			$('#tblHistorial').DataTable().clear().destroy();
		}

		let tablaHistorial = $('#tblHistorial').DataTable({
			dom: '<"row"<"col-sm-12"f>>rt<"row"<"col-sm-5"i><"col-sm-7"p>>',
			ajax: {
				url: 'includes/api/historyProducts.php',
				type: 'GET',
				data: function(d){ 
					d.idProducto = id 
				},
				dataType: 'json'
			},
			// ordenar por fecha de emisión
			order: [[0, 'desc']],
			columns: [
				{ data: 'dateEmission' },
				{ data: 'vendedor' },
				{ data: 'cb' },
				{ data: 'lp' },
				{ data: 'sc' },
				{ data: 'st' },
				{ data: 'tj' },
				{ data: 'descripcion' },
			]
		});
		// Mostrar el modal de historial
		$("#add1").modal('show');
		
	});

	// Función para formatear precio según moneda actual
	function formatPrice(price, type) {
		if (type === 'display') {
			let valor = parseFloat(price) || 0;
			if (!isUSD) {
				valor = valor * precioDolar;
				// Formatear por miles la cantidad en BS : 1.000 sin decimales
				return valor.toLocaleString('es-BO', { minimumFractionDigits: 0, maximumFractionDigits: 0 });
			} else {
				return valor.toFixed(2);
			}
		}
		return price;
	}

	// Evento para cambiar moneda
    $(document).on('click', '#toggle-currency', function(event) {
        event.preventDefault();
        
        // Cambiar estado de moneda
        isUSD = !isUSD;
        
        // Actualizar texto del botón
        $(this).html(`<i class="fa fa-exchange-alt"></i> ${isUSD ? 'USD/Bs' : 'Bs/USD'}`);
        
        // Redibujar la tabla para aplicar el nuevo formato
        tableProductos.rows().invalidate().draw(false);
        
        // Mostrar mensaje
        Swal.fire({
            title: 'Moneda cambiada',
            text: `Precios mostrados en ${isUSD ? 'USD' : 'Bolivianos'}`,
            type: 'info',
            timer: 1500,
            showConfirmButton: false
        });
    });

});

function loadDataSucursales(producto) { 
	$.ajax({
		url: 'includes/api/sucursales.php',
		type: 'GET',
		dataType: 'json',
		success: function(data) {
			const sucursales = data['data'];
			sucursales.forEach(function(item) {		

				let stock = producto['Stock' + item.iniciales] || 0;		
				let precio = producto['Precio' + item.iniciales] || 0.00;
				let description = producto['Observaciones' + item.iniciales] || '';

				$("#ProdStock_" + item.idSucursal).val(stock);
				$("#ProdPrecio_" + item.idSucursal).val(precio);
				$("#ProdObserv_" + item.idSucursal).val(description);
			});
		}
	});
}

function filterColumsSucursal(sucursal, rango){
	// si es rango 2 (admin), no ocultar ninguna columna
	if (rango == 2) {
		return [];
	}
	// Mostrar colummas segun sucursal
	const iniciales = sucursal?.iniciales;

	// columnas por sucursal
	const columnsBySucursal = {
		'CB': [4],
		'LP': [6],
		'SC': [8],
		'ST': [10],
		'TJ': [12]
	};

	// Obtener las columnas que no estan relacionadas con la sucursal del usuario
	const columnsToHide = Object.keys(columnsBySucursal)
		.filter(key => key !== iniciales)
		.flatMap(key => columnsBySucursal[key]);
	
	return columnsToHide;

}