<?php

declare (strict_types = 1);

namespace Larke\Admin\Command;

use Illuminate\Support\Str;
use Illuminate\Console\Command;

use Larke\Admin\Stubs\Stubs;

/**
 * app-admin 相关
 *
 * > php artisan larke-admin:app-admin create_app_admin [--force]
 * > php artisan larke-admin:app-admin create_controller --name=NewsContent [--force]
 * > php artisan larke-admin:app-admin create_model --name=NewsContent [--force]
 * > php artisan larke-admin:app-admin create_extension --extension=deatil/news-books [--force]
 *
 * @create 2022-12-8
 * @author deatil
 */
class AppAdmin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'larke-admin:app-admin
        {type : Run type name.}
        {--name=none : File name.}
        {--extension=none : Extension info.}
        {--force : Force action.}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'larke-admin app-admin action.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $type = $this->argument('type');
        if (empty($type)) {
            $this->line("<error>Enter type'name is empty !</error> ");
            return;
        }
        
        switch ($type) {
            case 'create_controller':
                $this->makeController();
                
                break;
            case 'create_model':
                $this->makeModel();
                
                break;
            case 'create_extension':
                $this->makeExtension();
                
                break;
            case 'create_app_admin':
                $this->makeAppAdmin();
                
                break;
            default:
                $this->line("<error>Enter type'name is error !</error> ");
                return;
        }
    }

    /**
     * 生成控制器
     */
    public function makeController()
    {
        $name = $this->option('name');
        if (empty($name) || $name == "none") {
            $this->line("<error>Enter file'name is empty !</error> ");
            return;
        }

        $force = $this->option('force');
        
        $data = [
            'controllerName' => $name,
            'controllerPath' => Str::kebab($name),
        ];
        
        $status = Stubs::create()->makeController($name, $data, $force);
        if ($status !== true) {
            $this->line("<error>Make controller fail ! {$status} </error> ");

            return;
        }
        
        $this->info('Make controller successfully!');
    }

    /**
     * 生成模型
     */
    public function makeModel()
    {
        $name = $this->option('name');
        if (empty($name) || $name == "none") {
            $this->line("<error>Enter file'name is empty !</error> ");
            return;
        }
        
        $force = $this->option('force');
        
        $data = [
            'modelName' => $name,
            'modelNameTable' => Str::snake($name),
        ];
        
        $status = Stubs::create()->makeModel($name, $data, $force );
        if ($status !== true) {
            $this->line("<error>Make model fail ! {$status} </error> ");

            return;
        }
        
        $this->info('Make model successfully!');
    }

    /**
     * 生成 app-admin 目录
     */
    public function makeAppAdmin()
    {
        $force = $this->option('force');

        $status = Stubs::create()->makeAppAdmin($force);
        if ($status !== true) {
            $this->line("<error>Make appAdmin dir fail ! {$status} </error> ");

            return;
        }
        
        $this->info('Make appAdmin dir successfully! You can see doc [/app/Admin/README.md]! ');
    }

    /**
     * 生成扩展
     */
    public function makeExtension()
    {
        $extension = $this->option('extension');
        if (empty($extension) || $extension == "none") {
            $this->line("<error>Enter extension is empty !</error> ");
            return;
        }
        
        $extensions = explode("/", $extension);
        if (count($extensions) != 2) {
            $this->line("<error>Enter extension is error !</error> ");
            return;
        }
        
        // 获取信息
        [$author, $name] = $extensions;
        
        $force = $this->option('force');

        $status = Stubs::create()->makeExtension($author, $name, $force);
        if ($status !== true) {
            $this->line("<error>Make extension [{$extension}] fail ! {$status} </error> ");

            return;
        }
        
        $this->info("Make extension [{$extension}] successfully!");
    }
    
    
}
