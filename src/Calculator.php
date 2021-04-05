<?php

declare(strict_types=1);

namespace App;


class Calculator
{
    private \SplStack $operandStack;
    private \SplStack $operatorStack;
    private array $operations;

    public function __construct()
    {
        $this->operandStack = new \SplStack();
        $this->operatorStack = new \SplStack();
        $this->operations = [
            '+' => [
                'operation' => fn(float $a, float $b) => $a + $b,
                'priority' => 1
            ],
            '-' => [
                'operation' => fn(float $a, float $b) => $a - $b,
                'priority' => 1
            ],
            '*' => [
                'operation' => fn(float $a, float $b) => $a * $b,
                'priority' => 2
            ],
            '/' => [
                'operation' => fn(float $a, float $b) => $a / $b,
                'priority' => 2
            ],
        ];
    }

    public function calculate(string $expression) : float
    {
        $tokens = str_split(str_replace(" ", '', $expression));
        $tokens[] = PHP_EOL;

        foreach ($tokens as $token) {
            $this->handleToken($token);
        }

        return $this->operandStack->pop();
    }

    private function handleToken(string $token): void
    {
        switch (true) {
            case is_numeric($token):
                $this->operandStack->push((float) $token);
                break;
            case $this->isOperation($token):
                if ($this->operatorStack->isEmpty()) {
                    $this->operatorStack->push($token);
                    break;
                }

                $currentOperation = $this->operations[$token];
                $previousOperator = $this->operatorStack->top();
                $previousOperation = $this->operations[$previousOperator];

                if ($previousOperation['priority'] > $currentOperation['priority']) {
                    $this->operandStack->push($this->calculateLastOperation());
                    $this->handleToken($token);
                } else {
                    $this->operatorStack->push($token);
                }

                break;
            case $token === '(':
                $this->operatorStack->push($token);
                break;
            case $token === PHP_EOL:
                if ($this->operatorStack->isEmpty()) {
                    break;
                }

                $this->operandStack->push($this->calculateLastOperation());
                $this->handleToken($token);
                break;
        }
    }

    private function isOperation(string $token): bool
    {
        return array_key_exists($token, $this->operations);
    }

    private function calculateLastOperation(): float
    {
        $a = $this->operandStack->pop();
        $b = $this->operandStack->pop();
        $operation =  $this->operatorStack->pop();

        return $this->operations[$operation]['operation']($b, $a);
    }
}