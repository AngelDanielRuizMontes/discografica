<link href="../css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="../icons/font/bootstrap-icons.min.css">
<link href="../styles/edit.css" rel="stylesheet" type="text/css" />
<h3 class="disquera">Actualizar Disco</h3><br>
<?php
require_once "../config.php";

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $id = $_GET['id'];
    $apiUrl = $webServer . '/discos/' . $id;

    $params = array(
        "NombreDisco" => $_POST['NombreDisco'],
        "AnnoPublicacion" => $_POST['AnnoPublicacion'],
        "CodGrupo" =>  $_POST['CodGrupo']
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
        <h1 class="mensaje">El Disco con el código <?=$id?> no existe.</h1>
        <div class="centro">
        <br><a href = "<?=$urlPrefix?>/discos.php" class="btn btn-primary">Página Anterior</a>
        </div>
        <?php
        exit();
    }

    include("detail.php");
} else {
    $id = $_GET["id"];
    $apiUrl = $webServer . '/discos/' . $id;
    $curl = curl_init($apiUrl);
    curl_setopt($curl, CURLOPT_ENCODING, "");
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    $json = curl_exec($curl);
    $disco = json_decode($json);
    curl_close($curl);

    $apiUrl = $webServer . '/grupos';
    $curl = curl_init($apiUrl);
    curl_setopt($curl, CURLOPT_ENCODING, "");
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    $json = curl_exec($curl);
    $grupos = json_decode($json);
    curl_close($curl);

        if (empty($disco)) {
            ?>
            <h1 class="mensaje">El Disco con el código <?=$id?> no existe.</h1>
            <div class="centro">
            <br><a href = "<?=$urlPrefix?>/discos.php" class="btn btn-primary">Página Anterior</a>
            </div>
            <?php
            exit();
        }

?>

    <form method="post">
        <label for="CodDisco">CodDisco:</label>
        <input type="text" id="CodDisco" name="CodDisco" value="<?= $disco->CodDisco ?>" disabled><br><br>
        <label for="NombreDisco">Nombre del Disco:</label>
        <input type="text" id="NombreDisco" name="NombreDisco" value="<?=$disco->NombreDisco?>" required><br><br>
        <label for="AnnoPublicacion">Año de Publicación:</label>
        <input type="text" id="AnnoPublicacion" name="AnnoPublicacion" value="<?=$disco->AnnoPublicacion?>" required><br><br>
        <label for="CodGrupo">Grupo:</label>
        <select name="CodGrupo" id="CodGrupo">
            <?php
            foreach ($grupos as $grupo) {
                $selected = $disco->CodGrupo == $grupo->CodGrupo ? "selected" : "";
                echo "<option value=$grupo->CodGrupo $selected>$grupo->Artista</option>";
            }
            ?>
        </select>
        <br><br>
        <button class="btn btn-success" type="submit">Guardar Cambios</button>
        <a href="<?= $urlPrefix ?>/discos.php" class="btn btn-primary">Página Anterior</a>
    </form>

<?php
}
?>
<script src="../js/bootstrap.bundle.min.js"></script>