<?php

class Student {
    public $StudentId;
    public $StudentName;
    public $MidtermScore;
    public $FinalScore;
    public $FinalGrade;
    public $Status;

    public function __construct($StudentId, $StudentName, $MidtermScore, $FinalScore) {
        $this->StudentId = $StudentId;
        $this->StudentName = $StudentName;
        $this->MidtermScore = $MidtermScore;
        $this->FinalScore = $FinalScore;
    }
}
