<?php
	// Vérifie si le paramètre "id" est présent dans l'URL
	if(isset($_GET['id'])) {
		// Vérifie si l'id afficher dans l'URL correspond à la valeur 1
		if($_GET['id'] === '1') {
			// Affiche la div 1
			echo '<div><p>Contenu de la div 1</p></div>';
		} else if($_GET['id'] === '2')  {
			// Affiche la div 2
			echo '<div ><p>Contenu de la div 2</p></div>';
	
        } 
}
?>