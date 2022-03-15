<?php
session_start();

if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"]) {
  header('Location: /files.php');
  die();
}

?>

<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Klaxon Shared Document Area
</title>

  <link rel="canonical" href="https://getbootstrap.com/docs/5.1/examples/sign-in/">

  <!-- Bootstrap core CSS -->
  <link href="https://getbootstrap.com/docs/5.1/dist/css/bootstrap.min.css" rel="stylesheet">

  <style>
    .bd-placeholder-img {
      font-size: 1.125rem;
      text-anchor: middle;
      -webkit-user-select: none;
      -moz-user-select: none;
      user-select: none;
    }

    @media (min-width: 768px) {
      .bd-placeholder-img-lg {
        font-size: 3.5rem;
      }
    }
  </style>


  <!-- Custom styles for this template -->
  <link href="signin.css" rel="stylesheet">
</head>

<body class="text-center">

  <main class="form-signin">
    <form action="login.php" method="post">
      <img class="mb-4" src="logo-klaxon.png" alt="" width="200" height="200">
      <h1 class="h3 mb-3 fw-normal">Klaxon Shared Document Area</h1>
      <?php
      if ($_SESSION["error"] == "1") {
      ?><div class="alert alert-warning" role="alert">
          Wrong email/password, try again.
        </div>
      <?php } ?>

      <div class="form-floating">
        <input type="email" class="form-control" id="floatingInput" name="email" placeholder="name@example.com">
        <label for="floatingInput">Email address</label>
      </div>
      <div class="form-floating">
        <input type="password" class="form-control" id="floatingPassword" name="password" placeholder="Password">
        <label for="floatingPassword">Password</label>
      </div>

      <button class="w-100 btn btn-lg button-klaxon" type="submit">Sign in</button>
      <p class="mt-5 mb-3 text-muted">&copy; 2022 Klaxon Mobility GmbH</p>
    </form>
  </main>



</body>

</html>