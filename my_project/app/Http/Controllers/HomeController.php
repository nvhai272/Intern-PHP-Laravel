<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Constants\Constant as Constant;
class HomeController extends Controller
{
    public function __construct()
    {
    }
    public function index()
    {
        return view('layouts.home');
    }
}


