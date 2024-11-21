<?php
/**
 * The purpose here is to convert the list of data into a proper file formatted as intended according to the chart.
 * the chart is : NOM\tPRENOM\t+33X XX XX XX XX
 * First we're gonna need to retrieve the datas from the list and
 */

$fichier = ["01.23.45.56.78, MARTIN, PIERRE",
"02.56.32.41.87, DURAND, MATHILDE",
"03.23.98.87.54, LOTARD, HENRI",
"04.32.45.65.10, GEAI, JACQUES",
"+333.21.65.98.01, JOYEUX, JEAN-PIERRE",
"01.65.87.41.20, TOULIN, MARIE",
"03.58.56.21.02, DE BIEN, NOEMIE"];

$match = '/(.*)()()/';
$replacement= '/\2\3\+331/'
var_dump(preg_replace($match,$replacement,$fichier));