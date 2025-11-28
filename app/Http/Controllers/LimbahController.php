<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\KlasifikasiLimbah;
use App\Models\AktivitasMasukLimbah;
use App\Models\AktivitasKeluarLimbah;
use App\Models\AktivitasLimbah;
use App\Models\Dokumen;
use App\Models\Pengajuan;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Session;
use App\Services\WhatsAppService;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use DB;
use App\Models\User;


class LimbahController extends Controller
{
    /**
     * Display a listing of the resource.
     */

     protected $whatsappService;

     public function __construct(WhatsAppService $whatsappService)
     {
         $this->whatsappService = $whatsappService;
     }

    public function index(Request $request)
    {
        $results = DB::table('users as u')
        ->join('aktivitas_masuk_limbahs as aml', 'u.id', '=', 'aml.id_user')
        ->join('klasifikasi_limbahs as kl', 'aml.id_klasifikasilimbah', '=', 'kl.id')
        ->leftJoin('units as un', 'u.unit_id', '=', 'un.id')
        ->select('un.nama_unit as unit', 'kl.jenis_limbah', DB::raw('SUM(aml.jml_masuk) as total_jml_masuk'))
        ->groupBy('un.nama_unit', 'kl.jenis_limbah')
        ->orderBy('un.nama_unit')
        ->get();

        $tahun_sekarang = Carbon::now()->year;
        $tahun_mulai = 2017; // Atur tahun awal sesuai kebutuhan
        $tahun_list = range($tahun_mulai, $tahun_sekarang);
        $limbahMasuk = DB::table('aktivitas_masuk_limbahs')
            ->select(DB::raw('YEAR(tgl_masuk) as tahun, id_klasifikasilimbah, SUM(jml_masuk) as total_masuk'))
            ->where('status', 1) // Menambahkan kondisi status = 1
            ->groupBy(DB::raw('YEAR(tgl_masuk)'), 'id_klasifikasilimbah')
            ->get();

        // Ambil data limbah keluar per tahun
        $limbahKeluar = DB::table('aktivitas_keluar_limbahs')
            ->select(DB::raw('YEAR(tgl_keluar) as tahun, id_klasifikasilimbah, SUM(jml_keluar) as total_keluar'))
            ->groupBy('tahun', 'id_klasifikasilimbah')
            ->get();

        // Gabungkan data masuk dan keluar berdasarkan tahun
        $tahunData = [];
        foreach ($limbahMasuk as $masuk) {
            $tahunData[$masuk->tahun]['masuk'][$masuk->id_klasifikasilimbah] = $masuk->total_masuk;
        }
        foreach ($limbahKeluar as $keluar) {
            $tahunData[$keluar->tahun]['keluar'][$keluar->id_klasifikasilimbah] = $keluar->total_keluar;
        }

        // Hitung sisa limbah (masuk - keluar) per tahun
        $sisaPerTahun = [];
        foreach ($tahunData as $tahun => $data) {
            $totalMasuk = array_sum($data['masuk'] ?? []);
            $totalKeluar = array_sum($data['keluar'] ?? []);
            $sisaPerTahun[$tahun] = $totalMasuk - $totalKeluar;
        }

        $klasifikasi_limbah = KlasifikasiLimbah::all(); // Ambil data untuk select input
        $aktivitas_masuk = AktivitasMasukLimbah::where('status',1)->get();
        $aktivitas_keluar = AktivitasKeluarLimbah::all();
        $selectedMonth = $request->input('month', date('m'));
        $selectedYear = $request->input('year', date('Y'));
        $years = range(2017, 2024); 
        $data = DB::table('klasifikasi_limbahs as kl')
        ->leftJoin('aktivitas_masuk_limbahs as aml', 'kl.id', '=', 'aml.id_klasifikasilimbah')
        ->leftJoin('users as usr', 'aml.id_user', '=', 'usr.id')
        ->leftJoin('units as un', 'usr.unit_id', '=', 'un.id')
        ->select(
            'kl.jenis_limbah',
            'un.nama_unit as unit_dengan_akumulasi_terbanyak',
            DB::raw('SUM(aml.jml_masuk) as jumlah_akumulasi')
        )
        ->groupBy('kl.jenis_limbah', 'un.nama_unit')
        ->orderBy('kl.jenis_limbah')
        ->get()
        ->groupBy('jenis_limbah')
        ->map(function ($items) {
            return $items->sortByDesc('jumlah_akumulasi')->first();
        });
        return view('limbah.index2', compact('data','results','klasifikasi_limbah','aktivitas_masuk','aktivitas_keluar','selectedMonth', 'selectedYear','limbahMasuk','limbahKeluar','sisaPerTahun','years','tahun_list'));
    }

