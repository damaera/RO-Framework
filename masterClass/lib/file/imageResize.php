<?php
//TODO : membuat class induk dari class ini dan class uploadFile, diutamakan penggabungan agar tidak terjadi perulangan fungsi
class file_imageResize{
    private $config;
    
    private $img;
    private $img_link;//alamat file gambar yang akan diresize
    private $img_name;//nama asli file yang akan diresize beserta folder tanpa extensi
    private $img_ext;// ekstensi file
    private $img_width;//lebar asli gambar
    private $img_heigth;//tinggi asli gambar
    
    private $tmb;
    private $tmb_width;
    private $tmb_height;
    private $tmb_name;//nama tambahannya
    
    private $error = false;
    
    private function imgPrepare(){
        if(isset($this->config['image']['link'])&&isset($this->config['image']['name'])&&isset($this->config['image']['ext'])){
            $this->img_link = $this->config['image']['link'];
            $this->img_name = $this->config['image']['name'];
            $this->img_ext = $this->config['image']['ext'];
        }else{
            $this->error = true;
        }
    }
    
    private function imgMaker(){
        if(!$this->error){
            if($this->img_ext == "jpg")$this->img = imagecreatefromjpeg($this->img_link);
            elseif($this->img_ext == "png")$this->img = imagecreatefrompng($this->img_link);
            else $this->error = true;
        }
    }
    
    private function imgSize(){
        if(!$this->error){
            $this->img_heigth= imageSY($this->img);
            $this->img_width=imageSX($this->img);
        }
    }
    
    private function tmbName(){
        if(isset($this->config['tmb']['name'])){
            $this->tmb_name = $this->img_name.$this->config['tmb']['name'].".".$this->img_ext;
        }else{
            $this->tmb_name = $this->img_name.".".$this->img_ext;
        }
    }
    
    private function tmbSize(){
        if(!$this->error){
            if(isset($this->config['tmb']['w'])){
                $this->tmb_width = $this->config['tmb']['w'];
                if(isset($this->config['tmb']['h'])){
                    $this->tmb_height = $this->config['tmb']['h'];    
                }else{
                    //if($this->tmb_width>$this->img_width)$this->tmb_width = $this->img_width;
                    $this->tmb_height = $this->tmb_width/$this->img_width*$this->img_heigth;
                }
            }
            elseif(isset($this->config['tmb']['h'])){
                //if($this->tmb_height>$this->img_heigth)$this->tmb_height = $this->img_heigth;
                $this->tmb_height = $this->config['tmb']['h'];
                $this->tmb_width = $this->tmb_height/$this->img_heigth*$this->img_width;
            }else{
                $this->error = true;
            }
        }
    }
    
    private function upload(){
        if($this->img_ext == "jpg")imagejpeg($this->tmb,$this->tmb_name);
        elseif($this->img_ext == "png")imagepng($this->tmb,$this->tmb_name);
        else $this->error = true;
    }
    
    private function createTmb(){
        $this->tmb = imagecreatetruecolor($this->tmb_width,$this->tmb_height);
        imagecopyresampled($this->tmb, $this->img, 0, 0, 0, 0, $this->tmb_width, $this->tmb_height, $this->img_width, $this->img_heigth);
        $this->upload();
        imagedestroy($this->img);
        imagedestroy($this->tmb);
    }
    
    /**
     * Config berisikan 
     *  - image = (array)return dari upload pada file upload class || jika tidak dari sini maka dibuat array yang berisikan :
     *      - link = (string) link gambar
     *      - name = (string) nama gambar
     *      - ext = (string) ekstensi gambar
     *  - tmb = (array) yang isi nya adalah 
     *      - w = (int) lebar
     *      - h = (int) tinggi
     *      - name = (string) tambahan nama yang akan diberikan pada tmb file
     *  
     *  extensi sementara baru bisa jpg dan png saja
    */
    public function resize($config = array()){
        $this->config = $config;
        //print_r($config);
        $this->imgPrepare();
        $this->imgMaker();
        $this->imgSize();
        $this->tmbSize();
        if(!$this->error){
            $this->tmbName();
            $this->createTmb();
            $return = true;
        }else{
            $return = false;
        }
        return $return;
    }
    
}
?>