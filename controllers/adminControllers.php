<?php
namespace App\controllers;

use App\Modeles\category;
use App\Modeles\product;
use App\Modeles\role;
use App\Modeles\user;
use App\Utils\abstractController;

class adminControllers extends abstractController
{
    /**
     * Rôle : Générer et afficher le template de page d’accueil administrateur
     */
    function displayAdminHomePage(){
        return $this->render("admin/accueil_admin");
    }
    /**
     * Rôle : Générer et afficher la liste des produits pour l'administrateur
     */
    function displayAdminProductsList(){
        $product = new product();
        $productsList = $product->listEtendue(["is_active"=>1]);
        return $this->render("admin/list_products_admin",[
            "productsList" => $productsList
        ]);
    }
    /**
     * Rôle : Générer et afficher le formulaire d’ajout d’un article
     */
    function displayAdminProductAddForm(){
        $category = new category();
        $categoriesList = $category->listEtendue();
        return $this->render("admin/form_add_product_admin",[
            "categoriesList" => $categoriesList
        ]);
    }
    /**
     * Rôle : Enregistrer l’article dans la BDD, générer et afficher la liste des articles
     */
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
    /**
     * Rôle : Générer et afficher le formulaire de modification d'un article
     * @param $_GET : id : id du produit à modifier
     */
    function displayAdminProductModifForm($id){
        $product = new product($id);
        $category = new category();
        $categoriesList = $category->listEtendue();
        return $this->render("admin/form_modif_product_admin",[
            "product" => $product,
            "categoriesList" => $categoriesList
        ]);
    }
    /**
     * Rôle : Mettre à jour l’article dans la BDD, générer et afficher la liste des articles
     * @param $_POST : name (nom du produit), price (prix du produit), image (nom du fichier), description (description du produit), is_dispo (booléen : true si dispo, false sinon), is_menu_product (booléen : true si en menu, false sinon)
     * @param $_GET : id int : id du produit à modifier
     */
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
    /**
     * Rôle : Modifier le statut de l'article en inactif
     * @param $_GET : id : id du produit à rendre inactif
     */
    function deleteProduct($id){
        $product = new product($id);
        $product->is_active = 0;
        $product->update();
        return $this->redirectToRoute("/admin/products");
    }
    /**
     * Rôle : Générer et afficher la liste des utilisateurs
     */
    function displayAdminUsersList(){
        $user = new user();
        $usersList = $user->listEtendue(["is_active"=>1]);
        return $this->render("admin/list_users_admin",[
            "usersList" => $usersList
        ]);
    }
    /**
     * Rôle : Générer et afficher le formulaire d’ajout d’un utilisateur
     */
    function displayAdminUserAddForm(){
        $role = new role();
        $listRole = $role->listEtendue();
        return $this->render("admin/form_add_user_admin", [
            "listRole" => $listRole
        ]);
    }
    /**
     * Rôle : Enregistrer l'utilisateur dans la BDD, générer et afficher la liste des articles
     */
    function saveUser(){
        $user = new user();
        $user->loadFromTab($_POST);
        if ($_POST["new-password"] === $_POST["new-password-verification"]) {
            $user->set("password", $_POST["new-password"]);
            $user->is_active = 1;
        }
        $user->insert();
        return $this->redirectToRoute("/admin/users");
    }
    /**
     * Rôle : Générer et afficher le formulaire de modification de l’utilisateur
     * @param $_GET : id : id de l'utilisateur à modifier
     */
    function displayAdminUserModifForm($id){
        $role = new role();
        $listRole = $role->listEtendue();
        $user = new user($id);
        return $this->render("admin/form_modif_user_admin",[
            "user" => $user,
            "listRole" => $listRole
        ]);
    }
    /**
     * Rôle : Mettre à jour l’utilisateur, générer et afficher la liste des utilisateurs
     * @param $_POST : name (nom de l'utilisateur), forename (prénom de l'utilisateur), role (rôle de l'utilisateur admin, preparateur, hote)
     * @param $_GET : id : id de l'utilisateur à mettre à jour
     */
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
    /**
     * Rôle : Modifier le statut de l'utilisateur en inactif
     * @param $_GET : id : l'id de l'utilisateur à supprimer
     */
    function deleteUser($id){
        $user = new user($id);
        $user->is_active = 0;
        $user->update();
        return $this->redirectToRoute("/admin/users");
    }
}
