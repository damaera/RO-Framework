<?php

    /**
     * 'website'=>'official                                     //Folder tempat website berada
     * 'maincont' => 'roController',                            //controller default
     * 'baseurl'=>'/RO/official/',                              //baseurl
     * 'tz'=>'Asia/Jakarta',                                    //timezone
     * 'roadmin'=>true,                                         //boolean, ro-admin hidup atau tidak
     * 'lib' => array()                                         //lib default yang diload, key menjadi variabel pada controller, dan value adalah nama libnya
     * 'accessrule'=>true,                                      //boolean, memerlukan accessrule atau tidak
     * 'enabledb'=>true,                                        //boolean, enable database
     * 'dbstring'=>'mysql:dbname=official_ro;host=localhost',   //string database PDO
     * 'dbuser'=>'root',                                        //username database PDO
     * 'dbpass'=>'',                                            //password database PDO
    */

foreach ($config as $key=>$val) {
	define('__' . strtoupper($key) . '__', $val);
}

if(__ACCESSRULE__)session_start();

require_once "controller.php";
require_once "model.php";
require_once "RO.php";


?>