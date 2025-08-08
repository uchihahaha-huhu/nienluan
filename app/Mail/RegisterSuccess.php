<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RegisterSuccess extends Mailable
{
    use Queueable, SerializesModels;

    public $name;
    public $email;
    public $verificationToken;

    public function __construct($name, $email)
    {
        $this->name = $name;
        $this->email = $email;
        // Tạo token xác thực hoặc truyền token từ controller nếu có
        $this->verificationToken = base64_encode($email . '|' . now()->timestamp);
    }

    public function build()
    {
        $verificationLink = route('get.verify_email', ['token' => $this->verificationToken]);

        return $this->subject('Xác thực tài khoản')
                    ->view('emails.register_success')
                    ->with([
                        'name' => $this->name,
                        'verificationLink' => $verificationLink,
                    ]);
    }
}
