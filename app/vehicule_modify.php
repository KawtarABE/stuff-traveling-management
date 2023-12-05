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

    // get all vehicules
    require 'vehicule_verification.php';

   // select all puissances
    $query = $db->prepare("select * from puissance_fiscale");
    $query->execute();
    $puissances = $query->fetchAll();

    // Get action
    $update = "";
    $modify = "";
    $action = "all";
    $errors = array();
    if(!empty($_GET['action'])) {
        $action = $_GET['action'];
    }

    // add to data base
    switch($action) {
        case 'add':
            header('location:vehicule_add.php?action='.$action);
            break;
        case 'view':
            header('location:vehicule_view.php?action='.$action);
            break;
        case 'modify':
            $modify = "Veuiller entrer la matricule, saisir vos modification puis cliquez sur valider";
            if(isset($_POST['valid'])) {
                $modify = "";
                $matricule = $_POST['matricule'];
                $count = 0;

                foreach($lines as $line) {
                    if($line['immatriculation'] == $matricule) {
                        $count = 1;
                    }
                }

                // define errors
                if(!empty($matricule) && $count == 0) {
                    $errors['inexistant'] = "immatricule non trouvée";
                }

                if(empty($matricule)) {
                    $errors['matricule'] = "matricule obligatoire";
                }

                if(empty($errors)) {
                    // get form data 
                    $proprietaire = $_POST['proprietaire'];
                    $puissance = $_POST['puissance'];

                    // search code of puissance
                    $query = $db->prepare("select * from puissance_fiscale where libelle=?");
                    $query->execute(array($puissance));
                    $result = $query->fetch();
                    $code = $result['code'];

                    // update data
                    $query1 = $db->prepare("update vehicule_personnel set id_emp=?, code_puissance=? where immatriculation=?");
                    $result1 = $query1->execute(array($proprietaire,$code,$matricule));
                    if($result1) {
                        $update = "Une ligne a été modifier avec succès";
                    }
                }
            } 
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
    <title>gestion des véhicules</title>
</head>
<body>
    <!-- including the navbar file -->
    <?php include 'navbar.html'; ?>

    <!-- modify message -->
    <?php if($modify == true) { ?>
        <div id="add1" class="insert modify_1">
            <i class='bx bx-info-circle'></i>
            <span class="msg"><?php echo $modify ?></span>
            <i onclick="hide('add1')" class='bx bx-x'></i>
        </div>
    <?php $modify = ""; } ?>

    <!-- success message -->
    <?php if($update == true) { ?>
        <div id="insert" class="insert">
            <i class='bx bx-check'></i>
            <span class="msg"><?php echo $update ?></span>
            <i onclick="hide('insert')" class='bx bx-x'></i>
        </div>
    <?php $update = ""; } ?>


    <div class="form">
        <!-- form title -->
        <div class="intro">
            <h1>Modifier une <span>Véhicule</span></h1>
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
            <h4>Véhicule</h4>

            <!-- first section -->
            <div class="input-group">
                <div class="input-box">
                    <input class="name" type="text" onkeyup="hide('hide'),hide('error') ,showMsg(this.value,'4','msg3','txt')" oninput="getnumericDetails(this.value,'4','id','employe'), getDetails(this.value,'4','id1','puiss')"  name="matricule" placeholder="Matricule du véhicule">
                    <i class='bx bxs-car'></i>
                </div>
            </div>

            <!-- alert message -->
            <div class="danger">
                <div class="num">
                    <span id="txt"></span>
                    <p id="hide"><?php if(isset($errors['matricule'])) { echo $errors['matricule']; }?></p>
                    <p id="error"><?php if(isset($errors['inexistant'])) { echo $errors['inexistant']; }?></p>
                </div>
            </div>

            <!-- second section title -->
            <h4>Propriétaire</h4>

            <!-- second section -->
            <div class="input-group">
                <div class="input-box">
                    <input class="name" id="employe" name="proprietaire" type="numeric" onkeyup="showMsg(this.value,'4','msg1','rib')" placeholder="Matricule de l'employé">
                    <i class='bx bxs-user'></i>
                </div>
            </div>

            <!-- alert message -->
            <div class="danger">
                <div class="num">
                    <span id="rib"></span>
                </div>
            </div>
 
            <!-- third section title -->
            <h4>Puissance</h4>

            <!-- third section -->
            <div class="input-group">
                <div class="input-box">
                    <input type="text" id="puiss" class="name" name="puissance" onkeyup="showMsg(this.value,'4','msg4','libelle')" placeholder="Puissance du véhicule">
                    <i class='bx bxs-bolt'></i>
                </div>
            </div>

            <!-- alert message -->
            <div class="danger">
                <div class="num">
                    <span style="color: rgba(209, 43, 43, 0.847);" id="libelle"></span>
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

</body>
</html>
<!-- html end -->


<!-- ajax start -->
<!-- javascript start -->
<script src="../js/validation.js"></script>
<!-- ajax end -->
<!-- javascript end -->





