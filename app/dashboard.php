<!-- php start -->
<?php
    // to ban access from url
    session_start();
    if(!isset($_SESSION['login'])) {
        header('location:index.php');
        exit;
    }
?>
<!-- php end -->

<!-- html start -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://unpkg.com/boxicons@2.1.2/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" type="text/css" href="../css/style1.css">
    <title>Dashboard</title>
</head>
<body>
    <!-- including the navbar file -->
    <?php include 'navbar.html'; ?>

    <!-- welcome message -->
    <?php if($_SESSION['welcome'] != "") { ?>
        <div id="welcome" class="welcome">
            <i class='bx bxs-user-check'></i>
            <span class="msg"><?php echo $_SESSION['welcome'] ?></span>
            <i onclick="hide()" class='bx bx-x'></i>
        </div>
    <?php $_SESSION['welcome'] = ""; } ?>

    <!-- dashboard section -->
    <div class="home">

        <!-- page's title -->
        <h1>Tableau <span>de bord</span></h1>

        <!-- widget responsive cards -->
        <div class="cards">
            <!-- first card -->
            <div class="card-body">
                <a href="employe.php">
                    <!-- title of card -->
                    <div class="title">
                        <h3>Employés</h3>
                    </div>
                    <!-- icon of card -->
                    <div class="icon">
                        <i class='bx bx-id-card'></i>
                    </div>
                </a>
            </div>
            <!-- second card -->
            <div class="card-body">
                <a href="grade.php">
                    <div class="title">
                        <h3>Grades</h3>
                    </div>
                    <div class="icon">
                        <i class='bx bx-group'></i>
                    </div>
                </a>
            </div>
            <!-- third card -->
            <div class="card-body">
                <a href="frais.php">
                    <div class="title">
                        <h3>Frais standards</h3>
                    </div>
                    <div class="icon">
                        <i class='bx bx-dollar'></i>
                    </div>
                </a>
            </div>
            <!-- forth card -->
            <div class="card-body">
                <a href="vehicule.php">
                    <div class="title">
                        <h3>Véhicules</h3>
                    </div>
                    <div class="icon">
                        <i class='bx bxs-car'></i>
                    </div>
                </a>
            </div>
            <!-- fifth card -->
            <div class="card-body">
                <a href="puissance.php">
                    <div class="title">
                        <h3>Puissances fiscales</h3>
                    </div>
                    <div class="icon">
                        <i class='bx bxs-bolt'></i>
                    </div>
                </a>
            </div>
            <!-- sexth card -->
            <div class="card-body">
                <a href="destination.php">
                    <div class="title">
                        <h3>Destinations</h3>
                    </div>
                    <div class="icon">
                        <i class='bx bx-map'></i>
                    </div>
                </a>
            </div>
            <!--seventh card -->
            <div class="card-body">
                <a href="transport.php">
                    <div class="title">
                        <h3>Transports</h3>
                    </div>
                    <div class="icon">
                        <i class='bx bxs-plane-take-off'></i>
                    </div>
                </a>
            </div>
            <!-- eighth card -->
            <div class="card-body">
                <a href="mission.php">
                    <div class="title">
                        <h3>Ordres missions</h3>
                    </div>
                    <div class="icon">
                        <i class='bx bx-briefcase-alt-2'></i>
                    </div>
                </a>
            </div>
            <!-- ninth card -->
            <div class="card-body final">
                <a href="mission_form.php">
                    <div class="title">
                        <h3>Calcule des frais</h3>
                    </div>
                    <div class="icon">
                        <i class='bx bxs-calculator'></i>
                    </div>
                </a>
            </div>
        </div>
    </div>   
</body>
</html>
<!-- html end  -->

<!-- javascript start -->
<script type="text/javascript" href="../js/validation.js"></script>
<script>
    function hide() {
        document.getElementById('welcome').style.display = "none";
    }
</script>
<!-- javascript end -->