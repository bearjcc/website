/* ==========================================================================
   LETTER WALKER - SCRIPT
   ========================================================================== */

class SeededRandom {
  constructor(seed) {
    this.seed = seed;
  }
  next() {
    this.seed = (this.seed * 9301 + 49297) % 233280;
    return this.seed / 233280;
  }
  nextInt(min, max) {
    return Math.floor(this.next() * (max - min + 1)) + min;
  }
  nextLetter() {
    const letters = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
    return letters[this.nextInt(0, letters.length - 1)];
  }
}

const gameState = {
  grid: [],
  moves: 0,
  score: 0,
  foundWords: [],
  selectedCells: [],
  puzzleNumber: 1,
  rng: null,
  dictionary: new Set(),
  isSelecting: false,
  selectionStart: null,
  dictionaryLoaded: false,
  theme: localStorage.getItem('lw-theme') || 'light',
  lastMoveType: null // Tracks {type: 'row'|'col', index: number, direction: string}
};

// --- Theme Management ---
function initTheme() {
  document.documentElement.setAttribute('data-theme', gameState.theme);
  const toggleBtn = document.getElementById('theme-toggle');
  if (toggleBtn) {
    toggleBtn.addEventListener('click', () => {
      gameState.theme = gameState.theme === 'light' ? 'dark' : 'light';
      document.documentElement.setAttribute('data-theme', gameState.theme);
      localStorage.setItem('lw-theme', gameState.theme);
    });
  }
}

// --- Persistence ---
function getDailySeed(date = new Date()) {
  return parseInt(`${date.getFullYear()}${String(date.getMonth() + 1).padStart(2, "0")}${String(date.getDate()).padStart(2, "0")}`);
}

function saveGameState() {
  const stateToSave = {
    grid: gameState.grid,
    moves: gameState.moves,
    score: gameState.score,
    foundWords: gameState.foundWords,
    puzzleNumber: gameState.puzzleNumber,
    dailySeed: getDailySeed().toString(),
    lastMoveType: gameState.lastMoveType,
  };
  localStorage.setItem("letterWalkerState", JSON.stringify(stateToSave));
}

function loadGameState() {
  const saved = localStorage.getItem("letterWalkerState");
  if (!saved) return false;
  try {
    const savedState = JSON.parse(saved);
    if (savedState.dailySeed !== getDailySeed().toString()) {
      localStorage.removeItem("letterWalkerState");
      return false;
    }
    Object.assign(gameState, savedState);
    gameState.rng = new SeededRandom(parseInt(savedState.dailySeed) + (gameState.puzzleNumber || 0));
    return true;
  } catch (e) { return false; }
}

// --- Core Game Logic ---
function initGame() {
  const dailySeed = getDailySeed();
  if (!loadGameState()) {
    gameState.puzzleNumber = gameState.puzzleNumber || 1;
    gameState.rng = new SeededRandom(dailySeed + gameState.puzzleNumber);
    gameState.grid = Array.from({ length: 8 }, () =>
      Array.from({ length: 8 }, () => ({ letter: gameState.rng.nextLetter(), hidden: false }))
    );
    gameState.moves = 0;
    gameState.score = 0;
    gameState.foundWords = [];
    gameState.lastMoveType = null;
  }
  updateDisplay();
  renderGrid();
  updateSelectedWord();
  updateDateDisplay();
}

function renderGrid() {
  const container = document.getElementById("grid");
  container.innerHTML = "";
  gameState.grid.forEach((row, r) => {
    row.forEach((cellData, c) => {
      const cell = document.createElement("div");
      cell.className = "grid-cell";
      if (cellData.hidden) cell.classList.add("hidden");
      if (gameState.selectedCells.some(sc => sc.row === r && sc.col === c)) cell.classList.add("selected");
      cell.textContent = cellData.letter;
      cell.dataset.row = r;
      cell.dataset.col = c;
      
      cell.addEventListener("mousedown", (e) => startSelection(r, c));
      cell.addEventListener("mouseenter", (e) => updateSelection(r, c));
      cell.addEventListener("touchstart", (e) => { e.preventDefault(); startSelection(r, c); }, {passive: false});
      
      container.appendChild(cell);
    });
  });
}

