<?php

/*
    Template de page : Liste des produit (administrateur)
    Paramètres :
                $productsList : liste des produits actifs
*/

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Liste des produits pour l'administrateur">
    <title>Admin : Liste des produits</title>
    <link rel="stylesheet" href="/css/style.css">
    <link rel="shortcut icon" href="/public/images/logo.png" type="image/png">
</head>
<body>
    <?php include_once "templates/fragments/header.php" ?>
    <main>
        <h1 class="text-center mb-50">Liste des produits</h1>
        <div class="width-70 margin-auto flex justify-end">
            <a href="/admin/products/add" class="btn-yellow">Ajouter un article</a>
        </div>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Nom</th>
                        <th>Prix</th>
                        <th>Image</th>
                        <th>est dispo ?</th>
                        <th>Category</th>
                        <th>est en Menu ?</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($productsList as $product) {
                    ?>
                        <tr>
                            <td><?= htmlentities($product->name) ?></td>
                            <td><?= htmlentities($product->price) ?> €</td>
                            <td><?= htmlentities($product->image) ?></td>
                            <td><?= htmlentities($product->is_dispo) ? "oui" : "non" ?></td>
                            <td><?= htmlentities($product->getTarget("category")->name) ?></td>
                            <td><?= htmlentities($product->is_menu_product ? "oui" : "non") ?></td>
                            <td>
                                <a href="/admin/products/modif/<?=htmlentities($product->id)?>">Modifier</a>
                                <a href="/admin/products/delete/<?=htmlentities($product->id)?>">Supprimer</a>
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