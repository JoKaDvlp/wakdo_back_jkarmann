<?php
namespace App\Middlewares;

use App\Utils\session;

class AuthMiddleware {
    public function handle() {
        if (!session::isconnected()) {
            // L'utilisateur n'est pas authentifié, on renvoie un code 401
            http_response_code(401);
            include "templates/pages/401.php";
            return false;
        }
        return true;
    }
}