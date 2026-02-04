// Seeded random number generator
class SeededRandom {
  constructor(seed) {
    this.seed = seed;
  }

  // Simple linear congruential generator
  next() {
    this.seed = (this.seed * 9301 + 49297) % 233280;
    return this.seed / 233280;
  }

  nextInt(min, max) {
    return Math.floor(this.next() * (max - min + 1)) + min;
  }

  // Generate random letter (A-Z, no accents)
  nextLetter() {
    const letters = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
    return letters[this.nextInt(0, letters.length - 1)];
  }
}

// Game state
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
  selectionDirection: null,
  dictionaryLoaded: false,
  lastSavedAt: null,
};

// Get date-based seed
function getDailySeed(date = new Date()) {
  const year = date.getFullYear();
  const month = date.getMonth() + 1;
  const day = date.getDate();
  return parseInt(
    `${year}${String(month).padStart(2, "0")}${String(day).padStart(2, "0")}`,
  );
}

// Save game state to localStorage
function saveGameState() {
  const stateToSave = {
    grid: gameState.grid,
    moves: gameState.moves,
    score: gameState.score,
    foundWords: gameState.foundWords,
    puzzleNumber: gameState.puzzleNumber,
    dailySeed: getDailySeed().toString(),
    savedAt: new Date().toISOString(),
  };

  localStorage.setItem("letterWalkerState", JSON.stringify(stateToSave));
  gameState.lastSavedAt = Date.now();
}

// Load game state from localStorage
function loadGameState() {
  const saved = localStorage.getItem("letterWalkerState");

  if (!saved) return false;

  try {
    const savedState = JSON.parse(saved);
    const currentSeed = getDailySeed().toString();

    // Only load if saved from today
    if (savedState.dailySeed !== currentSeed) {
      clearSavedState();
      return false;
    }

    // Restore state
    gameState.grid = savedState.grid;
    gameState.moves = savedState.moves;
    gameState.score = savedState.score;
    gameState.foundWords = savedState.foundWords;
    gameState.puzzleNumber = savedState.puzzleNumber || 1;
    gameState.rng = new SeededRandom(
      parseInt(savedState.dailySeed) + gameState.puzzleNumber,
    );

    return true;
  } catch (error) {
    console.error("Error loading saved state:", error);
    clearSavedState();
    return false;
  }
}

// Clear saved state
function clearSavedState() {
  localStorage.removeItem("letterWalkerState");
}

// Auto-save helper
function autoSave() {
  saveGameState();
}

// Initialize game
function initGame() {
  const dailySeed = getDailySeed();

  // Try to load saved state
  if (!loadGameState()) {
    // Initialize new game if no saved state
    gameState.rng = new SeededRandom(dailySeed + gameState.puzzleNumber);

    // Initialize 8x8 grid
    gameState.grid = [];
    for (let row = 0; row < 8; row++) {
      gameState.grid[row] = [];
      for (let col = 0; col < 8; col++) {
        gameState.grid[row][col] = {
          letter: gameState.rng.nextLetter(),
          hidden: false,
        };
      }
    }

    gameState.moves = 0;
    gameState.score = 0;
    gameState.foundWords = [];
    gameState.selectedCells = [];
  }

  updateDisplay();
  renderGrid();
  updateDateDisplay();
  hideMessage();
}

// Render grid
function renderGrid() {
  const gridContainer = document.getElementById("grid");
  gridContainer.innerHTML = "";

  for (let row = 0; row < 8; row++) {
    for (let col = 0; col < 8; col++) {
      const cell = document.createElement("div");
      cell.className = "grid-cell";
      cell.dataset.row = row;
      cell.dataset.col = col;

      const cellData = gameState.grid[row][col];
      cell.textContent = cellData.letter;

      if (cellData.hidden) {
        cell.classList.add("hidden");
      }

      if (
        gameState.selectedCells.some((sc) => sc.row === row && sc.col === col)
      ) {
        cell.classList.add("selected");
      }

      // Mouse events
      cell.addEventListener("mousedown", handleCellMouseDown);
      cell.addEventListener("mouseenter", handleCellMouseEnter);
      cell.addEventListener("mouseup", handleCellMouseUp);

      // Touch events
      cell.addEventListener("touchstart", handleTouchStart, { passive: false });
      cell.addEventListener("touchmove", handleTouchMove, { passive: false });
      cell.addEventListener("touchend", handleTouchEnd, { passive: false });

      gridContainer.appendChild(cell);
    }
  }
}

