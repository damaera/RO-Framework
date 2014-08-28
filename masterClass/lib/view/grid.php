<?php

class view_grid{
    
    /** 
    $view = dalam bentuk array dan bentuk string
        array = header : judul pada kolom
                value : isi pada kolom, jika mengambil data dari database bisa menggunakan $data->kolom
    $config 
        array = primary : mengatur prymary key untuk update dan delete pada table
                update_link : mengatur link untuk mengupdate
                delete_link : mengatur link untuk mendelete
    */
    public function gridView($data,$view,$config = array()){
        $update = "update";
        $delete = "delete";
        $primary = "id";
        $del = true;
        $upd = true;
        
        if(isset($config['primary']))$primary = $config['primary'];
        if(isset($config['update_link']))$update = $config['update_link'];
        if(isset($config['delete_link']))$delete = $config['delete_link'];
        if(isset($config['update']))$upd = $config['update'];
        if(isset($config['delete']))$del = $config['update'];
        
        echo "<table class='main_grid'>";
        echo "<tr class='h'>";
            foreach($view as $key=>$val){
                if(is_array($val)){$val = (isset($val['header']))?$val['header']:"Belum Diberi Judul";}
                echo "<th>$val</th>";
            }
            echo "<th>Aksi</th>";
        echo "</tr>";
        $color = 'd';
        $no =1;        
        foreach($data as $data){
            echo "<tr class='$color'>";
            
            foreach($view as $val){
                if(is_array($val)){
                    $value = (isset($val['value']))?$val['value']:" - ";
                    if($value=="no")$value = $no;
                    else eval("\$value = $val[value];");
                }else{
                    $value = (isset($data[$val]))?$data[$val]:"-";
                }
                echo "<td>".$value."</td>";
            }
            echo "<td class='center'>";
            if($upd) echo "<a title='update' href='$update/".$data[$primary]."'><img class='grid_image' src='image/asset/edit.png'/></a>";
            if($del) echo "<a title='delete' onclick='return validasiHapus()' href='$delete/".$data[$primary]."'><img class='grid_image' src='image/asset/delete.png'/></a></td>";
            echo "</tr>";
            $no++;            
            $color = ($color=='d')?"w":"d";
        }

        echo "</table>";
    }
    
}
?>