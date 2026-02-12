<?php

namespace Framework\Mail;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mime\Address;

class Mailer
{
    /**
     * The Symfony Mailer instance.
     */
    protected $transport;

    /**
     * The global "from" address and name.
     */
    protected $from;

    /**
     * The recipient address.
     */
    protected $to;

    public function __construct(MailerInterface $transport, array $from = [])
    {
        $this->transport = $transport;
        $this->from = $from;
    }

    /**
     * Set the recipient of the message.
     */
    public function to($address): self
    {
        $this->to = $address;
        return $this;
    }

    /**
     * Send a new message using a Mailable instance.
     */
    public function send(Mailable $mailable): void
    {
        $mailable->build();

        $html = view($mailable->view, $mailable->viewData)->render();

        $fromAddress = $mailable->from['address'] ?? $this->from['address'];
        $fromName = $mailable->from['name'] ?? $this->from['name'];

        $email = (new Email())
            ->from(new Address($fromAddress, $fromName))
            ->to($this->to)
            ->subject($mailable->subject)
            ->html($html);

        if ($mailable->replyTo) {
            $email->replyTo(new Address(
                $mailable->replyTo['address'],
                $mailable->replyTo['name'] ?? ''
            ));
        }

        $this->transport->send($email);
    }
}