<?php 
    // to ban access from url
    session_start();
    if(!isset($_SESSION['login'])) {
        header('location:index.php');
        exit;
    }

    // connexion to database
    require 'connexion.php' ;

    // get all employes
    $query6 = $db->prepare("select id_emp from employe");
    $query6->execute();
    $employes = $query6->fetchAll();

    // verify if n° ordre mission existe in the year
    if (!empty($_REQUEST['annee'])) { 
        $annee = $_REQUEST['annee'];
        $num = $_GET['id'];
        if(!empty($annee)) {
            $query = $db->prepare("select N_OM from ordre_mission where annee=?");
            $query->execute(array($annee));
            $rows = $query->fetchAll();
        }
        $alert = "";
        if(!empty($num)) {
            foreach($rows as $row) {
                if($row['N_OM']==$num) {
                    $alert = "N° mission existe déjà en cette année";
                }
            }
        }
        echo $alert;
    }

    // verify if employes exist
    if (!empty($_REQUEST['employe'])) {
        $emp = $_REQUEST['employe'];
        $a = 0;
        $alert1 = "" ;
        if ($emp != "") {
            foreach($employes as $employe) {
                if($emp == $employe['id_emp']){
                    $a = 1;
                }
            }
            if($a == 1) {
                $alert1 = "";
            }
            else {
                $alert1 = "employe non trouvée";
            }

            
        }
        // show alert
        echo $alert1;
    }

    // year validity
    if(!empty($_REQUEST['annee1'])) {
        $anne = $_REQUEST['annee1'];
        $alert2 = "";
        if(intval($anne) < 1900) {
            $alert2 = "année inférieur à 1900";
        }
        echo $alert2;
    }

    // dates validity
    if(!empty($_REQUEST['date'])) {
        $date_retour = $_REQUEST['date'];
        $date_depart = $_GET['id'];
        $alert3 = "";
        if($date_retour < $date_depart) {
            $alert3 = "dates invalides";
        }
        echo $alert3;
    }

    // verify if mission exist 
    if(!empty($_REQUEST['annee2'])) {
        $annee = $_REQUEST['annee2'];
        $num = $_GET['id'];
        $alert4 = "";
        if(!empty($num)) {
            $query7 = $db->prepare("select * from ordre_mission where N_OM=? and annee=?");
            $query7->execute(array($num,$annee));
            $result3 = $query7->fetch();
            if(empty($result3)) {
                $alert4 = "ordre mission non trouvé";
            }
        }
        echo $alert4;
    }

    // get details 
    // get employe
    if(!empty($_REQUEST['msg'])) {
        $annee = $_REQUEST['msg'];
        $num = $_GET['num'];
        $query8 = $db->prepare("select * from ordre_mission where N_OM=? and annee=?");
        $query8->execute(array($num,$annee));
        $mission = $query8->fetch();
        if(!empty($mission)) {
            $employee = $mission['id_emp'];
            echo $employee;
        }
    }

    // get destination
    if(!empty($_REQUEST['msg1'])) {
        $annee = $_REQUEST['msg1'];
        $num = $_GET['num'];
        $query9 = $db->prepare("select * from ordre_mission OM, destination D where OM.id_dest=D.id_destination and N_OM=? and annee=?");
        $query9->execute(array($num,$annee));
        $mission = $query9->fetch();
        if(!empty($mission)) {
            $destination = $mission['nom_destination'];
            echo $destination;
        }
    }

    // get destination
    if(!empty($_REQUEST['msg2'])) {
        $annee = $_REQUEST['msg2'];
        $num = $_GET['num'];
        $query10 = $db->prepare("select * from ordre_mission OM, transport T where OM.id_dest=T.code and N_OM=? and annee=?");
        $query10->execute(array($num,$annee));
        $mission = $query10->fetch();
        if(!empty($mission)) {
            $transport = $mission['libelle'];
            echo $transport;
        }
    }

    // get date_depart
    if(!empty($_REQUEST['msg3'])) {
        $annee = $_REQUEST['msg3'];
        $num = $_GET['num'];
        $query8 = $db->prepare("select * from ordre_mission where N_OM=? and annee=?");
        $query8->execute(array($num,$annee));
        $mission = $query8->fetch();
        if(!empty($mission)) {
            $date_depart = $mission['date_depart'];
            echo $date_depart;
        }
    }

    // get date_retour
    if(!empty($_REQUEST['msg4'])) {
        $annee = $_REQUEST['msg4'];
        $num = $_GET['num'];
        $query8 = $db->prepare("select * from ordre_mission where N_OM=? and annee=?");
        $query8->execute(array($num,$annee));
        $mission = $query8->fetch();
        if(!empty($mission)) {
            $date_retour = $mission['date_retour'];
            echo $date_retour;
        }
    }

    // get heure_depart
    if(!empty($_REQUEST['msg5'])) {
        $annee = $_REQUEST['msg5'];
        $num = $_GET['num'];
        $query8 = $db->prepare("select * from ordre_mission where N_OM=? and annee=?");
        $query8->execute(array($num,$annee));
        $mission = $query8->fetch();
        if(!empty($mission)) {
            $heure_depart = $mission['heure_depart'];
            echo $heure_depart;
        }
    }

    // get heure_retour
    if(!empty($_REQUEST['msg6'])) {
        $annee = $_REQUEST['msg6'];
        $num = $_GET['num'];
        $query8 = $db->prepare("select * from ordre_mission where N_OM=? and annee=?");
        $query8->execute(array($num,$annee));
        $mission = $query8->fetch();
        if(!empty($mission)) {
            $heure_retour = $mission['heure_retour'];
            echo $heure_retour;
        }
    }

    // get motif
    if(!empty($_REQUEST['msg7'])) {
        $annee = $_REQUEST['msg7'];
        $num = $_GET['num'];
        $query8 = $db->prepare("select * from ordre_mission where N_OM=? and annee=?");
        $query8->execute(array($num,$annee));
        $mission = $query8->fetch();
        if(!empty($mission)) {
            $motif = $mission['motif'];
            echo $motif;
        }
    }

    // get nbrj
    if(!empty($_REQUEST['msg8'])) {
        $annee = $_REQUEST['msg8'];
        $num = $_GET['num'];
        $query8 = $db->prepare("select * from ordre_mission where N_OM=? and annee=?");
        $query8->execute(array($num,$annee));
        $mission = $query8->fetch();
        if(!empty($mission)) {
            $nbrj = $mission['nbrj'];
            echo $nbrj;
        }
    }

    // get frais
    if(!empty($_REQUEST['msg9'])) {
        $annee = $_REQUEST['msg9'];
        $num = $_GET['num'];
        $query8 = $db->prepare("select * from ordre_mission where N_OM=? and annee=?");
        $query8->execute(array($num,$annee));
        $mission = $query8->fetch();
        if(!empty($mission)) {
            $frais = $mission['frais_divers'];
            echo $frais;
        }
    }
    

?>