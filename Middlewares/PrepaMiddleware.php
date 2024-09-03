<?php
namespace App\Middlewares;

use App\Utils\session;

class PrepaMiddleware {
    public function handle() {
        if (!session::isconnected() || session::userconnected()->getRole() !== 'PREPARATEUR') {
            // L'utilisateur n'est pas préparateur, on renvoie un code 403
            http_response_code(403);
            include "templates/pages/403.php";
            return false;
        }
        return true;
    }
}