<?php

namespace App\Http\Controllers;

use App\User as us;
use Illuminate\Contracts\View\Factory as ViewFactory;
use Illuminate\Http\Request;

class User extends Controller
{
    public function index(Request $request)
    {
        return view('user.index', []);
    }
}
