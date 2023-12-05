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


    // Get action
    $view = "";
    $action = "all";
    $errors = array();
    if(!empty($_GET['action'])) {
        $action = $_GET['action'];
    }

    // see data 
    switch($action) {
        case 'add':
            header('location:vehicule_add.php?action='.$action);
            break;
        case 'view':
            $view = "Veuiller entrer la matricule puis cliquer sur valider";
            if(isset($_POST['valid'])) {
                $view = "";
                $matricule = $_POST['matricule'];
                $count = 0;

                foreach($lines as $line) {
                    if($line['immatriculation'] == $matricule) {
                        $count = 1;
                    }
                }

                // define errors
                if(!empty($matricule) && $count == 0) {
                    $errors['inexistant'] = "immatriculation non trouvée";
                }

                if(empty($matricule)) {
                    $errors['matricule'] = "Immatricule obligatoire";
                }

                if(empty($errors)) {
                    // get vehicule
                    $query = $db->prepare("select * from vehicule_personnel V, puissance_fiscale P where V.code_puissance = P.code and immatriculation=?");
                    $result = $query->execute(array($matricule));
                    $vehicule = $query->fetch();
                    $proprietaire = $vehicule['id_emp'];
                    $puissance = $vehicule['libelle'];;
                }
            }
            break;
        case 'modify':
            header('location:vehicule_modify.php?action='.$action);
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
    <title>gestion des vehicules</title>
</head>
<body>
    <!-- including the navbar file -->
    <?php include 'navbar.html'; ?>

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
            <h1>Consulter une <span>Véhicule</span></h1>
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
                    <input class="name" type="text" onkeyup="hide('hide'), hide('error')" oninput="showMsg(this.value,'4','msg3','txt'), hide_details()" name="matricule" value='<?php if(!empty($matricule)) {echo $_POST['matricule']; } ?>' type="text" placeholder="Matricule du véhicule">
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
                    <input class="name" type="number" id="employe_hide" value='<?php if(!empty($proprietaire)) {echo $proprietaire; } ?>' placeholder="Matricule de l'employé" readonly>
                    <i class='bx bxs-user'></i>
                </div>
            </div>


            <!-- third section title -->
            <h4>Puissance</h4>

            <!-- third section -->
            <div class="input-group">
                <div class="input-box">
                    <input class="name" type="text" id="puissance_hide" value='<?php if(!empty($puissance)) {echo $puissance; } ?>' placeholder="Puissance du véhicule" readonly>
                    <i class='bx bxs-bolt'></i>
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
<script>
    // hide details
    function hide_details() {
        document.getElementById("employe_hide").value = "";
        document.getElementById("puissance_hide").value = "";
    }
</script>
<!-- javascript end -->





