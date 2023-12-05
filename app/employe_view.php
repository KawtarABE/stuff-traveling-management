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

    // get all employees
    require 'employe_verification.php';

    // Get action
    $view = "";
    $action = "all";
    $errors = array();
    if(!empty($_GET['action'])) {
        $action = $_GET['action'];
    }

    // add to data base
    switch($action) {
        case 'add':
            header('location:employe_add.php?action='.$action);
            break;
        case 'view':
            $view = "Veuiller entrer la matricule de l'employé puis cliquer sur valider";
            if(isset($_POST['valid'])) {
                $view = "";
                $num = $_POST['num'];
                $count = 0;
                foreach($rows as $row) {
                    if($row['id_emp'] == $num) {
                        $count = 1;
                    }
                }

                // define errors
                if(!empty($num) && $count == 0) {
                    $errors['inexistant'] = "matricule non trouvée";
                }

                if(empty($num)) {
                    $errors['num'] = "Numéro obligatoire";
                }

                if(empty($errors)) {
                    // get employee
                    $query = $db->prepare("select * from employe E, grade G where E.cod_grade=G.code_grade and id_emp=?");
                    $result = $query->execute(array($num));
                    $employe = $query->fetch();
                    $nom = $employe['nom'];
                    $prenom = $employe['prenom'];
                    $bank = $employe['banque'];
                    $rib = $employe['rib'];
                    $code_grade = $employe['code_grade'];
                    $grade = $employe['int_grade'];
                }
            }
            break;
        case 'modify':
            header('location:employe_modify.php?action='.$action);
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
    <title>gestion d'employés</title>
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
            <h1>Consulter un <span>Employé</span></h1>
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
            <h4>Informations</h4>

            <!-- first section -->
            <div class="input-group">
                <div class="input-box">
                    <input class="name" onkeyup="hide('hide'), hide('error')" oninput="showMsg(this.value,'','msg2','txt'), hide_details()" name="num" value='<?php if(!empty($num)) {echo $_POST['num']; } ?>' type="number" placeholder="Matricule">
                    <i class='bx bx-id-card'></i>
                </div>
                <div class="input-box">
                    <input class="name" id="prenom_hide" name="prenom" value='<?php if(!empty($prenom)) {echo $prenom; } ?>' type="text" placeholder="Prénom" readonly>
                    <i class='bx bxs-user'></i>
                </div>
                <div class="input-box">
                    <input class="name" id="nom_hide" name="nom" value='<?php if(!empty($nom)) {echo $nom; } ?>' type="text" placeholder="Nom" readonly>
                    <i class='bx bxs-user'></i>
                </div>
            </div>

            <!-- alert message -->
            <div class="danger">
                <div class="num">
                    <span id="txt"></span>
                    <p id="hide"><?php if(isset($errors['num'])) { echo $errors['num']; }?></p>
                    <p id="error"><?php if(isset($errors['inexistant'])) { echo $errors['inexistant']; }?></p>
                </div>
            </div>


            <!-- second section title -->
            <h4>Banque</h4>

            <!-- second section -->
            <div class="input-group">
                <div class="input-box">
                    <input class="name" id="bank_hide" name="bank" value='<?php if(!empty($bank)) {echo $bank; } ?>' type="text" placeholder="Banque" readonly>
                    <i class='bx bxs-bank'></i>
                </div>
                <div class="input-box">
                    <input class="name" id="rib_hide" name="rib" value='<?php if(!empty($rib)) {echo $rib; } ?>' type="text" placeholder="RIB" readonly>
                    <i class='bx bx-credit-card'></i>
                </div>
            </div>

            <!-- third section title -->
            <h4>Grade</h4>

            <!-- third section -->
            <div class="input-group">
                <div class="input-box">
                    <input type="text" id="grade_hide" class="name" name="grade" value='<?php if(!empty($grade)) {echo $code_grade."- ".$grade; } ?>' placeholder="Grade" readonly>
                    <i class='bx bx-expand-vertical'></i>
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
        document.getElementById("prenom_hide").value = "";
        document.getElementById("nom_hide").value = "";
        document.getElementById("bank_hide").value = "";
        document.getElementById("rib_hide").value = "";
        document.getElementById("grade_hide").value = "";
    }
</script>
<!-- javascript end -->



