# Livewire & Octane Installation Guide

## Current Status

✅ **Livewire** and **Octane** are already added to `composer.json`:
- `"livewire/livewire": "^3.5"`
- `"laravel/octane": "^2.0"`

## Installation Issue

⚠️ **Problem**: Cannot install due to missing private package `xgenious/paymentgateway`

## Solution Options

### Option 1: Install with Composer (Recommended)

If you have access to the private repository, make sure your SSH keys are set up:

```bash
cd core
composer require livewire/livewire laravel/octane
```

### Option 2: Install Packages Manually (If repository access is not available)

Temporarily comment out the problematic package in `composer.json`:

```bash
# In composer.json, temporarily comment out or remove:
# "xgenious/paymentgateway": "^6.0.4",

# Then run:
cd core
composer require livewire/livewire laravel/octane

# After installation, uncomment the package line
```

### Option 3: Update Dependencies (If packages are in composer.json but not installed)

If Livewire and Octane are in `composer.json` but not in vendor:

```bash
cd core
composer update livewire/livewire laravel/octane --no-interaction
```

## After Installation

Once Livewire and Octane are installed, publish assets:

```bash
cd core
php artisan livewire:publish --assets
php artisan livewire:publish --config
php artisan route:clear
php artisan config:clear
php artisan cache:clear
php artisan view:clear
```

## Verification

Check if Livewire is installed:

```bash
php artisan livewire:list
php artisan octane:install
```

## Notes

- All Livewire Components are already created and ready
- Routes are configured to use Livewire SPA
- Configuration files are ready
- The application will work once packages are installed


