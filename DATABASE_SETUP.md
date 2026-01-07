# Database Setup - Automatic Environment Detection

This application automatically detects the environment and uses the appropriate database without requiring manual configuration changes.

## 🔄 How It Works

The system automatically switches databases based on the `APP_ENV` value:

| Environment | Database | Use Case |
|-------------|----------|----------|
| `local` | SQLite | Local development |
| `testing` | SQLite | Automated testing |
| `production` | MySQL | Live server |
| `staging` | MySQL | Staging server |

## 🚀 Quick Setup

### Local Development (SQLite)
```bash
# Run the automatic setup script
php setup_local.php

# Start the development server
php artisan serve
```

### Production Deployment (MySQL)
```bash
# Deploy your code to the server
# Then run the production setup script
php deploy_production.php
```

## 📁 Configuration Files

### `.env` File
The `.env` file contains both SQLite and MySQL configurations. The system automatically selects the appropriate one based on environment:

```env
# Database Configuration - Auto-detected
DB_CONNECTION=sqlite                    # Used for local
DB_DATABASE=database/database.sqlite     # Used for local

DB_HOST=localhost                       # Used for production
DB_PORT=3306                           # Used for production
DB_DATABASE_MYSQL=your_database_name    # Used for production
DB_USERNAME=your_username               # Used for production
DB_PASSWORD=your_password               # Used for production
```

**Important:** `DB_DATABASE` is used for SQLite (local), while `DB_DATABASE_MYSQL` is used for MySQL (production).

### `config/database.php`
The database configuration uses smart auto-detection:

```php
'default' => env('DB_CONNECTION', match(env('APP_ENV', 'local')) {
    'local' => 'sqlite',
    'testing' => 'sqlite',  
    'production' => 'mysql',
    'staging' => 'mysql',
    default => 'sqlite'
}),
```

## 🛠️ Environment Detection

### Local Development
- `APP_ENV=local` → SQLite database
- No database server required
- Fast and lightweight
- Easy to reset and test

### Production Server
- `APP_ENV=production` → MySQL database
- Robust and scalable
- Supports concurrent connections
- Production-ready performance

## 📋 Deployment Checklist

### For Local Development:
- [ ] Clone the repository
- [ ] Run `php setup_local.php`
- [ ] Start with `php artisan serve`
- [ ] Login with master credentials

### For Production Server:
- [ ] Deploy code to server
- [ ] Set `APP_ENV=production` in `.env`
- [ ] Update MySQL credentials in `.env`
- [ ] Run `php deploy_production.php`
- [ ] Test the application

## 🔧 Manual Override

If you need to manually specify the database, you can set `DB_CONNECTION` in your `.env` file:

```env
# Force MySQL in local development
DB_CONNECTION=mysql

# Force SQLite in production (not recommended)
DB_CONNECTION=sqlite
```

## 🗄️ Database Schema

Both SQLite and MySQL use the same schema with:
- Proper foreign key constraints
- Indexes for performance
- Enum fields for status management
- Audit trails and timestamps

## 🚨 Important Notes

1. **No Manual Changes Required**: The system automatically detects and switches databases
2. **Same Schema**: Both databases use identical table structures
3. **Data Migration**: Use the provided scripts for initial setup
4. **Environment Specific**: Each environment maintains its own database
5. **Backup Strategy**: Ensure appropriate backups for production MySQL

## 🐛 Troubleshooting

### SQLite Issues
```bash
# Recreate SQLite database
php setup_local.php

# Clear caches
php artisan config:clear
php artisan cache:clear
```

### MySQL Issues
```bash
# Check connection
php deploy_production.php

# Verify credentials
mysql -u username -p database_name
```

### Environment Detection
```bash
# Check current environment
php artisan tinker
> echo env('APP_ENV');

# Check database connection
php artisan tinker  
> echo config('database.default');
```

## 📞 Support

For issues with database setup:
1. Check the environment detection logic
2. Verify database credentials
3. Run the appropriate setup script
4. Check the error logs in `storage/logs/laravel.log`
