<?php

declare(strict_types=1);

namespace App\Resources;

use App\Exception\BadRequestException;
use App\Resources\Message\PlainMessage;
use App\Resources\Message\UnicodeMessage;
use MessageBird\Objects\Message;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class RequestConverter
{
    const INVALID_JSON_OBJECT       = 'Invalid json object';
    const MISSING_MANDATORY_FIELD   = '\'%s\' field is required';
    const INVALID_ORIGINATOR        = 'Invalid originator';
    const INVALID_ORIGINATOR_LENGTH = 'Invalid originator - the maximum length is 11 characters';
    const INVALID_RECIPIENT         = 'Invalid recipient';
    const INVALID_MESSAGE           = 'Invalid message - Limit of characters in message was exceeded';

    private static $messageFields = [
        'recipient',
        'originator',
        'message'
    ];

    private static $validationErrors = [];

    /**
     * @param \stdClass $bodyData
     * @throws BadRequestException
     */
    private function verifyMandatoryFields(\stdClass $bodyData)
    {
        foreach (static::$messageFields as $field) {
            if (!property_exists($bodyData, $field)) {
                array_push(
                    static::$validationErrors,
                    sprintf(static::MISSING_MANDATORY_FIELD, $field)
                );
            }
        }

        if (count(static::$validationErrors) > 0) {
            throw new BadRequestException(
                implode(", ", static::$validationErrors),
                Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * @param string $originator
     * @throws BadRequestException
     */
    private function verifyOriginator(string $originator)
    {
        if ((is_numeric($originator) && intval($originator) < 0) || !ctype_alnum($originator)) {
            throw new BadRequestException(static::INVALID_ORIGINATOR);
        }

        if (strlen($originator) > 11) {
            throw new BadRequestException(static::INVALID_ORIGINATOR_LENGTH);
        }
    }

    /**
     * @param int $recipient
     * @throws BadRequestException
     */
    private function verifyRecipient(int $recipient)
    {
        if (!preg_match('/^[0-9]{1,15}$/', (string)$recipient)) {
            throw new BadRequestException(static::INVALID_RECIPIENT);
        }
    }

    /**
     * @param string $message
     * @param bool $isUnicode
     * @throws BadRequestException
     */
    private function verifyMessage(string $message, bool $isUnicode)
    {
        if ($isUnicode) {
            $messageLength = mb_strlen($message, "UTF-8");
            $smsMaxLength  = UnicodeMessage::MAX_CHARACTERS;
        } else {
            $messageLength = strlen($message);
            $smsMaxLength  = PlainMessage::MAX_CHARACTERS;
        }

        if ($messageLength > $smsMaxLength) {
            throw new BadRequestException(static::INVALID_MESSAGE);
        }
    }

    private function isMessageUnicode($message): bool {
        return strlen($message) != strlen(utf8_decode($message));
    }

    /**
     * @param Request $request
     * @return Message
     * @throws BadRequestException
     */
    public function convert(Request $request): Message
    {
        $content = $request->getContent();
        $bodyData = json_decode($content);

        if (JSON_ERROR_NONE !== json_last_error()) {
            throw new BadRequestException(static::INVALID_JSON_OBJECT, Response::HTTP_BAD_REQUEST);
        }

        $isUnicode = $this->isMessageUnicode($bodyData->message);

        $this->verifyMandatoryFields($bodyData);
        $this->verifyRecipient($bodyData->recipient);
        $this->verifyOriginator($bodyData->originator);
        $this->verifyMessage($bodyData->message, $isUnicode);

        $message             = new Message();
        $message->originator = $bodyData->originator;
        $message->recipients = [$bodyData->recipient];
        $message->body       = $bodyData->message;
        $message->datacoding = $isUnicode ? UnicodeMessage::MESSAGE_ENCODING : PlainMessage::MESSAGE_ENCODING_AUTO;

        return $message;
    }
}
