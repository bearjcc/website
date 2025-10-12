# Railway Deployment Guide for Ursa Minor

This Laravel 11 + TALL stack application is configured for deployment on Railway.

## Prerequisites

- Railway account
- GitHub repository connected to Railway
- PostgreSQL database service on Railway (or SQLite for development)

## Environment Configuration

### Required Environment Variables

Set these in your Railway project settings:

```env
# Application
APP_NAME="Ursa Minor"
APP_ENV=production
APP_KEY=base64:... # Generate with: php artisan key:generate --show
APP_DEBUG=false
APP_URL=https://your-railway-domain.up.railway.app

# Database (Railway PostgreSQL)
DB_CONNECTION=pgsql
DB_HOST=${PGHOST}
DB_PORT=${PGPORT}
DB_DATABASE=${PGDATABASE}
DB_USERNAME=${PGUSER}
DB_PASSWORD=${PGPASSWORD}

# Session & Cache
SESSION_DRIVER=database
CACHE_DRIVER=database

# Optional: for SQLite in development
# DB_CONNECTION=sqlite
# DB_DATABASE=/app/database/database.sqlite
```

### Database Setup

**Option 1: PostgreSQL (Recommended for Production)**
1. Add PostgreSQL service in Railway
2. Railway will automatically inject `PG*` variables
3. Use the environment variables shown above

**Option 2: SQLite (Development Only)**
1. Set `DB_CONNECTION=sqlite`
2. Ensure `database/database.sqlite` exists
3. Add to `.gitignore` or commit empty file

## Deployment Process

### Automatic Deployment

1. **Connect Repository**: Link your GitHub repo to Railway
2. **Set Branch**: Configure main branch for auto-deploy
3. **Add Variables**: Set all required environment variables
4. **Deploy**: Push to main branch triggers automatic deployment

### Build Process

The `nixpacks.toml` configuration handles:
- PHP 8.3 + Composer installation
- Node.js 20 + NPM installation
- Dependency installation (composer & npm)
- Asset building (Vite)
- Laravel optimization (config, route, view caching)
- Database migrations on start

### Manual Commands

If you need to run commands manually via Railway CLI:

```bash
# Run migrations
railway run php artisan migrate

# Seed database
railway run php artisan db:seed

# Clear caches
railway run php artisan cache:clear
railway run php artisan config:clear
railway run php artisan route:clear
railway run php artisan view:clear

# Optimize for production
railway run php artisan optimize
```

## Post-Deployment Checklist

- [ ] Verify APP_KEY is set
- [ ] Database migrations ran successfully
- [ ] Seed database with sample data: `php artisan db:seed`
- [ ] Test authentication flow
- [ ] Verify Lore section is hidden from guests
- [ ] Test admin features access
- [ ] Confirm assets are loading (CSS, JS, starfield)
- [ ] Check responsive layout on multiple devices

## Troubleshooting

### Assets Not Loading
- Ensure `APP_URL` matches your Railway domain
- Check Vite build completed: `npm run build`
- Verify public assets are in `public/build/`

### Database Connection Errors
- Confirm PostgreSQL service is running
- Verify `PG*` variables are injected
- Check database credentials in Railway dashboard

### Migrations Failing
- Review migration files for syntax errors
- Check database connection
- Run migrations manually: `railway run php artisan migrate --force`

### 500 Errors
- Enable debug temporarily: `APP_DEBUG=true` (remember to disable after)
- Check logs: `railway logs`
- Verify all required environment variables are set

## Scaling Considerations

### Performance
- Use Redis for cache/session in production
- Enable OPcache for PHP
- Consider CDN for static assets

### Database
- Use PostgreSQL connection pooling
- Add indexes for frequently queried fields
- Monitor query performance

### Monitoring
- Enable Laravel Telescope for debugging (dev only)
- Use Railway logs for error tracking
- Set up uptime monitoring

## Security Notes

- Never commit `.env` file
- Rotate APP_KEY if exposed
- Use strong passwords for admin/contributor accounts
- Enable HTTPS (Railway provides this automatically)
- Keep Laravel and dependencies updated

## Local Development

To match Railway environment locally:

```bash
# Install dependencies
composer install
npm install

# Setup environment
cp .env.example .env
php artisan key:generate

# Create database
touch database/database.sqlite

# Run migrations & seed
php artisan migrate
php artisan db:seed

# Start development server
npm run dev
php artisan serve
```

## Support

For Railway-specific issues, consult:
- Railway Docs: https://docs.railway.app
- Railway Discord: https://discord.gg/railway

For Laravel issues:
- Laravel Docs: https://laravel.com/docs
- Livewire Docs: https://livewire.laravel.com

