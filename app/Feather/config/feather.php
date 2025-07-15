<?php

return [
    /*
    |--------------------------------------------------------------------------
    | FeatherPanel Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration options for the FeatherPanel enhancements to Pterodactyl.
    |
    */

    'enabled' => env('FEATHER_ENABLED', true),

    'version' => '1.0.0',

    /*
    |--------------------------------------------------------------------------
    | Module Configuration
    |--------------------------------------------------------------------------
    |
    | Enable or disable specific FeatherPanel modules.
    |
    */
    'modules' => [
        'modpacks' => env('FEATHER_MODPACKS_ENABLED', true),
        'tickets' => env('FEATHER_TICKETS_ENABLED', true),
        'themes' => env('FEATHER_THEMES_ENABLED', true),
        'invites' => env('FEATHER_INVITES_ENABLED', true),
        'roles' => env('FEATHER_ROLES_ENABLED', true),
        'marketplace' => env('FEATHER_MARKETPLACE_ENABLED', false),
    ],

    /*
    |--------------------------------------------------------------------------
    | Theme Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for the theme system.
    |
    */
    'themes' => [
        'path' => base_path('themes'),
        'public_path' => public_path('themes'),
        'cache_enabled' => env('FEATHER_THEME_CACHE', true),
        'default_theme' => env('FEATHER_DEFAULT_THEME', 'pterodactyl'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Plugin Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for the plugin system.
    |
    */
    'plugins' => [
        'path' => base_path('plugins'),
        'auto_discovery' => env('FEATHER_PLUGIN_AUTO_DISCOVERY', true),
        'require_approval' => env('FEATHER_PLUGIN_REQUIRE_APPROVAL', true),
        'audit_enabled' => env('FEATHER_PLUGIN_AUDIT', true),
    ],

    /*
    |--------------------------------------------------------------------------
    | Marketplace Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for the optional marketplace integration.
    |
    */
    'marketplace' => [
        'url' => env('FEATHER_MARKETPLACE_URL', 'https://marketplace.featherpanel.com/api'),
        'auto_update' => env('FEATHER_MARKETPLACE_AUTO_UPDATE', true),
        'update_interval' => env('FEATHER_MARKETPLACE_UPDATE_INTERVAL', 180), // minutes
        'cache_ttl' => env('FEATHER_MARKETPLACE_CACHE_TTL', 3600), // seconds
    ],

    /*
    |--------------------------------------------------------------------------
    | Modpack Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for modpack management.
    |
    */
    'modpacks' => [
        'supported_games' => ['minecraft', 'valheim', 'rust', 'ark'],
        'default_game' => 'minecraft',
        'api_endpoints' => [
            'curseforge' => env('CURSEFORGE_API_KEY'),
            'modrinth' => env('MODRINTH_API_URL', 'https://api.modrinth.com/v2'),
        ],
        'cache_ttl' => env('FEATHER_MODPACK_CACHE_TTL', 1800), // 30 minutes
        'max_file_size' => env('FEATHER_MODPACK_MAX_SIZE', 500 * 1024 * 1024), // 500MB
    ],

    /*
    |--------------------------------------------------------------------------
    | Ticket System Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for the ticket system.
    |
    */
    'tickets' => [
        'departments' => [
            'general' => 'General Support',
            'technical' => 'Technical Support',
            'billing' => 'Billing Support',
            'abuse' => 'Abuse Reports',
        ],
        'priorities' => [
            'low' => 'Low',
            'normal' => 'Normal',
            'high' => 'High',
            'urgent' => 'Urgent',
        ],
        'statuses' => [
            'open' => 'Open',
            'in_progress' => 'In Progress',
            'waiting_customer' => 'Waiting for Customer',
            'resolved' => 'Resolved',
            'closed' => 'Closed',
        ],
        'auto_close_days' => env('FEATHER_TICKET_AUTO_CLOSE_DAYS', 7),
        'email_notifications' => env('FEATHER_TICKET_EMAIL_NOTIFICATIONS', true),
    ],

    /*
    |--------------------------------------------------------------------------
    | Role System Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for the extended role system.
    |
    */
    'roles' => [
        'custom_roles' => [
            'reseller' => [
                'name' => 'Reseller',
                'permissions' => [
                    'user.create',
                    'user.view',
                    'user.edit',
                    'server.create',
                    'server.view',
                    'server.edit',
                ],
            ],
            'support' => [
                'name' => 'Support Staff',
                'permissions' => [
                    'ticket.view',
                    'ticket.reply',
                    'ticket.close',
                    'user.view',
                    'server.view',
                ],
            ],
            'moderator' => [
                'name' => 'Moderator',
                'permissions' => [
                    'ticket.view',
                    'ticket.reply',
                    'ticket.close',
                    'ticket.delete',
                    'user.view',
                    'user.edit',
                    'server.view',
                    'server.edit',
                ],
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Email Configuration
    |--------------------------------------------------------------------------
    |
    | Email settings for FeatherPanel notifications.
    |
    */
    'mail' => [
        'from_name' => env('FEATHER_MAIL_FROM_NAME', 'FeatherPanel'),
        'from_address' => env('FEATHER_MAIL_FROM_ADDRESS', 'noreply@featherpanel.com'),
        'templates' => [
            'ticket_created' => 'feather.emails.ticket.created',
            'ticket_replied' => 'feather.emails.ticket.replied',
            'ticket_closed' => 'feather.emails.ticket.closed',
            'invite_sent' => 'feather.emails.invite.sent',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Security Configuration
    |--------------------------------------------------------------------------
    |
    | Security settings for FeatherPanel.
    |
    */
    'security' => [
        'csrf_protection' => env('FEATHER_CSRF_PROTECTION', true),
        'rate_limiting' => env('FEATHER_RATE_LIMITING', true),
        'audit_logging' => env('FEATHER_AUDIT_LOGGING', true),
        'file_upload_validation' => env('FEATHER_FILE_UPLOAD_VALIDATION', true),
    ],
];
