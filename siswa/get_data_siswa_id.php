<?php

include '../global_var/index.php';
include '../global_var/get_custome_data.php';

if ($_SERVER['REQUEST_METHOD'] === "GET") {
    $token = getallheaders();
    $id = $_GET["id"];
    $obj = new GetCustomeData();
    $getReturnTk = $obj->checkToken($token);
    $query = "
    SELECT tbl_siswa.*, tbl_kelas_siswa.tahun_ajaran, tbl_kelas_siswa.id_kelas, tbl_kelas.id_jurusan
    FROM tbl_siswa, tbl_kelas_siswa, tbl_kelas
    WHERE tbl_kelas_siswa.id_siswa = tbl_siswa.id
    AND tbl_kelas.id = tbl_kelas_siswa.id_kelas
    AND tbl_siswa.id = '$id'
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
        'status_message' => 'Method is not Allowed'
    ];

}
echo json_encode($response);
mysqli_close($conn);
