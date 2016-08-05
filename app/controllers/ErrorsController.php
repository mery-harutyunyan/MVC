<?php

class ErrorsController extends Controller
{
    public function __construct($controller, $action)
    {
        parent::__construct($controller, $action);
    }

    public function notFoundController()
    {
        $this->get_view()->render('errors/not_found_controller');
    }

    public function notFoundMethod()
    {
        $this->get_view()->render('errors/not_found_method');
    }
}