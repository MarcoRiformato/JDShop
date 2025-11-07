# JD Shop - E-commerce Admin Panel & Shop Frontend

A modern, mobile-first e-commerce platform built with Laravel, Vue 3, Inertia.js, and Tailwind CSS. Features a comprehensive admin panel for product management with mobile-optimized image uploads and a beautiful public-facing shop frontend.

## Features

### Admin Panel
- 🔐 **Role-based Access Control** - Multiple admin roles (Super Admin, Admin, Editor, Viewer)
- 📦 **Product Management** - Full CRUD operations for products
- 📸 **Mobile-Friendly Image Uploads** - Camera/gallery integration with automatic resizing
- 🖼️ **Image Gallery Management** - Set cover images, delete, and organize product photos
- 📱 **Responsive Design** - Works seamlessly on mobile and desktop
- ⚡ **Real-time Image Upload** - AJAX-powered uploads with progress indicators

### Public Shop
- 🛍️ **Product Browsing** - Modern grid layout with product cards
- 🔍 **Search & Filter** - Search products and filter by tags
- 🎨 **Beautiful UI** - Gradient accents, smooth transitions, and modern design
- 📱 **Mobile-First** - Optimized for all screen sizes
- 🖼️ **Product Gallery** - Interactive image galleries on product pages

## Tech Stack

- **Backend**: Laravel 12
- **Frontend**: Vue 3, Inertia.js
- **Styling**: Tailwind CSS 4
- **Image Processing**: Intervention Image
- **Database**: SQLite (easily configurable to MySQL/PostgreSQL)
- **Build Tool**: Vite

## Prerequisites

- PHP 8.2 or higher
- Composer
- Node.js 18+ and npm
- SQLite, MySQL, or PostgreSQL

## Installation

### 1. Clone the repository

```bash
git clone <repository-url>
cd jdshop
```

### 2. Install PHP dependencies

```bash
composer install
```

### 3. Install JavaScript dependencies

```bash
npm install
```

### 4. Environment setup

```bash
cp .env.example .env
php artisan key:generate
```

### 5. Database setup

```bash
# Create SQLite database (if using SQLite)
touch database/database.sqlite

# Run migrations
php artisan migrate

# Seed the database with roles, sample users, and products
php artisan db:seed
```

### 6. Storage setup

```bash
# Create symbolic link for public storage
php artisan storage:link

# Create product image directories
mkdir -p storage/app/public/products
mkdir -p storage/app/public/products/thumbnails
```

### 7. Build assets

```bash
# For development
npm run dev

# For production
npm run build
```

### 8. Start the development server

```bash
# Start Laravel server
php artisan serve

# In another terminal, start Vite dev server (if not already running)
npm run dev
```

The application will be available at `http://localhost:8000`

## Creating Admin Users

To create an admin user, set the following environment variables in your `.env` file before running the database seeder:

```env
ADMIN_EMAIL=your-admin@example.com
ADMIN_PASSWORD=your_secure_password
ADMIN_NAME=Admin Name
```

Then run:

```bash
php artisan db:seed
```

The UserSeeder will create an admin account with the credentials you specified.

## User Roles

- **Super Admin**: Full system access with all permissions
- **Admin**: Administrative access to manage products and content
- **Editor**: Can create and edit products
- **Viewer**: Read-only access to admin panel (does not have admin access)

## Project Structure

