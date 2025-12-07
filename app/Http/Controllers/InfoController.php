<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class InfoController extends Controller
{
    /**
     * Display the info page
     */
    public function index()
    {
        return view('info.index');
    }
}
