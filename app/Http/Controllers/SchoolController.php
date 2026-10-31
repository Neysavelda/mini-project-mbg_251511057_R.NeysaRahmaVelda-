<?php

namespace App\Http\Controllers;

use App\Models\School;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class SchoolController extends Controller
{
    public function index()
    {
        $schools = School::withCount('distributions')->get();
        return view('schools.index', compact('schools'));
    }

    public function create()
    {
        return view('schools.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated =$request->validate([
            'name' => ['required', 'string', 'max:255'],
            'district' => ['required', 'string', 'max:255'],
        ]);

        School::create($validated);

        return redirect()->route('schools.index')->with('status', 'Sekolah berhasil ditambahkan.');
    }
}