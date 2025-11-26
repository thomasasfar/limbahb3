<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Info;
use Illuminate\Support\Facades\Http;
use App\Models\Galeri;
use App\Models\User;
use App\Models\AktivitasLimbah;
use App\Models\KlasifikasiLimbah;
use App\Models\AktivitasMasukLimbah;
use Session;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function getRandomQuote() {
        $response = Http::get('https://indonesian-quotes-api.vercel.app/api/quotes/random');
        if ($response->successful()) {
            $data = $response->json()['data'];
            return $data;
        }
        return null;
    }

    public function index()
    {
        $idUser = Session::get('id');
        $user = User::find(Session::get('id'));
        $klasifikasi_limbah = KlasifikasiLimbah::all();
        // Ambil data aktivitas limbah berdasarkan id_user
        $aktivitasLimbah = AktivitasMasukLimbah::join('klasifikasi_limbahs', 'aktivitas_masuk_limbahs.id_klasifikasilimbah', '=', 'klasifikasi_limbahs.id')
            ->where('aktivitas_masuk_limbahs.id_user', $idUser)
            ->select(
                'klasifikasi_limbahs.jenis_limbah',
                'klasifikasi_limbahs.kode_limbah',
                'aktivitas_masuk_limbahs.tgl_masuk',
                'aktivitas_masuk_limbahs.sumber',
                'aktivitas_masuk_limbahs.jml_masuk',
                'aktivitas_masuk_limbahs.status',
                'klasifikasi_limbahs.satuan',
                'aktivitas_masuk_limbahs.keterangan'
            )
            ->get();

        $aktivitas_limbah = AktivitasLimbah::where('id_user',Session::get('id'))->get();
        // Siapkan data untuk grafik berdasarkan jenis limbah
        $chartData = AktivitasMasukLimbah::join('klasifikasi_limbahs', 'aktivitas_masuk_limbahs.id_klasifikasilimbah', '=', 'klasifikasi_limbahs.id')
            ->where('aktivitas_masuk_limbahs.id_user', $idUser)
            ->groupBy('klasifikasi_limbahs.jenis_limbah')
            ->selectRaw('klasifikasi_limbahs.jenis_limbah, SUM(aktivitas_masuk_limbahs.jml_masuk) as total')
            ->get();

        $chartDataFormatted = [
            'labels' => $chartData->pluck('jenis_limbah'),
            'data' => $chartData->pluck('total')
        ];
        $infos = Info::latest()->take(5)->get(); // Ambil 10 info terbaru
        
        $galeris = Galeri::all();
        
        return view('dashboard.index', [
            'aktivitasLimbah' => $aktivitasLimbah,
            'aktivitas_limbah' => $aktivitas_limbah,
            'chartData' => $chartDataFormatted,
            'klasifikasi_limbah'=>$klasifikasi_limbah,
            'infos'=>$infos,
            'galeris'=>$galeris,
            'user'=>$user,
        ]);
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
