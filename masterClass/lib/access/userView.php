<?php

class access_userView{    
    /** berisikan tipe user yang diijinkan mengakses bagian tersebut akan mengecek dan mengambalikan kembalian */
    public function allowAccess($allow_type_user = array()){
        $user = (!empty($_SESSION['tipe_user']))?$_SESSION['tipe_user']:"";
        $return = in_array($user,$allow_type_user); 
        return $return;
    }
    
    /** admin atau tipe yang ditentukan yang memiliki id sama*/
    public function standart1($id,$allow_type_user = array()){
        $return = false;
        if(!empty($id)){
            $return1 = $this->admin();
            $return2 = $this->allowAccess($allow_type_user);
            $return3 = $this->sameId($id);
            $return = ($return1||($return2&&$return3))?true:false;
        }
        return $return;
    }
    
    /** admin atau tipe yang ditentukan */
    public function standart2($allow_type_user = array()){
        $return1 = $this->allowAccess($allow_type_user);
        $return2 = $this->admin();
        $return = ($return1||$return2)?true:false;
        return $return;
    }
    
    /** admin dan id yang sama */
    public function standart3($id_user = ""){
        $return1 = (!empty($id_user))?$this->sameId($id_user):false;
        $return2 = $this->admin();
        $return = ($return1||$return2)?true:false;
        return $return;
    }
    
    /** mengecek untuk add, id adalah id tabel yang terkait */
    public function add($id){
        $return = (empty($id))?true:false;
        return $return;
    }
    
    public function update($id){
        $return = (empty($id))?false:true;
        return $return;
    }
    
    public function admin(){
        $user = (!empty($_SESSION['tipe_user']))?$_SESSION['tipe_user']:"";
        $return = ($user=="admin")?true:false; 
        return $return;
    }
    
    /** mengembalikan true jika idnya sama, id_name adalah nama index pada session yang menyimpan id_user yang login */
    public function sameId($id,$id_name = "id_user"){
        $user = (!empty($_SESSION["$id_name"]))?$_SESSION["$id_name"]:"";
        $return = ($user==$id)?true:false; 
        return $return;
    }
    
    
}

?>