<?php
namespace App\Modeles;

use App\Utils\_model;

class order_composition extends _model
{
    protected $table = "order_composition";
    protected $fields = ["order_detail", "product", "menu_size", "beverage_size", "side_menu", "beverage_menu", "sauce_menu", "quantity"];
    
    protected $links = ["order_detail"=>"order_detail", "product"=>"product", "menu_size"=>"menu_size", "beverage_size"=>"beverage_size", "side_menu"=>"product", "beverage_menu"=>"product", "sauce_menu"=>"product",];


    // Méthodes

    function loadOrderComposition($composition){
        // Rôle : Charger l'objet courant à partir d'un tableau
        // Paramètres : tableau de données stockant la valeur associée à un champ
        // Retour : néant

        $this->product = $composition["article"];
        if ($composition["category"] === "menus") {
            $this->menu_size = $composition["size"];
            $this->beverage_size = null;
            $this->side_menu = $composition["side"];
            $this->beverage_menu = $composition["beverage"];
            $this->sauce_menu = $composition["sauce"];
            $this->quantity = 1;
        } elseif ($composition["category"] === "boissons") {
            $this->menu_size = null;
            $this->beverage_size = $composition["size"];
            $this->side_menu = null;
            $this->beverage_menu = null;
            $this->sauce_menu = null;
            $this->quantity = $composition["qty"];
        } else {
            $this->menu_size = null;
            $this->beverage_size = null;
            $this->side_menu = null;
            $this->beverage_menu = null;
            $this->sauce_menu = null;
            $this->quantity = $composition["qty"];
        }
    }
}