// Shift row left
function shiftRowLeft(rowIndex) {
  const row = gameState.grid[rowIndex];
  const leftmost = row[0];

  // Shift all elements left
  for (let col = 0; col < 7; col++) {
    row[col] = row[col + 1];
  }

  // Hide leftmost letter
  leftmost.hidden = true;

  // Generate new letter for rightmost position
  row[7] = {
    letter: gameState.rng.nextLetter(),
    hidden: false,
  };

  gameState.moves++;
  clearSelection();
  updateDisplay();
  renderGrid();
  autoSave();
}

// Shift row right
function shiftRowRight(rowIndex) {
  const row = gameState.grid[rowIndex];
  const rightmost = row[7];

  // Shift all elements right
  for (let col = 7; col > 0; col--) {
    row[col] = row[col - 1];
  }

  // Hide rightmost letter
  rightmost.hidden = true;

  // Generate new letter for leftmost position
  row[0] = {
    letter: gameState.rng.nextLetter(),
    hidden: false,
  };

  gameState.moves++;
  clearSelection();
  updateDisplay();
  renderGrid();
  autoSave();
}

// Shift column up
function shiftColUp(colIndex) {
  const topmost = gameState.grid[0][colIndex];

  // Shift all elements up
  for (let row = 0; row < 7; row++) {
    gameState.grid[row][colIndex] = gameState.grid[row + 1][colIndex];
  }

  // Hide topmost letter
  topmost.hidden = true;

  // Generate new letter for bottom position
  gameState.grid[7][colIndex] = {
    letter: gameState.rng.nextLetter(),
    hidden: false,
  };

  gameState.moves++;
  clearSelection();
  updateDisplay();
  renderGrid();
  autoSave();
}

// Shift column down
function shiftColDown(colIndex) {
  const bottommost = gameState.grid[7][colIndex];

  // Shift all elements down
  for (let row = 7; row > 0; row--) {
    gameState.grid[row][colIndex] = gameState.grid[row - 1][colIndex];
  }

  // Hide bottommost letter
  bottommost.hidden = true;

  // Generate new letter for top position
  gameState.grid[0][colIndex] = {
    letter: gameState.rng.nextLetter(),
    hidden: false,
  };

  gameState.moves++;
  clearSelection();
  updateDisplay();
  renderGrid();
  autoSave();
}

// Cell selection handlers
function handleCellMouseDown(e) {
  e.preventDefault();
  const row = parseInt(e.target.dataset.row);
  const col = parseInt(e.target.dataset.col);
  startSelection(row, col);
}

function handleCellMouseEnter(e) {
  if (!gameState.isSelecting) return;

  const row = parseInt(e.target.dataset.row);
  const col = parseInt(e.target.dataset.col);
  updateSelection(row, col);
}

function handleCellMouseUp(e) {
  endSelection();
}

// Touch handlers
function handleTouchStart(e) {
  e.preventDefault();
  const touch = e.touches[0];
  const element = document.elementFromPoint(touch.clientX, touch.clientY);

  if (element && element.classList.contains("grid-cell")) {
    const row = parseInt(element.dataset.row);
    const col = parseInt(element.dataset.col);
    startSelection(row, col);
  }
}

function handleTouchMove(e) {
  e.preventDefault();
  if (!gameState.isSelecting) return;

  const touch = e.touches[0];
  const element = document.elementFromPoint(touch.clientX, touch.clientY);

  if (element && element.classList.contains("grid-cell")) {
    const row = parseInt(element.dataset.row);
    const col = parseInt(element.dataset.col);
    updateSelection(row, col);
  }
}

function handleTouchEnd(e) {
  e.preventDefault();
  endSelection();
}

// Start cell selection
function startSelection(row, col) {
  gameState.isSelecting = true;
  gameState.selectionStart = { row, col };
  gameState.selectionDirection = null;
  gameState.selectedCells = [{ row, col }];
  renderGrid();
  updateSelectedWord();
}

