<?php
namespace App\Utils;

// Class abstractController : class générique de gestion des controlleurs

class abstractController {

    protected function render($view, $parameter = []){
        // Rôle : Extrait les données nécessaire à la construction du template et inclut le template
        // Paramètres :
        //          $view : chemin du template à afficher
        //          $parameter : tableau des données nécessaires à la construction du template
        // Retour : le template rendu
        ob_start();
        extract($parameter);
        require APPLICATION_PATH . $view . ".php";            
        $content = ob_get_contents();
        ob_end_clean();
        return $content;
    }

    protected function redirectToRoute($url){
        // Rôle : Redirige vers l'url ou la route spécifiée
        // Paramètres :
        //          $url : l'URL ou la route vers laquelle rediriger
        // Retour : néant
        header("Location: {$url}");
        exit;
    }

}