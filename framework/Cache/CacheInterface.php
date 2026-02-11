<?php

namespace Framework\Cache;

interface CacheInterface
{
    /**
     * Retrieve an item from the cache by key.
     *
     * @param  string  $key
     * @param  mixed   $default
     * @return mixed
     */
    public function get(string $key, mixed $default = null): mixed;

    /**
     * Store an item in the cache for a given number of seconds.
     *
     * @param  string  $key
     * @param  mixed   $value
     * @param  int     $seconds
     * @return bool
     */
    public function put(string $key, mixed $value, int $seconds): bool;

    /**
     * Get an item from the cache, or execute the given Closure and store the result.
     *
     * @param  string   $key
     * @param  int      $seconds
     * @param  callable $callback
     * @return mixed
     */
    public function remember(string $key, int $seconds, callable $callback): mixed;

    /**
     * Determine if an item exists in the cache.
     *
     * @param  string  $key
     * @return bool
     */
    public function has(string $key): bool;

    /**
     * Remove an item from the cache.
     *
     * @param  string  $key
     * @return bool
     */
    public function forget(string $key): bool;
}