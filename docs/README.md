# Documentation

Simplified documentation structure for Ursa Minor Games.

## Essential Documents (Human-Readable)

### 1. Project Readme
**[README.md](../README.md)** (root) — Project overview, setup, quick start

### 2. Design Bible
**[DESIGN_BIBLE.md](../DESIGN_BIBLE.md)** (root) — Complete design reference: brand story, philosophy, visual identity, colors, typography, layout, tone, accessibility

### 3. Task Tracking
**[TODO.md](TODO.md)** — Current tasks and roadmap

### 4. Technical Guides

**Deployment**:
- **[DEPLOYMENT_GUIDE.md](DEPLOYMENT_GUIDE.md)** — Railway deployment with Docker

**Integration**:
- **[FEATURE_EXTRACTION_GUIDE.md](FEATURE_EXTRACTION_GUIDE.md)** — Extract features from other repos
- **[FLUX_INTEGRATION.md](FLUX_INTEGRATION.md)** — Flux UI component usage

**Development**:
- **[GAME_DEVELOPMENT_GUIDE.md](GAME_DEVELOPMENT_GUIDE.md)** — Building games framework & patterns
- **[Component Library](../resources/views/components/ui/README.md)** — Available UI components

**Architecture**:
- **[PROJECT_STRUCTURE.md](PROJECT_STRUCTURE.md)** — Codebase organization
- **[DEPENDENCIES.md](DEPENDENCIES.md)** — Package management

---

## AI-Consumable Rules (.cursor/rules/)

Technical implementation guidance lives in `.cursor/rules/*.mdc` for AI agents:

### Core Rules
- `001-core-project-conventions.mdc` — Project layout, naming, workflow
- `002-development-workflow.mdc` — Dev process, testing, commits
- `030-code-standards.mdc` — Code quality, PHP standards
- `040-commit-standards.mdc` — Conventional commits

### Design Rules
- `100-ui-ux-patterns.mdc` — UI/UX patterns, accessibility
- `110-minimal-copy.mdc` — Minimal copy philosophy
- `120-lowercase-mode.mdc` — Lowercase feature flag
- `150-component-implementation.mdc` — Component technical patterns

### Domain Rules
- `200-domain-guidelines.mdc` — Business logic, general architecture
- `210-game-development-patterns.mdc` — Game development framework
- `300-testing-and-guardrails.mdc` — Testing requirements, content validation

---

## Quick Reference

**Starting a new feature?** → DESIGN_BIBLE.md  
**Building a game?** → GAME_DEVELOPMENT_GUIDE.md  
**Need UI component?** → Component Library README  
**Need setup help?** → README.md  
**What's next?** → TODO.md  
**Deploying?** → DEPLOYMENT_GUIDE.md  
**Integrating code?** → FEATURE_EXTRACTION_GUIDE.md

---

## Documentation Philosophy

**Less is more**:
- 3 core MD files for humans (README, DESIGN_BIBLE, TODO)
- Technical guides for specific tasks
- MDC files for AI agent guidance
- No redundancy, no verbosity

---

**Built under the stars** | **© Ursa Minor Games**
