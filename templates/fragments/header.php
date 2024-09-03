<?php

// Fragment de page : header

use App\Utils\session;

?>

<header class="flex justify-between align-center">
    <div class="flex gap-10">
        <a href="#" class="flex align-center"><img src="/public/images/logo.png" alt="Logo Wakdo">Bakdo</a>
        <?php
            $tabUrl = explode('/', ltrim($_SERVER['REQUEST_URI'],"/"));
            if (count($tabUrl)>1) {
        ?>
            <a class="btn-yellow" href="/<?=$tabUrl[0]?>">Retour à votre accueil</a>
        <?php
            }
        ?>
        <?php
            if ($tabUrl[0] === "host") {
        ?>
            <a class="btn-yellow" href="/host/orders/add">Ajouter une commande</a>
        <?php
            }
        ?>
    </div>
    <p><?= session::isconnected() ? "Bonjour ".session::userconnected()->forename : ""?></p>
    <a href="/deconnexion" class="btn-mono">Deconnexion</a>
</header>