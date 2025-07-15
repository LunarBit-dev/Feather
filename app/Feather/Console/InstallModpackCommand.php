<?php

namespace App\Feather\Console;

use Illuminate\Console\Command;
use App\Feather\Modpacks\ModpackManager;
use App\Feather\Modpacks\ModpackInstaller;
use App\Models\Server;

class InstallModpackCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'feather:install-modpack 
                            {server : The server ID}
                            {modpack : The modpack ID or name}
                            {--version= : Specific version to install}
                            {--force : Force installation even if already installed}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install a modpack on a server';

    protected ModpackManager $modpackManager;
    protected ModpackInstaller $installer;

    public function __construct(ModpackManager $modpackManager, ModpackInstaller $installer)
    {
        parent::__construct();
        $this->modpackManager = $modpackManager;
        $this->installer = $installer;
    }

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $serverId = $this->argument('server');
        $modpackId = $this->argument('modpack');
        $version = $this->option('version');
        $force = $this->option('force');

        // Validate server
        $server = Server::find($serverId);
        if (!$server) {
            $this->error("Server with ID {$serverId} not found.");
            return 1;
        }

        // Find modpack
        $modpack = $this->modpackManager->findModpack($modpackId);
        if (!$modpack) {
            $this->error("Modpack '{$modpackId}' not found.");
            return 1;
        }

        $this->info("Installing modpack '{$modpack->name}' on server '{$server->name}'...");

        try {
            // Check if already installed
            if (!$force && $this->installer->isInstalled($server, $modpack)) {
                if (!$this->confirm('Modpack is already installed. Continue anyway?')) {
                    $this->info('Installation cancelled.');
                    return 0;
                }
            }

            // Start installation
            $installation = $this->installer->install($server, $modpack, [
                'version' => $version,
                'user_id' => 1, // System user
            ]);

            $this->info("Installation started with ID: {$installation->id}");

            // Monitor installation progress
            $this->monitorInstallation($installation);

            if ($installation->fresh()->status === 'completed') {
                $this->info('✅ Modpack installed successfully!');
                return 0;
            } else {
                $this->error('❌ Installation failed.');
                if ($installation->installation_log) {
                    $this->line('Installation log:');
                    $this->line($installation->installation_log);
                }
                return 1;
            }

        } catch (\Exception $e) {
            $this->error("Installation failed: " . $e->getMessage());
            return 1;
        }
    }

    /**
     * Monitor installation progress.
     */
    protected function monitorInstallation($installation): void
    {
        $bar = $this->output->createProgressBar(100);
        $bar->start();

        while (true) {
            $installation = $installation->fresh();
            
            switch ($installation->status) {
                case 'pending':
                    $bar->setProgress(10);
                    break;
                case 'installing':
                    $bar->setProgress(50);
                    break;
                case 'completed':
                case 'failed':
                    $bar->setProgress(100);
                    $bar->finish();
                    $this->newLine();
                    return;
            }

            sleep(2);
        }
    }
}
