<?php

include '../global_var/index.php';
include '../global_var/get_custome_data.php';

if ($_SERVER['REQUEST_METHOD'] === "GET") {
    $token = getallheaders();
    $kategori = $_GET['kategori_soal'];
    $obj = new GetCustomeData();
    $getReturnTk = $obj->checkToken($token);
    $query = "
    SELECT tbl_soal.*, tbl_jawaban_benar.jawaban_benar_txt
        FROM tbl_soal, tbl_jawaban_benar
        WHERE tbl_jawaban_benar.soal_id = tbl_soal.id      
        AND tbl_soal.kategori_soal = '$kategori' ORDER BY RAND() LIMIT 20
    ";
    if ($getReturnTk == true) {
        return $obj->getSoalByKategori($query);
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
