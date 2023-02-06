<?php

    include '../global_var/get_custome_data.php';
    include '../global_var/index.php';
    include '../global_var/check_token.php';

    if($_SERVER['REQUEST_METHOD'] === "GET"){
        $token = getallheaders();
        $tkn = explode(" ", $token['authorization']);
        $tk = $tkn[1];
        $ob = new Token();
        $table = "tbl_siswa";
        $ob->GETData($tk, $table);
    } else {
        $response = [
            'status_code' => 405,
            'status_message' => 'Method is not Allowed'
        ];
    }