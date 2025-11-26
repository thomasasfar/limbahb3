<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AktivitasKeluarLimbah;
use App\Models\AktivitasMasukLimbah;
use App\Models\KlasifikasiLimbah;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Dompdf\Dompdf;
use Dompdf\Options;

class LaporanLimbahController extends Controller
{
    public function index()
    {
        $tahun_sekarang = Carbon::now()->year;
        $tahun_mulai = 2017; // Atur tahun awal sesuai kebutuhan
        $tahun_list = range($tahun_mulai, $tahun_sekarang);

        return view('laporan.limbah_keluar', compact('tahun_list'));
    }

    public function generateReport(Request $request)
    {
        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');

        // Validasi input tanggal
        if (!$start_date || !$end_date) {
            return back()->with('error','Start date and end date are required.');
        }

        $startMonth = Carbon::parse($start_date)->month;
        $endMonth = Carbon::parse($end_date)->month;
        $monthsInRange = range($startMonth, $endMonth);

        // Ambil data klasifikasi limbah
        $klasifikasiLimbahs = KlasifikasiLimbah::all();

        // Akumulasi data limbah berdasarkan range tanggal
        $akumulasi = AktivitasKeluarLimbah::join('klasifikasi_limbahs', 'aktivitas_keluar_limbahs.id_klasifikasilimbah', '=', 'klasifikasi_limbahs.id')
            ->whereBetween('tgl_keluar', [$start_date, $end_date])
            ->selectRaw('
                klasifikasi_limbahs.id as id_klasifikasilimbah,
                klasifikasi_limbahs.jenis_limbah,
                SUM(CASE WHEN perlakuan = "diserahkan_ke_pihak_ketiga" THEN jml_keluar ELSE 0 END) as diserahkan_ke_pihak_ketiga,
                SUM(CASE WHEN perlakuan = "tidak_dikelola" THEN jml_keluar ELSE 0 END) as tidak_dikelola,
                SUM(CASE WHEN perlakuan = "dihasilkan" THEN jml_keluar ELSE 0 END) as dihasilkan,
                SUM(CASE WHEN perlakuan = "disimpan" THEN jml_keluar ELSE 0 END) as disimpan,
                SUM(CASE WHEN perlakuan = "dimanfaatkan_sendiri" THEN jml_keluar ELSE 0 END) as dimanfaatkan_sendiri,
                SUM(CASE WHEN perlakuan = "diolah_sendiri" THEN jml_keluar ELSE 0 END) as diolah_sendiri,
                SUM(CASE WHEN perlakuan = "ditimbun_sendiri" THEN jml_keluar ELSE 0 END) as ditimbun_sendiri,
                MONTH(tgl_keluar) as bulan
            ')
            ->groupBy('klasifikasi_limbahs.id', 'klasifikasi_limbahs.jenis_limbah', 'bulan')
            ->orderBy('klasifikasi_limbahs.jenis_limbah')
            ->get()
            ->groupBy('id_klasifikasilimbah');

        $htmlContent = view('limbah.limbah_keluar_hasil', compact('akumulasi', 'klasifikasiLimbahs', 'monthsInRange', 'start_date', 'end_date'))->render();

        // DOMPDF Setup
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);

        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($htmlContent);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();

        return $dompdf->stream("Laporan_Limbah_{$start_date}_to_{$end_date}.pdf", ["Attachment" => false]);
    }

}
