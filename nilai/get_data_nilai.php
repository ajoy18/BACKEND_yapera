<?php

include '../global_var/index.php';
include '../global_var/get_custome_data.php';

if($_SERVER['REQUEST_METHOD'] === "GET"){
    $token = getallheaders();
    $obj = new GetCustomeData();
    $getReturnTk = $obj->checkToken($token);
    $query = "SELECT * FROM tbl_nilai";
    if ($getReturnTk == true) {
        return $obj->getData($query);
    } else {
        $response = [
            'status_code' => 401,
            'status_message' => 'Unauthorized Token'
        ];
    }
}
else {
    $response = [
        'status_code' => 405,
        'status_message' => 'Method is not Allowed'
    ];
    echo json_encode($response);
    mysqli_close($conn);
}