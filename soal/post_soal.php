<?php

include '../global_var/index.php';
include '../global_var/get_custome_data.php';

if ($_SERVER['REQUEST_METHOD'] === "POST") {
    $token          = getallheaders();
    $soal           = $_POST['soal_txt'];
    $jawaban_benar  = $_POST['jawaban_benar_txt'];
    $jawaban_salah  = $_POST['jawaban_salah_txt'];
    $kategori       = $_POST['kategori_soal'];
    $obj = new GetCustomeData();
    $getReturnTk = $obj->checkToken($token);
    if ($_FILES['imageupload']["name"] != "") {
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
                $query          = "
                    INSERT INTO tbl_soal(soal_txt, gambar, kategori_soal) VALUES('$soal', '$gambar', '$kategori')
                ";
                $obj = new GetCustomeData();
                if ($getReturnTk == true) {
                    return $obj->insertSoal($query, $jawaban_benar, $jawaban_salah);
                } else {
                    $response = [
                        'status_code' => 401,
                        'status_message' => 'Unauthorized Token'
                    ];
                }
            }
        } else {
            $response = [
                'status_code' => 400,
                'status_message' => 'Image is not Supported'
            ];

            echo json_encode($response);
            mysqli_close($conn);
        }
    } else {
        $query          = "
                    INSERT INTO tbl_soal(soal_txt, kategori_soal) VALUES('$soal', '$kategori')
                ";
        $obj = new GetCustomeData();
        if ($getReturnTk == true) {
            return $obj->insertSoal($query, $jawaban_benar, $jawaban_salah);
        } else {
            $response = [
                'status_code' => 401,
                'status_message' => 'Unauthorized Token'
            ];
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
