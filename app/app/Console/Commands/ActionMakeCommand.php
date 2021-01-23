<?php

namespace App\Console\Commands;

use Illuminate\Console\GeneratorCommand as Command;
use Symfony\Component\Console\Input\InputOption;

class ActionMakeCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'make:action';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create new Action Controller';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Action';

    /**
     * Execute the console command.
     *
     * @return mixed
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function handle(): bool
    {
        $name_input = 'Http/Controllers/'.$this->getNameInput().'Action';
        $name = $this->qualifyClass($name_input);
        $usecase_name_input = 'Http/Usecases/'.$this->getNameInput().'Usecase';
        $usecase_name = $this->qualifyClass($usecase_name_input);
        $responder_name_input = 'Http/Responders/'.$this->getNameInput().'Responder';
        $responder_name = $this->qualifyClass($responder_name_input);


        $path = $this->getPath($name);

        if ($this->checkFileExists($name_input)) {
            return false;
        }

//        dd($name, $path, $this->option('usecase'));

        // Next, we will generate the path to the location where this class' file should get
        // written. Then, we will build the class and make the proper replacements on the
        // stub files so that it gets the correctly formatted namespace and class name.
        $this->makeDirectory($path);

        $this->files->put($path, $this->sortImports($this->buildActionClass($name, $usecase_name, $responder_name)));

        $this->info($this->type.' created successfully.');

        return true;
    }

    /**
     * Check the file already exists.
     *
     * @param string $name_input
     * @return bool
     */
    private function checkFileExists(string $name_input): bool
    {
        // First we will check to see if the class already exists. If it does, we don't want
        // to create the class and overwrite the user's code. So, we will bail out so the
        // code is untouched. Otherwise, we will continue generating this class' files.
        if ((! $this->hasOption('force') ||
                ! $this->option('force')) &&
            $this->alreadyExists($name_input)) {
            $this->error($this->type.' already exists!');

            return true;
        }

        return false;
    }

    /**
     * Build the class with the given name.
     *
     * @param string $name
     * @param string $usecase_name
     * @param string $responder_name
     * @return string
     *
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    protected function buildActionClass(string $name, string $usecase_name, string $responder_name): string
    {
        $stub = $this->files->get($this->getStub());

        return $this->replaceActionNamespace($stub, $name, $usecase_name, $responder_name)->replaceActionClass($stub, $name, $usecase_name, $responder_name);
    }

    protected function getStub()
    {
        if ($this->option('usecase')) {
            return __DIR__.'/stubs/action_with_usecase.stub.stub';
        }

        return __DIR__.'/stubs/action.stub';
    }

    /**
     * Replace the namespace for the given stub.
     *
     * @param string $stub
     * @param string $name
     * @param string $usecase_name
     * @param string $responder_name
     * @return $this
     */
    protected function replaceActionNamespace(&$stub, string $name, string $usecase_name, string $responder_name)
    {
        $stub = str_replace(
            ['DummyNamespace', 'DummyUsecaseNamespace', 'DummyResponderNamespace', 'DummyRootNamespace', 'NamespacedDummyUserModel'],
            [$this->getNamespace($name), $this->getNamespace($usecase_name), $this->getNamespace($responder_name), $this->rootNamespace(), $this->userProviderModel()],
            $stub
        );

        return $this;
    }

    /**
     * Replace the class name for the given stub.
     *
     * @param string $stub
     * @param string $name
     * @param string $usecase_name
     * @param string $responder_name
     * @return string
     */
    protected function replaceActionClass($stub, string $name, string $usecase_name, string $responder_name): string
    {
        $class = str_replace($this->getNamespace($name).'\\', '', $name);
        $usecase_class = str_replace($this->getNamespace($usecase_name).'\\', '', $usecase_name);
        $responder_class = str_replace($this->getNamespace($responder_name).'\\', '', $responder_name);

        return str_replace(
            ['DummyClass', 'DummyUsecaseClass', 'DummyResponderClass'],
            [$class, $usecase_class, $responder_class],
            $stub);
    }

    /**
     * InputOptionのコンストラクタへ渡す引数の配列のリストを返します
     *
     * @return array
     */
    protected function getOptions(): array
    {
        return [
            // InputOptionのコンストラクタへ渡す引数の配列を追加していく
            // 左から
            // @param string                        $name        オプション名
            // @param string|array|null             $shortcut    オプションショートカット
            // @param int|null                      $mode        オプションのモード(self::VALUE_NONEとself::VALUE_REQUIREDとself::VALUE_OPTIONALはどれか一つ)
            // @param string                        $description オプションの説明
            // @param string|string[]|int|bool|null $default     オプションの初期値(オプションのモードにself::VALUE_NONE以外を指定している場合のみ)
            ['usecase', 'u', InputOption::VALUE_NONE, 'make usecase class']
        ];
    }
}
