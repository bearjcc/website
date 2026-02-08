# Feature Extraction Guide

This guide explains how to extract features from existing repositories in `C:\Users\bearj\Herd` and integrate them into the Ursa Minor Games website.

---

## General Extraction Process

### 1. Analysis Phase
- [ ] Read the repository README
- [ ] Identify key features and dependencies
- [ ] Map database schema
- [ ] List required assets
- [ ] Note third-party integrations
- [ ] Check Laravel version compatibility

### 2. Planning Phase
- [ ] Create feature branch
- [ ] Document migration plan
- [ ] Identify namespace changes needed
- [ ] Plan asset migration strategy
- [ ] Create database migration files
- [ ] List configuration changes

### 3. Extraction Phase
- [ ] Copy relevant models
- [ ] Copy controllers
- [ ] Copy Livewire components
- [ ] Copy views and update paths
- [ ] Copy migrations
- [ ] Copy tests
- [ ] Copy configuration files

### 4. Integration Phase
- [ ] Update namespaces
- [ ] Fix import statements
- [ ] Update asset paths (@images alias)
- [ ] Update route definitions
- [ ] Integrate with main navigation
- [ ] Apply Ursa Minor branding

### 5. Testing Phase
- [ ] Run existing tests
- [ ] Add new integration tests
- [ ] Manual testing
- [ ] Mobile testing
- [ ] Performance testing
- [ ] Railway deployment test

### 6. Documentation Phase
- [ ] Update main README
- [ ] Document new features
- [ ] Update roadmap
- [ ] Create user guides
- [ ] Add code comments

---

## Repository-Specific Guides

## Extracting from `formula1predictions`

### Overview
- **Laravel Version**: 11 (compatible with Laravel 12)
- **Key Dependencies**: Livewire 3, Tailwind CSS
- **Database**: Complex schema with races, drivers, teams, predictions
- **Third-party**: F1 API integration

### Key Files to Extract

#### Models (`app/Models/`)
- `Driver.php`
- `Team.php`
- `Circuit.php`
- `Race.php`
- `Season.php`
- `Prediction.php`
- `Standing.php`

#### Controllers (`app/Http/Controllers/`)
- `F1Controller.php`
- `PredictionController.php`
- `LeaderboardController.php`
- `AdminController.php` (if exists)

#### Livewire Components (`app/Livewire/`)
- `PredictionForm.php`
- `Leaderboard.php`
- `RaceSchedule.php`
- `UserProfile.php` (F1-specific)

#### Migrations (`database/migrations/`)
- All F1-related migrations
- Check for foreign key dependencies

#### Views (`resources/views/`)
- `livewire/f1/` - All F1 Livewire views
- `f1/` - Static F1 pages

#### Services (`app/Services/`)
- `F1ApiService.php`
- `ScoringService.php`
- `NotificationService.php`

#### Tests (`tests/`)
- `Feature/F1Test.php`
- `Unit/ScoringTest.php`

### Migration Steps

1. **Create F1 Feature Branch**
```bash
git checkout -b feature/f1-predictions
```

2. **Copy Models**
```bash
# From C:\Users\bearj\Herd\formula1predictions\app\Models
# To C:\Users\bearj\Herd\website\app\Models
cp Driver.php Team.php Circuit.php Race.php Season.php Prediction.php Standing.php
```

3. **Update Namespaces**
All models need namespace: `App\Models`

4. **Copy and Run Migrations**
```bash
# Copy migration files
# Update timestamps if needed
php artisan migrate
```

5. **Copy Controllers**
- Update namespace to `App\Http\Controllers`
- Update model imports

6. **Copy Livewire Components**
- Update namespace to `App\Livewire`
- Update model imports
- Update view references

7. **Copy Views**
- Place in `resources/views/f1/`
- Update asset paths to use `asset()` helper
- Apply Ursa Minor branding (colors, fonts)

8. **Add Routes**
```php
// routes/web.php
Route::prefix('f1')->name('f1.')->group(function () {
    Route::get('/', [F1Controller::class, 'index'])->name('index');
    Route::get('/predict/{race}', [F1Controller::class, 'predict'])->name('predict');
    Route::post('/predict/{race}', [F1Controller::class, 'store'])->name('store');
    Route::get('/leaderboard', [F1Controller::class, 'leaderboard'])->name('leaderboard');
});
```

9. **Environment Variables**
Add to `.env`:
```env
F1_API_KEY=your_api_key_here
F1_API_URL=https://api.f1api.dev
```

10. **Test Integration**
```bash
php artisan test --filter=F1
```

### Design Integration

