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
    
    // get all existing employees
    require 'employe_verification.php';

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
            header('location:employe_add.php?action='.$action);
            break;
        case 'view':
            header('location:employe_view.php?action='.$action);
            break;
        case 'modify':
            $modify = "Veuiller entrer la matricule de l'employé, saisir vos modification puis cliquez sur valider";
            if(isset($_POST['valid'])) {
                $modify = "";
                $count = 0;
                $num = $_POST['num'];
                foreach($rows as $row) {
                    if($row['id_emp'] == $num) {
                        $count = 1;
                    }
                }

                // define errors
                if(!empty($num) && $count == 0) {
                    $errors['inexistant'] = "matricule non trouvé";
                }

                if(empty($num)) {
                    $errors['num'] = "Numéro obligatoire";
                }

                if(empty($errors)) {
                    // get form data 
                    $nom = $_POST['nom'];
                    $prenom = $_POST['prenom'];
                    $bank  = $_POST['bank'];
                    $rib = $_POST['rib'];
                    $grade = $_POST['grade'];

                    // search code of grade
                    $query = $db->prepare("select * from grade where int_grade=?");
                    $query->execute(array($grade));
                    $result = $query->fetch();
                    $code = $result['code_grade'];

                    // update data
                    $query1 = $db->prepare("update employe set nom=?, prenom=?, banque=?, rib=?, cod_grade=? where id_emp=?");
                    $result1 = $query1->execute(array($nom,$prenom,$bank,$rib,$code,$num));
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
    <title>gestion d'employés</title>
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

    <div class="form">
        <!-- form title -->
        <div class="intro">
            <h1>Modifier un <span>Employé</span></h1>
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
                    <input class="name" onkeyup="hide('hide'), hide('error'), showMsg(this.value,'','msg2','txt')" oninput="getDetails(this.value,'','id','nom'), getDetails(this.value,'','id1','prenom'), getDetails(this.value,'','id2','bank'), getDetails(this.value,'','id3','rib1'), getDetails(this.value,'','id4','grade')"  name="num" type="number" placeholder="Matricule"/>
                    <i class='bx bx-id-card'></i>
                </div>
                <div class="input-box">
                    <input id="prenom" class="name" name="prenom" type="text" placeholder="Prénom"/>
                    <i class='bx bxs-user'></i>
                </div>
                <div class="input-box">
                    <input id="nom" class="name" name="nom" type="text" placeholder="Nom"/>
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
                    <input id="bank" class="name" name="bank" type="text" placeholder="Banque"/>
                    <i class='bx bxs-bank'></i>
                </div>
                <div class="input-box">
                    <input id="rib1" class="name" name="rib" type="text" placeholder="RIB"/>
                    <i class='bx bx-credit-card'></i>
                </div>
            </div>

            <!-- third section title -->
            <h4>Grade</h4>

            <!-- third section -->
            <div class="input-group">
                <div class="input-box">
                    <input id="grade" type="text" class="name" name="grade" placeholder="Grade"/>
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
<!-- javascript end -->


