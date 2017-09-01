<?php namespace App\Contracts;

use App\Services\TestResult;

interface TestInterface
{
    public function run(\string $URL): TestResult;
}
