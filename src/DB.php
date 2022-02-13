<?php
 $pdo = null;
function connect($dbname,$username_db,$password_db){
    global $pdo;
    $Option = [
    PDO::ATTR_PERSISTENT => TRUE,
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::MYSQL_ATTR_INIT_COMMAND =>'SET NAMES utf8',
    PDO::ATTR_EMULATE_PREPARES => false
];

try {
    
    $pdo = new PDO("mysql:host=localhost;dbname=".$dbname.";charset=utf8",$username_db, $password_db , $Option); // set and connect to db by pdo
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
    #'fetch' => $result->fetch(PDO::FETCH_ASSOC)
  ];
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
