<?php

/*
    Template de page : formulaire de modification d'un utilisateur (administrateur)
    Paramètres :
                $user : objet utilisateur à afficher
                $listRole : liste des rôles
*/

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Formulaire de modification d'un utilisateur par l'administrateur">
    <title>Admin : modification de l'utilisateur <?= $user->id ?></title>
    <link rel="stylesheet" href="/css/style.css">
    <link rel="shortcut icon" href="/public/images/logo.png" type="image/png">
</head>
<body>
    <?php include_once "templates/fragments/header.php" ?>
    <main>
        <h1 class="text-center">Modification d'un utilisateur</h1>
        <div class="form-modif-user-container">
            <form action="/admin/users/update/<?=htmlentities($user->id)?>" method="POST">
                <div class="flex justify-between">
                    <div class="large-2">
                        <div class="flex align-center gap-10">
                            <label for="name">Nom</label>
                            <input type="text" name="name" value="<?= htmlentities($user->name) ?>">
                        </div>
                        <div class="flex align-center gap-10">
                            <label for="forename">Prénom</label>
                            <input type="text" name="forename" value="<?= htmlentities($user->forename) ?>">
                        </div>
                        <div class="flex align-center gap-10">
                            <label for="identifier">Identifiant</label>
                            <input type="text" name="identifier" value="<?= htmlentities($user->identifier) ?>">
                        </div>
                        <div class="flex align-center gap-10">
                            <label for="role">Rôle</label>
                            <select name="role">
                                <?php foreach ($listRole as $role) {?>
                                    <option value="<?= $role->id ?>" <?= $role->role === $user->getRole() ? "selected" : "" ?>><?= htmlentities($role->role) ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="large-2">
                        <div class="flex align-center gap-10">
                            <label for="old-password">Ancien mot de passe</label>
                            <input type="password" name="old-password">
                        </div>
                        <div class="flex align-center gap-10">
                            <label for="new-password">Nouveau mot de passe</label>
                            <input type="password" name="new-password">
                        </div>
                        <div class="flex align-center gap-10">
                            <label for="new-password-verification">Retapez votre nouveau mot de passe</label>
                            <input type="password" name="new-password-verification">
                        </div>
                    </div>
                </div>
                <div class="button-container">
                    <button class="btn-yellow" type="submit">Modifier l'utilisateur</button>
                </div>
            </form>
        </div>
    </main>
</body>
</html>