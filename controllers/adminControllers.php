<?php
namespace App\controllers;

use App\Modeles\category;
use App\Modeles\product;
use App\Modeles\role;
use App\Modeles\user;
use App\Utils\abstractController;

class adminControllers extends abstractController
{
    function displayAdminHomePage(){
        return $this->render("admin/accueil_admin");
    }
    function displayAdminProductsList(){
        $product = new product();
        $productsList = $product->listEtendue(["is_active"=>1]);
        return $this->render("admin/list_products_admin",[
            "productsList" => $productsList
        ]);
    }
    function displayAdminProductAddForm(){
        $category = new category();
        $categoriesList = $category->listEtendue();
        return $this->render("admin/form_add_product_admin",[
            "categoriesList" => $categoriesList
        ]);
    }
    function saveProduct(){
        $product = new product();
        $product->loadFromTab($_POST);
        !isset($_POST["is_dispo"]) ? $product->is_dispo = 0 : $product->is_dispo = 1;
        $product->is_active = 1;
        $file = $_FILES["image"];
        if (!empty($file)) {
            $fileName = time() . "-" . uniqid();
            $fileName .= "." . pathinfo($file["name"], PATHINFO_EXTENSION);
            $path = $_SERVER['DOCUMENT_ROOT'] . "/public/images/".$product->getCategory("name");
            if (!is_dir($path)) mkdir($path, 0777, true);
            move_uploaded_file($file["tmp_name"],$path."/".$fileName);
            if (is_writable($path) && isset($product->image)) {
                if (unlink($_SERVER['DOCUMENT_ROOT'] . "/public/images".$product->image)) {
                    echo "Fichier supprimé avec succès.\n";
                } else {
                    echo "Erreur : Impossible de supprimer le fichier.\n";
                    die();
                }
            }
            $product->image = "/".$product->getCategory("name")."/".$fileName;
        }
        $product->insert();
        return $this->redirectToRoute("/admin/products");
    }
    function displayAdminProductModifForm($id){
        $product = new product($id);
        $category = new category();
        $categoriesList = $category->listEtendue();
        return $this->render("admin/form_modif_product_admin",[
            "product" => $product,
            "categoriesList" => $categoriesList
        ]);
    }
    function updateProduct($id){
        $product = new product($id);
        $product->loadFromTab($_POST);
        if (!isset($_POST["is_dispo"])) {
            $product->is_dispo = 0;
        } else {
            $product->is_dispo = 1;
        }
        $file = $_FILES["image"];
        if (!empty($file)) {
            $fileName = time() . "-" . uniqid();
            $fileName .= "." . pathinfo($file["name"], PATHINFO_EXTENSION);
            $path = $_SERVER['DOCUMENT_ROOT'] . "/public/images/".$product->getCategory("name");
            if (!is_dir($path)) mkdir($path, 0777, true);
            move_uploaded_file($file["tmp_name"],$path."/".$fileName);
            if (is_writable($path) && isset($product->image)) {
                if (unlink($_SERVER['DOCUMENT_ROOT'] . "/public/images".$product->image)) {
                    echo "Fichier supprimé avec succès.\n";
                } else {
                    echo "Erreur : Impossible de supprimer le fichier.\n";
                    die();
                }
            }
            $product->image = "/".$product->getCategory("name")."/".$fileName;
        }
        $product->update();
        return $this->redirectToRoute("/admin/products");
    }
    function deleteProduct($id){
        $product = new product($id);
        $product->is_active = 0;
        $product->update();
        return $this->redirectToRoute("/admin/products");
    }
    function displayAdminUsersList(){
        $user = new user();
        $usersList = $user->listEtendue(["is_active"=>1]);
        return $this->render("admin/list_users_admin",[
            "usersList" => $usersList
        ]);
    }
    function displayAdminUserAddForm(){
        $role = new role();
        $listRole = $role->listEtendue();
        return $this->render("admin/form_add_user_admin", [
            "listRole" => $listRole
        ]);
    }
    function saveUser(){
        $user = new user();
        $user->loadFromTab($_POST);
        if ($_POST["new-password"] === $_POST["new-password-verification"]) {
            $user->set("password", $_POST["new-password"]);
        }
        $user->insert();
        return $this->redirectToRoute("/admin/users");
    }
    function displayAdminUserModifForm($id){
        $role = new role();
        $listRole = $role->listEtendue();
        $user = new user($id);
        return $this->render("admin/form_modif_user_admin",[
            "user" => $user,
            "listRole" => $listRole
        ]);
    }

    function updateUser($id){
        $user = new user($id);
        $user->loadFromTab($_POST);
        if (!empty($_POST["old-password"]) && !empty($_POST["new-password"]) && !empty($_POST["new-password-verification"])) {
            if (!$user->updatePassword($_POST["old-password"], $_POST["new-password"], $_POST["new-password-verification"])) {
                return $this->redirectToRoute("/admin/users/modif/$id");
            }
        }
        $user->update();
        return $this->redirectToRoute("/admin/users");
    }
    function deleteUser($id){
        $user = new user($id);
        $user->is_active = 0;
        $user->update();
        return $this->redirectToRoute("/admin/users");
    }
}
