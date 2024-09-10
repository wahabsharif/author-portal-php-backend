<?php
include 'controllers/AuthorController.php';

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];

$controller = new AuthorController();

if ($uri === '/authors' && $method === 'GET') {
    $controller->getAuthors();
} elseif ($uri === '/authors' && $method === 'POST') {
    $controller->addAuthor();
} elseif (preg_match('/\/authors\/(\d+)/', $uri, $matches) && $method === 'GET') {
    $controller->getAuthorById($matches[1]);
} elseif (preg_match('/\/authors\/(\d+)/', $uri, $matches) && $method === 'DELETE') {
    $controller->deleteAuthor($matches[1]);
} elseif (preg_match('/\/authors\/(\d+)/', $uri, $matches) && $method === 'PUT') {
    $controller->updateAuthorById($matches[1]);
} else {
    http_response_code(404);
    echo json_encode(['status' => 'error', 'message' => 'Not Found']);
}

function sendCORSHeaders($responseCode = 200)
{
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type, Authorization");
    header("Content-Type: application/json");
    http_response_code($responseCode);
}
