<?php

namespace Framework\Mail;

abstract class Mailable
{
    /**
     * The subject of the message.
     */
    public $subject;

    /**
     * The view to use for the message.
     */
    public $view;

    /**
     * The data to pass to the view.
     */
    public $viewData = [];

    /**
     * The "from" address of the message.
     */
    public $from = [];

    /**
     * Adres Reply-To.
     *
     * @var array|null
     */
    public $replyTo = null;

    /**
     * Build the message.
     *
     * @return $this
     */
    abstract public function build();

    /**
     * Set the subject of the message.
     */
    public function subject(string $subject): self
    {
        $this->subject = $subject;
        return $this;
    }

    /**
     * Set the view and data for the message.
     */
    public function view(string $view, array $data = []): self
    {
        $this->view = $view;
        $this->viewData = $data;
        return $this;
    }

    /**
     * Set the sender of the message.
     */
    public function from(string $address, string $name = null): self
    {
        $this->from = ['address' => $address, 'name' => $name];
        return $this;
    }

    /**
     * Set the "reply to" address of the message.
     *
     * @param string $address
     * @param string|null $name
     * @return $this
     */
    public function replyTo(string $address, string $name = null): self
    {
        $this->replyTo = [
            'address' => $address,
            'name' => $name,
        ];

        return $this;
    }
}