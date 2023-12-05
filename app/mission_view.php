<!-- php part -->
<?php
    // to ban access from url
    session_start();
    if(!isset($_SESSION['login'])) {
        header('location:index.php');
        exit;
    }

    // connexion to database
    require 'connexion.php'; 


    // Get action
    $view = "";
    $action = "all";
    $errors = array();
    if(!empty($_GET['action'])) {
        $action = $_GET['action'];
    }

    // add to data base
    switch($action) {
        case 'add':
            header('location:mission_add.php?action='.$action);
            break;
        case 'view':
            $view = "Veuiller entrer le numéro et l'année puis cliquer sur valider";
            if(isset($_POST['valid'])) {
                $view = "";
                $num = $_POST['num'];
                $annee = $_POST['annee'];


                // define errors
                if(!empty($num) && !empty($annee)) {
                    $query1 = $db->prepare("select * from ordre_mission where N_OM=? and annee=?");
                    $query1->execute(array($num,$annee));
                    $result2 = $query1->fetch();
                    if(empty($result2)) {
                        $errors['mission'] = "ordre mission non trouvé";
                    }
                }

                if(empty($num)) {
                    $errors['num'] = "Numéro obligatoire";
                }

                if(empty($annee)) {
                    $errors['annee'] = "année obligatoire";
                }

                if(empty($errors)) {
                    // get employee
                    $query = $db->prepare("select * from ordre_mission where N_OM=? and annee=?");
                    $result = $query->execute(array($num,$annee));
                    $mission = $query->fetch();
                    $employe = $mission['id_emp'];
                    $query3 = $db->prepare("select * from employe where id_emp=?");
                    $query3->execute(array($employe));
                    $employes = $query3->fetch();
                    $nom = $employes['nom'];
                    $prenom = $employes['prenom'];
                    $id_destination = $mission['id_dest'];
                    $query2 = $db->prepare("select nom_destination from destination where id_destination=?");
                    $query2->execute(array($id_destination));
                    $destinations = $query2->fetch();
                    $destination = $destinations['nom_destination'];
                    $id_transport = $mission['code_transport'];
                    $query3 = $db->prepare("select libelle from transport where code=?");
                    $query3->execute(array($id_transport));
                    $transports = $query3->fetch();
                    $transport = $transports['libelle'];
                    $date_depart = $mission['date_depart'];
                    $date_retour = $mission['date_retour'];
                    $heure_depart = $mission['heure_depart'];
                    $heure_retour = $mission['heure_retour'];
                    $motif = $mission['motif'];
                    $nbr_jours = $mission['nbrj'];
                    $frais = $mission['frais_divers'];
                }
            }
            break;
        case 'modify':
            header('location:mission_modify.php?action='.$action);
            break;
    }
?>
<!-- php end -->

<!-- html part -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://unpkg.com/boxicons@2.1.2/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" type="text/css" href="../css/style2.css">
    <title>gestion des ordres mission</title>
