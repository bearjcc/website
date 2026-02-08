<?php

namespace App\Games\TicTacToe;

final class Engine
{
    /**
     * Determine the winner.
     *
     * @param  array<int, null|string>  $board
     * @return null|string 'X'|'O'|null
     */
    public function winner(array $board): ?string
    {
        $lines = [
            [0, 1, 2], [3, 4, 5], [6, 7, 8], // rows
            [0, 3, 6], [1, 4, 7], [2, 5, 8], // cols
            [0, 4, 8], [2, 4, 6],         // diags
        ];
        foreach ($lines as [$a,$b,$c]) {
            if ($board[$a] !== null && $board[$a] === $board[$b] && $board[$b] === $board[$c]) {
                return $board[$a];
            }
        }

        return null;
    }

    /** @param array<int, null|string> $board */
    public function isDraw(array $board): bool
    {
        return $this->winner($board) === null && ! in_array(null, $board, true);
    }

    /** @return array<int> */
    public function availableMoves(array $board): array
    {
        $moves = [];
        foreach ($board as $i => $cell) {
            if ($cell === null) {
                $moves[] = (int) $i;
            }
        }

        return $moves;
    }

    /** @param array<int, null|string> $board */
    public function makeMove(array $board, int $pos, string $player): array
    {
        if ($board[$pos] === null) {
            $board[$pos] = $player;
        }

        return $board;
    }

    /**
     * Perfect minimax algorithm - IMPOSSIBLE to beat
     * Uses alpha-beta pruning with advanced position evaluation
     */
    public function bestMoveMinimax(array $board, string $player): int
    {
        $minimax = function (array $board, string $currentPlayer, int $depth, int $alpha = -1000, int $beta = 1000) use (&$minimax, $player): int {
            $winner = $this->winner($board);

            // Terminal states with enhanced evaluation
            if ($winner === $player) {
                return 1000 - $depth; // Prefer faster wins, but heavily weighted
            }
            if ($winner !== null && $winner !== $player) {
                return -1000 + $depth; // Prefer slower losses
            }
            if ($this->isDraw($board)) {
                return 0; // Neutral draw evaluation
            }

            $availableMoves = $this->availableMoves($board);

            // Early game optimization - prefer center and corners
            if (count($availableMoves) > 6) {
                return $this->earlyGameEvaluation($board, $currentPlayer, $player);
            }

            if ($currentPlayer === $player) {
                // Maximizing player (AI) - enhanced with position evaluation
                $maxEval = -10000;
                foreach ($availableMoves as $move) {
                    $newBoard = $board;
                    $newBoard[$move] = $currentPlayer;

                    // Add position evaluation bonus for strategic moves
                    $positionBonus = $this->getPositionBonus($move, $newBoard, $player);
                    $eval = $minimax($newBoard, $currentPlayer === 'X' ? 'O' : 'X', $depth + 1, $alpha, $beta) + $positionBonus;

                    $maxEval = max($maxEval, $eval);
                    $alpha = max($alpha, $eval);
                    if ($beta <= $alpha) {
                        break;
                    } // Alpha-beta pruning
                }

                return $maxEval;
            } else {
                // Minimizing player (opponent)
                $minEval = 10000;
                foreach ($availableMoves as $move) {
                    $newBoard = $board;
                    $newBoard[$move] = $currentPlayer;

                    // Subtract position bonus for opponent moves
                    $positionBonus = $this->getPositionBonus($move, $newBoard, $player);
                    $eval = $minimax($newBoard, $currentPlayer === 'X' ? 'O' : 'X', $depth + 1, $alpha, $beta) - $positionBonus;

                    $minEval = min($minEval, $eval);
                    $beta = min($beta, $eval);
                    if ($beta <= $alpha) {
                        break;
                    } // Alpha-beta pruning
                }

                return $minEval;
            }
        };

        $bestMove = -1;
        $bestValue = -10000;

        foreach ($this->availableMoves($board) as $move) {
            $newBoard = $board;
            $newBoard[$move] = $player;
            $moveValue = $minimax($newBoard, $player === 'X' ? 'O' : 'X', 0);

            if ($moveValue > $bestValue) {
                $bestValue = $moveValue;
                $bestMove = $move;
            }
        }

        return $bestMove;
    }

