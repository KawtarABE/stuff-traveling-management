<!-- php start -->
<?php 
    // connexion to database
    require 'connexion.php';

    // select all existing puissances
    $query = $db->prepare("select code from puissance_fiscale");
    $query->execute();
    $lines = $query->fetchAll();
?>
<!-- php end -->