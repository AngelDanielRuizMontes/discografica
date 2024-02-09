<?php
$url = $_SERVER['REQUEST_URI'];
if(strpos($url,"/") !== 0){
    $url = "/$url";
}

$dbInstance = new DB();
$dbConn = $dbInstance->connect($db);

header("Content-Type:application/json");
error_log("URL: " . $url);
error_log("METHOD: " . $_SERVER['REQUEST_METHOD']);
if($url == $urlPrefix . '/discos' && $_SERVER['REQUEST_METHOD'] == 'GET') {
    error_log("List discos");
    $discos = getAllDiscos($dbConn);
    echo json_encode($discos);
}

if(preg_match("/discos\/([0-9]+)\/canciones/", $url, $matches) && $_SERVER['REQUEST_METHOD'] == 'GET'){
    error_log("Listado canciones disco");

    $CodDisco = $matches[1];
    $canciones = getCanciones($dbConn, $CodDisco);
    echo json_encode($canciones);
    return;
}

if($url == $urlPrefix . '/discos' && $_SERVER['REQUEST_METHOD'] == 'POST') {
    error_log("Create Disco");
    $input = $_POST;
    $CodDisco = addDisco($input, $dbConn);
    if($CodDisco){
        $input['id'] = $CodDisco;
        $input['link'] = "/discos/$CodDisco";
    }

    echo json_encode($input);
}

if(preg_match("/discos\/([0-9]+)/", $url, $matches) && $_SERVER['REQUEST_METHOD'] == 'PUT'){
    error_log("Update Disco");

    $input = $_GET;
    $CodDisco = $matches[1];
    updateDisco($input, $dbConn, $CodDisco);

    $disco = getDisco($dbConn, $CodDisco);
    echo json_encode($disco);
}

if(preg_match("/discos\/([0-9]+)/", $url, $matches) && $_SERVER['REQUEST_METHOD'] == 'GET'){
    error_log("Get Discos");

    $CodDisco = $matches[1];
    $disco = getDisco($dbConn, $CodDisco);

    echo json_encode($disco);
}

if(preg_match("/discos\/([0-9]+)/", $url, $matches) && $_SERVER['REQUEST_METHOD'] == 'DELETE'){

    $CodDisco = $matches[1];
    error_log("Delete post: ". $CodDisco);
    $deletedCount = deleteDisco($dbConn, $CodDisco);
    $deleted = $deletedCount >0?"true":"false";

    echo json_encode([
        'id'=> $CodDisco,
        'deleted'=> $deleted
    ]);
}

/**
 * Get Record based on ID
 *
 * @param $db
 * @param $id
 *
 * @return mixed Associative Array with statement fetch
 */
function getDisco($db, $CodDisco) {
    $statement = $db->prepare("SELECT disco.*, grupo.Artista as Grupo FROM disco join grupo on disco.CodGrupo = grupo.CodGrupo where CodDisco=:CodDisco");
    $statement->bindValue(':CodDisco', $CodDisco);
    $statement->execute();

    return $statement->fetch(PDO::FETCH_ASSOC);
}

/**
 * Delete record based on ID
 *
 * @param $db
 * @param $id
 * 
 * @return integer number of deleted records
 */
function deleteDisco($db, $CodDisco) {
    $sql = "DELETE FROM disco where CodDisco=:CodDisco";
    $statement = $db->prepare($sql);
    $statement->bindValue(':CodDisco', $CodDisco);
    $statement->execute();
    return $statement->rowCount();
}

/**
 * Get all records
 *
 * @param $db
 * @return mixed fetchAll result
 */
function getAllDiscos($db) {
    $statement = $db->prepare("SELECT disco.*, grupo.Artista as Grupo FROM disco join grupo on disco.CodGrupo = grupo.CodGrupo");
    $statement->execute();
    $statement->setFetchMode(PDO::FETCH_ASSOC);

    return $statement->fetchAll();
}

/**
 * Add record
 *
 * @param $input
 * @param $db
 * @return integer id of the inserted record
 */
function addDisco($input, $db){

    $sql = "INSERT INTO disco 
            (NombreDisco, AnnoPublicacion, CodGrupo) 
            VALUES 
            (:NombreDisco, :AnnoPublicacion, :CodGrupo)";

    $statement = $db->prepare($sql);

    bindAllValues($statement, $input);

    $statement->execute();

    return $db->lastInsertId();
}

/**
 * @param $statement
 * @param $params
 * @return PDOStatement
 */
function bindAllValues($statement, $params){
    $allowedFields = ['NombreDisco', 'AnnoPublicacion', 'CodGrupo'];

    foreach($params as $param => $value){
        if(in_array($param, $allowedFields)){
            error_log("bind $param $value");
            $statement->bindValue(':'.$param, $value);
        }
    }

    return $statement;
}

/**
 * Get fields as parameters to set in record
 *
 * @param $input
 * @return string
 */
function getParams($input) {
    $allowedFields = ['NombreDisco', 'AnnoPublicacion', 'CodGrupo'];

    foreach($input as $param => $value){
        if(in_array($param, $allowedFields)){
                $filterParams[] = "$param=:$param";
        }
    }

    return implode(", ", $filterParams);
}


/**
 * Update Record
 *
 * @param $input
 * @param $db
 * @param $id
 * @return integer number of updated records
 */
function updateDisco($input, $db, $CodDisco){

    $fields = getParams($input);

    $sql = "UPDATE disco SET $fields WHERE CodDisco=$CodDisco";

    $statement = $db->prepare($sql);

    bindAllValues($statement, $input);
    $statement->execute();

    return $CodDisco;
}

/**
 * Get all comments of the post
 *
 * @param $db
 * @param $postId
 * @return mixed fetchAll result
 */
function getCanciones($db, $CodDisco) {
    $statement = $db->prepare("SELECT disco.CodDisco, disco.NombreDisco as Disco, cancion.NombreCancion as Canción, cancion.DuracionCancion as 'Duración', cancion.CodCancion 
    FROM cancion left join disco on cancion.CodDisco = disco.CodDisco WHERE cancion.CodDisco = $CodDisco");
    $statement->execute();
    $statement->setFetchMode(PDO::FETCH_ASSOC);

    return $statement->fetchAll();
}
