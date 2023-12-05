<?php 

    // to ban access from url
    session_start();
    if(!isset($_SESSION['login'])) {
        header('location:index.php');
        exit;
    }

    // connexion to database
    require 'connexion.php' ;

    // select all destinations
    $query2 = $db->prepare("select id_destination from destination");
    $query2->execute();
    $destinations= $query2->fetchAll();


    //verify if id is alrady existing
    if (!empty($_REQUEST['msg'])) {
        $msg = $_REQUEST['msg'];
        $alert = "" ;
        if ($msg != "") {
            foreach($destinations as $destination) {
                if($msg == $destination['id_destination']){
                    $alert = "id destination existe déjà" ;
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
            foreach($destinations as $destination) {
                if($msg2 == $destination['id_destination']){
                    $a = 1;
                }
            }
            if($a == 1) {
                $alert2 = "";
            }
            else {
                $alert2 = "destination non trouvée";
            }

            
        }
        // show alert
        echo $alert2;
    }

    // get details
    // get libellé
    if(!empty($_REQUEST['id'])) {
        $num = $_REQUEST['id'];
        $query3 = $db->prepare("select * from destination where id_destination=?");
        $query3->execute(array($num));
        $destination = $query3->fetch();
        if(!empty($destination)) {
            $nom_destination = $destination['nom_destination'];
            echo $nom_destination;
        }
    }

    // taux
    if(!empty($_REQUEST['id1'])) {
        $num = $_REQUEST['id1'];
        $query3 = $db->prepare("select * from destination where id_destination=?");
        $query3->execute(array($num));
        $destination = $query3->fetch();
        if(!empty($destination)) {
            $distance = $destination['distance'];
            echo $distance;
        }
    }
?>
