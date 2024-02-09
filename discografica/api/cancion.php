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

if($url == $urlPrefix . '/canciones' && $_SERVER['REQUEST_METHOD'] == 'GET') {
    error_log("Lista Canciones");
    $cancion = getAllCancion($dbConn);
    echo json_encode($cancion);
    return;
}

if($url == $urlPrefix . '/canciones' && $_SERVER['REQUEST_METHOD'] == 'POST') {
    error_log("Create Cancion");
    $input = $_POST;
    $CodCancion = addCancion($input, $dbConn);
    if($CodCancion){
        $input['id'] = $CodCancion;
        $input['link'] = "/canciones/$CodCancion";
    }

    echo json_encode($input);
    return;
}

if(preg_match("/canciones\/([0-9]+)/", $url, $matches) && $_SERVER['REQUEST_METHOD'] == 'PUT'){
    error_log("Update Cancion");

    $input = $_GET;
    $CodCancion = $matches[1];
    updateCancion($input, $dbConn, $CodCancion);

    $cancion = getCancion($dbConn, $CodCancion);
    echo json_encode($cancion);
    return;
}

if(preg_match("/canciones\/([0-9]+)/", $url, $matches) && $_SERVER['REQUEST_METHOD'] == 'GET'){
    error_log("Get Cancion");

    $CodCancion = $matches[1];
    $cancion = getCancion($dbConn, $CodCancion);

    echo json_encode($cancion);
    return;
}

if(preg_match("/canciones\/([0-9]+)/", $url, $matches) && $_SERVER['REQUEST_METHOD'] == 'DELETE'){

    $CodCancion = $matches[1];
    error_log("Delete Cancion: ". $CodCancion);
    $deletedCount = deleteCancion($dbConn, $CodCancion);
    $deleted = $deletedCount >0?"true":"false";

    echo json_encode([
        'id'=> $CodCancion,
        'deleted'=> $deleted
    ]);
    return;
}

/**
 * Get record based on ID
 *
 * @param $db
 * @param $id
 *
 * @return mixed Associative Array with statement fetch
 */
function getCancion($db, $CodCancion) {
    $statement = $db->prepare("SELECT cancion.*, disco.NombreDisco FROM cancion join disco on cancion.CodDisco = disco.CodDisco where CodCancion=:CodCancion");
    $statement->bindValue(':CodCancion', $CodCancion);
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
function deleteCancion($db, $CodCancion) {
    $sql = "DELETE FROM cancion where CodCancion=:CodCancion";
    $statement = $db->prepare($sql);
    $statement->bindValue(':CodCancion', $CodCancion);
    $statement->execute();
    return $statement->rowCount();
}

/**
 * Add record
 *
 * @param $input
 * @param $db
 * @return integer id of the inserted record
 */
function addCancion($input, $db){

    $sql = "INSERT INTO cancion 
    (NombreCancion, DuracionCancion, CodDisco) 
    VALUES (:NombreCancion, :DuracionCancion, :CodDisco)";

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
    $allowedFields = ['NombreCancion', 'DuracionCancion', 'CodDisco'];

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
    $allowedFields = ['NombreCancion', 'DuracionCancion', 'CodDisco'];

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
function updateCancion($input, $db, $CodCancion){

    $fields = getParams($input);

    $sql = "UPDATE cancion SET $fields WHERE CodCancion=$CodCancion";

    $statement = $db->prepare($sql);

    bindAllValues($statement, $input);
    $statement->execute();

    return $CodCancion;
}

function getAllCancion($db) {
    $statement = $db->prepare("SELECT disco.CodDisco, disco.NombreDisco as Disco, cancion.NombreCancion as Canción, cancion.DuracionCancion as 'Duración', cancion.CodCancion 
    FROM cancion left join disco on cancion.CodDisco = disco.CodDisco");
    $statement->execute();
    $statement->setFetchMode(PDO::FETCH_ASSOC);

    return $statement->fetchAll();
}