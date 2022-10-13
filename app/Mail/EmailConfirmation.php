<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EmailConfirmation extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * User name
     *
     * @var string
     */
    protected string $name;

    /**
     * Verification token
     *
     * @var string
     */
    protected string $token;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(string $name, string $token)
    {
        $this->name = $name;
        $this->token = $token;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('email.email-confirmation', [
            'name'  => $this->name,
            'token' => $this->token
        ]);
    }
}
