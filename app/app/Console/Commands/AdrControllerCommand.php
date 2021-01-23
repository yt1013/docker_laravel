<?php

namespace App\Console\Commands;

use Illuminate\Console\GeneratorCommand as Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class AdrControllerCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'make:controller-adr';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create new controller with adr pattern';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'adr-controller';

    /**
     * Execute the console command.
     *
     * @return mixed
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function handle(): bool
    {
        $action_name_input = 'Http/Controllers/'.$this->getNameInput().'Action';
        $responder_name_input = 'Http/Responders/'.$this->getNameInput().'Responder';

        if ($this->checkFileExists($action_name_input, 'action') || $this->checkFileExists($responder_name_input, 'responder')) {
            return false;
        }

        if ($this->option('usecase')) {
            $usecase_name_input = 'Usecases/'.$this->getNameInput().'Usecase';

            if ($this->checkFileExists($usecase_name_input, 'usecase')) {
                return false;
            }

            $this->putFileClass($usecase_name_input, 'usecase');
        }

        $this->putFileClass($action_name_input, 'action');
        $this->putFileClass($responder_name_input, 'responder');

        return true;
    }

    /**
     * Check the file already exists.
     *
     * @param string $name_input
     * @param string $method
     * @return bool
     */
    private function checkFileExists(string $name_input, string $method): bool
    {
        // First we will check to see if the class already exists. If it does, we don't want
        // to create the class and overwrite the user's code. So, we will bail out so the
        // code is untouched. Otherwise, we will continue generating this class' files.
        if ((! $this->hasOption('force') ||
                ! $this->option('force')) &&
            $this->alreadyExists($name_input)) {
            $this->error($method.' already exists!');

            return true;
        }

        return false;
    }


    /**
     * Put built file
     *
     * @param string $name_input
     * @param string $method
     * @return void
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    private function putFIleClass(string $name_input, string $method): void
    {
        $name = $this->qualifyClass($name_input);
        $path = $this->getPath($name);

        // Next, we will generate the path to the location where this class' file should get
        // written. Then, we will build the class and make the proper replacements on the
        // stub files so that it gets the correctly formatted namespace and class name.
        $this->makeDirectory($path);

        $this->files->put($path, $this->sortImports($this->buildControllerClass($name, $method)));

        $this->info($method.' created successfully.');
    }

    /**
     * Build the class with the given name.
     *
     * @param string $name
     * @param string $method
     * @return string
     *
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    private function buildControllerClass(string $name, string $method): string
    {
        $stub = $this->files->get($this->getControllerStub($method));

        return $this->replaceNamespace($stub, $name)->replaceClass($stub, $name);
    }

    /**
     * Build the class with the given name.
     *
     * @param string $method
     * @return string
     *
     */
    private function getControllerStub(string $method): string
    {
        if ($method === 'action' && $this->option('usecase')) {
            return __DIR__."/stubs/${method}_with_usecase.stub";
        }

        return __DIR__."/stubs/${method}.stub";
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
            ['usecase', 'u', InputOption::VALUE_NONE, 'make usecase class'],
        ];
    }

    protected function getStub()
    {
        // 必須メソッドのため残す
    }
}
