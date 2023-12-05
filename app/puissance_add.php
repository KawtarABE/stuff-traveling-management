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
    
    // get all puissance
    require 'puissance_verification.php';


    // Get action
    $insert = "";
    $add = "";
    $action = "all";
    if(!empty($_GET['action'])) {
        $action = $_GET['action'];
    }

    // add to data base
    switch($action) {
        case 'add':
            $add = "Veuiller remplir les champs puis cliquer sur valider";
            if(isset($_POST['valid'])) {
                $add = "";
                $code = $_POST['code'];
                $libelle = $_POST['libelle'];
                $taux = $_POST['taux'];
                $errors = array();

                $count = 0;
                foreach($lines as $line) {
                    if($line['code'] == $code) {
                        $count = 1;
                    }
                }

                // define errors
                if($count == 1) {
                    $errors['duplicated'] = "code puissance existe déjà";
                }

                if(empty($code)) {
                    $errors['code'] = "code obligatoire";
                }

                if(empty($libelle)) {
                    $errors['libelle'] = "libellé obligatoire";
                }

                if(empty($taux)) {
                    $errors['taux'] = "taux obligatoire";
                }

                if(empty($errors)) {
                    // insert employee
                    $query2 = $db->prepare("insert into puissance_fiscale values(?,?,?)");
                    $result = $query2->execute(array($code,$libelle,$taux));
                    if($result) {
                        $_POST['code'] = "";
                        $_POST['libelle'] = "";
                        $_POST['taux'] = "";
                        $insert = "Une ligne ajoutée avec succès";
                    }
                }
            }
            break;
        case 'view':
            header('location:puissance_view.php?action='.$action);
            break;
        case 'modify':
            header('location:puissance_modify.php?action='.$action);
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

    <!-- add message -->
    <?php if($add == true) { ?>
        <div id="add1" class="insert add1">
            <i class='bx bx-info-circle'></i>
            <span class="msg"><?php echo $add ?></span>
            <i onclick="hide('add1')" class='bx bx-x'></i>
        </div>
    <?php $add = ""; } ?>

    <!-- success message -->
    <?php if($insert == true) { ?>
        <div id="insert" class="insert">
            <i class='bx bx-check'></i>
            <span class="msg"><?php echo $insert ?></span>
            <i onclick="hide('insert')" class='bx bx-x'></i>
        </div>
    <?php $insert = ""; } ?>


    <div style="margin-top: 8px;" class="form">
        <!-- form title -->
        <div class="intro">
            <h1>Ajouter une <span>Puissance fiscale</span></h1>
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
                    <input class="name" type="number" onkeyup="hide('hide'), hide('error')" oninput="showMsg(this.value,'3','msg','txt')" name="code" value='<?php if(!empty($code)) {echo $_POST['code']; } ?>' placeholder="Code">
                    <i class='bx bx-hash'></i>
                </div>
            </div>

            <!-- alert message -->
            <div style="margin-bottom: 8px;" class="danger">
                <div class="num">
                    <span id="txt"></span>
                    <p id="hide"><?php if(isset($errors['code'])) { echo $errors['code']; }?></p>
                    <p id="error"><?php if(isset($errors['duplicated'])) { echo $errors['duplicated']; }?></p>
                </div>
            </div>

            <!-- second section title -->
            <h4>Libellé</h4>

            <!-- second section -->
            <div class="input-group">
                <div class="input-box">
                    <input class="name" type="text" onkeyup="hide('hide1')" name="libelle" value='<?php if(!empty($libelle)) {echo $_POST['libelle']; } ?>' placeholder="libellé">
                    <i class='bx bx-message-alt-detail'></i>
                </div>
            </div>

            <!-- alert message -->
            <div style="margin-bottom: 8px;" class="danger">
                <div class="bank"><p id="hide1"><?php if(isset($errors['libelle'])) { echo $errors['libelle']; }?></p></div>
            </div>


            <!-- third section title -->
            <h4>Taux</h4>

            <!-- third section -->
            <div class="input-group">
                <div class="input-box">
                    <input type="number" step="0.01" class="name" onkeyup="hide('hide2')" name="taux" value='<?php if(!empty($taux)) {echo $_POST['taux']; } ?>' placeholder="Taux">
                    <i class='bx bx-line-chart'></i>
                </div>
            </div>

            <!-- alert message -->
            <div class="danger">
                <div class="bank"><p id="hide2"><?php if(isset($errors['taux'])) { echo $errors['taux']; }?></p></div>
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


<!-- ajax start-->
<!-- javascript start -->
<script src="../js/validation.js"></script>
<!-- javascript end -->
<!-- ajax end -->





