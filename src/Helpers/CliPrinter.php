<?php

namespace App\Helpers;

class CliPrinter
{
    /**
     * @param string $message
     */
    public function out(string $message)
    {
        echo $message;
    }

    public function newline()
    {
        $this->out("\n");
    }

    /**
     * @param string $message
     */
    public function display(string $message)
    {
        $this->newline();
        $this->out($message);
        $this->newline();
        $this->newline();
    }

    /**
     * @param array $messages
     */
    public function printArray(array $messages)
    {
        foreach ($messages as $message) {
            $this->newline();
            $this->out($message);
            $this->newline();
        }
        $this->newline();
    }
}