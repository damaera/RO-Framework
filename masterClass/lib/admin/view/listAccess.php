<h1 class='mt10 f29 d-uline d-italic'><?php echo $user; ?> 
    <a class="ml5" title="update nama tipe user" onclick="showUpdateUser()">
        edit
    </a>
</h1>
<form style="display: none;" id="updateTypeUser" method="post" action="updateTypeUser">
    <h3 class="f13">Update Tipe User</h3>
    <input type="hidden" name="id" value="<?php echo $id_user; ?>" />
    <input name="nama" value="<?php echo $user; ?>" />
    <input type="submit" name="submit" value="Update" class="btn-1 pad220" />
</form>
<?php 
$controller = "";

foreach($data as $data){
    if($controller!=$data['controller']){
        echo "</ul>";
        echo "<ul id='$data[controller]"."_list'>";
        echo "<h3>$data[controller]</h3>";
        $controller = $data['controller'];
    }
    if(isset($validasi[$data['controller']])){
        $valid = (in_array($data['action'],$validasi[$data['controller']]))?true:false;
    }else{
        $valid = false;
    }
    if($valid){
        echo 
        "<li>".$data['action']."
            <a id='LA$data[id]' onclick='ubahAkses(\"LA$data[id]\",$data[id])'>".$data['akses']."</a>
            <a id='LR$data[id]' onclick='ubahRedirect($data[id])'>".$data['redirect']."</a>
        </li>";
    }else{
        echo
        "<li id='delAc$data[id]' class=bg-red1>".$data['action']."
            <a onclick='hapusAkses($data[id])'>Hapus</a>
        </li>";
    }
}
?>
</ul>

<div class="clear"></div>