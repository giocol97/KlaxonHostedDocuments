<?php
session_start();
if (!isset($_SESSION["loggedin"]) && !$_SESSION["loggedin"]) {
    header('Location: /');
    die();
} else {
    if(isset($_POST["downloadid"]) && $_POST["downloadid"]!=""){
        $user = "wecars";
        $pass = "Wecars123";

        $host = "localhost";
        $db = "klaxon";
        $conn = new mysqli($host, $user, $pass, $db);
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $result = $conn->query("SELECT filename FROM files WHERE id=".$_POST["downloadid"]);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $filename=$row["filename"];

            deltree("files/tmp/");
            mkdir("files/tmp/");
            copy("files/".$_POST["downloadid"],"files/tmp/".$filename);

            echo "/files/tmp/".$filename;
        }        
    }
}

function delTree($dir) {
    $files = array_diff(scandir($dir), array('.','..'));
     foreach ($files as $file) {
       (is_dir("$dir/$file")) ? delTree("$dir/$file") : unlink("$dir/$file");
     }
     return rmdir($dir);
   }
?>