    public function filterAktivitasMasuk(Request $request)
    {
        $startMonth = $request->start_month;
        $startYear = $request->start_year;
        $endMonth = $request->end_month;
        $endYear = $request->end_year;

        // Filter data berdasarkan bulan dan tahun yang dipilih
        $aktivitasMasuk = AktivitasMasukLimbah::with('klasifikasi_limbah')
            ->whereMonth('tgl_masuk', '>=', $startMonth)
            ->whereYear('tgl_masuk', '>=', $startYear)
            ->whereMonth('tgl_masuk', '<=', $endMonth)
            ->whereYear('tgl_masuk', '<=', $endYear)
            ->where('status',1)
            ->get()
            ->map(function($a) {
                
                return [
                    'tgl_masuk' => $a->tgl_masuk,
                    'jenis_limbah' => $a->klasifikasi_limbah->jenis_limbah,
                    'sumber_limbah' => $a->sumber_unit->nama_unit ?? $a->user->unit->nama_unit ?? "-",
                    'jml_masuk' => $a->jml_masuk,
                    'satuan' => $a->klasifikasi_limbah->satuan,
                    'maksimal_penyimpanan' => Carbon::parse($a->tgl_masuk)->addDays(90)->toDateString(),
                ];
            });

        return response()->json(['data' => $aktivitasMasuk]);
    }

    public function getKlasifikasiLimbahs()
    {
        $data = KlasifikasiLimbah::all();
        return response()->json($data);
    }

    

    public function pengajuan(Request $request)
    {
        $user = User::find(Session::get('id'));
        
        // Generate nomor surat otomatis
        $currentMonth = Carbon::now()->format('m');
        $currentYear = Carbon::now()->format('Y');
        $kodeUnit = $user->unit->kode ?? '0000';
        
        // Ambil nomor terakhir untuk bulan ini
        $lastNoForm = AktivitasLimbah::where('id_user', Session::get('id'))
            ->whereYear('tanggal', $currentYear)
            ->whereMonth('tanggal', $currentMonth)
            ->orderBy('id', 'desc')
            ->first();
        
        // Ekstrak nomor urut dari no_form terakhir
        $lastNumber = 0;
        if ($lastNoForm && $lastNoForm->no_form) {
            $parts = explode('/', $lastNoForm->no_form);
            if (count($parts) > 0) {
                $lastNumber = intval($parts[0]);
            }
        }
        
        // Increment nomor
        $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        $noForm = "{$newNumber}/TPS-LIMBAHB3/{$kodeUnit}/{$currentMonth}.{$currentYear}";
        
        $aktivitas = AktivitasLimbah::insertGetId([
            'aktivitas'=> 'menunggu_validasi',
            'id_user'=>Session::get('id'),
            'tanggal'=>Carbon::now(),
            'no_form'=>$noForm,
            'menyerahkan'=>$request->menyerahkan ?? Session::get('name'),
            'penghasil'=>$user->penanggung_jawab,
            // store sumber as unit_id (integer FK)
            'sumber'=>$user->unit_id,
        ]);
        $pengajuan = Pengajuan::insertGetId([
            'id_user'=>Session::get('id'),
            'tanggal'=>\Carbon\Carbon::now(),
            'nama'=> $request->nama ?? null,
            'signature'=> $request->signature_menyerahkan,
            'id_aktivitaslimbah'=>$aktivitas,
        ]);
        foreach ($request->id_klasifikasilimbah as $index => $limbah) {
            $lmbh = AktivitasMasukLimbah::insertGetId([
                'id_klasifikasilimbah' => $limbah,
                'jml_masuk' => $request->jml_kg[$index],
                // store sumber as unit_id (integer FK)
                'sumber'=>$user->unit_id,
                'tgl_masuk'=>$request->tanggal,
                'id_aktivitaslimbah'=>$aktivitas,
                'id_user'=>Session::get('id'),
                'status'=>false,
                'keterangan'=>$request->keterangan[$index],
            ]);
        }
        // $whatsappService = new WhatsappService();
        $l = AktivitasMasukLimbah::find($lmbh);
        $user = User::find(Session::get('id'));
        $number = $user->no_hp;
        $id_user = Session::get('id');
        $whatsappService = new WhatsappService();
        $message = "*{$user->name} telah melakukan pengajuan penyerahan Limbah B3 kepada TPS Limbah B3 PT Semen Padang.\n"
        ."Silahkan melakukan approval pada dokumen pengajuan penyerahan Limbah B3 ke TPS Limbah B3 PT Semen Padang.*\n"
        . "*Pada link berikut https://limbahb3.tenagasp.com/{$id_user}/{$aktivitas}/pengajuan \n";
        $result = $this->whatsappService->sendMessage($number, $message);
        $results[] = [
            'number' => $number,
            'result' => $result,
        ];

        return back()->with('success','Berhasil mengirim pengajuan limbah!');
    }

