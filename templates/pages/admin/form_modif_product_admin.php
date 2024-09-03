<?php

/*
    Template de page : formulaire de modification d'un produit (administrateur)
    Paramètres : 
            $product : objet produit à modifier
            $categoriesList : liste des catégories
*/

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin : modification du produit <?= $product->id ?></title>
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>
    <?php include_once "templates/fragments/header.php" ?>
    <main>
        <h1 class="text-center">Modification du produit</h1>
        <div class="form-modif-container">
            <form action="/admin/products/update/<?=htmlentities($product->id)?>" method="POST" enctype="multipart/form-data">
                <div class="flex justify-between">
                    <div class="large-2">
                        <div class="flex justify-end align-center gap-10">
                            <label for="name">Nom</label>
                            <input type="text" name="name" value="<?= htmlentities($product->name) ?>">
                        </div>
                        <div class="flex justify-end align-center gap-10">
                            <label for="price">Prix</label>
                            <input type="text" name="price" value="<?= htmlentities($product->price) ?>">
                        </div>
                        <div class="flex justify-end align-center gap-10">
                            <label for="image">Image</label>
                            <input type="file" name="image">
                        </div>
                        <div class="flex justify-end align-center gap-10">
                            <label for="category">Catégorie</label>
                            <select name="category">
                                <?php foreach ($categoriesList as $category) {?>
                                    <option value="<?= htmlentities($category->id) ?>" <?= $category->id === $product->getCategory("id") ? "selected" : "" ?>><?= htmlentities($category->name) ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="large-2">
                        <div class="flex">
                            <label for="description">Description</label>
                            <textarea name="description" rows="10" class="width-100"><?= htmlentities($product->description) ?></textarea>
                        </div>
                        <div class="flex align-center gap-10">
                            <input type="checkbox" name="is_dispo" <?= $product->is_dispo ? "checked" : "" ?>>
                            <label for="is_dispo">Stock</label>
                        </div>
                    </div>
                </div>
                <div class="button-container">
                    <button class="btn-yellow" type="submit">Modifier l'article</button>
                </div>
            </form>
        </div>
    </main>
</body>
</html>