<link href="../css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="../icons/font/bootstrap-icons.min.css">
<link href="../styles/styles.css" rel="stylesheet" type="text/css" />
<h3 class="disquera">Lista de Grupos</h3><br>

<table border=1>
<tr>
<td>CodGrupo</td>
<td>Artista</td>
<td>Genero</td>
</tr>
<?php

$apiUrl = $webServer . '/grupos';
$curl = curl_init($apiUrl);
curl_setopt($curl, CURLOPT_ENCODING ,"");
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
$json = curl_exec($curl);
$grupos = json_decode($json);
curl_close($curl);

foreach ($grupos as $grupo) {
?>
<tr>
    <td><a href="<?=$urlPrefix?>/grupos/view.php?id=<?=$grupo->CodGrupo?>"><?=$grupo->CodGrupo?></a></td>
    <td><?=$grupo->Artista ?></td>
    <td><?=$grupo->Genero ?></td>
    <td>
        <a href="<?=$urlPrefix?>/grupos/edit.php?id=<?=$grupo->CodGrupo?>" class="btn btn-primary"><i class="bi bi-pencil"></i></a>
        <a href="<?=$urlPrefix?>/grupos/posts.php?CodGrupo=<?=$grupo->CodGrupo?>" class="btn btn-primary"><i class="bi bi-eye"></i></a>
        <a href="<?=$urlPrefix?>/grupos/delete.php?id=<?=$grupo->CodGrupo?>" class="btn btn-danger"><i class="bi bi-trash3"></i></a>
    </td>
</tr>
<?php
}
?>
</table>
<br>
<div class="centro">
<a href="<?=$urlPrefix?>/grupos/new.php" class="btn btn-success"><i class="bi bi-music-note-list"></i> Nuevo Grupo</a>&nbsp;&nbsp;
<a href="<?=$urlPrefix?>" class="btn btn-warning" id="enlace"><i class="bi bi-list"></i> Menú</a>
</div>
<script src="../js/bootstrap.bundle.min.js"></script>
