<?php
namespace App\controllers;

use App\Modeles\category;
use App\Modeles\order_composition;
use App\Modeles\order_detail;
use App\Modeles\product;
use App\Utils\abstractController;

class apiControllers extends abstractController
{
    function addOrder(){
        header("Access-Control-Allow-Origin: http://exam-front.jkarmann.mywebecom.ovh");
        header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
        header("Access-Control-Allow-Headers: Content-Type, Authorization");

        // Pour les requêtes préliminaires OPTIONS, il faut juste répondre avec les en-têtes et sortir
        if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
            // On arrête l'exécution ici pour les requêtes préliminaires
            exit(0);
        }
        $bodyRaw = file_get_contents("php://input");
        $body = json_decode($bodyRaw,true);
        $orderDetail = new order_detail($body["order_id"]);
        $orderDetail->loadOrderDetail($body);
        $orderDetail->update();
        $orderComposition = new order_composition();
        foreach ($body["item"] as $composition) {
            $orderComposition->loadOrderComposition($composition);
            $orderComposition->order_detail = $orderDetail->id;
            $orderComposition->insert();
        }

        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(['message'=>'ajouté avec succés']);
    }

    function listProducts(){
        header("Access-Control-Allow-Origin: http://exam-front.jkarmann.mywebecom.ovh");
        header("Access-Control-Allow-Methods: GET, OPTIONS");
        header("Access-Control-Allow-Headers: Content-Type, Authorization");

        // Pour les requêtes préliminaires OPTIONS, il faut juste répondre avec les en-têtes et sortir
        if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
            // On arrête l'exécution ici pour les requêtes préliminaires
            exit(0);
        }
        $product = new product();
        $productsListJson = $product->listProductsByCategory();

        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($productsListJson);
    }

    function listCategories(){
        header("Access-Control-Allow-Origin: http://exam-front.jkarmann.mywebecom.ovh");
        header("Access-Control-Allow-Methods: GET, OPTIONS");
        header("Access-Control-Allow-Headers: Content-Type, Authorization");

        // Pour les requêtes préliminaires OPTIONS, il faut juste répondre avec les en-têtes et sortir
        if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
            // On arrête l'exécution ici pour les requêtes préliminaires
            exit(0);
        }
        $category = new category();
        $categoriesList = $category->listCategories();

        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($categoriesList);
    }

    function prepareOrder(){
        header("Access-Control-Allow-Origin: http://exam-front.jkarmann.mywebecom.ovh");
        header("Access-Control-Allow-Methods: GET, OPTIONS");
        header("Access-Control-Allow-Headers: Content-Type, Authorization");

        // Pour les requêtes préliminaires OPTIONS, il faut juste répondre avec les en-têtes et sortir
        if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
            // On arrête l'exécution ici pour les requêtes préliminaires
            exit(0);
        }
        
        $order = new order_detail();
        $order->extractLastOrder();
        $order->insert();
        $newOrderDetails = [
            "order_id" => $order->id,
            "order_number" => $order->order_number
        ];
        
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($newOrderDetails);
    }
}