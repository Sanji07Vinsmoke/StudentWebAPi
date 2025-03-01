<?php
require_once 'core/Router.php';

// Capture request URI
$request_uri = trim($_GET['route'] ?? '', '/');

$router = new Router();
$router->HandleRequest($request_uri);
?>
