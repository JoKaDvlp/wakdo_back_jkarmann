<?php

// Class router: classe de gestion des routes du projet

namespace App\Utils;

class router {
    private $routes = []; // Tableau des routes du projet de la forme ['/chemin/absolu', 'method', [class, 'controller']]

    public function add($methods, $path, $controller, $middlewares = []) {
        // Rôle : Ajouter une route au tableau $routes
        // Paramètres : 
        //          $methods : tableau de requête pour accéder à la page
        //          $path : chaine de caractères décrivant le chemin absolu de la page
        //          $controller : tableau de la forme [$classe, $methode]
        if (empty($methods)) {
            $methods = ['GET'];
        }
        $path = $this->normalizePath($path);
        foreach ($methods as $method) {
            $this->routes[] = ['path' => $path, 'method' => strtoupper($method), 'controller' => $controller, 'middlewares' => $middlewares];
        }
    }

    private function normalizePath($path) {
        // Rôle : Normaliser le chemin pour éviter les erreurs
        // Paramètres :
        //          $path : chaine de caractère du chemin à traiter
        // Retour : La chaine de caractères du chemin normalisé
        $path = trim($path, '/');
        $path = "/{$path}/";
        $path = preg_replace('#{[\w]+}#', '([\w]+)', $path); // Permet de gérer les paramètres dynamiques
        $path = preg_replace('#[/]{2,}#', '/', $path);
        return $path;
    }

    public function dispatch($path) {
        $path = $this->normalizePath($path);
        $method = strtoupper($_SERVER['REQUEST_METHOD']);
        foreach ($this->routes as $route) {
            if (preg_match("#^{$route['path']}$#", $path, $matches) && $route['method'] === $method) {
                if (!empty($route['middlewares'])) {
                    foreach ($route['middlewares'] as $middleware) {
                        $middlewareInstance = new $middleware();
                        if (!$middlewareInstance->handle()) {
                            return;
                        }
                    }
                }
                array_shift($matches); // Supprime la correspondance complète de $matches
                [$class, $function] = $route['controller'];
                $controllerInstance = new $class;
                $content = call_user_func_array([$controllerInstance, $function], $matches);
                echo $content;
                return;
            }
        }
        // Gérer la route non trouvée
        http_response_code(404);
        include "templates/pages/404.php";
    }
}