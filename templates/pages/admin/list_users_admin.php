<?php

/*
    Template de page : Liste d'utilisateurs actif (administrateur)
    Paramètres :
                $usersList : liste des utilisateurs actifs
*/

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin : Liste des utilisateurs</title>
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>
<?php include_once "templates/fragments/header.php" ?>
    <main>
        <h1 class="text-center mb-50">Liste des utilisateurs</h1>
        <div class="width-70 margin-auto flex justify-end">
            <a href="/admin/users/add" class="btn-yellow">Ajouter un utilisateur</a>
        </div>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Nom</th>
                        <th>Prénom</th>
                        <th>Identifiant</th>
                        <th>Rôle</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($usersList as $user) {
                    ?>
                        <tr>
                            <td><?= htmlentities($user->name) ?></td>
                            <td><?= htmlentities($user->forename) ?></td>
                            <td><?= htmlentities($user->identifier) ?></td>
                            <td><?= htmlentities($user->getRole()) ?></td>
                            <td>
                                <a href="/admin/users/modif/<?=htmlentities($user->id)?>">Modifier</a>
                                <a href="/admin/users/delete/<?=htmlentities($user->id)?>">Supprimer</a>
                            </td>
                        </tr>
                    <?php
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </main>
</body>
</html>