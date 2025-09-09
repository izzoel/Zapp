<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        $data['path'] = 'auth.';
        $data['styles'] = $data['path'] . 'templates.styles';
        $data['sidebar'] = $data['path'] . 'templates.sidebar';
        $data['navbar'] = $data['path'] . 'templates.navbar';
        $data['footer'] = $data['path'] . 'templates.footer';
        $data['scripts'] = $data['path'] . 'templates.scripts';
        return view($data['path'] . 'index', $data);
    }
}
