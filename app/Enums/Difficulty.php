<?php

namespace App\Enums;

enum Difficulty: string
{
    case Easy = 'easy';
    case Medium = 'medium';
    case Hard = 'hard';
    case Expert = 'expert';

    public function getLabel(): string
    {
        return match ($this) {
            self::Easy => 'Easy',
            self::Medium => 'Medium',
            self::Hard => 'Hard',
            self::Expert => 'Expert',
        };
    }

    public function getClueCount(): int
    {
        return match ($this) {
            self::Easy => 36,
            self::Medium => 30,
            self::Hard => 24,
            self::Expert => 18,
        };
    }
}
