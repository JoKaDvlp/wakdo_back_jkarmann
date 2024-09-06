<?php
namespace App\controllers;

use App\Modeles\order_detail;
use App\Utils\abstractController;

class hostControllers extends abstractController
{
    /**
     * Rôle : Générer et afficher la liste des commandes
     */
    function displayHostOrdersList(){
        $order = new order_detail();
        $ordersList = $order->listEtendue(["status"=>"préparée"]);
        return $this->render("host/list_orders_host",[
            'ordersList' => $ordersList
        ]);
    }
    /**
     * Rôle : Modifier le statut de la commande en cours en “Remise” puis générer et afficher la liste des commande en cours
     * @param $_GET : id : id du produit à modifier
     */
    function changeHostOrderStatus($id){
        $order = new order_detail($id);
        $order->status = "remise";
        $order->update();
        return;
    }
    /**
     * Extrait les données de la commande pour les afficher
     * @param $_GET : id : l'id de la commande à extraire
     */
    function extractOrderDetails($id){
        $order = new order_detail();
        $orderList = $order->extractOrderDetailsJSON($id);

        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($orderList);
    }

    function displayHostOrderView(){
        return $this->render("host/add_order_host");
    }
}