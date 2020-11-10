<?php
    session_start();
    include('../conn/function.php');
    if(!(isset($_SESSION['user_mtworld'])&&$_SESSION['user_mtworld']>0)){
        if(isset($_COOKIE['mtworldPass'])&&isset($_COOKIE['mtworldKey'])){
            $sql="select * from usuario where email='{$_COOKIE['mtworldPass']}' and senha='{$_COOKIE['mtworldKey']}';";
            if($linha = (enviarComand($sql,'bd_mtworld'))->fetch_assoc()){
                $_SESSION['user_mtworld'] = $linha['id'];
                $_SESSION['user_mtworld_nome'] = $linha['nome'];
                $_SESSION['user_mtworld_email'] = $linha['email'];
            } 
        }
    }
?>
<!DOCTYPE HTML>
<html lang="pt-br">
<head>
    <title>HIMYM</title>
    <meta charset="UTF-8"/>
    <link href="https://fonts.googleapis.com/css?family=Playfair+Display" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" type="text/css" href="css/estilo.css"/>
    <link rel="stylesheet" type="text/css" href="css/bootstrap.css"/>
    <link rel="apple-touch-icon" sizes="76x76" href="img/favicon-logo.ico"><!--ICONE-->
    <link rel="icon" type="image/png" href="img/favicon-logo.ico"><!--ICONE-->
    <style>
        body { 
            background-color: #eee;
            font-family: 'Playfair Display', serif;
            margin: 0;
            padding: 0;
            overflow: hidden;
        }
        main{
            margin: 0;
            height: 100vh;
            width: 100vw;
            background-image: url(img/capa.jpg);
            background-position: center;
            background-color: transparent;
            overflow: auto;
        }
        .bg-transparent{ background: transparent; }
    </style>
    <script src="jquery/jquery-3.4.1.min.js"></script>
</head>
<body>
    <main class="d-flex justify-content-center align-items-center">
        <div class="container ">
            <div class="card m-2 p-2">
                <li class="list-group-item active text-center bg-danger border-0">Manutenções no Site</li>
            </div>
        </div>
    </main>
    <?php
        include('../function/global.php');
    ?>
</body>
</html>
<script type="text/javascript" src="js/bootstrap.bundle.js"></script>