<?php

/**
 * Contrôleur de test
 */

namespace App\controllers;

use App\Modeles\order_detail;
use App\Modeles\product;
use App\Modeles\user;

class testControllers
{
    function test(){
        $product = new product();
        $productsListByCategory = $product->listProductsByCategory();
        print_r($productsListByCategory);
    }
}