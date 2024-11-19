<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <?php
    // Initialisation de la variable pseudo pour éviter les erreurs lors du premier chargement
    $pseudo = isset($_POST['pseudo']) ? htmlspecialchars($_POST['pseudo']) : '';
    ?>
    <br>
    <form action="" method="POST">
        <label for="pseudo">Pseudo:</label>
        <input type="text" id="pseudo" name="pseudo" required><br><br>
        
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required><br><br>
        
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required><br><br>

        <input type="submit" value="Send">
    </form>
    <?php
    // Vérification si le formulaire a été soumis
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Récupération et validation des données
        $email = isset($_POST['email']) ? $_POST['email'] : '';
        $password = isset($_POST['password']) ? $_POST['password'] : '';

        // Vérification de l'email
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            // Validation du mot de passe
            if (strlen($password) <= 8) {
                echo "The password is too short.";
            } else {
                echo "Welcome, $pseudo!";
            }
        } else {
            echo "Your email address is not valid.";
        }
    }
    ?>
</body>
</html>
