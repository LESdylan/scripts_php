<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Image</title>
</head>
<body>
    <form action="" method="post" enctype="multipart/form-data">
        <label for="image">Choisir une image :</label>
        <input type="file" name="photo" id="image" required>
        <button type="submit" name="submit">Envoyer</button>
    </form>

    <?php
    if (isset($_POST['submit']) && isset($_FILES['photo'])) {
        // Informations sur le fichier
        $image_name = $_FILES['photo']['name']; // Nom original du fichier
        $image_tmp_name = $_FILES['photo']['tmp_name']; // Chemin temporaire
        $image_error = $_FILES['photo']['error']; // Code d'erreur
        $image_size = $_FILES['photo']['size']; // Taille du fichier

        // Dossier de destination
        $upload_dir = "uploads/";

        // Vérification si le dossier existe, sinon le créer
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true); // Crée le dossier avec permissions
        }

        // Vérification des erreurs
        if ($image_error === 0) {
            // Limite de taille (par exemple, 2 Mo)
            $max_size = 2 * 1024 * 1024; // 2 Mo
            if ($image_size > $max_size) {
                echo "Le fichier est trop volumineux. La taille maximale est de 2 Mo.";
            } else {
                // Vérification du type de fichier
                $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];
                $image_ext = strtolower(pathinfo($image_name, PATHINFO_EXTENSION)); // Extension du fichier

                if (in_array($image_ext, $allowed_types)) {
                    // Éviter les collisions de noms
                    $new_image_name = uniqid("IMG_", true) . "." . $image_ext;
                    $destination = $upload_dir . $new_image_name;

                    // Déplacer le fichier vers le dossier final
                    if (move_uploaded_file($image_tmp_name, $destination)) {
                        echo "L'image a bien été enregistrée sous le nom : $new_image_name";
                    } else {
                        echo "Une erreur est survenue lors de l'enregistrement de l'image.";
                    }
                } else {
                    echo "Seuls les fichiers JPG, JPEG, PNG et GIF sont autorisés.";
                }
            }
        } else {
            echo "Une erreur est survenue lors du téléchargement de l'image.";
        }
    }
    ?>
</body>
</html>
