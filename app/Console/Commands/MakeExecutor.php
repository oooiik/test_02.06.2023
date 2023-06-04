<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class MakeExecutor extends Command
{
    protected $signature = 'make:executor {name?} {--model=?}';

    protected $description = 'Create a new executor class';

    public function handle(): void
    {
        $name = $this->argument('name');
        $model = $this->option('model');
        if (!$name && !$model) {
            $this->error('You must specify a name or a model');
            return;
        }
        if (!$model) {
            $model = str_replace('Executor', '', $name);
        }
        if (!$name) {
            $name = $model . 'Executor';
        }
        $stub = file_get_contents(base_path('/stubs/executor.stub'));
        $stub = str_replace(['{{name}}', '{{ name }}'], $name, $stub);
        $stub = str_replace(['{{model}}', '{{ model }}'], $model, $stub);
        $path = app_path('Executor/' . $name . '.php');
        file_put_contents($path, $stub);
        $this->info('Executor created successfully.');
    }
}
