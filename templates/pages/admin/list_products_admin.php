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
    <title>Admin : Liste des produits</title>
    <link rel="stylesheet" href="/css/style.css">
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
                            <td><?= $product->name ?></td>
                            <td><?= $product->price ?> €</td>
                            <td><?= $product->image ?></td>
                            <td><?= $product->is_dispo ? "oui" : "non" ?></td>
                            <td><?= $product->getTarget("category")->name ?></td>
                            <td><?= $product->is_menu_product ? "oui" : "non" ?></td>
                            <td>
                                <a href="/admin/products/modif/<?=$product->id?>">Modifier</a>
                                <a href="/admin/products/delete/<?=$product->id?>">Supprimer</a>
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