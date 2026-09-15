<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Pengajuan;

class PesananRevisiMail extends Mailable
{
    use Queueable, SerializesModels;

    public $pengajuan;

    public function __construct(Pengajuan $pengajuan)
    {
        $this->pengajuan = $pengajuan;
    }

    public function build()
    {
        return $this->subject('Pemberitahuan: Revisi Dokumen Pengajuan Sewa (Titik Aman)')
                    ->view('emails.pesanan_revisi')
                    ->text('emails.pesanan_revisi_text');
    }
}