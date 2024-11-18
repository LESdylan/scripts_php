<?php
for($a=1 ; $a<=10 ; $a++){ # on prévoit 10 itérations à l'aide de la variable $a
	for($d=10 ; $d>$a ; $d--){ # on boucle avec une variable $d
		# on répète cette boucle tant que $d est supérieur à $a
		if($d<5){
			continue 2; # on passe à l'itération suivante de la boucle extérieure quand $d est inférieur à 5
			# on n’affiche donc plus la ligne de séparation dans ce cas
		}
		echo $d,'-',$a,' = ',$d-$a,"\n" ; # on affiche la soustraction de $d par $a
	}
	echo "-----------------\n" ; # on affiche une ligne de séparation
}
?>