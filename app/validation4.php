<?php 

    // to ban access from url
    session_start();
    if(!isset($_SESSION['login'])) {
        header('location:index.php');
        exit;
    }

    // connexion to database
    require 'connexion.php' ;

    // select all vehicules
    $query = $db->prepare("select immatriculation from vehicule_personnel");
    $query->execute();
    $matricules= $query->fetchAll();

    // select all employes
    $query1 = $db->prepare("select id_emp from employe");
    $query1->execute();
    $employes= $query1->fetchAll();

    // select all puissances
    $query2 = $db->prepare("select libelle from puissance_fiscale");
    $query2->execute();
    $puissances= $query2->fetchAll();


    //verify if matricule alrady existing
    if (!empty($_REQUEST['msg'])) {
        $msg = $_REQUEST['msg'];
        $alert = "" ;
        if ($msg != "") {
            foreach($matricules as $matricule) {
                if($msg == $matricule['immatriculation']){
                    $alert = "immatriculation existe déjà" ;
                }
            }
        }
        // to show alert
        echo $alert;
    }

    //verify if employe exist
    if (!empty($_REQUEST['msg1'])) {
        $msg1 = $_REQUEST['msg1'];
        $a = 0;
        $alert1 = "" ;
        if ($msg1 != "") {
            foreach($employes as $employe) {
                if($msg1 == $employe['id_emp']){
                    $a = 1;
                }
            }
            if($a == 1) {
                $alert1 = "";
            }
            else {
                $alert1 = "Employé non trouvée";
            }

            
        }
        // show alert
        echo $alert1;
    }

    // verify if matricule is valid
    if (!empty($_REQUEST['msg2'])) {
        $msg2 = $_REQUEST['msg2'];
        $alert2 = "" ;
        if (strlen($msg2) < 9) {
            $alert2 = "immaticule invalide" ;
        }
        // show alert
        echo $alert2;
    }

    //verify if matricule exist
    if (!empty($_REQUEST['msg3'])) {
        $msg3 = $_REQUEST['msg3'];
        $b = 0;
        $alert3 = "" ;
        if ($msg3 != "") {
            foreach($matricules as $matricule) {
                if($msg3 == $matricule['immatriculation']){
                    $b = 1;
                }
            }
            if($b == 1) {
                $alert3 = "";
            }
            else {
                $alert3 = "véhicule non trouvée";
            }

            
        }
        // show alert
        echo $alert3;
    }

    // verify if puissance exist
    if (!empty($_REQUEST['msg4'])) {
        $msg1 = $_REQUEST['msg4'];
        $a = 0;
        $alert1 = "" ;
        if ($msg1 != "") {
            foreach($puissances as $puissance) {
                if($msg1 == $puissance['libelle']){
                    $a = 1;
                }
            }
            if($a == 1) {
                $alert1 = "";
            }
            else {
                $alert1 = "Puissance non trouvée";
            }

            
        }
        // show alert
        echo $alert1;
    }

    
    // get details
    // employe id
    if(!empty($_REQUEST['id'])) {
        $num = $_REQUEST['id'];
        $query3 = $db->prepare("select * from vehicule_personnel where immatriculation=?");
        $query3->execute(array($num));
        $vehicule = $query3->fetch();
        if(!empty($vehicule)) {
            $id = $vehicule['id_emp'];
            echo $id;
        }
    }

    // puissaance
    if(!empty($_REQUEST['id1'])) {
        $num = $_REQUEST['id1'];
        $query3 = $db->prepare("select * from vehicule_personnel V, puissance_fiscale P where V.code_puissance=P.code and immatriculation=?");
        $query3->execute(array($num));
        $vehicule = $query3->fetch();
        if(!empty($vehicule)) {
            $libelle = $vehicule['libelle'];
            echo $libelle;
        }
    }
?>
