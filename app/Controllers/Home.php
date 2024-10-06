<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index(): string
    {
        $data = [
            'title'     => 'Home',
            'content'       => 'dashboard/dashboard'
        ];
        return view('layout/wrapper', $data);
    }
}
