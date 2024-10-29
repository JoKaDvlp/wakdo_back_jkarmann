<?php
namespace App\Utils;

use PDO;

/*

Class _model : classe générique de gestion des objets du modèle de données

Méthodes de cette classe :

    is() : Dire si l'objet est chargé
    getTarget($nomChamp) : instancier un objet en lien avec l'objet en cours
    id() : Retourne l'id de l'objet en cours
    load($id) : charger l'objet à partir de l'id
    loadFromTab($tab) : Charger un objet à partir d'un tableau
    insert() : Insérer l'objet courant dans la bdd
    makeRequestSet() : Créer le texte d'une requête utilisant "SET" sous la forme "`nomChamp1` = :nomChamp1, `nomChamp2` = :nomChamp2,..."
    makeRequestParamForSet() : Construire un tableau pour valoriser les paramètre d'une requête
    update() : Mettre à jour une ligne de la bdd à partir de l'objet courant
    delete() : Supprime l'objet courant de la bdd
    sqlToList($sql, $param = []) : à partir d'ubne requête SQL et de ses paramètres, générer une liste d'objets
    listChamps() : construire la liste des champs de la table pour une requête SELECT
    toTab() : retourner un tableau des valeurs des champs de cet objet
    sqlExecute($sql, $param) : exécuter une requête SQL sur la BDD
    listEtendue($filtres = [], $tris = []) : extraire une liste d'objet de cette classe, avec des critères de tri et de filtrage

*/

class _model {

    // Attributs :

    protected $table = "";      // Nom de la table
    protected $fields = [];     // Tableau simple listant les noms de champs de la table

    protected $links = [];      // Tableau des liens indexés par le nom du champ qui est un lien

    protected $id = 0;          // id de l'objet chargé
    protected $values = [];     // Tableau des valeurs sous la forme ["nomChamps" => valeur1,...]
    protected $targets = [];    // Tableau stockant les objets ayant pour index le nom du champ ["nomChamp" => objetLie,...]

    // Constructeur

    function __construct($id = null) {
        // Rôle : Charger l'objet correspondant à l'id
        // Paramètres :
        //          $id : id de la ligne à charger
        // Retour : constructeur, pas de retour

        // Si l'id est non null, on charge l'objet avec cet id
        if (! is_null($id)) {
            $this->load($id);
        }
    }


    // Méthodes magiques

    function __get($nomChamp) {
        // Rôle : Appeler le getter avec une notation de la forme $objet->nomChamp
        // Paramètres :
        //          $nomChamp : nom du champ où récupérer la valeur
        // Retour : la valeur associé au champ

        if ($nomChamp == "id") return $this->id();
        else if (in_array($nomChamp, $this->fields)) return $this->get($nomChamp);
        else if (in_array($nomChamp, $this->links)) return $this->getTarget($nomChamp); 
    }

    function __set($nomChamp, $valeur) {
        // Rôle : Appliquer une valeur à un champ sous la forme $this->nomChamp = valeur
        // Paramètres :
        //          $nomChamp : nom du champ à valoriser
        //          $valeur : la valeur à donner au champ
        // Retour : néant

        if (in_array($nomChamp, $this->fields)) $this->set($nomChamp, $valeur);
    }


    // Méthodes

    function is() {
        // Rôle : dire si l'objet est chargé
        // Paramètres : néant
        // Retour : true si il est chargé, false sinon

        return ! empty($this->id);
    }

    // Getter

    function get($nomChamp) {
        // Rôle : récupère la valeur associée à un champ
        // Paramètres :
        //      $nomChamp : le nom du champ
        // Retour : La valeur associée au champ
        if (isset($this->values[$nomChamp])) {
            return $this->values[$nomChamp];
        } else {
            return "";
        }
    }

