<?php
    ob_start();
    ini_set('session.save_path', 'C:/laragon/tmp');
    ini_set('session.use_strict_mode', 0);
	session_start();
	include 'includes/conexion.php';
    error_log("Do.php Session ID: " . session_id());
	include 'includes/date.class.php';
	include 'includes/TeleGram.php';
	include 'includes/global.php';
	require 'includes/librerias/phpMailer/vendor/autoload.php';
include 'includes/App/Controllers/ProductsController.php';
use App\Controllers\ProductsController;
use PHPMailer\PHPMailer\PHPMailer;
	use PHPMailer\PHPMailer\SMTP;
	use PHPMailer\PHPMailer\Exception;
	$mail = new PHPMailer(true);

	$sqlurlcucu = mysqli_query($MySQLi, "SELECT * FROM token_access");
	$dataurlcucu = mysqli_fetch_assoc($sqlurlcucu) or die(mysqli_error($MySQLi));
	$urlcucu=$dataurlcucu['urlcucu'];
	$to = $dataurlcucu['token'];
	$usercucu = $dataurlcucu['user'];
	$passcucu = $dataurlcucu['passcucu'];

	$Action 	=	filter_var($_POST['action'],FILTER_SANITIZE_FULL_SPECIAL_CHARS);
	switch ($Action) {
		case 'TeleGram':
			$idUser		=	$_SESSION['idUser'];
			$apiTele 	=	$_POST['api'];
			$queryAPI 	=	mysqli_query($MySQLi,"SELECT * FROM TeleGram WHERE idUser='$idUser' ");
			$resultAPI	=	mysqli_num_rows($queryAPI);
			if ($resultAPI>0) {
				$updateAPI	=	mysqli_query($MySQLi,"UPDATE TeleGram SET Api='$apiTele' WHERE idUser='$idUser' ")or die(mysqli_error($MySQLi));
				if ($updateAPI) { mysqli_close($MySQLi); ?>
<script type="text/javascript">
Swal.fire({
    type: 'success',
    title: 'ID TeleGram Actualizado',
    animation: false,
    customClass: {
        popup: 'animated bounceInDown'
    }
})
setTimeout(function() {
    location.reload();
}, 2000);
</script><?php
				}else{ mysqli_close($MySQLi); ?>
<script type="text/javascript">
Swal.fire({
    type: 'error',
    title: 'error Actualizar',
    animation: false,
    customClass: {
        popup: 'animated shake'
    }
})
</script><?php
				}
			}else{
				$insertAPI	=	mysqli_query($MySQLi,"INSERT INTO TeleGram (Api, idUser) VALUES ('$apiTele', '$idUser') ")or die(mysqli_error($MySQLi));
				if ($insertAPI) { mysqli_close($MySQLi); ?>
<script type="text/javascript">
Swal.fire({
    type: 'success',
    title: 'ID TeleGram Registrado',
    animation: false,
    customClass: {
        popup: 'animated bounceInDown'
    }
})
setTimeout(function() {
    location.reload();
}, 2000);
</script><?php
				}else{ mysqli_close($MySQLi); ?>
<script type="text/javascript">
Swal.fire({
    type: 'error',
    title: 'error Registrado',
    animation: false,
    customClass: {
        popup: 'animated shake'
    }
})
</script><?php
				}
			}
		break;
		case 'LOGIN':
			$User 	=	$_POST['user'];
			$Pswd 	=	$_POST['pswd'];
            error_log("Login attempt: User=$User");
			//VERIFICAMOS SI EL USUARIO EXISTE
			$queryUser 	=	mysqli_query($MySQLi,"SELECT * FROM Usuarios WHERE Correo='$User' ");
            if (!$queryUser) {
                error_log("MySQL Error: " . mysqli_error($MySQLi));
                die(mysqli_error($MySQLi));
            }
			$resultUsers=	mysqli_num_rows($queryUser);
            error_log("Users found: $resultUsers");
			if ($resultUsers>0) {
				$data 	=	mysqli_fetch_assoc($queryUser);

				// RESTRICCIÓN DE HORARIO LABORAL PARA VENDEDORES
				if ($data['Rango'] == 1) {
                    error_log("Vendedor login check...");
					// Hora inicio y final de la jornada laboral
					// Hora inicio y final de la jornada laboral
					// Hora final 19:00:00
					$horaInicio = "08:30:00";
					$horaFinal  = "19:00:00";
					// Dias de la semana laboral (0=Domingo, 6=Sábado)
					$diasLaborales = array(1, 2, 3, 4, 5, 6); // Lunes a Sabado
					
					// Obtener horario actual del sistema
					$horaActual = date("H:i:s");
					$diaActual  = date("w");

					// Validar si la hora y dia actual está dentro del horario laboral
					if ($horaActual < $horaInicio || $horaActual > $horaFinal || !in_array($diaActual, $diasLaborales)) { ?>
						<script type="text/javascript">
							Swal.fire({
								type: 'error',
								title: 'Fuera de Horario Laboral',
								html: 'El acceso al sistema solo está permitido de <strong>08:30 AM</strong> a <strong>07:00 PM</strong>.',
								animation: false,
								customClass: {
									popup: 'animated bounceInDown'
								}
							});
						</script>
						<?php 
						exit();
					}
				}
				//VERIFICAMOS SI LA CONTRASEÑA ES VALIDA
                $is_valid = password_verify($Pswd, $data['Contrasena']);
                error_log("Password valid: " . ($is_valid ? 'YES' : 'NO'));
				if ($is_valid) {
					//VERIFICAMOS SI LA CUENTA ESTÁ ACTIVA
					if ($data['Estado']==1) {
                        error_log("Account active, setting session...");
						$_SESSION['Contrasena']	=	$Pswd;
						$_SESSION['Estado']		=	$data['Estado'];
						$_SESSION['idUser']		=	$data['idUser'];
						$_SESSION['Rango']		=	$data['Rango'];

							// $url = $urlcucu.'/api/v1/auth/login';
							// $ch = curl_init($url);
							// $data = array(
							// 	'username' => $usercucu,
							// 	'password' => $passcucu,
							// );
							// $payload = json_encode(($data));
							// curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
							// curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
							// curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
							// $result = curl_exec($ch);
							// curl_close($ch);
							// //GUARDANDO EN BD
							// $datos = json_decode($result);
							// if ($datos->{'message'} != "TOKEN CREADED") {
							// 	$jsvar = $datos->{'message'};
							// } else {
							// 	$var = $datos->{'data'};
							// 	$username = $var->{'username'};
							// 	$token = $var->{'token'};
							// 	$jsvar = $datos->{'message'};
							// 	$sql = mysqli_query($MySQLi, "UPDATE token_access SET token='$token' WHERE user='$username'");}
								
							




						//$_SESSION['FullName']	=	$data['Nombres']." ".$data['Apellidos'];
						//$_SESSION['Correo']		=	$data['Correo'];
						//$_SESSION['Cargo']		=	$data['Cargo'];
						//$_SESSION['Sexo']		=	$data['Sexo']; mysqli_close($MySQLi);
                        session_write_close();
                        ob_end_clean();
                        ?>
<script type="text/javascript">
location.replace("./");
</script><?php
					}else{ mysqli_close($MySQLi); ?>
<script type="text/javascript">
$(".login-content").addClass('d-none');
$(".disbleAccount").removeClass('d-none');
</script><?php
					}
				}else{ mysqli_close($MySQLi); ?>
<script type="text/javascript">
$(".errorPswd").removeClass('d-none');
setTimeout(function() {
    $(".errorPswd").addClass('d-none');
}, 2500);
</script><?php					
				}
			}else{ mysqli_close($MySQLi); ?>
<script type="text/javascript">
$(".errorUser").removeClass('d-none');
setTimeout(function() {
    $(".errorUser").addClass('d-none');
}, 2500);
</script><?php
			}
		break;
		/*	AREA CLIENTES	 */
		case 'RegistrarNuevoCliente':
			if (isset($_SESSION['idUser'])) {				
				$idUser 	=	$_SESSION['idUser'];
				$Nombres 	=	$_POST['Nombres'];
				$Apellidos=	$_POST['Apellidos'];
				$Celular 	=	$_POST['Celular'];
				$Fijo 		=	$_POST['Otro'];
				$Correo 	=	$_POST['Correo'];
				$Ciudad 	=	$_POST['Ciudad'];
				$Empresa 	=	$_POST['Empresa'];
				$NIT 			=	$_POST['NIT'];
				$Direccion 	=	$_POST['Direccion'];
				$Comentarios=	$_POST['Observaciones'];
				$Sucursal 	=	$_POST['sucursal'];
				$dateRegist =	$_POST['fechaRegistro'];
				$query 		=	mysqli_query($MySQLi,"INSERT INTO Clientes (Nombres, Apellidos, Correo, Empresa, NIT, Celular, Otro, Ciudad, Direccion, Comentarios, Fecha_Reg, Registrador,Sucursal) VALUES ('$Nombres', '$Apellidos', '$Correo', '$Empresa', '$NIT', '$Celular', '$Fijo', '$Ciudad', '$Direccion', '$Comentarios', '$dateRegist', '$idUser', '$Sucursal')");
				if ($query) { mysqli_close($MySQLi); ?>
<script type="text/javascript">
Swal.fire({
    type: 'success',
    title: 'Cliente registrado!',
    html: 'El Cliente se guardó exitosamente.',
    animation: false,
    customClass: {
        popup: 'animated bounceInDown'
    }
})
setTimeout(function() {
    location.reload();
}, 2000);
</script> <?php exit();
				}else{ mysqli_close($MySQLi); ?>
<script type="text/javascript">
Swal.fire({
    type: 'error',
    title: 'Insert Error!',
    html: 'Hubo un error al intentar registrar el Cliente.<br>Notifica al Administrador',
    animation: false,
    customClass: {
        popup: 'animated shake'
    }
})
</script> <?php exit();
				}
			}else{ mysqli_close($MySQLi); session_destroy(); ?>
<script type="text/javascript">
Swal.fire({
    type: 'error',
    title: 'Sesión caducada',
})
setTimeout(function() {
    location.reload();
}, 2500);
</script>
<?php
			}
		break;
		case 'ActualizarMiCliente':
			if (isset($_SESSION['idUser'])) {
				$idCliente 	=	$_POST['idCliente'];
				$Nombres 		=	$_POST['Nombres'];
				$Apellidos 	=	$_POST['Apellidos'];
				$Correo 		=	$_POST['Correo'];
				$Empresa 		=	$_POST['Empresa'];
				$NIT 				=	$_POST['NIT'];
				$Celular 		=	$_POST['Celular'];
				$Fijo 			=	$_POST['Otro'];
				$Ciudad 		=	$_POST['Ciudad'];
				$Direccion 	=	$_POST['Direccion'];
				$Comentarios=	$_POST['Comentarios'];
				$FechaRegist=	$_POST['fechaRegistro'];
				// VEAMOS SI HUBO ALGUN CAMBIO
				$sqlCambios =	mysqli_query($MySQLi,"SELECT * FROM Clientes WHERE idCliente='$idCliente' ");
				$dataCliente=	mysqli_fetch_assoc($sqlCambios);
				if ($Nombres==$dataCliente['Nombres'] AND $Apellidos==$dataCliente['Apellidos'] AND $Correo==$dataCliente['Correo'] AND $Empresa==$dataCliente['Empresa'] AND $NIT==$dataCliente['NIT'] AND $Celular==$dataCliente['Celular'] AND $Fijo==$dataCliente['Otro'] AND $Ciudad==$dataCliente['Ciudad'] AND $FechaRegist=$dataCliente['Fecha_Reg'] AND $Direccion==$dataCliente['Direccion'] AND $Comentarios==$dataCliente['Comentarios']) { mysqli_close($MySQLi); ?>
<script type="text/javascript">
Swal.fire({
    type: 'error',
    title: 'SIN CAMBIOS!',
    html: 'No se detectó ningún cambio',
    animation: false,
    customClass: {
        popup: 'animated shake'
    }
})
</script> <?php exit();
				}else{
					$query 		=	mysqli_query($MySQLi,"UPDATE Clientes SET Nombres='$Nombres', Apellidos='$Apellidos', Correo='$Correo', Empresa='$Empresa', NIT='$NIT',  Celular='$Celular', Otro='$Fijo', Ciudad='$Ciudad', Direccion='$Direccion', Comentarios='$Comentarios', Fecha_Reg='$FechaRegist' WHERE idCliente='$idCliente'");
					if ($query) { mysqli_close($MySQLi); ?>
<script type="text/javascript">
Swal.fire({
    type: 'success',
    title: 'Cliente Actualizado!',
    html: 'El Cliente se actualizó exitosamente.',
    animation: false,
    customClass: {
        popup: 'animated bounceInDown'
    }
})
setTimeout(function() {
    location.reload();
}, 2000);
</script> <?php
					}else{ mysqli_close($MySQLi); ?>
<script type="text/javascript">
Swal.fire({
    type: 'error',
    title: 'update Error!',
    html: 'Hubo un error al intentar actualizar los datos del Cliente.<br>Notifica al Administrador',
    animation: false,
    customClass: {
        popup: 'animated shake'
    }
})
</script> <?php exit();
					}
				}
			}else{ mysqli_close($MySQLi); session_destroy(); ?>
<script type="text/javascript">
Swal.fire({
    type: 'error',
    title: 'Sesión caducada',
})
setTimeout(function() {
    location.reload();
}, 2500);
</script>
<?php
			}
		break;
		case 'ActualizarCliente':
			if (isset($_SESSION['idUser'])) {
				if ($_SESSION['Rango']==2) {
					if (!empty($_POST['Celular'] && !is_numeric($_POST['Celular']))) {
						echo "<script>$('.isNaNCell_').removeClass('d-none'); setTimeout(function(){ $('.isNaNCell_').addClass('d-none');},2500);</script>";
					}elseif (!empty($_POST['Otro'] && !is_numeric($_POST['Otro']))) {
						echo "<script>$('.isNaNOtro_').removeClass('d-none'); setTimeout(function(){ $('.isNaNOtro_').addClass('d-none');},2500);</script>";
					}else{
						$idCliente 	=	$_POST['idCliente'];
						$Nombres 		=	$_POST['Nombres'];
						$Apellidos 	=	$_POST['Apellidos'];
						$Correo 		=	$_POST['Correo'];
						$Empresa 		=	$_POST['Empresa'];
						$NIT 				=	$_POST['NIT'];
						$Celular 		=	$_POST['Celular'];
						$Otro 			=	$_POST['Otro'];
						$Ciudad 		=	$_POST['Ciudad'];
						$Direccion 	=	$_POST['Direccion'];
						$Comentarios=	$_POST['Comentarios'];
						// VEAMOS SI HUBO ALGUN CAMBIO
						$sqlCambios =	mysqli_query($MySQLi,"SELECT * FROM Clientes WHERE idCliente='$idCliente' ");
						$dataCliente=	mysqli_fetch_assoc($sqlCambios);
						if ($Nombres==$dataCliente['Nombres'] AND $Apellidos==$dataCliente['Apellidos'] AND $Correo==$dataCliente['Correo'] AND $Empresa==$dataCliente['Empresa'] AND $NIT==$dataCliente['NIT'] AND $Celular==$dataCliente['Celular'] AND $Otro==$dataCliente['Otro'] AND $Ciudad==$dataCliente['Ciudad'] AND $Direccion==$dataCliente['Direccion'] AND $Comentarios==$dataCliente['Comentarios']) { mysqli_close($MySQLi); ?>
<script type="text/javascript">
Swal.fire({
    type: 'error',
    title: 'SIN CAMBIOS!',
    html: 'No se detectó ningún cambio',
    animation: false,
    customClass: {
        popup: 'animated shake'
    }
})
</script> <?php exit();
						}else{
							$query 		=	mysqli_query($MySQLi,"UPDATE Clientes SET Nombres='$Nombres', Apellidos='$Apellidos', Correo='$Correo', Empresa='$Empresa', NIT='$NIT',  Celular='$Celular', Otro='$Otro', Ciudad='$Ciudad', Direccion='$Direccion', Comentarios='$Comentarios' WHERE idCliente='$idCliente'");
							if ($query) { mysqli_close($MySQLi); ?>
<script type="text/javascript">
Swal.fire({
    type: 'success',
    title: 'Cliente Actualizado!',
    html: 'El Cliente se actualizó exitosamente.',
    animation: false,
    customClass: {
        popup: 'animated bounceInDown'
    }
})
setTimeout(function() {
    location.reload();
}, 2000);
</script> <?php
							}else{ mysqli_close($MySQLi); ?>
<script type="text/javascript">
Swal.fire({
    type: 'error',
    title: 'update Error!',
    html: 'Hubo un error al intentar actualizar los datos del Cliente.<br>Notifica al Administrador',
    animation: false,
    customClass: {
        popup: 'animated shake'
    }
})
</script> <?php exit();
							}
						}
					}
				}else{ mysqli_close($MySQLi); ?>
<script type="text/javascript">
Swal.fire({
    type: 'error',
    title: 'SIN AUTORIZACIÓN!',
    html: 'No tienes los privilegios de Administrador para modificar los datos del cliente seleccionado.',
    animation: false,
    customClass: {
        popup: 'animated shake'
    }
})
</script> <?php exit();
				}
			}else{ mysqli_close($MySQLi); session_destroy(); ?>
<script type="text/javascript">
Swal.fire({
    type: 'error',
    title: 'Sesión caducada',
})
setTimeout(function() {
    location.reload();
}, 2500);
</script> <?php
			}
		break;
		/*	AREA DE USUARIOS LISTA	*/
		case 'RegistrarNuevoUsuario':
			if (isset($_SESSION['idUser'])) {
				if ($_SESSION['Rango']==2) {
					$Nombres		=	$_POST['Nombres'];
					$Apellidos		=	$_POST['Apellidos'];
					$Telefono		=	$_POST['Telefono'];
					$Ciudad 		= 	$_POST['Ciudad'];

					$Correo			=	$_POST['Correo'];
					$Sexo				=	$_POST['Sexo'];
					$Cargo 			=	$_POST['Cargo'];
					if ($Cargo == 	'Administrador') {
						$Rango 		=	2;
						if ($Sexo ==	'Masculino') {
							$Cargo 	=	'Administrador';
							$Avatar =	'admin.png';
						}else{
							$Cargo 	=	'Administradora';
							$Avatar =	'admin3.png';
						}
					}elseif ($Cargo ==	'Vendedor') {
						$Rango 		=	1;
						if ($Sexo ==	'Masculino') {
							$Cargo 	=	'Vendedor';
							$Avatar	=	'male.png';
						}else{
							$Cargo 	=	'Vendedora';
							$Avatar =	'female.png';
						}
					} /*	VERIFICAMOS SI EL USUARIO YA EXISTE 	*/
					$queryUser 	=	mysqli_query($MySQLi,"SELECT * FROM Usuarios WHERE Correo='$Correo' ");
					$resultUser =	mysqli_num_rows($queryUser);
					if ($resultUser>0) {
						mysqli_close($MySQLi); ?>
<script type="text/javascript">
// $("#NewNombres").val("");
// $("#NewApellidos").val("");
// $("#NewCorreo").val("");
// $("#NewTelefono").val("");
// $("#NewSucursal").val("Seleccione Sucursal");
// $("#NewSucursal option:selected").html("Seleccione Sucursal");
// $("#NewSexo").val("Sexo");
// $("#NewSexo option:selected").html("Sexo");
Swal.fire({
    type: 'error',
    title: 'EL CORREO YA ESTÁ REGISTRADO',
    showConfirmButton: false,
    //timer: 2500,
    animation: false,
    customClass: {
        popup: 'animated shake'
    }
})
</script><?php exit();
					}else{
						//SI EL USUARIO NO EXISTE EN LA BASE DE DATOS, PROCEDEMOS AL REGISTRO
						function password ($length = 6) { 
							$chars 	= '0123456789';
							$count 	= mb_strlen($chars);
							for ($i = 0, $result = ''; $i < $length; $i++) { 
								$index 	= rand(0, $count - 1); 
								$result .= mb_substr($chars, $index, 1); 
							} 
							return $result; 
						}
						$Password	=	password();
						$Hash 		=	password_hash($Password, PASSWORD_DEFAULT);
						//INSERTAMOS EL NUEVO USUARIO A LA BASE DE DATOS
						$saveUser	=	mysqli_query($MySQLi,"INSERT INTO Usuarios (Nombres, Apellidos, Correo, Rango, Cargo, Avatar, Contrasena, Ciudad, Telefono, Sexo) VALUES ('$Nombres', '$Apellidos', '$Correo', '$Rango', '$Cargo', '$Avatar', '$Hash', '$Ciudad', '$Telefono', '$Sexo') ");
						// SI EL USUARIO SE GUARDÓ CORRECTAMENTE
						if ($saveUser) { 
							if ($Sexo=='Femenino') {
								$Welcome = 'Bienvenida';
							}else{
								$Welcome = 'Bienvenido';
							}
							$html 		=	"
							<style>.contenedor{width: 75%;}.logo{text-align: center;}p{margin-left: 10%;font-size: 16px}</style><meta charset='UTF-8'><body><div class='contenedor'><div class='logo'><img src='https://sistema.yulisrl.com/assets/img/logo.png' width='20%' style='display:block; margin: 1.5% 3%' alt='Logo Yuli SRL'><h3>".$Welcome.": ".$Nombres." ".$Apellidos."</h3></div><p>Tu nueva cuenta ha sido creada exitosamente<br>Los datos de acceso son los siguientes:<br><br>Username: <strong>".$Correo."</strong><br>Password: <strong>".$Password."</strong><br><br>Puedes accesar desde aqu&iacute;:<br><a target='_blank' href='https://sistema.yulisrl.com/'>Yuli SRL</a><br><br>Mensaje enviado desde el Sistema Automatizado el d&iacute;a:<br>".$Fecha."<br>".$hora."</p></div></body>";							
							//LLAMAMOS LA CUENTA SMTP
							$queryMail	=	mysqli_query($MySQLi,"SELECT * FROM CuentasMail WHERE Sucursal='Soporte' ");
							$dataMail 	=	mysqli_fetch_assoc($queryMail);
							try {
							    //Server settings
							    $mail->SMTPDebug = 0;//SMTP::DEBUG_SERVER;                      // Enable verbose debug output
							    $mail->isSMTP();                                            // Send using SMTP
							    $mail->Host       = 'mail.yulisrl.com';               	   // Set the SMTP server to send through
							    $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
							    $mail->Username   = $dataMail['Correo'];                     // SMTP username
							    $mail->Password   = $dataMail['Password'];                               // SMTP password
							    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;	         // Enable TLS encryption;
							    //$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         // Enable TLS encryption;  
							    //`PHPMailer::ENCRYPTION_SMTPS` also accepted
							    $mail->Port       = 465;                                    // TCP port to connect to

							    //Recipients
							    $mail->setFrom($dataMail['Correo'], 'YuliSRL Nuevo Usuario');
							    $mail->addAddress($Correo);     // Add a recipient
							    //$mail->addAddress('ellen@example.com');               // Name is optional
							    //$mail->addReplyTo('info@example.com', 'Information');
							    //$mail->addCC('cc@example.com');
							    $mail->addBCC('abrahan.zambrana@gmail.com');

							    // Attachments
							   /* $mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
							    $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name*/

							    // Content
							    $mail->Charset = 'utf-8';
							    $mail->isHTML(true);                                  // Set email format to HTML
							    $mail->Subject = 'Tu cuenta ha sido creada';
							    $mail->Body    = $html;
							    //$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

							    $mail->send();
							    ///echo 'Mensaje enviado';
							    mysqli_close($MySQLi); ?>
<script type="text/javascript">
Swal.fire({
    type: 'success',
    title: 'USUARIO REGISTRADO CON ÉXITO',
    html: 'Hemos enviado un correo a:<br><strong><?php echo $Correo ?></strong><br>con los datos de acceso.<br>Si el correo no aparece en la bandeja de entrada, favor revisar en SPAM.<br>Los datos de acceso temporal son:<br>Usuario: <?php echo $Correo ?><br>Contraseña: <strong><?php echo $Password ?></strong>',
    showConfirmButton: false,
    animation: false,
    customClass: {
        popup: 'animated bounceInDown'
    }
})
setTimeout(function() {
    location.reload();
}, 5000);
</script><?php
							} catch (Exception $e) {
								//echo "hubo un error de envío: {$mail->ErrorInfo}";
								mysqli_close($MySQLi); ?>
<script type="text/javascript">
Swal.fire({
    type: 'error',
    title: "<?php echo $mail->ErrorInfo ?>",
    showConfirmButton: false,
    animation: false,
    customClass: {
        popup: 'animated shake'
    }
})
</script><?php
							}
						}else{
							mysqli_close($MySQLi); ?>
<script type="text/javascript">
Swal.fire({
    type: 'error',
    title: 'error',
    //html: 'Hemos enviado un correo a:<br><strong><?php //echo $Correo ?></strong><br>con los datos de acceso.<br>Si el correo no aparece en la bandeja de entrada, favor revisar en SPAM.<b>Los datos de acceso temporal son:<br>Usuario: <?php //echo $Correo ?><br>Contraseña: <strong><?php //echo $Password ?></strong>',
    showConfirmButton: false,
    animation: false,
    customClass: {
        popup: 'animated shake'
    }
})
</script><?php
						}
					}
				}else{
					mysqli_close($MySQLi);
					session_destroy(); ?>
<script type="text/javascript">
Swal.fire({
    position: 'center',
    type: 'error',
    title: 'SIN PRIVILEGIOS',
    showConfirmButton: false,
    timer: 2500,
})
setTimeout(function() {
    location.reload();
}, 2500);
</script><?php
				}
			}else{
				mysqli_close($MySQLi);
				session_destroy(); ?>
<script type="text/javascript">
Swal.fire({
    position: 'center',
    type: 'error',
    title: 'SESIÓN EXPIRADA',
    showConfirmButton: false,
    timer: 2500,
})
setTimeout(function() {
    location.reload();
}, 2500);
</script>
<?php
			}
		break;
		case 'ActualizarUsuarioLista':
			if (isset($_SESSION['idUser'])) {
				if ($_SESSION['Rango']==2) {
					if (empty($_POST['Nombres'])) {
						echo "<script>$('.emptyNombres').removeClass('d-none');setTimeout(function(){ $('.emptyNombres').addClass('d-none');},2500) </script>";
					}elseif (empty($_POST['Apellidos'])) {
						echo "<script>$('.emptyApellidos').removeClass('d-none');setTimeout(function(){ $('.emptyApellidos').addClass('d-none');},2500) </script>";
					}elseif (empty($_POST['Telefono'])) {
						echo "<script>$('.emptyTelefono').removeClass('d-none');setTimeout(function(){ $('.emptyTelefono').addClass('d-none');},2500) </script>";
					}elseif (empty($_POST['Sucursal'])) {
						echo "<script>$('.emptySucursal').removeClass('d-none');setTimeout(function(){ $('.emptySucursal').addClass('d-none');},2500) </script>";
					}elseif (empty($_POST['Correo'])) {
						echo "<script>$('.emptyCorreo').removeClass('d-none');setTimeout(function(){ $('.emptyCorreo').addClass('d-none');},2500) </script>";
					}elseif (empty($_POST['Sexo'])) {
						echo "<script>$('.emptySexo').removeClass('d-none');setTimeout(function(){ $('.emptySexo').addClass('d-none');},2500) </script>";
					}else{
						$idUser		=	$_POST['idUser'];
						$Nombres	=	$_POST['Nombres'];
						$Apellidos=	$_POST['Apellidos'];
						$Telefono	=	$_POST['Telefono'];
						$Sucursal	=	$_POST['Sucursal'];
						$Correo		=	$_POST['Correo'];
						$Sexo			=	$_POST['Sexo'];
						$Cargo 		=	$_POST['Cargo'];
						/*	VEAMOS SI HAY CAMBIOS EN LOS CAMPOS*/
						$queryUsers	=	mysqli_query($MySQLi,"SELECT * FROM Usuarios WHERE idUser='$idUser' ");
						$dataUsers	=	mysqli_fetch_assoc($queryUsers);
						if ($Nombres==$dataUsers['Nombres'] AND $Apellidos==$dataUsers['Apellidos'] AND $Telefono==$dataUsers['Telefono'] AND $Sucursal==$dataUsers['Ciudad'] AND $Correo==$dataUsers['Correo'] AND $Cargo==$dataUsers['Cargo'] AND $Sexo==$dataUsers['Sexo']) { mysqli_close($MySQLi);?>
<script type="text/javascript">
Swal.fire({
    position: 'center',
    type: 'error',
    title: 'NO HAY CAMBIOS',
    html: 'No se detectó ningún cambio dentro del formulario',
    showConfirmButton: false,
    timer: 2500,
    animation: false,
    customClass: {
        popup: 'animated shake'
    }
})
</script><?php exit();
						}else{
							if ($Sexo	==	'Masculino') {
								$Cargo	=	'Vendedor';
								$Avatar	=	'male.png';
							}else{
								$Cargo 	=	'Vendedora';
								$Avatar	=	'female.png';
							}
							$updataUser=	mysqli_query($MySQLi,"UPDATE Usuarios SET Nombres='$Nombres', Apellidos='$Apellidos', Correo='$Correo', Ciudad='$Sucursal', Telefono='$Telefono', Sexo='$Sexo', Cargo='$Cargo', Avatar='$Avatar' WHERE idUser='$idUser' ");
							if ($updataUser) { mysqli_close($MySQLi); ?>
<script type="text/javascript">
Swal.fire({
    position: 'center',
    type: 'success',
    title: 'USUARIO ACTUALIZADO',
    html: 'El usuario fué actualizado exitosamente',
    showConfirmButton: false,
    animation: false,
    customClass: {
        popup: 'animated bounceInDown'
    }
});
setTimeout(function() {
    location.reload();
}, 2500);
</script><?php
							}else{ mysqli_close($MySQLi); ?>
<script type="text/javascript">
Swal.fire({
    position: 'center',
    type: 'error',
    title: 'ERROR UPDATE',
    html: 'hubo un error al intentar actualizar el usuario seleccionado <br>Notifica al Proveedor.',
    showConfirmButton: false,
    animation: false,
    customClass: {
        popup: 'animated shake'
    }
})
</script><?php
							}
						}
					}
				}else{
					mysqli_close($MySQLi);
					session_destroy(); ?>
<script type="text/javascript">
Swal.fire({
    position: 'center',
    type: 'error',
    title: 'SIN PRIVILEGIOS',
    showConfirmButton: false,
    timer: 2500,
})
setTimeout(function() {
    location.reload();
}, 2500);
</script><?php
				}
			}else{
				mysqli_close($MySQLi);
				session_destroy(); ?>
<script type="text/javascript">
Swal.fire({
    position: 'center',
    type: 'error',
    title: 'SESIÓN EXPIRADA',
    showConfirmButton: false,
    timer: 2500,
})
setTimeout(function() {
    location.reload();
}, 2500);
</script><?php
			}
		break;
		case 'DeshabilitarUsuario':
			if (isset($_SESSION['idUser'])) {
				if ($_SESSION['Rango']==2) {
					$idUser 	=	$_POST['id'];
					//CONSULTAMOS SI SE TRATA DE UN ADMINISTRADOR EL QUE SE QUIERE BORRAR
					$queryAdmin	=	mysqli_query($MySQLi,"SELECT * FROM Usuarios WHERE idUser='$idUser' ");
					$dataAdmin	=	mysqli_fetch_assoc($queryAdmin);
					if ($dataAdmin['Rango']==2) { mysqli_close($MySQLi); ?>
<script type="text/javascript">
Swal.fire({
    position: 'center',
    type: 'error',
    title: 'Houston, tenemos un problema !!',
    html: 'Estás intentando deshabilitar a un administrador.<br>Eso no está permitido.',
    showConfirmButton: false,
    animation: false,
    customClass: {
        popup: 'animated shake'
    },
    timer: 5000,
})
</script><?php exit();
					}else{
						$updateUser	=	mysqli_query($MySQLi,"UPDATE Usuarios SET Estado=0 WHERE idUser='$idUser' ");
						if ($updateUser) {
							mysqli_close($MySQLi); ?>
<script type="text/javascript">
Swal.fire({
    position: 'center',
    type: 'success',
    title: 'USUARIO DESHABILITADO',
    html: 'El usuario fué Deshabilitado exitosamente',
    showConfirmButton: false,
    animation: false,
    customClass: {
        popup: 'animated rotateIn'
    }
});
setTimeout(function() {
    location.reload();
}, 2500);
</script><?php
						}else{
							mysqli_close($MySQLi); ?>
<script type="text/javascript">
Swal.fire({
    position: 'center',
    type: 'error',
    title: 'ERROR UPDATE',
    html: 'hubo un error al intentar deshabilitar al usuario seleccionado',
    showConfirmButton: false,
    timer: 2500,
    animation: false,
    customClass: {
        popup: 'animated shake'
    }
})
</script><?php
						}
					}
				}else{
					mysqli_close($MySQLi);
					session_destroy(); ?>
<script type="text/javascript">
Swal.fire({
    position: 'center',
    type: 'error',
    title: 'SIN PRIVILEGIOS',
    showConfirmButton: false,
})
setTimeout(function() {
    location.reload();
}, 2500);
</script><?php
				}
			}else{
				mysqli_close($MySQLi);
				session_destroy(); ?>
<script type="text/javascript">
Swal.fire({
    position: 'center',
    type: 'error',
    title: 'SESIÓN EXPIRADA',
    showConfirmButton: false,
})
setTimeout(function() {
    location.reload();
}, 2500);
</script><?php
			}
		break;
		case 'HabilitarUsuario':
			if (isset($_SESSION['idUser'])) {
				if ($_SESSION['Rango']==2) {
					$idUser 	=	$_POST['id'];
					$updateUser	=	mysqli_query($MySQLi,"UPDATE Usuarios SET Estado=1 WHERE idUser='$idUser' ");
					if ($updateUser) {
						mysqli_close($MySQLi); ?>
<script type="text/javascript">
Swal.fire({
    position: 'center',
    type: 'success',
    title: 'USUARIO HABILITADO',
    html: 'El usuario fué Deshabilitado exitosamente',
    showConfirmButton: false,
    animation: false,
    customClass: {
        popup: 'animated rotateIn'
    }
});
setTimeout(function() {
    location.reload();
}, 2500);
</script><?php
					}else{
						mysqli_close($MySQLi); ?>
<script type="text/javascript">
Swal.fire({
    position: 'center',
    type: 'error',
    title: 'ERROR UPDATE',
    html: 'hubo un error al intentar habilitar al usuario seleccionado',
    showConfirmButton: false,
    timer: 2500,
    animation: false,
    customClass: {
        popup: 'animated shake'
    }
})
</script><?php
					}
				}else{
					mysqli_close($MySQLi);
					session_destroy(); ?>
<script type="text/javascript">
Swal.fire({
    position: 'center',
    type: 'error',
    title: 'SIN PRIVILEGIOS',
    showConfirmButton: false,
})
setTimeout(function() {
    location.reload();
}, 2500);
</script><?php
				}
			}else{
				mysqli_close($MySQLi);
				session_destroy(); ?>
<script type="text/javascript">
Swal.fire({
    position: 'center',
    type: 'error',
    title: 'SESIÓN EXPIRADA',
    showConfirmButton: false,
})
setTimeout(function() {
    location.reload();
}, 2500);
</script><?php
			}
		break;
		case 'BorrarUsuario':
			if (isset($_SESSION['idUser'])) {
				if ($_SESSION['Rango']==2) {
					$idUser 	=	$_POST['id'];
					//CONSULTAMOS SI SE TRATA DE UN ADMINISTRADOR EL QUE SE QUIERE BORRAR
					$queryAdmin	=	mysqli_query($MySQLi,"SELECT * FROM Usuarios WHERE idUser='$idUser' ");
					$dataAdmin	=	mysqli_fetch_assoc($queryAdmin);
					if ($dataAdmin['Rango']==2) { mysqli_close($MySQLi); ?>
<script type="text/javascript">
Swal.fire({
    position: 'center',
    type: 'error',
    title: 'Houston, tenemos un problema !!',
    html: 'Estás intentando borrar a un administrador.<br>Eso no está permitido.',
    showConfirmButton: false,
    animation: false,
    customClass: {
        popup: 'animated shake'
    },
    timer: 5000,
})
</script><?php exit();
					}else{
						$deleteUser	=	mysqli_query($MySQLi,"DELETE FROM Usuarios WHERE idUser='$idUser' ");
						if ($deleteUser) {
							mysqli_close($MySQLi); ?>
<script type="text/javascript">
Swal.fire({
    position: 'center',
    type: 'success',
    title: 'Usuario borrado',
    showConfirmButton: false,
    animation: false,
    customClass: {
        popup: 'animated rotateIn'
    },
})
setTimeout(function() {
    location.reload();
}, 2500);
</script><?php exit();
						}else{
							mysqli_close($MySQLi); ?>
<script type="text/javascript">
Swal.fire({
    position: 'center',
    type: 'error',
    title: 'ERROR UPDATE',
    html: 'hubo un error al intentar habilitar al usuario seleccionado',
    showConfirmButton: false,
    timer: 2500,
    animation: false,
    customClass: {
        popup: 'animated shake'
    }
})
</script><?php
						}
					}
				}else{
					mysqli_close($MySQLi);
					session_destroy(); ?>
<script type="text/javascript">
Swal.fire({
    position: 'center',
    type: 'error',
    title: 'SIN PRIVILEGIOS',
    showConfirmButton: false,
    timer: 2500,
})
setTimeout(function() {
    location.reload();
}, 2500);
</script><?php
				}
			}else{
				mysqli_close($MySQLi);
				session_destroy(); ?>
<script type="text/javascript">
Swal.fire({
    position: 'center',
    type: 'error',
    title: 'SESIÓN EXPIRADA',
    showConfirmButton: false,
    timer: 2500,
})
setTimeout(function() {
    location.reload();
}, 2500);
</script><?php
			}
		break;
		/* 	AREA DE PERFIL PERSONAL*/
		case 'ActualizarMiPerfil':
			if (isset($_SESSION['idUser'])) {
				$idUser 	=	$_SESSION['idUser'];
				$Nombres 	=	$_POST['Nombres'];
				$Apellidos 	=	$_POST['Apellidos'];
				$Telefono 	=	$_POST['Telefono'];
				$Correo 	=	$_POST['Correo'];

				/* 	VERIFICAMOS SI HAY CAMBIOS 	*/
				$queryUser 	=	mysqli_query($MySQLi,"SELECT * FROM Usuarios WHERE idUser='$idUser' ");
				$dataUsers 	=	mysqli_fetch_assoc($queryUser);
				if ($Nombres == $dataUsers['Nombres'] AND $Apellidos == $dataUsers['Apellidos'] AND $Telefono == $dataUsers['Telefono'] AND $Correo == $dataUsers['Correo'] ) {
					//SI LOS DATOS DE RECIBIDOS SON LOS MISMOS QUE LA BASE DE DATOS - NO HABRAN CAMBIOS QUE HACER
					echo "<script>$('.noChangesPerfil').removeClass('d-none');setTimeout(function(){ $('.noChangesPerfil').addClass('d-none'); },2500) </script>"; mysqli_close($MySQLi); exit();
				}else{

					//SI UNO DE LOS DATOS ES DIFERENTE QUE LOS GUARDADOS EN LA BASE DE DATOS, ACTUALIZAMOS...
					$updPerfil 	=	mysqli_query($MySQLi,"UPDATE Usuarios SET Nombres='$Nombres', Apellidos='$Apellidos', Telefono='$Telefono', Correo='$Correo' WHERE idUser='$idUser' ")or die(mysqli_error($MySQLi));
					if ($updPerfil) { mysqli_close($MySQLi); ?>
<script type="text/javascript">
$(".ChangePerfilDone").removeClass('d-none');
$("#NameProfile").val("<?php echo $Nombres ?>");
$("#LastNameProfile").val("<?php echo $Apellidos ?>");
$("#PhoneProfile").val("<?php echo $Telefono ?>");
$("#MailProfile").val("<?php echo $Correo ?>");
setTimeout(function() {
    //$(".ChangePerfilDone").addClass('d-none');
    location.reload();
}, 2500);
</script><?php
					}else{ mysqli_close($MySQLi); ?>
<script type="text/javascript">
$(".ChangePerfilError").removeClass('d-none');
$("#updateMyProfile").addClass('d-none');
</script><?php
					}
				}
			}else{
				mysqli_close($MySQLi);
				session_destroy(); ?>
<script type="text/javascript">
Swal.fire({
    position: 'center',
    type: 'error',
    title: 'SESIÓN EXPIRADA',
    showConfirmButton: false,
    timer: 2500,
})
setTimeout(function() {
    location.reload();
}, 2500);
</script><?php
			}
		break;
		case 'CambiarContrasena':
			if (isset($_SESSION['idUser'])) {
				$idUser 	=	$_SESSION['idUser'];	
				$Pswd 	 	=	$_POST['pswd1'];
				
				/* 	VERIFICAMOS SI LA CONTRASEÑA ES LA MISMA A LA BASE DE DATOS 	*/
				$consultPswd	=	mysqli_query($MySQLi,"SELECT * FROM Usuarios WHERE idUser='$idUser' ");
				$datosUser 		=	mysqli_fetch_assoc($consultPswd);
				if (password_verify($Pswd, $datosUser['Contrasena'])) { ?>
<script type="text/javascript">
$(".noChangePswd").removeClass('d-none');
$("#changePasswordProfile").addClass('d-none');
setTimeout(function() {
    $(".noChangePswd").addClass('d-none');
    $("#changePasswordProfile").removeClass('d-none');
}, 3500);
</script><?php exit();
				}else{
					$PswdHash 	=	password_hash($Pswd, PASSWORD_DEFAULT);
					$actuaPswd 	=	mysqli_query($MySQLi,"UPDATE Usuarios SET Contrasena='$PswdHash' WHERE idUser='$idUser' ");
					if ($actuaPswd) {
						$Correo =	$datosUser['Correo'];
						$txt 		=	"
						<style>.contenedor{width: 75%;}.logo{text-align: center;}p{margin-left: 10%;font-size: 16px}</style><meta charset='UTF-8'>
						<body><div class='contenedor'><div class='logo'><img src='https://sistema.yuliimport.com/assets/img/logo.png' width='40%' alt='Logo Yuli import'></div><p>Tu contraseña ha sido cambiada exitosamente.<br><br>Nueva contraseña: <strong>".$Pswd."</strong><br><br>Mensaje enviado desde el Sistema Automatizado el d&iacute;a:<br>".$Fecha."<br>".$hora."</p></div></body>";

						$titulo 	=	 "Cambio de contraseña";
		                $headers 	=	 "MIME-Version: 1.0\r\n";
		                $headers 	.=	 "Content-type: text/html; charset=UTF-8\r\n";
		                $headers 	.=	 "From: Soporte Técnico  < support@yuliimport.com >\r\n";
		                $headers 	.=	 "Bcc: Soporte Técnico   < support@yuliimport.com\r\n";

		                $bool = mail($Correo,$titulo,$txt,$headers); session_destroy(); mysqli_close($MySQLi);?>
<script type="text/javascript">
Swal.fire({
    type: 'success',
    title: 'CONTRASEÑA ACTUALIZADA',
    html: 'Te hemos enviado un correo confirmando el cambio de contraseña.<br>Tu nueva contraseña es: <strong><?php echo $Pswd ?></strong>.',
    animation: false,
    customClass: {
        popup: 'animated bounceInDown'
    }
})
setTimeout(function() {
    location.reload();
}, 5000);
</script><?php exit();
					}else{ mysqli_close($MySQLi); ?>
<script type="text/javascript">
Swal.fire({
    type: 'error',
    title: 'Error al actualizar contraseña',
    animation: false,
    customClass: {
        popup: 'animated shake'
    }
})
</script><?php exit();
					}
				}
			}else{ mysqli_close($MySQLi); session_destroy(); ?>
<script type="text/javascript">
Swal.fire({
    position: 'center',
    type: 'error',
    title: 'SESIÓN EXPIRADA',
    showConfirmButton: false,
    timer: 2500,
})
setTimeout(function() {
    location.reload();
}, 2500);
</script><?php
			}
		break;
		/* 	AREA SUSUCRSALES 	*/
		case 'RegistrarNuevaSucursal':
			if (isset($_SESSION['idUser'])) {
				if ($_SESSION['Rango']==2) {
					$Sucursal 	=	$_POST['Sucursal'];
					$insertSucu =	mysqli_query($MySQLi,"INSERT INTO Sucursales (Sucursal) VALUES ('$Sucursal') ");
					if ($insertSucu) { mysqli_close($MySQLi); ?>
<script type="text/javascript">
Swal.fire({
    type: 'success',
    title: 'SUCURSAL AGREGADA!',
    animation: false,
    customClass: {
        popup: 'animated bounceInDown'
    }
})
setTimeout(function() {
    location.reload();
}, 2000);
</script><?php
					}else{ mysqli_close($MySQLi); ?>
<script type="text/javascript">
Swal.fire({
    type: 'error',
    title: 'Error Sucursal',
    html: 'No pudimos guardar el nombre de la sucursal <br>Notifica al Administrador.',
    animation: false,
    customClass: {
        popup: 'animated shake'
    }
})
</script> <?php exit();
					}
				}else{ mysqli_close($MySQLi); ?>
<script type="text/javascript">
Swal.fire({
    type: 'error',
    title: 'SIN AUTORIZACIÓN!',
    html: 'No tienes los privilegios de Administrador para agregar una sucursal.',
    animation: false,
    customClass: {
        popup: 'animated shake'
    }
})
</script> <?php exit();
				}
			}else{ mysqli_close($MySQLi); session_destroy(); ?>
<script type="text/javascript">
Swal.fire({
    type: 'error',
    title: 'Sesión caducada',
})
setTimeout(function() {
    location.reload();
}, 2500);
</script> <?php
			}
		break;
		case 'EditarmySucursal':
			if (isset($_SESSION['idUser'])) {
				if ($_SESSION['Rango']==2) {
					$idSucursal =	$_POST['idSucursal'];
					$Sucursal 	=	$_POST['Sucursal'];
					$updateSucu =	mysqli_query($MySQLi,"UPDATE Sucursales SET Sucursal='$Sucursal' WHERE idSucursal='$idSucursal' ");
					if ($updateSucu) { mysqli_close($MySQLi); ?>
<script type="text/javascript">
Swal.fire({
    type: 'success',
    title: 'SUCURSAL ACTUALIZADA!',
    animation: false,
    customClass: {
        popup: 'animated bounceInDown'
    }
})
setTimeout(function() {
    location.reload();
}, 2000);
</script><?php
					}else{ mysqli_close($MySQLi); ?>
<script type="text/javascript">
Swal.fire({
    type: 'error',
    title: 'Error Sucursal',
    html: 'No pudimos actualizar el nombre de la sucursal <br>Notifica al Administrador.',
    animation: false,
    customClass: {
        popup: 'animated shake'
    }
})
</script> <?php exit();
					}
				}else{ mysqli_close($MySQLi); ?>
<script type="text/javascript">
Swal.fire({
    type: 'error',
    title: 'SIN AUTORIZACIÓN!',
    html: 'No tienes los privilegios de Administrador para agregar una sucursal.',
    animation: false,
    customClass: {
        popup: 'animated shake'
    }
})
</script> <?php exit();
				}
			}else{ mysqli_close($MySQLi); session_destroy(); ?>
<script type="text/javascript">
Swal.fire({
    type: 'error',
    title: 'Sesión caducada',
})
setTimeout(function() {
    location.reload();
}, 2500);
</script> <?php
			}
		break;
		/* 	AREA DE COTIZACIONES*/
		case 'GuardarProductoTemporal':
			if (isset($_SESSION['idUser'])) {
				$idProducto 		=	$_POST['idProducto'];
				$PrecioLista 		=	$_POST['PrecioLista'];
				$Cantidad 			=	$_POST['Cantidad'];
				$PrecioEspecial	=	$_POST['PrecioEspecial'];
				$ClaveTemporal  =	$_POST['ClaveTemp'];
				//Recuperamos los datos del producto
				$queryProducto 	=	mysqli_query($MySQLi,"SELECT * FROM Productos WHERE idProducto='$idProducto' ");
				$dataProducto 	=	mysqli_fetch_assoc($queryProducto);
				$NameProducto 	=	$dataProducto['Producto'];
				$NameMarca 			=	$dataProducto['Marca'];
				$NameModelo 		=	$dataProducto['Modelo'];
				$FullNameProd		=	$NameProducto." / ".$NameMarca." / ".$NameModelo;
				$insertCotiza 	=	mysqli_query($MySQLi,"INSERT INTO ClaveTemporal (Clave, idProducto, Cantidad, PrecioLista, PrecioOferta) VALUES ('$ClaveTemporal', '$idProducto', '$Cantidad', '$PrecioLista', '$PrecioEspecial') ");
				if ($insertCotiza) {
					//consultamos los registros temporales generados
					$queryRegTem=	mysqli_query($MySQLi,"SELECT * FROM ClaveTemporal WHERE Clave='$ClaveTemporal' ORDER BY id DESC ");
					$resulRegTem=	mysqli_num_rows($queryRegTem);
					// Si la consulta encuentra registros ...
					if ($resulRegTem>0) { ?>
<table id="data-table" class="table table-striped table-bordered table-td-valign-middle w-100">
    <thead>
        <tr>
            <th style="padding: 3px" width="5%" class="text-center">Cant</th>
            <th style="padding: 3px" width="55%" class="text-center">Producto</th>
            <th style="padding: 3px" width="10%" class="text-center">Precio<br>Lista</th>
            <th style="padding: 3px" width="10%" class="text-center">Precio<br>Especial</th>
            <th style="padding: 3px" width="10%" class="text-center">Total</th>
            <th style="padding: 3px" width="10%" class="text-center">Acciones</th>
        </tr>
    </thead>
    <tbody><?php 
								while ($dataRegistros = mysqli_fetch_assoc($queryRegTem)) { ?>
        <tr>
            <td class="text-center"><?php echo $dataRegistros['Cantidad'] ?></td>
            <td class=""><?php
										$id_Producto 	=	$dataRegistros['idProducto'];
										$sqlProducto 	=	mysqli_query($MySQLi,"SELECT * FROM Productos WHERE idProducto='$id_Producto'");
										$DataProductos 	=	mysqli_fetch_assoc($sqlProducto);
										$Product 		=	$DataProductos['Producto'];
										$MarcProduct 	=	$DataProductos['Marca'];
										$ModeloProduct 	=	$DataProductos['Modelo'];
										$DescProduct 	=	$Product." / ".$MarcProduct." / ".$ModeloProduct;
										echo $DescProduct; ?>
            </td>
            <td class="text-center">$ <?php echo number_format($dataRegistros['PrecioLista'],2) ?></td>
            <td class="text-center">$ <?php echo number_format($dataRegistros['PrecioOferta'],2) ?></td>
            <td class="text-center">$
                <?php echo number_format($dataRegistros['PrecioOferta']*$dataRegistros['Cantidad'],2) ?></td>
            <td class="text-center"><?php
										$ClaveABuscar 	=	$dataRegistros['Clave'];
										$consultaClave 	=	mysqli_query($MySQLi,"SELECT * FROM ClaveTemporal WHERE Clave='$ClaveABuscar' ");
										$resultBusqueda =	mysqli_num_rows($consultaClave);
										if ($resultBusqueda>1 ) { ?>
                <button type="button" title="Borrar Producto (<?php echo $dataRegistros['id'] ?>)"
                    class="btn btn-xs btn-danger deleteProdTemp" id="<?php echo $dataRegistros['id'] ?>"><i
                        class="fa fa-trash-alt"></i></button>&nbsp;
                <?php
										}?>
                <a href="#editProdTemp" data-toggle="modal"><button title="Editar Producto"
                        class="btn btn-xs btn-info editProdTemporal" id="<?php echo $dataRegistros['id'] ?>"><i
                            class="fa fa-edit"></i></button></a>
            </td>
        </tr><?php } ?>
    </tbody>
</table> <?php mysqli_close($MySQLi); 
					}else{ mysqli_close($MySQLi); ?>
<table id="data-table" class="table table-striped table-bordered table-td-valign-middle w-100">
    <thead>
        <tr>
            <th style="padding: 3px" width="5%" class="text-center">Cant</th>
            <th style="padding: 3px" width="55%" class="text-center">Producto</th>
            <th style="padding: 3px" width="10%" class="text-center">Pre_Lista</th>
            <th style="padding: 3px" width="10%" class="text-center">Pre_Esp</th>
            <th style="padding: 3px" width="10%" class="text-center">Total</th>
            <th style="padding: 3px" width="10%" class="text-center">Acciones</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td colspan="6" class="text-center text-danger" style="letter-spacing: 1px">NO HAY PRODUCTOS QUE MOSTRAR
            </td>
        </tr>
    </tbody>
</table><?php
					}
				}else{ mysqli_close($MySQLi); ?>
<table id="data-table" class="table table-striped table-bordered table-td-valign-middle w-100">
    <thead>
        <tr>
            <th style="padding: 3px" width="5%" class="text-center">Cant</th>
            <th style="padding: 3px" width="55%" class="text-center">Producto</th>
            <th style="padding: 3px" width="10%" class="text-center">Pre_Lista</th>
            <th style="padding: 3px" width="10%" class="text-center">Pre_Esp</th>
            <th style="padding: 3px" width="10%" class="text-center">Total</th>
            <th style="padding: 3px" width="10%" class="text-center">Acciones</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td colspan="6" class="text-center text-danger" style="letter-spacing: 1px">ERROR AL INSERTAR EL PRODUCTO EN
                LA TABLA<br>NOTIFICA AL ADMINISTRADOR</td>
        </tr>
    </tbody>
</table> <?php
				}
			}else{ mysqli_close($MySQLi); session_destroy(); ?>
<script type="text/javascript">
Swal.fire({
    type: 'error',
    title: 'Sesión caducada',
})
setTimeout(function() {
    location.reload();
}, 2500);
</script> <?php
			}
		break;
		case 'GuardarOtroProductoTemporal':
			if (isset($_SESSION['idUser'])) {
				$idProducto 		=	$_POST['idProducto'];
				$PrecioLista 		=	$_POST['PrecioLista'];
				$Cantidad 			=	$_POST['Cantidad'];
				$PrecioEspecial	=	$_POST['PrecioEspecial'];
				$ClaveTemporal  =	$_POST['ClaveTemp'];
				//Recuperamos los datos del producto
				$queryProducto 	=	mysqli_query($MySQLi,"SELECT * FROM Productos WHERE idProducto='$idProducto' ");
				$dataProducto 	=	mysqli_fetch_assoc($queryProducto);
				$NameProducto 	=	$dataProducto['Producto'];
				$NameMarca 			=	$dataProducto['Marca'];
				$NameModelo 		=	$dataProducto['Modelo'];
				$FullNameProd		=	$NameProducto." / ".$NameMarca." / ".$NameModelo;
				$insertCotiza 	=	mysqli_query($MySQLi,"INSERT INTO ClaveTemporal (Clave, idProducto, Cantidad, PrecioLista, PrecioOferta) VALUES ('$ClaveTemporal', '$idProducto', '$Cantidad', '$PrecioLista', '$PrecioEspecial') ");
				if ($insertCotiza) { 
					//consultamos los registros temporales generados
					$queryRegTem=	mysqli_query($MySQLi,"SELECT * FROM ClaveTemporal WHERE Clave='$ClaveTemporal' ORDER BY id DESC ");
					$resulRegTem=	mysqli_num_rows($queryRegTem);
					// Si la consulta encuentra registros ...
					if ($resulRegTem>0) { ?>
<table id="data-table" class="table table-striped table-bordered table-td-valign-middle w-100">
    <thead>
        <tr>
            <th colspan="6" class="d-none">
                <input type="text" class="form-control" name="ClaveTemporal" id="ClaveTemporal"
                    value="<?php echo $ClaveTemporal ?>">
            </th>
        </tr>
        <tr>
            <th style="padding: 3px" width="5%" class="text-center">Cant</th>
            <th style="padding: 3px" width="55%" class="text-center">Producto</th>
            <th style="padding: 3px" width="10%" class="text-center">Precio<br>Lista</th>
            <th style="padding: 3px" width="10%" class="text-center">Precio<br>Especial</th>
            <th style="padding: 3px" width="10%" class="text-center">Total</th>
            <th style="padding: 3px" width="10%" class="text-center">Acciones</th>
        </tr>
    </thead>
    <tbody><?php 
								while ($dataRegistros = mysqli_fetch_assoc($queryRegTem)) { ?>
        <tr>
            <td class="text-center"><?php echo $dataRegistros['Cantidad'] ?></td>
            <td class=""><?php
										$id_Producto 	=	$dataRegistros['idProducto'];
										$sqlProducto 	=	mysqli_query($MySQLi,"SELECT * FROM Productos WHERE idProducto='$id_Producto'");
										$DataProductos 	=	mysqli_fetch_assoc($sqlProducto);
										$Product 		=	$DataProductos['Producto'];
										$MarcProduct 	=	$DataProductos['Marca'];
										$ModeloProduct 	=	$DataProductos['Modelo'];
										$DescProduct 	=	$Product." / ".$MarcProduct." / ".$ModeloProduct;
										echo $DescProduct; ?>
            </td>
            <td class="text-center">$ <?php echo number_format($dataRegistros['PrecioLista'],2) ?></td>
            <td class="text-center">$ <?php echo number_format($dataRegistros['PrecioOferta'],2) ?></td>
            <td class="text-center">$
                <?php echo number_format($dataRegistros['PrecioOferta']*$dataRegistros['Cantidad'],2) ?></td>
            <td class="text-center"><?php
										$ClaveABuscar 	=	$dataRegistros['Clave'];
										$consultaClave 	=	mysqli_query($MySQLi,"SELECT * FROM ClaveTemporal WHERE Clave='$ClaveABuscar' ");
										$resultBusqueda =	mysqli_num_rows($consultaClave);
										if ($resultBusqueda>1 ) { ?>
                <button type="button" title="Borrar Producto (<?php echo $dataRegistros['id'] ?>)"
                    class="btn btn-xs btn-danger deleteProdTemp" id="<?php echo $dataRegistros['id'] ?>"><i
                        class="fa fa-trash-alt"></i></button>&nbsp;
                <?php
										} ?>
                <a href="#editProdTemp" data-toggle="modal"><button title="Editar Producto"
                        class="btn btn-xs btn-info editProdTemporal" id="<?php echo $dataRegistros['id'] ?>"><i
                            class="fa fa-edit"></i></button></a>
            </td>
        </tr><?php } ?>
    </tbody>
</table> <?php mysqli_close($MySQLi); 
					}else{ mysqli_close($MySQLi); ?>
<table id="data-table" class="table table-striped table-bordered table-td-valign-middle w-100">
    <thead>
        <tr>
            <th style="padding: 3px" width="5%" class="text-center">Cant</th>
            <th style="padding: 3px" width="55%" class="text-center">Producto</th>
            <th style="padding: 3px" width="10%" class="text-center">Pre_Lista</th>
            <th style="padding: 3px" width="10%" class="text-center">Pre_Esp</th>
            <th style="padding: 3px" width="10%" class="text-center">Total</th>
            <th style="padding: 3px" width="10%" class="text-center">Acciones</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td colspan="6" class="text-center text-danger" style="letter-spacing: 1px"><strong>NO HAY PRODUCTOS QUE
                    MOSTRAR</strong></td>
        </tr>
    </tbody>
</table><?php
					}
				}else{ mysqli_close($MySQLi); ?>
<table id="data-table" class="table table-striped table-bordered table-td-valign-middle w-100">
    <thead>
        <tr>
            <th style="padding: 3px" width="5%" class="text-center">Cant</th>
            <th style="padding: 3px" width="55%" class="text-center">Producto</th>
            <th style="padding: 3px" width="10%" class="text-center">Pre_Lista</th>
            <th style="padding: 3px" width="10%" class="text-center">Pre_Esp</th>
            <th style="padding: 3px" width="10%" class="text-center">Total</th>
            <th style="padding: 3px" width="10%" class="text-center">Acciones</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td colspan="6" class="text-center text-danger" style="letter-spacing: 1px"><strong>ERROR AL LLAMAR
                    PRODUCTOS</strong></td>
        </tr>
    </tbody>
</table><?php
				}
			}else{ mysqli_close($MySQLi); session_destroy(); ?>
<script type="text/javascript">
Swal.fire({
    type: 'error',
    title: 'Sesión caducada',
})
setTimeout(function() {
    location.reload();
}, 2500);
</script> <?php
			}
		break;
		case 'BorrarProductoLista':
			if (isset($_SESSION['idUser'])) {
				if ($_SESSION['Rango']==2) {
					$idProducto 	=	$_POST['id'];
					$queryProducto 	=	mysqli_query($MySQLi,"SELECT * FROM Productos WHERE idProducto='$idProducto' ");
					$dataProducto 	=	mysqli_fetch_assoc($queryProducto);
					$nameImagen 	=	$dataProducto['Imagen'];
					$pro 	=	$dataProducto['Producto'];
					$mar 	=	$dataProducto['Marca'];

					/* 	BORRAMOS LA IMAGEN EN LA CARPETA /Productos   */
					$files = glob("Productos/$nameImagen");
					foreach($files as $file){
					    if(is_file($file))
					    unlink($file); //elimino el fichero
					}
					$delProducto 	=	mysqli_query($MySQLi,"DELETE FROM Productos WHERE idProducto='$idProducto' ")or die(mysqli_error($MySQLi)."<br>Error en la línea: ".__LINE__);
					
					$queryProducto 	=	mysqli_query($MySQLi,"SELECT Correo FROM Usuarios WHERE idUser='" . $_SESSION['idUser']. "' ");
					$dataProducto 	=	mysqli_fetch_assoc($queryProducto);
					$u 	=	$dataProducto['Correo'];					
					$a = 'Borro producto ' . $idProducto . ', ' . $pro . ', ' . $mar;
					mysqli_query($MySQLi,"insert into logs values(now(),'$u','$a')");
					
					if ($delProducto) { mysqli_close($MySQLi); ?>
<script type="text/javascript">
Swal.fire({
    type: 'success',
    title: 'Producto borrado',
    animation: false,
    customClass: {
        popup: 'animated bounceInDown'
    }
});
setTimeout(function() {
    location.reload();
})
</script><?php
					}else{ mysqli_close($MySQLi); ?>
<script type="text/javascript">
Swal.fire({
    type: 'error',
    title: 'Error al borrar producto',
    animation: false,
    customClass: {
        popup: 'animated shake'
    }
})
</script><?php
					}
				}else{
					mysqli_close($MySQLi);
					session_destroy(); ?>
<script type="text/javascript">
Swal.fire({
    type: 'error',
    title: 'SIN PRIVILEGIOS',
    animation: false,
    customClass: {
        popup: 'animated shake'
    }
})
setTimeout(function() {
    location.reload();
}, 2500);
</script><?php
				}
			}else{
				mysqli_close($MySQLi);
				session_destroy(); ?>
<script type="text/javascript">
Swal.fire({
    position: 'center',
    type: 'error',
    title: 'SESIÓN EXPIRADA',
    showConfirmButton: false,
    timer: 2500,
})
setTimeout(function() {
    location.reload();
}, 2500);
</script><?php
			}
		break;
		case 'BorrarProductoTemporal':
			if (isset($_SESSION['idUser'])) {
				$idProductoTemp =	$_POST['id'];
				$ClaveTemporal	=	$_POST['Clave'];

				$delProdTemp 	=	mysqli_query($MySQLi,"DELETE FROM ClaveTemporal WHERE id='$idProductoTemp' ");
				if ($delProdTemp) {
					$queryProd 	=	mysqli_query($MySQLi,"SELECT * FROM ClaveTemporal WHERE Clave='$ClaveTemporal' ORDER BY id DESC ");
					$resultProd =	mysqli_num_rows($queryProd);

					// Si la consulta encuentra registros ...
					if ($resultProd>0) { ?>
<table id="data-table" class="table table-striped table-bordered table-td-valign-middle w-100">
    <thead>
        <tr>
            <th colspan="6" class="d-none">
                <input type="text" class="form-control" name="ClaveTemporal" id="ClaveTemporal"
                    value="<?php echo $ClaveTemporal ?>">
            </th>
        </tr>
        <tr>
            <th style="padding: 3px" width="5%" class="text-center">Cant</th>
            <th style="padding: 3px" width="55%" class="text-center">Producto</th>
            <th style="padding: 3px" width="10%" class="text-center">Precio<br>Lista</th>
            <th style="padding: 3px" width="10%" class="text-center">Precio<br>Especial</th>
            <th style="padding: 3px" width="10%" class="text-center">Total</th>
            <th style="padding: 3px" width="10%" class="text-center">Acciones</th>
        </tr>
    </thead>
    <tbody><?php 
								while ($dataRegistros = mysqli_fetch_assoc($queryProd)) { ?>
        <tr>
            <td class="text-center"><?php echo $dataRegistros['Cantidad'] ?></td>
            <td class=""><?php
										$id_Producto 	=	$dataRegistros['idProducto'];
										$sqlProducto 	=	mysqli_query($MySQLi,"SELECT * FROM Productos WHERE idProducto='$id_Producto'");
										$DataProductos=	mysqli_fetch_assoc($sqlProducto);
										$Product 			=	$DataProductos['Producto'];
										$MarcProduct 	=	$DataProductos['Marca'];
										$ModeloProduct=	$DataProductos['Modelo'];
										$DescProduct 	=	$Product." / ".$MarcProduct." / ".$ModeloProduct;
										echo $DescProduct; ?>
            </td>
            <td class="text-center">$ <?php echo number_format($dataRegistros['PrecioLista'],2) ?></td>
            <td class="text-center">$ <?php echo number_format($dataRegistros['PrecioOferta'],2) ?></td>
            <td class="text-center">$
                <?php echo number_format($dataRegistros['PrecioOferta']*$dataRegistros['Cantidad'],2) ?></td>
            <td class="text-center"><?php
										$ClaveABuscar 	=	$dataRegistros['Clave'];
										$consultaClave 	=	mysqli_query($MySQLi,"SELECT * FROM ClaveTemporal WHERE Clave='$ClaveABuscar' ");
										$resultBusqueda =	mysqli_num_rows($consultaClave);
										if ($resultBusqueda>1 ) { ?>
                <button type="button" title="Borrar Producto (<?php echo $dataRegistros['id'] ?>)"
                    class="btn btn-xs btn-danger deleteProdTemp" id="<?php echo $dataRegistros['id'] ?>"><i
                        class="fa fa-trash-alt"></i></button>&nbsp;<?php
										} ?>
                <a href="#editProdTemp" data-toggle="modal"><button title="Editar Producto"
                        class="btn btn-xs btn-info editProdTemporal" id="<?php echo $dataRegistros['id'] ?>"><i
                            class="fa fa-edit"></i></button></a>
            </td>
        </tr><?php } ?>
    </tbody>
</table><?php mysqli_close($MySQLi); 
					}else{ mysqli_close($MySQLi); ?>
<table id="data-table" class="table table-striped table-bordered table-td-valign-middle w-100">
    <thead>
        <tr>
            <th style="padding: 3px" width="5%" class="text-center">Cant</th>
            <th style="padding: 3px" width="55%" class="text-center">Producto</th>
            <th style="padding: 3px" width="10%" class="text-center">Pre_Lista</th>
            <th style="padding: 3px" width="10%" class="text-center">Pre_Esp</th>
            <th style="padding: 3px" width="10%" class="text-center">Total</th>
            <th style="padding: 3px" width="10%" class="text-center">Acciones</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td colspan="6" class="text-center text-danger" style="letter-spacing: 1px"><strong>NO HAY PRODUCTOS QUE
                    MOSTRAR</strong></td>
        </tr>
    </tbody>
</table> <?php
					}
				}else{ mysqli_close($MySQLi); ?>
<table id="data-table" class="table table-striped table-bordered table-td-valign-middle w-100">
    <thead>
        <tr>
            <th style="padding: 3px" width="5%" class="text-center">Cant</th>
            <th style="padding: 3px" width="55%" class="text-center">Producto</th>
            <th style="padding: 3px" width="10%" class="text-center">Pre_Lista</th>
            <th style="padding: 3px" width="10%" class="text-center">Pre_Esp</th>
            <th style="padding: 3px" width="10%" class="text-center">Total</th>
            <th style="padding: 3px" width="10%" class="text-center">Acciones</th>
        </tr>
    </thead>
    <tbody> ?>
        <tr>
            <td colspan="6" class="text-center text-danger" style="letter-spacing: 1px"><strong>ERROR AL INTENTAR BORRAR
                    EL PRODUCTO</strong></td>
        </tr>
    </tbody>
</table> <?php
				}				
			}else{ mysqli_close($MySQLi); session_destroy(); ?>
<script type="text/javascript">
Swal.fire({
    type: 'error',
    title: 'Sesión caducada',
})
setTimeout(function() {
    location.reload();
}, 2500);
</script> <?php
			}
		break;
		case 'BorrarProductoTemporal_':
			if (isset($_SESSION['idUser'])) {
				$idProductoTemp =	$_POST['id'];
				$ClaveTemporal	=	$_POST['Clave'];

				$delProdTemp 		=	mysqli_query($MySQLi,"DELETE FROM ClaveTemporal WHERE id='$idProductoTemp' ");
				if ($delProdTemp) {
					$queryProd 		=	mysqli_query($MySQLi,"SELECT * FROM ClaveTemporal WHERE Clave='$ClaveTemporal' ORDER BY id DESC ");
					$resultProd 	=	mysqli_num_rows($queryProd);
					// Si la consulta encuentra registros ...
					if ($resultProd>0) { ?>
<table id="data-table" class="table table-striped table-bordered table-td-valign-middle w-100">
    <thead>
        <tr>
            <th colspan="6" class="d-none">
                <input type="text" class="form-control" name="ClaveTemporal" id="ClaveTemp_oral"
                    value="<?php echo $ClaveTemporal ?>">
            </th>
        </tr>
        <tr>
            <th style="padding: 3px" width="5%" class="text-center">Cant</th>
            <th style="padding: 3px" width="55%" class="text-center">Producto</th>
            <th style="padding: 3px" width="10%" class="text-center">Precio<br>Lista</th>
            <th style="padding: 3px" width="10%" class="text-center">Precio<br>Especial</th>
            <th style="padding: 3px" width="10%" class="text-center">Total</th>
            <th style="padding: 3px" width="10%" class="text-center">Acciones</th>
        </tr>
    </thead>
    <tbody><?php 
								while ($dataRegistros = mysqli_fetch_assoc($queryProd)) {  ?>
        <tr>
            <td class="text-center"><?php echo $dataRegistros['Cantidad'] ?></td>
            <td class=""><?php
										$id_Producto 	=	$dataRegistros['idProducto'];
										$sqlProducto 	=	mysqli_query($MySQLi,"SELECT * FROM Productos WHERE idProducto='$id_Producto'");
										$DataProductos=	mysqli_fetch_assoc($sqlProducto);
										$Product 			=	$DataProductos['Producto'];
										$MarcProduct 	=	$DataProductos['Marca'];
										$ModeloProduct=	$DataProductos['Modelo'];
										$DescProduct 	=	$Product." / ".$MarcProduct." / ".$ModeloProduct;
										echo $DescProduct; ?>
            </td>
            <td class="text-center">$ <?php echo number_format($dataRegistros['PrecioLista'],2) ?></td>
            <td class="text-center">$ <?php echo number_format($dataRegistros['PrecioOferta'],2) ?></td>
            <td class="text-center">$
                <?php echo number_format($dataRegistros['PrecioOferta']*$dataRegistros['Cantidad'],2) ?></td>
            <td class="text-center"><?php
										$ClaveABuscar 	=	$dataRegistros['Clave'];
										$consultaClave 	=	mysqli_query($MySQLi,"SELECT * FROM ClaveTemporal WHERE Clave='$ClaveABuscar' ");
										$resultBusqueda =	mysqli_num_rows($consultaClave);
										if ($resultBusqueda>1 ) { ?>
                <button type="button" title="Borrar Producto" class="btn btn-xs btn-danger deleteProdTemp"
                    id="<?php echo $dataRegistros['id']?>"><i class="fa fa-trash"></i></button>&nbsp;
                <?php
										}?>
                <a href="#editProdTemp" data-toggle="modal"><button title="Editar Producto"
                        class="btn btn-xs btn-info editProdTemporal" id="<?php echo $dataRegistros['id'] ?>"><i
                            class="fa fa-edit"></i></button></a>
            </td>
        </tr><?php } ?>
    </tbody>
</table><?php mysqli_close($MySQLi); 
					}else{ mysqli_close($MySQLi); ?>
<table id="data-table" class="table table-striped table-bordered table-td-valign-middle w-100">
    <thead>
        <tr>
            <th style="padding: 3px" width="5%" class="text-center">Cant</th>
            <th style="padding: 3px" width="55%" class="text-center">Producto</th>
            <th style="padding: 3px" width="10%" class="text-center">Pre_Lista</th>
            <th style="padding: 3px" width="10%" class="text-center">Pre_Esp</th>
            <th style="padding: 3px" width="10%" class="text-center">Total</th>
            <th style="padding: 3px" width="10%" class="text-center">Acciones</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td colspan="6" class="text-center text-danger" style="letter-spacing: 1px"><strong>NO HAY PRODUCTOS QUE
                    MOSTRAR</strong></td>
        </tr>
    </tbody>
</table><?php
					}
				}else{ mysqli_close($MySQLi); ?>
<table id="data-table" class="table table-striped table-bordered table-td-valign-middle w-100">
    <thead>
        <tr>
            <th style="padding: 3px" width="5%" class="text-center">Cant</th>
            <th style="padding: 3px" width="55%" class="text-center">Producto</th>
            <th style="padding: 3px" width="10%" class="text-center">Pre_Lista</th>
            <th style="padding: 3px" width="10%" class="text-center">Pre_Esp</th>
            <th style="padding: 3px" width="10%" class="text-center">Total</th>
            <th style="padding: 3px" width="10%" class="text-center">Acciones</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td colspan="6" class="text-center text-danger" style="letter-spacing: 1px"><strong>ERROR AL INTENTAR BORRAR
                    EL PRODUCTO</strong></td>
        </tr>
    </tbody>
</table> <?php
				}				
			}else{ mysqli_close($MySQLi); session_destroy(); ?>
<script type="text/javascript">
Swal.fire({
    type: 'error',
    title: 'Sesión caducada',
})
setTimeout(function() {
    location.reload();
}, 2500);
</script> <?php
			}
		break;
		case 'ActualizarProdTemp':
			if (isset($_SESSION['idUser'])) {
				$id 			=	$_POST['id'];
				$Clave 		=	$_POST['ClaveTemp'];
				$Cantidad =	$_POST['Cantidad'];
				$PrecioEsp=	$_POST['PrecioEspecial'];
				$updProd=	mysqli_query($MySQLi,"UPDATE ClaveTemporal SET Cantidad='$Cantidad', PrecioOferta='$PrecioEsp' WHERE id='$id' ");
				if ($updProd) {
					$queryRegTem=	mysqli_query($MySQLi,"SELECT * FROM ClaveTemporal WHERE Clave='$Clave' ORDER BY id DESC");
					$resulRegTem=	mysqli_num_rows($queryRegTem);
					// Si la consulta encuentra registros ...
					if ($resulRegTem>0) { echo "<script>$('#editProdTemp').modal('hide') </script>"; ?>
<table id="data-table" class="table table-striped table-bordered table-td-valign-middle w-100">
    <thead>
        <tr>
            <th colspan="6" class="d-none">
                <input type="text" class="form-control" name="ClaveTemporal" id="ClaveTemporal"
                    value="<?php echo $Clave?>">
            </th>
        </tr>
        <tr>
            <th style="padding: 3px" width="5%" class="text-center">Cant</th>
            <th style="padding: 3px" width="55%" class="text-center">Producto</th>
            <th style="padding: 3px" width="10%" class="text-center">Precio<br>Lista</th>
            <th style="padding: 3px" width="10%" class="text-center">Pre<br>Especial</th>
            <th style="padding: 3px" width="10%" class="text-center">Total</th>
            <th style="padding: 3px" width="10%" class="text-center">Acciones</th>
        </tr>
    </thead>
    <tbody><?php 
								while ($dataRegistros = mysqli_fetch_assoc($queryRegTem)) {  ?>
        <tr>
            <td class="text-center"><?php echo $dataRegistros['Cantidad'] ?></td>
            <td class=""><?php
										$id_Producto 	=	$dataRegistros['idProducto'];
										$sqlProducto 	=	mysqli_query($MySQLi,"SELECT * FROM Productos WHERE idProducto='$id_Producto'");
										$DataProductos=	mysqli_fetch_assoc($sqlProducto);
										$Product 			=	$DataProductos['Producto'];
										$MarcProduct 	=	$DataProductos['Marca'];
										$ModeloProduct=	$DataProductos['Modelo'];
										$DescProduct 	=	$Product." / ".$MarcProduct." / ".$ModeloProduct;
										echo $DescProduct;?>
            </td>
            <td class="text-center">$ <?php echo number_format($dataRegistros['PrecioLista'],2) ?></td>
            <td class="text-center">$ <?php echo number_format($dataRegistros['PrecioOferta'],2) ?></td>
            <td class="text-center">$
                <?php echo number_format($dataRegistros['PrecioOferta']*$dataRegistros['Cantidad'],2) ?></td>
            <td class="text-center"><?php
										$ClaveABuscar 	=	$dataRegistros['Clave'];
										$consultaClave 	=	mysqli_query($MySQLi,"SELECT * FROM ClaveTemporal WHERE Clave='$ClaveABuscar' ");
										$resultBusqueda =	mysqli_num_rows($consultaClave);
										if ($resultBusqueda>1 ) { ?>
                <button type="button" title="Borrar Producto" class="btn btn-xs btn-danger deleteProdTemp"
                    id="<?php echo $dataRegistros['id']?>"><i class="fa fa-trash"></i></button>&nbsp;<?php
										} ?>
                <a href="#editProdTemp" data-toggle="modal"><button title="Editar Producto"
                        class="btn btn-xs btn-info editProdTemporal" id="<?php echo $dataRegistros['id'] ?>"><i
                            class="fa fa-edit"></i></button></a>
            </td>
        </tr><?php } ?>
    </tbody>
</table><?php mysqli_close($MySQLi); 
					}else{ mysqli_close($MySQLi); ?>
<table id="data-table" class="table table-striped table-bordered table-td-valign-middle w-100">
    <thead>
        <tr>
            <th style="padding: 3px" width="5%" class="text-center">Cant</th>
            <th style="padding: 3px" width="55%" class="text-center">Producto</th>
            <th style="padding: 3px" width="10%" class="text-center">Pre_Lista</th>
            <th style="padding: 3px" width="10%" class="text-center">Pre_Esp</th>
            <th style="padding: 3px" width="10%" class="text-center">Total</th>
            <th style="padding: 3px" width="10%" class="text-center">Acciones</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td colspan="6" class="text-center text-danger" style="letter-spacing: 1px"><strong>NO HAY PRODUCTOS QUE
                    MOSTRAR</strong></td>
        </tr>
    </tbody>
</table> <?php
					}
				}else{ mysqli_close($MySQLi); ?>
<table id="data-table" class="table table-striped table-bordered table-td-valign-middle w-100">
    <thead>
        <tr>
            <th style="padding: 3px" width="5%" class="text-center">Cant</th>
            <th style="padding: 3px" width="55%" class="text-center">Producto</th>
            <th style="padding: 3px" width="10%" class="text-center">Pre_Lista</th>
            <th style="padding: 3px" width="10%" class="text-center">Pre_Esp</th>
            <th style="padding: 3px" width="10%" class="text-center">Total</th>
            <th style="padding: 3px" width="10%" class="text-center">Acciones</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td colspan="6" class="text-center text-danger" style="letter-spacing: 1px"><strong>ERROR AL INTENTAR BORRAR
                    EL PRODUCTO</strong></td>
        </tr>
    </tbody>
</table> <?php
				}
			}else{ mysqli_close($MySQLi); session_destroy(); ?>
<script type="text/javascript">
Swal.fire({
    type: 'error',
    title: 'Sesión caducada',
})
setTimeout(function() {
    location.reload();
}, 2500);
</script> <?php
			}
		break;
		case 'ActualizarProdTemp_':
			if (isset($_SESSION['idUser'])) {
				$id 			=	$_POST['id'];
				$Clave 		=	$_POST['ClaveTemp'];
				$Cantidad =	$_POST['Cantidad'];
				$PrecioEsp=	$_POST['PrecioEspecial'];
				$updProd=	mysqli_query($MySQLi,"UPDATE ClaveTemporal SET Cantidad='$Cantidad', PrecioOferta='$PrecioEsp' WHERE id='$id' ");
				if ($updProd) {
					$queryRegTem=	mysqli_query($MySQLi,"SELECT * FROM ClaveTemporal WHERE Clave='$Clave' ORDER BY id DESC ");
					$resulRegTem=	mysqli_num_rows($queryRegTem);
					// Si la consulta encuentra registros ...
					if ($resulRegTem>0) { ?>
<table id="data-table" class="table table-striped table-bordered table-td-valign-middle w-100">
    <thead>
        <tr>
            <th colspan="6" class="d-none">
                <input type="text" class="form-control" name="ClaveTemporal" id="ClaveTemporal"
                    value="<?php echo $Clave?>">
            </th>
        </tr>
        <tr>
            <th style="padding: 3px" width="5%" class="text-center">Cant</th>
            <th style="padding: 3px" width="55%" class="text-center">Producto</th>
            <th style="padding: 3px" width="10%" class="text-center">Precio<br>Lista</th>
            <th style="padding: 3px" width="10%" class="text-center">Precio<br>Especial</th>
            <th style="padding: 3px" width="10%" class="text-center">Total</th>
            <th style="padding: 3px" width="10%" class="text-center">Acciones</th>
        </tr>
    </thead>
    <tbody><?php 
								while ($dataRegistros = mysqli_fetch_assoc($queryRegTem)) {  ?>
        <tr>
            <td class="text-center"><?php echo $dataRegistros['Cantidad'] ?></td>
            <td class=""><?php
										$id_Producto 	=	$dataRegistros['idProducto'];
										$sqlProducto 	=	mysqli_query($MySQLi,"SELECT * FROM Productos WHERE idProducto='$id_Producto'");
										$DataProductos 	=	mysqli_fetch_assoc($sqlProducto);
										$Product 		=	$DataProductos['Producto'];
										$MarcProduct 	=	$DataProductos['Marca'];
										$ModeloProduct 	=	$DataProductos['Modelo'];
										$DescProduct 	=	$Product." / ".$MarcProduct." / ".$ModeloProduct;
										echo $DescProduct;?>
            </td>
            <td class="text-center">$ <?php echo number_format($dataRegistros['PrecioLista'],2) ?></td>
            <td class="text-center">$ <?php echo number_format($dataRegistros['PrecioOferta'],2) ?></td>
            <td class="text-center">$
                <?php echo number_format($dataRegistros['PrecioOferta']*$dataRegistros['Cantidad'],2) ?></td>
            <td class="text-center"><?php
										$ClaveABuscar 	=	$dataRegistros['Clave'];
										$consultaClave 	=	mysqli_query($MySQLi,"SELECT * FROM ClaveTemporal WHERE Clave='$ClaveABuscar' ");
										$resultBusqueda =	mysqli_num_rows($consultaClave);
										if ($resultBusqueda>1 ) { ?>
                <button type="button" title="Borrar Producto" class="btn btn-xs btn-danger deleteProdTemp"
                    id="<?php echo $dataRegistros['id']?>"><i class="fa fa-trash"></i></button>&nbsp;<?php
										} ?>
                <a href="#editProdTemp" data-toggle="modal"><button title="Editar Producto"
                        class="btn btn-xs btn-info editProdTemporal" id="<?php echo $dataRegistros['id'] ?>"><i
                            class="fa fa-edit"></i></button></a>
            </td>
        </tr><?php } ?>
    </tbody>
</table><?php mysqli_close($MySQLi); 
					}else{ mysqli_close($MySQLi); ?>
<table id="data-table" class="table table-striped table-bordered table-td-valign-middle w-100">
    <thead>
        <tr>
            <th style="padding: 3px" width="5%" class="text-center">Cant</th>
            <th style="padding: 3px" width="55%" class="text-center">Producto</th>
            <th style="padding: 3px" width="10%" class="text-center">Pre_Lista</th>
            <th style="padding: 3px" width="10%" class="text-center">Pre_Esp</th>
            <th style="padding: 3px" width="10%" class="text-center">Total</th>
            <th style="padding: 3px" width="10%" class="text-center">Acciones</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td colspan="6" class="text-center text-danger" style="letter-spacing: 1px"><strong>NO HAY PRODUCTOS QUE
                    MOSTRAR</strong></td>
        </tr>
    </tbody>
</table> <?php
					}
				}else{ mysqli_close($MySQLi); ?>
<table id="data-table" class="table table-striped table-bordered table-td-valign-middle w-100">
    <thead>
        <tr>
            <th style="padding: 3px" width="5%" class="text-center">Cant</th>
            <th style="padding: 3px" width="55%" class="text-center">Producto</th>
            <th style="padding: 3px" width="10%" class="text-center">Pre_Lista</th>
            <th style="padding: 3px" width="10%" class="text-center">Pre_Esp</th>
            <th style="padding: 3px" width="10%" class="text-center">Total</th>
            <th style="padding: 3px" width="10%" class="text-center">Acciones</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td colspan="6" class="text-center text-danger" style="letter-spacing: 1px"><strong>ERROR AL INTENTAR BORRAR
                    EL PRODUCTO</strong></td>
        </tr>
    </tbody>
</table> <?php
				}
			}else{ mysqli_close($MySQLi); session_destroy(); ?>
<script type="text/javascript">
Swal.fire({
    type: 'error',
    title: 'Sesión caducada',
})
setTimeout(function() {
    location.reload();
}, 2500);
</script> <?php
			}
		break;
		case 'GenerarCotizacion':
			if (isset($_SESSION['idUser'])) {
				$idUser 						=	$_SESSION['idUser'];
				$miCiudad 					=	$_POST['miCiudad'];
				$Cliente_Existente	=	$_POST['Cliente_Existente'];
				$NombreCliente 			=	$_POST['Nombre'];
				$ApellidoCliente 		=	$_POST['Apellido'];
				$CorreoCliente 			=	$_POST['Correo'];
				$EmpresaCliente 		=	$_POST['Empresa'];
				$NITCliente 				=	$_POST['NIT'];
				$CiudadCliente 			=	$_POST['Ciudad'];
				$CelularCliente 		=	$_POST['Celular'];
				$FijoCliente 				=	$_POST['Otro'];
				$DireccionCliente 	=	$_POST['Direccion'];
				$ComentariosCliente =	$_POST['Comentarios'];
				$FormaPagoCliente 	=	$_POST['formaPago'];
				$FinOfertaCliente 	=	$_POST['fechaFin'];
				$TiempoEntrega 			=	$_POST['tiempoEntrega'];
				$Observaciones 			=	$_POST['observaciones'];
				$Aleatorio 					=	uniqid();
				$Aleatorio 					=	substr($Aleatorio, -4);
				$Aleatorio 					=	strtoupper($Aleatorio);
				if ($miCiudad=='Santa Cruz') {
					$CCode 	=	'SC';
				}elseif ($miCiudad=='Cochabamba') {
					$CCode 	=	'CB';
				}elseif ($miCiudad=='La Paz') {
					$CCode 	=	'LP';
				}elseif ($miCiudad == 'Santa Cruz Trompillo') {
					$CCode 	=	'ST';
				}elseif ($miCiudad == 'Ferias' || $miCiudad == 'Tarija') {
					$CCode 	=	'TF';
				}
				$Code = $CCode."-".date("y").date('m').date('d')."-".$Aleatorio;				
				//Si el Cliente no existe, lo Guardamos en la base de datos
				if ($Cliente_Existente=='0') {
					$insertNewCliente 	=	mysqli_query($MySQLi,"INSERT INTO Clientes (Nombres, Apellidos, Correo, Empresa, NIT, Celular, Otro, Ciudad, Direccion, Comentarios, Fecha_Reg, Registrador, Sucursal) VALUES ('$NombreCliente', '$ApellidoCliente', '$CorreoCliente', '$EmpresaCliente', '$NITCliente', '$CelularCliente', '$FijoCliente', '$CiudadCliente', '$DireccionCliente', '$Observaciones', '$fecha', '$idUser', '$miCiudad') ")or die(mysqli_error($MySQLi));
					if ($insertNewCliente) {
						// llamamos la clave temporales generada
						$ClaveTemp 				=	$_POST['ClaveTemporalCotiza'];
						$queryProdTemp 		=	mysqli_query($MySQLi,"SELECT * FROM ClaveTemporal WHERE Clave='$ClaveTemp' ");
						$dataProdTemp 		=	mysqli_fetch_assoc($queryProdTemp);						
						$Clave 						=	$dataProdTemp['Clave'];
						$idProd 					=	$dataProdTemp['idProducto'];
						$Cantidad 				=	$dataProdTemp['Cantidad'];
						$PreList 					=	$dataProdTemp['PrecioLista'];
						$PreOfer 					=	$dataProdTemp['PrecioOferta'];
						//llamamos los datos del cliente recien registrado
						$callNewCliente	=	mysqli_query($MySQLi,"SELECT * FROM Clientes WHERE Registrador='$idUser' AND Correo='$CorreoCliente' ");
						$resultCliente 	=	mysqli_num_rows($callNewCliente);
						if ($resultCliente>1) {
							$Buscar 	=	$resultCliente-1;
							$newCallCli =	mysqli_query($MySQLi,"SELECT * FROM Clientes WHERE Registrador='$idUser'AND Correo='$CorreoCliente' LIMIT $Buscar,1 ");
							$dataNewCall=	mysqli_fetch_assoc($newCallCli);
							$NewCliente =	$dataNewCall['idCliente'];
							//Insertamos los datos en la base de datos de Cotizaciones
							$insertCotizacion 	=	mysqli_query($MySQLi,"INSERT INTO Cotizaciones (Code, Clave, idUser, idCliente, Forma_Pago, FinFecha_Oferta, Dias_Entrega, Comentarios, Sucursal, Fecha, Hora) VALUES ('$Code', '$ClaveTemp', '$idUser', '$NewCliente', '$FormaPagoCliente', '$FinOfertaCliente', '$TiempoEntrega', '$Observaciones', '$miCiudad', '$fecha', '$Hora') ")or die(mysqli_error($MySQLi));
						}else{
							$dataNewCliente =	mysqli_fetch_assoc($callNewCliente);
							$NewCliente 	=	$dataNewCliente['idCliente'];
							//Insertamos los datos en la base de datos de Cotizaciones
							$insertCotizacion 	=	mysqli_query($MySQLi,"INSERT INTO Cotizaciones (Code, Clave, idUser, idCliente, Forma_Pago, FinFecha_Oferta, Dias_Entrega, Comentarios, Sucursal, Fecha, Hora) VALUES ('$Code', '$ClaveTemp', '$idUser', '$NewCliente', '$FormaPagoCliente', '$FinOfertaCliente', '$TiempoEntrega', '$Observaciones', '$miCiudad', '$fecha', '$Hora') ")or die(mysqli_error($MySQLi));
						}						
						if ($insertCotizacion) { mysqli_close($MySQLi);?>
<script type="text/javascript">
Swal.fire({
    type: 'success',
    title: 'Cotización y Cliente Guardados!',
    animation: false,
    customClass: {
        popup: 'animated bounceInDown'
    }
})
setTimeout(function() {
    location.replace("?root=generadas");
}, 2000);
</script><?php
						}else{ mysqli_close($MySQLi); ?>
<script type="text/javascript">
Swal.fire({
    type: 'error',
    title: 'Error Cotización',
})
</script> <?php	
						}
					}else{ mysqli_close($MySQLi); ?>
<script type="text/javascript">
Swal.fire({
    type: 'error',
    title: 'Error al registrar Cliente',
})
</script> <?php exit();
					}
				}else{
					$updateCliente	=	mysqli_query($MySQLi,"UPDATE Clientes SET Nombres='$NombreCliente', Apellidos='$ApellidoCliente', Correo='$CorreoCliente', Empresa='$EmpresaCliente', NIT='$NITCliente', Celular='$CelularCliente', Otro='$FijoCliente', Ciudad='$CiudadCliente', Direccion='$DireccionCliente', Comentarios='$Observaciones' WHERE idCliente='$Cliente_Existente' ")or die(mysqli_error($MySQLi));
					if ($updateCliente) {
						// llamamos la clave temporales generada
						$ClaveTemp 		=	$_POST['ClaveTemporalCotiza'];
						$queryProdTemp=	mysqli_query($MySQLi,"SELECT * FROM ClaveTemporal WHERE Clave='$ClaveTemp' ");
						$dataProdTemp =	mysqli_fetch_assoc($queryProdTemp);
						//while ($dataProdTemp = mysqli_fetch_assoc($queryProdTemp)) {
						$Clave 			=	$dataProdTemp['Clave'];
						$idProd 		=	$dataProdTemp['idProducto'];
						$Cantidad 	=	$dataProdTemp['Cantidad'];
						$PreList 		=	$dataProdTemp['PrecioLista'];
						$PreOfer 		=	$dataProdTemp['PrecioOferta'];
						/*	VERIFICAMOS SI EL CLIENTE TIENE SALDO A FAVOR	*/
						//$sqlNotaCredito = mysqli_query($MySQLi,"SELECT SUM(MontoUSD)AS MontoUSD, SUM(MontoBs)AS MontoBs FROM notasCredito WHERE idCliente='$Cliente_Existente' AND Estado=1 ");
						//$dataNotaCredito= mysqli_fetch_assoc($sqlNotaCredito);						
						// if ($dataNotaCredito['MontoUSD']!='' or $dataNotaCredito['MontoBs']!='') {
						// 	$CreditoUSD 	= $dataNotaCredito['MontoUSD'];
						// 	$CreditoBs 		= $dataNotaCredito['MontoBs'];
							/*	VERIFICAMOS EL TOTAL DE LA NUEVA COTIZACION	*/
							// $sqlClave 		= mysqli_query($MySQLi,"SELECT SUM(PrecioOferta)AS PrecioOferta FROM ClaveTemporal WHERE Clave='$ClaveTemp' ")or die(mysqli_error($MySQLi)."<br>Error en la línea: ".__LINE__);
							// $dataTotal 		= mysqli_fetch_assoc($sqlClave);
							// if ($dataTotal['PrecioOferta']<$CreditoUSD) { ?>
<!-- <script type="text/javascript">
									$(".efectSaveCotiza").addClass('d-none');
									$(".guardaNewCotiza").attr('disabled', false);
									$(".btnSaveCotiza").before('<div class="row mt-2 resNoPosible"><div class="col text-center"><div class="alert alert-danger" style="font-size: 20px" role="alert">El válor de la nueva cotización, es menor al saldo disponible del cliente.<br>Para poder guardar esta cotización, la sumatoria debe ser mayor o igual al crédito del cliente seleccionado.</div></div></div>');
									setTimeout(function(){
										$(".resNoPosible").remove();
									},10000)
								</script> --><?php //exit();
						// 	} 
						// }						
						/*	Deshabilitamos el crédito que poseé el cliente	*/
						//mysqli_query($MySQLi,"UPDATE notasCredito SET Estado=0  WHERE idCliente='$Cliente_Existente' AND Estado=1 ");						
						//Insertamos los datos en la base de datos de Cotizaciones
						$insertCotizacion 	=	mysqli_query($MySQLi,"INSERT INTO Cotizaciones (Code, Clave, idUser, idCliente, Forma_Pago, FinFecha_Oferta, Dias_Entrega, Comentarios, Sucursal, Fecha, Hora) VALUES ('$Code', '$ClaveTemp', '$idUser', '$Cliente_Existente', '$FormaPagoCliente', '$FinOfertaCliente', '$TiempoEntrega', '$Observaciones', '$miCiudad', '$fecha', '$Hora') ")or die(mysqli_error($MySQLi));
						if ($insertCotizacion) { mysqli_close($MySQLi); ?>
<script type="text/javascript">
Swal.fire({
    type: 'success',
    title: 'Cotización Guardada!',
    animation: false,
    customClass: {
        popup: 'animated bounceInDown'
    }
})
setTimeout(function() {
    location.replace("?root=generadas");
}, 2000);
</script><?php
						}else{ mysqli_close($MySQLi); ?>
<script type="text/javascript">
Swal.fire({
    type: 'error',
    title: 'Error Cotización',
})
</script> <?php	
						}
					}else{ mysqli_close($MySQLi); ?>
<script type="text/javascript">
Swal.fire({
    type: 'error',
    title: 'Error al actualizar Cliente',
})
</script> <?php						
					}
				}
			}else{ mysqli_close($MySQLi); session_destroy(); ?>
<script type="text/javascript">
Swal.fire({
    type: 'error',
    title: 'Sesión caducada',
})
setTimeout(function() {
    location.reload();
}, 2500);
</script> <?php
			}
		break;
		case 'borrarCotizacion':
			if (isset($_SESSION['idUser'])) {
				if ($_SESSION['Rango']==2) {
					$idCotizacion	=	$_POST['id'];
					//OBTENEMOS LA CLAVE E LA COTIZACION PARA PODER BORRAR LA CLAVE TEMPORAL
					$queryCotizacion=	mysqli_query($MySQLi,"SELECT * FROM Cotizaciones WHERE idCotizacion='$idCotizacion' ")or die(mysqli_error($MySQLi));
					$dataCotizacion	=	mysqli_fetch_assoc($queryCotizacion);
					$ClaveCotizacion=	$dataCotizacion['Clave'];
					//BORRAMOS LA COTIZACION
					$borrarCotiza 	=	mysqli_query($MySQLi,"DELETE FROM Cotizaciones WHERE idCotizacion='$idCotizacion' ");					
					//OBTENEMOS TODOS LOS PRODUCTOS EN LA CLAVE TEMPORAL PARA PODERLOS BORRAR
					$queryClave		=	mysqli_query($MySQLi,"SELECT * FROM ClaveTemporal WHERE Clave='$ClaveCotizacion' ")or die(mysqli_error($MySQLi));
					while ($dataClave = mysqli_fetch_assoc($queryClave)) {
						$Clave 		=	$dataClave['Clave'];
						//BORRAMOS TODO LO QUE CONTENGA LA CLAVE
						$borrarClaves	=	mysqli_query($MySQLi,"DELETE FROM ClaveTemporal WHERE Clave='$Clave' ")or die(mysqli_error($MySQLi));
					}?>
<script type="text/javascript">
Swal.fire({
    type: 'success',
    title: 'Cotización Borrada!',
    animation: false,
    customClass: {
        popup: 'animated bounceInDown'
    }
})
setTimeout(function() {
    location.reload();
}, 2000);
</script><?php exit();
				}else{
					mysqli_close($MySQLi);
					session_destroy(); ?>
<script type="text/javascript">
Swal.fire({
    position: 'center',
    type: 'error',
    title: 'SIN PRIVILEGIOS',
    showConfirmButton: false,
    timer: 2500,
})
setTimeout(function() {
    location.reload();
}, 2500);
</script><?php
				}
			}else{
				mysqli_close($MySQLi);
				session_destroy(); ?>
<script type="text/javascript">
Swal.fire({
    position: 'center',
    type: 'error',
    title: 'SESIÓN EXPIRADA',
    showConfirmButton: false,
    timer: 2500,
})
setTimeout(function() {
    location.reload();
}, 2500);
</script><?php
			}
		break;
		case 'deleteCotizacionGenerada':
			if (isset($_SESSION['idUser'])) {
				if ($_SESSION['Rango']==2) {
					$idCotizacion	=	$_POST['id'];
					//OBTENEMOS LA CLAVE E LA COTIZACION PARA PODER BORRAR LA CLAVE TEMPORAL
					$queryCotizacion=	mysqli_query($MySQLi,"SELECT * FROM Cotizaciones WHERE idCotizacion='$idCotizacion' ")or die(mysqli_error($MySQLi));
					$dataCotizacion	=	mysqli_fetch_assoc($queryCotizacion);
					$ClaveCotizacion=	$dataCotizacion['Clave'];

					//BORRAMOS LA COTIZACION
					$borrarCotiza 	=	mysqli_query($MySQLi,"DELETE FROM Cotizaciones WHERE idCotizacion='$idCotizacion' ");
					
					//OBTENEMOS TODOS LOS PRODUCTOS EN LA CLAVE TEMPORAL PARA PODERLOS BORRAR
					$queryClave		=	mysqli_query($MySQLi,"SELECT * FROM ClaveTemporal WHERE Clave='$ClaveCotizacion' ")or die(mysqli_error($MySQLi));
					while ($dataClave = mysqli_fetch_assoc($queryClave)) {
						$Clave 		=	$dataClave['Clave'];
						//BORRAMOS TODO LO QUE CONTENGA LA CLAVE
						$borrarClaves	=	mysqli_query($MySQLi,"DELETE FROM ClaveTemporal WHERE Clave='$Clave' ")or die(mysqli_error($MySQLi));
					}?>
<script type="text/javascript">
Swal.fire({
    type: 'success',
    title: 'Cotización Borrada!',
    animation: false,
    customClass: {
        popup: 'animated bounceInDown'
    }
})
setTimeout(function() {
    location.reload();
}, 2000);
</script><?php exit();
				}else{
					mysqli_close($MySQLi);
					session_destroy(); ?>
<script type="text/javascript">
Swal.fire({
    position: 'center',
    type: 'error',
    title: 'SIN PRIVILEGIOS',
    showConfirmButton: false,
    timer: 2500,
})
setTimeout(function() {
    location.reload();
}, 2500);
</script><?php
				}
			}else{
				mysqli_close($MySQLi);
				session_destroy(); ?>
<script type="text/javascript">
Swal.fire({
    position: 'center',
    type: 'error',
    title: 'SESIÓN EXPIRADA',
    showConfirmButton: false,
    timer: 2500,
})
setTimeout(function() {
    location.reload();
}, 2500);
</script><?php
			}
		break;
		case 'datosComplementarios':
			if (isset($_SESSION['idUser'])) {
				$Clave 			=	$_POST['Clave'];
				$FormaPago 		=	$_POST['formaPago'];
				$FechaFin 		=	$_POST['fechaFin'];
				$TiempoEntrega 	=	$_POST['tiempoEntrega'];
				$Observaciones 	=	$_POST['observaciones'];				

				$updataDatos 	=	mysqli_query($MySQLi,"UPDATE Cotizaciones SET Forma_Pago='$FormaPago', FinFecha_Oferta='$FechaFin', Dias_Entrega='$TiempoEntrega', Comentarios='$Observaciones' WHERE Clave='$Clave' ")or die(mysqli_error($MySQLi));
				//echo "hasta aquí"; exit();
				if ($updataDatos) { ?>
<script type="text/javascript">
Swal.fire({
    type: 'success',
    title: 'Datos actualizados',
    animation: false,
    customClass: {
        popup: 'animated bounceInDown'
    }
})
setTimeout(function() {
    location.reload();
}, 2000);
</script><?php
				}else{ ?>
<script type="text/javascript">
Swal.fire({
    type: 'error',
    title: 'Error al actulizar!',
    animation: false,
    customClass: {
        popup: 'animated shake'
    }
})
</script><?php
				}
			}else{ mysqli_close($MySQLi); session_destroy(); ?>
<script type="text/javascript">
Swal.fire({
    type: 'error',
    title: 'Sesión caducada',
})
setTimeout(function() {
    location.reload();
}, 2500);
</script> <?php
			}
		break;
		//	GUARDAR LOS DATOS PARA EL RECIBO
		case 'Guardar primer abono':
			if (isset($_SESSION['idUser'])) {
				$idUser 		=	$_POST['idUser'];	//
				$idCliente 	=	$_POST['idCliente'];
				$idCotizacion=	$_POST['idCotizacion'];	//
				$Moneda 		=	$_POST['moneda'];					//
				$PrecioDolar=	$_POST['dolar'];
				$Suma 			=	$_POST['lasumade'];				//	Cantidad en letras
				$Concepto 	=	$_POST['concetpde'];			//	Descripción en letras
				$Anticipo 	=	$_POST['anticipo'];				//	Cantidad en números
				$SaldoActual=	$_POST['saldoAct'];
				$SaldoAnter =	$_POST['saldoAnt'];
				$Total 			=	$_POST['total'];					//	Pago de anticipo en números
				$Cantidad 	=	$_POST['cantidad'];				//	Pago de anticipo en números
				$NameCliente=	$_POST['recibide'];				//	Nombre del Cliente
				$Sucursal 	=	$_POST['miCiudad'];				//	Sucursal
				$CodeCotiza =	$_POST['CodeCotiza'];
				/*	PRIMERO, GENERAMOS UN RECIBO DEL PRIMER ABONO	*/
				if ($Moneda=='USD') {
					$saveAbono	=	mysqli_query($MySQLi,"INSERT INTO Recibos (idCotizacion, CodeCotizacion, idUser, idCliente, Cliente, Moneda, PrecioDolar, Cantidad, CantidadUSD, Cant_Letras, Concepto, Fecha, Sucursal, Tipo, SaldoAnterior, SaldoActual, Total, TotalUSD) VALUES ('$idCotizacion', '$CodeCotiza', '$idUser', '$idCliente', '$NameCliente', '$Moneda', '$PrecioDolar', '0', '$Cantidad', '$Suma', '$Concepto', '$fecha', '$Sucursal', 'Abono', '$SaldoAnter', '$SaldoActual', '0', '$Total') ")or die(mysqli_error($MySQLi)."<br>Error en la línea: ".__LINE__);
					if ($saveAbono) {
						/*	SI TODO SE GUARDÓ BIEN, AHORA LLAMAMOS EL RECIBO	*/
						$callRecibo	=	mysqli_query($MySQLi,"SELECT idRecibo FROM Recibos WHERE idCotizacion='$idCotizacion' AND Tipo='Abono' ")or die(mysqli_error($MySQLi)."<br>Error en la línea: ".__LINE__);
						$resultCall	=	mysqli_num_rows($callRecibo);
						if ($resultCall>1) { ?>
							<script type="text/javascript">
							$("#byAbono").modal("hide");
							Swal.fire({
								type: 'error',
								title: 'error al llamar el recibo!',
								html: 'Notificar al programador de este error en la línea: <?php echo __LINE__ ?>',
								animation: false,
								customClass: {
									popup: 'animated shake'
								}
							})
							</script>
							<?php break;
						}else{
							$dataRecibo =	mysqli_fetch_assoc($callRecibo);
							$idRecibo 	=	$dataRecibo['idRecibo'];
						}
					}

					$insertAbono	=	mysqli_query($MySQLi,"INSERT INTO Abonos (idRecibo, idUser, idCliente, Cliente, Sucursal, idCotizacion, CodeCotizacion, Moneda, PrecioDolar, LaCantidadDe, EnConceptoDe, porAnticipo, AnticipoUSD, SaldoAnterior, SaldoActual, Total, TotalUSD, Fecha) VALUES ('$idRecibo', '$idUser', '$idCliente', '$NameCliente', '$Sucursal', '$idCotizacion', '$CodeCotiza', '$Moneda', '$PrecioDolar', '$Suma', '$Concepto', '0', '$Anticipo', '$SaldoAnter', '$SaldoActual', '0', '$Total', '$fecha') ")or die(mysqli_error($MySQLi)."<br>Error en la línea: ".__LINE__);
					if ($insertAbono) { 
						// CAMBIAMOS EL ESTADO DE LA COTIZACION
						mysqli_query($MySQLi,"UPDATE Cotizaciones SET Estado=5 WHERE idCotizacion='$idCotizacion' "); mysqli_close($MySQLi);?>
						<script type="text/javascript">
							/*	OCULTAMOS EL MODAL */
							$("#byAbono").modal("hide");
							$(".saveAbonoEfect1").addClass('d-none');
							//$(".guardarAbono").attr('disabled', false);
							Swal.fire({
								type: 'success',
								title: 'Abono guardado!',
								animation: false,
								customClass: {
									popup: 'animated bounceInDown'
								}
							})
							setTimeout(function() {
								location.replace("?root=anticipo");
							}, 2500)
						</script><?php
					}else{ mysqli_close($MySQLi); ?>
						<script type="text/javascript">
							/*	OCULTAMOS EL MODAL */
							$("#byAbono").modal("hide");
							Swal.fire({
								type: 'error',
								title: 'error Recibo!',
								animation: false,
								customClass: {
									popup: 'animated shake'
								}
							})
						</script><?php exit();
					}
				}else{
					/*	SI LA MONEDA ES EN Bs	*/
					$TotalenBs 	=	$Total;
					$TotalenUSD =	$TotalenBs/$PrecioDolar;
					$saveAbono	=	mysqli_query($MySQLi,"INSERT INTO Recibos (idCotizacion, CodeCotizacion, idUser, idCliente, Cliente, Moneda, PrecioDolar, Cantidad, CantidadUSD, Cant_Letras, Concepto, Fecha, Sucursal, Tipo, SaldoAnterior, SaldoActual, Total, TotalUSD) VALUES ('$idCotizacion', '$CodeCotiza', '$idUser', '$idCliente', '$NameCliente', '$Moneda', '$PrecioDolar', '$Cantidad', '0', '$Suma', '$Concepto', '$fecha', '$Sucursal', 'Abono', '$SaldoAnter', '$SaldoActual', '$Total', '$TotalenUSD') ")or die(mysqli_error($MySQLi)."<br>Error en la línea: ".__LINE__);
					if ($saveAbono) {
						/*	SI TODO SE GUARDÓ BIEN, AHORA LLAMAMOS EL RECIBO	*/
						$callRecibo	=	mysqli_query($MySQLi,"SELECT idRecibo FROM Recibos WHERE idCotizacion='$idCotizacion' AND Tipo='Abono' ")or die(mysqli_error($MySQLi)."<br>Error en la línea: ".__LINE__);
						$resultCall	=	mysqli_num_rows($callRecibo);
						if ($resultCall>1) { ?>
						<script type="text/javascript">
							$("#byAbono").modal("hide");
							Swal.fire({
								type: 'error',
								title: 'error al llamar el recibo!',
								html: 'Notificar al programador de este error en la línea: <?php echo __LINE__ ?>',
								animation: false,
								customClass: {
									popup: 'animated shake'
								}
							})
						</script>
						<?php break;
						}else{
							$dataRecibo =	mysqli_fetch_assoc($callRecibo);
							$idRecibo 	=	$dataRecibo['idRecibo'];
						}
					}

					$AnticipoenUSD 	=	$Cantidad/$PrecioDolar;
					$insertAbono	=	mysqli_query($MySQLi,"INSERT INTO Abonos (idRecibo, idUser, idCliente, Cliente, Sucursal, idCotizacion, CodeCotizacion, Moneda, PrecioDolar, LaCantidadDe, EnConceptoDe, porAnticipo, anticipoUSD, SaldoAnterior, SaldoActual, Total, TotalUSD, Fecha) VALUES ('$idRecibo', '$idUser', '$idCliente', '$NameCliente', '$Sucursal', '$idCotizacion', '$CodeCotiza', '$Moneda', '$PrecioDolar', '$Suma', '$Concepto', '$Anticipo', '0', '$SaldoAnter', '$SaldoActual', '$Total', '$TotalenUSD', '$fecha') ")or die(mysqli_error($MySQLi)."<br>Error en la línea: ".__LINE__);
					if ($insertAbono) { 
						// CAMBIAMOS EL ESTADO DE LA COTIZACION
						mysqli_query($MySQLi,"UPDATE Cotizaciones SET Estado=5 WHERE idCotizacion='$idCotizacion' "); mysqli_close($MySQLi);?>
						<script type="text/javascript">
							/*	OCULTAMOS EL MODAL */
							$("#byAbono").modal("hide");
							$(".saveAbonoEfect1").addClass('d-none');
							//$(".guardarAbono").attr('disabled', false);
							Swal.fire({
								type: 'success',
								title: 'Abono guardado!',
								animation: false,
								customClass: {
									popup: 'animated bounceInDown'
								}
							})
							setTimeout(function() {
								location.replace("?root=anticipo");
							}, 2500)
						</script><?php
					}else{ mysqli_close($MySQLi); ?>
						<script type="text/javascript">
							/*	OCULTAMOS EL MODAL */
							$("#byAbono").modal("hide");
							Swal.fire({
								type: 'error',
								title: 'error Recibo!',
								animation: false,
								customClass: {
									popup: 'animated shake'
								}
							})
						</script><?php exit();
					}
				}
			}else{ mysqli_close($MySQLi); session_destroy(); ?>
				<script type="text/javascript">
					Swal.fire({
						type: 'error',
						title: 'Sesión caducada',
					})
					setTimeout(function() {
						location.reload();
					}, 2500);
				</script> <?php
			}
		break;
		/*	RECIBE ABONO DEL CRÉDITO	*/
		case 'GuardaraAbonoCredito':
			if (isset($_SESSION['idUser'])) {
				$idUser 		=	$_POST['idUser'];
				$idCliente 		=	$_POST['idCliente'];
				$idCotizacion	=	$_POST['idCotizacion'];
				$Moneda 		=	$_POST['moneda'];
				$PrecioDolar	=	$_POST['dolar'];
				$Suma 			=	$_POST['lasumade'];
				$Concepto 		=	$_POST['concetpde'];
				$Anticipo 		=	$_POST['anticipo'];
				$SaldoActual	=	$_POST['saldoAct'];
				$SaldoAnter 	=	$_POST['saldoAnt'];
				$Total 			=	$_POST['total'];
				$Cantidad 		=	$_POST['cantidad'];
				$NameCliente	=	$_POST['recibide'];
				$Sucursal 		=	$_POST['miCiudad'];
				$CodeCotiza 	=	$_POST['CodeCotiza'];
				$vendedor 		=	$_POST['vendedor'];

				/*	DESCONTAMOS LOS PRODUCTOS DE LA COTIZACION	*/

				//	OBTENEMOS LA CLAVE DE LA COTIZACION
				$queryClave 	=	mysqli_query($MySQLi,"SELECT Clave FROM Cotizaciones WHERE idCotizacion='$idCotizacion'")or die(mysqli_error($MySQLi)."<br>Error en la línea: ".__LINE__);
				$dataClave		=	mysqli_fetch_assoc($queryClave);
				$ClaveCotizacion=	$dataClave['Clave'];

				//	LLAMAMOS LOS PRODUCTOS DE LA COTIZACION CON LA CLAVE TEMPORAL
				$queryProductos	=	mysqli_query($MySQLi,"SELECT * FROM ClaveTemporal WHERE Clave='$ClaveCotizacion' ")or die(mysqli_error($MySQLi)."<br>Error en la línea: ".__LINE__);

				while ($dataProducto=	mysqli_fetch_assoc($queryProductos)) {
					$idProducto		=	$dataProducto['idProducto'];
					$CantidadPro	=	$dataProducto['Cantidad'];
					$PrecioLista 	=	$dataProducto['PrecioLista']; 	//Precio Lista en USD
					$PrecioListaBs	=	$PrecioLista*$PrecioDolar;
					$PrecioVenta 	=	$dataProducto['PrecioOferta']; 	//Precio Venta en USD
					$PrecioVentaBs	=	$PrecioVenta*$PrecioDolar;
					$TotalVentaUS 	=	$CantidadPro*$PrecioVenta;
					$TotalVentaBs 	=	$CantidadPro*$PrecioVentaBs;

					//	ACTUALIZAMOS EL STOCK DEL PRODUCTO
					$response = new ProductsController();
					$response->sale($idProducto, $Sucursal, $dataProducto['Cantidad'], $vendedor, 'DESCUENTO STOCK-VENTA-CREDITO');					
				}

				// CAMBIAMOS EL ESTADO DE LA COTIZACION
				$statusChang=	mysqli_query($MySQLi,"UPDATE Cotizaciones SET Estado=4 WHERE idCotizacion='$idCotizacion' ")or die(mysqli_error($MySQLi)."<br>Error en la línea: ".__LINE__);
				/*	CREAMOS LA NOTA DE ENTREGA	*/
				$observacionesYuli='Garantía fallas de fábrica los primeros 3 meses, asistencia técnica en tienda gratis por 1 año presentando la nota de entrega emitida por importadora Yuli. *No cubrimos mal uso del equipo*';
				$createNotaE=	mysqli_query($MySQLi,"INSERT INTO NotaEntrega (idUser, idCliente, idCotizacion, Fecha, Sucursal, Observaciones) VALUES ('$idUser', '$idCliente', '$idCotizacion', '$fecha', '$Sucursal', '$observacionesYuli') ")or die(mysqli_error($MySQLi)."<br>Error en la línea: ".__LINE__);
				/*	LLAMAMOS LA NOTA DE ENTREGA RECIEN CREADA	*/
				$callNotaE 	=	mysqli_query($MySQLi,"SELECT idNotaE FROM NotaEntrega WHERE idCotizacion='$idCotizacion' ")or die(mysqli_error($MySQLi)."<br>Error en la línea: ".__LINE__);
				$dataNotaE 	=	mysqli_fetch_assoc($callNotaE);
				$idNotaE 	=	$dataNotaE['idNotaE'];

				if ($Moneda=='USD') {
					/*	PRIMERO, GENERAMOS UN RECIBO DEL PRIMER ABONO DEL CRÉDITO	*/
					$generaRecibo	=	mysqli_query($MySQLi,"INSERT INTO Recibos (idCotizacion, CodeCotizacion, idUser, idNotaE, idCliente, Cliente, Moneda, PrecioDolar, Cantidad, CantidadUSD, Cant_Letras, Concepto, Fecha, Sucursal, Tipo, SaldoAnterior, SaldoActual, Total, TotalUSD) VALUES ('$idCotizacion', '$CodeCotiza', '$idUser', '$idNotaE', '$idCliente', '$NameCliente', '$Moneda', '$PrecioDolar', '0', '$Cantidad', '$Suma', '$Concepto', '$fecha', '$Sucursal', 'Credito', '$SaldoAnter', '$SaldoActual', '0', '$Total') ")or die(mysqli_error($MySQLi)."<br>Error en la línea: ".__LINE__);
					
					/*	LLAMAMOS EL RECIBO RECIEN CREADO	*/
					$queryRecibo 	=	mysqli_query($MySQLi,"SELECT idRecibo FROM Recibos WHERE idCotizacion='$idCotizacion' ")or die(mysqli_error($MySQLi)."<br>Error en la línea: ".__LINE__);
					$dataRecibo =	mysqli_fetch_assoc($queryRecibo);
					$NewidRecibo=	$dataRecibo['idRecibo'];

					/*	GUARDAMOS EL ABONO	*/
					$insertAbono	=	mysqli_query($MySQLi,"INSERT INTO Creditos (idRecibo, idUser, idCliente, Cliente, Sucursal, idCotizacion, CodeCotizacion, Moneda, PrecioDolar, LaCantidadDe, EnConceptoDe, porAbono, AbonoUSD, SaldoAnterior, SaldoActual, Total, TotalUSD, Fecha) VALUES ('$NewidRecibo', '$idUser', '$idCliente', '$NameCliente', '$Sucursal', '$idCotizacion', '$CodeCotiza', '$Moneda', '$PrecioDolar', '$Suma', '$Concepto', '0', '$Anticipo', '$SaldoAnter', '$SaldoActual', '0', '$Total', '$fecha') ")or die(mysqli_error($MySQLi)."<br>Error en la línea: ".__LINE__);

				} else if ($Moneda=='Bs') {
					$TotalenUSD 	=	$Total/$PrecioDolar;

					/*	PRIMERO, GENERAMOS UN RECIBO DEL PRIMER ABONO DEL CRÉDITO	*/
					$generaRecibo	=	mysqli_query($MySQLi,"INSERT INTO Recibos (idCotizacion, CodeCotizacion, idUser, idNotaE, idCliente, Cliente, Moneda, PrecioDolar, Cantidad, CantidadUSD, Cant_Letras, Concepto, Fecha, Sucursal, Tipo, SaldoAnterior, SaldoActual, Total, TotalUSD) VALUES ('$idCotizacion', '$CodeCotiza', '$idUser', '$idNotaE', '$idCliente', '$NameCliente', '$Moneda', '$PrecioDolar', '$Anticipo', '0', '$Suma', '$Concepto', '$fecha', '$Sucursal', 'Credito', '$SaldoAnter', '$SaldoActual', '$Total', '$TotalenUSD') ")or die(mysqli_error($MySQLi)."<br>Error en la línea: ".__LINE__);
					
					/*	LLAMAMOS EL RECIBO RECIEN CREADO	*/
					$queryRecibo 	=	mysqli_query($MySQLi,"SELECT idRecibo FROM Recibos WHERE idCotizacion='$idCotizacion' ")or die(mysqli_error($MySQLi)."<br>Error en la línea: ".__LINE__);
					$dataRecibo =	mysqli_fetch_assoc($queryRecibo);
					$NewidRecibo=	$dataRecibo['idRecibo'];

					/*	GUARDAMOS EL ABONO	*/
					$insertAbono	=	mysqli_query($MySQLi,"INSERT INTO Creditos (idRecibo, idUser, idCliente, Cliente, Sucursal, idCotizacion, CodeCotizacion, Moneda, PrecioDolar, LaCantidadDe, EnConceptoDe, porAbono, AbonoUSD, SaldoAnterior, SaldoActual, Total, TotalUSD, Fecha) VALUES ('$NewidRecibo', '$idUser', '$idCliente', '$NameCliente', '$Sucursal', '$idCotizacion', '$CodeCotiza', '$Moneda', '$PrecioDolar', '$Suma', '$Concepto', '$Anticipo', '0', '$SaldoAnter', '$SaldoActual', '$Total', '$TotalenUSD', '$fecha') ")or die(mysqli_error($MySQLi)."<br>Error en la línea: ".__LINE__);
				}

				echo json_encode(array(
					'status' 	=> 'success',
					'message' 	=> 'Venta a crédito registrada correctamente',
				));
			} else { mysqli_close($MySQLi); session_destroy();
				//	SESION CADUCADA
				echo json_encode(array(
					'status' 	=> 'error',
					'message' 	=> 'Sesión caducada, por favor inicie sesión nuevamente',
				));
			}
		break;
		/*	ESTE CASO SERÁ SOLO PARA EL PRIMER ABONO	*/
		case 'actualizar datos del Recibo':
			if (isset($_SESSION['idUser'])) {
				if ($_SESSION['Rango']==2) {					
					$CodeCotiza =	$_POST['CodeCotiza'];
					$idUser 		=	$_POST['idUser'];
					$idRecibo 	=	$_POST['idRecibo'];
					$idAbono 		=	$_POST['idAbono'];
					$idCliente 	=	$_POST['idCliente'];
					$idCotizacion=	$_POST['idCotizacion'];
					$Moneda 		=	$_POST['moneda'];
					$PrecioDolar=	$_POST['dolar'];
					$Cantidad 	=	$_POST['cantidad'];		//	Pago de anticipo en números
					$NameCliente=	$_POST['recibide'];		//	Nombre del Cliente
					$Suma 			=	$_POST['lasumade'];		//	Cantidad en letras
					$Concepto 	=	$_POST['concetpde'];	//	Descripción en letras
					$Anticipo 	=	$_POST['anticipo'];		//	Cantidad en números
					$Sal_Actual =	$_POST['saldoAct'];		//	Saldo pendiente en números
					$Sal_Anteri =	$_POST['saldoAnt'];		//	Saldo pendiente en números
					$Total 		=	$_POST['total'];		//	Pago de anticipo en números
					if ($Moneda=='USD') {
						/*	VERIFICAMOS SI HAY CAMBIOS	*/
						$queryRecibo=	mysqli_query($MySQLi,"SELECT * FROM Recibos WHERE idRecibo='$idRecibo' ")or die(mysqli_error($MySQLi)."<br>Error en la línea: ".__LINE__);
						$dataRecibo =	mysqli_fetch_assoc($queryRecibo);
						if ($Cantidad == $dataRecibo['CantidadUSD'] AND $Suma == $dataRecibo['Cant_Letras'] ) {							
							/*	NO HAY CAMBIOS	*/
							$updateRecibo	=	mysqli_query($MySQLi,"UPDATE Recibos SET PrecioDolar='$PrecioDolar', Cliente='$NameCliente', Concepto='$Concepto' WHERE idRecibo='$idRecibo' ")or die(mysqli_error($MySQLi)."<br>Error en la línea: ".__LINE__);

							$updateAbono 	=	mysqli_query($MySQLi,"UPDATE Abonos SET PrecioDolar='$PrecioDolar', Cliente='$NameCliente', EnConceptoDe='$Concepto' WHERE idAbono='$idAbono' ")or die(mysqli_error($MySQLi)."<br>Error en la línea: ".__LINE__);

							if ($updateRecibo AND $updateAbono) { mysqli_close($MySQLi); ?>
<script type="text/javascript">
/*	OCULTAMOS EL MODAL */
$("#byAbono").modal("hide");
Swal.fire({
    type: 'success',
    title: 'No hubieron cambios importantes!',
    animation: false,
    customClass: {
        popup: 'animated bounceInDown'
    }
})
setTimeout(function() {
    location.reload();
}, 2500)
</script><?php
							}else{ mysqli_close($MySQLi); ?>
<script type="text/javascript">
/*	OCULTAMOS EL MODAL */
$("#byAbono").modal("hide");
Swal.fire({
    type: 'error',
    title: 'error Recibo!',
    animation: false,
    customClass: {
        popup: 'animated shake'
    }
})
</script><?php
							}
						}else{
							/*	SI HAY CAMBIOS	*/
							$updateRecibo	=	mysqli_query($MySQLi,"UPDATE Recibos SET Moneda='$Moneda', PrecioDolar='$PrecioDolar', Cantidad='0', CantidadUSD='$Cantidad', Cant_Letras='$Suma', Concepto='$Concepto', SaldoAnterior='$Sal_Anteri', SaldoActual='$Sal_Actual', Total='0', TotalUSD='$Total', Cliente='$NameCliente' WHERE idRecibo='$idRecibo' ")or die(mysqli_error($MySQLi)."<br>Error en la línea: ".__LINE__);

							$updateAbono 	=	mysqli_query($MySQLi,"UPDATE Abonos SET Cliente='$NameCliente', Moneda='$Moneda', PrecioDolar='$PrecioDolar', LaCantidadDe='$Suma', EnConceptoDe='$Concepto', porAnticipo='0', anticipoUSD='$Cantidad', SaldoAnterior='$Sal_Anteri', SaldoActual='$Sal_Actual', Total='0', TotalUSD='$Total' WHERE idAbono='$idAbono' ")or die(mysqli_error($MySQLi)."<br>Error en la línea: ".__LINE__);

							if ($updateRecibo AND $updateAbono) { mysqli_close($MySQLi); ?>
<script type="text/javascript">
/*	OCULTAMOS EL MODAL */
$("#byAbono").modal("hide");
Swal.fire({
    type: 'success',
    title: 'Abono actualizado!',
    animation: false,
    customClass: {
        popup: 'animated bounceInDown'
    }
})
setTimeout(function() {
    location.reload();
}, 2500)
</script><?php
							}else{ mysqli_close($MySQLi); ?>
<script type="text/javascript">
/*	OCULTAMOS EL MODAL */
$("#byAbono").modal("hide");
Swal.fire({
    type: 'error',
    title: 'error Recibo!',
    animation: false,
    customClass: {
        popup: 'animated shake'
    }
})
</script><?php
							}
						}
					}else{
						/*	SI LA MONEDA ES Bs	*/
						$TotalenBs 	=	$Total;
						$TotalenUSD =	$TotalenBs/$PrecioDolar;

						/*	VERIFICAMOS SI HAY CAMBIOS	*/
						$queryRecibo=	mysqli_query($MySQLi,"SELECT * FROM Recibos WHERE idRecibo='$idRecibo' ")or die(mysqli_error($MySQLi)."<br>Error en la línea: ".__LINE__);
						$dataRecibo =	mysqli_fetch_assoc($queryRecibo);
						if ($Cantidad == $dataRecibo['Cantidad'] AND $Suma == $dataRecibo['Cant_Letras'] ) {							
							/*	NO HAY CAMBIOS	*/
							$updateRecibo	=	mysqli_query($MySQLi,"UPDATE Recibos SET PrecioDolar='$PrecioDolar', Cliente='$NameCliente', Concepto='$Concepto' WHERE idRecibo='$idRecibo' ")or die(mysqli_error($MySQLi)."<br>Error en la línea: ".__LINE__);
							$updateAbono 	=	mysqli_query($MySQLi,"UPDATE Abonos SET PrecioDolar='$PrecioDolar', Cliente='$NameCliente', EnConceptoDe='$Concepto' WHERE idAbono='$idAbono' ")or die(mysqli_error($MySQLi)."<br>Error en la línea: ".__LINE__);
							if ($updateRecibo AND $updateAbono) { mysqli_close($MySQLi); ?>
<script type="text/javascript">
/*	OCULTAMOS EL MODAL */
$("#byAbono").modal("hide");
Swal.fire({
    type: 'success',
    title: 'No hubieron cambios importantes!',
    animation: false,
    customClass: {
        popup: 'animated bounceInDown'
    }
})
setTimeout(function() {
    location.reload();
}, 2500)
</script><?php
							}else{ mysqli_close($MySQLi); ?>
<script type="text/javascript">
/*	OCULTAMOS EL MODAL */
$("#byAbono").modal("hide");
Swal.fire({
    type: 'error',
    title: 'error Recibo!',
    animation: false,
    customClass: {
        popup: 'animated shake'
    }
})
</script><?php
							}
						}else{
							/*	SI HAY CAMBIOS	*/
							$updateRecibo	=	mysqli_query($MySQLi,"UPDATE Recibos SET Moneda='$Moneda', PrecioDolar='$PrecioDolar', Cantidad='$Cantidad', CantidadUSD='0', Cant_Letras='$Suma', Concepto='$Concepto', SaldoAnterior='$Sal_Anteri', SaldoActual='$Sal_Actual', Total='$Total', TotalUSD='$TotalenUSD', Cliente='$NameCliente' WHERE idRecibo='$idRecibo' ")or die(mysqli_error($MySQLi)."<br>Error en la línea: ".__LINE__);

							$updateAbono 	=	mysqli_query($MySQLi,"UPDATE Abonos SET Cliente='$NameCliente', Moneda='$Moneda', PrecioDolar='$PrecioDolar', LaCantidadDe='$Suma', EnConceptoDe='$Concepto', porAnticipo='$Cantidad', anticipoUSD='0', SaldoAnterior='$Sal_Anteri', SaldoActual='$Sal_Actual', Total='$Total', TotalUSD='$TotalenUSD' WHERE idAbono='$idAbono' ")or die(mysqli_error($MySQLi)."<br>Error en la línea: ".__LINE__);

							if ($updateRecibo AND $updateAbono) { mysqli_close($MySQLi); ?>
<script type="text/javascript">
/*	OCULTAMOS EL MODAL */
$("#byAbono").modal("hide");
Swal.fire({
    type: 'success',
    title: 'Abono actualizado!',
    animation: false,
    customClass: {
        popup: 'animated bounceInDown'
    }
})
setTimeout(function() {
    location.reload();
}, 2500)
</script><?php
							}else{ mysqli_close($MySQLi); ?>
<script type="text/javascript">
/*	OCULTAMOS EL MODAL */
$("#byAbono").modal("hide");
Swal.fire({
    type: 'error',
    title: 'error Recibo!',
    animation: false,
    customClass: {
        popup: 'animated shake'
    }
})
</script><?php
							}							
						}
					}					
				}else{ mysqli_close($MySQLi); ?>
<script type="text/javascript">
/*	OCULTAMOS EL MODAL */
$("#byAbono").modal("hide");
Swal.fire({
    type: 'error',
    title: 'SIN PRIVILEGIOS',
    animation: false,
    customClass: {
        popup: 'animated shake'
    }
})
</script><?php exit();
				}				
			}else{ mysqli_close($MySQLi); session_destroy(); ?>
<script type="text/javascript">
Swal.fire({
    type: 'error',
    title: 'Sesión caducada',
})
setTimeout(function() {
    location.reload();
}, 2500);
</script> <?php
			}
		break;
		case 'editarAbonoCredito':
			if (isset($_SESSION['idUser'])) {
				if ($_SESSION['Rango']==2) {
					//echo "alto aquí"; exit();
					$CodeCotiza =	$_POST['CodeCotiza'];
					$idUser 		=	$_POST['idUser'];
					$idRecibo 	=	$_POST['idRecibo'];
					$idAbono 		=	$_POST['idAbono'];
					$idCliente 	=	$_POST['idCliente'];
					$idCotizacion=	$_POST['idCotizacion'];
					$Moneda 		=	$_POST['moneda'];
					$PrecioDolar=	$_POST['dolar'];
					$Cantidad 	=	$_POST['cantidad'];		//	Pago de anticipo en números
					$NameCliente=	$_POST['recibide'];		//	Nombre del Cliente
					$Suma 			=	$_POST['lasumade'];		//	Cantidad en letras
					$Concepto 	=	$_POST['concetpde'];	//	Descripción en letras
					$Anticipo 	=	$_POST['anticipo'];		//	Cantidad en números
					$Sal_Actual =	$_POST['saldoAct'];		//	Saldo pendiente en números
					$Sal_Anteri =	$_POST['saldoAnt'];		//	Saldo pendiente en números
					$Total 			=	$_POST['total'];		//	Pago de anticipo en números
					/*	VERIFICAMOS SI HAY CAMBIOS	*/
					$queryRecibo=	mysqli_query($MySQLi,"SELECT * FROM Recibos WHERE idRecibo='$idRecibo' ")or die(mysqli_error($MySQLi)."<br>Error en la línea: ".__LINE__);
					$dataRecibo =	mysqli_fetch_assoc($queryRecibo);
					if ($Cantidad == $dataRecibo['Cantidad'] AND $Suma == $dataRecibo['Cant_Letras'] ) {
						/*	NO HAY CAMBIOS	*/
						$updateRecibo	=	mysqli_query($MySQLi,"UPDATE Recibos SET PrecioDolar='$PrecioDolar', Cliente='$NameCliente', Concepto='$Concepto' WHERE idRecibo='$idRecibo' ")or die(mysqli_error($MySQLi)."<br>Error en la línea: ".__LINE__);
						$updateAbono 	=	mysqli_query($MySQLi,"UPDATE Creditos SET PrecioDolar='$PrecioDolar', Cliente='$NameCliente', EnConceptoDe='$Concepto' WHERE idCredito='$idAbono' ")or die(mysqli_error($MySQLi)."<br>Error en la línea: ".__LINE__);
						if ($updateRecibo AND $updateAbono) { mysqli_close($MySQLi); ?>
<script type="text/javascript">
/*	OCULTAMOS EL MODAL */
$("#byAbonoCredito").modal("hide");
Swal.fire({
    type: 'success',
    title: 'No hubieron cambios importantes!',
    animation: false,
    customClass: {
        popup: 'animated bounceInDown'
    }
})
setTimeout(function() {
    location.reload();
}, 2500)
</script><?php
						}else{ mysqli_close($MySQLi); ?>
<script type="text/javascript">
/*	OCULTAMOS EL MODAL */
$("#byAbonoCredito").modal("hide");
Swal.fire({
    type: 'error',
    title: 'error Recibo!',
    animation: false,
    customClass: {
        popup: 'animated shake'
    }
})
</script><?php
						}
					}else{
						/*	SI HAY CAMBIOS	*/
						$AnticipoenUSD=	$Cantidad/$PrecioDolar;
						$TotalenBs 		=	$Total;
						$TotalenUSD 	=	$TotalenBs/$PrecioDolar;
						if ($Moneda   =='USD') {
							$updateRecibo	=	mysqli_query($MySQLi,"UPDATE Recibos SET Moneda='$Moneda', PrecioDolar='$PrecioDolar', CantidadUSD='$Cantidad', Cantidad='0', Cant_Letras='$Suma', Concepto='$Concepto', SaldoAnterior='$Sal_Anteri', SaldoActual='$Sal_Actual', Total='0', TotalUSD='$Total', Cliente='$NameCliente' WHERE idRecibo='$idRecibo' ")or die(mysqli_error($MySQLi)."<br>Error en la línea: ".__LINE__);
							$updateAbono=	mysqli_query($MySQLi,"UPDATE Creditos SET Cliente='$NameCliente', Moneda='$Moneda', PrecioDolar='$PrecioDolar', LaCantidadDe='$Suma', EnConceptoDe='$Concepto', porAbono='0', AbonoUSD='$Cantidad', SaldoAnterior='$Sal_Anteri', SaldoActual='$Sal_Actual', Total='0', TotalUSD='$Total' WHERE idCredito='$idAbono' ")or die(mysqli_error($MySQLi)."<br>Error en la línea: ".__LINE__);
							if ($updateRecibo AND $updateAbono) { mysqli_close($MySQLi); ?>
<script type="text/javascript">
/*	OCULTAMOS EL MODAL */
$("#byAbonoCredito").modal("hide");
Swal.fire({
    type: 'success',
    title: 'Abono actualizado!',
    animation: false,
    customClass: {
        popup: 'animated bounceInDown'
    }
})
setTimeout(function() {
    location.reload();
}, 2500)
</script><?php
							}else{ mysqli_close($MySQLi); ?>
<script type="text/javascript">
/*	OCULTAMOS EL MODAL */
$("#byAbonoCredito").modal("hide");
Swal.fire({
    type: 'error',
    title: 'error Recibo!',
    animation: false,
    customClass: {
        popup: 'animated shake'
    }
})
</script><?php
							}
						}else{
							$updateRecibo	=	mysqli_query($MySQLi,"UPDATE Recibos SET Moneda='$Moneda', PrecioDolar='$PrecioDolar', CantidadUSD='0', Cantidad='$Cantidad', Cant_Letras='$Suma', Concepto='$Concepto', SaldoAnterior='$Sal_Anteri', SaldoActual='$Sal_Actual', Total='$Total', Cliente='$NameCliente' WHERE idRecibo='$idRecibo' ")or die(mysqli_error($MySQLi)."<br>Error en la línea: ".__LINE__);
							$updateAbono 	=	mysqli_query($MySQLi,"UPDATE Creditos SET Cliente='$NameCliente', Moneda='$Moneda', PrecioDolar='$PrecioDolar', LaCantidadDe='$Suma', EnConceptoDe='$Concepto', porAbono='$Cantidad', AbonoUSD='0', SaldoAnterior='$Sal_Anteri', SaldoActual='$Sal_Actual', Total='$Total', TotalUSD='$TotalenUSD' WHERE idCredito='$idAbono' ")or die(mysqli_error($MySQLi)."<br>Error en la línea: ".__LINE__);
							if ($updateRecibo AND $updateAbono) { mysqli_close($MySQLi); ?>
<script type="text/javascript">
/*	OCULTAMOS EL MODAL */
$("#byAbonoCredito").modal("hide");
Swal.fire({
    type: 'success',
    title: 'Abono actualizado!',
    animation: false,
    customClass: {
        popup: 'animated bounceInDown'
    }
})
setTimeout(function() {
    location.reload();
}, 2500)
</script><?php
							}else{ mysqli_close($MySQLi); ?>
<script type="text/javascript">
/*	OCULTAMOS EL MODAL */
$("#byAbonoCredito").modal("hide");
Swal.fire({
    type: 'error',
    title: 'error Recibo!',
    animation: false,
    customClass: {
        popup: 'animated shake'
    }
})
</script><?php
							}
						}
					}
				}else{ mysqli_close($MySQLi); ?>
<script type="text/javascript">
/*	OCULTAMOS EL MODAL */
$("#byAbonoCredito").modal("hide");
Swal.fire({
    type: 'error',
    title: 'SIN PRIVILEGIOS',
    animation: false,
    customClass: {
        popup: 'animated shake'
    }
})
</script><?php exit();
				}				
			}else{ mysqli_close($MySQLi); session_destroy(); ?>
<script type="text/javascript">
Swal.fire({
    type: 'error',
    title: 'Sesión caducada',
})
setTimeout(function() {
    location.reload();
}, 2500);
</script> <?php
			}
		break;
		// Agrega una transaccion para el abono
		case 'Agregar nuevo abono':
			if (isset($_SESSION['idUser'])) {
				$CodeCotiza 	=	$_POST['CodeCotiza'];
				$Moneda 		=	$_POST['moneda'];
				$PrecioDolar	=	$_POST['dolar'];
				$idCliente 		=	$_POST['idCliente'];
				$idUser 		=	$_POST['idVendedor'];
				$Sucursal 		=	$_POST['miCiudad'];		//	Sucursal
				$idCotizacion	=	$_POST['idCotizacion'];
				$Cantidad 		=	$_POST['cantidad'];		//	Pago de anticipo en números
				$NameCliente	=	$_POST['recibide'];		//	Nombre del Cliente
				$Suma 			=	$_POST['lasumade'];		//	Cantidad en letras
				$Concepto 		=	$_POST['concetpde'];	//	Descripción en letras
				$Anticipo 		=	$_POST['anticipo'];		//	Cantidad en números
				$SaldoActual	=	$_POST['saldoActual'];	//	Saldo pendiente en números
				$SaldoAnteri	=	$_POST['saldoAnterior'];
				$Total 			=	$_POST['total'];		//	Pago de anticipo en números
				$cantidadBS 	= 0; // Cantidad abonado en BS
				$TotalenUSD 	= 0;
				$anticipoBs 	= 0; // Anticipo abonado en BS
				// INICIA TRANSACCIÓN
				mysqli_begin_transaction($MySQLi);

				try {
					// ASIGNAMOS LA CANTIDADES EN USD O BS
					if ($Moneda == 'USD') {
						$TotalenUSD = $Total;
						$Total 		= 0;
					} else {
						$cantidadBS = $Cantidad;
						$TotalenBs 	= $Total;
						$TotalenUSD = $TotalenBs/$PrecioDolar;
						$Cantidad 	= 0;
						$anticipoBs = $Anticipo;
						$Anticipo 	= 0;
					}

					//	1° GUARDAMOS LOS DATOS DEL RECIBO
					$insertDataRecibo = mysqli_query($MySQLi, "INSERT INTO Recibos (idCotizacion, CodeCotizacion, idUser, idCliente, Cliente, Moneda, PrecioDolar, Cantidad, CantidadUSD, Cant_Letras, Concepto, Fecha, Sucursal, Tipo, SaldoAnterior, SaldoActual, Total, TotalUSD) VALUES ('$idCotizacion', '$CodeCotiza', '$idUser', '$idCliente', '$NameCliente', '$Moneda', '$PrecioDolar', $cantidadBS, '$Cantidad', '$Suma', '$Concepto', '$fecha', '$Sucursal', 'Abono', '$SaldoAnteri', '$SaldoActual', $Total, '$TotalenUSD') ");
                	if (!$insertDataRecibo) throw new Exception(mysqli_error($MySQLi));
					
					// Obtener Id del recibo insertado
					$NewidRecibo = mysqli_insert_id($MySQLi);

					// 2° GUARDAMOS LOS DATOS DEL ABONO
					$saveAbono 	=	mysqli_query($MySQLi,"INSERT INTO Abonos (idRecibo, idUser, idCliente, Cliente, Sucursal, idCotizacion, CodeCotizacion, Moneda, PrecioDolar, LaCantidadDe, EnConceptoDe, porAnticipo, anticipoUSD, SaldoAnterior, SaldoActual, Total, TotalUSD, Fecha) VALUES ('$NewidRecibo', '$idUser', '$idCliente', '$NameCliente', '$Sucursal', '$idCotizacion', '$CodeCotiza', '$Moneda', '$PrecioDolar', '$Suma', '$Concepto', '$anticipoBs', '$Anticipo', '$SaldoAnteri', '$SaldoActual', '$Total', '$TotalenUSD','$fecha') ");
					if (!$saveAbono) throw new Exception(mysqli_error($MySQLi));

					// 3° VERIFICAR SALDO
					$queryRecibos=	mysqli_query($MySQLi,"SELECT SaldoActual FROM Recibos WHERE idRecibo='$NewidRecibo' ");
					if (!$queryRecibos) throw new Exception(mysqli_error($MySQLi));
					$dataRecibo =	mysqli_fetch_assoc($queryRecibos);
					$SaldoActual=	$dataRecibo['SaldoActual'];
					if ($SaldoActual=='0') {
						// 4° GENERAMOS LA NOTA DE ENTREGA
						$observacionesYuli='Garantía fallas de fábrica los primeros 3 meses, asistencia técnica en tienda gratis por 1 año presentando la nota de entrega emitida por importadora Yuli. *No cubrimos mal uso del equipo*';
						$addNotaEntrega	=	mysqli_query($MySQLi,"INSERT INTO NotaEntrega (idUser, idCliente, idCotizacion, Fecha, Sucursal, Observaciones) VALUES ('$idUser', '$idCliente', '$idCotizacion', '$fecha', '$Sucursal', '$observacionesYuli') ");
						if (!$addNotaEntrega) throw new Exception(mysqli_error($MySQLi));
						// obtener id de la nota de entrega
						$idNotaEntrega = mysqli_insert_id($MySQLi);

						$vendedor 		=	$_POST['vendedor'];

						// 5° OBTENER CLAVE DE COTIZACION
						$queryCotiza = mysqli_query($MySQLi, "SELECT Clave FROM Cotizaciones WHERE idCotizacion='$idCotizacion' ");
						if (!$queryCotiza) throw new Exception(mysqli_error($MySQLi));
						$dataCotiza = mysqli_fetch_assoc($queryCotiza);
						$ClaveCotiza=	$dataCotiza['Clave'];

						// 6° OBTENER PRODUCTOS RELACIONADOS A LA COTIZACION
						$queryProductos = mysqli_query($MySQLi, "SELECT * FROM ClaveTemporal WHERE Clave='$ClaveCotiza' ");
						if (!$queryProductos) throw new Exception(mysqli_error($MySQLi));

						// 7° RECORRER CADA PRODUCTO Y DESCONTARLO DEL INVENTARIO
						while ($dataProducto = mysqli_fetch_assoc($queryProductos)) {
							$idProducto		=	$dataProducto['idProducto'];
							$CantidadPro	=	$dataProducto['Cantidad'];
							$PrecioLista 	=	$dataProducto['PrecioLista'];
							$PrecioListaBs	=	$PrecioLista*$PrecioDolar;
							$PrecioVenta 	=	$dataProducto['PrecioOferta']; //este es el precio en dólares por default
							$PrecioVentaBs	=	$PrecioVenta*$PrecioDolar;
							$TotalVentaUS 	=	$CantidadPro*$PrecioVenta;
							$TotalVentaBs 	=	$CantidadPro*$PrecioVentaBs;

							//	ACTUALIZAMOS EL STOCK DEL PRODUCTO
							$response = new ProductsController();
							$proccess = $response->sale($idProducto, $Sucursal, $dataProducto['Cantidad'], $vendedor, 'DESCUENTO STOCK-ABONO-CANCELADO COTIZACION: ' . $CodeCotiza);
							if ($proccess) {
								$insertReporteVenta	=	mysqli_query($MySQLi,"INSERT INTO Ventas (idCotizacion, CodeCotizacion, idUser, idCliente, idRecibo, idEntrega, idProducto, Cantidad, Moneda, PrecioDolar, PrecioListaUSD, PrecioListaBs, PrecioVentaUSD, PrecioVentaBs, Sucursal, Fecha, TotalVentaUS, TotalVentaBs) VALUES ('$idCotizacion', '$CodeCotiza', '$idUser', '$idCliente', '$NewidRecibo', '$idNotaEntrega', '$idProducto', '$CantidadPro', '$Moneda', '$PrecioDolar', '$PrecioLista', '$PrecioListaBs', '$PrecioVenta', '$PrecioVentaBs', '$Sucursal', '$fecha', '$TotalVentaUS', '$TotalVentaBs') ");
								if (!$insertReporteVenta) throw new Exception(mysqli_error($MySQLi));
							} else {
								throw new Exception("Error al descontar el stock del producto");
							}
							
						}

						// 8° ACTUALIZAR ESTADO DE COTIZACION A ANTICIPO CANCELADO
						$ChangeStatus 	=	mysqli_query($MySQLi,"UPDATE Cotizaciones SET Estado=6, Completada='$fecha' WHERE idCotizacion='$idCotizacion' ");
						if (!$ChangeStatus) throw new Exception(mysqli_error($MySQLi));

						// 9° ACTUALIZAR RECIBO CON LA NOTA DE ENTREGA
						$updateRecibo	=	mysqli_query($MySQLi,"UPDATE Recibos SET idNotaE='$idNotaEntrega' WHERE idRecibo='$NewidRecibo' ");
						if (!$updateRecibo) throw new Exception(mysqli_error($MySQLi));

						mysqli_commit($MySQLi); // CONFIRMA TRANSACCIÓN
            			mysqli_close($MySQLi); ?>

						<script type="text/javascript">
							/*	OCULTAMOS EL MODAL */
							$("#plusAbono").modal("hide");
							$(".newPay").addClass('d-none');
							//$(".addNewAbono").attr('disabled', false);
							Swal.fire({
								type: 'success',
								title: 'Abono agregado!',
								html: 'La cuenta ha sido saldada!!',
								animation: false,
								customClass: {
									popup: 'animated bounceInDown'
								}
							})
							setTimeout(function() {
								location.replace("?root=anticipoCancelados");
							}, 2500)
						</script><?php
					} else {
						mysqli_commit($MySQLi); // CONFIRMA TRANSACCIÓN
            			mysqli_close($MySQLi); ?>

						<script type="text/javascript">
							/*	OCULTAMOS EL MODAL */
							$("#plusAbono").modal("hide");
							$(".newPay").addClass('d-none');
							//$(".addNewAbono").attr('disabled', false);
							Swal.fire({
								type: 'success',
								title: 'Abono agregado!',
								animation: false,
								customClass: {
									popup: 'animated bounceInDown'
								}
							})
							setTimeout(function() {
								location.replace("?root=anticipo");
							}, 2500)
						</script><?php
					}

				} catch (\Throwable $e) {
					mysqli_rollback($MySQLi); // DESHACE LA TRANSACCIÓN
					mysqli_close($MySQLi);
					?>
					<script type="text/javascript">
						Swal.fire({
							type: 'error',
							title: 'Error en agregar el abono',
							html: '<?php echo json_encode($e->getMessage()); ?>',
							animation: false,
							customClass: {
								popup: 'animated shake'
							}
						})
						setTimeout(function() {
							location.reload();
						}, 5000);
					</script>
					<?php
				}
			}else{ 
				mysqli_close($MySQLi); 
				session_destroy();
				?>
				<script type="text/javascript">
					Swal.fire({
						type: 'error',
						title: 'Sesión caducada',
					})
					setTimeout(function() {
						location.reload();
					}, 2500);
				</script> <?php
			}
		break;

		case 'AgregarNuevoAbonoCredito':
			if (isset($_SESSION['idUser'])) {
				$CodeCotiza =	$_POST['CodeCotiza'];
				$Moneda 		=	$_POST['moneda'];
				$PrecioDolar=	$_POST['dolar'];
				$idCliente 	=	$_POST['idCliente'];
				$idUser 		=	$_POST['idUser'];
				$Sucursal 	=	$_POST['miCiudad'];		//	Sucursal
				$idCotizacion=	$_POST['idCotizacion'];
				$Cantidad 	=	$_POST['cantidad'];		//	Pago de anticipo en números
				$NameCliente=	$_POST['recibide'];		//	Nombre del Cliente
				$Suma 			=	$_POST['lasumade'];		//	Cantidad en letras
				$Concepto 	=	$_POST['concetpde'];	//	Descripción en letras
				$Anticipo 	=	$_POST['anticipo'];		//	Cantidad en números
				$SaldoActual=	$_POST['saldoActual'];	//	Saldo pendiente en números
				$SaldoAnteri=	$_POST['saldoAnterior'];
				$Total 		=	$_POST['total'];		//	Pago de anticipo en números
				/*	LLAMAOS LA NOTA DE ENTREGA	*/
				$callNotaE 	=	mysqli_query($MySQLi,"SELECT idNotaE FROM NotaEntrega WHERE idCotizacion='$idCotizacion' ")or die(mysqli_error($MySQLi)."<br>Error en la línea: ".__LINE__);
				$dataNotaE 	=	mysqli_fetch_assoc($callNotaE);
				$idNotaE 	=	$dataNotaE['idNotaE'];
				if ($Moneda=='USD') {
			 		/*	GUARDAMOS LOS DATOS DEL FORMULARIO Y LLENAMOS EL RECIBO	*/
					$saveRecibo =	mysqli_query($MySQLi,"INSERT INTO Recibos (idCotizacion, CodeCotizacion, idUser, idNotaE, idCliente, Cliente, Moneda, PrecioDolar, Cantidad, CantidadUSD, Cant_Letras, Concepto, Fecha, Sucursal, Tipo, SaldoAnterior, SaldoActual, Total, TotalUSD) VALUES ('$idCotizacion', '$CodeCotiza', '$idUser', '$idNotaE', '$idCliente', '$NameCliente', '$Moneda', '$PrecioDolar', '0', '$Cantidad', '$Suma', '$Concepto', '$fecha', '$Sucursal', 'Credito', '$SaldoAnteri', '$SaldoActual', '0', '$Total') ")or die(mysqli_error($MySQLi)."<br>Error en la línea: ".__LINE__);
					/*	AHORA, LLAMAMOS LOS DATOS DEL RECIBO RECIEN CREADO	*/
					$callRecibo =	mysqli_query($MySQLi,"SELECT * FROM Recibos WHERE idCotizacion='$idCotizacion' AND Tipo='Credito' ")or die(mysqli_error($MySQLi)."<br>Error en la línea: ".__LINE__);
				 	$resCallRec =	mysqli_num_rows($callRecibo);
				 	$Busqueda 	=	$resCallRec-1;
				 	$newCall 	=	mysqli_query($MySQLi,"SELECT idRecibo FROM Recibos WHERE idCotizacion='$idCotizacion' AND Tipo='Credito' LIMIT $Busqueda,1 ")or die(mysqli_error($MySQLi)."<br>Error en la línea: ".__LINE__);
				 	$newData 	=	mysqli_fetch_assoc($newCall);
				 	$NewidRecibo=	$newData['idRecibo'];
				 	/*	GUARDAMOS LOS DATOS DEL ABONO	*/
					$saveAbono 	=	mysqli_query($MySQLi,"INSERT INTO Creditos (idRecibo, idUser, idCliente, Cliente, Sucursal, idCotizacion, CodeCotizacion, Moneda, PrecioDolar, LaCantidadDe, EnConceptoDe, porAbono, AbonoUSD, SaldoAnterior, SaldoActual, Total, TotalUSD, Fecha) VALUES ('$NewidRecibo', '$idUser', '$idCliente', '$NameCliente', '$Sucursal', '$idCotizacion', '$CodeCotiza', '$Moneda', '$PrecioDolar', '$Suma', '$Concepto', '0', '$Anticipo', '$SaldoAnteri', '$SaldoActual', '0', '$Total', '$fecha') ")or die(mysqli_error($MySQLi)."<br>Error en la línea: ".__LINE__);
					//	VERIFICAMOS SI EL SALDO ES IGUAL A 0
					$queryRecibos 	=	mysqli_query($MySQLi,"SELECT SaldoActual FROM Recibos WHERE idRecibo='$NewidRecibo' ")or die(mysqli_error($MySQLi)."<br>Error en la línea: ".__LINE__);
					$dataRecibo 	=	mysqli_fetch_assoc($queryRecibos);
					$SaldoActual 	=	$dataRecibo['SaldoActual'];
					if ($SaldoActual=='0') {
						/*	CAMBIAMOS EL ESTADO A COTIZACIONES AL CRÉDITO CANCELADA	*/
						$ChangeStatus 	=	mysqli_query($MySQLi,"UPDATE Cotizaciones SET Estado=7, Completada='$fecha' WHERE idCotizacion='$idCotizacion' ")or die(mysqli_error($MySQLi)."<br>Error en la línea: ".__LINE__);
						//	OBTENEMOS LA CLAVE DE LA COTIZACION
						$queryClave 	=	mysqli_query($MySQLi,"SELECT Clave FROM Cotizaciones WHERE idCotizacion='$idCotizacion' AND Estado=7 ")or die(mysqli_error($MySQLi)."<br>Error en la línea: ".__LINE__);
						$dataClave		=	mysqli_fetch_assoc($queryClave);
						$ClaveCotizacion=	$dataClave['Clave'];
						//	LLAMAMOS LOS PRODUCTOS DE LA COTIZACION CON LA CLAVE TEMPORAL
						$queryProductos		=	mysqli_query($MySQLi,"SELECT * FROM ClaveTemporal WHERE Clave='$ClaveCotizacion' ")or die(mysqli_error($MySQLi)."<br>Error en la línea: ".__LINE__);
						while ($dataProducto=	mysqli_fetch_assoc($queryProductos)) {
							$idProducto		=	$dataProducto['idProducto'];
							$CantidadPro	=	$dataProducto['Cantidad'];
							$PrecioLista 	=	$dataProducto['PrecioLista']; 	//Precio Lista en USD
							$PrecioListaBs=	$PrecioLista*$PrecioDolar;
							$PrecioVenta 	=	$dataProducto['PrecioOferta']; 	//Precio Venta en USD
							$PrecioVentaBs=	$PrecioVenta*$PrecioDolar;
							$TotalVentaUS =	$CantidadPro*$PrecioVenta;
							$TotalVentaBs =	$CantidadPro*$PrecioVentaBs;

							//	LUEGO, GUARDAMOS EL REPORTE DE VENTA
							$insertReporteVenta	=	mysqli_query($MySQLi,"INSERT INTO Ventas (idCotizacion, CodeCotizacion, idUser, idCliente, idRecibo, idEntrega, idProducto, Cantidad, Moneda, PrecioDolar, PrecioListaUSD, PrecioListaBs, PrecioVentaUSD, PrecioVentaBs, Sucursal, Fecha, TotalVentaUS, TotalVentaBs) VALUES ('$idCotizacion', '$CodeCotiza', '$idUser', '$idCliente', '$NewidRecibo', '$idNotaE', '$idProducto', '$CantidadPro', '$Moneda', '$PrecioDolar', '$PrecioLista', '$PrecioListaBs', '$PrecioVenta', '$PrecioVentaBs', '$Sucursal', '$fecha', '$TotalVentaUS', '$TotalVentaBs') ")or die(mysqli_error($MySQLi)."<br>Error en la línea: ".__LINE__);
						} mysqli_close($MySQLi); ?>
<script type="text/javascript">
/*	OCULTAMOS EL MODAL */
$("#plusAbono").modal("hide");
Swal.fire({
    type: 'success',
    title: 'Abono agregado!',
    html: 'La cuenta ha sido saldada!!',
    animation: false,
    customClass: {
        popup: 'animated bounceInDown'
    }
})
setTimeout(function() {
    location.replace("?root=creditosCancelados");
}, 2500)
</script><?php exit();
					}else{ mysqli_close($MySQLi); ?>
<script type="text/javascript">
Swal.fire({
    type: 'success',
    title: 'Abono agregado!',
    animation: false,
    customClass: {
        popup: 'animated bounceInDown'
    }
})
setTimeout(function() {
    location.reload();
}, 2500)
</script><?php
					}
			 	}else{
			 		$TotalUSD = $Total/$PrecioDolar;
			 		/*	GUARDAMOS LOS DATOS DEL FORMULARIO Y LLENAMOS EL RECIBO	*/
					$saveRecibo =	mysqli_query($MySQLi,"INSERT INTO Recibos (idCotizacion, CodeCotizacion, idUser, idNotaE, idCliente, Cliente, Moneda, PrecioDolar, Cantidad, CantidadUSD, Cant_Letras, Concepto, Fecha, Sucursal, Tipo, SaldoAnterior, SaldoActual, Total, TotalUSD) VALUES ('$idCotizacion', '$CodeCotiza', '$idUser', '$idNotaE', '$idCliente', '$NameCliente', '$Moneda', '$PrecioDolar', '$Cantidad', '0', '$Suma', '$Concepto', '$fecha', '$Sucursal', 'Credito', '$SaldoAnteri', '$SaldoActual', '$Total', '$TotalUSD') ")or die(mysqli_error($MySQLi)."<br>Error en la línea: ".__LINE__);
					/*	AHORA, LLAMAMOS LOS DATOS DEL RECIBO RECIEN CREADO	*/
					$callRecibo =	mysqli_query($MySQLi,"SELECT * FROM Recibos WHERE idCotizacion='$idCotizacion' AND Tipo='Credito' ")or die(mysqli_error($MySQLi)."<br>Error en la línea: ".__LINE__);
				 	$resCallRec =	mysqli_num_rows($callRecibo);
				 	$Busqueda 	=	$resCallRec-1;
				 	$newCall 	=	mysqli_query($MySQLi,"SELECT idRecibo FROM Recibos WHERE idCotizacion='$idCotizacion' AND Tipo='Credito' LIMIT $Busqueda,1 ")or die(mysqli_error($MySQLi)."<br>Error en la línea: ".__LINE__);
				 	$newData 	=	mysqli_fetch_assoc($newCall);
				 	$NewidRecibo=	$newData['idRecibo'];
				 	/*	GUARDAMOS LOS DATOS DEL ABONO	*/
					$saveAbono 	=	mysqli_query($MySQLi,"INSERT INTO Creditos (idRecibo, idUser, idCliente, Cliente, Sucursal, idCotizacion, CodeCotizacion, Moneda, PrecioDolar, LaCantidadDe, EnConceptoDe, porAbono, AbonoUSD, SaldoAnterior, SaldoActual, Total, TotalUSD, Fecha) VALUES ('$NewidRecibo', '$idUser', '$idCliente', '$NameCliente', '$Sucursal', '$idCotizacion', '$CodeCotiza', '$Moneda', '$PrecioDolar', '$Suma', '$Concepto', '$Anticipo', '0', '$SaldoAnteri', '$SaldoActual', '$Total', '$TotalUSD', '$fecha') ")or die(mysqli_error($MySQLi)."<br>Error en la línea: ".__LINE__);
					//	VERIFICAMOS SI EL SALDO ES IGUAL A 0
					$queryRecibos 	=	mysqli_query($MySQLi,"SELECT SaldoActual FROM Recibos WHERE idRecibo='$NewidRecibo' ")or die(mysqli_error($MySQLi)."<br>Error en la línea: ".__LINE__);
					$dataRecibo 	=	mysqli_fetch_assoc($queryRecibos);
					$SaldoActual 	=	$dataRecibo['SaldoActual'];
					if ($SaldoActual=='0') {
						/*	CAMBIAMOS EL ESTADO A COTIZACIONES AL CRÉDITO CANCELADA	*/
						$ChangeStatus 	=	mysqli_query($MySQLi,"UPDATE Cotizaciones SET Estado=7, Completada='$fecha' WHERE idCotizacion='$idCotizacion' ")or die(mysqli_error($MySQLi)."<br>Error en la línea: ".__LINE__);
						//	OBTENEMOS LA CLAVE DE LA COTIZACION
						$queryClave 	=	mysqli_query($MySQLi,"SELECT Clave FROM Cotizaciones WHERE idCotizacion='$idCotizacion' AND Estado=7 ")or die(mysqli_error($MySQLi)."<br>Error en la línea: ".__LINE__);
						$dataClave		=	mysqli_fetch_assoc($queryClave);
						$ClaveCotizacion=	$dataClave['Clave'];
						//	LLAMAMOS LOS PRODUCTOS DE LA COTIZACION CON LA CLAVE TEMPORAL
						$queryProductos		=	mysqli_query($MySQLi,"SELECT * FROM ClaveTemporal WHERE Clave='$ClaveCotizacion' ")or die(mysqli_error($MySQLi)."<br>Error en la línea: ".__LINE__);
						while ($dataProducto=	mysqli_fetch_assoc($queryProductos)) {
							$idProducto		=	$dataProducto['idProducto'];
							$CantidadPro	=	$dataProducto['Cantidad'];
							$PrecioLista 	=	$dataProducto['PrecioLista']; 	//Precio Lista en USD
							$PrecioListaBs 	=	$PrecioLista*$PrecioDolar;
							$PrecioVenta 	=	$dataProducto['PrecioOferta']; 	//Precio Venta en USD
							$PrecioVentaBs 	=	$PrecioVenta*$PrecioDolar;
							$TotalVentaUS 	=	$CantidadPro*$PrecioVenta;
							$TotalVentaBs 	=	$CantidadPro*$PrecioVentaBs;

							//	LUEGO, GUARDAMOS EL REPORTE DE VENTA
							$insertReporteVenta	=	mysqli_query($MySQLi,"INSERT INTO Ventas (idCotizacion, CodeCotizacion, idUser, idCliente, idRecibo, idEntrega, idProducto, Cantidad, Moneda, PrecioDolar, PrecioListaUSD, PrecioListaBs, PrecioVentaUSD, PrecioVentaBs, Sucursal, Fecha, TotalVentaUS, TotalVentaBs) VALUES ('$idCotizacion', '$CodeCotiza', '$idUser', '$idCliente', '$NewidRecibo', '$idNotaE', '$idProducto', '$CantidadPro', '$Moneda', '$PrecioDolar', '$PrecioLista', '$PrecioListaBs', '$PrecioVenta', '$PrecioVentaBs', '$Sucursal', '$fecha', '$TotalVentaUS', '$TotalVentaBs') ")or die(mysqli_error($MySQLi)."<br>Error en la línea: ".__LINE__);
						} mysqli_close($MySQLi); ?>
<script type="text/javascript">
/*	OCULTAMOS EL MODAL */
$("#plusAbono").modal("hide");
Swal.fire({
    type: 'success',
    title: 'Abono agregado!',
    html: 'La cuenta ha sido saldada!!',
    animation: false,
    customClass: {
        popup: 'animated bounceInDown'
    }
})
setTimeout(function() {
    location.replace("?root=creditosCancelados");
}, 2500)
</script><?php exit();
					}else{ mysqli_close($MySQLi); ?>
<script type="text/javascript">
Swal.fire({
    type: 'success',
    title: 'Abono agregado!',
    animation: false,
    customClass: {
        popup: 'animated bounceInDown'
    }
})
setTimeout(function() {
    location.reload();
}, 2500)
</script><?php
					}
			 	}
			}else{ mysqli_close($MySQLi); session_destroy(); ?>
<script type="text/javascript">
Swal.fire({
    type: 'error',
    title: 'Sesión caducada',
})
setTimeout(function() {
    location.reload();
}, 2500);
</script> <?php
			}
		break;
		case 'cambiar_a_CotGenerada':
			if (isset($_SESSION['idUser'])) {
				$idCotizacion	=	$_POST['id'];
				//ACTUALIZAMOS EL ESTADO DELA COTIZACION A COMPRADA
				$queryCotizacion=	mysqli_query($MySQLi,"UPDATE Cotizaciones SET Estado=0 WHERE idCotizacion='$idCotizacion' ");
				if ($queryCotizacion) { ?>
<script type="text/javascript">
Swal.fire({
    type: 'success',
    title: 'Cotización Generada!',
    animation: false,
    customClass: {
        popup: 'animated bounceInDown'
    }
})
setTimeout(function() {
    location.replace("?root=cotizaciones");
}, 2000);
</script><?php exit();
				}else{ ?>
<script type="text/javascript">
Swal.fire({
    type: 'error',
    title: 'Error update!',
    animation: false,
    customClass: {
        popup: 'animated shake'
    }
})
</script><?php exit();
				}
			}else{ mysqli_close($MySQLi); session_destroy(); ?>
<script type="text/javascript">
Swal.fire({
    type: 'error',
    title: 'Sesión caducada',
})
setTimeout(function() {
    location.reload();
}, 2500);
</script> <?php
			}
		break;
		case 'CambiarEstadoCotiEntregada':
			if (isset($_SESSION['idUser'])) {
				$idCotizacion 	=	$_POST['id'];
				/*	VERIFICAMOS SI YA EXISTE UNA COTIZACION MODIFICADA	*/
				$sqlCotizacion  = mysqli_query($MySQLi,"SELECT * FROM CotMod WHERE idCotizacion='$idCotizacion' ");
				$sqlResultCotMod= mysqli_num_rows($sqlCotizacion);
				if ($sqlResultCotMod>0) {
					/*	VERIFICAMOS SI LA COTIZACION ES TIPO COMPRA CASH O COMPRA POR ANTICIPO	*/
					$dataResultCot= mysqli_fetch_assoc($sqlCotizacion);
					$tipoCotiza 	= $dataResultCot['Tipo'];
					if ($tipoCotiza==1) {
						/*	VERIFICAMOS SI LA COTIZACION HA CAMBIADO	*/
						$sqlCotMod 			= mysqli_query($MySQLi,"SELECT * FROM CotMod WHERE idCotizacion='$idCotizacion' ");
						$dataCotMod 		= mysqli_fetch_assoc($sqlCotMod);
						$idCliente 			= $dataCotMod['idCliente'];
						$ClaveCotizacion= $dataCotMod['Clave'];
						$queryClave 		= mysqli_query($MySQLi,"SELECT SUM(PrecioOferta*Cantidad)AS Total FROM ClaveTemporal WHERE Clave='$ClaveCotizacion' ");
						$dataClave  		= mysqli_fetch_assoc($queryClave);
						$Total 					= $dataClave['Total'];
						$queryClave2		= mysqli_query($MySQLi,"SELECT SUM(PrecioOferta*Cantidad)AS Total FROM ClaveTempMod WHERE Clave='$ClaveCotizacion' ");
						$dataClave2 		= mysqli_fetch_assoc($queryClave2);
						$Total2 				= $dataClave2['Total'];
						// echo $Total."<br>".$Total2; exit();
						if ($Total  		< $Total2) { ?>
<script type="text/javascript">
$(".productosCot").after(
    '<div class="row avisoNoPosible mt-2"><div class="col"><div class="alert alert-danger" role="alert"><h4 class="alert-heading">Error!</h4><p>No es posible conntinuar, ya que, la sumatoria de esta cotización es menor al crédito del cliente.<br>Pra que pueda continuar, el total debe ser mayor o igual al crédito del cliente.</p><hr><p class="mb-0">El cliente tiene un crédito de <span class="text-black" style="font-size: 20px">: $ <?php echo $Total2  ?> Dólares</span>.</p></div></div></div>'
    );
setTimeout(function() {
    $(".avisoNoPosible").remove();
    $(".iEntregada").removeClass('fas fa-spinner fa-pulse');
    $(".iEntregada").addClass('fas fa-paper-plane');
    $(".cambiarEntregada").attr('disabled', false);
}, 8000);
</script><?php exit();
						}
					}else{
						/*	VERIFICAMOS SI LA COTIZACION HA CAMBIADO	*/
						$sqlCotMod 			= mysqli_query($MySQLi,"SELECT * FROM CotMod WHERE idCotizacion='$idCotizacion'AND Tipo=2 ");
						$dataCotMod 		= mysqli_fetch_assoc($sqlCotMod);
						$idCliente 			= $dataCotMod['idCliente'];
						$ClaveCotizacion= $dataCotMod['Clave'];
						$queryClave 		= mysqli_query($MySQLi,"SELECT SUM(PrecioOferta*Cantidad)AS Total FROM ClaveTemporal WHERE Clave='$ClaveCotizacion' ");
						$dataClave  		= mysqli_fetch_assoc($queryClave);
						$Total 					= $dataClave['Total'];

						$sqlNotaCredito = mysqli_query($MySQLi,"SELECT * FROM notasCredito WHERE idCliente='$idCliente'AND Estado=1 ");
						$dataNotaCredito= mysqli_fetch_assoc($sqlNotaCredito);
						$idNotaCredito 	= $dataNotaCredito['idNotaCredito'];
						$Total2 				= $dataNotaCredito['MontoUSD'];
						
						if ($Total  		< $Total2) { ?>
<script type="text/javascript">
$(".productosCot").after(
    '<div class="row avisoNoPosible mt-2"><div class="col"><div class="alert alert-danger" role="alert"><h4 class="alert-heading">Error!</h4><p>No es posible continuar, ya que, la sumatoria de esta cotización es menor al crédito del cliente.<br>Para que pueda continuar, el total debe ser mayor o igual al crédito del cliente.</p><hr><p class="mb-0">El cliente tiene un crédito de <span class="text-black" style="font-size: 20px">: $ <?php echo $Total2  ?> Dólares</span>.</p></div></div></div>'
    );
setTimeout(function() {
    $(".avisoNoPosible").remove();
    $(".iEntregada").removeClass('fas fa-spinner fa-pulse');
    $(".iEntregada").addClass('fas fa-paper-plane');
    $(".cambiarEntregada").attr('disabled', false);
}, 8000);
</script><?php exit();
						}
					}
					/*	CAMBIAMOS LA NOTA DE CREDITO A ZERO (QUIERE DECIR QUE EL CLIENTE YA NO TIENE CREDITO DISPONIBLE)	*/
					//mysqli_query($MySQLi,"UPDATE notasCredito SET Estado=0 WHERE idCliente='$idCliente'AND Estado=1 ");
					mysqli_query($MySQLi,"UPDATE notasCredito SET Estado=0 WHERE idNotaCredito='$idNotaCredito' ");
				}
				$Entregada 			=	date("Y-m-d H:i:s");				
				$updateCotiza 	=	mysqli_query($MySQLi,"UPDATE Cotizaciones SET Entregada='$Entregada', Estado=1 WHERE idCotizacion='$idCotizacion' ");
				if ($updateCotiza) { ?>
<script type="text/javascript">
Swal.fire({
    type: 'success',
    title: 'Cotización Entregada!',
    animation: false,
    customClass: {
        popup: 'animated bounceInDown'
    }
})
setTimeout(function() {
    location.replace('?root=entregadas');
}, 2000);
</script><?php
				}else{ ?>
<script type="text/javascript">
Swal.fire({
    type: 'error',
    title: 'Error al actulizar!',
    animation: false,
    customClass: {
        popup: 'animated shake'
    }
})
</script><?php
				}
			}else{ mysqli_close($MySQLi); session_destroy(); ?>
<script type="text/javascript">
Swal.fire({
    type: 'error',
    title: 'Sesión caducada',
})
setTimeout(function() {
    location.reload();
}, 2500);
</script> <?php
			}
		break;
		/*	COMPRA INSTANTANEA DE LA COTIZACIÓN	*/
		case 'ModificarReciboVentaCash':
			if (isset($_SESSION['idUser'])) {
				if ($_SESSION['Rango']==2) {
					$idRecibo 	= $_POST['idRecibo'];
					$enConcepto = $_POST['concepto'];
					$laSumade 	= $_POST['lasumade'];
					$cantidad 	= $_POST['cantidad'];
					/*	MODIFICAMOS EL RECIBO	*/
					$updateRecib= mysqli_query($MySQLi,"UPDATE Recibos SET Cant_Letras='$laSumade', Concepto='$enConcepto', Cantidad='$cantidad' WHERE idRecibo='$idRecibo' ");
					if (!$updateRecib) {
						die(mysqli_error($MySQLi)."<br>Error en la línea: ".__LINE__);
					}else{ mysqli_close($MySQLi); ?>
<script type="text/javascript">
Swal.fire({
    type: 'success',
    title: 'Recibo modificado!',
    animation: false,
    customClass: {
        popup: 'animated bounceInDown'
    }
})
setTimeout(function() {
    location.reload();
}, 2000);
</script><?php
					}
				}else{ mysqli_close($MySQLi); ?>
<script type="text/javascript">
Swal.fire({
    type: 'error',
    title: 'SIN AUTORIZACIÓN!',
    animation: false,
    customClass: {
        popup: 'animated shake'
    }
})
</script> <?php exit();
				}
			}else{ mysqli_close($MySQLi); session_destroy(); ?>
<script type="text/javascript">
Swal.fire({
    type: 'error',
    title: 'Sesión caducada',
})
setTimeout(function() {
    location.reload();
}, 2500);
</script> <?php
			}
		break;
		case 'GuardarPgo':
			if (isset($_SESSION['idUser'])) {
				$idCotiza 	 =	$_POST['idCotizacion'];
				$idUser 	 =	$_POST['idUser'];
				$idCliente 	 =	$_POST['idCliente'];
				$Sucursal 	 =	$_POST['Sucursal'];
				$CodeCotiza	 =	$_POST['CodeCotiza'];
				$PrecioDolar =	$_POST['dolar'];
				$Moneda 	 =	$_POST['moneda'];
				$CantidadNum =	$_POST['cantidad']; // se refiere a la cantidad en números del PAGO
				$NameCliente =	$_POST['recibide'];
				$CantLetras	 =	$_POST['lasumade'];
				$Concepto 	 =	$_POST['concepto'];
				$vendedor 	 =	$_POST['vendedor'];
				$fecha       = isset($fecha) ? $fecha : date('Y-m-d');

				mysqli_begin_transaction($MySQLi); // INICIA TRANSACCIÓN
				try {
				if ($Moneda=='USD') {
					//	PRIMERO, GUARDAMOS LOS DATOS DEL RECIBO
					$insertDataRecibo = mysqli_query($MySQLi, "INSERT INTO Recibos (idCotizacion, CodeCotizacion, idUser, idCliente, Cliente, Moneda, PrecioDolar, Cantidad, CantidadUSD, Cant_Letras, Concepto, Fecha, Sucursal) VALUES ('$idCotiza', '$CodeCotiza', '$idUser', '$idCliente', '$NameCliente', '$Moneda', '$PrecioDolar', '0', '$CantidadNum', '$CantLetras', '$Concepto', '$fecha', '$Sucursal')");
                	if (!$insertDataRecibo) throw new Exception(mysqli_error($MySQLi));
				}else{
					//	PRIMERO, GUARDAMOS LOS DATOS DEL RECIBO
					$enBs =	$CantidadNum;
					$enUSD=	$enBs/$PrecioDolar;
					
					$insertDataRecibo = mysqli_query($MySQLi, "INSERT INTO Recibos (idCotizacion, CodeCotizacion, idUser, idCliente, Cliente, Moneda, PrecioDolar, Cantidad, CantidadUSD, Cant_Letras, Concepto, Fecha, Sucursal) VALUES ('$idCotiza', '$CodeCotiza', '$idUser', '$idCliente', '$NameCliente', '$Moneda', '$PrecioDolar', '$enBs', '$enUSD', '$CantLetras', '$Concepto', '$fecha', '$Sucursal')");
                	if (!$insertDataRecibo) throw new Exception(mysqli_error($MySQLi));
				}

				//	SEGUNDO, BUSCAMOS EL NUMERO CORRELATIVO DEL RECIBO RECIEN GUARDADO
				$queryRecibo = mysqli_query($MySQLi, "SELECT idRecibo FROM Recibos WHERE idCotizacion='$idCotiza' ");
            	if (!$queryRecibo) throw new Exception(mysqli_error($MySQLi));
				$dataRecibo 				=	mysqli_fetch_assoc($queryRecibo);
				$idRecibo 					=	$dataRecibo['idRecibo'];

				//	TERCERO, GUARDAMOS LA NOTA DE ENTREGA
				$observacionesYuli='Garantía fallas de fábrica los primeros 3 meses, asistencia técnica en tienda gratis por 1 año presentando la nota de entrega emitida por importadora Yuli. *No cubrimos mal uso del equipo*';
				$insertNotaEntrega 	=	mysqli_query($MySQLi,"INSERT INTO NotaEntrega (idUser, idCliente, idCotizacion, Fecha, Sucursal, Observaciones) VALUES ('$idUser', '$idCliente', '$idCotiza', '$fecha', '$Sucursal', '$observacionesYuli') ");
				if (!$insertNotaEntrega) throw new Exception(mysqli_error($MySQLi));
				
				//	CUARTO, BUSCAMOS EL NUMERO CORRELATIVO DE LA NOTA DE ENTREGA RECIEN AGREGADA
				$queryNotaEntrega = mysqli_query($MySQLi, "SELECT idNotaE FROM NotaEntrega WHERE idCotizacion='$idCotiza' ");
            	if (!$queryNotaEntrega) throw new Exception(mysqli_error($MySQLi));
				$dataNotaEntrega 		=	mysqli_fetch_assoc($queryNotaEntrega);
				$idNotaEntrega 			=	$dataNotaEntrega['idNotaE'];

				//	OBTENEMOS LA CLAVE DE LA COTIZACION
				$queryCotiza = mysqli_query($MySQLi, "SELECT Clave FROM Cotizaciones WHERE idCotizacion='$idCotiza'");
            	if (!$queryCotiza) throw new Exception(mysqli_error($MySQLi));
				$dataCotiza					=	mysqli_fetch_assoc($queryCotiza);
				$ClaveCotizacion		=	$dataCotiza['Clave'];

				//	LLAMAMOS LOS PRODUCTOS DE LA COTIZACION CON LA CLAVE TEMPORAL
				$queryProductos = mysqli_query($MySQLi, "SELECT * FROM ClaveTemporal WHERE Clave='$ClaveCotizacion' ");
            	if (!$queryProductos) throw new Exception(mysqli_error($MySQLi));

				date_default_timezone_set('America/La_Paz');
				$fechaActual = date('c');

				while ($dataProducto=	mysqli_fetch_assoc($queryProductos)) {
					$idProducto				=	$dataProducto['idProducto'];
					$Cantidad 				=	$dataProducto['Cantidad'];
					$PrecioLista 			=	$dataProducto['PrecioLista'];
					$PrecioListaBs			=	$PrecioLista*$PrecioDolar;
					$PrecioVenta 			=	$dataProducto['PrecioOferta']; //este es el precio en dólares por default
					$PrecioVentaBs			=	$PrecioVenta*$PrecioDolar;
					$TotalVentaUS 			=	$Cantidad*$PrecioVenta;
					$TotalVentaBs 			=	$Cantidad*$PrecioVentaBs;					
					$Sucursal 				=	$_POST['Sucursal'];
					//	RESTAMOS LOS PRODUCTOS DE SU RESPECTIVA TIENDA
					//insertamos historial --------------------------------------------------------ini
					$response = new ProductsController();
					$proccess = $response->sale($idProducto, $Sucursal, $Cantidad, $vendedor, 'DESCUENTO STOCK-VENTA COTIZACION: '.$CodeCotiza);
					if ($proccess) {
						//	LUEGO, GUARDAMOS EL REPORTE DE VENTA
						$insertReporteVenta = mysqli_query($MySQLi, "INSERT INTO Ventas (idCotizacion, CodeCotizacion, idUser, idCliente, idRecibo, idEntrega, idProducto, Cantidad, Moneda, PrecioDolar, PrecioListaUSD, PrecioListaBs, PrecioVentaUSD, PrecioVentaBs, Sucursal, Fecha, TotalVentaUS, TotalVentaBs) VALUES ('$idCotiza', '$CodeCotiza', '$idUser', '$idCliente', '$idRecibo', '$idNotaEntrega', '$idProducto', '$Cantidad', '$Moneda', '$PrecioDolar', '$PrecioLista', '$PrecioListaBs', '$PrecioVenta', '$PrecioVentaBs', '$Sucursal', '$fecha', '$TotalVentaUS', '$TotalVentaBs')");
                    	if (!$insertReporteVenta) throw new Exception(mysqli_error($MySQLi));
					} else {
						throw new Exception('Error al descontar stock del producto');
					}
				}
				
				//	CAMBIAMOS EL ESTADO DE LA COTIZACION A "2" QUE SIGNIFICA "COMPRADA"
				$queryCotizacion = mysqli_query($MySQLi, "UPDATE Cotizaciones SET Estado=2, Compra='$fecha' WHERE idCotizacion='$idCotiza' ");
            	if (!$queryCotizacion) throw new Exception(mysqli_error($MySQLi));

				mysqli_commit($MySQLi); // CONFIRMA TRANSACCIÓN
            	mysqli_close($MySQLi);
				?>
				<script type="text/javascript">
					Swal.fire({
						type: 'success',
						title: 'Venta exitosa!',
						animation: false,
						customClass: {
							popup: 'animated bounceInDown'
						}
					})
					setTimeout(function() {
						location.replace("?root=compradas");
					}, 2500)
				</script><?php
				} catch (\Throwable $e) {
					mysqli_rollback($MySQLi); // DESHACE LA TRANSACCIÓN
					mysqli_close($MySQLi);
					?>
					<script type="text/javascript">
						Swal.fire({
							type: 'error',
							title: 'Error en la venta',
							html: '<?php echo json_encode($e->getMessage()); ?>',
							animation: false,
							customClass: {
								popup: 'animated shake'
							}
						})
						setTimeout(function() {
							location.reload();
						}, 5000);
					</script>
					<?php
				}
			}else{ 
				mysqli_close($MySQLi); 
				session_destroy();
				?>
				<script type="text/javascript">
					Swal.fire({
						type: 'error',
						title: 'Sesión caducada',
					})
					setTimeout(function() {
						location.reload();
					}, 2500);
				</script> <?php
			}
		break;
		/*	AREA ENVIO DE CORREO AL CLIENTE	 */
		case 'EnviarCorreoalCliente':
			if (isset($_SESSION['idUser'])) {
				if ($_SESSION['Rango']=='2') {
					if (empty($_POST['remitente'])) {
						echo "<script>$('.noRemitenteMail').removeClass('d-none'); $('.MsjMail').addClass('d-none'); setTimeout(function(){ $('.noRemitenteMail').addClass('d-none'); $('.MsjMail').removeClass('d-none');},3500); </script>";
					}else{
						$FromMail	=	$_POST['remitente'];
						$idUser 	=	$_SESSION['idUser'];
						$idCliente=	$_POST['idCliente'];
						$Correo 	=	$_POST['Correo'];
						$Asunto 	=	$_POST['Asunto'];
						$Contenido=	$_POST['Mensaje'];
						$miCiudad =	$_POST['miCiudad'];

						$mail 		=	$Contenido;
            $titulo 	=	$Asunto;
            $headers 	=	"MIME-Version: 1.0\r\n";
            $headers 	.=	"Content-type: text/html; charset=utf-8\r\n";
            $headers 	.=	"From: Importadora YULI  < ".$FromMail." >\r\n";
            $headers 	.=	"Bcc: Importadora YULI  < ".$FromMail." >\r\n";
            $headers 	.=	"Bcc: Importadora YULI   < support@yuliimport.com\r\n";
            $bool = mail($Correo,$titulo,$mail,$headers);
            /*	INSERTAMOS UN REGISTRO DE ENVIO DE CORREO 	*/
            $insertLog 	=	mysqli_query($MySQLi,"INSERT INTO Log_Correos (idUser, idCliente, Asunto, Mensaje, Fecha, Para, Desde, Sucursal) VALUES ('$idUser', '$idCliente', '$Asunto', '$Contenido', '$fecha', '$Correo', '$FromMail', '$miCiudad') ");
            $increment 	=	mysqli_query($MySQLi,"UPDATE Clientes SET Enviados=Enviados+1  WHERE idCliente='$idCliente' ");
            if ($insertLog AND $increment) { mysqli_close($MySQLi); ?>
<script type="text/javascript">
Swal.fire({
    type: 'success',
    title: 'CORREO ENVIADO!',
    html: 'El correo fué enviado exitosamente.',
    animation: false,
    customClass: {
        popup: 'animated bounceInDown'
    }
})
setTimeout(function() {
    location.reload();
}, 2000);
</script><?php
            }else{ mysqli_close($MySQLi); ?>
<script type="text/javascript">
Swal.fire({
    type: 'error',
    title: 'INSERT LOG ERROR',
})
// setTimeout(function(){
// 	location.reload();
// },2500);
</script> <?php
            }
					}						
				}else{
					$idUser 	=	$_SESSION['idUser'];
					$idCliente 	=	$_POST['idCliente'];
					$Correo 	=	$_POST['Correo'];
					$Asunto 	=	$_POST['Asunto'];
					$Contenido 	=	$_POST['Mensaje'];
					$miCiudad 	=	$_POST['miCiudad'];
					if ($miCiudad=='La Paz') {
						$FromMail=	'ventaslpz@yuliimport.com';
					}elseif ($miCiudad=='Santa Cruz') {
						$FromMail=	'ventasscz@yuliimport.com';
					}elseif ($miCiudad=='Cochabamba') {
						$FromMail=	'ventascbba@yuliimport.com';
					}else{
						$FromMail=	'ventastarija@yuliimport.com';
					}
					$mail 		=	$Contenido;
          $titulo 	=	$Asunto;
          $headers 	=	"MIME-Version: 1.0\r\n";
          $headers 	.=	"Content-type: text/html; charset=utf-8\r\n";
          $headers 	.=	"From: Importadora YULI  < ".$FromMail." >\r\n";
          $headers 	.=	"Bcc: Importadora YULI  < ".$FromMail." >\r\n";
          $headers 	.=	"Bcc: Importadora YULI   < support@yuliimport.com\r\n";
          $bool = mail($Correo,$titulo,$mail,$headers);
          /*	INSERTAMOS UN REGISTRO DE ENVIO DE CORREO 	*/
          $insertLog 	=	mysqli_query($MySQLi,"INSERT INTO Log_Correos (idUser, idCliente, Asunto, Mensaje) VALUES ('$idUser', '$idCliente', '$Asunto', '$Contenido') ");
          $increment 	=	mysqli_query($MySQLi,"UPDATE Clientes SET Enviados=Enviados+1  WHERE idCliente='$idCliente' ");
          if ($insertLog AND $increment) { mysqli_close($MySQLi); ?>
<script type="text/javascript">
Swal.fire({
    type: 'success',
    title: 'CORREO ENVIADO!',
    html: 'El correo fué enviado exitosamente.',
    animation: false,
    customClass: {
        popup: 'animated bounceInDown'
    }
})
setTimeout(function() {
    location.reload();
}, 2000);
</script><?php
          }else{ mysqli_close($MySQLi); ?>
<script type="text/javascript">
Swal.fire({
    type: 'error',
    title: 'INSERT LOG ERROR',
})
// setTimeout(function(){
// 	location.reload();
// },2500);
</script> <?php
          }
				}
			}else{ mysqli_close($MySQLi); session_destroy(); ?>
<script type="text/javascript">
Swal.fire({
    type: 'error',
    title: 'Sesión caducada',
})
setTimeout(function() {
    location.reload();
}, 2500);
</script> <?php
			}
		break;
		/* 	NOTA DE ENTREGA	*/
		case 'GuardarObservacionenNotadeEntrega':
			if (isset($_SESSION['idUser'])) {
				$idNotaE 	=	$_POST['idNotaEntrega'];
				$Observa 	=	$_POST['observaciones'];
				$updateObsv =	mysqli_query($MySQLi,"UPDATE NotaEntrega SET Observaciones='$Observa' WHERE idNotaE='$idNotaE' ")or die(mysqli_error($MySQLi)); 
				if ($updateObsv) { mysqli_close($MySQLi); ?>
<script type="text/javascript">
Swal.fire({
    type: 'success',
    title: 'Observaciones guardadas!',
    animation: false,
    customClass: {
        popup: 'animated bounceInDown'
    }
})
setTimeout(function() {
    location.reload();
}, 2000);
</script><?php
				}else{ mysqli_close($MySQLi); ?>
<script type="text/javascript">
Swal.fire({
    type: 'error',
    title: 'Error Observaciones!',
    animation: false,
    customClass: {
        popup: 'animated shake'
    }
})
</script><?php
				}				
			}else{ mysqli_close($MySQLi); session_destroy(); ?>
<script type="text/javascript">
Swal.fire({
    type: 'error',
    title: 'Sesión caducada',
})
setTimeout(function() {
    location.reload();
}, 2500);
</script> <?php
			}
		break;
		case 'AgregarObservacionesNotaE':
			if (isset($_SESSION['idUser'])) {
				$idNotaE 	=	$_POST['idNotaE'];
				$Observa 	=	$_POST['Observaciones'];
				$updateObsv =	mysqli_query($MySQLi,"UPDATE NotaEntrega SET Observaciones='$Observa' WHERE idNotaE='$idNotaE' ")or die(mysqli_error($MySQLi)."<br>Error en la línea: ".__LINE__); 
				if ($updateObsv) { mysqli_close($MySQLi); ?>
<script type="text/javascript">
Swal.fire({
    type: 'success',
    title: 'Observaciones guardadas!',
    animation: false,
    customClass: {
        popup: 'animated bounceInDown'
    }
})
setTimeout(function() {
    location.reload();
}, 2000);
</script><?php
				}else{ mysqli_close($MySQLi); ?>
<script type="text/javascript">
Swal.fire({
    type: 'error',
    title: 'Error Observaciones!',
    animation: false,
    customClass: {
        popup: 'animated shake'
    }
})
</script><?php
				}				
			}else{ mysqli_close($MySQLi); session_destroy(); ?>
<script type="text/javascript">
Swal.fire({
    type: 'error',
    title: 'Sesión caducada',
})
setTimeout(function() {
    location.reload();
}, 2500);
</script> <?php
			}
		break;
		/* 	REPORTES DE VENTAS	*/
		case 'BuscarVentasporFecha':
			if (isset($_SESSION['idUser'])) {
				$idUser 		=	$_SESSION['idUser'];
				$FechaInicio	=	$_POST['inicio'];
				$FechaFin 		=	$_POST['fin'];
				$Num = 1;
				$ConsultaVenta	=	mysqli_query($MySQLi,"SELECT idVenta, Cotizacion, idUser, idCliente, idProducto, Cantidad, PrecioLista, PrecioVenta, Sucursal, Observaciones, DATE_FORMAT(Fecha, '%d-%m-%Y')AS Fecha FROM Ventas WHERE idUser='$idUser' AND Fecha BETWEEN '$FechaInicio' AND '$FechaFin' ORDER BY Fecha DESC ");
				$resultVentas 	=	mysqli_num_rows($ConsultaVenta);
				if ($resultVentas>0) { 
					while ($dataVentas = mysqli_fetch_assoc($ConsultaVenta)) { ?>
<tr>
    <td class="text-center"><?php echo $Num; ?></td>
    <td class="text-center"><?php echo $dataVentas['Fecha'] ?></td>
    <td class="text-center"><?php echo $dataVentas['Cotizacion']  ?></td>
    <?php
								$idCliente	=	$dataVentas['idCliente'];
								$queryCliente=	mysqli_query($MySQLi,"SELECT * FROM Clientes WHERE idCliente='$idCliente' ");
								$dataCliente=	mysqli_fetch_assoc($queryCliente);
								$NameCliente=	$dataCliente['Nombres']." ".$dataCliente['Apellidos'];

								$idProducto	=	$dataVentas['idProducto'];
								$queryProduc=	mysqli_query($MySQLi,"SELECT * FROM Productos WHERE idProducto='$idProducto' ");
								$dataProduct=	mysqli_fetch_assoc($queryProduc);
							?>
    <td><?php echo $NameCliente ?></td>
    <td><?php echo $dataProduct['Producto']  ?></td>
    <td class="text-center"><?php echo $dataProduct['Marca']  ?></td>
    <td class="text-center"><?php echo $dataProduct['Modelo']  ?></td>
    <td class="text-center"><?php echo $dataVentas['Cantidad']  ?></td>
    <td class="text-center">$&nbsp;&nbsp;<?php echo $dataVentas['PrecioVenta'] ?></td>
    <td class="text-center">$&nbsp;&nbsp;<?php echo $dataVentas['PrecioVenta']*$dataVentas['Cantidad'] ?></td>
</tr><?php $Num++; }mysqli_close($MySQLi);
				}else{ ?>
<tr>
    <td colspan="10" class="text-center text-danger">NO HAY RESULTADOS</td>
</tr><?php
				}
			}else{ mysqli_close($MySQLi); session_destroy(); ?>
<script type="text/javascript">
Swal.fire({
    type: 'error',
    title: 'Sesión caducada',
})
setTimeout(function() {
    location.reload();
}, 2500);
</script> <?php
			}
		break;
		case 'BuscarReporteporFecha':
			if (isset($_SESSION['idUser'])) {
				$FechaInicio	=	$_POST['inicio'];
				$FechaFin 		=	$_POST['fin'];
				$Num = 1; ?>
<table id="data-table-buttonss" class="table table-striped table-bordered table-td-valign-middle w-100">
    <thead>
        <tr>
            <th class="text-center">N&ordm;</th>
            <th class="text-center">FECHA</th>
            <th class="text-center">RECIBO</th>
            <th class="text-center">CODIGO</th>
            <th class="text-center">FACTURA</th>
            <th class="text-center">CLIENTE</th>
            <th class="text-center">NIT</th>
            <th class="text-center">TELEFONO</th>
            <th class="text-center">PRODUCTO</th>
            <th class="text-center">MARCA</th>
            <th class="text-center">MODELO</th>
            <th class="text-center">CANT</th>
            <th class="text-center">PRE_LISTA</th>
            <th class="text-center">DESC</th>
            <th class="text-center">PRE_VENTA</th>
            <th class="text-center">Bs</th>
            <th class="text-center">PAGO_VENTA Bs</th>
            <th class="text-center">PAGO_VENTA USD</th>
            <th class="text-center">VENDEDOR</th>
            <th class="text-center">SUCURSAL</th>
            <th class="text-center">No.</th>
            <th class="text-center">OBSERVACIONES</th>
        </tr>
    </thead>
    <tbody id="Tabla_idReportes">
        <?php
							$Num 		=	1;
							$queryVenta	=	mysqli_query($MySQLi,"SELECT idVenta, Cotizacion, idUser, idCliente, idProducto, Cantidad, PrecioLista, PrecioVenta, Sucursal, Observaciones, DATE_FORMAT(Fecha, '%d-%m-%Y') AS Fecha FROM Ventas WHERE Fecha BETWEEN '$FechaInicio' AND '$FechaFin' ORDER BY idVenta DESC")or die(mysqli_error($MySQLi));
							//$resultBuscar=	mysqli_num_rows($queryVenta);
							while ($dataVentas = mysqli_fetch_assoc($queryVenta)) {
						?>
        <tr>
            <td class="text-center"><?php echo $Num; ?></td>
            <td class="text-center"><?php echo $dataVentas['Fecha'] ?></td>
            <td class="text-center">&nbsp;&nbsp;</td>
            <td class="text-center"><?php echo $dataVentas['Cotizacion']  ?></td>
            <td class="text-center">&nbsp;&nbsp;</td>
            <?php
								$idCliente		=	$dataVentas['idCliente'];
								$consultCliente =	mysqli_query($MySQLi,"SELECT * FROM Clientes WHERE idCliente='$idCliente' ");
								$datosCliente	=	mysqli_fetch_assoc($consultCliente);
								
								$idUsuario		=	$dataVentas['idUser'];
								$consultUsuario	=	mysqli_query($MySQLi,"SELECT Nombres, Apellidos FROM Usuarios WHERE idUser='$idUsuario' ");
								$datosUsuario	=	mysqli_fetch_assoc($consultUsuario);
								$Vendedor 		=	$datosUsuario['Nombres']." ".$datosUsuario['Apellidos'];

								$idProducto 	=	$dataVentas['idProducto'];
								$consultProducto=	mysqli_query($MySQLi,"SELECT Producto, Marca, Modelo FROM Productos WHERE idProducto='$idProducto' ");
								$datosProducto	=	mysqli_fetch_assoc($consultProducto);

								if ($datosCliente['Celular']=='' AND $datosCliente['Otro']=='') {
									$TelCliente	=	'VACÍO';
								}elseif ($datosCliente['Otro']=='') {
									$TelCliente	=	$datosCliente['Celular'];
								}elseif ($datosCliente['Celular']=='') {
									$TelCliente	=	$datosCliente['Otro'];
								}else{
									$TelCliente	=	$datosCliente['Celular']." / ".$datosCliente['Otro'];
								}
							?>
            <td><?php echo $datosCliente['Nombres']." ".$datosCliente['Apellidos']; ?></td>
            <td><?php echo $datosCliente['NIT'] ?></td>
            <td><?php echo $TelCliente ?></td>
            <td><?php echo $datosProducto['Producto'] ?></td>
            <td><?php echo $datosProducto['Marca'] ?></td>
            <td><?php echo $datosProducto['Modelo'] ?></td>
            <td><?php echo $dataVentas['Cantidad'] ?></td>
            <td>$&nbsp;&nbsp;<?php echo $dataVentas['PrecioLista'] ?></td>
            <td class="text-center">&nbsp;&nbsp;</td>
            <td>$&nbsp;&nbsp;<?php echo $dataVentas['PrecioVenta'] ?></td>
            <td class="text-center">&nbsp;&nbsp;</td>
            <td class="text-center">&nbsp;&nbsp;</td>
            <td class="text-center">$&nbsp;&nbsp;<?php echo $dataVentas['Cantidad']*$dataVentas['PrecioVenta'] ?></td>
            <td class="text-center"><?php echo $Vendedor  ?></td>
            <td class="text-center"><?php echo $dataVentas['Sucursal']  ?></td>
            <td class="text-center">&nbsp;&nbsp;</td>
            <td class="text-center">&nbsp;&nbsp;</td>
            <!-- <td class="text-center">
								<button class="btn btn-xs btn-danger"><i class="fa fa-trash"></i></button>
							</td> -->
        </tr> <?php $Num++; } mysqli_close($MySQLi); ?>
    </tbody>
</table><?php
			}else{ mysqli_close($MySQLi); session_destroy(); ?>
<script type="text/javascript">
Swal.fire({
    type: 'error',
    title: 'Sesión caducada',
})
setTimeout(function() {
    location.reload();
}, 2500);
</script>
<?php
			}
		break;
		case 'Actualizar Metas y Comisiones':
			if (isset($_SESSION['idUser'])) {
				$idTabla 		=	$_POST['idTabla'];
				$metaMinima		=	$_POST['metaMinima'];
				$comisionMinima	=	$_POST['comisionMinima'];
				$metaMaxima 	=	$_POST['metaMaxima'];
				$comisionMaxima	=	$_POST['comisionMaxima'];
				/*	VERIFICAMOS SI LOS DATOS HAN CAMBIADO	*/
				$queryTablaMetas=	mysqli_query($MySQLi,"SELECT * FROM TablaComisiones WHERE idTabla='$idTabla' ");
				$dataTabla 		=	mysqli_fetch_assoc($queryTablaMetas);
				if ($metaMinima==$dataTabla['Meta1'] AND $comisionMinima==$dataTabla['Comision1'] AND $metaMaxima==$dataTabla['Meta2'] AND $comisionMaxima['Comision2']) { ?>
<script type="text/javascript">
Swal.fire({
    type: 'error',
    title: 'No hay cambios que guardar!',
    animation: false,
    customClass: {
        popup: 'animated shake'
    }
})
</script><?php
				}else{
					$guardarCambios=	mysqli_query($MySQLi,"UPDATE TablaComisiones SET Meta1='$metaMinima', Comision1='$comisionMinima', Meta2='$metaMaxima', Comision2='$comisionMaxima' WHERE idTabla='$idTabla' ")or die(mysqli_error($MySQLi));
					if ($guardarCambios) { ?>
<script type="text/javascript">
Swal.fire({
    type: 'success',
    title: 'Datos actualizados!',
    animation: false,
    customClass: {
        popup: 'animated bounceInDown'
    }
})
setTimeout(function() {
    location.reload();
}, 2000);
</script><?php
					}else{ ?>
<script type="text/javascript">
Swal.fire({
    type: 'error',
    title: 'Error al guardar cambios',
    animation: false,
    customClass: {
        popup: 'animated shake'
    }
})
</script><?php
					}
				}
			}else{ mysqli_close($MySQLi); session_destroy(); ?>
<script type="text/javascript">
Swal.fire({
    type: 'error',
    title: 'Sesión caducada',
})
setTimeout(function() {
    location.reload();
}, 2500);
</script> <?php
			}
		break;
		case 'buscarMis_Comisiones':
			if (isset($_SESSION['idUser'])) {
				$idUser 	=	$_POST['idUser'];
				$Sucursal =	$_POST['sucursal'];
				$Inicio 	=	$_POST['inicio'];
				$Fin 			=	$_POST['fin'];
				/*	BUSCAMOS LA CANTIDAD DE USUARIOS DE ESA TIENDA	*/
				$findUsers 	=	mysqli_query($MySQLi,"SELECT * FROM Usuarios WHERE Ciudad='$Sucursal' ");
				$cantUsers 	=	mysqli_num_rows($findUsers);

				/*	BSCAMOS EL TOTAL DE VENTAS DE LA SUCURSAL ENTERA	*/
				$queryVentas	=	mysqli_query($MySQLi,"SELECT SUM(TotalVentaUS)AS TotalVentaUS FROM Ventas WHERE Fecha BETWEEN '$Inicio' AND  '$Fin' AND Sucursal='$Sucursal' ")or die(mysqli_error($MySQLi));
				// $queryVentas	=	mysqli_query($MySQLi,"SELECT SUM(TotalVentaUS)AS TotalVentaUS FROM Ventas WHERE idUser='$idUser' AND Fecha BETWEEN '$startBusqueda' AND  '$fecha' ")or die(mysqli_error($MySQLi));
				$dataVentas		=	mysqli_fetch_assoc($queryVentas);
				$TotalVentas 	=	$dataVentas['TotalVentaUS'];

				/*	AGREGAMOS LAS COMPRAS AL CRÉDITO CANCELADAS	*/
				// $queryCredit 	=	mysqli_query($MySQLi,"SELECT SUM(TotalUSD)AS TotalUSD FROM Creditos WHERE SaldoActual=0 AND Fecha BETWEEN '$startBusqueda' AND  '$fecha' ")or die(mysqli_error($MySQLi));
				// $dataCredit 	=	mysqli_fetch_assoc($queryCredit);
				// $TotalCredit 	=	$dataCredit['TotalUSD'];
				// if (empty($TotalCredit)) {
				// 	$TotalCredit=0;
				// }

				/*	AGREGAMOS LAS COMPRAS POR ABONO CANCELADAS	*/
				// $queryAbonos  	=	mysqli_query($MySQLi,"SELECT SUM(TotalUSD)AS TotalUSD FROM Abonos WHERE SaldoActual=0 AND Fecha BETWEEN '$startBusqueda'AND '$fecha' ")or die(mysqli_error($MySQLi));
				// $dataAbonos 	=	mysqli_fetch_assoc($queryAbonos);
				// $TotalAbonos  	=	$dataAbonos['TotalUSD'];
				// if (empty($TotalAbonos)) {
				// 	$TotalAbonos=0;
				// }
				// $GranTotal 		=	$TotalVentas+$TotalCredit+$TotalAbonos;
				
				/*	BUSCAMOS LA TABLA DE COMISIONES	*/
				$Comisiones	=	mysqli_query($MySQLi,"SELECT * FROM TablaComisiones WHERE Sucursal='$Sucursal' ");
				$dataComi 	=	mysqli_fetch_assoc($Comisiones);

				if ($TotalVentas>=$dataComi['Meta2']) {
					$MiComision =	number_format(((($TotalVentas*$dataComi['Comision2'])/100)/$cantUsers),2);
					echo "$ ".$MiComision." dólares";;
				}elseif ($TotalVentas>=$dataComi['Meta1']) {
					$MiComision =	number_format(((($TotalVentas*$dataComi['Comision1'])/100)/$cantUsers),2);
					echo "$ ".$MiComision." dólares";
				}else{
					echo "$ 0.00 No llegaron a la meta";
				}

			}else{ mysqli_close($MySQLi); session_destroy(); ?>
<script type="text/javascript">
Swal.fire({
    type: 'error',
    title: 'Sesión caducada',
})
setTimeout(function() {
    location.reload();
}, 2500);
</script> <?php
			}
		break;
		case 'DescargarTablaProductos':
		break;
		case 'actualizarDolar':
			if (isset($_SESSION['idUser'])) {
				if ($_SESSION['Rango']==2) {
					$Precio = $_POST['precio'];
					$update = mysqli_query($MySQLi,"UPDATE precio SET precioDolar='$Precio' ");mysqli_close($MySQLi); ?>
<script type="text/javascript">
Swal.fire({
    type: 'success',
    title: 'Precio dólar Actualizado',
    animation: false,
    customClass: {
        popup: 'animated bounceInDown'
    }
})
setTimeout(function() {
    location.reload();
}, 2000);
</script><?php
				}else{ mysqli_close($MySQLi); ?>
<script type="text/javascript">
Swal.fire({
    type: 'error',
    title: 'SIN AUTORIZACIÓN!',
    html: 'No tienes los privilegios de Administrador para agregar una sucursal.',
    animation: false,
    customClass: {
        popup: 'animated shake'
    }
})
</script> <?php exit();
				}
			}else{ mysqli_close($MySQLi); session_destroy(); ?>
<script type="text/javascript">
Swal.fire({
    type: 'error',
    title: 'Sesión caducada',
})
setTimeout(function() {
    location.reload();
}, 2500);
</script> <?php
			}
		break;
		case 'BorrarClienteCall':
			if (isset($_SESSION['idUser'])) {
				if ($_SESSION['Rango']==2) {
					$idCliente = $_POST['idCliente'];
					
					
					$queryProducto 	=	mysqli_query($MySQLi,"SELECT Correo FROM Usuarios WHERE idUser='" . $_SESSION['idUser']. "' ");
					$dataProducto 	=	mysqli_fetch_assoc($queryProducto);
					$u 	=	$dataProducto['Correo'];
					
					$queryProducto 	=	mysqli_query($MySQLi,"SELECT * FROM Clientes WHERE idCliente='$idCliente'");
					$dataProducto 	=	mysqli_fetch_assoc($queryProducto);
					$nom 	=	$dataProducto['Nombres'] . ' ' . $dataProducto['Apellidos'] . ', ' . $dataProducto['NIT'];
					
					$a = 'Borro cliente ' . $idCliente . ', ' . $nom;
					mysqli_query($MySQLi,"insert into logs values(now(),'$u','$a')");
					
					
                    $deleteClte= mysqli_query($MySQLi,"DELETE FROM Clientes WHERE idCliente='$idCliente' ");					
					mysqli_close($MySQLi); ?>
<script type="text/javascript">
Swal.fire({
    type: 'success',
    title: 'Cliente borrado exitosamente',
    animation: false,
    customClass: {
        popup: 'animated bounceInDown'
    }
})
setTimeout(function() {
    location.reload();
}, 2000);
</script><?php
				}else{ mysqli_close($MySQLi); ?>
<script type="text/javascript">
Swal.fire({
    type: 'error',
    title: 'SIN AUTORIZACIÓN!',
    html: 'No tienes los privilegios de Administrador para agregar una sucursal.',
    animation: false,
    customClass: {
        popup: 'animated shake'
    }
})
</script> <?php exit();
				}
			}else{ mysqli_close($MySQLi); session_destroy(); ?>
<script type="text/javascript">
Swal.fire({
    type: 'error',
    title: 'Sesión caducada',
})
setTimeout(function() {
    location.reload();
}, 2500);
</script> <?php
			}
		break;
		case 'eliminarVentaDirecta':
			if (isset($_SESSION['idUser'])) {
				if ($_SESSION['Rango']==2) {
					$idCotizacion 	= $_POST['idCotizacion'];
					
            		mysqli_begin_transaction($MySQLi); // INICIA TRANSACCIÓN
					try {
						/*	COPIAMOS LA COTIZACION A LA TABLA CotMod y SUS CLAVES A ClaveTempMod	*/
						copiarTablas($MySQLi,$idCotizacion);
						/*	CAMBIAMOS EL ESTADO DE LA COTIZACION A 0	*/
						if (!mysqli_query($MySQLi,"UPDATE Cotizaciones SET Estado=0 WHERE idCotizacion='$idCotizacion' ")) throw new Exception(mysqli_error($MySQLi));
						/*	CAMBIAMOS EL ESTADO DE LA NOTA DE ENTREGA A 1 QUE SIGNIFICA QUE HA SIDO MODIFICADA	*/
						if (!mysqli_query($MySQLi,"UPDATE NotaEntrega SET Estado=1 WHERE idCotizacion='$idCotizacion' ")) throw new Exception(mysqli_error($MySQLi));
						/*	CAMBIAMOS EL ESTADO DEL RECIBO A 1 QUE SIGNIFICA QUE HA SIDO MODIFICADO 	*/
						if (!mysqli_query($MySQLi,"UPDATE Recibos SET Estado=1 WHERE idCotizacion='$idCotizacion' ")) throw new Exception(mysqli_error($MySQLi));
						/*	CONSULTAMOS LA CLAVE DE LA COTIZACION 	*/
						$queryClave			= mysqli_query($MySQLi,"SELECT * FROM Cotizaciones WHERE idCotizacion='$idCotizacion' ");
						if (!$queryClave) throw new Exception(mysqli_error($MySQLi));
						$dataClave 			= mysqli_fetch_assoc($queryClave);
						$Clave 				= $dataClave['Clave'];
						$Sucursal 			= $dataClave['Sucursal'];
						$idVendedor 		= $dataClave['idUser'];
						$idCliente 			= $dataClave['idCliente'];
						/*	DEVOLVEMOS LOS PRODUCTOS AL STOCK CORRESPONDIENTE	*/
						if (!devolverProductos($MySQLi,$Sucursal,$Clave)) throw new Exception('Error al devolver productos');
						/*	INSERTAMOS LA NOTA DE CREDITO AL CLIENTE	*/
						if (!notaCredito($MySQLi,$idCotizacion,$idVendedor,$idCliente,$Sucursal,$fecha)) throw new Exception('Error al crear nota de crédito');						/*	MODIFICAMOS EL TOTAL DE VENTAS A SALDO NEGATIVO	*/
						if (!modificarVentaCash($MySQLi,$idCotizacion)) throw new Exception('Error al modificar venta');	
						mysqli_commit($MySQLi); // CONFIRMA TRANSACCIÓN					
						mysqli_close($MySQLi); ?>
						<script type="text/javascript">
							Swal.fire({
								type: 'success',
								title: 'Venta Modificada',
								//html: "El ID modificado fué: "+idCotizacion,
								animation: false,
								customClass: {
									popup: 'animated bounceInDown'
								}
							})
							setTimeout(function() {
								location.replace("?root=generadas");
							}, 2000);
						</script><?php
					} catch (Exception $e) {
						mysqli_rollback($MySQLi); // DESHACE LA TRANSACCIÓN
						mysqli_close($MySQLi); ?>
						<script type="text/javascript">
							Swal.fire({
								type: 'error',
								title: 'Error al modificar venta',
								html: '<?php echo addslashes($e->getMessage()); ?>',
								animation: false,
								customClass: {
									popup: 'animated shake'
								}
							})
						</script><?php
					}
				}else{ mysqli_close($MySQLi); ?>
					<script type="text/javascript">
					Swal.fire({
						type: 'error',
						title: 'SIN AUTORIZACIÓN!',
						animation: false,
						customCWlass: {
							popup: 'animated shake'
						}
					})
					</script> <?php exit();
				}
			}else{ mysqli_close($MySQLi); session_destroy(); ?>
				<script type="text/javascript">
				Swal.fire({
					type: 'error',
					title: 'Sesión caducada',
				})
				setTimeout(function() {
					location.reload();
				}, 2500);
				</script> <?php
			}
		break;

		// eliminar credito en proceso
		case 'eliminarCreditoProceso':
			if (isset($_SESSION['idUser'])) {
				if ($_SESSION['Rango']==2) {
					$idCotizacion 	= $_POST['idCotizacion'];
					/*	COPIAMOS LA COTIZACION A LA TABLA CotMod y SUS CLAVES A ClaveTempMod	*/
					copiarTablas($MySQLi,$idCotizacion);
					/*	CAMBIAMOS EL ESTADO DE LA COTIZACION A 0	*/
					mysqli_query($MySQLi,"UPDATE Cotizaciones SET Estado=0 WHERE idCotizacion='$idCotizacion' ");
					/*	CAMBIAMOS EL ESTADO DE LA NOTA DE ENTREGA A 1 QUE SIGNIFICA QUE HA SIDO MODIFICADA	*/
					mysqli_query($MySQLi,"UPDATE NotaEntrega SET Estado=1 WHERE idCotizacion='$idCotizacion' ");
					/*	CAMBIAMOS EL ESTADO DEL RECIBO A 1 QUE SIGNIFICA QUE HA SIDO MODIFICADO 	*/
					mysqli_query($MySQLi,"UPDATE Recibos SET Estado=1 WHERE idCotizacion='$idCotizacion' ");
					/*	CONSULTAMOS LA CLAVE DE LA COTIZACION 	*/
					$queryClave			= mysqli_query($MySQLi,"SELECT * FROM Cotizaciones WHERE idCotizacion='$idCotizacion' ");
					$dataClave 			= mysqli_fetch_assoc($queryClave);
					$Clave 					= $dataClave['Clave'];
					$Sucursal 			= $dataClave['Sucursal'];
					$idVendedor 		= $dataClave['idUser'];
					$idCliente 			= $dataClave['idCliente'];
					/*	DEVOLVEMOS LOS PRODUCTOS AL STOCK CORRESPONDIENTE	*/
					devolverProductos($MySQLi,$Sucursal,$Clave);
					/*	INSERTAMOS LA NOTA DE CREDITO AL CLIENTE	*/
					//notaCredito($MySQLi,$idCotizacion,$idVendedor,$idCliente,$Sucursal,$fecha);
					/*	MODIFICAMOS EL TOTAL DE VENTAS A SALDO NEGATIVO	*/
					//modificarVentaCash($MySQLi,$idCotizacion);
					mysqli_close($MySQLi); ?>
<script type="text/javascript">
Swal.fire({
    type: 'success',
    title: 'Credito Eliminado',
    //html: "El ID modificado fué: "+idCotizacion,
    animation: false,
    customClass: {
        popup: 'animated bounceInDown'
    }
})
setTimeout(function() {
    location.replace("?root=generadas");
}, 2000);
</script><?php
				}else{ mysqli_close($MySQLi); ?>
<script type="text/javascript">
Swal.fire({
    type: 'error',
    title: 'SIN AUTORIZACIÓN!',
    animation: false,
    customClass: {
        popup: 'animated shake'
    }
})
</script> <?php exit();
				}
			}else{ mysqli_close($MySQLi); session_destroy(); ?>
<script type="text/javascript">
Swal.fire({
    type: 'error',
    title: 'Sesión caducada',
})
setTimeout(function() {
    location.reload();
}, 2500);
</script> <?php
			}
		break;


		case 'BorrarVentaporAnticipo':
			if (isset($_SESSION['idUser'])) {
				if ($_SESSION['Rango']==2) {
					$idCotizacion 	= $_POST['idCotizacion'];
					/*	OBTENEMOS EL TOTAL ABONADO HASTA LA FECHA 	*/
					$TotalAbonos		= mysqli_query($MySQLi,"SELECT SUM(porAnticipo)AS Bs, SUM(anticipoUSD)AS USD, Moneda, PrecioDolar, idUser, idCliente, Cliente, Sucursal, CodeCotizacion FROM Abonos WHERE idCotizacion='$idCotizacion' AND Estado=0 ");
					$dataAbonos 		= mysqli_fetch_assoc($TotalAbonos);
					$anticipoUSD		= $dataAbonos['USD'];
					$anticipoBs			= $dataAbonos['Bs'];
					$Moneda 				= $dataAbonos['Moneda'];
					$PrecioDolar 		= $dataAbonos['PrecioDolar'];
					$idCliente 			= $dataAbonos['idCliente'];
					$Cliente 				= $dataAbonos['Cliente'];
					$idVendedor 		= $dataAbonos['idUser'];
					$Sucursal 			= $dataAbonos['Sucursal'];
					$CodeCotizacion = $dataAbonos['CodeCotizacion'];
					/*	CREAMOS LA NOTA DE CRÉDITO A FAVOR DEL CLIENTE 	*/
					if ($Moneda   	=='USD') {
						$USD 					= $anticipoUSD;
						$Bs 					= $anticipoUSD*$PrecioDolar;
					}else{
						$USD 					= $anticipoBs/$PrecioDolar;
						$Bs 					= $anticipoBs;
					}
					mysqli_query($MySQLi,"INSERT INTO notasCredito (idUser, idCliente, MontoUSD, MontoBs, Fecha) VALUES ('$idVendedor', '$idCliente', '$USD', '$Bs', '$fecha') ");
					/*	DUPLICAMOS LOS ABONOS A LA TABLA ABONOS MODIFICADOS	*/
					$sqlAbonos 		= mysqli_query($MySQLi,"SELECT * FROM Abonos WHERE idCotizacion='$idCotizacion'AND Estado=0 ");
					while ( $data = mysqli_fetch_assoc($sqlAbonos)) {
						$idAbono 		= $data['idAbono'];
						$idRecibo 	= $data['idRecibo'];
						//==================================================
						$LaCantidadDe 	=	$data['LaCantidadDe'];
						$EnConceptoDe 	=	$data['EnConceptoDe'];						
						$anticipo_USD 	= $data['anticipoUSD'];
						$anticipo_Bs 		= $data['porAnticipo'];
						$SaldoAnterior 	= $data['SaldoAnterior'];
						$SaldoActual 		= $data['SaldoActual'];
						$Total 					= $data['Total']; // total en bolivianos
						$TotalUSD 			= $data['TotalUSD'];
						$FechaAbono 		= $data['Fecha'];						
						mysqli_query($MySQLi,"INSERT INTO AbonosModificados (idRecibo, idUser, idCliente, Cliente, Sucursal, idCotizacion, CodeCotizacion, Moneda, PrecioDolar, LaCantidadDe, EnConceptoDe, porAnticipo, anticipoUSD, SaldoAnterior, SaldoActual, Total, TotalUSD, Fecha) VALUES ('$idRecibo', '$idVendedor', '$idCliente', '$Cliente', '$Sucursal', '$idCotizacion', '$CodeCotizacion', '$Moneda', '$PrecioDolar', '$LaCantidadDe', '$EnConceptoDe', '$anticipo_Bs', '$anticipo_USD', '$SaldoAnterior', '$SaldoActual', '$Total', '$TotalUSD', '$FechaAbono') ")or die(mysqli_error($MySQLi."<br>Error en la línea: ".__LINE__));
					}
					//Revisar si los estados de los recibos deben cambiar a estado 1
					copiarTablas_Abonos($MySQLi,$idCotizacion);
					modificarAbonos($MySQLi,$idCotizacion);
					/*	CAMBIAMOS EL ESTAD DE LA COTIZACION A GENERADA	*/
					changeStatusCoti($MySQLi,$idCotizacion);
					mysqli_close($MySQLi); ?>
<script type="text/javascript">
Swal.fire({
    type: 'success',
    title: 'Venta Abonos eliminada',
    animation: false,
    customClass: {
        popup: 'animated bounceInDown'
    }
})
setTimeout(function() {
    location.replace("?root=generadas");
}, 2000);
</script><?php
				}else{ mysqli_close($MySQLi); ?>
<script type="text/javascript">
Swal.fire({
    type: 'error',
    title: 'SIN AUTORIZACIÓN!',
    animation: false,
    customClass: {
        popup: 'animated shake'
    }
})
</script> <?php exit();
				}
			}else{ mysqli_close($MySQLi); session_destroy(); ?>
<script type="text/javascript">
Swal.fire({
    type: 'error',
    title: 'Sesión caducada',
})
setTimeout(function() {
    location.reload();
}, 2500);
</script> <?php
			}
		break;
		case 'guardarEnvioStock':
			if (isset($_SESSION['idUser'])) {
				$clave         = $_POST["clave"];
  			$idProducto    = $_POST["idProducto"];
  			$cantidad      = $_POST["cantidad"];
  			$idUser        = $_POST["idUser"];
  			$sucursal      = $_POST["sucursal"];
  			$destino       = $_POST["destino"];
  			$observaciones = $_POST["observaciones"];
  			if ($sucursal  == "Cochabamba") {
  				$fromEnvio 	 = "StockCB";
  			}elseif ($sucursal == "Santa Cruz") {
  				$fromEnvio 	 = "StockSC";
  			}elseif ($sucursal == "La Paz") {
  				$fromEnvio   = "StockLP";
  			}else{
  				$fromEnvio   = "StockTJ";
  			}
  			mysqli_query($MySQLi,"INSERT INTO clavesEnvioStock (clave,idProducto,cantidad) VALUES('$clave', '$idProducto', '$cantidad') ");
  			$sqlClaveTempor= mysqli_query($MySQLi,"SELECT clave FROM envioStock WHERE clave='$clave' ");
  			$resultBusqueda= mysqli_num_rows($sqlClaveTempor);
  			if ($resultBusqueda==0) {
  				mysqli_query($MySQLi,"INSERT INTO envioStock (clave, idUser, desde, hasta, observaciones, fecha, hora)VALUES('$clave', '$idUser', '$sucursal', '$destino', '$observaciones', '$fecha', '$hora') ")or die(mysqli_error($MySQLi)."<br>Error en la línea: ".__LINE__);
  			}
  			mysqli_query($MySQLi,"UPDATE envioStock SET observaciones='$observaciones' WHERE clave='$clave' ");
  			/*	DESCONTAMOS LOS PRODUCTOS ENVIADOS	*/
  			/*$sqlProducto 				= mysqli_query($MySQLi,"SELECT * FROM clavesEnvioStock WHERE clave='$clave' ");
  			while ($dataProducto= mysqli_fetch_assoc($sqlProducto)) {
  				$IDProducto 			= $dataProducto['idProducto'];
  				$cantProducto 		= $dataProducto['cantidad'];
  				mysqli_query($MySQLi,"UPDATE Productos SET $fromEnvio=$fromEnvio-$cantProducto WHERE idProducto='$IDProducto' ")or die(mysqli_error($MySQLi)."<br>Error en la línea: ".__LINE__);
  			}*/
  			echo '<table id="data-table" class="table table-striped table-bordered table-td-valign-middle w-100">
					<thead>
						<tr>
							<th style="padding: 3px" width="5%" class="text-center">N&ordm;</th>
							<th style="padding: 3px" width="55%" class="text-center">Producto</th>
							<th style="padding: 3px" width="10%" class="text-center">Enviado</th>
							<th style="padding: 3px" width="10%" class="text-center">Acciones</th>
						</tr>
					</thead>
					<tbody>';
					$num = 1;
					$consultaClave 		= mysqli_query($MySQLi,"SELECT * FROM clavesEnvioStock WHERE clave='$clave' ");
					$resultClaves 		= mysqli_num_rows($consultaClave);
					while($dataClave 	= mysqli_fetch_assoc($consultaClave)){
						$idClave 				= $dataClave["idClave"];
				    echo '<tr>
				    <td class="text-center">'.$num.'</td>';
				    $thisProducto 	= $dataClave['idProducto'];
				    $consultaProducto = mysqli_query($MySQLi, "SELECT * FROM Productos WHERE idProducto='$thisProducto' ");
				    $dataProducto = mysqli_fetch_assoc($consultaProducto);
				    $ProductoName = $dataProducto['Producto'].' '.$dataProducto['Modelo']; echo'
				    <td>'.$ProductoName.'</td>
        		<td class="text-center">'.$dataClave["cantidad"] .'</td>
        		<td class="text-center">';
        			if ($resultClaves>1) { echo'
        				<button class="btn btn-sm btn-danger cancelarEnvio" id='.$idClave.'><i class="fa fa-trash"></i></button>';
        			} echo'
				    </td></tr>';$num++;
					} echo'</tbody>
				</table>';
     	}
		break;
		case 'cancelarEnvioStock':
			if (isset($_SESSION['idUser'])) {
				$idEnvioStock  = $_POST['id'];
				$clave 				 = $_POST['clave'];
				// $sqlIDEnvio 	 = mysqli_query($MySQLi,"SELECT * FROM clavesEnvioStock WHERE idClave='$idEnvioStock' ")or die(mysqli_error($MySQLi)."<br>Error en la línea: ".__LINE__);
				// $dataClave 		 = mysqli_fetch_assoc($sqlIDEnvio);
				mysqli_query($MySQLi,"DELETE FROM clavesEnvioStock WHERE idClave='$idEnvioStock' ")or die(mysqli_error($MySQLi)."<br>Error en la línea: ".__LINE__);
				echo '<table id="data-table" class="table table-striped table-bordered table-td-valign-middle w-100">
					<thead>
						<tr>
							<th style="padding: 3px" width="5%" class="text-center">N&ordm;</th>
							<th style="padding: 3px" width="55%" class="text-center">Producto</th>
							<th style="padding: 3px" width="10%" class="text-center">Enviado</th>
							<th style="padding: 3px" width="10%" class="text-center">Acciones</th>
						</tr>
					</thead>
					<tbody>';
					$num = 1;
					$consultaClave 		= mysqli_query($MySQLi,"SELECT * FROM clavesEnvioStock WHERE clave='$clave' ");
					$resultClaves 		= mysqli_num_rows($consultaClave);
					while($dataClave 	= mysqli_fetch_assoc($consultaClave)){
						$idClave 				= $dataClave["idClave"];
				    echo '<tr>
				    <td class="text-center">'.$num.'</td>';
				    $thisProducto 	= $dataClave['idProducto'];
				    $consultaProducto = mysqli_query($MySQLi, "SELECT * FROM Productos WHERE idProducto='$thisProducto' ");
				    $dataProducto = mysqli_fetch_assoc($consultaProducto);
				    $ProductoName = $dataProducto['Producto'].' '.$dataProducto['Modelo']; echo'
				    <td>'.$ProductoName.'</td>
        		<td class="text-center">'.$dataClave["cantidad"] .'</td>
        		<td class="text-center">';
        			if ($resultClaves>1) { echo'
        				<button class="btn btn-sm btn-danger cancelarEnvio" id='.$idClave.'><i class="fa fa-trash"></i></button>';
        			} echo'
				    </td></tr>';$num++;
					} echo'</tbody>
				</table>';
      }
		break;
		case 'agregarObservacionEnvioStock':
			if (isset($_SESSION['idUser'])) {
				$clave         = $_POST["clave"];  			
  			$observaciones = $_POST["observaciones"];
  			$sqlEnvios 			= mysqli_query($MySQLi,"SELECT * FROM envioStock WHERE clave='$clave' ");
				$dataEnvio  		= mysqli_fetch_assoc($sqlEnvios);
				$sucursal   		= $dataEnvio['desde'];
				if ($sucursal  	== "Cochabamba") {
  				$fromEnvio 	 	= "StockCB";
  			}elseif ($sucursal == "Santa Cruz") {
  				$fromEnvio 	 	= "StockSC";
  			}elseif ($sucursal == "La Paz") {
  				$fromEnvio   	= "StockLP";
  			}else{
  				$fromEnvio   	= "StockTJ";
  			}

				$sucursalFin   		= $dataEnvio['hasta'];
				date_default_timezone_set('America/La_Paz');
				$fechaActual = date('c');

				$update_observaciones=mysqli_query($MySQLi,"UPDATE envioStock SET observaciones='$observaciones' WHERE clave='$clave' ");

				$sqlClave 			= mysqli_query($MySQLi,"SELECT * FROM clavesEnvioStock WHERE clave='$clave' ");
				while ($dataClave = mysqli_fetch_assoc($sqlClave)) {
					$idProducto 	= $dataClave['idProducto'];
					$cantidad 		= $dataClave['cantidad'];
					$sqlProducto 	= mysqli_query($MySQLi,"SELECT * FROM Productos WHERE idProducto='$idProducto' ");
					$dataProducto = mysqli_fetch_assoc($sqlProducto);
					$stockActual 	= $dataProducto[$fromEnvio];
					mysqli_query($MySQLi,"UPDATE Productos SET $fromEnvio=$stockActual-$cantidad WHERE idProducto='$idProducto' ")or die(mysqli_error($MySQLi)."<br>Error en la línea: ".__LINE__);
				
				//insertamos historial --------------------------------------------------------ini

				$cb = 0;
				$lp = 0;
				$sc = 0;
				$tj = 0;
				$inicial=0;
				$final=0;

				if ($sucursal 		==	'Cochabamba') {
					$cb = $cantidad*-1;
					$inicial = $stockActual;
					$final=$stockActual-$cantidad ;
				}elseif ($sucursal==	'La Paz') {
					$lp = $cantidad*-1;
					$inicial = $stockActual;
					$final=$stockActual-$cantidad ;
				}elseif ($sucursal==	'Santa Cruz') {
					$sc = $cantidad*-1;
					$inicial = $stockActual;
					$final=$stockActual-$cantidad ;
				}else{
					$tj = $cantidad*-1;
					$inicial = $stockActual;
					$final=$stockActual-$cantidad ;
				}

				$producto = $dataProducto['Producto'];

				$idUser = $_SESSION['idUser'];
				$ConsltaUser = mysqli_query($MySQLi, "SELECT * FROM Usuarios WHERE idUser='$idUser' ");
				$datosUser = mysqli_fetch_assoc($ConsltaUser);
				$userPos = $datosUser['Nombres'] . " " . $datosUser['Apellidos'];

				$sql_insert = mysqli_query($MySQLi, "INSERT INTO historial_stock_envios(

					producto,
					inicial,
					cb,
					lp,
					sc,
					tj,
					final,
					vendedor,
					dateEmission,
					descripcion,
					idProducto,
					sucursal

					)
					VALUES(

					'$producto',
					'$inicial',
					'$cb',
					'$lp',
					'$sc',
					'$tj',
					'$final',
					'$userPos',
					'$fechaActual',
					'DESCUENTO STOCK EN $sucursal POR ENVIO A: $sucursalFin',
					'$idProducto',
					'$sucursal'

					)") or die(mysqli_error($MySQLi));
				//insertamos historial --------------------------------------------------------fin
				
				}
  			
			
			?>
			<?php
     	}
		break;
		case 'cancelarProcesoStock':
			if (isset($_SESSION['idUser'])) {
				$idEnvio 							= $_POST['idEnvio'];
				$sqlEnvioStock 				= mysqli_query($MySQLi,"SELECT clave,desde FROM envioStock WHERE idEnvio='$idEnvio' ");
				$dataEnvio 						= mysqli_fetch_assoc($sqlEnvioStock);
				$claveEnvio 					= $dataEnvio['clave'];
				$sucursal   					= $dataEnvio['desde'];
				if ($sucursal  				== "Cochabamba") {
  				$fromEnvio 	 				= "StockCB";
  			}elseif ($sucursal 		== "Santa Cruz") {
  				$fromEnvio 	 				= "StockSC";
  			}elseif ($sucursal 		== "La Paz") {
  				$fromEnvio   				= "StockLP";
  			}else{
  				$fromEnvio   				= "StockTJ";
  			}

			  $sucursalInicio   		= $dataEnvio['desde'];
			  $sucursalFin   		= $dataEnvio['hasta'];
			  date_default_timezone_set('America/La_Paz');
			  $fechaActual = date('c');


				$sqlClaves 						= mysqli_query($MySQLi,"SELECT idProducto, cantidad FROM clavesEnvioStock WHERE clave='$claveEnvio' ");
				while ($dataClave 		= mysqli_fetch_assoc($sqlClaves)) {
					$idProducto 				= $dataClave['idProducto'];
					$sqlProducto 				= mysqli_query($MySQLi,"SELECT * FROM Productos WHERE idProducto='$idProducto' ");
					$data_Producto 			= mysqli_fetch_assoc($sqlProducto);
					$stockActual 				= $data_Producto[$fromEnvio];
					$cantidad 					= $dataClave['cantidad'];
	  			mysqli_query($MySQLi,"UPDATE Productos SET $fromEnvio=$stockActual+$cantidad WHERE idProducto='$idProducto' ")or die(mysqli_error($MySQLi)."<br>Error en la línea: ".__LINE__)or die(mysqli_error($MySQLi)."<br>Error en la línea: ".__LINE__);
				
					//insertamos historial --------------------------------------------------------ini

					$cb = 0;
					$lp = 0;
					$sc = 0;
					$tj = 0;
					$inicial=0;
					$final=0;

					if ($sucursalInicio == 'Cochabamba') {
						$cb = $cantidad;
						$inicial = $stockActual;
						$final=$stockActual+$cantidad ;
					}elseif ($sucursalInicio==	'La Paz') {
						$lp = $cantidad;
						$inicial = $stockActual;
						$final=$stockActual+$cantidad ;
					}elseif ($sucursalInicio==	'Santa Cruz') {
						$sc = $cantidad;
						$inicial = $stockActual;
						$final=$stockActual+$cantidad ;
					}else{
						$tj = $cantidad;
						$inicial = $stockActual;
						$final=$stockActual+$cantidad ;
					}

					$sqlProductoEnviado 	= mysqli_query($MySQLi,"SELECT * FROM Productos WHERE idProducto='$idProducto' ");
					$dataProductoEnviado = mysqli_fetch_assoc($sqlProductoEnviado);
					$productoEnviado = $dataProductoEnviado['Producto'];

				
					$idUser = $_SESSION['idUser'];
					$ConsltaUser = mysqli_query($MySQLi, "SELECT * FROM Usuarios WHERE idUser='$idUser' ");
					$datosUser = mysqli_fetch_assoc($ConsltaUser);
					$userPos = $datosUser['Nombres'] . " " . $datosUser['Apellidos'];

					$sql_insert = mysqli_query($MySQLi, "INSERT INTO historial_stock_envios(

						producto,
						inicial,
						cb,
						lp,
						sc,
						tj,
						final,
						vendedor,
						dateEmission,
						descripcion,
						idProducto,
						sucursal

						)
						VALUES(

						'$productoEnviado',
						'$inicial',
						'$cb',
						'$lp',
						'$sc',
						'$tj',
						'$final',
						'$userPos',
						'$fechaActual',
						'RETORNO STOCK EN $sucursalInicio POR CANCELAR LA OPERACION DE ENVIO A $sucursalFin',
						'$idProducto',
						'$sucursalInicio'

						)") or die(mysqli_error($MySQLi));
					//insertamos historial --------------------------------------------------------fin				
			
			
			
			}
				mysqli_query($MySQLi,"UPDATE envioStock SET estado=2 WHERE idEnvio='$idEnvio' ");
				//mysqli_query($MySQLi,"DELETE FROM clavesEnvioStock WHERE clave='$claveEnvio' ");
				//mysqli_query($MySQLi,"DELETE FROM envioStock WHERE idEnvio='$idEnvio' "); ?><script type="text/javascript">
$("#modalRestoreEnvioStock").modal();
location.replace('?root=enviostock');
</script><?php
     	}
		break;
		case 'descontarStockEnviado':
			if (isset($_SESSION['idUser'])) {
				$clave 		= $_POST['clave'];
				$sqlEnvios 			= mysqli_query($MySQLi,"SELECT * FROM envioStock WHERE clave='$clave' ");
				$dataEnvio  		= mysqli_fetch_assoc($sqlEnvios);
				$sucursal   		= $dataEnvio['desde'];
				if ($sucursal  	== "Cochabamba") {
  				$fromEnvio 	 	= "StockCB";
  			}elseif ($sucursal == "Santa Cruz") {
  				$fromEnvio 	 	= "StockSC";
  			}elseif ($sucursal == "La Paz") {
  				$fromEnvio   	= "StockLP";
  			}else{
  				$fromEnvio   	= "StockTJ";
  			}

				$sucursalFin   		= $dataEnvio['hasta'];
				date_default_timezone_set('America/La_Paz');
				$fechaActual = date('c');
				
				$sqlClave 			= mysqli_query($MySQLi,"SELECT * FROM clavesEnvioStock WHERE clave='$clave' ");
				while ($dataClave = mysqli_fetch_assoc($sqlClave)) {
					$idProducto 	= $dataClave['idProducto'];
					$cantidad 		= $dataClave['cantidad'];
					$sqlProducto 	= mysqli_query($MySQLi,"SELECT * FROM Productos WHERE idProducto='$idProducto' ");
					$dataProducto = mysqli_fetch_assoc($sqlProducto);
					$stockActual 	= $dataProducto[$fromEnvio];
					mysqli_query($MySQLi,"UPDATE Productos SET $fromEnvio=$stockActual-$cantidad WHERE idProducto='$idProducto' ")or die(mysqli_error($MySQLi)."<br>Error en la línea: ".__LINE__);
				
					//insertamos historial --------------------------------------------------------ini

					$cb = 0;
					$lp = 0;
					$sc = 0;
					$tj = 0;
					$inicial=0;
					$final=0;

					if ($sucursal 		==	'Cochabamba') {
						$cb = $cantidad*-1;
						$inicial = $stockActual;
						$final=$stockActual-$cantidad ;
					}elseif ($sucursal==	'La Paz') {
						$lp = $cantidad*-1;
						$inicial = $stockActual;
						$final=$stockActual-$cantidad ;
					}elseif ($sucursal==	'Santa Cruz') {
						$sc = $cantidad*-1;
						$inicial = $stockActual;
						$final=$stockActual-$cantidad ;
					}else{
						$tj = $cantidad*-1;
						$inicial = $stockActual;
						$final=$stockActual-$cantidad ;
					}

					$producto = $dataProducto['Producto'];

					$idUser = $_SESSION['idUser'];
					$ConsltaUser = mysqli_query($MySQLi, "SELECT * FROM Usuarios WHERE idUser='$idUser' ");
					$datosUser = mysqli_fetch_assoc($ConsltaUser);
					$userPos = $datosUser['Nombres'] . " " . $datosUser['Apellidos'];

					$sql_insert = mysqli_query($MySQLi, "INSERT INTO historial_stock_envios(

						producto,
						inicial,
						cb,
						lp,
						sc,
						tj,
						final,
						vendedor,
						dateEmission,
						descripcion,
						idProducto,
						sucursal

						)
						VALUES(

						'$producto',
						'$inicial',
						'$cb',
						'$lp',
						'$sc',
						'$tj',
						'$final',
						'$userPos',
						'$fechaActual',
						'DESCUENTO STOCK EN $sucursal POR ENVIO A: $sucursalFin',
						'$idProducto',
						'$sucursal'

						)") or die(mysqli_error($MySQLi));
					//insertamos historial --------------------------------------------------------fin
					
				}
     	}
		break;
		case 'confirmarEnvioStock':
			if (isset($_SESSION['idUser'])) {				
				$idEnvio 		= $_POST['idEnvio'];
				$sqlEnvios 	= mysqli_query($MySQLi,"SELECT * FROM envioStock WHERE idEnvio='$idEnvio' ");
				$dataEnvio 	= mysqli_fetch_assoc($sqlEnvios);
				$claveEnvio	= $dataEnvio['clave'];
				$sucursal 	= $dataEnvio['hasta'];
				if ($sucursal == 'Cochabamba') {
					$sucursal = 'StockCB';
				}elseif ($sucursal == 'La Paz') {
					$sucursal = 'StockLP';
				}elseif ($sucursal == 'Santa Cruz') {
					$sucursal = 'StockSC';
				}else{
					$sucursal = 'StockTJ';
				}

				$sucursalInicio   		= $dataEnvio['desde'];
				$sucursalFin   		= $dataEnvio['hasta'];
				date_default_timezone_set('America/La_Paz');
				$fechaActual = date('c');
				
				$sqlClaves 	= mysqli_query($MySQLi,"SELECT * FROM clavesEnvioStock WHERE clave='$claveEnvio' ");
				while ($data= mysqli_fetch_assoc($sqlClaves) ) {
					$idProducto 	= $data['idProducto'];
					$cantidad 		= $data['cantidad'];
					$sqlProducto 	= mysqli_query($MySQLi,"SELECT $sucursal FROM Productos WHERE idProducto='$idProducto' ");
					$dataProducto = mysqli_fetch_assoc($sqlProducto);
					$stockActual  = $dataProducto[$sucursal];
					//echo "Cantidad: ".$cantidad."<br>idProducto: ".$idProducto."<br>StockAcual: ".$stockActual;
					mysqli_query($MySQLi,"UPDATE Productos SET $sucursal=$stockActual+$cantidad WHERE idProducto='$idProducto' ")or die(mysqli_error($MySQLi)."<br>Error en la línea: ".__LINE__);
				
					//insertamos historial --------------------------------------------------------ini

					$cb = 0;
					$lp = 0;
					$sc = 0;
					$tj = 0;
					$inicial=0;
					$final=0;

					if ($sucursalFin == 'Cochabamba') {
						$cb = $cantidad;
						$inicial = $stockActual;
						$final=$stockActual+$cantidad ;
					}elseif ($sucursalFin==	'La Paz') {
						$lp = $cantidad;
						$inicial = $stockActual;
						$final=$stockActual+$cantidad ;
					}elseif ($sucursalFin==	'Santa Cruz') {
						$sc = $cantidad;
						$inicial = $stockActual;
						$final=$stockActual+$cantidad ;
					}else{
						$tj = $cantidad;
						$inicial = $stockActual;
						$final=$stockActual+$cantidad ;
					}

					$sqlProductoRecibido 	= mysqli_query($MySQLi,"SELECT * FROM Productos WHERE idProducto='$idProducto' ");
					$dataProductoRecibido = mysqli_fetch_assoc($sqlProductoRecibido);
					$productoRecibido = $dataProductoRecibido['Producto'];

				
					$idUser = $_SESSION['idUser'];
					$ConsltaUser = mysqli_query($MySQLi, "SELECT * FROM Usuarios WHERE idUser='$idUser' ");
					$datosUser = mysqli_fetch_assoc($ConsltaUser);
					$userPos = $datosUser['Nombres'] . " " . $datosUser['Apellidos'];

					$sql_insert = mysqli_query($MySQLi, "INSERT INTO historial_stock_envios(

						producto,
						inicial,
						cb,
						lp,
						sc,
						tj,
						final,
						vendedor,
						dateEmission,
						descripcion,
						idProducto,
						sucursal

						)
						VALUES(

						'$productoRecibido',
						'$inicial',
						'$cb',
						'$lp',
						'$sc',
						'$tj',
						'$final',
						'$userPos',
						'$fechaActual',
						'AUMENTO STOCK EN $sucursalFin POR RECIBIR DE: $sucursalInicio',
						'$idProducto',
						'$sucursalFin'

						)") or die(mysqli_error($MySQLi));
					//insertamos historial --------------------------------------------------------fin
				
				
				
				}
				mysqli_query($MySQLi,"UPDATE envioStock SET estado=1 WHERE idEnvio='$idEnvio' ");?>
<script type="text/javascript">
Swal.fire({
    position: 'center',
    type: 'success',
    title: 'RECEPCI&Oacute;N CONFIRMADA',
    showConfirmButton: false,
    timer: 2500,
})
setTimeout(function() {
    location.reload()
}, 500)
</script><?php
     	}
		break;
		case 'facturaElectronica':
			if (isset($_SESSION['idUser'])) {				
				$NombreCliente 	= $_POST['nombreCliente'];
				$nitCliente 		= $_POST['nitCliente'];
				$nombreVendedor = $_POST['nombreVendedor'];
				$fechaFactura 	= $_POST['fecha'];
				
				
				
     	}
		break;

/* ############## ACTUALIZACION MARZO-2025	###################*/

		// eliminar Venta por Anticipo Cancelado en proceso
		case 'BorrarVentaporAnticipoCancelado':
			try {
				if (isset($_SESSION['idUser'])) {
				if ($_SESSION['Rango']==2) {
					$idCotizacion 	= $_POST['idCotizacion'];
					/*	COPIAMOS LA COTIZACION A LA TABLA CotMod y SUS CLAVES A ClaveTempMod	*/
					copiarTablas_Abonos($MySQLi,$idCotizacion);     //VERIFICAR
					/*	CAMBIAMOS EL ESTADO DE LA COTIZACION A 0	*/
					mysqli_query($MySQLi,"UPDATE Cotizaciones SET Estado=0 WHERE idCotizacion='$idCotizacion' ");
					/*	CAMBIAMOS EL ESTADO DE LA NOTA DE ENTREGA A 1 QUE SIGNIFICA QUE HA SIDO MODIFICADA	*/
					mysqli_query($MySQLi,"UPDATE NotaEntrega SET Estado=1 WHERE idCotizacion='$idCotizacion' ");
					/*	CAMBIAMOS EL ESTADO DEL RECIBO A 1 QUE SIGNIFICA QUE HA SIDO MODIFICADO 	*/
					mysqli_query($MySQLi,"UPDATE Recibos SET Estado=1 WHERE idCotizacion='$idCotizacion' ");
					/*	INSERTAMOS LA NOTA DE CREDITO AL CLIENTE	*/
					//notaCredito($MySQLi,$idCotizacion,$idVendedor,$idCliente,$Sucursal,$fecha);
					/*	MODIFICAMOS EL TOTAL DE VENTAS A SALDO NEGATIVO	*/
					modificarVentaCash($MySQLi,$idCotizacion);

					/*	CONSULTAMOS LA CLAVE DE LA COTIZACION 	*/
					$queryClave			= mysqli_query($MySQLi,"SELECT * FROM Cotizaciones WHERE idCotizacion='$idCotizacion' ");
					$dataClave 			= mysqli_fetch_assoc($queryClave);
					$Clave 				= $dataClave['Clave'];
					$Sucursal 			= $dataClave['Sucursal'];
					$idVendedor 		= $dataClave['idUser'];
					$idCliente 			= $dataClave['idCliente'];
					/*	DEVOLVEMOS LOS PRODUCTOS AL STOCK CORRESPONDIENTE	*/
					devolverProductos($MySQLi,$Sucursal,$Clave, 'ANTICIPO CANCELADO');

					mysqli_close($MySQLi); ?>
					<script type="text/javascript">
					Swal.fire({
						type: 'success',
						title: 'Abono Cancelado Eliminado',
						animation: false,
						customClass: {
							popup: 'animated bounceInDown'
						}
					})
					setTimeout(function() {
						location.replace("?root=generadas");
					}, 2000);
					</script><?php
				}else{ mysqli_close($MySQLi); ?>
					<script type="text/javascript">
					Swal.fire({
						type: 'error',
						title: 'SIN AUTORIZACIÓN!',
						animation: false,
						customClass: {
							popup: 'animated shake'
						}
					})
					</script> <?php exit();
				}
			}else{ mysqli_close($MySQLi); session_destroy(); ?>
				<script type="text/javascript">
				Swal.fire({
					type: 'error',
					title: 'Sesión caducada',
				})
				setTimeout(function() {
					location.reload();
				}, 2500);
				</script> <?php
			}
			} catch (\Throwable $th) {
				throw $th;
			}
			
		break;

		// eliminar credito en proceso
		case 'eliminarCreditoCancelado':
		if (isset($_SESSION['idUser'])) {
			if ($_SESSION['Rango']==2) {
				$idCotizacion 	= $_POST['idCotizacion'];
				/*	COPIAMOS LA COTIZACION A LA TABLA CotMod y SUS CLAVES A ClaveTempMod	*/
				copiarTablas($MySQLi,$idCotizacion);
				/*	CAMBIAMOS EL ESTADO DE LA COTIZACION A 0	*/
				mysqli_query($MySQLi,"UPDATE Cotizaciones SET Estado=0 WHERE idCotizacion='$idCotizacion' ");
				/*	CAMBIAMOS EL ESTADO DE LA NOTA DE ENTREGA A 1 QUE SIGNIFICA QUE HA SIDO MODIFICADA	*/
				mysqli_query($MySQLi,"UPDATE NotaEntrega SET Estado=1 WHERE idCotizacion='$idCotizacion' ");
				/*	CAMBIAMOS EL ESTADO DEL RECIBO A 1 QUE SIGNIFICA QUE HA SIDO MODIFICADO 	*/
				mysqli_query($MySQLi,"UPDATE Recibos SET Estado=1 WHERE idCotizacion='$idCotizacion' ");
				/*	CONSULTAMOS LA CLAVE DE LA COTIZACION 	*/
				$queryClave			= mysqli_query($MySQLi,"SELECT * FROM Cotizaciones WHERE idCotizacion='$idCotizacion' ");
				$dataClave 			= mysqli_fetch_assoc($queryClave);
				$Clave 				= $dataClave['Clave'];
				$Sucursal 			= $dataClave['Sucursal'];
				$idVendedor 		= $dataClave['idUser'];
				$idCliente 			= $dataClave['idCliente'];
				/*	DEVOLVEMOS LOS PRODUCTOS AL STOCK CORRESPONDIENTE	*/
				devolverProductos($MySQLi,$Sucursal,$Clave, 'CREDITO CANCELADO');
				/*	INSERTAMOS LA NOTA DE CREDITO AL CLIENTE	*/
				//notaCredito($MySQLi,$idCotizacion,$idVendedor,$idCliente,$Sucursal,$fecha);
				/*	MODIFICAMOS EL TOTAL DE VENTAS A SALDO NEGATIVO	*/
				modificarVentaCash($MySQLi,$idCotizacion);
				mysqli_close($MySQLi); ?>
				<script type="text/javascript">
				Swal.fire({
					type: 'success',
					title: 'Credito Eliminado',
					//html: "El ID modificado fué: "+idCotizacion,
					animation: false,
					customClass: {
						popup: 'animated bounceInDown'
					}
				})
				setTimeout(function() {
					location.replace("?root=generadas");
				}, 2000);
				</script><?php
			}else{ mysqli_close($MySQLi); ?>
				<script type="text/javascript">
				Swal.fire({
					type: 'error',
					title: 'SIN AUTORIZACIÓN!',
					animation: false,
					customClass: {
						popup: 'animated shake'
					}
				})
				</script> <?php exit();
			}
		}else{ mysqli_close($MySQLi); session_destroy(); ?>
			<script type="text/javascript">
			Swal.fire({
				type: 'error',
				title: 'Sesión caducada',
			})
			setTimeout(function() {
				location.reload();
			}, 2500);
			</script> <?php
		}
		break;

/* ############## FIN ACTUALIZACION MARZO-2025	###################*/

		default: ?>
<script type="text/javascript">
Swal.fire({
    position: 'center',
    type: 'error',
    title: 'TOKEN INCORRECTO',
    showConfirmButton: false,
    timer: 2500,
})
</script><?php
		break;
	}