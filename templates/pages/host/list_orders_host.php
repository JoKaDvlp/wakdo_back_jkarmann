<?php

/*
    Template de page : liste des commandes à remettres triées pas order croissant (hote)
    Paramètres :
            $orderList : liste des commandes à remettres triées pas order croissant
*/

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hote : liste des commandes prêtes</title>
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>
    <?php include "templates/fragments/header.php" ?>
    <main>
        <h1 class="text-center">Commandes prêtes</h1>
        <div class="orders-table-container flex justify-between">
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>n° commande</th>
                            <th>Date de la commande</th>
                            <th>Lieu de consommation</th>
                            <th>n° table</th>
                            <th>Statut</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($ordersList as $order) { ?>
                            <tr>
                                <td><?= htmlentities($order->order_number) ?></td>
                                <td><?= htmlentities($order->date_time) ?></td>
                                <td><?= htmlentities($order->location) ?></td>
                                <td><?= htmlentities($order->table_number) ?></td>
                                <td><?= htmlentities($order->status) ?></td>
                                <td>
                                    <button class="btn-view-details" data-url="/host/orders/details/<?= htmlentities($order->id) ?>">Détails</button>
                                    <button class="btn-ready-status" data-url="/host/orders/ready/<?= htmlentities($order->id) ?>">Remise</button>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
            <div class="order-detail">
                <!--
                <div>
                    <p>1 Menu Maxi Best Of Big Mac</p>
                    <ul>
                        <li>Frites</li>
                        <li>Boisson</li>
                        <li>Sauce</li>
                    </ul>
                </div>
                -->
            </div>
        </div>
    </main>
    <script src="/js/app.js" defer></script>
</body>
</html>