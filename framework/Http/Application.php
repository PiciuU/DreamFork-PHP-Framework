<?php

namespace Framework\Http;

use Framework\Bootstrap\LoadConfiguration;

/**
 * Class Application
 *
 * The main class of the framework application, responsible for managing access to the kernel and router objects.
 * This is the central part of the framework that initializes its operation.
 *
 * @package Framework\Http
 */
class Application extends Container
{
    use LoadConfiguration;

    /**
     * Base path of the application.
     *
     * @var string
     */
    protected $basePath;

    /**
     * The custom bootstrap path defined by the developer.
     *
     * @var string
     */
    protected $bootstrapPath;

    /**
     * The custom application path defined by the developer.
     *
     * @var string
     */
    protected $appPath;

    /**
     * The custom configuration path defined by the developer.
     *
     * @var string
     */
    protected $configPath;

    /**
     * The custom public / web path defined by the developer.
     *
     * @var string
     */
    protected $publicPath;


    /**
     * Application constructor.
     *
     * @param string $basePath The base path of the application.
     */
    public function __construct($basePath = null)
    {
        if ($basePath) {
            $this->setBasePath($basePath);
        }

        $this->registerBaseBindings();

        $this->bootstrap();

        $this->registerExceptionHandler();
    }

    /**
     * Register the basic bindings into the container.
     *
     * @return void
     */
    protected function registerBaseBindings()
    {
        static::setInstance($this);

        $this->instance('app', $this);

        $this->instance(Container::class, $this);
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
     * Join the given paths together.
     *
     * @param  string  $basePath
     * @param  string  $path
     * @return string
     */
    public function joinPaths($basePath, $path = '')
    {
        return $basePath.($path != '' ? DIRECTORY_SEPARATOR.ltrim($path, DIRECTORY_SEPARATOR) : '');
    }

    /**
     * Get the base path of the application.
     *
     * @param  string  $path
     * @return string
     */
    public function basePath($path = '')
    {
        return $this->joinPaths($this->basePath, $path);
    }

    /**
     * Set the base path for the application.
     *
     * @param  string  $basePath
     */
    public function setBasePath($basePath)
    {
        $this->basePath = rtrim($basePath, '\/');

        $this->bindPathsInContainer();
    }

    /**
     * Get the path to the application "app" directory.
     *
     * @param  string  $path
     * @return string
     */
    public function path($path = '')
    {
       return $this->joinPaths($this->appPath ?: $this->basePath('app'), $path);
    }

    /**
     * Get the path to the application configuration files.
     *
     * @param  string  $path
     * @return string
     */
    public function configPath($path = '')
    {
        return $this->joinPaths($this->configPath ?: $this->basePath('config'), $path);
    }

    /**
     * Get the path to the public / web directory.
     *
     * @param  string  $path
     * @return string
     */
    public function publicPath($path = '')
    {
        return $this->joinPaths($this->publicPath ?: $this->basePath('public'), $path);
    }

    /**
     * Bind all of the application paths in the container.
     *
     * @return void
     */
    protected function bindPathsInContainer()
    {
        $this->instance('path', $this->path());
        $this->instance('path.base', $this->basePath());
        $this->instance('path.config', $this->configPath());
        $this->instance('path.public', $this->publicPath());
    }

    /**
     * Register the exception handler into the container.
     *
     * @return void
     */
    protected function registerExceptionHandler()
    {
        $this->instance('handler', new \App\Exceptions\Handler());
    }

}