<?php
namespace App\controllers;

use App\Modeles\order_detail;
use App\Utils\abstractController;

class hostControllers extends abstractController
{
    function displayHostOrdersList(){
        $order = new order_detail();
        $ordersList = $order->listEtendue(["status"=>"préparée"]);
        return $this->render("host/list_orders_host",[
            'ordersList' => $ordersList
        ]);
    }

    function changeHostOrderStatus($id){
        $order = new order_detail($id);
        $order->status = "remise";
        $order->update();
        return;
    }

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