    function getTarget($nomChamp) {
        // Rôle : instancier un objet en lien avec l'objet en cours
        // Paramètres :
        //          $nomChamp : nom du champ en lien avec la table ciblée
        // Retour : un objet chargé lié avec l'objet en cours

        // Si l'objet existe
        if (isset($this->targets[$nomChamp])) {
            return $this->targets[$nomChamp];
        }

        // Si le champ n'est pas un lien
        if ( ! isset($this->links[$nomChamp])) {
            $this->targets[$nomChamp] = new _model();
            return $this->targets[$nomChamp];
        }

        // Si le champ est un lien mais vide
        $classe = "App\Modeles\\".$this->links[$nomChamp];
        $this->targets[$nomChamp] = new $classe($this->get($nomChamp));

        return $this->targets[$nomChamp];

    }

    function id() {
        // Rôle : Retourne l'id de l'objet en cours
        // Paramètres : néant
        // Retour : l'id de l'objet en cours si il est chargé, sinon 0
        
        if (isset($this->id)) {
            return $this->id;
        } else {
            return 0;
        }
    }

    // Setter

    function set($nomChamp, $valeur) {
        // Rôle : Ajoute une valeur associée à un champ
        // Paramètres :
        //      $nomChamp : Nom du champ
        //      $valeur : Valeur associée au champ
        // Retour : true si réussi, false sinon

        $this->values[$nomChamp] = $valeur;

        return true;
    }


    // Méthodes gérant la base de données

    function load($id) {
        // Rôle : charger l'objet à partir de l'id
        // Paramètres :
        //      l'id de la ligne à charger
        // Retour : true si on l'a trouvé, false sinon

        // Construction de la requête sql
        $sql = "SELECT " . $this->listChamps() . "FROM $this->table WHERE `id` = :id";
        // Construction du tableau de paramètre
        $param = [":id" => $id];
        // Executer la requête sql
        $req = $this->sqlExecute($sql, $param);
        // Traiter le résultat
        // Création d'un tableau de résultat
        $result = $req->fetch(PDO::FETCH_ASSOC);

        // Si aucun résultat
        if (empty($result)) {
            return false;
        }

        // On valorise le résultat
        foreach ($this->fields as $nomChamp) {
            $this->values[$nomChamp] = $result[$nomChamp];
        }
        
        $this->id = $id;
            
        return true;
    }

    function loadFromTab($tab) {
        // Rôle : Charger un objet à partir d'un tableau
        // Paramètres : 
        //          $tab : tableau indexé par $nomChamp stockant la valeur associée
        // Retour : true si réussi, false sinon

        if (isset($tab["id"])) $this->id = $tab["id"];
        foreach ($this->fields as $nomChamp) {
            if (isset($tab[$nomChamp])) 
                $this->values[$nomChamp] = $tab[$nomChamp];
        }
    }

    function insert() {
        // Rôle : Insérer l'objet courant dans la bdd
        // Paramètres : néant
        // Retour : true si réussi, false sinon

        // Création de la requête sql
        $sql = "INSERT INTO `$this->table` SET " . $this->makeRequestSet();
        $param = $this->makeRequestParamForSet();

        // Exécution de la requête sql
        $this->sqlExecute($sql, $param);

        // Enregistrer le dernier id de la bdd dans l'objet courant
        global $bdd;
        $this->id = $bdd->lastInsertId();

        return true;
    }

    function makeRequestSet() {
        // Rôle : Créer le texte d'une requête utilisant "SET" sous la forme "`nomChamp1` = :nomChamp1, `nomChamp2` = :nomChamp2,..."
        // Paramètres : néant
        // Retour : le texte à utiliser dans la requête après "SET"
        $result = [];
        foreach ($this->fields as $nomChamp) {
            $result[] = "`$nomChamp` = :$nomChamp";
        }
        return implode(", ", $result);
    }

    function makeRequestParamForSet() {
        // Rôle : Construire un tableau pour valoriser les paramètre d'une requête 
        // Paramètres : néant
        // Retour : un tableau indexé de la forme [":nomChamp1" => valeur1, ":nomChamp2" => valeur2,...]

        $result = [];
        foreach ($this->fields as $nomChamp) {
            if (isset($this->values[$nomChamp])) {
                $result[":$nomChamp"] = $this->values[$nomChamp];
            } else {
                $result[":$nomChamp"] = null;
            }
        }

        return $result;
    }

