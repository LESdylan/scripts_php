<?php
$iswall = true;

if ($iswall) {
    function marioWall($height) {
        $i = 1; // CURRENT LEVEL OF PYRAMID

        // EACH STEPA OF PYRAMID
        while ($i <= $height) {
            $left = 0;
            // LEFT SIDE OF PYRAMID
            while ($left < $height) {
                if ($left < $height - $i) {
                    // Afficher un espace si on est dans la zone de décalage
                    echo " ";
                } else {
                    // Afficher un "#" si on est dans la zone de briques
                    echo "#";
                }
                $left++;
            }

            // GAP
            echo "                     ";

            // RIGHT-SIDE OF PYRAMID
            $brick_right = 0;
            while ($brick_right < $i) {
                echo "#";
                $brick_right++;
            }

            // Passage à la ligne suivante
            echo "\n";
            $i++;
        }
    }
}

// Appeler la fonction si $iswall est vrai
if ($iswall) marioWall(5);
