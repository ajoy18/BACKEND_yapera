<?php

include '../global_var/index.php';
include '../global_var/get_custome_data.php';

if ($_SERVER['REQUEST_METHOD'] === "POST") {
    
    $token      = getallheaders();
    $id_soal    = $_POST['id'];
    $query      = "DELETE FROM tbl_soal WHERE id ='$id_soal' ";
    $obj        = new GetCustomeData();
    $getReturnTk = $obj->checkToken($token);
    if ($getReturnTk == true) {
        return $obj->hapusSoal($query, $id_soal);
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