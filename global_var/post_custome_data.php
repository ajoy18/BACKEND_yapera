<?php

    include '../koneksi/koneksi.php';
    require_once('../vendor/autoload.php');

    use Firebase\JWT\JWT;
    use Firebase\JWT\Key;

    class PostCustomeData
    {
        function postSiswa($tk, $que)
        {
            $dt = array();
            global $conn;
            $var_token = "kjadfksdnfklsdnfklsjnanjknsdkjfnsdjknfsadjklfhklnfwewnkjsnfkjlndskljfasn";
            
            try {
                JWT::decode($tk, new Key($var_token, 'HS256'));
                $query = mysqli_query($conn, $que);
                $check = mysqli_num_rows($query);
    
                if ($check > 0) {
                    while ($ambil = mysqli_fetch_object($query)) {
                        array_push($dt, $ambil);
                    }
                    $response = [
                        'status_code' => 200,
                        'status_message' => 'Successfull',
                        'data' => $dt
                    ];
                } else {
                    $response = [
                        'status_code' => 404,
                        'status_message' => 'Data is not found'
                    ];
                }
            } catch (Exception $e) {
                $response = [
                    'status_code' => 401,
                    'status_message' => 'Unauthorized Token'
                ];
            }
            echo json_encode($response);
            mysqli_close($conn);
        }
    }