<?php

namespace Illuminate\Foundation\Console;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputOption;

class ObserverMakeCommand extends GeneratorCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'make:observer';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new observer class';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Observer';

    /**
     * Build the class with the given name.
     *
     * @param  string  $name
     * @return string
     */
    protected function buildClass($name)
    {
        $stub = parent::buildClass($name);

        $model = $this->option('model');

        return $model ? $this->replaceModel($stub, $model) : $stub;
    }

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return $this->option('model')
                    ? __DIR__.'/stubs/observer.stub'
                    : __DIR__.'/stubs/observer.plain.stub';
    }

    /**
     * Replace the model for the given stub.
     *
     * @param  string  $stub
     * @param  string  $model
     * @return string
     */
    protected function replaceModel($stub, $model)
    {
        $model = str_replace('/', '\\', $model);

<<<<<<< HEAD
        $namespaceModel = $this->laravel->getNamespace().$model;
=======
        $namespacedModel = $this->qualifyModel($model);
>>>>>>> 618d5a84e3460e9d830f42d69dd19295c6b2cbbd

        if (Str::startsWith($model, '\\')) {
            $stub = str_replace('NamespacedDummyModel', trim($model, '\\'), $stub);
        } else {
<<<<<<< HEAD
            $stub = str_replace('NamespacedDummyModel', $namespaceModel, $stub);
        }

        $stub = str_replace(
            "use {$namespaceModel};\nuse {$namespaceModel};", "use {$namespaceModel};", $stub
=======
            $stub = str_replace('NamespacedDummyModel', $namespacedModel, $stub);
        }

        $stub = str_replace(
            "use {$namespacedModel};\nuse {$namespacedModel};", "use {$namespacedModel};", $stub
>>>>>>> 618d5a84e3460e9d830f42d69dd19295c6b2cbbd
        );

        $model = class_basename(trim($model, '\\'));

        $stub = str_replace('DocDummyModel', Str::snake($model, ' '), $stub);

        $stub = str_replace('DummyModel', $model, $stub);

        return str_replace('dummyModel', Str::camel($model), $stub);
    }

    /**
     * Get the default namespace for the class.
     *
     * @param  string  $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace.'\Observers';
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['model', 'm', InputOption::VALUE_OPTIONAL, 'The model that the observer applies to.'],
        ];
    }
}
