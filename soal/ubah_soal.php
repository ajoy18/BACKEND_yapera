<?php

include '../global_var/index.php';
include '../global_var/get_custome_data.php';

if ($_SERVER['REQUEST_METHOD'] === "POST") {

    $token          = getallheaders();
    $id             = $_POST['id'];
    $soal           = $_POST['soal_txt'];
    $jawaban_benar  = $_POST['jawaban_benar_txt'];
    $jawaban_salah1 = $_POST['jawaban_salah_txt_1'];
    $jawaban_salah2 = $_POST['jawaban_salah_txt_2'];
    $jawaban_salah3 = $_POST['jawaban_salah_txt_3'];
    $salahid_1      = $_POST['jawaban_salah_id_1'];
    $salahid_2      = $_POST['jawaban_salah_id_2'];
    $salahid_3      = $_POST['jawaban_salah_id_3'];
    $obj = new GetCustomeData();
    $getReturnTk = $obj->checkToken($token);

    if ($_FILES['imageupload']) {
        $part_image = './image/';
        $ekstensi_diperbolehkan = ['jpg', 'png', 'jpeg', 'svg'];
        $nama_image = $_FILES['imageupload']['name'];
        $x = explode('.', $nama_image);
        $ekstensi_image = strtolower(end($x));

        $file_tmp_image = $_FILES['imageupload']['tmp_name'];
        $filenameImage = 'image_' . rand(10, 1000) . '_' . $nama_image;
        $destImage = $part_image . $filenameImage;
        if (in_array($ekstensi_image, $ekstensi_diperbolehkan) === true) {
            if (move_uploaded_file($_FILES['imageupload']['tmp_name'], $destImage)) {
                $gambar         = $filenameImage;
                $query = "
                    UPDATE tbl_soal SET soal_txt='$soal', gambar = '$gambar' WHERE id = '$id'
                ";
                $obj = new GetCustomeData();
                if ($getReturnTk == true) {
                    return $obj->ubahSoal(
                        $query,
                        $id,
                        $jawaban_benar,
                        $jawaban_salah1,
                        $jawaban_salah2,
                        $jawaban_salah3,
                        $salahid_1,
                        $salahid_2,
                        $salahid_3
                    );
                } else {
                    $response = [
                        'status_code' => 401,
                        'status_message' => 'Unauthorized Token'
                    ];
                }
            }
        } else {

            $query          = "
                UPDATE tbl_soal SET soal_txt = '$soal' WHERE id = '$id'
            ";
            $obj = new GetCustomeData();
            if ($getReturnTk == true) {
                return $obj->ubahSoal(
                    $query,
                    $id,
                    $jawaban_benar,
                    $jawaban_salah1,
                    $jawaban_salah2,
                    $jawaban_salah3,
                    $salahid_1,
                    $salahid_2,
                    $salahid_3
                );
            } else {
                $response = [
                    'status_code' => 401,
                    'status_message' => 'Unauthorized Token'
                ];
            }
        }
    }
} else {
    $response = [
        'status_code' => 405,
        'status_message' => 'Method is not Allowed'
    ];

    echo json_encode($response);
    mysqli_close($conn);
}
