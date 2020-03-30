<?php
//namespace App\core;
use App\Core\AppException;

/**
 * Router
 */
class Router
{
    private static $routers = [];

    private $basePath;
    private $supportedHttpMethods = array(
        "GET",
        "POST",
    );
    public function __construct($basePath)
    {
        $this->basePath = $basePath;
    }

    private function getRequestURL()
    {

        $url = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '/';
        $url = rtrim($url, "/");
        $url = $url === '' || empty($url) ? '/' : $url;

        return $url;
    }

    private function getRequestMethod()
    {
        $method = isset($_SERVER['REQUEST_METHOD']) ? $_SERVER['REQUEST_METHOD'] : 'GET';
        return $method;
    }
    private function getRegex($pattern)
    {
        if (preg_match('/[^-:\/_{}()a-zA-Z\d]/', $pattern)) {
            return false;
        }
        // Invalid pattern

        // thay (/) bang /?
        $pattern = preg_replace('#\(/\)#', '/?', $pattern);

        // Create capture group for ":parameter"
        $allowedParamChars = '[a-zA-Z0-9\_\-]+';
        $pattern = preg_replace(
            '/:(' . $allowedParamChars . ')/', # Replace ":parameter"
            '(?<$1>' . $allowedParamChars . ')', # with "(?<parameter>[a-zA-Z0-9\_\-]+)"
            $pattern
        );

        // Create capture group for '{parameter}'
        $pattern = preg_replace(
            '/{(' . $allowedParamChars . ')}/', # Replace "{parameter}"
            '(?<$1>' . $allowedParamChars . ')', # with "(?<parameter>[a-zA-Z0-9\_\-]+)"
            $pattern
        );

        // Add start and end matching
        $patternAsRegex = "@^" . $pattern . "$@D";

        return $patternAsRegex;
    }
    private function getParamater($testCases)
    {
        foreach ($testCases as $test) {
            // Make regexp from route
            $patternAsRegex = $this->getRegex($test['route']);

            if ($ok = !!$patternAsRegex) {
                // We've got a regex, let's parse a URL
                if ($ok = preg_match($patternAsRegex, $test['url'], $matches)) {
                    // Get elements with string keys from matches
                    $params = array_intersect_key(
                        $matches,
                        array_flip(array_filter(array_keys($matches), 'is_string')));
                    //print_r($params['ok']);

                    return $params;
                }
            }
            return;

        }
    }
    private static function addRouter($method, $url, $action)
    {
        self::$routers[] = [$method, $url, $action];
    }

    public static function get($url, $action)
    {
        self::addRouter('GET', $url, $action);
    }

    public static function post($url, $action)
    {
        self::addRouter('POST', $url, $action);
    }

    public static function any($url, $action)
    {
        self::addRouter('GET|POST', $url, $action);
    }

    public function map()
    {

        $checkRoute = false;
        $params = [];

        $requestURL = $this->getRequestURL();
        $requestMethod = $this->getRequestMethod();
        if (!in_array($requestURL, $this->supportedHttpMethods)) {
            $this->invalidMethodHandler();
        }
        $routers = self::$routers;

        foreach ($routers as $route) {
            list($method, $url, $action) = $route;

            if (strpos($method, $requestMethod) === false) {
                continue;
            } elseif ($url === $requestURL) {
                $checkRoute = true;
            } elseif ($this->getParamater(array([
                'route' => $url,
                'url' => $requestURL,
                'expectedParam' => ['name' => 'sarah'],
            ]))) {
                $checkRoute = true;

            }

            if ($checkRoute === true) {
                $params = $this->getParamater(array(['route' => $url, 'url' => $requestURL]));
                if (is_callable($action)) {
                    //print_r($params);
                    //die;
                    call_user_func_array($action, array(&$params));
                } elseif (is_string($action)) {
                    $this->compieRoute($action, $params);
                }
                return;
            } else {
                continue;
            }

        }

        return;
    }

    private function compieRoute($action, $params)
    {

        if (count(explode('@', $action)) !== 2) {
            die('Router error');
        }

        $className = explode('@', $action)[0];
        $methodName = explode('@', $action)[1];

        $classNamespace = "App\\Controllers\\" . $className;

        if (class_exists($classNamespace)) {
            //Registry::getIntanAe()->controller = $className;
            $object = new $classNamespace;
            if (method_exists($classNamespace, $methodName)) {
                //Registry::getIntance()->action = $methodName;
                call_user_func_array([$object, $methodName], $params);
            } else {
                throw new AppException('Method "' . $methodName . '" not found');
            }
        } else {
            throw new AppException('Class "' . $classNamespace . '" not found');
        }
    }
    private function invalidMethodHandler()
    {
        header("{$this->getRequestMethod()} 405 Method Not Allowed");
    }
    public function run()
    {
        $this->map();
    }

}
