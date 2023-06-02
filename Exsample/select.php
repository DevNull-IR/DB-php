<?php
include_once '../src/DB.php';

$him = select('*', 'db_product', ['id'=>'3'], 'limit 10');

foreach ($him['fetchAll'] as $key => $value) {
    echo $value['Referee'].'<br />';
}
