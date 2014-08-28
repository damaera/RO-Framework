<h1 class="f29 d-uitalic mb10">Acces User</h1>
<a class="btn-1 pad5" onclick="showAddUser()">add</a>
<a href="RefreshAccess" class="btn-2 pad5">refresh</a>

<form id="addTypeUser" method="post" action="addTypeUser">
    <h3 class="f13 mt10">Tambah Tipe User</h3>
    <input name="nama" />
    <input type="submit" name="submit" value="Tambah" class="btn-1 pad220" />
</form>

<?php 

$a = RO::lib("access_tipeUser");
$data = $a->listTipeUser();
$view = RO::lib("view_grid");
$view->gridView($data,
    array(
        array(
            "header"=>"No",
            "value"=>'no',
        ),
        array(
            "header"=>"Nama",
            "value"=>'\'<a onclick="listAccessUser(\'.$data["id"].\')">\'.$data["nama"].\'</a>\'',
        ),
    ),
    array(
    'update'=>false,
    'delete_link'=>'deleteTypeUser',
    )
);

?>
<div>
<br /> 
<table>
    <tr>
        <td>Tanggal</td>
        <td>:<input style="width: 90%;" type="date" /></td>
    </tr>
    <tr>
        <td>Keterangan :</td>
        <td>:<textarea style="width: 90%;"></textarea></td>
    </tr>
</table>
</div>
<div id="listAkses">

</div>

<script>
function listAccessUser(id){
    ajax({
        method:"GET",
        url:"listAccess/"+id,
        id:"#listAkses",
        afterSuccess:function(){
          gridHeightPositionNormalizer(listAkses,"ul",1);  
        },
    });
}
function ubahAkses(target,id){
    ajax({
        method:"GET",
        url:"changeAccess/"+id,
        id:"#"+target,
    });
}
function ubahRedirect(id){
    ajax({
        method:"GET",
        url:"changeRedirect/"+id,
        id:"#LR"+id,
    });
}
function hapusAkses(id){
    ajax({
        method:"POST",
        url:"deleteAccess",
        sendDirect:"id::"+id,
        id:"#delAc"+id,
    });
}
function showAddUser(){
   addTypeUser.style.display = (addTypeUser.style.display=="")?"none":"";
}
function showUpdateUser(){
   updateTypeUser.style.display = (updateTypeUser.style.display=="")?"none":"";
}
addTypeUser.style.display ="none"
</script>

<style>
#listAkses{
    width: 80%;
    padding: 10px;
}
#listAkses h3{
    margin-top: 25px;
    color: #444;
}
#listAkses ul{
    float: left;
    width: 30%;
    min-width: 266px;
    list-style: none;
    margin-left: 9%;
}
#listAkses li{
    width: 100%;
    font-size: 13px;
    background: #eaeaea;
    padding: 5px 10px;
    margin-bottom: 1px;
}
#listAkses li.bg-red1{
    background: #E05836;
}
#listAkses ul a{
    margin-right: 10px;
    float: right;
}
#listAkses ul a:hover{
    text-decoration: underline;
}
</style>