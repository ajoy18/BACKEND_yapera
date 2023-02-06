<?php

include '../global_var/index.php';
include '../global_var/get_custome_data.php';

if($_SERVER['REQUEST_METHOD'] === "GET"){
    $token = getallheaders();
    $kategori = $_GET["kategori"];
    $obj = new GetCustomeData();
    $getReturnTk = $obj->checkToken($token);
    $query = "
    SELECT * FROM tbl_soal WHERE kategori_soal = '$kategori'
    ";
    if ($getReturnTk == true) {
        return $obj->getData($query);
    } else {
        $response = [
            'status_code' => 401,
            'status_message' => 'Unauthorized Token'
        ];
        echo json_encode($response);
        mysqli_close($conn);
    }
} else {
    $response = [
        'status_code' => 405,
        'status_message' => 'Method is not Allowed'
    ];
    echo json_encode($response);
    mysqli_close($conn);
}