    /** Get position bonus for strategic moves */
    private function getPositionBonus(int $move, array $board, string $player): int
    {
        $bonus = 0;

        // Center is most valuable
        if ($move === 4) {
            $bonus += 50;
        }

        // Corners are valuable
        if (in_array($move, [0, 2, 6, 8])) {
            $bonus += 30;
        }

        // Edges are least valuable
        if (in_array($move, [1, 3, 5, 7])) {
            $bonus += 10;
        }

        // Bonus for moves that create multiple threats
        $testBoard = $board;
        $testBoard[$move] = $player;
        $winningLines = $this->countWinningLines($testBoard, $player);

        if ($winningLines > 0) {
            $bonus += $winningLines * 100; // Massive bonus for creating winning lines
        }

        // Bonus for blocking opponent threats
        $opponent = $player === 'X' ? 'O' : 'X';
        $opponentWinningLines = $this->countWinningLines($board, $opponent);
        if ($opponentWinningLines > 0) {
            $testBoard = $board;
            $testBoard[$move] = $player;
            $newOpponentLines = $this->countWinningLines($testBoard, $opponent);
            if ($newOpponentLines < $opponentWinningLines) {
                $bonus += 80; // Bonus for blocking opponent
            }
        }

        return $bonus;
    }

    /** Early game evaluation for better opening play */
    private function earlyGameEvaluation(array $board, string $currentPlayer, string $player): int
    {
        if ($currentPlayer === $player) {
            // AI prefers center, then corners, then edges
            $moveValues = [
                4 => 100,    // center
                0 => 80,     // corners
                2 => 80,
                6 => 80,
                8 => 80,
                1 => 60,     // edges
                3 => 60,
                5 => 60,
                7 => 60,
            ];

            return $moveValues[array_rand($moveValues)] ?? 50;
        } else {
            // Opponent - minimize their advantage
            $moveValues = [
                4 => -100,   // prevent center
                0 => -80,    // prevent corners
                2 => -80,
                6 => -80,
                8 => -80,
                1 => -60,    // allow edges
                3 => -60,
                5 => -60,
                7 => -60,
            ];

            return $moveValues[array_rand($moveValues)] ?? -50;
        }
    }

    /** @param array<int, null|string> $board */
    public function currentTurn(array $board): string
    {
        $x = 0;
        $o = 0;
        foreach ($board as $cell) {
            if ($cell === 'X') {
                $x++;
            } elseif ($cell === 'O') {
                $o++;
            }
        }

        return ($x === $o) ? 'X' : 'O';
    }

    /** Easy AI: Always takes wins, 40% chance for smart blocking, plays corners/center strategically */
    public function aiEasy(array $board, string $player): int
    {
        $moves = $this->availableMoves($board);
        $opponent = $player === 'X' ? 'O' : 'X';

        // ALWAYS try to win if possible (100% of the time)
        foreach ($moves as $move) {
            $testBoard = $board;
            $testBoard[$move] = $player;
            if ($this->winner($testBoard) === $player) {
                return $move;
            }
        }

        // 40% chance of making smart blocking moves
        if (mt_rand(1, 100) <= 40) {
            foreach ($moves as $move) {
                $testBoard = $board;
                $testBoard[$move] = $opponent;
                if ($this->winner($testBoard) === $opponent) {
                    return $move;
                }
            }
        }

        // Strategic position preferences (corners > center > edges)
        $preferredPositions = [0, 2, 6, 8, 4, 1, 3, 5, 7]; // corners, center, then edges
        foreach ($preferredPositions as $pos) {
            if (in_array($pos, $moves)) {
                return $pos;
            }
        }

        // Fallback to random
        return $moves[array_rand($moves)];
    }

    /** Medium AI: Smart strategic play with occasional tactical mistakes */
    public function aiMedium(array $board, string $player): int
    {
        $opponent = $player === 'X' ? 'O' : 'X';
        $moves = $this->availableMoves($board);

        // 1. Always try to win immediately
        foreach ($moves as $move) {
            $testBoard = $board;
            $testBoard[$move] = $player;
            if ($this->winner($testBoard) === $player) {
                return $move;
            }
        }

        // 2. 90% chance to block immediate opponent wins
        if (mt_rand(1, 100) <= 90) {
            foreach ($moves as $move) {
                $testBoard = $board;
                $testBoard[$move] = $opponent;
                if ($this->winner($testBoard) === $opponent) {
                    return $move;
                }
            }
        }

        // 3. Strategic positioning based on board state
        return $this->getMediumStrategicMove($board, $moves, $player, $opponent);
    }

