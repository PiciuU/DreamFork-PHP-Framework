<?php

namespace Framework\Http;

/**
 * Class ServiceProvider
 *
 * This class is responsible for managing the available interfaces and loading routes for the application.
 * It filters enabled interfaces, registers them in the route collection, and loads corresponding route files.
 *
 * @package Framework\Http
 */
class ServiceProvider
{
    /**
     * An array containing information about available interfaces.
     *
     * @var array
     */
    private array $availableInterfaces;

    /**
     * An array to store loaded routes.
     *
     * @var array
     */
    private array $loadedRoutes;

    /**
     * Constructor for the ServiceProvider class.
     *
     * @param array $availableInterfaces An array containing information about available interfaces.
     */
    public function __construct(Array $availableInterfaces) {
        $this->availableInterfaces = $availableInterfaces;

        // Filter enabled interfaces
        $enabledInterfaces = array_filter($availableInterfaces, function ($interfaceData) {
            return $interfaceData['enabled'] === true;
        });

        // Register enabled interfaces in the route collection and load route files
        Route::collection(array_keys($enabledInterfaces));

        foreach ($enabledInterfaces as $interfaceName => $interfaceData) {
            Route::interface($interfaceName, $interfaceData['prefix']);
            load(base_path("routes/$interfaceName.php"));
        }

        // Store the loaded routes
        $this->loadedRoutes = Route::getRoutes();
    }

    /**
     * Get the loaded routes.
     *
     * @return array The loaded routes.
     */
    public function getRoutes(): array {
        return $this->loadedRoutes;
    }

    /**
     * Get the requested interface based on the incoming request.
     *
     * @param mixed $request The incoming request object.
     * @return array An array containing the matching interface name and prefix.
     */
    public function getRequestedInterface($request): array {
        $pathInfo = $request->getPathInfo();

        $matchingInterface = null;

        foreach ($this->availableInterfaces as $interfaceName => $interfaceData) {
            if ($interfaceData['enabled'] && strpos($pathInfo, $interfaceData['prefix'].'/') === 0) {
                $matchingInterface['name'] = $interfaceName;
                $matchingInterface['prefix'] = $interfaceData['prefix'];
                break;
            }
        }

        return $matchingInterface;
    }
}