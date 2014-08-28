<?php

/**
 * @author Jendro
 */

class access_controller extends model{

    public $table = "ro_controller";
    
    public function tambah($nama){
        $this->insertQuery("nama","'$nama'");
        $this->execute();
    }
    
    public function hapus($id){
        $this->deleteQuery("WHERE id ='$id'");
        $this->execute();
    }
    
    public function cekController($nama){
        $this->selectQuery("id","WHERE nama = '$nama'");
        return RO::$DB->fetch();
    }
    
}

?>