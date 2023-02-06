<?php

include '../global_var/index.php';
include '../global_var/get_custome_data.php';


if ($_SERVER['REQUEST_METHOD'] === "GET") {
    $token = getallheaders();
    $id_jurusan = $_GET["id_jurusan"];
    $obj = new GetCustomeData();
    $getReturnTk = $obj->checkToken($token);
    $query = "SELECT * FROM tbl_kelas WHERE id_jurusan = '$id_jurusan' ORDER BY id ASC ";
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
        'status_message' => 'Method is not allowed'
    ];

}
echo json_encode($response);
mysqli_close($conn);
