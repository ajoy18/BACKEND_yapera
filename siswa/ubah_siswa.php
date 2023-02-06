<?php

include '../global_var/index.php';
include '../global_var/get_custome_data.php';

if ($_SERVER['REQUEST_METHOD'] === "POST") {
    $token      = getallheaders();
    $id_kelas   = $_POST['id_kelas'];
    $id         = $_POST['id'];
    $nisn       = $_POST['nisn'];
    $nama_siswa = strtolower($_POST['nama']);
    $tgl_lahir  = $_POST['tgl_lahir'];
    $tahun_ajaran = $_POST['tahun_ajaran'];

    $tahunLahir = explode("-", $tgl_lahir);
    $nama_siswa_explode = explode(" ", $nama_siswa);
    $res = strtolower($nama_siswa_explode[0]). '' . $tahunLahir[2] . '' . $tahunLahir[1] . '' .$tahunLahir[0] ;
    // print_r($res);
    $query = "UPDATE tbl_siswa SET nisn='$nisn', nama='$nama_siswa', tgl_lahir='$tgl_lahir', password='$res' 
    WHERE id = '$id' ";
    $obj = new GetCustomeData();
    $getReturnTk = $obj->checkToken($token);
    if ($getReturnTk == true) {
        return $obj->ubahSiswa($query, $id, $id_kelas, $tahun_ajaran);
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