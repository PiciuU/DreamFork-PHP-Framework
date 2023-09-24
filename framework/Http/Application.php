<?php

namespace Framework\Http;

use Dotenv\Dotenv;

/**
 * Class Application
 *
 * The main class of the framework application, responsible for managing access to the kernel and router objects.
 * This is the central part of the framework that initializes its operation.
 *
 * @package Framework\Http
 */
class Application
{
    /**
     * Application instance (Singleton).
     *
     * @var Application|null
     */
    private static $app;

    /**
     * Instance of the application's kernel.
     *
     * @var Kernel|null
     */
    private $kernel;

    /**
     * Instance of the application's router.
     *
     * @var Router|null
     */
    private $router;

    /**
     * Base path of the application.
     *
     * @var string
     */
    private $basePath;

    /**
     * Application constructor.
     *
     * @param string $basePath The base path of the application.
     */
    public function __construct($basePath)
    {
        if (!self::$app) {
            self::$app = $this;
        }

        $this->basePath = $basePath;

        $this->loadEnvironmentVariables();
    }

    /**
     * Get the instance of the application (Singleton).
     *
     * @return Application The application instance.
     * @throws \RuntimeException If the application instance does not exist.
     */
    public static function getInstance()
    {
        if (!self::$app) {
            throw new \RuntimeException("Application instance does not exist.");
        }

        return self::$app;
    }

    /**
     * Get the kernel instance.
     *
     * @return Kernel The kernel instance.
     * @throws \RuntimeException If the kernel instance does not exist yet.
     */
    public function getKernel()
    {
        if (!$this->kernel) {
            throw new \RuntimeException("Kernel instance does not exist yet.");
        }

        return $this->kernel;
    }

    /**
     * Get the router instance.
     *
     * @return Router The router instance.
     * @throws \RuntimeException If the router instance does not exist yet.
     */
    public function getRouter()
    {
        if (!$this->router) {
            throw new \RuntimeException("Router instance does not exist yet.");
        }

        return $this->router;
    }

    /**
     * Set the kernel instance.
     *
     * @param Kernel $kernel The kernel instance to set.
     * @throws \RuntimeException If the kernel instance is already set.
     */
    public function setKernel(Kernel $kernel)
    {
        if ($this->kernel) {
            throw new \RuntimeException("Kernel instance is already set.");
        }

        $this->kernel = $kernel;
    }

    /**
     * Set the router instance.
     *
     * @param Router $router The router instance to set.
     * @throws \RuntimeException If the router instance is already set.
     */
    public function setRouter(Router $router)
    {
        if ($this->router) {
            throw new \RuntimeException("Router instance is already set.");
        }

        $this->router = $router;
    }

    /**
     * Get the base path of the application.
     *
     * @return string The base path of the application.
     */
    public function getBasePath($path = '') {
        return $this->basePath.($path != '' ? DIRECTORY_SEPARATOR.ltrim($path, DIRECTORY_SEPARATOR) : '');
    }

    /**
     * Create an object based on the given class name.
     *
     * @param string $className The name of the class to create.
     * @return object The created class object.
     * @throws \InvalidArgumentException If the class does not exist.
     */
    public function make(string $className) {
        if (!class_exists($className)) {
            throw new \InvalidArgumentException("Class $className does not exist.");
        }

        $classObject = new $className();

        if ($className === 'Framework\Http\Kernel') {
            $this->setKernel($classObject);
        }

        return $classObject;
    }

    /**
     * Loads environment variables from the application's .env file using the Dotenv library.
     * This function is responsible for initializing and populating the application's environment
     * with the configuration values specified in the .env file.
     *
     * @throws \RuntimeException If the .env file is not found or if there is an issue loading its contents.
     */
    private function loadEnvironmentVariables() {
        if (!file_exists($this->basePath . '/.env')) {
            throw new \RuntimeException('.env file not found.');
        }

        $dotenv = Dotenv::createImmutable($this->basePath);
        $dotenv->load();
    }

}