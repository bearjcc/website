# Ursa Minor Games Website - Project-Specific Rules

## Project Identity

**Name**: Ursa Minor Games  
**Theme**: Simple elegance of the night sky  
**Tagline**: "Where games are born under the stars"  
**Tech Stack**: Laravel 12.x, Livewire, TALL Stack  
**Local Dev**: http://website.test/ (Laravel Herd)  
**Deployment**: Railway (auto-deploy from main branch)

## Core Design Philosophy: The Peaceful Night Sky

**THIS IS THE MOST IMPORTANT PRINCIPLE FOR THIS PROJECT.**

Our motif is: **"The peaceful quiet soothing yet joyful night sky"**

Every element must feel like it belongs like a star in a constellation — never noise.

### Decision Filter
Before adding ANY feature, UI element, copy, or interaction, ask:
- Does this feel like peaceful night sky?
- Does this belong like a star in a constellation, or is it noise?
- Is this calm, collected, and welcoming?

**If answer is no to any of these questions, don't add it.**

## Critical Development Rules

### Development Server
- **ALWAYS use:** `http://website.test/`
- **DO NOT** start your own dev server
- **DO NOT** use `localhost:8000` or any other port
- Laravel Herd handles the server automatically

### Anti-Patterns (CRITICAL)
**NEVER:**
- ❌ Use emoji in production code (absolutely forbidden)
- ❌ Use purple gradients (avoid AI-typical design patterns)
- ❌ Modify .env files (security requirement)
- ❌ Claim success without verifying fixes actually work
- ❌ Create status/summary/completion files
- ❌ Start your own dev server

**ALWAYS:**
- ✅ Test locally at http://website.test/ before committing
- ✅ Verify every change by checking actual output/behavior
- ✅ Acknowledge when things break and diagnose properly
- ✅ One feature at a time (incremental changes only)
- ✅ Follow conventional commits with professional tone

## File References for Context

Load these files when working on specific tasks:

**Always load first:**
- `@001-core-project-conventions.mdc` - Complete project conventions and architecture
- `@docs/PROJECT_STRUCTURE.md` - Where to find/add things
- `@docs/BRAND_GUIDELINES.md` - Night sky design philosophy

**Context-specific:**
- `@003-tech-stack.mdc` - TALL stack patterns and usage
- `@030-code-standards.mdc` - PHP, Blade, and JS standards
- `@040-commit-standards.mdc` - Git workflow and commit format
- `@100-ui-ux-patterns.mdc` - UI components and design patterns
- `@150-component-implementation.mdc` - Livewire and Blade component patterns
- `@200-domain-guidelines.mdc` - Game development patterns
- `@300-testing-and-guardrails.mdc` - Testing requirements and strategies

## TALL Stack Usage
- **Laravel** - Backend framework, service container
- **Livewire** - Interactive UI components (preferred over React/Vue)
- **Tailwind CSS** - Utility-first styling (future implementation)
- **Alpine.js** - Lightweight JavaScript for UI interactions

### TALL Stack Decision Framework

#### Component Choice Decision Tree
- **Styling needed?** → Use Tailwind CSS utilities only
- **Client-side UI toggle?** → Use Alpine.js (no server round-trip)
- **Database interaction needed?** → Use Livewire component
- **Authentication/business logic?** → Use Laravel backend

#### Specific Patterns
| Feature | Tool | Why |
|---------|------|-----|
| Dropdown/Modal | Alpine | Pure UI, no persistence |
| Form submission | Livewire | Validation + database |
| Real-time search | Livewire | Server queries |
| Pagination | Livewire | Server-side data |
| Tabs/Accordion | Alpine | UI state only |
| Shopping cart | Livewire | Persistence required |
| Dark mode toggle | Alpine | UI preference only |

#### Integration Best Practices
```blade
<!-- Sync Livewire property with Alpine -->
<div x-data="{ show: @entangle('showSuccess').live }">
    <div x-show="show" x-transition>Success message</div>
</div>
```

