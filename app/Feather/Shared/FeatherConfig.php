<?php

namespace App\Feather\Shared;

use Illuminate\Support\Facades\Config;

class FeatherConfig
{
    /**
     * Check if FeatherPanel is enabled.
     */
    public function isEnabled(): bool
    {
        return Config::get('feather.enabled', true);
    }

    /**
     * Check if a specific module is enabled.
     */
    public function isModuleEnabled(string $module): bool
    {
        return Config::get("feather.modules.{$module}", false);
    }

    /**
     * Get FeatherPanel version.
     */
    public function getVersion(): string
    {
        return Config::get('feather.version', '1.0.0');
    }

    /**
     * Get configuration for a specific module.
     */
    public function getModuleConfig(string $module): array
    {
        return Config::get("feather.{$module}", []);
    }

    /**
     * Get all enabled modules.
     */
    public function getEnabledModules(): array
    {
        return array_filter(Config::get('feather.modules', []), function ($enabled) {
            return $enabled === true;
        });
    }

    /**
     * Check if marketplace is enabled.
     */
    public function isMarketplaceEnabled(): bool
    {
        return $this->isModuleEnabled('marketplace');
    }

    /**
     * Get marketplace configuration.
     */
    public function getMarketplaceConfig(): array
    {
        return $this->getModuleConfig('marketplace');
    }
}
