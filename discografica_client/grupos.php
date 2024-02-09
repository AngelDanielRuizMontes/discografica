<?php
require_once "config.php";

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="./img/favicon.png" type="image/png" />
    <link href="./styles/styles.css" rel="stylesheet" type="text/css" />
    <link href="./css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="./icons/font/bootstrap-icons.min.css">
    <title>Discográfica Turing</title>
</head>

<body>
    <div class="container">
        <div class="content">

            <div class="mb-3 aire">
            <?php
            include "grupos/list.php";
            ?>
            </div>
        </div>
    </div>
    <script src="./js/bootstrap.bundle.min.js"></script>
</body>

</html>