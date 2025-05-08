# Deploying to Vercel with Supabase

This guide will help you deploy your Laravel app to Vercel with Supabase as the database.

## Step 1: Update Vercel Configuration

Make sure your `vercel.json` file is correctly configured:

```json
{
  "version": 2,
  "framework": null,
  "functions": {
    "api/index.php": {
      "runtime": "vercel-php@0.6.0"
    }
  },
  "routes": [
    { "src": "/(css|js|images|fonts|favicon)/(.*)", "dest": "public/$1/$2" },
    { "src": "/(.*)", "dest": "/api/index.php" }
  ],
  "builds": [
    { "src": "api/index.php", "use": "vercel-php@0.6.0" },
    { "src": "public/**", "use": "@vercel/static" }
  ]
}
```

## Step 2: Set Up Vercel Environment Variables

In the Vercel dashboard, add these environment variables:

```
APP_ENV=production
APP_DEBUG=false
APP_KEY=YOUR_APP_KEY_HERE
APP_URL=${VERCEL_URL}

DB_CONNECTION=supabase
DB_HOST=aws-0-eu-west-2.pooler.supabase.com
DB_PORT=6543
DB_DATABASE=postgres
DB_USERNAME=postgres.qmgcnzweewqlbsgyyutn
DB_PASSWORD=DAhvz2bBksMnex5Z

DATABASE_URL=postgres://postgres.qmgcnzweewqlbsgyyutn:DAhvz2bBksMnex5Z@aws-0-eu-west-2.pooler.supabase.com:6543/postgres?sslmode=require

APP_CONFIG_CACHE=/tmp/config.php
APP_EVENTS_CACHE=/tmp/events.php
APP_PACKAGES_CACHE=/tmp/packages.php
APP_ROUTES_CACHE=/tmp/routes.php
APP_SERVICES_CACHE=/tmp/services.php
VIEW_COMPILED_PATH=/tmp
CACHE_DRIVER=array
LOG_CHANNEL=stderr
SESSION_DRIVER=cookie
```

## Step 3: Deploy to Vercel

1. Push your code to GitHub
2. Connect Vercel to your GitHub repository
3. Import the project in Vercel
4. Add the environment variables
5. Deploy

## Step 4: Run Migrations

After deployment, run migrations with the Vercel CLI:

```bash
# Install Vercel CLI if you haven't already
npm install -g vercel

# Login to Vercel
vercel login

# Run migrations
vercel run -- php artisan migrate:fresh --force
```

## Troubleshooting

If you have issues, check:

1. Vercel logs for any PHP errors
2. Database connection - make sure the credentials are correct
3. Verify that the PostgreSQL extension is enabled in Vercel

## Testing Locally

Test your database connection locally by updating your `.env` file with:

```
DB_CONNECTION=supabase
DB_HOST=aws-0-eu-west-2.pooler.supabase.com
DB_PORT=6543
DB_DATABASE=postgres
DB_USERNAME=postgres.qmgcnzweewqlbsgyyutn
DB_PASSWORD=DAhvz2bBksMnex5Z
```

Then run:

```bash
php artisan migrate:status
``` 