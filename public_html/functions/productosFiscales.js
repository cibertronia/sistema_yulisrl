$(document).ready(function () {
	//OCULTA LA TABLA DE PRODUCTOS Y MUESTRA EL FORMULARIO DE AGREGAR NUEVO PRODUCTO
	$(document).on('click', '.AddNewProductoBTN', function (event) {
		event.preventDefault();
		$(".tableProductos").addClass('d-none');
		$(".formNewProducto").removeClass('d-none');
	});

	//OCULTA EL FORMULARIO DE NUEVO PRODUCTOS Y REGRESA A LA TABLA PRODUCTOS
	$(document).on('click', '.cancelarRegNewProducto', function (event) {
		event.preventDefault();
		$(".tableProductos").removeClass('d-none');
		$(".formNewProducto").addClass('d-none');
	});

	//LLAMA LOS DATOS DEL PRODUCTO A EDITAR
	$(document).on('click', '.editProdExistente', function (event) {
		event.preventDefault();
		$(".editProducto").removeClass('d-none');
		$(".tableProductos").addClass('d-none');
		var idProducto = $(this).attr("id");
		$('#imgx_ + img').remove();
		$.ajax({
			url: 'includes/getDataProducto.php',
			type: 'POST',
			dataType: 'json',
			data: { id: idProducto },
			success: function (data) {
				$("#idProducto_").val(data.idProducto);
				$("#imgx_").after("<img src='Productos/" + data.Imagen + "' alt='Imagen' height='150px'>");
				$("#ProdNomnbre_").val(data.Producto);
				$("#ProdMarca_").val(data.Marca);
				$("#ProdModelo_").val(data.Modelo);
				$("#ProdStockCB_").val(data.StockCB);
				$("#ProdStockSC_").val(data.StockSC);
				$("#ProdStockTJ_").val(data.StockTJ);
				$("#ProdStockLP_").val(data.StockLP);
				$("#ProdPrecioCB_").val(data.PrecioCB);
				$("#ProdPrecioLP_").val(data.PrecioLP);
				$("#ProdPrecioSC_").val(data.PrecioSC);
				$("#ProdPrecioTJ_").val(data.PrecioTJ);
				$("#ProdObservCB_").val(data.ObservacionesCB);
				$("#ProdObservLP_").val(data.ObservacionesLP);
				$("#ProdObserv_SC").val(data.ObservacionesSC);
				$("#ProdObserv_TJ").val(data.ObservacionesTJ);
				$("#ProdDescripcion_").val(data.Descripcion);
			}
		})
	});

	//OCULTA EL FORMULARIO DE EDITAR PRODUCTO Y MUESTRA LA TABLA PRODUCTOS
	$(document).on('click', '.cancelarEditProducto', function (event) {
		event.preventDefault();
		$(".editProducto").addClass('d-none');
		$(".tableProductos").removeClass('d-none');
	});

	//BUSCAR PRODUCTO POR MODELO
	$(document).on('click', '.buscarProductoBtn', function (event) {
		event.preventDefault();

		var Modelo = $("#byModelo").val();

		if (Modelo == '') {
			$(".noModeloProd").removeClass('d-none');
			setTimeout(function () {
				$(".noModeloProd").addClass('d-none');
			}, 2000); return false;
		} else {
			$.ajax({
				url: 'includes/getFindProductos.php',
				type: 'POST',
				dataType: 'html',
				data: $("#findProducto").serialize(),
			})
				.done(function (data) {
					$("#respuestaFindProducto").html(data);
				})
			return false;
		}
	});

	$(document).on('click', '.borrarProducto', function (event) {
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
					data: "action=BorrarProductoLista&id=" + idProducto,
				})
					.done(function (data) {
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

	$(document).on('click', '.DownProductos', function (event) {
		event.preventDefault();
		$.post('do.php', "action=DescargarTablaProductos", function (data) {
			/*optional stuff to do after success */
		});
	});

	//LLAMA DATOS PRODUCTO FISCAL
	$(document).on('click', '.editProdFiscal', function (event) {
		event.preventDefault();
		$(".editProducto").removeClass('d-none');
		$(".tableProductos").addClass('d-none');
		var idProducto = $(this).attr("id");
		$('#imgx_ + img').remove();
		$.ajax({
			url: 'includes/getDataProductoFiscales.php',
			type: 'POST',
			dataType: 'json',
			data: { id: idProducto },
			success: function (data) {
				$("#idProducto_").val(data.idProducto);
				document.getElementById("spanidproducto").innerHTML = "#ID PRODUCTO: "+data.idProducto+" -----> SUBIDO AL SISTEMA : "+data.fecha_subido_sistema;
""
				$("#detalle").val(data.detalle);
				$("#codigo").val(data.codigo);

				$("#fecha_poliza").val(data.fecha_poliza);
				$("#saldo_fisico").val(data.saldo_fisico);

				$("#c_u_facturar_minimo").val(data.c_u_facturar_minimo);
				$("#importes_para_facturar").val(data.importes_para_facturar);

			}
		})
	});

	//cerrar formulario
	$(document).on('click', '.cancelarEditProducto', function (event) {
		event.preventDefault();
		$(".editProducto").addClass('d-none');
		$(".tableProductos").removeClass('d-none');
	});

	$(document).on('click', '.borrarFiscal', function (event) {
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
			title: '¿Borrar Producto Fiscal?',
			html: "Esta acción no se puede revertir.",
			type: 'warning',
			showCancelButton: true,
			confirmButtonText: 'Sí, borrar!',
			cancelButtonText: 'No, cancelar!',
			reverseButtons: true
		}).then((result) => {
			if (result.value) {
				$.ajax({
					url: 'includes/productosFiscalesDelete.php',
					type: 'POST',
					dataType: 'html',
					data: "action=BorrarProductoFiscal&id=" + idProducto,
				})
					.done(function (data) {
						$(".respuesta").html(data);
					})
				return false;
			} else if (
				/* Read more about handling dismissals below */
				result.dismiss === Swal.DismissReason.cancel
			) {
				swalWithBootstrapButtons.fire(
					'Cancelado',
					'El Producto Fiscal ya no será borrado',
					'error'
				)
			}
		})
	});

	$(document).on('click', '.BuscarSubidaSistema', function (event) {
		event.preventDefault();
		$(".divBuscarSubidaSistema").addClass('d-none');
		$(".divFecha").removeClass('d-none');
		
	});







});

