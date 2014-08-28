<!DOCTYPE HTML>
<head>
    <link href="css/ro/basic.css" rel="stylesheet" type="text/css" />
    <link href="css/ro/super_admin.css" rel="stylesheet" type="text/css" />
    <script type="text/javascript" src="js/armor.v.2.1.js"></script>
	<meta http-equiv="content-type" content="text/html" />
	<title>RO-ADMIN</title>
</head>

<body>
<style>
#left{
    float: left;
    width: 20%;
    height: 700px;
    position: fixed;
}
#right{
    float: right;
    width: 79.5%;
    min-height: 700px;
}
#main_menu{
    list-style: none;
}
#main_menu li{
    padding: 7px 10px;
    margin: 1px 0;
    color: whitesmoke;
    font-size: 14px;
    font-weight: bold;
    -webkit-transition: all 0.3s;
    -moz-transition: all 0.3s;
    -o-transition: all 0.3s;
}
#main_menu li:hover,#main_menu li.c{
    background: #06212F;
    color: ghostwhite;
}
#main_menu li img{
    margin-bottom: -5px;
}
#main_menu li a:hover{
    text-decoration: underline;
}
header{
    background: #1C679F;
    width: 100%;
}
header a{
    
}
</style>
<section id="left" class="bg-blue1">
    <h1 class="f29 pad710 bg-rad-black1 clr-white1">RO ADMIN</h1>
    
    <ul id="main_menu">
        <li>
            <a href="admin">Access</a>
        </li>
        <li>
            <a href="log">Log</a>
        </li>
    </ul>
</section>

<section id="right" class="bg-rad-white1">
    <header class="bg-rad-black1">
        <a href="logout" class="btn-3 d-bold pad1520 fr">Logout</a>
        <div class="clear"></div>
    </header>
    <div class="pad30">
        <?php echo $content ?>
    </div>
</section>

</body>

</html>