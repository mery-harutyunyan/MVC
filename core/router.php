<?php

class Router
{
    public function route($url)
    {
        // Split the URL into parts
        $url_array = array();
        $url_array = explode("/", $url);

        // get controller name
        $controller = isset($url_array[0]) && !empty($url_array[0]) ? $url_array[0] : DEFAULT_CONTROLLER;
        array_shift($url_array);

        // get method name
        $action = isset($url_array[0]) && !empty($url_array[0])  ? $url_array[0] : 'index';
        array_shift($url_array);

        // get parameters
        $query_string = $url_array;

        $controller_name = $controller;
        $controller = ucwords($controller).'Controller';
        $dispatch = new $controller($controller_name, $action);


        if ((int)method_exists($controller, $action)) {
            call_user_func_array(array($dispatch, $action), $query_string);
        } else {
            /* Error Generation Code Here */
        }
    }
}