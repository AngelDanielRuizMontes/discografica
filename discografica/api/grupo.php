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
if($url == $urlPrefix . '/grupos' && $_SERVER['REQUEST_METHOD'] == 'GET') {
    error_log("Lista Grupos");
    $grupo = getAllGrupo($dbConn);
    echo json_encode($grupo);
    return;
}

if(preg_match("/grupos\/([0-9]+)\/discos/", $url, $matches) && $_SERVER['REQUEST_METHOD'] == 'GET'){
    error_log("Lista Disco de un Grupo");

    $CodGrupo = $matches[1];
    $discos = getDiscos($dbConn, $CodGrupo);
    echo json_encode($discos);
    return;
}


if($url == $urlPrefix . '/grupos' && $_SERVER['REQUEST_METHOD'] == 'POST') {
    error_log("Create Grupo");
    $input = $_POST;
    $CodGrupo = addGrupo($input, $dbConn);
    if($CodGrupo){
        $input['id'] = $CodGrupo;
        $input['link'] = "/grupos/$CodGrupo";
    }

    echo json_encode($input);

}

if(preg_match("/grupos\/([0-9]+)/", $url, $matches) && $_SERVER['REQUEST_METHOD'] == 'PUT'){
    error_log("Update Grupo");

    $input = $_GET;
    $CodGrupo = $matches[1];
    updateGrupo($input, $dbConn, $CodGrupo);

    $grupo = getGrupo($dbConn, $CodGrupo);
    echo json_encode($grupo);
}

if(preg_match("/grupos\/([0-9]+)/", $url, $matches) && $_SERVER['REQUEST_METHOD'] == 'GET'){
    error_log("Get grupo");

    $CodGrupo = $matches[1];
    $grupo = getGrupo($dbConn, $CodGrupo);

    echo json_encode($grupo);
}

if(preg_match("/grupos\/([0-9]+)/", $url, $matches) && $_SERVER['REQUEST_METHOD'] == 'DELETE'){

    $CodGrupo = $matches[1];
    error_log("Delete Grupo: ". $CodGrupo);
    $deletedCount = deleteGrupo($dbConn, $CodGrupo);
    $deleted = $deletedCount >0?"true":"false";

    echo json_encode([
        'id'=> $CodGrupo,
        'deleted'=> $deleted
    ]);
}

/**
 * Get record based on ID
 *
 * @param $db
 * @param $id
 *
 * @return mixed Associative Array with statement fetch
 */
function getGrupo($db, $CodGrupo) {
    $statement = $db->prepare("SELECT * FROM grupo where CodGrupo=:CodGrupo");
    $statement->bindValue(':CodGrupo', $CodGrupo);
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
function deleteGrupo($db, $CodGrupo) {
    $sql = "DELETE FROM grupo where CodGrupo=:CodGrupo";
    $statement = $db->prepare($sql);
    $statement->bindValue(':CodGrupo', $CodGrupo);
    $statement->execute();
    return $statement->rowCount();
}

/**
 * Get all records
 *
 * @param $db
 * @return mixed fetchAll result
 */
function getAllGrupo($db) {
    $statement = $db->prepare("SELECT * FROM grupo");
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
function addGrupo($input, $db){

    $sql = "INSERT INTO grupo 
            (Artista, Genero) 
            VALUES 
            (:Artista, :Genero)";

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
    $allowedFields = ['Artista', 'Genero'];

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
    $allowedFields = ['Artista', 'Genero'];

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
function updateGrupo($input, $db, $CodGrupo){

    $fields = getParams($input);

    $sql = "UPDATE grupo 
            SET $fields 
            WHERE CodGrupo=$CodGrupo";

    $statement = $db->prepare($sql);

    bindAllValues($statement, $input);
    $statement->execute();

    return $CodGrupo;
}

/**
 * Get all posts of the user
 *
 * @param $db
 * @param $CodGrupo
 * @return mixed fetchAll result
 */
function getDiscos($db, $CodGrupo) {
    $statement = $db->prepare("SELECT disco.CodDisco, disco.NombreDisco, grupo.Artista as Grupo, disco.AnnoPublicacion, grupo.CodGrupo
    FROM disco left join grupo on disco.CodGrupo = grupo.CodGrupo WHERE disco.CodGrupo = $CodGrupo");
    $statement->execute();
    $statement->setFetchMode(PDO::FETCH_ASSOC);

    return $statement->fetchAll();
}

