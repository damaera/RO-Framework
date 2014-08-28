<?php

/**`
 * @author Jendro
 */
 
class access_action extends model{

    public $table = "ro_action";
    
    public function tambah($nama){
        $this->insertQuery("nama","'$nama'");
        $this->execute();
    }
    
    public function hapus($id){
        $this->deleteQuery("WHERE id ='$id'");
        $this->execute();
    }
    
    public function cekAction($nama){
        $this->selectQuery("id","WHERE nama = '$nama'");
        $return = RO::$DB->fetch();
        return $return;
    }
}

?>