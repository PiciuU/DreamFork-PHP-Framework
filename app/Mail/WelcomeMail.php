<?php

namespace App\Mail;

use Framework\Mail\Mailable;

class WelcomeMail extends Mailable
{
    /**
     * The name of the recipient.
     *
     * @var string
     */
    public $name;

    /**
     * Create a new message instance.
     *
     * @param string $name
     */
    public function __construct(string $name = 'Developer')
    {
        $this->name = $name;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.welcome', ['name' => $this->name])
            ->subject('Welcome to DreamFork Framework!');
    }
}