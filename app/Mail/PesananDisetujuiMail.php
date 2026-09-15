<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Pengajuan;

class PesananDisetujuiMail extends Mailable
{
    use Queueable, SerializesModels;

    public $pengajuan;

    public function __construct(Pengajuan $pengajuan)
    {
        $this->pengajuan = $pengajuan;
    }

    public function build()
    {
        return $this->subject('Pengajuan Sewa Titik Reklame Disetujui - BOMA')
                    ->view('emails.pesanan_disetujui')
                    ->text('emails.pesanan_disetujui_text');
    }
}