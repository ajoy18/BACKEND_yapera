<?php

include '../global_var/index.php';
include '../global_var/get_custome_data.php';

if ($_SERVER['REQUEST_METHOD'] === "POST") {
    $token      = getallheaders();
    $nisn       = $_POST['nisn'];
    $nama_siswa = strtolower($_POST['nama']);
    $tgl_lahir     = $_POST['tgl_lahir'];
    $tahun_ajaran = $_POST['tahun_ajaran'];
    //1 IPA, 2 IPS, 3 Bahasa
    $id_kelas = $_POST['id_kelas'];
    $tahunLahir = explode("-", $tgl_lahir);
    $nama_siswa_explode = explode(" ", $nama_siswa);
    $res = strtolower($nama_siswa_explode[0]). '' . $tahunLahir[2] . '' . $tahunLahir[1] . '' .$tahunLahir[0] ;
    // print_r($res);
    $query = "INSERT INTO tbl_siswa(nisn, nama, tgl_lahir, password, flag) VALUES('$nisn', '$nama_siswa', '$tgl_lahir', '$res', '0')";
    $obj = new GetCustomeData();
    $getReturnTk = $obj->checkToken($token);
    if ($getReturnTk == true) {
        return $obj->insertSiswa($query,  $id_kelas, $tahun_ajaran);
    } else {
        $response = [
            'status_code' => 401,
            'status_message' => 'Unauthorized Token'
        ];
    }
}
