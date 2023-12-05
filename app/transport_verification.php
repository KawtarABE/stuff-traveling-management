<!-- php start -->
<?php 
    // connexion to database
    require 'connexion.php';

    // select all existing transports
    $query = $db->prepare("select code from transport");
    $query->execute();
    $lines = $query->fetchAll();
?>
<!-- php end -->