<?php

declare(strict_types=1);

namespace App\Resources\Message;

use MessageBird\Objects\Message;

class StrategyMessage
{
    private $strategy = null;

    public function __construct(Message $message)
    {
        switch ($message->datacoding) {
            case UnicodeMessage::MESSAGE_ENCODING:
                $this->strategy = new UnicodeMessage($message);
                break;

            case PlainMessage::MESSAGE_ENCODING:
            case PlainMessage::MESSAGE_ENCODING_AUTO:
                $this->strategy = new PlainMessage($message);
                break;
        }
    }

    public function prepareMessage()
    {
        return $this->strategy->prepareMessage();
    }
}