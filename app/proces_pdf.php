<?php
// to ban access from url
session_start();
if(!isset($_SESSION['login'])) {
    header('location:index.php');
    exit;
}

// include pdf library
require('../fpdf184/fpdf.php');


// function to convert numbers to words
function convertNumberToWord($num = false)
{
    $num = str_replace(array(',', ' '), '' , trim($num));
    if(! $num) {
        return false;
    }
    $num = (int) $num;
    $words = array();
    $list1 = array('', 'UN', 'DEUX', 'TROIS', 'QUATRE', 'CINQ', 'SIX', 'SEPT', 'HUIT', 'NEUF', 'DIX', 'ONZE',
        'DOUZE', 'TREIZE', 'QUATORZE', 'QUINZE', 'SEIZE', 'DIX-SEPT', 'DIX-HUIT', 'DIX-NEUF'
    );
    $list2 = array('', 'DIX', 'VINGT', 'TRENTE', 'QUARANTE', 'CINQUANTE', 'SOIXANTE', 'SOIXANTE-DIX', 'QUATRE-VINGT', 'QUATRE-VINGT DIX', 'CENT');
    $list3 = array('', 'MILLE', 'MILLION');
    $num_length = strlen($num);
    $levels = (int) (($num_length + 2) / 3);
    $max_length = $levels * 3;
    $num = substr('00' . $num, -$max_length);
    $num_levels = str_split($num, 3);
    for ($i = 0; $i < count($num_levels); $i++) {
        $levels--;
        $hundreds = (int) ($num_levels[$i] / 100);
        $hundreds = ($hundreds ? ' ' . $list1[$hundreds] . ' CENT' . ' ' : '');
        $tens = (int) ($num_levels[$i] % 100);
        $singles = '';
        if ( $tens < 20 ) {
            $tens = ($tens ? ' ' . $list1[$tens] . ' ' : '' );
        } else {
            $tens = (int)($tens / 10);
            $tens = ' ' . $list2[$tens] . ' ';
            $singles = (int) ($num_levels[$i] % 10);
            $singles = ' ' . $list1[$singles] . ' ';
        }
        $words[] = $hundreds . $tens . $singles . ( ( $levels && ( int ) ( $num_levels[$i] ) ) ? ' ' . $list3[$levels] . ' ' : '' );
    } //end for loop
    $commas = count($words);
    if ($commas > 1) {
        $commas = $commas - 1;
    }
    return implode(' ', $words);
}


// connexion to data base
$db = new PDO('mysql:host=localhost;dbname=alomrane','root','');


