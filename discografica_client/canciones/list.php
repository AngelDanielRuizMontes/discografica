<link href="styles.css" rel="stylesheet" type="text/css" />
<link href="../css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="../icons/font/bootstrap-icons.min.css">
<?php
$CodDisco = isset($_GET['id'])?$_GET['id']:null;
$title = "Lista Canciones";
if ($CodDisco != null) {
    $title .= " del " . $CodDisco;
}
?>
<h3 class="disquera"> Lista de Canciones</h3>
<table border=1>
<tr>
<td>CodCancion</td>
<td>Nombre de la Canción</td>
<td>Duración</td>
<td>Disco</td>
</tr>
<?php

if ($CodDisco == null) {
    $apiUrl = $webServer . '/canciones';
} else {
    $apiUrl = $webServer . '/discos/' . $CodDisco . "/canciones";
}

$curl = curl_init($apiUrl);
curl_setopt($curl, CURLOPT_ENCODING ,"");
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
$json = curl_exec($curl);
$canciones = json_decode($json);
curl_close($curl);
$filtroDisco = "CodDisco=$CodDisco";
foreach ($canciones as $cancion) {

    if ($CodDisco == NULL) {
        $filtroDisco = "";
    } else {
        $filtroDisco = "CodDisco=$CodDisco";

    }
?>
<tr>
    <td><a href="<?=$urlPrefix?>/canciones/view.php?id=<?=$cancion->CodCancion?>"><?=$cancion->CodCancion?></a></td>
    <td><?=$cancion->Canción ?></td>
    <td><?=$cancion->Duración ?></td>
    <td><?=$cancion->Disco ?></td>
    <td>
        <a href="<?=$urlPrefix?>/canciones/edit.php?id=<?=$cancion->CodCancion?>" class="btn btn-primary"><i class="bi bi-pencil"></i></a>
        <a href="<?=$urlPrefix?>/canciones/delete.php?id=<?=$cancion->CodCancion?>" class="btn btn-danger"><i class="bi bi-trash3"></i></a>
    </td>
</tr>
<?php
}
?>
</table>
<br>
<div class="centro">
<a href="<?=$urlPrefix?>/canciones/new.php?<?=$filtroDisco?>" class="btn btn-success"><i class="bi bi-music-note-list"></i> Nueva Canción</a>&nbsp;&nbsp;
<a href="<?=$urlPrefix?>" class="btn btn-warning" id="enlace"><i class="bi bi-list"></i> Menú</a>
</div>
<script src="../js/bootstrap.bundle.min.js"></script>