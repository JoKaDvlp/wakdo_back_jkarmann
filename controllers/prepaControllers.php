<?php
namespace App\controllers;

use App\Modeles\order_detail;
use App\Utils\abstractController;

class prepaControllers extends abstractController
{
    /**
     * Rôle : Générer et afficher la page de prise de commande
     */
    function displayPrepaOrdersList(){
        $order = new order_detail();
        $ordersList = $order->listEtendue(["status"=>"en cours"]);
        return $this->render("preparateur/list_orders_prepa",[
            'ordersList' => $ordersList
        ]);
    }
    /**
     * Rôle : Modifier le statut de la commande en cours en “A préparer” puis générer et afficher la liste des commande en cours
     * @param $_GET : id : l'id de la commande à modifier
     */
    function changePrepaOrderStatus($id){
        $order = new order_detail($id);
        $order->status = "préparée";
        $order->update();
        return;
    }
    /**
     * Rôle : Extrait les données de la commande pour les afficher
     * @param $_GET : id : l'id de la commande à extraire
     */
    function extractOrderDetails($id){
        $order = new order_detail();
        $orderList = $order->extractOrderDetailsJSON($id);

        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($orderList);
    }
}