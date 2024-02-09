<link href="../css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="../icons/font/bootstrap-icons.min.css">
<link href="../styles/styles.css" rel="stylesheet" type="text/css" />
<?php
require_once "../config.php";

$CodGrupo = isset($_GET['CodGrupo'])?$_GET['CodGrupo']:null;
$title = "Nuevo Disco";
if ($CodGrupo != null) {
    $title .= " Para el Grupo " . $CodGrupo;
}
?>
<h3 class="disquera">Nuevo Disco</h3>
<?php

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $apiUrl = $webServer . '/discos';

	$ch = curl_init();

	curl_setopt($ch, CURLOPT_URL, $apiUrl);
	curl_setopt($ch, CURLOPT_POST, 1);

	curl_setopt($ch, CURLOPT_POSTFIELDS, 
    http_build_query($_POST));

	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

	$server_output = curl_exec($ch);

	curl_close ($ch);
    
	$disco = json_decode($server_output);
    $_GET["id"] = $disco->id;

	include("detail.php");
} else {
	$apiUrl = $webServer . '/grupos/' . $CodGrupo;
	$curl = curl_init($apiUrl);
	curl_setopt($curl, CURLOPT_ENCODING ,"");
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	$json = curl_exec($curl);
	$grupo = json_decode($json);
	curl_close($curl);

	$apiUrl = $webServer . '/grupos';
	$curl = curl_init($apiUrl);
	curl_setopt($curl, CURLOPT_ENCODING ,"");
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	$json = curl_exec($curl);
	$grupos = json_decode($json);
	curl_close($curl);
?>

<form method="post" >
<label for="CodDisco">CodDisco:</label>
<input type="text" id="CodDisco" name="CodDisco" value="" disabled><br><br>
<label for="NombreDisco">Nombre del Disco:</label>
<input type="text" id="NombreDisco" name="NombreDisco" value="" maxlength="30" required><br><br>
<label for="AnnoPublicacion">Año de Publicación:</label>
<input type="text" id="AnnoPublicacion" name="AnnoPublicacion" value="" maxlength="4" required><br><br>
<label for="user_id">Grupo:</label>
<?php
if ($CodGrupo == null){
	?>
	<select name="CodGrupo" id="CodGrupo">
	<?php
	foreach ($grupos as $grupo) {
		$selected = $CodGrupo==$grupo->CodGrupo?"selected":"";
		echo "<option value=$grupo->CodGrupo $selected>$grupo->Artista</option>";
	}
	?>
	</select>
	<?php
	}else{
	?>
		<input type="hidden" name="CodGrupo" value="<?=$CodGrupo?>">
		<input type="text" id="Artista" name="Artista" value="<?= $grupo -> Artista ?>" disabled>
	<?php
	}
?>
</select>
<br><br>
<button class="btn btn-success" type="submit">Guardar</button>
<a href = "<?=$urlPrefix?>/discos.php" class="btn btn-primary">Página Anterior</a>
</form>
<?php
}
?>
<script src="../js/bootstrap.bundle.min.js"></script>