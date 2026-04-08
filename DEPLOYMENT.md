# Deployment guide for GitHub Actions -> cPanel (FTP)

This repository includes a GitHub Actions workflow at `.github/workflows/ci-cd.yml` that:

- Runs a CI job on pull requests and pushes to `main` (PHP setup, composer install, quick lint, runs PHPUnit if present).
- Deploys to your cPanel server via FTP when a push to `main` occurs or when you trigger the workflow manually.

Required GitHub Secrets
-----------------------
Set the following repository Secrets (Repository Settings -> Secrets):

- `FTP_HOST` — your cPanel FTP hostname (e.g., ftp.example.com)
- `FTP_USERNAME` — FTP username
- `FTP_PASSWORD` — FTP password
- `FTP_REMOTE_PATH` — path on server where project should be uploaded (e.g., `/public_html/` or `/public_html/your-site/`)
- `FTP_PORT` — (optional) FTP port (default 21)
- `FTP_PROTOCOL` — (optional) `ftp` or `ftps` (the action supports both)

Notes and recommended workflow
------------------------------

- The workflow deploys the repository root by default (`local-dir: ./`). If you'd prefer to only upload the `public` folder, set `FTP_REMOTE_PATH` to point to the appropriate remote directory and/or modify the workflow to set `local-dir: public/`.
- You mentioned you'll clean `public/html` — good. Make sure the target remote directory matches how you want files laid out.
- Deploy is gated to runs on `main` pushes or via manual `workflow_dispatch`. If you want deploys on other branches, edit `.github/workflows/ci-cd.yml`.
- For safety, consider adding a `.ftpignore` or tuning the action to exclude `vendor/` and other local-only files and instead run `composer install` on the server or deploy `vendor` with care.

How to trigger
--------------

1. Commit and push these files to your GitHub repository (push to `main` to trigger automatic deploy). Example:

   git add .github/workflows/ci-cd.yml DEPLOYMENT.md
   git commit -m "Add CI/CD workflow and deployment guide"
   git push origin main

2. Or trigger a manual deployment from the Actions tab: select the workflow and click "Run workflow".

Next steps / Improvements
-------------------------

- Use SFTP/rsync for faster, safer deployments.
- Add a build step to compile assets (npm/yarn) if the project includes frontend assets.
- Implement zero-downtime deploys (upload to a release folder and switch a symlink) — requires SSH access.

If you want, I can:

- Change the workflow to deploy only the `public` folder.
- Add an `.ftpignore` file to exclude `storage`, `vendor`, `.env`.
- Add an SFTP/rsync-based workflow (requires SSH key setup).
# 🚀 TaskGo Live Deployment Guide

## Server Requirements

- PHP 8.2+ with extensions: BCMath, Ctype, Fileinfo, JSON, Mbstring, OpenSSL, PDO, Tokenizer, XML
- MySQL 8.0+ or SQLite 3
- Composer
- Node.js & NPM (optional for assets)
- SSL Certificate (Recommended)

---

## Step 1: Prepare Files for Upload

### Option A: Using Git (Recommended)
```bash
# On your server
cd /var/www
git clone https://github.com/your-repo/taskgo.git
cd taskgo
```

### Option B: Upload via FTP/SFTP
Upload all files EXCEPT these folders:
- `/vendor` (will be installed on server)
- `/node_modules` (if exists)
- `.env` (will be created on server)

---

## Step 2: Server Configuration

### Create `.env` file on server:
```bash
cp .env.example .env
nano .env
```

### Update `.env` for Production:
```env
APP_NAME="Task Go"
APP_ENV=production
APP_KEY=
APP_DEBUG=false
APP_TIMEZONE=Asia/Dhaka
APP_URL=https://yourdomain.com

# Database (MySQL recommended for production)
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=taskgo_db
DB_USERNAME=your_db_user
DB_PASSWORD=your_db_password

# Session
SESSION_DRIVER=database
SESSION_LIFETIME=120

# Cache
CACHE_STORE=file

# Queue
QUEUE_CONNECTION=database
```

---

## Step 3: Install Dependencies

```bash
# Install PHP dependencies
composer install --optimize-autoloader --no-dev

# Generate application key
php artisan key:generate

# Create storage link
php artisan storage:link
```

---

## Step 4: Database Setup

### For MySQL:
```sql
-- Create database
CREATE DATABASE taskgo_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Create user
CREATE USER 'taskgo_user'@'localhost' IDENTIFIED BY 'secure_password';
GRANT ALL PRIVILEGES ON taskgo_db.* TO 'taskgo_user'@'localhost';
FLUSH PRIVILEGES;
```

