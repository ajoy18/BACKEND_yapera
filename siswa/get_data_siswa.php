<?php

include '../global_var/index.php';
// include '../global_var/check_token.php';
include '../global_var/get_custome_data.php';
date_default_timezone_set('Asia/Jakarta');

if ($_SERVER['REQUEST_METHOD'] === "GET") {
    $token = getallheaders();
    $kls = $_GET["kelas"];
    $year = date("Y");
    // $id = $_GET["id_kelas"];
    $obj = new GetCustomeData();
    $getReturnTk = $obj->checkToken($token);
    $date = date('Y-m-d');
    //get data siswa by kelas 
    $query = "
    SELECT tbl_siswa.id, tbl_siswa.nisn, tbl_siswa.flag, tbl_siswa.nama, tbl_kelas.nama_kelas, YEAR(tbl_kelas_siswa.tahun_ajaran) AS tahun_ajaran
    FROM tbl_siswa, tbl_kelas, tbl_kelas_siswa 
    WHERE tbl_siswa.id = tbl_kelas_siswa.id_siswa
    AND tbl_kelas_siswa.id_kelas = tbl_kelas.id 
    AND tbl_kelas_siswa.id_kelas = '$kls'
    AND YEAR(tbl_kelas_siswa.tahun_ajaran) = YEAR('$date') ORDER BY tbl_kelas_siswa.tahun_ajaran DESC
    ";
    $query1 = "
    SELECT tbl_siswa.id, tbl_siswa.nisn, tbl_siswa.nama, tbl_siswa.flag, tbl_kelas.nama_kelas, YEAR(tbl_kelas_siswa.tahun_ajaran) AS tahun_ajaran
    FROM tbl_siswa, tbl_kelas, tbl_kelas_siswa 
    WHERE tbl_siswa.id = tbl_kelas_siswa.id_siswa
    AND tbl_kelas_siswa.id_kelas = tbl_kelas.id 
    AND tbl_kelas_siswa.id_kelas = '$kls'
    ORDER BY tbl_kelas_siswa.tahun_ajaran DESC
    ";
    if ($getReturnTk == true) {
        return $obj->getKelasDetail($query, $query1);
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