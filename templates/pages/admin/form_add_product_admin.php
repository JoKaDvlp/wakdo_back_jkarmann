<?php

/*
    Template de page : formulaire d'ajout d'un produit (administrateur)
    Paramètres : néant
*/

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin : ajout d'un produit</title>
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>
    <?php include_once "templates/fragments/header.php" ?>
    <main>
        <h1 class="text-center">Ajout du produit</h1>

        <div class="form-modif-container">
            <form action="/admin/products/save" method="POST" enctype="multipart/form-data">
                <div class="flex justify-between">
                    <div class="large-2">
                        <div class="flex justify-end align-center gap-10">
                            <label for="name">Nom</label>
                            <input type="text" name="name">
                        </div>
                        <div class="flex justify-end align-center gap-10">
                            <label for="price">Prix</label>
                            <input type="text" name="price">
                        </div>
                        <div class="flex justify-end align-center gap-10">
                            <label for="image">Image</label>
                            <input type="file" name="image">
                        </div>
                        <div class="flex justify-end align-center gap-10">
                            <label for="category">Catégorie</label>
                            <select name="category">
                                <?php foreach ($categoriesList as $category) {?>
                                    <option value="<?= htmlentities($category->id) ?>"><?= htmlentities($category->name) ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="large-2">
                        <div class="flex">
                            <label for="description">Description</label>
                            <textarea name="description" rows="10" class="width-100"></textarea>
                        </div>
                        <div class="flex justify-between">
                            <div class="flex align-center gap-10">
                                <input type="checkbox" name="is_dispo" checked>
                                <label for="is_dispo">Stock</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="button-container">
                    <button class="btn-yellow" type="submit">Enregistrer l'article</button>
                </div>
            </form>
        </div>
    </main>
</body>
</html>