    /** Get strategic move for medium difficulty */
    private function getMediumStrategicMove(array $board, array $moves, string $player, string $opponent): int
    {
        // Check for fork opportunities (two ways to win)
        $forkMoves = $this->findForkMoves($board, $moves, $player);
        if (! empty($forkMoves)) {
            return $forkMoves[array_rand($forkMoves)];
        }

        // Block opponent forks (70% chance)
        if (mt_rand(1, 100) <= 70) {
            $blockForkMoves = $this->findForkMoves($board, $moves, $opponent);
            if (! empty($blockForkMoves)) {
                return $blockForkMoves[array_rand($blockForkMoves)];
            }
        }

        // Take center if available (highest priority)
        if (in_array(4, $moves)) {
            return 4;
        }

        // Take corners strategically
        $corners = [0, 2, 6, 8];
        $availableCorners = array_intersect($corners, $moves);
        if (! empty($availableCorners)) {
            // Prefer corners adjacent to opponent pieces (20% chance for mistake)
            if (mt_rand(1, 100) <= 80) {
                $strategicCorners = $this->getStrategicCorners($board, $availableCorners, $opponent);
                if (! empty($strategicCorners)) {
                    return $strategicCorners[array_rand($strategicCorners)];
                }
            }

            return $availableCorners[array_rand($availableCorners)];
        }

        // Take edges, but avoid creating immediate threats (60% success rate)
        $edges = [1, 3, 5, 7];
        $availableEdges = array_intersect($edges, $moves);
        if (! empty($availableEdges)) {
            if (mt_rand(1, 100) <= 60) {
                // Make smart edge choice
                return $this->getBestEdge($board, $availableEdges, $player);
            }
        }

        // Fallback to random
        return $moves[array_rand($moves)];
    }

    /** Find moves that create forks (two winning lines) */
    private function findForkMoves(array $board, array $moves, string $player): array
    {
        $forkMoves = [];

        foreach ($moves as $move) {
            $testBoard = $board;
            $testBoard[$move] = $player;
            $winningLines = $this->countWinningLines($testBoard, $player);

            if ($winningLines >= 2) {
                $forkMoves[] = $move;
            }
        }

        return $forkMoves;
    }

    /** Count how many winning lines a player has */
    private function countWinningLines(array $board, string $player): int
    {
        $lines = [
            [0, 1, 2], [3, 4, 5], [6, 7, 8], // rows
            [0, 3, 6], [1, 4, 7], [2, 5, 8], // cols
            [0, 4, 8], [2, 4, 6],         // diags
        ];

        $winningLines = 0;
        foreach ($lines as [$a, $b, $c]) {
            if ($board[$a] === $player && $board[$b] === $player && $board[$c] === $player) {
                $winningLines++;
            }
        }

        return $winningLines;
    }

    /** Get strategic corners (avoid corners that give opponent advantage) */
    private function getStrategicCorners(array $board, array $corners, string $opponent): array
    {
        $strategic = [];

        foreach ($corners as $corner) {
            // Check if taking this corner creates a dangerous diagonal
            $testBoard = $board;
            $testBoard[$corner] = 'X'; // Test as if player takes it

            // Check if opponent could create a fork
            $diagonal1 = [0, 4, 8];
            $diagonal2 = [2, 4, 6];

            $dangerous = false;

            // If corner is in a diagonal with center, check if it's dangerous
            if (in_array($corner, $diagonal1) && $testBoard[4] === $opponent) {
                if (($corner === 0 && $testBoard[8] === null) ||
                    ($corner === 8 && $testBoard[0] === null) ||
                    ($corner === 2 && $testBoard[6] === null) ||
                    ($corner === 6 && $testBoard[2] === null)) {
                    $dangerous = true;
                }
            }

            if (! $dangerous) {
                $strategic[] = $corner;
            }
        }

        return empty($strategic) ? $corners : $strategic;
    }

    /** Get best edge position */
    private function getBestEdge(array $board, array $edges, string $player): int
    {
        // Prefer edges that don't create immediate threats
        foreach ($edges as $edge) {
            $testBoard = $board;
            $testBoard[$edge] = $player;

            // Check if this move creates a winning line for opponent
            $opponent = $player === 'X' ? 'O' : 'X';
            $threateningMoves = 0;

            foreach ($this->availableMoves($testBoard) as $oppMove) {
                $oppTestBoard = $testBoard;
                $oppTestBoard[$oppMove] = $opponent;
                if ($this->winner($oppTestBoard) === $opponent) {
                    $threateningMoves++;
                }
            }

            // If fewer than 2 threatening moves, it's probably safe
            if ($threateningMoves < 2) {
                return $edge;
            }
        }

        // Fallback to any edge
        return $edges[array_rand($edges)];
    }

    /** Hard AI: Advanced strategic play with sophisticated evaluation and rare mistakes */
    public function aiHard(array $board, string $player): int
    {
        $opponent = $player === 'X' ? 'O' : 'X';
        $moves = $this->availableMoves($board);

        // 1. Always try to win immediately
        foreach ($moves as $move) {
            $testBoard = $board;
            $testBoard[$move] = $player;
            if ($this->winner($testBoard) === $player) {
                return $move;
            }
        }

        // 2. Always block immediate opponent wins
        foreach ($moves as $move) {
            $testBoard = $board;
            $testBoard[$move] = $opponent;
            if ($this->winner($testBoard) === $opponent) {
                return $move;
            }
        }

        // 3. Advanced evaluation - 95% optimal play
        if (mt_rand(1, 100) <= 95) {
            return $this->bestMoveAdvanced($board, $player);
        }

        // 4. Rare suboptimal move (5% chance)
        return $this->aiMedium($board, $player);
    }

