<?php

// Template de page de connexion

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Page de connexion à l'application de gestion de votre restaurant Wakdo">
    <title>Backdo : Connexion</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="shortcut icon" href="/public/images/logo.png" type="image/png">
</head>
<body>
    <?php include "templates/fragments/header.php" ?>
    <main>
        <form id="form-connexion" action="/connexion" method="POST" class="flex flex-column align-center">
            <div>
                <h1>Page de connexion</h1>
            </div>
            <div class="flex flex-column align-end">
                <div class="flex">
                    <label for="name">Votre identifiant</label>
                    <input type="text" name="name">
                </div>
                <div class="flex">
                    <label for="password">Votre mot de passe</label>
                    <input type="password" name="password">
                </div>
            </div>
            <div>
                <button class="btn-yellow" type="submit">Se connecter</button>
            </div>
        </form>
    </main>
</body>
</html>