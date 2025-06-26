<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class InfoAkunSiswaMail extends Mailable
{
    use Queueable, SerializesModels;

    public $nama;
    public $username;
    public $password;

    public function __construct($nama, $username, $password)
    {
        $this->nama = $nama;
        $this->username = $username;
        $this->password = $password;
    }

    public function build()
    {
        return $this->subject('Informasi Akun Siswa Nesasen')->view('admin.email.info-akun-siswa');
    }
}
