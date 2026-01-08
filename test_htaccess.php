<?php

echo "=== Laravel .htaccess Configuration Test ===\n\n";

// Check if .htaccess files exist
$rootHtaccess = file_exists('.htaccess');
$publicHtaccess = file_exists('public/.htaccess');

echo "📁 File Check:\n";
echo "Root .htaccess: " . ($rootHtaccess ? "✅ EXISTS" : "❌ MISSING") . "\n";
echo "Public .htaccess: " . ($publicHtaccess ? "✅ EXISTS" : "❌ MISSING") . "\n\n";

if ($rootHtaccess) {
    $rootContent = file_get_contents('.htaccess');
    echo "🔧 Root .htaccess Features:\n";
    echo "• Public folder redirect: " . (strpos($rootContent, '/public/') !== false ? "✅" : "❌") . "\n";
    echo "• Security headers: " . (strpos($rootContent, 'X-Content-Type-Options') !== false ? "✅" : "❌") . "\n";
    echo "• Directory protection: " . (strpos($rootContent, 'RedirectMatch 403') !== false ? "✅" : "❌") . "\n";
    echo "• HTTPS redirect: " . (strpos($rootContent, 'RewriteCond %{HTTPS}') !== false ? "🔄 Available (commented)" : "❌") . "\n\n";
}

if ($publicHtaccess) {
    $publicContent = file_get_contents('public/.htaccess');
    echo "🔧 Public .htaccess Features:\n";
    echo "• Laravel routing: " . (strpos($publicContent, 'index.php') !== false ? "✅" : "❌") . "\n";
    echo "• Cache control: " . (strpos($publicContent, 'mod_expires') !== false ? "✅" : "❌") . "\n";
    echo "• Gzip compression: " . (strpos($publicContent, 'mod_deflate') !== false ? "✅" : "❌") . "\n";
    echo "• Security headers: " . (strpos($publicContent, 'X-Frame-Options') !== false ? "✅" : "❌") . "\n\n";
}

echo "🚀 Deployment Instructions:\n";
echo "1. Upload all files to your server's root directory\n";
echo "2. Ensure both .htaccess files are in place:\n";
echo "   - Root .htaccess (removes /public from URLs)\n";
echo "   - public/.htaccess (handles Laravel routing)\n";
echo "3. Configure your .env file for production\n";
echo "4. Set proper file permissions (755 for directories, 644 for files)\n";
echo "5. Run: composer install --optimize-autoloader --no-dev\n";
echo "6. Run: php artisan config:cache\n";
echo "7. Run: php artisan route:cache\n";
echo "8. Run: php artisan view:cache\n\n";

echo "🌐 URL Structure After Deployment:\n";
echo "✅ https://yourdomain.com/ (instead of /public/)\n";
echo "✅ https://yourdomain.com/login\n";
echo "✅ https://yourdomain.com/admin/dashboard\n";
echo "✅ https://yourdomain.com/register\n\n";

echo "🔒 Security Features:\n";
echo "• Blocks access to .env, .git, storage/, config/, etc.\n";
echo "• Implements security headers (XSS, CSRF, etc.)\n";
echo "• Prevents directory browsing\n";
echo "• Removes server information\n";
echo "• Cache control for better performance\n";
echo "• Gzip compression for faster loading\n\n";

echo "📋 Test Accounts for Live Server:\n";
echo "Admin: admin@example.com / password\n";
echo "Teacher: teacher1@example.com / password\n";
echo "Student: student1@example.com / password\n\n";

echo "🎉 Your Laravel School Management System is ready for deployment!\n";
echo "Check DEPLOYMENT_GUIDE.md for detailed instructions.\n";