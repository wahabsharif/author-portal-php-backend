<?php

// Define constants for easier maintenance
define('ALLOWED_ORIGINS', 'http://localhost:3000'); // Ensure this is the exact origin of your frontend app
define('ALLOWED_METHODS', ['GET', 'POST', 'PUT', 'DELETE', 'OPTIONS']);
define('ALLOWED_HEADERS', ['Content-Type', 'Authorization']);

// Function to handle CORS
function handleCORS()
{
    // Allow specific origin
    header("Access-Control-Allow-Origin: " . ALLOWED_ORIGINS);
    header('Access-Control-Allow-Credentials: true');
    header('Access-Control-Max-Age: 3600'); // Cache preflight response

    if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
        header('Access-Control-Allow-Methods: ' . implode(', ', ALLOWED_METHODS));
        header('Access-Control-Allow-Headers: ' . implode(', ', ALLOWED_HEADERS));
        exit(0);
    }
}

// Call the CORS handler at the beginning of each request
handleCORS();

// Your main application logic goes here
// ...
