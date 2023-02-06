<?php

include '../koneksi/koneksi.php';
include '../global_var/index.php';
require_once('../vendor/autoload.php');
use Firebase\JWT\JWT;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $query = mysqli_query($conn, "SELECT * FROM admin WHERE username = '$username' ");
    $check = mysqli_num_rows($query);
    // echo password_hash('123', PASSWORD_DEFAULT);

    if ($check > 0) {
        while ($ambil = mysqli_fetch_object($query)) {
            if (password_verify($password, $ambil->password)) {
                $payload = [
                    'data' => $ambil // inisialisasi
                ];
                $jwt = JWT::encode($payload, $var_token, 'HS256');
                $response = [
                    'status_code' => 200,
                    'status_message' => 'Successfully',
                    'data' => $ambil,
                    'token' => $jwt
                ];
            } else {
                $response =  [
                    'status_code' => 404,
                    'status_message' => 'username or password is wrong'
                ];
            }
        }
    } else {
        $response =  [
            'status_code' => 404,
            'status_message' => 'username or password is wrong'
        ];
    }
}else{
    $response =  [
        'status_code' => 401,
        'status_message' => 'Methode Request is Not Supported'
    ];
    
}

echo json_encode($response);
mysqli_close($conn);