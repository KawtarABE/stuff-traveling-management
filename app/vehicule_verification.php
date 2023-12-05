<!-- php start -->
<?php 
    // connexion to database
    require 'connexion.php';

    // select all existing puissances
    $query = $db->prepare("select immatriculation from vehicule_personnel");
    $query->execute();
    $lines = $query->fetchAll();
?>
<!-- php end -->