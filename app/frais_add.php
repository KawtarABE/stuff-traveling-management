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

    // get all grades
    require 'grade_verification.php';


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
            case 'add':
                $add = "Veuiller remplir les champs puis cliquer sur valider";
                if(isset($_POST['valid'])) {
                    $add = "";
                    $code = $_POST['code'];
                    $dejeuner = $_POST['dejeuner'];
                    $diner = $_POST['diner'];
                    $deboucher = $_POST['deboucher'];
                    $count = 0;
                    $count1 = 0;
                    $errors = array();
                    foreach($lines as $line) {
                        if($line['code_grade'] == $code) {
                            $count = 1;
                        }
                    }

                    if($count == 1) {
                        foreach($rows as $row) {
                            if($row['code_grade'] == $code) {
                                $count1 = 1;
                            }
                        }
                    }
    
    
                    // define errors
                    if($count == 0) {
                        $errors['inexistant'] = "grade non trouvée";
                    }

                    if($count1 == 1) {
                        $errors['duplicated'] = "frais déjà déclarés";
                    }
    
                    if(empty($code)) {
                        $errors['code'] = "Code grade obligatoire";
                    }
    
                    if(empty($dejeuner)) {
                        $errors['dejeuner'] = "frais déjeuner obligatoire";
                    }

                    if(empty($diner)) {
                        $errors['diner'] = "frais diner obligatoire";
                    }

                    if(empty($deboucher)) {
                        $errors['deboucher'] = "frais déboucher obligatoire";
                    }
    
                    if(empty($errors)) {
                        // insert frais
                        $query2 = $db->prepare("insert into frais_standard values(?,?,?,?)");
                        $result = $query2->execute(array($code,$dejeuner,$diner,$deboucher));
                        if($result) {
                            $_POST['code'] = "";
                            $_POST['dejeuner'] = "";
                            $_POST['diner'] = "";
                            $_POST['deboucher'] = "";
                            $insert = "Une ligne ajoutée avec succès";
                        }
                    }
                }
                break;
            break;
        case 'view':
            header('location:frais_view.php?action='.$action);
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


    <div style="margin-top: 60px" class="form">
        <!-- form title -->
        <div class="intro">
            <h1>Ajouter des <span>frais standars</span></h1>
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
                    <input class="name" name="code" type="number" onkeyup="hide('hide3'), hide('error'), hide('error1')" oninput="showMsg(this.value,'2','msg','txt'), showMsg(this.value,'2','msg2','txt_1')"  value='<?php if(!empty($code)) {echo $_POST['code']; } ?>' placeholder="Code grade">
                    <i class='bx bx-hash'></i>
                </div>
            </div>

            <!-- alert message -->
            <div class="danger">
                <div class="num">
                    <span style="width: 100%;" id="txt"></span>
                    <span style="color: rgba(209, 43, 43, 0.847);" id="txt_1"></span>
                    <p id="hide3"><?php if(isset($errors['code'])) { echo $errors['code']; }?></p>
                    <p id="error"><?php if(isset($errors['duplicated'])) { echo $errors['duplicated']; }?></p>
                    <p id="error1"><?php if(isset($errors['inexistant'])) { echo $errors['inexistant']; }?></p>
                </div>
            </div>

            <!-- second section title -->
            <h4>Frais</h4>

            <div class="input-group">
                <div class="input-box">
                    <input class="name" name="dejeuner" onkeyup="hide('hide')" value='<?php if(!empty($dejeuner)) {echo $_POST['dejeuner']; } ?>' type="number"  placeholder="déjeuner">
                    <i class='bx bx-restaurant'></i>
                </div>

                <div class="input-box">
                    <input class="name" name="diner" onkeyup="hide('hide1')" value='<?php if(!empty($diner)) {echo $_POST['diner']; } ?>' type="number" placeholder="diner">
                    <i class='bx bxs-bowl-hot'></i>
                </div>

                <div class="input-box">
                    <input class="name" name="deboucher" onkeyup="hide('hide2')" value='<?php if(!empty($deboucher)) {echo $_POST['deboucher']; } ?>' type="number" placeholder="déboucher">
                    <i class='bx bxs-hotel' ></i>
                </div>
            </div>

            <!-- alert message -->
            <div class="danger">
                <div class="num"><p id="hide"><?php if(isset($errors['dejeuner'])) { echo $errors['dejeuner']; }?></p></div>
                <div class="prenom"><p id="hide1"><?php if(isset($errors['diner'])) { echo $errors['diner']; }?></p></div>
                <div class="nom"><p id="hide2"><?php if(isset($errors['deboucher'])) { echo $errors['deboucher']; }?></p></div>
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






