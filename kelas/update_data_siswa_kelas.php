<?php

include '../global_var/index.php';
include '../global_var/get_custome_data.php';

if ($_SERVER['REQUEST_METHOD'] === "POST") {
    $token      = getallheaders();
    $id_kelas   = $_POST['id_kelas'];
    $id_siswa        = $_POST['id_siswa'];

    $query = "UPDATE tbl_kelas_siswa SET id_kelas='$id_kelas' WHERE id_siswa = '$id_siswa' ";
    $obj = new GetCustomeData();
    $getReturnTk = $obj->checkToken($token);
    if ($getReturnTk == true) {
        return $obj->ubah($query);
    } else {
        $response = [
            'status_code' => 401,
            'status_message' => 'Unauthorized Token'
        ];
    }
} else {
    $response = [
        'status_code' => 405,
        'status_message' => 'Method is not Allowed'
    ];

}
echo json_encode($response);
mysqli_close($conn);