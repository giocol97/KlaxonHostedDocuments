<?php
session_start();
if (!$_SESSION["loggedin"]) {
    header('Location: /');
    die();
} else {
    $user = "wecars";
    $pass = "Wecars123";

    $host = "localhost";
    $db = "klaxon";
    $conn = new mysqli($host, $user, $pass, $db);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $result = $conn->query("SELECT DISTINCT filename FROM files");

    $filenames = array();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            array_push($filenames, $row["filename"]);
        }
    }

    $files = array();

    foreach ($filenames as $name) {
        $result = $conn->query("SELECT COUNT(filename) as version FROM files WHERE filename='" . $name . "'");
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $version = $row["version"];
        }

        $result = $conn->query("SELECT date_added, email, id FROM files WHERE filename='" . $name . "' ORDER BY date_added DESC");
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $file = array();
            $file["filename"] = $name;
            $file["version"] = $version;
            $file["email"] = $row["email"];
            $file["date_added"] = $row["date_added"];

            $file["id"] = array($row["id"]);

            while ($row = $result->fetch_assoc()) {
                array_push($file["id"], $row["id"]);
            }

            //$file["id"] = array_reverse($file["id"]);

            array_push($files, $file);
        }
    }

    $conn->close();
}

?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
    <meta name="generator" content="Hugo 0.88.1">
    <title>Klaxon Shared Document Area
</title>

    <link rel="canonical" href="https://getbootstrap.com/docs/5.1/examples/sign-in/">

    <link href="https://getbootstrap.com/docs/5.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" integrity="sha512-9usAa10IRO0HhonpyAIVpjrylPvoDwiPUiKdWk5t3PyolY1cOd4DSE0Ga+ri4AuTroPR5aQvXU9xC6qOPnzFeg==" crossorigin="anonymous" referrerpolicy="no-referrer" />

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
    <link href="files.css" rel="stylesheet">
</head>

<body class="text-center">


    <main class="form-signin">
        <div style="display:inline-flex"><img class="mb-4" src="logo-klaxon.png" alt="" width="50" height="50">
            <h1 class="h3 mb-3 fw-normal">Klaxon Shared Document Area</h1>
        </div>

        <div class="file-upload-container">
            <form enctype="multipart/form-data" id="file-upload-form">

                <input class="form-control form-control-lg" type="file" id="new-document" name="new-document" />

                <div class="w-100 btn btn-lg button-klaxon" id="upload-button">Upload a new file</div>
            </form>
        </div>

        <table class="table">
            <thead>
                <tr>
                    <th scope="col">Filename</th>
                    <th scope="col">Version</th>
                    <th scope="col">Uploaded by</th>
                    <th scope="col">Last modified</th>
                    <th scope="col">Actions</th>
                </tr>
            </thead>
            <tbody>

                <?php
                foreach ($files as $file) {
                ?>
                    <tr>
                        <td><?= $file["filename"] ?></td>
                        <td><?= $file["version"] ?></td>
                        <td><?= $file["email"] ?></td>
                        <td><?= $file["date_added"] ?></td>
                        <td class="button-container">
                            <button type="button" class="btn btn-primary download-button" download-id="<?= $file["id"][0] ?>"><i class="fa-solid fa-arrow-down"></i></button>

                            <div class="dropdown">
                                <button class="btn btn-secondary dropdown-toggle" type="button" id="version-dropdown-button" data-bs-toggle="dropdown" aria-expanded="false">
                                    v<?= $file["version"] ?>
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="version-dropdown-button">
                                    <?php
                                    $j = 0;
                                    for ($i = $file["version"]; $i > 0; $i--) {
                                    ?>
                                        <li><a class="dropdown-item version-dropdown-element" version-id="<?= $file["id"][$j++] ?>" href="#">v<?= $i ?></a></li>
                                    <?php
                                    }
                                    ?>
                                </ul>
                            </div>
                            <?php
                            if ($_SESSION["user"] == $file["email"]) {
                            ?>
                                <button type="button" class="btn btn-danger delete-button" delete-id="<?= $file["id"][0] ?>"><i class="fa-solid fa-trash"></i></button>
                        </td>
                    <?php
                            }
                    ?>
                    </tr>
                <?php
                }
                ?>
            </tbody>
        </table>
    </main>
    <script src="https://getbootstrap.com/docs/5.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script type="text/javascript">
        $(".version-dropdown-element").click(function() {
            var version = $(this).text();
            var id = $(this).attr("version-id");

            $("#version-dropdown-button").text(version);
            $(this).parent().parent().parent().prev().attr("download-id", id);//download button
            $(this).parent().parent().parent().next().attr("delete-id", id)//delete button
        });

        $("#upload-button").click(function() {
            $(this).hide();
            var formData = new FormData($('#file-upload-form')[0])
            $.ajax({
                // Your server script to process the upload
                url: '/upload.php',
                type: 'POST',

                // Form data
                data: formData,

                // Tell jQuery not to process data or worry about content-type
                // You *must* include these options!
                cache: false,
                contentType: false,
                processData: false,

                // Custom XMLHttpRequest
                xhr: function() {
                    var myXhr = $.ajaxSettings.xhr();
                    if (myXhr.upload) {
                        // For handling the progress of the upload
                        myXhr.upload.addEventListener('progress', function(e) {
                            if (e.lengthComputable) {
                                $('progress').attr({
                                    value: e.loaded,
                                    max: e.total,
                                });
                            }
                        }, false);
                    }
                    return myXhr;
                },
                success: function(result) {
                    $(".file-upload-container").html(result);
                }
            });
        });
        $(".delete-button").click(function() {
            var id = $(this).attr("delete-id");
            var el=$(this);
            $.post("delete.php", {
                    deleteid: id
                },
                function(data, status) {
                    el.parent().parent().fadeOut()
                });
        });

        $(".download-button").click(function(){
            var id = $(this).attr("download-id");
            $.post("download.php", {
                    downloadid: id
                },
                function(data, status) {
                    window.location.href = data;
                });
        });
    </script>
</body>

</html>