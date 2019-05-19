<?php

namespace Apkom\Http\Controllers;

use Illuminate\Http\Request;
use Apkom\Skpi;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }

    public function chart(Request $request)
    {   
        $this->authorize('isKaprodi');
        $this->skpi = new Skpi;
        $skpi = $this->skpi->chart($request->id);
        return $skpi;
    }

    public function saveChart(Request $request)
    {   
        $this->authorize('isKaprodi');
        $this->skpi = new Skpi;
        $skpi = $this->skpi->saveChart($request);
        return $skpi;
    }

}
