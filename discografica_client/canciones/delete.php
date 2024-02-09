<link href="../css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="../icons/font/bootstrap-icons.min.css">
<link href="../styles/delete.css" rel="stylesheet" type="text/css" />
<h1 class="disquera">Eliminar Canción</h1><br>
<?php
require_once "../config.php";

$id = $_GET["id"];
$apiUrl = $webServer . '/canciones/' . $id;
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $apiUrl);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");

curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$server_output = curl_exec($ch);

curl_close ($ch);

$result = json_decode($server_output);

if (!is_null($result) && $result->deleted == "true") {
    ?>
    <h2 class="mensaje">La Canción ha sido eliminada</h2>
    <?php
} else {
    ?>
    <h2 class="mensaje">ERROR: No se pudo eliminar la Canción</h2>
    <h3 class="mensaje">Es posible que la canción que desea borrar no exista.</h3>
    <?php
}

?>
<div class="centro">
<a href = "<?=$urlPrefix?>/canciones.php" class="btn btn-primary">Página Anterior</a>
</div>
<script src="../js/bootstrap.bundle.min.js"></script>