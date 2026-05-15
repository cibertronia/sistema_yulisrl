<?php
    ob_start();
    // Configuración dinámica de sesiones (Windows vs Linux)
    if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
        $_tmp_dir = 'C:/laragon/tmp';
    } else {
        $_tmp_dir = '/home/cibertronia/domains/sistema-yulisrl.cibertronia.cloud/tmp';
    }
    if (!is_dir($_tmp_dir)) { @mkdir($_tmp_dir, 0777, true); }
    ini_set('session.save_path', $_tmp_dir);
    ini_set('session.use_strict_mode', 0);
	session_start();
	include 'includes/conexion.php';
    error_log("Index.php Session ID: " . session_id());
    error_log("Index.php Session Content: " . print_r($_SESSION, true));
    // Debug sessions
    if (isset($_SESSION['idUser'])) {
        error_log("Index.php Session active: idUser=" . $_SESSION['idUser'] . " Estado=" . ($_SESSION['Estado'] ?? 'N/A'));
    }
	include 'includes/date.class.php';
	include 'includes/global.php';
	mysqli_query($MySQLi,"SET lc_time_names= 'es_BO' ");
	if (isset($_SESSION['idUser'])) {
		if (isset($_GET['root'])) {
			$Pag	=	$_GET['root'];
		}else{
			$Pag 	=	"inicio";
		}
		switch ($_SESSION['Estado']) {
			case '1':
				if (file_exists("Paginas/".$Pag.".php" )) {
					include 'Paginas/'.$Pag.".php" ;
				}else{
					header("HTTP/1.0 404 Not found", true, 404);
					include_once 'Paginas/404.php';
				}
			break;
			
			default:
				session_destroy();
				header('Location: ./');
			break;
		}
	}else{
		include 'Paginas/login.php';
	}
?>