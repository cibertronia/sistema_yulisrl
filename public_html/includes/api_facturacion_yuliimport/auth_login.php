<?php
error_reporting(0);
regenerarToken();
function regenerarToken(){
    include './../conexion_yuliimport.php';
    $sql_token_access = mysqli_query($YuliimportDB, "SELECT * FROM token_access");
    $data_token_access = mysqli_fetch_assoc($sql_token_access) or die(mysqli_error($YuliimportDB));
    
    $user = $data_token_access['user'];
    $passcucu = $data_token_access['passcucu'];
    $urlyapame = $data_token_access['urlcucu'];
    

    // $token_actual = $data_token_access['token'];


    // iniciamos peticion api
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $urlyapame . "/api/v1.0.0/users/get-token");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_HEADER, FALSE);
    curl_setopt($ch, CURLOPT_POST, TRUE);
    curl_setopt($ch, CURLOPT_POSTFIELDS, "{
      \"username\": \"$user\",
      \"password\": \"$passcucu\"
    }");
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        "Content-Type: application/json"
    ));
    $response = curl_exec($ch);
    curl_close($ch);
    // cerramos peticion api
    
    $response_decode = json_decode($response);
    $valor_response = $response_decode->{'response'};
    $valor_token = $response_decode->{'data'}->{'token'};
    $valor_username = $response_decode->{'data'}->{'user'}->{'username'};
    //GUARDANDO EN BD NUEVO TOKEN
    if ($valor_response == "ok") {
        $sql = mysqli_query($YuliimportDB, "UPDATE token_access SET token='$valor_token' WHERE user='$valor_username'");
        $res=$sql? 'Actualizacion Correcta' : 'Actualizacion Fallida';
        echo json_encode('ok');
    }else{
        echo json_encode('error');
    }

}





