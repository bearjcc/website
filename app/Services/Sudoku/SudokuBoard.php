<?php

declare(strict_types=1);

namespace App\Services\Sudoku;

/**
 * Sudoku Board Representation
 *
 * This class manages the Sudoku game board state using efficient bitmask-based
 * candidate tracking. It provides O(1) candidate lookups and maintains three
 * constraint arrays for rows, columns, and boxes.
 *
 * Key Features:
 * - Bitmask-based candidate tracking (9 bits per cell)
 * - O(1) candidate lookup using bitwise operations
 * - Immutable operations with proper state management
 * - Comprehensive validation and error reporting
 */
class SudokuBoard
{
    private array $grid;              // 9x9 array, 0 = empty, 1-9 = digits

    private array $rowMasks;          // 9 bitmasks for row candidates

    private array $colMasks;          // 9 bitmasks for column candidates

    private array $boxMasks;          // 9 bitmasks for box candidates

    public function __construct(?array $grid = null)
    {
        $this->grid = $grid ?? array_fill(0, 9, array_fill(0, 9, 0));
        $this->initializeMasks();
    }

    /**
     * Get box index from row and column
     */
    public static function getBoxIndex(int $row, int $col): int
    {
        return (int) (floor($row / 3) * 3 + floor($col / 3));
    }

    /**
     * Get value at position
     */
    public function getValue(int $row, int $col): int
    {
        return $this->grid[$row][$col];
    }

    /**
     * Set a value in the grid
     */
    public function setValue(int $row, int $col, int $value): void
    {
        if ($value < 0 || $value > 9) {
            throw new \InvalidArgumentException('Value must be 0-9');
        }

        $oldValue = $this->grid[$row][$col];

        // Remove old value from masks
        if ($oldValue !== 0) {
            $this->addCandidateToMasks($row, $col, $oldValue);
        }

        // Set new value
        $this->grid[$row][$col] = $value;

        // Update masks with new value
        if ($value !== 0) {
            $this->removeCandidateFromMasks($row, $col, $value);
        }
    }

    /**
     * Get candidates for a cell as array of digits
     */
    public function getCandidates(int $row, int $col): array
    {
        if ($this->grid[$row][$col] !== 0) {
            return [];
        }

        $boxIndex = self::getBoxIndex($row, $col);
        $mask = $this->rowMasks[$row]
              & $this->colMasks[$col]
              & $this->boxMasks[$boxIndex];

        return $this->maskToDigits($mask);
    }

    /**
     * Check if board has any conflicts
     */
    public function isValid(): bool
    {
        // Check all rows
        for ($r = 0; $r < 9; $r++) {
            if (! $this->isUnitValid($this->getRow($r))) {
                return false;
            }
        }

        // Check all columns
        for ($c = 0; $c < 9; $c++) {
            if (! $this->isUnitValid($this->getCol($c))) {
                return false;
            }
        }

        // Check all boxes
        for ($b = 0; $b < 9; $b++) {
            if (! $this->isUnitValid($this->getBox($b))) {
                return false;
            }
        }

        return true;
    }

    /**
     * Get detailed validation report
     */
    public function getValidationReport(): array
    {
        $errors = [];
        $warnings = [];

        // Check for conflicts
        for ($r = 0; $r < 9; $r++) {
            $rowErrors = $this->getUnitErrors($this->getRow($r), 'row '.($r + 1));
            $errors = array_merge($errors, $rowErrors);
        }

        for ($c = 0; $c < 9; $c++) {
            $colErrors = $this->getUnitErrors($this->getCol($c), 'column '.($c + 1));
            $errors = array_merge($errors, $colErrors);
        }

        for ($b = 0; $b < 9; $b++) {
            $boxErrors = $this->getUnitErrors($this->getBox($b), 'box '.($b + 1));
            $errors = array_merge($errors, $boxErrors);
        }

        // Check for empty cells with no candidates (impossible puzzles)
        for ($r = 0; $r < 9; $r++) {
            for ($c = 0; $c < 9; $c++) {
                if ($this->grid[$r][$c] === 0) {
                    $candidates = $this->getCandidates($r, $c);
                    if (empty($candidates)) {
                        $warnings[] = 'Cell R'.($r + 1).'C'.($c + 1).' has no possible candidates';
                    }
                }
            }
        }

        return [
            'isValid' => empty($errors),
            'errors' => $errors,
            'warnings' => $warnings,
        ];
    }

