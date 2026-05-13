<?php

namespace App\Http\Controllers;

use App\Models\Page;
use Illuminate\Contracts\View\View;

class ServicesController extends Controller
{
    public function index(): View
    {
        $page = Page::query()
            ->where('slug', 'services')
            ->published()
            ->with(['sections' => fn ($q) => $q->active()->ordered()])
            ->first();

        return view('pages.services.services', compact('page'));
    }
}
