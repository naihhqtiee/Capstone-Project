<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index()
    {
        return view('index'); // now loads app/Views/index.php
    }
    public function login()
{
    return view('login'); // this will load app/Views/login.php
}

}
