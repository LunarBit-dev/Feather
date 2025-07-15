# 🪶 FeatherPanel - How to Use Guide

Welcome to FeatherPanel! This guide will walk you through using all the features after installation.

## 🎯 Getting Started

After installing FeatherPanel, you'll have access to several new features in your Pterodactyl Panel:

- **🎨 Theme Management** - Customize your panel's appearance
- **🎫 Support Tickets** - Built-in ticketing system
- **📦 Modpack Browser** - Install modpacks directly to servers
- **👥 Enhanced Roles** - Advanced permission management
- **📨 User Invitations** - Invite users with specific roles

## 🎨 Theme Management

### Accessing Theme Manager

1. **Login as Administrator**
2. **Navigate to:** `/admin/themes` or use the admin sidebar
3. **You'll see:** List of installed themes and upload options

### Installing a New Theme

**Method 1: Upload ZIP File**
1. Click **"Upload Theme"** button
2. Select your theme ZIP file (max 50MB)
3. Click **"Install Theme"**
4. Wait for processing completion

**Method 2: Manual Installation**
1. Extract theme files to `/themes/theme-name/` directory
2. Ensure `theme.json` manifest is present
3. Click **"Scan for Themes"** in admin panel

### Theme Structure
Your theme ZIP should contain:
```
my-theme/
├── theme.json          # Required manifest file
├── assets/
│   ├── css/
│   │   └── app.css
│   └── js/
│       └── app.js
├── views/              # Optional custom views
└── preview.png         # Theme preview image
```

### Example theme.json:
```json
{
    "name": "My Custom Theme",
    "version": "1.0.0",
    "author": "Your Name",
    "description": "A beautiful custom theme",
    "pterodactyl_version": ">=1.11.0",
    "feather_version": ">=1.0.0",
    "preview": "preview.png",
    "assets": {
        "css": ["assets/css/app.css"],
        "js": ["assets/js/app.js"]
    }
}
```

### Activating a Theme

1. Go to **Admin → Themes**
2. Find your theme in the list
3. Click **"Activate"**
4. Your panel will immediately use the new theme

### Managing Themes

- **Preview**: Click theme name to see preview
- **Deactivate**: Switch back to default Pterodactyl theme
- **Delete**: Remove theme from server (irreversible)
- **Update**: Upload new version of existing theme

## 🎫 Support Tickets

### For Users

**Creating a Ticket**
1. Navigate to `/tickets` in your panel
2. Click **"New Ticket"**
3. Fill out the form:
   - **Subject**: Brief description
   - **Server**: Select affected server (optional)
   - **Priority**: Low, Medium, High, or Urgent
   - **Category**: Select appropriate category
   - **Message**: Detailed description
4. Click **"Submit Ticket"**

**Managing Your Tickets**
- **View Tickets**: See all your tickets at `/tickets`
- **Reply**: Click ticket to add replies
- **Attachments**: Upload files (if enabled)
- **Status**: Track progress (Open → In Progress → Resolved → Closed)

### For Staff/Admins

**Accessing Ticket Management**
1. Go to **Admin → Tickets** or `/admin/tickets`
2. View all tickets with filtering options

**Responding to Tickets**
1. Click on any ticket to view details
2. Add reply in the response box
3. Optionally:
   - Change priority level
   - Assign to staff member
   - Update status
   - Mark reply as internal (staff-only)

**Ticket Assignment**
- **Auto-assign**: Enable in settings for automatic assignment
- **Manual assign**: Select staff member from dropdown
- **Reassign**: Change assignment at any time

**Ticket Categories**
- Technical Support
- Billing Issues
- Server Problems
- Feature Requests
- General Questions

## 📦 Modpack Management

### Browsing Modpacks

1. **Navigate to:** `/modpacks`
2. **Search**: Use search bar or browse categories
3. **Filter by:**
   - Game type (Minecraft, Terraria, etc.)
   - Source (CurseForge, Modrinth)
   - Category/Genre

### Installing Modpacks

**Prerequisites:**
- Server must be stopped
- Sufficient disk space
- Compatible game type

**Installation Steps:**
1. Find desired modpack
2. Click **"Install"**
3. Select target server
4. Choose modpack version
5. Configure options:
   - ✅ **Backup server first** (recommended)
   - ✅ **Stop server automatically**
   - ✅ **Clear existing mods** (for clean install)
