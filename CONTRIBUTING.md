# Contributing to Ursa Minor Games

Thank you for your interest in contributing! This guide will help you understand our development workflow.

## Development Philosophy

We follow a **"baby steps"** approach:
- Small, incremental changes via direct commits to feature branches
- Larger features developed in feature branches
- Everything merges to `main` through pull requests
- Railway auto-deploys from `main`

## Getting Started

### Prerequisites

- PHP 8.3+
- Composer
- Git
- Node.js & NPM (for future frontend tooling)

### Local Setup

```powershell
# Clone the repository
git clone https://github.com/bearjcc/website.git
cd website

# Install dependencies
composer install

# Set up environment
Copy-Item -Path ".env.example" -Destination ".env"
php artisan key:generate

# Run development server
php artisan serve
```

Visit http://localhost:8000

## Development Workflow

### For Small Changes (Bug fixes, typos, minor improvements)

```powershell
# Make your changes directly on a feature branch
git checkout -b fix/typo-in-homepage

# Make changes
# ... edit files ...

# Commit with conventional commits
git add .
git commit -m "fix(content): correct typo on homepage"

# Push to GitHub
git push origin fix/typo-in-homepage

# Create pull request on GitHub
# After review, merge to main
```

### For New Features

```powershell
# Create feature branch
git checkout -b feature/sudoku-game

# Make incremental commits as you develop
git add .
git commit -m "feat(games): add sudoku board component"

# Continue working
git add .
git commit -m "feat(games): implement sudoku logic"

# Push when ready for review
git push origin feature/sudoku-game

# Create pull request with detailed description
# After review and testing, merge to main
```

## Commit Message Convention

We use [Conventional Commits](https://www.conventionalcommits.org/):

### Format
```
<type>(<scope>): <description>

[optional body]

[optional footer]
```

### Types
- `feat`: New feature
- `fix`: Bug fix
- `docs`: Documentation changes
- `style`: Code style changes (formatting, no logic change)
- `refactor`: Code refactoring
- `perf`: Performance improvements
- `test`: Adding or updating tests
- `chore`: Maintenance tasks
- `ci`: CI/CD changes

### Scopes (optional but recommended)
- `content`: Homepage or content changes
- `games`: Browser games functionality
- `f1`: F1 predictions system
- `board-games`: Board game platform
- `wiki`: World-building wiki
- `ui`: User interface components
- `api`: Backend API changes

### Examples

```bash
# Simple fix
git commit -m "fix(ui): correct header alignment on mobile"

# New feature
git commit -m "feat(games): add sudoku difficulty selector"

# Breaking change
git commit -m "feat(api)!: change user authentication flow

BREAKING CHANGE: old auth tokens are no longer valid"

# Documentation
git commit -m "docs: update README with new installation steps"
```

## Code Style

### PHP
- Follow PSR-12 coding standard
- Use Laravel Pint for automatic formatting:
  ```powershell
  ./vendor/bin/pint
  ```

### Blade Templates
- Use proper indentation (tabs)
- Keep logic minimal in views
- Extract repeated markup into components

### CSS
- Use existing CSS variables for colors
- Group related styles together
- Add comments for complex selectors
- Follow BEM naming when applicable

### JavaScript
- Use modern ES6+ syntax
- Keep functions small and focused
- Comment complex logic
- Avoid inline scripts in Blade files

## Project Structure

```
website/
├── app/
│   ├── Http/Controllers/     # Request handlers
│   ├── Models/                # Database models
│   └── Providers/             # Service providers
├── resources/
│   ├── views/                 # Blade templates
│   │   ├── layouts/          # Layout templates (to be created)
│   │   └── components/       # Reusable components (to be created)
│   ├── css/                   # Future Tailwind setup
│   └── js/                    # Frontend JavaScript
├── public/                    # Static assets
│   ├── style.css             # Main stylesheet
│   ├── script.js             # Starfield animation
│   └── assets/               # Images, fonts, etc.
├── routes/
│   └── web.php               # Application routes
├── database/
│   ├── migrations/           # Database migrations
│   └── seeders/              # Database seeders
└── tests/                    # Automated tests
```

## Testing

### Running Tests
```powershell
# Run all tests
php artisan test

# Run specific test file
php artisan test tests/Feature/HomepageTest.php

# Run with coverage
php artisan test --coverage
```

### Writing Tests
- Write tests for new features
- Test both happy path and edge cases
- Use factories for test data
- Keep tests isolated and independent

## Pull Request Process

1. **Create Feature Branch**
   ```powershell
   git checkout -b feature/your-feature-name
   ```

2. **Make Changes**
   - Write code
   - Write/update tests
   - Update documentation if needed

3. **Self-Review**
   - Run tests: `php artisan test`
   - Format code: `./vendor/bin/pint`
   - Check for console errors
   - Test in browser

4. **Commit and Push**
   ```powershell
   git add .
   git commit -m "feat(scope): description"
   git push origin feature/your-feature-name
   ```

5. **Create Pull Request**
   - Use descriptive title
   - Explain what and why
   - Reference related issues
   - Add screenshots for UI changes

6. **Address Review Feedback**
   - Make requested changes
   - Commit additional fixes
   - Push updates

7. **Merge**
   - After approval, merge to `main`
   - Delete feature branch
   - Railway auto-deploys!

## Code Review Guidelines

### For Authors
- Keep PRs focused and reasonably sized
- Respond to feedback promptly
- Don't take criticism personally
- Explain your decisions when asked

### For Reviewers
- Be constructive and specific
- Praise good solutions
- Ask questions if unclear
- Approve when satisfied

## Database Changes

### Creating Migrations
```powershell
php artisan make:migration create_games_table
```

### Migration Best Practices
- Always provide `down()` method for rollback
- Use descriptive migration names
- Test both `up()` and `down()`
- Don't modify old migrations after they're deployed

## Adding New Dependencies

### Composer Packages
```powershell
composer require vendor/package

# Commit the updated files
git add composer.json composer.lock
git commit -m "chore: add vendor/package dependency"
```

### NPM Packages (future)
```powershell
npm install package-name

# Commit the updated files
git add package.json package-lock.json
git commit -m "chore: add package-name dependency"
```

## Environment Variables

- Never commit `.env` file
- Update `.env.example` when adding new variables
- Document new variables in deployment docs
- Use sensible defaults when possible

## Security

- Never commit secrets or API keys
- Use environment variables for sensitive data
- Validate and sanitize user input
- Follow OWASP security guidelines
- Report security issues privately

## Questions?

- Open an issue for bugs or feature requests
- Start a discussion for ideas or questions
- Check ROADMAP.md for planned features
- Review existing code for patterns

## Recognition

Contributors will be recognized in:
- README.md contributors section (to be added)
- Release notes for significant contributions
- Special thanks for major features

---

**Happy coding! Let's build something great together! 🐻⭐**

