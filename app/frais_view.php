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

    // get all frais
    require 'frais_verification.php';


    // Get action
    $view = "";
    $errors = array();
    $action = "all";
    if(!empty($_GET['action'])) {
        $action = $_GET['action'];
    }

    // add to data base
    switch($action) {
        case 'add':
            header('location:frais_add.php?action='.$action);
            break;
        case 'view':
            $view = "Veuiller entrer le code puis cliquer sur valider";
            if(isset($_POST['valid'])) {
                $view = "";
                $code = $_POST['code'];
                $count = 0;
                foreach($rows as $row) {
                    if($row['code_grade'] == $code) {
                        $count = 1;
                    }
                }

                // define errors
                if(!empty($code) && $count == 0) {
                    $errors['inexistant'] = "frais non encore déclarés";
                }

                if(empty($code)) {
                    $errors['code'] = "code obligatoire";
                }

                if(empty($errors)) {
                    // get prices
                    $query = $db->prepare("select * from frais_standard where code_grade=?");
                    $result = $query->execute(array($code));
                    $frais = $query->fetch();
                    $dejeuner = $frais['dejeuner'];
                    $diner = $frais['diner'];
                    $deboucher = $frais['deboucher'];
                }
            }
            break;
        case 'modify':
            header('location:frais_modify.php?action='.$action);
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
    <title>gestion des frais</title>
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


    <div style="margin-top: 60px" class="form">
        <!-- form title -->
        <div class="intro">
            <h1>Consulter des <span>frais standars</span></h1>
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
            <h4>Grade</h4>

            <!-- first section -->
            <div class="input-group">
                <div class="input-box">
                    <input class="name" type="number" onkeyup="hide('hide'), hide('error')" oninput="showMsg(this.value,'1','msg2','txt'), hide_details()" name="code" value='<?php if(!empty($code)) {echo $_POST['code']; } ?>' placeholder="Code grade">
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
            <h4>Frais</h4>

            <div class="input-group">
                <div class="input-box">
                    <input class="name" id="dejeuner" type="text" value='<?php if(!empty($dejeuner)) {echo $dejeuner; } ?>' placeholder="déjeuner" readonly>
                    <i class='bx bx-restaurant'></i>
                </div>

                <div class="input-box">
                    <input class="name" id="diner" type="text" value='<?php if(!empty($diner)) {echo $diner; } ?>' placeholder="diner" readonly>
                    <i class='bx bxs-bowl-hot'></i>
                </div>

                <div class="input-box">
                    <input class="name" id="deboucher" type="text" value='<?php if(!empty($deboucher)) {echo $deboucher; } ?>' placeholder="déboucher" readonly>
                    <i class='bx bxs-hotel' ></i>
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
        document.getElementById("dejeuner").value = "";
        document.getElementById("diner").value = "";
        document.getElementById("deboucher").value = "";
    }
</script>
<!-- javascript end -->





