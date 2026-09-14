<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller;

class HabitsController extends Controller
{
    public function dashboard()
    {
        return view('habits.dashboard');
    }

    public function show($id)
    {
        return view('habits.show', compact('id'));
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