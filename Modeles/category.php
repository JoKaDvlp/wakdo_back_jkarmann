<?php
namespace App\Modeles;

use App\Utils\_model;

class category extends _model
{
    protected $table = "category";
    protected $fields = ["name", "image"];


    // Méthodes

    function listCategories(){
        // Rôle : créer une liste de catégories
        // Paramètres : néant
        // Retour : Liste des catégories

        $categoriesList = $this->listEtendue();
        $categoriesListJson = [];
        foreach ($categoriesList as $category) {
            $categoriesListJson[] = [
                "id" => $category->id,
                "title" => $category->name,
                "image" => $category->image
            ];
        }

        return $categoriesListJson;
    }
}
