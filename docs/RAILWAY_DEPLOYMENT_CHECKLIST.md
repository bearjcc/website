# Railway Deployment Checklist

Complete checklist for deploying Ursa Minor Games to Railway using Docker.

## Prerequisites

- [ ] Railway account created at https://railway.app
- [ ] Railway CLI installed (optional): `npm i -g @railway/cli`
- [ ] GitHub repository connected to Railway project
- [ ] Docker Desktop installed locally (for testing)

## One-Time Railway Setup

### 1. Create Railway Project

- [ ] Log into Railway dashboard
- [ ] Click "New Project"
- [ ] Select "Deploy from GitHub repo"
- [ ] Choose `bearjcc/website` repository
- [ ] Confirm project created

### 2. Configure Build Settings

- [ ] Go to project Settings → Build
- [ ] **IMPORTANT**: Change "Builder" from "Nixpacks" to "Dockerfile"
- [ ] Save settings

### 3. Configure Environment Variables

Go to project Variables tab and add:

**Required Variables:**
```env
APP_ENV=production
APP_DEBUG=false
APP_KEY=                    # Leave empty - will auto-generate on first deploy
APP_URL=                    # Your Railway URL (e.g., https://your-app.up.railway.app)
LOG_CHANNEL=stderr
LOG_LEVEL=info
CACHE_DRIVER=file
SESSION_DRIVER=file
QUEUE_CONNECTION=sync
FILESYSTEM_DISK=public
```

**Database Variables (if using PostgreSQL):**

- [ ] Add PostgreSQL plugin to project
- [ ] Railway auto-injects these variables:
  - `PGHOST`
  - `PGPORT`
  - `PGDATABASE`
  - `PGUSER`
  - `PGPASSWORD`
  - `DATABASE_URL`

- [ ] Add these to reference Postgres:
```env
DB_CONNECTION=pgsql
DB_HOST=${{Postgres.PGHOST}}
DB_PORT=${{Postgres.PGPORT}}
DB_DATABASE=${{Postgres.PGDATABASE}}
DB_USERNAME=${{Postgres.PGUSER}}
DB_PASSWORD=${{Postgres.PGPASSWORD}}
```

**Optional Variables:**
```env
RUN_MIGRATIONS=1           # Auto-run migrations on deploy
```

### 4. Configure Networking

- [ ] Go to Settings → Networking
- [ ] Verify port 8080 is detected (should be automatic)
- [ ] Enable "Public Network" if needed
- [ ] Note your Railway domain

### 5. Configure Health Checks (Optional)

- [ ] Go to Settings → Health Checks
- [ ] Set Path: `/up`
- [ ] Set Interval: 60 seconds
- [ ] Save

## Pre-Deployment Checklist

### Code Quality

- [ ] All tests pass locally: `php artisan test`
- [ ] No linter errors: `vendor/bin/pint`
- [ ] Assets build successfully: `npm run build`
- [ ] Migrations are idempotent (safe to run multiple times)

### Docker Compatibility

- [ ] No `env()` calls outside config files
- [ ] All secrets in environment variables
- [ ] Logging configured to stderr in production
- [ ] No hardcoded paths or URLs

### Local Docker Test (Optional)

If Docker is running locally:

```powershell
# Build image
docker build -t ursaminor:test .

# Verify PHP
docker run --rm ursaminor:test php -v

# Verify artisan
docker run --rm -e APP_KEY=base64:TESTKEY= ursaminor:test php artisan --version

# Check assets exist
docker run --rm ursaminor:test ls -la /var/www/html/public/build
```

### Git Status

- [ ] All changes committed
- [ ] Conventional commit messages used
- [ ] No uncommitted secrets or .env files

## Deployment Process

### 1. Push to GitHub

```powershell
git status
git add -A
git commit -m "feat: your feature description"
git push origin main
```

### 2. Monitor GitHub Actions

- [ ] Go to GitHub repository → Actions tab
- [ ] Watch "Railway Smoke Test" workflow
- [ ] Ensure all checks pass (green)
- [ ] If fails, fix issues and push again

### 3. Monitor Railway Build

- [ ] Go to Railway dashboard → Deployments
- [ ] Watch build logs in real-time
- [ ] Verify all stages complete:
  - [ ] Node stage: Assets built
  - [ ] Composer stage: Dependencies installed
  - [ ] Runtime stage: Image assembled
  - [ ] Container starts successfully

### 4. Monitor Application Startup

Watch Railway logs for:
- [ ] "App booted" message appears
- [ ] No error messages
- [ ] APP_KEY generated (if was empty)
- [ ] Migrations ran (if `RUN_MIGRATIONS=1`)
- [ ] Config/route/view caches created

## Post-Deployment Verification

### 1. Site Accessibility

- [ ] Visit Railway URL
- [ ] Homepage loads correctly
- [ ] No 500/404 errors
- [ ] Check browser console for JS errors

### 2. Visual Verification

- [ ] CSS loads correctly (no unstyled content)
- [ ] Images load correctly
- [ ] Fonts load correctly
- [ ] Interactive elements work

### 3. Functionality Tests

