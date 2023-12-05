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
    $view = "";
    $action = "all";
    $errors = array();
    if(!empty($_GET['action'])) {
        $action = $_GET['action'];
    }

    // see data 
    switch($action) {
        case 'add':
            header('location:destination_add.php?action='.$action);
            break;
        case 'view':
            $view = "Veuiller entrer le code de la destination puis cliquer sur valider";
            if(isset($_POST['valid'])) {
                $view = "";
                $count = 0;
                $code = $_POST['code'];
                foreach($rows as $row) {
                    if($row['id_destination'] == $code) {
                        $count = 1;
                    }
                }

                // define errors
                if(!empty($code) && $count == 0) {
                    $errors['inexistant'] = "code destination non trouvé";
                }

                if(empty($code)) {
                    $errors['code'] = "code obligatoire";
                }

                if (empty($errors)) {
                    // get destination
                    $query = $db->prepare("select * from destination where id_destination=?");
                    $result = $query->execute(array($code));
                    $destination = $query->fetch();
                    $nom = $destination['nom_destination'];
                    $distance = $destination['distance'];
                }
            }
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
            <h1>Consulter une <span>Destination</span></h1>
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
                    <input class="name" type="number" onkeyup="hide('hide'), hide('error')" oninput="showMsg(this.value,'5','msg2','txt'), hide_details()" name="code" value='<?php if(!empty($code)) {echo $_POST['code']; } ?>'  placeholder="Code">
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
            <h4>Destination</h4>

            <!-- second section -->
            <div class="input-group">
                <div class="input-box">
                    <input class="name" type="text" id="destination" name="destination" value='<?php if(!empty($nom)) {echo $nom; } ?>' placeholder="Destination" readonly>
                    <i class='bx bxs-map'></i>
                </div>
            </div>

            <!-- third section title -->
            <h4>Distance</h4>

            <!-- third section -->
            <div class="input-group">
                <div class="input-box">
                    <input type="number" class="name" id="distance" name="distance" value='<?php if(!empty($distance)) {echo $distance; } ?>' placeholder="distance" readonly>
                    <i class='bx bx-trip'></i>
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
        document.getElementById("destination").value = "";
        document.getElementById("distance").value = ""
    }
</script>
<!-- javascript end -->





