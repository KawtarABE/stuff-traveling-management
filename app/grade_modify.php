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

    // get all grades
    require 'grade_verification.php';

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
            header('location:grade_add.php?action='.$action);
            break;
        case 'view':
            header('location:grade_view.php?action='.$action); 
            break;
        case 'modify':
            $modify = "Veuiller entrer le code de la grade, saisir vos modification puis cliquez sur valider";
            if(isset($_POST['valid'])) {
                $modify = "";
                $num = $_POST['num'];
                $count = 0;

                foreach($lines as $line) {
                    if($line['code_grade'] == $num) {
                        $count = 1;
                    }
                }

                // define errors
                if(!empty($num) && $count == 0) {
                    $errors['inexistant'] = "grade non trouvée";
                }

                if(empty($num)) {
                    $errors['num'] = "Numéro obligatoire";
                }

                if(empty($errors)) {
                    // get form data 
                    $nom_grade = $_POST['nom_grade'];

                    // update data
                    $query1 = $db->prepare("update grade set int_grade=? where code_grade=?");
                    $result1 = $query1->execute(array($nom_grade,$num));
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
    <title>gestion des grades</title>
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
            <h1>Modifier une <span>Grade</span></h1>
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
                    <input class="name" onkeyup="hide('hide'), hide('error'), showMsg(this.value,'1','msg2','txt')" oninput="getDetails(this.value,'1','id','grade_name')" name="num"  type="number" placeholder="Code">
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
                    <input id="grade_name" class="name" onkeyup="hide('hide3')" name="nom_grade" type="text" placeholder="Grade">
                    <i class='bx bxs-award'></i>
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



<!-- ajax start -->
<!-- javascript start -->
<script src="../js/validation.js"></script>
<!-- ajax end -->
<!-- javascript end -->


