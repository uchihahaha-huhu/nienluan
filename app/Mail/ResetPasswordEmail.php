<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ResetPasswordEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $resetLink;

    /**
     * Create a new message instance.
     *
     * @param string $resetLink
     */
    public function __construct(string $resetLink)
    {
        $this->resetLink = $resetLink;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        // Áp dụng view email
        return $this->subject('Thông báo khôi phục mật khẩu')
                    ->view('emails.reset_password')
                    ->with([
                        'link' => $this->resetLink,
                    ]);
    }
}
