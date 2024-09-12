<?php
require_once 'controllers/AuthorController.php';
require_once 'cors.php';


// Handle preflight requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    handleCORS();
    exit(0);
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
switch ($uri) {
    case '/authors':
        if ($method === 'GET') {
            handleCORS();
            $controller->getAuthors();
        } elseif ($method === 'POST') {
            handleCORS();
            $controller->addAuthor();
        }
        break;

    case preg_match('/\/authors\/(\d+)/', $uri, $matches) ? $uri : '':
        $authorId = $matches[1];
        if ($method === 'GET') {
            handleCORS();
            $controller->getAuthorById($authorId);
        } elseif ($method === 'DELETE') {
            handleCORS();
            $controller->deleteAuthor($authorId);
        } elseif ($method === 'PUT') {
            handleCORS();
            $controller->updateAuthorById($authorId);
        }
        break;

    case '/login':
        if ($method === 'POST') {
            handleCORS();
            $controller->loginAuthor();
        }
        break;

    default:
        http_response_code(404);
        echo json_encode(['status' => 'error', 'message' => 'Not Found']);
        break;
}
