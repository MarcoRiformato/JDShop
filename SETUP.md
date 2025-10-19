# JDShop - Setup Guide

This guide will walk you through setting up the JDShop application from scratch.

## Prerequisites Checklist

Before you begin, ensure you have:

- [ ] PHP 8.2 or higher installed
- [ ] Composer installed
- [ ] Node.js 18+ and npm installed
- [ ] SQLite, MySQL, or PostgreSQL installed
- [ ] Git installed (for version control)

## Step-by-Step Setup

### Step 1: Install Backend Dependencies

```bash
composer install
```

This will install:
- Laravel Framework 12
- Inertia.js Laravel adapter
- Intervention Image (for image processing)
- Ziggy (for route helpers in Vue)

### Step 2: Install Frontend Dependencies

```bash
npm install
```

This will install:
- Vue 3
- Inertia.js Vue adapter
- Tailwind CSS 4
- Vite and build tools
- Axios for HTTP requests

### Step 3: Environment Configuration

```bash
# Copy the example environment file
cp .env.example .env

# Generate application key
php artisan key:generate
```

**Edit your `.env` file** and configure:

```env
APP_NAME="JD Shop"
APP_URL=http://localhost:8000

# Database configuration
DB_CONNECTION=sqlite
# For SQLite, no additional DB settings needed

# For MySQL, use:
# DB_CONNECTION=mysql
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=jdshop
# DB_USERNAME=root
# DB_PASSWORD=
```

### Step 4: Database Setup

```bash
# If using SQLite, create the database file
touch database/database.sqlite

# Run migrations to create tables
php artisan migrate

# Seed the database with roles, users, and sample products
php artisan db:seed
```

**What gets seeded:**

**Roles:**
- Super Admin
- Admin
- Editor
- Viewer

**Users:**
- admin@jdshop.com (Super Admin)
- manager@jdshop.com (Admin)
- editor@jdshop.com (Editor)

All passwords are: `password`

**Sample Products:**
- 5 demo products with various tags and prices

### Step 5: Storage Setup

```bash
# Create symbolic link from public/storage to storage/app/public
php artisan storage:link

# Create directories for product images
mkdir -p storage/app/public/products
mkdir -p storage/app/public/products/thumbnails
```

On Windows PowerShell:
```powershell
New-Item -ItemType Directory -Force -Path storage/app/public/products
New-Item -ItemType Directory -Force -Path storage/app/public/products/thumbnails
```

### Step 6: Build Frontend Assets

**For Development:**
```bash
npm run dev
```
This starts the Vite dev server with hot module replacement.

**For Production:**
```bash
npm run build
```

### Step 7: Start the Development Server

Open two terminal windows:

**Terminal 1 - Laravel Server:**
```bash
php artisan serve
```

**Terminal 2 - Vite Dev Server (if not already running):**
```bash
npm run dev
```

### Step 8: Access the Application

Open your browser and navigate to:

- **Shop Frontend:** http://localhost:8000/shop
- **Login Page:** http://localhost:8000/login
- **Admin Panel:** http://localhost:8000/admin (after logging in with admin credentials)

## Quick Setup Script (All-in-One)

For a fresh installation, run these commands in sequence:

```bash
# Install dependencies
composer install
npm install

# Setup environment
cp .env.example .env
php artisan key:generate

# Setup database (SQLite)
touch database/database.sqlite
php artisan migrate
php artisan db:seed

# Setup storage
php artisan storage:link
mkdir -p storage/app/public/products/thumbnails

# Build assets
npm run dev
```

Then in another terminal:
```bash
php artisan serve
```

## Testing Your Setup

### 1. Test Public Shop Access
- Navigate to http://localhost:8000/shop
- You should see the shop with sample products
- Click on a product to view details

### 2. Test Authentication
- Go to http://localhost:8000/login
- Login with: `admin@jdshop.com` / `password`
- You should be redirected to the admin dashboard

### 3. Test Admin Panel
- After logging in, you should see the admin dashboard
- Try creating a new product
- Upload images using the camera/gallery option (works on mobile)
- Edit a product
- Delete a product