    /**
     * Get errors for a unit (row, column, or box)
     */
    private function getUnitErrors(array $unit, string $unitName): array
    {
        $errors = [];
        $seen = [];
        $positions = [];

        for ($i = 0; $i < 9; $i++) {
            $value = $unit[$i];
            if ($value !== 0) {
                if (in_array($value, $seen, true)) {
                    $errors[] = "Duplicate {$value} in {$unitName}";
                }
                $seen[] = $value;
            }
        }

        return $errors;
    }

    /**
     * Check if board is completely filled and valid
     */
    public function isSolved(): bool
    {
        if (! $this->isValid()) {
            return false;
        }

        // Check all cells are filled
        foreach ($this->grid as $row) {
            if (in_array(0, $row, true)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Convert board to 81-character string
     */
    public function toString(): string
    {
        $result = '';
        foreach ($this->grid as $row) {
            foreach ($row as $cell) {
                $result .= ($cell === 0) ? '.' : $cell;
            }
        }

        return $result;
    }

    /**
     * Parse 81-character string into board
     */
    public static function fromString(string $str): self
    {
        if (strlen($str) !== 81) {
            throw new \InvalidArgumentException('String must be 81 characters');
        }

        $grid = [];
        for ($r = 0; $r < 9; $r++) {
            $grid[$r] = [];
            for ($c = 0; $c < 9; $c++) {
                $char = $str[$r * 9 + $c];
                $grid[$r][$c] = ($char === '.' || $char === '0') ? 0 : (int) $char;
            }
        }

        return new self($grid);
    }

    /**
     * Get the full grid as array
     */
    public function toArray(): array
    {
        return $this->grid;
    }

    /**
     * Clone the board
     */
    public function __clone()
    {
        $this->grid = array_map(fn ($row) => array_map(fn ($cell) => $cell, $row), $this->grid);
        $this->rowMasks = $this->rowMasks;
        $this->colMasks = $this->colMasks;
        $this->boxMasks = $this->boxMasks;
    }

    // Private helper methods
    private function initializeMasks(): void
    {
        // Initialize all masks to allow all digits (bits 0-8 set)
        $fullMask = (1 << 9) - 1; // 511 in binary: 111111111

        $this->rowMasks = array_fill(0, 9, $fullMask);
        $this->colMasks = array_fill(0, 9, $fullMask);
        $this->boxMasks = array_fill(0, 9, $fullMask);

        // Update masks based on existing values
        for ($r = 0; $r < 9; $r++) {
            for ($c = 0; $c < 9; $c++) {
                $value = $this->grid[$r][$c];
                if ($value !== 0) {
                    $this->removeCandidateFromMasks($r, $c, $value);
                }
            }
        }
    }

    private function removeCandidateFromMasks(int $row, int $col, int $digit): void
    {
        $mask = ~(1 << ($digit - 1)); // Clear the bit for this digit

        $this->rowMasks[$row] &= $mask;
        $this->colMasks[$col] &= $mask;
        $this->boxMasks[self::getBoxIndex($row, $col)] &= $mask;
    }

    private function addCandidateToMasks(int $row, int $col, int $digit): void
    {
        $mask = (1 << ($digit - 1)); // Set the bit for this digit

        $this->rowMasks[$row] |= $mask;
        $this->colMasks[$col] |= $mask;
        $this->boxMasks[self::getBoxIndex($row, $col)] |= $mask;
    }

    private function maskToDigits(int $mask): array
    {
        $digits = [];
        for ($i = 1; $i <= 9; $i++) {
            if ($mask & (1 << ($i - 1))) {
                $digits[] = $i;
            }
        }

        return $digits;
    }

    private function isUnitValid(array $unit): bool
    {
        $seen = [];
        foreach ($unit as $digit) {
            if ($digit === 0) {
                continue;
            }
            if (in_array($digit, $seen, true)) {
                return false;
            }
            $seen[] = $digit;
        }

        return true;
    }

    private function getRow(int $row): array
    {
        return $this->grid[$row];
    }

    private function getCol(int $col): array
    {
        $column = [];
        for ($r = 0; $r < 9; $r++) {
            $column[] = $this->grid[$r][$col];
        }

        return $column;
    }

    private function getBox(int $box): array
    {
        $startRow = intval($box / 3) * 3;
        $startCol = ($box % 3) * 3;

        $boxValues = [];
        for ($r = $startRow; $r < $startRow + 3; $r++) {
            for ($c = $startCol; $c < $startCol + 3; $c++) {
                $boxValues[] = $this->grid[$r][$c];
            }
        }

        return $boxValues;
    }
}
