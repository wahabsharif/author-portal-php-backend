<?php
include 'controllers/AuthorController.php';

function sendCORSHeaders($responseCode = 200)
{
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type, Authorization");
    header("Content-Type: application/json");
    http_response_code($responseCode);
}

// Determine environment (local or live)
$host = $_SERVER['HTTP_HOST'];
$isLocal = in_array($host, ['localhost', '127.0.0.1']);

// Set the base path depending on the environment
$basePath = $isLocal ? '' : '/projects/authors-portal-api'; // No base path locally

// Parse the full URL and remove the base path (if applicable)
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];

// Normalize the URI for both local and live environments
$uri = str_replace($basePath, '', $uri);

$controller = new AuthorController();

// Routing logic
if ($uri === '/authors' && $method === 'GET') {
    sendCORSHeaders();
    $controller->getAuthors();
} elseif ($uri === '/authors' && $method === 'POST') {
    sendCORSHeaders();
    $controller->addAuthor();
} elseif (preg_match('/\/authors\/(\d+)/', $uri, $matches) && $method === 'GET') {
    sendCORSHeaders();
    $controller->getAuthorById($matches[1]);
} elseif (preg_match('/\/authors\/(\d+)/', $uri, $matches) && $method === 'DELETE') {
    sendCORSHeaders();
    $controller->deleteAuthor($matches[1]);
} elseif (preg_match('/\/authors\/(\d+)/', $uri, $matches) && $method === 'PUT') {
    sendCORSHeaders();
    $controller->updateAuthorById($matches[1]);
} elseif ($uri === '/login' && $method === 'POST') {
    sendCORSHeaders();
    $controller->loginAuthor();
} else {
    http_response_code(404);
    echo json_encode(['status' => 'error', 'message' => 'Not Found']);
}