6. Click **"Start Installation"**

**Monitoring Installation:**
- Real-time progress bar
- Current step indicator
- Estimated completion time
- Installation logs

**Supported Sources:**
- **CurseForge**: Largest modpack repository
- **Modrinth**: Modern, fast platform
- **Custom**: Manual modpack uploads

### Installation Process
1. **Validation**: Check server compatibility
2. **Backup**: Create server backup (if enabled)
3. **Download**: Fetch modpack files
4. **Extract**: Unpack modpack contents
5. **Install**: Copy files to server
6. **Configure**: Update server settings
7. **Complete**: Ready to start server

## 👥 Role Management

### Understanding Roles

FeatherPanel extends Pterodactyl's role system with additional permissions:

**Default Roles:**
- **Super Admin**: Full access to everything
- **Admin**: Panel administration
- **Moderator**: User and ticket management
- **Support**: Ticket system access
- **User**: Basic panel access

### Managing User Roles

**For Administrators:**
1. Go to **Admin → Users**
2. Click on any user
3. Navigate to **Roles** tab
4. Assign/remove FeatherPanel roles

**FeatherPanel Permissions:**
- `feather.themes.view` - View theme management
- `feather.themes.install` - Install new themes
- `feather.themes.activate` - Activate themes
- `feather.tickets.view` - View tickets
- `feather.tickets.create` - Create tickets
- `feather.tickets.reply` - Reply to tickets
- `feather.tickets.admin` - Manage all tickets
- `feather.modpacks.view` - Browse modpacks
- `feather.modpacks.install` - Install modpacks
- `feather.invites.create` - Send invitations
- `feather.admin.*` - Full admin access

### Creating Custom Roles

1. **Admin → Roles → Create Role**
2. **Set basic info:**
   - Role name
   - Display name
   - Description
3. **Select permissions:**
   - Check desired FeatherPanel permissions
   - Combine with existing Pterodactyl permissions
4. **Save role**

## 📨 User Invitations

### Sending Invitations

**Admin Process:**
1. Go to **Admin → Invitations**
2. Click **"Send Invitation"**
3. Fill invitation form:
   - **Email**: Recipient's email
   - **Role**: Assign initial role
   - **Expires**: Set expiration date
   - **Max Uses**: How many times can be used
   - **Message**: Personal welcome message
4. Click **"Send Invitation"**

**Email Sent Contains:**
- Welcome message
- Registration link
- Role information
- Expiration date

### Managing Invitations

**View Active Invitations:**
- See all pending invitations
- Track usage statistics
- Monitor expiration dates

**Invitation Actions:**
- **Resend**: Send invitation email again
- **Revoke**: Cancel invitation immediately
- **Extend**: Update expiration date
- **Modify**: Change role or permissions

### User Registration Process

**For Invited Users:**
1. **Receive email** invitation
2. **Click registration link**
3. **Complete signup form:**
   - Username
   - Password
   - Confirm details
4. **Account created** with assigned role
5. **Welcome email** sent with next steps

## ⚙️ Configuration & Settings

### Environment Configuration

Key settings in your `.env` file:

```env
# Enable/disable modules
FEATHER_THEMES_ENABLED=true
FEATHER_TICKETS_ENABLED=true
FEATHER_MODPACKS_ENABLED=true

# Theme settings
FEATHER_THEMES_ALLOW_UPLOAD=true
FEATHER_THEMES_MAX_SIZE=50  # MB

# Ticket settings
FEATHER_TICKETS_AUTO_ASSIGN=false
FEATHER_TICKETS_MAX_ATTACHMENTS=5

# Email settings
FEATHER_SMTP_HOST=smtp.supabase.co
FEATHER_SMTP_PORT=587
FEATHER_SMTP_USERNAME=your-username
FEATHER_SMTP_PASSWORD=your-password
```

### Admin Panel Settings

**Global Settings:**
1. **Admin → FeatherPanel → Settings**
2. **Configure:**
   - Module enable/disable
   - Default theme
   - Email templates
   - File upload limits
   - Security settings

### Security Best Practices

**Theme Security:**
- Only install themes from trusted sources
- Review theme code before installation
- Keep themes updated
- Use virus scanning if available

**Ticket Security:**
- Limit file attachment types
- Set reasonable file size limits
- Regular backup of ticket data
- Monitor for spam/abuse

