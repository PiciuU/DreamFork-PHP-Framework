<?php

namespace Framework\Http;

use Framework\Support\Facades\Auth;
use Framework\Support\Arr;
use Framework\Support\Str;

use Symfony\Component\HttpFoundation\Request as SymfonyRequest;

/**
 * Class Request
 *
 * The Request class extends Symfony's Request class and includes additional functionality.
 * It incorporates traits for handling content types and interacting with input data.
 *
 * @package Framework\Http
 */
class Request extends SymfonyRequest
{
    use Traits\HandleContentTypes,
        Traits\InteractsWithInput;

    /**
     * Capture the current request.
     *
     * @return static The captured request.
     */
    public static function capture()
    {
        static::enableHttpMethodParameterOverride();

        return static::createFromBase(SymfonyRequest::createFromGlobals());
    }

    /**
     * Create a new request instance from the base Symfony request.
     *
     * @param SymfonyRequest $request The Symfony request to create from.
     * @return static The new request instance.
     */
    public static function createFromBase(SymfonyRequest $request)
    {
        $newRequest = (new static)->duplicate(
            $request->query->all(), $request->request->all(), $request->attributes->all(),
            $request->cookies->all(), $request->files->all(), $request->server->all()
        );

        $newRequest->headers->replace($request->headers->all());

        $newRequest->content = $request->content;

        return $newRequest;
    }

    /**
     * Validate the request data against given rules.
     *
     * @param array $rules The validation rules.
     * @param array $messages The custom validation messages.
     * @param array $attributes The custom attributes for validation.
     * @return void
     */
    public function validate(array $rules, array $messages = [], array $attributes = [])
    {
        return validator($this->all(), $rules, $messages, $attributes)->validate();
    }

    /**
     * Determine if the current request URL and query string match a pattern.
     *
     * @param  mixed  ...$patterns
     * @return bool
     */
    public function fullUrlIs(...$patterns)
    {
        $url = $this->fullUrl();

        return collect($patterns)->contains(fn ($pattern) => Str::is($pattern, $url));
    }

    /**
     * Get the full URL for the request.
     *
     * @return string
     */
    public function fullUrl()
    {
        $query = $this->getQueryString();

        $question = $this->getBaseUrl().$this->getPathInfo() === '/' ? '/?' : '?';

        return $query ? $this->url().$question.$query : $this->url();
    }

    /**
     * Get the URL (no query string) for the request.
     *
     * @return string
     */
    public function url()
    {
        return rtrim(preg_replace('/\?.*/', '', $this->getUri()), '/');
    }


    /**
     * Determine if the current request URI matches a pattern.
     *
     * @param  mixed  ...$patterns
     * @return bool
     */
    public function is(...$patterns)
    {
        $path = $this->decodedPath();

        return collect($patterns)->contains(fn ($pattern) => Str::is($pattern, $path));
    }

    /**
     * Get the current decoded path info for the request.
     *
     * @return string
     */
    public function decodedPath()
    {
        return rawurldecode($this->path());
    }

    /**
     * Get the current path info for the request.
     *
     * @return string
     */
    public function path()
    {
        $pattern = trim($this->getPathInfo(), '/');

        return $pattern === '' ? '/' : $pattern;
    }

    /**
     * Get the authenticated user associated with the request.
     *
     * @return mixed|null The authenticated user instance.
     */
    public function user()
    {
        return Auth::user();
    }

    /**
     * Magic method to get values from the request data.
     *
     * @param string $key The key to retrieve.
     * @return mixed|null The value for the given key.
     */
    public function __get($key)
    {
        return Arr::get($this->all(), $key);
    }
}