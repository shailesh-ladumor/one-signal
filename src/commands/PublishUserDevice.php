<?php

namespace Ladumor\OneSignal\commands;

use Illuminate\Support\Facades\File;
use Illuminate\Console\Command;

class PublishUserDevice extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'one-signal.userDevice:publish';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Publish Migration|Controller|Service of User Device APIs';

    public $composer;
    /**
     * Create a new command instance.
     */
    public function __construct()
    {
        parent::__construct();

        $this->composer = app()['composer'];
    }
    public function handle()
    {
        $controllerDir = app_path('Http/Controllers/API');
        if (!File::isDirectory($controllerDir)) {
            File::makeDirectory($controllerDir);
        }

        $controllerTemplate = file_get_contents(__DIR__.'/../stubs/UserDeviceAPIController.stub');
        $this->createFile($controllerDir. DIRECTORY_SEPARATOR, 'UserDeviceAPIController.php', $controllerTemplate);
        $this->info('UserDeviceController published.');

        $modelDir = app_path('Models');

        if (!File::isDirectory($modelDir)) {
            File::makeDirectory($modelDir);
        }

        $modelTemplate = file_get_contents(__DIR__.'/../stubs/UserDevice.stub');
        $this->createFile($modelDir. DIRECTORY_SEPARATOR, 'UserDevice.php', $modelTemplate);
        $this->info('UserDevice published.');

        $repoDir = app_path('Repositories');

        if (!File::isDirectory($repoDir)) {
            File::makeDirectory($repoDir);
        }

        $repoTemplate = file_get_contents(__DIR__.'/../stubs/UserDeviceRepository.stub');
        $this->createFile($repoDir. DIRECTORY_SEPARATOR, 'UserDeviceRepository.php', $repoTemplate);
        $this->info('UserDeviceRepository published.');

        $fileName = date('Y_m_d_His').'_'.'create_user_devices_table.php';

        $repoTemplate = file_get_contents(__DIR__.'/../stubs/CreateUserDeviceTable.stub');
        $this->createFile(base_path('database/migrations/'), $fileName, $repoTemplate);
        $this->info('UserDevice migration created.');

        $this->info('Generating autoload files');
        $this->composer->dumpOptimized();

        if ($this->confirm("\nDo you want to migrate database? [y|N]", false)) {
            $this->call('migrate');
        }

        $this->info('Greeting From Shailesh Ladumor!');
    }

    public static function createFile($path, $fileName, $contents)
    {
        if (!file_exists($path)) {
            mkdir($path, 0755, true);
        }

        $path = $path.$fileName;

        file_put_contents($path, $contents);
    }
}
