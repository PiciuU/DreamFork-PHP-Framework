<?php

namespace Framework\Log;

/**
 * Class Logger
 *
 * The Logger class implements the LoggerInterface and provides methods for logging messages
 * at different log levels. It also supports custom log levels.
 *
 * @package Framework\Log
 */
class Logger implements LoggerInterface {

    /**
     * Log an emergency message.
     *
     * @param string $message The log message.
     * @param array $context Additional context data.
     */
    public function emergency(string $message, array $context = [])
    {
        $this->addRecord(LogLevel::EMERGENCY, $message, $context);
    }

    /**
     * Log an alert message.
     *
     * @param string $message The log message.
     * @param array $context Additional context data.
     */
    public function alert(string $message, array $context = [])
    {
        $this->addRecord(LogLevel::ALERT, $message, $context);
    }

    /**
     * Log a critical message.
     *
     * @param string $message The log message.
     * @param array $context Additional context data.
     */
    public function critical(string $message, array $context = [])
    {
        $this->addRecord(LogLevel::CRITICAL, $message, $context);
    }

    /**
     * Log an error message.
     *
     * @param string $message The log message.
     * @param array $context Additional context data.
     */
    public function error(string $message, array $context = [])
    {
        $this->addRecord(LogLevel::ERROR, $message, $context);
    }

    /**
     * Log a warning message.
     *
     * @param string $message The log message.
     * @param array $context Additional context data.
     */
    public function warning(string $message, array $context = [])
    {
        $this->addRecord(LogLevel::WARNING, $message, $context);
    }

    /**
     * Log a notice message.
     *
     * @param string $message The log message.
     * @param array $context Additional context data.
     */
    public function notice(string $message, array $context = [])
    {
        $this->addRecord(LogLevel::NOTICE, $message, $context);
    }

    /**
     * Log an informational message.
     *
     * @param string $message The log message.
     * @param array $context Additional context data.
     */
    public function info(string $message, array $context = [])
    {
        $this->addRecord(LogLevel::INFO, $message, $context);
    }

    /**
     * Log a debug message.
     *
     * @param string $message The log message.
     * @param array $context Additional context data.
     */
    public function debug(string $message, array $context = [])
    {
        $this->addRecord(LogLevel::DEBUG, $message, $context);
    }

    /**
     * Log a message with a custom log level.
     *
     * @param string $level The custom log level.
     * @param string $message The log message.
     * @param array $context Additional context data.
     */
    public function log(string $level, string $message, array $context = [])
    {
        // Check if the provided log level is valid
        $object = new \ReflectionClass(LogLevel::class);
        $validLogLevelsArray = $object->getConstants();
        if (!in_array($level, $validLogLevelsArray)) {
            throw new InvalidLogLevelArgument($level, $validLogLevelsArray);
        }

        // Log the message
        $this->addRecord($level, $message, $context);
    }

    /**
     * Add a log record.
     *
     * @param string $level The log level.
     * @param string $message The log message.
     * @param array $context Additional context data.
     */
    private function addRecord(string $level, string $message, array $context = [])
    {
        // Get the current date and time
        $date = new \DateTime();
        $date = $date->format('Y-m-d H:i:s');

        // Create log details
        $details = sprintf(
            "%s - Level: %s - Message: %s - Context: %s", $date, $level, $message, json_encode($context)
        ).PHP_EOL;

        // Save log details to storage (W.I.P)
        print_r($details);
    }
}