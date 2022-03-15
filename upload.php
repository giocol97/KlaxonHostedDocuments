<?php
session_start();
if (!isset($_SESSION["loggedin"]) && !$_SESSION["loggedin"]) {
    header('Location: /');
    die();
} else {
    if (isset($_FILES["new-document"])) {

        $user = "wecars";
        $pass = "Wecars123";

        $host = "localhost";
        $db = "klaxon";
        $conn = new mysqli($host, $user, $pass, $db);
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $stmt = $conn->prepare("INSERT INTO files (filename, email) VALUES (?,?)");
        $stmt->bind_param("ss", $_FILES["new-document"]["name"], $_SESSION["user"]);

        $stmt->execute();

        $result = $conn->query("SELECT id FROM files ORDER BY date_added DESC");

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $id = $row["id"];
        }

        if (move_uploaded_file($_FILES["new-document"]["tmp_name"], "files/" . $id)) {
            echo '<div class="alert alert-warning" role="alert">
            Upload Completed
          </div>';
        } else {
            echo '<div class="alert alert-danger" role="alert">
            Upload Error
          </div>';
        }
    }
}
