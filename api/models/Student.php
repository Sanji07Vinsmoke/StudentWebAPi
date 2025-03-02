<?php

class Student {
    public $StudentId;
    public $StudentName;
    public $MidtermScore;
    public $FinalScore;
    public $FinalGrade;
    public $Status;

    public function __construct($StudentId = NULL, $StudentName, $MidtermScore, $FinalScore, $FinalGrade = NULL, $Status = NULL) {
        $this->StudentId = $StudentId;
        $this->StudentName = $StudentName;
        $this->MidtermScore = $MidtermScore;
        $this->FinalScore = $FinalScore;
        $this->FinalGrade = $FinalGrade;
        $this->Status = $Status;
    }
    
}