// Update selection during drag
function updateSelection(row, col) {
  if (!gameState.selectionStart) return;

  const start = gameState.selectionStart;
  const rowDiff = row - start.row;
  const colDiff = col - start.col;

  // Only allow horizontal or vertical selection
  if (rowDiff === 0 && colDiff === 0) {
    // Same cell
    gameState.selectedCells = [{ row, col }];
  } else if (Math.abs(rowDiff) > Math.abs(colDiff)) {
    // Vertical selection
    gameState.selectionDirection = "vertical";
    const direction = rowDiff > 0 ? 1 : -1;
    gameState.selectedCells = [];
    for (
      let r = start.row;
      direction > 0 ? r <= row : r >= row;
      r += direction
    ) {
      if (r >= 0 && r < 8) {
        gameState.selectedCells.push({ row: r, col: start.col });
      }
    }
  } else {
    // Horizontal selection
    gameState.selectionDirection = "horizontal";
    const direction = colDiff > 0 ? 1 : -1;
    gameState.selectedCells = [];
    for (
      let c = start.col;
      direction > 0 ? c <= col : c >= col;
      c += direction
    ) {
      if (c >= 0 && c < 8) {
        gameState.selectedCells.push({ row: start.row, col: c });
      }
    }
  }

  renderGrid();
  updateSelectedWord();
}

// End selection
function endSelection() {
  gameState.isSelecting = false;
  gameState.selectionStart = null;
  gameState.selectionDirection = null;
}

// Clear selection
function clearSelection() {
  gameState.selectedCells = [];
  gameState.isSelecting = false;
  gameState.selectionStart = null;
  gameState.selectionDirection = null;
  renderGrid();
  updateSelectedWord();
}

// Get selected word
function getSelectedWord() {
  return gameState.selectedCells
    .sort((a, b) => {
      if (a.row !== b.row) return a.row - b.row;
      return a.col - b.col;
    })
    .map((cell) => gameState.grid[cell.row][cell.col].letter)
    .join("");
}

// Update selected word display
function updateSelectedWord() {
  const selectedWord = getSelectedWord();
  const display = document.getElementById("selected-word");
  const submitBtn = document.getElementById("submit-btn");

  display.textContent = selectedWord;
  submitBtn.disabled = selectedWord.length === 0;
}

// Update display
function updateDisplay() {
  document.getElementById("score").textContent = gameState.score;
  document.getElementById("moves").textContent = gameState.moves;
  document.getElementById("puzzle-num").textContent = gameState.puzzleNumber;

  // Update found words list
  const foundWordsList = document.getElementById("found-words-list");
  foundWordsList.innerHTML = "";

  gameState.foundWords.forEach((word) => {
    const wordElement = document.createElement("div");
    wordElement.className = "found-word";
    wordElement.textContent = word;
    foundWordsList.appendChild(wordElement);
  });
}

// Update date display
function updateDateDisplay() {
  const now = new Date();
  const options = {
    weekday: "long",
    year: "numeric",
    month: "long",
    day: "numeric",
  };
  const dateStr = now.toLocaleDateString("en-US", options);
  document.getElementById("date-display").textContent = dateStr;
}

// Check if word is valid
function isValidWord(word) {
  return (
    word.length >= 3 &&
    word.length <= 8 &&
    gameState.dictionary.has(word.toLowerCase())
  );
}

// Submit word
function submitWord() {
  const word = getSelectedWord();

  if (word.length === 0) {
    showMessage("Please select a word", "error");
    return;
  }

  // Check if dictionary is loaded, if not show loading indicator
  if (!gameState.dictionaryLoaded) {
    const message = document.getElementById("message");
    message.innerHTML = `
      <div style="display: flex; align-items: center; justify-content: center; gap: 10px;">
        <span style="animation: pulse 1s ease-in-out infinite; font-size: 1.2em;">●</span>
        <span>Dictionary loading, please wait...</span>
      </div>
    `;
    message.className = "message info";
    message.style.display = "block";

    // Disable submit button to prevent multiple clicks
    const submitBtn = document.getElementById("submit-btn");
    submitBtn.disabled = true;

    // Set up a listener for when dictionary loads
    const checkInterval = setInterval(() => {
      if (gameState.dictionaryLoaded) {
        clearInterval(checkInterval);
        message.style.display = "none";
        // Re-enable submit button
        submitBtn.disabled = false;
        // Retry submission
        submitWord();
      }
    }, 100);
    return;
  }

  if (!isValidWord(word)) {
    showMessage(`"${word}" is not a valid word`, "error");
    return;
  }

  // Word is valid - game over!
  // Calculate score
  const basePoints = word.length * 10;
  const movePenalty = gameState.moves;
  const multiplier = word.length === 8 ? 2 : 1;
  gameState.score = Math.max(0, (basePoints - movePenalty) * multiplier);
  gameState.foundWords.push(word);

  showMessage(`Game Over! Score: ${gameState.score}`, "success");

  // Show score submission modal
  showScoreModal();
}

