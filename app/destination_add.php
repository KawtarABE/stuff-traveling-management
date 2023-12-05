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

    // get all existing destinations
    require 'destination_verification.php';

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
                $count = 0;
                $code = $_POST['code'];
                $destination = $_POST['destination'];
                $distance = $_POST['distance'];
                foreach($rows as $row) {
                    if($row['id_destination'] == $code) {
                        $count = 1;
                    }
                }
                $errors = array();

                // define errors
                if($count == 1) {
                    $errors['duplicated'] = "code existe déjà";
                }

                if(empty($code)) {
                    $errors['code'] = "code obligatoire";
                }

                if(empty($destination)) {
                    $errors['destination'] = "destination obligatoire";
                }

                if(empty($distance)) {
                    $errors['distance'] = "distance obligatoire";
                }

                if(empty($errors)) {
                    // insert destination
                    $query2 = $db->prepare("insert into destination values(?,?,?)");
                    $result = $query2->execute(array($code,$destination,$distance));
                    if($result) {
                        $_POST['code'] = "";
                        $_POST['destination'] = "";
                        $_POST['distance'] = "";
                        $insert = "Une ligne ajoutée avec succès";
                    }
                }
            }
            break;
        case 'view':
            header('location:destination_view.php?action='.$action);
            break;
        case 'modify':
            header('location:destination_modify.php?action='.$action);
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
    <title>gestion des destinations</title>
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
            <h1>Ajouter une <span>Destination</span></h1>
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
                    <input class="name" type="number" onkeyup="hide('hide'), hide('error')" oninput="showMsg(this.value,'5','msg','txt')" name="code" value='<?php if(!empty($code)) {echo $_POST['code']; } ?>' placeholder="Code">
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
            <h4>Destination</h4>

            <!-- second section -->
            <div class="input-group">
                <div class="input-box">
                    <input class="name" type="text" onkeyup="hide('hide1')" name="destination" value='<?php if(!empty($destination)) {echo $_POST['destination']; } ?>' placeholder="Destination">
                    <i class='bx bxs-map'></i>
                </div>
            </div>

            <!-- alert message -->
            <div style="margin-bottom: 8px;" class="danger">
                <div class="bank"><p id="hide1"><?php if(isset($errors['destination'])) { echo $errors['destination']; }?></p></div>
            </div>

            <!-- third section title -->
            <h4>Distance</h4>

            <!-- third section -->
            <div class="input-group">
                <div class="input-box">
                    <input type="number" class="name" onkeyup="hide('hide2')" name="distance" value='<?php if(!empty($distance)) {echo $_POST['distance']; } ?>' placeholder="distance">
                    <i class='bx bx-trip'></i>
                </div>
            </div>

            <!-- alert message -->
            <div class="danger">
                <div class="bank"><p id="hide2"><?php if(isset($errors['distance'])) { echo $errors['distance']; }?></p></div>
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





