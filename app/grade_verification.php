<!-- php start -->
<?php 
    // connexion to database
    require 'connexion.php';

    // select all existing grades
    $query = $db->prepare("select code_grade from grade");
    $query->execute();
    $lines = $query->fetchAll();
?>
<!-- php end -->