### Run Migrations:
```bash
php artisan migrate --force
php artisan db:seed --force
```

---

## Step 5: Optimize for Production

```bash
# Cache configuration
php artisan config:cache

# Cache routes
php artisan route:cache

# Cache views
php artisan view:cache

# Optimize autoloader
composer dump-autoload --optimize
```

---

## Step 6: Set Permissions

```bash
# Set ownership (adjust user/group for your server)
sudo chown -R www-data:www-data /var/www/taskgo

# Set directory permissions
sudo find /var/www/taskgo -type d -exec chmod 755 {} \;

# Set file permissions
sudo find /var/www/taskgo -type f -exec chmod 644 {} \;

# Make storage and cache writable
sudo chmod -R 775 /var/www/taskgo/storage
sudo chmod -R 775 /var/www/taskgo/bootstrap/cache
```

---

## Step 7: Web Server Configuration

### Apache (with .htaccess)
Make sure `mod_rewrite` is enabled:
```bash
sudo a2enmod rewrite
sudo systemctl restart apache2
```

Virtual Host example (`/etc/apache2/sites-available/taskgo.conf`):
```apache
<VirtualHost *:80>
    ServerName yourdomain.com
    ServerAlias www.yourdomain.com
    DocumentRoot /var/www/taskgo/public

    <Directory /var/www/taskgo/public>
        AllowOverride All
        Require all granted
    </Directory>

    ErrorLog ${APACHE_LOG_DIR}/taskgo_error.log
    CustomLog ${APACHE_LOG_DIR}/taskgo_access.log combined
</VirtualHost>
```

Enable site:
```bash
sudo a2ensite taskgo.conf
sudo systemctl reload apache2
```

### Nginx
```nginx
server {
    listen 80;
    server_name yourdomain.com www.yourdomain.com;
    root /var/www/taskgo/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

---

## Step 8: SSL Certificate (Let's Encrypt)

```bash
# Install Certbot
sudo apt install certbot python3-certbot-apache  # For Apache
# OR
sudo apt install certbot python3-certbot-nginx   # For Nginx

# Get SSL certificate
sudo certbot --apache -d yourdomain.com -d www.yourdomain.com
# OR
sudo certbot --nginx -d yourdomain.com -d www.yourdomain.com
```

---

## Step 9: Setup Cron Jobs

Add to crontab (`crontab -e`):
```cron
* * * * * cd /var/www/taskgo && php artisan schedule:run >> /dev/null 2>&1
```

---

## Step 10: Queue Worker (Optional but Recommended)

### Using Supervisor:
```bash
sudo apt install supervisor
```

Create config (`/etc/supervisor/conf.d/taskgo-worker.conf`):
```ini
[program:taskgo-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/taskgo/artisan queue:work --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=www-data
numprocs=2
redirect_stderr=true
stdout_logfile=/var/www/taskgo/storage/logs/worker.log
stopwaitsecs=3600
```

Start worker:
```bash
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start taskgo-worker:*
```

---

## 🔐 Default Login Credentials

| Role  | Email              | Password |
|-------|-------------------|----------|
| Admin | admin@taskgo.com  | password |
| Agent | agent@taskgo.com  | password |
| User  | user@taskgo.com   | password |

⚠️ **IMPORTANT**: Change these passwords immediately after first login!

---

## 📋 Post-Deployment Checklist

- [ ] Change default admin password
- [ ] Change default agent password  
- [ ] Configure payment methods in Admin > Settings
- [ ] Set currency rates in Admin > Settings
- [ ] Create announcements
- [ ] Test user registration
- [ ] Test task submission
- [ ] Test deposit/withdrawal flow
- [ ] Setup backup schedule

---

## 🔧 Troubleshooting

### 500 Internal Server Error
```bash
# Check Laravel logs
tail -f /var/www/taskgo/storage/logs/laravel.log

# Check permissions
sudo chmod -R 775 storage bootstrap/cache
```

### Blank Page
```bash
# Clear all caches
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

### Images Not Loading
```bash
# Recreate storage link
php artisan storage:link
```

---

## 🔄 Updating the Application

```bash
# Pull latest changes (if using git)
git pull origin main

# Install new dependencies
composer install --optimize-autoloader --no-dev

# Run new migrations
php artisan migrate --force

# Clear and rebuild caches
php artisan cache:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

---

## 📞 Support

If you face any issues, check:
1. Laravel logs: `storage/logs/laravel.log`
2. Server error logs
3. PHP error logs

Good luck with your deployment! 🎉
