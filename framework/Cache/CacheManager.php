<?php

namespace Framework\Cache;

use Framework\Filesystem\Filesystem;
use InvalidArgumentException;

class CacheManager
{
    /**
     * The array of resolved cache stores.
     */
    protected $stores = [];

    /**
     * Get a cache store instance by name.
     */
    public function store($name = null)
    {
        $name = $name ?: $this->getDefaultDriver();

        return $this->stores[$name] ?? $this->stores[$name] = $this->resolve($name);
    }

    /**
     * Resolve the given store.
     */
    protected function resolve($name)
    {
        $config = $this->getConfig($name);

        if (is_null($config)) {
            throw new InvalidArgumentException("Cache store [{$name}] is not defined.");
        }

        $driverMethod = 'create'.ucfirst($config['driver']).'Driver';

        if (method_exists($this, $driverMethod)) {
            return $this->{$driverMethod}($config);
        }

        throw new InvalidArgumentException("Cache driver [{$config['driver']}] is not supported.");
    }

    /**
     * Create an instance of the file cache driver.
     */
    protected function createFileDriver(array $config)
    {
        return new FileStore(new Filesystem, $config['path']);
    }

    protected function getConfig($name)
    {
        return config("cache.stores.{$name}");
    }

    public function getDefaultDriver()
    {
        return config('cache.default');
    }

    /**
     * Dynamically call the default driver instance.
     */
    public function __call($method, $parameters)
    {
        return $this->store()->$method(...$parameters);
    }
}