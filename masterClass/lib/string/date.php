<?php 

class string_date{
    
    public function get_date($date){
        $return = "";
        if(!empty($date)){
            $tanggal = explode("-",$date);
            $nama_bulan = array("Januari","Februari","Maret","April","Mei","Juni","Juli","Agustus","September","Oktober","November","Desember");
            $tahun = $tanggal[0];
            $bulan = $nama_bulan[abs($tanggal[1]-1)];
            $hari = $tanggal[2];
            $return = "$hari $bulan $tahun ";
        }
        return $return;
    }
    
    public function date_3name($bulan){
        $bln = abs($bulan);
        $name = array("Jan","Feb","Mar","Apr","Mei","Jun","Jul","Agu","Sep","Okt","Nov","Des");
        return $name[$bln];
    }
    
    public function date_exploder($date){
        $date_e = explode("-",$date);
        $ret = array();
        $ret['y'] = $date_e[0];
        $ret['m'] = $date_e[1]-1;
        $ret['d'] = $date_e[2];
        return $ret;
        
    }
    
    public function get_time($time){
        $waktu = explode(":",$time);
        $jam = $waktu[0];
        $menit = $waktu[1];
        $return="$jam:$menit";
        return $return;
    }
    
    /** memodif timestap pada database */
    public function date_maker($date){        
        $date = explode(" ",$date);
        $tanggal = $this->get_date($date[0]);
        $return = "$tanggal";
        
        if(!empty($date[1])){
            $check_jam = true;
            $jam = $this->get_time($date[1]);
            $return.="pukul $jam";
        }
        
        return $return;
    }
    
}

?>