// Show message
function showMessage(text, type) {
  const message = document.getElementById("message");
  message.textContent = text;
  message.className = `message ${type}`;

  setTimeout(() => {
    hideMessage();
  }, 3000);
}

// Hide message
function hideMessage() {
  const message = document.getElementById("message");
  message.className = "message";
  message.style.display = "none";
}

// New puzzle
function newPuzzle() {
  gameState.puzzleNumber++;
  clearSavedState();
  initGame();
  showMessage("New puzzle started!", "info");
}

// Share results
function shareResults() {
  const word = gameState.foundWords.length > 0 ? gameState.foundWords[0].toUpperCase() : "None";
  const results =
    `Letter Walker - Puzzle ${gameState.puzzleNumber}\n` +
    `Word: ${word}\n` +
    `Score: ${gameState.score}\n` +
    `Moves: ${gameState.moves}\n`;

  // Try to copy to clipboard
  if (navigator.clipboard && navigator.clipboard.writeText) {
    navigator.clipboard
      .writeText(results)
      .then(() => {
        showMessage("Results copied to clipboard!", "success");
      })
      .catch(() => {
        // Fallback: show alert with results
        alert(results);
      });
  } else {
    // Fallback: show alert with results
    alert(results);
  }
}

// Show score submission modal
function showScoreModal() {
  const nameModal = document.getElementById("name-modal");
  const playerNameInput = document.getElementById("player-name");
  const saveNameBtn = document.getElementById("save-name-btn");
  const cancelNameBtn = document.getElementById("cancel-name-btn");

  // Set final score in modal
  document.getElementById("final-score").textContent = gameState.score;

  nameModal.classList.remove("hidden");
  playerNameInput.value = "";
  playerNameInput.focus();

  // Handle save name
  saveNameBtn.onclick = async () => {
    const playerName = playerNameInput.value.trim() || "Anonymous";
    nameModal.classList.add("hidden");

    try {
      const response = await fetch("/api/letter-walker/score", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
          Accept: "application/json",
        },
        body: JSON.stringify({
          score: gameState.score,
          moves: gameState.moves,
          words_found: 1,
          puzzle_number: gameState.puzzleNumber,
          player_name: playerName,
        }),
      });

      const data = await response.json();

      if (data.success) {
        showMessage("Score saved to leaderboard!", "success");
        await refreshLeaderboard();
      } else {
        showMessage(
          data.message || "Failed to save score. Please try again.",
          "error",
        );
      }
    } catch (error) {
      console.error("Score submission error:", error);
      showMessage("Error saving score. Please try again.", "error");
    }
  };

  // Handle cancel
  cancelNameBtn.onclick = () => {
    nameModal.classList.add("hidden");
  };

  // Close on escape key
  const handleEscape = (e) => {
    if (e.key === "Escape") {
      nameModal.classList.add("hidden");
      document.removeEventListener("keydown", handleEscape);
    }
  };
  document.addEventListener("keydown", handleEscape);
}

async function refreshLeaderboard() {
  const leaderboardContainer = document.getElementById("leaderboard-body");

  if (!leaderboardContainer) {
    return;
  }

  leaderboardContainer.textContent = "Loading today\u2019s scores\u2026";

  try {
    const response = await fetch("/api/letter-walker/scores/daily", {
      headers: {
        Accept: "application/json",
      },
    });

    if (!response.ok) {
      leaderboardContainer.textContent = "Unable to load leaderboard.";
      return;
    }

    const data = await response.json();
    const scores = Array.isArray(data.scores) ? data.scores : [];

    if (!scores.length) {
      leaderboardContainer.innerHTML =
        '<p class="leaderboard-empty">No scores yet today. Be the first!</p>';
      return;
    }

    const list = document.createElement("div");
    list.className = "leaderboard-list";

    scores.forEach((entry, index) => {
      const row = document.createElement("div");
      row.className = "leaderboard-row";

      const rank = document.createElement("span");
      rank.className = "leaderboard-rank";
      rank.textContent = `#${index + 1}`;

      const name = document.createElement("span");
      name.className = "leaderboard-name";
      name.textContent = entry.player_name || "Anonymous";

      const score = document.createElement("span");
      score.className = "leaderboard-score";
      score.textContent = entry.score;

      row.appendChild(rank);
      row.appendChild(name);
      row.appendChild(score);

      list.appendChild(row);
    });

    leaderboardContainer.innerHTML = "";
    leaderboardContainer.appendChild(list);
  } catch (error) {
    console.error("Error loading leaderboard:", error);
    leaderboardContainer.textContent = "Unable to load leaderboard.";
  }
}

