<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Document;
use App\Models\AktivitasLimbah;
use App\Models\AktivitasMasuk;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;

class ValidationController extends Controller
{
    public function showForm()
    {
        return view('validation.form');
    }

    private function validateRoleSequence($aktivitasLimbah, $requiredRole)
    {
        $roles = [
            'menyerahkan' => 'approved_by_menyerahkan_at',
            'menerima' => 'approved_by_menerima_at',
            'personil_she' => 'approved_by_personil_she_at',
            'personil_pengamanan' => 'approved_by_personil_pengamanan_at',
            'kaunit_she' => 'approved_by_kaunit_she_at'
        ];

        $currentIndex = array_search($requiredRole, array_keys($roles));
        foreach (array_slice($roles, 0, $currentIndex) as $column) {
            if (empty($aktivitasLimbah->$column)) {
                return false;
            }
        }
        return true;
    }

    public function approveMenyerahkan(Request $request)
    {
        return $this->approve($request, 'menyerahkan');
    }

    public function approveMenerima(Request $request)
    {
        return $this->approve($request, 'menerima');
    }

    public function approvePersonilShe(Request $request)
    {
        return $this->approve($request, 'personil_she');
    }

    public function approvePersonilPengamanan(Request $request)
    {
        return $this->approve($request, 'personil_pengamanan');
    }

    public function approveKaunitShe(Request $request)
    {
        return $this->approve($request, 'kaunit_she');
    }

    private function approve(Request $request, $role)
    {
        $aktivitasLimbah = AktivitasLimbah::findOrFail($request->id);

        if (!$this->validateRoleSequence($aktivitasLimbah, $role)) {
            return redirect()->back()->with('error', 'Role sebelumnya belum melakukan validasi.');
        }

        $roleColumn = 'approved_by_' . $role . '_at';
        $aktivitasLimbah->$roleColumn = Carbon::now();
        $aktivitasLimbah->save();

        $qrCodeText = "Telah diapprove oleh {$request->name} sebagai {$role} pada " . Carbon::now()->toDateTimeString();
        $qrCode = new QrCode($qrCodeText);
        $qrCode->setSize(100);
        $writer = new PngWriter();
        $qrCodeImage = $writer->write($qrCode)->getDataUri();

        return redirect()->back()->with('success', 'Approved successfully.')->with('qrCode', $qrCodeImage);
    }

    public function generateDocument($id)
    {
        $aktivitasLimbah = AktivitasLimbah::findOrFail($id);
        $aktivitasMasuk = AktivitasMasuk::where('id_aktivitaslimbah', $id)->get();

        $qrCode = new QrCode('Approved by: ' . $aktivitasLimbah->kaunit_she);
        $qrCode->setSize(100);
        $writer = new PngWriter();
        $qrCodeImage = $writer->write($qrCode)->getDataUri();

        $pdf = Pdf::loadView('validation.pdf', compact('aktivitasLimbah', 'aktivitasMasuk', 'qrCodeImage'));
        $fileName = 'document_' . $id . '.pdf';
        $pdf->save(storage_path('app/public/documents/' . $fileName));

        return response()->download(storage_path('app/public/documents/' . $fileName));
    }
}
