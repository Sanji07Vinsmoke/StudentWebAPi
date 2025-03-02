<?php
require_once __DIR__ . '/api/config/Database.php';
require_once __DIR__ . '/api/repositories/StudentRepository.php';

try {
    $studentRepository = new StudentRepository();

    $StudentId = 4;  
    $NewMidtermScore = 80;
    $NewFinalScore = 80;

    $result = $studentRepository->UpdateStudent($StudentId, $NewMidtermScore, $NewFinalScore);

    echo json_encode($result, JSON_PRETTY_PRINT);
} catch (Exception $e) {
    echo json_encode(["error" => $e->getMessage()]);
}
