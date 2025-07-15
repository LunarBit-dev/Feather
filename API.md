# FeatherPanel API Documentation

The FeatherPanel API extends Pterodactyl's existing API with additional endpoints for managing themes, tickets, modpacks, and more.

## 🔑 Authentication

FeatherPanel uses the same authentication system as Pterodactyl Panel:

- **API Keys**: Create application API keys in the admin panel
- **User Tokens**: Personal access tokens for user-specific actions

### Headers

```http
Authorization: Bearer YOUR_API_KEY
Content-Type: application/json
Accept: application/json
```

## 🎨 Themes API

### List Available Themes

```http
GET /api/feather/themes
```

**Response:**
```json
{
    "data": [
        {
            "id": "modern-dark",
            "name": "Modern Dark",
            "description": "A sleek dark theme",
            "version": "1.2.0",
            "author": "ThemeAuthor",
            "active": true,
            "preview_url": "/themes/modern-dark/preview.png",
            "installed_at": "2024-01-15T10:30:00Z"
        }
    ],
    "meta": {
        "total": 5,
        "active_theme": "modern-dark"
    }
}
```

### Get Theme Details

```http
GET /api/feather/themes/{theme_id}
```

### Install Theme

```http
POST /api/feather/themes/install
```

**Request:**
```json
{
    "source": "upload",
    "file": "base64_encoded_zip_file"
}
```

**Response:**
```json
{
    "message": "Theme installed successfully",
    "theme": {
        "id": "new-theme",
        "name": "New Theme",
        "status": "installed"
    }
}
```

### Activate Theme

```http
POST /api/feather/themes/{theme_id}/activate
```

### Delete Theme

```http
DELETE /api/feather/themes/{theme_id}
```

## 🎫 Tickets API

### List Tickets

```http
GET /api/feather/tickets
```

**Query Parameters:**
- `status` - Filter by status (open, in_progress, resolved, closed)
- `priority` - Filter by priority (low, medium, high, urgent)
- `assigned_to` - Filter by assigned user ID
- `per_page` - Items per page (default: 15)
- `page` - Page number

**Response:**
```json
{
    "data": [
        {
            "id": 1,
            "subject": "Server not starting",
            "status": "open",
            "priority": "high",
            "user": {
                "id": 123,
                "username": "user123",
                "email": "user@example.com"
            },
            "assigned_to": {
                "id": 456,
                "username": "admin"
            },
            "server": {
                "id": 789,
                "name": "My Server",
                "identifier": "abc123def"
            },
            "replies_count": 3,
            "created_at": "2024-01-15T10:30:00Z",
            "updated_at": "2024-01-15T14:20:00Z"
        }
    ],
    "meta": {
        "current_page": 1,
        "total": 25,
        "per_page": 15
    }
}
```

### Create Ticket

```http
POST /api/feather/tickets
```

**Request:**
```json
{
    "subject": "Server Configuration Issue",
    "message": "Detailed description of the issue...",
    "priority": "medium",
    "server_id": 789,
    "category": "technical"
}
```

### Get Ticket Details

```http
GET /api/feather/tickets/{ticket_id}
```

**Response:**
```json
{
    "data": {
        "id": 1,
        "subject": "Server not starting",
        "status": "open",
        "priority": "high",
        "message": "Original ticket message...",
        "user": {...},
        "assigned_to": {...},
        "server": {...},
        "replies": [
            {
                "id": 1,
                "message": "Reply message...",
                "user": {...},
                "created_at": "2024-01-15T11:00:00Z"
            }
        ],
        "created_at": "2024-01-15T10:30:00Z"
    }
}
```

### Reply to Ticket

```http
POST /api/feather/tickets/{ticket_id}/reply
```

**Request:**
```json
{
    "message": "Reply message...",
    "internal": false
}
```

### Update Ticket

```http
PATCH /api/feather/tickets/{ticket_id}
```

**Request:**
```json
{
    "status": "in_progress",
    "priority": "high",
    "assigned_to": 456
}
```

## 📦 Modpacks API

### Search Modpacks

