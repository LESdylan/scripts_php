<?php
$entree = [
    "bonjour !",
    "Voici",
    "un",
    "tableau",
    "de 5 éléments."
];
$masque1 = "/u/"; # on va rechercher la lettre u
$sortie1 = preg_grep($masque1,$entree);
# le tableau de sortie contient les éléments 
# du tableau d'entrée qui contiennent la lettre u
print_r($sortie1);
/* Affiche :
Array
(
    [0] => bonjour !
    [2] => un
    [3] => tableau
)
Remarquez l'absence de l'élément d'indice 1 (Voici) qui ne contient pas de u
*/
$masque2 = "/^[A-Z]/"; # on va rechercher les éléments commençant par une majuscule
$sortie2 = preg_grep($masque2,$entree);
# le tableau de sortie contient les éléments
# du tableau d'entrée qui commencent par une majuscule
print_r($sortie2);
/* Affiche :
Array
(
    [1] => Voici
)
*/
$masque3 = "/\W$/"; # on va rechercher les éléments terminant par un non-mot
$sortie3 = preg_grep($masque3,$entree);
# le tableau de sortie contient les éléments
# du tableau d'entrée qui terminent par un symbole
print_r($sortie3);
/* Affiche :
Array
(
    [0] => bonjour !
    [4] => de 5 éléments.
)
Remarquez le dernier caractère sur chaque ligne, qui est un symbole de ponctuation
*/
$masque4 = "/n/"; # on va rechercher les éléments contenant la lettre n
$sortie4 = preg_grep($masque4,$entree,PREG_GREP_INVERT);
# le tableau de sortie contient les éléments
# du tableau d'entrée qui ne contiennent pas la lettre n
# Remarquez l'utilisation de l'option d'inversion (le 3ème paramètre de la fonction)
print_r($sortie4);
/* Affiche :
Array
(
    [1] => Voici
    [3] => tableau
)
*/
?>