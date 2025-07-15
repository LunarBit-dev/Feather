<?php

namespace App\Feather\Console;

use Illuminate\Console\Command;
use App\Feather\Modpacks\ModpackManager;

class SyncMarketplaceCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'feather:sync-marketplace 
                            {--source=all : Source to sync (curseforge, modrinth, all)}
                            {--game=minecraft : Game to sync}
                            {--force : Force full resync}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync modpacks from external marketplaces';

    protected ModpackManager $modpackManager;

    public function __construct(ModpackManager $modpackManager)
    {
        parent::__construct();
        $this->modpackManager = $modpackManager;
    }

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $source = $this->option('source');
        $game = $this->option('game');
        $force = $this->option('force');

        $this->info("Syncing modpacks from {$source} for {$game}...");

        try {
            $sources = $source === 'all' ? ['curseforge', 'modrinth'] : [$source];
            $totalSynced = 0;

            foreach ($sources as $currentSource) {
                $this->line("Syncing from {$currentSource}...");
                
                $synced = $this->modpackManager->syncFromSource($currentSource, $game, $force);
                $totalSynced += $synced;
                
                $this->info("✅ Synced {$synced} modpacks from {$currentSource}");
            }

            $this->info("🎉 Total synced: {$totalSynced} modpacks");
            return 0;

        } catch (\Exception $e) {
            $this->error("Sync failed: " . $e->getMessage());
            return 1;
        }
    }
}
