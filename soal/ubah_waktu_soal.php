<?php

include '../global_var/index.php';
include '../global_var/get_custome_data.php';

if($_SERVER['REQUEST_METHOD'] === "POST"){
    $token          = getallheaders();
    // $id             = $_POST['id'];
    $mulai          = $_POST['mulai'];
    $berhenti       = $_POST['berhenti'];
    $kategori       = $_POST['kategori'];
    $query          = "
        UPDATE tbl_waktu SET mulai='$mulai', berhenti='$berhenti' WHERE kategori ='$kategori'
    ";
    $obj = new GetCustomeData();
    $getReturnTk = $obj->checkToken($token);
    if ($getReturnTk == true) {
        return $obj->ubahWaktu($query);
    } else {
        $response = [
            'status_code' => 401,
            'status_message' => 'Unauthorized Token'
        ];
    }

}