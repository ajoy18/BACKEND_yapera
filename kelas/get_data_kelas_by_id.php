<?php

include '../global_var/index.php';
// include '../global_var/check_token.php';
include '../global_var/get_custome_data.php';
require_once('../vendor/autoload.php');

use Firebase\JWT\JWT;
use Firebase\JWT\Key;


if ($_SERVER['REQUEST_METHOD'] === "GET") {
    $token = getallheaders();
    $id_kelas = $_GET["id_kelas"];
    $obj = new GetCustomeData();
    $getReturnTk = $obj->checkToken($token);
    $query = "
        SELECT * FROM tbl_kelas WHERE id = '$id_kelas'
    ";
    if ($getReturnTk == true) {
        return $obj->getData($query);
    } else {
        $response = [
            'status_code' => 401,
            'status_message' => 'Unauthorized Token'
        ];
    }
    
} else {
    $response = [
        'status_code' => 405,
        'status_message' => 'Method  is not Allowed'
    ];
}
