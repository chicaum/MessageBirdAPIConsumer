<?php

declare(strict_types=1);

namespace App\Entity;

final class MessageRequest
{
    private $recipient;
    private $originator;
    private $message;
    private $isUnicode;

    /**
     * MessageRequest constructor.
     *
     * @param int $recipient
     * @param string $originator
     * @param string $message
     * @param bool $isUnicode
     */
    public function __construct(
        int $recipient,
        string $originator,
        string $message,
        bool $isUnicode = false
    )
    {
        $this->recipient  = $recipient;
        $this->originator = $originator;
        $this->message    = $message;
        $this->isUnicode  = $isUnicode;
    }

    public function getRecipient(): int
    {
        return $this->recipient;
    }

    public function getOriginator(): string
    {
        return $this->originator;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function isUnicode(): bool
    {
        return $this->isUnicode;
    }
}
