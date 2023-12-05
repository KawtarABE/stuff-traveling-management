<!-- php start -->
<?php 
    // connexion to database
    require 'connexion.php';

    // select all existing employes
    $query = $db->prepare("select id_emp from employe");
    $query->execute();
    $rows = $query->fetchAll();
?>
<!-- php end -->