<?php
require_once 'controllers/ProductController.php';
$controller = new ProductController();

$request_method = $_SERVER["REQUEST_METHOD"];
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = explode('/', trim($uri, '/'));

// Debugging: Log URI segments
file_put_contents('debug.log', "URI Segments: " . print_r($uri, true) . "\n", FILE_APPEND);

// Handle /simple-api/api/products
$api_index = array_search('api', $uri);
// Wishlist handling
if ($api_index !== false && isset($uri[$api_index + 1]) && $uri[$api_index + 1] === 'wishlist') {
    require_once 'controllers/WishlistController.php';
    $database = new Database();
    $wishlistController = new WishlistController($database->getConnection());

    switch ($request_method) {
        case 'GET':
            $user_id = isset($_GET['user_id']) ? (int)$_GET['user_id'] : null;
            if ($user_id) {
                $wishlistController->index($user_id);
            } else {
                http_response_code(400);
                echo json_encode(["message" => "User ID required"]);
            }
            break;
        case 'POST':
            $wishlistController->add();
            break;
        case 'DELETE':
            $wishlistController->remove();
            break;
        default:
            http_response_code(405);
            echo json_encode(["message" => "Method not allowed"]);
    }

    exit(); // Stop further execution
}


$id = null;
if (isset($uri[$api_index + 2])) {
    $id = (int) $uri[$api_index + 2];
}

switch ($request_method) {
    case 'GET':
        if ($id) {
            $controller->show($id);
        } else {
            $controller->index();
        }
        break;
    case 'POST':
        $controller->create();
        break;
    case 'PUT':
        if ($id) {
            $controller->update($id);
        } else {
            header("HTTP/1.1 400 Bad Request");
            header('Content-Type: application/json');
            echo json_encode(["message" => "ID required for update"]);
        }
        break;
    case 'DELETE':
        if ($id) {
            $controller->delete($id);
        } else {
            header("HTTP/1.1 400 Bad Request");
            header('Content-Type: application/json');
            echo json_encode(["message" => "ID required for delete"]);
        }
        break;
    default:
        header("HTTP/1.1 405 Method Not Allowed");
        header('Content-Type: application/json');
        echo json_encode(["message" => "Method not allowed"]);
        break;
}
?>