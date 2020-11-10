<?php
// ALTERAR ASIDE E FAZER UMA FUNÇÃO QUE MARQUE OS ASSISTIDOS

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
        body{ font-family: 'Playfair Display', serif; margin: 0px; color: white; background: rgba(5,5,5,1);}
        main{
            height: 100%;
            width: 100%;
            position: absolute;
            margin: 0px;
        }
        header.principal{
            background: rgb(15,15,15);
            padding: 14px;
            position: absolute;
            top: 0;
            left: 0;
            right: 0
        }
        header.principal i{ display: inline; float: right; cursor: pointer; }
        div.my-container{
            position: static;
            padding: 10px;
            margin-top:52px;
            margin-bottom: 0px;
            background: rgba(5,5,5,1);
        }
        div.my-container section{
            float: left;
        }
        section#sectionVideo video{
            height: 100%;
            width: 100%;
            border: none;
        }
        div.my-container aside{
            background: rgb(15,15,15);
            float: left;
            width: 0px;
            margin-top: -20px;
            margin-right: -20px;
            overflow-y: auto;
            position: relative;
        }
        #iRecolhe{
            text-align: center;
            width: 100%;
            margin-top:-6px;
            cursor: pointer;
        }
        ul#ulTemp{
            margin-top: -7px;
            list-style: none;
            padding-left: 0px;
        }
        ul#ulTemp li.liPrincipal{
            list-style: none;
            margin: 10px;
            padding: 14px;
            background: rgba(100,100,100,.1);
            border: 1px solid rgba(100,100,100,.2);
            text-align: center;
            transition: .6s background;
            cursor: pointer;
        }
        ul#ulTemp li:hover{
            background: rgba(100,100,100,.6);
        }
        ul.subMenu{
            display: none;
            list-style: none;
            margin: -10px 10px 10px 10px;
            padding: 10px 0px 10px 0px;
            background: rgba(10,10,10,.3);
        }
        ul.subMenu li{
            margin-left: 10px;margin-right: 10px;
            font-weight: 100,
        }
        ul.subMenu a{
            text-decoration: none;
            color: inherit;
        }
        #spanEp{
            display: inline-block;
            text-align: center;
            margin-left: 20px;
        }
        #spanEp a{
            margin-left: 5pt;
            color: #ccc;
        }
        a#aTitulo{
            color: inherit;
            text-decoration: none;
        }
        a#aTitulo:hover{
            font-weight: 600;
            text-shadow: 6px 6px 6px black;
        }
        #iAleatorio{
            float: left;
            padding-left: 4px;
            padding-right: 4px;
            text-align: center;
        }
        #iAleatorio.active{
            background: rgba(200,200,200,0.4);            
        }
        .cursor{ cursor: pointer; }
    </style>
    <script src="jquery/jquery-3.4.1.min.js"></script>
    <script>
        var jqExp = true;
        function redimensionar(){
            var altWindowH = ($(window).height()-72);
            var altWindowW = ($(window).width()-20);
            $('.my-container').css('height',altWindowH);
            $('#sectionVideo').css('height',altWindowH);
            $('#sectionVideo').css('width',altWindowW);
            $('#asideOutros').css('height',altWindowH+30);
            $('#asideOutros').css('width',0);
            $('#asideOutros').css('display','none');
        }
        function abreExp(){
            var altWindowH = ($(window).height()-72);
            var altWindowW = ($(window).width()-20);
            if(jqExp){
                $('#btnExpande').html('close');
                $('#sectionVideo').css('height',altWindowH);
                $('#asideOutros').css('height',altWindowH+30);
                $('#sectionVideo').css('width',altWindowW-280);
                $('#asideOutros').css('width',290);
                $('#asideOutros').css('display','block');
                jqExp = false;
            }else{
                $('#btnExpande').html('dehaze');
                $('#sectionVideo').css('height',altWindowH);
                $('#sectionVideo').css('width',altWindowW);
                $('#asideOutros').css('height',altWindowH+30);
                $('#asideOutros').css('width',0);
                $('#asideOutros').css('display','none');
                
                jqExp = true;
            }
        }
        function expandeSub(p){
            for(c=1;c<10;c++){
                dblock('sub'+c,false);
            }
            dblock('sub'+p,true);
        }
        function dblock(p,b){
            if(b) document.getElementById(p).style="display:block;";
            else  document.getElementById(p).style="display:none;";
        }
        function finalizaVid(s,e){
            if($('#iAleatorio').hasClass('active')){ window.location.href="play.php?random=1"; }
            else{ window.location.href="progresso.php?s="+s+"&ep="+e; }
        }
        function alterActive(){
            if($('#iAleatorio').hasClass('active')){
                $('#iAleatorio').removeClass('active');    
            }else{
                $('#iAleatorio').addClass('active');    
            }            
        }
        function favoritar(s,ep){
            location.href="progresso.php?favorite&s="+s+"&ep="+ep+"&time="+$('video')[0].currentTime;
        }
        $(function(e){
            <?php 
                if(isset($_GET['time'])){
                    echo "$('video')[0].currentTime = {$_GET['time']}; ";         
                }
            ?>
            $('video').trigger('play');

            redimensionar();
            $(window).resize(function(e){ redimensionar(); });
            $('#btnExpande').on('click',abreExp);
            <?php if(isset($_GET['random'])){ ?> $('#iAleatorio').addClass('active'); <?php } ?>
            $(document).prop('title','HIMYM'+ $('#spanEp').attr('alt'));
        });
    </script>
