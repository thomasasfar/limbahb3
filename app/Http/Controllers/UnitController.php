<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use Illuminate\Http\Request;

class UnitController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $units = Unit::all();
        return view('unit.index', compact('units'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_unit' => 'required|string|max:255',
        ]);

        // Generate kode otomatis
        $lastUnit = Unit::orderBy('kode', 'desc')->first();
        if ($lastUnit) {
            $lastKode = intval($lastUnit->kode);
            $newKode = str_pad($lastKode + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $newKode = '0001';
        }

        Unit::create([
            'kode' => $newKode,
            'nama_unit' => $request->nama_unit,
        ]);

        return back()->with('success', 'Unit berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Unit $unit)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Unit $unit)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Unit $unit)
    {
        $request->validate([
            'nama_unit' => 'required|string|max:255',
        ]);

        $unit->update([
            'nama_unit' => $request->nama_unit,
        ]);

        return back()->with('success', 'Unit berhasil diupdate!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Unit $unit)
    {
        $unit->delete();
        return back()->with('success', 'Unit berhasil dihapus!');
    }
}
