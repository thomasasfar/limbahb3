<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AktivitasLimbah;
use App\Models\AktivitasMasukLimbah;
use App\Models\Pengajuan;
use App\Models\KlasifikasiLimbah;
use Session;
use Alert;
use App\Models\User;
use Endroid\QrCode\QrCode;
use App\Services\WhatsAppService;
use Endroid\QrCode\Writer\PngWriter;


class PengajuanController extends Controller
{
    /**
     * Display a listing of the resource.
     */

     protected $whatsappService;

     public function __construct(WhatsAppService $whatsappService)
     {
         $this->whatsappService = $whatsappService;
     }

    public function index()
    {
        $aktivitas = AktivitasLimbah::where('aktivitas','pengajuan')->get();
        $menunggu = AktivitasLimbah::where('aktivitas','menunggu')->get();
        $aktivitas_masuk = AktivitasMasukLimbah::where('status',0)->get();
        $pengajuan = Pengajuan::all();
        $klasifikasi_limbah = KlasifikasiLimbah::all();
        return view('pengajuan.index',compact('aktivitas','aktivitas_masuk','pengajuan','menunggu','klasifikasi_limbah'));
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

    /**
     * Show the form for creating a new resource.
     */
    public function pengajuan(string $id,$aktivitas)
    {
        $aktivitas_limbah= AktivitasLimbah::find($aktivitas);
        if($aktivitas_limbah->aktivitas=="menunggu_validasi"){
            $user = User::find($id);
            $pengajuan = Pengajuan::where('id_aktivitaslimbah',$aktivitas)->first();
            $aktivitas_limbah= AktivitasLimbah::find($aktivitas);
            $aktivitas_masuk= AktivitasMasukLimbah::where('id_aktivitaslimbah',$aktivitas)->get();
            $menyerahkan = $aktivitas_limbah->menyerahkan;
            $tanggal = \Carbon\Carbon::now();
            $qrcode_menyerahkan = $this->generateQrCode("Dokumen telah di ajukan oleh {$menyerahkan} pada {$tanggal->translatedFormat('l, d F Y')}.");
            return view('limbah.pengajuan_form',compact('pengajuan','aktivitas_limbah','aktivitas_masuk','user','qrcode_menyerahkan'));
        }else{
            return redirect('/')->with('error','Tidak dapat mengakses halaman');
        }
    }

    public function setujui(string $id)
    {
        $aktivitas_limbah= AktivitasLimbah::find($id);
        if($aktivitas_limbah->aktivitas=="menunggu"){
            $aktivitas_masuk= AktivitasMasukLimbah::where('id_aktivitaslimbah',$id)->get();
            $menyerahkan = $aktivitas_limbah->menyerahkan;
            $kaunit = $aktivitas_limbah->penghasil;
            $tanggal = \Carbon\Carbon::now();
            $klasifikasi_limbah = KlasifikasiLimbah::all();
            $qrcode_menyerahkan = $this->generateQrCode("Dokumen telah di ajukan oleh {$menyerahkan} pada {$tanggal->translatedFormat('l, d F Y')}.");
            $qrcode_kaunit = $this->generateQrCode("Dokumen telah di approve oleh {$kaunit} sebagai Ka. Unit {$aktivitas_limbah->penghasil} pada {$tanggal->translatedFormat('l, d F Y')}.");
            return view('limbah.setujui_form',compact('aktivitas_limbah','aktivitas_masuk','qrcode_menyerahkan','qrcode_kaunit','klasifikasi_limbah'));
        }else{
            return redirect('/')->with('error','Tidak dapat mengakses halaman');
        }
    }

    public function setujui1(string $id)
    {
        $aktivitas_limbah= AktivitasLimbah::find($id);
        if($aktivitas_limbah->aktivitas=="pengajuan"){
            $aktivitas_masuk= AktivitasMasukLimbah::where('id_aktivitaslimbah',$id)->get();
            $menyerahkan = $aktivitas_limbah->menyerahkan;
            $kaunit = $aktivitas_limbah->penghasil;
            $tanggal = \Carbon\Carbon::now();
            $klasifikasi_limbah = KlasifikasiLimbah::all();
            $qrcode_menyerahkan = $this->generateQrCode("Dokumen telah di ajukan oleh {$menyerahkan} pada {$tanggal->translatedFormat('l, d F Y')}.");
            $qrcode_kaunit = $this->generateQrCode("Dokumen telah di approve oleh {$kaunit} sebagai Ka. Unit {$aktivitas_limbah->penghasil} pada {$tanggal->translatedFormat('l, d F Y')}.");
            return view('limbah.setujui_form1',compact('aktivitas_limbah','aktivitas_masuk','qrcode_menyerahkan','qrcode_kaunit','klasifikasi_limbah'));
        }else{
            return redirect('/')->with('error','Tidak dapat mengakses halaman');
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function validasi(string $id)
    {
        $aktivitas = AktivitasLimbah::find($id);
        $aktivitas->update([
            'aktivitas'=>"pengajuan"
        ]);
        $admin = User::where('level','admin')->get();
        $whatsappService = new WhatsappService();
        $msg = "*Permintaan penyerahan limbah anda telah di approve oleh penanggung jawab unit anda.Silahkan menunggu validasi dari Personil TPS Limbah B3 sebelum melakukan pengantaran Limbah B3 ke TPS Limbah B3.*\n";
        $user = $aktivitas->user->nohp_user; 
        $result = $this->whatsappService->sendMessage($user, $msg);
        foreach($admin as $index=>$a){
            $whatsappService = new WhatsappService();
            $number = $a->no_hp;
            $message = "*Permintaan penyerahan limbah baru telah diterima.Silahkan melakukan penerimaan pengajuan pada link berikut https://limbahb3.tenagasp.com/{$aktivitas->id}/setujui*\n";
            $result = $this->whatsappService->sendMessage($number, $message);
            $results[] = [
                'number' => $number,
                'result' => $result,
            ];
        }
        return redirect('/')->with('success','Anda telah melakukan validasi pengajuan');
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
