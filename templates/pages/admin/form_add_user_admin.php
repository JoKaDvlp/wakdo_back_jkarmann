<?php

/*
    Template de page : formulaire d'ajout d'un utilisateur (administrateur)
    Paramètres : 
                $listRole : liste des rôles
*/

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin : ajout d'un utilisateur</title>
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>
    <?php include_once "templates/fragments/header.php" ?>
    <main>
        <h1 class="text-center">Modification d'un utilisateur</h1>
        <div class="form-modif-user-container">
            <form action="/admin/users/save" method="POST">
                <div class="flex justify-between">
                    <div class="large-2">
                        <div class="flex justify-end align-center gap-10">
                            <label for="name">Nom</label>
                            <input type="text" name="name">
                        </div>
                        <div class="flex justify-end align-center gap-10">
                            <label for="forename">Prénom</label>
                            <input type="text" name="forename">
                        </div>
                        <div class="flex justify-end align-center gap-10">
                            <label for="identifier">Identifiant</label>
                            <input type="text" name="identifier">
                        </div>
                        <div class="flex align-center gap-10">
                            <label for="role">Rôle</label>
                            <select name="role">
                                <?php foreach ($listRole as $role) {?>
                                    <option value="<?= $role->id ?>"><?= $role->role ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="large-2">
                        <div class="flex justify-end align-center gap-10">
                            <label for="new-password">Mot de passe</label>
                            <input type="password" name="new-password">
                        </div>
                        <div class="flex justify-end align-center gap-10">
                            <label for="new-password-verification">Retapez votre mot de passe</label>
                            <input type="password" name="new-password-verification">
                        </div>
                    </div>
                </div>
                <div class="button-container">
                    <button class="btn-yellow" type="submit">Enregistrer l'utilisateur</button>
                </div>
            </form>
        </div>
    </main>
</body>
</html>