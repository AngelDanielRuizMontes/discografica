<link href="../styles/edit.css" rel="stylesheet" type="text/css" />
<link href="../css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="../icons/font/bootstrap-icons.min.css">
<h1 class="disquera">Editar Cancion</h1><br>
<?php
require_once "../config.php";

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $id = $_GET['id'];
    $apiUrl = $webServer . '/canciones/' . $id;
    $params = array("CodDisco"   => $_POST['CodDisco'],
                    "NombreCancion"   => $_POST['NombreCancion'],
                    "DuracionCancion"     => $_POST['DuracionCancion'],
                    "CodCancion"   =>  $_GET['id']);
    $apiUrl .= "?" . http_build_query($params);

    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $apiUrl);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");

	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

	$server_output = curl_exec($ch);

	curl_close ($ch);
    
	$result = json_decode($server_output);

	$CodCancion=$id;
	include("detail.php");
} else {
    $id = $_GET["id"];
    $apiUrl = $webServer . '/canciones/' . $id;
    $curl = curl_init($apiUrl);
    curl_setopt($curl, CURLOPT_ENCODING ,"");
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    $json = curl_exec($curl);
    $cancion = json_decode($json);
    curl_close($curl);
    
    $apiUrl = $webServer . '/grupos';
    $curl = curl_init($apiUrl);
    curl_setopt($curl, CURLOPT_ENCODING ,"");
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    $json = curl_exec($curl);
    $grupos = json_decode($json);
    curl_close($curl);

    $apiUrl = $webServer . '/discos';
    $curl = curl_init($apiUrl);
    curl_setopt($curl, CURLOPT_ENCODING ,"");
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    $json = curl_exec($curl);
    $discos = json_decode($json);
    curl_close($curl);
    
        if (empty($cancion)) {
            ?>
            <h1 class="mensaje">La Canción con el código <?=$id?> no existe.</h1>
            <div class="centro">
            <br><a href = "<?=$urlPrefix?>/canciones.php" class="btn btn-primary">Página Anterior</a>
            </div>
            <?php
            exit();
        }


?>

<form method="POST">
        <label for="CodCancion">CodCancion:</label>
        <input type="text" id="CodCancion" name="CodCancion" value="<?= $cancion->CodCancion ?>" disabled><br><br>
        <label for="NombreCancion">Nombre de la Canción:</label>
        <input type="text" id="NombreCancion" name="NombreCancion" value="<?= $cancion->NombreCancion ?>" required><br><br>
        <label for="DuracionCancion">Duración:</label>
        <input type="text" id="DuracionCancion" name="DuracionCancion" value="<?= $cancion->DuracionCancion ?>" required><br><br>
        <label for="NombreDisco">Disco:</label>
        <select name="CodDisco" id="NombreDisco">
            <?php
            foreach ($discos as $disco) {
                $selected = $cancion->CodDisco == $disco->CodDisco ? "selected" : "";
                echo "<option value=$disco->CodDisco $selected>$disco->NombreDisco</option>";
            }
            ?>
        </select>
        <br><br>
        <button class="btn btn-success" type="submit">Guardar Cambios</button>
        <a href = "<?=$urlPrefix?>/canciones.php" class="btn btn-primary" id="centro">Página Anterior</a>
    </form>

<?php
}
?>

<script src="./js/bootstrap.bundle.min.js"></script>