# 🚀 TaskGo - cPanel Hosting Deployment Guide

## 📋 Pre-Deployment Checklist

আগে এই কাজগুলো Local এ করুন:
- [ ] সব কোড test করা হয়েছে
- [ ] Database backup নেওয়া হয়েছে
- [ ] `.env` file production ready করা হয়েছে

---

## Step 1: Files Upload করুন

### Option A: ZIP Upload (Recommended for first time)

1. **Local এ ZIP তৈরি করুন:**
   - পুরো project folder ZIP করুন
   - তবে এগুলো বাদ দিন:
     - `/vendor` folder
     - `/node_modules` folder (যদি থাকে)
     - `.env` file
     - `/storage/logs/*` files

2. **cPanel এ Login করুন**

3. **File Manager এ যান:**
   - `public_html` folder এ যান
   - অথবা subdomain এর জন্য সেই folder এ যান

4. **ZIP Upload করুন এবং Extract করুন**

---

## Step 2: Folder Structure Setup (গুরুত্বপূর্ণ!)

cPanel এ Laravel এর জন্য special structure দরকার:

### Current Structure:
```
public_html/
├── app/
├── bootstrap/
├── config/
├── database/
├── public/        <-- এটা মূল সমস্যা
├── resources/
├── routes/
├── storage/
├── vendor/
└── ...
```

### Required Structure:
```
home/username/
├── taskgo/                    <-- Laravel files (public_html এর বাইরে)
│   ├── app/
│   ├── bootstrap/
│   ├── config/
│   ├── database/
│   ├── resources/
│   ├── routes/
│   ├── storage/
│   ├── vendor/
│   └── ...
│
public_html/                   <-- শুধু public folder এর content
├── index.php (modified)
├── .htaccess
├── css/
├── js/
└── storage -> ../taskgo/storage/app/public (symlink)
```

### কিভাবে করবেন:

#### A) Main Domain এ Deploy করলে:

1. **`public_html` এর বাইরে folder তৈরি করুন:**
   - File Manager এ `home/your_username/` এ যান
   - New Folder: `taskgo` নামে তৈরি করুন

2. **Laravel files move করুন:**
   - সব files (public folder বাদে) `taskgo` folder এ move করুন

3. **Public folder এর content `public_html` এ রাখুন:**
   - `public` folder এর সব content `public_html` এ copy করুন

4. **`public_html/index.php` Edit করুন:**

```php
<?php

use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Determine if the application is in maintenance mode...
if (file_exists($maintenance = __DIR__.'/../taskgo/storage/framework/maintenance.php')) {
    require $maintenance;
}

// Register the Composer autoloader...
require __DIR__.'/../taskgo/vendor/autoload.php';

// Bootstrap Laravel and handle the request...
(require_once __DIR__.'/../taskgo/bootstrap/app.php')
    ->handleRequest(Request::capture());
```

#### B) Subdomain এ Deploy করলে:

1. cPanel এ Subdomain তৈরি করুন (যেমন: `app.yourdomain.com`)
2. Document Root: `public_html/taskgo/public` সেট করুন
3. সব files `public_html/taskgo/` এ upload করুন

---

## Step 3: Database Setup

### MySQL Database তৈরি করুন:

1. **cPanel > MySQL Databases**

2. **New Database তৈরি করুন:**
   - Database Name: `taskgo_db`

3. **New User তৈরি করুন:**
   - Username: `taskgo_user`
   - Password: Strong password দিন

4. **User কে Database এ Add করুন:**
   - ALL PRIVILEGES দিন

---

## Step 4: .env File তৈরি করুন

cPanel File Manager এ `.env` file তৈরি করুন (`taskgo` folder এ):

```env
APP_NAME="Task Go"
APP_ENV=production
APP_KEY=
APP_DEBUG=false
APP_TIMEZONE=Asia/Dhaka
APP_URL=https://yourdomain.com

APP_LOCALE=en
APP_FALLBACK_LOCALE=en

BCRYPT_ROUNDS=12

LOG_CHANNEL=single
LOG_LEVEL=error

DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=your_cpanel_username_taskgo_db
DB_USERNAME=your_cpanel_username_taskgo_user
DB_PASSWORD=your_database_password

SESSION_DRIVER=file
SESSION_LIFETIME=120
SESSION_ENCRYPT=false

FILESYSTEM_DISK=public
QUEUE_CONNECTION=sync

CACHE_STORE=file
```

**Note:** cPanel এ database name format: `cpanel_username_database_name`

---

## Step 5: Composer Install (Terminal/SSH)

### Option A: SSH Access থাকলে:

```bash
cd ~/taskgo
composer install --optimize-autoloader --no-dev
php artisan key:generate
php artisan migrate --force
php artisan db:seed --force
php artisan storage:link
```