### 4. Test Image Upload
- Edit any product
- Click "Take Photo / Select Images"
- On mobile: You'll be able to use the camera
- On desktop: You can select files from your computer
- Watch the upload progress
- Verify images appear in the gallery
- Try setting a cover image
- Try deleting an image

## Mobile Testing

To test on a real mobile device:

### Option 1: Using your local network

1. Find your computer's IP address:
   ```bash
   # On Mac/Linux
   ifconfig | grep "inet "
   
   # On Windows
   ipconfig
   ```

2. Update your `.env`:
   ```env
   APP_URL=http://YOUR_IP_ADDRESS:8000
   ```

3. Make sure your phone is on the same WiFi network

4. Access: `http://YOUR_IP_ADDRESS:8000/shop` on your phone

### Option 2: Using ngrok

1. Install ngrok: https://ngrok.com/download

2. Start your Laravel server:
   ```bash
   php artisan serve
   ```

3. In another terminal, start ngrok:
   ```bash
   ngrok http 8000
   ```

4. Use the provided ngrok URL on your mobile device

## Running Tests

```bash
# Run all tests
php artisan test

# Run specific test file
php artisan test tests/Feature/ProductManagementTest.php

# Run with coverage
php artisan test --coverage
```

## Troubleshooting

### Issue: "500 Server Error" when uploading images

**Solution:**
- Check storage permissions: `chmod -R 775 storage bootstrap/cache`
- Ensure storage link exists: `php artisan storage:link`
- Verify `.env` has correct `APP_URL`

### Issue: "Class 'Intervention\Image\ImageManager' not found"

**Solution:**
```bash
composer require intervention/image-laravel
php artisan optimize:clear
```

### Issue: Images not displaying

**Solution:**
- Run: `php artisan storage:link`
- Check `APP_URL` in `.env` matches your actual URL
- Clear browser cache
- Verify files exist in `storage/app/public/products/`

### Issue: "Route [login] not defined"

**Solution:**
```bash
php artisan route:clear
php artisan route:cache
```

### Issue: Vue components not rendering

**Solution:**
```bash
npm run build
php artisan optimize:clear
```

### Issue: "CSRF token mismatch"

**Solution:**
- Clear cookies and cache
- Check `APP_URL` matches your actual URL
- Restart the server

## Development Tips

### Clearing Caches

When you encounter unexpected issues:

```bash
php artisan optimize:clear
# This clears config, route, view, and cache
```

### Watching for File Changes

The Vite dev server (npm run dev) automatically watches for changes in:
- Vue components
- JavaScript files
- CSS files

No need to rebuild manually during development!

### Database Reset

To reset the database and start fresh:

```bash
php artisan migrate:fresh --seed
```

**Warning:** This will delete all data!

### Creating Additional Admin Users

```bash
php artisan tinker
```

Then in the tinker console:
```php
$role = App\Models\Role::where('slug', 'admin')->first();
$user = App\Models\User::create([
    'name' => 'Your Name',
    'email' => 'your@email.com',
    'password' => bcrypt('password'),
    'role_id' => $role->id
]);
```

## Next Steps

Now that your setup is complete:

1. **Customize the branding** - Update colors, logos, and text
2. **Add more product fields** - Extend the product model as needed
3. **Implement cart functionality** - Add shopping cart and checkout
4. **Set up email notifications** - Configure mail settings
5. **Add payment integration** - Stripe, PayPal, etc.
6. **Deploy to production** - Use Laravel Forge, Vapor, or your preferred hosting

## Additional Resources

- **Laravel Documentation:** https://laravel.com/docs
- **Vue 3 Documentation:** https://vuejs.org/guide/introduction.html
- **Inertia.js Documentation:** https://inertiajs.com/
- **Tailwind CSS Documentation:** https://tailwindcss.com/docs
- **Intervention Image:** https://image.intervention.io/

## Support

If you encounter issues not covered here:

1. Check the main README.md for API endpoints and project structure
2. Review Laravel logs: `storage/logs/laravel.log`
3. Check browser console for JavaScript errors
4. Verify all steps in this guide were completed

Happy coding! 🚀

