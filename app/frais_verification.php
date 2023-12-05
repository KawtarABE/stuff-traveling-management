<!-- php start -->
<?php 
    // connexion to database
    require 'connexion.php';

    // select all existing frais
    $query = $db->prepare("select code_grade from frais_standard");
    $query->execute();
    $rows = $query->fetchAll();
?>
<!-- php end -->