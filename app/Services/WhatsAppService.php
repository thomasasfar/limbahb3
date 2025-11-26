<?php

namespace App\Services;

class WhatsappService
{
    protected $url;

    public function __construct()
    {
        // URL endpoint API WhatsApp
        $this->url = 'http://wa.tenagasp.com/send-message';
    }

    /**
     * Send WhatsApp message
     *
     * @param string $number The recipient's phone number
     * @param string $message The message to send
     * @param string|null $filePath Optional file to send
     * @return mixed The response from the WhatsApp API
     */
    public function sendMessage(string $number, string $message, string $filePath = null)
    {
        $curl = curl_init();

        // Membuat array data yang akan dikirim
        $postData = [
            'message' => $message,
            'number' => $number
        ];

        // Jika file path ada, tambahkan file ke postData
        if ($filePath) {
            $postData['file_dikirim'] = new \CURLFile($filePath);
        }

        curl_setopt_array($curl, [
            CURLOPT_URL => $this->url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $postData,
        ]);

        $response = curl_exec($curl);
        curl_close($curl);

        return $response;
    }
}