**General Security:**
- Regular FeatherPanel updates
- Strong SMTP credentials
- Proper file permissions
- Monitor system logs

## 🔧 Troubleshooting

### Common Issues

**Theme Not Loading:**
1. Check theme.json syntax
2. Verify file permissions
3. Clear panel cache: `php artisan cache:clear`
4. Check error logs

**Tickets Not Working:**
1. Verify database migrations ran
2. Check email SMTP settings
3. Confirm user permissions
4. Review ticket module status

**Modpack Installation Fails:**
1. Check server disk space
2. Verify API keys (CurseForge/Modrinth)
3. Ensure server is stopped
4. Check network connectivity

**Email Not Sending:**
1. Verify SMTP credentials
2. Check firewall/port settings
3. Test email configuration
4. Review mail logs

### Getting Help

**Log Files:**
- Laravel logs: `storage/logs/laravel.log`
- FeatherPanel logs: `storage/logs/feather.log`
- Web server logs: Check your web server configuration

**Debug Mode:**
```env
FEATHER_DEBUG=true
APP_DEBUG=true  # Only for troubleshooting
```

**Support Channels:**
- GitHub Issues: Report bugs
- Discord Community: Get help from users
- Documentation: Check wiki for guides

## 📱 Mobile Usage

### Mobile-Responsive Design

FeatherPanel is fully responsive and works on:
- **Smartphones**: iOS/Android browsers
- **Tablets**: iPad, Android tablets
- **Mobile browsers**: Chrome, Safari, Firefox

### Mobile Features

**Touch-Optimized:**
- Large touch targets
- Swipe gestures
- Mobile-friendly forms
- Responsive tables

**Key Mobile Functions:**
- Create and reply to tickets
- Browse modpacks
- View server status
- Basic theme management

## 🚀 Advanced Usage

### API Integration

**Authentication:**
```bash
# Get API token from panel
curl -H "Authorization: Bearer YOUR_TOKEN" \
     https://panel.example.com/api/feather/tickets
```

**Common API Endpoints:**
- `GET /api/feather/tickets` - List tickets
- `POST /api/feather/tickets` - Create ticket
- `GET /api/feather/modpacks/search` - Search modpacks
- `POST /api/feather/themes/install` - Install theme

### WebSocket Real-time Updates

**JavaScript Integration:**
```javascript
// Listen for ticket updates
Echo.channel('tickets')
    .listen('TicketCreated', (e) => {
        // Handle new ticket
    });

// Monitor modpack installation
Echo.channel(`modpack.install.${installId}`)
    .listen('InstallationProgress', (e) => {
        // Update progress bar
    });
```

### CLI Commands

**Useful Artisan Commands:**
```bash
# Check FeatherPanel status
php artisan feather:status

# Install modpack via CLI
php artisan feather:modpack:install curse:123456 --server=1

# Sync marketplace data
php artisan feather:marketplace:sync

# Generate API documentation
php artisan feather:docs:generate
```

## 🎉 Tips & Tricks

### Power User Tips

**Keyboard Shortcuts:**
- `Ctrl+N` - New ticket (on tickets page)
- `Ctrl+/` - Search modpacks (on modpacks page)
- `Ctrl+S` - Save current form

**Bulk Operations:**
- Select multiple tickets for bulk actions
- Mass assign tickets to staff
- Bulk update ticket priorities

**Customization:**
- Create custom ticket categories
- Set up auto-responses
- Configure notification preferences
- Customize email templates

### Best Practices

**For Users:**
- Use descriptive ticket subjects
- Include server information when relevant
- Attach screenshots for visual issues
- Follow up on resolved tickets

**For Administrators:**
- Regular theme and system updates
- Monitor disk usage for modpack installations
- Set up automated backups
- Review and optimize email templates
- Train staff on ticket management

**For Developers:**
- Use the plugin API for custom extensions
- Follow Laravel conventions
- Test themes thoroughly before production
- Contribute to the community

---

## 🆘 Need More Help?

**Documentation:**
- [Installation Guide](README.md)
- [Developer Guide](DEVELOPMENT.md)
- [API Documentation](API.md)

**Community:**
- GitHub Issues for bug reports
- Discord for community support
- Wiki for detailed guides

**Professional Support:**
- Contact FeatherPanel team for enterprise support
- Custom development services available
- Training and consultation options

---

*Happy panel management with FeatherPanel! 🚀*