    function update() {
        // Rôle : Mettre à jour une ligne de la bdd à partir de l'objet courant
        // Paramètres : néant
        // Retour : true si réussi, false sinon

        // Création de la requête sql
        $sql = "UPDATE `$this->table` SET" . $this->makeRequestSet() . " WHERE `id` = :id";
        // Création du tableau de param
        $param = $this->makeRequestParamForSet();
        $param[":id"] = $this->id();

        // Exécution de la requête
        $this->sqlExecute($sql, $param);

        return true;
    }

    function delete() {
        // Rôle : Supprime l'objet courant de la bdd
        // Paramètres : néant
        // Retour : true si réussi, false sinon

        // Création de la requête sql
        $sql ="DELETE FROM `$this->table` WHERE `id` = :id";
        // Création de param
        $param = ["`id`" => $this->id()];

        // Execution de la requête sql
        $this->sqlExecute($sql, $param);

        // Mise à zéro de l'id pour signifier que l'article n'existe plus
        $this->id = 0;
    }

    function sqlToList($sql, $param = []) {
        // Role : à partir d'ubne requête SQL et de ses paramètres, générer une liste d'objets
        // Paramètres:
        //      $sql : texte de la requête SQL (avec des parametres :xxx)
        //      $param : tableau de valorisation des paramètres de la requête
        // Retour : tableau d'objets de la classe courante (indexés par l'ID)

        // Exécution de la requête à partir des paramètres reçus
        $req = $this->sqlExecute($sql, $param);

        // On parcours la bdd pour créer une liste de résultat
        // On construit le tableau de résultat
        $result = [];
        while ($tab = $req->fetch(PDO::FETCH_ASSOC)) {
            // Récupération du nom de la classe de l'objet courant
            $classe = get_class($this);
            $obj = new $classe();
            // Chargement de l'objet
            $obj->loadFromTab($tab);
            foreach ($tab as $fieldName => $value) {
                if (!in_array($fieldName,$tab)){
                    $obj->values[$fieldName] = $value;
                }
            }

            // On ajoute l'objet à $result
            $result[$obj->id()] = $obj;
        }

        return $result;
    }

    function listChamps() {
        // Rôle : construire la liste des champs de la table pour une requête SELECT
        // Paramètres : néant
        // Retour : texte du type `id`, `nom`, `prenom`
        $liste = [];
        foreach ($this->fields as $nomChamp) {
            $liste[] = "`$nomChamp`";
        }

        return implode(", ", $liste);
    }

    function toTab() {
        // Rôle : retourner un tableau des valeurs des champs de cet objet
        // Paramètres : néant
        // Retour : tableau des valeurs indexé par le nom des champs
        //          exemple : [ "id" => 12, "nom" => "Blanchot", "prenom" => "Christophe"]
        $result = [];
        foreach ($this->fields as $nomChamp) {
            if (isset($this->values[$nomChamp]))
                $result["$nomChamp"] = $this->values[$nomChamp];
        }

        return $result;
    }

    function sqlExecute($sql, $param = []) {
        // Role : exécuter une requête SQL sur la BDD
        // Paramètres:
        //      $sql : texte de la requête SQL (avec des parametres :xxx)
        //      $param : tableau de valorisation des paramètres de la requête
        // Retour : 
        //      Objet requete exécutée (requête au sens PDO, que l'on pourra donc interroger comme on veut)
        
        global $bdd;
        $req = $bdd->prepare($sql);
        if (! empty($param)) {
            if ( ! $req->execute($param)) {
                echo "erreur requête";
                return [];
            } else {
                return $req;
            }
        } else {
            if ( ! $req->execute()) {
                echo "erreur requête ici";
                return [];
            } else {
                return $req;
            }
        }
    }

