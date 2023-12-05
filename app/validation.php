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
    $query2 = $db->prepare("select id_emp from employe");
    $query2->execute();
    $ids = $query2->fetchAll();

    //verify if id is alrady existing
    if (!empty($_REQUEST['msg'])) {
        $msg = $_REQUEST['msg'];
        $alert = "" ;
        if ($msg != "") {
            foreach($ids as $id) {
                if($msg == $id['id_emp']){
                    $alert = "Id déjà utilisé" ;
                }
            }
        }
        // to show alert
        echo $alert;
    }

    // verify if rib is valid
    if (!empty($_REQUEST['msg1'])) {
        $msg1 = $_REQUEST['msg1'];
        $alert1 = "" ;
        if (strlen($msg1) != 24) {
            $alert1 = "RIB invalid" ;
        }
        // show alert
        echo $alert1;
    }

    //verify if id exist
    if (!empty($_REQUEST['msg2'])) {
        $msg2 = $_REQUEST['msg2'];
        $a = 0;
        $alert2 = "" ;
        if ($msg2 != "") {
            foreach($ids as $id) {
                if($msg2 == $id['id_emp']){
                    $a = 1;
                }
            }
            if($a == 1) {
                $alert2 = "";
            }
            else {
                $alert2 = "Matricule non trouvée";
            }

            
        }
        // show alert
        echo $alert2;
    }

    // get details
    // nom
    if(!empty($_REQUEST['id'])) {
        $num = $_REQUEST['id'];
        $query3 = $db->prepare("select * from employe E, grade G where E.cod_grade=G.code_grade and id_emp=?");
        $query3->execute(array($num));
        $employe = $query3->fetch();
        if(!empty($employe)) {
            $nom = $employe['nom'];
            echo $nom;
        }
    }
    // prenom
    if(!empty($_REQUEST['id1'])) {
        $num = $_REQUEST['id1'];
        $query3 = $db->prepare("select * from employe E, grade G where E.cod_grade=G.code_grade and id_emp=?");
        $query3->execute(array($num));
        $employe = $query3->fetch();
        if(!empty($employe)) {
            $prenom = $employe['prenom'];
            echo $prenom;
        }
    }
    // bank
    if(!empty($_REQUEST['id2'])) {
        $num = $_REQUEST['id2'];
        $query3 = $db->prepare("select * from employe E, grade G where E.cod_grade=G.code_grade and id_emp=?");
        $query3->execute(array($num));
        $employe = $query3->fetch();
        if(!empty($employe)) {
            $banque = $employe['banque'];
            echo $banque;
        }
    }
    // rib
    if(!empty($_REQUEST['id3'])) {
        $num = $_REQUEST['id3'];
        $query3 = $db->prepare("select * from employe E, grade G where E.cod_grade=G.code_grade and id_emp=?");
        $query3->execute(array($num));
        $employe = $query3->fetch();
        if(!empty($employe)) {
            $rib = $employe['rib'];
            echo $rib;
        }
    }
    // grade
    if(!empty($_REQUEST['id4'])) {
        $num = $_REQUEST['id4'];
        $query3 = $db->prepare("select * from employe E, grade G where E.cod_grade=G.code_grade and id_emp=?");
        $query3->execute(array($num));
        $employe = $query3->fetch();
        if(!empty($employe)) {
            $grade = $employe['int_grade'];
            echo $grade;
        }
    }

?>
