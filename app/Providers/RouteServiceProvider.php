<?php

namespace App\Providers;

use Framework\Http\Route as Route;
use Framework\Http\Application;
use Framework\Http\RouteServiceProvider as ServiceProvider;

/**
 * Class RouteServiceProvider
 *
 * This class extends the base RouteServiceProvider and is responsible for configuring and loading routes based on
 * available interfaces. It defines which interfaces are enabled, their prefixes, and loads corresponding routes.
 *
 * @package App\Providers
 */
class RouteServiceProvider extends ServiceProvider
{
    /**
     * An array containing information about available interfaces.
     *
     * @var array
     */
    private $availableInterfaces = [
        'api' => [
            'enabled' => true,
            'prefix' => '/api',
            'request-headers' => [
                'Accept' => 'application/json',
            ],
            'response-headers' => [
                'Content-Type' => 'application/json'
            ]
        ],
        'web' => [
            'enabled' => true,
            'prefix' => '',
            'request-headers' => [],
            'response-headers' => []
        ]
    ];

    /**
     * Constructor for the RouteServiceProvider class.
     *
     * This constructor initializes the `RouteServiceProvider` and inherits from the parent class, passing the
     * configuration of available interfaces as its argument. It leverages the parent class constructor to create a
     * collection of routes for all enabled interfaces, effectively setting up the routing system for the application.
     *
     */
    public function __construct() {
        // Call the parent class constructor, passing the available interfaces configuration.
        parent::__construct($this->availableInterfaces);
    }
}
