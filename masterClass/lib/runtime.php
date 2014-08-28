<?php

class runtime{
    private $startTime;
    private $totalTime;
    
    function __construct(){
        $mtime = microtime(); 
        $mtime = explode(" ",$mtime); 
        $mtime = $mtime[1] + $mtime[0]; 
        $this->startTime = $mtime; 
    }
    
    public function hasil(){
        $mtime = microtime(); 
        $mtime = explode(" ",$mtime); 
        $mtime = $mtime[1] + $mtime[0]; 
        $this->totalTime= ($mtime - $this->startTime); 
        echo "Halaman dibuat dalam waktu {$this->totalTime} detik";
    }
}

?>