<?php
namespace App\Modeles;

use App\Utils\_model;

class product extends _model
{
    protected $table = "product";
    protected $fields = ["name", "price", "image", "description", "is_dispo", "category", "is_active"];
    
    protected $links = ["category"=>"category"];

    function getCategory($fieldName){
        // Rôle : Retourne la valeur du champ (en paramètre) d'un objet Category
        // Paramètres :
        //              $fieldName : le nom de champ où l'on souhaite récupérer la valeur
        // Retour : La valeur du champ de l'objet category souhaité
        $categoryFieldValue = $this->getTarget("category")->$fieldName;
        return $categoryFieldValue;
    }

    function loadFromTab($tab){
        // Rôle : surcharge la méthode loadFromTab pour ajouter la gestion du fichier image
        if (empty($tab["image"])) {
            $tab["image"] = $this->image;
        }

        return parent::loadFromTab($tab);
    }

    function listProductsByCategory(){
        // Rôle : Créer une liste de produits par catégorie à partir d'une liste de produits
        // Paramètres : néant
        // Retour : Liste d'objets "produit" triés par catégorie

        $productsList = $this->listEtendue(["is_active"=>1]);
        $productsListByCategory = [];
        foreach ($productsList as $product) {
            $categoryName = $product->getCategory("name");
            $productsListByCategory[$categoryName][] = [
                "id" => $product->id,
                "nom" => $product->name,
                "prix" => (float)$product->price,
                "image" => "http://exam-back.jkarmann.mywebecom.ovh/public/images/".$product->image
            ];
        }
        return $productsListByCategory;
    }
}
