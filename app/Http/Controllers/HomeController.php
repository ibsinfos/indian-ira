<?php

namespace IndianIra\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Display the home page of the application.
     *
     * @return  \Illuminate\View\View
     */
    public function index()
    {
        $carousels = \IndianIra\Carousel::with(['products' => function ($query) {
            return $query->whereDisplay('Enabled');
        }])->whereDisplay('Enabled')->get();

        return view('welcome', compact('carousels'));
    }
}
