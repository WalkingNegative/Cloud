<?php

namespace core;

class Route
{
    const PAGE_404 = '/404';

    public static $routes = [];
    protected static $route = [];

    public static function add($pattern, $rote = [])
    {
        if (!empty(self::$routes[$pattern])) {
            return false;
        }

        self::$routes[$pattern] = $rote;
        return true;
    }

    private static function matchRote($url)
    {
        if ($url === self::PAGE_404) {
            return self::PAGE_404;
        }

        foreach (self::$routes as $pattern => $rote) {
            if (preg_match("#$pattern#i", $url)) {
                self::$route = $rote;
                return true;
            }
        }

        return false;
    }

    public static function dispatch($url)
    {
        $match_rote = self::matchRote($url);
        if (!empty($match_rote) && ($match_rote !== self::PAGE_404)) {
            $controller = self::getController(self::$route['controller']);
            $action = self::getAction(self::$route['action']);

            $controller->{$action}();
        } else if ($match_rote === self::PAGE_404) {
            $error = new Controller();
            $error->render404();
        } else {
            self::error404();
        }
    }

    public static function error404()
    {
//      TODO: create link to page "404 not found"
        $host = 'http://'.$_SERVER['HTTP_HOST'].'/';
        header('HTTP/1.1 404 Not Found ');
        header("Status: 404 Not Found");
        header('Location:'.$host.'404');
    }

    public static function getController($name)
    {
        if (empty($name)) {
            return null;
        }

        $name = str_replace('-', ' ', $name);
        $name = ucwords($name);
        $name = str_replace(' ', '', $name) . 'Controller';
        $controller = Config::singleton()->get('path.controller') . $name;

        return class_exists($controller) ? new $controller : null;
    }

    public static function getAction($name)
    {
        if (empty($name)) {
            return Config::singleton()->get('default_action');
        }

        $name = str_replace('-', ' ', $name);
        $name = ucwords($name);
        $name = str_replace(' ', '', $name);
        $name = lcfirst ($name) . 'Action';

        return $name !== 'Action' ? $name : null;
    }
}