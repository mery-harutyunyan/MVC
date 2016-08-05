<?php

class View
{
    protected $variables = array();

    function __construct()
    {

    }

    function viewData($data = array())
    {
        $this->variables = $data;
    }

    function render($view_name, $data = array())
    {
        $this->variables = $this->variables + $data;
        extract($this->variables);
        if (file_exists(ROOT . DS . 'app' . DS . 'views' . DS . $view_name . '.php')) {
            include(ROOT . DS . 'app' . DS . 'views' . DS . $view_name . '.php');
        } else {
            /* throw exception */
        }
    }
}