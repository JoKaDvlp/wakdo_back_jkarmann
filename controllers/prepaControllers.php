<?php
namespace App\controllers;

use App\Modeles\order_detail;
use App\Utils\abstractController;

class prepaControllers extends abstractController
{
    function displayPrepaOrdersList(){
        $order = new order_detail();
        $ordersList = $order->listEtendue(["status"=>"en cours"]);
        return $this->render("preparateur/list_orders_prepa",[
            'ordersList' => $ordersList
        ]);
    }

    function changePrepaOrderStatus($id){
        $order = new order_detail($id);
        $order->status = "préparée";
        $order->update();
        return;
    }

    function extractOrderDetails($id){
        $order = new order_detail();
        $orderList = $order->extractOrderDetailsJSON($id);

        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($orderList);
    }
}