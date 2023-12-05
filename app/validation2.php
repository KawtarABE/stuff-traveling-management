<?php 

    // to ban access from url
    session_start();
    if(!isset($_SESSION['login'])) {
        header('location:index.php');
        exit;
    }

    // connexion to database
    require 'connexion.php' ;

    // select all declared frais
    $query2 = $db->prepare("select code_grade from frais_standard");
    $query2->execute();
    $ids = $query2->fetchAll();

    // select all grades
    $query2 = $db->prepare("select code_grade from grade");
    $query2->execute();
    $grades = $query2->fetchAll();

    //verify if id is alrady existing
    if (!empty($_REQUEST['msg'])) {
        $msg = $_REQUEST['msg'];
        $alert = "" ;
        if ($msg != "") {
            foreach($ids as $id) {
                if($msg == $id['code_grade']){
                    $alert = "frais déjà déclarés" ;
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
            foreach($grades as $grade) {
                if($msg2 == $grade['code_grade']){
                    $a = 1;
                }
            }
            if($a == 1) {
                $alert2 = "";
            }
            else {
                $alert2 = "grade non trouvée";
            }

            
        }
        // show alert
        echo $alert2;
    }

    //verify if frais existe
    if (!empty($_REQUEST['msg3'])) {
        $msg2 = $_REQUEST['msg3'];
        $a = 0;
        $alert2 = "" ;
        if ($msg2 != "") {
            foreach($ids as $id) {
                if($msg2 == $id['code_grade']){
                    $a = 1;
                }
            }
            if($a == 1) {
                $alert2 = "";
            }
            else {
                $alert2 = "frais non encore déclarés";
            }

            
        }
        // show alert
        echo $alert2;
    }

    // get details
    // déjeuer
    if(!empty($_REQUEST['id'])) {
        $num = $_REQUEST['id'];
        $query3 = $db->prepare("select * from frais_standard where code_grade=?");
        $query3->execute(array($num));
        $frais = $query3->fetch();
        if(!empty($frais)) {
            $dejeuner = $frais['dejeuner'];
            echo $dejeuner;
        }
    }

        // diner
        if(!empty($_REQUEST['id1'])) {
            $num = $_REQUEST['id1'];
            $query3 = $db->prepare("select * from frais_standard where code_grade=?");
            $query3->execute(array($num));
            $frais = $query3->fetch();
            if(!empty($frais)) {
                $diner = $frais['diner'];
                echo $diner;
            }
        }

    // déboucher
    if(!empty($_REQUEST['id2'])) {
        $num = $_REQUEST['id2'];
        $query3 = $db->prepare("select * from frais_standard where code_grade=?");
        $query3->execute(array($num));
        $frais = $query3->fetch();
        if(!empty($frais)) {
            $deboucher = $frais['deboucher'];
            echo $deboucher;
        }
    }
?>
