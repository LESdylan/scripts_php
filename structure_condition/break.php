<?php
$tab = [1,2,3,4,5,6,7];
foreach($tab as $v)
{
    if(!is_int($v))break;
    echo $v."\n";
}


var_dump("\n____________________________\n");

for($a=1 ; $a<=10 ; $a++){ # on prévoit 10 itérations à l'aide de la variable $a
	$d = 10 ; # on initialise une variable $d
	do {
		echo $d,'-',$a,' = ',$d-$a,"\n" ; # on affiche la soustraction de $d par $a
		$d-- ; # on décrémente $d
	} while($d>$a) ; # on répète cette boucle tant que $d est supérieur à $a
echo "-----------------\n" ; # on affiche une ligne de séparation
}


var_dump("\n_____________________\n");
for($a=1 ; $a<=10 ; $a++){ # on prévoit 10 itérations à l'aide de la variable $a
	$d = 10 ; # on initialise une variable $d
	do {
		if($a>=$d){
			break 1; # on sort de la boucle while quand $a et $d valent 10
			# on continue donc la boucle for et affiche la ligne de séparation
		}
		echo $d,'-',$a,' = ',$d-$a,"\n" ; # on affiche la soustraction de $d par $a
		$d-- ; # on décrémente $d
	} while($d>$a) ; # on répète cette boucle tant que $d est supérieur à $a
	echo "-----------------\n" ; # on affiche une ligne de séparation
}


var_dump("\n=====================\n");
for($a=1 ; $a<=10 ; $a++){ # on prévoit 10 itérations à l'aide de la variable $a
	$d = 10 ; # on initialise une variable $d
	do {
		if($a>=$d){
			break 2; # on sort de la boucle for quand $a et $d valent 10
			# on n'affiche plus la ligne de séparation
		}
		echo $d,'-',$a,' = ',$d-$a,"\n" ; # on affiche la soustraction de $d par $a
		$d-- ; # on décrémente $d
	} while($d>$a) ; # on répète cette boucle tant que $d est supérieur à $a
	echo "-----------------\n" ; # on affiche une ligne de séparation
}
