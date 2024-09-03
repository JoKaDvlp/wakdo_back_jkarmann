<?php
namespace App\Middlewares;

use App\Utils\session;

class HostMiddleware {
    public function handle() {
        if (!session::isconnected() || session::userconnected()->getRole() !== 'HOTE') {
            // L'utilisateur n'est pas hot, on renvoie un code 403
            http_response_code(403);
            include "templates/pages/403.php";
            return false;
        }
        return true;
    }
}