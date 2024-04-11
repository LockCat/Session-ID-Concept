

   <?php
$servername = "local host";
$username = "root";
$password = "";
$dbname = "Session-ID";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$data = json_decode(file_get_contents("php://input"));
$session_id = $data->session_id;

$sql = "SELECT * FROM users WHERE session_id = '$session_id'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo json_encode(array('exists' => true));
} else {
    echo json_encode(array('exists' => false));
}

$conn->close();
?>
