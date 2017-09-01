<?php namespace App\Services;

class TestResult
{

    protected $problems = [];
    protected $warnings = [];
    protected $notices = [];

    public function addProblem(string $message): void
    {
        array_push($this->problems, $message);
    }

    public function addWarning(string $message): void
    {
        array_push($this->warnings, $message);
    }

    public function addNotice(string $message): void
    {
        array_push($this->notices, $message);
    }

    public function getProblems(): array
    {
        return $this->problems;
    }

    public function getWarnings(): array
    {
        return $this->warnings;
    }

    public function getNotices(): array
    {
        return $this->notices;
    }

    public function getAllMessages(): array
    {
        return [
            'problems' => $this->getProblems(),
            'warnings' => $this->getWarnings(),
            'notices' => $this->getNotices(),
        ];
    }

}
