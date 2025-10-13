/**
 * Game state persistence using localStorage
 * 
 * Coordinates with Livewire components via events to save/load game state.
 * Allows players to resume games after closing browser.
 */

window.gameStorage = {
    /**
     * Save game state to localStorage.
     */
    save(key, state) {
        try {
            localStorage.setItem(key, JSON.stringify(state));
        } catch (e) {
            console.error('Failed to save game state:', e);
        }
    },
    
    /**
     * Load game state from localStorage.
     */
    load(key) {
        try {
            const data = localStorage.getItem(key);
            return data ? JSON.parse(data) : null;
        } catch (e) {
            console.error('Failed to load game state:', e);
            return null;
        }
    },
    
    /**
     * Clear saved game state.
     */
    clear(key) {
        try {
            localStorage.removeItem(key);
        } catch (e) {
            console.error('Failed to clear game state:', e);
        }
    },
    
    /**
     * Get all saved game keys.
     */
    getAllKeys() {
        const keys = [];
        for (let i = 0; i < localStorage.length; i++) {
            const key = localStorage.key(i);
            if (key && key.startsWith('game_state_')) {
                keys.push(key);
            }
        }
        return keys;
    }
};

/**
 * Listen for Livewire events to save/load/clear state.
 */
document.addEventListener('livewire:init', () => {
    // Save game state
    Livewire.on('save-game-state', ({ key, state }) => {
        gameStorage.save(key, state);
    });
    
    // Clear game state
    Livewire.on('clear-game-state', ({ key }) => {
        gameStorage.clear(key);
    });
    
    // Request saved state (component will handle response)
    Livewire.on('request-saved-state', ({ key }) => {
        const state = gameStorage.load(key);
        if (state) {
            Livewire.dispatch('saved-state-loaded', { key, state });
        }
    });
});

/**
 * Auto-save on page unload (optional).
 * Components should already be saving after each move.
 */
window.addEventListener('beforeunload', () => {
    // Games handle their own saving via the trait
    // This is just a safety net if needed in the future
});

