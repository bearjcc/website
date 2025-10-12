<?php

namespace App\Livewire\Games;

use App\Games\TicTacToe\Engine;
use App\Games\TicTacToe\TicTacToeGame;
use Livewire\Component;

class TicTacToe extends Component
{
    public array $board = [];
    public string $currentPlayer = 'X';
    public ?string $winner = null;
    public bool $isDraw = false;
    public string $gameMode = 'pvp'; // pvp, ai-easy, ai-medium, ai-hard
    public string $playerSymbol = 'X'; // Player chooses X or O when playing AI
    public int $movesCount = 0;

    public function mount()
    {
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
        
        if ($this->winner === null && !$this->isDraw) {
            // Switch turns
            $this->currentPlayer = $this->currentPlayer === 'X' ? 'O' : 'X';
            
            // AI turn (if applicable)
            if ($this->gameMode !== 'pvp' && $this->currentPlayer !== $this->playerSymbol) {
                $this->makeAiMove();
            }
        }
    }

    protected function makeAiMove()
    {
        $engine = new Engine();
        
        // Determine AI difficulty
        $aiMove = match($this->gameMode) {
            'ai-easy' => $engine->aiEasy($this->board, $this->currentPlayer),
            'ai-medium' => $engine->aiMedium($this->board, $this->currentPlayer),
            'ai-hard' => $engine->aiHard($this->board, $this->currentPlayer),
            default => $engine->bestMoveMinimax($this->board, $this->currentPlayer),
        };
        
        // Make AI move
        $this->board = $engine->makeMove($this->board, $aiMove, $this->currentPlayer);
        $this->movesCount++;
        
        // Check for winner or draw after AI move
        $this->winner = $engine->winner($this->board);
        $this->isDraw = $engine->isDraw($this->board);
        
        if ($this->winner === null && !$this->isDraw) {
            // Switch back to player
            $this->currentPlayer = $this->playerSymbol;
        }
    }

    public function render()
    {
        return view('livewire.games.tic-tac-toe');
    }
}
