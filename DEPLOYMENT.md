# Deployment Guide

## Railway Deployment

### Prerequisites
- GitHub repository created and pushed: https://github.com/bearjcc/website
- Railway account linked to GitHub
- Railway CLI installed (optional)

### Deployment Steps

#### Option 1: Via Railway Dashboard (Recommended for first deployment)

1. **Log in to Railway**
   - Go to https://railway.app
   - Log in with your GitHub account

2. **Create New Project**
   - Click "New Project"
   - Select "Deploy from GitHub repo"
   - Choose the `bearjcc/website` repository
   - Click "Deploy Now"

3. **Configure Environment Variables**
   Railway will auto-detect the Laravel application. Add these environment variables in the Railway dashboard:
   
   ```
   APP_NAME=Ursa Minor
   APP_ENV=production
   APP_DEBUG=false
   APP_URL=https://your-domain.railway.app
   ```
   
   Railway will automatically generate `APP_KEY` during the first build.

4. **Configure Custom Domain (Optional)**
   - Go to Settings > Networking
   - Click "Generate Domain" for a Railway subdomain
   - Or add your custom domain (e.g., ursaminor.games)

5. **Deploy**
   - Railway will automatically build and deploy using the `nixpacks.toml` configuration
   - Monitor the deployment logs
   - Once complete, your site will be live!

#### Option 2: Via Railway CLI

```powershell
# Install Railway CLI (if not installed)
npm install -g @railway/cli

# Login to Railway
railway login

# Initialize project
railway init

# Link to GitHub repository
railway link

# Deploy
railway up
```

### Post-Deployment

1. **Verify Deployment**
   - Visit your Railway URL
   - Check that the starfield animation and header work correctly

2. **Set Up Auto-Deployments**
   - Railway automatically sets up webhooks for GitHub
   - Every push to the `main` branch will trigger a new deployment

3. **Monitor**
   - View logs in the Railway dashboard
   - Monitor build times and errors

### Configuration Files

- **nixpacks.toml**: Configures the build process (PHP 8.3, Composer, optimizations)
- **.env.example**: Template for environment variables
- **public/**: Static assets served by Laravel

### Troubleshooting

**Build Failures:**
- Check Railway logs for specific errors
- Verify `composer.json` and `composer.lock` are in sync
- Ensure `APP_KEY` is set in environment variables

**Assets Not Loading:**
- Verify files are in the `public/` directory
- Check that `asset()` helper is used in Blade templates
- Clear browser cache

**500 Errors:**
- Check Railway logs
- Ensure `APP_KEY` is generated
- Verify storage directories are writable (handled by Laravel automatically)

### Future Updates

To deploy updates:

```powershell
# Make changes to your code
git add .
git commit -m "feat: add new feature"
git push origin main
```

Railway will automatically deploy the new version.

### Branch Strategy for New Features

For new features, use feature branches:

```powershell
# Create feature branch
git checkout -b feature/sudoku-game

# Make changes and commit
git add .
git commit -m "feat(games): add sudoku game"

# Push to GitHub
git push origin feature/sudoku-game

# Create pull request on GitHub
# After review, merge to main
# Railway will auto-deploy the merged changes
```

