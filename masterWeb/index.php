<?php

$config = array(
    'website'=>'../masterWeb/',
    'maincont' => 'webController',
    'baseurl'=>'/RO/masterWeb/',
    'tz'=>'Asia/jakarta',
    'roadmin'=>false,
    'accessrule'=>false,
    'enabledb'=>false,
    'dbhost'=>'localhost', 
    'dbname'=>'',
    'dbuser'=>'root',
    'dbpass'=>'',
);
include_once "../masterClass/loader.php";
$RO = new RO();

?>