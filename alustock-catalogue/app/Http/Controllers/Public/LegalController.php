<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class LegalController extends Controller
{
    public function mentions(): View
    {
        return view('public.legal.mentions', ['l' => config('legal')]);
    }

    public function confidentialite(): View
    {
        return view('public.legal.confidentialite', ['l' => config('legal')]);
    }
}
