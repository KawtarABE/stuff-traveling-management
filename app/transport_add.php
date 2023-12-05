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
                $num = $_POST['num'];
                $nom_transport = $_POST['nom_transport'];
                $errors = array();
                $count = 0;
                foreach($lines as $line) {
                    if($line['code'] == $num) {
                        $count = 1;
                    }
                }

                // define errors
                if($count == 1) {
                    $errors['duplicated'] = "code transport existe déjà";
                }

                if(empty($num)) {
                    $errors['num'] = "code obligatoire";
                }

                if(empty($nom_transport)) {
                    $errors['nom'] = "libellé obligatoire";
                }

                if(empty($errors)) {
                    // insert transport
                    $query2 = $db->prepare("insert into transport values(?,?)");
                    $result = $query2->execute(array($num,$nom_transport));
                    if($result) {
                        $_POST['num'] = "";
                        $_POST['nom_transport'] = "";
                        $insert = "Une ligne ajoutée avec succès";
                    }
                }
            }
            break;
        case 'view':
            header('location:transport_view.php?action='.$action);
            break;
        case 'modify':
            header('location:transport_modify.php?action='.$action);
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

    <div class="form" style="margin-top: 60px;">
        <!-- form title -->
        <div class="intro">
            <h1>Ajouter un <span>transport</span></h1>
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
                    <input class="name" name="num" type="number" onkeyup="hide('hide'), hide('error')" oninput="showMsg(this.value,'6','msg','txt')" value='<?php if(!empty($num)) {echo $_POST['num']; } ?>' placeholder="Code du Transport">
                    <i class='bx bx-hash'></i>
                </div>
            </div>

            <!-- alert message -->
            <div style="margin-top: -10px; margin-bottom: 8px;" class="danger">
                <div class="num">
                    <span id="txt"></span>
                    <p id="hide"><?php if(isset($errors['num'])) { echo $errors['num']; }?></p>
                    <p id="error"><?php if(isset($errors['duplicated'])) { echo $errors['duplicated']; }?></p>
                </div>
            </div>

            <!-- second section -->
            <div style="margin-bottom: 20px;" class="input-group">
                <div class="input-box">
                    <input class="name" type="text" onkeyup="hide('hide3')" name="nom_transport" value='<?php if(!empty($nom_transport)) {echo $_POST['nom_transport']; } ?>' placeholder="moyen de Transport">
                    <i class='bx bxs-plane-take-off'></i>
                </div>
            </div>

            <!-- alert message -->
            <div style="margin-top: -10px; margin-bottom: 8px;" class="danger">
                <div class="bank"><p id="hide3"><?php if(isset($errors['nom'])) { echo $errors['nom']; }?></p></div>
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



