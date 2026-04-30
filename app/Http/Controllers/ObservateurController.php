<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ObservateurController extends Controller
{
    public function index()
    {
        return view('pages.observateur.observateur-urbain');
    }
}
