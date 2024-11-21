<?php 

$entree = [
    "bonjour !",
    "Voici",
    "un",
    "tableau",
    "de 5 éléments."
];
$capture = '/(i)/';
var_dump(preg_match($capture, $entree, PREG_GREP_INVERT));
