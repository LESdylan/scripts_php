<?php

$mysqli = new mysqli('localhost', 'root', 'MO3848seven_36', 'normals', 3306);

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}
echo "Connected successfully<br>";

// Requête pour sélectionner tous les utilisateurs
$result = $mysqli->query("SELECT * FROM normals LIMIT 4000");

if ($result->num_rows > 0) {
    // Début du tableau
    echo "<table border='1'>";
    echo "<tr><th>Longitude</th><th>Latitude</th><th>0m</th><th>50m</th><th>100m</th></tr>"; // En-tête du tableau

    // Affichage des données de chaque ligne
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($row["longitude"]) . "</td>"; // Longitude
        echo "<td>" . htmlspecialchars($row["latitude"]) . "</td>";  // Latitude
        echo "<td>" . htmlspecialchars($row["0m"]) . "</td>"; // surface
        echo "<td>" . htmlspecialchars($row["50m"]) . "</td>"; // surface
        echo "<td>" . htmlspecialchars($row["100m"]) . "</td>"; // surface
        echo "</tr>";
    }
    echo "</table>"; // Fin du tableau
} else {
    echo "Aucun résultat trouvé.";
}

$mysqli->close();
?>