    public function acc_pengajuan(string $id)
    {
        $aktivitaslimbah = AktivitasLimbah::find($id);
        $aktivitaslimbah->update([
            'aktivitas'=>'menunggu',
        ]);
        $whatsappService = new WhatsappService();
        $message = "*Pengajuan penyerahan limbah anda diterima oleh TPS Limbah B3. Silahkan antarkan limbah B3 anda ke TPS Limbah PT Semen Padang*\n";
        $number = $aktivitaslimbah->user->nohp_user;
        $result = $this->whatsappService->sendMessage($number, $message);
        $results[] = [
            'number' => $number,
            'result' => $result,
        ];
        $admin = User::where('level','admin')->get();
        $unit = $aktivitaslimbah->user->unit->nama_unit ?? 'Unit Tidak Diketahui';
        foreach($admin as $index=>$a){
            $message2 = "*Penyerahan limbah baru oleh Unit {$unit} telah diterima. Silahkan menunggu pengantaran oleh personil unit terkait.Berikut link untuk menerima pengantaran https://limbahb3.tenagasp.com/{$aktivitaslimbah->id}/terima*\n";
            $number2 = $aktivitaslimbah->user->nohp_user;
            $result2 = $this->whatsappService->sendMessage($number2, $message2);
            $results2[] = [
                'number' => $number,
                'result' => $result,
            ];
        }
        return redirect('/')->with('success','Berhasil melakukan');
    }

    private function generateQrCode($data)
    {
        $qrCode = new QrCode($data);
        $writer = new PngWriter();

        // Output QR Code ke dalam objek PngResult
        $result = $writer->write($qrCode);

        // Ambil string gambar dari objek PngResult
        $imageData = $result->getString();

        // Mengonversi gambar ke base64
        return base64_encode($imageData);
    }

    public function terima_pengantaran(Request $request,string $id)
    {
        $aktivitas_limbah = AktivitasLimbah::find($id);
        $aktivitas_masuk = AktivitasMasukLimbah::where('id_aktivitaslimbah',$id)->get();
        $mengetahui = $aktivitas_limbah->user->name;
        $aktivitas_limbah->update([
            'aktivitas'=>'masuk',
            'tanggal'=>Carbon::now(),
            'pengumpul'=>$mengetahui,
            'unit'=>$request->unit,
            'revisi'=>0,
            'menerima'=>$request->menerima,
            'menyerahkan'=>$aktivitas_limbah->menyerahkan,
        ]);
        foreach ($request->id_aktivitasmasuk as $index => $limbah) {
        $lmbh = AktivitasMasukLimbah::find($limbah);
        $lmbh->update([
            'jml_masuk' => $request->jml_masuk[$index],
            'tgl_masuk'=>Carbon::now(),
            'status'=>1,
        ]);
    }
        $akt = "masuk";
        $tanggal = Carbon::now();
        $aktivitas_masuk = AktivitasMasukLimbah::where('id_aktivitaslimbah',$id)->get();
        $aktivitas_limbah = AktivitasLimbah::find($id);
        $menyerahkan=$aktivitas_limbah->menyerahkan;
        $menerima=Session::get('name');
        
        $qrcode_kaunit = $this->generateQrCode("Dokumen telah disetujui oleh {$mengetahui} pada {$tanggal->translatedFormat('l, d F Y')}");
        $qrcode_menyerahkan = $this->generateQrCode("Dokumen telah di tanda tangani dan limbah telah diserahkan oleh {$menyerahkan} pada {$tanggal->translatedFormat('l, d F Y')} kepada TPS Limbah B# PT Semen Padang");
        $qrcode_menerima = $this->generateQrCode("Dokumen telah ditanda tangani dan limbah telah diterima oleh {$menerima} pada {$tanggal->translatedFormat('l, d F Y')} di TPS Limbah B3 PT Semen Padang");
        
        $pdf = Pdf::loadView('limbah.form', compact('aktivitas_masuk','aktivitas_limbah','akt','qrcode_kaunit','qrcode_menyerahkan','qrcode_menerima'));

        $fileName = 'limbah_masuk'.$id.'.pdf';
        $path = public_path('laporan/limbah/masuk/'. $fileName);

        if (!is_dir(public_path('laporan/limbah/masuk/'))) {
            mkdir(public_path('laporan/limbah/masuk/'), 0755, true);
        }
        Dokumen::create([
            'file_name'=>$fileName,
            'file_path'=>$path,
            'jenis_file'=>'Laporan Limbah Masuk',
        ]);
        file_put_contents($path, $pdf->output());
        $nmbr = [];
        $admin = User::where('level','admin')->get();
        foreach($admin as $a){
            array_push($nmbr,$admin);
        }
        $kaunit = $aktivitas_limbah->user->no_hp;
        array_push($nmbr,$kaunit);
        $user = $aktivitas_limbah->user->nohp_user;
        array_push($nmbr,$user);
        $file = public_path('laporan/limbah/masuk/limbah_masuk'.$id.'.pdf');
        foreach($nmbr as $index=>$n){
            $message2 = "*Dokumen penyerahan limbah telah dibuat.Berikut dokumen limbah tersebut.*\n";
            $number2 = $n;
            $filePath = $file;
            $result2 = $this->whatsappService->sendMessage($number2, $message2);
            $result2 = $this->whatsappService->sendMessage($number2, $message2,$filePath);
        }
        return back()->with('success','Pengajuan limbah telah diterima.Dan dokumen telah terkirim');
    }

