# Production Deployment Checklist for Yohannes Hoveniersbedrijf Finance App

## Local Preparation
- [ ] Run `npm run build` to build frontend assets
- [ ] Make sure all tests pass
- [ ] Commit all changes to Git
- [ ] Push changes to GitHub repository

## Server Setup on DigitalOcean
- [ ] Create a Droplet (recommended: Ubuntu 22.04 with at least 1GB RAM)
- [ ] Connect via SSH using your key (`~/.ssh/do_hoveniers_key`)
- [ ] Upload and run the deployment script: `bash deploy.sh`
- [ ] Configure firewall: `sudo ufw allow 'Nginx Full'` and `sudo ufw allow ssh`
- [ ] Enable firewall: `sudo ufw enable`

## Application Deployment
- [ ] Clone repository to server or upload files to `/var/www/hoveniers_finance`
- [ ] Create `.env` file with production settings (based on `.env.production`)
- [ ] Install Composer dependencies: `composer install --no-dev --optimize-autoloader`
- [ ] Generate application key: `php artisan key:generate`
- [ ] Run database migrations: `php artisan migrate --force`
- [ ] Run seeders if needed: `php artisan db:seed --force`
- [ ] Set proper file permissions:
  ```
  sudo chown -R www-data:www-data /var/www/hoveniers_finance/storage
  sudo chown -R www-data:www-data /var/www/hoveniers_finance/bootstrap/cache
  ```
- [ ] Create storage symlink: `php artisan storage:link`
- [ ] Cache configuration: `php artisan config:cache`
- [ ] Cache routes: `php artisan route:cache`
- [ ] Cache views: `php artisan view:cache`

## Domain and SSL Setup
- [ ] Point your domain to the DigitalOcean Droplet IP
- [ ] Install certbot: `sudo apt install certbot python3-certbot-nginx`
- [ ] Configure SSL certificate: `sudo certbot --nginx -d your-domain.com -d www.your-domain.com`
- [ ] Test automatic renewal: `sudo certbot renew --dry-run`

## Final Checks
- [ ] Verify the application is running correctly
- [ ] Check error logs: `/var/log/nginx/error.log` and storage/logs
- [ ] Test login functionality
- [ ] Test invoice generation
- [ ] Ensure all features work in production 