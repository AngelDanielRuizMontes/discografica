<link href="../css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="../icons/font/bootstrap-icons.min.css">
<link href="../canciones/styles.css" rel="stylesheet" type="text/css" />
<?php
$CodGrupo = isset($_GET['CodGrupo'])?$_GET['CodGrupo']:null;
$title = "Lista de Discos";
?>
<h3 class="disquera">Lista de Discos</h3>
<table border=1>
<tr>
<td>CodDisco</td>
<td>Nombre del Disco</td>
<td>Año de Publicación</td>
<td>Grupo</td>
</tr>
<?php

if ($CodGrupo == null) {
    $apiUrl = $webServer . '/discos';
} else {
    $apiUrl = $webServer . '/grupos/' . $CodGrupo . "/discos";
}

$curl = curl_init($apiUrl);
curl_setopt($curl, CURLOPT_ENCODING ,"");
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
$json = curl_exec($curl);
$discos = json_decode($json);
curl_close($curl);
$filtroGrupo = "CodGrupo=$CodGrupo";
foreach ($discos as $disco) {
    if ($CodGrupo == NULL) {
        $filtroGrupo = "";
    } else {
        $filtroGrupo = "CodGrupo=$CodGrupo";

    }
?>
<tr>
    <td><a href="<?=$urlPrefix?>/discos/view.php?id=<?=$disco->CodDisco?>"><?=$disco->CodDisco?></a></td>
    <td><?=$disco->NombreDisco ?></td>
    <td><?=$disco->AnnoPublicacion ?></td>
    <td><?=$disco->Grupo ?></td>
    <td>
        <a href="<?=$urlPrefix?>/discos/edit.php?id=<?=$disco->CodDisco?>" class="btn btn-primary"><i class="bi bi-pencil"></i></a>
        <a href="<?=$urlPrefix?>/discos/posts.php?id=<?=$disco->CodDisco?>" class="btn btn-primary"><i class="bi bi-eye"></i></a>
        <a href="<?=$urlPrefix?>/discos/delete.php?id=<?=$disco->CodDisco?>" class="btn btn-danger"><i class="bi bi-trash3"></i></a>
    </td>
</tr>
<?php
}
?>
</table>
<br>
<div class="centro">
<a href="<?=$urlPrefix?>/discos/new.php?<?=$filtroGrupo?>" class="btn btn-success"><i class="bi bi-music-note-list"></i> Nuevo Disco</a>&nbsp;&nbsp;
<a href="<?=$urlPrefix?>" class="btn btn-warning" id="enlace"><i class="bi bi-list"></i> Menú</a>
</div>
<script src="../js/bootstrap.bundle.min.js"></script>