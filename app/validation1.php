<?php 

    // to ban access from url
    session_start();
    if(!isset($_SESSION['login'])) {
        header('location:index.php');
        exit;
    }

    // connexion to database
    require 'connexion.php' ;

    // select all ids
    $query2 = $db->prepare("select code_grade from grade");
    $query2->execute();
    $ids = $query2->fetchAll();

    //verify if id is alrady existing
    if (!empty($_REQUEST['msg'])) {
        $msg = $_REQUEST['msg'];
        $alert = "" ;
        if ($msg != "") {
            foreach($ids as $id) {
                if($msg == $id['code_grade']){
                    $alert = "code déjà utilisé" ;
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
            foreach($ids as $id) {
                if($msg2 == $id['code_grade']){
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

    // get details
    // grade name
    if(!empty($_REQUEST['id'])) {
        $num = $_REQUEST['id'];
        $query3 = $db->prepare("select * from grade where code_grade=?");
        $query3->execute(array($num));
        $grade = $query3->fetch();
        if(!empty($grade)) {
            $nom_grade = $grade['int_grade'];
            echo $nom_grade;
        }
    }
?>
