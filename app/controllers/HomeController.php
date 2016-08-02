<?php

class HomeController extends Controller
{
    public function __construct($controller, $action)
    {
        parent::__construct($controller, $action);
    }

    public function index(){
        $this->get_view()->render('home/index');
    }
}