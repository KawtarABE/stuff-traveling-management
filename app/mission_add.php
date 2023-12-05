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

    // select all destinations
    $query = $db->prepare("select * from destination");
    $query->execute();
    $destinations = $query->fetchAll();

    // select all transports
    $query1 = $db->prepare("select * from transport");
    $query1->execute();
    $transports = $query1->fetchAll();

    // Get action
    $insert = "";
    $add ="";
    $errors = array();
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
                $annee = $_POST['annee'];
                $employe = $_POST['employe'];
                $destination = $_POST['destination'];
                $transport = $_POST['transport'];
                $date_depart = $_POST['date_depart'];
                $date_retour = $_POST['date_retour'];
                $heure_depart = $_POST['heure_depart'];
                $heure_retour = $_POST['heure_retour'];
                $motif = $_POST['motif'];
                $nbrj = $_POST['nbrj'];
                $frais = $_POST['frais'];
                $count = 0;
                $errors = array();
                $query2 = $db->prepare("select * from ordre_mission where N_OM=? and annee=?");
                $query2->execute(array($num,$annee));
                $result = $query2->fetch();
                if(!empty($result)) {
                        $count = 1;
                    }
                $count1 = 0;
                $query3 = $db->prepare("select * from employe where id_emp=?");
                $query3->execute(array($employe));
                $result2 = $query3->fetch();
                if(!empty($result2)) {
                    $count1 = 1;
                }


                // define errors
                if($count == 1) {
                    $errors['duplicated'] = "n° mission existe déjà en cette année";
                }

                if(!empty($employe) && $count1 == 0) {
                    $errors['employe_unfound'] = "employé non trouvé";
                }

                if(empty($num)) {
                    $errors['num'] = "Numéro de mission obligatoire";
                }

                if(empty($annee)) {
                    $errors['annee'] = "année obligatoire";
                }

                if(!empty($annee) && ($annee < 1900)) {
                    $errors['annee'] = "année invalid";
                }

                if(empty($employe)) {
                    $errors['employe'] = "id d'employé obligatoire";
                }

                if(empty($destination)) {
                    $errors['destination'] = "destination obligatoire";
                }

                if(empty($transport)) {
                    $errors['transport'] = "transport obligatoire";
                }

                if(empty($date_depart)) {
                    $errors['date_depart'] = "date de depart obligatoire";
                }

                if(empty($date_retour)) {
                    $errors['date_retour'] = "date de retour obligatoire";
                }

                if(empty($heure_depart)) {
                    $errors['heure_depart'] = "heure de depart obligatoire";
                }

                if(empty($heure_retour)) {
                    $errors['heure_retour'] = "heure de retour obligatoire";
                }

                if(empty($motif)) {
                    $errors['motif'] = "motif obligatoire";
                }

                if(empty($nbrj)) {
                    $errors['nbrj'] = "nombre du jours obligatoire";
                }

                if(empty($frais)) {
                    $errors['frais'] = "frais obligatoire";
                }

                if($date_depart > $date_retour){
                    $errors['invalid'] = "dates invalides";
                }

                if(empty($errors)) {
                    // search code of destination
                    $query3 = $db->prepare("select * from destination where nom_destination=?");
                    $query3->execute(array($destination));
                    $result1 = $query3->fetch();
                    $code_destination = $result1['id_destination'];

                    // search code of transport
                    $query4 = $db->prepare("select * from transport where libelle=?");
                    $query4->execute(array($transport));
                    $result2 = $query4->fetch();
                    $code_transport = $result2['code'];

                    // insert ordre mission
                    $query5 = $db->prepare("insert into ordre_mission values(?,?,?,?,?,?,?,?,?,?,?,?)");
                    $result3 = $query5->execute(array($num,$annee,$date_depart,$heure_depart,$date_retour,$heure_retour,$motif,$nbrj,$frais,$employe,$code_destination,$code_transport));
                    if($result3) {
                        $_POST['num'] = "";
                        $_POST['annee'] = "";
                        $_POST['employe'] = "";
                        $_POST['destination'] = "";
                        $_POST['transport'] = "";
                        $_POST['date_depart'] = "";
                        $_POST['date_retour'] = "";
                        $_POST['heure_depart'] = "";
                        $_POST['heure_retour'] = "";
                        $_POST['motif'] = "";
                        $_POST['nbrj'] = "";
                        $_POST['frais'] = "";
                        $insert = "Une ligne ajoutée avec succès";
                    }
                }
            }
            break;
        case 'view':
            header('location:mission_view.php?action='.$action);
            break;
        case 'modify':
            header('location:mission_modify.php?action='.$action);
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
    <title>gestion des ordres mission</title>
