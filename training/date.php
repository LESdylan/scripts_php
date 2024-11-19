<?php
$month = 12;
$year = 32768-1;
$day = 25;
var_dump(checkdate($month,$day,$year));


// Créer un objet DateTime avec la date et l'heure actuelles
$object = new DateTime();
echo "Current Date and Time: " . $object->format("Y-m-d H:i:s") . PHP_EOL; // Affiche la date et l'heure actuelles

// Créer un intervalle de 1 jour
$object1 = new DateInterval('P2W');

// Ajouter 1 jour à l'objet DateTime
$object->add($object1); // Modification de l'objet DateTime directement

// Afficher la nouvelle date après ajout de 1 jour
echo "Updated Date and Time: " . $object->format("Y-m-d H:i:s") . PHP_EOL; // Affiche la date mise à jour

//$object3 = new DateTimeZone();
//$object4 = new DateTimeImmutable();
// $object5 = new DateTimeInterface(); it is an interface
//$object7 = new DatePeriod();
//$object8 = new DateError();
//$object9 = new DateObjectError();
//$object10 = new DateRangeError();
//$object11 = new DateException();
//$object11 = new DateInvalidOperationException();
//$object11 = new DateInvalidTimeZoneException();
//$object11 = new DateMalformedIntervalStringException();
//$object11 = new DateMalformedPeriodStringException();
//$object2 = new DateMalformedStringException();
//

//$object->add($object1); // Ajoute 1 jour à l'objet DateTime actuel
//echo "Date after adding 1 day: " . $object->format("Y-m-d H:i:s") . PHP_EOL; // Affiche la nouvelle date après ajout
//
//// Affichage du timestamp actuel
//echo "Current timestamp: " . $object->getTimestamp() . PHP_EOL; // Affiche le timestamp Unix (secondes depuis le 1er janvier 1970)
//
//// Récupérer l'offset (décalage horaire) de la timezone
//echo "Timezone offset: " . $object->getOffset() . " seconds" . PHP_EOL; // Décalage de la timezone en secondes
//
//// Récupérer le fuseau horaire
//echo "Timezone: " . $object->getTimezone()->getName() . PHP_EOL; // Nom du fuseau horaire
//
//// DateTimeImmutable - un objet DateTime qui ne peut pas être modifié
//$object4 = new DateTimeImmutable();
//echo "Immutable Date: " . $object4->format("Y-m-d H:i:s") . PHP_EOL; // Affiche une date immuable
//
//// DatePeriod - permet de générer une période de dates
//$start = new DateTime("2024-01-01");
//$end = new DateTime("2024-01-10");
//$interval = new DateInterval("P1D");
//$period = new DatePeriod($start, $interval, $end);
//
//// Afficher chaque date dans la période
//foreach ($period as $date) {
//    echo $date->format("Y-m-d") . PHP_EOL;
//}
//
//// Affichage d'un timestamp
//echo "Current timestamp from DateTime object: " . date("Y-m-d H:i:s", $object->getTimestamp()) . PHP_EOL;
//
//try {
//    // Par exemple, création d'une DateTime avec une date invalide
//    $invalidDate = new DateTime('invalid date');
//} catch (Exception $e) {
//    echo "Exception caught: " . $e->getMessage() . PHP_EOL;
//}
//
//$object6 = new DateInterval('P2D'); // Intervalle de 2 jours
//date_add($object, $object6); // Ajoute 2 jours à l'objet DateTime
//echo "Date after adding 2 days: " . $object->format("Y-m-d H:i:s") . PHP_EOL; // Affiche la nouvelle date
//
?>
<p>examples of relative Parts</p>
<?php
// Each set of intervals is equal.
$i = new DateInterval('P1D');
$i = DateInterval::createFromDateString('1 day');

$i = new DateInterval('P2W');
$i = DateInterval::createFromDateString('2 weeks');

$i = new DateInterval('P3M');
$i = DateInterval::createFromDateString('3 months');

$i = new DateInterval('P4Y');
$i = DateInterval::createFromDateString('4 years');

$i = new DateInterval('P1Y1D');
$i = DateInterval::createFromDateString('1 year + 1 day');

$i = new DateInterval('P1DT12H');
$i = DateInterval::createFromDateString('1 day + 12 hours');

$i = new DateInterval('PT3600S');
$i = DateInterval::createFromDateString('3600 seconds');
?>