<?php

declare(strict_types=1);

namespace App\Services\Sudoku;

/**
 * Dancing Links implementation for Algorithm X
 */
class DancingLinksMatrix
{
    public array $columns = [];

    public array $rows = [];

    private int $columnCount;

    public function __construct(int $columnCount)
    {
        $this->columnCount = $columnCount;

        // Create column headers
        for ($i = 0; $i < $columnCount; $i++) {
            $this->columns[$i] = new ColumnNode($i);
        }

        // Link column headers
        for ($i = 0; $i < $columnCount; $i++) {
            $this->columns[$i]->left = $this->columns[($i - 1 + $columnCount) % $columnCount];
            $this->columns[$i]->right = $this->columns[($i + 1) % $columnCount];
        }
    }

    public function addRow(array $constraints, $metadata = null): void
    {
        if (empty($constraints)) {
            return;
        }

        $rowId = count($this->rows);
        $rowNodes = [];

        // Create nodes for each constraint in this row
        foreach ($constraints as $colIndex) {
            if ($colIndex < 0 || $colIndex >= $this->columnCount) {
                continue;
            }

            $node = new DataNode($rowId, $colIndex, $metadata);
            $rowNodes[] = $node;

            // Link to column
            $column = $this->columns[$colIndex];
            $node->column = $column;
            $column->size++;

            // Link to previous node in column (if any)
            $node->up = $column->up;
            $node->up->down = $node;
            $node->down = $column;
            $column->up = $node;
        }

        // Link nodes horizontally within the row
        $nodeCount = count($rowNodes);
        for ($i = 0; $i < $nodeCount; $i++) {
            $node = $rowNodes[$i];
            $node->left = $rowNodes[($i - 1 + $nodeCount) % $nodeCount];
            $node->right = $rowNodes[($i + 1) % $nodeCount];
        }

        $this->rows[] = $rowNodes;
    }

    public function isEmpty(): bool
    {
        return $this->columns[0]->right === $this->columns[0];
    }

    public function chooseColumnWithMinSize(): ColumnNode
    {
        $minSize = PHP_INT_MAX;
        $bestColumn = null;

        $current = $this->columns[0]->right;
        while ($current !== $this->columns[0]) {
            if ($current->size < $minSize) {
                $minSize = $current->size;
                $bestColumn = $current;
            }
            $current = $current->right;
        }

        return $bestColumn ?? $this->columns[0];
    }

    public function cover(ColumnNode $column): void
    {
        // Remove column header from horizontal list
        $column->right->left = $column->left;
        $column->left->right = $column->right;

        // Cover all rows that have a 1 in this column
        $row = $column->down;
        while ($row !== $column) {
            $node = $row->right;
            while ($node !== $row) {
                // Remove node from its column
                $node->up->down = $node->down;
                $node->down->up = $node->up;
                $node->column->size--;

                $node = $node->right;
            }
            $row = $row->down;
        }
    }

    public function uncover(ColumnNode $column): void
    {
        // Uncover all rows that had a 1 in this column (in reverse order)
        $row = $column->up;
        while ($row !== $column) {
            $node = $row->left;
            while ($node !== $row) {
                // Restore node to its column
                $node->column->size++;
                $node->down->up = $node;
                $node->up->down = $node;

                $node = $node->left;
            }
            $row = $row->up;
        }

        // Restore column header to horizontal list
        $column->right->left = $column;
        $column->left->right = $column;
    }
}

class Node
{
    public ?Node $left = null;

    public ?Node $right = null;

    public ?Node $up = null;

    public ?Node $down = null;
}

class ColumnNode extends Node
{
    public int $size = 0;

    public int $index;

    public function __construct(int $index)
    {
        $this->index = $index;
        $this->up = $this;
        $this->down = $this;
    }
}

class DataNode extends Node
{
    public int $row;

    public int $columnIndex;

    public ColumnNode $column;

    public $metadata;

    public function __construct(int $row, int $columnIndex, $metadata = null)
    {
        $this->row = $row;
        $this->columnIndex = $columnIndex;
        $this->metadata = $metadata;
    }
}
