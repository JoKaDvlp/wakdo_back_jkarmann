<?php
namespace App\Utils;

// Classe session : classe de gestion de la session

use App\Modeles\user;

class session {

    static function activation() {
        // Rôle : Démarrer la session
        // Paramètres : néant
        // Retour : True si la session est connecté, false sinon

        // Démarrer le mécanisme
        session_start();

        // Si un utilisateur est connecté
        if (self::isconnected()){
            global $utilisateurConnecte;
            $utilisateurConnecte = new user(self::idconnected());
        }

        // Retourner si on est connecté ou pas
        return self::isconnected();
    }

    static function isconnected() {
        // Rôle : Dire si il y a une connexion active
        // Paramètres : néant
        // Retour : true si un utilisateur est connecté, false sinon

        return ! empty($_SESSION["id"]);
    }

    static function idconnected() {
        // Rôle : Renvoyer l'id de l'utilisateur connecté
        // Paramètres : néant
        // Retour : L'id de l'utilisateur connecté ou 0

        if (self::isconnected()) {
            return $_SESSION["id"];
        } else {
            return 0;
        }
    }

    static function userconnected() {
        // Rôle : retourner un objet utilisateur chargé à parti de l'idconnected
        // Paramètres : néant
        // Retour : Un objet utilisateur chargé

        if(self::isconnected()) {
            return new user(self::idconnected());
        } else {
            return new user();
        }
    }

    static function deconnect() {
        // Rôle : déconnecter la session courante
        // Paramètres : néant
        // Retour : true

        $_SESSION["id"] = 0;
    }

    static function connect($id) {
        // Rôle : connecter l'utilisateur
        // Paramètres :
        //      $id : id de l'utilisateur à connecter
        // Retour : true

        $_SESSION["id"] = $id;
    }
}