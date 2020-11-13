<?php
//Fazer um card para os favoritos
    session_start();
    include('../conn/function.php');
    $himym = 4;
    if(isset($_SESSION['user_mtworld'])&&$_SESSION['user_mtworld']>0){
        $sql="select count(*) qtd from usuario u inner join user_sites us on u.id=us.usuario_id where u.id='{$_SESSION['user_mtworld']}' and us.sites_id='$himym' and status='ativo';";
        if(((enviarComand($sql,'bd_mtworld'))->fetch_assoc())['qtd']==0){
            header('Location: ../');
        }
    }
    else{
        if(isset($_COOKIE['mtworldPass'])&&isset($_COOKIE['mtworldKey'])){
            $sql="select u.id,u.nome,u.email from usuario u inner join user_sites us on u.id=us.usuario_id where u.email='{$_COOKIE['mtworldPass']}' and u.senha='{$_COOKIE['mtworldKey']}' and us.sites_id='$himym' and status='ativo';";
            if($linha = (enviarComand($sql,'bd_mtworld'))->fetch_assoc()){
                $_SESSION['user_mtworld'] = $linha['id'];
                $_SESSION['user_mtworld_nome'] = $linha['nome'];
                $_SESSION['user_mtworld_email'] = $linha['email'];
            } 
        }else header('Location: ../');
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
    <link rel="stylesheet" type="text/css" href="../css/scroll.css"/>
    <link rel="apple-touch-icon" sizes="76x76" href="img/favicon-logo.ico"><!--ICONE-->
    <link rel="icon" type="image/png" href="img/favicon-logo.ico"><!--ICONE-->
    <style>
        body { background-color: #eee; font-family: 'Playfair Display', serif; }
        main{
            position: fixed;
            background-image: url(img/capa.jpg);
            background-position: center;
            background-color: transparent;
        }
        .cursor { cursor: pointer; }
        [onclick] { cursor: pointer; }
        .bg-transparent{ background: transparent; }
    </style>
    <script src="jquery/jquery-3.4.1.min.js"></script>
</head>
<body>
    <main class="h-100 w-100 d-flex align-items-center justify-content-center">
        <div class="rounded-circle bg-dark text-light border dropleft cursor" 
             style="position: absolute;top:15px;right:20px; width: 35px; height: 35px; display: flex; align-items: center;justify-content: center;">
            <div role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <b class=""><?php echo substr($_SESSION['user_mtworld_nome'],0,1);?></b>
                <div class="dropdown-menu text-center p-3" aria-labelledby="dropdownMenuLink">
                    <div>
                        <?php echo $_SESSION['user_mtworld_nome']; ?>
                        <hr class="m-0 p-0"/>
                        <small class="text-muted"><?php echo $_SESSION['user_mtworld_email']; ?></small>
                    </div>
                    <button type="button" class="btn btn-danger btn-sm mt-2" onclick="location.href='../';">Sair</button>
                </div>
            </div>
        </div>
        <div class="card text-center px-4 pt-2 pb-0" style="background: rgba(250,250,250,.9)">
            <h1>How I Met Your Moth</h1>
            <button class="btn btn-light btn-block shadow" onclick="location.href='play.php';">
                <i class="material-icons align-middle">event_seat</i> Continuar
            </button>
            <button class="btn btn-light btn-block shadow" onclick="location.href='play.php?random=1';">
                <i class="material-icons align-middle">whatshot</i> Aleatório
            </button>
            <button class="btn btn-light btn-block shadow" data-toggle="modal" data-target="#modalProgresso" id="btnChamaModalProgresso">
                <i class="material-icons align-middle">linear_scale</i> Progresso
            </button>
            <button class="btn btn-light btn-block shadow" onclick="alert('Em Desenvolvimento');"><i class="material-icons align-middle">stars</i> Favoritos</button>
            <?php if(isset($_SESSION['user_mtworld'])&&$_SESSION['user_mtworld']>0){ ?>
                <button class="btn btn-light btn-block shadow" id="aMatthNavigate" onclick="$('#matthNavigate').modal('show');">
                <span class="material-icons align-middle px-1">ac_unit</span>
            </button>
            <?php } ?>
            <div class="mt-2 mb-0 pb-0">
                <p class="mb-0 small">Percentual de Episódios Assistidos</p>
                <?php
                    $sql = "select ceil((count(*)*100)/208) assistiu from views where viewed=1 and id_mtworld='{$_SESSION['user_mtworld']}';";
                    $dataProgress = enviarComand($sql,'bd_himym');
                    $resProgress = $dataProgress->fetch_array();
                ?>
                <div class="progress">
                    <div class="progress-bar progress-bar-striped bg-dark" role="progressbar" style="width: <?php echo $resProgress['assistiu'];?>%" aria-valuenow="<?php echo $resProgress['assistiu'];?>" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
                <p id="percentual"><?php echo $resProgress['assistiu'];?>%</p>
            </div>
        </div>
    </main>
    <!--Modal Resultado: Zerar-->
    <div class="modal fade" tabindex="-1" role="dialog" id="modalResultado" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content"  id="corpoModalResultado">
                <div class="modal-header">
                    <h5 class="modal-title" id="tituloModalResultado" style="font-weight:normal;">Zerar Progresso!</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <p id="subTituloModalResultado" style="margin-top: 10px;text-align:left;">Isso irá reiniciar todo o seu progresso de Epsódios assistidos. Deseja continuar?</p>
                </div>
                <div class="modal-footer btn-group">
                    <a href="progresso.php?reiniciar=0" title="Zerar" class="btn btn-danger">Sim</a>
                    <a href="#" title="Cancelar" class="btn btn-secondary" data-dismiss="modal">Não</a>
                </div>
            </div>
        </div>
    </div>
    <!--Modal Progresso-->
    <div class="modal fade" tabindex="-1" role="dialog" id="modalProgresso" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="tituloModalResultado" style="font-weight:normal;">Alterar Progresso!</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body pt-2">
                    <h5 class="text-center mb-2 border rounded p-2">Último Episódio Assistido</h5>
                    <form method="POST" action="progresso.php">
                        <script>
                            var arrRange = new Array('Nenhum Episódio Assistido...');
                            for(s=1;s<=2;s++){ for(ep=1;ep<=22;ep++){ arrRange.push('Season '+s+' Ep. '+ep); } }
                            for(ep=1;ep<=20;ep++){ arrRange.push('Season 3 Ep. '+ep); }
                            for(s=4;s<=9;s++){ for(ep=1;ep<=24;ep++){ arrRange.push('Season '+s+' Ep. '+ep); } }
                            function rangeLabel(){ $("label[for='powerRange']").html(arrRange[$('#powerRange').val()]); }
                            $(function(){
                                rangeLabel();
                            });
                        </script>
                        <div class="form-group">
                            <label for="powerRange">Nenhum Episódio Assistido...</label>
                            <?php
                                $sql = "select count(*) assistiu from views where viewed=1 and id_mtworld='{$_SESSION['user_mtworld']}';";
                                $dataProgress = enviarComand($sql,'bd_himym');
                                $altProgress = $dataProgress->fetch_array();
                            ?>
                            <input type="range" class="custom-range" min="0" max="208" value="<?php echo $altProgress['assistiu']; ?>" id="powerRange" name="powerRange" oninput="rangeLabel();">
                        </div>
                        <button type="submit" class="btn btn-primary btn-block">Alterar</button>
                        <button type="button" class="btn btn-dark btn-block" data-dismiss="modal" data-toggle="modal" data-target="#modalResultado" id="btnChamaModalResult"><i class="material-icons align-middle">360</i> Zerar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <?php include('../function/global.php'); ?>
</body>
</html>
<script type="text/javascript" src="js/bootstrap.bundle.js"></script>
