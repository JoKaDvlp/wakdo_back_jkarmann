<?php

/*
    Template de page : page de prise de commandes (hote)
    Paramètres :
                $orderNumber : numéro de commande
*/

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Page d'ajout d'une commande par l'hôte d'accueil">
    <title>Ajouter commande</title>
    <link rel="stylesheet" href="/css/style.css">
    <link rel="shortcut icon" href="/public/images/logo.png" type="image/png">
</head>
<body>
    <?php include "templates/fragments/header.php" ?>
    <main>
        <iframe style="height: calc(100vh - 110px); width: 100vw; border: none" src="http://exam-front.jkarmann.mywebecom.ovh/">
        </iframe>
    </main>
    <script src="/js/app.js" defer></script>
</body>
</html>