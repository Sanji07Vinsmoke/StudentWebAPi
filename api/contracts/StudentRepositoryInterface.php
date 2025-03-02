<?php

interface StudentRepositoryInterface {
    public function AddStudent(Student $student); 
    public function GetAllStudents();
    public function GetStudentById($StudentId);
    public function UpdateStudent($StudentId, $MidtermScore, $FinalScore);
    public function DeleteStudent($StudentId);
}
