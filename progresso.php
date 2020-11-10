<?php
    session_start();
    include('../conn/function.php');
    $himym = 4;
    if(isset($_SESSION['user_mtworld'])&&$_SESSION['user_mtworld']>0){
        $sql="select count(*) qtd from usuario u inner join user_sites us on u.id=us.usuario_id where u.id='{$_SESSION['user_mtworld']}' and us.sites_id='$himym' and status='ativo';";
        if(((enviarComand($sql,'bd_mtworld'))->fetch_assoc())['qtd']==0) header('Location: ../');        
    }
    else header('Location: ../');

    if(isset($_POST['powerRange'])){
        $sql = "call progress({$_POST['powerRange']},'{$_SESSION['user_mtworld']}');";
        enviarComand($sql,'bd_himym');
        header('Location: index.php');
    }
    else{
        if(isset($_GET['favorite'])){
            $sql = "call toggleFavorite('{$_GET['s']}','{$_GET['ep']}','{$_SESSION['user_mtworld']}');";
            if(enviarComand($sql,'bd_himym')) header('Location: play.php?s='.$_GET['s'].'&ep='.$_GET['ep'].'&time='.$_GET['time']);
            else header('Location: play.php?s='.$_GET['s'].'&ep='.$_GET['ep'].'&time='.$_GET['time'].'&erro');

        }else
        if(isset($_GET['s']) && isset($_GET['ep'])){   
            $sql = "call viewed('{$_GET['s']}','{$_GET['ep']}','{$_SESSION['user_mtworld']}');";
            $dataRetorno = enviarComand($sql,'bd_himym');
            $resRetorno = $dataRetorno->fetch_assoc();
            header('Location: play.php?s='.$resRetorno['season'].'&ep='.$resRetorno['ep']);
        }else
        if(isset($_GET['reiniciar'])){
            $sql = "update views set viewed=false where id_mtworld='{$_SESSION['user_mtworld']}';";
            enviarComand($sql,'bd_himym');
            header('Location: index.php');
        }
    }
?>
