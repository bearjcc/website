# Cursor Rules for Ursa Minor Games

This directory contains all Cursor AI rule files (`.mdc`) that guide development for the Ursa Minor Games project.

## Organization

All rule files use a numbering system for logical ordering:

### Meta Rules (Always Apply)
- **rules.mdc** - Schema and structure for all .mdc files
- **cursor-rules-location.mdc** - Location enforcement rules

### Core Conventions (001-0xx)
- **001-core-project-conventions.mdc** - Project-wide conventions, architecture, coding standards
- **002-development-workflow.mdc** - Development processes and best practices
- **003-tech-stack.mdc** - Technology stack and dependencies

### Backend/Laravel (020-029)
- **020-laravel-best-practices.mdc** - Laravel patterns and architecture

### Code Quality (030-039)
- **030-code-standards.mdc** - Code quality and style standards

### Process (040-049)
- **040-commit-standards.mdc** - Git workflow and conventional commits

### Deployment (050-059)
- **050-railway-deployment.mdc** - Railway deployment configuration

### UI/UX (100-1xx)
- **100-ui-ux-patterns.mdc** - Design system, frontend standards, accessibility

### Domain (200-2xx)
- **200-domain-guidelines.mdc** - Business logic, models, services, game architecture

### Testing (300-3xx)
- **300-testing-and-guardrails.mdc** - Testing requirements, verification protocols, content guardrails

## File Format

All `.mdc` files must include YAML frontmatter:

```yaml
---
description: "Brief description of the rule file"
globs: "pattern1,pattern2,pattern3"  # Optional if alwaysApply is true
alwaysApply: true  # Optional, defaults to false
---
```

## Critical Project Rules

1. **NO EMOJI** in production code
2. **NO PURPLE GRADIENTS** - Use night sky theme only
3. **VERIFY CHANGES** - Test before claiming success
4. **ACKNOWLEDGE FAILURES** - Be honest about what breaks
5. **TEST LOCALLY** - Always test at http://website.test/
6. **ONE FEATURE AT A TIME** - Incremental development

## Night Sky Color Palette

- Night Black: `#000000`
- Midnight Blue: `#001a33`
- Evening Blue: `#002d58`
- Star White: `#ffffff`
- Star Yellow: `#fff89a`

## Adding New Rules

1. Choose appropriate number range for the category
2. Use descriptive kebab-case filename
3. Include proper frontmatter with description and globs
4. Follow the schema defined in `rules.mdc`
5. Commit with: `docs(cursor): add rule-name`

## Questions?

- Check `rules.mdc` for file schema and structure
- Review existing `.mdc` files for examples
- Keep rules specific to this project
- Prioritize clarity and actionability

---

**Built under the stars** | **© Ursa Minor Games**

