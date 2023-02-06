<?php

include '../global_var/index.php';
// include '../global_var/check_token.php';
include '../global_var/get_custome_data.php';


if ($_SERVER['REQUEST_METHOD'] === "GET") {
    $token = getallheaders();
    $kls = $_GET["kelas"];
    // $id = $_GET["id_kelas"];
    $tahun = $_GET["tahun"];
    $obj = new GetCustomeData();
    $getReturnTk = $obj->checkToken($token);
    //get data siswa by kelas 
    $query = "
    SELECT tbl_siswa.id, tbl_siswa.nisn, tbl_siswa.nama, tbl_siswa.flag, tbl_kelas.nama_kelas, YEAR(tbl_kelas_siswa.tahun_ajaran) AS tahun_ajaran 
    FROM tbl_siswa, tbl_kelas, tbl_kelas_siswa 
    WHERE tbl_siswa.id = tbl_kelas_siswa.id_siswa
    AND tbl_kelas_siswa.id_kelas = tbl_kelas.id 
    AND tbl_kelas_siswa.id_kelas = '$kls'
    AND tbl_kelas_siswa.tahun_ajaran LIKE '%$tahun%'
   
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
        'status_message' => 'Method  is not Allowed'
    ];

}
echo json_encode($response);
mysqli_close($conn);