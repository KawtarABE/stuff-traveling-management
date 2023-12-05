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

    // get all puissances 
    require 'puissance_verification.php';

    // Get action
    $update = "";
    $modify = "";
    $action = "all";
    $errors = array();
    if(!empty($_GET['action'])) {
        $action = $_GET['action'];
    }

    // modify data
    switch($action) {
        case 'add':
            header('location:puissance_add.php?action='.$action);
            break;
        case 'view':
            header('location:puissance_view.php?action='.$action);
            break;
        case 'modify':
            $modify = "Veuiller entrer le code, saisir vos modification puis cliquez sur valider";
            if(isset($_POST['valid'])) {
                $modify = "";
                $code = $_POST['code'];
                $count = 0;

                foreach($lines as $line) {
                    if($line['code'] == $code) {
                        $count = 1;
                    }
                }

                // define errors
                if(!empty($code) && $count == 0) {
                    $errors['inexistant'] = "puissance non trouvée";
                }

                if(empty($code)) {
                    $errors['code'] = "code obligatoire";
                }

                if(empty($errors)) {
                    // get form data 
                    $libelle = $_POST['libelle'];
                    $taux = $_POST['taux'];

                    // update data
                    $query1 = $db->prepare("update puissance_fiscale set libelle=?, taux=? where code=?");
                    $result1 = $query1->execute(array($libelle,$taux,$code));
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
    <title>gestion des puissances</title>
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
            <h1>Modifier une <span>Puissance fiscale</span></h1>
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
            <h4>Code</h4>

            <!-- first section -->
            <div class="input-group">
                <div class="input-box">
                    <input class="name" type="number" onkeyup="hide('hide'),hide('error') ,showMsg(this.value,'3','msg2','txt')" oninput="getDetails(this.value,'3','id','libelle_1'), getnumericDetails(this.value,'3','id1','taux')" name="code" placeholder="Code">
                    <i class='bx bx-hash'></i>
                </div>
            </div>

            <!-- alert message -->
            <div style="margin-bottom: 8px;" class="danger">
                <div class="num">
                    <span id="txt"></span>
                    <p id="hide"><?php if(isset($errors['code'])) { echo $errors['code']; }?></p>
                    <p id="error"><?php if(isset($errors['inexistant'])) { echo $errors['inexistant']; }?></p>
                </div>
            </div>

            <!-- second section title -->
            <h4>Libellé</h4>

            <!-- second section -->
            <div class="input-group">
                <div class="input-box">
                    <input type="text" class="name" id="libelle_1" name="libelle" placeholder="libellé">
                    <i class='bx bx-message-alt-detail'></i>
                </div>
            </div>

            <!-- third section title -->
            <h4>Taux</h4>

            <!-- third section -->
            <div class="input-group">
                <div class="input-box">
                    <input type="number" step="0.01" class="name" id="taux" name="taux" placeholder="Taux">
                    <i class='bx bx-line-chart'></i>
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





