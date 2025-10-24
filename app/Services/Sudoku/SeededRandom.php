<?php

namespace App\Services\Sudoku;

class SeededRandom
{
    private int $seed;
    private int $state;
    
    public function __construct(int $seed)
    {
        $this->seed = $seed;
        $this->state = $seed;
    }
    
    public function getSeed(): int
    {
        return $this->seed;
    }
    
    /**
     * Xorshift32 PRNG
     */
    public function next(): int
    {
        $this->state ^= $this->state << 13;
        $this->state ^= $this->state >> 17;
        $this->state ^= $this->state << 5;
        
        return abs($this->state);
    }
    
    public function nextFloat(): float
    {
        return $this->next() / PHP_INT_MAX;
    }
    
    public function shuffle(array &$array): void
    {
        $count = count($array);
        for ($i = $count - 1; $i > 0; $i--) {
            $j = $this->next() % ($i + 1);
            [$array[$i], $array[$j]] = [$array[$j], $array[$i]];
        }
    }
    
    public function randomInt(int $min, int $max): int
    {
        if ($min >= $max) {
            return $min;
        }
        
        return $min + ($this->next() % ($max - $min + 1));
    }
}





