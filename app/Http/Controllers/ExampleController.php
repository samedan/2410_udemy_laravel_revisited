<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ExampleController extends Controller
{
    public function homepage() {
        // data from dbb
        $ourName = "dan";
        $animals = ['Meowalot', 'Barks', 'Purssalot'];

        return view('homepage', [
            'allAnimals' => $animals,
            'name' => $ourName,
            'catname' => "Meawalot"
        ]);
    }
    public function aboutPage() {
        return view('single-post');
    }
}
