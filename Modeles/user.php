<?php
namespace App\Modeles;

use App\Utils\_model;
use PDO;

class user extends _model
{
    protected $table = "user";
    protected $fields = ["name", "forename", "identifier", "password", "role", "is_active"];

    protected $links = ["role"=>"role"];

    // Methodes
    function set($nomChamp, $valeur) {
        // Rôle : Surcharge le setter pour y ajouter le hashage du mdp
        // Paramètres :
        //      $nomChamp : nom du champ à valoriser
        //      $valeur : valeur à associer au champ
        // Retour : néant

        if ($nomChamp == "password") {
            $valeur = password_hash($valeur, PASSWORD_DEFAULT);
        }

        return parent::set($nomChamp,$valeur);
    }

    function verifierUtilisateur($identifier, $password) {
        // Rôle : Vérifier si un utilisateur existe dans la bdd et contrôle son mdp puis charge l'objet en cours.
        // Paramètres :
        //      $mail : Adresse mail de l'utilisateur
        //      $mdp : mot de passe à vérifier en correspondance dans la bdd
        // Retour : true si l'utilisateur est vérifié, false sinon

        // Création de la requête
        $sql = "SELECT `id`, " . $this->listChamps() . " FROM `$this->table` WHERE `identifier` = :identifier";
        $param = [":identifier" => $identifier];

        $req = $this->sqlExecute($sql, $param);

        $user = $req->fetch(PDO::FETCH_ASSOC);

        if (empty($user)){
            return false;
        }

        if (password_verify($password, $user["password"])) {
            $this->loadFromTab($user);
            $this->id = $user["id"];
            return true;
        } else {
            return false;
        }
    }

    function getRole(){
        return $this->getTarget("role")->role;
    }

    function updatePassword($oldPassword, $newPassword, $newPasswordVerification){
        // Rôle : Comparer l'ancien mdp avec celui de la bdd, vérifier le nouveau mdp, puis l'enregistrer si OK
        // Paramètres :
        //          $oldPassword : ancien mot de passe
        //          $newPassword : nouveau mot de passe
        //          $newPasswordVerification : vérification du nouveau mdp
        // Retour : true si mdp modifié, false sinon

        if (password_verify($oldPassword, $this->password)){
            if ($newPassword === $newPasswordVerification) {
                $this->set("password", $newPassword);
                return true;
            }
            return false;
        }
        return false;
    }
}