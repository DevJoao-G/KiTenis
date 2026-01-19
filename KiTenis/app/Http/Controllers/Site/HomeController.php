<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class HomeController extends Controller
{
    /**
     * Exibe a página inicial
     */
    public function index(): View
    {
        return view('site.home');
    }
}