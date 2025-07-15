# FeatherPanel Development Guide

Welcome to FeatherPanel development! This guide will help you get started with contributing to the project.

## 🏗️ Architecture Overview

FeatherPanel is built as a modular Laravel package that extends Pterodactyl Panel. Each feature is organized as a separate module with its own service provider.

### Directory Structure

```
app/Feather/
├── FeatherServiceProvider.php      # Main service provider
├── Shared/                         # Shared utilities and traits
├── Themes/                         # Theme management system
├── Tickets/                        # Support ticket system
├── Modpacks/                       # Modpack integration
├── Roles/                          # Enhanced role system
├── Invites/                        # Invitation system
└── Console/                        # Artisan commands

resources/views/feather/            # Blade templates
routes/feather/                     # Route definitions
database/migrations/feather/        # Database migrations
lang/en/                           # Language files
```

## 🚀 Setting Up Development Environment

### Prerequisites

- PHP 8.1+
- Composer
- Node.js & npm
- MySQL/PostgreSQL
- Redis (recommended)
- Running Pterodactyl Panel installation

### Installation Steps

1. **Clone the repository:**
   ```bash
   git clone https://github.com/LunarBit-dev/Feather.git
   cd Feather
   ```

2. **Install dependencies:**
   ```bash
   composer install
   npm install
   ```

3. **Copy FeatherPanel files to your Pterodactyl installation:**
   ```bash
   # Assuming your Pterodactyl is in /var/www/pterodactyl
   cp -r app/Feather /var/www/pterodactyl/app/
   cp -r resources/views/feather /var/www/pterodactyl/resources/views/
   cp -r routes/feather /var/www/pterodactyl/routes/
   # ... etc
   ```

4. **Configure environment:**
   ```bash
   cp .env.feather.example /var/www/pterodactyl/.env.feather
   # Edit .env file and add FeatherPanel settings
   ```

5. **Run migrations:**
   ```bash
   cd /var/www/pterodactyl
   php artisan migrate --path=database/migrations/feather
   ```

## 🛠️ Development Workflow

### Creating a New Module

1. **Create module directory:**
   ```bash
   mkdir -p app/Feather/YourModule/{Controllers,Models,Services}
   ```

2. **Create service provider:**
   ```php
   <?php
   namespace App\Feather\YourModule;
   
   class YourModuleServiceProvider extends ServiceProvider
   {
       public function register()
       {
           // Register services
       }
       
       public function boot()
       {
           // Boot module
       }
   }
   ```

3. **Register in main service provider:**
   ```php
   // In FeatherServiceProvider.php
   public function register()
   {
       $this->app->register(YourModuleServiceProvider::class);
   }
   ```

### Database Migrations

Create migrations in `database/migrations/feather/`:

```bash
php artisan make:migration create_your_table --path=database/migrations/feather
```

### Views and Assets

- Place Blade views in `resources/views/feather/yourmodule/`
- Place CSS/JS in `resources/assets/feather/`
- Use TailwindCSS classes for consistency

### API Development

1. **Create API controller:**
   ```php
   <?php
   namespace App\Feather\YourModule\Controllers\Api;
   
   use App\Http\Controllers\Api\ApplicationApiController;
   
   class YourModuleApiController extends ApplicationApiController
   {
       // Your API methods
   }
   ```

2. **Define API routes:**
   ```php
   // In routes/feather/api.php
   Route::group(['prefix' => 'yourmodule'], function () {
       Route::get('/', [YourModuleApiController::class, 'index']);
   });
   ```

## 🧪 Testing

### Unit Tests

Create tests in `tests/Feature/Feather/`:

```php
<?php
namespace Tests\Feature\Feather;

use Tests\TestCase;

class YourModuleTest extends TestCase
{
    public function test_your_feature()
    {
        // Test your feature
    }
}
```

### Running Tests

```bash
# Run all FeatherPanel tests
php artisan test --filter=Feather

# Run specific module tests
php artisan test tests/Feature/Feather/YourModuleTest.php
```

## 📝 Code Standards

### PHP Standards

- Follow PSR-12 coding standards
- Use type hints and return types
- Add docblocks for public methods
- Use Laravel conventions

### Example Controller:

```php
<?php
namespace App\Feather\YourModule\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class YourModuleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        // Implementation
    }
}
```

### JavaScript/CSS Standards

- Use modern JavaScript (ES6+)
- Follow TailwindCSS utility-first approach
- Comment complex logic
- Use consistent naming conventions

## 🎨 Frontend Development

### TailwindCSS Usage

Use existing Pterodactyl theme classes when possible:

