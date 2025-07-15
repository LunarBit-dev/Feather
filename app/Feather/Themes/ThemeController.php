<?php

namespace App\Feather\Themes;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use App\Http\Controllers\Controller;

class ThemeController extends Controller
{
    protected ThemeManager $themeManager;

    public function __construct(ThemeManager $themeManager)
    {
        $this->themeManager = $themeManager;
    }

    /**
     * Display theme management page.
     */
    public function index(): View
    {
        $themes = $this->themeManager->getAvailableThemes();
        $activeTheme = $this->themeManager->getActiveTheme();

        return view('feather.themes.index', compact('themes', 'activeTheme'));
    }

    /**
     * Upload and install a new theme.
     */
    public function upload(Request $request): JsonResponse
    {
        $request->validate([
            'theme_file' => 'required|file|mimes:zip|max:10240', // 10MB max
        ]);

        $file = $request->file('theme_file');
        $tempPath = $file->storeAs('temp', uniqid() . '.zip');
        
        try {
            $result = $this->themeManager->installTheme(storage_path('app/' . $tempPath));
            
            // Clean up temp file
            unlink(storage_path('app/' . $tempPath));
            
            if ($result['success']) {
                return response()->json([
                    'success' => true,
                    'message' => $result['message'],
                    'theme' => $result['theme'],
                ]);
            }
            
            return response()->json([
                'success' => false,
                'message' => $result['message'],
            ], 400);
            
        } catch (\Exception $e) {
            // Clean up temp file on error
            if (file_exists(storage_path('app/' . $tempPath))) {
                unlink(storage_path('app/' . $tempPath));
            }
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to install theme: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Activate a theme.
     */
    public function activate(Request $request, string $theme): JsonResponse
    {
        $success = $this->themeManager->setActiveTheme($theme);
        
        if ($success) {
            return response()->json([
                'success' => true,
                'message' => 'Theme activated successfully',
            ]);
        }
        
        return response()->json([
            'success' => false,
            'message' => 'Failed to activate theme',
        ], 400);
    }

    /**
     * Delete a theme.
     */
    public function delete(string $theme): JsonResponse
    {
        $success = $this->themeManager->uninstallTheme($theme);
        
        if ($success) {
            return response()->json([
                'success' => true,
                'message' => 'Theme deleted successfully',
            ]);
        }
        
        return response()->json([
            'success' => false,
            'message' => 'Failed to delete theme. Make sure it\'s not the active theme.',
        ], 400);
    }
}
