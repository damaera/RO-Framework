<?php 
class view_pagging{
       
    private $total;//total list
    private $jph;//jumlah perhalaman
    private $curent_hal;//sedang berada pada halaman
    private $jumlah_halaman;//total halaman
    private $link_page;
    private $parameter="hal";
    private $kata_selanjutnya="";
    private $kata_sebelumnya="";
    
    /** mendapatkan halaman dengan nilai kembalian berupa array halaman yang berisi link */
    private function getHalaman(){
        $i=1;
        $this->jumlah_halaman = floor($this->total/$this->jph);
    }
    
    private function sebelumnya(){
        if($this->curent_hal>1){?> 
            <a href="<?php echo $this->link_page."/".($this->curent_hal-1) ?>">
                <img src='image/asset/before_aktif.png' class='img_pagging' />
            </a> <?php }
        else{?>
                <img src='image/asset/before_passif.png' class='img_pagging' />
            <?php
            }
    }
    private function selanjutnya(){
        if($this->curent_hal<$this->jumlah_halaman){?> 
            <a href="<?php echo $this->link_page."/".($this->curent_hal+1) ?>">
                <img src='image/asset/next_aktif.png' class='img_pagging' />
            </a> <?php }
        else{?>
                <img src='image/asset/next_passif.png' class='img_pagging' />
            <?php
            }
    }
    private function listHalaman(){
        $length = $this->jumlah_halaman;
        for($i=1;$i<=$length;$i++){?>
            <a <?php if($i!=$this->curent_hal){?>href="<?php echo $this->link_page."/".$i ?>"<?php }?> class="pagelink <?php if($i==$this->curent_hal){?>cp<?php } ?>">
                <?php echo $i ?>
            </a><?php
        }
    }
    
    
    /** mengatur berapa jumlah list tiap halaman dan berapa total halaman */
    function __construct($jumlah_per_halaman,$total){
        $this->jph=$jumlah_per_halaman;
        $this->total=$total;
    }
    /** mengatur sekarang berada pada halaman berapa */
    public function setCurentHal($hal=1){
        if(empty($hal))$this->curent_hal = 1;
        else $this->curent_hal=$hal;
    }
    /** diletakkan pada di limit pada query */
    public function curentLimit(){
        return (($this->curent_hal-1)*$this->jph);
    }
    /** diisi link halaman yang bersangkutan */
    public function setLink($link){
        $this->link_page=$link;
    }
    /** mengatur parameter pada link */
    public function setParameter($parameter){
        $this->parameter=$parameter;
    }
    public function setKataNavigasi($sebelumnya,$selanjutnya){
        $this->kata_sebelumnya=$sebelumnya;
        $this->kata_selanjutnya=$selanjutnya;
    }
    /** siap pakai */
    public function showHalaman(){
        echo "<section class=\"pagging\">";
        $this->getHalaman();
        $this->sebelumnya();
        $this->listHalaman();
        $this->selanjutnya();
        echo "</section>";
    }
    
}

?>