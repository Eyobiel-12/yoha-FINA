# Deploying to DigitalOcean

This guide covers deploying your Yohannes Hoveniersbedrijf financial management application to DigitalOcean.

## Step 1: Prepare Your GitHub Repository

1. Make sure all your code is committed and pushed to GitHub
2. Ensure your repository contains:
   - All application code
   - deploy.sh (deployment script)
   - production-checklist.md (deployment checklist)
   - .env.example (for reference)
   - database/database.sqlite (if using SQLite)

## Step 2: Create a DigitalOcean Droplet

1. Log in to your DigitalOcean account
2. Click on "Create" and select "Droplets"
3. Choose the following options:
   - **Region**: Choose a location close to your users
   - **Image**: Ubuntu 22.04 LTS
   - **Size**: Basic plan with at least 1GB RAM
   - **Authentication**: SSH keys (use the `~/.ssh/do_hoveniers_key.pub` you created)
   - **Hostname**: hoveniers-finance (or your preferred name)

## Step 3: Connect to Your Droplet

```bash
ssh -i ~/.ssh/do_hoveniers_key root@your-droplet-ip
```

## Step 4: Server Setup

1. Clone your repository:

```bash
git clone https://github.com/your-username/your-repo.git /var/www/hoveniers_finance
cd /var/www/hoveniers_finance
```

2. Make the deployment script executable and run it:

```bash
chmod +x deploy.sh
./deploy.sh
```

3. The script will:
   - Update your system
   - Install required packages (Nginx, MySQL, PHP, etc.)
   - Configure Nginx
   - Create the database and user
   - Set up the application directory

## Step 5: Application Configuration

1. Create a production environment file:

```bash
cp .env.example .env
nano .env
```

2. Update the following values:
   - APP_URL: Your actual domain
   - DB_DATABASE, DB_USERNAME, and DB_PASSWORD with the values from deploy.sh
   - Generate an app key: `php artisan key:generate`

3. If using SQLite, ensure the database file exists:

```bash
# For SQLite
mkdir -p database
touch database/database.sqlite
```

4. Install Composer dependencies:

```bash
composer install --no-dev --optimize-autoloader
```

5. Run migrations and seed the database:

```bash
php artisan migrate --force
php artisan db:seed --force
```

6. Set proper permissions:

```bash
sudo chown -R www-data:www-data storage bootstrap/cache
php artisan storage:link
```

7. Cache configuration for better performance:

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## Step 6: Domain and SSL Setup

1. Update your domain's DNS settings to point to your droplet's IP address
2. Install Certbot for SSL:

```bash
sudo apt install certbot python3-certbot-nginx
sudo certbot --nginx -d your-domain.com -d www.your-domain.com
```

## Final Steps

1. Visit your domain in a browser to verify the application works
2. Check the production checklist to make sure you haven't missed anything

## Useful Commands

- Check Nginx status: `sudo systemctl status nginx`
- View Nginx error logs: `sudo tail -f /var/log/nginx/error.log`
- View application logs: `tail -f storage/logs/laravel.log`
- Restart Nginx: `sudo systemctl restart nginx`
- Restart PHP-FPM: `sudo systemctl restart php8.1-fpm` 