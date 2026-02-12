<?php

namespace Framework\Mail;

use InvalidArgumentException;
use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mailer\Mailer as SymfonyMailer;

class MailManager
{
    /**
     * The array of resolved mailers.
     *
     * @var array
     */
    protected $mailers = [];

    /**
     * Get a mailer instance by name.
     *
     * @param  string|null  $name
     * @return \Framework\Mail\Mailer
     */
    public function mailer($name = null)
    {
        $name = $name ?: $this->getDefaultDriver();

        return $this->mailers[$name] ?? $this->mailers[$name] = $this->resolve($name);
    }

    /**
     * Resolve the given mailer.
     *
     * @param  string  $name
     * @return \Framework\Mail\Mailer
     *
     * @throws \InvalidArgumentException
     */
    protected function resolve($name)
    {
        $config = $this->getConfig($name);

        if (is_null($config)) {
            throw new InvalidArgumentException("Mailer [{$name}] is not defined.");
        }

        $from = config('mail.from');

        if ($config['transport'] === 'smtp') {
            return $this->createSmtpTransport($config, $from);
        }

        throw new InvalidArgumentException("Mailer transport [{$config['transport']}] is not supported.");
    }

    /**
     * Create an instance of the SMTP Swift Transport driver.
     *
     * @param  array  $config
     * @param  array  $from
     * @return \Framework\Mail\Mailer
     */
    protected function createSmtpTransport(array $config, array $from)
    {
        $dsn = sprintf(
            'smtp://%s:%s@%s:%s',
            urlencode($config['username']),
            urlencode($config['password']),
            $config['host'],
            $config['port']
        );

        $transport = Transport::fromDsn($dsn);
        $symfonyMailer = new SymfonyMailer($transport);

        return new Mailer($symfonyMailer, $from);
    }

    /**
     * Get the mail connection configuration.
     *
     * @param  string  $name
     * @return array
     */
    protected function getConfig($name)
    {
        return config("mail.mailers.{$name}");
    }

    /**
     * Get the default mail driver name.
     *
     * @return string
     */
    public function getDefaultDriver()
    {
        return config('mail.default');
    }

    /**
     * Dynamically call the default mailer instance.
     *
     * @param  string  $method
     * @param  array  $parameters
     * @return mixed
     */
    public function __call($method, $parameters)
    {
        return $this->mailer()->$method(...$parameters);
    }
}