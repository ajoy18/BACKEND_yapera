<?php

include '../global_var/index.php';
// include '../global_var/check_token.php';
include '../global_var/get_custome_data.php';
require_once('../vendor/autoload.php');

use Firebase\JWT\JWT;
use Firebase\JWT\Key;


if ($_SERVER['REQUEST_METHOD'] === "GET") {
    $token = getallheaders();
    $kls = $_GET["kelas"];
    $obj = new GetCustomeData();
    $getReturnTk = $obj->checkToken($token);
    $query = "
        SELECT tbl_jurusan.*, tbl_kelas.nama_kelas, tbl_kelas.id AS id_kelas FROM tbl_jurusan, tbl_kelas
        WHERE tbl_kelas.id_jurusan = tbl_jurusan.id AND tbl_kelas.tingkatan_kelas = '$kls' ORDER BY tbl_kelas.nama_kelas ASC
    ";
    if ($getReturnTk == true) {
        return $obj->getData($query);
    } else {
        $response = [
            'status_code' => 401,
            'status_message' => 'Unauthorized Token'
        ];
    }

    echo json_encode($response);
    mysqli_close($conn);
} else {
    $response = [
        'status_code' => 405,
        'status_message' => 'Method  is not Allowed'
    ];

    echo json_encode($response);
    mysqli_close($conn);
}
