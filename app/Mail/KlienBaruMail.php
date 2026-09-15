<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\User;

class KlienBaruMail extends Mailable
{
    use Queueable, SerializesModels;

    public $klien;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $klien)
    {
        $this->klien = $klien;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Klien Baru Mendaftar - BOMA Sys')
                    ->view('emails.klien_baru')
                    ->text('emails.klien_baru_text');
    }
}