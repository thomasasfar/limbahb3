<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AktivitasMasukLimbah;
use App\Models\AktivitasKeluarLimbah;
use App\Models\AktivitasLimbah;
use Session;
use App\Models\Dokumen;

class DokumenController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $aktivitas_masuk = AktivitasLimbah::where('aktivitas','masuk')->get();
        $aktivitas_keluar = AktivitasLimbah::where('aktivitas','keluar')->get();
        return view('dokumen.index',compact('aktivitas_masuk','aktivitas_keluar'));
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // Filter by sumber now stored as unit_id integer
        $aktivitas_limbah = AktivitasLimbah::where('sumber',Session::get('unit_id'))->where('aktivitas','masuk')->get();
        return view('dokumen.index2',compact('aktivitas_limbah'));
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
