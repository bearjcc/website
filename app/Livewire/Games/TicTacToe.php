<?php

declare(strict_types=1);

namespace App\Livewire\Games;

use App\Games\TicTacToe\Engine;
use App\Games\TicTacToe\TicTacToeGame;
use App\Models\Game;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.app')]
class TicTacToe extends Component
{
    public Game $game;

    public array $board = [];

    public string $currentPlayer = 'X';

    public ?string $winner = null;

    public bool $isDraw = false;

    public string $gameMode = 'pvp'; // pvp, ai-easy, ai-medium, ai-impossible

    public string $playerSymbol = 'X'; // Player chooses X or O when playing AI

    public int $movesCount = 0;

    public array $winningPositions = []; // Track positions that form the winning line

    public int $gameDuration = 0; // Game duration in seconds

    public string $aiDifficulty = ''; // Current AI difficulty for display

    /** Session win/ties counts for info bubbles (X you, Ties, O CPU). */
    public int $xWins = 0;

    public int $ties = 0;

    public int $oWins = 0;

    /** Set from game entry when coming from GamePlay (avoids inline mode selection). */
    public ?string $initialGameMode = null;

    public ?string $initialPlayerSymbol = null;

    public function mount(): void
    {
        if (! isset($this->game->slug)) {
            $this->game = Game::where('slug', 'tic-tac-toe')->firstOrFail();
        }
        if ($this->initialGameMode !== null && $this->initialGameMode !== '') {
            $this->setGameMode($this->initialGameMode, $this->initialPlayerSymbol ?? 'X');
            return;
        }
        $this->newGame();
    }

    public function newGame()
    {
        $game = new TicTacToeGame();
        $state = $game->newGameState();
        $this->board = $state['board'];
        $this->currentPlayer = 'X';
        $this->winner = null;
        $this->isDraw = false;
        $this->movesCount = 0;
        $this->winningPositions = [];
        $this->gameDuration = 0;
        $this->aiDifficulty = match ($this->gameMode) {
            'ai-easy' => 'Easy',
            'ai-medium' => 'Medium',
            'ai-impossible' => 'Impossible',
            default => ''
        };
    }

    public function setGameMode(string $mode, string $symbol = 'X')
    {
        $this->gameMode = $mode;
        $this->playerSymbol = $symbol;
        $this->newGame();
    }

    public function makeMove(int $position)
    {
        // Game over check
        if ($this->winner !== null || $this->isDraw) {
            return;
        }

        // Position already taken
        if ($this->board[$position] !== null) {
            return;
        }

        // AI mode: only allow player's turn
        if ($this->gameMode !== 'pvp' && $this->currentPlayer !== $this->playerSymbol) {
            return;
        }

        $engine = new Engine();

        // Make the player's move
        $this->board = $engine->makeMove($this->board, $position, $this->currentPlayer);
        $this->movesCount++;

        // Check for winner or draw
        $this->winner = $engine->winner($this->board);
        $this->isDraw = $engine->isDraw($this->board);

        if ($this->winner === null && ! $this->isDraw) {
            // Switch turns
            $this->currentPlayer = $this->currentPlayer === 'X' ? 'O' : 'X';

            // AI turn (if applicable)
            if ($this->gameMode !== 'pvp' && $this->currentPlayer !== $this->playerSymbol) {
                // Add a delay to let the player's move animation complete
                $this->dispatch('ai-move-delay');
            }
        } else {
            $this->recordSessionResult();
            if ($this->winner) {
                $this->dispatch('game-completed', [
                    'winner' => $this->winner,
                    'moves' => $this->movesCount,
                    'mode' => $this->gameMode,
                    'duration' => round($this->movesCount / 2, 1),
                ]);
            }
        }
    }

    public function makeAiMove()
    {
        $engine = new Engine();

        // Determine AI difficulty
        $aiMove = match ($this->gameMode) {
            'ai-easy' => $engine->aiEasy($this->board, $this->currentPlayer),
            'ai-medium' => $engine->aiMedium($this->board, $this->currentPlayer),
            'ai-impossible' => $engine->bestMoveMinimax($this->board, $this->currentPlayer),
            default => $engine->bestMoveMinimax($this->board, $this->currentPlayer),
        };

        // Make AI move
        $this->board = $engine->makeMove($this->board, $aiMove, $this->currentPlayer);
        $this->movesCount++;

        // Check for winner or draw after AI move
        $this->winner = $engine->winner($this->board);
        $this->isDraw = $engine->isDraw($this->board);

        if ($this->winner) {
            $this->winningPositions = $this->findWinningPositions($this->board, $this->winner);
        }

        if ($this->winner === null && ! $this->isDraw) {
            $this->currentPlayer = $this->playerSymbol;
        } else {
            $this->recordSessionResult();
            if ($this->winner) {
                $this->dispatch('game-completed', [
                    'winner' => $this->winner,
                    'moves' => $this->movesCount,
                    'mode' => $this->gameMode,
                    'duration' => round($this->movesCount / 2, 1),
                    'winningPositions' => $this->winningPositions,
                ]);
            }
        }
    }

    private function recordSessionResult(): void
    {
        if ($this->winner === 'X') {
            $this->xWins++;
        } elseif ($this->winner === 'O') {
            $this->oWins++;
        } elseif ($this->isDraw) {
            $this->ties++;
        }
    }

    /** Find the positions that form the winning line */
    private function findWinningPositions(array $board, string $winner): array
    {
        $lines = [
            [0, 1, 2], [3, 4, 5], [6, 7, 8], // rows
            [0, 3, 6], [1, 4, 7], [2, 5, 8], // cols
            [0, 4, 8], [2, 4, 6],         // diags
        ];

        foreach ($lines as $line) {
            // Check if all positions in the line are filled and match the winner
            $positionsFilled = array_filter($line, fn ($pos) => $board[$pos] === $winner);
            if (count($positionsFilled) === 3) {
                return $line;
            }
        }

        return [];
    }

    /** Turn/status line for game chrome bar. */
    public function getChromeTurnText(): string
    {
        if ($this->winner !== null) {
            return $this->winner === 'X' ? "Moon wins" : "Star wins";
        }
        if ($this->isDraw) {
            return "Draw";
        }
        if ($this->gameMode === 'pvp') {
            return $this->currentPlayer === 'X' ? "Moon's turn" : "Star's turn";
        }
        return $this->currentPlayer === $this->playerSymbol ? 'Your turn' : 'AI thinking...';
    }

    public function render(): \Illuminate\Contracts\View\View
    {
        return view('livewire.games.tic-tac-toe');
    }
}
