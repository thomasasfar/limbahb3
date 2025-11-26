<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LimbahController;
use App\Http\Controllers\WhatsappController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\LaporanLimbahController;
use App\Http\Controllers\DaftarController;
use App\Http\Controllers\GaleriController;
use App\Http\Controllers\InfoController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DokumenController;
use App\Http\Controllers\PengajuanController;
use App\Http\Controllers\PanduanController;
use App\Http\Controllers\UnitController;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\ValidationController;


// Web Routes
Route::get('/validation/form', [ValidationController::class, 'showForm'])->name('validation.form');
Route::post('/validation/approve/menyerahkan', [ValidationController::class, 'approveMenyerahkan'])->name('validation.approve.menyerahkan');
Route::post('/validation/approve/menerima', [ValidationController::class, 'approveMenerima'])->name('validation.approve.menerima');
Route::post('/validation/approve/personil_she', [ValidationController::class, 'approvePersonilShe'])->name('validation.approve.personil_she');
Route::post('/validation/approve/personil_pengamanan', [ValidationController::class, 'approvePersonilPengamanan'])->name('validation.approve.personil_pengamanan');
Route::post('/validation/approve/kaunit_she', [ValidationController::class, 'approveKaunitShe'])->name('validation.approve.kaunit_she');
Route::get('/validation/document/{id}', [ValidationController::class, 'generateDocument'])->name('validation.document');


Route::get('/validasi_pengajuan/{id}', [PengajuanController::class, 'validasi'])->name('validasi.pengajuan');
Route::get('/{id}/{aktivitas}/pengajuan', [PengajuanController::class, 'pengajuan'])->name('pengajuan.user');
Route::get('/{id}/terima', [PengajuanController::class, 'setujui'])->name('terima.penyerahan');
Route::get('/{id}/setujui', [PengajuanController::class, 'setujui1'])->name('setujui.penyerahan');
Route::get('/limbah/validate/{id}/{role}', [LimbahController::class, 'validateRole'])->name('limbah.validate');
Route::post('/logout', [LoginController::class, 'destroy'])->name('logout');
// Web Routes
Route::get('/validation/form', [ValidationController::class, 'showForm'])->name('validation.form');
Route::post('/validation/approve/{role}', [ValidationController::class, 'approve'])->name('validation.approve');
Route::get('/validation/document/{id}', [ValidationController::class, 'generateDocument'])->name('validation.document');

Route::get('/send-email', function () {
    $data = [
        'title' => 'Testing Email with Gmail SMTP',
        'body' => 'This email is sent from Laravel using Gmail SMTP.',
    ];

    try {
        Mail::raw($data['body'], function ($message) use ($data) {
            $message->to('radhiguath@gmail.com')
                    ->subject($data['title']);
        });
        return 'Email sent successfully!';
    } catch (\Exception $e) {
        return 'Failed to send email: ' . $e->getMessage();
    }
});

Route::get('/setujui_pengajuan/{id}', [LimbahController::class, 'acc_pengajuan'])->name('setujui.pengajuan');
Route::post('/terima_pengantaran/{id}', [LimbahController::class, 'terima_pengantaran'])->name('terima.pengantaran');


Route::post('/pengajuan/limbah', [LimbahController::class, 'pengajuan'])->name('pengajuan.limbah');
Route::get('/laporan-limbah', [LaporanLimbahController::class, 'index'])->name('laporan.index');
Route::post('/laporan-limbah', [LaporanLimbahController::class, 'generateReport'])->name('laporan.generate');

Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

Route::get('/klasifikasi-limbahs', [LimbahController::class, 'getKlasifikasiLimbahs']);
Route::get('/klasifikasi-limbahs/{id}', [LimbahController::class, 'getSatuan']);
// Route menambahkan klasifikasi Limbah
Route::post('/klasifikasi_limbah', [LimbahController::class, 'klasifikasi'])->name('limbah.klasifikasi');

Route::get('/limbah/creates', [LimbahController::class, 'creates'])->name('limbah.creates');
Route::get('/limbah-aktivitas/grafik', [LimbahController::class, 'showGrafik']);
Route::post('/limbah-aktivitas/data', [LimbahController::class, 'getDataForChart'])->name('limbah-aktivitas.data');
Route::get('/limbah-masuk/{year}', [LimbahController::class, 'getLimbahMasukByYear']);
Route::get('/limbah-keluar/{year}', [LimbahController::class, 'getLimbahKeluarByYear']);

Route::get('/filter-aktivitas-masuk', [LimbahController::class, 'filterAktivitasMasuk'])->name('filterAktivitasMasuk');
Route::get('/filter-limbah', [LimbahController::class, 'filterLimbah'])->name('filterLimbah');
Route::get('/grafik-limbah', [LimbahController::class, 'index'])->name('grafik.limbah');
Route::post('/grafik-limbah/data', [LimbahController::class, 'getLimbahData'])->name('grafik.limbah.data');
Route::resources([
    '/limbah'=>LimbahController::class,
    '/login'=>LoginController::class,
    '/album'=>GaleriController::class,
    '/info'=>InfoController::class,
    '/daftar'=>DaftarController::class,
    '/pengajuan'=>PengajuanController::class,
    '/dokumen'=>DokumenController::class,
    '/panduan'=>PanduanController::class,
]);
Route::get('/show-qr', [WhatsAppController::class, 'showQr'])->name('show-qr');

Route::resource('unit', UnitController::class);