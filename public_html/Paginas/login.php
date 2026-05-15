<!DOCTYPE html>
<html lang="es">

<head>
    <title>YULI SRL</title>
    <?php include 'php/meta.php';?>
    <link href="assets/css/apple/app.min.css" rel="stylesheet">
    <link href="assets/plugins/ionicons/css/ionicons.min.css" rel="stylesheet">
</head>

<body class="pace-top">
    <?php
include 'php/loader.php';
include 'php/background.php';
?>
    <div id="page-container" class="fade">
        <div class="respuesta"></div>
        <div class="login login-v2" data-pageload-addclass="animated fadeIn">
            <div class="login-header">
                <div class="brand">
                    <img src="assets/img/logo.png" alt="Importadora YULI" width="100%">
                </div>
                <div class="alert alert-danger fade show mt-3 text-center d-none disbleAccount">
                    <strong>LO SENTIMOS!!</strong><br>
                    Tu cuenta ha sido deshabilitada<br>
                </div>
            </div>
            <div class="login-content">
                <form id="login" class="margin-bottom-0">
                    <!-- <h1 class="text-center">SISTEMA PRUEBAS</h1> -->
                    <div class="form-group m-b-20">
                        <input type="hidden" name="action" value="LOGIN">
                        <input type="text" name="user" class="form-control form-control-lg"
                            placeholder="usuario@email.com" required>
                        <div class="text-center text-danger f-s-16 d-none errorUser">El Usuario ingresado no existe
                        </div>
                    </div>
                    <div class="form-group m-b-20">
                        <input type="password" name="pswd" class="form-control form-control-lg" placeholder="Contraseña"
                            required>
                        <div class="text-center text-danger f-s-16 d-none errorPswd">La contraseña no es válida</div>
                    </div>
                    <div class="login-buttons">
                        <button type="submit" class="btn btn-success btn-block btn-lg">INICIAR SESIÓN &nbsp;<i
                                class="fas d-none fa-spinner fa-pulse"></i></button>
                    </div>
                </form>
            </div>
        </div>
        <?php //include 'php/images_background.php'; ?>
    </div>
    <script src="assets/js/app.min.js"></script>
    <script src="assets/js/theme/apple.min.js"></script>
    <script src="assets/js/demo/login-v2.demo.js"></script>
    <script type="text/javascript" src="functions/analitic.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@8"></script>
</body>

</html>
<script type="text/javascript">
$("#login").submit(function() {
    $(".fa-spinner").removeClass('d-none');
    $(".btn-block").attr('disabled', true);
    $.ajax({
            url: 'do.php',
            type: 'POST',
            dataType: 'html',
            data: $(login).serialize(),
        })
        .done(function(data) {
            $(".fa-spinner").addClass('d-none');
            $(".btn-block").attr('disabled', false);
            $(".respuesta").html(data);
        })
    return false;
});
</script>