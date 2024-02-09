<link href="styles.css" rel="stylesheet" type="text/css" />
<link href="../css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="../icons/font/bootstrap-icons.min.css">
<h1 class="disquera">Nueva Canción</h1><br>
<?php
require_once "../config.php";

$CodDisco = empty($_GET["CodDisco"])?NULL:$_GET["CodDisco"];
if ($_SERVER['REQUEST_METHOD'] == "POST") {

    $apiUrl = $webServer . '/canciones';

	$ch = curl_init();

	curl_setopt($ch, CURLOPT_URL, $apiUrl);
	curl_setopt($ch, CURLOPT_POST, 1);

	curl_setopt($ch, CURLOPT_POSTFIELDS, 
        http_build_query($_POST));

	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

	$server_output = curl_exec($ch);

	curl_close ($ch);
    
	$cancion = json_decode($server_output);
    $CodCancion = $cancion->id;

	include("detail.php");
} else {
	$apiUrl = $webServer . '/discos/' . $CodDisco;
	$curl = curl_init($apiUrl);
	curl_setopt($curl, CURLOPT_ENCODING ,"");
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	$json = curl_exec($curl);
	$disco = json_decode($json);
	curl_close($curl);

	$apiUrl = $webServer . '/discos';
	$curl = curl_init($apiUrl);
	curl_setopt($curl, CURLOPT_ENCODING ,"");
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	$json = curl_exec($curl);
	$discos = json_decode($json);
	curl_close($curl);

?>

<form method="post" >
<label for="NombreCancion">Nombre de la Canción:</label>
<input type="text" id="NombreCancion" name="NombreCancion" required maxlength="30"><br>
<br>
<label for="DuracionCancion">Duración:</label>
<input type="text" id="DuracionCancion" name="DuracionCancion" placeholder="00:00:00" required><br><br>
<label for="NombreDisco">Disco:</label>
<?php
if ($CodDisco == null){
?>
<select name="CodDisco" id="CodDisco">
<?php
foreach ($discos as $disco) {
	$selected = $CodDisco==$disco->CodDisco?"selected":"";
	echo "<option value=$disco->CodDisco $selected>$disco->NombreDisco</option>";
}
?>
</select>
<?php
}else{
?>
	<input type="hidden" name="CodDisco" value="<?=$CodDisco?>">
	<input type="text" id="NombreDisco" name="NombreDisco" value="<?= $disco -> NombreDisco ?>" disabled>
<?php
}

?>
<br><br>
<button class="btn btn-success" type="submit">Guardar</button>
<a href = "<?=$urlPrefix?>/canciones.php" class="btn btn-primary" id="centro">Página Anterior</a>
</form>

<?php
}
?>

<script src="./js/bootstrap.bundle.min.js"></script>