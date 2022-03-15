<?php
session_start();
if(isset($_POST["email"]) && $_POST["email"]!="" && isset($_POST["password"]) && $_POST["password"]!="" ){
  $user="wecars";
  $pass="Wecars123";
  
  $host="localhost";
  $db="klaxon";
  $conn=new mysqli($host, $user, $pass,$db);
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  $mail=$_POST["email"];
  $password=$_POST["password"];

  $stmt = $conn->prepare("SELECT email FROM users WHERE email=? AND password=?");
  $stmt->bind_param("ss", $mail,$password);

  $stmt->execute();

  $stmt->bind_result($out);

  if($stmt->fetch()){
    $_SESSION["loggedin"]=true;
    $_SESSION["user"]=$mail;
    $_SESSION["error"]="0";
    header('Location: /files.php');    
  }else{
    $_SESSION["error"]="1";
    header('Location: /');
  }


  $stmt->close();
  $conn->close();  
}
die();

?>