</head>
<body>
    <!-- including the navbar file -->
    <?php include 'navbar2.html'; ?>

    <!-- view message -->
    <?php if($view == true) { ?>
        <div id="add1" class="insert">
            <i class='bx bx-info-circle'></i>
            <span class="msg"><?php echo $view ?></span>
            <i onclick="hide('add1')" class='bx bx-x'></i>
        </div>
    <?php $view = ""; } ?>


    <div class="form">
        <!-- form title -->
        <div class="intro">
            <h1>Consulter un <span>ordre mission</span></h1>
        </div>

    <!-- navigation buttons -->
    <div class="navigation">

        <!-- Add button -->
        <a href='?action=add'><button class="back add">
            <i class='bx bx-user-plus'></i>
            <span class="text">Ajouter</span>
        </button></a>

        <!-- modify button -->
        <a href="?action=modify"><button class="back modify">
            <i class='bx bx-edit'></i>
            <span class="text">Modifier</span>
        </button></a>

        <!-- consult button -->
        <a href="?action=view"><button type="submit" name="consult" class="back show">
            <i class='bx bx-show'></i>
            <span class="text">Consulter</span>
        </button></a>

        <!-- Exit button -->
        <a href="dashboard.php"><button class="back end">
            <i class='bx bx-log-out-circle'></i>
            <span class="text">Quitter</span>
        </button></a>
    </div> 

        <!-- form start -->
        <form action="" method="post">

            <!-- first section title -->
            <h4>Ordre mission</h4>

            <!-- first section -->
            <div class="input-group">
                <div class="input-box">
                    <input class="name" id="id" type="number" name="num" onkeyup="hide('hide'), hide('error')" oninput="showMsg1(document.getElementById('annee').value,this.value,'7','annee2','txt'), hide_details()" value='<?php if(!empty($num)) {echo $_POST['num']; } ?>' placeholder="Numéro d'ordre mission">
                    <i class='bx bx-hash'></i>
                </div>
                <div class="input-box">
                    <input class="name" type="number" id="annee" name="annee" onkeyup="hide('hide1'), hide('error')" oninput="showMsg1(this.value,document.getElementById('id').value,'7','annee2','txt') ,hide_details()" value='<?php if(!empty($annee)) {echo $_POST['annee']; } ?>' placeholder="Année">
                    <i class='bx bx-calendar'></i>
                </div>
            </div>

            <!-- alert message -->
            <div class="danger">
                <div class="bank">
                    <span id="txt"></span>
                    <p id="hide"><?php if(isset($errors['num'])) { echo $errors['num']; }?></p>
                    <p id="error"><?php if(isset($errors['mission'])) { echo $errors['mission']; }?></p>
                </div>
                <div class="rib">
                    <p id="hide1"><?php if(isset($errors['annee'])) { echo $errors['annee']; }?></p>
                </div>
            </div>

            <!-- second section title -->
            <h4>Employé chargé</h4>

            <!-- second section -->
            <div class="input-group">
                <div class="input-box">
                    <input class="name" type="text" id="employe" name="employe" value='<?php if(!empty($employe)) {echo $employe."- ".$nom." ".$prenom; } ?>' placeholder="Matricule d'employé" readonly>
                    <i class='bx bxs-id-card'></i>
                </div>
            </div>

            <!-- third section title -->
            <h4>Destination</h4>

            <!-- third section -->
            <div class="input-group">
                <div class="input-box">
                    <input type="text" class="name" id="destination" name="destination" value='<?php if(!empty($destination)) {echo $id_destination."- ".$destination; } ?>' placeholder="Destination" readonly>
                    <i class='bx bxs-map'></i>
                </div>
            </div>

            <!-- fifth section title -->
            <h4>transport</h4>

            <!-- fifth section -->
            <div style="margin-bottom: 15px;" class="input-group">
                <div class="input-box">
                    <input type="text" class="name" id="transport" name="transport" value='<?php if(!empty($transport)) {echo $id_transport."- ".$transport; } ?>' placeholder="moyen de transport" readonly>
                    <i class='bx bxs-bus'></i>
                </div>
            </div>

            <!-- forth section title -->
            <h4>Date et heure</h4>
            <div style="margin-top: 3px; display: flex; flex-direction:row;"><h4 style="margin-right: 200px;">Date et heure départ</h4><h4>Date et heure retour</h4></div>
            <!-- forth section -->
            <div class="input-group">
                <div class="input-box">
                    <input type="text" class="name" id="date1" name="date_depart" value='<?php if(!empty($date_depart)) {echo $date_depart; } ?>' placeholder="Date départ" readonly>
                    <i class='bx bxs-calendar-week'></i>
                </div>
                <div class="input-box">
                    <input type="text" class="name" id="heure1" name="date_retour" value='<?php if(!empty($date_retour)) {echo $date_retour; } ?>' placeholder="Date retour" readonly>
                    <i class='bx bxs-calendar-week'></i>
                </div>
            </div>

            <div class="input-group">
                <div class="input-box">
                    <input type="text" class="name" id="date2" name="heure_depart" value='<?php if(!empty($heure_depart)) {echo $heure_depart; } ?>' placeholder="heure départ" readonly>
                    <i class='bx bxs-time'></i>
                </div>
                <div class="input-box">
                    <input type="text" class="name" id="heure2" name="heure_retour" value='<?php if(!empty($heure_retour)) {echo $heure_retour; } ?>' placeholder="heure retour" readonly>
                    <i class='bx bxs-time'></i>
                </div>
            </div>

            <!-- sixth section title -->
            <h4>Motif et durée</h4>

            <!-- sixth section -->
            <div class="input-group">
                <div class="input-box">
                    <input type="text" class="name" id="motif" name="motif" value='<?php if(!empty($motif)) {echo $motif; } ?>' placeholder="Motif" readonly>
                    <i class='bx bxs-detail'></i>
                </div>
            </div>

            <div class="input-group">
                <div class="input-box">
                    <input type="text" class="name" id="duree" name="nbrj" value='<?php if(!empty($nbr_jours)) {echo $nbr_jours; } ?>' placeholder="Nombre du jours" readonly>
                    <i class='bx bx-hourglass'></i>
                </div>
            </div>

            <!-- seventh section title -->
            <h4>Frais</h4>

            <!-- seventh section -->
            <div class="input-group">
                <div class="input-box">
                    <input type="number" class="name" id="frais" name="frais" value='<?php if(!empty($frais)) {echo $frais; } ?>' placeholder="Frais divers" readonly>
                    <i class='bx bx-dollar'></i>
                </div>
            </div>

            <!-- submit button -->
            <div class="input-group">
                <div class="input-box">
                    <button type="submit" name="valid">Valider</button>
                </div>
            </div>
        </form>
        <!-- end form -->
    </div>
<?php require 'footer.html'; ?>
</body>
</html>
<!-- html end -->



<!-- ajax start -->
<!-- javascript start -->
<script src="../js/validation.js"></script>
<!-- ajax end -->
<script>
    // hide details
    function hide_details() {
        document.getElementById("employe").value = "";
        document.getElementById("destination").value = "";
        document.getElementById("transport").value = "";
        document.getElementById("date1").value = "";
        document.getElementById("heure1").value = "";
        document.getElementById("date2").value = "";
        document.getElementById("heure2").value = "";
        document.getElementById("motif").value = "";
        document.getElementById("duree").value = "";
        document.getElementById("frais").value = "";
    }
</script>
<!-- javascript end -->





