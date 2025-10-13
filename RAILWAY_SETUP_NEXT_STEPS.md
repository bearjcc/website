# Railway Production Setup - Next Steps

**Status**: Code is ready. Railway environment variables need to be set manually.

---

## Quick Start

### 1. Set Environment Variables in Railway Dashboard

Navigate to your Railway project → Service → Variables tab and set:

```env
# Required
RUN_MIGRATIONS=1
DB_CONNECTION=pgsql

# Verify these exist (from PostgreSQL plugin)
DB_HOST=${Postgres.PGHOST}
DB_PORT=${Postgres.PGPORT}
DB_DATABASE=${Postgres.PGDATABASE}
DB_USERNAME=${Postgres.PGUSER}
DB_PASSWORD=${Postgres.PGPASSWORD}

# Application
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-app.up.railway.app
LOG_CHANNEL=stderr
LOG_LEVEL=info
```

### 2. Verify PostgreSQL Plugin

- Ensure PostgreSQL plugin is added to your Railway project
- It should automatically provide the `Postgres.*` variables
- If not present: Click "+ New" → Database → PostgreSQL

### 3. Deploy

**Option A: Push to Main**
```powershell
git add .
git commit -m "refactor(core): comprehensive review fixes"
git push origin main
```

Railway will automatically:
- Detect push
- Build Docker image
- Run migrations (via RUN_MIGRATIONS=1)
- Start application

**Option B: Manual Redeploy**
- In Railway dashboard, click "Redeploy"

### 4. Seed Production Data

After successful deployment, run the seeder:

**Using Railway CLI** (recommended):
```powershell
railway run php artisan db:seed --class=ProductionSeeder
```

**Using Railway Shell**:
1. Open Railway dashboard
2. Click on your service
3. Click "Shell" tab
4. Run: `php artisan db:seed --class=ProductionSeeder`

### 5. Verify Production

Visit your Railway URL and verify:
- ✅ Homepage loads without errors
- ✅ Starfield animation appears
- ✅ 5 games appear in carousel
- ✅ Navigation works
- ✅ Footer displays correctly
- ✅ No database errors in Railway logs

---

## Troubleshooting

### If Database Errors Persist

**Check Railway Logs**:
- Service → Deployments → Click latest deployment → View logs
- Look for migration errors or connection issues

**Common Issues**:

1. **"Database file does not exist"**
   - Cause: `DB_CONNECTION` not set to `pgsql`
   - Fix: Set `DB_CONNECTION=pgsql` in Railway

2. **"Connection refused"**
   - Cause: PostgreSQL plugin not connected
   - Fix: Add PostgreSQL plugin to project

3. **"No games appearing"**
   - Cause: Seeder not run
   - Fix: Run `railway run php artisan db:seed --class=ProductionSeeder`

4. **"500 error with no logs"**
   - Cause: `LOG_CHANNEL` not set to stderr
   - Fix: Set `LOG_CHANNEL=stderr` in Railway

### Verify Environment Variables

In Railway CLI:
```powershell
railway variables
```

Should show all required variables listed above.

---

## What the Seeder Creates

The `ProductionSeeder` will create 5 published games:

1. **Tic-Tac-Toe** (board game)
   - Slug: `tic-tac-toe`
   - Motif: 3x3 grid SVG

2. **Sudoku** (puzzle)
   - Slug: `sudoku`
   - Motif: Grid icon

3. **Minesweeper** (puzzle)
   - Slug: `minesweeper`
   - Motif: Grid with flags

4. **Connect 4** (board game)
   - Slug: `connect-4`
   - Motif: Connect4 pattern

5. **Snake** (puzzle)
   - Slug: `snake`
   - Motif: Snake icon

These match the motif map in `home.blade.php` and will display correctly in the carousel.

---

## After Production is Working

### Cleanup

1. **Delete this file**: `RAILWAY_SETUP_NEXT_STEPS.md` (one-time guide)
2. **Delete**: `REVIEW_SUMMARY.md` (temporary review document)
3. **Update**: `docs/TODO.md` to mark Phase 1 complete

### Commit

Commit all changes with conventional commit message (see `REVIEW_SUMMARY.md` for suggested message).

### Monitor

- Check Railway logs for any warnings
- Test all functionality on production
- Verify games are playable
- Check responsive design on different devices

---

## Questions?

- **How do I access Railway CLI?**: Install via `npm install -g @railway/cli` then `railway login`
- **Where are the logs?**: Railway dashboard → Service → Deployments → Click latest → View logs
- **How do I rollback?**: Railway dashboard → Deployments → Click previous working deployment → Redeploy
- **Database backup?**: Railway handles automatic backups for PostgreSQL plugin

---

**Ready to deploy! Follow steps 1-5 above to get production working with content.**

