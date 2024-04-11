<?php
session_start();

$servername = "local host";
$username = "root";
$password = "";
$dbname = "Session-ID";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    $sql = "SELECT * FROM users WHERE username=? AND password=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        if ($row["status"] === "Banned" || $row["agbs_status"] === "Not Accepted") {
            header("Location: banned.html");
            exit(); 
        } else {
            $session_id = bin2hex(random_bytes(10)); 

            setcookie("session_id", $session_id, time() + (86400 * 1), "/"); 

            $update_sql = "UPDATE users SET session_id=? WHERE username=?";
            $update_stmt = $conn->prepare($update_sql);
            $update_stmt->bind_param("ss", $session_id, $username);
            $update_stmt->execute();
            $update_stmt->close();

            $_SESSION["username"] = $username;
            header("Location: ai.html");
            exit();
        }
    } else {
        echo "Falscher Benutzername oder Passwort.";
    }

    $stmt->close();
}

$conn->close();
?>
