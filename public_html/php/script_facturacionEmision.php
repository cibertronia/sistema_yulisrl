<script src="assets/js/app.min.js"></script>
<script src="assets/js/theme/apple.min.js"></script>

<script src="assets/plugins/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="assets/plugins/datatables.net-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="assets/plugins/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
<script src="assets/plugins/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js"></script>
<script src="assets/plugins/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
<script src="assets/plugins/datatables.net-buttons-bs4/js/buttons.bootstrap4.min.js"></script>
<script src="assets/plugins/datatables.net-buttons/js/buttons.colVis.min.js"></script>
<script src="assets/plugins/datatables.net-buttons/js/buttons.flash.min.js"></script>
<!-- <script src="assets/plugins/datatables.net-buttons/js/buttons.html5.min.js"></script> -->

<!-- ORIENTACION -->
<script src="assets/plugins/datatables.net-buttons/js/pdfHorizontal.js"></script>
<!-- <script src="assets/plugins/datatables.net-buttons/js/pdfVertical.js"></script> -->
<!-- ORIENTACION -->

<script src="assets/plugins/blueimp-file-upload/js/vendor/jquery.ui.widget.js"></script>
<script src="assets/plugins/blueimp-tmpl/js/tmpl.js"></script>
<script src="assets/plugins/blueimp-load-image/js/load-image.all.min.js"></script>
<script src="assets/plugins/blueimp-canvas-to-blob/js/canvas-to-blob.js"></script>
<script src="assets/plugins/blueimp-gallery/js/jquery.blueimp-gallery.min.js"></script>
<script src="assets/plugins/blueimp-file-upload/js/jquery.iframe-transport.js"></script>
<script src="assets/plugins/blueimp-file-upload/js/jquery.fileupload.js"></script>
<script src="assets/plugins/blueimp-file-upload/js/jquery.fileupload-process.js"></script>
<script src="assets/plugins/blueimp-file-upload/js/jquery.fileupload-image.js"></script>
<script src="assets/plugins/blueimp-file-upload/js/jquery.fileupload-audio.js"></script>
<script src="assets/plugins/blueimp-file-upload/js/jquery.fileupload-video.js"></script>
<script src="assets/plugins/blueimp-file-upload/js/jquery.fileupload-validate.js"></script>
<script src="assets/plugins/blueimp-file-upload/js/jquery.fileupload-ui.js"></script>
<script src="assets/js/demo/form-multiple-upload.demo.js"></script>
<script src="assets/plugins/parsleyjs/dist/parsley.min.js"></script>
<script src="assets/plugins/highlight.js/highlight.min.js"></script>
<script src="assets/js/demo/render.highlight.js"></script>
<script src="assets/plugins/datatables.net-buttons/js/buttons.print.min.js"></script>
<script src="assets/plugins/pdfmake/build/pdfmake.min.js"></script>
<script src="assets/plugins/pdfmake/build/vfs_fonts.js"></script>
<script src="assets/plugins/jszip/dist/jszip.min.js"></script>
<script src="assets/js/demo/table-manage-buttons.demo.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@8"></script>
<!-- <script src="assets/plugins/select2/dist/js/select2.min.js"></script> -->
<script src="assets/select2oscuro/js/select2.min.js"></script>
<script type="text/javascript" src="assets/switchery/switchery.js"></script>
<script type="text/javascript" src="assets/js/jquery.mask.js"></script>
<script src="functions/facturacionEmision.js"></script>
<script type="text/javascript">
var elem = document.querySelector('.js-switch');
var init = new Switchery(elem);
</script>
<style>
.card {
    background-color: #f9f9f9;
    border: none;
    border-radius: 10px;
    padding: 20px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    transition: box-shadow 0.3s ease;
}

.card:hover {
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.2);
}

.options {
    display: flex;
    justify-content: center;
}

.option {
    cursor: pointer;
    text-align: center;
    padding: 10px 20px;
    margin: 0 10px;
    border: none;
    border-radius: 5px;
    background-color: #007bff;
    color: white;
    font-weight: bold;
    transition: transform 0.3s ease;
}

.option:hover {
    transform: translateY(-5px);
}

.efecto_op {
    opacity: 0;
    transform: translateY(20px);
    animation: fadeInUp 1.5s forwards;
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }

    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.lineas-margen {
    border: 2px dashed #ccc;
    margin-top: 0px;
    margin-left: 5px;
    margin-right: 5px;
    padding: 20px;
}
.lineas-margen-yuliimport {
    border: 2px dashed #19CC19;
    margin-top: 0px;
    margin-left: 5px;
    margin-right: 5px;
    padding: 20px;
}
.lineas-margen-srl {
    border: 2px dashed #1C3756;
    margin-top: 0px;
    margin-left: 5px;
    margin-right: 5px;
    padding: 20px;
}

.bg-custom-blue {
    background-color: #1C3756;
    /* Cambia el valor hexadecimal por el color que desees */
    color: white;
    /* Cambia el color del texto para que sea legible en el fondo */
}
.bg-custom-green {
    background-color: #19CC19;
    /* Cambia el valor hexadecimal por el color que desees */
    color: white;
    /* Cambia el color del texto para que sea legible en el fondo */
}

/* baile
*/

.invoice {
    width: 200px;
    height: 240px;
    background-color: #fff;
    position: relative;
    animation: dance 2s infinite;
    border-radius: 10px;
    box-shadow: 0 3px 6px rgba(0, 0, 0, 0.1);
    display: flex;
    flex-direction: column;
    justify-content: flex-end;
    align-items: center;
}

.paper {
    width: 120px;
    height: 160px;
    background-color: #f2f2f2;
    position: relative;
    border-top-left-radius: 10px;
    border-top-right-radius: 10px;
    display: flex;
    flex-direction: column;
    justify-content: flex-end;
    align-items: center;
}

.details {
    width: 100%;
    padding: 10px;
    box-sizing: border-box;
    color: #333;
    font-size: 12px;
    text-align: center;
}

.header {
    font-weight: bold;
    padding-bottom: 5px;
    background-color: #1C3756;
    color: white;
}

@keyframes dance {

    0%,
    100% {
        transform: translateY(0);
    }

    50% {
        transform: translateY(-10px);
    }
}

/* termina baile */
</style>
