<?php
require_once 'core/Router.php';

$request_uri = trim($_SERVER['REQUEST_URI'], '/');

$router = new Router();
$router->HandleRequest($request_uri);