    function  listEtendue($filtres = [], $tris = []) {
        // Rôle : extraire une liste d'objet de cette classe, avec des critères de tri et de filtrage
        // Paramètres :
        //      $filtres : tableau permettant de définir des filtres du type ["`nomChamp`"=> valeur]
        //      $tris : liste des critères de tri, 
        //              chaque critère est de la forme : "+/-nomChamp", "+/-nnomChamp", ....
        //             chaque critère est donc le nom du champ précédé de - pour un tri descedant, 
        //              optionnellement de + pour un tri ascendant
        // Retour : tableau d'objets de la classe courante (indexés par l'ID)

        // Construction de la requête sql = "SELECT $this->listChamps() FROM `$this->table` WHERE implode(", ", $filtres)
        $sql = "SELECT `id`, " . $this->listChamps() . " FROM `$this->table`";
        $paramForFiltres = [];
        $param = [];
        foreach ($filtres as $fieldName => $value) {
            $paramForFiltres[] = "`$fieldName`=:$fieldName";
            $param[":$fieldName"] = "$value";
        }

        if (! empty($filtres)) {
            $sql .= " WHERE " . implode(" AND ", $paramForFiltres);
        }

        $result =[];
        foreach ($tris as $tri) {
            $signe = substr($tri, 0, 1);
            if ($signe === "-") {
                $ordre = "DESC";
                $nomChamp = substr($tri, 1);
            } else if ($signe === "+") {
                $ordre = "ASC";
                $nomChamp = substr($tri, 1);
            } else {
                $ordre = "ASC";
                $nomChamp = $tri;
            }
            $result[] = "`$nomChamp` $ordre";
        }
        if (! empty($result)) $sql .= " ORDER BY " . implode(", ", $result);

        // Execution de la requête
        return $this->sqlToList($sql, $param);
    }

    function  listRecherche($filtres = [], $tris = []) {
        // Rôle : extraire une liste d'objet de cette classe, à partir d'un mot clé
        // Paramètres :
        //      $filtres : tableau permettant de définir des filtres du type `nomChamp` LIKE valeur
        //      $tris : liste des critères de tri, 
        //              chaque critère est de la forme : "+/-nomChamp", "+/-nnomChamp", ....
        //             chaque critère est donc le nom du champ précédé de - pour un tri descedant, 
        //              optionnellement de + pour un tri ascendant
        // Retour : tableau d'objets de la classe courante (indexés par l'ID)

        // Construction de la requête sql = "SELECT $this->listChamps() FROM `$this->table` WHERE implode(" OR ", $filtres)
        $sql = "SELECT `id`, " . $this->listChamps() . " FROM `$this->table`";
        
        if (! empty($filtres)) {
            $sql .= " WHERE " . implode(" OR ", $filtres);
        }

        $result =[];
        foreach ($tris as $tri) {
            $signe = substr($tri, 0, 1);
            if ($signe === "-") {
                $ordre = "DESC";
                $nomChamp = substr($tri, 1);
            } else if ($signe === "+") {
                $ordre = "ASC";
                $nomChamp = substr($tri, 1);
            } else {
                $ordre = "ASC";
                $nomChamp = $tri;
            }
            $result[] = "`$nomChamp` $ordre";
        }
        if (! empty($result)) $sql .= " ORDER BY " . implode(", ", $result);

        // Execution de la requête
        return $this->sqlToList($sql);
    }
    
    function prepareJSON(){
        // Rôle : Retourne un tableau sous la forme d'un tableau JSON
        // Paramètres : néant
        // Retour : tableau au format JSON
    
        $result = [];
    
        foreach ($this->values as $fieldName => $value) {
            if (in_array($fieldName, $this->links)) {
                $obj = $this->getTarget($fieldName);
                foreach ($obj->values as $objFieldName => $objValue) {
                    $result["$fieldName"]["$objFieldName"] = "$objValue";
                }
            } else {
                $result["$fieldName"] = "$value";
            }
        }
        
        return $result;
    }
}