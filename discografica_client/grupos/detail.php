<link rel="shortcut icon" href="../img/favicon.png" type="image/png" />
<?php
require_once "../config.php";

$id = $_GET["id"];
$apiUrl = $webServer . '/grupos/' . $id;

$curl = curl_init($apiUrl);
curl_setopt($curl, CURLOPT_ENCODING ,"");
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
$json = curl_exec($curl);

$httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

curl_close($curl);

if ($httpCode !== 200 || $json === 'false') {
    ?>
    <h1 class="mensaje">El Grupo con el código <?=$id?> no existe.</h1>
    <div class="centro">
    <br><a href = "<?=$urlPrefix?>/grupos.php" class="btn btn-primary">Página Anterior</a>
    </div>
    <?php
    exit();
}
$grupo = json_decode($json);
?>

<form>
<label for="CodGrupo">CodGrupo:</label>
<input type="text" id="CodGrupo" name="CodGrupo" value="<?=$grupo->CodGrupo?>" disabled>
<br><br>
<label for="grupo">Grupo:</label>
<input type="text" id="grupo" name="grupo" value="<?=$grupo->Artista?>" disabled>
<br><br>
<label for="genero">Genero:</label>
<input type="text" id="genero" name="genero" value="<?=$grupo->Genero?>" disabled>
<br><a href = "<?=$urlPrefix?>/grupos.php" class="btn btn-primary">Página Anterior</a>
</form>
<br>