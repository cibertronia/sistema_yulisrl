<?php 
include './../includes/conexion.php';
$sqlurlcucu = mysqli_query($MySQLi, "SELECT * FROM token_access");
$dataurlcucu = mysqli_fetch_assoc($sqlurlcucu) or die(mysqli_error($MySQLi));
$urlcucu=$dataurlcucu['urlcucu'];
$to = $dataurlcucu['token'];


$nit=$_POST["verificarnit"];
$endpoint = $urlcucu.'/api/v1/codes/nit';
$data = array(
    'posId'=>1,
    'nit'=> $nit
  ); 
$url = $endpoint.'?'.http_build_query($data);
$ch = curl_init($url); 

curl_setopt($ch, CURLOPT_POSTFIELDS,1);
curl_setopt($ch, CURLOPT_HTTPHEADER, 
array( "cucukey: Token $to","Content-Type: application/json"));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$result = curl_exec($ch);

$descri = json_decode($result);
$var=$descri->data[0];
$code=$var->{'description'};

curl_close($ch);

?>

<script src="https://cdn.bootcss.com/jquery/3.3.1/jquery.js"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script type="text/javascript">
<?php
       echo "var jsvar ='$code';";
   ?>
if ($(document).ready(function() {
        if (jsvar == "NIT ACTIVO") {
            swal({
                title: "NIT VALIDO",
                text: jsvar,
                icon: "success",
                button: "Ok",
                timer: 5000
            });
        } else {
            swal({
                title: "NIT INVALIDO",
                text: jsvar,
                icon: "error",
                button: "Ok",
                timer: 5000
            });
        }

    })) {


}
</script>';