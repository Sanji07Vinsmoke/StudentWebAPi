<?php
require_once __DIR__ . '/../repositories/StudentRepository.php';

class Router {
    private $repository;

    public function __construct() {
        $this->repository = new StudentRepository();
    }

    public function HandleRequest($route) {
        header('Content-Type: application/json');

        $request_method = $_SERVER['REQUEST_METHOD'];
        $input = json_decode(file_get_contents('php://input'), true);
        $uri = explode('/', trim($_SERVER['REQUEST_URI'], '/')); 
        $studentId = isset($uri[2]) ? (int) $uri[2] : null; 

        switch ($route) {
            case 'student':
                if ($request_method === 'GET') {
                    echo json_encode($studentId ? $this->repository->GetStudentById($studentId) ?? ["error" => "Student not found"] : $this->repository->GetAllStudents());
                } elseif ($request_method === 'POST' && isset($input['StudentName'], $input['MidtermScore'], $input['FinalScore'])) {
                    echo json_encode($this->repository->AddStudent($input['StudentName'], $input['MidtermScore'], $input['FinalScore']));
                } elseif ($request_method === 'PUT' && $studentId && isset($input['MidtermScore'], $input['FinalScore'])) {
                    echo json_encode($this->repository->UpdateStudent($studentId, $input['MidtermScore'], $input['FinalScore']));
                } elseif ($request_method === 'DELETE' && $studentId) {
                    echo json_encode(["message" => $this->repository->DeleteStudent($studentId) ? "Student deleted" : "Student not found"]);
                } else {
                    http_response_code($studentId || $request_method === 'POST' ? 400 : 405);
                    echo json_encode(["error" => $studentId ? "Missing required fields" : "Method Not Allowed"]);
                }
                break;
            
            default:
                http_response_code(404);
                echo json_encode(["error" => "Invalid API request"]);
        }
        
    }
}