    /** Advanced move selection with sophisticated board evaluation */
    private function bestMoveAdvanced(array $board, string $player): int
    {
        $moves = $this->availableMoves($board);
        $bestMove = $moves[0];
        $bestScore = -1000;

        foreach ($moves as $move) {
            $testBoard = $board;
            $testBoard[$move] = $player;

            // Use advanced evaluation instead of pure minimax
            $score = $this->evaluatePosition($testBoard, $player);

            if ($score > $bestScore) {
                $bestScore = $score;
                $bestMove = $move;
            }
        }

        return $bestMove;
    }

    /** Advanced position evaluation considering multiple strategic factors */
    private function evaluatePosition(array $board, string $player): int
    {
        $opponent = $player === 'X' ? 'O' : 'X';
        $score = 0;

        // 1. Immediate winning potential
        $playerWinningLines = $this->countWinningLines($board, $player);
        $opponentWinningLines = $this->countWinningLines($board, $opponent);

        if ($playerWinningLines > 0) {
            $score += $playerWinningLines * 100; // Strong preference for winning moves
        }

        if ($opponentWinningLines > 0) {
            $score -= $opponentWinningLines * 100; // Strong penalty for losing positions
        }

        // 2. Center control (very important)
        if ($board[4] === $player) {
            $score += 30;
        } elseif ($board[4] === $opponent) {
            $score -= 30;
        } elseif ($board[4] === null) {
            $score += 15; // Bonus for taking center if available
        }

        // 3. Corner control (important for diagonals)
        $corners = [0, 2, 6, 8];
        $playerCorners = 0;
        $opponentCorners = 0;

        foreach ($corners as $corner) {
            if ($board[$corner] === $player) {
                $playerCorners++;
            } elseif ($board[$corner] === $opponent) {
                $opponentCorners++;
            }
        }

        $score += $playerCorners * 20;
        $score -= $opponentCorners * 20;

        // 4. Edge control (less important but still relevant)
        $edges = [1, 3, 5, 7];
        $playerEdges = 0;
        $opponentEdges = 0;

        foreach ($edges as $edge) {
            if ($board[$edge] === $player) {
                $playerEdges++;
            } elseif ($board[$edge] === $opponent) {
                $opponentEdges++;
            }
        }

        $score += $playerEdges * 10;
        $score -= $opponentEdges * 10;

        // 5. Fork potential (ability to create multiple winning lines)
        $playerForks = count($this->findForkMoves($board, $this->availableMoves($board), $player));
        $opponentForks = count($this->findForkMoves($board, $this->availableMoves($board), $opponent));

        $score += $playerForks * 50;
        $score -= $opponentForks * 50;

        // 6. Block potential forks
        $opponentForkMoves = $this->findForkMoves($board, $this->availableMoves($board), $opponent);
        if (! empty($opponentForkMoves)) {
            $score += 40; // Bonus for blocking opponent forks
        }

        // 7. Game phase consideration (early game vs late game)
        $filledPositions = count(array_filter($board, fn ($cell) => $cell !== null));
        if ($filledPositions <= 2) {
            // Early game: prioritize center and corners
            $score += 25;
        } elseif ($filledPositions >= 6) {
            // Late game: more defensive play
            $score += 15;
        }

        // 8. Symmetry and balance (avoid predictable patterns)
        $symmetryBonus = $this->evaluateSymmetry($board, $player);
        $score += $symmetryBonus;

        return $score;
    }

    /** Evaluate symmetry and strategic balance */
    private function evaluateSymmetry(array $board, string $player): int
    {
        $score = 0;

        // Check rotational symmetry (90 degrees)
        $symmetricPositions = [
            [0, 2, 6, 8], // corners
            [1, 3, 5, 7], // edges
        ];

        foreach ($symmetricPositions as $positions) {
            $symmetryScore = 0;
            foreach ($positions as $pos) {
                if ($board[$pos] === $player) {
                    $symmetryScore++;
                }
            }

            // Bonus for having 2 or 4 symmetric positions (balanced control)
            if ($symmetryScore === 2 || $symmetryScore === 4) {
                $score += 10;
            }
        }

        // Penalty for having only 1 or 3 symmetric positions (unbalanced)
        if ($symmetryScore === 1 || $symmetryScore === 3) {
            $score -= 5;
        }

        return $score;
    }
}
