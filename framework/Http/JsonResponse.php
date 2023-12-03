<?php

namespace Framework\Http;

use Symfony\Component\HttpFoundation\JsonResponse as SymfonyJsonResponse;

/**
 * Class JsonResponse
 *
 * The JsonResponse class extends Symfony's JsonResponse to customize the constructor.
 * It allows setting encoding options for JSON responses.
 *
 * @package Framework\Http
 */
class JsonResponse extends SymfonyJsonResponse
{
    /**
     * Create a new JsonResponse instance.
     *
     * @param mixed $data The JSON response data.
     * @param int $status The HTTP status code.
     * @param array $headers The response headers.
     * @param int $options The JSON encoding options.
     * @param bool $json Set to true to indicate that the data is already in JSON format.
     */
    public function __construct($data = null, $status = 200, $headers = [], $options = 0, $json = false)
    {
        $this->encodingOptions = $options;

        parent::__construct($data, $status, $headers, $json);
    }
}