// get data
$num = $_POST['num_mission'];
$annee = $_POST['annee_mission'];
if(empty($num)) {
    $_SESSION['error'] = "Numéro obligatoire";
    header('location:mission_form.php');
}
if(empty($annee)) {
    $_SESSION['error1'] = "Année obligatoire";
    header('location:mission_form.php');
}
$query = $db->prepare("select * from ordre_mission OM, employe E, grade G, frais_standard F, transport T, destination D where E.id_emp=OM.id_emp and G.code_grade=E.cod_grade and F.code_grade=G.code_grade and T.code=OM.code_transport and D.id_destination=OM.id_dest and N_OM=? and annee=?");
$query->execute(array($num,$annee));
$result = $query->fetch();
$month = date('F', strtotime($result['date_depart']));
$year = date('Y', strtotime($result['date_depart']));
switch($month) {
    case "January":
        $month = "JANVIER";
        break;
    case "Februray":
        $month = "FEVRIER";
        break;
    case "March":
        $month = "MARS";
        break;
    case "April":
        $month = "AVRIL";
        break;
    case "May":
        $month = "MAI";
        break;
    case "June":
        $month = "JUIN";
        break;
    case "July":
        $month = "JUILLET";
        break;
    case "August":
        $month = "AOUT";
        break;
    case "September":
        $month = "SEPTEMBRE";
        break;
    case "November":
        $month = "NOVEMBRE";
        break;
    case "December":
        $month = "DECEMBRE";
        break;
}
$matricule = $result['id_emp'];
$nom = $result['nom'];
$prenom = $result['prenom'];
$bank = $result['banque'];
$rib = $result['rib'];
$grade = $result['int_grade'];
$destination = $result['nom_destination'];
$code_transport = $result['code_transport'];
$query1 = $db->prepare("select * from transport where code=?");
$query1->execute(array($code_transport));
$result1 = $query1->fetch();
$transport = $result1['libelle'];
$motif = $result['motif'];
$nbrj = $result['nbrj'];
if($code_transport == 3) {
    $query2 = $db->prepare("select * from employe E, vehicule_personnel VP, puissance_fiscale PF where E.id_emp=VP.id_emp and VP.code_puissance=PF.code and E.id_emp=?");
    $query2->execute(array($matricule));
    $result2 = $query2->fetch();
    $taux = $result2['taux'];
}
else {
    $taux = 0;
}
$distance = $result['distance'];
$indemnite = $taux * $distance;
$dejeuner = $result['dejeuner'];
$diner = $result['diner'];
$deboucher = $result['deboucher'];
$date_depart = date('d/m/y', strtotime($result['date_depart']));
$date_after = date('d/m/y', strtotime($result['date_depart'])+86400);
$heure_depart = date('H:i', strtotime($result['heure_depart']));
$date_retour = date('d/m/y', strtotime($result['date_retour']));
$heure_retour = date('H:i', strtotime($result['heure_retour']));
$frais_transport = $result['frais_divers'];
$frais_jour_depart = 0;
$frais_jour_retour = 0;
if($nbrj == 1) {
    if(date('H',strtotime($result['heure_depart'])) <= '12' && date('H',strtotime($result['heure_retour'])) > '12') {
        $frais_jour_depart = $dejeuner;
    }
    if(date('H:i',strtotime($result['heure_retour'])) > '18:30' && date('H',strtotime($result['heure_retour'])) <= '24') {
        $frais_jour_retour = $diner;
    }
}
if($nbrj == 2) {
    if(date('H',strtotime($result['heure_depart'])) <= '12') {
        $frais_jour_depart = $dejeuner + $diner + $deboucher;
    }
    if(date('H',strtotime($result['heure_depart'])) > '12' && date('H:i',strtotime($result['heure_depart'])) <= '24') {
        $frais_jour_depart = $diner + $deboucher; 
    }
    if(date('H',strtotime($result['heure_depart'])) > '24' ) {
        $frais_jour_depart = $deboucher; 
    }
    if(date('H',strtotime($result['heure_retour'])) > '12' && date('H:i',strtotime($result['heure_retour'])) <= '18:30') {
        $frais_jour_retour = $dejeuner;
    }
    if(date('H:i',strtotime($result['heure_retour'])) > '18:30' && date('H',strtotime($result['heure_retour'])) <= '24') {
        $frais_jour_retour = $dejeuner + $diner;
    }
    if(date('H',strtotime($result['heure_retour'])) > '24') {
        $frais_jour_retour = $dejeuner + $diner + $deboucher;
    }
}
if($nbrj >= 3) {
    if(date('H',strtotime($result['heure_depart'])) <= '12') {
        $frais_jour_depart = $dejeuner + $diner + $deboucher;
    }
    if(date('H',strtotime($result['heure_depart'])) > '12' && date('H:i',strtotime($result['heure_depart'])) <= '24') {
        $frais_jour_depart = $diner + $deboucher; 
    }
    if(date('H',strtotime($result['heure_depart'])) > '24' ) {
        $frais_jour_depart = $deboucher; 
    }
    if(date('H',strtotime($result['heure_retour'])) > '12' && date('H:i',strtotime($result['heure_retour'])) <= '18:30') {
        $frais_jour_retour = $dejeuner;
    }
    if(date('H:i',strtotime($result['heure_retour'])) > '18:30' && date('H',strtotime($result['heure_retour'])) <= '24') {
        $frais_jour_retour = $dejeuner + $diner;
    }
    if(date('H',strtotime($result['heure_retour'])) > '24') {
        $frais_jour_retour = $dejeuner + $diner + $deboucher;
    }
}
// calcul de frais total 
if($nbrj == 1) {
    $frais_deplacement = $frais_jour_depart + $frais_jour_retour + $frais_transport;
}
else {
    $frais_deplacement = $frais_jour_depart + $frais_jour_retour + $frais_transport + ($nbrj-2)*($dejeuner + $diner + $deboucher);
}
// calcul de frais total 
if($nbrj == 1) {
    $frais_total = $indemnite + $frais_jour_depart + $frais_jour_retour + $frais_transport;
}
else {
    $frais_total = $indemnite + $frais_jour_depart + $frais_jour_retour + $frais_transport + ($nbrj-2)*($dejeuner + $diner + $deboucher);
}