// --- Movement ---
function shiftRow(rowIndex, direction) {
  const row = gameState.grid[rowIndex];
  if (direction === 'left') {
    const leftmost = row.shift();
    leftmost.hidden = true;
    row.push({ letter: gameState.rng.nextLetter(), hidden: false });
  } else {
    const rightmost = row.pop();
    rightmost.hidden = true;
    row.unshift({ letter: gameState.rng.nextLetter(), hidden: false });
  }
  
  // Only count as a new move if this is a different row/col/direction than the last move
  const currentMoveType = { type: 'row', index: rowIndex, direction: direction };
  if (!isSameMoveType(gameState.lastMoveType, currentMoveType)) {
    gameState.moves++;
    gameState.lastMoveType = currentMoveType;
  }
  
  clearSelection();
  renderGrid();
  updateDisplay();
  saveGameState();
}

function shiftCol(colIndex, direction) {
  if (direction === 'up') {
    const topmost = gameState.grid[0][colIndex];
    topmost.hidden = true;
    for (let r = 0; r < 7; r++) gameState.grid[r][colIndex] = gameState.grid[r+1][colIndex];
    gameState.grid[7][colIndex] = { letter: gameState.rng.nextLetter(), hidden: false };
  } else {
    const bottommost = gameState.grid[7][colIndex];
    bottommost.hidden = true;
    for (let r = 7; r > 0; r--) gameState.grid[r][colIndex] = gameState.grid[r-1][colIndex];
    gameState.grid[0][colIndex] = { letter: gameState.rng.nextLetter(), hidden: false };
  }
  
  // Only count as a new move if this is a different row/col/direction than the last move
  const currentMoveType = { type: 'col', index: colIndex, direction: direction };
  if (!isSameMoveType(gameState.lastMoveType, currentMoveType)) {
    gameState.moves++;
    gameState.lastMoveType = currentMoveType;
  }
  
  clearSelection();
  renderGrid();
  updateDisplay();
  saveGameState();
}

// Helper function to check if two move types are the same
function isSameMoveType(lastMove, currentMove) {
  if (!lastMove || !currentMove) return false;
  return lastMove.type === currentMove.type &&
         lastMove.index === currentMove.index &&
         lastMove.direction === currentMove.direction;
}

// --- Selection ---
function startSelection(r, c) {
  gameState.isSelecting = true;
  gameState.selectedCells = [{ row: r, col: c }];
  renderGrid();
  updateSelectedWord();
}

function updateSelection(r, c) {
  if (!gameState.isSelecting) return;
  const last = gameState.selectedCells[gameState.selectedCells.length - 1];
  if (last.row === r && last.col === c) return;

  // Only allow horizontal or vertical neighbors
  const isNeighbor = (Math.abs(last.row - r) === 1 && last.col === c) || 
                     (Math.abs(last.col - c) === 1 && last.row === r);
  
  if (isNeighbor) {
    // If re-selecting the previous cell, treat as "undo"
    if (gameState.selectedCells.length > 1) {
      const prev = gameState.selectedCells[gameState.selectedCells.length - 2];
      if (prev.row === r && prev.col === c) {
        gameState.selectedCells.pop();
      } else if (!gameState.selectedCells.some(sc => sc.row === r && sc.col === c)) {
        gameState.selectedCells.push({ row: r, col: c });
      }
    } else if (!gameState.selectedCells.some(sc => sc.row === r && sc.col === c)) {
      gameState.selectedCells.push({ row: r, col: c });
    }
  }
  renderGrid();
  updateSelectedWord();
}

function endSelection() {
  gameState.isSelecting = false;
}

function clearSelection() {
  gameState.selectedCells = [];
  gameState.isSelecting = false;
  renderGrid();
  updateSelectedWord();
}

function updateSelectedWord() {
  const word = gameState.selectedCells.map(sc => gameState.grid[sc.row][sc.col].letter).join("");
  document.getElementById("selected-word").textContent = word;
  document.getElementById("submit-btn").disabled = word.length < 3;
}

// --- UI Updates ---
function updateDisplay() {
  document.getElementById("score").textContent = gameState.score;
  document.getElementById("moves").textContent = gameState.moves;
  document.getElementById("puzzle-num").textContent = gameState.puzzleNumber;
  
  const list = document.getElementById("found-words-list");
  list.innerHTML = "";
  gameState.foundWords.forEach(w => {
    const div = document.createElement("div");
    div.className = "found-word";
    div.textContent = w;
    list.appendChild(div);
  });
}

function updateDateDisplay() {
  const options = { weekday: "long", year: "numeric", month: "long", day: "numeric" };
  document.getElementById("date-display").textContent = new Date().toLocaleDateString("en-US", options);
}

