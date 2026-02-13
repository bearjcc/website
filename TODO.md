# TODO: Ursa Minor Website Audit & Roadmap

This file contains the prioritized tasks based on the senior developer and security audit of the repository.

## 🔴 High Priority: Infrastructure & Critical Fixes
- [ ] **Fix Breaking Tests**: Investigate and fix `horizontal win`, `vertical win`, `diagonal win` failures in `Connect4EngineTest`.
- [ ] **Stabilize HTTP Tests**: Fix `TestsFeatureErrorHandlingTest` and `TestsFeatureExampleTest` which are failing due to environment/routing/seeding mismatches.
- [ ] **Add Game Seeds**: Ensure `ProductionSeeder` or a specific `GameSeeder` is part of the test setup to avoid `ModelNotFoundException`.
- [ ] **Consolidate Navigation**: Resolve the redundancy between `partials/nav.blade.php`, `partials/footer.blade.php`, and the inline navigation in `layouts/app.blade.php`. Use the `x-ui` components consistently.
- [ ] **Environment Setup**: Standardize `.env.example` to ensure local developers have one-click setup for testing.

## 🟠 Medium Priority: Feature Alignment & DRY
- [ ] **Chess & Checkers Implementation**: Complete the implementation of the Chess and Checkers engines. Use the existing AI/minimax framework where applicable.
- [ ] **Score Tracking Integration**: The `Score` model exists but is not fully integrated into the game loop for the 6 core games (unlike Letter Walker's custom implementation).
- [ ] **Standardize API Responses**: Align the `SudokuController` and `LetterWalkerScoreController` response formats.
- [ ] **Standardize Game State Storage**: Migrate Games to use the `game-storage.js` helper consistently for localStorage persistence.
- [ ] **Refactor Letter Walker to Livewire**: (Optional but Recommended) Port the Letter Walker JS game into a Livewire component to match the rest of the site's architecture, enabling easier integration with the `Score` model and Auth system.

## 🟡 Low Priority: UI/UX & Polish
- [ ] **Letter Walker Branding Audit**: Ensure the "Wordle" branding for Letter Walker remains independent but visually high-quality. (Current: separate CSS/JS in `public/assets`).
- [ ] **Lowercase Mode Consistency**: Audit all components to ensure the `lowercase-mode` CSS class/config is respected everywhere, especially in game messages.
- [ ] **A11y Audit**: Perform a deep audit of ARIA labels in the 6 recently polished games.
- [ ] **Game Loading States**: Add starfield-themed loading skeletons to prevent layout shift when Livewire components load.

## 🟢 Security Audit Findings
- [ ] **Mass Assignment Check**: Audit `LetterWalkerScoreController@store` to ensure `user_id` cannot be hijacked via request data (ensure it's set via `auth()->id()`).
- [ ] **XSS Audit**: Verify that `player_name` in the Letter Walker leaderboard is properly escaped in the Blade view and JS rendering.
- [ ] **Rate Limiting**: Apply rate limiting to `api/letter-walker/score` to prevent leaderboard spam.
- [ ] **CSRF Verification**: Ensure custom JS fetches (like in Letter Walker) include the `X-CSRF-TOKEN` header from the meta tag.

---
**AI Generated Audit Report - Phase 1 Complete**
