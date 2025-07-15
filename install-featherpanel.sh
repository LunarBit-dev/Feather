#!/bin/bash

# FeatherPanel Installation Script
# This script helps set up FeatherPanel on top of a Pterodactyl Panel installation

set -e

echo "🛠️  FeatherPanel Installation Script"
echo "======================================"
echo ""

# Check if we're in the right directory
if [ ! -f "artisan" ]; then
    echo "❌ Error: This script must be run from the root of your Pterodactyl Panel directory."
    echo "   Please cd to your panel directory and run this script again."
    exit 1
fi

# Check if FeatherPanel is already installed
if [ -d "app/Feather" ]; then
    echo "✅ FeatherPanel appears to be already installed!"
    echo "   If you want to reinstall, please remove the app/Feather directory first."
    exit 0
fi

echo "📋 Pre-installation checks..."

# Check PHP version
PHP_VERSION=$(php -r "echo PHP_MAJOR_VERSION.'.'.PHP_MINOR_VERSION;")

# Use PHP directly with the actual version numbers
if php -r "exit(version_compare(PHP_MAJOR_VERSION.'.'.PHP_MINOR_VERSION, '8.1', '>=') ? 0 : 1);"; then
    echo "✅ PHP version: $PHP_VERSION"
else
    echo "❌ Error: PHP 8.1 or higher is required. You have PHP $PHP_VERSION."
    exit 1
fi

# Check if composer is available
if ! command -v composer &> /dev/null; then
    echo "❌ Error: Composer is not installed or not in PATH."
    exit 1
fi
echo "✅ Composer is available"

# Check if npm is available
if ! command -v npm &> /dev/null; then
    echo "❌ Error: npm is not installed or not in PATH."
    exit 1
fi
echo "✅ npm is available"

echo ""
echo "🚀 Starting FeatherPanel installation..."

# Create .env backup
if [ -f ".env" ]; then
    echo "📄 Backing up existing .env file..."
    cp .env .env.backup.$(date +%Y%m%d_%H%M%S)
fi

# Install FeatherPanel directories
echo "📁 Creating FeatherPanel directory structure..."
sudo mkdir -p app/Feather/{Modpacks,Tickets,Themes,Invites,Roles,Shared,Console,config}
sudo mkdir -p routes/feather
sudo mkdir -p resources/views/feather/{themes,tickets,modpacks}
sudo mkdir -p database/migrations/feather
sudo mkdir -p plugins
sudo mkdir -p themes
sudo mkdir -p public/themes
sudo mkdir -p lang/en

echo "✅ Directory structure created"

# Set proper permissions
echo "🔒 Setting permissions..."
sudo chmod -R 755 storage bootstrap/cache
sudo chmod -R 775 themes plugins public/themes
echo "✅ Permissions set"

# Install dependencies if needed
echo "📦 Installing/updating dependencies..."
sudo composer install --no-dev --optimize-autoloader

echo "📦 Installing npm dependencies (resolving React version conflicts)..."
if sudo npm install --legacy-peer-deps; then
    echo "✅ Dependencies installed successfully"
else
    echo "⚠️  npm install failed, trying with --force flag..."
    sudo npm install --force
    echo "✅ Dependencies installed with --force (may have warnings)"
fi

# Run migrations
echo "🗄️  Running FeatherPanel migrations..."
if [ -d "database/migrations/feather" ] && [ "$(ls -A database/migrations/feather)" ]; then
    sudo php artisan migrate --path=database/migrations/feather --force
    echo "✅ Migrations completed"
else
    echo "ℹ️  No FeatherPanel migrations found to run"
fi

# Clear caches
echo "🧹 Clearing caches..."
sudo php artisan config:clear
sudo php artisan cache:clear
sudo php artisan view:clear
echo "✅ Caches cleared"

# Compile assets
echo "🎨 Compiling assets..."
echo "   Detected Node.js $(node --version) - using legacy OpenSSL provider for compatibility..."

# Set Node.js legacy OpenSSL provider for webpack compatibility
export NODE_OPTIONS="--openssl-legacy-provider"

if sudo NODE_OPTIONS="--openssl-legacy-provider" npm run build; then
    echo "✅ Assets compiled successfully"
else
    echo "⚠️  Asset compilation failed, trying alternative method..."
    if sudo NODE_OPTIONS="--openssl-legacy-provider --max-old-space-size=4096" npm run dev; then
        echo "✅ Assets compiled with dev build"
    else
        echo "❌ Asset compilation failed. You may need to run 'npm run build' manually later."
        echo "   Use: NODE_OPTIONS=\"--openssl-legacy-provider\" npm run build"
    fi
fi

echo ""
echo "🎉 FeatherPanel installation completed!"
echo ""
echo "📝 Next steps:"
echo "   1. Configure your .env file with FeatherPanel settings:"
echo "      - FEATHER_ENABLED=true"
echo "      - FEATHER_*_ENABLED=true (for modules you want to enable)"
echo "      - Configure Supabase SMTP for email notifications"
echo "      - Add API keys for CurseForge/Modrinth if using modpack features"
echo ""
echo "   2. Visit your panel and navigate to:"
echo "      - /admin/themes (Theme management)"
echo "      - /tickets (Ticket system)"
echo "      - /modpacks (Modpack browser)"
echo ""
echo "   3. Check the README.md for detailed configuration options"
echo ""
echo "🚀 Enjoy FeatherPanel!"
