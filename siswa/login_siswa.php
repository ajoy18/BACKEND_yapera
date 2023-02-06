<?php

include '../global_var/index.php';
include '../global_var/get_custome_data.php';

if ($_SERVER['REQUEST_METHOD'] === "POST") {
    $nisn       = $_POST['nisn'];
    $password   = $_POST['password'];

    $query = "SELECT * FROM tbl_siswa WHERE
    nisn = '$nisn'
    AND password = '$password'
    ";

    $obj    = new GetCustomeData();
    $obj->loginSiswa($query);
} else {
    $response = [
        'status_code' => 405,
        'status_message' => 'Method is not Allowed'
    ];

    echo json_encode($response);
    mysqli_close($conn);
}
