<?php

namespace Framework\Http\Traits;

use Framework\Support\Arr;

/**
 * Trait InteractsWithInput
 *
 * The InteractsWithInput trait provides methods for interacting with request input data.
 * It includes functionality to retrieve all input data, specific input keys, or the entire input array.
 *
 * @package Framework\Http\Traits
 */
trait InteractsWithInput
{
    /**
     * Get all input data or specific keys from the request.
     *
     * @param array|string|null $keys The keys to retrieve, or null to get all input data.
     * @return array The input data.
     */
    public function all($keys = null)
    {
        $input = array_replace_recursive($this->input(), $this->files->all());

        if (!$keys) {
            return $input;
        }

        $results = [];

        foreach (is_array($keys) ? $keys : func_get_args() as $key) {
            Arr::set($results, $key, Arr::get($input, $key));
        }

        return $results;
    }

    /**
     * Get a specific input value from the request.
     *
     * @param string|null $key The key to retrieve, or null to get all input data.
     * @param mixed $default The default value if the key is not present.
     * @return mixed The input value.
     */
    public function input($key = null, $default = null)
    {
        return data_get(
            $this->getInputSource()->all() + $this->query->all(), $key, $default
        );
    }

    /**
     * Get the input source based on the request method.
     *
     * @return \Symfony\Component\HttpFoundation\ParameterBag The input source.
     */
    protected function getInputSource()
    {
        return in_array($this->getRealMethod(), ['GET', 'HEAD']) ? $this->query : $this->request;
    }
}