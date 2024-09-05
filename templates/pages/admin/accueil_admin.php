<?php

/*

template de page : accueil administrateur
Paramètres : néant

*/

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Page d'accueil pour les administrateurs">
    <title>Accueil Admin</title>
    <link rel="stylesheet" href="/css/style.css">
    <link rel="shortcut icon" href="/public/images/logo.png" type="image/png">
</head>
<body>
    <?php include_once "templates/fragments/header.php" ?>
    <main>
        <h1 class="text-center">Bienvenue sur la page d'accueil de l'administration</h1>
        <div class="admin-btn-container flex justify-center">
            <a class="btn-yellow" href="/admin/products">Produits</a>
            <a class="btn-yellow" href="/admin/users">Utilisateurs</a>
        </div>
    </main>
</body>
</html>