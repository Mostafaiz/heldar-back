<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class MakeDtoCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:dto {name} {--response} {--service=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new DTO class';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $name = Str::studly($this->argument('name'));
        $isResponse = $this->option('response');
        $service = Str::studly($this->option('service'));

        $stubPath = base_path('stubs/dto.' . ($isResponse ? 'response' : 'request') . '.stub');
        if (!File::exists($stubPath)) {
            $this->fail("Stub file not found: {$stubPath}");
        }
        if (!$isResponse && !$service) {
            $this->error('Service is required for Dto request.');
            $this->line('Please use --service option to specify the service.');
            return;
        }
        if ($isResponse)
            $directory = app_path('Http/Dto/Response');
        else
            $directory = app_path('Http/Dto/Request/' . $service);

        if (!File::exists($directory)) {
            File::makeDirectory($directory, 0755, true);
        }

        $path = $directory . '/' . $name . '.php';

        if (File::exists($path)) {
            $this->fail("{$name} DTO already exists!");
            return;
        }



        $stub = File::get($stubPath);

        if ($isResponse) {
            $content = str_replace(
                ['{{ name }}'],
                [$name],
                $stub
            );
        } else {
            $content = str_replace(
                ['{{ name }}','{{ namespace }}'],
                [$name, $service],
                $stub
            );
        }
        File::put($path, $content);
        $this->newLine();
        $this->info("DTO {$name} created successfully.");
        $this->line('path : { ' . Str::after($path, base_path() . DIRECTORY_SEPARATOR) . ' }');
    }
}
