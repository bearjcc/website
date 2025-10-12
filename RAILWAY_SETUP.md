# Railway Setup Instructions

## Quick Start Guide

Your Laravel homepage is ready to deploy! Follow these steps to get it live on Railway.

### Step 1: Access Railway Dashboard

1. Go to https://railway.app
2. Log in with your GitHub account (the one linked to https://github.com/bearjcc/website)

### Step 2: Create New Project

1. Click the **"New Project"** button
2. Select **"Deploy from GitHub repo"**
3. Choose the repository: **`bearjcc/website`**
4. Railway will automatically detect it's a Laravel application

### Step 3: Configure Environment Variables

Railway will ask you to set environment variables. Add these:

```
APP_NAME=Ursa Minor
APP_ENV=production
APP_DEBUG=false
```

**Note:** Railway will automatically generate `APP_KEY` during the build process. You don't need to add it manually.

### Step 4: Deploy

1. Click **"Deploy"**
2. Wait for the build to complete (2-5 minutes)
3. Monitor the logs for any errors

### Step 5: Access Your Site

1. Once deployed, Railway will provide a URL like: `https://your-app.railway.app`
2. Click on the URL to view your live site
3. Verify that:
   - The starfield animation appears
   - The Ursa Minor logo and text are visible
   - The header shrinks when you scroll down

### Step 6: (Optional) Set Up Custom Domain

If you want to use `ursaminor.games`:

1. Go to your project settings in Railway
2. Click **"Networking"**
3. Click **"Custom Domain"**
4. Add your domain: `ursaminor.games`
5. Follow Railway's instructions to update your DNS records at your domain registrar

### Step 7: Automatic Deployments

Railway is now watching your GitHub repository! Every time you push to the `main` branch, Railway will automatically:
- Pull the latest code
- Run the build process
- Deploy the new version
- Zero downtime deployment

## Build Configuration

The `nixpacks.toml` file configures the Railway build:

```toml
[phases.setup]
nixPkgs = ["php83", "php83Packages.composer"]

[phases.install]
cmds = ["composer install --no-dev --optimize-autoloader"]

[phases.build]
cmds = ["php artisan optimize"]

[start]
cmd = "php artisan serve --host=0.0.0.0 --port=${PORT:-8080}"
```

This ensures:
- PHP 8.3 is used
- Dependencies are installed optimally
- Laravel is configured for production
- The app runs on Railway's dynamic port

## Troubleshooting

### Build Fails

**Symptom:** Railway shows "Build Failed"

**Solutions:**
1. Check the build logs in Railway dashboard
2. Ensure `composer.json` and `composer.lock` are committed
3. Verify `APP_KEY` is either set or auto-generated

### Site Shows 500 Error

**Symptom:** White page or "Server Error"

**Solutions:**
1. Check the application logs in Railway
2. Ensure `APP_KEY` environment variable is set
3. Verify `APP_DEBUG=false` for production

### Assets Not Loading

**Symptom:** No background, no stars, broken layout

**Solutions:**
1. Check browser console for 404 errors
2. Verify files exist in `public/` directory
3. Clear browser cache
4. Check that `asset()` helper is used in Blade templates

### Gradual Rollout Issues

If you need to roll back:
1. Go to Railway dashboard
2. Click "Deployments"
3. Find the previous successful deployment
4. Click "Redeploy"

## Next Steps

Once your site is live:

1. **Test Everything**
   - Visit the site
   - Test on mobile devices
   - Check different browsers

2. **Monitor Performance**
   - Check Railway metrics
   - Monitor response times
   - Watch for errors in logs

3. **Plan Next Feature**
   - Create a feature branch: `git checkout -b feature/sudoku-game`
   - Develop locally: `php artisan serve`
   - Push when ready: `git push origin feature/sudoku-game`
   - Create PR on GitHub
   - Merge to main when tested
   - Railway auto-deploys!

## Support

- **Railway Docs**: https://docs.railway.app
- **Laravel Docs**: https://laravel.com/docs
- **GitHub Repository**: https://github.com/bearjcc/website

---

🚀 **You're ready to deploy!** Just follow the steps above and your Ursa Minor homepage will be live in minutes.

