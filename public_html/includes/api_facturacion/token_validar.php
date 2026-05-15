<?php
error_reporting(0);
token_validar();
function token_validar()
{
    if (token_vigente()) {
        echo json_encode('Token Vigente');
    } else {
        echo  token_regenerar() ? json_encode('Token regenerado en api y actualizado en BD') : json_encode('Token vencido y error al actualizar');
    }
}

function token_vigente()
{
    include './../conexion.php';
    $sql_token_access = mysqli_query($MySQLi, "SELECT * FROM token_access");
    $data_token_access = mysqli_fetch_assoc($sql_token_access) or die(mysqli_error($MySQLi));
    $token = $data_token_access['token'];
    if ($token == '') {
        return false;
    } else {
        $decoded = json_decode(base64_decode(str_replace('_', '/', str_replace('-', '+', explode('.', $token)[1]))));
        $iat = $decoded->iat;
        $datetimeFormat = 'Y-m-d H:i:s';
        $date = new \DateTime();
        $date->setTimestamp($iat);
        $timestamp = $iat;
        $date = new DateTime("@" . $timestamp);
        $date->setTimezone(new DateTimeZone('America/La_Paz'));
        $datex = $date->format('Y-m-d H:i:s');
        $timestamp = $iat;
        $date_h = new DateTime("@" . $timestamp);
        $date_h->setTimezone(new DateTimeZone('America/La_Paz'));
        $date_fin = $date_h->add(new DateInterval('PT2H'));
        $date_fin_f = $date_fin->format('Y-m-d H:i:s');
        $date_fin_timestamp = strtotime($date_fin_f);
        $current_timestampx = time();
        $datexx = new DateTime("@" . $current_timestampx);
        $datexx->setTimezone(new DateTimeZone('America/La_Paz'));
        $current_date = $datexx->format('Y-m-d H:i:s');
        $current_date_timestamp = strtotime($current_date);
        $renew = $date_fin_timestamp - $current_date_timestamp;
        $renew = (int)$renew;
        //echo $renew;
        mysqli_close($MySQLi);
        $activo = $renew >= 3600 ? true : false;
        return $activo;
    }
}

function token_regenerar()
{
    include './../conexion.php';
    $sql_token_access = mysqli_query($MySQLi, "SELECT * FROM token_access");
    $data_token_access = mysqli_fetch_assoc($sql_token_access) or die(mysqli_error($MySQLi));

    $user = $data_token_access['user'];
    $passcucu = $data_token_access['passcucu'];
    $urlyapame = $data_token_access['urlcucu'];

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

    //print_r($valor_token);
    //GUARDANDO EN BD NUEVO TOKEN
    if ($valor_response == "ok") {
        $sql = mysqli_query($MySQLi, "UPDATE token_access SET token='$valor_token' WHERE user='$valor_username'");
        $res = $sql ? true : false;
        return $res; //regenerado y updateado
    } else {
        return false;
    }
    mysqli_close($MySQLi);
}