</head>
<body>
    <main>
    <?php
        $sql = "select * from eps;";
        $data = enviarComand($sql,'bd_himym');
        $arrEps = array();
        while($res = $data->fetch_assoc()){ $arrEps[] = $res; }
        
        $sql = "select * from views v inner join eps e on v.id_ep=e.id where v.id_mtworld='{$_SESSION['user_mtworld']}' and (v.viewed=1 or v.favorite=1) order by v.id_ep desc;";
        $data = enviarComand($sql,'bd_himym');
        $arrViewed = array();
        while($res = $data->fetch_assoc()){ $arrViewed[] = $res; }
        $arrViewedReverse = array_reverse($arrViewed);

        foreach($arrEps as &$value){
            $value['assistido']=false;
            $value['favorito']=false;
            for($c=0;$c<count($arrViewedReverse);$c++){
                if($value['id']==$arrViewedReverse[$c]['id_ep']){
                    if($arrViewedReverse[$c]['viewed'])
                        $value['assistido']=true;
                    if($arrViewedReverse[$c]['favorite'])
                        $value['favorito']=true;
                    unset($arrViewedReverse[$c]);
                    sort($arrViewedReverse);
                    $c=count($arrViewedReverse);
                }
            }
        }

        if(isset($_GET['random'])){ $rand = rand(1,208); $sql = "select * from eps where id='$rand'"; }
        else{
            if(isset($_GET['s'])){ $sql = "select * from eps where season='{$_GET['s']}' and ep='{$_GET['ep']}';"; }
            else {
                $sql = "select count(*) qtd from views where id_mtworld='{$_SESSION['user_mtworld']}' and viewed=1;";
                $qtd = ((enviarComand($sql,'bd_himym'))->fetch_assoc())['qtd']; 
                if($qtd>0){
                    if($arrViewed[0]['id_ep']==$qtd){
                        $i = $arrViewed[0]['id_ep'];
                        $rEpAtual['season']= $arrEps[$i]['season'];
                        $rEpAtual['ep']= $arrEps[$i]['ep'];
                        $rEpAtual['nome']= $arrEps[$i]['nome'];
                        $rEpAtual['favorito']= $arrEps[$i]['favorito'];
                    }else{
                        $i=0;
                        while($arrEps[$i]['assistido']){
                            $i++;
                            if($i==208){ $i=0; break; }
                        }
                        $rEpAtual['season']= $arrEps[$i]['season'];
                        $rEpAtual['ep']= $arrEps[$i]['ep'];
                        $rEpAtual['nome']= $arrEps[$i]['nome'];
                        $rEpAtual['favorito']= $arrEps[$i]['favorito'];
                    }
                }
                else{
                    $rEpAtual['season']= $arrEps[0]['season'];
                    $rEpAtual['ep']= $arrEps[0]['ep'];
                    $rEpAtual['nome']= $arrEps[0]['nome'];
                    $rEpAtual['favorito']= $arrEps[0]['favorito'];
                }

            }
        }
        if(!isset($rEpAtual)){
            $dataEpAtual = enviarComand($sql,'bd_himym');
            $rEpAtual = $dataEpAtual->fetch_assoc();
            if(!$rEpAtual){
                $rEpAtual['season']= $arrEps[0]['season'];
                $rEpAtual['ep']= $arrEps[0]['ep'];
                $rEpAtual['nome']= $arrEps[0]['nome'];
                $rEpAtual['favorito']= $arrEps[0]['favorito'];
            }else{
                $rEpAtual['favorito']= $arrEps[$rEpAtual['id']-1]['favorito'];
            }
        }
    ?>
        <header class="principal">
            <i class="mr-1 material-icons rounded border border-secondary shadow" id="iAleatorio" onclick="alterActive();"> call_split </i>
            <span class="mr-1 material-icons align-top border border-secondary rounded px-1 text-<?php echo $rEpAtual['favorito']?'danger':'dark'; ?> shadow cursor" 
                onclick="favoritar(<?php echo $rEpAtual['season'].','.$rEpAtual['ep']; ?>)">favorite</span>
            <a href="index.php" id="aTitulo">How I Met Your Mother {</a>

            <span id="spanEp" alt="<?php echo '(S'.$rEpAtual['season'].'/E'.$rEpAtual['ep'].') '.$rEpAtual['nome']; ?>">  
                <?php echo 'Season '.$rEpAtual['season'].' - Ep '.$rEpAtual['ep'].'. &nbsp; '.$rEpAtual['nome']; ?>
                
                <a href="#" onclick="finalizaVid(<?php echo $rEpAtual['season']; ?>,<?php echo $rEpAtual['ep']; ?>)"><i class="material-icons">arrow_forward_ios</i></a>                
            
                <?php if(isset($_SESSION['user_mtworld'])&&$_SESSION['user_mtworld']>0){ ?>
                <a  class="text-light align-middle " style="opacity: .9" 
                    id="aMatthNavigate" onclick="$('#matthNavigate').modal('show');" href="#">
                    <span class="material-icons px-1">ac_unit</span>
                </a>
                <?php } ?>            
            </span>
            <i id="btnExpande" class="material-icons">dehaze</i>
        </header>
        <div class="my-container">
            <section id="sectionVideo">
                <video id="vidTela" controls onended="finalizaVid(<?php echo $rEpAtual['season']; ?>,<?php echo $rEpAtual['ep']; ?>)">
                    <source src="<?php echo inweb==0?'../../himym/':'';?>video/<?php echo 's'.$rEpAtual['season'].'/ep'.$rEpAtual['ep'];?>.mp4" type="video/mp4">
                    Ainda não cadastrado
                </video>
            </section>
            <aside id="asideOutros">
                <i id="iRecolhe" class="material-icons" title="Recolher Temporadas" onclick="expandeSub(0)">expand_less</i>
                <ul id="ulTemp">
                    <?php 
                        $countEp = 0;
                        for($li=1;$li<10;$li++){
                    ?>
                    <li class="liPrincipal" onclick="expandeSub(<?php echo $li; ?>)"><?php echo $li; ?>ª Temporada</li>
                        <ul class="subMenu" id="sub<?php echo $li; ?>">
                            <?php while(count($arrEps)>$countEp && $arrEps[$countEp]['season']==$li){ ?>
                                <a href="play.php?s=<?php echo $li; ?>&ep=<?php echo $arrEps[$countEp]['ep']; ?>">
                                    <li>
                                        <i class="material-icons <?php echo $arrEps[$countEp]['favorito']?'text-danger':''; ?>" style="vertical-align: bottom;"><?php echo $arrEps[$countEp]['assistido']?"check_box":"check_box_outline_blank";?> </i>
                                        <?php echo $arrEps[$countEp]['ep'].". ";echo strlen($arrEps[$countEp]['nome'])<21?$arrEps[$countEp]['nome']:substr($arrEps[$countEp]['nome'],0,20)."..."; ?>
                                    </li>
                                </a>
                            <?php $countEp++; }?>
                        </ul>
                    <?php } ?>
                </ul>
            </aside>
        </div>
    </main>
    <?php
        include('../function/ctrlm.php');
        include('../function/mnav.php');
        include('../function/arty.php');
        include('../function/wmatth.php');
    ?>
</body>
</html>
<script type="text/javascript" src="js/bootstrap.bundle.js"></script>