```
jdshop/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Auth/AuthController.php
│   │   │   ├── ProductController.php
│   │   │   └── ShopController.php
│   │   ├── Middleware/CheckAdminRole.php
│   │   └── Requests/
│   ├── Models/
│   │   ├── Image.php
│   │   ├── Product.php
│   │   ├── Role.php
│   │   └── User.php
│   └── Services/ImageService.php
├── database/
│   ├── migrations/
│   └── seeders/
├── resources/
│   ├── js/
│   │   ├── Components/
│   │   │   ├── Admin/
│   │   │   │   ├── ImagePreview.vue
│   │   │   │   ├── ImageUploader.vue
│   │   │   │   └── ProductTable.vue
│   │   │   └── Shop/
│   │   │       ├── ProductCard.vue
│   │   │       └── ProductGallery.vue
│   │   ├── Layouts/
│   │   │   ├── AdminLayout.vue
│   │   │   └── ShopLayout.vue
│   │   ├── Pages/
│   │   │   ├── Admin/
│   │   │   │   ├── Dashboard.vue
│   │   │   │   ├── ProductCreate.vue
│   │   │   │   └── ProductEdit.vue
│   │   │   ├── Auth/
│   │   │   │   └── Login.vue
│   │   │   └── Shop/
│   │   │       ├── Index.vue
│   │   │       └── Show.vue
│   │   └── app.js
│   └── css/app.css
└── routes/web.php
```

## API Endpoints

### Public Routes
- `GET /shop` - Shop index page
- `GET /shop/{product}` - Product detail page
- `GET /login` - Login page
- `POST /login` - Login handler

### Admin Routes (Requires Authentication & Admin Role)
- `GET /admin` - Admin dashboard
- `GET /admin/products/create` - Create product form
- `POST /admin/products` - Store new product
- `GET /admin/products/{product}/edit` - Edit product form
- `PUT /admin/products/{product}` - Update product
- `DELETE /admin/products/{product}` - Delete product
- `POST /admin/products/{product}/images` - Upload product image
- `DELETE /admin/images/{image}` - Delete image
- `PATCH /admin/images/{image}/set-cover` - Set cover image

## Image Upload Specifications

- **Accepted formats**: JPEG, PNG, JPG, WebP
- **Max file size**: 2MB per image
- **Automatic processing**:
  - Full-size: Resized to max 1200px width
  - Thumbnail: Resized to 300px width
  - JPEG compression applied for optimization

## Mobile Camera Integration

The image uploader uses the HTML5 `capture` attribute to enable direct camera access on mobile devices:

```html
<input type="file" accept="image/*" capture="environment" multiple>
```

This allows users to:
- Take photos directly with their phone camera
- Select multiple images from their gallery
- Upload images progressively with real-time feedback

## Testing

```bash
# Run all tests
php artisan test

# Run specific test suite
php artisan test --testsuite=Feature

# Run with coverage
php artisan test --coverage
```

## Development

### Running in development mode

```bash
# Terminal 1: Laravel server
php artisan serve

# Terminal 2: Vite dev server
npm run dev
```

### Building for production

```bash
npm run build
php artisan optimize
```

## Deployment

1. Set up your production environment
2. Configure your `.env` file for production
3. Run migrations: `php artisan migrate --force`
4. Seed database: `php artisan db:seed --force`
5. Build assets: `npm run build`
6. Link storage: `php artisan storage:link`
7. Optimize: `php artisan optimize`

## Testing on Mobile Devices

For local mobile testing, use a tool like [ngrok](https://ngrok.com/):

```bash
# Install ngrok
# Then run:
ngrok http 8000
```

This creates a public URL you can access on your mobile device to test camera uploads and mobile UI.

## Troubleshooting

### Images not displaying
- Ensure `php artisan storage:link` has been run
- Check file permissions on `storage/app/public`
- Verify `APP_URL` in `.env` matches your local URL

### Middleware errors
- Clear config cache: `php artisan config:clear`
- Clear route cache: `php artisan route:clear`

### Vue components not loading
- Rebuild assets: `npm run build`
- Clear browser cache
- Check browser console for errors

## License

This project is open-source software licensed under the [MIT license](https://opensource.org/licenses/MIT).

## Credits

Built with:
- [Laravel](https://laravel.com/)
- [Vue.js](https://vuejs.org/)
- [Inertia.js](https://inertiajs.com/)
- [Tailwind CSS](https://tailwindcss.com/)
- [Intervention Image](https://image.intervention.io/)
