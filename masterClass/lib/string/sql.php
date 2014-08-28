<?php 

class string_sql{
    
    public function likeQueryMaker($name,$value){
        return "($name LIKE '%$value' OR $name LIKE '%$value%' OR $name LIKE '$value%')";
    }
    
}

?>