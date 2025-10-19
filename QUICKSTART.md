# JDShop - Quick Start Commands

Copy and paste these commands to get up and running quickly!

## 🚀 Fresh Installation (All Commands)

```bash
# 1. Install dependencies
composer install
npm install

# 2. Setup environment
cp .env.example .env
php artisan key:generate

# 3. Database setup (SQLite)
touch database/database.sqlite
php artisan migrate
php artisan db:seed

# 4. Storage setup
php artisan storage:link

# 5. Start development servers
# Run these in separate terminals:

# Terminal 1:
php artisan serve

# Terminal 2:
npm run dev
```

## 🪟 Windows PowerShell Version

```powershell
# Install dependencies
composer install
npm install

# Setup environment
Copy-Item .env.example .env
php artisan key:generate

# Database setup
New-Item database/database.sqlite -ItemType File
php artisan migrate
php artisan db:seed

# Storage setup
php artisan storage:link

# Start servers (run in separate terminals)
php artisan serve  # Terminal 1
npm run dev        # Terminal 2
```

## 🔄 Reset Everything & Start Fresh

```bash
# Delete database
rm database/database.sqlite

# Recreate and reseed
touch database/database.sqlite
php artisan migrate:fresh --seed

# Clear all caches
php artisan optimize:clear

# Rebuild assets
npm run build
```

## 🧪 Run Tests

```bash
# Run all tests
php artisan test

# Run specific test file
php artisan test tests/Feature/ProductManagementTest.php

# Run with coverage
php artisan test --coverage
```

## 🛠️ Common Development Commands

### Clear Caches
```bash
php artisan optimize:clear
# or individually:
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

### Database Commands
```bash
# Fresh migration (deletes all data!)
php artisan migrate:fresh

# Fresh migration with seeding
php artisan migrate:fresh --seed

# Rollback last migration
php artisan migrate:rollback

# Seed specific seeder
php artisan db:seed --class=RoleSeeder
```

### Asset Commands
```bash
# Development with hot reload
npm run dev

# Production build
npm run build

# Watch mode
npm run watch
```

## 📱 Mobile Testing Setup

### Using Local Network

```bash
# 1. Find your IP (Mac/Linux)
ifconfig | grep "inet "

# Windows
ipconfig

# 2. Update .env
# Replace APP_URL with: http://YOUR_IP:8000

# 3. Restart server
php artisan serve --host=0.0.0.0

# 4. Access on mobile: http://YOUR_IP:8000/shop
```

### Using ngrok

```bash
# 1. Start Laravel
php artisan serve

# 2. In another terminal
ngrok http 8000

# 3. Use the ngrok URL on your mobile device
```

## 🔐 Default Login Credentials

All passwords: `password`

**Super Admin:**
```
Email: admin@jdshop.com
Password: password
```

**Admin:**
```
Email: manager@jdshop.com
Password: password
```

**Editor:**
```
Email: editor@jdshop.com
Password: password
```

## 📂 Quick File Access

### Backend Files
```bash
# Models
app/Models/Product.php
app/Models/Image.php
app/Models/Role.php
app/Models/User.php

# Controllers
app/Http/Controllers/ProductController.php
app/Http/Controllers/ShopController.php
app/Http/Controllers/Auth/AuthController.php

# Routes
routes/web.php

# Migrations
database/migrations/

# Seeders
database/seeders/
```

### Frontend Files
```bash
# Admin Pages
resources/js/Pages/Admin/Dashboard.vue
resources/js/Pages/Admin/ProductCreate.vue
resources/js/Pages/Admin/ProductEdit.vue

# Shop Pages
resources/js/Pages/Shop/Index.vue
resources/js/Pages/Shop/Show.vue

# Layouts
resources/js/Layouts/AdminLayout.vue
resources/js/Layouts/ShopLayout.vue

# Components
resources/js/Components/Admin/
resources/js/Components/Shop/
```

## 🐛 Troubleshooting Commands

### Fix: Images not displaying
```bash
php artisan storage:link
chmod -R 775 storage bootstrap/cache
```

### Fix: Routes not working
```bash
php artisan route:clear
php artisan route:cache
composer dump-autoload
```

### Fix: View/Component errors
```bash
npm run build
php artisan view:clear
```

### Fix: Permission errors
```bash
# Mac/Linux
chmod -R 775 storage
chmod -R 775 bootstrap/cache

# Set owner (replace www-data with your web server user)
sudo chown -R $USER:www-data storage
sudo chown -R $USER:www-data bootstrap/cache
```

## 🎯 Access Points

After starting the server:

- **Shop Homepage:** http://localhost:8000/shop
- **Login Page:** http://localhost:8000/login
- **Admin Dashboard:** http://localhost:8000/admin
- **Create Product:** http://localhost:8000/admin/products/create

## 📦 Package Installation (if needed)

### Backend Packages
```bash
composer require intervention/image-laravel
composer require tightenco/ziggy
```

### Frontend Packages
```bash
npm install @inertiajs/vue3
npm install vue
npm install axios
```

## 🔧 Tinker Commands

```bash
php artisan tinker
```

Then in tinker:

### Create Admin User
```php
$role = App\Models\Role::where('slug', 'admin')->first();
$user = App\Models\User::create([
    'name' => 'John Doe',
    'email' => 'john@example.com',
    'password' => bcrypt('password'),
    'role_id' => $role->id
]);
```

### Create Product
```php
$product = App\Models\Product::create([
    'title' => 'Test Product',
    'description' => 'Test description',
    'tags' => 'test,demo',
    'price' => 99.99,
    'sold_out' => false
]);
```

### List All Products
```php
App\Models\Product::all();
```

### List All Users
```php
App\Models\User::with('role')->get();
```

## 🚢 Production Deployment

```bash
# 1. Clone repository
git clone <repo-url>
cd jdshop

# 2. Install dependencies
composer install --optimize-autoloader --no-dev
npm install --production

# 3. Environment setup
cp .env.example .env
# Edit .env with production settings
php artisan key:generate

# 4. Database
php artisan migrate --force
php artisan db:seed --force

# 5. Storage
php artisan storage:link

# 6. Optimize
composer dump-autoload --optimize
php artisan config:cache
php artisan route:cache
php artisan view:cache
npm run build

# 7. Permissions
chmod -R 775 storage bootstrap/cache
```

## ⚡ Performance Tips

### Cache Everything
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache
```

### Clear Everything
```bash
php artisan optimize:clear
```

### Optimize for Production
```bash
composer install --optimize-autoloader --no-dev
php artisan optimize
npm run build
```

## 📝 Git Commands

```bash
# Initial commit
git init
git add .
git commit -m "Initial JDShop implementation"

# Create develop branch
git checkout -b develop

# Push to remote
git remote add origin <repo-url>
git push -u origin main
```

## 🎨 Customization Quick Edits

### Change App Name
```bash
# Edit .env
APP_NAME="Your Shop Name"

# Edit resources/js/app.js
const appName = 'Your Shop Name';
```

### Change Colors
```bash
# Edit your Vue components
# Replace: blue-600, purple-600, etc.
# With your brand colors
```

### Add Logo
```bash
# Add your logo to: public/logo.png
# Update layouts to use it
```

That's it! You're ready to go! 🎉

For detailed information, see:
- README.md - Project overview
- SETUP.md - Detailed setup guide
- IMPLEMENTATION_SUMMARY.md - What was built

