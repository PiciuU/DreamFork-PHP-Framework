<?php

namespace Framework\Http;

use App\Providers\RouteServiceProvider;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\Exception\MethodNotAllowedException;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\Exception\NoConfigurationException;

/**
 * Class Router
 *
 * The Router class handles the routing logic for incoming HTTP requests. It interacts with the RouteServiceProvider to
 * determine the active interface, fetches the appropriate routes, and dispatches the request to the corresponding
 * controller or closure.
 *
 * @package Framework\Http
 */
class Router
{
    /**
     * The provider responsible for managing routes and interfaces.
     *
     * @var RouteServiceProvider
     */
    private $provider;

    /**
     * An array containing the routes for various interfaces.
     *
     * @var array
     */
    private $routes;

    /**
     * Information about the requested interface.
     *
     * @var array
     */
    private $requestedInterface;

    /**
     * Constructor initializing the route provider and route collection.
     */
    public function __construct()
    {
        $this->provider = new RouteServiceProvider();
        $this->routes = $this->provider->getRoutes();
    }

    /**
     * Handles incoming requests and invokes the appropriate route.
     *
     * @param Request $request HTTP request.
     * @return Response HTTP response.
     */
    public function dispatch(Request $request): Response
    {
        $this->requestedInterface = $this->provider->getRequestedInterface($request);
        $response = $this->runRoute($request, $this->routes[$this->requestedInterface['name']]);
        return $this->prepareResponse($response);
    }

    /**
     * Invokes the route and handles exceptions, generating the appropriate HTTP responses.
     *
     * @param Request $request HTTP request.
     * @param RouteCollection $routes Collection of routes for the interface.
     * @return mixed Generated response based on content of requested route.
     */
    private function runRoute(Request $request, RouteCollection $routes): mixed
    {
        $context = new RequestContext();
        $context->fromRequest($request);

        $matcher = new UrlMatcher($routes, $context);

        try {
            $matcher = $matcher->match($request->getPathInfo());

            array_walk($matcher, function(&$param) {
                if(is_numeric($param)) {
                    $param = (int) $param;
                }
            });

            if (isset($matcher['_controller']) && $matcher['_controller'] instanceof \Closure) {
                $availableParams = array_merge(array_slice($matcher, 1, -1), array('request' => $request, 'routes' => $routes));

                $reflection = new \ReflectionFunction($matcher['_controller']);

                $params = $this->prepareParameterList($reflection, $availableParams);

                $response = call_user_func_array($matcher['_controller'], $params);
            }
            elseif (isset($matcher['controller']) && isset($matcher['method'])) {
                $availableParams = array_merge(array_slice($matcher, 2, -1), array('request' => $request, 'routes' => $routes));

                $className = '\\App\\Controllers\\'.$matcher['controller'];
                $classInstance = new $className();

                if (!method_exists($classInstance, $matcher['method'])) {
                    throw new \Exception('Method does not exist');
                }

                $reflection = new \ReflectionMethod($classInstance, $matcher['method']);

                $params = $this->prepareParameterList($reflection, $availableParams);

                $response = call_user_func_array([$classInstance, $matcher['method']], $params);
            }
            else {
                throw new \Exception('Invalid route handler');
            }

            return $response;

        } catch (MethodNotAllowedException $e) {
            return new Response('Route method is not allowed.', Response::HTTP_METHOD_NOT_ALLOWED);
        } catch (ResourceNotFoundException $e) {
            return new Response('Route does not exist.', Response::HTTP_NOT_FOUND);
        } catch (NoConfigurationException $e) {
            return new Response('Configuration does not exists.', Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (\Exception $e) {
            throw $e;
            // return new Response('Internal Server Error', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Prepares the HTTP response by adding headers, especially setting the content type to "application/json" for the "api" interface.
     *
     * @param mixed $response Route response.
     * @return Response HTTP response.
     */
    private function prepareResponse($response): Response
    {
        if (!$response instanceof Response) {
            $response = new Response($response);
        }

        if ($this->requestedInterface['name'] == 'api') {
            $response->headers->set('Content-Type', 'application/json');
        }

        return $response;
    }

    /**
     * Prepare the parameter list for a route handler method or closure based on its reflection and available parameters.
     *
     * This method analyzes the reflection of the route handler (either a method or closure) to determine its required parameters.
     * It then selects the matching parameters from the available parameters list. If no matching parameters are found,
     * it defaults to passing the 'request' parameter. This ensures that the route handler receives only the parameters it needs.
     *
     * @param \ReflectionMethod|\ReflectionFunction $reflection Reflection of the route handler.
     * @param array $availableParams Available parameters for the route handler.
     * @return array List of parameters to be passed to the route handler.
     */
    private function prepareParameterList(\ReflectionMethod|\ReflectionFunction $reflection, array $availableParams) : array
    {
        $reflectionParams = array_map(fn($param) => $param->getName(), $reflection->getParameters());

        $params = array_intersect_key($availableParams, array_flip($reflectionParams));

        return $params ?: [$availableParams['request']];
    }
}