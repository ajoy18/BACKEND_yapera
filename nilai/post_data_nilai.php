<?php

include '../global_var/index.php';
include '../global_var/get_custome_data.php';

if ($_SERVER['REQUEST_METHOD'] === "POST") {
    $token      = getallheaders();
    $id_siswa   = $_POST['siswa_id'];
    $nilai      = $_POST['nilai'];
    $kategori   = $_POST['kategori_soal'];
    $tahun      = date("Y-m-d");
    $obj        = new GetCustomeData();
    $getReturnTk = $obj->checkToken($token);
    $query = "INSERT INTO tbl_nilai(nilai, kategori_soal, siswa_id, tahun_ajaran) 
    VALUES('$nilai', '$kategori', '$id_siswa', '$tahun')
    ";
    if ($getReturnTk == true) {
        return $obj->insertNilai($query);
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

    echo json_encode($response);
    mysqli_close($conn);
}