// generate pdf
$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial','U',12);
$pdf->setY(8);
$pdf->Cell(80,15,'AL OMRANE-SOUSS MASSA',0,1,'C');
$pdf->setY(17);
$pdf->Cell(80,15,'DIVISION LOGISTIQUE ET MOYEN GENERAUX',0,1,'');
$pdf->SetFont('Arial','',12);
$pdf->setY(36);
$pdf->setX(34);
$pdf->MultiCell(150,5,"PROCES VERBAL DE TOURNEES ET MEMOIRES DE SOMMES DUES POUR FRAIS DE DEPLACEMENTS ET FRAIS DE TRANSPORT",1,'C');
$pdf->Ln();
$pdf->SetFont('Arial','',10);
$pdf->Cell(80,15,'No        /AL OMRANE/D .A.',0,0,'');
$pdf->setX(100);
$pdf->Cell(80,15,'O.M No    :   '.$num,0,1,'');
$pdf->setY(59);
$pdf->Cell(80,15,'MOIS DE   :   '.$month.' '.$year,0,0,'');
$pdf->setX(100);
$pdf->Cell(80,15,'NBRE JOUR   :   '.$nbrj,0,1,'');
$pdf->setY(67);
$pdf->Cell(80,15,'MATRICULE    :   '.$matricule.'    '.$nom.' '.$prenom,0,0,'');
$pdf->setX(100);
$pdf->Cell(80,15,'CATEGORIE    :    '.$grade,0,1,'');
$pdf->setY(75);
$pdf->Cell(80,15,'DESTINATION   :   '.$destination,0,0,'');
$pdf->setX(100);
$pdf->Cell(80,15,'MOYEN DE TRANSPORT    :   '.$transport,0,1,'');
$pdf->setY(83);
$pdf->Cell(80,15,'MOTIF DE LA MISSION   :   '.$motif,0,1,'');
$pdf->line(8,101,200,101);
$pdf->SetFont('Arial','U',12);
$pdf->setY(108);
$pdf->Cell(0,5,"RECAPITULATION DES SOMMES DUES",0,1,'C');
$pdf->SetFont('Arial','U',10);
$pdf->setY(121);
$pdf->Cell(0,5,"INDEMNITE KILOMETRIQUE",0,0,'r');
$pdf->SetFont('Arial','',10);
$pdf->setX(58);
$pdf->Cell(0,5,"    :",0,0,'r');
$pdf->setX(62);
$pdf->Cell(0,5,"                ".$taux."   *   ".$distance,0,0,'r'); 
$pdf->SetX(120);
$pdf->Cell(0,5,"=           ".sprintf("%.2f",$indemnite),0,0,'r');    
$pdf->SetX(150);
$pdf->Cell(0,5,"DH",0,0,'');
$pdf->SetFont('Arial','U',10);
$pdf->setY(131);
$pdf->Cell(0,5,"DATE DE DEPART",0,0,'r');
$pdf->SetFont('Arial','',10);
$pdf->setX(58);
$pdf->Cell(0,5,"    :       ",0,0,'r');
$pdf->setX(69);
$pdf->Cell(0,5,$date_depart."       A       ".$heure_depart,0,0,'r'); 
$pdf->SetX(120);
$pdf->Cell(0,5,"=           ".sprintf("%.2f",$frais_jour_depart),0,0,'r');   
$pdf->SetX(150);
$pdf->Cell(0,5,"DH",0,0,'');
$pdf->SetFont('Arial','U',10);
if($nbrj >= 3) {
    $pdf->setY(141);
    $pdf->Cell(0,5,"PERIODE DU      ".$date_after."      AU      ".$date_retour,0,0,'r');
    $pdf->SetFont('Arial','',10);
    $pdf->setX(80);
    $pdf->Cell(0,5,"    :       ",0,0,'r');
    $pdf->SetX(120);
    $pdf->Cell(0,5,"=           ".sprintf("%.2f",($nbrj-2)*($dejeuner+$diner+$deboucher)),0,0,'r'); 
    $pdf->SetX(150);
    $pdf->Cell(0,5,"DH",0,0,'');
    $pdf->SetFont('Arial','U',10);
    $pdf->setY(151);
    $pdf->Cell(0,5,"DATE  D ' ARRIVEE",0,0,'r');
    $pdf->SetFont('Arial','',10);
    $pdf->setX(58);
    $pdf->Cell(0,5,"    :       ",0,0,'r');
    $pdf->setX(69);
    $pdf->Cell(0,5,$date_retour."       A       ".$heure_retour,0,0,'r'); 
    $pdf->SetX(120);
    $pdf->Cell(0,5,"=           ".sprintf("%.2f",$frais_jour_retour),0,0,'r'); 
    $pdf->SetX(150);
    $pdf->Cell(0,5,"DH",0,0,'');
    $pdf->SetFont('Arial','U',10);
    $pdf->setY(161);
    $pdf->Cell(0,5,"FRAIS DE TRANSPORT",0,0,'r');
    $pdf->SetFont('Arial','',10);
    $pdf->setX(58);
    $pdf->Cell(0,5,"    :       ",0,0,'r');
    $pdf->SetX(120);
    $pdf->Cell(0,5,"=           ".sprintf("%.2f",$frais_transport),0,0,'r'); 
    $pdf->SetX(150);
    $pdf->Cell(0,5,"DH",0,0,'');
    $pdf->line(8,172,160,172);
    $pdf->SetFont('Arial','U',10);
    $pdf->setY(177);
    $pdf->setX(20);
    $pdf->Cell(0,5,"T   O   T   A   L",0,0,'r');
    $pdf->SetFont('Arial','',10);
    $pdf->SetX(120);
    $pdf->Cell(0,5,"=           ".sprintf("%.2f",$frais_total),0,0,'r'); 
    $pdf->setY(190);
    $pdf->SetFont('Arial','U',12);
    $pdf->Cell(170,5,"LE PRESENT ETAT S'ELEVANT A LA SOMME DE",0,0,'C');
    $pdf->setY(200);
    $pdf->SetFont('Arial','',10);
    $pdf->Cell(0,5,"*** ".convertNumberToWord($frais_total)." DIRHAMS"." ***",0,0,'');
    $pdf->setY(215);
    $pdf->SetX(20);
    $pdf->Cell(150,5,"VERIFIE ET TRANSMIS PAR LE CHEF DU SERVICE QUI ATTESTE QUE LES TAUX ",0,1,'');
    $pdf->SetX(8);
    $pdf->MultiCell(150,5,"MENTIONNES AU PRESENT ETAT SONT CONFORMES A L'ORDRE DE MISSION ET AUX TABLEAUX FIXANT LES INDEMNITES KILOMETRIQUES ET DE DEPLACEMENTS.",0,'');
    $pdf->Ln();
    $pdf->setY(235);
    $pdf->SetFont('Arial','U',12);
    $pdf->Cell(170,5,"ARRETE LE PRESENT P.V A LA SOMME DE",0,0,'C');
    $pdf->setY(245);
    $pdf->SetFont('Arial','',10);
    $pdf->Cell(0,5,"*** ".convertNumberToWord($frais_total)." DIRHAMS"." ***",0,0,'');
    $pdf->setY(260);
    $pdf->SetFont('Arial','U',12);
    $pdf->Cell(150,5,"LE DIRECTEUR GENERAL",0,0,'R');
}
else {
    $pdf->SetFont('Arial','U',10);
    $pdf->setY(141);
    $pdf->Cell(0,5,"DATE  D ' ARRIVEE",0,0,'r');
    $pdf->SetFont('Arial','',10);
    $pdf->setX(58);
    $pdf->Cell(0,5,"    :       ",0,0,'r');
    $pdf->setX(69);
    $pdf->Cell(0,5,$date_retour."       A       ".$heure_retour,0,0,'r'); 
    $pdf->SetX(120);
    $pdf->Cell(0,5,"=           ".sprintf("%.2f",$frais_jour_retour),0,0,'r'); 
    $pdf->SetX(150);
    $pdf->Cell(0,5,"DH",0,0,'');
    $pdf->SetFont('Arial','U',10);
    $pdf->setY(151);
    $pdf->Cell(0,5,"FRAIS DE TRANSPORT",0,0,'r');
    $pdf->SetFont('Arial','',10);
    $pdf->setX(58);
    $pdf->Cell(0,5,"    :       ",0,0,'r');
    $pdf->SetX(120);
    $pdf->Cell(0,5,"=           ".sprintf("%.2f",$frais_transport),0,0,'r'); 
    $pdf->SetX(150);
    $pdf->Cell(0,5,"DH",0,0,'');
    $pdf->line(8,165,160,165);
    $pdf->SetFont('Arial','U',10);
    $pdf->setY(170);
    $pdf->setX(20);
    $pdf->Cell(0,5,"T   O   T   A   L",0,0,'r');
    $pdf->SetFont('Arial','',10);
    $pdf->SetX(125);
    $pdf->Cell(0,5,"=           ".sprintf("%.2f",$frais_total),0,0,'r');
    $pdf->SetFont('Arial','',10);
    $pdf->setY(185);
    $pdf->SetFont('Arial','U',12);
    $pdf->Cell(170,5,"LE PRESENT ETAT S'ELEVANT A LA SOMME DE",0,0,'C');
    $pdf->setY(195);
    $pdf->SetFont('Arial','',10);
    $pdf->Cell(0,5,"*** ".convertNumberToWord($frais_total)." DIRHAMS"." ***",0,0,'');
    $pdf->setY(208);
    $pdf->SetX(20);
    $pdf->Cell(150,5,"VERIFIE ET TRANSMIS PAR LE CHEF DU SERVICE QUI ATTESTE QUE LES TAUX ",0,1,'');
    $pdf->SetX(8);
    $pdf->MultiCell(150,5,"MENTIONNES AU PRESENT ETAT SONT CONFORMES A L'ORDRE DE MISSION ET AUX TABLEAUX FIXANT LES INDEMNITES KILOMETRIQUES ET DE DEPLACEMENTS.",0,'');
    $pdf->Ln();
    $pdf->setY(230);
    $pdf->SetFont('Arial','U',12);
    $pdf->Cell(170,5,"ARRETE LE PRESENT P.V A LA SOMME DE",0,0,'C');
    $pdf->setY(240);
    $pdf->SetFont('Arial','',10);
    $pdf->Cell(0,5,"*** ".convertNumberToWord($frais_total)." DIRHAMS"." ***",0,0,'');
    $pdf->setY(255);
    $pdf->SetFont('Arial','U',12);
    $pdf->Cell(150,5,"LE DIRECTEUR GENERAL",0,0,'R'); 
}
$pdf->AddPage();
$pdf->SetFont('Arial','U',12);
$pdf->setY(8);
$pdf->Cell(80,15,'AL OMRANE-SOUSS MASSA',0,1,'C');
$pdf->setY(18);
$pdf->Cell(80,15,'DIVISION ADMINISTRATIVE',0,0,'');
$pdf->setX(115);
$pdf->Cell(80,15,'ORDONNANCE DE PAIEMENT',0,1,'');
$pdf->setY(26);
$pdf->Cell(80,15,'SERVICE DU PERSONNEL',0,0,'');
$pdf->setX(115);
$pdf->Cell(80,15,"No DE L'ORDONNANCE  :",0,1,'');
$pdf->setY(35);
$pdf->Cell(80,15,'RUBRIQUE BUDGETAIRE  :',0,0,'');
$pdf->SetFont('Arial','',11);
$pdf->setY(46);
$pdf->Cell(80,10,'BUDGET DE FONCTIONNEMENT',0,0,'');
$pdf->setY(52);
$pdf->Cell(80,10,"AUTRES CHARGES D'EXPLOITATION",0,0,'');
$pdf->setX(115);
$pdf->Cell(80,5,"I M P U T A T I O N",1,1,'C');
$pdf->setX(115);
$pdf->Cell(26,5,"CHAPITRE",1,0,'C');
$pdf->Cell(26,5,"ARTICLE",1,0,'C');
$pdf->Cell(28,5,"PARAGRAPHE",1,1,'C');
$pdf->setX(115);
$pdf->Cell(26,5,"III",1,0,'C');
$pdf->Cell(26,5,"3",1,0,'C');
$pdf->Cell(28,5,"1",1,1,'C');
$pdf->setX(115);
$pdf->Cell(26,5,"III",1,0,'C');
$pdf->Cell(26,5,"3",1,0,'C');
$pdf->Cell(28,5,"4",1,0,'C');
$pdf->setY(58);
$pdf->Cell(80,10,"TRANSPORT ET DEPLACEMENT",0,0,'');
$pdf->setY(64);
$pdf->Cell(80,10,"TRANSPORT ET DEPLACEMENT DU PERSONNEL",0,0,'');
$pdf->setY(70);
$pdf->Cell(80,10,"AU MAROC ET INDEMNITE KILOMETRIQUE",0,0,'');
$pdf->setY(80);
$pdf->SetFont('Arial','U',12);
$pdf->Cell(80,15,"EXERCICE",0,0,'');
$pdf->setX(32);
$pdf->SetFont('Arial','',12);
$pdf->Cell(80,15,"  :   ".date('Y'),0,0,'');
$pdf->SetFont('Arial','',10);
$pdf->setY(100);
$pdf->Cell(50,10,"BENEFICITAIRE",1,0,'C');
$pdf->Cell(147,10,$nom."  ".$prenom,1,1,'');
$pdf->Cell(50,10,"A D R E S S E",1,0,'C');
$pdf->Cell(147,10,"AL OMRANE / SOUSS MASSA",1,1,'');
$pdf->Cell(50,10,"OBJET DE LA DEPENSE",1,0,'C');
$pdf->Cell(147,10,"FRAIS DE DEPLACEMENT ET INDEMNITE KILOMETRIQUE DU MOIS DE ".$month." ".$year,1,1,'');
$pdf->Cell(50,10,"PIECES JUSTIFICATIVES",1,0,'C');
$pdf->Cell(147,10,"O.M No    ".$num,1,1,'');
$pdf->SetFont('Arial','',11);
$pdf->setY(150);
$pdf->setX(40);
$pdf->Cell(130,10,"- FRAIS  DE  DEPLACEMENT     :     ".sprintf("%.2f",$frais_deplacement),1,1,'');
$pdf->setX(40);
$pdf->Cell(130,10,"- INDEMNITE KILOMETRIQUE    :     ".sprintf("%.2f",$indemnite),1,1,'');
$pdf->setX(40);
$pdf->Cell(130,10,"- T O T A L                                    :     ".sprintf("%.2f",$frais_total),1,1,'');
$pdf->line(8,191,200,191);
$pdf->setY(190);
$pdf->SetFont('Arial','U',12);
$pdf->Cell(170,15,"VIREMENT BANCAIRE",0,0,'C');
$pdf->SetFont('Arial','',10);
$pdf->SetY(202);
$pdf->SetX(20);
$pdf->Cell(90,10,"BANQUE   :   ".$bank."/AGADIR",0,0,'');
$pdf->SetX(115);
$pdf->Cell(100,10,"CPTE    :   ".$rib,0,0,'');
$pdf->line(8,215,200,215);
$pdf->setY(235);
$pdf->SetFont('Arial','U',12);
$pdf->Cell(170,5,"ARRETE LE PRESENT P.V A LA SOMME DE :",0,0,'C');
$pdf->setY(245);
$pdf->SetFont('Arial','',10);
$pdf->Cell(0,5,"*** ".convertNumberToWord($frais_total)." DIRHAMS"." ***",0,0,'');
$pdf->setY(260);
$pdf->SetFont('Arial','U',12);
$pdf->Cell(150,5,"LE DIRECTEUR GENERAL",0,0,'R');
$pdf->Output();
$pdf->Output();

?>