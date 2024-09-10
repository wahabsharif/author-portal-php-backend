<?php
include 'config/config.php';

function logMessage($message)
{
    $logFile = LOG_FILE;
    file_put_contents($logFile, date('Y-m-d H:i:s') . ' - ' . $message . PHP_EOL, FILE_APPEND);
}

// Example usage
logMessage('This is a log message.');
