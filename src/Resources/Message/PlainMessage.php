<?php

declare(strict_types=1);

namespace App\Resources\Message;

use MessageBird\Objects\Message;

class PlainMessage implements MessageInterface
{
    const MAX_CHARACTERS                  = 1377;
    const MAX_LENGTH_MESSAGE_SINGLE       = 160;
    const MAX_LENGTH_MESSAGE_CONCATENATED = 153;
    const MESSAGE_ENCODING                = 'plain';
    const MESSAGE_ENCODING_AUTO           = 'auto';
    const MESSAGE_TYPE                    = 'sms';

    /** @var Message $message */
    private $message;

    public function __construct(Message $message)
    {
        $this->message = $message;
    }

    public function prepareMessage(): array
    {
        $splitBodyMessages = $this->splitMessage();

        if(count($splitBodyMessages) == 1) {
            return [$this->message];
        }

        $messageList      = [];
        $referenceNumber  = sprintf('%02X', mt_rand(0, 0xFF));
        $numberOfMessages = count($splitBodyMessages);

        foreach ($splitBodyMessages as $key => $splitBody) {
            $userDataHeader = $this->getUDH($referenceNumber, $numberOfMessages, $key + 1);
            $message = $this->getMessageBirdMessage($splitBody, ['udh' => $userDataHeader]);
            array_push($messageList, $message);
        }

        return $messageList;
    }

    private static function getUDH($referenceNumber, $numberOfMessages, $messageNumber)
    {
        return MessageInterface::UDH_HEADER_IMMUTABLE . $referenceNumber . '0' . $numberOfMessages . '0' . $messageNumber;
    }

    private function splitMessage()
    {
        if (strlen($this->message->body) <= static::MAX_LENGTH_MESSAGE_SINGLE) {
            return [$this->message->body];
        }

        return str_split($this->message->body, static::MAX_LENGTH_MESSAGE_CONCATENATED);
    }

    private function getMessageBirdMessage($body, $typeDetails = []) {
        $message = new Message();
        $message->originator  = $this->message->originator;
        $message->recipients  = $this->message->recipients;
        $message->body        = $body;
        $message->typeDetails = $typeDetails;
        $message->datacoding  = $this->message->datacoding;
        $message->type        = static::MESSAGE_TYPE;

        return $message;
    }
}
