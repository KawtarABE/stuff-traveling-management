<!-- php part -->
<?php
    // to ban access from url
    session_start();
    if(!isset($_SESSION['login'])) {
        header('location:index.php');
        exit;
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


    <div class="form" style="margin-top: 80px;">
        <!-- form title -->
        <div class="intro">
            <h1>calcul des <span>frais de déplacement</span></h1>
        </div> 

        <!-- form start -->
        <form action="proces_pdf.php" id="form" method="post">

            <!-- first section -->
            <div style="margin-bottom: 20px; margin-top: 40px;" class="input-group">
                <div class="input-box">
                    <input class="name" name="num_mission" id="num" type="number" onkeyup="hide('hide')" oninput="showMsg1(document.getElementById('annee').value,this.value,'7','annee2','txt')" placeholder="Code mission">
                    <i class='bx bx-hash'></i>
                </div>
            </div>

            <!-- alert message -->
            <div class="danger">
                <div style="margin-top: -20px;" class="num">
                    <span id="txt"></span>
                    <p id="hide"><?php if(!empty($_SESSION['error'])) {echo $_SESSION['error'];} ?></p>
                </div>
            </div>

            <!-- second section -->
            <div style="margin-bottom: 20px;" class="input-group">
                <div class="input-box">
                    <input class="name" type="number" id="annee" name="annee_mission" onkeyup="hide('hide1')" oninput="showMsg1(this.value,document.getElementById('num').value,'7','annee2','txt')" placeholder="annee">
                    <i class='bx bxs-calendar'></i>
                </div>
            </div>

            <!-- alert message -->
            <div class="danger">
                <div style="margin-top: -20px;" class="num">
                    <p id="hide1"><?php if(!empty($_SESSION['error1'])) {echo $_SESSION['error1'];} ?></p>
                </div>
            </div>

        <!-- navigation buttons -->
        <div style="margin-left: 190px;" class="navigation">

            <!-- consult button -->
            <a href="ordonnace.php"><button type="submit" name="consult" class="back show">
                <i class='bx bxs-file-doc'></i>
                <span class="text">Calculer</span>
            </button></a>

            <!-- Exit button -->
            <a href="dashboard.php"><button onclick="document.getElementById('form').action='dashboard.php'" class="back end">
                <i class='bx bx-log-out-circle'></i>
                <span class="text">Quitter</span>
            </button></a>
            <?php $_SESSION['error'] = ""; $_SESSION['error1']=""; ?>
        </div> 
    </body>
</html>
<!-- html end -->

<!-- javascript start -->
<script src="../js/validation.js"></script>
<!-- javascript end -->



