<?php

namespace App\Http\Controllers;

use App\Models\MealDistribution;
use App\Models\School;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class MealDistributionController extends Controller
{
    public function index()
    {
        $distributions = MealDistribution::with('school')->latest('distribution_date')->get();
        return view('distributions.index', compact('distributions'));
    }

    public function create()
    {
        $schools = School::all();
        return view('distributions.create', compact('schools'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated =$request->validate([
            'school_id' => ['required', 'exists:schools,id'],
            'distribution_date' => ['required', 'date'],
            'menu_description' => ['required', 'string'],
            'portion_count' => ['required', 'integer', 'min:1'],
        ]);

        MealDistribution::create($validated);

        return redirect()->route('distributions.index')->with('status', 'Jadwal distribusi berhasil ditambahkan.');
    }
}