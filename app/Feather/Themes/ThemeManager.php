<?php

namespace App\Feather\Themes;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use ZipArchive;

class ThemeManager
{
    protected string $themesPath;
    protected string $publicPath;
    protected bool $cacheEnabled;

    public function __construct()
    {
        $this->themesPath = config('feather.themes.path');
        $this->publicPath = config('feather.themes.public_path');
        $this->cacheEnabled = config('feather.themes.cache_enabled', true);
    }

    /**
     * Get all available themes.
     */
    public function getAvailableThemes(): array
    {
        $cacheKey = 'feather.themes.available';
        
        if ($this->cacheEnabled && Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }

        $themes = [];
        $directories = File::directories($this->themesPath);

        foreach ($directories as $directory) {
            $manifestPath = $directory . '/manifest.json';
            
            if (File::exists($manifestPath)) {
                $manifest = json_decode(File::get($manifestPath), true);
                
                if ($manifest && $this->isValidManifest($manifest)) {
                    $themes[basename($directory)] = $manifest;
                }
            }
        }

        if ($this->cacheEnabled) {
            Cache::put($cacheKey, $themes, 3600); // 1 hour
        }

        return $themes;
    }

    /**
     * Get the currently active theme.
     */
    public function getActiveTheme(): ?string
    {
        return Cache::get('feather.theme.active', config('feather.themes.default_theme'));
    }

    /**
     * Set the active theme.
     */
    public function setActiveTheme(string $theme): bool
    {
        $availableThemes = $this->getAvailableThemes();
        
        if (!isset($availableThemes[$theme])) {
            return false;
        }

        Cache::put('feather.theme.active', $theme);
        $this->clearCache();
        
        return true;
    }

    /**
     * Check if there's an active theme.
     */
    public function hasActiveTheme(): bool
    {
        return $this->getActiveTheme() !== null;
    }

    /**
     * Get the path to the active theme.
     */
    public function getActiveThemePath(): ?string
    {
        $activeTheme = $this->getActiveTheme();
        
        if (!$activeTheme) {
            return null;
        }

        return $this->themesPath . '/' . $activeTheme;
    }

    /**
     * Install a theme from a ZIP file.
     */
    public function installTheme(string $zipPath): array
    {
        $zip = new ZipArchive();
        
        if ($zip->open($zipPath) !== true) {
            return ['success' => false, 'message' => 'Failed to open ZIP file'];
        }

        // Extract to temporary directory first
        $tempDir = sys_get_temp_dir() . '/feather_theme_' . uniqid();
        $zip->extractTo($tempDir);
        $zip->close();

        // Validate manifest
        $manifestPath = $tempDir . '/manifest.json';
        
        if (!File::exists($manifestPath)) {
            File::deleteDirectory($tempDir);
            return ['success' => false, 'message' => 'Theme manifest not found'];
        }

        $manifest = json_decode(File::get($manifestPath), true);
        
        if (!$this->isValidManifest($manifest)) {
            File::deleteDirectory($tempDir);
            return ['success' => false, 'message' => 'Invalid theme manifest'];
        }

        // Check if theme already exists
        $themeName = $manifest['name'];
        $themeDir = $this->themesPath . '/' . $themeName;
        
        if (File::exists($themeDir)) {
            File::deleteDirectory($tempDir);
            return ['success' => false, 'message' => 'Theme already exists'];
        }

        // Move theme to themes directory
        File::moveDirectory($tempDir, $themeDir);

        // Copy assets to public directory
        $this->publishThemeAssets($themeName);

        $this->clearCache();

        return ['success' => true, 'message' => 'Theme installed successfully', 'theme' => $themeName];
    }

    /**
     * Uninstall a theme.
     */
    public function uninstallTheme(string $theme): bool
    {
        $themeDir = $this->themesPath . '/' . $theme;
        
        if (!File::exists($themeDir)) {
            return false;
        }

        // Don't allow uninstalling the active theme
        if ($this->getActiveTheme() === $theme) {
            return false;
        }

        // Remove theme directory
        File::deleteDirectory($themeDir);

        // Remove public assets
        $publicThemeDir = $this->publicPath . '/' . $theme;
        if (File::exists($publicThemeDir)) {
            File::deleteDirectory($publicThemeDir);
        }

        $this->clearCache();

        return true;
    }

    /**
     * Publish theme assets to public directory.
     */
    protected function publishThemeAssets(string $theme): void
    {
        $themeAssetsPath = $this->themesPath . '/' . $theme . '/assets';
        $publicThemeDir = $this->publicPath . '/' . $theme;

        if (File::exists($themeAssetsPath)) {
            File::ensureDirectoryExists($publicThemeDir);
            File::copyDirectory($themeAssetsPath, $publicThemeDir);
        }
    }

    /**
     * Validate theme manifest.
     */
    protected function isValidManifest(array $manifest): bool
    {
        $requiredFields = ['name', 'version', 'author', 'description'];
        
        foreach ($requiredFields as $field) {
            if (!isset($manifest[$field]) || empty($manifest[$field])) {
                return false;
            }
        }

        return true;
    }

    /**
     * Clear theme cache.
     */
    public function clearCache(): void
    {
        Cache::forget('feather.themes.available');
        Cache::forget('feather.theme.active');
    }

    /**
     * Get theme asset URL.
     */
    public function getThemeAssetUrl(string $theme, string $asset): string
    {
        return asset("themes/{$theme}/{$asset}");
    }

    /**
     * Get active theme asset URL.
     */
    public function getActiveThemeAssetUrl(string $asset): ?string
    {
        $activeTheme = $this->getActiveTheme();
        
        if (!$activeTheme) {
            return null;
        }

        return $this->getThemeAssetUrl($activeTheme, $asset);
    }
}
