<?php

include '../global_var/index.php';
include '../global_var/get_custome_data.php';


if ($_SERVER['REQUEST_METHOD'] === "GET") {
    $token = getallheaders();
    $id_siswa = $_GET["id_siswa"];
    $obj = new GetCustomeData();
    $getReturnTk = $obj->checkToken($token);
    //get data siswa by kelas 
    $query = "
    SELECT * FROM tbl_siswa WHERE id = '$id_siswa' 
    ";
    if ($getReturnTk == true) {
        return $obj->getSiswaDetail($query);
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
echo json_encode($response);
mysqli_close($conn);
