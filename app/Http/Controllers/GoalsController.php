<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller;

class GoalsController extends Controller
{
    public function index()
    {
        return view('goals.index');
    }

    public function create()
    {
        return redirect()->back();
    }

    public function edit()
    {
        return redirect()->back();
    }

    public function delete()
    {
        return redirect()->back();
    }
}