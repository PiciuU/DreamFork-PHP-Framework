<?php

namespace Framework\Cache;

class Repository implements CacheInterface
{
    /**
     * The cache store implementation.
     *
     * @var \Framework\Cache\FileStore
     */
    protected FileStore $store;

    /**
     * Create a new cache repository instance.
     *
     * @param  \Framework\Cache\FileStore  $store
     */
    public function __construct(FileStore $store)
    {
        $this->store = $store;
    }

    /**
     * Retrieve an item from the cache by key.
     *
     * @param  string  $key
     * @param  mixed   $default
     * @return mixed
     */
    public function get(string $key, mixed $default = null): mixed
    {
        $value = $this->store->get($key);

        if (is_null($value)) {
            return $default instanceof \Closure ? $default() : $default;
        }

        return $value;
    }

    /**
     * Store an item in the cache.
     *
     * @param  string  $key
     * @param  mixed   $value
     * @param  int     $seconds
     * @return bool
     */
    public function put(string $key, mixed $value, int $seconds): bool
    {
        return $this->store->put($key, $value, $seconds);
    }

    /**
     * Get an item from the cache, or execute the given Closure and store the result.
     *
     * @param  string    $key
     * @param  int       $seconds
     * @param  callable  $callback
     * @return mixed
     */
    public function remember(string $key, int $seconds, callable $callback): mixed
    {
        $value = $this->get($key);

        if (!is_null($value)) {
            return $value;
        }

        $value = $callback();

        $this->put($key, $value, $seconds);

        return $value;
    }

    /**
     * Determine if an item exists in the cache.
     *
     * @param  string  $key
     * @return bool
     */
    public function has(string $key): bool
    {
        return !is_null($this->get($key));
    }

    /**
     * Remove an item from the cache.
     *
     * @param  string  $key
     * @return bool
     */
    public function forget(string $key): bool
    {
        return $this->store->forget($key);
    }
}