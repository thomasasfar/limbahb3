<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Galeri;

class GaleriController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $photo = Galeri::all();
        return view('galeri.index',compact('photo'));
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
            'photos.*' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $uploadedFiles = $request->file('photos');
        $storedFiles = [];

        foreach ($uploadedFiles as $file) {
            $fileName = time() . '-' . $file->getClientOriginalName();
            $gbr = $file->move('img/galeri/', $fileName);

            // Simpan ke database
            $photo = Galeri::create(['filename' => $fileName,'desksripsi'=>'Gambar']);
        }
        return back()->with('success','Berhasil memasukan gambar!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
