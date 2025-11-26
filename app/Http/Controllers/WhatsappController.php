<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class WhatsAppController extends Controller
{
    public function showQr()
    {
        // Ambil QR code dan status login dari Node.js server
        $response = Http::get('http://localhost:3000/qr-code');
        $qrCodeImageUrl = $response->json()['qrCodeImageUrl'];
        $isAuthenticated = $response->json()['isAuthenticated'];

        return view('whatsapp.qr', compact('qrCodeImageUrl', 'isAuthenticated'));
    }
}