- [ ] Test main user flows
- [ ] Test database interactions
- [ ] Test authentication (if applicable)
- [ ] Test forms and submissions

### 4. Performance Check

- [ ] Page load time < 3 seconds
- [ ] No console errors
- [ ] Assets cached properly
- [ ] HTTPS enabled

## Troubleshooting Guide

### Build Fails

**Symptom**: Railway build fails during Docker build

**Check**:
- [ ] GitHub Actions smoke test passed?
- [ ] `composer.lock` committed?
- [ ] `package-lock.json` committed?
- [ ] Dockerfile syntax correct?

**Solutions**:
- Review Railway build logs for specific error
- Run `docker build .` locally to reproduce
- Check GitHub Actions logs for details

### Container Fails to Start

**Symptom**: Build succeeds but container crashes

**Check**:
- [ ] APP_KEY is set or can be generated?
- [ ] Required env vars are set?
- [ ] Port 8080 is accessible?
- [ ] Entrypoint script has no errors?

**Solutions**:
- Check Railway logs for startup errors
- Verify all required env vars set
- Test entrypoint script logic

### 500 Internal Server Error

**Symptom**: Site loads but shows 500 error

**Check**:
- [ ] APP_KEY is set?
- [ ] Database connection works?
- [ ] Storage directories writable?
- [ ] Log channel set to stderr?

**Solutions**:
- Enable `APP_DEBUG=true` temporarily
- Check Railway logs for PHP errors
- Verify database credentials
- Run `railway run php artisan config:clear`

### Assets Not Loading (No CSS/JS)

**Symptom**: HTML loads but no styling or interactivity

**Check**:
- [ ] `npm run build` succeeded in Docker?
- [ ] `public/build/` directory exists in image?
- [ ] Blade templates use `@vite` directive?
- [ ] APP_URL matches Railway domain?

**Solutions**:
- Check build logs for Vite errors
- Verify `public/build/manifest.json` exists
- Run `docker run --rm ursaminor:test ls -la /var/www/html/public/build`
- Clear browser cache

### Database Connection Fails

**Symptom**: Errors about database connection

**Check**:
- [ ] PostgreSQL plugin added to Railway?
- [ ] Database variables injected correctly?
- [ ] SSL mode set for Postgres?
- [ ] Migrations ran successfully?

**Solutions**:
- Verify `DATABASE_URL` or individual `PG*` vars set
- Check Railway Postgres service is running
- Use `DATABASE_URL` with `?sslmode=require`
- Run migrations manually: `railway run php artisan migrate --force`

### Logs Not Appearing

**Symptom**: Can't see application logs in Railway

**Check**:
- [ ] `LOG_CHANNEL=stderr` set?
- [ ] Not logging to files?
- [ ] PHP errors going to stderr?

**Solutions**:
- Verify `LOG_CHANNEL=stderr` in Railway variables
- Update `config/logging.php` to use stderr channel
- Restart deployment

## Rollback Procedure

If deployment has critical issues:

### 1. Immediate Rollback

- [ ] Go to Railway dashboard → Deployments
- [ ] Find last successful deployment
- [ ] Click "..." menu → "Redeploy"
- [ ] Confirm rollback

### 2. Fix and Redeploy

- [ ] Identify issue from logs
- [ ] Create hotfix branch: `git checkout -b hotfix/issue-name`
- [ ] Fix issue in code
- [ ] Test locally
- [ ] Commit: `git commit -m "fix: describe fix"`
- [ ] Push: `git push origin hotfix/issue-name`
- [ ] Merge to main after verification
- [ ] Delete hotfix branch

## Maintenance Tasks

### Weekly

- [ ] Check Railway metrics dashboard
- [ ] Review error logs
- [ ] Monitor response times
- [ ] Check disk usage

### Monthly

- [ ] Update dependencies: `composer update`, `npm update`
- [ ] Run security audit: `composer audit`, `npm audit`
- [ ] Review and optimize database queries
- [ ] Check for deprecated PHP/Laravel features

### Quarterly

- [ ] Test disaster recovery (rollback)
- [ ] Review and update environment variables
- [ ] Optimize Docker image size
- [ ] Update PHP/Node versions if needed

## Emergency Contacts

- **Railway Support**: https://railway.app/help
- **GitHub Issues**: https://github.com/bearjcc/website/issues
- **Laravel Docs**: https://laravel.com/docs

## Useful Railway Commands

```bash
# Install Railway CLI
npm i -g @railway/cli

# Login to Railway
railway login

# Link local project
railway link

# View logs
railway logs

# Run artisan commands
railway run php artisan migrate
railway run php artisan tinker

# SSH into container
railway run bash

# View environment variables
railway variables
```

## Success Criteria

Deployment is successful when:

- [x] Build completes without errors
- [x] Container starts and stays running
- [x] Site loads at Railway URL
- [x] All assets (CSS/JS/images) load
- [x] No 500 errors in logs
- [x] Database connections work
- [x] Migrations completed successfully
- [x] Health check passes (if configured)
- [x] GitHub Actions smoke test passed

---

**Last Updated**: 2025-10-13
**Next Review**: 2026-01-13

**Deployment Status**: ✅ Railway-Ready

