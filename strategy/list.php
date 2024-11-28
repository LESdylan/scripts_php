<?php

//// Fonction pour traiter les listes non ordonnées avec une hiérarchie correcte
//function bulletedList($markdown) {
//    $html = "";
//    $lines = explode("\n", $markdown);
//    $stack = [];  // Pile pour gérer les sous-listes imbriquées
//
//    foreach ($lines as $line) {
//        $line = trim($line);
//        
//        // Si la ligne commence par un tiret, c'est une liste non ordonnée
//        if (strpos($line, "-") === 0) {
//            // Enlever le tiret et les espaces
//            $item = trim(substr($line, 1));
//            
//            // Déterminer le niveau de la liste en fonction de l'indentation
//            $level = strlen($line) - strlen(ltrim($line));
//            while (count($stack) > $level) {
//                $html .= "</ul>\n";
//                array_pop($stack);
//            }
//            
//            if (count($stack) == $level) {
//                $html .= "<ul>\n";
//                array_push($stack, $level);
//            }
//
//            $html .= "  <li>$item</li>\n";
//        }
//    }
//    
//    // Fermer les balises <ul> restantes
//    while (count($stack) > 0) {
//        $html .= "</ul>\n";
//        array_pop($stack);
//    }
//
//    return $html;
//}

// Fonction pour traiter les listes ordonnées avec une hiérarchie correcte
function orderedList($markdown) {
    $html = "";
    $lines = explode("\n", $markdown);
    $stack = [];  // Pile pour gérer les sous-listes imbriquées
    print_r($lines);
    foreach ($lines as $line) {
        $line = trim($line);
        
        // Si la ligne commence par un numéro, c'est une liste ordonnée
        if (preg_match("/^\d+\./", $line)) {
            // Enlever le numéro et le point
            $item = preg_replace("/^\d+\.\s/", "", $line);
            
            // Déterminer le niveau de la liste en fonction de l'indentation
            $level = strlen($line) - strlen(ltrim($line));
            while (count($stack) > $level) {
                $html .= "</ol>\n";
                array_pop($stack);
            }
            
            if (count($stack) == $level) {
                $html .= "<ol>\n";
                array_push($stack, $level);
            }

            $html .= "  <li>$item</li>\n";
        }
    }
    
    // Fermer les balises <ol> restantes
    while (count($stack) > 0) {
        $html .= "</ol>\n";
        array_pop($stack);
    }

    return $html;
}

// Texte markdown d'exemple
$markdown = "
Liste ordonnée :
1. Premier élément
2. Deuxième élément
3. Troisième élément

Liste non ordonnée :
- Élément A
- Élément B
- Élément C

Liste ordonnée avec sous-listes :
1. Premier élément
  1.1. Sous-élément 1
  1.2. Sous-élément 2
2. Deuxième élément
  2.1. Sous-élément 1
    2.1.1 Sous-élément 2

Liste non ordonnée avec sous-listes :
- Élément A
  - Sous-élément A.1
  - Sous-élément A.2
- Élément B
  - Sous-élément B.1
  - Sous-élément B.2
    - Sous-élément C.3
        - Sous-élément D.4
";

// Générer le code HTML pour les listes ordonnées et non ordonnées
$html_content = "<html>\n<head>\n<title>Markdown to HTML</title>\n</head>\n<body>\n";

// Ajouter les listes converties en HTML
$html_content .= "<h2>Liste Ordonnée:</h2>\n" . orderedList($markdown);
//$html_content .= "<h2>Liste Non Ordonnée:</h2>\n" . bulletedList($markdown);

$html_content .= "</body>\n</html>";

// Enregistrer le contenu HTML dans un fichier index.html
file_put_contents("index.html", $html_content);

echo "Fichier index.html créé avec succès!";
?>
