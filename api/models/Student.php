<?php

class Student {
    public $StudentId;
    public $StudentName;
    public $MidtermScore;
    public $FinalScore;
    public $FinalGrade;
    public $Status;

    public function __construct($StudentId, $StudentName, $MidtermScore, $FinalScore, $FinalGrade, $Status) {
        $this->StudentId = $StudentId;
        $this->StudentName = $StudentName;
        $this->MidtermScore = $MidtermScore;
        $this->FinalScore = $FinalScore;
        $this->FinalGrade = $FinalGrade;
        $this->Status = $Status;
    }
}
