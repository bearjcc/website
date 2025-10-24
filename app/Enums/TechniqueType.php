<?php

namespace App\Enums;

enum TechniqueType: string
{
    case NakedSingle = 'naked_single';
    case HiddenSingle = 'hidden_single';
    case LockedCandidates = 'locked_candidates';
    case NakedPair = 'naked_pair';
    case HiddenPair = 'hidden_pair';
    case NakedTriple = 'naked_triple';
    case HiddenTriple = 'hidden_triple';
    case XWing = 'x_wing';
    case Swordfish = 'swordfish';
    
    public function getWeight(): int
    {
        return match($this) {
            self::NakedSingle => 1,
            self::HiddenSingle => 2,
            self::LockedCandidates => 3,
            self::NakedPair => 4,
            self::HiddenPair => 5,
            self::NakedTriple => 6,
            self::HiddenTriple => 7,
            self::XWing => 12,
            self::Swordfish => 18,
        };
    }
    
    public function getDescription(): string
    {
        return match($this) {
            self::NakedSingle => 'A cell that has only one possible candidate',
            self::HiddenSingle => 'A digit that can only go in one cell within a row, column, or box',
            self::LockedCandidates => 'Candidates confined to one row/column within a box, or one box within a row/column',
            self::NakedPair => 'Two cells in a unit that share exactly the same two candidates',
            self::HiddenPair => 'Two digits that appear in exactly the same two cells within a unit',
            self::NakedTriple => 'Three cells in a unit whose candidates are a subset of three digits',
            self::HiddenTriple => 'Three digits that appear in exactly the same three cells within a unit',
            self::XWing => 'A digit appears in exactly two rows and two columns, forming a rectangle',
            self::Swordfish => 'A digit appears in exactly three rows and three columns',
        };
    }
}





