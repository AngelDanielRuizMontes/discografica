<link href="styles.css" rel="stylesheet" type="text/css" />
<link href="../css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="../icons/font/bootstrap-icons.min.css">
<?php
require_once "../config.php";

$apiUrl = $webServer . '/canciones/' . $CodCancion;

$curl = curl_init($apiUrl);
curl_setopt($curl, CURLOPT_ENCODING ,"");
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
$json = curl_exec($curl);

$httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

curl_close($curl);

if ($httpCode !== 200 || $json === 'false') {
    ?>
    <h1 class="mensaje">La canción con el código <?=$CodCancion?> no existe.</h1>
    <div class="centro">
    <br><a href = "<?=$urlPrefix?>/canciones.php" class="btn btn-primary">Página Anterior</a>
    </div>
    <?php
    exit();
}

$cancion = json_decode($json);

?>

<form>
<label for="CodCancion">CodCancion:</label>
<input type="text" id="CodCancion" name="CodCancion" value="<?=$cancion->CodCancion?>" disabled>
<input type="hidden" id="CodCancion" name="CodCancion" value="<?=$cancion->CodCancion?>">
<br><br>
<label for="NombreCancion">Nombre de la Canción:</label>
<input type="text" id="NombreCancion" name="NombreCancion" value="<?=$cancion->NombreCancion?>" disabled>
<br><br>
<label for="DuracionCancion">Duración:</label>
<input type="text" id="DuracionCancion" name="DuracionCancion" value="<?=$cancion->DuracionCancion?>" disabled>
<br><br>
<label for="NombreDisco">Disco:</label>
<input type="text" id="NombreDisco" name="NombreDisco" value="<?=$cancion->NombreDisco?>" disabled>
<a href = "<?=$urlPrefix?>/canciones.php" class="btn btn-primary" id="centro">Página Anterior</a>
</form>

<script src="./js/bootstrap.bundle.min.js"></script>