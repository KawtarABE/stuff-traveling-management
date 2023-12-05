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

    // get all transports
    require 'transport_verification.php';

    // Get action
    $update = "";
    $modify = "";
    $action = "all";
    if(!empty($_GET['action'])) {
        $action = $_GET['action'];
    }

    // modify data 
    switch($action) {
        case 'add':
            header('location:transport_add.php?action='.$action);
            break;
        case 'view':
            header('location:transport_view.php?action='.$action);
            break;
        case 'modify':
            $modify = "Veuiller entrer le code, saisir vos modification puis cliquez sur valider";
            if(isset($_POST['valid'])) {
                $modify = "";
                $num = $_POST['num'];
                $count = 0;

                foreach($lines as $line) {
                    if($line['code'] == $num) {
                        $count = 1;
                    }
                }

                // define errors
                if(!empty($num) && $count == 0) {
                    $errors['inexistant'] = "code transport non trouvée";
                }

                if(empty($num)) {
                    $errors['num'] = "code obligatoire";
                }

                if(empty($errors)) {
                    // get form data 
                    $nom_transport = $_POST['nom_transport'];

                    // update data
                    $query1 = $db->prepare("update transport set libelle=? where code=?");
                    $result1 = $query1->execute(array($nom_transport,$num));
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
    <title>gestion des transports</title>
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

    <div class="form" style="margin-top: 60px;">
        <!-- form title -->
        <div class="intro">
            <h1>Modifier un <span>transport</span></h1>
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


            <!-- first section -->
            <div style="margin-bottom: 20px; margin-top: 40px;" class="input-group">
                <div class="input-box">
                    <input class="name" name="num" type="number" onkeyup="hide('hide'), hide('error'), showMsg(this.value,'6','msg2','txt')" oninput="getDetails(this.value,'6','id','transport_name')" placeholder="Code du Transport">
                    <i class='bx bx-hash'></i>
                </div>
            </div>

            <!-- alert message -->
            <div style="margin-top: -10px; margin-bottom: 8px;" class="danger">
                <div class="num">
                    <span id="txt"></span>
                    <p id="hide"><?php if(isset($errors['num'])) { echo $errors['num']; }?></p>
                    <p id="error"><?php if(isset($errors['inexistant'])) { echo $errors['inexistant']; }?></p>
                </div>
            </div>

            <!-- second section -->
            <div style="margin-bottom: 20px;" class="input-group">
                <div class="input-box">
                    <input class="name" type="text" id="transport_name" name="nom_transport" placeholder="moyen de Transport">
                    <i class='bx bxs-plane-take-off'></i>
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