```http
GET /api/feather/modpacks/search
```

**Query Parameters:**
- `q` - Search query
- `game` - Game type (minecraft, terraria, etc.)
- `source` - Source platform (curseforge, modrinth)
- `category` - Modpack category
- `per_page` - Results per page

**Response:**
```json
{
    "data": [
        {
            "id": "curse_123456",
            "name": "All the Mods 9",
            "slug": "all-the-mods-9",
            "description": "Kitchen sink modpack...",
            "game": "minecraft",
            "source": "curseforge",
            "downloads": 1500000,
            "version": "0.2.44",
            "minecraft_version": "1.20.1",
            "author": "ATM Team",
            "thumbnail": "https://...",
            "updated_at": "2024-01-15T08:00:00Z"
        }
    ],
    "meta": {
        "total": 150,
        "sources": ["curseforge", "modrinth"]
    }
}
```

### Get Modpack Details

```http
GET /api/feather/modpacks/{modpack_id}
```

### Install Modpack

```http
POST /api/feather/modpacks/{modpack_id}/install
```

**Request:**
```json
{
    "server_id": 789,
    "version": "0.2.44",
    "options": {
        "backup_first": true,
        "stop_server": true
    }
}
```

**Response:**
```json
{
    "message": "Modpack installation started",
    "installation": {
        "id": "inst_abc123",
        "status": "pending",
        "progress": 0
    }
}
```

### Get Installation Status

```http
GET /api/feather/modpacks/installations/{installation_id}
```

**Response:**
```json
{
    "data": {
        "id": "inst_abc123",
        "status": "installing",
        "progress": 45,
        "current_step": "Downloading mods",
        "started_at": "2024-01-15T10:00:00Z",
        "estimated_completion": "2024-01-15T10:15:00Z"
    }
}
```

## 👥 Roles API

### List Roles

```http
GET /api/feather/roles
```

### Create Role

```http
POST /api/feather/roles
```

**Request:**
```json
{
    "name": "Moderator",
    "display_name": "Server Moderator",
    "description": "Can manage tickets and moderate servers",
    "permissions": [
        "feather.tickets.view",
        "feather.tickets.reply",
        "feather.themes.view"
    ]
}
```

### Assign Role to User

```http
POST /api/feather/users/{user_id}/roles
```

**Request:**
```json
{
    "role_id": 5
}
```

## 📨 Invites API

### Create Invite

```http
POST /api/feather/invites
```

**Request:**
```json
{
    "email": "newuser@example.com",
    "role_id": 3,
    "expires_at": "2024-01-22T10:30:00Z",
    "max_uses": 1,
    "message": "Welcome to our server!"
}
```

### List Invites

```http
GET /api/feather/invites
```

### Revoke Invite

```http
DELETE /api/feather/invites/{invite_id}
```

## ⚙️ System API

### Get FeatherPanel Status

```http
GET /api/feather/system/status
```

**Response:**
```json
{
    "data": {
        "version": "1.0.0",
        "enabled": true,
        "modules": {
            "themes": {
                "enabled": true,
                "active_theme": "modern-dark"
            },
            "tickets": {
                "enabled": true,
                "open_tickets": 15
            },
            "modpacks": {
                "enabled": true,
                "cache_status": "healthy"
            }
        },
        "stats": {
            "total_themes": 8,
            "total_tickets": 142,
            "total_modpack_installs": 28
        }
    }
}
```

### Get Configuration

```http
GET /api/feather/system/config
```

### Update Configuration

```http
PUT /api/feather/system/config
```

**Request:**
```json
{
    "feather_themes_enabled": true,
    "feather_tickets_auto_assign": false,
    "feather_modpacks_cache_ttl": 1800
}
```

## 📊 Analytics API

### Get Usage Statistics

```http
GET /api/feather/analytics/usage
```

**Query Parameters:**
- `period` - Time period (day, week, month, year)
- `start_date` - Start date (YYYY-MM-DD)
- `end_date` - End date (YYYY-MM-DD)

