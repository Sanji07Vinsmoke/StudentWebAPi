<?php

require_once __DIR__ . '/../models/Student.php';

class StudentService {
    public function CalculateFinalGrade(Student $student) {
        $student->FinalGrade = (0.4 * $student->MidtermScore) + (0.6 * $student->FinalScore);
        $student->Status = $student->FinalGrade >= 75 ? 'Passed' : 'Failed';
        return $student;
    }
}
