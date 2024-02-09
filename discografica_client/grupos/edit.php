<link href="../css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="../icons/font/bootstrap-icons.min.css">
<link href="../styles/edit.css" rel="stylesheet" type="text/css" />
<h1 class="disquera">Actualizar Grupo</h1><br>
<?php
require_once "../config.php";

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $id = $_GET['id'];
    $apiUrl = $webServer . '/grupos/' . $id;
    $params = array(
        "Artista" => $_POST['Artista'],
        "Genero" => $_POST['Genero']
    );
    $apiUrl .= "?" . http_build_query($params);

    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $apiUrl);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $server_output = curl_exec($ch);

    curl_close($ch);

    $result = json_decode($server_output);

    $httpCode = 200;
    if ($httpCode !== 200 ) {
        ?>
        <h1 class="mensaje">El Grupo con el código <?=$id?> no existe.</h1>
        <div class="centro">
        <br><a href = "<?=$urlPrefix?>/grupos.php" class="btn btn-primary">Página Anterior</a>
        </div>
        <?php
        exit();
    }

    include("detail.php");
} else {
    $id = $_GET["id"];
    $apiUrl = $webServer . '/grupos/' . $id;
    $curl = curl_init($apiUrl);
    curl_setopt($curl, CURLOPT_ENCODING, "");
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    $json = curl_exec($curl);
    $grupo = json_decode($json);
    curl_close($curl);

        if (empty($grupo)) {
            ?>
            <h1 class="mensaje">El Grupo con el código <?=$id?> no existe.</h1>
            <div class="centro">
            <br><a href = "<?=$urlPrefix?>/grupos.php" class="btn btn-primary">Página Anterior</a>
            </div>
            <?php
            exit();
        }
?>

    <form method="POST">
        <label for="CodGrupo">CodGrupo:</label>
        <input type="text" id="CodGrupo" name="CodGrupo" value="<?= $grupo->CodGrupo ?>" disabled><br><br>
        <label for="Artista">Grupo:</label>
        <input type="text" id="Artista" name="Artista" value="<?= $grupo->Artista ?>" required><br><br>
        <label for="Genero">Genero:</label>
        <input type="text" id="Genero" name="Genero" value="<?= $grupo->Genero ?>" required><br><br>
        <button class="btn btn-success" type="submit">Guardar Cambios</button>
        <a href = "<?=$urlPrefix?>/grupos.php" class="btn btn-primary">Página Anterior</a>
    </form>

<?php
}
?>
<script src="../js/bootstrap.bundle.min.js"></script>