#### Performance Optimization
- **Debounce inputs**: `wire:model.live.debounce.300ms="search"`
- **Lazy load**: `<livewire:expensive-component lazy />`
- **Blur updates**: `wire:model.blur="title"` (not every keystroke)
- **Alpine for speed**: UI toggles, immediate feedback

#### Anti-Patterns to Avoid
- ❌ **Livewire for dropdown menus** → Use Alpine
- ❌ **Alpine for data fetching** → Use Livewire/Blade
- ❌ **Inline styles with Tailwind** → Use utility classes only
- ❌ **No debouncing on search** → Add `.debounce.300ms`
- ❌ **Business logic in views** → Move to Livewire methods

#### Complete TALL Reference
- **Full decision framework**: `../TALL_STACK_QUICK_REFERENCE.md`
- **Performance patterns**: Debouncing, lazy loading, Alpine optimization
- **Code templates**: Component skeletons, testing patterns
- **Anti-pattern checklist**: Common mistakes to avoid

### Component Architecture
- **Livewire Components** in `app/Livewire/` for interactive UI
- **Blade Components** in `resources/views/components/` for reusable UI
- **Game Engines** in `app/Games/{GameName}/` for game logic
- **Thin Controllers** - Delegate to services and Livewire

### Naming Conventions
- **Models**: Singular, PascalCase (`Game.php`, `User.php`)
- **Controllers**: PascalCase + Controller suffix (`GameController.php`)
- **Livewire**: PascalCase, descriptive (`SudokuGame.php`)
- **Blade Components**: kebab-case in usage, PascalCase in PHP
- **Database Tables**: plural, snake_case (`games`, `user_scores`)

## Code Quality Standards

### PHP Standards
- **PSR-12** compliance with 4-space indentation
- **Strict types**: `declare(strict_types=1);`
- **Type hints** for all method parameters and return types
- **Pint** for code formatting: `./vendor/bin/pint`

### Testing Requirements
- **Target**: 80%+ code coverage
- **Feature tests** for end-to-end functionality
- **Unit tests** for isolated logic testing
- **Test command**: `php artisan test`

## Security Rules

1. **Never read or modify .env files**
2. **Validate all user input**
3. **Use CSRF protection** (`@csrf`)
4. **Escape output** (use `{{ }}` not `{!! !!}`)
5. **Use parameterized queries** (Eloquent handles this)

## Development Workflow

### Before Committing
- [ ] Test locally at http://website.test/
- [ ] Run Pint: `./vendor/bin/pint`
- [ ] Check for linter errors
- [ ] Verify no emoji in production code
- [ ] Ensure no secrets committed
- [ ] Write descriptive commit message

### Conventional Commits
Format: `<type>(<scope>): <description>`

Types: `feat`, `fix`, `docs`, `style`, `refactor`, `perf`, `test`, `chore`

Examples:
```
feat(games): add sudoku game component
fix(header): correct sticky positioning on scroll
docs(brand): update branding guidelines
```

## Asset Management

### Vite Integration
- **Entry point**: `resources/js/app.js`
- **Styles**: `resources/css/app.css`
- **Dev server**: `npm run dev`
- **Build**: `npm run build`
- **Images**: Use `@images/` alias for imports

### File Organization
- **Static assets** in `public/` (bear.svg, robots.txt)
- **Compiled assets** in `public/build/` (handled by Vite)
- **Views** in `resources/views/` with proper component structure

## Success Metrics

Rules working when:
- ✅ All changes tested at http://website.test/
- ✅ No emoji in production code
- ✅ Conventional commits with professional tone
- ✅ 80%+ test coverage maintained
- ✅ Design follows peaceful night sky motif
- ✅ Components are reusable and well-structured
- ✅ Security best practices followed

---

*Built under the stars* | *© Ursa Minor Games*