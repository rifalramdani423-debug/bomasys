<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Termin;
use Illuminate\Support\Facades\Log;
use Midtrans\Config;
use Midtrans\CoreApi;

class PaymentController extends Controller
{
    public function __construct()
    {
        Config::$serverKey = env('MIDTRANS_SERVER_KEY');
        Config::$isProduction = env('MIDTRANS_IS_PRODUCTION', false);
        Config::$isSanitized = true;
        Config::$is3ds = true;
    }

    // 1. FUNGSI GENERATE VA (Meminta nomor VA ke Midtrans untuk Termin tertentu)
    public function generateVirtualAccount($terminId)
    {
        $termin = Termin::with('pengajuan.user')->findOrFail($terminId);

        if ($termin->status_termin === 'Lunas') {
            return false;
        }

        $params = [
            "payment_type" => "bank_transfer",
            "bank_transfer" => [
                "bank" => "bca" // Bisa diganti bni, bri, mandiri, dll
            ],
            "transaction_details" => [
                "order_id" => "BOMA-TRM-" . $termin->id . "-" . time(),
                "gross_amount" => (int) $termin->nominal,
            ],
            "customer_details" => [
                "first_name" => $termin->pengajuan->user->name ?? 'Klien BOMA',
                "email" => $termin->pengajuan->user->email ?? 'klien@boma.com',
                "phone" => $termin->pengajuan->user->no_wa ?? '08123456789',
            ]
        ];

        try {
            $response = CoreApi::charge($params);
            
            if (isset($response->va_numbers[0]->va_number)) {
                $nomorVa = $response->va_numbers[0]->va_number;
                
                // Simpan nomor VA ke database tabel termins
                $termin->update([
                    'nomor_va' => $nomorVa,
                    'status_termin' => 'Menunggu Pembayaran'
                ]);

                return true;
            }
        } catch (\Exception $e) {
            Log::error("Gagal generate VA Midtrans: " . $e->getMessage());
            return false;
        }
    }

    // 2. FUNGSI WEBHOOK (Menerima laporan lunas & membuat VA berantai otomatis)
    public function handleWebhook(Request $request)
    {
        $payload = $request->all();
        Log::info('Midtrans Webhook diterima:', $payload);

        $transactionStatus = $payload['transaction_status'] ?? null;
        $orderId = $payload['order_id'] ?? null;

        preg_match('/BOMA-TRM-(\d+)-/', $orderId, $matches);
        $terminId = $matches[1] ?? null;

        if (!$terminId) {
            return response()->json(['message' => 'ID Termin tidak dikenali.'], 404);
        }

        $termin = Termin::find($terminId);
        if (!$termin) {
            return response()->json(['message' => 'Data termin tidak ditemukan.'], 404);
        }

        if ($transactionStatus == 'settlement' || $transactionStatus == 'capture') {
            
            // Ubah status termin menjadi Lunas
            $termin->update([
                'status_termin' => 'Lunas'
            ]);

            // LOGIKA VA BERANTAI: Cari termin berikutnya
            $terminBerikutnya = Termin::where('pengajuan_id', $termin->pengajuan_id)
                                      ->where('termin_ke', $termin->termin_ke + 1)
                                      ->first();

            if ($terminBerikutnya && empty($terminBerikutnya->nomor_va)) {
                $this->generateVirtualAccount($terminBerikutnya->id);
            }

            return response()->json(['status' => 'success', 'message' => 'Pembayaran berhasil diverifikasi.']);
        }

        return response()->json(['message' => 'Status transaksi diabaikan.']);
    }
}