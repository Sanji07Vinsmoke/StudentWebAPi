<?php
require_once __DIR__ . '/../repositories/StudentRepository.php';
require_once __DIR__ . '/../controllers/StudentController.php';

class Router {
    private array $routes = [];

    public function __construct(){
        $this->defineRoutes();
    }

    private function defineRoutes(){
        $this->routes = [
            'GET' => [
                'student' => [StudentController::class, 'GetAllStudents'],
                'student/{id}' => [StudentController::class, 'GetStudentById'],
            ],
            'POST' => [
                'student' => [StudentController::class, 'AddStudent'],
            ],
            'PUT' => [
                'student/{id}' => [StudentController::class, 'UpdateStudent'],
            ],
            'DELETE' => [
                'student/{id}' => [StudentController::class, 'DeleteStudent'],
            ],
        ];
    }

    public function handleRequest(){
        $requestMethod = $_SERVER['REQUEST_METHOD'];
        $requestUri = $this->getProcessedUri();

        if (!isset($this->routes[$requestMethod])){
            $this->sendNotFound();
            return;
        }

        foreach ($this->routes[$requestMethod] as $route => $handler){
            $pattern = $this->convertToRegex($route);

            if (preg_match($pattern, $requestUri, $matches)){
                array_shift($matches);

                if ($requestMethod === 'POST' || $requestMethod === 'PUT'){
                    $requestData = $this->getRequestData();
                    $this->dispatch($handler, array_merge([$requestData], $matches));
                } else {
                    $this->dispatch($handler, $matches);
                }
                return;
            }
        }
        $this->sendNotFound();
    }

    private function getProcessedUri(): string {
        $requestUri = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
        $scriptName = dirname($_SERVER["SCRIPT_NAME"]);
        return trim(str_replace($scriptName, "", $requestUri), "/");
    }

    private function convertToRegex(string $route): string {
        $pattern = preg_replace('/\{(\w+)\}/', '(\\d+)', $route); 
        return '/^' . str_replace('/', '\/', $pattern) . '$/';
    }

    private function getRequestData(){
        $data = json_decode(file_get_contents('php://input'), true);
        return is_array($data) ? $data : [];
    }

    private function dispatch(array $handler, array $params){
        [$controllerClass, $method] = $handler;
        $controller = new $controllerClass();
        call_user_func_array([$controller, $method], $params);
    }

    private function sendNotFound(){
        header("HTTP/1.0 404 Not Found");
        echo json_encode(["message" => "Route Not Found"]);
    }
} 
?>
