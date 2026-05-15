<script type="text/javascript">
function filePreview_(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            $('#imgx + img').remove();
            $('#imgx').after('<img src="' + e.target.result + '" width="150"/>');
        }
        reader.readAsDataURL(input.files[0]);
    }
}
$("#img_file").change(function() {
    filePreview_(this);
});

$("#img_file").change(function() {
    var file = this.files[0];
    var imagefile = file.type;
    var match = ["image/jpeg", "image/png", "image/jpg"];
    if (!((imagefile == match[0]) || (imagefile == match[1]) || (imagefile == match[2]))) {
        alert('Seleccione una imagen válida (JPEG/JPG/PNG).');
        $("#img_file").val('');
        return false;
    }
});

function filePreview(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            $('#imgx_ + img').remove();
            $('#imgx_').after('<img src="' + e.target.result + '" width="150"/>');
        }
        reader.readAsDataURL(input.files[0]);
    }
}

$("#img_file_").change(function() {
    filePreview(this);
});

$("#img_file_").change(function() {
    var file = this.files[0];
    var imagefile = file.type;
    var match = ["image/jpeg", "image/png", "image/jpg"];
    if (!((imagefile == match[0]) || (imagefile == match[1]) || (imagefile == match[2]))) {
        alert('Seleccione una imagen válida (JPEG/JPG/PNG).');
        $("#img_file").val('');
        return false;
    }
});

function uploadExcel() {
    $(".buttonexcel").attr('disabled', true);

    var Form = new FormData($('#filesForm')[0]);
    $.ajax({

        url: "includes/recibe_excel_validando.php",
        type: "post",
        data: Form,
        processData: false,
        contentType: false,
        success: function(data) {
            $(".respuesta").html(data);
            // Swal.fire({
            //     type: 'success',
            //     title: 'EXCEL SUBIDO AL SISTEMA',
            // })
            // setTimeout(function() {
            //     location.reload();
            // }, 4500);
            //location.reload();
        }
    });
};

function uploadExcelProductosReales() {
    $(".buttonexcel").attr('disabled', true);

    var Form = new FormData($('#filesForm')[0]);
    $.ajax({

        url: "includes/recibe_excel_productos_reales.php",
        type: "post",
        data: Form,
        processData: false,
        contentType: false,
        success: function(data) {
            $(".respuesta").html(data);
            // Swal.fire({
            //     type: 'success',
            //     title: 'EXCEL SUBIDO AL SISTEMA',
            // })
            // setTimeout(function() {
            //     location.reload();
            // }, 4500);
            //location.reload();
        }
    });
};



function onLoadImage(files) {
    console.log(files)
    if (files && files[0]) {
        document
            .getElementById('imgName')
            .innerHTML = '  SELECCIONADO:  ' + (files[0].name).toUpperCase()
    }
}
</script>