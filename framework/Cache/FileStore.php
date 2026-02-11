<?php

namespace Framework\Cache;

use Framework\Filesystem\Filesystem;

class FileStore
{
	/**
	 * The filesystem instance.
	 *
	 * @var \Framework\Filesystem\Filesystem
	 */
	protected Filesystem $files;

	/**
	 * The file cache directory.
	 *
	 * @var string
	 */
	protected string $directory;

	/**
	 * Create a new file store instance.
	 *
	 * @param  \Framework\Filesystem\Filesystem  $files
	 * @param  string  $directory
	 */
	public function __construct(Filesystem $files, string $directory)
	{
		$this->files = $files;
		$this->directory = $directory;
	}

	/**
	 * Retrieve an item from the cache by key.
	 *
	 * @param  string  $key
	 * @return mixed
	 */
	public function get(string $key): mixed
	{
		$path = $this->path($key);

        if (!$this->files->exists($path)) {
            return null;
        }

        try {
            $contents = $this->files->get($path);

            $expire = substr($contents, 0, 10);

            if (time() >= $expire) {
                $this->forget($key);
                return null;
            }

            return unserialize(substr($contents, 10));
        } catch (\Exception $e) {
            return null;
        }
	}

	/**
	 * Store an item in the cache for a given number of seconds.
	 *
	 * @param  string  $key
	 * @param  mixed   $value
	 * @param  int     $seconds
	 * @return bool
	 */
	public function put(string $key, mixed $value, int $seconds): bool
	{
		$this->ensureCacheDirectoryExists();

        $path = $this->path($key);
        $expire = time() + $seconds;

        $content = $expire . serialize($value);

        $this->files->put($path, $content);

        return true;
	}

	/**
     * Get an item from the cache, or execute the given Closure and store the result.
     */
    public function remember($key, $seconds, \Closure $callback)
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
	 * Remove an item from the cache.
	 *
	 * @param  string  $key
	 * @return bool
	 */
	public function forget(string $key): bool
	{
		if ($this->files->exists($path = $this->path($key))) {
            return $this->files->delete($path);
        }
        return false;
	}

	/**
	 * Get the full path for the given cache key.
	 *
	 * @param  string  $key
	 * @return string
	 */
	protected function path(string $key): string
	{
		return $this->directory.'/'.sha1($key);
	}

	/**
	 * Ensure the cache directory exists.
	 *
	 * @return void
	 */
	protected function ensureCacheDirectoryExists(): void
	{
		if (!$this->files->exists($this->directory)) {
            $this->files->makeDirectory($this->directory, 0755, true);
        }
	}
}