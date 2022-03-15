<?php
session_start();
if (!isset($_SESSION["loggedin"]) && !$_SESSION["loggedin"]) {
    header('Location: /');
    die();
} else {
    if(isset($_POST["deleteid"]) && $_POST["deleteid"]!="" && isset($_SESSION["user"]) && $_SESSION["user"]!=""){
        $user = "wecars";
        $pass = "Wecars123";

        $host = "localhost";
        $db = "klaxon";
        $conn = new mysqli($host, $user, $pass, $db);
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $stmt = $conn->prepare("DELETE FROM files WHERE id=? AND email=?");
        $stmt->bind_param("ss", $_POST["deleteid"],$_SESSION["user"]);

        $stmt->execute();
    }
}
?>