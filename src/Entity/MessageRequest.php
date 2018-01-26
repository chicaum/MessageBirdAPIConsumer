<?php

declare(strict_types=1);

namespace App\Entity;

final class MessageRequest {
    private $recipient;
    private $originator;
    private $message;

    /**
     * MessageRequest constructor.
     *
     * @param $recipient
     * @param $originator
     * @param $message
     */
    public function __construct(
        int $recipient,
        string $originator,
        string $message
    ) {
        $this->recipient  = $recipient;
        $this->originator = $originator;
        $this->message    = $message;
    }

    public function getRecipient(): int {
        return $this->recipient;
    }

    public function getOriginator(): string {
        return $this->originator;
    }

    public function getMessage(): string {
        return $this->message;
    }
}