**Response:**
```json
{
    "data": {
        "period": "month",
        "tickets": {
            "created": 45,
            "resolved": 38,
            "avg_resolution_time": "4.2 hours"
        },
        "themes": {
            "installations": 12,
            "activations": 8
        },
        "modpacks": {
            "searches": 156,
            "installations": 23
        }
    }
}
```

## 🚨 Error Responses

All API endpoints return consistent error responses:

### Validation Error (422)

```json
{
    "message": "The given data was invalid.",
    "errors": {
        "subject": [
            "The subject field is required."
        ],
        "priority": [
            "The priority field must be one of: low, medium, high, urgent."
        ]
    }
}
```

### Not Found (404)

```json
{
    "message": "Ticket not found."
}
```

### Unauthorized (401)

```json
{
    "message": "Unauthenticated."
}
```

### Forbidden (403)

```json
{
    "message": "You do not have permission to access this resource."
}
```

### Server Error (500)

```json
{
    "message": "An error occurred while processing your request.",
    "error_id": "err_abc123def"
}
```

## 📝 Rate Limiting

API endpoints are rate limited:

- **General API**: 60 requests per minute
- **Upload endpoints**: 10 requests per minute
- **Search endpoints**: 30 requests per minute

Rate limit headers are included in responses:

```http
X-RateLimit-Limit: 60
X-RateLimit-Remaining: 59
X-RateLimit-Reset: 1642248600
```

## 🔗 WebSocket Events

FeatherPanel provides real-time updates via WebSocket:

### Ticket Events

```javascript
// Subscribe to ticket updates
Echo.channel('tickets')
    .listen('TicketCreated', (e) => {
        console.log('New ticket:', e.ticket);
    })
    .listen('TicketUpdated', (e) => {
        console.log('Ticket updated:', e.ticket);
    });

// Subscribe to specific ticket
Echo.channel(`ticket.${ticketId}`)
    .listen('ReplyAdded', (e) => {
        console.log('New reply:', e.reply);
    });
```

### Modpack Installation Events

```javascript
Echo.channel(`modpack.install.${installationId}`)
    .listen('InstallationProgress', (e) => {
        console.log('Progress:', e.progress);
    })
    .listen('InstallationCompleted', (e) => {
        console.log('Installation completed');
    });
```

## 📖 SDK Examples

### JavaScript/Node.js

```javascript
const axios = require('axios');

class FeatherPanelAPI {
    constructor(baseURL, apiKey) {
        this.client = axios.create({
            baseURL: `${baseURL}/api/feather`,
            headers: {
                'Authorization': `Bearer ${apiKey}`,
                'Content-Type': 'application/json'
            }
        });
    }
    
    async getTickets(filters = {}) {
        const response = await this.client.get('/tickets', { params: filters });
        return response.data;
    }
    
    async createTicket(ticketData) {
        const response = await this.client.post('/tickets', ticketData);
        return response.data;
    }
    
    async installModpack(modpackId, serverId, options = {}) {
        const response = await this.client.post(`/modpacks/${modpackId}/install`, {
            server_id: serverId,
            ...options
        });
        return response.data;
    }
}

// Usage
const feather = new FeatherPanelAPI('https://panel.example.com', 'your_api_key');
const tickets = await feather.getTickets({ status: 'open' });
```

### Python

```python
import requests

class FeatherPanelAPI:
    def __init__(self, base_url, api_key):
        self.base_url = f"{base_url}/api/feather"
        self.headers = {
            "Authorization": f"Bearer {api_key}",
            "Content-Type": "application/json"
        }
    
    def get_tickets(self, **filters):
        response = requests.get(f"{self.base_url}/tickets", 
                               headers=self.headers, params=filters)
        return response.json()
    
    def create_ticket(self, ticket_data):
        response = requests.post(f"{self.base_url}/tickets", 
                                headers=self.headers, json=ticket_data)
        return response.json()

# Usage
feather = FeatherPanelAPI("https://panel.example.com", "your_api_key")
tickets = feather.get_tickets(status="open")
```

For more examples and detailed documentation, visit our [GitHub Wiki](https://github.com/LunarBit-dev/Feather/wiki).
