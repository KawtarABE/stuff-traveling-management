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
    $errors = array();
    $insert = "";
    $add ="";


    // Get action
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
                $id = $_POST['id'];
                $puissance = $_POST['puissance'];
                $errors = array();

                $count = 0;
                foreach($lines as $line) {
                    if($line['immatriculation'] == $code) {
                        $count = 1;
                    }
                }

                // define errors
                if($count == 1) {
                    $errors['duplicated'] = "immatricule existe déjà";
                }

                if(empty($code)) {
                    $errors['code'] = "Matricule de voiture obligatoire";
                }

                if(empty($id)) {
                    $errors['id'] = "Matricule d'employé obligatoire";
                }

                if(empty($puissance)) {
                    $errors['puissance'] = "Puissance obligatoire";
                }

                if(empty($errors)) {
                    // search code of puissance
                    $query1 = $db->prepare("select * from puissance_fiscale where libelle=?");
                    $query1->execute(array($puissance));
                    $result = $query1->fetch();
                    $code_puissance = $result['code'];

                    // insert vehicule
                    $query2 = $db->prepare("insert into vehicule_personnel values(?,?,?)");
                    $result1 = $query2->execute(array($code,$id,$code_puissance));
                    if($result1) {
                        $_POST['code'] = "";
                        $_POST['id'] = "";
                        $_POST['puissance'] = "";
                        $insert = "Une ligne ajoutée avec succès";
                    }
                }
            }
            break;
        case 'view':
            header('location:vehicule_view.php?action='.$action);
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
    <title>gestion des véhicules</title>
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


    <div class="form">
        <!-- form title -->
        <div class="intro">
            <h1>Ajouter une <span>Véhicule</span></h1>
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
                    <input class="name" type="text" onkeyup="hide('hide'), hide('error')" oninput="showMsg(this.value,'4','msg','txt'), showMsg(this.value,'4','msg2','txt1')" name="code" value='<?php if(!empty($code)) {echo $_POST['code']; } ?>' placeholder="Immatricule du véhicule">
                    <i class='bx bxs-car'></i>
                </div>
            </div>

            <!-- alert message -->
            <div class="danger">
                <div class="num">
                    <span id="txt"></span>
                    <span style="color: rgba(209, 43, 43, 0.847);" id="txt1"></span>
                    <p id="hide"><?php if(isset($errors['code'])) { echo $errors['code']; }?></p>
                    <p id="error"><?php if(isset($errors['duplicated'])) { echo $errors['duplicated']; }?></p>
                </div>
            </div>

            <!-- second section title -->
            <h4>Propriétaire</h4>

            <!-- second section -->
            <div class="input-group">
                <div class="input-box">
                    <input class="name" type="text" onkeyup="hide('hide1')" oninput="showMsg(this.value,'4','msg1','rib')" name="id" value='<?php if(!empty($id)) {echo $_POST['id']; } ?>' placeholder="Matricule de l'employé">
                    <i class='bx bxs-user'></i>
                </div>
            </div>

            <!-- alert message -->
            <div class="danger">
                <div class="bank"><p id="hide1"><?php if(isset($errors['id'])) { echo $errors['id']; }?></p></div>
                <span style="margin-left: -380px" id="rib"></span>
            </div>


            <!-- third section title -->
            <h4>Puissance</h4>

            <!-- third section -->
            <div class="input-group">
                <div class="input-box">
                    <select type="text" class="name" onchange="hide('hide2')" name="puissance" value='<?php if(!empty($puissance)) {echo $_POST['puissance']; } ?>' placeholder="Puissance du véhicule">
                    <option value="">Choisissez une puissance</option>
                        <?php foreach($puissances as $puissance) { ?>
                        <option value='<?php echo $puissance['libelle']; ?>'><?php echo $puissance['libelle']; ?></option>
                        <?php } ?>
                    </select>
                    <i class='bx bxs-bolt'></i>
                </div>
            </div>

            <!-- alert message -->
            <div class="danger">
                <div class="bank"><p id="hide2"><?php if(isset($errors['puissance'])) { echo $errors['puissance']; }?></p></div>
                <span id="puissance"></span>
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






