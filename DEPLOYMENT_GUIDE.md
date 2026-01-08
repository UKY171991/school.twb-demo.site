# Laravel Deployment Guide - Remove Public Folder from URL

This guide explains how to deploy your Laravel School Management System to a live server and remove the `/public` folder from URLs.

## 📁 File Structure for Live Server

```
your-domain.com/
├── .htaccess                    (Root .htaccess - redirects to public)
├── public/                      (Laravel public folder)
│   ├── .htaccess               (Public .htaccess - handles Laravel routing)
│   ├── index.php               (Laravel entry point)
│   ├── build/                  (Compiled assets)
│   └── ...
├── app/
├── config/
├── database/
├── resources/
├── routes/
├── storage/
├── vendor/
├── .env
└── ...
```

## 🚀 Deployment Steps

### Step 1: Upload Files
1. Upload all Laravel files to your server's root directory
2. Make sure the `.env` file is configured for production
3. Ensure `storage` and `bootstrap/cache` directories are writable

### Step 2: Configure .htaccess Files

#### Root .htaccess (already created)
Place the root `.htaccess` file in your domain's root directory. This file:
- Redirects all requests to the `public` folder
- Removes `/public` from URLs
- Provides security protection
- Handles static assets

#### Public .htaccess (already updated)
The `public/.htaccess` file has been enhanced with:
- Security headers
- Cache control
- Gzip compression
- Protection against sensitive files

### Step 3: Environment Configuration

Update your `.env` file for production:

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com

# Database configuration
DB_CONNECTION=mysql
DB_HOST=your-db-host
DB_PORT=3306
DB_DATABASE=your-database
DB_USERNAME=your-username
DB_PASSWORD=your-password

# Mail configuration (if needed)
MAIL_MAILER=smtp
MAIL_HOST=your-smtp-host
MAIL_PORT=587
MAIL_USERNAME=your-email
MAIL_PASSWORD=your-password
```

### Step 4: Run Deployment Commands

If you have SSH access, run these commands:

```bash
# Install dependencies
composer install --optimize-autoloader --no-dev

# Generate application key (if not set)
php artisan key:generate

# Cache configuration
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Run migrations
php artisan migrate --force

# Link storage
php artisan storage:link

# Set permissions
chmod -R 755 storage
chmod -R 755 bootstrap/cache
```

## 🔧 URL Structure After Deployment

### Before (with public folder):
- ❌ `https://yourdomain.com/public/`
- ❌ `https://yourdomain.com/public/login`
- ❌ `https://yourdomain.com/public/admin/dashboard`

### After (without public folder):
- ✅ `https://yourdomain.com/`
- ✅ `https://yourdomain.com/login`
- ✅ `https://yourdomain.com/admin/dashboard`

## 🔒 Security Features Included

### Root .htaccess Security:
- Blocks access to sensitive directories (`/storage`, `/config`, etc.)
- Prevents access to sensitive files (`.env`, `.git`, etc.)
- Removes server information headers
- Implements security headers

### Public .htaccess Security:
- X-Content-Type-Options: nosniff
- X-Frame-Options: DENY
- X-XSS-Protection: enabled
- Referrer-Policy: strict-origin-when-cross-origin
- Permissions-Policy: restrictive

## 🚨 Important Notes

### For Shared Hosting:
1. Upload all files to your domain's root directory (usually `public_html` or `www`)
2. The root `.htaccess` will handle redirecting to the `public` folder
3. Make sure mod_rewrite is enabled on your server

### For VPS/Dedicated Servers:
1. You can either use this .htaccess method OR
2. Point your domain's document root directly to the `public` folder (recommended)

### SSL Certificate:
- Uncomment the HTTPS redirect lines in root `.htaccess` if you have SSL
- Update `APP_URL` in `.env` to use `https://`

## 🧪 Testing Your Deployment

1. **Homepage**: Visit `https://yourdomain.com` (should show welcome page)
2. **Login**: Visit `https://yourdomain.com/login` (should show login form)
3. **Admin**: Login and visit `https://yourdomain.com/admin/dashboard`
4. **Assets**: Check that CSS/JS files load properly
5. **Security**: Try accessing `https://yourdomain.com/.env` (should be blocked)

## 📞 Test Accounts

Use these accounts to test your live deployment:

- **Admin**: admin@example.com / password
- **Teacher**: teacher1@example.com / password
- **Student**: student1@example.com / password

## 🔧 Troubleshooting

### Common Issues:

1. **500 Internal Server Error**
   - Check file permissions (755 for directories, 644 for files)
   - Ensure `.env` file exists and is configured
   - Check server error logs

2. **Assets not loading**
   - Run `npm run build` before uploading
   - Check that `public/build` directory exists
   - Verify asset paths in browser developer tools

3. **Database connection errors**
   - Verify database credentials in `.env`
   - Ensure database exists and is accessible
   - Run migrations: `php artisan migrate`

4. **Routes not working**
   - Ensure mod_rewrite is enabled
   - Check .htaccess file syntax
   - Clear route cache: `php artisan route:clear`

## 🎉 Success!

If everything is configured correctly, your Laravel School Management System should now be accessible without the `/public` folder in the URL, with enhanced security and performance optimizations.