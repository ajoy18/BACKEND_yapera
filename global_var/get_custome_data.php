<?php

// include './index.php';
include '../koneksi/koneksi.php';
require_once('../vendor/autoload.php');
date_default_timezone_set('Asia/Jakarta');

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class GetCustomeData
{
    function checkToken($token)
    {
        $auth = '';
        if (array_key_exists("Authorization", $token)) {
            $auth = 'Authorization';
        } else if (array_key_exists("authorization", $token)) {
            $auth = 'authorization';
        }
        $tkn = explode(" ", $token[$auth]);
        $tk = $tkn[1];

        $var_token = "kjadfksdnfklsdnfklsjnanjknsdkjfnsdjknfsadjklfhklnfwewnkjsnfkjlndskljfasn";
        try {
            JWT::decode($tk, new Key($var_token, 'HS256'));
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    function getKelasDetail($que, $que1)
    {
        global $conn;

        $dt = array();

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
            $query = mysqli_query($conn, $que1);
            while ($ambil = mysqli_fetch_object($query)) {
                array_push($dt, $ambil);
            }
            $response = [
                'status_code' => 200,
                'status_message' => 'Successfull',
                'data' => $dt
            ];
        }
        echo json_encode($response);
        mysqli_close($conn);
    }

    function getData($que)
    {
        global $conn;

        // print_r(JWT::decode( new Key($var_token, 'HS256')));
        $query = mysqli_query($conn, $que);
        $check = mysqli_num_rows($query);
        // echo $check;
        $response = [];
        if ($check > 0) {
            $dt = array();
            while ($ambil = mysqli_fetch_object($query)) {
                array_push($dt, $ambil);
            } // print_r($dt);
            $response = [
                'status_code' => 200,
                'status_message' => 'Successfull',
                'data' => $dt
            ];
            echo json_encode($response);
            mysqli_close($conn);
        } else {
            $response = [
                'status_code' => 404,
                'status_message' => 'Data is not found'
            ];
            echo json_encode($response);
            mysqli_close($conn);
        }
        
    }


    function getDataSiswa($que)
    {
        $dt = array();
        global $conn;
        try {
            $query = mysqli_query($conn, $que);
            $check = mysqli_num_rows($query);

            if ($check > 0) {
                while ($ambil = mysqli_fetch_object($query)) {
                    $dtNilai = array();
                    $queryy = mysqli_query(
                        //nilai by id siswa
                        $conn,
                        "
                        SELECT tbl_nilai.kategori_soal , tbl_nilai.nilai
                        FROM tbl_nilai, tbl_siswa
                        WHERE tbl_nilai.siswa_id = tbl_siswa.id 
                        AND tbl_nilai.siswa_id = '$ambil->id'
                        "
                    );
                    while ($ambill = mysqli_fetch_object($queryy)) {

                        $F['nilai'] = $ambill->nilai;
                        $F['kategori'] = $ambill->kategori_soal;

                        array_push($dtNilai, $F);
                    }
                    $detail = $this->slicingData($dtNilai);
                    $data = [
                        'id' => $ambil->id,
                        'nisn' => $ambil->nisn,
                        'nama' => $ambil->nama,
                        'nilai' => $dtNilai,
                        'detail' => $detail
                    ];
                    array_push($dt, $data);
                };
                $response = [
                    'status_code' => 200,
                    'status_message' => 'Succesfull',
                    'data' => $dt,
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

    function getAllSoal($que)
    {
        global $conn;

        $dt = array();
        $dtSalah = array();

        $query = mysqli_query($conn, $que);
        $check = mysqli_num_rows($query);

        if ($check > 0) {
            while ($ambil = mysqli_fetch_object($query)) {
                // print_r($ambil->id.' ');
                $queryy = mysqli_query(
                    $conn,
                    "
                    SELECT tbl_jawaban_salah.jawaban_salah_txt, tbl_jawaban_salah.id
                    FROM `tbl_jawaban_salah`, tbl_jawaban 
                    WHERE tbl_jawaban.jawaban_salah_id = tbl_jawaban_salah.id 
                    AND tbl_jawaban.soal_id = '$ambil->id'; 
                    "
                );

                while ($ambill = mysqli_fetch_object($queryy)) {
                    $F['jawaban_salah'] = $ambill->jawaban_salah_txt;
                    $F['id'] = $ambill->id;
                    array_push($dtSalah, $F);
                }
                $data = [
                    'id' =>  $ambil->id,
                    'soal_txt' => $ambil->soal_txt,
                    'kategori_soal' => $ambil->kategori_soal,
                    'gambar' => $ambil->gambar,
                    'jawaban_benar' => $ambil->jawaban_benar_txt,
                    'jawaban_salah' => $dtSalah
                ];
                array_push($dt, $data);
            };


            $response = [
                'status_code' => 200,
                'status_message' => 'Succesfull',
                'data' => $dt,
            ];
        } else {
            $response = [
                'status_code' => 404,
                'status_message' => 'Data is not found'
            ];
        }
        echo json_encode($response);
        mysqli_close($conn);
    }



    function getSoalByKategori($que)
    {
        global $conn;

        $dt = array();

        $query = mysqli_query($conn, $que);
        $check = mysqli_num_rows($query);

        if ($check > 0) {
            while ($ambil = mysqli_fetch_object($query)) {
                $dtSalah = array();

                $queryy = mysqli_query(
                    $conn,
                    "
                    SELECT tbl_jawaban_salah.jawaban_salah_txt
                    FROM `tbl_jawaban_salah`, tbl_jawaban 
                    WHERE tbl_jawaban.jawaban_salah_id = tbl_jawaban_salah.id 
                    AND tbl_jawaban.soal_id = '$ambil->id'; 
                    "
                );

                $random = rand(0, 3);
                $i = 0;
                $number = 0;

                while ($ambill = mysqli_fetch_object($queryy)) {
                    if ($i == $random) {
                        $Y['id'] = $number;
                        $Y['label'] = $ambil->jawaban_benar_txt;
                        $Y['value'] = "benar";
                        array_push($dtSalah, $Y);
                        $number++;
                    }
                    $F['id'] = $number;
                    $F['label'] = $ambill->jawaban_salah_txt;
                    $F['value'] = 'salah';
                    array_push($dtSalah, $F);

                    $i++;
                    $number++;
                }
                if ($random == 3) {
                    $X['id'] = 3;
                    $X['label'] = $ambil->jawaban_benar_txt;
                    $X['value'] = "benar";
                    array_push($dtSalah, $X);
                }

                $data = [
                    'id' =>  $ambil->id,
                    'soal_txt' => $ambil->soal_txt,
                    'kategori_soal' => $ambil->kategori_soal,
                    'gambar' => $ambil->gambar,
                    'jawaban' => $dtSalah
                ];
                array_push($dt, $data);
            };


            $response = [
                'status_code' => 200,
                'status_message' => 'Succesfull',
                'data' => $dt,
            ];
        } else {
            $response = [
                'status_code' => 404,
                'status_message' => 'Data is not found'
            ];
        }
        echo json_encode($response);
        mysqli_close($conn);
    }

    public function slicingData($data)
    {
        $param1 = "";
        $param2 = "";
        $res = "";
        foreach ($data as $row) {
            if ($row['kategori'] == "numerasi") {
                if ($row['nilai'] < 50) {
                    $param1 = "low";
                }
                if (intval($row['nilai']) >= 50 && intval($row['nilai']) < 70) {
                    $param1 = "standar";
                }
                if (intval($row['nilai']) >= 70 && intval($row['nilai']) < 90) {
                    $param1 = "high";
                }
                if (intval($row['nilai']) >= 90) {
                    $param1 = "pro";
                }
            }
            if ($row['kategori'] == "literasi") {
                if (intval($row['nilai']) < 50) {
                    $param2 = "low";
                }
                if (intval($row['nilai']) >= 50 && intval($row['nilai']) < 70) {
                    $param2 = "standar";
                }
                if (intval($row['nilai']) >= 70 && intval($row['nilai']) < 90) {
                    $param2 = "high";
                }
                if (intval($row['nilai']) >= 90) {
                    $param2 = "pro";
                }
            }
        }

        if ($param1 == "low" && $param2 == "low") {
            $res .= "Membutuhkan Intervensi Khusus Pada Literasi dan numerasi";
        } else if ($param1 == "low" && $param2 == "standar") {
            $res .= "Membutuhkan Intervensi Khusus pada numerasi dan memiliki dasar literasi";
        } else if ($param1 == "low" && $param2 == "high") {
            $res = "Membutuhkan Intervensi Khusus pada numerasi dan cakap pada literasi";
        } else if ($param1 == "low" && $param2 == "pro") {
            $res .= "Membutuhkan Intervensi Khusus pada numerasi dan mahir pada literasi";
        }

        if ($param1 == "standar" && $param2 == "low") {
            $res .= "Memiliki dasar pada numerasi dan Membutuhkan Intervensi Khusus Pada literasi";
        } else if ($param1 == "standar" && $param2 == "standar") {
            $res .= "Memiliki dasar pada numerasi dan memiliki dasar literasi";
        } else if ($param1 == "standar" && $param2 == "high") {
            $res .= "Memiliki dasar pada numerasi dan cakap pada literasi";
        } else if ($param1 == "standar" && $param2 == "pro") {
            $res .= "Memiliki dasar pada numerasi dan mahir pada literasi";
        }

        if ($param1 == "high" && $param2 == "low") {
            $res .= "Cakap pada numerasi dan Membutuhkan Intervensi Khusus Pada literasi";
        } else if ($param1 == "high" && $param2 == "standar") {
            $res .= "Cakap pada numerasi dan memiliki dasar literasi";
        } else if ($param1 == "high" && $param2 == "high") {
            $res .= "Cakap pada numerasi dan cakap pada literasi";
        } else if ($param1 == "high" && $param2 == "pro") {
            $res .= "Cakap pada numerasi dan mahir pada literasi";
        }

        if ($param1 == "pro" && $param2 == "low") {
            $res .= "Mahir pada numerasi dan Membutuhkan Intervensi Khusus Pada literasi";
        } else if ($param1 == "pro" && $param2 == "standar") {
            $res .= "Mahir pada numerasi dan memiliki dasar literasi";
        } else if ($param1 == "pro" && $param2 == "high") {
            $res .= "Mahir pada numerasi dan cakap pada literasi";
        } else if ($param1 == "pro" && $param2 == "pro") {
            $res .= "Mahir pada numerasi dan mahir pada literasi";
        }


        // var_dump($res);
        return $res;
    }

    function getSiswaDetail($que)
    {
        global $conn;

        $dt = array();

        $query = mysqli_query($conn, $que);
        $check = mysqli_num_rows($query);

        if ($check > 0) {

            $ambil = mysqli_fetch_assoc($query);
            $id = $ambil['id'];
            $quee = mysqli_query(
                $conn,
                "
                    SELECT DISTINCT tahun_ajaran AS tahun_ajaran FROM tbl_nilai WHERE siswa_id = '$id';
                    "
            );
            $temp1 = array();
            while ($get = mysqli_fetch_object($quee)) {
                $temp2 = array();
                $queee = mysqli_query(
                    $conn,
                    "
                                SELECT tbl_nilai.kategori_soal , tbl_nilai.nilai , tbl_nilai.tahun_ajaran
                                FROM tbl_nilai, tbl_siswa
                                WHERE tbl_nilai.siswa_id = tbl_siswa.id 
                                AND tbl_nilai.siswa_id = '$id'
                                AND tbl_nilai.tahun_ajaran = '$get->tahun_ajaran'
                                ORDER BY tbl_nilai.kategori_soal DESC
                            "
                );
                $i = 0;
                while ($gett = mysqli_fetch_object($queee)) {

                    $i++;
                    if ($gett->kategori_soal == "literasi" && $i == 1) {
                        $Q['nilai'] = "";
                        $Q['kategori'] = "numerasi";
                        array_push($temp2, $Q);
                    }

                    $Q['nilai'] = $gett->nilai;
                    $Q['kategori'] = $gett->kategori_soal;
                    array_push($temp2, $Q);
                }

                if (count($temp2) == 1) {
                    $Q['nilai'] = "";
                    $Q['kategori'] = "literasi";
                    array_push($temp2, $Q);
                }
                
                // print(count($temp2).' ');

                $detail = "";
                if ($i == 2) {
                    $detail .= $this->slicingData($temp2);
                } else {
                    $detail .= " Nilai Literasi Dan Numerasi Harus Lengkap";
                }
                $W['tahun'] = $get->tahun_ajaran;
                $W['data_nilai'] = $temp2;
                $W['detail'] = $detail;
                array_push($temp1, $W);
            }
            $data = [
                'id' => $ambil['id'],
                'nisn' => $ambil['nisn'],
                'nama' => $ambil['nama'],
                'data' => $temp1
            ];
            array_push($dt, $data);


            $response = [
                'status_code' => 200,
                'status_message' => 'Succesfull',
                'data' => $dt,
            ];
        } else {
            $response = [
                'status_code' => 404,
                'status_message' => 'Data is not found'
            ];
        }
        echo json_encode($response);
        mysqli_close($conn);
    }

    function getValidasiNilai($que)
    {
        global $conn;

        $dt = array();

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
                'status_code' => 200,
                'status_message' => 'Successfull',
                'data' => null
            ];
        }
        echo json_encode($response);
        mysqli_close($conn);
    }

    // POST METHOD // POST METHOD // POST METHOD // POST METHOD


    function insertSiswa($que, $id_kelas, $tahun_ajaran)
    {
        global $conn;



        // proses insert siswa
        mysqli_query($conn, $que);
        $txtQuery = "SELECT id FROM tbl_siswa ORDER BY id DESC LIMIT 1";
        // proses get data siswa terakhir, lalu dijadikan object / $row->id
        $res = mysqli_fetch_object(mysqli_query($conn, $txtQuery));
        $id = $res->id;
        // print($id);
        $txtQuery2 = "INSERT INTO tbl_kelas_siswa(id_kelas, id_siswa, tahun_ajaran) VALUES('$id_kelas', '$id', '$tahun_ajaran')";
        mysqli_query($conn, $txtQuery2);

        $response = [
            'status_code' => 200,
            'status_message' => 'Succesfull',
        ];
        echo json_encode($response);
        mysqli_close($conn);
    }

    function insertSoal($que, $jawaban_benar_txt, $jawaban_salah_txt)
    {
        global $conn;



        //proses insert id_soal ke jawaban
        mysqli_query($conn, $que);
        $txtQuery = "SELECT id FROM tbl_soal ORDER BY id DESC LIMIT 1";
        //proses get data soal terakhir,
        $res = mysqli_fetch_object(mysqli_query($conn, $txtQuery));
        $id = $res->id;
        $txtQuery2 = "INSERT INTO tbl_jawaban_benar(jawaban_benar_txt, soal_id) VALUES('$jawaban_benar_txt', '$id')";
        mysqli_query($conn, $txtQuery2);

        foreach ($jawaban_salah_txt as $value) {
            $querySalah = "INSERT INTO tbl_jawaban_salah(jawaban_salah_txt) VALUES('$value')";
            $querySalah = mysqli_query($conn, $querySalah);
            $idS = mysqli_insert_id($conn);
            $txtQuery4 = "
            INSERT INTO tbl_jawaban(jawaban_salah_id, soal_id) VALUES('$idS', '$id')
            ";
            mysqli_query($conn, $txtQuery4);
        }

        $response = [
            'status_code' => 200,
            'status_message' => 'Successfull',
        ];
        echo json_encode($response);
        mysqli_close($conn);
    }

    function insertNilai($query)
    {
        global $conn;


        mysqli_query($conn, $query);

        $response = [
            'status_code' => 200,
            'status_message' => 'Succesfull',
        ];
        echo json_encode($response);
        mysqli_close($conn);
    }

    // UBAH METHOD // UBAH METHOD // UBAH METHOD // UBAH METHOD // UBAH METHOD

    function ubahSoal(

        $que,
        $id_soal,
        $jawaban_benar,
        $jawaban_salah1,
        $jawaban_salah2,
        $jawaban_salah3,
        $salahid_1,
        $salahid_2,
        $salahid_3
    ) {
        global $conn;



        mysqli_query($conn, $que);

        $query = "
            UPDATE tbl_jawaban_benar SET jawaban_benar_txt = '$jawaban_benar' WHERE id = '$id_soal'
        ";
        mysqli_query($conn, $query);

        $queryy = "
            UPDATE tbl_jawaban_salah SET jawaban_salah_txt = '$jawaban_salah1' WHERE id = '$salahid_1'
        ";
        mysqli_query($conn, $queryy);
        $queryyy = "
            UPDATE tbl_jawaban_salah SET jawaban_salah_txt = '$jawaban_salah2' WHERE id = '$salahid_2'
        ";
        mysqli_query($conn, $queryyy);
        $queryyyy = "
            UPDATE tbl_jawaban_salah SET jawaban_salah_txt = '$jawaban_salah3' WHERE id = '$salahid_3'
        ";
        mysqli_query($conn, $queryyyy);

        $response = [
            'status_code' => 200,
            'status_message' => 'Successfull',
        ];
        echo json_encode($response);
        mysqli_close($conn);
    }

    function ubahSiswa($que, $id, $id_kelas, $tahun_ajaran)
    {
        global $conn;

        mysqli_query($conn, $que);

        $query = "
            UPDATE tbl_kelas_siswa SET tahun_ajaran ='$tahun_ajaran', id_kelas='$id_kelas' WHERE id_siswa = '$id';
        ";
        mysqli_query($conn, $query);

        // $query = "UPDATE tbl_kelas_siswa SET id_kelas='$id_kelas' WHERE id_siswa = '$id' ";
        // mysqli_query($conn, $query);

        $response = [
            'status_code' => 200,
            'status_message' => 'Successfull',
        ];
        echo json_encode($response);
        mysqli_close($conn);
    }

    function ubah($que)
    {
        global $conn;
        mysqli_query($conn, $que);
        $response = [
            'status_code' => 200,
            'status_message' => 'Succesfull',
        ];
        echo json_encode($response);
        mysqli_close($conn);
    }

    function ubahWaktu($que)
    {
        global $conn;


        mysqli_query($conn, $que);


        $response = [
            'status_code' => 200,
            'status_message' => 'Succesfull',
        ];
        echo json_encode($response);
        mysqli_close($conn);
    }

    function verifikasi($que)
    {
        global $conn;

        $count = mysqli_query($conn, $que);

        if (mysqli_num_rows($count) > 0) {
            $data = mysqli_fetch_object($count);
            $id = $data->id;
            $query = "UPDATE tbl_siswa SET flag=1 WHERE id = '$id' ";

            mysqli_query($conn, $query);

            $response = [
                'status_code' => 200,
                'status_message' => 'Succesfull',
            ];
        } else {
            $response = [
                'status_code' => 405,
                'status_message' => 'Data is not found',
            ];
        }



        echo json_encode($response);
        mysqli_close($conn);
    }

    function loginSiswa($que)
    {
        global $conn;

        $count = mysqli_query($conn, $que);
        if (mysqli_num_rows($count) > 0) {
            $data = mysqli_fetch_object($count);
            $flag = $data->flag;
            if ($flag == 1) {

                $response = [
                    'status_code' => 200,
                    'status_message' => 'Login Berhasil',
                    'data' => $data,
                    'token' => "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJkYXRhIjp7ImlkIjoiMSIsIm5hbWEiOiJham95IHN5YW50aWsiLCJyb2xlIjoiYWRtaW5fcGVydGFtYSIsInVzZXJuYW1lIjoiYWpveSIsInBhc3N3b3JkIjoiJDJ5JDEwJFVIbmdkUzVKNGJGQXA4S2ZNWFBwdGVkNThDcElMYXZOLzVBdmV0LmYzTFd0cHlwSzIzeVhTIn19.L_ZTGtE9xT8riKlAkPoCy_jJt1w7y7I9wtZ_D5z1bd4"
                ];
            } else {
                $response = [
                    'status_code' => 405,
                    'status_message' => 'Akun anda belum terverifikasi',
                ];
            }
        } else {
            $response = [
                'status_code' => 400,
                'status_message' => 'nisn atau password salah',
            ];
        }

        echo json_encode($response);
        mysqli_close($conn);
    }

    // DELETE DELETE HAPUS HAPUS DELETE DELETE HAPUS HAPUS DELETE DELETE HAPUS HAPUS 

    function hapusSoal($que, $id_soal)
    {
        global $conn;


        mysqli_query($conn, $que);

        $queryBenar = "
            DELETE FROM tbl_jawaban_benar WHERE soal_id ='$id_soal'
        ";
        mysqli_query($conn, $queryBenar);

        $queryJoin = "
             SELECT tbl_jawaban_salah.* FROM tbl_jawaban_salah, tbl_jawaban WHERE tbl_jawaban.jawaban_salah_id = tbl_jawaban_salah.id
             AND tbl_jawaban.soal_id = '$id_soal'
        ";
        $query = mysqli_query($conn, $queryJoin);

        while (
            $ambil = mysqli_fetch_object($query)
        ) {
            // print($ambil->id);
            $qry = "DELETE FROM tbl_jawaban_salah WHERE id = '$ambil->id'";
            mysqli_query($conn, $qry);
        }

        mysqli_query($conn, "DELETE FROM tbl_jawaban WHERE soal_id = '$id_soal'");

        $response = [
            'status_code' => 200,
            'status_message' => 'Successfull',
        ];
        echo json_encode($response);
        mysqli_close($conn);
    }
}
