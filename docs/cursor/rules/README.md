# Cursor Rules for Ursa Minor Games

This directory contains structured rules for Cursor AI to follow when working on the Ursa Minor Games website project.

## Organization

Rules are organized into categories for easy reference and maintenance:

### Foundation (001-003)
Core project information and context
- **001-project-overview.mdc**: Project overview, tech stack, and key principles
- **002-development-workflow.mdc**: Development processes and workflows
- **003-tech-stack.mdc**: Detailed technology stack and dependencies

### Design (010-011)
Visual design and frontend standards
- **010-brand-guidelines.mdc**: Brand identity, colors, typography, and visual standards
- **011-frontend-standards.mdc**: HTML, CSS, JavaScript standards and best practices

### Laravel (020)
Backend framework best practices
- **020-laravel-best-practices.mdc**: Laravel architecture, patterns, and conventions

### Quality (030-031)
Code quality and testing standards
- **030-code-standards.mdc**: Code quality, style, and documentation standards
- **031-testing-requirements.mdc**: Testing philosophy, practices, and coverage goals

### Process (040)
Development process and version control
- **040-commit-standards.mdc**: Conventional commits, branching, and release process

### Deployment (050)
Production deployment and operations
- **050-railway-deployment.mdc**: Railway deployment, configuration, and monitoring

## Numbering System

Files are numbered by category:
- **000-009**: Reserved for future foundation rules
- **010-019**: Design and frontend rules
- **020-029**: Laravel and backend rules
- **030-039**: Quality and testing rules
- **040-049**: Process and workflow rules
- **050-059**: Deployment and operations rules

This allows for easy insertion of new rules within categories.

## Usage

These `.mdc` files are automatically loaded by Cursor based on the file patterns specified in each file's `globs` field. They provide context-aware guidance to the AI assistant.

## Key Project Rules

### Critical Requirements

1. **NO EMOJI** - Absolutely prohibited in production code
2. **NO PURPLE GRADIENTS** - AI cliché, strictly forbidden
3. **Night Sky Theme** - Use black to evening blue gradient only
4. **Test Locally First** - Always test at http://website.test/ before committing
5. **Test Before Merge** - Run full test suite before merging to main
6. **One Feature at a Time** - "Eat the elephant one bite at a time"
7. **Railway Compatible** - Maintain deployment compatibility

### Brand Colors

- Night Black: `#000000`
- Midnight Blue: `#001a33`
- Evening Blue: `#002d58`
- Star White: `#ffffff`
- Star Yellow: `#fff89a`

### Development Stack

- **Backend**: Laravel 12.x (PHP 8.3+)
- **Frontend**: Vanilla HTML, CSS, JavaScript
- **Future**: TALL Stack (Tailwind, Alpine.js, Laravel, Livewire)
- **Local**: Laravel Herd at http://website.test/
- **Production**: Railway with auto-deploy from main

## Contributing

When adding new rules:

1. Choose appropriate category directory
2. Use next available number in range
3. Include descriptive filename
4. Add front matter with `description` and `globs`
5. Update this README
6. Test that rules work correctly
7. Commit with conventional commit message

Example:
```
docs(cursor): add security best practices rule
```

## Maintenance

### Regular Reviews

- Review rules quarterly for accuracy
- Update for new features and technologies
- Remove outdated guidance
- Incorporate lessons learned

### Version Control

- All rules are version controlled in Git
- Changes follow conventional commit format
- Document significant rule changes
- Keep rules synchronized with project evolution

## Related Documentation

- [BRAND_GUIDELINES.md](../../BRAND_GUIDELINES.md) - Detailed brand guidelines
- [README.md](../../README.md) - Project README
- [CONTRIBUTING.md](../../CONTRIBUTING.md) - Contribution guidelines
- [DEPLOYMENT.md](../../DEPLOYMENT.md) - Deployment documentation

## Contact

For questions about these rules or to suggest improvements, open an issue on GitHub.

---

**Built under the stars** | **© Ursa Minor Games**

