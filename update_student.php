<?php
require_once __DIR__ . '/api/config/Database.php';
require_once __DIR__ . '/api/services/StudentService.php'; 

$pdo = require __DIR__ . '/api/config/Database.php';
$studentService = new StudentService();


$StudentId = 4;
$NewMidtermScore = 80; 
$NewFinalScore = 80; 


$checkSql = "SELECT * FROM student WHERE STUD_ID = :StudentId";
$checkStmt = $pdo->prepare($checkSql);
$checkStmt->execute([':StudentId' => $StudentId]);
$student = $checkStmt->fetch(PDO::FETCH_ASSOC);

if (!$student) {
    echo "No student found with ID: $StudentId";
    exit;
}


$studentObj = new Student(
    $StudentId,
    $student['STUD_NAME'],
    $NewMidtermScore,
    $NewFinalScore
);

$updatedStudent = $studentService->CalculateFinalGrade($studentObj);


$updateSql = "UPDATE student 
              SET STUD_MIDTERM = :MidtermScore, 
                  STUD_FINAL = :FinalScore, 
                  STUD_GRADE = :FinalGrade, 
                  STUD_STATUS = :Status 
              WHERE STUD_ID = :StudentId";

$updateStmt = $pdo->prepare($updateSql);
$updateStmt->execute([
    ':MidtermScore' => $NewMidtermScore,
    ':FinalScore' => $NewFinalScore,
    ':FinalGrade' => $updatedStudent->FinalGrade,
    ':Status' => $updatedStudent->Status,
    ':StudentId' => $StudentId
]);

echo "Student with ID $StudentId has been updated successfully!";