    public function setujui_penyerahan(string $id)
    {
        $aktivitas_limbah = AktivitasLimbah::find($id);
        $aktivitas_masuk = AktivitasMasukLimbah::where('id_aktivitaslimbah',$id)->get();
        return view('limbah.form01',compact('aktivitas_limbah','aktivitas_masuk'))->with('success','Silahkan setujui pengantaran limbah');
    }

    public function getSatuan(string $id)
    {
        $klasifikasiLimbah = KlasifikasiLimbah::find($id);

        if ($klasifikasiLimbah) {
            return response()->json(['satuan' => $klasifikasiLimbah->satuan]);
        }

        return response()->json(['error' => 'Data tidak ditemukan'], 404);
    }

    public function filterLimbah(Request $request)
    {
        $startMonth = $request->start_month;
        $startYear = $request->start_year;
        $endMonth = $request->end_month;
        $endYear = $request->end_year;
    
        // Filter data berdasarkan bulan dan tahun yang dipilih
        $klasifikasiLimbah = KlasifikasiLimbah::all()->map(function($k) use ($startMonth, $startYear, $endMonth, $endYear) {
            $jml_masuk = App\Models\AktivitasMasukLimbah::where('id_klasifikasilimbah', $k->id)
                ->whereMonth('tgl_masuk', '>=', $startMonth)
                ->whereYear('tgl_masuk', '>=', $startYear)
                ->whereMonth('tgl_masuk', '<=', $endMonth)
                ->whereYear('tgl_masuk', '<=', $endYear)
                ->pluck('jml_masuk')->toArray();
            
            $jml_keluar = App\Models\AktivitasKeluarLimbah::where('id_klasifikasilimbah', $k->id)
                ->whereMonth('tgl_keluar', '>=', $startMonth)
                ->whereYear('tgl_keluar', '>=', $startYear)
                ->whereMonth('tgl_keluar', '<=', $endMonth)
                ->whereYear('tgl_keluar', '<=', $endYear)
                ->pluck('jml_keluar')->toArray();
            
            $akumulasi_masuk = array_sum($jml_masuk);
            $akumulasi_keluar = array_sum($jml_keluar);
            
            return [
                'jenis_limbah' => $k->jenis_limbah,
                'kode_limbah' => $k->kode_limbah,
                'satuan' => $k->satuan,
                'akumulasi_masuk' => $akumulasi_masuk,
                'akumulasi_keluar' => $akumulasi_keluar
            ];
        });
    
        return response()->json(['data' => $klasifikasiLimbah]);
    }

   
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $klasifikasi_limbah = KlasifikasiLimbah::all();
        return view('limbah.form_input',compact('klasifikasi_limbah'));
    }

    public function creates()
    {
        $klasifikasi_limbah = KlasifikasiLimbah::all();
        return view('limbah.form_input2',compact('klasifikasi_limbah'));
    }


    public function show(Request $request)
    {
        $selectedMonth = $request->input('month', date('m'));
        $selectedYear = $request->input('year', date('Y'));
    
        $klasifikasi_limbah = KlasifikasiLimbah::all(); // Adjust query as necessary
        return back();
    }
    
    public function generatePDF(Request $request)
    {
        $selectedMonth = $request->input('month');
        $selectedYear = $request->input('year');
    
        $klasifikasi_limbah = KlasifikasiLimbah::all(); // Adjust query as necessary
    
        $pdf = PDF::loadView('limbah.report', compact('klasifikasi_limbah', 'selectedMonth', 'selectedYear'));
    
        return $pdf->download('laporan-limbah-' . $selectedMonth . '-' . $selectedYear . '.pdf');
    }

    public function getLimbahMasukByYear(string $year)
    {
        // Validasi tahun yang diterima
        // $validated = $request->validate([
        //     'year' => 'required|integer|min:2000|max:2099',  // Validasi input tahun
        // ]);

        // Ambil data berdasarkan tahun yang dipilih
        $limbahData = KlasifikasiLimbah::select('klasifikasi_limbahs.jenis_limbah', DB::raw('SUM(aktivitas_masuk_limbahs.jml_masuk) as total_masuk'))
                        ->join('aktivitas_masuk_limbahs', 'aktivitas_masuk_limbahs.id_klasifikasilimbah', '=', 'klasifikasi_limbahs.id')
                        ->whereYear('aktivitas_masuk_limbahs.tgl_masuk', $year) // Menambahkan kondisi untuk tahun tertentu
                        ->groupBy('klasifikasi_limbahs.jenis_limbah')
                        ->get();
        // Return data dalam format JSON
        return response()->json($limbahData);
    }

    public function getLimbahKeluarByYear(string $year)
    {
        // Validasi tahun yang diterima
        // $validated = $request->validate([
        //     'year' => 'required|integer|min:2000|max:2099',  // Validasi input tahun
        // ]);

        // Ambil data berdasarkan tahun yang dipilih
        $limbahData = KlasifikasiLimbah::select('klasifikasi_limbahs.jenis_limbah', DB::raw('SUM(aktivitas_keluar_limbahs.jml_keluar) as total_keluar'))
                        ->join('aktivitas_keluar_limbahs', 'aktivitas_keluar_limbahs.id_klasifikasilimbah', '=', 'klasifikasi_limbahs.id')
                        ->whereYear('aktivitas_keluar_limbahs.tgl_keluar', $year) // Menambahkan kondisi untuk tahun tertentu
                        ->groupBy('klasifikasi_limbahs.jenis_limbah')
                        ->get();
        // Return data dalam format JSON
        return response()->json($limbahData);
    }

    public function getLimbahData(Request $request)
    {
        $idKlasifikasiLimbah = $request->id_klasifikasilimbah;
        $tahun = $request->tahun;
        $bulan = $request->bulan;

        // Query builder untuk limbah masuk
        $queryMasuk = DB::table('aktivitas_masuk_limbahs')
            ->where('id_klasifikasilimbah', $idKlasifikasiLimbah);

        // Query builder untuk limbah keluar
        $queryKeluar = DB::table('aktivitas_keluar_limbahs')
            ->where('id_klasifikasilimbah', $idKlasifikasiLimbah);

        // Filter berdasarkan tahun dan bulan
        if ($tahun && $bulan) {
            // Group by date (per hari dalam bulan tersebut)
            $queryMasuk->select(DB::raw('DAY(tgl_masuk) as hari, SUM(jml_masuk) as total_masuk'))
                ->whereYear('tgl_masuk', $tahun)
                ->whereMonth('tgl_masuk', $bulan)
                ->groupBy('hari')
                ->orderBy('hari');

            $queryKeluar->select(DB::raw('DAY(tgl_keluar) as hari, SUM(jml_keluar) as total_keluar'))
                ->whereYear('tgl_keluar', $tahun)
                ->whereMonth('tgl_keluar', $bulan)
                ->groupBy('hari')
                ->orderBy('hari');

            $limbahMasuk = $queryMasuk->get();
            $limbahKeluar = $queryKeluar->get();

            // Hitung sisa limbah per hari
            $data = [];
            foreach ($limbahMasuk as $masuk) {
                $hari = $masuk->hari;
                $data[$hari]['masuk'] = $masuk->total_masuk;
            }
            foreach ($limbahKeluar as $keluar) {
                $hari = $keluar->hari;
                $data[$hari]['keluar'] = $keluar->total_keluar;
            }

            $sisaLimbah = [];
            foreach ($data as $hari => $values) {
                $masuk = $values['masuk'] ?? 0;
                $keluar = $values['keluar'] ?? 0;
                $sisaLimbah[] = [
                    'tanggal' => $hari,
                    'sisa'  => $masuk - $keluar,
                    'masuk' => $masuk,
                    'keluar'=> $keluar
                ];
            }

        } elseif ($tahun) {
            // Group by month (per bulan dalam tahun tersebut)
            $queryMasuk->select(DB::raw('MONTH(tgl_masuk) as bulan, SUM(jml_masuk) as total_masuk'))
                ->whereYear('tgl_masuk', $tahun)
                ->groupBy('bulan')
                ->orderBy('bulan');

            $queryKeluar->select(DB::raw('MONTH(tgl_keluar) as bulan, SUM(jml_keluar) as total_keluar'))
                ->whereYear('tgl_keluar', $tahun)
                ->groupBy('bulan')
                ->orderBy('bulan');

            $limbahMasuk = $queryMasuk->get();
            $limbahKeluar = $queryKeluar->get();

            // Hitung sisa limbah per bulan
            $data = [];
            foreach ($limbahMasuk as $masuk) {
                $bulanNum = $masuk->bulan;
                $data[$bulanNum]['masuk'] = $masuk->total_masuk;
            }
            foreach ($limbahKeluar as $keluar) {
                $bulanNum = $keluar->bulan;
                $data[$bulanNum]['keluar'] = $keluar->total_keluar;
            }

            $namaBulan = [
                1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
                5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
                9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
            ];

            $sisaLimbah = [];
            foreach ($data as $bulanNum => $values) {
                $masuk = $values['masuk'] ?? 0;
                $keluar = $values['keluar'] ?? 0;
                $sisaLimbah[] = [
                    'bulan' => $namaBulan[$bulanNum],
                    'sisa'  => $masuk - $keluar,
                    'masuk' => $masuk,
                    'keluar'=> $keluar
                ];
            }

        } else {
            // Group by year (default behavior)
            $queryMasuk->select(DB::raw('YEAR(tgl_masuk) as tahun, SUM(jml_masuk) as total_masuk'))
                ->groupBy('tahun')
                ->orderBy('tahun');

            $queryKeluar->select(DB::raw('YEAR(tgl_keluar) as tahun, SUM(jml_keluar) as total_keluar'))
                ->groupBy('tahun')
                ->orderBy('tahun');

            $limbahMasuk = $queryMasuk->get();
            $limbahKeluar = $queryKeluar->get();

            // Hitung sisa limbah per tahun
            $data = [];
            foreach ($limbahMasuk as $masuk) {
                $tahun = $masuk->tahun;
                $data[$tahun]['masuk'] = $masuk->total_masuk;
            }
            foreach ($limbahKeluar as $keluar) {
                $tahun = $keluar->tahun;
                $data[$tahun]['keluar'] = $keluar->total_keluar;
            }

            $sisaLimbah = [];
            foreach ($data as $tahun => $values) {
                $masuk = $values['masuk'] ?? 0;
                $keluar = $values['keluar'] ?? 0;
                $sisaLimbah[] = [
                    'tahun' => $tahun,
                    'sisa'  => $masuk - $keluar,
                    'masuk' => $masuk,
                    'keluar'=> $keluar
                ];
            }
        }

        return response()->json($sisaLimbah);
    }

    public function getLimbahByUnit()
    {
        // Ambil data limbah masuk berdasarkan unit
        $limbahByUnit = DB::table('aktivitas_masuk_limbahs as aml')
            ->join('users as u', 'aml.id_user', '=', 'u.id')
            ->join('units as un', 'u.unit_id', '=', 'un.id')
            ->select(
                'un.nama_unit',
                'un.kode',
                DB::raw('SUM(aml.jml_masuk) as total_masuk')
            )
            ->where('aml.status', 1)
            ->groupBy('un.id', 'un.nama_unit', 'un.kode')
            ->orderBy('total_masuk', 'desc')
            ->get();

        return response()->json($limbahByUnit);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if($request->aktivitas=="masuk"){
            $aktivitas = AktivitasLimbah::insertGetid([
                'aktivitas'=>$request->aktivitas,
                'tanggal'=>$request->tanggal,
                'no_form'=>$request->no_form,
                'mengetahui'=>$request->mengetahui,
                'unit'=>$request->unit,
                'revisi'=>0,
                'pengumpul'=>$request->pengumpul,
                'penghasil'=>$request->penghasil,
                'menerima'=>$request->pengumpul,
                'menyerahkan'=>$request->penghasil,
                'sumber'=>$request->unit,
                'signature_pengumpul' => $request->signature_pengumpul, // Base64 tanda tangan pengumpul
                'signature_penghasil' => $request->signature_penghasil, // Base64 tanda tangan peghasil
                'signature_unit' => $request->signature_unit, // Base64 tanda tangan penghasil
                'id_user'=>Session::get('id'),
            ]);
            foreach ($request->id_klasifikasilimbah as $index => $limbah) {
            $limbah = AktivitasMasukLimbah::insertGetId([
                'id_klasifikasilimbah' => $limbah,
                'jml_masuk' => $request->jml_masuk[$index],
                // accept either unit_id (preferred) or fallback to unit
                'sumber'=>$request->unit_id ?? $request->unit,
                'tgl_masuk'=>$request->tanggal,
                'id_aktivitaslimbah'=>$aktivitas,
                'id_user'=>Session::get('id'),
            ]);
        }
            $akt = "masuk";
            $aktivitas_masuk = AktivitasMasukLimbah::where('id_aktivitaslimbah',$aktivitas)->get();
            $aktivitas_limbah = AktivitasLimbah::find($aktivitas);

            $pdf = Pdf::loadView('limbah.form', compact('aktivitas_masuk','aktivitas_limbah','akt'));

            $fileName = 'limbah_masuk'.$aktivitas.'.pdf';
            $path = public_path('laporan/limbah/masuk/'. $fileName);

            if (!is_dir(public_path('laporan/limbah/masuk/'))) {
                mkdir(public_path('laporan/limbah/masuk/'), 0755, true);
            }
            Dokumen::create([
                'file_name'=>$fileName,
                'file_path'=>$path,
                'jenis_file'=>'Laporan Limbah Masuk',
            ]);
            file_put_contents($path, $pdf->output());
            return $pdf->stream('surat_masuk_limbah.pdf');
        
        }else if($request->aktivitas=="keluar"){
            $aktivitas2 = AktivitasLimbah::insertGetid([
                'aktivitas'=>$request->aktivitas,
                'tanggal'=>$request->tanggal,
                'no_form'=>$request->no_form,
                'mengetahui'=>$request->mengetahui,
                'unit'=>$request->unit,
                'revisi'=>0,
                'tanggal'=>$request->tgl_keluar,
                'menyerahkan'=>$request->menyerahkan,
                'menerima'=>$request->menerima,
                'personil_she'=>$request->personil_she,
                'personil_pengamanan'=>$request->personil_pengamanan,
                'kaunit_she' =>$request->kaunit_she,              
                'tujuan'=>$request->tujuan,
                'no_menyerahkan' => $request->no_menyerahkan, // Base64 tanda tangan pengumpul
                'no_menerima' => $request->no_menerima, // Base64 tanda tangan penghasil
                'no_kaunit_she' => $request->no_kaunit_she, // Base64 tanda tangan penghasil
                'no_personil_pengamanan' => $request->no_personil_pengamanan, // Base64 tanda tangan pengumpul
                'no_personil_she' => $request->no_personil_she, // Base64 tanda tangan penghasil
                'id_user'=>Session::get('id'),
            ]);
            foreach ($request->id_klasifikasilimbah as $index => $limbah) {
            $limbah = AktivitasKeluarLimbah::insertGetId([
                'id_klasifikasilimbah' => $limbah,
                'jml_keluar' => $request->jml_kg[$index],
                'tujuan'=>$request->tujuan,
                'tgl_keluar'=>$request->tgl_keluar,
                'id_aktivitaslimbah'=>$aktivitas2,
                'id_user'=>Session::get('id'),
            ]);
        }
            // $akt = "keluar";
            // $roles = ['menyerahkan', 'menerima', 'personil_she', 'no_personil_pengamanan', 'kaunit_she'];
            // $aktivitasLimbah = AktivitasLimbah::find($aktivitas2);
            // foreach ($roles as $role) {
                // $number = $aktivitasLimbah["no_$role"];
                // $url = route('limbah.validate', ['id' => $aktivitasLimbah->id, 'role' => $role]);
                // // Kirimkan WhatsApp
                // $whatsappService = new WhatsappService();
                // $message = "*Dokumen penyerahan limbah telah ditambahkan. Silahkan validasi pada halaman berikut: $url*\n";
                // $result = $this->whatsappService->sendMessage($number, $message);
                // $results[] = [
                //     'number' => $number,
                //     'result' => $result,
                // ];
            // }
            // $aktivitas_keluar = AktivitasKeluarLimbah::where('id_aktivitaslimbah', $aktivitas2);
            // $aktivitas_limbah = AktivitasLimbah::find($aktivitas2);
            
            // $pdf = Pdf::loadView('limbah.form2', compact('aktivitas_keluar','aktivitas_limbah','akt'));

            // $fileName = 'limbah_keluar'.$aktivitas2.'.pdf';
            // $path = public_path('laporan/limbah/keluar/'. $fileName);

            // if (!is_dir(public_path('laporan/limbah/keluar/'))) {
            //     mkdir(public_path('laporan/limbah/keluar/'), 0755, true);
            // }

            // Dokumen::create([
            //     'file_name'=>$fileName,
            //     'file_path'=>$path,
            //     'jenis_file'=>'Laporan Limbah Keluar',
            // ]);

            // file_put_contents($path, $pdf->output());
            return redirect('/limbah')->with('success', 'Data limbah keluar berhasil disimpan.');
        }
        return back()->with('error','Gagal mengirimkan data!');
    }


    public function validateRole($id, $role)
    {
            // Ambil data aktivitas limbah berdasarkan ID
        $aktivitasLimbah = AktivitasLimbah::findOrFail($id);
        $tanggal = now(); // Ambil tanggal saat ini

        // Ambil data role dari tabel
        $name = null;
        $qrcode = null;

        if ($role === 'personil_she') {
            $name = $aktivitasLimbah->mengetahui;
            $qrcode = $this->generateQrCode("Dokumen telah disetujui oleh {$name} sebagai Ka. Unit pada {$tanggal->translatedFormat('l, d F Y')}");
        } elseif ($role === 'menyerahkan') {
            $name = $aktivitasLimbah->menyerahkan;
            $qrcode = $this->generateQrCode("Dokumen telah ditanda tangani dan limbah telah diserahkan oleh {$name} pada {$tanggal->translatedFormat('l, d F Y')} kepada TPS Limbah B3 PT Semen Padang");
        } elseif ($role === 'menerima') {
            $name = $aktivitasLimbah->menerima;
            $qrcode = $this->generateQrCode("Dokumen telah ditanda tangani dan limbah telah diterima oleh {$name} pada {$tanggal->translatedFormat('l, d F Y')} di TPS Limbah B3 PT Semen Padang");
        }elseif ($role === 'personil_ke') {
            $name = $aktivitasLimbah->menyerahkan;
            $qrcode = $this->generateQrCode("Dokumen telah ditanda tangani dan limbah telah diserahkan oleh {$name} pada {$tanggal->translatedFormat('l, d F Y')} kepada TPS Limbah B3 PT Semen Padang");
        } elseif ($role === 'menerima') {
            $name = $aktivitasLimbah->menerima;
            $qrcode = $this->generateQrCode("Dokumen telah ditanda tangani dan limbah telah diterima oleh {$name} pada {$tanggal->translatedFormat('l, d F Y')} di TPS Limbah B3 PT Semen Padang");
        }

        // Simpan QR Code ke database atau masukkan ke dalam template view
        return view('limbah.qr_validation', compact('aktivitasLimbah', 'qrcode', 'role', 'name'));
    }

    /**
     * Display the specified resource.
     */
    public function klasifikasi(Request $request)
    {
        KlasifikasiLimbah::create([
            'jenis_limbah'=>$request->jenis_limbah,
            'kode_limbah'=>$request->kode_limbah,
            'satuan'=>$request->satuan,
            'konversi'=>$request->konversi,
        ]);
        return back()->with('success','Data klasifikasi berhasil ditambhakan');
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
        $limbah = Limbah::findOrFail($id);
        $limbah->delete();
        return back()->with('success', 'Data berhasil dihapus.');
    }
}
