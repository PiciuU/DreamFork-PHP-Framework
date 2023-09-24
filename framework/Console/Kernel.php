<?php

namespace Framework\Console;

class Kernel {
    private $arguments;

    /**
     * Create a new Kernel instance.
     *
     * @param array $arguments The command line arguments.
     */
    public function __construct($arguments) {
        $this->arguments = $arguments;

        set_exception_handler([$this, 'handleException']);
    }

    /**
     * Run the command specified by the developer.
     */
    public function run() {
        $commandName = $this->getCommandName();
        $commandNameFunction = $this->getCommandNameFunction();

        $commandPath = __DIR__ . '/Commands/' . $commandName . '.php';

        if (file_exists($commandPath)) {
            require_once $commandPath;

            $commandClassName = '\\Framework\\Console\\Commands\\' . $commandName;

            if (class_exists($commandClassName)) {
                $commandInstance = new $commandClassName();

                if ($commandNameFunction) {
                    if (method_exists($commandInstance, $commandNameFunction)) {
                        call_user_func([$commandInstance, $commandNameFunction]);
                    }
                    else {
                        throw new \Exception("Function '$commandNameFunction' for command class '$commandName' does not exist.");
                    }
                }
                else {
                    $commandInstance->execute();
                }
            } else {
                throw new \Exception("Command class '$commandName' does not exist.");
            }
        } else {
            throw new \Exception("Command file '$commandName' does not exist.");
        }
    }

    /**
     * Get the name of the command specified in the arguments.
     *
     * @return string|null The name of the command or null if not found.
     */
    public function getCommandName() {
        $parts = explode(':', $this->arguments[0] ?? '');

        return $parts[0] ?? null;
    }

    /**
     * Get the name of the function for the command specified in the arguments.
     *
     * @return string|null The name of the command or null if not found.
     */
    public function getCommandNameFunction() {
        $parts = explode(':', $this->arguments[0] ?? '');

        return $parts[1] ?? null;
    }

    /**
     * Handle exceptions by displaying an error message and exiting.
     *
     * @param \Exception $e The exception to handle.
     */
    public function handleException(\Exception $e) {
        echo "Error: " . $e->getMessage() . "\n";
        exit(1);
    }
}