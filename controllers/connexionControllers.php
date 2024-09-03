<?php
namespace App\controllers;

use App\Modeles\user;
use App\Utils\abstractController;
use App\Utils\session;

class connexionControllers extends abstractController
{
    function displayConnexionForm(){
        if (session::isconnected()) {
            return $this->redirectToRoute("/connexion");
        } else {
            return $this->render("form_connexion");
        }
    }
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
    function deconnectUser(){
        session::deconnect();
        return $this->redirectToRoute("/");
    }
}