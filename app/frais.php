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
            header('location:frais_add.php?action='.$action);
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

    <!-- message -->
    <div style="display: none;"id="add1" class="insert add1">
        <i class='bx bx-info-circle'></i>
        <span class="msg"><?php echo "Veuillez choisir votre action" ?></span>
        <i onclick="hide('add1')" class='bx bx-x'></i>
    </div>


    <div style="margin-top: 60px" class="form">
        <!-- form title -->
        <div class="intro">
            <h1>Gestion des <span>frais standars</span></h1>
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
                    <input class="name" type="number" onclick="show('add1')" placeholder="Code grade" readonly>
                    <i class='bx bx-hash'></i>
                </div>
            </div>

            <!-- second section title -->
            <h4>Frais</h4>

            <div class="input-group">
                <div class="input-box">
                    <input class="name" type="text" onclick="show('add1')" placeholder="déjeuner" readonly>
                    <i class='bx bx-restaurant'></i>
                </div>

                <div class="input-box">
                    <input class="name" type="text" onclick="show('add1')" placeholder="diner" readonly>
                    <i class='bx bxs-bowl-hot'></i>
                </div>

                <div class="input-box">
                    <input class="name" type="text" onclick="show('add1')" placeholder="déboucher" readonly>
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

<!-- javascript start -->
<script src="../js/validation.js"></script>
<!-- javascript end -->





