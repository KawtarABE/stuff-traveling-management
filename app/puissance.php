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


    // Get action
    $action = "all";
    if(!empty($_GET['action'])) {
        $action = $_GET['action'];
    }

    // add to data base
    switch($action) {
        case 'add':
            header('location:puissance_add.php?action='.$action);
            break;
        case 'view':
            header('location:puissance_view.php?action='.$action);
            break;
        case 'modify':
            header('location:puissance_modify.php?action='.$action);
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
    <title>gestion des puissances</title>
</head>
<body>
    <!-- including the navbar file -->
    <?php include 'navbar.html'; ?>

    <!-- message -->
    <div style="display: none;"id="add1" class="insert add1">
        <i class='bx bx-info-circle'></i>
        <span class="msg"><?php echo "Veuillez choisir votre action" ?></span>
        <i onclick="hide('add1')" class='bx bx-x'></i>
    </div>


    <div class="form">
        <!-- form title -->
        <div class="intro">
            <h1>Gestion de <span>Puissances fiscales</span></h1>
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
                    <input class="name" type="number" onclick="show('add1')" placeholder="Code" readonly>
                    <i class='bx bx-hash'></i>
                </div>
            </div>

            <!-- second section title -->
            <h4>Libellé</h4>

            <!-- second section -->
            <div class="input-group">
                <div class="input-box">
                    <input class="name" type="text" onclick="show('add1')" placeholder="libellé" readonly>
                    <i class='bx bx-message-alt-detail'></i>
                </div>
            </div>

            <!-- third section title -->
            <h4>Taux</h4>

            <!-- third section -->
            <div class="input-group">
                <div class="input-box">
                    <input type="number" class="name" onclick="show('add1')" placeholder="Taux" readonly>
                    <i class='bx bx-line-chart'></i>
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

<!-- javascript start -->
<script src="../js/validation.js"></script>
<!-- javascript end -->





