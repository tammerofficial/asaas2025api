# Livewire SPA Setup for Central Dashboard

## Installation Steps

### 1. Install Livewire and Octane

First, you need to install the packages. If composer fails due to the private repository issue, you can:

Option A: Temporarily remove the private repository from `composer.json`, install packages, then add it back.

Option B: Install packages manually:
```bash
cd core
composer require "livewire/livewire:^3.5" "laravel/octane:^2.0"
```

### 2. Publish Livewire Assets

```bash
php artisan livewire:publish --config
php artisan livewire:publish --assets
```

### 3. Clear Cache

```bash
php artisan optimize:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

## Testing the SPA Navigation

Once Livewire is installed, you can test:

1. **Dashboard Page**: Navigate to `/admin-home` - Should show Dashboard
2. **Admin Role Manage Page**: Click on "All Admin" in sidebar - Should navigate without page reload

## How It Works

- The main route (`/admin-home`) uses `AdminLayout` Livewire component
- Navigation in sidebar automatically converts to Livewire navigation
- Pages switch dynamically without full page reload
- All text in UI is in English as required

## Current Status

✅ Created Files:
- `app/Livewire/Landlord/Admin/AdminLayout.php` - Main SPA Layout
- `app/Livewire/Landlord/Admin/Navigation.php` - Navigation Component
- `app/Livewire/Landlord/Admin/Dashboard.php` - Dashboard Component
- `app/Livewire/Landlord/Admin/AdminRoleManage.php` - Admin Role Manage Component
- `resources/views/livewire/landlord/admin/*.blade.php` - All views
- `resources/views/layouts/landlord/admin/master.blade.php` - SPA Layout
- `config/octane.php` - Octane configuration
- `.rr.yaml` - RoadRunner configuration

✅ Updated Files:
- `routes/admin.php` - Uses Livewire conditionally (falls back to controller if Livewire not installed)
- `composer.json` - Added Livewire and Octane packages

## Next Steps After Installation

Once Livewire is installed, test the navigation between:
1. Dashboard
2. Admin Role Manage

Then we can continue with the remaining pages.

## Note

The routes are set up to work even if Livewire is not installed yet - they will fallback to the legacy controller routes.

