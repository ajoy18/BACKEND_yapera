 <?php 

global $conn;

$servername = "localhost";
$database = "db_smayapera";
$username = "root";
$password = "";

//Create connection
$conn = mysqli_connect($servername, $username, $password, $database);

// Check Connection
if (!$conn){
    die("Koneksi Gagal: " . mysqli_connect_error());
}

?>