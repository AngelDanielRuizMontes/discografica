<link href="../styles/styles.css" rel="stylesheet" type="text/css" />
<link href="../css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="../icons/font/bootstrap-icons.min.css">
<h1 class="disquera">Nuevo Grupo</h1><br>
<?php
require_once "../config.php";

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $apiUrl = $webServer . '/grupos';

	$ch = curl_init();

	curl_setopt($ch, CURLOPT_URL, $apiUrl);
	curl_setopt($ch, CURLOPT_POST, 1);

	curl_setopt($ch, CURLOPT_POSTFIELDS, 
	http_build_query($_POST));

	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

	$server_output = curl_exec($ch);

	curl_close ($ch);
    
	$grupo = json_decode($server_output);
    $_GET["id"] = $grupo->id;

	include("detail.php");
	?>
	<?php
} else {
?>

<form method="POST">
        <label for="CodGrupo">CodGrupo:</label>
        <input type="text" id="CodGrupo" name="CodGrupo" value="" disabled><br><br>
        <label for="Artista">Grupo:</label>
        <input type="text" id="Artista" name="Artista" value="" maxlength="30" required><br><br>
        <label for="Genero">Genero:</label>
        <input type="text" id="Genero" name="Genero" value="" maxlength="30" required><br><br>
		<button class="btn btn-success" type="submit">Guardar</button>
		<a href = "<?=$urlPrefix?>/grupos.php" class="btn btn-primary">Página Anterior</a>
	</form>
<?php };
?>
<script src="./js/bootstrap.bundle.min.js"></script>
