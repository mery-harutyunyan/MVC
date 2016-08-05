<?php

class Router
{
    public function route($url)
    {
        // Split the URL into parts
        $url_array = explode("/", $url);

        // get controller name
        $controller = isset($url_array[0]) && !empty($url_array[0]) ? $url_array[0] : DEFAULT_CONTROLLER;
        array_shift($url_array);
        $controller = ucwords($controller) . 'Controller';

        if (!file_exists(ROOT . DS . 'app' . DS . 'controllers' . DS . $controller . '.php')) {

            $errorUrl = '/errors/notFoundController';
            header('Location: ' . $errorUrl, true, $permanent ? 301 : 302);

            exit();
        }

        // get method name
        $action = isset($url_array[0]) && !empty($url_array[0]) ? $url_array[0] : 'index';
        array_shift($url_array);

        // get parameters
        $query_string = $url_array;

        $controller_name = $controller;
        $dispatch = new $controller($controller_name, $action);


        if ((int)method_exists($controller, $action)) {
            call_user_func_array(array($dispatch, $action), $query_string);
        } else {
            $errorUrl = '/errors/notFoundMethod';
            header('Location: ' . $errorUrl, true, $permanent ? 301 : 302);

            exit();
        }
    }
}