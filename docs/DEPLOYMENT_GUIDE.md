# Deployment Guide

Complete guide for deploying Ursa Minor Games to Railway.

## Prerequisites

- GitHub repository: https://github.com/bearjcc/website
- Railway account linked to GitHub
- Laravel 12.x application ready

## Quick Deploy

### Via Railway Dashboard

1. **Create New Project**
   - Go to https://railway.app
   - Click "New Project" → "Deploy from GitHub repo"
   - Select `bearjcc/website`

2. **Environment Variables**
   ```env
   APP_NAME="Ursa Minor"
   APP_ENV=production
   APP_KEY=      # Auto-generated on first build
   APP_DEBUG=false
   APP_URL=https://your-domain.railway.app
   ```

3. **Database Setup** (Optional)
   - Add PostgreSQL service in Railway
   - Railway auto-injects `PG*` variables
   - For SQLite: No additional setup needed

4. **Deploy**
   - Railway auto-detects Laravel via `nixpacks.toml`
   - Monitors build logs
   - Site goes live automatically

### Auto-Deploy Setup

Railway automatically deploys when you push to `main`:

```powershell
git add .
git commit -m "feat: new feature"
git push origin main
# Railway deploys automatically
```

## Build Configuration

### nixpacks.toml

The `nixpacks.toml` file configures Railway build:

```toml
[phases.setup]
nixPkgs = ["php83", "php83Packages.composer", "nodejs-20_x"]

[phases.install]
cmds = [
  "composer install --no-dev --optimize-autoloader",
  "npm ci",
  "npm run build"
]

[phases.build]
cmds = [
  "php artisan config:cache",
  "php artisan route:cache",
  "php artisan view:cache"
]

[start]
cmd = "php artisan serve --host=0.0.0.0 --port=${PORT:-8080}"
```

## Environment Variables

### Required

```env
APP_NAME="Ursa Minor"
APP_ENV=production
APP_DEBUG=false
APP_KEY=        # Auto-generated
APP_URL=        # Your Railway domain
```

### Database (PostgreSQL)

```env
DB_CONNECTION=pgsql
DB_HOST=${PGHOST}
DB_PORT=${PGPORT}
DB_DATABASE=${PGDATABASE}
DB_USERNAME=${PGUSER}
DB_PASSWORD=${PGPASSWORD}
```

### Database (SQLite - Development)

```env
DB_CONNECTION=sqlite
DB_DATABASE=/app/database/database.sqlite
```

### Cache & Session

```env
SESSION_DRIVER=database
CACHE_DRIVER=database
```

## Custom Domain

1. In Railway dashboard → Settings → Networking
2. Click "Custom Domain"
3. Add your domain (e.g., `ursaminor.games`)
4. Update DNS records at your registrar:
   - CNAME record pointing to Railway domain

## Post-Deployment

### Verify

- [ ] Site loads at Railway URL
- [ ] Assets load correctly (CSS, JS, images)
- [ ] Database migrations ran
- [ ] No 500 errors in logs

### Seed Database (First Deploy)

```bash
railway run php artisan db:seed
```

### Manual Commands

```bash
# Run migrations
railway run php artisan migrate

# Clear caches
railway run php artisan cache:clear
railway run php artisan config:clear
railway run php artisan route:clear
railway run php artisan view:clear

# Optimize for production
railway run php artisan optimize
```

## Health Checks

Railway can monitor your app with the `/health` endpoint:

1. Settings → Health Checks
2. Set URL: `https://your-domain.com/health`
3. Set interval: 60 seconds

## Troubleshooting

### Build Fails

**Check:**
- Build logs in Railway dashboard
- `composer.lock` is committed
- `package-lock.json` is committed
- Node.js and PHP versions compatible

### Assets Not Loading

**Check:**
- `npm run build` succeeded
- Assets in `public/build/`
- `APP_URL` matches Railway domain
- Clear browser cache

### Database Errors

**Check:**
- Database service is running
- `PG*` variables injected correctly
- Migrations ran: `railway run php artisan migrate`

### 500 Errors

**Check:**
- Enable debug temporarily: `APP_DEBUG=true`
- Check logs: `railway logs`
- Verify `APP_KEY` is set
- Ensure storage directories writable

## Local Development

Match production environment locally:

```powershell
# Install dependencies
composer install
npm install

# Setup environment
Copy-Item .env.example .env
php artisan key:generate

# Create SQLite database
New-Item -ItemType File database\database.sqlite

# Run migrations and seed
php artisan migrate
php artisan db:seed

# Start dev server
npm run dev        # Terminal 1
php artisan serve  # Terminal 2
```

Visit: http://localhost:8000

## Performance

### Optimization

- Enable OPcache in production
- Use Redis for cache/session (Railway add-on)
- Enable Laravel config/route/view caching
- Optimize images before upload

### Monitoring

- Check Railway metrics dashboard
- Monitor response times
- Watch for memory/CPU spikes
- Set up error tracking (Sentry, Bugsnag)

## Security

- Never commit `.env` file
- Use strong `APP_KEY`
- Keep dependencies updated
- Enable HTTPS (Railway provides automatically)
- Rotate secrets if exposed

## Scaling

Railway handles scaling automatically, but consider:

- Database connection pooling
- CDN for static assets
- Queue workers for background jobs
- Redis for distributed caching

## Support

- **Railway**: https://docs.railway.app
- **Laravel**: https://laravel.com/docs
- **GitHub Issues**: https://github.com/bearjcc/website/issues

---

**Status**: Production-ready deployment on Railway

