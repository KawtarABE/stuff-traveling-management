<?php 

    // to ban access from url
    session_start();
    if(!isset($_SESSION['login'])) {
        header('location:index.php');
        exit;
    }

    // connexion to database
    require 'connexion.php' ;

    // select all codes
    $query2 = $db->prepare("select code from puissance_fiscale");
    $query2->execute();
    $codes= $query2->fetchAll();


    //verify if id is alrady existing
    if (!empty($_REQUEST['msg'])) {
        $msg = $_REQUEST['msg'];
        $alert = "" ;
        if ($msg != "") {
            foreach($codes as $code) {
                if($msg == $code['code']){
                    $alert = "Code puissance existe déjà" ;
                }
            }
        }
        // to show alert
        echo $alert;
    }

    //verify if id exist
    if (!empty($_REQUEST['msg2'])) {
        $msg2 = $_REQUEST['msg2'];
        $a = 0;
        $alert2 = "" ;
        if ($msg2 != "") {
            foreach($codes as $code) {
                if($msg2 == $code['code']){
                    $a = 1;
                }
            }
            if($a == 1) {
                $alert2 = "";
            }
            else {
                $alert2 = "Code non trouvée";
            }

            
        }
        // show alert
        echo $alert2;
    }

    // get details
    // get libellé
    if(!empty($_REQUEST['id'])) {
        $num = $_REQUEST['id'];
        $query3 = $db->prepare("select * from puissance_fiscale where code=?");
        $query3->execute(array($num));
        $puissance = $query3->fetch();
        if(!empty($puissance)) {
            $libelle = $puissance['libelle'];
            echo $libelle;
        }
    }

    // taux
    if(!empty($_REQUEST['id1'])) {
        $num = $_REQUEST['id1'];
        $query3 = $db->prepare("select * from puissance_fiscale where code=?");
        $query3->execute(array($num));
        $puissance = $query3->fetch();
        if(!empty($puissance)) {
            $taux = $puissance['taux'];
            echo $taux;
        }
    }
?>
