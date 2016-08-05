<?php

class Controller extends Application
{
    protected $controller;
    protected $action;
    protected $models;
    protected $view;

    public function __construct($controller, $action)
    {
        parent::__construct();

        $this->controller = $controller;
        $this->action = $action;
        $this->view = new View();

        session_start();


        if (isset($_SESSION['login_user']) && $_SESSION['login_user']) {
            $this->load_model('User');
            $user_id = $_SESSION['login_user'];
            $user = $this->get_model('User')->getById($user_id);

            $this->get_view()->viewData(array(
                'user' => $user
            ));
        }
    }

    // Load model class protected
    function load_model($model)
    {
        if (class_exists($model)) {
            $this->models[$model] = new $model();
        }
    }


    protected function get_model($model)
    {
        if (is_object($this->models[$model])) {
            return $this->models[$model];
        } else {
            return false;
        }
    }

// Return view object
    protected function get_view()
    {
        return $this->view;
    }

    function redirect($url, $permanent = false)
    {
        header('Location: ' . $url, true, $permanent ? 301 : 302);

        exit();
    }
}
