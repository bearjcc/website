<?php

namespace App\Services\Sudoku;

use App\Enums\TechniqueType;

/**
 * Sudoku Solving Step
 *
 * Represents a single step in the human solving process, including the
 * technique used, placements made, eliminations performed, and educational
 * explanation for the user.
 *
 * This class provides the foundation for the hint system, allowing users
 * to learn Sudoku solving techniques while playing.
 */
class Step
{
    public function __construct(
        public TechniqueType $type,
        public array $placements = [],      // [['r' => int, 'c' => int, 'd' => int], ...]
        public array $eliminations = [],    // [['r' => int, 'c' => int, 'd' => int], ...]
        public string $explanation = '',
        public int $weight = 0,
        public array $focusCells = []       // [['r' => int, 'c' => int], ...]
    ) {
        if ($weight === 0) {
            $this->weight = $type->getWeight();
        }
    }

    /**
     * Get human-readable technique name
     */
    public function getTechniqueName(): string
    {
        return match ($this->type) {
            TechniqueType::NakedSingle => 'Naked Single',
            TechniqueType::HiddenSingle => 'Hidden Single',
            TechniqueType::LockedCandidates => 'Locked Candidates',
            TechniqueType::NakedPair => 'Naked Pair',
            TechniqueType::HiddenPair => 'Hidden Pair',
            TechniqueType::NakedTriple => 'Naked Triple',
            TechniqueType::HiddenTriple => 'Hidden Triple',
            TechniqueType::XWing => 'X-Wing',
            TechniqueType::Swordfish => 'Swordfish',
        };
    }

    /**
     * Check if this step has any placements
     */
    public function hasPlacements(): bool
    {
        return ! empty($this->placements);
    }

    /**
     * Check if this step has any eliminations
     */
    public function hasEliminations(): bool
    {
        return ! empty($this->eliminations);
    }
}
