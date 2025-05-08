<?php

/**
 * Redirect to public folder - For shared hosting
 */

// Path to the public directory
$publicPath = __DIR__ . '/public';

// Path info from the request
$pathInfo = $_SERVER['PATH_INFO'] ?? $_SERVER['REQUEST_URI'] ?? '';

// Find the current script name (index.php) in the path
$scriptName = basename($_SERVER['SCRIPT_NAME']);
$pathInfo = str_replace($scriptName, '', $pathInfo);

// Include the public index.php
chdir($publicPath);
require_once $publicPath . '/index.php'; 