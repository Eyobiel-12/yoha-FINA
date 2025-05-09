#!/bin/bash

# Deployment script for Yohannes Hoveniersbedrijf finance application

echo "Starting deployment process..."

# Update system packages
sudo apt update
sudo apt upgrade -y

# Install dependencies
sudo apt install -y nginx mysql-server php8.1-fpm php8.1-cli php8.1-common php8.1-mysql \
php8.1-zip php8.1-gd php8.1-mbstring php8.1-curl php8.1-xml php8.1-bcmath \
unzip git curl

# Configure Nginx
sudo tee /etc/nginx/sites-available/hoveniers_finance > /dev/null << EOL
server {
    listen 80;
    server_name your-domain.com www.your-domain.com;
    root /var/www/hoveniers_finance/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;

    charset utf-8;

    location / {
        try_files \$uri \$uri/ /index.php?\$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.1-fpm.sock;
        fastcgi_param SCRIPT_FILENAME \$realpath_root\$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
EOL

# Enable site and restart Nginx
sudo ln -sf /etc/nginx/sites-available/hoveniers_finance /etc/nginx/sites-enabled/
sudo systemctl restart nginx

# Create database
sudo mysql -e "CREATE DATABASE hoveniers_finance;"
sudo mysql -e "CREATE USER 'hoveniers_user'@'localhost' IDENTIFIED BY 'your_secure_password';"
sudo mysql -e "GRANT ALL PRIVILEGES ON hoveniers_finance.* TO 'hoveniers_user'@'localhost';"
sudo mysql -e "FLUSH PRIVILEGES;"

# Application directory setup
sudo mkdir -p /var/www/hoveniers_finance
sudo chown -R $USER:$USER /var/www/hoveniers_finance

echo "Deployment script completed! Next steps:"
echo "1. Upload your application code to /var/www/hoveniers_finance"
echo "2. Set up your .env file with proper database credentials"
echo "3. Run 'php artisan key:generate' to generate an app key"
echo "4. Run 'php artisan migrate --seed' to set up the database"
echo "5. Run 'php artisan storage:link' to create symlink for file storage"
echo "6. Set proper permissions: 'sudo chown -R www-data:www-data /var/www/hoveniers_finance/storage'"
echo "7. Update the domain name in Nginx config" 