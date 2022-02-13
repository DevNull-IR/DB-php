<?php

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
