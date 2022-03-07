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
            if (gettype($value) == 'string' or gettype($value) == 'integer'){
            $a .= "$key = ? and ";
            $answer[] = $value;
            } elseif (gettype($value) == 'array'){
                     $a .= "$value[0] " . "$value[1] " . '? and ';
                     $answer[] = $value[2];
            }
        }
        $where_q = preg_replace("/ and(?=( \w+)?$)/", null, trim($a));
    } else {
        $where_q = "1";
    }
    $query = 'select ' . $select . ' from ' . $db . ' where ' . $where_q . $other;
    try {
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
    } catch(PDOException $error){
        file_put_contents("ErrorDB.log",$error->getMessage().PHP_EOL,8);
        return 0;
    }
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
    try {
        $result = $pdo->prepare($query);
        for($i = 1;$i<count($array) +1; $i++){
            $result->bindValue($i,$answer[$i -1]);
        }
        var_dump($result->execute());
        return $result->rowCount();
    } catch(PDOException $error){
        file_put_contents("ErrorDB.log",$error->getMessage().PHP_EOL,8);
        return 0;
    }
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
    try {
        $result = $pdo->prepare($query);
        if (gettype($where) == "array"){
            for($i = 1;$i<count($where) +1; $i++){
                $result->bindValue($i,$answer[$i -1]);
            }
        }
        $result->execute();
        return $result->rowCount();
    } catch(PDOException $error){
        file_put_contents("ErrorDB.log",$error->getMessage().PHP_EOL,8);
        return 0;
    }
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

    try {
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
        if ($result->execute()){
            return $result->rowCount();
        }
        else {
            return 0;
        }
    } catch(PDOException $error){
        file_put_contents("ErrorDB.log",$error->getMessage().PHP_EOL,8);
        return 0;
    }
    
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
    try {
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
    } catch(PDOException $error){
        file_put_contents("ErrorDB.log",$error->getMessage().PHP_EOL,8);
        return 0;
    }
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
        try {
            $result = $pdo->prepare($query);
            if($result->execute()){
                return 1;
            } else return 0;
        } catch(PDOException $error){
            file_put_contents("ErrorDB.log",$error->getMessage().PHP_EOL,8);
            return 0;
        }
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
    try {
        $result = $pdo->prepare($q);
        if ($result->execute()) {
            return 1;
        } else {
            return 0;
        }
    } catch(PDOException $error){
        file_put_contents("ErrorDB.log",$error->getMessage().PHP_EOL,8);
        return 0;
    }
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
    try {
        $result = $pdo->prepare($q);
        if ($result->execute()){
            return 1;
        } else {
            return 0;
        }
    } catch(PDOException $error){
        file_put_contents("ErrorDB.log",$error->getMessage().PHP_EOL,8);
        return 0;
    }
}

function drop($table,array $columns = []){
    global $pdo;
    $a = null;
    $q = null;
    if (gettype($table) == "array"){
        foreach($table as $key=>$value){
            $a .= " $value,";
        }
        $a = preg_replace("/,(?=( \w+)?$)/", null, trim($a));
        $q = "DROP TABLE $a;";
    } else {
        foreach($columns as $key=>$value){
            $a .= "DROP COLUMN $value,";
        }
        $a = preg_replace("/,(?=( \w+)?$)/", null, trim($a));
        $q = "ALTER TABLE $table
$a;";
    }
    try {
            $result = $pdo->prepare($q);
            if ($result->execute()) {
                return 1;
            } else {
                return 0;
            }
    } catch(PDOException $error){
        file_put_contents("ErrorDB.log",$error->getMessage().PHP_EOL,8);
        return 0;
    }
}