// --- Dictionary & Submission ---
async function loadDictionary() {
  try {
    const loading = document.getElementById("dict-loading");
    loading.style.display = "flex";
    const res = await fetch("/assets/letter-walker/dictionary.txt");
    const text = await res.text();
    text.split("\n").forEach(w => {
      const trimmed = w.trim().toLowerCase();
      if (trimmed.length >= 3 && trimmed.length <= 8) gameState.dictionary.add(trimmed);
    });
    gameState.dictionaryLoaded = true;
    loading.style.display = "none";
  } catch (e) { console.error(e); }
}

function submitWord() {
  const word = gameState.selectedCells.map(sc => gameState.grid[sc.row][sc.col].letter).join("").toLowerCase();
  
  if (!gameState.dictionaryLoaded) {
    showToast("Loading dictionary...", "info");
    return;
  }
  
  if (!gameState.dictionary.has(word)) {
    showToast(`"${word.toUpperCase()}" is not in dictionary`, "error");
    return;
  }

  // Calculate high score logic
  // Penalty is based on total letter count of all found words, not move count
  const totalLetterCount = gameState.foundWords.reduce((sum, w) => sum + w.length, 0) + word.length;
  const basePoints = word.length * 50;
  const movePenalty = totalLetterCount * 5;
  gameState.score = Math.max(0, basePoints - movePenalty);
  gameState.foundWords.push(word.toUpperCase());
  
  showToast(`Found ${word.toUpperCase()}!`, "success");
  showScoreModal();
}

function showToast(text, type) {
  const toast = document.getElementById("message");
  toast.textContent = text;
  toast.className = `toast ${type}`;
  toast.style.display = "block";
  setTimeout(() => toast.style.display = "none", 3000);
}

// --- Modals ---
function initModals() {
  const helpModal = document.getElementById("help-modal");
  document.getElementById("help-btn").addEventListener("click", () => helpModal.classList.remove("hidden"));
  document.getElementById("close-help-btn").addEventListener("click", () => {
    helpModal.classList.add("hidden");
    localStorage.setItem("lw-help-seen", "true");
  });
  if (!localStorage.getItem("lw-help-seen")) helpModal.classList.remove("hidden");

  document.getElementById("cancel-name-btn").addEventListener("click", () => document.getElementById("name-modal").classList.add("hidden"));
}

function showScoreModal() {
  const modal = document.getElementById("name-modal");
  document.getElementById("final-score").textContent = gameState.score;
  modal.classList.remove("hidden");
  
  document.getElementById("save-name-btn").onclick = async () => {
    const name = document.getElementById("player-name").value.trim() || "Anonymous";
    modal.classList.add("hidden");
    try {
      await fetch("/api/letter-walker/score", {
        method: "POST",
        headers: { "Content-Type": "application/json", "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]')?.content },
        body: JSON.stringify({ score: gameState.score, moves: gameState.moves, words_found: 1, puzzle_number: gameState.puzzleNumber, player_name: name })
      });
      showToast("Score saved!", "success");
    } catch (e) { showToast("Failed to save score", "error"); }
  };
}

// --- Initialization ---
document.addEventListener("DOMContentLoaded", () => {
  initTheme();
  initModals();
  initGame();
  loadDictionary();

  // Global events
  document.addEventListener("mouseup", endSelection);
  document.addEventListener("touchend", endSelection);
  
  // Row/Col Shift buttons
  document.addEventListener("click", (e) => {
    const btn = e.target.closest('button');
    if (!btn) return;
    
    if (btn.classList.contains('row-btn')) shiftRow(parseInt(btn.dataset.row), btn.classList.contains('left') ? 'left' : 'right');
    if (btn.classList.contains('col-btn')) shiftCol(parseInt(btn.dataset.col), btn.classList.contains('up') ? 'up' : 'down');
  });

  document.getElementById("new-puzzle-btn").addEventListener("click", () => {
    gameState.puzzleNumber++;
    localStorage.removeItem("letterWalkerState");
    initGame();
  });
  
  document.getElementById("submit-btn").addEventListener("click", submitWord);
  document.getElementById("clear-btn").addEventListener("click", clearSelection);
  document.getElementById("share-btn").addEventListener("click", () => {
    const text = `Letter Walker Puzzle #${gameState.puzzleNumber}\nScore: ${gameState.score}\nWord: ${gameState.foundWords[0] || "???"}`;
    if (navigator.clipboard) {
      navigator.clipboard.writeText(text).then(() => showToast("Copied to clipboard!", "success"));
    } else {
      alert(text);
    }
  });
});
