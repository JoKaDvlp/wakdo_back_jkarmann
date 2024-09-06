<?php
namespace App\controllers;

use App\Modeles\user;
use App\Utils\abstractController;
use App\Utils\session;

class connexionControllers extends abstractController
{
    /**
     * Rôle : Générer et afficher le formulaire de connexion à l’application
     */
    function displayConnexionForm(){
        if (session::isconnected()) {
            return $this->redirectToRoute("/connexion");
        } else {
            return $this->render("form_connexion");
        }
    }
    /**
     * Rôle : En fonction du rôle de l’utilisateur, vérifier si l'utilisateur existe, comparer le mdp avec celui de la BDD puis  générer et afficher la page qui lui est associé
     * @param $_GET : userRole
     */
    function displayUserView(){
        if (session::isconnected()) {
            $user = new user(session::idconnected());
        } else {
            $name = $_POST["name"];
            $password = $_POST["password"];
            $user = new user();
            if ($user->verifierUtilisateur($name, $password)) {
                session::connect($user->id);
            } else {
                return $this->redirectToRoute("/");
            }
        }
        if ($user->getTarget("role")->role == "ADMIN") {
            return $this->redirectToRoute("/admin");
        } else if ($user->getTarget("role")->role == "PREPARATEUR") {
            return $this->redirectToRoute("/prepa");
        } else if ($user->getTarget("role")->role == "HOTE") {
            return $this->redirectToRoute("/host");
        } else {
            return $this->redirectToRoute("/");
        }
    }
    /**
     * Rôle : Deconnecte l'utilisateur en cours
     */
    function deconnectUser(){
        session::deconnect();
        return $this->redirectToRoute("/");
    }
}