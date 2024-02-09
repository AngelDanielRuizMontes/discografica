<link href="../styles/styles.css" rel="stylesheet" type="text/css" />
<link href="../css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="../icons/font/bootstrap-icons.min.css">
<?php
require_once "../config.php";

$id = $_GET["id"];
$apiUrl = $webServer . '/discos/' . $id;

$curl = curl_init($apiUrl);
curl_setopt($curl, CURLOPT_ENCODING ,"");
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
$json = curl_exec($curl);
$disco = json_decode($json);

$httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

curl_close($curl);

if ($httpCode !== 200 || $json === 'false') {
    ?>
    <h1 class="mensaje">El Disco con el código <?=$id?> no existe.</h1>
    <div class="centro">
    <br><a href = "<?=$urlPrefix?>/discos.php" class="btn btn-primary">Página Anterior</a>
    </div>
    <?php
    exit();
}
$disco = json_decode($json);
?>

<form>
<label for="CodDisco">CodDisco:</label>
<input type="text" id="CodDisco" name="CodDisco" value="<?=$disco->CodDisco?>" disabled><br><br>
<label for="NombreDisco">Nombre del Disco:</label>
<input type="text" id="NombreDisco" name="NombreDisco" value="<?=$disco->NombreDisco?>" disabled><br><br>
<label for="AnnoPublicacion">Año de Publicación:</label>
<input type="text" id="AnnoPublicacion" name="AnnoPublicacion" value="<?=$disco->AnnoPublicacion?>" disabled><br><br>
<label for="Grupo">Grupo:</label>
<input type="text" id="Grupo" name="Grupo" value="<?=$disco->Grupo?>" disabled>
<a href = "<?=$urlPrefix?>/discos.php" class="btn btn-primary">Página Anterior</a>
</form>
<script src="../js/bootstrap.bundle.min.js"></script>