<?php

include '../global_var/index.php';
include '../global_var/get_custome_data.php';

if($_SERVER['REQUEST_METHOD'] === "GET"){
    $token = getallheaders();
    // $nilai = $_GET['nilai'];
    $kategori = $_GET['kategori_soal'];
    $id_siswa = $_GET['siswa_id'];
    $tahun = date('Y-m-d');
    $obj = new GetCustomeData();
    $getReturnTk = $obj->checkToken($token);

    $query = "
        SELECT tbl_nilai.*, tbl_siswa.id
        FROM tbl_nilai, tbl_siswa
        WHERE tbl_nilai.siswa_id = tbl_siswa.id
        AND tbl_siswa.id = '$id_siswa'
        AND tbl_nilai.tahun_ajaran = '$tahun'
        AND tbl_nilai.kategori_soal = '$kategori'
    ";
    if($getReturnTk == true){
        return $obj->getValidasiNilai($query);
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