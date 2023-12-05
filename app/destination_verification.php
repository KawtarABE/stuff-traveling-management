<!-- php start -->
<?php 
    // connexion to database
    require 'connexion.php';

    // select all existing destinations
    $query = $db->prepare("select id_destination from destination");
    $query->execute();
    $rows = $query->fetchAll();
?>
<!-- php end -->