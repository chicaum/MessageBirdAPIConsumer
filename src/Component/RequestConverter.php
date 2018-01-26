<?php

declare(strict_types=1);

namespace App\Component;

use App\Entity\MessageRequest;
use Symfony\Component\HttpFoundation\Request;

class RequestConverter {

    public function convert(Request $request): MessageRequest {

        $content = $request->getContent();
        $data = json_decode($content);

        return new MessageRequest(
            $data->recipient,
            $data->originator,
            $data->message
        );
    }
}
