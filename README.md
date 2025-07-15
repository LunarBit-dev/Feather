[![Logo Image](https://cdn.pterodactyl.io/logos/new/pterodactyl_logo.png)](https://pterodactyl.io)

![GitHub Workflow Status](https://img.shields.io/github/actions/workflow/status/pterodactyl/panel/ci.yaml?label=Tests&style=for-the-badge&branch=1.0-develop)
![Discord](https://img.shields.io/discord/122900397965705216?label=Discord&logo=Discord&logoColor=white&style=for-the-badge)
![GitHub Releases](https://img.shields.io/github/downloads/pterodactyl/panel/latest/total?style=for-the-badge)
![GitHub contributors](https://img.shields.io/github/contributors/pterodactyl/panel?style=for-the-badge)

# 🛠️ FeatherPanel - Enhanced Pterodactyl Panel

FeatherPanel is a modular enhancement framework for the Pterodactyl Panel, adding powerful features like theme management, ticket systems, modpack management, and more while maintaining full compatibility with the original Pterodactyl Panel.

## ✨ Features

### 🎨 Theme System
- **ZIP Upload Support**: Upload custom themes as ZIP packages
- **Theme Manager**: Easy activation/deactivation of themes
- **Manifest System**: Structured theme configuration with `manifest.json`
- **Asset Management**: Automatic publishing of theme assets
- **Fallback Support**: Graceful fallback to default themes

### 🎮 Modpack Manager
- **Multi-Game Support**: Minecraft, Valheim, Rust, ARK, and more
- **External API Integration**: CurseForge and Modrinth support
- **Version Management**: Install specific modpack versions
- **Rollback System**: Easy rollback to previous installations
- **Installation History**: Track all modpack installations per server

### 💬 Ticket System
- **Multi-Department Support**: General, Technical, Billing, Abuse
- **Priority Levels**: Low, Normal, High, Urgent
- **Status Tracking**: Open, In Progress, Waiting, Resolved, Closed
- **Email Notifications**: Automatic email updates via Supabase SMTP
- **Staff Assignment**: Assign tickets to support staff
- **Internal Notes**: Staff-only internal notes

### 🧑‍🤝‍🧑 Enhanced Role System
- **Custom Roles**: Reseller, Support Staff, Moderator
- **Permission Management**: Granular permission control
- **User Visibility**: Role-based user and server visibility
- **ACL Integration**: Extends Pterodactyl's existing ACL system

### 🔌 Plugin System
- **Laravel Package Architecture**: Standard Laravel package structure
- **Auto-Discovery**: Automatic plugin detection and loading
- **Approval System**: Optional plugin approval workflow
- **Audit Logging**: Track plugin installations and activities

### 🛒 Marketplace Integration (Optional)
- **Plugin Marketplace**: Browse and install plugins
- **Theme Marketplace**: Download community themes
- **Auto-Updates**: Scheduled marketplace synchronization
- **GitHub Integration**: Pull from GitHub repositories

### 🌍 Internationalization
- **Multi-Language Support**: Full i18n support with Laravel translations
- **Extensible**: Easy addition of new languages
- **Module-Specific**: Each module has its own translation files

### 🛡️ Security & Auditing
- **CSRF Protection**: Built-in CSRF protection
- **Rate Limiting**: API and action rate limiting
- **Audit Logging**: Comprehensive activity logging
- **File Validation**: Secure file upload validation

## 🚀 Quick Start

### Prerequisites
- Laravel 10.x+
- PHP 8.1+
- Existing Pterodactyl Panel installation
- Composer
- Node.js 16+ & NPM (⚠️ Node.js 17+ requires OpenSSL legacy provider - see troubleshooting)

### Installation
```bash
# Clone the FeatherPanel repository
git clone https://github.com/LunarBit-dev/Feather.git
cd Feather

# Copy to your Pterodactyl installation
cp -r * /var/www/pterodactyl/

# Install dependencies
cd /var/www/pterodactyl
composer install
npm install

# Configure environment
cp .env.feather.example .env
# Edit .env with your settings including Supabase SMTP

# Run migrations
php artisan migrate
php artisan migrate --path=database/migrations/feather

# Generate application key
php artisan key:generate

# Compile assets
npm run build

# Start development server
php artisan serve
```

## 🚨 Troubleshooting

### Node.js OpenSSL Error (Node.js 17+)

If you encounter this error during asset compilation:
```
Error: error:0308010C:digital envelope routines::unsupported
```

**Quick Fix:**
```bash
# Run the automated fix script
./fix-nodejs-build.sh

# Or manually set the environment variable
export NODE_OPTIONS="--openssl-legacy-provider"
npm run build
```

**Permanent Solutions:**
1. **Use Node.js 16 (Recommended):**
   ```bash
   nvm install 16
   nvm use 16
   npm run build
   ```

2. **Set permanent environment variable:**
   ```bash
   echo 'export NODE_OPTIONS="--openssl-legacy-provider"' >> ~/.bashrc
   source ~/.bashrc
   ```

3. **Use the updated npm scripts** (already included in package.json)

### Common Issues

**Permission Denied:**
```bash
sudo chown -R www-data:www-data /var/www/pterodactyl
sudo chmod -R 755 storage bootstrap/cache
```

**Database Migration Errors:**
```bash
php artisan migrate:status
php artisan migrate --path=database/migrations/feather
```

**Cache Issues:**
```bash
php artisan config:clear
php artisan cache:clear
php artisan view:clear
```

## 📚 Usage Examples

### Managing Themes
```bash
# Via Web Interface
Navigate to: /admin/themes

# Via CLI
php artisan feather:install-theme path/to/theme.zip
```

### Managing Modpacks
```bash
# Install modpack
php artisan feather:install-modpack {server_id} {modpack_id}

# Sync marketplace
php artisan feather:sync-marketplace --source=curseforge
```

### Managing Tickets
```bash
# Access tickets
/tickets (users)
/admin/tickets (staff)
```

## 🔧 Configuration

Add to your `.env` file:
```env
# FeatherPanel Configuration
FEATHER_ENABLED=true
FEATHER_MODPACKS_ENABLED=true
FEATHER_TICKETS_ENABLED=true
FEATHER_THEMES_ENABLED=true

# Supabase SMTP
MAIL_MAILER=smtp
MAIL_HOST=smtp.supabase.com
MAIL_PORT=587
MAIL_USERNAME=your@domain.com
MAIL_PASSWORD=yourpassword
MAIL_FROM_ADDRESS=noreply@yourpanel.com
MAIL_FROM_NAME="FeatherPanel"

# API Keys
CURSEFORGE_API_KEY=your_key_here
```

## 🏗️ Architecture

### Directory Structure
```
featherpanel/
├── app/Feather/                    # FeatherPanel modules
│   ├── Modpacks/                   # Modpack management
│   ├── Tickets/                    # Ticket system
│   ├── Themes/                     # Theme management
│   ├── Invites/                    # Invite system
│   ├── Roles/                      # Role management
│   ├── Shared/                     # Shared utilities
│   └── Console/                    # Artisan commands
├── routes/feather/                 # Custom routes
├── resources/views/feather/        # Blade templates
├── database/migrations/feather/    # Database migrations
├── lang/en/                        # Language files
├── plugins/                        # Uploaded plugins
├── themes/                         # Uploaded themes
└── public/themes/                  # Published theme assets
```

## 🎨 Creating Themes

### Theme Structure
```
my-theme/
├── manifest.json              # Theme metadata
├── assets/                    # Static assets
├── views/                     # Blade templates
├── preview.png               # Theme preview
└── README.md                 # Documentation
```

### Manifest Example
```json
{
    "name": "my-awesome-theme",
    "version": "1.0.0",
    "author": "Your Name",
    "description": "A beautiful theme for FeatherPanel",
    "preview_image": "preview.png",
    "compatibility": {
        "feather": "^1.0.0",
        "pterodactyl": "^1.11.0"
    }
}
```

## 🔌 Creating Plugins

FeatherPanel plugins are Laravel packages with additional metadata:

```php
<?php

namespace MyVendor\MyPlugin;

use Illuminate\Support\ServiceProvider;
use App\Feather\Shared\Contracts\FeatherPlugin;

class PluginServiceProvider extends ServiceProvider implements FeatherPlugin
{
    public function getPluginInfo(): array
    {
        return [
            'name' => 'My Plugin',
            'version' => '1.0.0',
            'author' => 'Your Name',
        ];
    }
}
```

## 📧 Email Configuration

FeatherPanel uses Supabase SMTP for reliable email delivery:

1. Create a Supabase project
2. Configure SMTP settings in your Supabase dashboard
3. Add credentials to your `.env` file
4. Test with: `php artisan tinker` → `Mail::raw('Test', function($m) { $m->to('test@example.com')->subject('Test'); });`

## 🛠️ Development

### Contributing
1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Add tests
5. Submit a pull request

### Testing
```bash
php artisan test
```

### Code Quality
```bash
./vendor/bin/php-cs-fixer fix
./vendor/bin/phpstan analyse
```

## 📄 License

MIT License - see [LICENSE](LICENSE) for details.

## 🆘 Support

- 📚 Documentation: Coming soon
- 💬 Discord: Coming soon  
- 🐛 Issues: [GitHub Issues](https://github.com/your-org/featherpanel/issues)

---

**FeatherPanel** - Enhancing Pterodactyl Panel with powerful, modular features.

## Documentation

* [Panel Documentation](https://pterodactyl.io/panel/1.0/getting_started.html)
* [Wings Documentation](https://pterodactyl.io/wings/1.0/installing.html)
* [Community Guides](https://pterodactyl.io/community/about.html)
* Or, get additional help [via Discord](https://discord.gg/pterodactyl)

## Sponsors

I would like to extend my sincere thanks to the following sponsors for helping fund Pterodactyl's development.
[Interested in becoming a sponsor?](https://github.com/sponsors/matthewpi)

| Company                                                                           | About                                                                                                                                                                                                                                           |
|-----------------------------------------------------------------------------------|-------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------|
| [**Aussie Server Hosts**](https://aussieserverhosts.com/)                         | No frills Australian Owned and operated High Performance Server hosting for some of the most demanding games serving Australia and New Zealand.                                                                                                 |
| [**BisectHosting**](https://www.bisecthosting.com/)                               | BisectHosting provides Minecraft, Valheim and other server hosting services with the highest reliability and lightning fast support since 2012.                                                                                                 |
| [**MineStrator**](https://minestrator.com/)                                       | Looking for the most highend French hosting company for your minecraft server? More than 24,000 members on our discord trust us. Give us a try!                                                                                                 |
| [**HostEZ**](https://hostez.io)                                                   | US & EU Rust & Minecraft Hosting. DDoS Protected bare metal, VPS and colocation with low latency, high uptime and maximum availability. EZ!                                                                                                     |
| [**Blueprint**](https://blueprint.zip/?utm_source=pterodactyl&utm_medium=sponsor) | Create and install Pterodactyl addons and themes with the growing Blueprint framework - the package-manager for Pterodactyl. Use multiple modifications at once without worrying about conflicts and make use of the large extension ecosystem. |
| [**indifferent broccoli**](https://indifferentbroccoli.com/)                      | indifferent broccoli is a game server hosting and rental company. With us, you get top-notch computer power for your gaming sessions. We destroy lag, latency, and complexity--letting you focus on the fun stuff.                              |

### Supported Games

Pterodactyl supports a wide variety of games by utilizing Docker containers to isolate each instance. This gives
you the power to run game servers without bloating machines with a host of additional dependencies.

Some of our core supported games include:

* Minecraft — including Paper, Sponge, Bungeecord, Waterfall, and more
* Rust
* Terraria
* Teamspeak
* Mumble
* Team Fortress 2
* Counter Strike: Global Offensive
* Garry's Mod
* ARK: Survival Evolved

In addition to our standard nest of supported games, our community is constantly pushing the limits of this software
and there are plenty more games available provided by the community. Some of these games include:

* Factorio
* San Andreas: MP
* Pocketmine MP
* Squad
* Xonotic
* Starmade
* Discord ATLBot, and most other Node.js/Python discord bots
* [and many more...](https://pterodactyleggs.com)

## License

Pterodactyl® Copyright © 2015 - 2022 Dane Everitt and contributors.

Code released under the [MIT License](./LICENSE.md).
