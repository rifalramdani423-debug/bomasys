<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PesananBaruMail extends Mailable
{
    use Queueable, SerializesModels;

    public $pengajuan;

    public function __construct($pengajuan)
    {
        $this->pengajuan = $pengajuan;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Notifikasi BOMA: Pesanan Sewa Reklame Baru Masuk!',
        );
    }

    public function content(): Content
    {
        // Kita arahkan ke view email buatan kita
        return new Content(
            view: 'emails.pesanan_baru',
            text: 'emails.pesanan_baru_text',
        );
    }
}