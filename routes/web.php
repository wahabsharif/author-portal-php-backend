<?php
include '../controllers/AuthorController.php';

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];

$controller = new AuthorController();

if ($uri === '/authors' && $method === 'GET') {
    $controller->getAuthors();
} elseif ($uri === '/authors' && $method === 'POST') {
    $controller->addAuthor();
} elseif ($uri === '/authors' && $method === 'PUT') {
    $controller->updateAuthor();
} else {
    http_response_code(404);
    echo json_encode(['status' => 'error', 'message' => 'Not Found']);
}
