<?php
require_once 'core/Router.php';

$request_uri = trim($_GET['route'] ?? '', '/');

$router = new Router();
$router->HandleRequest($request_uri);