```html
<!-- Good: Uses existing theme classes -->
<div class="bg-gray-700 rounded-lg shadow-md p-6">
    <h2 class="text-lg font-semibold text-gray-100">Title</h2>
</div>

<!-- Bad: Custom styles that don't match theme -->
<div style="background: #333; padding: 20px;">
    <h2 style="color: white;">Title</h2>
</div>
```

### Vue.js Components

If adding Vue components:

```javascript
// resources/assets/feather/js/components/YourComponent.vue
<template>
    <div class="your-component">
        <!-- Template -->
    </div>
</template>

<script>
export default {
    name: 'YourComponent',
    props: {
        // Props
    },
    data() {
        return {
            // Data
        };
    }
};
</script>
```

## 🔌 Plugin Development

### Creating a Plugin

1. **Create plugin structure:**
   ```
   plugins/your-plugin/
   ├── plugin.json          # Plugin metadata
   ├── src/                 # Plugin source code
   ├── views/               # Plugin views
   ├── assets/              # Plugin assets
   └── README.md            # Plugin documentation
   ```

2. **Plugin manifest (plugin.json):**
   ```json
   {
       "name": "Your Plugin",
       "version": "1.0.0",
       "description": "Description of your plugin",
       "author": "Your Name",
       "feather_version": ">=1.0.0",
       "main": "src/YourPlugin.php",
       "routes": "routes.php",
       "views": "views/",
       "assets": "assets/",
       "dependencies": [],
       "permissions": []
   }
   ```

### Plugin API

```php
<?php
namespace Plugins\YourPlugin;

use App\Feather\Shared\Contracts\PluginInterface;

class YourPlugin implements PluginInterface
{
    public function boot(): void
    {
        // Initialize plugin
    }
    
    public function register(): void
    {
        // Register services
    }
    
    public function getInfo(): array
    {
        return [
            'name' => 'Your Plugin',
            'version' => '1.0.0',
        ];
    }
}
```

## 🌍 Internationalization

### Adding Translations

1. **Create language file:**
   ```php
   // lang/en/yourmodule.php
   <?php
   return [
       'title' => 'Your Module',
       'actions' => [
           'create' => 'Create New',
           'edit' => 'Edit',
           'delete' => 'Delete',
       ],
   ];
   ```

2. **Use in Blade views:**
   ```blade
   <h1>{{ __('yourmodule.title') }}</h1>
   <button>{{ __('yourmodule.actions.create') }}</button>
   ```

3. **Use in PHP code:**
   ```php
   $message = __('yourmodule.actions.create');
   ```

## 🐛 Debugging

### Debug Mode

Enable debug mode in development:

```env
FEATHER_DEBUG=true
FEATHER_LOG_LEVEL=debug
```

### Logging

Use Laravel's logging system:

```php
use Illuminate\Support\Facades\Log;

Log::channel('feather')->info('Debug message', ['data' => $data]);
Log::channel('feather')->error('Error occurred', ['exception' => $e]);
```

### Common Issues

1. **Service provider not loading:**
   - Check if registered in `FeatherServiceProvider`
   - Clear config cache: `php artisan config:clear`

2. **Views not found:**
   - Check view namespace registration
   - Verify file paths and naming

3. **Routes not working:**
   - Check route file loading in service provider
   - Verify middleware and authentication

## 📦 Building and Deployment

### Asset Compilation

```bash
# Development
npm run dev

# Production
npm run build

# Watch for changes
npm run watch
```

### Creating Releases

1. **Update version numbers:**
   - `composer.json`
   - `package.json`
   - `app/Feather/config/feather.php`

2. **Run tests:**
   ```bash
   php artisan test --filter=Feather
   ```

3. **Build assets:**
   ```bash
   npm run build
   ```

4. **Create release package:**
   ```bash
   ./scripts/build-release.sh
   ```

## 🤝 Contributing

### Pull Request Process

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Add tests for new features
5. Ensure all tests pass
6. Submit a pull request

### Commit Messages

Use conventional commit format:

```
feat(themes): add theme validation system
fix(tickets): resolve attachment upload issue
docs(readme): update installation instructions
```

### Code Review

All contributions require code review. Please:

- Follow the style guide
- Add appropriate tests
- Update documentation
- Respond to feedback promptly

## 📚 Resources

- [Laravel Documentation](https://laravel.com/docs)
- [Pterodactyl Panel](https://pterodactyl.io)
- [TailwindCSS](https://tailwindcss.com)
- [Vue.js](https://vuejs.org) (optional)

## 💬 Getting Help

- GitHub Issues: Report bugs and request features
- Discord: Join our development community
- Documentation: Check the wiki for detailed guides

Happy coding! 🚀