### Option B: SSH Access না থাকলে:

1. **Local এ vendor folder তৈরি করুন:**
```bash
composer install --optimize-autoloader --no-dev
```

2. **vendor folder ZIP করে upload করুন**

3. **cPanel Terminal ব্যবহার করুন** (যদি available):
   - cPanel > Terminal
   - উপরের commands run করুন

---

## Step 6: Storage Link তৈরি করুন

### Option A: Artisan command (SSH/Terminal):
```bash
php artisan storage:link
```

### Option B: Manual symlink:
cPanel File Manager এ:
1. `public_html` এ যান
2. Symbolic Link তৈরি করুন:
   - Link Name: `storage`
   - Link Path: `/home/username/taskgo/storage/app/public`

### Option C: PHP দিয়ে (যদি উপরেরগুলো কাজ না করে):

`public_html/create-symlink.php` ফাইল তৈরি করুন:
```php
<?php
$target = $_SERVER['DOCUMENT_ROOT'].'/../taskgo/storage/app/public';
$link = $_SERVER['DOCUMENT_ROOT'].'/storage';
symlink($target, $link);
echo 'Symlink created!';
```
Browser এ visit করুন: `https://yourdomain.com/create-symlink.php`
তারপর file টি delete করুন!

---

## Step 7: Permissions Set করুন

cPanel File Manager এ:

1. **storage folder:**
   - Right click > Change Permissions
   - Permission: 775 (recursive)

2. **bootstrap/cache folder:**
   - Permission: 775

---

## Step 8: .htaccess Check করুন

`public_html/.htaccess` এ এটা থাকতে হবে:

```apache
<IfModule mod_rewrite.c>
    <IfModule mod_negotiation.c>
        Options -MultiViews -Indexes
    </IfModule>

    RewriteEngine On

    # Handle Authorization Header
    RewriteCond %{HTTP:Authorization} .
    RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]

    # Redirect Trailing Slashes If Not A Folder...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_URI} (.+)/$
    RewriteRule ^ %1 [L,R=301]

    # Send Requests To Front Controller...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^ index.php [L]
</IfModule>
```

---

## Step 9: SSL Certificate Setup

1. **cPanel > SSL/TLS Status**
2. **AutoSSL চালু করুন** (বেশিরভাগ hosting এ free)
3. অথবা **Let's Encrypt** install করুন

---

## Step 10: Final Testing

1. **Site visit করুন:** `https://yourdomain.com`
2. **Admin login করুন:** `https://yourdomain.com/admin/login`
3. **Test করুন:**
   - [ ] Homepage loads
   - [ ] Login works
   - [ ] Image upload works
   - [ ] Database operations work

---

## 🔐 Default Login Credentials

| Role  | Email              | Password |
|-------|-------------------|----------|
| Admin | admin@taskgo.com  | password |
| Agent | agent@taskgo.com  | password |
| User  | user@taskgo.com   | password |

⚠️ **গুরুত্বপূর্ণ:** Login করার পর immediately password change করুন!

---

## 🔧 Common Issues & Solutions

### Issue 1: 500 Internal Server Error
```
Solution:
1. Check .htaccess file
2. Check file permissions (775 for storage & cache)
3. Check error logs: cPanel > Error Log
4. Ensure APP_DEBUG=true temporarily to see actual error
```

### Issue 2: Page Not Found (404)
```
Solution:
1. Check mod_rewrite enabled
2. Check .htaccess is correct
3. Run: php artisan route:cache
```

### Issue 3: Images Not Loading
```
Solution:
1. Check storage symlink exists
2. Check FILESYSTEM_DISK=public in .env
3. Recreate symlink if needed
```

### Issue 4: Database Connection Error
```
Solution:
1. Check database credentials in .env
2. Remember: DB name format is cpanel_username_dbname
3. Check user has all privileges
```

### Issue 5: Session/Login Issues
```
Solution:
1. Check SESSION_DRIVER=file
2. Check storage/framework/sessions folder writable
3. Clear browser cookies
```

---

## 📱 Post-Deployment Tasks

1. **Admin Panel Setup:**
   - Payment methods configure করুন
   - Currency rates set করুন
   - Default settings update করুন

2. **Security:**
   - Default passwords change করুন
   - APP_DEBUG=false নিশ্চিত করুন
   - Backup schedule করুন

3. **Monitoring:**
   - Error logs check করুন নিয়মিত
   - Server resources monitor করুন

---

## 📞 Support

কোনো সমস্যা হলে:
1. cPanel Error Log check করুন
2. Laravel log check করুন: `storage/logs/laravel.log`
3. Browser console check করুন

Good luck! 🎉
