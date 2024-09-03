<?php

// Code d'initialisation à inclure en début de contrôleur

// Paramétrer l'affichage des erreurs

use App\Utils\Autoloader;
use App\Utils\session;

ini_set("display_errors", 1);   // Afficher les erreurs
error_reporting(E_ALL);         // Toutes les erreurs

// Ouverture de la BDD dans une variable globale $bdd
global $bdd;
$bdd = new PDO("mysql:host=localhost;dbname=projets_exam-back_jkarmann;charset=UTF8", "jkarmann", "KU=WFl4d?G");
$bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);

// Autochargement des classes
require "autoloader.php";
if (class_exists("App\Utils\Autoloader")) {
    Autoloader::register();
} else {
    echo "Erreur, autoloader introuvable...";
}

// Activation de la session
session::activation();
