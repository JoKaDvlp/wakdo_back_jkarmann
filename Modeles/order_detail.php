<?php
namespace App\Modeles;

use App\Utils\_model;
use PDO;

class order_detail extends _model
{
    protected $table = "order_detail";
    protected $fields = ["order_number", "date_time", "location", "table_number", "status"];

    // Methods
    function extractOrderDetailsJSON($idOrder){
        // Rôle : extraire les données de la commande
        // Paramètres : néant
        // Retour : tableau d'objets de la classe courante (indexés par l'id)
        $sql = "
        SELECT 
            oc.id,
            oc.quantity,
            c.name AS category,
            -- Informations sur le produit principal
            p.name AS product_name,
            p.price AS product_price,
            p.description AS product_description,
            
            -- Informations sur la taille du menu
            ms.menu_size AS menu_size,
            
            -- Informations sur la boisson
            bp.name AS beverage_name,
            bs.beverage_size AS beverage_size,
            
            -- Informations sur l'accompagnement
            sp.name AS side_name,
            
            -- Informations sur la sauce
            saucep.name AS sauce_name
            
        FROM 
            order_composition oc
        JOIN 
            product p ON oc.product = p.id
        JOIN
            category c ON p.category = c.id
        LEFT JOIN 
            menu_size ms ON oc.menu_size = ms.id
        LEFT JOIN 
            product bp ON oc.beverage_menu = bp.id
        LEFT JOIN 
            beverage_size bs ON oc.beverage_size = bs.id
        LEFT JOIN 
            product sp ON oc.side_menu = sp.id
        LEFT JOIN 
            product saucep ON oc.sauce_menu = saucep.id

        WHERE 
            oc.order_detail = :id;  -- Remplacez :order_id par l'ID de la commande spécifique
        ";

        $param[":id"] = $idOrder;

        $listProducts = $this->sqlToList($sql, $param);

        $listProductsJson = [];

        foreach ($listProducts as $object) {
            $listProductsJson[] = $object->prepareJSON();
        }
        
        return $listProductsJson;
    }

    function loadOrderDetail($tab){
                // Rôle : Charger un objet à partir d'un tableau
        // Paramètres : 
        //          $tab : tableau de données stockant la valeur associée à un champ
        // Retour : néant

        $this->order_number = $tab["order"];
        $this->date_time = date("Y-m-d H:i:s");
        $this->location = $tab["choix_lieu"];
        if (!empty($tab["service"])) {
            $this->table_number = $tab["service"];
        } else {
            $this->table_number = 0;
        }
        $this->status = "en cours";
    }

    function extractLastOrder(){
        // Rôle : extraire la dernière commande du jour de la base de données
        // Paramètres : néant
        // Retour : néant

        $sql = "SELECT `order_number`
        FROM $this->table
        WHERE DATE(date_time) = CURDATE()
        ORDER BY date_time DESC
        LIMIT 1";

        $req = $this->sqlExecute($sql);
        $result = $req->fetch(PDO::FETCH_ASSOC);

        // Si on a pas de résultat, on recommance les numéro de commande à 1
        if (empty($result)) {
            $this->order_number = 1;
            $this->date_time = date("Y-m-d H:i:s");
        } else {
            $this->order_number = $result["order_number"] + 1;
            $this->date_time = date("Y-m-d H:i:s");
        }
    }

}
