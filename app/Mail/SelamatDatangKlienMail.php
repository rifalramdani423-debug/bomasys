<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\User;

class SelamatDatangKlienMail extends Mailable
{
    use Queueable, SerializesModels;

    public $klien;

    public function __construct(User $klien)
    {
        $this->klien = $klien;
    }

    public function build()
    {
        return $this->subject('Selamat Datang di BOMA Advertising!')
                    ->view('emails.selamat_datang_klien')
                    ->text('emails.selamat_datang_klien_text');
    }
}