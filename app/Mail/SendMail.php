<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendMail extends Mailable
{
    use Queueable, SerializesModels;

    public $data;
    public $type;

    /**
     * Create a new message instance.
     * @param $data
     * @param $type
     */
    public function __construct($data, $type)
    {
        $this->data = $data;
        $this->type = $type;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        if($this->type == 1)
            return $this
                    ->subject("Verify your email.")
                    ->view('email.verify_email', $this->data);
        else if($this->type == 2)
            return $this
                    ->subject("Verify your new email.")
                    ->view('email.verify_new_email', $this->data);
        else if($this->type == 3)
            return $this
                    ->subject("Reset your password")
                    ->view('email.reset_password', $this->data);
        else if($this->type == 4)
            return $this
                ->subject("Musixblvd")
                ->view('email.invite');
        else
            return null;
    }

}
