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

    // get all existing employes
    require 'employe_verification.php';

    // select all categories
    $query = $db->prepare("select * from grade");
    $query->execute();
    $grades = $query->fetchAll();
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
                $num = $_POST['num'];
                $nom = $_POST['nom'];
                $prenom = $_POST['prenom'];
                $bank = $_POST['bank'];
                $rib = $_POST['rib'];
                $grade = $_POST['grade'];
                $count = 0;
                $errors = array();
                foreach($rows as $row) {
                    if($row['id_emp'] == $num) {
                        $count = 1;
                    }
                }


                // define errors
                if($count == 1) {
                    $errors['duplicated'] = "matricule d'employé existe déjà";
                }

                if(empty($num)) {
                    $errors['num'] = "Numéro obligatoire";
                }

                if(empty($nom)) {
                    $errors['nom'] = "Nom obligatoire";
                }

                if(empty($prenom)) {
                    $errors['prenom'] = "Prénom obligatoire";
                }

                if(empty($bank)) {
                    $errors['bank'] = "Banque obligatoire";
                }

                if(empty($rib)) {
                    $errors['rib'] = "RIB obligatoire";
                }

                if(empty($grade)) {
                    $errors['grade'] = "Grade obligatoire";
                }

                if(!empty($rib) && strlen($rib) != 24){
                    $errors['rib1'] = "Rib invalid";
                }

                if(empty($errors)) {
                    // search code of grade
                    $query1 = $db->prepare("select * from grade where int_grade=?");
                    $query1->execute(array($grade));
                    $result = $query1->fetch();
                    $code = $result['code_grade'];

                    // insert employee
                    $query2 = $db->prepare("insert into employe values(?,?,?,?,?,?)");
                    $result1 = $query2->execute(array($num,$nom,$prenom,$bank,$rib,$code));
                    if($result1) {
                        $_POST['num'] = "";
                        $_POST['nom'] = "";
                        $_POST['prenom'] = "";
                        $_POST['bank'] = "";
                        $_POST['rib'] = "";
                        $_POST['grade'] = "";
                        $insert = "Une ligne ajoutée avec succès";
                    }
                }
            }
            break;
        case 'view':
            header('location:employe_view.php?action='.$action);
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
            <h1>Ajouter un <span>Employé</span></h1>
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
                    <input class="name" onkeyup="hide('hide'), hide('error')" oninput="showMsg(this.value,'','msg','txt')" name="num" value='<?php if(!empty($num)) {echo $_POST['num']; } ?>' type="number" placeholder="Matricule">
                    <i class='bx bx-id-card'></i>
                </div>
                <div class="input-box">
                    <input class="name" onkeyup="hide('hide1')" name="prenom" value='<?php if(!empty($prenom)) {echo $_POST['prenom']; } ?>' type="text" placeholder="Prénom">
                    <i class='bx bxs-user'></i>
                </div>
                <div class="input-box">
                    <input class="name" onkeyup="hide('hide2')" name="nom" value='<?php if(!empty($nom)) {echo $_POST['nom']; } ?>' type="text" placeholder="Nom">
                    <i class='bx bxs-user'></i>
                </div>
            </div>

            <!-- alert message -->
            <div class="danger">
                <div class="num">
                    <span id="txt"></span>
                    <p id="hide"><?php if(isset($errors['num'])) { echo $errors['num']; }?></p>
                    <p id="error"><?php if(isset($errors['duplicated'])) { echo $errors['duplicated']; }?></p>
                </div>
                <div class="prenom"><p id="hide1"><?php if(isset($errors['prenom'])) { echo $errors['prenom']; }?></p></div>
                <div class="nom"><p id="hide2"><?php if(isset($errors['nom'])) { echo $errors['nom']; }?></p></div>
            </div>


            <!-- second section title -->
            <h4>Banque</h4>

            <!-- second section -->
            <div class="input-group">
                <div class="input-box">
                    <input class="name" onkeyup="hide('hide3')" name="bank" value='<?php if(!empty($bank)) {echo $_POST['bank']; } ?>' type="text" placeholder="Banque">
                    <i class='bx bxs-bank'></i>
                </div>
                <div class="input-box">
                    <input class="name" onkeyup="hide('hide4')" oninput="showMsg(this.value,'','msg1','rib')" name="rib" value='<?php if(!empty($rib)) {echo $_POST['rib']; } ?>' type="text" placeholder="RIB">
                    <i class='bx bx-credit-card'></i>
                </div>
            </div>

            <!-- alert message -->
            <div class="danger">
                <div class="bank"><p id="hide3"><?php if(isset($errors['bank'])) { echo $errors['bank']; }?></p></div>
                <p id="hide4" class="rib"><?php if(isset($errors['rib'])) { echo $errors['rib']; }?></p>
                <span id="rib"></span>
            </div>


            <!-- third section title -->
            <h4>Grade</h4>

            <!-- third section -->
            <div class="input-group">
                <div class="input-box">
                    <select class="name" onchange="hide('hide5')" name="grade" value='<?php if(!empty($grade)) {echo $_POST['grade']; } ?>'>
                        <option value="">Choisissez une grade</option>
                        <?php foreach($grades as $grade) { ?>
                        <option value='<?php echo $grade['int_grade']; ?>'><?php echo $grade['int_grade']; ?></option>
                        <?php } ?>
                    </select>
                    <i class='bx bx-expand-vertical'></i>
                </div>
            </div>

            <!-- alert message -->
            <div class="danger">
                <p id="hide5" class="grade"><?php if(isset($errors['grade'])) { echo $errors['grade']; }?></p>
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



