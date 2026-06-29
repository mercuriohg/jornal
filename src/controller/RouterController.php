<?php
require_once __DIR__ . '/AuthMiddleware.php';
class RouterController
{
    public static function route($uri)
    {
        switch ($uri) {

            // ===== PÁGINAS =====
            case '':
                require_once __DIR__ . '/../views/home.php';
                break;
            case 'contato':
                require_once __DIR__ . '/../views/contato.php';
                break;

            case 'noticias':
                require_once __DIR__ . '/../views/noticias.php';
                break;
                
            case 'membros':
                require_once __DIR__ . '/../views/gremio.php';
                break;

            case 'noticia':
                require_once __DIR__ . '/../views/noticia.php';
                break;

            case 'esportes':
                require_once __DIR__ . '/../views/esportes.php';
                break;

            case 'projetos':
                require_once __DIR__ . '/../views/projetos.php';
                break;
             
            case 'admin':
                AuthMiddleware::check();
                require_once __DIR__ . '/../views/admin/admin.php';
                break;

            case 'admin-members':
                AuthMiddleware::check();
                require_once __DIR__ . '/../views/admin/admin-members.php';
                break;

            case 'save-news':
                AuthMiddleware::check();
                require_once __DIR__ . '/NewsController.php';
                NewsController::save();
                break;

            case 'update-news':
                AuthMiddleware::check();
                require_once __DIR__ . '/NewsController.php';
                NewsController::update($_GET['id'] ?? '');
                break;

            case 'delete-news':
                AuthMiddleware::check();
                require_once __DIR__ . '/NewsController.php';
                NewsController::delete($_GET['id'] ?? '');
                break;

            case 'save-member':
                AuthMiddleware::check();
                require_once __DIR__ . '/MemberController.php';
                MemberController::save();
                break;

            case 'update-member':
                AuthMiddleware::check();
                require_once __DIR__ . '/MemberController.php';
                MemberController::update($_GET['id'] ?? '');
                break;

            case 'delete-member':
                AuthMiddleware::check();
                require_once __DIR__ . '/MemberController.php';
                MemberController::delete($_GET['id'] ?? '');
                break;

            case 'delete-news':
                AuthMiddleware::check();
                require_once __DIR__ . '/NewsController.php';
                NewsController::delete($_GET['id'] ?? '');
                break;

            case 'log-admin':
                require_once __DIR__ . '/../views/admin/login-admin.php';
                break;

            case 'logout':
                require_once __DIR__ . '/AdminController.php';
                AdminController::logout();
                break;
            case 'login':
                require_once __DIR__ . '/AdminController.php';
                AdminController::login();
                break;
            default:
                require_once __DIR__ . '/../views/404.php';
                break;
        }
    }
}
