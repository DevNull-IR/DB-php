<?php
 $pdo = null;
function connect(string $dbname,string $username_db,string $password_db,string $host = 'localhost'){
        global $pdo;
        $Option = [
        PDO::ATTR_PERSISTENT => TRUE,
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::MYSQL_ATTR_INIT_COMMAND =>'SET NAMES utf8',
        PDO::ATTR_EMULATE_PREPARES => false
    ];
    
    try {
        $pdo = new PDO("mysql:host=". $host .";dbname=". $dbname .";charset=utf8", $username_db , $password_db , $Option ); // set and connect to db by pdo
        $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false); // not hack :)
        
    } catch (PDOException $error) {
        file_put_contents("ErrorDB.log",$error->getMessage().PHP_EOL,8);
        die("Error To Connected To Mysql");
    }

}
function select(string $select, string $db,$where = "None",string $other = null){
    global $pdo;
    $a = null;
    $answer = [];
    if (isset($other) && !empty($other)){
        $other = trim($other);
        $other = " $other";
    } else {
        $other = null;
    }
    if ($where === "None"){
        $where_q = "1";
    } else if (gettype($where) == "array"){
        foreach($where as $key => $value){
            $a .= "$key = ? and ";
            $answer[] = $value;
        }
        $where_q = preg_replace("/ and(?=( \w+)?$)/", null, trim($a));
    } else {
        $where_q = "1";
    }
    $query = 'select ' . $select . ' from ' . $db . ' where ' . $where_q . $other;
    $result = $pdo->prepare($query);
    if (gettype($where) == "array"){
        for($i = 1;$i<count($where) +1; $i++){
        $result->bindValue($i,$answer[$i -1]);
        }
    }
    $result->execute();
    $execute = [
        'count' => $result->rowCount(),
        'fetchAll' => $result->fetchall(PDO::FETCH_ASSOC),
    ];
    $execute['fetch'] = $execute['fetchAll'][0] ?? null;
    if ($execute['fetch'] == null){
        unset($execute['fetch']);
    }
    return $execute;
}


function insert(string $table,array $array){
    global $pdo;
    $a = null;
    $b = null;
    $answer = [];
    foreach($array as $key=>$value){
    	$a .= " ? ,";
        $b .= " $key ,";
        $answer[] = $value;
    }
    $a = preg_replace('/,(?=( \w+)?$)/',null,$a);
    $b = preg_replace('/,(?=( \w+)?$)/',null,$b);
    $query = 'INSERT INTO ' . $table .'( ' . $b . ' ) VALUES (' . $a . ')';
    $result = $pdo->prepare($query);
    for($i = 1;$i<count($array) +1; $i++){
        $result->bindValue($i,$answer[$i -1]);
    }
    $result->execute();
    return $result->rowCount();
}


function deleted(string $table ,$where = "None",string $other = null){
    global $pdo;
    $a = null;
    if (isset($other) && !empty($other)){
        $other = trim($other);
        $other = " $other";
    } else {
        $other = null;
    }
    if ($where === "None"){
        $where_q = "1";
    } else if (gettype($where) == "array"){
        foreach($where as $key => $value){
            $a .= "$key = ? and ";
            $answer[] = $value;
        }
        $where_q = preg_replace("/ and(?=( \w+)?$)/", null, trim($a));
    } else {
        $where_q = "1";
    }
    $query = "DELETE FROM $table WHERE $where_q $other";
    $query = trim($query);
    $result = $pdo->prepare($query);
    if (gettype($where) == "array"){
        for($i = 1;$i<count($where) +1; $i++){
            $result->bindValue($i,$answer[$i -1]);
        }
    }
    $result->execute();
    return $result->rowCount();
}


function update(string $db,$update,$where = "None",string $other = null){
    global $pdo;
    $answer = [];
    $a = null;
    $answer_where = [];
    $b = null;
    foreach($update as $key => $value){
        $a .= "$key = ? , ";
        $answer[] = $value;
    }
    if (gettype($where) == "array"){
        foreach($where as $key => $value){
            $b .= "$key = ? and ";
            $answer_where[] = $value;
        }
        $b = preg_replace("/ and(?=( \w+)?$)/i", null, trim($b));
    } else {
        $b = 1;
    }
    $a = preg_replace("/ ,(?=( \w+)?$)/", null, trim($a));
    if (isset($other) && !empty($other)){
        $other = " $other";
    }
    $Sql = "UPDATE $db SET $a WHERE $b" . $other;
    $Sql = trim($Sql);
	$result = $pdo->prepare($Sql);
    if (gettype($update) == "array"){
		for($i = 1; $i < count($update) + 1; $i++){
			$result->bindValue($i,$answer[$i -1]);
		}
	}
	if (gettype($where) == "array"){
		for($i = count($update) + 1; $i < count($where) + count($update) + 1; $i++){
			$result->bindValue($i,$answer_where[$i -count($update)-1]);
		}
	}
	    $result->execute();
    return $result->rowCount();
}
function like(string $select,string $table,$like,$where = null){
    global $pdo;
    $a = null;
	if (gettype($like) == 'array'){
    	foreach($like as $key=>$value){
        	$a .= "$key like ? and ";
            $answer[] = $value;
        }
    }
    if (gettype($where) == 'array'){
    	foreach($where as $key=>$value){
        	$a .= "$key = ? and ";
            $answer[] = $value;
        }
    }
    $a = preg_replace("/ and(?=( \w+)?$)/i", null, trim($a));
    $a = "select $select from $table where $a";
    $result = $pdo->prepare($a);
    for($i = 1;$i < count($answer) + 1;$i++){
        $result->bindValue($i,$answer[$i - 1]);
    }
    $result->execute();
        $execute = [
        'count' => $result->rowCount(),
        'fetchAll' => $result->fetchall(PDO::FETCH_ASSOC),
    ];
    $execute['fetch'] = $execute['fetchAll'][0] ?? null;
    if ($execute['fetch'] == null){
        unset($execute['fetch']);
    }
    return $execute;
}

function table(string $table,$column){
    global $pdo;
    $a = null;
    if (gettype($column) == 'array'){
    	foreach($column as $key=>$value){
        	$a .= "$key $value,
        ";
        }
    }
    $a = preg_replace("/,(?=( \w+)?$)/", null, trim($a));
    $query = "CREATE TABLE $table (
        $a
        )";
    echo $query;
    $result = $pdo->prepare($query);
    $result->execute();
    return $result->rowCount();
}
function unique(string $table,$column){
    global $pdo;
    $a = null;
    if (gettype($column) == 'array'){
    	foreach($column as $key=>$value){
        	$a .= "$value,";
        }
    }
    $a = preg_replace("/,(?=( \w+)?$)/", null, trim($a));
    $q = "ALTER TABLE $table
    ADD UNIQUE ($a);";
    $result = $pdo->prepare($q);
    $result->execute();
    return $result->rowCount();
}
function primary(string $table,$column){
    global $pdo;
    $a = null;
    if (gettype($column) == 'array'){
    	foreach($column as $key=>$value){
        	$a .= "$value,";
        }
    }
    $a = preg_replace("/,(?=( \w+)?$)/", null, trim($a));
    $q = "ALTER TABLE $table
    ADD PRIMARY KEY ($a);";
    $result = $pdo->prepare($q);
    $result->execute();
    return $result->rowCount();
}
