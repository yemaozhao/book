<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\M3Email;

class OrderShipped extends Mailable
{
    use Queueable, SerializesModels;

    public $m3_email;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(M3Email  $m3_email)
    {
        $this->m3_email=$m3_email;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('邮箱验证')->view('email_register');
    }
}
