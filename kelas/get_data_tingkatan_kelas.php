<?php

include '../global_var/check_token.php';
include '../global_var/index.php';
require_once('../vendor/autoload.php');

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

if($_SERVER['REQUEST_METHOD'] === "GET"){
    $token = getallheaders();
    $tkn = explode(" ", $token['authorization']);
    $tk = $tkn[1];
    $ob = new Token();
    $table = "tbl_tingkatan_kelas";
    $ob->GETData($tk, $table);
} else {
    $response = [
        'status_code' => 405,
        'status_message' => 'Method is not Allowed'
    ];
}