</head>
<body>
    <!-- including the navbar file -->
    <?php include 'navbar2.html'; ?>

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
            <h1>Ajouter un <span>ordre mission</span></h1>
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
            <h4>Ordre mission</h4>

            <!-- first section -->
            <div class="input-group">
                <div class="input-box">
                    <input class="name" type="number" id="num" name="num" onkeyup="hide('hide'), hide('error')" oninput=" showMsg1(document.getElementById('annee').value,this.value,'7','annee','txt')" value='<?php if(!empty($num)) {echo $_POST['num']; } ?>' placeholder="Numéro d'ordre mission">
                    <i class='bx bx-hash'></i>
                </div>
                <div class="input-box">
                    <input class="name" type="number" id="annee" onkeyup="hide('hide1')" oninput="showMsg1(this.value,document.getElementById('num').value,'7','annee','txt'), showMsg(this.value,'7','annee1','txt2')" value='<?php if(!empty($annee)) {echo $_POST['annee']; } ?>' name="annee" placeholder="Année">
                    <i class='bx bx-calendar'></i>
                </div>
            </div>

            <!-- alert message -->
            <div class="danger">
                <div style="margin-right: 120px;" class="num">
                    <span id="txt"></span>
                    <span style="color: rgba(209, 43, 43, 0.847);" id="txt2"></span>
                    <p id="hide"><?php if(isset($errors['num'])) { echo $errors['num']; }?></p>
                    <p id="error"><?php if(isset($errors['duplicated'])) { echo $errors['duplicated']; }?></p>
                </div>
                <div><span style="color: rgba(209, 43, 43, 0.847);" id="txt2"></span></div>
                <div class="rib">
                <p id="hide1"><?php if(isset($errors['annee'])) { echo $errors['annee']; }?></p>
                </div>
            </div>

            <!-- second section title -->
            <h4>Employé chargé</h4>

            <!-- second section -->
            <div class="input-group">
                <div class="input-box">
                    <input class="name" type="number" onkeyup="hide('hide2'), hide('error1')" oninput="showMsg(this.value,'7','employe','txt1')" value='<?php if(!empty($employe)) {echo $_POST['employe']; } ?>' name="employe" placeholder="Matricule d'employé">
                    <i class='bx bxs-id-card'></i>
                </div>
            </div>

            <!-- alert message -->
            <div class="danger">
                <div class="num">
                    <span style="color: rgba(209, 43, 43, 0.847);" id="txt1"></span>
                    <p id="hide2"><?php if(isset($errors['employe'])) { echo $errors['employe']; }?></p>
                    <p id="error1"><?php if(isset($errors['employe_unfound'])) { echo $errors['employe_unfound']; }?></p>
                </div>
            </div>

            <!-- third section title -->
            <h4>Destination</h4>

            <!-- third section -->
            <div class="input-group">
                <div class="input-box">
                    <select class="name" onchange="hide('hide3')" value='<?php if(!empty($destination)) {echo $_POST['destination']; } ?>' name="destination" placeholder="Destination">
                        <option value="">Choisissez une destination</option>
                        <?php foreach($destinations as $destination) { ?>
                            <option value='<?php echo $destination['nom_destination']; ?>'><?php echo $destination['nom_destination']; ?></option>
                        <?php } ?>
                    </select>
                    <i class='bx bxs-map'></i>
                </div>
            </div>

            <!-- alert message -->
            <div class="danger">
                <div class="num">
                    <p id="hide3"><?php if(isset($errors['destination'])) { echo $errors['destination']; }?></p>
                </div>
            </div>

            <!-- fifth section title -->
            <h4>transport</h4>

            <!-- fifth section -->
            <div style="margin-bottom: 15px;" class="input-group">
                <div class="input-box">
                    <select class="name" onchange="hide('hide4')" name="transport" placeholder="moyen de transport">
                    <option value="">Choisissez un transport</option>
                        <?php foreach($transports as $transport) { ?>
                            <option value='<?php echo $transport['libelle']; ?>'><?php echo $transport['libelle']; ?></option>
                        <?php } ?>
                    </select>
                    <i class='bx bxs-bus'></i>
                </div>
            </div>

            <!-- alert message -->
            <div class="danger">
                <div style="margin-top: -15px;" class="num">
                    <p id="hide4"><?php if(isset($errors['transport'])) { echo $errors['transport']; }?></p>
                </div>
            </div>

            <!-- forth section title -->
            <h4>Date et heure</h4>
            <div style="margin-top: 3px; display: flex; flex-direction:row;"><h4 style="margin-right: 200px;">Date et heure départ</h4><h4>Date et heure retour</h4></div>
            <!-- forth section -->
            <div class="input-group">
                <div class="input-box">
                    <input type="date" class="name" id="date_depart" onkeyup="hide('hide5')" onchange="hide('hide5')" oninput="showMsg1(document.getElementById('date_retour').value,this.value,'7','date','txt3')" value='<?php if(!empty($date_depart)) {echo $_POST['date_depart']; } ?>' name="date_depart" placeholder="Date départ">
                    <i class='bx bxs-calendar-week'></i>
                </div>
                <div class="input-box">
                    <input type="date" id="date_retour" class="name" onkeyup="hide('hide6'), hide('hide12')" onchange="hide('hide6')" oninput="showMsg1(this.value,document.getElementById('date_depart').value,'7','date','txt3')" value='<?php if(!empty($date_retour)) {echo $_POST['date_retour']; } ?>' name="date_retour" placeholder="Date retour">
                    <i class='bx bxs-calendar-week'></i>
                </div>
            </div>

            <!-- alert message -->
            <div class="danger">
            <p id="hide12" class="bank"><?php if(isset($errors['invalid'])) { echo $errors['invalid']; }?></p>
            </div>

            <!-- alert message -->
            <div class="danger">
                <span style="margin-top: -10px; color: rgba(209, 43, 43, 0.847);" id="txt3"></span>
                <div class="bank"><p id="hide5"><?php if(isset($errors['date_depart'])) { echo $errors['date_depart']; }?></p></div>
                <p id="hide6" class="rib"><?php if(isset($errors['date_retour'])) { echo $errors['date_retour']; }?></p>
            </div>

            <div class="input-group">
                <div class="input-box">
                    <input type="time" class="name" name="heure_depart" onkeyup="hide('hide7')" onchange="hide('hide7')" value='<?php if(!empty($heure_depart)) {echo $_POST['heure_depart']; } ?>' placeholder="heure départ">
                    <i class='bx bxs-time'></i>
                </div>
                <div class="input-box">
                    <input type="time" class="name" name="heure_retour" onkeyup="hide('hide8')" onchange="hide('hide8')" value='<?php if(!empty($heure_retour)) {echo $_POST['heure_retour']; } ?>' placeholder="heure retour">
                    <i class='bx bxs-time'></i>
                </div>
            </div>

            <!-- alert message -->
            <div class="danger">
                <div class="bank"><p id="hide7"><?php if(isset($errors['heure_depart'])) { echo $errors['heure_depart']; }?></p></div>
                <p id="hide8" class="rib"><?php if(isset($errors['heure_retour'])) { echo $errors['heure_retour']; }?></p>
            </div>

            <!-- sixth section title -->
            <h4>Motif et durée</h4>

            <!-- sixth section -->
            <div class="input-group">
                <div class="input-box">
                    <input type="text" class="name" name="motif" onkeyup="hide('hide9')" value='<?php if(!empty($motif)) {echo $_POST['motif']; } ?>' placeholder="Motif">
                    <i class='bx bxs-detail'></i>
                </div>
            </div>

            <!-- alert message -->
            <div class="danger">
                <div class="num">
                    <p id="hide9"><?php if(isset($errors['motif'])) { echo $errors['motif']; }?></p>
                </div>
            </div>

            <div class="input-group">
                <div class="input-box">
                    <input type="number" class="name" name="nbrj" onkeyup="hide('hide10')" value='<?php if(!empty($nbrj)) {echo $_POST['nbrj']; } ?>' placeholder="Nombre du jours">
                    <i class='bx bx-hourglass'></i>
                </div>
            </div>

            <!-- alert message -->
            <div class="danger">
                <div class="num">
                    <p id="hide10"><?php if(isset($errors['nbrj'])) { echo $errors['nbrj']; }?></p>
                </div>
            </div>


            <!-- seventh section title -->
            <h4>Frais</h4>

            <!-- seventh section -->
            <div class="input-group">
                <div class="input-box">
                    <input type="number" step="0.1" class="name" name="frais" onkeyup="hide('hide11')" value='<?php if(!empty($frais)) {echo $_POST['frais']; } ?>' placeholder="Frais divers">
                    <i class='bx bx-dollar'></i>
                </div>
            </div>

            <!-- alert message -->
            <div class="danger">
                <div class="num">
                    <p id="hide11"><?php if(isset($errors['frais'])) { echo $errors['frais']; }?></p>
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
<?php require 'footer.html'; ?>
</body>
</html>
<!-- html end -->

<!-- javascript start -->
<script src="../js/validation.js"></script>
<!-- javascript end -->





