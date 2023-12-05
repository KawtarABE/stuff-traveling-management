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
    $action = "all";
    if(!empty($_GET['action'])) {
        $action = $_GET['action'];
    }

    // add to data base
    switch($action) {
        case 'add':
            header('location:mission_add.php?action='.$action);
            break;
        case 'view':
            header('location:mission_view.php?action='.$action);
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

    <!-- message -->
    <div style="display: none;"id="add1" class="insert add1">
        <i class='bx bx-info-circle'></i>
        <span class="msg"><?php echo "Veuillez choisir votre action" ?></span>
        <i onclick="hide('add1')" class='bx bx-x'></i>
    </div>

    <!-- message -->
    <div style="display: none; margin-top: 650px; " id="add2" class="insert add1">
        <i class='bx bx-info-circle'></i>
        <span class="msg"><?php echo "Veuillez choisir votre action" ?></span>
        <i onclick="hide('add2')" class='bx bx-x'></i>
    </div>


    <div class="form">
        <!-- form title -->
        <div class="intro">
            <h1>Gestion des <span>ordres missions</span></h1>
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
                    <input class="name" type="number" name="num" onclick="show('add1')" placeholder="Numéro d'ordre mission" readonly>
                    <i class='bx bx-hash'></i>
                </div>
                <div class="input-box">
                    <input class="name" type="number" name="annee" onclick="show('add1')" placeholder="Année" readonly>
                    <i class='bx bx-calendar'></i>
                </div>
            </div>

            <!-- second section title -->
            <h4>Employé chargé</h4>

            <!-- second section -->
            <div class="input-group">
                <div class="input-box">
                    <input class="name" type="number" name="employe" onclick="show('add1')" placeholder="Matricule d'employé" readonly>
                    <i class='bx bxs-id-card'></i>
                </div>
            </div>

            <!-- third section title -->
            <h4>Destination</h4>

            <!-- third section -->
            <div class="input-group">
                <div class="input-box">
                    <input type="text" class="name" name="destination" onclick="show('add1')" placeholder="Destination" readonly>
                    <i class='bx bxs-map'></i>
                </div>
            </div>

            <!-- fifth section title -->
            <h4>transport</h4>

            <!-- fifth section -->
            <div style="margin-bottom: 15px;" class="input-group">
                <div class="input-box">
                    <input type="text" class="name" name="transport" onclick="show('add1')" placeholder="moyen de transport" readonly>
                    <i class='bx bxs-bus'></i>
                </div>
            </div>

            <!-- forth section title -->
            <h4>Date et heure</h4>
            <div style="margin-top: 3px; display: flex; flex-direction:row;"><h4 style="margin-right: 200px;">Date et heure départ</h4><h4>Date et heure retour</h4></div>
            <!-- forth section -->
            <div class="input-group">
                <div class="input-box">
                    <input type="text" class="name" name="date_depart" onclick="show('add2')" placeholder="Date départ" readonly>
                    <i class='bx bxs-calendar-week'></i>
                </div>
                <div class="input-box">
                    <input type="text" class="name" name="heure_depart" onclick="show('add2')" placeholder="Date retour" readonly>
                    <i class='bx bxs-calendar-week'></i>
                </div>
            </div>

            <div class="input-group">
                <div class="input-box">
                    <input type="text" class="name" name="date_retour" onclick="show('add2')" placeholder="heure départ" readonly>
                    <i class='bx bxs-time'></i>
                </div>
                <div class="input-box">
                    <input type="text" class="name" name="heure_retour" onclick="show('add2')" placeholder="heure retour" readonly>
                    <i class='bx bxs-time'></i>
                </div>
            </div>

            <!-- sixth section title -->
            <h4>Motif et durée</h4>

            <!-- sixth section -->
            <div class="input-group">
                <div class="input-box">
                    <input type="text" class="name" name="motif" onclick="show('add1')" placeholder="Motif" readonly>
                    <i class='bx bxs-detail'></i>
                </div>
            </div>

            <div class="input-group">
                <div class="input-box">
                    <input type="text" class="name" name="nbrj" onclick="show('add1')" placeholder="Nombre du jours" readonly>
                    <i class='bx bx-hourglass'></i>
                </div>
            </div>

            <!-- seventh section title -->
            <h4>Frais</h4>

            <!-- seventh section -->
            <div class="input-group">
                <div class="input-box">
                    <input type="number" class="name" name="frais" onclick="show('add1')" placeholder="Frais divers" readonly>
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

<!-- javascript start -->
<script src="../js/validation.js"></script>
<!-- javascript end -->





