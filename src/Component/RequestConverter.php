<?php

declare(strict_types=1);

namespace App\Component;

use App\Entity\MessageRequest;
use App\Exception\BadRequestException;
use Symfony\Component\HttpFoundation\Request;

class RequestConverter {

    const INVALID_JSON = 'Invalid json object';

    const INVALID_RECIPIENT = 'Invalid recipient';

    const INVALID_ORIGINATOR = 'Invalid originator';

    const INVALID_MESSAGE = 'Invalid message';

    public function convert(Request $request): MessageRequest {

        $content = $request->getContent();
        $data    = json_decode($content);

        if (JSON_ERROR_NONE !== json_last_error()) {
            throw new BadRequestException(self::INVALID_JSON, 400);
        }
        if (!property_exists($data, 'recipient') || !is_int($data->recipient)) {
            throw new BadRequestException(self::INVALID_RECIPIENT, 400);
        }
        if (!property_exists($data, 'originator')) {
            throw new BadRequestException(self::INVALID_ORIGINATOR, 400);
        }
        if (!property_exists($data, 'message') || !is_string($data->message)) {
            throw new BadRequestException(self::INVALID_MESSAGE, 400);
        }

        return new MessageRequest($data->recipient, $data->originator, $data->message);
    }
}