// Load dictionary from txt file
async function loadDictionary() {
  try {
    // Show loading indicator
    const dictLoading = document.getElementById("dict-loading");
    dictLoading.style.display = "flex";

    const response = await fetch("/assets/letter-walker/dictionary.txt");
    const text = await response.text();
    const lines = text.split("\n");

    gameState.dictionary.clear();

    // Process words - dictionary.txt is already lowercase and has no accents
    for (let i = 0; i < lines.length; i++) {
      const word = lines[i].trim();
      if (word.length >= 3 && word.length <= 8) {
        gameState.dictionary.add(word);
      }
    }

    gameState.dictionaryLoaded = true;
    console.log(`Dictionary loaded: ${gameState.dictionary.size} words`);

    // Hide loading indicator
    dictLoading.style.display = "none";
  } catch (error) {
    console.error("Error loading dictionary:", error);
    showMessage(
      "Error loading dictionary. Please refresh the page.",
      "error",
    );
  }
}

// Setup event listeners
// Help modal management
function initializeHelpModal() {
  const helpModal = document.getElementById("help-modal");
  const helpBtn = document.getElementById("help-btn");
  const closeHelpBtn = document.getElementById("close-help-btn");
  const hasSeenHelp = localStorage.getItem("letterWalkerHelpSeen");

  // Show help on first visit
  if (!hasSeenHelp) {
    helpModal.classList.remove("hidden");
  }

  // Help button click
  helpBtn.addEventListener("click", () => {
    helpModal.classList.remove("hidden");
  });

  // Close button click
  closeHelpBtn.addEventListener("click", () => {
    helpModal.classList.add("hidden");
    localStorage.setItem("letterWalkerHelpSeen", "true");
  });

  // Close on background click
  helpModal.addEventListener("click", (e) => {
    if (e.target === helpModal) {
      helpModal.classList.add("hidden");
      localStorage.setItem("letterWalkerHelpSeen", "true");
    }
  });
}

function setupEventListeners() {
  // Row shift buttons
  document.querySelectorAll(".row-btn.left").forEach((btn) => {
    btn.addEventListener("click", () => {
      const row = parseInt(btn.dataset.row);
      shiftRowLeft(row);
    });
  });

  document.querySelectorAll(".row-btn.right").forEach((btn) => {
    btn.addEventListener("click", () => {
      const row = parseInt(btn.dataset.row);
      shiftRowRight(row);
    });
  });

  // Column shift buttons
  document.querySelectorAll(".col-btn.up").forEach((btn) => {
    btn.addEventListener("click", () => {
      const col = parseInt(btn.dataset.col);
      shiftColUp(col);
    });
  });

  document.querySelectorAll(".col-btn.down").forEach((btn) => {
    btn.addEventListener("click", () => {
      const col = parseInt(btn.dataset.col);
      shiftColDown(col);
    });
  });

  // Game control buttons
  document.getElementById("new-puzzle-btn").addEventListener("click", newPuzzle);
  document.getElementById("share-btn").addEventListener("click", shareResults);
  document.getElementById("submit-btn").addEventListener("click", submitWord);
  document.getElementById("clear-btn").addEventListener("click", clearSelection);

  // Global mouse up to end selection
  document.addEventListener("mouseup", () => {
    if (gameState.isSelecting) {
      endSelection();
    }
  });
}

// Initialize game when page loads
document.addEventListener("DOMContentLoaded", () => {
  initializeHelpModal();
  setupEventListeners();
  initGame();
  // Start loading dictionary in background
  loadDictionary();
  refreshLeaderboard();
});