#### Color Mapping
Formula1Predictions uses its own design system. Map to Ursa Minor colors:
- Replace dark mode colors with night sky gradient
- Use Star Yellow (#fff89a) for accents
- Maintain F1 red for specific F1 branding

#### Component Updates
- Update card styles to match Ursa Minor feature cards
- Use Oswald font family
- Apply backdrop blur effects
- Update button styles

---

## Extracting from `games`

### Overview
- **Laravel Version**: Compatible
- **Key Dependencies**: Livewire, 470+ game assets
- **Database**: Minimal (mostly stateless games)
- **Asset System**: Uses @images alias for bootstrap

### Key Files to Extract

#### Game Classes (`app/Games/`)
Each game has:
- `{GameName}Game.php` - GameInterface implementation
- `{GameName}Engine.php` - Pure game logic
- `AI/{GameName}AI.php` - AI opponent (optional)

#### Livewire Components (`app/Livewire/`)
- `resources/views/livewire/games/{slug}.blade.php`

#### Services
- `app/Services/AssetManager.php`
- `app/Services/UserBestScoreService.php`
- `app/Services/HintEngine.php`

#### Assets (`public/images/`)
- 470+ game pieces, cards, dice, etc.
- Must be migrated carefully

### Migration Strategy: One Game at a Time

Let's use **Sudoku** as the example:

1. **Create Games Directory Structure**
```bash
mkdir -p app/Games/Sudoku
mkdir -p app/Games/Sudoku/AI
mkdir -p resources/views/livewire/games
```

2. **Copy Sudoku Files**
```bash
# From C:\Users\bearj\Herd\games\app\Games\Sudoku\
cp SudokuGame.php SudokuEngine.php
```

3. **Update Namespaces**
```php
namespace App\Games\Sudoku;
```

4. **Copy Livewire Component**
```bash
# Component file
cp app/Livewire/Games/Sudoku.php app/Livewire/Games/

# View file
cp resources/views/livewire/games/sudoku.blade.php resources/views/livewire/games/
```

5. **Copy Required Assets**
Sudoku needs minimal assets (if any). For games with assets:
```bash
# Copy specific game assets
cp -r public/images/sudoku/ public/images/
```

6. **Update Asset Paths**
Change all asset references to use @images alias:
```php
// Old
<img src="{{ asset('images/piece.png') }}">

// New (with Vite)
@vite(['resources/images/piece.png'])
```

7. **Add to Games Database**
Create a games registry:
```php
// database/seeders/GameSeeder.php
DB::table('games')->insert([
    'name' => 'Sudoku',
    'slug' => 'sudoku',
    'category' => 'puzzle',
    'difficulty' => 'medium',
    'description' => 'Classic number puzzle game',
    'icon' => 'sudoku-icon.png',
    'is_active' => true,
]);
```

8. **Create Route**
```php
Route::get('/games/sudoku', fn() => view('livewire.games.sudoku'))->name('games.sudoku');
```

9. **Add to Game Lobby**
Update game lobby to show Sudoku card with link

10. **Test**
```bash
php artisan test --filter=Sudoku
```

### Asset Migration Best Practices

1. **Use Vite for Asset Bundling**
- Don't copy assets to `public/` manually
- Use `@images/` alias in imports
- Let Vite handle cache busting

2. **Organize by Game**
```
resources/images/
├── games/
│   ├── sudoku/
│   ├── chess/
│   ├── checkers/
│   └── ...
```

3. **Update Asset Manager**
Migrate AssetManager service to use new paths

### Games Priority Order

**Phase 1 (Weeks 3-5)**: Easy wins
1. Sudoku - No assets, pure logic
2. 2048 - Minimal assets
3. Tic Tac Toe - Simple assets

**Phase 2 (Weeks 6-8)**: Medium complexity
4. Minesweeper - Simple assets
5. Memory - Card assets needed
6. Snake - Minimal assets

**Phase 3 (Weeks 9-10)**: Complex games
7. Chess - Complex assets and AI
8. Checkers - Board game mechanics
9. Solitaire - Full card deck
10. Poker - Card game + AI

---

## Extracting from `tavernsandtreasures`

### Overview
- **Laravel Version**: Compatible
- **Key Dependencies**: Complex world-building system
- **Database**: Extensive lore database
- **Content**: GAME_BIBLE.md with comprehensive world lore

### Key Files to Extract

#### Models
- Character, Region, Settlement, Species, etc.
- Item, Food, Crafting, Recipe models

#### Wiki System
- Article management
- Version control
- Category/tag system
- Search functionality

#### Content
- GAME_BIBLE.md (convert to wiki articles)
- Character profiles
- Region descriptions
- Lore and legends

### Migration Strategy

1. **Set Up Wiki Infrastructure** (Phase 5)
```bash
# Create wiki structure
php artisan make:controller WikiController
php artisan make:model Article -m
php artisan make:model Category -m
```

2. **Create Article Schema**
```php
Schema::create('articles', function (Blueprint $table) {
    $table->id();
    $table->string('title');
    $table->string('slug')->unique();
    $table->text('content');
    $table->string('template')->nullable(); // character, location, item
    $table->foreignId('category_id')->nullable();
    $table->foreignId('user_id'); // author
    $table->timestamps();
    $table->softDeletes();
});
```

3. **Import GAME_BIBLE Content**
- Parse GAME_BIBLE.md
- Split into individual articles
- Create database entries
- Maintain cross-references

4. **Implement Wiki Features**
- Markdown editor (TipTap or similar)
- Version history
- Search (Laravel Scout with Meilisearch)
- Categories and tags
- Article templates

5. **Add Collaboration Tools**
- User roles (viewer, editor, admin)
- Approval workflow
- Comments system
- Change notifications

---

## Extracting from `agency`

### Overview
- **Laravel Version**: 12 (same as website)
- **Key Dependencies**: TALL stack, Reverb for real-time
- **Database**: Card management system
- **Focus**: Board game prototyping

### Key Files to Extract

#### Card System
- `app/Models/Card.php`
- `app/Console/Commands/ImportCards.php`
- `app/Console/Commands/ManageCards.php`
- Card import/export services

#### Game Engine
- State management
- Turn-based mechanics
- Multiplayer support

#### Analytics
- Player behavior tracking
- Balance analysis tools
- Statistics aggregation

### Migration Strategy

1. **Extract Card Management** (Phase 4)
```bash
# Copy card system
cp app/Models/Card.php
cp app/Console/Commands/ImportCards.php
cp app/Console/Commands/ManageCards.php
```

2. **Set Up Card Database**
```bash
php artisan migrate
```

3. **Import Game Data**
```bash
php artisan cards:import storage/cards/game_data.json
```

4. **Build Game Interface**
- Create game board Livewire component
- Implement card interactions
- Add real-time updates

5. **Add Playtesting Tools**
- Analytics dashboard
- Feedback forms
- Balance reporting

---

## Common Integration Patterns

### Updating Namespaces
```bash
# Use find and replace
# Old: App\Http\Controllers\SourceRepo
# New: App\Http\Controllers
```

### Updating Asset Paths
```php
// Old (direct public path)
asset('images/game.png')

// New (Vite with @images alias)
@vite(['resources/images/game.png'])
```

### Applying Ursa Minor Branding

1. **Colors**
```css
/* Replace old colors with Ursa Minor palette */
--primary: #fff89a; /* Star Yellow */
--background: #000000; /* Night Black */
--accent: #002d58; /* Evening Blue */
```

2. **Fonts**
```css
font-family: 'Oswald', sans-serif;
```

3. **Components**
Use Ursa Minor component patterns:
- Feature cards with backdrop blur
- Night sky gradients
- Subtle animations
- Yellow accent borders

### Testing Integration

1. **Run Extracted Tests**
```bash
php artisan test --filter=ExtractedFeature
```

2. **Add Integration Tests**
```php
test('extracted feature integrates with main site', function () {
    // Test navigation
    // Test branding consistency
    // Test data flow
});
```

3. **Manual Testing Checklist**
- [ ] Feature works standalone
- [ ] Navigation integration works
- [ ] Branding is consistent
- [ ] Mobile responsive
- [ ] No console errors
- [ ] Assets load correctly
- [ ] Database operations work
- [ ] Tests pass

---

## Troubleshooting Common Issues

### Issue: Asset paths break after extraction
**Solution**: Use @images alias and Vite bundling

### Issue: Namespace conflicts
**Solution**: Use IDE refactoring tools, find/replace carefully

### Issue: Database migration conflicts
**Solution**: Review and merge migration files, update timestamps

### Issue: Route conflicts
**Solution**: Use route prefixes and name prefixes

### Issue: Different Laravel versions
**Solution**: Check compatibility, update deprecated methods

### Issue: Design inconsistency
**Solution**: Create component library, apply systematically

---

## Extraction Checklist Template

Use this checklist for each feature extraction:

### Pre-Extraction
- [ ] Feature analyzed and documented
- [ ] Migration plan created
- [ ] Feature branch created
- [ ] Dependencies identified

### During Extraction
- [ ] Models copied and updated
- [ ] Controllers copied and updated
- [ ] Livewire components copied and updated
- [ ] Views copied and updated
- [ ] Migrations copied and run
- [ ] Routes added
- [ ] Assets migrated
- [ ] Tests copied and updated

### Post-Extraction
- [ ] Namespaces updated
- [ ] Asset paths updated
- [ ] Branding applied
- [ ] Tests passing
- [ ] Manual testing complete
- [ ] Documentation updated
- [ ] Ready for PR

### Deployment
- [ ] Railway deployment tested
- [ ] Environment variables configured
- [ ] Database migrations run
- [ ] Assets loading correctly
- [ ] Feature live and working

---

## Best Practices

1. **One Feature at a Time**: Don't try to extract multiple features simultaneously
2. **Test Frequently**: Test after each major step
3. **Commit Often**: Small commits with clear messages
4. **Update Documentation**: Keep docs in sync with code
5. **Maintain Compatibility**: Ensure Railway can build and deploy
6. **Apply Branding**: Consistency is key to professional appearance
7. **Preserve Tests**: Tests are valuable documentation
8. **Ask for Help**: Complex extractions may need clarification

---

## Resources

- [Laravel Upgrade Guide](https://laravel.com/docs/upgrade)
- [Livewire Migration Guide](https://laravel-livewire.com/docs/upgrading)
- [Vite Asset Bundling](https://laravel.com/docs/vite)
- [Ursa Minor Design Bible](../DESIGN_BIBLE.md)

---

*Remember: "Eat the elephant one bite at a time." Extract and integrate one feature completely before moving to the next.*

