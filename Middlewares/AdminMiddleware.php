<?php
namespace App\Middlewares;

use App\Utils\session;

class AdminMiddleware {
    public function handle() {
        if (!session::isconnected() || session::userconnected()->getRole() !== 'ADMIN') {
            // L'utilisateur n'est pas admin, on renvoie un code 403
            http_response_code(403);
            include "templates/pages/403.php";
            return false;
        }
        return true;
    }
}